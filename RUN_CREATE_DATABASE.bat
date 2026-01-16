@echo off
echo ========================================
echo Membuat Database db_digilib_tbm
echo ========================================
echo.

REM Cek apakah XAMPP ada di lokasi default
if exist "C:\xampp\php\php.exe" (
    echo Menggunakan PHP dari XAMPP...
    C:\xampp\php\php.exe create_database.php
    goto :end
)

REM Cek apakah WAMP ada di lokasi default
if exist "C:\wamp64\bin\php\php8.2.0\php.exe" (
    echo Menggunakan PHP dari WAMP...
    C:\wamp64\bin\php\php8.2.0\php.exe create_database.php
    goto :end
)

if exist "C:\wamp\bin\php\php8.2.0\php.exe" (
    echo Menggunakan PHP dari WAMP...
    C:\wamp\bin\php\php8.2.0\php.exe create_database.php
    goto :end
)

REM Jika PHP tidak ditemukan, coba cari di PATH
where php >nul 2>&1
if %errorlevel% == 0 (
    echo Menggunakan PHP dari PATH...
    php create_database.php
    goto :end
)

echo.
echo ERROR: PHP tidak ditemukan!
echo.
echo Silakan install PHP atau tambahkan ke PATH.
echo Atau edit file ini dan sesuaikan path ke php.exe
echo.
echo Lokasi umum PHP:
echo   - XAMPP: C:\xampp\php\php.exe
echo   - WAMP: C:\wamp64\bin\php\php8.x.x\php.exe
echo.
pause

:end

