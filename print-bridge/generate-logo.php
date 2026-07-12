<?php
/**
 * Generate ESC/POS logo bytes untuk print-bridge (jalankan sekali atau saat ganti logo).
 * Butuh PHP + ext-gd. Contoh: php generate-logo.php
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

$candidates = [
    __DIR__ . '/assets/logofafa.png',
    __DIR__ . '/assets/logofafa.jpg',
    dirname(__DIR__) . '/admin/img/logofafa2.png',
    dirname(__DIR__) . '/admin/img/logofafa.png',
    dirname(__DIR__) . '/admin/img/logo_login.jpg',
    dirname(__DIR__) . '/admin/img/logo_login2.jpg',
];

$imagePath = null;
foreach ($candidates as $path) {
    if (file_exists($path)) {
        $imagePath = $path;
        break;
    }
}

if ($imagePath === null) {
    fwrite(STDERR, "File logo tidak ditemukan. Letakkan logofafa.png di print-bridge/assets/\n");
    exit(1);
}

$outDir = __DIR__ . '/assets';
if (!is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

$connector = new DummyPrintConnector();
$printer = new Printer($connector);
$printer->initialize();
$printer->setJustification(Printer::JUSTIFY_CENTER);
$logo = EscposImage::load($imagePath, true);
$printer->bitImage($logo);
$printer->feed(1);
$printer->setJustification(Printer::JUSTIFY_LEFT);

$data = $connector->getData();
$outFile = $outDir . '/logo-raster.bin';
file_put_contents($outFile, $data);
$printer->close();

echo "Logo dibuat: $outFile (" . strlen($data) . " bytes) dari $imagePath\n";
