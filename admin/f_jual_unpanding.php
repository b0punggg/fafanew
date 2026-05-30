<?php
ob_start();
session_start();
include 'config.php';
$kd_toko=$_SESSION['id_toko'];
$id_user=$_SESSION['id_user'];
$conun=opendtcek();
$tgl=mysqli_escape_string($conun,$_POST['tgl_jual']);
$no_fakjual=mysqli_escape_string($conun,$_POST['no_fakjual']);
mysqli_query($conun,"UPDATE dum_jual SET panding='0' WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl'");

  mysqli_close($conun);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>