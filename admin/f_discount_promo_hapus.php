<?php
  // File untuk menghapus discount promo
  // File ini dapat diakses oleh semua user termasuk operator (otoritas 1) dan administrator (otoritas 2)
  // Tidak ada pembatasan berdasarkan otoritas
  session_start();
  include 'config.php';
  $connect = opendtcek();
  $kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
  
  // Pastikan kd_toko ada
  if (empty($kd_toko)) {
    echo json_encode(array('status' => 'error', 'message' => 'Session tidak valid. Silakan login kembali.'));
    exit;
  }
  
  $no_promo = isset($_POST['no_promo']) ? mysqli_real_escape_string($connect, $_POST['no_promo']) : '';
  
  if (empty($no_promo)) {
    echo json_encode(array('status' => 'error', 'message' => 'Nomor promo tidak valid!'));
    exit;
  }
  
  // Hapus detail dulu
  $delete_detail = "DELETE FROM disc_promo_detail WHERE no_promo = '$no_promo' AND kd_toko = '$kd_toko'";
  mysqli_query($connect, $delete_detail);
  
  // Hapus promo
  $delete_promo = "DELETE FROM disc_promo WHERE no_promo = '$no_promo' AND kd_toko = '$kd_toko'";
  $result = mysqli_query($connect, $delete_promo);
  
  mysqli_close($connect);
  
  if ($result) {
    echo json_encode(array('status' => 'success', 'message' => 'Data promo berhasil dihapus!'));
  } else {
    echo json_encode(array('status' => 'error', 'message' => 'Gagal menghapus data promo!'));
  }
?>

