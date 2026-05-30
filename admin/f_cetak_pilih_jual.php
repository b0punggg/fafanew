<?php 
	$tgl1     = $_POST['tgl1'];
	$tgl2     = $_POST['tgl2'];
	$pilihcet = $_POST['pilih'];	
	$pilihbayar=$_POST['pilih3'];
	$kd_brand = isset($_POST['kd_brand']) ? $_POST['kd_brand'] : '';
	// echo "$pilihcet";
    if ($pilihcet=="NOTA"){
      header("location:f_cetak_jual_nota.php?pesan=$tgl1;$tgl2;$pilihbayar;$kd_brand");	
    }else{
      header("location:f_cetak_jual_item.php?pesan=$tgl1;$tgl2;$pilihbayar;$kd_brand");	
    }
?>    