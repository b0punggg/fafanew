<?php
session_start();
include 'config.php';

// File ini dapat diakses oleh semua user termasuk operator (otoritas 1) dan administrator (otoritas 2)
// Tidak ada pembatasan berdasarkan otoritas
$kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
$connect = opendtcek();

// Pastikan kd_toko ada
if (empty($kd_toko)) {
  echo json_encode(array('status' => 'error', 'message' => 'Session tidak valid. Silakan login kembali.'));
  exit;
}

$no_promo = isset($_POST['no_promo']) ? mysqli_real_escape_string($connect, $_POST['no_promo']) : '';
$nama_promo = isset($_POST['nama_promo']) ? mysqli_real_escape_string($connect, $_POST['nama_promo']) : '';
$tgl_awal = isset($_POST['tgl_awal']) ? mysqli_real_escape_string($connect, $_POST['tgl_awal']) : '';
$tgl_akhir = isset($_POST['tgl_akhir']) ? mysqli_real_escape_string($connect, $_POST['tgl_akhir']) : '';
$disc_rupiah = isset($_POST['disc_rupiah']) ? floatval($_POST['disc_rupiah']) : 0;
$disc_persen = isset($_POST['disc_persen']) ? floatval($_POST['disc_persen']) : 0;
$by_nama = isset($_POST['by_nama']) ? mysqli_real_escape_string($connect, $_POST['by_nama']) : '';
$aksi = isset($_POST['aksi']) ? $_POST['aksi'] : 'simpan_tutup';

// Validasi
if (empty($no_promo) || empty($nama_promo) || empty($tgl_awal) || empty($tgl_akhir)) {
  echo json_encode(array('status' => 'error', 'message' => 'Data tidak lengkap!'));
  exit;
}

// Pastikan tabel disc_promo dan disc_promo_detail ada
$create_table_promo = "CREATE TABLE IF NOT EXISTS disc_promo (
  no_urut INT AUTO_INCREMENT PRIMARY KEY,
  no_promo VARCHAR(50) NOT NULL,
  nama_promo VARCHAR(255) NOT NULL,
  tgl_awal DATE NOT NULL,
  tgl_akhir DATE NOT NULL,
  disc_rupiah DECIMAL(15,2) DEFAULT 0,
  disc_persen DECIMAL(5,2) DEFAULT 0,
  by_nama VARCHAR(255),
  kd_toko VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_no_promo (no_promo),
  INDEX idx_tgl (tgl_awal, tgl_akhir),
  INDEX idx_toko (kd_toko)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$create_table_detail = "CREATE TABLE IF NOT EXISTS disc_promo_detail (
  no_urut INT AUTO_INCREMENT PRIMARY KEY,
  no_promo VARCHAR(50) NOT NULL,
  kd_brg VARCHAR(50) NOT NULL,
  disc_rupiah DECIMAL(15,2) DEFAULT 0,
  disc_persen DECIMAL(5,2) DEFAULT 0,
  kd_toko VARCHAR(50) NOT NULL,
  INDEX idx_no_promo (no_promo),
  INDEX idx_kd_brg (kd_brg),
  INDEX idx_toko (kd_toko)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

mysqli_query($connect, $create_table_promo);
mysqli_query($connect, $create_table_detail);

// SELALU INSERT BARU - Tidak pernah UPDATE untuk menghindari data promo yang sudah ada tergantikan
// Jika no_promo sudah ada, generate nomor baru
$cek_promo = mysqli_query($connect, "SELECT * FROM disc_promo WHERE no_promo='$no_promo' AND kd_toko='$kd_toko'");

if (mysqli_num_rows($cek_promo) > 0) {
  // Jika no_promo sudah ada, generate nomor baru
  $tahun = date('y');
  $bulan = date('m');
  $max_no = 0;
  
  $query_no = mysqli_query($connect, "SELECT MAX(CAST(SUBSTRING(no_promo, 10) AS UNSIGNED)) as max_no FROM disc_promo WHERE no_promo LIKE 'JS-DIS $tahun$bulan.%' AND kd_toko='$kd_toko'");
  if ($query_no) {
    $data_no = mysqli_fetch_assoc($query_no);
    if ($data_no && isset($data_no['max_no'])) {
      $max_no = intval($data_no['max_no']);
    }
  }
  
  $no_urut = $max_no + 1;
  $no_promo = "JS-DIS $tahun$bulan." . str_pad($no_urut, 4, '0', STR_PAD_LEFT);
}

