<?php 
$bcode=$_POST['bcode'];
ob_start();
session_start();
include 'config.php';
$kd_toko=$_SESSION['id_toko'];
$concek=opendtcek();

$s=mysqli_query($concek,"SELECT pilih FROM mas_brg WHERE pilih='1'");
if (mysqli_num_rows($s)>=1){ 
  $pilcode= 'f_cetbarcodego.php?bcode='.$bcode; ?>
  <script>
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
      document.getElementById('linkcetak').href="f_cetbarcodego_hp.php?bcode="+"<?=$bcode?>";document.getElementById('linkcetak').click();
    }else{
      document.getElementById('linkcetak').href="f_cetbarcodego.php?bcode="+"<?=$bcode?>";
      document.getElementById('linkcetak').click();
    } 
  </script><?php 
} else { ?>
  <script>popnew_warning("Belum ada yang dipilih")</script><?php 
} ?>

<?php
  mysqli_close($concek); unset($s);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>
