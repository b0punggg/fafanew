<?php 
 include "config.php";
 session_start();
 $connect=opendtcek();
    $id=$_GET['param'];
    // $tt=explode(";", $params);	
    // $id=$tt[0];
      $f=mysqli_query($connect, "Delete from supplier where no_urut='$id'" );    	
      if($f){
        header("location:m_sup.php?pesan=hapus");	
      }
      else {header("location:m_sup.php?pesan=gagal");	}
  mysqli_close($connect);  
 ?>    