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
| 1. GUEST ROUTES (Belum Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLoginForm'])->name('root');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 2. AUTH ROUTES (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- SETUP & VERIFIKASI (Boleh diakses setelah login untuk membuka gembok) ---
    Route::get('/setup', function () {
        return view('setup');
    })->name('setup.index');

    Route::post('/setup', [LoggerAuthController::class, 'processSetup'])->name('setup.process');

    Route::get('/verify-access', function () {
        return view('app'); // Tampilan Cyberpunk DAS
    })->name('verify.index');

    Route::post('/verify-access', [LoggerAuthController::class, 'verify'])->name('verify.submit');

    // Pengaturan Profil & Keamanan (Boleh diakses tanpa kode aktivasi)
    Route::get('/my-profile', [AuthController::class, 'profile'])->name('my-profile');
    Route::put('/my-profile', [AuthController::class, 'updateProfile'])->name('my-profile.update');
    Route::get('/security', [AuthController::class, 'security'])->name('security');
    Route::put('/security', [AuthController::class, 'updatePassword'])->name('security.update');


    /*
    |--------------------------------------------------------------------------
    | 3. ACTIVATED ROUTES (TERKUNCI RAPAT - 60 MENIT)
    |--------------------------------------------------------------------------
    | Semua rute di bawah ini HANYA bisa diakses jika user sudah login
    | DAN sudah memasukkan kode verifikasi (melalui middleware 'activated').
    | Jika diakses manual via URL, user akan langsung mental ke /verify-access atau /setup.
    */
    Route::middleware(['activated'])->group(function () {

        // --- DASHBOARD UTAMA ---
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/live', [DashboardController::class, 'getLiveDashboard'])->name('dashboard.live');
        Route::post('/dashboard/toggle-rca', [DashboardController::class, 'toggleRca'])->name('dashboard.toggle-rca');

        // --- MONITORING DATA & LOGS ---
        Route::get('/logs-data', [DasLogController::class, 'index'])->name('logs.index');
        Route::get('/logs-data/export', [DasLogController::class, 'exportExcel'])->name('logs.export');
        Route::get('/rca-records', [RcaLogController::class, 'index'])->name('rca.index');
        Route::get('/rca-records/export', [RcaLogController::class, 'exportExcel'])->name('rca.export');
        Route::get('/hourly-avg', [HourlyLogController::class, 'index'])->name('hourly.index');
        Route::get('/hourly-avg/export-excel', [HourlyLogController::class, 'exportExcel'])->name('hourly.export.excel');
        Route::get('/hourly-avg/export-simpel', [HourlyLogController::class, 'exportSimpel'])->name('hourly.export.simpel');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // --- MASTER DATA CRUD (Full Access) ---
        Route::resource('units', UnitController::class);
        Route::resource('sensor-config', SensorConfigController::class);
        Route::resource('stack-config', StackConfigController::class);
        Route::resource('users', UserController::class);
        Route::resource('references', ReferenceController::class);

        // --- SYSTEM CONFIG ---
        Route::prefix('system')->group(function () {
            Route::get('/global-config', [GlobalConfigController::class, 'index'])->name('global-config.index');
            Route::put('/global-config/update', [GlobalConfigController::class, 'update'])->name('global-config.update');
        });
    });

});