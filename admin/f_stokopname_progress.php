<?php
include 'config.php';
if (!session_id()) {
  session_start();
}
header('Content-Type: application/json; charset=UTF-8');

$connect = opendtcek();
$kd_toko = '';
if ($connect) {
  $kd_toko = isset($_SESSION['id_toko']) ? mysqli_real_escape_string($connect, $_SESSION['id_toko']) : '';
}

$tgl_awal = isset($_POST['tgl_awal']) ? trim($_POST['tgl_awal']) : '';
$tgl_akhir = isset($_POST['tgl_akhir']) ? trim($_POST['tgl_akhir']) : '';
$filter_stok = isset($_POST['filter_stok']) ? $_POST['filter_stok'] : 'semua';

$filter_labels = array(
  'semua' => 'Semua barang',
  'ada_stok' => 'Hanya ada stok',
  'stok_nol' => 'Hanya stok 0',
);
if (!isset($filter_labels[$filter_stok])) {
  $filter_stok = 'semua';
}
$filter_label = $filter_labels[$filter_stok];

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_awal) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_akhir)) {
  echo json_encode(array('ok' => false, 'msg' => 'Format tanggal tidak valid.'));
  if ($connect) {
    mysqli_close($connect);
  }
  exit;
}

if ($tgl_awal > $tgl_akhir) {
  echo json_encode(array('ok' => false, 'msg' => 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.'));
  if ($connect) {
    mysqli_close($connect);
  }
  exit;
}

$total = 0;
$sudah = 0;

if (!$connect || $kd_toko === '') {
  echo json_encode(array(
    'ok' => false,
    'msg' => 'Koneksi atau data toko tidak tersedia.',
  ));
  exit;
}

$tgl_awal_esc = mysqli_real_escape_string($connect, $tgl_awal);
$tgl_akhir_esc = mysqli_real_escape_string($connect, $tgl_akhir);

$having_sql = '';
if ($filter_stok === 'ada_stok') {
  $having_sql = ' HAVING stok_juals > 0';
} elseif ($filter_stok === 'stok_nol') {
  $having_sql = ' HAVING stok_juals <= 0';
}

$sub_brg = "SELECT kd_brg, SUM(stok_jual) AS stok_juals
  FROM beli_brg
  WHERE kd_toko='$kd_toko'
  GROUP BY kd_brg
  $having_sql";

$q_total = mysqli_query(
  $connect,
  "SELECT COUNT(*) AS jumlah FROM ($sub_brg) t"
);
if ($q_total) {
  $row_total = mysqli_fetch_assoc($q_total);
  $total = isset($row_total['jumlah']) ? (int) $row_total['jumlah'] : 0;
  mysqli_free_result($q_total);
}

$q_sudah = mysqli_query(
  $connect,
  "SELECT COUNT(DISTINCT m.kd_brg) AS jumlah
   FROM mutasi_adj m
   INNER JOIN ($sub_brg) b ON b.kd_brg = m.kd_brg
   WHERE m.kd_toko='$kd_toko'
   AND m.tgl_input >= '$tgl_awal_esc'
   AND m.tgl_input <= '$tgl_akhir_esc'
   AND INSTR(UPPER(m.ket), 'PENYESUAIAN') > 0
   AND INSTR(UPPER(m.ket), 'LINE') = 0"
);
if ($q_sudah) {
  $row_sudah = mysqli_fetch_assoc($q_sudah);
  $sudah = isset($row_sudah['jumlah']) ? (int) $row_sudah['jumlah'] : 0;
  mysqli_free_result($q_sudah);
}

if ($sudah > $total) {
  $sudah = $total;
}
$belum = max(0, $total - $sudah);
$persen = $total > 0 ? round(($sudah / $total) * 100, 1) : 0;

mysqli_close($connect);

echo json_encode(array(
  'ok' => true,
  'total' => $total,
  'sudah' => $sudah,
  'belum' => $belum,
  'persen' => $persen,
  'filter_stok' => $filter_stok,
  'filter_label' => $filter_label,
  'tgl_awal' => $tgl_awal,
  'tgl_akhir' => $tgl_akhir,
  'tgl_awal_txt' => gantitgl($tgl_awal),
  'tgl_akhir_txt' => gantitgl($tgl_akhir),
));
