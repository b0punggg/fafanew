<?php
	session_start();
	include 'config.php';
    $connect=opendtcek();  
	$c_kertas=$_POST['c_kertas'];
    $_SESSION['kertas']=$c_kertas;
?>
<script>popnew_ok("Ukuran Kertas <?=$c_kertas?>")</script>
