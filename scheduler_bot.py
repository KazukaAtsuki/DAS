import subprocess
import time
import os
import sys
from datetime import datetime

# --- KONFIGURASI ---
# Pastikan ini menunjuk ke folder project kamu
PROJECT_PATH = os.getcwd()
INTERVAL = 1

def run_seeder():
    now = datetime.now().strftime("%H:%M:%S")
    print(f"[{now}] 📡 Sedang menyuntikkan data...", end="\r")

    try:
        # Jalankan perintah PHP dan TANGKAP outputnya
        result = subprocess.run(
            ["php", "artisan", "db:seed", "--class=DasLogSeeder"],
            capture_output=True,
            text=True,
            shell=True
        )

        # Cek apakah PHP berhasil (Return Code 0 = Sukses)
        if result.returncode == 0:
            print(f"[{now}] ✅ SUKSES: Data masuk ke Database!      ")
        else:
            # Kalau Gagal, Tampilkan Pesan Error Asli dari PHP
            print(f"\n[{now}] ❌ ERROR PHP:\n{result.stderr}")

    except Exception as e:
        print(f"\n[{now}] ❌ ERROR PYTHON: {e}")

if __name__ == "__main__":
    os.system('cls' if os.name == 'nt' else 'clear')
    print("==============================================")
    print("   🔍 DEBUG MODE: BOT SENSOR                  ")
    print("   Lokasi: " + PROJECT_PATH)
    print("==============================================\n")

    try:
        while True:
            run_seeder()
            time.sleep(INTERVAL)
    except KeyboardInterrupt:
        print("\n🛑 Berhenti.")