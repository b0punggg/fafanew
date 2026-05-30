<?php 
	$tgl1     = $_POST['tgl1'];
	$tgl2     = $_POST['tgl2'];
	$pilihcet = $_POST['pilih'];	
	// echo "$pilihcet";
    if ($pilihcet=="NOTA"){
      header("location:f_cetak_laba_nota.php?pesan=$tgl1;$tgl2");	
    }else{
      header("location:f_cetak_laba_item.php?pesan=$tgl1;$tgl2");	
    }
?>    