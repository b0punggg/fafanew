@echo off
title Restart Print Bridge - Tokofafa
cd /d "%~dp0"

echo Menghentikan print-bridge lama...
taskkill /F /IM node.exe >nul 2>&1
timeout /t 2 /nobreak >nul

echo Menjalankan print-bridge...
start "" wscript.exe "%~dp0start-hidden.vbs"
timeout /t 3 /nobreak >nul

echo.
echo Mengecek status...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3000/health" -TimeoutSec 8
echo.
pause
