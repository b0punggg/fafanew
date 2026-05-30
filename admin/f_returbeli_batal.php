<?php 
ob_start();
include 'config.php';
session_start();

$conhapus=opendtcek();
$kd_toko=$_SESSION['id_toko'];
$no_tran=mysqli_real_escape_string($conhapus,$_POST['keyword1']);
$tgl_tran=mysqli_real_escape_string($conhapus,$_POST['keyword2']);
$d=false;
// cek sudah proses simpan apa belum
$ceksim=mysqli_query($conhapus,"SELECT * FROM retur_beli_mas WHERE no_retur='$no_tran' AND tgl_retur='$tgl_tran' ");
if (mysqli_num_rows($ceksim)>=1){
  $d=mysqli_query($conhapus,"DELETE FROM retur_beli_mas WHERE no_retur='$no_tran' AND tgl_retur='$tgl_tran'");	
  $cekret=mysqli_query($conhapus,"SELECT * FROM retur_beli WHERE tgl_retur='$tgl_tran' AND no_retur='$no_tran'");
  if (mysqli_num_rows($cekret)>=1)
  {	
  	while($datcekret=mysqli_fetch_assoc($cekret)){
  		$no_urut=$datcekret['no_urut'];
  		$no_item=$datcekret['no_item'];
  		$kd_brg=$datcekret['kd_brg'];

        $jml_retur=$datcekret['qty_brg']*konjumbrg($datcekret['kd_sat'],$datcekret['kd_brg']);   	
        // echo '$no_item='.$no_item.',';
        //kembalikan nilai awal pd beli_brg dan mas brg
        $stok_akhir=caristokbeli($no_item,$kd_brg)+$jml_retur;	
        // echo '$stok_akhir='.$stok_akhir.',';
        $d=mysqli_query($conhapus,"UPDATE beli_brg SET stok_jual='$stok_akhir' WHERE no_urut='$no_item'");
        $x=explode(';',caristokmas($kd_brg));
		$jml_brgakhir=$x[0]+$jml_retur;
		$jml_brg_klr=$x[2]-$jml_retur;        
		$d=mysqli_query($conhapus,"UPDATE mas_brg SET jml_brg='$jml_brgakhir',brg_klr='$jml_brg_klr' WHERE kd_brg='$kd_brg'");
		$d=mysqli_query($conhapus,"DELETE FROM retur_beli WHERE no_urut='$no_urut'");
  	}

  }   
}else {
    $cekret=mysqli_query($conhapus,"SELECT * FROM retur_beli WHERE no_retur='$no_tran' AND tgl_retur='$tgl_tran'");
	  if (mysqli_num_rows($cekret)>=1){
	  	$d=mysqli_query($conhapus,"DELETE FROM retur_beli WHERE no_retur='$no_tran' AND tgl_retur='$tgl_tran'");
	  }
	  unset($cekret);
  }	
unset($ceksim);

if ($d){
	?><script>popnew_warning("Data Terupdate");kosongkan();carinoretur(1,true);</script><?php
} else {
	?><script>popnew_error("Update Data Gagal");kosongkan();carinoretur(1,true);</script><?php
}

mysqli_close($conhapus);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>  