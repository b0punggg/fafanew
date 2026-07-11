<?php
/**
 * @deprecated Gunakan thermal_printer.php
 */
include_once 'thermal_printer.php';

function printer_draw_text_custom($printerName, $text) {
    $result = thermal_print_raw($text, $printerName);
    return $result['success'];
}
?>