<?php 
  // mysqli_close($connect);
	include 'config.php';
  session_start();
  $connect=opendtcek();
  $no_urut=$_POST['no_urut'];
	$kd_sup=strtoupper($_POST['kd_sup']);
	$nm_sup=strtoupper($_POST['nm_sup']);
  $nm_sales=strtoupper($_POST['nm_sales']);
  $al_sup=strtoupper($_POST['al_sup']);
  $no_telp=strtoupper($_POST['no_telp']);
  
  $cekkat=mysqli_query($connect,"select * from supplier where kd_sup='$kd_sup'");

  // Insert data obat
   if(mysqli_num_rows($cekkat)>=1){
      $d=mysqli_query($connect,"update supplier set nm_sup='$nm_sup',nm_sales='$nm_sales',al_sup='$al_sup',no_telp='$no_telp' where kd_sup='$kd_sup'");              
   } else {
      $d=mysqli_query($connect,"insert into supplier values('','$kd_sup','$nm_sup','$nm_sales','$al_sup','$no_telp')");
   }
   unset($cekkat);
   if($d){header("location:m_sup.php?pesan=simpan");}
   else{header("location:m_sup.php?pesan=gagal");}    
   mysqli_close($connect);
?>
