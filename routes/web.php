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
| 1. Route GUEST (Bisa diakses publik / belum login)
|--------------------------------------------------------------------------
*/

// --- MODIFIKASI: Route Halaman Depan (Landing Page) ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 2. Route AUTH (Harus LOGIN dulu baru bisa akses)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- MODIFIKASI DASHBOARD ---
    // Sekarang Dashboard ada di '/dashboard', bukan '/'
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route AJAX Dashboard
    Route::get('/dashboard/live', [DashboardController::class, 'getLiveDashboard'])->name('dashboard.live');
    Route::post('/dashboard/toggle-rca', [DashboardController::class, 'toggleRca'])->name('dashboard.toggle-rca');


    // --- ROUTE FITUR LAINNYA (TIDAK DIUBAH) ---

    // Route Hourly Logs & Export
    Route::get('/hourly-avg', [HourlyLogController::class, 'index'])->name('hourly.index');
    Route::get('/hourly-avg/export-excel', [HourlyLogController::class, 'exportExcel'])->name('hourly.export.excel');
    Route::get('/hourly-avg/export-simpel', [HourlyLogController::class, 'exportSimpel'])->name('hourly.export.simpel');

    // Route Export Logs
    Route::get('/logs-data/export', [DasLogController::class, 'exportExcel'])->name('logs.export');

    // Route Export RCA
    Route::get('/rca-records/export', [RcaLogController::class, 'exportExcel'])->name('rca.export');

    // Route Profil & Security
    Route::get('/my-profile', [AuthController::class, 'profile'])->name('my-profile');
    Route::put('/my-profile', [AuthController::class, 'updateProfile'])->name('my-profile.update');
    Route::get('/security', [AuthController::class, 'security'])->name('security');
    Route::put('/security', [AuthController::class, 'updatePassword'])->name('security.update');

    // Route Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Resources
    Route::resource('sensor-config', SensorConfigController::class);
    Route::resource('references', ReferenceController::class);

    // Pages Index
    Route::get('/rca-records', [RcaLogController::class, 'index'])->name('rca.index');
    Route::get('/logs-data', [DasLogController::class, 'index'])->name('logs.index');

    // Note: Anda punya 2 route bernama 'hourly.index' (HourlyLogController & HourlyAverageController).
    // Pastikan pakai salah satu saja. Di sini saya biarkan sesuai code Anda.
    // Route::get('/hourly-avg', [HourlyAverageController::class, 'index'])->name('hourly.index');

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