// Insert promo baru (SELALU INSERT, TIDAK PERNAH UPDATE)
$insert_promo = "INSERT INTO disc_promo (no_promo, nama_promo, tgl_awal, tgl_akhir, disc_rupiah, disc_persen, by_nama, kd_toko) 
                 VALUES ('$no_promo', '$nama_promo', '$tgl_awal', '$tgl_akhir', '$disc_rupiah', '$disc_persen', '$by_nama', '$kd_toko')";

$insert_result = mysqli_query($connect, $insert_promo);
if (!$insert_result) {
  $error_msg = mysqli_error($connect);
  mysqli_close($connect);
  echo json_encode(array('status' => 'error', 'message' => 'Gagal insert promo: ' . $error_msg));
  exit;
}

// Insert detail barang untuk promo ini
// PENTING: Setiap promo memiliki detail barang yang terpisah berdasarkan no_promo yang unik
// Barang yang di-load untuk promo ini hanya akan tersimpan dengan no_promo ini, tidak tercampur dengan promo lain
// Handle items dari JSON atau array
$items = array();
if (isset($_POST['items'])) {
  if (is_string($_POST['items'])) {
    // Jika items adalah JSON string
    $items = json_decode($_POST['items'], true);
  } else if (is_array($_POST['items'])) {
    $items = $_POST['items'];
  }
}

// Alternatif: jika items dikirim sebagai array terpisah
// Handle format kd_brg[0], kd_brg[1], etc.
$kd_brg_array = array();
foreach ($_POST as $key => $value) {
  if (preg_match('/^kd_brg\[(\d+)\]$/', $key, $matches)) {
    $index = $matches[1];
    $kd_brg_array[$index] = mysqli_real_escape_string($connect, $value);
  }
}

$detail_count = 0;

if (!empty($kd_brg_array)) {
  // Simpan detail barang dengan no_promo yang unik untuk promo ini
  foreach ($kd_brg_array as $index => $kd_brg) {
    $disc_rp = isset($_POST['disc_rupiah_item[' . $index . ']']) ? floatval($_POST['disc_rupiah_item[' . $index . ']']) : 0;
    $disc_pr = isset($_POST['disc_persen_item[' . $index . ']']) ? floatval($_POST['disc_persen_item[' . $index . ']']) : 0;
    
    if (!empty($kd_brg)) {
      // Setiap detail barang disimpan dengan no_promo yang spesifik untuk promo ini
      $insert_detail = "INSERT INTO disc_promo_detail (no_promo, kd_brg, disc_rupiah, disc_persen, kd_toko) 
                        VALUES ('$no_promo', '$kd_brg', '$disc_rp', '$disc_pr', '$kd_toko')";
      $result_detail = mysqli_query($connect, $insert_detail);
      if ($result_detail) {
        $detail_count++;
      }
    }
  }
} else if (!empty($items)) {
  // Handle items dari JSON/array
  // Setiap item disimpan dengan no_promo yang unik untuk promo ini
  foreach ($items as $item) {
    if (!isset($item['kd_brg']) || empty($item['kd_brg'])) {
      continue;
    }
    
    $kd_brg = mysqli_real_escape_string($connect, $item['kd_brg']);
    $disc_rp = floatval($item['disc_rupiah']);
    $disc_pr = floatval($item['disc_persen']);
    
    if (!empty($kd_brg)) {
      // Setiap detail barang disimpan dengan no_promo yang spesifik untuk promo ini
      $insert_detail = "INSERT INTO disc_promo_detail (no_promo, kd_brg, disc_rupiah, disc_persen, kd_toko) 
                        VALUES ('$no_promo', '$kd_brg', '$disc_rp', '$disc_pr', '$kd_toko')";
      $result_detail = mysqli_query($connect, $insert_detail);
      if ($result_detail) {
        $detail_count++;
      }
    }
  }
}

// Pastikan ada detail yang tersimpan
if ($detail_count == 0 && (!empty($items) || !empty($kd_brg_array))) {
  mysqli_close($connect);
  echo json_encode(array('status' => 'error', 'message' => 'Gagal menyimpan detail barang!'));
  exit;
}

// Pastikan koneksi ditutup dengan benar
mysqli_close($connect);

echo json_encode(array('status' => 'success', 'message' => 'Data promo berhasil disimpan!'));
?>

