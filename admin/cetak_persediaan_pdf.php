<?php
// =====================================
// CETAK PERSEDIAAN BULANAN (HALAMAN PDF)
// Sumber data: transaksi beli_brg (sama dengan view)
// =====================================

session_start();
include 'cekmasuk.php';
include 'config.php';

// ==============================
// KONEKSI
// ==============================
$connect = opendtcek();
if(!$connect){
  exit("Koneksi database gagal");
}

// ==============================
// SESSION TOKO
// ==============================
$kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
if($kd_toko == ''){
  exit("Session toko tidak ditemukan");
}

// Ambil info toko
$nm_toko = '';
$al_toko = '';
$qtoko   = mysqli_query($connect, "SELECT nm_toko, al_toko FROM toko WHERE kd_toko='$kd_toko' LIMIT 1");
if($qtoko && mysqli_num_rows($qtoko) > 0){
  $rtoko   = mysqli_fetch_assoc($qtoko);
  $nm_toko = $rtoko['nm_toko'];
  $al_toko = $rtoko['al_toko'];
}
unset($qtoko, $rtoko);

// ==============================
// PARAMETER
// ==============================
$semua = isset($_GET['semua']) ? (int)$_GET['semua'] : 0;
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$stok  = isset($_GET['stok']) ? (int)$_GET['stok'] : 0; // 1 = sertakan stok kosong

if($semua != 1 && ($bulan=='' || $tahun=='')){
  exit("Parameter bulan/tahun tidak lengkap. Atau gunakan opsi Cetak semua data.");
}

if(strlen($bulan) == 1 && $bulan !== ''){
  $bulan = '0'.$bulan;
}

// ==============================
// NAMA BULAN
// ==============================
$nama_bulan = array(
  "01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April",
  "05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus",
  "09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember"
);

// ==============================
// QUERY DATA
// ==============================
if($semua == 1){
  // Data keseluruhan (tanpa filter bulan/tahun)
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
    (sub.stok_juals * sub.hrg_beli) AS nilai_persediaan
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
  ORDER BY COALESCE(m.nm_brg, sub.kd_brg) ASC";
} else {
  // Filter per bulan/tahun
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
    (sub.stok_juals * sub.hrg_beli) AS nilai_persediaan
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
  ORDER BY COALESCE(m.nm_brg, sub.kd_brg) ASC";
}

$q = mysqli_query($connect, $sql);
if(!$q){
  exit("Query gagal: ".mysqli_error($connect));
}

// ==============================
// OUTPUT HALAMAN CETAK
// ==============================
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cetak Persediaan Bulanan</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
  <style>
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #333; padding: 4px; }
    th { background: #f0f0f0; text-align: center; }
    .no-border { border: none !important; }
    @media print {
      #printPageButton { display: none; }
    }
  </style>
</head>
<body>
  <div class="w3-container">
    <div class="w3-center">
      <h3 style="margin:0; padding:0;"><?php echo htmlspecialchars($nm_toko); ?></h3>
      <div style="margin-bottom:8px;"><?php echo htmlspecialchars($al_toko); ?></div>
      <h4 style="margin:0; padding:4px 0;">
        <?php 
        if($semua == 1){ 
          echo "Laporan Persediaan Barang (Semua)"; 
        } else { 
          echo "Laporan Persediaan Barang Bulan ".$nama_bulan[$bulan].' '.$tahun; 
        } 
        ?>
      </h4>
    </div>

    <table>
      <thead>
        <tr>
          <th style="width:5%;">No</th>
          <th style="width:12%;">Kode Barang</th>
          <th>Nama Barang</th>
          <th style="width:15%;">Supplier</th>
          <th style="width:15%;">Bagian</th>
          <th style="width:8%;">Stok</th>
          <th style="width:12%;">Harga Beli</th>
          <th style="width:15%;">Nilai Persediaan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no    = 1;
        $total = 0;
        if(mysqli_num_rows($q) == 0){
          ?>
          <tr>
            <td colspan="8" class="no-border" style="text-align:center;padding:12px;">
              <?php echo $semua == 1 ? "Tidak ada data persediaan." : "Tidak ada data persediaan untuk periode ini."; ?>
            </td>
          </tr>
          <?php
        } else {
          while($r = mysqli_fetch_assoc($q)){
            $total += $r['nilai_persediaan'];
            ?>
            <tr>
              <td style="text-align:right;"><?php echo $no++; ?></td>
              <td><?php echo htmlspecialchars($r['kd_brg']); ?></td>
              <td><?php echo htmlspecialchars($r['nm_brg']); ?></td>
              <td><?php echo htmlspecialchars($r['nm_sup']); ?></td>
              <td><?php echo htmlspecialchars($r['nm_bag']); ?></td>
              <td style="text-align:right;">
                <?php echo number_format($r['stok_juals'],0,',','.'); ?>
              </td>
              <td style="text-align:right;">
                <?php echo number_format($r['hrg_beli'],0,',','.'); ?>
              </td>
              <td style="text-align:right;">
                <?php echo number_format($r['nilai_persediaan'],0,',','.'); ?>
              </td>
            </tr>
            <?php
          }
          ?>
          <tr>
            <td colspan="7" style="text-align:right;font-weight:bold;">TOTAL</td>
            <td style="text-align:right;font-weight:bold;">
              <?php echo number_format($total,0,',','.'); ?>
            </td>
          </tr>
          <?php
        }
        ?>
      </tbody>
    </table>

    <div class="w3-center" style="margin-top:16px;">
      <button id="printPageButton" class="w3-button w3-green" onclick="window.print();">
        Cetak / Simpan sebagai PDF
      </button>
    </div>
  </div>
</body>
</html>
<?php
mysqli_close($connect);
?>

