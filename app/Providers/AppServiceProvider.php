<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File; // Pastikan ini ada
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // --- 1. LOGIKA GLOBAL (TANPA BATAS WAKTU) ---
        $path = storage_path('app/expired.txt');
        $isActivated = File::exists($path);
        $loggerId = env('LOGGER_ID', 'LOG-001');

        if ($isActivated) {
            // PERBAIKAN: Gunakan ::get bukan .get
            $content = File::get($path);
            $parts = explode("|", $content);

            // Format: KODE | ID (Sesuai permintaan senior tanpa waktu)
            $loggerId = $parts[1] ?? $loggerId;
        }

        // Share data agar header dan master tidak error
        View::share('activeLoggerId', $loggerId);
        View::share('isSystemActivated', $isActivated);
        View::share('sessionExpiry', false); // Timer dimatikan
        View::share('remainingSeconds', 0);  // Detik di-nol-kan

        // --- 2. LOGIKA NOTIFIKASI HEADER ---
        View::composer('partials.header', function ($view) {
            $notifData = collect([]);
            if (Schema::hasTable('activity_logs')) {
                $notifData = ActivityLog::with('user')->latest()->take(6)->get();
            }
            $view->with('notifications', $notifData);
        });

        // --- 3. CUSTOM VALIDATOR: logger_active ---
        Validator::extend('logger_active', function ($attribute, $value, $parameters, $validator) {
            try {
                $currentId = session('setup_logger_id');
                if (!$currentId) return false;

                // Verifikasi ke Python
                $response = Http::withHeaders([
                    'x-api-key' => env('PYTHON_API_KEY'),
                    'Accept' => 'application/json'
                ])->post(env('PYTHON_API_URL') . "/verify-code", [
                    'logger_id'  => $currentId,
                    'input_code' => $value
                ]);

                $result = $response->json();

                if (isset($result['valid']) && $result['valid'] === true) {
                    // SIMPAN TANPA WAKTU: KODE | ID
                    File::put(
                        storage_path('app/expired.txt'),
                        $value . "|" . $currentId
                    );
                    return true;
                }
                return false;
            } catch (\Exception $e) {
                return false;
            }
        });
    }
}