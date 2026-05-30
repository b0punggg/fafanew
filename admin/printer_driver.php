<?php
function printer_draw_text_custom($printerName, $text) {
    // Buat file sementara
    $tmpFile = tempnam(sys_get_temp_dir(), 'print_');
    file_put_contents($tmpFile, $text);

    // Kirim ke printer (Windows command)
    shell_exec("copy /B \"$tmpFile\" \"\\\\localhost\\$printerName\"");
    unlink($tmpFile);
}
?>