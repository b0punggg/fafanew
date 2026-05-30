<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Exp Date</title>
  <link rel="shortcut icon" href="img/keranjang.png">
  <link rel="stylesheet" href="../assets/css/paper.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
</head>
<style>
  th {
    text-align: center;
    border: solid 1px #113300;
    padding:10px;
  }
  td {
    border: solid 1px #113300;
    background: white;
    font-size: 8pt;
    border-left: none;
    border-right: none;
    border-top: none;
    padding:3px;
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
ob_start();
include 'config.php';
session_start();
$cet_cr  = $_POST['cet_cr'];
$blncr   = $_POST['bln_cet_cr'];
$thncr   = $_POST['thn_cet_cr'];
$connect = opendtcek();  
$kd_toko = $_SESSION['id_toko'];  

if(!empty($cet_cr)){
  $xada=strpos($cet_cr,"like");
  if ($xada <> false){
    $pecah=explode('like', $cet_cr);
    $kunci=$pecah[0];
    $kunci2=$pecah[1];
    $cet_cr=$kunci." like '%".trim($kunci2)."%'";
  }	else{
    $pecah=explode('=',$cet_cr);
    $kunci=$pecah[0];
    $kunci2=$pecah[1];
    $cet_cr=$kunci." = '".trim($kunci2)."'";
  }
}else{
  $kunci='';
  $kunci2='';	
}

if ($cet_cr=="") {	 
  $sql =mysqli_query($connect, "SELECT beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,beli_brg.kd_bar,beli_brg.kd_brg,beli_brg.stok_jual,beli_brg.expdate,supplier.nm_sup,mas_brg.nm_brg FROM beli_brg
  LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
  LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
  WHERE MONTH(beli_brg.expdate)<='$blncr' AND YEAR(beli_brg.expdate)<='$thncr' AND beli_brg.expdate<>'0000-00-00' AND beli_brg.stok_jual>0 AND beli_brg.kd_toko='$kd_toko'
  ORDER BY mas_brg.nm_brg ASC ");
} else  {
  $sql =mysqli_query($connect, "SELECT beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,beli_brg.kd_bar,beli_brg.kd_brg,beli_brg.stok_jual,beli_brg.expdate,supplier.nm_sup,mas_brg.nm_brg FROM beli_brg
  LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
  LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
  WHERE $params AND MONTH(beli_brg.expdate)<='$blncr' AND YEAR(beli_brg.expdate)<='$thncr' AND beli_brg.expdate<>'0000-00-00' AND beli_brg.stok_jual>0 AND beli_brg.kd_toko='$kd_toko'
  ORDER BY mas_brg.nm_brg ASC");
}	
$cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
$sqld=mysqli_fetch_assoc($cektoko);
$nm_toko=mysqli_escape_string($connect,$sqld['nm_toko']);
$al_toko=mysqli_escape_string($connect,$sqld['al_toko']);
unset($cektoko,$sqld); 

$no=0;$no_fak=$tgl_fak=$kd_sup=$nm_sup='';
$stok1=$stok2=$stok3=$cekada=0; ?>
<body class="F4">
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
      <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
        <thead>
          <tr><td colspan="8" style="text-align: center;font-size: 10pt;border:none"><b><?=$nm_toko?></b></td></tr>
          <tr><td colspan="8" style="text-align: center;font-size: 9pt;border:none"><b><?=$al_toko?></b></td></tr>
          <tr><td style="border: none">&nbsp;</td></tr>
          <tr> <td colspan="8" style="text-align: left;font-size: 8pt"><b>Cek Expired Barang Sampai <?=nm_bln($blncr).' '.nm_bln($thncr)?></b></td></tr> 
          <tr>
            <th style="width: 1%" >NO</th>
            <th style="width: 8%" >TGL.BELI</th>
            <th style="width: 12%">FAKTUR</th>
            <th style="width: 15%">SUPPLIER</th>
            <th style="width: 10%">BARCODE</th>
            <th>NAMA BARANG</th>
            <th style="width:5%">STOK</th>  
            <th style="width:8%">EXPIRED</th>
          </tr>
         </thead>  <?php
          while($data=mysqli_fetch_assoc($sql)){
            $no++; ?>
            <tr>
              <td align="right" style="border-left:1px solid"><?php echo $no?>&nbsp;</td>
              <td align="center" style="border-right: none"><?php echo gantitgl($data['tgl_fak']); ?></td>
              <td align="left" style="border-right: none">&nbsp;<?php echo $data['no_fak']; ?></td>
              <td align="left" style="border-right: none">&nbsp;<?php echo $data['nm_sup']; ?></td>
              <td align="center" style="border-right: none"><?php echo $data['kd_bar']; ?></td>
  			      <td align="left" style="border-right: none;">&nbsp;<?php echo $data['nm_brg']; ?></td>
              <td align="right"><?php echo gantitides($data['stok_jual']);?>&nbsp;</td>
              <td align="center" style="border-right: 1px solid;"><?php echo gantitgl($data['expdate']); ?></td> 
            </tr> <?php 
          } ?>
      
      </table>
    </div>
    <div class="w3-row w3-margin-top">
      <div class="w3-col w3-center">
        <button id="printPageButton" class="w3-btn w3-green" onclick="window.print();">Cetak PDF</button>      
      </div>
    </div>
  </section>
</body>
</html>