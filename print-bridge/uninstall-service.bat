@echo off
title Uninstall Windows Service Print Bridge
cd /d "%~dp0"

net session >nul 2>&1
if errorlevel 1 (
    echo Jalankan sebagai Administrator.
    pause
    exit /b 1
)

set NSSM_EXE=%~dp0tools\nssm.exe
set SERVICE_NAME=TokofafaPrintBridge

if exist "%NSSM_EXE%" (
    "%NSSM_EXE%" stop %SERVICE_NAME% >nul 2>&1
    "%NSSM_EXE%" remove %SERVICE_NAME% confirm >nul 2>&1
    echo Service %SERVICE_NAME% dihapus.
) else (
    sc stop %SERVICE_NAME% >nul 2>&1
    sc delete %SERVICE_NAME% >nul 2>&1
    echo Service dihapus via sc.
)

pause
