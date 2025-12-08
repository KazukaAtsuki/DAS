<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StackConfigController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\GlobalConfigController;
use App\Http\Controllers\SensorConfigController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\DasLogController;
use App\Http\Controllers\RcaLogController;
use App\Http\Controllers\HourlyAverageController;
use App\Http\Controllers\DashboardController;


/*
|--------------------------------------------------------------------------
| 1. Route GUEST (Hanya bisa diakses kalau BELUM Login)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 2. Route AUTH (Harus LOGIN dulu baru bisa akses)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::middleware(['auth'])->group(function () {

        // Halaman Dashboard Utama
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

      // PENTING: Route untuk AJAX Live Update (Tiap 2 Detik)
Route::get('/dashboard/live', [DashboardController::class, 'getLiveDashboard'])->name('dashboard.live');


    });

    // Sensor Config
    Route::resource('sensor-config', SensorConfigController::class);

    // References
    Route::resource('references', ReferenceController::class);

    // RCA Records
    Route::get('/rca-records', [RcaLogController::class, 'index'])->name('rca.index');

    // DAS Logs
    Route::get('/logs-data', [DasLogController::class, 'index'])->name('logs.index');

    // Hourly Averages
    Route::get('/hourly-avg', [HourlyAverageController::class, 'index'])->name('hourly.index');

    // Master Data
    Route::resource('users', UserController::class);
    Route::resource('stack-config', StackConfigController::class);
    Route::resource('units', UnitController::class);

    // System Config
    Route::prefix('system')->group(function () {
        Route::get('/global-config', [GlobalConfigController::class, 'index'])->name('global-config.index');
        Route::put('/global-config/update', [GlobalConfigController::class, 'update'])->name('global-config.update');
    });

});