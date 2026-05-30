<?php
session_start();
include 'config.php';

$kd_toko = $_SESSION['id_toko'];
$connect = opendtcek();

$nama_rule = isset($_POST['nama_rule']) ? mysqli_real_escape_string($connect, $_POST['nama_rule']) : '';
$kondisi = isset($_POST['kondisi']) ? mysqli_real_escape_string($connect, $_POST['kondisi']) : '';
$nilai_kondisi = isset($_POST['nilai_kondisi']) ? mysqli_real_escape_string($connect, $_POST['nilai_kondisi']) : '';
$disc_rupiah = isset($_POST['disc_rupiah']) ? floatval($_POST['disc_rupiah']) : 0;
$disc_persen = isset($_POST['disc_persen']) ? floatval($_POST['disc_persen']) : 0;
$status = isset($_POST['status']) ? intval($_POST['status']) : 1;

// Validasi
if (empty($nama_rule) || empty($kondisi) || empty($nilai_kondisi)) {
  echo json_encode(array('status' => 'error', 'message' => 'Data tidak lengkap!'));
  exit;
}

// Pastikan tabel disc_auto_rule ada
$create_table = "CREATE TABLE IF NOT EXISTS disc_auto_rule (
  no_urut INT AUTO_INCREMENT PRIMARY KEY,
  nama_rule VARCHAR(255) NOT NULL,
  kondisi VARCHAR(50) NOT NULL,
  nilai_kondisi VARCHAR(255) NOT NULL,
  disc_rupiah DECIMAL(15,2) DEFAULT 0,
  disc_persen DECIMAL(5,2) DEFAULT 0,
  status TINYINT(1) DEFAULT 1,
  kd_toko VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_toko (kd_toko),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

mysqli_query($connect, $create_table);

// Insert rule baru
$insert = "INSERT INTO disc_auto_rule (nama_rule, kondisi, nilai_kondisi, disc_rupiah, disc_persen, status, kd_toko) 
           VALUES ('$nama_rule', '$kondisi', '$nilai_kondisi', '$disc_rupiah', '$disc_persen', '$status', '$kd_toko')";

if (mysqli_query($connect, $insert)) {
  echo json_encode(array('status' => 'success', 'message' => 'Rule discount berhasil disimpan!'));
} else {
  echo json_encode(array('status' => 'error', 'message' => 'Gagal menyimpan rule: ' . mysqli_error($connect)));
}

mysqli_close($connect);
?>

