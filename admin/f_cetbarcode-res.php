<?php 
session_start();
include 'config.php';
$conres=opendtcek();
$kd_toko=$_SESSION['id_toko'];
$id_user=$_SESSION['id_user'];
mysqli_query($conres,"UPDATE mas_brg SET pilih='0',copy='0' WHERE kd_toko='$kd_toko' AND id_user='$id_user'");
header("location:f_cetbarcode.php");
 ?>