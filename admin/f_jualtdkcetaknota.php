<?php 
  $keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();

  
	$open = chr(27).chr(112).chr(48).chr(25).chr(250);
	$Text  = $open;

	include_once 'thermal_printer.php';
	if (thermal_print_available()) {
		$printResult = thermal_print_raw($Text, 'BP-LITE 80D+80X Printer');
		if (!$printResult['success']) {
			error_log("Buka laci gagal: " . $printResult['error']);
		}
	} else {
		error_log("Cetak thermal tidak tersedia. Aktifkan php_printer.dll atau shell_exec di Windows.");
	}

	// if ('$d') {
 //      header("location:f_jual.php?pesan=simpan");
 //    }else{header("location:f_jual.php?pesan=gagal");}
?>

<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>