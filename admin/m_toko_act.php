<?php 
  // mysqli_close($connect);
	include 'config.php';
  session_start();
  $connect=opendtcek();
        
  $no_urut=$_POST['no_urut'];
	$kd_toko=strtoupper($_POST['kd_toko']);
	$nm_toko=strtoupper($_POST['nm_toko']);
  $al_toko=strtoupper($_POST['al_toko']);
  $no_telp=strtoupper($_POST['no_telp']);
  
  $cekkat=mysqli_query($connect,"select * from toko where kd_toko='$kd_toko'");

  // Insert data obat
   if(mysqli_num_rows($cekkat)>=1){
      $d=mysqli_query($connect,"update toko set nm_toko='$nm_toko',al_toko='$al_toko',no_telp='$no_telp' where kd_toko='$kd_toko'");              
   } else {
      $d=mysqli_query($connect,"insert into toko values('','$kd_toko','$nm_toko','$al_toko','$no_telp')");
   }
   unset($cekkat);
   if($d){header("location:m_toko.php?pesan=simpan");}
   else{header("location:m_toko.php?pesan=gagal");}    

   mysqli_close($connect);     
?>
