<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deadstock</title>
    <link rel="shortcut icon" href="img/keranjang.png">
    <link rel="stylesheet" href="../assets/css/paper.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
    <link rel="stylesheet" href="../assets/css/blue-themes.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</head>
<style>  
    th
    {
        text-align: center;
        border: solid 1px #113300;
        padding:5px;
    }

    td
    {
        border: solid 1px #113300;
        background: white;
        font-size: 9pt;
        border-left: none;
        border-right: none;
        border-top: none;
    }
    .sheet {
      overflow: visible;
      height: auto !important;
    }
    tbody {page-break-before:always;}
    @page { size: F4 landscape }
    @media print {
      #printPageButton {
        display: none;
      }
    }
</style>
   
<?php
session_start();
include 'config.php';
$concet = opendtcek();
$cbln = date('m', strtotime($_SESSION['tgl_set']));
$cthn = date('Y', strtotime($_SESSION['tgl_set']));
$kd_toko = $_SESSION['id_toko'];

$pilihbulan = isset($_POST['pilihbulan']) ? (int)$_POST['pilihbulan'] : (int)$cbln;
$endyear = isset($_POST['pilihtahun']) ? (int)$_POST['pilihtahun'] : (int)$cthn;
$ctkpil = isset($_POST['ctkpil']) ? $_POST['ctkpil'] : '0';
$pilihst = isset($_POST['pilihst']) ? $_POST['pilihst'] : '';

if ($pilihbulan < 1 || $pilihbulan > 12) {
  $pilihbulan = (int)$cbln;
}
$endbln = $pilihbulan;

$namabulan = array(
  1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
  7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'Nopember',12=>'Desember'
);
$ktbln = $namabulan[$endbln];

if ($ctkpil == '0') {
  $pil = '';
  $nmpil = '(SEMUA BAGIAN)';
} else {
  $ss = (int)$ctkpil;
  $pil = ' AND beli_brg.id_bag=' . $ss;
  $dd = mysqli_query($concet, "SELECT nm_bag FROM bag_brg WHERE no_urut='$ss'");
  $qq = mysqli_fetch_assoc($dd);
  $nmpil = '(' . $qq['nm_bag'] . ')';
  mysqli_free_result($dd);
  unset($qq);
}

if ($pilihst === '' || $pilihst === null) {
  $pil_st = 1000000;
} else {
  $pil_st = (int)$pilihst;
}

$cq = mysqli_query($concet, "SELECT * FROM toko WHERE kd_toko='$kd_toko'");
$dtt = mysqli_fetch_assoc($cq);
$nm_toko = $dtt['nm_toko'];
mysqli_free_result($cq);
unset($dtt);
?>
<body class="F4">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
      <h5 style="text-align: center;margin-bottom:5px ">DEADSTOCK TOKO FAFA BULAN <?= strtoupper($ktbln) ?> TAHUN <?= $endyear ?> <?= $nmpil ?></h5>
      <p style="text-align:center;font-size:9pt;margin-top:0">Barang berstok yang tidak terjual sama sekali pada periode tersebut</p>
      <table cellspacing="0" style="width: 100%; border: solid 1px black; text-align: center; font-size: 8pt;">
        <thead>
          <tr>
            <th style="width:5%;">NO</th>
            <th>NAMA BARANG</th>
            <th style="width:12%">STOK</th>
            <th style="width:15%">AWAL BELI</th>
            <th style="width:15%">TERAKHIR JUAL</th>
          </tr> 
        </thead>   
        <?php
        $cek = mysqli_query($concet, "SELECT beli_brg.kd_brg, MAX(mas_brg.nm_brg) AS nm_brg, SUM(beli_brg.stok_jual) AS stok, MIN(beli_brg.tgl_fak) AS tglbeli
          FROM beli_brg
          LEFT JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg AND mas_brg.kd_toko = beli_brg.kd_toko
          WHERE beli_brg.kd_toko='$kd_toko'
            AND INSTR(IFNULL(mas_brg.nm_brg,''),'JASA')=0
            $pil
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
          ORDER BY stok DESC");
        $a = 0;
        while ($data = mysqli_fetch_array($cek)) {
          $kdbrg = $data['kd_brg'];
          $stok = round($data['stok'], 0);
          if ($stok < 0) {
            $stok = 0;
          }
          if ($stok > $pil_st) {
            continue;
          }

          $ex = explode(';', carisatkecil2($kdbrg, $concet));
          $sat = strtolower(ceknmkem2($ex[0], $concet));
          unset($ex);

          $tgljual = '-';
          $cj = mysqli_query($concet, "SELECT MAX(tgl_jual) AS tglakhir FROM dum_jual WHERE kd_brg='$kdbrg' AND kd_toko='$kd_toko'");
          $dj = mysqli_fetch_assoc($cj);
          if (!empty($dj['tglakhir'])) {
            $tgljual = gantitgl($dj['tglakhir']);
          }
          unset($cj, $dj);

          $a++;
          ?>
          <tr style="font-size: 10pt">
            <td align="right"><?= $a ?>.</td>
            <td><?= $data['nm_brg'] ?></td>
            <td style="text-align:center"><?= $stok . ' ' . $sat ?></td>
            <td style="text-align:center"><?= !empty($data['tglbeli']) ? gantitgl($data['tglbeli']) : '-' ?></td>
            <td style="text-align:center"><?= $tgljual ?></td>
          </tr>
          <?php
        }
        if ($a == 0) { ?>
          <tr>
            <td colspan="5" style="text-align:center;padding:12px">Tidak ada deadstock pada periode ini</td>
          </tr>
        <?php } ?>
      </table>
    </div>
    <div class="row">
      <div class="col-sm w3-center">
        <button id="printPageButton" class="btn btn-sm btn-success w3-margin-top" onclick="window.print();">Cetak PDF</button>      
      </div>
    </div>
  </section>
</body>      
</html>
