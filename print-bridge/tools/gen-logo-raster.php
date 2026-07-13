<?php
/**
 * Generate ESC/POS raster logo for print-bridge (jalankan sekali setelah ganti logo).
 * php print-bridge/tools/gen-logo-raster.php
 */
require __DIR__ . '/../../assets/escpos-php/autoload.php';

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

$src = __DIR__ . '/../../admin/img/logofafaprc1.png';
$processed = __DIR__ . '/../assets/logo-processed.png';
$out = __DIR__ . '/../assets/logo-raster.bin';
$outAdmin = __DIR__ . '/../../admin/img/logo-raster.bin';
$maxWidth = 300;

if (!file_exists($src)) {
  fwrite(STDERR, "Logo tidak ditemukan: $src\n");
  exit(1);
}

if (!function_exists('imagecreatefrompng')) {
  fwrite(STDERR, "Ekstensi GD PHP diperlukan.\n");
  exit(1);
}

function preprocessLogoForThermal($srcPath, $dstPath, $maxWidth)
{
  $src = imagecreatefrompng($srcPath);
  if (!$src) {
    throw new Exception('Gagal baca PNG logo');
  }

  $w = imagesx($src);
  $h = imagesy($src);
  if ($w > $maxWidth) {
    $newH = (int) round($h * $maxWidth / $w);
    $resized = imagecreatetruecolor($maxWidth, $newH);
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
    imagefill($resized, 0, 0, $transparent);
    imagecopyresampled($resized, $src, 0, 0, 0, 0, $maxWidth, $newH, $w, $h);
    imagedestroy($src);
    $src = $resized;
    $w = $maxWidth;
    $h = $newH;
  }

  $out = imagecreatetruecolor($w, $h);
  $white = imagecolorallocate($out, 255, 255, 255);
  $black = imagecolorallocate($out, 0, 0, 0);
  imagefill($out, 0, 0, $white);

  for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
      $rgba = imagecolorat($src, $x, $y);
      $r = ($rgba >> 16) & 0xFF;
      $g = ($rgba >> 8) & 0xFF;
      $b = $rgba & 0xFF;
      $a = ($rgba >> 24) & 0x7F;
      if ($a >= 120) {
        continue;
      }
      $lum = ($r * 0.299) + ($g * 0.587) + ($b * 0.114);
      if ($lum < 200) {
        imagesetpixel($out, $x, $y, $black);
      }
    }
  }

  imagepng($out, $dstPath);
  imagedestroy($src);
  imagedestroy($out);
}

preprocessLogoForThermal($src, $processed, $maxWidth);

$connector = new FilePrintConnector($out);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$logo = EscposImage::load($processed, false);
$printer->bitImage($logo);
$printer->feed();
$printer->close();

copy($out, $outAdmin);

echo "OK: $out (" . filesize($out) . " bytes)\n";
echo "OK: $outAdmin\n";
echo "Processed: $processed\n";
