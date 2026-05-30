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

$connect = opendtcek();

$data = mysqli_query($connect, "SELECT * FROM persediaan_bulan 
  WHERE kd_brg='$kd_brg' AND bulan='$bulan' AND tahun='$tahun' AND kd_toko='$kd_toko'");

// Clean output buffer and set JSON header
ob_clean();
if (!headers_sent()) {
  header('Content-Type: application/json; charset=UTF-8');
}

if($data && mysqli_num_rows($data) > 0){
  $row = mysqli_fetch_assoc($data);
  echo json_encode(array('success' => true, 'data' => $row));
} else {
  echo json_encode(array('success' => false, 'message' => 'Data tidak ditemukan'));
}

mysqli_close($connect);
ob_end_flush();
exit;
?>

