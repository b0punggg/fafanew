<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'thermal_printer.php';
?>
<!DOCTYPE html>
<html>
<head><title>Test Printer Thermal</title></head>
<body>
<h2>Diagnostik Printer Thermal</h2>
<ul>
  <li>OS: <?= PHP_OS ?></li>
  <li>php_printer (printer_open): <?= function_exists('printer_open') ? '<b style="color:green">AKTIF</b>' : '<b style="color:orange">TIDAK AKTIF</b>' ?></li>
  <li>Windows copy fallback (shell_exec): <?= thermal_shell_available() ? '<b style="color:green">TERSEDIA</b>' : '<b style="color:red">TIDAK TERSEDIA</b>' ?></li>
  <li>Cetak tersedia: <?= thermal_print_available() ? '<b style="color:green">YA</b>' : '<b style="color:red">TIDAK</b>' ?></li>
</ul>

<h3>Daftar Printer:</h3>
<?php
$printers = thermal_list_printers();
if (empty($printers)) {
    echo '<p style="color:red">Tidak ada printer terdeteksi.</p>';
} else {
    echo '<ul>';
    foreach ($printers as $p) {
        echo '<li>' . htmlspecialchars($p['NAME']) . '</li>';
    }
    echo '</ul>';
}
?>

<p><a href="pilihprinter.php">→ Test cetak ke printer</a></p>
</body>
</html>
