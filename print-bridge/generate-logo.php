<?php
/**
 * Generate ESC/POS logo bytes untuk print-bridge.
 * Sumber: admin/img/logofafaprc.png (di-resize & dikonversi hitam-putih untuk thermal).
 *
 * Jalankan: php generate-logo.php
 */
$autoload = dirname(__DIR__) . '/assets/escpos-php/autoload.php';
if (!file_exists($autoload)) {
    fwrite(STDERR, "escpos-php tidak ditemukan.\n");
    exit(1);
}
require $autoload;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;

// Lebar kira-kira logo lama di nota 80mm (~36mm printable area)
define('LOGO_MAX_WIDTH', 288);
define('LOGO_MAX_HEIGHT', 120);

$sourcePath = dirname(__DIR__) . '/admin/img/logofafaprc.png';
if (!file_exists($sourcePath)) {
    $sourcePath = __DIR__ . '/assets/logofafaprc.png';
}
if (!file_exists($sourcePath)) {
    fwrite(STDERR, "Logo tidak ditemukan: admin/img/logofafaprc.png\n");
    exit(1);
}

$outDir = __DIR__ . '/assets';
if (!is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

/**
 * Konversi PNG gelap (abu di atas hitam) ke hitam-putih siap thermal.
 */
function prepareThermalLogo($sourcePath, $maxWidth, $maxHeight, $destPath) {
    $info = getimagesize($sourcePath);
    if ($info === false) {
        throw new RuntimeException('Gagal membaca gambar logo.');
    }

    $mime = $info['mime'];
    switch ($mime) {
        case 'image/png':
            $src = imagecreatefrompng($sourcePath);
            break;
        case 'image/jpeg':
            $src = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/gif':
            $src = imagecreatefromgif($sourcePath);
            break;
        default:
            throw new RuntimeException('Format gambar tidak didukung: ' . $mime);
    }

    if (!$src) {
        throw new RuntimeException('Gagal load gambar logo.');
    }

    $srcW = imagesx($src);
    $srcH = imagesy($src);

    $scale = min($maxWidth / $srcW, $maxHeight / $srcH, 1.0);
    $newW = max(1, (int) round($srcW * $scale));
    $newH = max(1, (int) round($srcH * $scale));

    $resized = imagecreatetruecolor($newW, $newH);
    $white = imagecolorallocate($resized, 255, 255, 255);
    imagefill($resized, 0, 0, $white);
    imagealphablending($resized, true);
    imagesavealpha($resized, false);
    imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

    $mono = imagecreatetruecolor($newW, $newH);
    imagefill($mono, 0, 0, $white);
    $black = imagecolorallocate($mono, 0, 0, 0);

    // Piksel gelap (bukan background hitam pekat) -> cetak hitam di kertas putih
    for ($y = 0; $y < $newH; $y++) {
        for ($x = 0; $x < $newW; $x++) {
            $rgb = imagecolorat($resized, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $lum = (int) (0.299 * $r + 0.587 * $g + 0.114 * $b);

            // Background hitam pekat -> putih; konten logo (abu gelap) -> hitam
            if ($lum < 45) {
                imagesetpixel($mono, $x, $y, $white);
            } elseif ($lum < 200) {
                imagesetpixel($mono, $x, $y, $black);
            } else {
                imagesetpixel($mono, $x, $y, $white);
            }
        }
    }

    imagepng($mono, $destPath);
    imagedestroy($src);
    imagedestroy($resized);
    imagedestroy($mono);

    return array('width' => $newW, 'height' => $newH, 'path' => $destPath);
}

$processedPath = $outDir . '/logo-processed.png';
$meta = prepareThermalLogo($sourcePath, LOGO_MAX_WIDTH, LOGO_MAX_HEIGHT, $processedPath);

$connector = new DummyPrintConnector();
$printer = new Printer($connector);
$printer->initialize();
$printer->setJustification(Printer::JUSTIFY_CENTER);

$logo = EscposImage::load($processedPath, true);
// graphics() lebih kompatibel daripada bitImage() di banyak printer 80mm
$printer->graphics($logo);
$printer->feed(1);
$printer->setJustification(Printer::JUSTIFY_LEFT);

$data = $connector->getData();
$outFile = $outDir . '/logo-raster.bin';
file_put_contents($outFile, $data);
$printer->close();

echo "Logo dibuat: $outFile (" . strlen($data) . " bytes)\n";
echo "Sumber      : $sourcePath\n";
echo "Processed   : {$meta['width']}x{$meta['height']} px -> $processedPath\n";
