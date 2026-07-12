@echo off
title Restart Print Bridge
color 0E
cd /d "%~dp0"

echo Menghentikan print-bridge lama...
taskkill /F /IM node.exe /FI "WINDOWTITLE eq Tokofafa Print Bridge*" >nul 2>&1
for /f "tokens=2" %%p in ('tasklist /FI "IMAGENAME eq node.exe" /FO LIST ^| find "PID:"') do (
    wmic process where "ProcessId=%%p" get CommandLine 2>nul | find /I "server.js" >nul && taskkill /F /PID %%p >nul 2>&1
)

timeout /t 2 /nobreak >nul
echo Menjalankan ulang...
start "" wscript.exe "%~dp0start-hidden.vbs"
timeout /t 3 /nobreak >nul

powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "http://localhost:3000/health" -TimeoutSec 8
echo.
pause
