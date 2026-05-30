<?php
session_start();
include 'config.php';

$kd_toko = $_SESSION['id_toko'];
$connect = opendtcek();

// Pastikan tabel ada
$create_table_rule = "CREATE TABLE IF NOT EXISTS disc_auto_rule (
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

mysqli_query($connect, $create_table_rule);

// Ambil semua rules yang aktif
$query_rules = "SELECT * FROM disc_auto_rule WHERE status=1 AND kd_toko='$kd_toko'";
$result_rules = mysqli_query($connect, $query_rules);

$total_updated = 0;
$total_rules = mysqli_num_rows($result_rules);

if ($total_rules > 0) {
  while ($rule = mysqli_fetch_assoc($result_rules)) {
    $kondisi = $rule['kondisi'];
    $nilai = $rule['nilai_kondisi'];
    $disc_rp = $rule['disc_rupiah'];
    $disc_pr = $rule['disc_persen'];
    
    // Build query berdasarkan kondisi
    $where_clause = "";
    
    switch($kondisi) {
      case 'nama_brg':
        $where_clause = "nm_brg LIKE '%$nilai%'";
        break;
      case 'kd_brg':
        $where_clause = "kd_brg LIKE '%$nilai%'";
        break;
      case 'harga_min':
        $where_clause = "hrg_jum1 >= " . floatval($nilai);
        break;
      case 'harga_max':
        $where_clause = "hrg_jum1 <= " . floatval($nilai);
        break;
      case 'stok_min':
        // Perlu join dengan beli_brg untuk cek stok
        $where_clause = "EXISTS (SELECT 1 FROM beli_brg bb WHERE bb.kd_brg = mas_brg.kd_brg AND bb.kd_toko = mas_brg.kd_toko HAVING SUM(bb.stok_jual) >= " . intval($nilai) . ")";
        break;
      case 'stok_max':
        $where_clause = "EXISTS (SELECT 1 FROM beli_brg bb WHERE bb.kd_brg = mas_brg.kd_brg AND bb.kd_toko = mas_brg.kd_toko HAVING SUM(bb.stok_jual) <= " . intval($nilai) . ")";
        break;
    }
    
    if (!empty($where_clause)) {
      // Update disctetap untuk barang yang sesuai
      // Hapus discount lama jika ada
      $query_delete = "DELETE FROM disctetap WHERE kd_brg IN (
        SELECT kd_brg FROM mas_brg WHERE $where_clause AND kd_toko='$kd_toko'
      ) AND kd_toko='$kd_toko'";
      mysqli_query($connect, $query_delete);
      
      // Insert discount baru
      // Untuk discount otomatis, kita akan update ke tabel disctetap
      // Tapi karena disctetap butuh kd_sat, kita perlu loop per barang
      $query_barang = "SELECT kd_brg, kd_kem1 FROM mas_brg WHERE $where_clause AND kd_toko='$kd_toko'";
      $result_barang = mysqli_query($connect, $query_barang);
      
      while ($barang = mysqli_fetch_assoc($result_barang)) {
        $kd_brg = $barang['kd_brg'];
        $kd_sat = $barang['kd_kem1'];
        
        // Hitung harga setelah discount
        $query_harga = "SELECT hrg_jum1 FROM mas_brg WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko'";
        $result_harga = mysqli_query($connect, $query_harga);
        if ($row_harga = mysqli_fetch_assoc($result_harga)) {
          $hrg_jual = $row_harga['hrg_jum1'];
          $hrg_disc = $hrg_jual;
          
          // Apply percentage discount
          if ($disc_pr > 0) {
            $hrg_disc = $hrg_jual * (1 - $disc_pr / 100);
          }
          
          // Apply rupiah discount
          if ($disc_rp > 0) {
            $hrg_disc = $hrg_disc - $disc_rp;
          }
          
          // Pastikan tidak negatif
          if ($hrg_disc < 0) $hrg_disc = 0;
          
          // Insert ke disctetap
          $insert_disc = "INSERT INTO disctetap (kd_brg, kd_sat, hrg_jual, lim_jual, kd_toko) 
                          VALUES ('$kd_brg', '$kd_sat', '$hrg_disc', 1, '$kd_toko')
                          ON DUPLICATE KEY UPDATE hrg_jual='$hrg_disc'";
          mysqli_query($connect, $insert_disc);
          $total_updated++;
        }
      }
    }
  }
  
  echo json_encode(array(
    'status' => 'success', 
    'message' => "Discount otomatis berhasil diterapkan! Total $total_updated barang diupdate dari $total_rules rules aktif."
  ));
} else {
  echo json_encode(array('status' => 'info', 'message' => 'Tidak ada rules aktif untuk diterapkan.'));
}

mysqli_close($connect);
?>

