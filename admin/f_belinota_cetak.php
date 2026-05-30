<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pembelian</title>
    <link rel="shortcut icon" href="img/keranjang.png">
    <link rel="stylesheet" href="../assets/css/paper.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
    <link rel="stylesheet" href="../assets/css/blue-themes.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</head>
<style>
	th{
      text-align: center;
      border: solid 1px #113300;
      font-size: 10pt;
    }

  td{
      border: solid 1px #113300;
      background: white;
      font-size: 10pt;
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
  <?php 
    session_start();    
    include 'config.php';
    $connect=opendtcek();
    ini_set('memory_limit', '1024M'); // or you could use 1G
    $pesan    = explode(';',$_GET['pesan']);
    $no_fak   = $pesan[0];
    $tgl_fak  = $pesan[1];
    $tgl_fak   = gantitglsave($tgl_fak);

    $kd_toko  = $_SESSION['id_toko'];
    $nm_toko  = "";
    $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
    $sql=mysqli_fetch_assoc($cektoko);
    $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
    $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
    unset($cektoko,$sql); 
    $a=0;
  ?>  

<body class="F4">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
      <table id="content" cellspacing="0" style="width: 100%; page-break-before: always">
        <thead>
          <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
          <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
          <tr><td style="border: none">&nbsp;</td></tr>
          <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>Cetak nota pembelian nomer <?=$no_fak?> tanggal <?=gantitgl($tgl_fak)?> </b></td></tr>   
          <tr class="yz-theme-l3">
            <th style="width:2%;">NO</th>
            <th style="width:15%">SUPPLIER</th>
            <th >NAMA BARANG</th>
            <th style="width:4%">QTY</th>
            <th style="width:4%">SAT</th>
            <th style="width:4%">DISC</th>
            <th style="width:9%">HARGA BELI</th>
            <th style="width:10%">SUB TOTAL</th>
          </tr>       
        </thead>
        <?php           
          $cekbeli=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.jml_brg,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,supplier.nm_sup,kemas.nm_sat1,mas_brg.nm_brg FROM beli_brg 
          LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup 
          LEFT JOIN kemas ON beli_brg.kd_sat=kemas.no_urut
          LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
          WHERE beli_brg.kd_toko='$kd_toko' and beli_brg.tgl_fak='$tgl_fak' and beli_brg.no_fak='$no_fak' ORDER BY beli_brg.no_urut ASC");
          if(mysqli_num_rows($cekbeli)>=1){
            $no=0;$totbeli1=0;$totbeli2=0;$jumlah=0;$disc=0;$subtot1=0;$subtot2=0;$disc=0;
            while ($sqlbeli=mysqli_fetch_assoc($cekbeli)) {
              $no++;
              $disc1=mysqli_escape_string($connect,$sqlbeli['disc1'])/100;
              $disc2=mysqli_escape_string($connect,$sqlbeli['disc2']);

              if ($sqlbeli['disc1']=='0.00'){
                // echo gantiti($sqlbeli['disc2']);
                $jumlah=(mysqli_escape_string($connect,$sqlbeli['hrg_beli'])-$disc2)*mysqli_escape_string($connect,$sqlbeli['jml_brg']);
                $disc=gantiti($sqlbeli['disc2']);
              }else{
                $jumlah=(mysqli_escape_string($connect,$sqlbeli['hrg_beli'])-(mysqli_escape_string($connect,$sqlbeli['hrg_beli'])*$disc1))*mysqli_escape_string($connect,$sqlbeli['jml_brg']);
                $disc=$sqlbeli['disc1'].'%';
              }
              if ($sqlbeli['disc1']=='0.00' && $sqlbeli['disc2']=='0'){
                $jumlah=mysqli_escape_string($connect,$sqlbeli['jml_brg'])*mysqli_escape_string($connect,$sqlbeli['hrg_beli']);
                $disc='0.00';
              }

              $totbeli1=$totbeli1+$sqlbeli['hrg_beli'];
              $totbeli2=$totbeli2+$jumlah;
              ?>
                <tr>
                  <td style="text-align:right;border-left: 1px solid"><?php echo $no.'.';?></td>
                  <td style="text-align:left;"><?php echo $sqlbeli['nm_sup']; ?></td>
                  <td style="text-align:left;"><?php echo $sqlbeli['nm_brg']; ?></td>
                  <td style="text-align:center;"><?php echo $sqlbeli['jml_brg'] ?></td>
                  <td style="text-align:center;"><?php echo $sqlbeli['nm_sat1']; ?></td>
                  <td style="text-align:right;"><?php echo $disc; ?></td>
                  <td style="text-align:right;"><?php echo gantiti($sqlbeli['hrg_beli']); ?></td>
                  <td style="text-align:right;border-right: 1px solid"><?php echo gantiti($jumlah); ?></td>
                </tr>  
              <?php     
            } ?>
            
            <tr cellspacing="2" class="yz-theme-l3">
                <th colspan=6 align="center" >T O T A L &nbsp; P E M B E L I A N &nbsp; B A R A N G</th>
                <th style="text-align:right"><?php echo gantiti($totbeli1) ?></th>
                <th style="text-align:right;"><?php echo gantiti($totbeli2) ?></th>
            </tr>
            <!-- Summary pembelian -->
            <?php 
              $ket='';$dp=$byr=0;$tgl_jt='0000-00-00';
              $cek2=mysqli_query($connect,"SELECT * from beli_bay where no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'");
              if(mysqli_num_rows($cek2)>0){
                $data=mysqli_fetch_assoc($cek2);
                $ket= $data['ket'];
                $dp = $data['byr_hutang'];
                $byr= $data['saldo_awal'];
                if (empty($data['tgl_jt'])){
                  $tgl_jt='0000-00-00';
                }else{ $tgl_jt=$data['tgl_jt'];}
              }
              unset($cek,$data);
              // echo $ket."<tr>";
              // echo $tgl_jt."<tr>";
            ?>
            <tr><td style="border: none">&nbsp;</td></tr>
            <tr><th style="border:none;text-align: left">KETERANGAN</th></tr>
            <tr>
              <td style="border: none">-Total Pembelian</td>
              <td style="border: none;text-align: right"><?=gantiti($totbeli2)?></td>       
            </tr>
            
            <?php if($ket=="TUNAI"){ ?>
                <tr>
                  <tr><td style="border: none">-Dibayar <?=$ket?> </td>
                    <td style="border: none;text-align: right"><?=gantiti($byr)?></td>
                </tr>
            <?php }else{?>
                <tr><tr><td style="border: none">-Dibayar <?=$ket?> </td></tr></tr>
                <tr>
                    <td style="border: none"> *Uang Muka</td>     
                    <td style="border: none;text-align: right"><?=gantiti($dp)?></td>
                </tr>
                <tr>
                  <td style="border: none"> *Sisa Tagihan</td>
                  <td style="border: none;text-align: right"><?=gantiti($byr-$dp)?></td>
                </tr>
                <tr>
                  <td style="border: none"> *Jatuh Tempo </td>
                  <td style="border: none;text-align: right"><?=gantitgl($tgl_jt)?></td>
                </tr>
            <?php
            }
          } 
        ?>  
      </table>    
    </div>  
    <div class="row">
      <div class="col-sm w3-center">
        <button id="printPageButton" class="btn btn-sm btn-success w3-margin-top " onclick="window.print();">Cetak PDF</button>      
      </div>
    </div>
  </section>      
</body>       
<?php 
  function hitjmlbrg($no_fak,$tgl_fak,$kd_toko){
    $connect1 = mysqli_connect("localhost","root", "", "toko_retail"); // Koneksi ke MySQL 
    $cek=mysqli_query($connect1,"SELECT COUNT(*) AS jumlah FROM beli_brg where no_fak='$no_fak' and tgl_fak='$tgl_fak' and kd_toko='$kd_toko'");
    $getjml = mysqli_fetch_array($cek);
    return mysqli_escape_string($connect1,$getjml['jumlah']);
    mysqli_close($connect1);unset($cek,$getjml);
  }
  function carisaldo($no_fak,$tgl_fak,$kd_toko){
    $connect2 = mysqli_connect("localhost","root", "", "toko_retail"); // Koneksi ke MySQL 
    $cek=mysqli_query($connect2,"SELECT saldo_awal FROM bay_beli where no_fak='$no_fak' and tgl_fak='$tgl_fak' and kd_toko='$kd_toko' ORDER BY no_urut ASC LIMIT 1");
    $getsld = mysqli_fetch_array($cek);
    return mysqli_escape_string($connect2,$getsld['saldo_awal']);
    mysqli_close($connect2);unset($cek,$getsld);
  }
?>
</html>
