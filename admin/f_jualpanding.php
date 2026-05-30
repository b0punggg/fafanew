<?php 
$keyword=$_POST['keyword'];
ob_start();
include 'config.php';
session_start();
$kd_toko=$_SESSION['id_toko'];
$id_user=$_SESSION['id_user'];
$connect=opendtcek();
if(!empty($keyword)){
  $x=explode(';', mysqli_escape_string($connect,$keyword));
  $no_fak=$x[0];
  $tgl_fak=$x[1];	
}else{$no_fak="";$tgl_fak="0000-00-00";}

if (empty($no_fak) || $tgl_fak=="0000-00-00"){
  ?><script>popnew_warning("Kiiihh... data kosong !");</script><?php
}else{
  // proses
  $con=$connect;
    mysqli_query($con,"UPDATE dum_jual SET panding=true WHERE no_fakjual='$no_fak' AND tgl_jual='$tgl_fak' AND kd_toko='$kd_toko'");
    $sq_set=mysqli_query($con,"SELECT * FROM seting WHERE nm_per='$kd_toko'");
    $dt_set=mysqli_fetch_assoc($sq_set);
    $nofak_awal=$dt_set['kode']+1;  
    mysqli_query($con,"UPDATE seting SET kode='$nofak_awal' WHERE nm_per='$kd_toko'");
    mysqli_free_result($sq_set);unset($dt_set);
 	  ?><script>startjual("<?=$kd_toko.';'.$id_user?>");</script><?php
}
?>
<script>kosongkan();</script>
<?php
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>