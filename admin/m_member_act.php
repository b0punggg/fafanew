<?php 
  // mysqli_close($connect);
	include 'config.php';
  session_start();
  $connect=opendtcek();
  $kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
        
  $no_urut=$_POST['no_urut'];
	$kd_member=strtoupper($_POST['kd_member']);
	$nm_member=strtoupper($_POST['nm_member']);
  $nm_toko=strtoupper($_POST['nm_toko']);
  $al_member=strtoupper($_POST['al_member']);
  $no_telp=strtoupper($_POST['no_telp']);
  
  $cekkat=mysqli_query($connect,"select * from member where kd_member='$kd_member' AND kd_toko='$kd_toko'");

  // Insert data member
   if(mysqli_num_rows($cekkat)>=1){
      $d=mysqli_query($connect,"update member set nm_member='$nm_member',nm_toko='$nm_toko',al_member='$al_member',no_telp='$no_telp' where kd_member='$kd_member' AND kd_toko='$kd_toko'");              
   } else {
      $d=mysqli_query($connect,"insert into member (kd_member,nm_member,nm_toko,al_member,no_telp,poin,tgl_daftar,kd_toko) values('$kd_member','$nm_member','$nm_toko','$al_member','$no_telp','0.00', CURDATE(),'$kd_toko')");
   }
   unset($cekkat);
   if($d){header("location:m_member.php?pesan=simpan");}
   else{header("location:m_member.php?pesan=gagal");}    
   
   mysqli_close($connect);     
?>

