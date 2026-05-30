<?php
	$kode = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
	include 'config.php';
	session_start();

    $connect=opendtcek();  
	$kd_toko=$_SESSION['id_toko'];
    
    $d=mysqli_query($connect,"UPDATE seting SET kode='$kode' WHERE nm_per='tampil_stok'");
    if ($d){
      if ($kode==1){
      	echo 'Stok kosong ditampilkan';
      }	else {
      	echo 'Stok kosong tidak ditampilkan';
      }
    }
?>

<?php
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>