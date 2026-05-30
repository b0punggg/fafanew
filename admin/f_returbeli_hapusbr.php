<?php 
$no_urut=$_POST['keyword'];
ob_start();
include 'config.php';
session_start();
$conhapus=opendtcek();
$no_urut=mysqli_real_escape_string($conhapus,$no_urut);
$cek=mysqli_query($conhapus,"SELECT * FROM retur_beli WHERE no_urut='$no_urut'");
$datcek=mysqli_fetch_assoc($cek);
$no_retur=$datcek['no_retur'];
$tgl_retur=$datcek['tgl_retur'];
$jml_retur=$datcek['qty_brg']*konjumbrg($datcek['kd_sat'],$datcek['kd_brg']);
$kd_brg=$datcek['kd_brg'];
$no_item=$datcek['no_item'];

$stok_akhir=caristokbeli($no_item,$kd_brg)+$jml_retur;

$x=explode(';',caristokmas($kd_brg));
$jml_brgakhir=$x[0]+$jml_retur;
$jml_brg_klr=$x[2]-$jml_retur;

unset($cek,$datcek);

$cek=mysqli_query($conhapus,"SELECT * FROM retur_beli_mas WHERE tgl_retur='$tgl_retur' AND no_retur='$no_retur'");
if (mysqli_num_rows($cek)>=1){
   $d=mysqli_query($conhapus,"UPDATE beli_brg SET stok_jual='$stok_akhir' where no_urut='$no_item'");
   $d=mysqli_query($conhapus,"UPDATE mas_brg SET jml_brg='$jml_brgakhir',brg_klr='$jml_brg_klr' where kd_brg='$kd_brg'");
   $d=mysqli_query($conhapus,"DELETE FROM retur_beli WHERE no_urut='$no_urut'");

   //**cek kembali pada retur_beli jika sdh tdk ada hapus pd retur_beli_mas
   $cekretur=mysqli_query($conhapus,"SELECT COUNT(*) AS jumdt FROM retur_beli WHERE tgl_retur='$tgl_retur' AND no_retur='$no_retur'");
   $dcekretur=mysqli_fetch_assoc($cekretur); 
   if ($dcekretur['jumdt']==0){
     $d=mysqli_query($conhapus,"DELETE FROM retur_beli_mas WHERE no_retur='$no_retur' AND tgl_retur='$tgl_retur'");     
   }
   unset($cekretur,$dcekretur);
   //----------
}else{
   $d=mysqli_query($conhapus,"DELETE FROM retur_beli WHERE no_urut='$no_urut'");	
}

if ($d){
	?><script>popnew_warning("Data Terhapus");kosongkan2();carinoretur(1,true);</script><?php
} else {
	?><script>popnew_error("Data Gagal Terhapus");kosongkan2();carinoretur(1,true);</script><?php
}
mysqli_close($conhapus);

?>	
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>