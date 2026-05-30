<?php 
  // mysqli_close($connect);
	include 'config.php';
  session_start();
  $connect=opendtcek();
  $no_urut=$_POST['no_urut'];
	$nm_sat1=strtoupper($_POST['nm_sat1']);
  $nm_sat2=strtoupper($_POST['nm_sat2']);
  
  $cekkat=mysqli_query($connect,"select * from kemas where no_urut='$no_urut'");

  // Insert data obat
   if(mysqli_num_rows($cekkat)>=1){
      $d=mysqli_query($connect,"update kemas set nm_sat1='$nm_sat1',nm_sat2='$nm_sat2' where no_urut='$no_urut'");              
   } else {
      $d=mysqli_query($connect,"insert into kemas values('','$nm_sat1','$nm_sat2')");
   }
   unset($cekkat);
   mysqli_close($connect);
   if($d){header("location:m_kemas.php?pesan=simpan");}
   else{header("location:m_kemas.php?pesan=gagal");}    

?>
