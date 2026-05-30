<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pembelian Item</title>
    <link rel="shortcut icon" href="img/keranjang.png">
    <link rel="stylesheet" href="../assets/css/paper.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
    <link rel="stylesheet" href="../assets/css/blue-themes.css">
</head>
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
  <?php 
    session_start();    
    include 'config.php';
    ini_set('memory_limit', '1024M'); // or you could use 1G
    $connect=opendtcek();
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
    $a=0;$ket='';

    if($cr_bay=="TUNAI"){
      $ket='TUNAI';
      $cekbeli=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.jml_brg,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.ket,supplier.nm_sup,kemas.nm_sat1,mas_brg.nm_brg,beli_bay.ket,beli_bay.ppn
        FROM beli_brg 
        LEFT JOIN beli_bay ON beli_brg.no_fak=beli_bay.no_fak 
        LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup 
        LEFT JOIN kemas ON beli_brg.kd_sat=kemas.no_urut
        LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
        WHERE beli_brg.kd_toko='$kd_toko' and beli_brg.tgl_fak>='$tgl1' and beli_brg.tgl_fak<='$tgl2' AND beli_bay.ket='TUNAI' AND INSTR(beli_brg.ket,'PEMBELIAN')>0 ORDER BY beli_brg.tgl_fak, beli_brg.no_fak ASC");     

      $cek=mysqli_query($connect,"SELECT no_fak,tgl_fak from beli_brg WHERE beli_brg.kd_toko='$kd_toko' and beli_brg.tgl_fak>='$tgl1' and beli_brg.tgl_fak<='$tgl2' AND INSTR(beli_brg.ket,'PEMBELIAN') >0  ORDER BY beli_brg.tgl_fak,beli_brg.no_fak ASC LIMIT 1");
      $sqlbeli=mysqli_fetch_assoc($cek);
      $no_fak=$sqlbeli['no_fak'];$tgl_bel=$sqlbeli['tgl_fak'];
      mysqli_free_result($cek);unset($sqlbeli);

    }else if($cr_bay=="TEMPO"){
      $ket='TEMPO';
      $cekbeli=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.jml_brg,beli_brg.hrg_beli,beli_brg.ket,beli_brg.disc1,beli_brg.disc2,supplier.nm_sup,kemas.nm_sat1,mas_brg.nm_brg,beli_bay.ket,beli_bay.ket,beli_bay.ppn
        FROM beli_brg 
        LEFT JOIN beli_bay ON beli_brg.no_fak=beli_bay.no_fak 
        LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup 
        LEFT JOIN kemas ON beli_brg.kd_sat=kemas.no_urut
        LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
        WHERE beli_brg.kd_toko='$kd_toko' and beli_brg.tgl_fak>='$tgl1' and beli_brg.tgl_fak<='$tgl2' AND INSTR(beli_brg.ket,'PEMBELIAN')>0 AND beli_bay.ket='TEMPO' ORDER BY beli_brg.no_fak ASC");     

      $cek=mysqli_query($connect,"SELECT no_fak,tgl_fak from beli_brg WHERE beli_brg.kd_toko='$kd_toko' and beli_brg.tgl_fak>='$tgl1' and beli_brg.tgl_fak<='$tgl2' AND INSTR(beli_brg.ket,'PEMBELIAN') >0  ORDER BY beli_brg.tgl_fak,beli_brg.no_fak ASC LIMIT 1");
      $sqlbeli=mysqli_fetch_assoc($cek);
      $no_fak=$sqlbeli['no_fak'];$tgl_bel=$sqlbeli['tgl_fak'];
      mysqli_free_result($cek);unset($sqlbeli); 

    }else{
      $ket='TUNAI / TEMPO';
      $cekbeli=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,mas_brg.nm_brg,beli_brg.jml_brg,beli_brg.hrg_beli,beli_brg.ket,beli_brg.disc1,beli_brg.disc2,supplier.nm_sup,kemas.nm_sat1,beli_bay.ket AS ket2,beli_bay.ppn FROM beli_brg 
        LEFT JOIN beli_bay ON beli_brg.no_fak=beli_bay.no_fak 
        LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup 
        LEFT JOIN kemas ON beli_brg.kd_sat=kemas.no_urut
        LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
        WHERE beli_brg.kd_toko='$kd_toko' and beli_brg.tgl_fak>='$tgl1' and beli_brg.tgl_fak<='$tgl2' AND INSTR(beli_brg.ket,'PEMBELIAN')>0 ORDER BY beli_brg.tgl_fak,beli_brg.no_fak ASC");

      $cek=mysqli_query($connect,"SELECT no_fak,tgl_fak from beli_brg WHERE beli_brg.kd_toko='$kd_toko' and beli_brg.tgl_fak>='$tgl1' and beli_brg.tgl_fak<='$tgl2' AND INSTR(beli_brg.ket,'PEMBELIAN') >0  ORDER BY beli_brg.tgl_fak,beli_brg.no_fak ASC LIMIT 1");
      $no_fak='';$tgl_bel='0000-00-00';
      if(mysqli_num_rows($cek)>0){
        $sqlbeli=mysqli_fetch_assoc($cek);
        $no_fak=$sqlbeli['no_fak'];$tgl_bel=$sqlbeli['tgl_fak'];
      }
      mysqli_free_result($cek);unset($sqlbeli);
    } 
    
  ?>  

