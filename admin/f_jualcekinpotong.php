<?php
$kd_sat=$_POST['keyword1'];
$kd_brg=$_POST['keyword2'];
ob_start();
include 'config.php';
session_start();
$concek=opendtcek();
$satbesar='';
if(!empty($kd_brg)){
  $satbesar=carisatbesar($kd_brg);
  $x=explode(";",$satbesar);
  $satbesar=$x[0];
}
if ($kd_sat != $satbesar){
  ?><script>document.getElementById('cekpotong').value='0';</script><?php
}

mysqli_close($concek); 
?>

<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html)); 
 ?>