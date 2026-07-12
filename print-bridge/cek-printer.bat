@echo off
title Cek Status Print Bridge
color 0B
cd /d "%~dp0"
echo.
echo [1] Health check HTTP (utama)...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "http://localhost:3000/health" -TimeoutSec 8
echo.
echo [2] Health check HTTPS (opsional)...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3443/health" -TimeoutSec 8
echo.
echo [3] Test cetak ke printer...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "http://localhost:3000/print/test" -Method POST -TimeoutSec 20
echo.
pause
