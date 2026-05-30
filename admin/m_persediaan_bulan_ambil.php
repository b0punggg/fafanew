<?php
// Increase execution time for large datasets
set_time_limit(300); // Increase to 300 seconds (5 minutes) for bulk insert
ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M'); // Increase memory limit

// Prevent output before JSON
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

// Start session only if not already started
if(!session_id()){
  session_start();
}
include 'config.php';
include 'cekmasuk.php';

$kd_toko = $_SESSION['id_toko'];
$connect = opendtcek();

// Clean any output before JSON
ob_clean();

$bulan = isset($_POST['bulan']) ? mysqli_real_escape_string($connect, $_POST['bulan']) : date('m');
$tahun = isset($_POST['tahun']) ? mysqli_real_escape_string($connect, $_POST['tahun']) : date('Y');

// Pastikan bulan dalam format 2 digit (01-12)
if(strlen($bulan) == 1){
  $bulan = '0' . $bulan;
}

// Pastikan tabel persediaan_bulan ada
$create_table = "CREATE TABLE IF NOT EXISTS `persediaan_bulan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_brg` varchar(50) NOT NULL,
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `stok_juals` decimal(15,2) DEFAULT 0.00,
  `hrg_beli` decimal(15,2) DEFAULT 0.00,
  `nilai_persediaan` decimal(15,2) DEFAULT 0.00,
  `kd_sup` varchar(50) DEFAULT NULL,
  `id_bag` int(11) DEFAULT NULL,
  `kd_toko` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_persediaan` (`kd_brg`, `bulan`, `tahun`, `kd_toko`),
  KEY `idx_kd_brg` (`kd_brg`),
  KEY `idx_bulan_tahun` (`bulan`, `tahun`),
  KEY `idx_kd_toko` (`kd_toko`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

mysqli_query($connect, $create_table);

// Query sama seperti f_cetak_pilih_stok.php (menggunakan struktur yang sama)
// Optimize: Filter stok_jual > 0 di WHERE clause untuk mengurangi data yang di-GROUP BY
$query = "SELECT beli_brg.kd_sup, beli_brg.kd_brg, SUM(beli_brg.stok_jual) AS stok_juals, 
          AVG(beli_brg.hrg_beli) AS hrg_beli, MAX(beli_brg.id_bag) AS id_bag
          FROM beli_brg
          LEFT JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg AND mas_brg.kd_toko = beli_brg.kd_toko
          WHERE beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual > 0
          GROUP BY beli_brg.kd_brg
          ORDER BY COALESCE(mas_brg.nm_brg, beli_brg.kd_brg) ASC";

$datain = mysqli_query($connect, $query);

if(!$datain){
  echo json_encode(array('success' => false, 'message' => 'Error: ' . mysqli_error($connect)));
  mysqli_close($connect);
  exit;
}

// Disable autocommit untuk performa lebih baik
mysqli_autocommit($connect, false);

$inserted = 0;
$updated = 0;
$batch_size = 200; // Reduce batch size untuk menghindari timeout
$insert_values = array();
$total_processed = 0;

// Process langsung tanpa collect semua data dulu (untuk menghemat memory)
$batch_count = 0;
while($data = mysqli_fetch_assoc($datain)){
  $kd_brg = mysqli_real_escape_string($connect, $data['kd_brg']);
  $stok_juals = floatval($data['stok_juals']);
  $hrg_beli = floatval($data['hrg_beli']);
  $nilai_persediaan = $stok_juals * $hrg_beli;
  $kd_sup = mysqli_real_escape_string($connect, $data['kd_sup']);
  $id_bag = intval($data['id_bag']);
  
  // Store for batch processing
  $insert_values[] = "('$kd_brg', '$bulan', '$tahun', '$stok_juals', '$hrg_beli', '$nilai_persediaan', '$kd_sup', '$id_bag', '$kd_toko')";
  $batch_count++;
  
  // Process setiap batch_size records
  if($batch_count >= $batch_size){
    $values_str = implode(',', $insert_values);
    $sql_batch = "INSERT INTO persediaan_bulan 
      (kd_brg, bulan, tahun, stok_juals, hrg_beli, nilai_persediaan, kd_sup, id_bag, kd_toko) 
      VALUES $values_str
      ON DUPLICATE KEY UPDATE 
      stok_juals=VALUES(stok_juals),
      hrg_beli=VALUES(hrg_beli),
      nilai_persediaan=VALUES(nilai_persediaan),
      kd_sup=VALUES(kd_sup),
      id_bag=VALUES(id_bag)";
    
    $result = mysqli_query($connect, $sql_batch);
    if($result){
      $affected = mysqli_affected_rows($connect);
      // ON DUPLICATE KEY UPDATE: affected_rows = 2 untuk update, 1 untuk insert
      // Tapi tidak bisa dibedakan dengan pasti, jadi kita hitung semua sebagai inserted
      $inserted += $batch_count;
      mysqli_commit($connect);
    } else {
      mysqli_rollback($connect);
    }
    
    // Reset batch
    $insert_values = array();
    $batch_count = 0;
    $total_processed += $batch_size;
    
    // Flush output buffer setiap batch
    if(ob_get_level() > 0){
      ob_flush();
      flush();
    }
  }
}

// Process sisa data yang belum di-batch
if($batch_count > 0){
  $values_str = implode(',', $insert_values);
  $sql_batch = "INSERT INTO persediaan_bulan 
    (kd_brg, bulan, tahun, stok_juals, hrg_beli, nilai_persediaan, kd_sup, id_bag, kd_toko) 
    VALUES $values_str
    ON DUPLICATE KEY UPDATE 
    stok_juals=VALUES(stok_juals),
    hrg_beli=VALUES(hrg_beli),
    nilai_persediaan=VALUES(nilai_persediaan),
    kd_sup=VALUES(kd_sup),
    id_bag=VALUES(id_bag)";
  
  $result = mysqli_query($connect, $sql_batch);
  if($result){
    $inserted += $batch_count;
    mysqli_commit($connect);
  } else {
    mysqli_rollback($connect);
  }
}

// Re-enable autocommit
mysqli_autocommit($connect, true);

mysqli_close($connect);

// Clean output buffer and set JSON header
ob_clean();
if (!headers_sent()) {
  header('Content-Type: application/json; charset=UTF-8');
}

// Hitung total records yang berhasil diproses
$total_records = $inserted; // Karena kita hitung semua sebagai inserted (ON DUPLICATE KEY UPDATE)

echo json_encode(array(
  'success' => true, 
  'message' => "Data berhasil diambil: $total_records records diproses"
));
ob_end_flush();
exit;
?>

