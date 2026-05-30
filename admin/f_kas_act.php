<?php 
  // mysqli_close($connect);
	include 'config.php';
  session_start();
  $connect=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
	$no_urut=$_POST['no_urut'];
	$tgl_kas=$_POST['tgl_kas'];
  $id_user=$_SESSION['id_user'];
  // echo $kd_toko.'<br>';
  // echo $no_urut.'<br>';
  // echo $tgl_kas.'<br>';
  // echo $id_user.'<br>';
  $uang_kas=backnum($_POST['uang_kas']);

  //$cekkas=mysqli_query($connect,"select * from kas_harian where no_urut='$no_urut'");
  $cekkas=mysqli_query($connect,"select * from kas_harian where tgl_kas='$tgl_kas' AND kd_toko='$kd_toko'");
  // Insert data obat
   if(mysqli_num_rows($cekkas)>=1){
     $d=mysqli_query($connect,"update kas_harian set tgl_kas='$tgl_kas',uang_kas='$uang_kas' where tgl_kas='$tgl_kas' AND kd_toko='$kd_toko'");              
   } else {
     $d=mysqli_query($connect,"insert into kas_harian values('','$tgl_kas','$uang_kas','$kd_toko','$id_user')");
   }
   unset($cekkas);
   if($d){header("location:f_kas.php?pesan=simpan");}
   else{header("location:f_kas.php?pesan=gagal");}    
?>
