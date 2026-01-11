<?php

namespace App\Http\Controllers; // Sesuaikan jika tidak pakai folder Api

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LoggerAuthController extends Controller
{
    public function verify(Request $request)
    {
        // 1. Validasi menggunakan rule 'logger_active' yang kita buat di AppServiceProvider
        $request->validate([
            'verif_code' => 'required|logger_active'
        ], [
            'verif_code.required' => 'Wajib memasukkan kode!',
            'verif_code.logger_active' => 'Otorisasi Gagal: Kode salah atau sudah expired.'
        ]);

        // 2. Jika lolos, artinya AppServiceProvider sudah membuat file expired.txt
        // Kita arahkan ke dashboard
        return redirect()->route('dashboard')->with('success', 'Akses dibuka! Anda punya waktu 60 menit.');
    }

    public function processSetup(Request $request)
{
    $request->validate([
        'logger_id' => 'required',
        'logger_name' => 'required',
        'user_email' => 'required|email',
    ]);

    try {
        // Ambil URL dari .env dan pastikan port 8000
        $apiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:8000/api');

        // Kita set timeout 5 detik saja agar tidak menunggu kelamaan (30 detik)
        $response = Http::withHeaders([
            'X-API-KEY' => env('PYTHON_API_KEY')
        ])->timeout(5)->post($apiUrl . "/loggers", [
            'logger_id'   => $request->logger_id,
            'logger_name' => $request->logger_name,
            'user_email'  => $request->user_email,
        ]);

        if ($response->successful()) {
            session(['setup_logger_id' => $request->logger_id]);
            return redirect()->route('verify.index')->with('success', 'Pendaftaran Berhasil!');
        }

        return back()->with('error', 'Pusat menolak permintaan: ' . ($response->json()['detail'] ?? 'Error Unknown'))->withInput();

    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        // JIKA PYTHON MATI / TIMEOUT
        return back()->with('error', 'Koneksi Terputus: Jembatan Python API (Port 8000) tidak merespon/mati.')->withInput();
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
    }
}
}