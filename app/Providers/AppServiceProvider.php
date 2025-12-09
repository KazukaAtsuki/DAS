<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <--- 1. PENTING: Import Paginator

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
        // 2. Gunakan gaya Pagination Bootstrap 5
        // Ini wajib agar tombol (<< 1 2 3 >>) di bawah Activity Logs terlihat rapi
        Paginator::useBootstrapFive();

        // 3. Logic Notifikasi Header (Existing)
        // Setiap kali file 'partials.header' dimuat, kirim data notifikasi
        View::composer('partials.header', function ($view) {
            // Cek apakah tabel activity_logs sudah ada untuk mencegah error saat migrate fresh
            if (\Schema::hasTable('activity_logs')) {
                $notifications = ActivityLog::with('user')
                                    ->latest()
                                    ->take(6) // Ambil 6 terakhir
                                    ->get();

                $view->with('notifications', $notifications);
            } else {
                $view->with('notifications', []);
            }
        });
    }
}