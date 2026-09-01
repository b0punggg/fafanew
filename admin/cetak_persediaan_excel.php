<?php
// =====================================
// EXPORT EXCEL PERSEDIAAN BULANAN
// Sumber: beli_brg (sama dengan view). Mode: semua data atau per bulan/tahun.
// =====================================

ob_start();
session_start();
include 'cekmasuk.php';
include 'config.php';
include 'f_cetak_jual_item_helper.php';

$is_admin = (isset($_SESSION['kodepemakai']) && $_SESSION['kodepemakai'] == '2');

$connect = opendtcek();
if(!$connect){
  exit("Koneksi database gagal");
}

$kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
if($kd_toko == ''){
  exit("Session toko tidak ditemukan");
}

$semua = isset($_GET['semua']) ? (int)$_GET['semua'] : 0;
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$stok  = isset($_GET['stok']) ? (int)$_GET['stok'] : 0;
$brand_filter = sanitizeReportBrandFilter($connect, isset($_GET['brand']) ? $_GET['brand'] : '');
$brand_sql = ($brand_filter === '') ? '' : " AND UPPER(m.nm_brg) LIKE UPPER('%$brand_filter%') ";
$brand_text = ($brand_filter === '') ? 'SEMUA' : $brand_filter;

if($semua != 1 && ($bulan=='' || $tahun=='')){
  exit("Parameter bulan/tahun tidak lengkap. Atau gunakan opsi Cetak semua data.");
}

if(strlen($bulan) == 1 && $bulan !== ''){
  $bulan = '0'.$bulan;
}

$keluar_date_sql = ($semua != 1 && $bulan !== '' && $tahun !== '')
  ? " AND MONTH(dum_jual.tgl_jual)='$bulan' AND YEAR(dum_jual.tgl_jual)='$tahun'"
  : '';
$sql_from_keluar = "(
  SELECT dum_jual.kd_brg, SUM(dum_jual.qty_brg) AS qty_keluar
  FROM dum_jual
  WHERE dum_jual.kd_toko='$kd_toko'
    AND (dum_jual.ket IS NULL OR dum_jual.ket <> 'RETUR BARANG')
    $keluar_date_sql
  GROUP BY dum_jual.kd_brg
) klr";

$nama_bulan = array(
  "01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April",
  "05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus",
  "09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember"
);

$filename = ($semua == 1) ? "Persediaan_Semua.xls" : "Persediaan_".$nama_bulan[$bulan]."_".$tahun.".xls";

ob_clean();
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);
header("Pragma: no-cache");
header("Expires: 0");

