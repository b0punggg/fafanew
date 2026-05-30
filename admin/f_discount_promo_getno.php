<?php
session_start();
include 'config.php';

// File ini dapat diakses oleh semua user termasuk operator (otoritas 1) dan administrator (otoritas 2)
// Tidak ada pembatasan berdasarkan otoritas
$kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';

// Pastikan kd_toko ada
if (empty($kd_toko)) {
  echo json_encode(array('no_promo' => ''));
  exit;
}

$connect = opendtcek();

// Generate nomor promo
$tahun = date('y');
$bulan = date('m');
$max_no = 0;

// Cek apakah tabel sudah ada, jika belum skip query
$table_check = mysqli_query($connect, "SHOW TABLES LIKE 'disc_promo'");
if (mysqli_num_rows($table_check) > 0) {
  $query_no = mysqli_query($connect, "SELECT MAX(CAST(SUBSTRING(no_promo, 10) AS UNSIGNED)) as max_no FROM disc_promo WHERE no_promo LIKE 'JS-DIS $tahun$bulan.%' AND kd_toko='$kd_toko'");
  if ($query_no) {
    $data_no = mysqli_fetch_assoc($query_no);
    if ($data_no && isset($data_no['max_no'])) {
      $max_no = intval($data_no['max_no']);
    }
  }
}

$no_urut = $max_no + 1;
$no_promo = "JS-DIS $tahun$bulan." . str_pad($no_urut, 4, '0', STR_PAD_LEFT);

mysqli_close($connect);

echo json_encode(array('no_promo' => $no_promo));
?>

