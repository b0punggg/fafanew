<?php
session_start();
include 'config.php';
$connect = opendtcek();
$kd_toko = $_SESSION['id_toko'];
$tglhi = isset($_SESSION['tgl_set']) ? $_SESSION['tgl_set'] : date('Y-m-d');
$xtgl = explode('-', $tglhi);
$endbln = $xtgl[1];
$endyear = $xtgl[0];

$periode = isset($_POST['periode']) ? $_POST['periode'] : 'bulan';
if (!in_array($periode, array('hari', 'minggu', 'bulan'), true)) {
  $periode = 'bulan';
}

if ($periode == 'hari') {
  $where = "dum_jual.tgl_jual='$tglhi'";
  $label = 'Hari ' . gantitgl($tglhi);
} elseif ($periode == 'minggu') {
  $where = "YEARWEEK(dum_jual.tgl_jual,1)=YEARWEEK('$tglhi',1)";
  $senin = date('Y-m-d', strtotime('monday this week', strtotime($tglhi)));
  $minggu = date('Y-m-d', strtotime('sunday this week', strtotime($tglhi)));
  $label = 'Minggu ' . gantitgl($senin) . ' s/d ' . gantitgl($minggu);
} else {
  $where = "MONTH(dum_jual.tgl_jual)='$endbln' AND YEAR(dum_jual.tgl_jual)='$endyear'";
  $label = 'Bulan ' . date('m/Y', strtotime($tglhi));
}

$cek = mysqli_query($connect, "SELECT dum_jual.kd_brg,dum_jual.nm_brg,COUNT(nm_brg) AS jmlbrg FROM dum_jual
  WHERE $where AND kd_toko='$kd_toko'
  GROUP BY dum_jual.nm_brg ORDER BY COUNT(*) DESC LIMIT 10");
?>
<small class="text-muted" id="bestseller-label"><?= htmlspecialchars($label) ?></small>
<table class="w3-table w3-striped w3-white hrf_arial" style="font-size: 11pt">
  <tr class="w3-border yz-theme-l3" style="font-size: 11pt">
    <td style="text-align: center">Nama Barang</td>
    <td style="text-align: center">Jml</td>
    <td style="text-align: center">Stok</td>
    <td style="text-align: center">Rank</td>
  </tr>
  <?php
  $a = 0;
  while ($data = mysqli_fetch_assoc($cek)) {
    $kdbrg = $data['kd_brg'];
    $a++;
    $stok = 0;
    $c = mysqli_query($connect, "SELECT SUM(stok_jual) AS jmls FROM beli_brg WHERE kd_brg='$kdbrg'");
    $d = mysqli_fetch_assoc($c);
    $stok = round($d['jmls'], 0);
    $ex = explode(';', carisatkecil2($kdbrg, $connect));
    $sat = strtolower(ceknmkem2($ex[0], $connect));
    unset($c, $d, $ex);
    ?>
    <tr style="font-size: 10pt">
      <td><?= $data['nm_brg'] ?></td>
      <td style="text-align:right"><?= $data['jmlbrg'] ?></td>
      <td style="text-align:right"><?= $stok . ' ' . $sat ?></td>
      <td style="text-align: center"><i class="fa fa-star" style="color: orange"></i> <?= $a ?></td>
    </tr>
  <?php }
  if ($a == 0) { ?>
    <tr style="font-size: 10pt">
      <td colspan="4" style="text-align:center">Tidak ada data penjualan pada periode ini</td>
    </tr>
  <?php }
  unset($cek, $data);
  ?>
</table>
