@echo off
title Uninstall Auto-Start Print Bridge
cd /d "%~dp0"

echo Menghapus auto-start print-bridge...
schtasks /Delete /TN "TokofafaPrintBridge" /F 2>nul

set STARTUP=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup
if exist "%STARTUP%\Tokofafa Print Bridge.lnk" del "%STARTUP%\Tokofafa Print Bridge.lnk"

taskkill /F /IM node.exe /FI "WINDOWTITLE eq *server.js*" >nul 2>&1

echo.
echo Auto-start dihapus.
echo Untuk hapus Windows Service, jalankan uninstall-service.bat sebagai Admin.
pause
