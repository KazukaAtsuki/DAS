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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\HourlyLogController;
use App\Http\Controllers\LoggerAuthController;

/*
|--------------------------------------------------------------------------
| 1. GUEST ROUTES (Pintu Luar)
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLoginForm'])->name('root');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 2. AUTH ROUTES (Sudah Login - Mode "View Only")
|--------------------------------------------------------------------------
| User yang sudah login diizinkan memantau data (Monitoring),
| tetapi tidak diizinkan memanipulasi data sebelum aktivasi.
*/
Route::middleware(['auth'])->group(function () {

    // --- SETUP & VERIFIKASI (Jalur Pembukaan Gembok) ---
    Route::get('/setup', function () { return view('setup'); })->name('setup.index');
    Route::post('/setup', [LoggerAuthController::class, 'processSetup'])->name('setup.process');
    Route::get('/verify-access', function () { return view('app'); })->name('verify.index');
    Route::post('/verify-access', [LoggerAuthController::class, 'verify'])->name('verify.submit');

    // --- MONITORING AREA (Bebas Akses Setelah Login) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/live', [DashboardController::class, 'getLiveDashboard'])->name('dashboard.live');
    Route::post('/dashboard/toggle-rca', [DashboardController::class, 'toggleRca'])->name('dashboard.toggle-rca');

    // Riwayat Log & Data Sensor
    Route::get('/logs-data', [DasLogController::class, 'index'])->name('logs.index');
    Route::get('/rca-records', [RcaLogController::class, 'index'])->name('rca.index');
    Route::get('/hourly-avg', [HourlyLogController::class, 'index'])->name('hourly.index');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Tampilan Tabel Master (Hanya fungsi INDEX / Melihat List)
    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::get('/sensor-config', [SensorConfigController::class, 'index'])->name('sensor-config.index');
    Route::get('/stack-config', [StackConfigController::class, 'index'])->name('stack-config.index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/references', [ReferenceController::class, 'index'])->name('references.index');
    Route::get('/system/global-config', [GlobalConfigController::class, 'index'])->name('global-config.index');

    // Export Data (Biasanya diizinkan tanpa verifikasi tambahan)
    Route::get('/logs-data/export', [DasLogController::class, 'exportExcel'])->name('logs.export');
    Route::get('/rca-records/export', [RcaLogController::class, 'exportExcel'])->name('rca.export');
    Route::get('/hourly-avg/export-excel', [HourlyLogController::class, 'exportExcel'])->name('hourly.export.excel');
    Route::get('/hourly-avg/export-simpel', [HourlyLogController::class, 'exportSimpel'])->name('hourly.export.simpel');

    // Pengaturan Akun Pribadi
    Route::get('/my-profile', [AuthController::class, 'profile'])->name('my-profile');
    Route::put('/my-profile', [AuthController::class, 'updateProfile'])->name('my-profile.update');
    Route::get('/security', [AuthController::class, 'security'])->name('security');
    Route::put('/security', [AuthController::class, 'updatePassword'])->name('security.update');

    /*
    |--------------------------------------------------------------------------
    | 3. ACTIVATED ROUTES (TERKUNCI TOTAL - KHUSUS CRUD)
    |--------------------------------------------------------------------------
    | Semua rute manipulasi data dibungkus middleware 'activated'.
    | Jika user klik tombol Add/Edit/Delete, sistem otomatis mengecek
    | kesamaan kode di storage/app/expired.txt dengan Python API (Logika 3).
    */
    Route::middleware(['activated'])->group(function () {

        // CRUD Master Data (Kecuali 'index' karena sudah didefinisikan di atas)
        // Ini mengunci otomatis route: create, store, edit, update, destroy
        Route::resource('units', UnitController::class)->except(['index']);
        Route::resource('sensor-config', SensorConfigController::class)->except(['index']);
        Route::resource('stack-config', StackConfigController::class)->except(['index']);
        Route::resource('users', UserController::class)->except(['index']);
        Route::resource('references', ReferenceController::class)->except(['index']);

        // Aksi Update Konfigurasi Sistem (PENTING)
        Route::put('/system/global-config/update', [GlobalConfigController::class, 'update'])->name('global-config.update');
    });

});