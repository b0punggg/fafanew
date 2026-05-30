<link rel="stylesheet" href="../assets/css/paper.css">
<link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
<link rel="stylesheet" href="../assets/css/blue-themes.css">
<?php
if(!session_id()) session_start();
include 'config.php';

$connect = opendtcek();
if(!$connect){
  exit("Koneksi database gagal");
}

$kd_toko = isset($_SESSION['id_toko']) ? mysqli_real_escape_string($connect, $_SESSION['id_toko']) : '';
$tgl1 = isset($_POST['tgl1']) ? mysqli_real_escape_string($connect, $_POST['tgl1']) : '';
$tgl2 = isset($_POST['tgl2']) ? mysqli_real_escape_string($connect, $_POST['tgl2']) : '';
$kd_member = isset($_POST['kd_member']) ? mysqli_real_escape_string($connect, $_POST['kd_member']) : '';
$include_poin = isset($_POST['include_poin']) && $_POST['include_poin'] == '1';

if($kd_toko === '' || $tgl1 === '' || $tgl2 === ''){
  exit("Parameter laporan belum lengkap");
}

$nm_toko = '';
$al_toko = '';
$cektoko = mysqli_query($connect, "SELECT nm_toko,al_toko FROM toko WHERE kd_toko='$kd_toko' LIMIT 1");
if($cektoko && mysqli_num_rows($cektoko) > 0){
  $dt_toko = mysqli_fetch_assoc($cektoko);
  $nm_toko = isset($dt_toko['nm_toko']) ? $dt_toko['nm_toko'] : '';
  $al_toko = isset($dt_toko['al_toko']) ? $dt_toko['al_toko'] : '';
}
if($cektoko){ mysqli_free_result($cektoko); }

$where = " WHERE mas_jual.kd_toko='$kd_toko' AND mas_jual.tgl_jual>='$tgl1' AND mas_jual.tgl_jual<='$tgl2' AND IFNULL(mas_jual.kd_member,'')<>'' ";
if($kd_member !== ''){
  $where .= " AND mas_jual.kd_member='$kd_member' ";
}

