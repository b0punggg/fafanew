<?php
session_start();
include 'config.php';

$kd_toko = $_SESSION['id_toko'];
$connect = opendtcek();

$hasil = '';
$no = 1;

// Pastikan tabel ada
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

$query = "SELECT * FROM disc_auto_rule WHERE kd_toko='$kd_toko' ORDER BY created_at DESC";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $status_badge = $row['status'] == 1 ? 
      '<span class="badge badge-success">Aktif</span>' : 
      '<span class="badge badge-secondary">Nonaktif</span>';
    
    $status_btn = $row['status'] == 1 ? 
      '<button onclick="toggleStatus(' . $row['no_urut'] . ', 1)" class="btn btn-warning btn-sm"><i class="fa fa-toggle-on"></i></button>' :
      '<button onclick="toggleStatus(' . $row['no_urut'] . ', 0)" class="btn btn-secondary btn-sm"><i class="fa fa-toggle-off"></i></button>';
    
    $kondisi_label = '';
    switch($row['kondisi']) {
      case 'nama_brg': $kondisi_label = 'Nama Barang Mengandung'; break;
      case 'kd_brg': $kondisi_label = 'Kode Barang Mengandung'; break;
      case 'harga_min': $kondisi_label = 'Harga Jual >='; break;
      case 'harga_max': $kondisi_label = 'Harga Jual <='; break;
      case 'stok_min': $kondisi_label = 'Stok >='; break;
      case 'stok_max': $kondisi_label = 'Stok <='; break;
      default: $kondisi_label = $row['kondisi'];
    }
    
    $hasil .= '<tr>';
    $hasil .= '<td>' . $no . '</td>';
    $hasil .= '<td>' . htmlspecialchars($row['nama_rule']) . '</td>';
    $hasil .= '<td>' . htmlspecialchars($kondisi_label) . '</td>';
    $hasil .= '<td>' . htmlspecialchars($row['nilai_kondisi']) . '</td>';
    $hasil .= '<td style="text-align: right;">' . number_format($row['disc_rupiah'], 2, ',', '.') . '</td>';
    $hasil .= '<td style="text-align: right;">' . number_format($row['disc_persen'], 2, ',', '.') . '%</td>';
    $hasil .= '<td>' . $status_badge . '</td>';
    $hasil .= '<td>';
    $hasil .= $status_btn . ' ';
    $hasil .= '<button onclick="hapusRule(' . $row['no_urut'] . ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
    $hasil .= '</td>';
    $hasil .= '</tr>';
    
    $no++;
  }
} else {
  $hasil = '<tr><td colspan="8" style="text-align: center; padding: 20px;"><i class="fa fa-info-circle"></i> Belum ada rule discount</td></tr>';
}

mysqli_close($connect);

echo json_encode(array('hasil' => $hasil));
?>

