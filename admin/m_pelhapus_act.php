<?php 
 include "config.php";
 session_start();
 $connect=opendtcek();
    $id=$_GET['param'];
    // $tt=explode(";", $params);	
    // $id=$tt[0];
      $f=mysqli_query($connect, "Delete from pelanggan where no_urut='$id'" );    	
      if($f){
        header("location:m_pel.php?pesan=hapus");	
      }
      else {header("location:m_pel.php?pesan=gagal");	}
  mysqli_close($connect);  
 ?>    