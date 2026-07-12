@echo off
title Cek Status Print Bridge
color 0B
cd /d "%~dp0"
echo.
echo [1] Health check (HTTPS)...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3000/health" -TimeoutSec 8
echo.
echo [2] Test cetak ke printer...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3000/print/test" -Method POST -TimeoutSec 20
echo.
pause
