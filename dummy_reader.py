import os
import time
import requests
import psycopg2
import psycopg2.extras
from datetime import datetime
from dotenv import load_dotenv
import random

# --- 1. CONFIGURATION ---
# Pastikan path ini sesuai dengan folder DAS kamu
BASE_DIR = "C:/laravel-pkl/ProjectTrudasPKL/ProjectTrudasPKL"
load_dotenv(os.path.join(BASE_DIR, ".env"))

EXPIRED_FILE_PATH = os.path.join(BASE_DIR, "storage/app/expired.txt")

# --- 2. SECURITY GATEKEEPER (UNLIMITED SESSION) ---
def is_authorized():
    """
    Mengecek izin akses:
    1. Cek keberadaan file gembok (expired.txt)
    2. Cek apakah kode masih sinkron dengan server (Logika Kick-Out)
    """
    try:
        # A. Cek File Lokal
        if not os.path.exists(EXPIRED_FILE_PATH):
            return False, "Gembok lokal (expired.txt) tidak ditemukan."

        with open(EXPIRED_FILE_PATH, 'r') as f:
            content = f.read().strip()
            parts = content.split('|')

            # Validasi format baru: KODE | ID (Hanya 2 bagian)
            if len(parts) < 2:
                return False, "Format file tidak sesuai. Silahkan verifikasi ulang di DAS."

            local_code = parts[0]
            logger_id  = parts[1]

        # B. SINKRONISASI PUSAT (KICK-OUT LOGIC)
        # Sesi ini unlimited, tapi jika Admin klik 'Generate', akses otomatis mati.
        try:
            api_url = os.getenv("PYTHON_API_URL", "http://127.0.0.1:8000/api")
            api_key = os.getenv("PYTHON_API_KEY", "TRUSUR_SECRET_KEY_2024")

            response = requests.get(f"{api_url}/loggers", headers={"X-API-KEY": api_key}, timeout=2)

            if response.status_code == 200:
                loggers = response.json()
                # Cari data logger kita di server
                server_data = next((l for l in loggers if l["logger_id"] == logger_id), None)

                if not server_data:
                    return False, f"ID {logger_id} dihapus dari pusat."

                # Jika kode lokal berbeda dengan kode di server (Admin Generate Baru)
                if server_data['activation_code'] != local_code:
                    return False, "Admin mereset kode. Otorisasi lama hangus."
        except:
            # Jika koneksi ke server pusat mati, gunakan mode offline (percaya file lokal)
            pass

        return True, f"Authorized ({logger_id})"

    except Exception as e:
        return False, f"Security Error: {str(e)}"

# --- 3. DATABASE CONNECTION ---
def connect_db():
    try:
        return psycopg2.connect(
            host=os.getenv("DB_HOST", "127.0.0.1"),
            user=os.getenv("DB_USERNAME", "postgres"),
            password=os.getenv("DB_PASSWORD", "postgres"),
            database=os.getenv("DB_DATABASE", "project_trudas"),
            port=os.getenv("DB_PORT", 5432)
        )
    except Exception as e:
        print(f"❌ DB Connection Error: {e}")
        return None

# --- 4. MAIN ENGINE ---
def main():
    print(f"[{datetime.now()}] >>> DUMMY READER ENGINE: ACTIVE (UNLIMITED) <<<")

    while True:
        # STEP 1: VALIDASI KEAMANAN
        authorized, msg = is_authorized()

        if not authorized:
            print(f"🛑 SIMULASI BERHENTI: {msg}")
            time.sleep(5)
            continue

        # STEP 2: PROSES LOGGING
        conn = connect_db()
        if conn:
            try:
                cur = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
                # Ambil sensor aktif
                cur.execute("SELECT id, parameter_name, formula, stack_config_id FROM sensor_configs WHERE status = 'Active'")
                sensors = cur.fetchall()

                for s in sensors:
                    # Simulasi 4-20mA
                    mA = round(random.uniform(4.0, 20.0), 2)
                    try:
                        measured = round(eval(s['formula']), 2) if s['formula'] else mA
                    except:
                        measured = mA

                    # A. Update Tabel Live Dashboard
                    cur.execute("""
                        INSERT INTO sensor_value_logs (sensor_id, measured, raw, updated_at)
                        VALUES (%s, %s, %s, NOW())
                        ON CONFLICT (sensor_id) DO UPDATE
                        SET measured = EXCLUDED.measured, raw = EXCLUDED.raw, updated_at = NOW()
                    """, (s['id'], measured, mA))

                    # B. Insert Tabel History Logs (Disesuaikan dengan tabel das_logs kamu)
                    cur.execute("""
                        INSERT INTO das_logs (sensor_config_id, stack_config_id, measured_value, raw_value, timestamp)
                        VALUES (%s, %s, %s, %s, NOW())
                    """, (s['id'], s['stack_config_id'], measured, mA))

                    print(f"📡 {msg} -> {s['parameter_name']}: {measured}")

                conn.commit()
                cur.close(); conn.close()
            except Exception as e:
                print(f"❌ Database Error: {e}")

        time.sleep(3)

if __name__ == "__main__":
    main()