<?php 
//**Cek balance penjualan nota dan per item, masukkan range tanggal
include 'config.php';
session_start();
$con=opendtcek();
$tot=0;$totdum=0;$x=0;
$cek=mysqli_query($con,"SELECT * FROM mas_jual where kd_toko='IDTOKO-1' AND tgl_jual>='2021-09-1' 
	AND tgl_jual<='2021-09-31'  ORDER BY no_fakjual");
while ($data=mysqli_fetch_assoc($cek)){
  $no_fakjual=$data['no_fakjual'];
  $tgl_jual=$data['tgl_jual'];
  
  $cek2=mysqli_query($con,"SELECT * FROM dum_jual WHERE kd_toko='IDTOKO-1' AND no_fakjual='$no_fakjual' AND tgl_jual>='2021-09-1' AND tgl_jual<='2021-09-31' order by no_fakjual");	
  $totdum=0;
  if (mysqli_num_rows($cek2)>=1){
    while ($da=mysqli_fetch_assoc($cek2)) {
  	    
        if ($da['discitem']>0 && $da['discrp']>0){
           $disc=(($da['hrg_jual']-$da['discrp'])-(($da['hrg_jual']-$da['discrp'])*($da['discitem']/100)))*$da['qty_brg'];	
        } 
        if ($da['discitem']>0 && $da['discrp']==0){
          $disc=($da['hrg_jual']-($da['hrg_jual']*($da['discitem']/100)))*$da['qty_brg']; 
        } 
        if ($da['discitem']==0 && $da['discrp']>0){
        	$disc=($da['hrg_jual']-$da['discrp'])*$da['qty_brg']; 
        }
        if ($da['discitem']==0 && $da['discrp']==0){	
        	$disc=$da['hrg_jual']*$da['qty_brg']; 
        }	
	  	$totdum=$totdum+$disc;
	  	//echo $da['no_fakjual'].'='.$totdum.'<br>';
	  	//$t=$da['no_fakjual'].' '.$totdum;
    }
  } else {
  	$totdum=0;
  	$t='';
  }
  mysqli_free_result($cek2);unset($da);
  $totjual=$data['tot_jual']-$data['tot_disc'];
  if (gantitides($totjual)<>gantitides($totdum)){
  	$x++;	
  	$xx=$totjual-$totdum;
  	echo $x.'. Faktur= '. $no_fakjual.' >> Nota : '.gantitides($totjual).' - Peritem : '.gantitides($totdum).' * Selisih = '.gantitides($xx).'<br>';
   }else {
   	$x++;	
   	echo $x.'. '.$no_fakjual.' >> Data OK'.'<br>';
   }
  
}
?>
