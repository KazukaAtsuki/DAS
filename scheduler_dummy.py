import psycopg2
import psycopg2.extras
import time
import random
import os
from datetime import datetime

# --- KONFIGURASI DATABASE ---
DB_HOST = "127.0.0.1"
DB_NAME = "project_trudas"
DB_USER = "postgres"
DB_PASS = "postgres" # Pastikan password benar
DB_PORT = "5432"

# Interval 1 Detik
INTERVAL = 1

def connect_db():
    try:
        conn = psycopg2.connect(host=DB_HOST, database=DB_NAME, user=DB_USER, password=DB_PASS, port=DB_PORT)
        return conn
    except Exception as e:
        print(f"❌ Connection Error: {e}")
        return None

def get_sensors():
    try:
        conn = connect_db()
        if conn is None: return []
        cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
        sql = "SELECT id, stack_config_id FROM sensor_configs WHERE status = 'Active'"
        cursor.execute(sql)
        result = cursor.fetchall()
        conn.close()
        return result
    except:
        return []

def get_config():
    try:
        conn = connect_db()
        if conn is None: return None
        cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
        cursor.execute("SELECT is_rca_mode FROM global_configs LIMIT 1")
        result = cursor.fetchone()
        conn.close()
        return result
    except:
        return None

def insert_log(sensor_id, stack_id, measured, raw, table_name):
    try:
        conn = connect_db()
        if conn is None: return
        cursor = conn.cursor()
        now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

        if table_name == 'das_logs':
            sql = "INSERT INTO das_logs (sensor_config_id, stack_config_id, measured_value, raw_value, status_sent_dis, created_at, updated_at, timestamp) VALUES (%s, %s, %s, %s, 'Pending', %s, %s, %s)"
            cursor.execute(sql, (sensor_id, stack_id, measured, raw, now, now, now))
        elif table_name == 'rca_logs':
            corrected = measured * 1.05
            sql = "INSERT INTO rca_logs (sensor_config_id, stack_config_id, measured_value, raw_value, corrected_o2, created_at, updated_at, timestamp) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"
            cursor.execute(sql, (sensor_id, stack_id, measured, raw, corrected, now, now, now))

        conn.commit()
        conn.close()
        # Print update di baris yang sama biar rapi
        print(f"[{now}] ✅ {table_name.upper()} | Sensor: {sensor_id} | Val: {measured:.2f}     ", end="\r")
    except Exception as e:
        print(f"\n❌ Error: {e}")

def main():
    os.system('cls' if os.name == 'nt' else 'clear')
    print("==========================================")
    print("   🚀 BOT SENSOR STARTED (LOOPING)        ")
    print("   Press CTRL + C to stop                 ")
    print("==========================================")

    # --- LOOPING SELAMANYA ---
    while True:
        try:
            config = get_config()
            is_rca = config['is_rca_mode'] if config else False
            target_table = 'rca_logs' if is_rca else 'das_logs'
            sensors = get_sensors()

            for sensor in sensors:
                raw_val = random.uniform(4, 20)
                measured_val = raw_val * random.uniform(2, 5)
                insert_log(sensor['id'], sensor['stack_config_id'], round(measured_val, 2), round(raw_val, 2), target_table)

            # Tidur 1 Detik sebelum ulang lagi
            time.sleep(INTERVAL)

        except KeyboardInterrupt:
            print("\n🛑 Stopped by User.")
            break
        except Exception as e:
            print(f"\n❌ Error Loop: {e}")
            time.sleep(1)

if __name__ == "__main__":
    main()