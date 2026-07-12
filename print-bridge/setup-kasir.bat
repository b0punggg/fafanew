@echo off
title Setup Print Bridge - Tokofafa
color 0A
cd /d "%~dp0"

echo ============================================
echo   SETUP PRINT BRIDGE TOKOFAFA (PC KASIR)
echo ============================================
echo.

:: Cek Node.js
where node >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Node.js belum terinstall!
    echo.
    echo Download dan install Node.js LTS dari: https://nodejs.org
    echo Setelah install, jalankan ulang file ini.
    echo.
    pause
    exit /b 1
)

echo [OK] Node.js terdeteksi:
node -v
echo.

:: Install dependency
echo [1/5] Menginstall dependency npm...
call npm install
if errorlevel 1 (
    echo [ERROR] npm install gagal.
    pause
    exit /b 1
)
echo [OK] Dependency terinstall.
echo.

:: Buat sertifikat SSL untuk HTTPS
echo [2/5] Membuat sertifikat SSL (HTTPS)...
call node generate-cert.js
if errorlevel 1 (
    echo [ERROR] Gagal buat sertifikat SSL.
    pause
    exit /b 1
)
echo [OK] Sertifikat SSL siap.
echo.

:: Buat logo raster untuk nota (butuh PHP + GD, opsional)
echo [3/6] Membuat logo nota...
where php >nul 2>&1
if errorlevel 1 (
    if exist assets\logo-raster.bin (
        echo [OK] Logo sudah ada di assets\logo-raster.bin
    ) else (
        echo [PERINGATAN] PHP tidak ditemukan. Salin assets\logo-raster.bin dari repo.
    )
) else (
    php generate-logo.php
    if errorlevel 1 (
        echo [PERINGATAN] generate-logo.php gagal. Cek file logo di assets\ atau admin\img\
    ) else (
        echo [OK] Logo nota siap.
    )
)
echo.

:: Hapus task lama jika ada
echo [4/6] Mendaftarkan auto-start Windows...
set TASK_NAME=TokofafaPrintBridge
set VBS_PATH=%~dp0start-hidden.vbs

schtasks /Delete /TN "%TASK_NAME%" /F >nul 2>&1

schtasks /Create /TN "%TASK_NAME%" /TR "wscript.exe \"%VBS_PATH%\"" /SC ONLOGON /RU "%USERNAME%" /RL HIGHEST /F
if errorlevel 1 (
    echo [PERINGATAN] Gagal buat Task Scheduler. Mencoba folder Startup...
    set STARTUP=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup
    powershell -NoProfile -Command "$ws=New-Object -ComObject WScript.Shell; $s=$ws.CreateShortcut('%STARTUP%\Tokofafa Print Bridge.lnk'); $s.TargetPath='wscript.exe'; $s.Arguments='\"%VBS_PATH%\"'; $s.WorkingDirectory='%~dp0'; $s.Save()" 2>nul
    if errorlevel 1 (
        echo [ERROR] Gagal setup auto-start. Jalankan sebagai Administrator atau setup manual.
        pause
        exit /b 1
    )
    echo [OK] Shortcut dibuat di folder Startup.
) else (
    echo [OK] Task Scheduler "%TASK_NAME%" berhasil dibuat.
    echo      Print bridge akan jalan otomatis setiap login Windows.
)
echo.

:: Jalankan sekarang
echo [5/6] Menjalankan print-bridge sekarang...
start "" wscript.exe "%VBS_PATH%"
timeout /t 3 /nobreak >nul
echo.

:: Test kesehatan
echo [6/6] Mengecek status print-bridge...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3000/health" -TimeoutSec 8
echo.

echo ============================================
echo   SETUP SELESAI
echo ============================================
echo.
echo Printer default : BP-LITE 80D+80X Printer
echo Cek status      : https://localhost:3000/health
echo.
echo PENTING (sekali saja):
echo Buka https://localhost:3000/health di browser Chrome/Edge,
echo lalu klik "Lanjutkan" / accept sertifikat agar cetak dari
echo https://tokofafa.dhe51.id tidak diblokir Mixed Content.
echo.
echo Kasir TIDAK perlu klik start.bat lagi.
echo Print bridge jalan otomatis setiap login Windows.
echo.
echo Untuk service permanen (opsional), jalankan
echo install-service.bat sebagai Administrator.
echo.
pause