// ==============================
// QUERY DATA
// ==============================
if($semua == 1){
  if($stok == 1){
    $tampil_stok = '';
    $having_clause = "HAVING stok_juals >= 0";
  } else {
    $tampil_stok = " AND beli_brg.stok_jual > 0 ";
    $having_clause = "HAVING stok_juals > 0";
  }
  $sql = "
  SELECT
    sub.kd_brg,
    m.nm_brg,
    sub.kd_sup,
    sub.id_bag,
    s.nm_sup,
    b.nm_bag,
    sub.stok_juals,
    sub.hrg_beli,
    (sub.stok_juals * sub.hrg_beli) AS nilai_persediaan,
    COALESCE(klr.qty_keluar, 0) AS qty_keluar
  FROM (
    SELECT 
      beli_brg.kd_brg,
      beli_brg.kd_sup,
      SUM(beli_brg.stok_jual) AS stok_juals,
      AVG(beli_brg.hrg_beli) AS hrg_beli,
      MAX(beli_brg.id_bag) AS id_bag
    FROM beli_brg
    WHERE beli_brg.kd_toko='$kd_toko' $tampil_stok
    GROUP BY beli_brg.kd_brg
    $having_clause
  ) AS sub
  LEFT JOIN mas_brg m ON sub.kd_brg = m.kd_brg AND m.kd_toko = '$kd_toko'
  LEFT JOIN supplier s ON sub.kd_sup = s.kd_sup
  LEFT JOIN bag_brg b ON sub.id_bag = b.no_urut
  LEFT JOIN $sql_from_keluar ON klr.kd_brg = sub.kd_brg
  WHERE 1=1 $brand_sql
  ORDER BY COALESCE(m.nm_brg, sub.kd_brg) ASC";
} else {
  if($stok == 1){
    $where_beli = "beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual >= 0 AND MONTH(beli_brg.tgl_fak)='$bulan' AND YEAR(beli_brg.tgl_fak)='$tahun'";
    $having_clause = "HAVING stok_juals >= 0";
  } else {
    $where_beli = "beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual > 0 AND MONTH(beli_brg.tgl_fak)='$bulan' AND YEAR(beli_brg.tgl_fak)='$tahun'";
    $having_clause = "HAVING stok_juals > 0";
  }
  $sql = "
  SELECT
    sub.kd_brg,
    m.nm_brg,
    sub.kd_sup,
    sub.id_bag,
    s.nm_sup,
    b.nm_bag,
    sub.stok_juals,
    sub.hrg_beli,
    (sub.stok_juals * sub.hrg_beli) AS nilai_persediaan,
    COALESCE(klr.qty_keluar, 0) AS qty_keluar
  FROM (
    SELECT 
      beli_brg.kd_brg,
      beli_brg.kd_sup,
      SUM(beli_brg.stok_jual) AS stok_juals,
      AVG(beli_brg.hrg_beli) AS hrg_beli,
      MAX(beli_brg.id_bag) AS id_bag
    FROM beli_brg
    WHERE $where_beli
    GROUP BY beli_brg.kd_brg
    $having_clause
  ) AS sub
  LEFT JOIN mas_brg m ON sub.kd_brg = m.kd_brg AND m.kd_toko = '$kd_toko'
  LEFT JOIN supplier s ON sub.kd_sup = s.kd_sup
  LEFT JOIN bag_brg b ON sub.id_bag = b.no_urut
  LEFT JOIN $sql_from_keluar ON klr.kd_brg = sub.kd_brg
  WHERE 1=1 $brand_sql
  ORDER BY COALESCE(m.nm_brg, sub.kd_brg) ASC";
}

$q = mysqli_query($connect, $sql);
if(!$q){
  exit("Query gagal: ".mysqli_error($connect));
}

$colspan_brand = $is_admin ? 9 : 7;
echo "<table border='1'>
<tr><td colspan='{$colspan_brand}'><b>Brand: ".htmlspecialchars($brand_text)."</b></td></tr>
<tr style='background:#4CAF50;color:white;font-weight:bold'>
  <th>No</th>
  <th>Kode Barang</th>
  <th>Nama Barang</th>
  <th>Supplier</th>
  <th>Bagian</th>
  <th>Stok</th>
  <th>Jml. Keluar</th>";
if($is_admin){
  echo "
  <th>Harga Beli</th>
  <th>Nilai Persediaan</th>";
}
echo "
</tr>";

$no = 1;
$total = 0;

while($r = mysqli_fetch_assoc($q)){
  if($is_admin){
    $total += $r['nilai_persediaan'];
  }
  $nm_sup = isset($r['nm_sup']) ? htmlspecialchars($r['nm_sup']) : '';
  $nm_bag = isset($r['nm_bag']) ? htmlspecialchars($r['nm_bag']) : '';
  $nm_brg = isset($r['nm_brg']) ? htmlspecialchars($r['nm_brg']) : '';
  $qty_keluar = isset($r['qty_keluar']) ? $r['qty_keluar'] : 0;
  echo "<tr>
    <td>{$no}</td>
    <td>".htmlspecialchars($r['kd_brg'])."</td>
    <td>{$nm_brg}</td>
    <td>{$nm_sup}</td>
    <td>{$nm_bag}</td>
    <td align='right'>".number_format($r['stok_juals'],0,',','.')."</td>
    <td align='right'>".number_format($qty_keluar,0,',','.')."</td>";
  if($is_admin){
    echo "
    <td align='right'>".number_format($r['hrg_beli'],0,',','.')."</td>
    <td align='right'>".number_format($r['nilai_persediaan'],0,',','.')."</td>";
  }
  echo "
  </tr>";
  $no++;
}

if($is_admin){
  echo "<tr style='font-weight:bold'>
    <td colspan='8' align='right'>TOTAL</td>
    <td align='right'>".number_format($total,0,',','.')."</td>
  </tr>";
}
echo "</table>";

mysqli_close($connect);
exit;
