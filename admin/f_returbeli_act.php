<?php 
include 'config.php';
session_start();
$consave=opendtcek();
$kd_toko=$_SESSION['id_toko'];
//variable
$no_tran=trim($_POST['no_tran']);
$tgl_tran=$_POST['tgl_tran'];
$kd_sup=$_POST['kd_sup'];
$no_fak=$_POST['no_fak'];
$tgl_fak=$_POST['tgl_fak'];
$kd_brg=$_POST['kd_brg'];
$kd_sat=$_POST['kd_sat'];
$qty_retur=$_POST['qtyretur'];
$discretur=$_POST['discretur'];
$ketretur=$_POST['ketretur'];
$tax=$_POST['tax'];
$no_item=$_POST['no_item'];
$hrg_beli=$_POST['hrg_beli']*konjumbrg($kd_sat,$kd_brg);

$cek=mysqli_query($consave,"SELECT * FROM retur_beli_mas WHERE no_retur='$no_tran'");
if(mysqli_num_rows($cek)>=1){
  //proses cek pada beli_brg dan mas_brg
	$jml_retur=$qty_retur*konjumbrg($kd_sat,$kd_brg);
	$stok_akhir=caristokbeli($no_item,$kd_brg)-$jml_retur;
	//echo '$stok_akhir='.$stok_akhir.'<br>';
	$x=explode(';',caristokmas($kd_brg));
	$jml_brgakhir=$x[0]-$jml_retur;
	$jml_brg_klr=$x[2]+$jml_retur;

	//proses simpan pengembalian
	$d=mysqli_query($consave,"UPDATE beli_brg SET stok_jual='$stok_akhir' where no_urut='$no_item'");
	$d=mysqli_query($consave,"UPDATE mas_brg SET jml_brg='$jml_brgakhir',brg_klr='$jml_brg_klr' where kd_brg='$kd_brg'"); 	
}
$d=mysqli_query($consave,"INSERT INTO retur_beli VALUES('','$tgl_tran','$no_tran','$kd_sup','$no_fak','$tgl_fak','$kd_brg','$qty_retur','$kd_sat','$hrg_beli','$kd_toko','$discretur','$tax','$ketretur','$no_item','')");

mysqli_close($consave);
if ($d){
 	
}else{
  ?><script>popnew_error("Data Gagal disimpan");carinoretur(1,true)</script><?php		
}
?>