<body class="F4 landscape">      

    <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
          <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
            <thead>
                <tr><td colspan="13" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="13" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="13" style="text-align: left;font-size: 10pt"><b>Laporan pembelian barang per item dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?>, pembayaran <?=$ket?></b></td></tr>   
                <tr class="yz-theme-l3">
                    <th style="width:3%;">NO</th>
                    <th style="width:6%">TGL. FAKT</th>
                    <th style="width:12%">NO.FAKTUR</th>
                    <th style="width:13%">SUPPLIER</th>
                    <th>NAMA BARANG</th>
                    <th style="width:5%">QTY</th>
                    <th style="width:4%">SAT</th>
                    <th style="width:4%">DISC</th>
                    <th style="width:4%">PPN</th>
                    <th style="width:8%">HARGA BELI</th>
                    <th style="width:8%">HARGA NETT</th>
                    <th style="width:9%">JUMLAH</th>
                    <th align="midle">KETERANGAN</th>
                   
                </tr>       
            </thead>

            <?php           
               if(mysqli_num_rows($cekbeli)>=1){
                    $no=0;$totbeli1=0;$totbeli2=0;$jumlah=0;$disc=0;$subtot1=0;$subtot2=0;$diskon=0;
                    $jumlah=0;$hrgnet=0;$totbeli3=0;$totx=0;
                    while ($sqlbeli=mysqli_fetch_assoc($cekbeli)) {
                      $no++;
                      $disc1=mysqli_escape_string($connect,$sqlbeli['disc1'])/100;
                      $disc2=mysqli_escape_string($connect,$sqlbeli['disc2']);

                      if ($sqlbeli['disc1']==0.00 && $sqlbeli['disc2']==0){
                        $jumlah=$sqlbeli['jml_brg']*$sqlbeli['hrg_beli'];
                        $disc='0.00';
                        $hrgnet=mysqli_escape_string($connect,$sqlbeli['hrg_beli']);
                      } else if ($sqlbeli['disc1'] > 0.00 && $sqlbeli['disc2']==0) {
                        $jumlah=(mysqli_escape_string($connect,$sqlbeli['hrg_beli'])-(mysqli_escape_string($connect,$sqlbeli['hrg_beli'])*$disc1))*mysqli_escape_string($connect,$sqlbeli['jml_brg']);
                        $disc=$sqlbeli['disc1'].'%';
                        $hrgnet=mysqli_escape_string($connect,$sqlbeli['hrg_beli'])-(mysqli_escape_string($connect,$sqlbeli['hrg_beli'])*$disc1); 
                      } else if ($sqlbeli['disc1'] == 0.00 && $sqlbeli['disc2']>0) {
                        $jumlah=(mysqli_escape_string($connect,$sqlbeli['hrg_beli'])-$disc2)*mysqli_escape_string($connect,$sqlbeli['jml_brg']);
                        $disc=gantiti($sqlbeli['disc2']);
                        $hrgnet=mysqli_escape_string($connect,$sqlbeli['hrg_beli'])-$disc2; 
                      }
                      $jumlah=$jumlah+(($jumlah*$sqlbeli['ppn'])/100);
                      $hrgnet=$hrgnet+(($hrgnet*$sqlbeli['ppn'])/100);
                      $totbeli1=$totbeli1+$sqlbeli['hrg_beli'];
                      $totbeli2=$totbeli2+$jumlah;
                      $totbeli3=$totbeli3+$hrgnet;
                      
                      ?>
                      <!-- <?php     
                        if ($no_fak != $sqlbeli['no_fak']  ){
                          ?>
                          <tr>
                            <td colspan=10>
                              
                            </td>
                            <th><?php echo gantitides($totx); ?></th>
                          </tr>
                          <?php
                          $totx=0;
                          $totx=$totx+$jumlah;    
                          $no_fak = $sqlbeli['no_fak'];    
                        } else {
                          $totx=$totx+$jumlah;    
                        }
                        
                        ?> -->

                      <!-- <tbody > -->
                           <tr >
                            <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqlbeli['tgl_fak']);?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqlbeli['no_fak'];?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqlbeli['nm_sup']; ?></td>
                            <td style="text-align:left;font-size: 8pt"><?php echo $sqlbeli['nm_brg']; ?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqlbeli['jml_brg'] ?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqlbeli['nm_sat1']; ?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $disc; ?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqlbeli['ppn'].'%'; ?></td>
                            <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqlbeli['hrg_beli']); ?></td>
                            <td style="text-align:right;font-size: 8pt"><?php echo gantitides($hrgnet); ?></td>
                            <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jumlah); ?></td>
                            <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $sqlbeli['ket']; ?></td>
                           </tr>
                        <!-- </tbody> -->
                        
                    <?php    
                    }
                    ?>
                    
                    <tr cellspacing="2" class="yz-theme-l3">
                        <th colspan=9 align="center" style="font-size: 8pt"><b>T O T A L &nbsp; P E M B E L I A N &nbsp; B A R A N G</b></th>
                        <th style="text-align:right;font-size: 8pt"><?php echo gantitides($totbeli1) ?></th>
                        <th style="text-align:right;font-size: 8pt"><?php echo gantitides($totbeli3) ?></th>
                        <th style="text-align:right;font-size: 8pt"><?php echo gantitides($totbeli2) ?></th>
                        <th></th>
                      </tr><?php
                } // if  
            ?>  
          </table>    
      </div>  
      <div class="w3-row">
        <div class="w3-col w3-center">
          <button id="printPageButton" class="w3-button w3-green w3-margin-top " onclick="window.print();">Cetak PDF</button>      
        </div>
      </div>
    </section>      
    
</body>       
</html>

<?php mysqli_close($connect); ?>
<?php     
    function hitjmlbrg($no_fak,$tgl_fak,$kd_toko){
      $connect1 = opendtcek(1);
      $cek=mysqli_query($connect1,"SELECT COUNT(*) AS jumlah FROM beli_brg where no_fak='$no_fak' and tgl_fak='$tgl_fak' and kd_toko='$kd_toko'");
      $getjml = mysqli_fetch_array($cek);
      return mysqli_escape_string($connect1,$getjml['jumlah']);
      mysqli_close($connect1);unset($cek,$getjml);
    }
    function carisaldo($no_fak,$tgl_fak,$kd_toko){
      $connect2 = opendtcek(1);
      $cek=mysqli_query($connect2,"SELECT saldo_awal FROM bay_beli where no_fak='$no_fak' and tgl_fak='$tgl_fak' and kd_toko='$kd_toko' ORDER BY no_urut ASC LIMIT 1");
      $getsld = mysqli_fetch_array($cek);
      return mysqli_escape_string($connect2,$getsld['saldo_awal']);
      mysqli_close($connect2);unset($cek,$getsld);
    }
?>

