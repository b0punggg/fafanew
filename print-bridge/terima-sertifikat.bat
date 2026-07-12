@echo off
title Terima Sertifikat Print Bridge
color 0E
cd /d "%~dp0"

if not exist "%~dp0ssl\cert.pem" (
    echo Membuat sertifikat SSL...
    node "%~dp0generate-cert.js"
    if errorlevel 1 (
        echo [ERROR] Gagal membuat sertifikat. Pastikan Node.js terinstall.
        pause
        exit /b 1
    )
)

echo Mengimpor sertifikat ke Trusted Root (user)...
certutil -addstore -user Root "%~dp0ssl\cert.pem"
if errorlevel 1 (
    echo [PERINGATAN] certutil gagal. Buka manual di browser:
    echo https://localhost:3000/health
    echo Klik Advanced -^> Proceed to localhost
) else (
    echo [OK] Sertifikat dipercaya. Browser tidak akan blokir cetak.
)
echo.
pause
