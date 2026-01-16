@echo off
echo ========================================
echo Menjalankan Yii2 Application
echo ========================================
echo.
echo Server akan berjalan di: http://localhost:8080
echo Tekan Ctrl+C untuk menghentikan server
echo.

REM Cek apakah XAMPP ada di lokasi default
if exist "C:\xampp\php\php.exe" (
    echo Menggunakan PHP dari XAMPP...
    cd /d "%~dp0"
    C:\xampp\php\php.exe -S localhost:8080 -t web
    goto :end
)

REM Cek apakah WAMP ada di lokasi default
if exist "C:\wamp64\bin\php\php8.2.0\php.exe" (
    echo Menggunakan PHP dari WAMP...
    cd /d "%~dp0"
    C:\wamp64\bin\php\php8.2.0\php.exe -S localhost:8080 -t web
    goto :end
)

if exist "C:\wamp\bin\php\php8.2.0\php.exe" (
    echo Menggunakan PHP dari WAMP...
    cd /d "%~dp0"
    C:\wamp\bin\php\php8.2.0\php.exe -S localhost:8080 -t web
    goto :end
)

REM Jika PHP tidak ditemukan, coba cari di PATH
where php >nul 2>&1
if %errorlevel% == 0 (
    echo Menggunakan PHP dari PATH...
    cd /d "%~dp0"
    php -S localhost:8080 -t web
    goto :end
)

echo.
echo ERROR: PHP tidak ditemukan!
echo.
echo Silakan install PHP atau tambahkan ke PATH.
echo Atau edit file ini dan sesuaikan path ke php.exe
echo.
pause

:end

