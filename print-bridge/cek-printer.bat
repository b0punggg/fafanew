@echo off
title Cek Print Bridge - Tokofafa
cd /d "%~dp0"

echo === Status Print Bridge ===
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3000/health" -TimeoutSec 8
echo.
echo === Test Cetak ===
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0http-helper.ps1" -Url "https://localhost:3000/print/test" -Method POST -Body "{}" -TimeoutSec 25
echo.
pause
