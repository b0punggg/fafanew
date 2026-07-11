<?php
header('Content-Type: application/json; charset=utf-8');
include_once 'thermal_printer.php';

$printerName = isset($_POST['nama_printer']) ? trim($_POST['nama_printer']) : '';
$testText = chr(27) . chr(64);
$testText .= "TEST CETAK TOKOFAFA\n";
$testText .= date('d/m/Y H:i:s') . "\n";
$testText .= "Printer OK\n\n\n";
$testText .= chr(29) . 'V' . chr(48) . chr(0);

$result = thermal_print_raw($testText, $printerName !== '' ? $printerName : null);
echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>
