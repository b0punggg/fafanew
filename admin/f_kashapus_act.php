<?php 
 include "config.php";
 session_start();
 $connect=opendtcek();
    $id=$_GET['param'];
    // $tt=explode(";", $params);	
    // $id=$tt[0];
      $f=mysqli_query($connect, "Delete from kas_harian where no_urut='$id'" );    	
      if($f){
        header("location:f_kas.php?pesan=hapus");	
      }
      else {header("location:f_kas.php?pesan=gagal");	}
    
 ?>    