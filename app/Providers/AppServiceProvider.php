<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
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
        // 1. Gunakan Pagination Bootstrap 5
        Paginator::useBootstrapFive();

        // 2. Logic Notifikasi Header (Ditaruh di sini)
        // Ini memastikan variabel $notifications selalu terkirim ke file header.blade.php
        View::composer('partials.header', function ($view) {
            if (Schema::hasTable('activity_logs')) {
                $notifications = ActivityLog::with('user')->latest()->take(6)->get();
                $view->with('notifications', $notifications);
            } else {
                // Mengirim collection kosong agar tidak error 'null' di PHP 8.4
                $view->with('notifications', collect([]));
            }
        });

        // 3. Share Logger ID ke semua View (Agar "Enak dilihat" sesuai saran senior)
        View::share('activeLoggerId', env('LOGGER_ID', 'LOG-001'));

        // 4. CUSTOM VALIDATOR: logger_active
        Validator::extend('logger_active', function ($attribute, $value, $parameters, $validator) {
            // AMBIL ID DARI SESSION YANG DISIMPAN SAAT SETUP TADI
            $loggerId = session('setup_logger_id');

            if (!$loggerId) return false;

            try {
                $response = Http::withHeaders([
                    'x-api-key' => env('PYTHON_API_KEY'),
                    'Accept' => 'application/json'
                ])->post(env('PYTHON_API_URL') . "/verify-code", [
                    'logger_id'  => $loggerId, // Gunakan ID dari session
                    'input_code' => $value
                ]);

                $result = $response->json();

                // app/Providers/AppServiceProvider.php di bagian Validator::extend

    if (isset($result['valid']) && $result['valid'] === true) {
    $expiry = now()->addMinutes(60);
    // SIMPAN KODE DAN WAKTU EXPIRED (Format: KODE|WAKTU)
    File::put(
        storage_path('app/expired.txt'),
        $value . "|" . $expiry
    );
    return true;
    }
            } catch (\Exception $e) {
                return false;
            }
        });
    }
}