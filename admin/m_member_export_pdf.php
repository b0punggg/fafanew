<?php
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
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Data Member</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }
    h3, h4 { margin: 0; padding: 0; }
    .header { text-align: center; margin-bottom: 10px; }
    .sub { margin-top: 4px; margin-bottom: 10px; text-align: left; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #333; padding: 4px; }
    th { background: #f2f2f2; text-align: center; }
    .right { text-align: right; }
    .center { text-align: center; }
    @media print {
      #printPageButton { display: none; }
    }
  </style>
</head>
<body>
  <div class="header">
    <h3>Data Member</h3>
    <h4><?php echo htmlspecialchars(isset($_SESSION['nm_toko']) ? $_SESSION['nm_toko'] : ''); ?></h4>
  </div>
  <div class="sub">
    Filter nama member: <b><?php echo htmlspecialchars($keyword === '' ? 'Semua' : $keyword); ?></b>
  </div>
  <table>
    <thead>
      <tr>
        <th style="width:5%;">No.</th>
        <th style="width:12%;">ID Member</th>
        <th style="width:20%;">Nama Member</th>
        <th style="width:16%;">Nama Toko</th>
        <th>Alamat</th>
        <th style="width:12%;">No. Telp/HP</th>
        <th style="width:10%;">Tgl Daftar</th>
        <th style="width:8%;">Poin</th>
      </tr>
    </thead>
    <tbody>
      <?php
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
      ?>
      <tr>
        <td class="right"><?php echo $no; ?></td>
        <td><?php echo htmlspecialchars(isset($data['kd_member']) ? $data['kd_member'] : ''); ?></td>
        <td><?php echo htmlspecialchars($nm_member); ?></td>
        <td><?php echo htmlspecialchars(isset($data['nm_toko']) ? $data['nm_toko'] : '-'); ?></td>
        <td><?php echo htmlspecialchars(isset($data['al_member']) ? $data['al_member'] : ''); ?></td>
        <td><?php echo htmlspecialchars(isset($data['no_telp']) ? $data['no_telp'] : ''); ?></td>
        <td class="center"><?php echo $tgl_daftar; ?></td>
        <td class="right"><?php echo number_format($poin, 0, ',', '.'); ?></td>
      </tr>
      <?php
        $no++;
      }
      if($no === 1){
      ?>
      <tr>
        <td colspan="8" class="center">Tidak ada data member.</td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <div style="text-align:center; margin-top:16px;">
    <button id="printPageButton" onclick="window.print();">Cetak / Simpan sebagai PDF</button>
  </div>
</body>
</html>
<?php
mysqli_close($connect);
?>
