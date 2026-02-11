<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class CheckLoggerActivation
{
    public function handle(Request $request, Closure $next)
    {
        $path = storage_path('app/expired.txt');

        // 1. Inisialisasi Data Lokal (Hanya Kode dan ID)
        $localCode = null;
        $loggerId = session('setup_logger_id');

        if (File::exists($path)) {
            $content = File::get($path);
            $parts = explode("|", $content);

            // Kita gunakan format baru: KODE|ID
            if (count($parts) >= 2) {
                $localCode = $parts[0];
                $loggerId = $parts[1];
            }
        }

        // Jika ID benar-benar tidak ditemukan (Belum Setup), paksa ke Setup
        if (!$loggerId) {
            return redirect()->route('setup.index');
        }

        // 2. LOGIKA KICK-OUT (SINKRONISASI REAL-TIME KE PUSAT)
        try {
            // Kita lakukan "Ping" ke Python dengan timeout singkat (2 detik)
            $response = Http::withHeaders([
                'X-API-KEY' => env('PYTHON_API_KEY')
            ])->timeout(2)->get(env('PYTHON_API_URL') . "/loggers");

            if ($response->successful()) {
                $serverData = collect($response->json())->firstWhere('logger_id', $loggerId);

                // SKENARIO A: Jika ID dihapus di Dashboard Admin Pusat
                if (!$serverData) {
                    if (File::exists($path)) File::delete($path);
                    session()->forget('setup_logger_id');
                    return redirect()->route('setup.index')->with('error', 'Logger ID Anda telah dinonaktifkan oleh Admin Pusat.');
                }

                // SKENARIO B: Jika Admin klik "Generate" baru (Kode di server berubah)
                if ($localCode && $serverData['activation_code'] !== "None") {
                    if ($localCode !== $serverData['activation_code']) {
                        if (File::exists($path)) File::delete($path);
                        return redirect()->route('verify.index')->with('error', 'Admin telah mereset kode akses. Silahkan verifikasi ulang.');
                    }
                }
            } else {
                throw new \Exception("API Error");
            }

        } catch (\Exception $e) {
            // --- LOGIKA AUTO-KICK JIKA TOMBOL OFF ---
            // Jika Python mati (Port 8000 OFF), hapus izin lokal demi keamanan
            if (File::exists($path)) {
                File::delete($path);
            }
            return redirect()->route('verify.index')->with('error', 'SISTEM PUSAT OFFLINE: Akses ditangguhkan sementara.');
        }

        // 3. CEK KETERSEDIAAN FILE LOKAL
        // Jika file tidak ada, berarti user harus verifikasi dulu
        if (!File::exists($path)) {
            return redirect()->route('verify.index');
        }

        // --- LOGIKA 60 MENIT SUDAH DIHAPUS DI SINI ---
        // Sesi sekarang bersifat unlimited selama Engine Pusat tetap ON dan Kode tidak diganti.

        return $next($request);
    }
}