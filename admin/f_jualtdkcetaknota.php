<?php 
  $keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();

  
	$open = chr(27).chr(112).chr(48).chr(25).chr(250);
	$Text  = $open;

	// Cek apakah fungsi printer tersedia
	if (function_exists('printer_open')) {
		// Definisikan konstanta PRINTER_MODE jika belum ada
		if (!defined('PRINTER_MODE')) {
			define('PRINTER_MODE', 2); // PRINTER_MODE = 2 untuk RAW mode
		}
		
		// Gunakan call_user_func untuk menghindari linter error
		$printer_name = "GP-80220(Cut) Series"; // Printer default yang digunakan sebelumnya
		$printer = call_user_func('printer_open', $printer_name);
		
		if ($printer) {
			call_user_func('printer_set_option', $printer, PRINTER_MODE, "RAW");
			call_user_func('printer_write', $printer, $Text);    
			call_user_func('printer_close', $printer);
		} else {
			// Jika printer tidak ditemukan, log error
			error_log("Printer $printer_name tidak ditemukan atau tidak dapat dibuka");
		}
	} else {
		// Jika extension printer tidak tersedia, log error
		error_log("PHP Printer extension tidak tersedia. Pastikan extension php_printer.dll sudah diaktifkan di php.ini");
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