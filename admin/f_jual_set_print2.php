<?php
ob_start();
include 'config.php';
session_start();
$pil=""; 
if($_POST['pilprints']=='CETAK-CK'){
  $pil="Printer 58inc";   
  $_SESSION['pilprint']="CETAK-CK";
}else{
  $pil="Printer 80inc";     
  $_SESSION['pilprint']="CETAK";
}


if(count($_COOKIE) < 1){
  //tdk ada
  setcookie("Kts", $_SESSION['pilprint'], time() + (86400 * 30), "/");
}else{
  // if(!isset($_COOKIE["kts"])){
    setcookie("Kts", $_SESSION['pilprint'], time() + (86400 * 30), "/");
  // }
}

?>
<script>
  document.getElementById('fpilprint2').style.display='none'
  popnew_ok("<?=$pil?>");
</script>
<?php
$html = ob_get_contents();
ob_end_clean();
echo json_encode(array('hasil'=>$html));