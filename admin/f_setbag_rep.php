<?php
	ob_start();
	session_start();
	include 'config.php';
	
    $connect=opendtcek();  
    $kd_brg = mysqli_escape_string($connect,$_POST['keyword1']);
    $id_bag = mysqli_escape_string($connect,$_POST['keyword2']);
    $kd_toko=$_SESSION['id_toko'];
    
    //$ada=mysqli_query($connect,"SELECT kd_brg from beli_brg WHERE kd_toko='kd_toko' AND kd_brg='$kd_brg'");
    // if(mysqli_num_rows($ada)>=1){
        $qw=mysqli_query($connect,"UPDATE beli_brg SET id_bag='$id_bag' WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko'");
        $qs=mysqli_query($connect,"UPDATE dum_jual SET id_bag='$id_bag' WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko'");    
    // }
?>
<script>popnew_ok('<?=$kd_brg?>')</script>
<?php
  mysqli_close($connect);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>