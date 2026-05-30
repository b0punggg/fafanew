<?php 
 include "config.php";
 session_start();
 $connect=opendtcek();
    $id=$_GET['param'];
    // $tt=explode(";", $params);	
    // $id=$tt[0];
      $f=mysqli_query($connect, "Delete from toko where no_urut='$id'" );    	
      if($f){
        header("location:m_toko.php?pesan=hapus");	
      }
      else {header("location:m_toko.php?pesan=gagal");	}
      
    mysqli_close($connect);	    
 ?>    