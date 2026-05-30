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
<?php
  session_start();
  include 'config.php';
  $connect=opendtcek();
?>
<style>
	th{
    text-align: center;
    border: solid 1px #113300;
    /*background: #EEFFEE;*/
  }

  td{
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
      ini_set('memory_limit', '1024M'); // or you could use 1G 
      $pesan    = explode(';',$_GET['pesan']);
      $tgl1     = $pesan[0];
      $tgl2     = $pesan[1];
      $cr_bay   = $pesan[2];
      $kd_toko  = $_SESSION['id_toko'];
      $nm_toko  = "";
      $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
      $sql=mysqli_fetch_assoc($cektoko);
      $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
      $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
      unset($cektoko,$sql); 
      if($cr_bay=="TUNAI"){
        $cek=mysqli_query($connect,"SELECT * FROM beli_bay LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup where beli_bay.kd_toko='$kd_toko' and beli_bay.tgl_fak>='$tgl1' and beli_bay.tgl_fak<='$tgl2' and beli_bay.ket='TUNAI' AND INSTR(beli_bay.ketbeli,'PEMBELIAN')>0 ORDER BY beli_bay.tgl_fak,beli_bay.no_fak ASC");     
      }else if($cr_bay=="TEMPO"){
        $cek=mysqli_query($connect,"SELECT * FROM beli_bay LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup where beli_bay.kd_toko='$kd_toko' and beli_bay.tgl_fak>='$tgl1' and beli_bay.tgl_fak<='$tgl2' and beli_bay.ket='TEMPO' AND INSTR(beli_bay.ketbeli,'PEMBELIAN')>0 ORDER BY beli_bay.tgl_fak, beli_bay.no_fak ASC");     
      }else{
        $cek=mysqli_query($connect,"SELECT * FROM beli_bay 
          LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup 
          WHERE beli_bay.kd_toko='$kd_toko' and beli_bay.tgl_fak>='$tgl1' and beli_bay.tgl_fak<='$tgl2' AND INSTR(beli_bay.ketbeli,'PEMBELIAN')>0  ORDER BY beli_bay.tgl_fak, beli_bay.no_fak ASC");
      } 
        
      if(mysqli_num_rows($cek)>=1){
        ?>
        <table cellspacing="0" style="width: 100%; border: solid 1px black; text-align: center; font-size: 8pt;">
          <thead>
            <h5 style="text-align: center">NOTA PEMBELIAN BARANG</h5>
            <h5 style="text-align: center;"><?php echo $nm_toko?>&nbsp;<?=$al_toko?></h5>
            <!-- <h6 style="text-align: center;"><?php echo $al_toko ?></h6> -->
            <h5 style="text-align: left;font-size: 8pt;margin-bottom:5px "><?php echo 'Tanggal Pembelian '. gantitgl($tgl1).' sampai tanggal '.gantitgl($tgl2).' , per nota pembelian' ?></h5>
            <tr>
              <th style="width:5%;">NO</th>
              <th style="width:11%">TGL. BELI</th>
              <th style="width:17%">NO.FAKTUR</th>
              <th style="width:25%">SUPPLIER</th>
              <th style="width:7%">JML. ITEM</th>
              <th style="width:12%">JML. TAGIHAN</th>
              <?php if($cr_bay="TEMPO"){ ?>
              <th style="width:10%">SISA HUTANG</th>     
              <?php }else{ ?>  
              <th style="width:10%">CR. BAYAR</th>     
              <?php } ?>  
              <th style="width:10%">KET</th>
            </tr> 
          </thead>   
          <?php
          $no=0;$totbeli=0;$no_fak='';$tgl_fak='0000-00-00';$disc=0;$ppn=0;
          while($databay=mysqli_fetch_assoc($cek)){
            $no++;	
            $disc =($databay['disc']/100)*$databay['tot_beli'];
            $ppn  =($databay['ppn']/100)*$databay['tot_beli']; 
            if($databay['saldo_hutang']==0){
                $ket="DONE";
            }else{
              $ket="UNDONE";
            }
            // if($databay['ket']=="TEMPO"){
          //     $bayar=$databay['saldo_awal'];
            // }else{
            $bayar=($databay['tot_beli']-$disc)+$ppn;
            // }

            $totbeli=$totbeli+$bayar;
            $jml_brg=hitjmlbrg($databay['no_fak'],$databay['tgl_fak'],$kd_toko,$connect);
            ?>
              <tr>
              <td style="text-align:right;font-size: 8pt"><?php echo $no.'.';?></td>
              <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($databay['tgl_fak']);?></td>
              <td style="text-align:left;font-size: 8pt"><?php echo $databay['no_fak'];?></td>
              <td style="text-align:left;font-size: 8pt"><?php echo $databay['nm_sup']; ?></td>
              <td style="text-align:center;font-size: 8pt"><?php echo $jml_brg; ?></td>
              <td style="text-align:right;font-size: 8pt"><?php echo gantitides($bayar); ?></td>
              <?php if($cr_bay="TEMPO"){ ?>
              <td style="text-align:center;font-size: 8pt"><?php echo gantitides($databay['saldo_hutang']); ?></td>
              <?php }else{ ?>  
              <td style="text-align:center;font-size: 8pt"><?php echo $databay['ket']; ?></td>
              <?php } ?>  
              <td style="text-align:center;font-size: 8pt"><?php echo $ket; ?></td>
              </tr>	
          <?php           
          }
          ?>
          <tr>
            <th colspan=5 align="center">Total</th>
            <th style="text-align:right"><?php echo gantitides($totbeli) ?></th>
            <th colspan=2 align="center"></th>
          </tr>
        
        </table>
        <?php  	
      }   
      mysqli_close($connect);
      ?>
    </div>
    <div class="row">
      <div class="col-sm w3-center">
        <button id="printPageButton" class="btn btn-sm btn-success w3-margin-top " onclick="window.print();">Cetak PDF</button>      
      </div>
    </div>
  </section>
  <?php
  function hitjmlbrg($no_fak,$tgl_fak,$kd_toko,$hub){
    $cek=mysqli_query($hub,"SELECT COUNT(*) AS jumlah FROM beli_brg where no_fak='$no_fak' and tgl_fak='$tgl_fak' and kd_toko='$kd_toko'");
    $getjml = mysqli_fetch_array($cek);
    return mysqli_escape_string($hub,$getjml['jumlah']);
    unset($cek,$getjml);
  }
  function carisaldo($no_fak,$tgl_fak,$kd_toko,$hub){
    $cek=mysqli_query($hub,"SELECT saldo_awal FROM beli_bay where no_fak='$no_fak' and tgl_fak='$tgl_fak' and kd_toko='$kd_toko' ORDER BY no_urut ASC LIMIT 1");
    $getsld = mysqli_fetch_array($cek);
    return mysqli_escape_string($hub,$getsld['saldo_awal']);
    unset($cek,$getsld);
  }
  ?>    
</body>

</html>      