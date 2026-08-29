<?php 
include 'config.php';
include 'f_cetak_jual_item_helper.php';
session_start();
$connect=opendtcek();
ensureBrandReportTable($connect);

$no_urut = isset($_POST['no_urut']) ? (int)$_POST['no_urut'] : 0;
$nm_brand = trim($_POST['nm_brand']);
$nm_esc = mysqli_real_escape_string($connect, $nm_brand);

if ($nm_brand === '') {
  header("location:m_brand_report.php?pesan=gagal");
  exit;
}

$d = false;
if ($no_urut > 0) {
  $dup = mysqli_query($connect, "SELECT no_urut FROM brand_report WHERE nm_brand='$nm_esc' AND no_urut<>$no_urut LIMIT 1");
  if ($dup && mysqli_num_rows($dup) >= 1) {
    mysqli_free_result($dup);
    mysqli_close($connect);
    header("location:m_brand_report.php?pesan=duplikat");
    exit;
  }
  if ($dup) {
    mysqli_free_result($dup);
  }
  $d = mysqli_query($connect, "UPDATE brand_report SET nm_brand='$nm_esc' WHERE no_urut=$no_urut");
} else {
  $dup = mysqli_query($connect, "SELECT no_urut FROM brand_report WHERE nm_brand='$nm_esc' LIMIT 1");
  if ($dup && mysqli_num_rows($dup) >= 1) {
    mysqli_free_result($dup);
    mysqli_close($connect);
    header("location:m_brand_report.php?pesan=duplikat");
    exit;
  }
  if ($dup) {
    mysqli_free_result($dup);
  }
  $d = mysqli_query($connect, "INSERT INTO brand_report (nm_brand) VALUES ('$nm_esc')");
}

mysqli_close($connect);
if ($d) {
  header("location:m_brand_report.php?pesan=simpan");
} else {
  header("location:m_brand_report.php?pesan=gagal");
}
?>