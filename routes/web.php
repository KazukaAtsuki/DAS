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
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\HourlyLogController;



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

        // Route Toggle RCA (BARU)
        Route::post('/dashboard/toggle-rca', [DashboardController::class, 'toggleRca'])
        ->name('dashboard.toggle-rca')
        ->middleware('auth');

    });

    // Route Hourly Logs
    Route::get('/hourly-avg', [HourlyLogController::class, 'index'])->name('hourly.index');

    // Route Export Baru
    Route::get('/hourly-avg/export-excel', [HourlyLogController::class, 'exportExcel'])->name('hourly.export.excel');
    Route::get('/hourly-avg/export-simpel', [HourlyLogController::class, 'exportSimpel'])->name('hourly.export.simpel');

     // Route Export Logs
     Route::get('/logs-data/export', [DasLogController::class, 'exportExcel'])->name('logs.export');

      // Route Export RCA
    Route::get('/rca-records/export', [RcaLogController::class, 'exportExcel'])->name('rca.export');

    // Route Profil
    Route::get('/my-profile', [AuthController::class, 'profile'])->name('my-profile');
    Route::put('/my-profile', [AuthController::class, 'updateProfile'])->name('my-profile.update');

    // Route Security
    Route::get('/security', [AuthController::class, 'security'])->name('security');
    Route::put('/security', [AuthController::class, 'updatePassword'])->name('security.update');

    // Route Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

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