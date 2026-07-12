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
    echo Download dan install Node.js v12 untuk Windows 7
    echo atau LTS terbaru untuk Windows 10+
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

:: Buat sertifikat SSL
echo [2/5] Membuat sertifikat HTTPS...
node generate-cert.js
if errorlevel 1 (
    echo [ERROR] Gagal membuat sertifikat SSL.
    pause
    exit /b 1
)
echo [OK] Sertifikat SSL siap.
echo.

:: Terima sertifikat di Windows (agar browser tidak blokir)
echo [3/5] Mendaftarkan sertifikat ke Windows...
certutil -addstore -user Root "%~dp0ssl\cert.pem" >nul 2>&1
if errorlevel 1 (
    echo [PERINGATAN] certutil gagal. Jalankan terima-sertifikat.bat manual.
) else (
    echo [OK] Sertifikat dipercaya Windows.
)
echo.

:: Hapus task lama jika ada
echo [4/5] Mendaftarkan auto-start Windows...
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
)
echo.

:: Jalankan sekarang
echo [5/5] Menjalankan print-bridge...
start "" wscript.exe "%VBS_PATH%"
timeout /t 4 /nobreak >nul
echo.

echo Mengecek status print-bridge...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3000/health" -TimeoutSec 8
echo.

echo ============================================
echo   SETUP SELESAI
echo ============================================
echo.
echo Printer default : BP-LITE 80D+80X Printer
echo Cek status      : https://localhost:3000/health
echo.
echo PENTING (sekali saja jika cetak masih gagal):
echo   Buka https://localhost:3000/health di browser kasir
echo   Klik Advanced -^> Proceed to localhost
echo.
echo Kasir TIDAK perlu klik start.bat lagi.
echo Print bridge jalan otomatis setiap login Windows.
echo.
pause
