<?php 
include "config.php";
session_start();
$connect=opendtcek();
$id = isset($_GET['param']) ? (int)$_GET['param'] : 0;
if ($id > 0) {
  $f = mysqli_query($connect, "DELETE FROM brand_report WHERE no_urut=$id");
  if ($f) {
    header("location:m_brand_report.php?pesan=hapus");
    exit;
  }
}
mysqli_close($connect);
header("location:m_brand_report.php?pesan=gagal");
?>