@echo off
title Install Print Bridge sebagai Windows Service
color 0E
cd /d "%~dp0"

:: Harus Administrator
net session >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Jalankan file ini sebagai Administrator.
    echo Klik kanan install-service.bat -^> Run as administrator
    pause
    exit /b 1
)

set SERVICE_NAME=TokofafaPrintBridge
set NODE_EXE=
set NSSM_EXE=%~dp0tools\nssm.exe

:: Cari node.exe
where node >nul 2>&1
if not errorlevel 1 (
    for /f "delims=" %%i in ('where node') do set NODE_EXE=%%i
)

if "%NODE_EXE%"=="" (
    if exist "C:\Program Files\nodejs\node.exe" set NODE_EXE=C:\Program Files\nodejs\node.exe
    if exist "C:\Program Files (x86)\nodejs\node.exe" set NODE_EXE=C:\Program Files (x86)\nodejs\node.exe
)

if "%NODE_EXE%"=="" (
    echo [ERROR] Node.js tidak ditemukan. Install Node.js terlebih dahulu.
    pause
    exit /b 1
)

:: Cek NSSM
if not exist "%NSSM_EXE%" (
    echo [ERROR] nssm.exe tidak ditemukan di: %~dp0tools\
    echo.
    echo Langkah:
    echo 1. Download NSSM dari https://nssm.cc/download
    echo 2. Extract nssm.exe ke folder: %~dp0tools\
    echo 3. Jalankan ulang file ini sebagai Administrator
    echo.
    echo Atau gunakan setup-kasir.bat ^(tanpa NSSM, via Task Scheduler^)
    pause
    exit /b 1
)

echo [1/5] Menginstall dependency...
call npm install
echo.

echo [2/5] Menghentikan service lama jika ada...
"%NSSM_EXE%" stop %SERVICE_NAME% >nul 2>&1
"%NSSM_EXE%" remove %SERVICE_NAME% confirm >nul 2>&1
schtasks /Delete /TN "TokofafaPrintBridge" /F >nul 2>&1
echo.

echo [3/5] Membuat Windows Service...
"%NSSM_EXE%" install %SERVICE_NAME% "%NODE_EXE%" "%~dp0server.js"
"%NSSM_EXE%" set %SERVICE_NAME% AppDirectory "%~dp0"
"%NSSM_EXE%" set %SERVICE_NAME% DisplayName "Tokofafa Print Bridge"
"%NSSM_EXE%" set %SERVICE_NAME% Description "Layanan cetak thermal lokal untuk aplikasi Tokofafa"
"%NSSM_EXE%" set %SERVICE_NAME% Start SERVICE_AUTO_START
"%NSSM_EXE%" set %SERVICE_NAME% AppStdout "%~dp0logs\service-out.log"
"%NSSM_EXE%" set %SERVICE_NAME% AppStderr "%~dp0logs\service-err.log"
"%NSSM_EXE%" set %SERVICE_NAME% AppRotateFiles 1
"%NSSM_EXE%" set %SERVICE_NAME% AppEnvironmentExtra "PRINTER_NAME=BP-LITE 80D+80X Printer"
if not exist "%~dp0logs" mkdir "%~dp0logs"
echo.

echo [4/5] Menjalankan service...
"%NSSM_EXE%" start %SERVICE_NAME%
timeout /t 3 /nobreak >nul
echo.

echo [5/5] Mengecek status...
sc query %SERVICE_NAME% | find "RUNNING" >nul
if errorlevel 1 (
    echo [PERINGATAN] Service belum RUNNING. Cek logs di folder logs\
) else (
    echo [OK] Service RUNNING
)

powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3000/health" -TimeoutSec 8
echo.
echo Setup service selesai. Print bridge jalan otomatis saat Windows boot.
pause
