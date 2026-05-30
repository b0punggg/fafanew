<?php 
ob_start();
session_start();
include 'config.php';
$no_returjual= $_POST['keyword1'];
$kd_toko     = $_POST['keyword2'];
$con         = opendtcek();


$q1=mysqli_query($con,"SELECT * FROM retur_jual WHERE no_returjual='$no_returjual' AND kd_toko='$kd_toko' AND proses=0");
if (mysqli_num_rows($q1)>=1){
  mysqli_query($con,"DELETE FROM retur_jual WHERE no_returjual='$no_returjual' AND kd_toko='$kd_toko' AND proses=0");	
}
?>
<?php
  mysqli_close($con);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>