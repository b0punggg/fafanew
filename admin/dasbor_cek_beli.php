<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cek Pembelian Barang</title>
  <link rel="stylesheet" href="../assets/css/paper.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
</head>
<style>
	th
    {
        text-align: center;
        border: solid 1px #113300;
        padding:10px;
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
      #printPerek {
        display: none;
      }
    }
</style>
<?php 
  include "config.php";
  session_start();
  $kd_toko=$_SESSION['id_toko'];
  $conv=opendtcek();
  $tgl1       = $_POST['tgl_p_beli1'];
  $tgl2       = $_POST['tgl_p_beli2'];
  $bag        = $_POST['bag_p_beli'];
  if($_POST['bag_p_beli'] == 0){
    $bag_p_beli=" AND beli_brg.kd_toko='".$kd_toko."' ";
  }else{
    $bag_p_beli=" AND beli_brg.id_bag=".$_POST['bag_p_beli']." AND beli_brg.kd_toko='".$kd_toko."' ";;
  }
  $nmbag='';
  $sc=mysqli_query($conv,"SELECT nm_bag FROM bag_brg WHERE no_urut='$bag'");
  if(mysqli_num_rows($sc)>0){
    $dc=mysqli_fetch_assoc($sc);
    $nmbag=ucwords(strtolower($dc['nm_bag']));
  }

  //   SELECT beli_brg.kd_brg,SUM(beli_brg.stok_jual) AS stokjual,mas_brg.nm_brg,SUM(dum_jual.qty_brg) AS qty FROM beli_brg 
  // LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
  // LEFT JOIN dum_jual ON beli_brg.kd_brg=dum_jual.kd_brg
  // WHERE beli_brg.tgl_fak>='2025-06-01' AND beli_brg.tgl_fak<='2025-06-30' GROUP BY beli_brg.kd_brg 
  $sql1       = mysqli_query($conv,"SELECT beli_brg.kd_brg,beli_brg.kd_bar,beli_brg.kd_sat,SUM(beli_brg.jml_brg) AS Jumbrg,mas_brg.nm_brg,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3 FROM beli_brg LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg WHERE tgl_fak>='$tgl1' AND tgl_fak<='$tgl2' $bag_p_beli GROUP BY beli_brg.kd_brg,beli_brg.kd_sat ORDER BY Jumbrg DESC ");
  $kd_brg=$kem='';$b_sk=$no=0;
  
?>  
<body class="F4">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;"><?php
      if(mysqli_num_rows($sql1)>0){ ?>
        <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always;">
          <thead>
              <tr><td colspan="6" style="text-align: center;font-size: 10pt;border:none"><b>Cek Jumlah Keluar - Masuk Barang Dari Tanggal <?=gantitgl($tgl1)?> Sampai Tanggal <?=gantitgl($tgl2)?></b></td></tr>
              <tr><td colspan="6" style="text-align: center;font-size: 10pt;border:none"><b>Bagian <?=$nmbag?></b></td></tr>

              <tr style="background-color:rgba(193, 233, 255, 0.74)">
                <th style="width:6%">NO</th>
                <th style="width:15%">BARCODE</th>
                <th>NAMA BARANG</th>
                <th style="width:8%">MASUK</th>
                <th style="width:8%">JUAL</th>
                <th style="width:8%">MUTASI ONLINE</th>
              <!-- <th>ANTAR TOKO</th> -->
            </tr> 
          </thead><?php  
          while($dt = mysqli_fetch_assoc($sql1)){ 
            $no++; $b_sk=0;
            $kd_brg = mysqli_real_escape_string($conv,$dt['kd_brg']);
            $b_sk   = $b_sk+($dt['Jumbrg']*konjumbrg2($dt['kd_sat'],$kd_brg,$conv)); 
            if($dt['nm_kem2']=='-NONE-' && $dt['nm_kem3']=='-NONE-'){
              $kem=$dt['nm_kem1'];
            }
            if($dt['nm_kem2']!='-NONE-' && $dt['nm_kem3']=='-NONE-'){
              $kem=$dt['nm_kem2'];
            }
            if($dt['nm_kem3']!='-NONE-' && $dt['nm_kem2']=='-NONE-'){
              $kem=$dt['nm_kem3'];
            }
            ?>
            <tr>
              <td align="right" style="border-left: 1px solid"><?=$no?>.&nbsp;</td>
              <td><?=$dt['kd_bar']?></td>
              <td><?=$dt['nm_brg']?></td>
              <td align="center" ><?=round($b_sk).' '.strtolower($kem)?></td>
              <td align="center" ><?=round(crjual($kd_toko,$kd_brg,$tgl1,$tgl2,$conv)).' '.strtolower($kem)?></td>
              <td align="center" style="border-right: 1px solid"><?=round(crol($kd_toko,$kd_brg,$tgl1,$tgl2,$conv)).' '.strtolower($kem)?></td>
            </tr><?php              
          }?>
        </table><?php
      }?>
    </div>  
    
    <div class="w3-row w3-margin-top">
      <div class="w3-col w3-center">
          <button type="button" id="printPageButton" class="w3-btn w3-green" onclick="window.print();" style="border-radius:5px;font-size:9pt">Cetak PDF</button>      
          <!-- <button type="button" id="printPerek" class="w3-btn w3-yellow" onclick="document.getElementById('btnrek').click()" style="border-radius:5px;font-size:9pt">Rekap Barang</button>       -->
      </div>
    </div>

  </section>
</body>
</html>
<?php
function crjual($kdtoko,$kdbrg,$tg1,$tg2,$hub){
  $qty=0;$jums=0;
  $qs=mysqli_query($hub, "SELECT SUM(qty_brg) AS qty,kd_sat FROM dum_jual WHERE kd_brg='$kdbrg' AND kd_toko='$kdtoko' AND tgl_jual>='$tg1' AND tgl_jual <='$tg2' GROUP BY kd_sat");
  while($ss=mysqli_fetch_assoc($qs)){
    $qty=$ss['qty']*konjumbrg2($ss['kd_sat'],$kdbrg,$hub);
    $jums=$jums+$qty;
  }
  unset($qs,$ss);
  return $jums;
}

function crol($kdtoko,$kdbrg,$tg1,$tg2,$hub){
  $de=mysqli_query($hub,"SELECT mutasi_adj.* FROM mutasi_adj
      WHERE mutasi_adj.kd_brg='$kdbrg' AND mutasi_adj.kd_toko='$kdtoko' AND mutasi_adj.tgl_input >='$tg1' AND mutasi_adj.tgl_input <='$tg2' AND UPPER(mutasi_adj.ket) LIKE '%LINE%'");
  $jum=0;    
  while($sql=mysqli_fetch_assoc($de)){
    $string=$x=$y='';$awal=$akhir=0;
    $string = strtolower($sql['ket']);
    $x      = strpos($string, "men");
    $awal   = str_replace(",",".",substr($string,strpos($string, ":")+1,$x-(strlen($string)+2)));
            
    $y      = strpos($string, ")");
    $akhir  = str_replace(",",".",substr($string,$x+10,$y-strlen($string)));
    $jum=$jum+($awal-$akhir);
    
  }  
  return $jum;
}
?>