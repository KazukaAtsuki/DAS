<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LoggerAuthController extends Controller
{
    /**
     * Memproses verifikasi kode 6 digit dari user DAS
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verif_code' => 'required|logger_active'
        ], [
            'verif_code.required'      => 'Wajib memasukkan kode!',
            'verif_code.logger_active' => 'Otorisasi Gagal!'
        ]);

        // UBAH BAGIAN INI: Hapus atau ganti teksnya agar tidak menyebut 60 menit
        return redirect()->route('dashboard')->with('swal_success', 'Sistem Berhasil Terotorisasi.');
    }

    /**
     * Mendaftarkan ID Logger baru ke Python API Pusat
     */
    public function processSetup(Request $request)
    {
        $request->validate([
            'logger_id'   => 'required|string|max:50',
            'logger_name' => 'required|string|max:100',
            'user_email'  => 'required|email',
        ]);

        try {
            // Ambil URL dari .env
            $apiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:8000/api');

            // Kirim request pendaftaran ke Python
            $response = Http::withHeaders([
                'X-API-KEY' => env('PYTHON_API_KEY')
            ])->timeout(5)->post($apiUrl . "/loggers", [
                'logger_id'   => $request->logger_id,
                'logger_name' => $request->logger_name,
                'user_email'  => $request->user_email,
            ]);

            if ($response->successful()) {
                // Simpan ID ke Session agar bisa ditampilkan di halaman verifikasi
                session(['setup_logger_id' => $request->logger_id]);

                // Gunakan swal_success untuk notifikasi awal yang cantik
                return redirect()->route('verify.index')->with('swal_success', 'ID Logger berhasil didaftarkan. Menunggu kode verifikasi.');
            }

            // Jika Python menolak (misal ID sudah ada)
            $errorMessage = $response->json()['detail'] ?? 'Pusat menolak permintaan aktivasi.';
            return back()->with('error', 'Gagal: ' . $errorMessage)->withInput();

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Jika Python mati
            return back()->with('error', 'Koneksi Gagal: Pastikan Jembatan Python API (Port 8000) sudah aktif!')->withInput();
        } catch (\Exception $e) {
            // Error lainnya
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }
}