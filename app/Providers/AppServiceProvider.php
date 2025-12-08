<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    // Setiap kali file 'partials.header' dimuat, kirim data notifikasi
    View::composer('partials.header', function ($view) {
        $notifications = ActivityLog::with('user')
                            ->latest()
                            ->take(6) // Ambil 6 terakhir
                            ->get();

        $view->with('notifications', $notifications);
    });
}
}
