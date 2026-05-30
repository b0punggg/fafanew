<?php
session_start();
include 'config.php';

$kd_toko = $_SESSION['id_toko'];
$connect = opendtcek();

$no_urut = isset($_POST['no_urut']) ? intval($_POST['no_urut']) : 0;
$status = isset($_POST['status']) ? intval($_POST['status']) : 0;

if ($no_urut > 0) {
  $update = "UPDATE disc_auto_rule SET status='$status' WHERE no_urut='$no_urut' AND kd_toko='$kd_toko'";
  
  if (mysqli_query($connect, $update)) {
    echo json_encode(array('status' => 'success', 'message' => 'Status berhasil diubah!'));
  } else {
    echo json_encode(array('status' => 'error', 'message' => 'Gagal mengubah status: ' . mysqli_error($connect)));
  }
} else {
  echo json_encode(array('status' => 'error', 'message' => 'ID tidak valid!'));
}

mysqli_close($connect);
?>

