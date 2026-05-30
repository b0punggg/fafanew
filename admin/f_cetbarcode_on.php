<?php 
ob_start();
session_start();
include 'config.php';
$conbar=opendtcek();
$kd_toko=$_SESSION['id_toko'];
$id_user=$_SESSION['id_user'];
$kd_brg=mysqli_real_escape_string($conbar,$_POST['keyword']);
//echo $kd_brg;
$d=false;
$d=mysqli_query($conbar,"UPDATE mas_brg SET pilih='1',id_user='$id_user' WHERE kd_brg='$kd_brg'");
if($d){ ?>
  <script>
	popnew_ok("ok"+" <?=$kd_brg?>");
  </script> <?php
}else{?>
  <script>
	popnew_error("error"+" <?=$kd_brg?>");
  </script><?php	
} ?>

<?php
  mysqli_close($conbar);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>