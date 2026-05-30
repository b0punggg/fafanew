<?php
session_start();
include 'config.php';

// File ini dapat diakses oleh semua user termasuk operator (otoritas 1) dan administrator (otoritas 2)
// Tidak ada pembatasan berdasarkan otoritas
$kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';

// Pastikan kd_toko ada
if (empty($kd_toko)) {
  echo json_encode(array('hasil' => '<tr><td colspan="6" style="text-align: center; padding: 20px;"><i class="fa fa-exclamation-triangle"></i> Session tidak valid. Silakan login kembali.</td></tr>'));
  exit;
}

$by_nama = isset($_POST['by_nama']) ? mysqli_real_escape_string(opendtcek(), $_POST['by_nama']) : '';
$disc_rupiah = isset($_POST['disc_rupiah']) ? floatval($_POST['disc_rupiah']) : 0;
$disc_persen = isset($_POST['disc_persen']) ? floatval($_POST['disc_persen']) : 0;

$connect = opendtcek();

$hasil = '';
$no = 1;

if (!empty($by_nama)) {
  // Cari barang berdasarkan nama yang mengandung kata kunci
  $query = "SELECT * FROM mas_brg 
            WHERE (nm_brg LIKE '%$by_nama%' OR kd_brg LIKE '%$by_nama%') 
            AND kd_toko='$kd_toko' 
            ORDER BY nm_brg ASC";
  
  $result = mysqli_query($connect, $query);
  
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $kd_brg = $row['kd_brg'];
      $nm_brg = $row['nm_brg'];
      $sku = isset($row['kd_bar']) && !empty($row['kd_bar']) ? $row['kd_bar'] : $kd_brg;
      
      // Gunakan disc dari form jika ada, jika tidak gunakan default
      $disc_rp = $disc_rupiah > 0 ? $disc_rupiah : 0;
      $disc_pr = $disc_persen > 0 ? $disc_persen : 0;
      
      $hasil .= '<tr id="row_' . $no . '">';
      $hasil .= '<td>' . $no . '</td>';
      $hasil .= '<td>' . htmlspecialchars($sku) . '</td>';
      $hasil .= '<td>' . htmlspecialchars($nm_brg) . '</td>';
      $hasil .= '<td>';
      $hasil .= '<input type="hidden" name="kd_brg[]" value="' . htmlspecialchars($kd_brg) . '">';
      $hasil .= '<input type="number" name="disc_rupiah_item[]" class="form-control" value="' . number_format($disc_rp, 2, '.', '') . '" min="0" step="0.01" style="width: 120px; text-align: right;">';
      $hasil .= '</td>';
      $hasil .= '<td>';
      $hasil .= '<input type="number" name="disc_persen_item[]" class="form-control" value="' . number_format($disc_pr, 2, '.', '') . '" min="0" max="100" step="0.01" style="width: 100px; text-align: right;">';
      $hasil .= '</td>';
      $hasil .= '<td>';
      $hasil .= '<button type="button" onclick="hapusBarang(' . $no . ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
      $hasil .= '</td>';
      $hasil .= '</tr>';
      
      $no++;
    }
  } else {
    $hasil = '<tr><td colspan="6" style="text-align: center; padding: 20px;"><i class="fa fa-exclamation-triangle"></i> Tidak ada barang ditemukan dengan nama "' . htmlspecialchars($by_nama) . '"</td></tr>';
  }
} else {
  $hasil = '<tr><td colspan="6" style="text-align: center; padding: 20px;"><i class="fa fa-info-circle"></i> Masukkan nama brand/kategori terlebih dahulu</td></tr>';
}

mysqli_close($connect);

echo json_encode(array('hasil' => $hasil));
?>

