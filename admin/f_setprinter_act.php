<?php 
session_start();
include 'config.php';
$cet_pilih = $_POST['cet_pilih'];
$cet_copy  = $_POST['cet_copy'];
$potong    = $_POST['pilih_potong'];
$p_proses  = $_POST['p_proses'];
// $nofaktur  = $_POST['nofakturs'];
$conp      = opendtcek();

if ($cet_copy==0){ $cet_copy=1; }
$d=false;$e=false;$f=false;
$d=mysqli_query($conp,"UPDATE seting SET kode='$cet_pilih' WHERE nm_per='CETAK'");
$e=mysqli_query($conp,"UPDATE seting SET kode='$cet_copy' WHERE nm_per='COPY'");
$f=mysqli_query($conp,"UPDATE seting SET kode='$potong' WHERE nm_per='POTONG'");
$f=mysqli_query($conp,"UPDATE seting SET kode='$p_proses' WHERE nm_per='PROSES'");
//$f=mysqli_query($conp,"UPDATE seting SET kode='$nofaktur' WHERE nm_per='NOFAKTUR'");

$df=mysqli_query($conp,"SELECT * FROM toko ORDER BY no_urut");
while($dt=mysqli_fetch_assoc($df)){
  $kode_toko=$_POST[$dt['kd_toko']];
  $kd_toko=$dt['kd_toko'];
  $f=mysqli_query($conp,"UPDATE seting SET kode='$kode_toko' WHERE nm_per='$kd_toko'");  
}
mysqli_free_result($df);unset($dt);
mysqli_close($conp);
if ( $d !=true ) {
 ?><script>popnew_error("Gagal update cetak..");</script><?php	
}
if ( $e !=true ) {
 ?><script>popnew_error("Gagal update copy..");</script><?php	
}
if ( $d !=true ) {
 ?><script>popnew_error("Gagal update potong..");</script><?php	
}
?>