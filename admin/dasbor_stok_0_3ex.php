<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Stok Barang</title>
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
$concet   = opendtcek();
$kd_toko  = $_SESSION['id_toko'];     
$xeplo    = explode("-",kurangtgl(date("Y-m-d"),-60));
$tglakhir = $xeplo[0]."-".$xeplo[1]."-01"; 
$tglhi    = date("Y-m-d");
$j_stok   = $_POST['pilihcstok'];
$a        = 0;
if($_POST['pilihbag']=='0'){
  $pil=''; 
  $nmpil='(SEMUA BAGIAN)';
}else{
  $pil=' AND dum_jual.id_bag='.$_POST['pilihbag']; 
  $ss=$_POST['pilihbag'];
  $dd=mysqli_query($concet,"SELECT nm_bag FROM bag_brg WHERE no_urut='$ss'");
  $qq=mysqli_fetch_assoc($dd);
  $nmpil='('.$qq['nm_bag'].')';
  mysqli_free_result($dd);unset($qq);
}
$cek= mysqli_query($concet,"SELECT kd_brg,nm_brg,count(nm_brg) as jmlbrg,max(tgl_jual) AS jualakhir FROM dum_jual 
      WHERE tgl_jual>='$tglakhir' $pil AND INSTR(nm_brg,'JASA')=0 AND INSTR(ket,'RETUR')=0 AND kd_toko='$kd_toko'
      GROUP BY nm_brg ORDER BY COUNT(*) DESC");
      
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Cek Stok ".gantitgl($_SESSION['tgl_set']).".xls"); ?>

<body>
  <table border="1" class="table align-middle" style="font-size:11pt">
    <tr>
      <th colspan="8">STOK BARANG</th>    
    </tr>
    <tr><th colspan="8"><?="CEK STOK DARI ".gantitgl($tglakhir). " SAMPAI ".gantitgl(date("Y-m-d")).'&nbsp;'. $nmpil?></th></tr>
    <tr>
      <th style="width:5%;">NO</th>
      <th >NAMA BARANG</th>
      <th style="width:8%">TRANSAKSI</th>
      <th style="width:7%">STOK</th>
      <th style="width:5%">RANGKING</th>
      <th style="width:15%">AWAL BELI</th>
      <th style="width:15%">TERAKHIR JUAL</th>
      <th style="width:15%">CEK DATA</th>
    </tr> <?php
    while($data=mysqli_fetch_array($cek)){
      $stok      = $jmlbrg=0;
      $kdbrg     = $data['kd_brg'];
      $nmbrg     = mysqli_escape_string($concet,$data['nm_brg']);
      $jualakhir = $data['jualakhir'];
      $jmlbrg    = $data['jmlbrg'];
         
      //stok jual beli_brg
      $c=mysqli_query($concet,"SELECT sum(beli_brg.stok_jual) as jmls,MIN(beli_brg.tgl_fak) AS beliakhir,mas_brg.nm_brg FROM beli_brg
        LEFT JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg
        WHERE mas_brg.nm_brg='$nmbrg' AND beli_brg.kd_toko='$kd_toko'");
      if(mysqli_num_rows($c)>0){
        $d=mysqli_fetch_assoc($c);
        if($d['jmls']!=NULL){
          $stok=round($d['jmls'],0);
          $beliakhir=$d['beliakhir'];
          if($stok<0){$stok=0;}
          
          //cek stok opname
          $cektgl=$x=$userx='';
          $cx=mysqli_query($concet,"SELECT tgl_input,ket FROM mutasi_adj WHERE kd_brg='$kdbrg' AND kd_toko='$kd_toko'  ORDER BY tgl_input DESC limit 1");
          if(mysqli_num_rows($cx)>0){
            $dx=mysqli_fetch_assoc($cx);
            $x = $dx['ket'];
            $jm=substr($x,strpos($x,"Jam :")+5,strpos($x,"<br>")-strlen($x));
            $userx = substr($x,strpos($x,'User :'),strpos($x,', Jam')-strlen($x));
            $cektgl=gantitgl($dx['tgl_input'])."<br>"."( ".timeago(date("Y-m-d h:m:s",strtotime($dx['tgl_input'].' '.$jm)))." )";
          }
          unset($cx,$dx); 
          if($stok<=$j_stok ){ $a++;?>
            <tr style="font-weight: lighter">
              <th align="right" class="p-4" style="font-weight: lighter"><?=$a?>.</th>
              <th align="left" style="font-weight: lighter"><?=$data['nm_brg']?></th>
              <th style="text-align:center;font-weight: lighter"><?=$jmlbrg?>&nbsp;kali</th>
              <th style="text-align:center;font-weight: lighter"><?=$stok?></th>
              <th style="text-align: center;font-weight: lighter"># <?=$a?></th>
              <th style="text-align: center;font-weight: lighter"><?=gantitgl($beliakhir)?></th> 
              <th style="text-align: center;font-weight: lighter"><?=gantitgl($jualakhir)?></th> 
              <th style="text-align:center;font-weight: lighter;color:red;font-size:10pt"><?=$cektgl."<br>".$userx?></td>
            </tr> <?php 
          }
        }  
      }     
    } 
    mysqli_close($concet); ?>
  </table>  
</body>
</html>