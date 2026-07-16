<?php
session_start();
include 'config.php';
$connect = opendtcek();
$kd_toko = $_SESSION['id_toko'];
$tglhi = isset($_SESSION['tgl_set']) ? $_SESSION['tgl_set'] : date('Y-m-d');
$xtgl = explode('-', $tglhi);

$endbln = isset($_POST['bulan']) ? (int)$_POST['bulan'] : (int)$xtgl[1];
$endyear = isset($_POST['tahun']) ? (int)$_POST['tahun'] : (int)$xtgl[0];
if ($endbln < 1 || $endbln > 12) {
  $endbln = (int)$xtgl[1];
}
if ($endyear < 2000 || $endyear > 2100) {
  $endyear = (int)$xtgl[0];
}

$namabulan = array(
  1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
  7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'Nopember',12=>'Desember'
);
$label = $namabulan[$endbln] . ' ' . $endyear;

$cek = mysqli_query($connect, "SELECT beli_brg.kd_brg, MAX(mas_brg.nm_brg) AS nm_brg, SUM(beli_brg.stok_jual) AS stok
  FROM beli_brg
  LEFT JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg AND mas_brg.kd_toko = beli_brg.kd_toko
  WHERE beli_brg.kd_toko='$kd_toko'
    AND INSTR(IFNULL(mas_brg.nm_brg,''),'JASA')=0
    AND beli_brg.kd_brg NOT IN (
      SELECT DISTINCT dum_jual.kd_brg FROM dum_jual
      WHERE dum_jual.kd_toko='$kd_toko'
        AND MONTH(dum_jual.tgl_jual)='$endbln'
        AND YEAR(dum_jual.tgl_jual)='$endyear'
        AND INSTR(dum_jual.nm_brg,'JASA')=0
        AND dum_jual.kd_brg IS NOT NULL
    )
  GROUP BY beli_brg.kd_brg
  HAVING stok > 0
  ORDER BY stok DESC
  LIMIT 10");
?>
<small class="text-muted">Tidak terjual di bulan <?= htmlspecialchars($label) ?></small>
<table class="w3-table w3-striped w3-white hrf_arial" style="font-size: 11pt">
  <tr class="w3-border yz-theme-l3" style="font-size: 11pt">
    <td style="text-align: center">Nama Barang</td>
    <td style="text-align: center">Stok</td>
    <td style="text-align: center">Terakhir Jual</td>
    <td style="text-align: center">No</td>
  </tr>
  <?php
  $a = 0;
  while ($data = mysqli_fetch_assoc($cek)) {
    $kdbrg = $data['kd_brg'];
    $a++;
    $stok = round($data['stok'], 0);
    $ex = explode(';', carisatkecil2($kdbrg, $connect));
    $sat = strtolower(ceknmkem2($ex[0], $connect));

    $tgljual = '-';
    $cj = mysqli_query($connect, "SELECT MAX(tgl_jual) AS tglakhir FROM dum_jual WHERE kd_brg='$kdbrg' AND kd_toko='$kd_toko'");
    $dj = mysqli_fetch_assoc($cj);
    if (!empty($dj['tglakhir'])) {
      $tgljual = gantitgl($dj['tglakhir']);
    }
    unset($cj, $dj, $ex);
    ?>
    <tr style="font-size: 10pt">
      <td><?= htmlspecialchars($data['nm_brg']) ?></td>
      <td style="text-align:right"><?= $stok . ' ' . $sat ?></td>
      <td style="text-align:center"><?= $tgljual ?></td>
      <td style="text-align: center"><i class="fa fa-archive" style="color: #c0392b"></i> <?= $a ?></td>
    </tr>
  <?php }
  if ($a == 0) { ?>
    <tr style="font-size: 10pt">
      <td colspan="4" style="text-align:center">Tidak ada deadstock pada bulan ini</td>
    </tr>
  <?php }
  unset($cek, $data);
  ?>
</table>
