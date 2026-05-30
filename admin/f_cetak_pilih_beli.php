<?php 
	$tgl1     = $_POST['tgl1'];
	$tgl2     = $_POST['tgl2'];
	$pilihcet = $_POST['pilih'];	
	$pilihbayar=$_POST['pilih2'];	
	// echo "$pilihcet";
    if ($pilihcet=="NOTA"){
      header("location:f_cetak_beli_nota.php?pesan=$tgl1;$tgl2;$pilihbayar");	
    }else{
      header("location:f_cetak_beli_item.php?pesan=$tgl1;$tgl2;$pilihbayar");	
    }
?>    