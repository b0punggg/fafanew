<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Opname export</title>
</head>
<style>
  table{
	margin: 20px auto;
	border-collapse: collapse;
  }
  table th,
  table td{
	border: 1px solid #3c3c3c;
	padding: 3px 8px;
  }    
</style>
<?php
include "config.php";
session_start();
$connect     = opendtcek();
$tgl1        = $_POST['tgleop1'];
$tgl2        = $_POST['tgleop2'];
$pilih       = $_POST['pilihestokop'];
$kd_tokocari = $_POST['kd_tokoestokop'];
$id_user     = $_SESSION['id_user'];
if ($pilih=='alldata'){
  $param="";   
} else {
  $param=" AND mutasi_adj.kd_toko="."'".$kd_tokocari."'";   
}
$datain=mysqli_query($connect,"SELECT mutasi_adj.*,mas_brg.nm_brg,toko.nm_toko FROM mutasi_adj
LEFT JOIN mas_brg ON mutasi_adj.kd_brg=mas_brg.kd_brg
LEFT JOIN toko ON mutasi_adj.kd_toko=toko.kd_toko
WHERE mutasi_adj.tgl_input >='$tgl1' AND mutasi_adj.tgl_input <='$tgl2' AND INSTR(UPPER(mutasi_adj.ket),'LINE')=0  $param
ORDER BY mutasi_adj.tgl_input");

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Stok Opname ".gantitgl($_SESSION['tgl_set']).".xls"); ?>

<body>
  <table border="1" class="table align-middle" style="font-size:11pt">
    <tr>
      <th colspan="7">LAPORAN STOK OPNAME</th>    
    </tr>
    <tr><th colspan="7">DARI TANGGAL <?=gantitgl($tgl1).' SAMPAI TANGGAL '.gantitgl($tgl2)?></th></tr>
    <tr>
      <th style="width:3%;">NO</th>
      <th style="width:6%;">TANGGAL</th>
      <th>NAMA BARANG</th>
      <th style="width:4%">AWAL</th>
      <th style="width:6%">PENYE-<br>SUAIAN</th>
      <th style="width:8%">TOKO</th>
      <th style="width:27%">KET</th>
    </tr> <?php
    $no=$awal=$akhir=0;$nm_brg='';$tgl='0000-00-00';
    while($sql=mysqli_fetch_assoc($datain)){ 
      $string = strtolower($sql['ket']);
      $x      = strpos($string, "men");
      $awal   = str_replace(",",".",substr($string,strpos($string, ":")+1,$x-(strlen($string)+2)));
      $y      = strpos($string, ")");
      $akhir  = str_replace(",",".",substr($string,$x+10,$y-strlen($string)));
      $nm_brg = mysqli_escape_string($connect,$sql['nm_brg']);
      $tgl    = $sql['tgl_input'];       
      $kd_brg = mysqli_escape_string($connect,$sql['kd_brg']);       
      $no++; ?>
      <tr>
        <th style="text-align:right;border-left: 1px solid;font-weight:lighter"><?php echo $no.'.';?>&nbsp;</th>
        <th style="text-align:center;font-weight:lighter"><?php echo gantitgl($sql['tgl_input']);?></th>
        <th style="text-align:left;padding-ledt:5px;font-weight:lighter"><?php echo $sql['nm_brg']; ?></th>
        <th style="text-align:center;font-weight:lighter"><?php echo gantitides($awal); ?></th>
        <th style="text-align:center;font-weight:lighter"><?php echo gantitides($akhir); ?></th>
        <th style="text-align:center;font-weight:lighter"><?php echo $sql['nm_toko']; ?></th> 
        <th style="text-align:left;border-right: 1px solid;font-size:10pt;font-weight:lighter">&nbsp;<?php echo $sql['ket']; ?></th>
      </tr> <?php             
    } mysqli_close($connect); ?>
  </table>  
</body>
</html>