<?php
    $filename = $_GET['nama_file'];
    $file = "../backup/".$filename;

    header('Content-type: application/octet-stream');
    header("Content-Type: ".mime_content_type($file));
    header("Content-Disposition: attachment; filename=".$filename);
    while (ob_get_level()) {
        ob_end_clean();
    }
    readfile($file);
   
    // $file=$_GET['nama_file'];    
    // // nama file yang akan didownload
    // header("Content-Disposition: attachment; filename=".$file);
    // // ukuran file yang akan didownload
    // header("Content-length: ".$file);
    // // jenis file yang akan didownload
    // header("Content-type: ".$file);
    // // proses membaca isi file yang akan didownload dari folder
    // $fp  = fopen("../backup/".$file, 'r');
    // $content = fread($fp, filesize('../backup/'.$file));
    // fclose($fp);
    // echo $content;
    // exit;
?>