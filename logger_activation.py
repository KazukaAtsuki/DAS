import requests
import json

# --- PASTIKAN DATA INI SAMA PERSIS DENGAN DI DASHBOARD ---
# Cek huruf besar/kecilnya juga!
LOGGER_ID = "LOG-001"       
ACTIVATION_CODE = "ABC12345"

URL = "http://127.0.0.1:8001/api/logger/activate"

def activate_device():
    print(f"📡 Menghubungi Server: {URL}")
    print(f"   -> ID: {LOGGER_ID}")
    print(f"   -> Code: {ACTIVATION_CODE}")

    payload = {
        'logger_id': LOGGER_ID,
        'activation_code': ACTIVATION_CODE
    }

    try:
        response = requests.post(URL, json=payload)
        
        # --- DEBUGGING: Tampilkan Status Code ---
        print(f"\nStatus Code: {response.status_code}")
        
        # Jika bukan 200 OK, cetak isi errornya (HTML/Text)
        if response.status_code != 200:
            print("❌ ERROR DARI SERVER:")
            print(response.text) # <--- INI AKAN MENAMPILKAN PENYEBABNYA
            return

        # Kalau aman, baru parsing JSON
        data = response.json()
        print("\n✅ AKTIVASI BERHASIL!")
        print(f"Token: {data['data']['token']}")

    except Exception as e:
        print(f"\n❌ Error Script: {e}")

if __name__ == "__main__":
    activate_device()