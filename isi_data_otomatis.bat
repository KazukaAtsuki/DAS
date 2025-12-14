@echo off
title Robot Pengisi Data DAS (JANGAN DI CLOSE)

:: 1. Masuk ke folder project
cd /d C:\laravel-pkl\ProjectTrudasPKL\ProjectTrudasPKL

:loop
:: ------------------------------------------------
:: 2. Jalankan perintah seeder
echo [%TIME%] Mengirim data ke Database...
php artisan db:seed --class=DasLogSeeder
:: ------------------------------------------------

:: 3. Tunggu 2 detik
timeout /t 2 /nobreak >nul

:: 4. Ulangi lagi dari atas (Looping)
goto loop