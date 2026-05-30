<?php 
 include "config.php";
 session_start();
 $connect=opendtcek();
    $id=$_GET['param'];
      $f=mysqli_query($connect, "Delete from bag_brg where no_urut='$id'" );    	
      if($f){
        header("location:m_bagian.php?pesan=hapus");	
      }
      else {header("location:m_bagian.php?pesan=gagal");	}
  mysqli_close($connect);  
 ?>    