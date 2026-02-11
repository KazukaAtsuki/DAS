import os
import time
import psycopg2
import psycopg2.extras
from datetime import datetime
from pymodbus.client.sync import ModbusTcpClient
from dotenv import load_dotenv

# --- 1. CONFIGURATION ---
BASE_DIR = "C:/laravel-pkl/ProjectTrudasPKL/ProjectTrudasPKL"
load_dotenv(os.path.join(BASE_DIR, ".env"))

def connect_db():
    try:
        return psycopg2.connect(
            host=os.getenv("DB_HOST"),
            user=os.getenv("DB_USERNAME"),
            password=os.getenv("DB_PASSWORD"),
            database=os.getenv("DB_DATABASE"),
            port=os.getenv("DB_PORT", 5432)
        )
    except Exception as e:
        print(f"❌ Koneksi DB Gagal: {e}")
        return None

def get_modbus_value(ip_address, port, address):
    try:
        if not ip_address or ip_address == "None": return 0
        client = ModbusTcpClient(ip_address, port=int(port), timeout=2)
        if client.connect():
            request = client.read_holding_registers(int(address), count=1)
            raw = request.registers[0] if request.registers else 0
            client.close()
            return raw
        return 0
    except:
        return 0

def main():
    print(f"[{datetime.now()}] >>> DAS DATA ACQUISITION ENGINE START <<<")

    while True:
        conn = connect_db()
        if conn:
            try:
                cur = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)

                # Query ambil data sensor aktif
                cur.execute("""
                    SELECT id, parameter_name, analyzer_ip, port, formula, stack_config_id
                    FROM sensor_configs
                    WHERE status = 'Active'
                """)
                sensors = cur.fetchall()

                for s in sensors:
                    # AMANKAN PROSES SPLIT PORT
                    raw_port_value = str(s['port'])
                    if '|' in raw_port_value:
                        p_list = raw_port_value.split('|')
                        port = p_list[0]
                        addr = p_list[1]
                    else:
                        port = raw_port_value
                        addr = 0 # Default address jika tidak ada pemisah |

                    # 1. Baca Hardware
                    raw_digital = get_modbus_value(s['analyzer_ip'], port, addr)

                    # 2. Konversi ke mA
                    mA = round(2.44144E-4 * raw_digital + 4, 2)

                    # 3. Hitung Nilai Fisik
                    try:
                        measured = eval(s['formula']) if s['formula'] else mA
                    except:
                        measured = -1

                    # 4. Simpan ke Database (sensor_value_logs & das_logs)
                    cur.execute("""
                        INSERT INTO sensor_value_logs (sensor_id, measured, raw, updated_at)
                        VALUES (%s, %s, %s, NOW())
                        ON CONFLICT (sensor_id) DO UPDATE
                        SET measured = EXCLUDED.measured, raw = EXCLUDED.raw, updated_at = NOW()
                    """, (s['id'], measured, mA))

                    cur.execute("""
                        INSERT INTO das_logs (stack_config_id, sensor_config_id, measured_value, raw_value, timestamp)
                        VALUES (%s, %s, %s, %s, NOW())
                    """, (s['stack_config_id'], s['id'], measured, mA))

                    print(f"✅ RECORDED: {s['parameter_name']} | Raw: {raw_digital} | Val: {measured}")

                conn.commit()
                cur.close()
                conn.close()

            except Exception as e:
                print(f"❌ Engine Error: {e}")

        time.sleep(3)

if __name__ == "__main__":
    main()