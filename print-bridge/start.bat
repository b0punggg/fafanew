@echo off
title Tokofafa Print Bridge
cd /d "%~dp0"
echo.
echo Untuk setup otomatis (disarankan), jalankan: setup-kasir.bat
echo.
if not exist node_modules (
  echo Installing dependencies...
  call npm install
)
echo Starting print bridge on port 3000...
node server.js
pause
