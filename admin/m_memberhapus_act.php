<?php 
 include "config.php";
 session_start();
 $connect=opendtcek();
  $kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
    $id=$_GET['param'];
    // $tt=explode(";", $params);	
    // $id=$tt[0];
      $f=mysqli_query($connect, "Delete from member where no_urut='$id' AND kd_toko='$kd_toko'" );    	
      if($f){
        header("location:m_member.php?pesan=hapus");	
      }
      else {header("location:m_member.php?pesan=gagal");	}
  mysqli_close($connect);  
 ?>     

