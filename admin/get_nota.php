<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
include 'config.php';

$cones   = opendtcek();
if(isset($_GET['dts'])){
  $xd=explode(';',$_GET['dts']);
  $no_fakjual = $xd[0];
  $tgl_jual   = $xd[1];
  $kd_toko    = $xd[2];
  $nm_pel     = $xd[3];
  $alamat     = $xd[4];
  $tgltime    = $xd[5];
  $disctot    = $xd[6];
  $voucher    = $xd[7];
  $ongkir     = $xd[8];
  $kd_bayar   = $xd[9];
  $bayar      = $xd[10];
  $susuk      = $xd[11];
  $saldohut   = $xd[12];
  $jtempo     = $xd[13];
}
$sql=mysqli_query($cones,"SELECT *,sum(dum_jual.qty_brg) AS qty_brg FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_jual' AND dum_jual.kd_toko='$kd_toko' GROUP BY dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.discrp,dum_jual.hrg_jual ORDER BY dum_jual.no_urut ASC");
$subtot=$total=$gdisc1=0;
if(mysqli_num_rows($sql)>0){
    while($data=mysqli_fetch_assoc($sql)){
      $nm_sat=' '.ucwords(strtolower($data['nm_sat1']));
      $hrg_jual=round($data['hrg_jual'],0);
            
      if ($data['discrp']>0){
        $subtot=($data['hrg_jual']-$data['discrp'])*$data['qty_brg'];
      }else{
        $subtot=$data['hrg_jual']*$data['qty_brg'];
      }

      $qty_brg=round($data['qty_brg'],0);   
      $discrp=round($data['discrp'],0);
      $hrg_jual=round($data['hrg_jual'],0);
      $total=$total+round($subtot,0); 
      $total=round($total,0);
      $subtot=round($subtot,0);
      
      $items[] = [
        "nmbrg" => trim(ucwords(strtolower($data["nm_brg"]))),
        "qty"   => $qty_brg,
        "sat"   => $nm_sat,
        "hrg"   => $hrg_jual,
        "disc"  => $discrp,
        "subtot"=> $subtot
      ];
    }
}
mysqli_close($cones);

// Gabungkan data toko dan penjualan
$totbelanja=($total-($disctot+$voucher))+$ongkir;
$disctot=gantiti($disctot);$voucher=gantiti($voucher);$ongkir=gantiti($ongkir);
$output = [
  "no_fakjual" => $no_fakjual,  
  "nm_pel"     => trim($nm_pel),
  "alamat"     => $alamat,
  "tgltime"    => $tgltime,
  "belanja"    => $total,
  "total"      => gantiti($totbelanja),
  "disctot"    => $disctot,
  "voucher"    => $voucher,
  "ongkir"     => $ongkir,
  "kd_bayar"   => $kd_bayar,
  "bayar"      => $bayar,
  "susuk"      => $susuk,
  "saldohut"   => $saldohut,
  "jtempo"     => $jtempo,
  "items"      => $items
];
echo json_encode([
    "success" => true,
    "data" => $output
], JSON_UNESCAPED_UNICODE);