$q = mysqli_query($connect, "SELECT mas_jual.tgl_jual,mas_jual.no_fakjual,mas_jual.kd_member,mas_jual.tot_jual,mas_jual.tot_disc,mas_jual.kd_bayar,mas_jual.poin_earned,member.nm_member
FROM mas_jual
LEFT JOIN member ON mas_jual.kd_member=member.kd_member
$where
ORDER BY mas_jual.tgl_jual ASC, mas_jual.no_urut ASC");
if(!$q){
  exit("Query laporan gagal: ".mysqli_error($connect));
}

$nm_member_filter = 'SEMUA MEMBER';
if($kd_member !== ''){
  $qmember = mysqli_query($connect, "SELECT nm_member FROM member WHERE kd_member='$kd_member' LIMIT 1");
  if($qmember && mysqli_num_rows($qmember) > 0){
    $dm = mysqli_fetch_assoc($qmember);
    $nm_member_filter = isset($dm['nm_member']) ? $dm['nm_member'] : $kd_member;
  }else{
    $nm_member_filter = $kd_member;
  }
  if($qmember){ mysqli_free_result($qmember); }
}

function tgl_indo_singkat($tgl){
  if($tgl == '' || $tgl == '0000-00-00'){ return '-'; }
  $x = explode('-', $tgl);
  if(count($x) !== 3){ return $tgl; }
  return $x[2].'-'.$x[1].'-'.$x[0];
}
?>
<style>
  body,h2,h3,h4,h5,h6 {font-family: Times,Helvetica}
  th{
    text-align: center;
    border: solid 1px #113300;
  }
  td{
    border: solid 1px #113300;
    background: white;
    font-size: 8pt;
    border-left: none;
    border-right: none;
    border-top: none;
  }
  .sheet {
    overflow: visible;
    height: auto !important;
  }
  @page { size: F4 landscape }
  @media print {
    #printPageButton { display: none; }
  }
</style>

<body class="F4 landscape">
  <section class="sheet padding-10mm">
    <table cellspacing="0" style="width:100%;font-size:8pt;">
      <thead>
        <tr><td colspan="<?=$include_poin ? 9 : 8?>" style="text-align:center;font-size:13pt;border:none"><b><?=htmlspecialchars($nm_toko)?></b></td></tr>
        <tr><td colspan="<?=$include_poin ? 9 : 8?>" style="text-align:center;font-size:11pt;border:none"><b><?=htmlspecialchars($al_toko)?></b></td></tr>
        <tr><td style="border:none">&nbsp;</td></tr>
        <tr><td colspan="<?=$include_poin ? 9 : 8?>" style="text-align:left;font-size:9pt"><b>Laporan transaksi member tanggal <?=tgl_indo_singkat($tgl1)?> s/d <?=tgl_indo_singkat($tgl2)?></b></td></tr>
        <tr><td colspan="<?=$include_poin ? 9 : 8?>" style="text-align:left;font-size:9pt"><b>Filter member: <?=htmlspecialchars($nm_member_filter)?></b></td></tr>
        <tr class="yz-theme-l3">
          <th style="width:4%">NO</th>
          <th style="width:10%">TGL. JUAL</th>
          <th style="width:14%">NO. NOTA</th>
          <th style="width:12%">KODE MEMBER</th>
          <th>MEMBER</th>
          <th style="width:10%">PEMBAYARAN</th>
          <th style="width:12%">TOTAL JUAL</th>
          <th style="width:12%">NETTO</th>
          <?php if($include_poin){ ?>
            <th style="width:9%">POIN</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 0;
        $tot_jual = 0;
        $tot_netto = 0;
        $tot_poin = 0;
        while($d = mysqli_fetch_assoc($q)){
          $no++;
          $sub_total = floatval($d['tot_jual']);
          $netto = $sub_total - floatval($d['tot_disc']);
          $poin = isset($d['poin_earned']) ? floatval($d['poin_earned']) : 0;
          $tot_jual += $sub_total;
          $tot_netto += $netto;
          $tot_poin += $poin;
          ?>
          <tr>
            <td style="text-align:right;border-left:1px solid"><?=$no?>.</td>
            <td style="text-align:center"><?=tgl_indo_singkat($d['tgl_jual'])?></td>
            <td style="text-align:left">&nbsp;<?=htmlspecialchars($d['no_fakjual'])?></td>
            <td style="text-align:center"><?=htmlspecialchars($d['kd_member'])?></td>
            <td style="text-align:left">&nbsp;<?=htmlspecialchars(isset($d['nm_member']) ? $d['nm_member'] : '')?></td>
            <td style="text-align:center"><?=htmlspecialchars($d['kd_bayar'])?></td>
            <td style="text-align:right"><?=number_format($sub_total, 0, ',', '.')?></td>
            <td style="text-align:right"><?=number_format($netto, 0, ',', '.')?></td>
            <?php if($include_poin){ ?>
              <td style="text-align:right;border-right:1px solid"><?=number_format($poin, 0, ',', '.')?></td>
            <?php } ?>
          </tr>
          <?php
        }
        if($no === 0){
          ?>
          <tr>
            <td colspan="<?=$include_poin ? 9 : 8?>" style="text-align:center;padding:12px;border-left:1px solid;border-right:1px solid">Tidak ada transaksi member pada periode ini.</td>
          </tr>
          <?php
        } else {
          ?>
          <tr class="yz-theme-l3">
            <th colspan="6" style="text-align:right">TOTAL&nbsp;&nbsp;</th>
            <th style="text-align:right"><?=number_format($tot_jual, 0, ',', '.')?></th>
            <th style="text-align:right"><?=number_format($tot_netto, 0, ',', '.')?></th>
            <?php if($include_poin){ ?>
              <th style="text-align:right"><?=number_format($tot_poin, 0, ',', '.')?></th>
            <?php } ?>
          </tr>
          <?php
        }
        mysqli_free_result($q);
        ?>
      </tbody>
    </table>

    <div class="w3-row w3-margin-top">
      <div class="w3-col w3-center">
        <button id="printPageButton" class="w3-btn w3-green" onclick="window.print();">Cetak PDF</button>
      </div>
    </div>
  </section>
</body>
<?php mysqli_close($connect); ?>
