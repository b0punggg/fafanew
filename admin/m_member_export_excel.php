<?php
ob_start();
if(!session_id()) session_start();
include 'cekmasuk.php';
include 'config.php';

$connect = opendtcek();
if(!$connect){
  exit("Koneksi database gagal");
}

$kd_toko = isset($_SESSION['id_toko']) ? mysqli_real_escape_string($connect, $_SESSION['id_toko']) : '';
$nm_toko_sesi = isset($_SESSION['nm_toko']) ? mysqli_real_escape_string($connect, $_SESSION['nm_toko']) : '';
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

if($kd_toko === ''){
  exit("Session toko tidak ditemukan");
}

$kolom = array();
$cek_kolom = mysqli_query($connect, "SHOW COLUMNS FROM member");
if($cek_kolom){
  while($row_kolom = mysqli_fetch_assoc($cek_kolom)){
    $kolom[$row_kolom['Field']] = true;
  }
  mysqli_free_result($cek_kolom);
}

$filter_where = array();
if(isset($kolom['kd_toko'])){
  if(isset($kolom['nm_toko']) && $nm_toko_sesi !== ''){
    $filter_where[] = "(kd_toko='$kd_toko' OR ((kd_toko='' OR kd_toko IS NULL) AND UPPER(TRIM(nm_toko))=UPPER(TRIM('$nm_toko_sesi'))))";
  } else {
    $filter_where[] = "kd_toko='$kd_toko'";
  }
} else if(isset($kolom['id_toko'])){
  $filter_where[] = "id_toko='$kd_toko'";
} else {
  $filter_where[] = "1=0";
}

if($keyword !== ''){
  $keyword_esc = mysqli_real_escape_string($connect, $keyword);
  $filter_where[] = "nm_member LIKE '%$keyword_esc%'";
}

$where_sql = '';
if(count($filter_where) > 0){
  $where_sql = " WHERE ".implode(" AND ", $filter_where);
}

$q = mysqli_query($connect, "SELECT * FROM member $where_sql ORDER BY nm_member ASC");
if(!$q){
  exit("Query member gagal: ".mysqli_error($connect));
}

$filename = "Data_Member";
if($keyword !== ''){
  $filename .= "_".preg_replace('/[^A-Za-z0-9_\-]/', '_', $keyword);
}
$filename .= "_".date('Ymd_His').".xls";

ob_clean();
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr><th colspan='8' style='font-size:14pt;'>Data Member</th></tr>";
echo "<tr><th colspan='8' style='text-align:left;'>Filter nama member: ".htmlspecialchars($keyword === '' ? 'Semua' : $keyword)."</th></tr>";
echo "<tr style='background:#f2f2f2;font-weight:bold;'>
  <th>No.</th>
  <th>ID Member</th>
  <th>Nama Member</th>
  <th>Nama Toko</th>
  <th>Alamat</th>
  <th>No. Telp/HP</th>
  <th>Tgl Daftar</th>
  <th>Poin</th>
</tr>";

$no = 1;
while($data = mysqli_fetch_assoc($q)){
  $nm_member = isset($data['nm_member']) ? trim($data['nm_member']) : '';
  if($nm_member === '-NONE-'){
    continue;
  }
  $tgl_daftar = '-';
  if(isset($data['tgl_daftar']) && $data['tgl_daftar'] !== '' && $data['tgl_daftar'] !== '0000-00-00'){
    $tgl_daftar = date('d-m-Y', strtotime($data['tgl_daftar']));
  }
  $poin = isset($data['poin']) ? floatval($data['poin']) : 0;
  echo "<tr>
    <td align='right'>".$no."</td>
    <td>".htmlspecialchars(isset($data['kd_member']) ? $data['kd_member'] : '')."</td>
    <td>".htmlspecialchars($nm_member)."</td>
    <td>".htmlspecialchars(isset($data['nm_toko']) ? $data['nm_toko'] : '-')."</td>
    <td>".htmlspecialchars(isset($data['al_member']) ? $data['al_member'] : '')."</td>
    <td>".htmlspecialchars(isset($data['no_telp']) ? $data['no_telp'] : '')."</td>
    <td align='center'>".$tgl_daftar."</td>
    <td align='right'>".number_format($poin, 0, ',', '.')."</td>
  </tr>";
  $no++;
}

if($no === 1){
  echo "<tr><td colspan='8' align='center'>Tidak ada data member.</td></tr>";
}

echo "</table>";

mysqli_close($connect);
exit;
?>
