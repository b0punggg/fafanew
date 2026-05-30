<?php 
  // mysqli_close($connect);
  include 'config.php';
  session_start();
  $connect=opendtcek();
  $no_urut=$_POST['no_urut'];
  $nm_bag=trim(strtoupper($_POST['nm_bag']));
  $cekkat=mysqli_query($connect,"select * from bag_brg where no_urut='$no_urut'");

   if(mysqli_num_rows($cekkat)>=1){
      $d=mysqli_query($connect,"UPDATE bag_brg set nm_bag='$nm_bag' WHERE no_urut='$no_urut'");              
   } else {
      $d=mysqli_query($connect,"INSERT INTO bag_brg values('','$nm_bag')");
   }
   unset($cekkat);
   mysqli_close($connect);
   if($d){header("location:m_bagian.php?pesan=simpan");}
   else{header("location:m_bagian.php?pesan=gagal");}    

?>
