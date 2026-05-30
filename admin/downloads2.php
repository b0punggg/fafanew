<?php 
    $filename = $_GET['nama_file'];
    $file = "../fdown/".$filename;

    header('Content-type: application/octet-stream');
    header("Content-Type: ".mime_content_type($file));
    header("Content-Disposition: attachment; filename=".$filename);
    while (ob_get_level()) {
        ob_end_clean();
    }
    readfile($file);
 ?>