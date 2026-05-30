<?php 
ob_start();
session_start();
include 'config.php';
$conbar=opendtcek();
$kd_toko=$_SESSION['id_toko'];
$id_user=$_SESSION['id_user'];
$x=explode(";",mysqli_real_escape_string($conbar,$_POST['keyword']));
$kd_brg=$x[0];
$copy=$x[1];
//echo $kd_brg;

mysqli_query($conbar,"UPDATE mas_brg SET copy='$copy',id_user='$id_user' WHERE kd_brg='$kd_brg'");
?>

<?php
  mysqli_close($conbar);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>