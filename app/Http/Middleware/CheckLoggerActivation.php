<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CheckLoggerActivation
{
    public function handle(Request $request, Closure $next)
{
    $path = storage_path('app/expired.txt');
    $loggerId = session('setup_logger_id');

    if (!$loggerId) return redirect()->route('setup.index');

    // 1. Ambil data dari Python Pusat
    try {
        $response = Http::withHeaders([
            'X-API-KEY' => env('PYTHON_API_KEY')
        ])->timeout(2)->get(env('PYTHON_API_URL') . "/loggers");

        if ($response->successful()) {
            $loggers = $response->json();
            // Cari data logger kita di server
            $serverData = collect($loggers)->firstWhere('logger_id', $loggerId);

            if (!$serverData) {
                session()->forget('setup_logger_id');
                if (File::exists($path)) File::delete($path);
                return redirect()->route('setup.index')->with('error', 'Logger dihapus.');
            }

            // --- LOGIKA UTAMA: CEK APAKAH ADMIN GENERATE BARU? ---
            if (File::exists($path)) {
                $content = File::get($path);
                $parts = explode("|", $content);
                $localCode = $parts[0]; // Kode yang disimpan di DAS

                // Jika kode di DAS beda dengan kode di Python Server
                // Berarti Admin baru saja klik "Generate"
                if ($localCode !== $serverData['activation_code']) {
                    File::delete($path); // Paksa hapus izin
                    return redirect()->route('verify.index')->with('error', 'Admin telah mereset kode. Silahkan verifikasi ulang.');
                }
            }
        }
    } catch (\Exception $e) {
        // Jika offline/koneksi putus, DAS tetap bisa jalan pakai file lokal (fallback)
    }

    // 2. Cek Expiry File Lokal (seperti biasa)
    if (!File::exists($path)) {
        return redirect()->route('verify.index');
    }

    $content = File::get($path);
    $parts = explode("|", $content);
    $expiryTime = Carbon::parse($parts[1]);

    if (now()->greaterThan($expiryTime)) {
        File::delete($path);
        return redirect()->route('verify.index')->with('error', 'Sesi Habis.');
    }

    return $next($request);
}
}