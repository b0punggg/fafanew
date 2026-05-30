<?php
session_start();
include 'config.php';

$kd_toko = $_SESSION['id_toko'];
$connect = opendtcek();

$no_urut = isset($_POST['no_urut']) ? intval($_POST['no_urut']) : 0;

if ($no_urut > 0) {
  $delete = "DELETE FROM disc_auto_rule WHERE no_urut='$no_urut' AND kd_toko='$kd_toko'";
  
  if (mysqli_query($connect, $delete)) {
    echo json_encode(array('status' => 'success', 'message' => 'Rule berhasil dihapus!'));
  } else {
    echo json_encode(array('status' => 'error', 'message' => 'Gagal menghapus rule: ' . mysqli_error($connect)));
  }
} else {
  echo json_encode(array('status' => 'error', 'message' => 'ID tidak valid!'));
}

mysqli_close($connect);
?>

