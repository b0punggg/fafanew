<?php 
  // mysqli_close($connect);
	include 'config.php';
  session_start();
  $connect=opendtcek();
        
  $no_urut=$_POST['no_urut'];
	$kd_pel=strtoupper($_POST['kd_pel']);
	$nm_pel=strtoupper($_POST['nm_pel']);
  $al_pel=strtoupper($_POST['al_pel']);
  $no_telp=strtoupper($_POST['no_telp']);
  
  $cekkat=mysqli_query($connect,"select * from pelanggan where kd_pel='$kd_pel'");

  // Insert data obat
   if(mysqli_num_rows($cekkat)>=1){
      $d=mysqli_query($connect,"update pelanggan set nm_pel='$nm_pel',al_pel='$al_pel',no_telp='$no_telp' where kd_pel='$kd_pel'");              
   } else {
      $d=mysqli_query($connect,"insert into pelanggan values('','$kd_pel','$nm_pel','$al_pel','$no_telp')");
   }
   unset($cekkat);
   if($d){header("location:m_pel.php?pesan=simpan");}
   else{header("location:m_pel.php?pesan=gagal");}    
   
   mysqli_close($connect);     
?>
