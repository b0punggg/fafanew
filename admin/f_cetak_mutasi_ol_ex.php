<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutasi online export</title>
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
$cons    = opendtcek();
$id_user = $_SESSION['id_user'];
$tgl1    = $_POST['tgl1'];
$tgl2    = $_POST['tgl2'];
$jum=$no=$stok=0;;
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Mutasi Online ".gantitgl($_SESSION['tgl_set']).".xls");

$data=mysqli_query($cons,"SELECT dum_mutol.nm_brg,dum_mutol.kd_brg,dum_mutol.kd_toko, SUM(dum_mutol.keluar) AS jumkel, toko.nm_toko FROM dum_mutol 
  LEFT JOIN toko ON dum_mutol.kd_toko=toko.kd_toko
  WHERE dum_mutol.id_user='$id_user' GROUP BY dum_mutol.nm_brg,dum_mutol.kd_toko ORDER BY dum_mutol.nm_brg ASC");
?>
<body>
  <table border="1">
    <tr>
      <th colspan="5">LAPORAN REKAP MUTASI PENJUALAN ONLINE</th>    
    </tr>
    <tr><th colspan="5">DARI TANGGAL <?=gantitgl($tgl1).' SAMPAI TANGGAL '.gantitgl($tgl2)?></th></tr>
    <tr>
	  <th>No</th>
	  <th>NAMA BARANG</th>
	  <th>KELUAR</th>
	  <th>STOK AKHIR</th>
    <th>TOKO</th>
	</tr> <?php
    while($d=mysqli_fetch_assoc($data)){ 
      $no++;    
      $jum=$jum+$d['jumkel'];
      $stok=carist_ak($d['kd_brg'],$d['kd_toko'],$cons); ?>
      <tr>
        <td><?=$no?> &nbsp;</td>
        <td>&nbsp; <?=ucwords(strtolower($d['nm_brg']))?></td>
        <td align="center"><?=$d['jumkel']?></td>
        <td align="center"><?=gantitides($stok)?></td>
        <td align="center"><?=$d['nm_toko']?></td>
      </tr><?php 
    } ?>
    <tr>
      <th colspan="2" align="right">JUMLAH &nbsp;</th>  
      <th align="center"><?=$jum?></th>      
      <th></th>
    </tr>
  </table>  
</body>
</html>