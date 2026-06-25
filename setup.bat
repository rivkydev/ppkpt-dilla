@echo off
setlocal enabledelayedexpansion

echo =======================================================
echo  Automated Laravel Project Setup Script (with XAMPP)
echo =======================================================

:: 1. Cek keberadaan PHP dari XAMPP
set "PHP_PATH=C:\xampp\php"
if not exist "%PHP_PATH%\php.exe" (
    echo [ERROR] php.exe tidak ditemukan di %PHP_PATH%
    echo Pastikan XAMPP terinstall dan path-nya benar.
    pause
    exit /b
)
:: Tambahkan PHP ke System PATH sementara untuk sesi ini
set "PATH=%PHP_PATH%;%PATH%"

echo.
echo [1/6] Memeriksa Composer...
if not exist "composer.phar" (
    echo Mengunduh composer.phar...
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
) else (
    echo Composer.phar sudah tersedia.
)

echo.
echo [2/6] Menjalankan composer install...
php composer.phar install

echo.
echo [3/6] Menyiapkan file .env...
if exist ".env" goto skip_env

copy .env.example .env
echo Mengubah konfigurasi database ke MySQL (XAMPP default)...
powershell -Command "(Get-Content .env) -replace 'DB_CONNECTION=sqlite', 'DB_CONNECTION=mysql' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_HOST=127.0.0.1', 'DB_HOST=127.0.0.1' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_PORT=3306', 'DB_PORT=3306' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_DATABASE=laravel', 'DB_DATABASE=ppkpt' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_USERNAME=root', 'DB_USERNAME=root' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_PASSWORD=', 'DB_PASSWORD=' | Set-Content .env"
goto env_done

:skip_env
echo File .env sudah ada, skip copy.

:env_done

echo.
echo [4/7] Menghasilkan Application Key, RSA Keys, dan Storage Link...
php artisan key:generate
php artisan rsa:generate
php artisan storage:link

echo.
echo [5/6] Menyiapkan Database dan Menjalankan Migrasi...
echo -------------------------------------------------------
echo [PERHATIAN]
echo 1. Pastikan Apache dan MySQL di XAMPP Control Panel sudah berstatus START.
echo 2. Buka browser: http://localhost/phpmyadmin
echo 3. Buat database baru dengan nama: ppkpt
echo -------------------------------------------------------
echo.
set /p run_migrate="Apakah Anda ingin melakukan migrate:fresh dan seeding data awal (reset database)? (y/n): "
if /i "%run_migrate%"=="y" (
    echo Menjalankan php artisan migrate:fresh --seed...
    php artisan migrate:fresh --seed
) else (
    echo Melewati proses migrasi.
)

echo.
echo [6/7] Menginstall Dependensi Frontend (NPM) dan Build Assets...
echo Memeriksa instalasi Node.js (NPM)...
where npm >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] NPM tidak ditemukan. Pastikan Node.js sudah di-install!
    echo Download di: https://nodejs.org/
    pause
    exit /b
)
call npm install
call npm run build

echo.
echo [7/7] Menjalankan Local Development Server...
echo Buka di browser: http://localhost:8000
echo Tekan CTRL+C untuk menghentikan server.
php artisan serve
