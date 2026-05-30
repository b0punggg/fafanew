<?php
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

// Clean any output before JSON
ob_clean();

$kd_toko = $_SESSION['id_toko'];
$kd_brg = isset($_POST['kd_brg']) ? mysqli_real_escape_string($connect, $_POST['kd_brg']) : '';
$bulan = isset($_POST['bulan']) ? mysqli_real_escape_string($connect, $_POST['bulan']) : '';
$tahun = isset($_POST['tahun']) ? mysqli_real_escape_string($connect, $_POST['tahun']) : '';
$stok_juals = isset($_POST['stok_juals']) ? floatval($_POST['stok_juals']) : 0;
$hrg_beli = isset($_POST['hrg_beli']) ? floatval($_POST['hrg_beli']) : 0;
$nilai_persediaan = $stok_juals * $hrg_beli;

$connect = opendtcek();

$update = mysqli_query($connect, "UPDATE persediaan_bulan SET 
  stok_juals='$stok_juals',
  hrg_beli='$hrg_beli',
  nilai_persediaan='$nilai_persediaan'
  WHERE kd_brg='$kd_brg' AND bulan='$bulan' AND tahun='$tahun' AND kd_toko='$kd_toko'");

mysqli_close($connect);

// Clean output buffer and set JSON header
ob_clean();
if (!headers_sent()) {
  header('Content-Type: application/json; charset=UTF-8');
}

if($update){
  echo json_encode(array('success' => true, 'message' => 'Data berhasil diupdate'));
} else {
  echo json_encode(array('success' => false, 'message' => 'Gagal update data'));
}
ob_end_flush();
exit;
?>

