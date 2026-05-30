<?php
include 'config.php';
session_start();
$con=opendtcek();
$q=mysqli_query($con,"SELECT kd_brg,kd_sat,SUM(stok_jual) AS jmlstok FROM beli_brg GROUP BY kd_brg ORDER BY kd_brg");
while($d=mysqli_fetch_assoc($q)){
   $kd_brg=mysqli_escape_string($con,$d['kd_brg']);
   $stok  =$d['jmlstok'];
   $qty=0;$jums=0;
   $qs=mysqli_query($con, "SELECT SUM(qty_brg) AS qty,kd_sat FROM dum_jual WHERE kd_brg='$kd_brg' GROUP BY kd_sat");
   while($ss=mysqli_fetch_assoc($qs)){
      $qty=$ss['qty']*konjumbrg2($ss['kd_sat'],$kd_brg,$con);
      if($kd_brg=='ZEN-0'){
         echo $ss['kd_sat'].'='.$qty.'<br>';
      }
      $jums=$jums+$qty;
   }
   unset($qs,$ss);

   $qq=mysqli_query($con,"SELECT no_urut,jml_brg,brg_msk,brg_klr FROM mas_brg WHERE kd_brg='$kd_brg' ORDER BY kd_brg");
   if(mysqli_num_rows($qq)){
    $cc=mysqli_fetch_assoc($qq);
    $no_urut=$cc['no_urut'];
    $brg_klr=$cc['brg_msk']-$stok;
    $up=mysqli_query($con,"UPDATE mas_brg SET jml_brg='$stok',brg_klr='$jums' WHERE no_urut='$no_urut'"); 
   }
   unset($cc,$qq); 
   

   if($up){echo "Data berhasil update - ".$kd_brg.'<br>';}else{echo "Gagal update...".'<br>';}
}
?>