param(
    [string]$Url = 'https://localhost:3000/health',
    [string]$Method = 'GET',
    [string]$Body = '',
    [int]$TimeoutSec = 10
)

# Izinkan sertifikat self-signed localhost (kompatibel PowerShell 2 / Windows 7)
Add-Type @"
using System.Net;
using System.Security.Cryptography.X509Certificates;
public class TrustLocalhostCertPolicy : ICertificatePolicy {
    public bool CheckValidationResult(
        ServicePoint srvPoint,
        X509Certificate certificate,
        WebRequest request,
        int certificateProblem) {
        return true;
    }
}
"@

[System.Net.ServicePointManager]::CertificatePolicy = New-Object TrustLocalhostCertPolicy

try {
    $req = [System.Net.WebRequest]::Create($Url)
    $req.Method = $Method
    $req.Timeout = $TimeoutSec * 1000

    if ($Method -eq 'POST') {
        $req.ContentType = 'application/json'
        $bytes = [System.Text.Encoding]::UTF8.GetBytes($Body)
        $req.ContentLength = $bytes.Length
        $stream = $req.GetRequestStream()
        $stream.Write($bytes, 0, $bytes.Length)
        $stream.Close()
    }

    $resp = $req.GetResponse()
    $reader = New-Object System.IO.StreamReader($resp.GetResponseStream())
    $content = $reader.ReadToEnd()
    $reader.Close()
    $resp.Close()
    Write-Output $content
} catch {
    Write-Output ('ERROR: ' + $_.Exception.Message)
    exit 1
}
