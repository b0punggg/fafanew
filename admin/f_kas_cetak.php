<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kas</title>
    <link rel="shortcut icon" href="img/keranjang.png">
    <link rel="stylesheet" href="../assets/css/paper.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
    <link rel="stylesheet" href="../assets/css/blue-themes.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</head>
<?php 
include 'config.php';
session_start();
$kd_toko=$_SESSION['id_toko'];
$id_user=$_SESSION['id_user'];
$connect=opendtcek();
?>
<style>
	 th
    {
        text-align: center;
        border: solid 1px #113300;
        /*background: #EEFFEE;*/
    }

    td
    {
        border: solid 1px #113300;
        background: white;
        font-size: 8pt;
        border-left: none;
        border-right: none;
        border-top: none;
        /*border-style:dotted; */
    }
    .sheet {
      overflow: visible;
      height: auto !important;
     
    }
    tbody {page-break-before:always;}
    @page { size: F4 landscape }

    #content {
    display: table;
    }

    #pageFooter {
        display: table-footer-group;
    }

    #pageFooter:after {
        counter-increment: page;
        content: counter(page);
    }

    @page {
      @bottom-right {
        content: counter(page) ' of ' counter(pages);
      }
    }
    @media print {
      #printPageButton {
        display: none;
      }
    }
</style>
<body class="F4">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
    	<?php 
      $param    = explode(';',$_GET['param']);
      $bln      = $param[0];
      $thn      = $param[1];
      $kd_toko  = $_SESSION['id_toko'];
      $nm_toko  = "";

      $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
      $sql=mysqli_fetch_assoc($cektoko);
      $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
      $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
      unset($cektoko,$sql); 
      ?>
      <table cellspacing="0" style="width: 100%; font-size: 8pt;">
        <thead>
          <tr><td colspan="8" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
          <tr><td colspan="8" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
          <tr><td style="border: none">&nbsp;</td></tr>
          <tr> <td colspan="8" style="text-align: left;font-size: 10pt;border-bottom: none;"><b>Rekap Transaksi Kas Bulan <?=nm_bln($bln).'&nbsp;'.$thn?></b></td></tr>
          <tr align="middle" class="yz-theme-l3">
            <th style="width: 4%">No.</th>
            <th style="width: 8%">Tanggal</th>
            <th>KETERANGAN</th>
            <th>KAS AWAL</th>
            <th>MASUK</th>
            <th>KELUAR</th>
            <th>KAS AKHIR</th>
          </tr>  
        </thead>
        <?php
        $no=0;$kas_awal=0;$kas_akhir=0;$susuk_uang=0;
        $totawal=0;$totakhir=0;$totmsk=0;$totklr=0;
        $tglhi1 =$thn.'-'.$bln.'-01';
        $tgl_pertama  = date('d',strtotime(date('Y-m-01', strtotime($tglhi1))));
        $tgl_terakhir = date('d',strtotime(date('Y-m-t', strtotime($tglhi1))));
        for ($x =0; $x <= $tgl_terakhir; $x++) {		
          $tanggal=$thn.'-'.$bln.'-'.$x;
          $sql1=mysqli_query($connect,"SELECT * FROM kas_harian WHERE tgl_kas='$tanggal' AND kd_toko='$kd_toko' ORDER BY tgl_kas ASC");
          if(mysqli_num_rows($sql1)>=1){
            while($data1=mysqli_fetch_assoc($sql1)){
              $no++;
              if($no==1){
                ?>
                <tr>
                <td align="right" style="border-left:solid black 1px"><?php echo $no ?>&nbsp;</td>
                <td align="left"><?=gantitgl($data1['tgl_kas'])?></td>
                <td align="left">KAS</td>
                <td align="right"><?php echo 0 ?></td>
                <td align="right"><?php echo gantitides($data1['uang_kas']); ?></td>
                <td align="right"><?php echo 0 ?></td>
                <td align="right" style="border-right:solid black 1px"><b><?php echo gantitides($data1['uang_kas']); ?></b>&nbsp;</td>
                </tr>
                <?php
                $kas_akhir=$kas_akhir+$data1['uang_kas'];
                $totmsk=$totmsk+$data1['uang_kas'];			
              }else{
                ?>
                <tr>
                <td align="right" style="border-left:solid black 1px"><?php echo $no ?>&nbsp;</td>
                <td align="left"><?=gantitgl($data1['tgl_kas'])?></td>
                <td align="left">KAS</td>
                <td align="right"><?php echo gantitides($kas_akhir); ?></td>
                <td align="right"><?php echo gantitides($data1['uang_kas']); ?></td>
                <td align="right"><?php echo 0 ?></td>
                <td align="right" style="border-right:solid black 1px"><b><?php echo gantitides($data1['uang_kas']+$kas_akhir); ?>&nbsp;</b></td>
                </tr>
                <?php
                $kas_akhir=$kas_akhir+$data1['uang_kas'];
                $totmsk=$totmsk+$data1['uang_kas'];					
              }
            }
            $totakhir=$totakhir+$kas_akhir;
          }
          unset($sql1,$data1);
          $cek2=mysqli_query($connect,"SELECT * FROM biaya_ops WHERE tgl_biaya='$tanggal' AND kd_toko='$kd_toko' ORDER BY tgl_biaya ASC");
          if(mysqli_num_rows($cek2)>=1){
            while($data2=mysqli_fetch_assoc($cek2)){
              $no++;
              ?>
              <tr>
              <td align="right" style="border-left:solid black 1px"><?php echo $no ?>&nbsp;</td>
              <td align="left"><?=gantitgl($data2['tgl_biaya'])?></td>
              <td align="left">BIAYA - <?=ucwords(strtolower($data2['ket_biaya']))?></td>
              <td align="right"><?php echo gantitides($kas_akhir); ?></td>
              <td align="right"><?= 0 ?></td>
              <td align="right"><?=gantitides($data2['nominal'])?></td>
              <td align="right" style="border-right:solid black 1px"><b><?php echo gantitides($kas_akhir-$data2['nominal']); ?>&nbsp;</b></td>
              </tr>
              <?php
              $kas_akhir=$kas_akhir-$data2['nominal'];
              $totklr=$totklr+$data2['nominal'];	

            }
          }
        }
	      ?>
        <tr class="yz-theme-l1">
        <th style="text-align: center;" colspan="4">KAS AKHIR</th>	
        <th style="text-align: right;"><?=gantitides($totmsk)?></th>
        <th style="text-align: right;"><?=gantitides($totklr)?></th>	
        <th style="text-align: right;"><?=gantitides(($totmsk-$totklr)+$totawal)?>&nbsp;</th>
        </tr>
      </table>     
    </div>
    <div class="row">
      <div class="col-sm w3-center">
        <button id="printPageButton" class="btn btn-sm btn-success w3-margin-top " onclick="window.print();">Cetak PDF</button>      
      </div>
    </div>
  </section>
</body>
