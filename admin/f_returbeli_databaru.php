<?php 
ob_start();
include 'config.php';
session_start();
$connew=opendtcek();
$kd_toko=mysqli_real_escape_string($connew,$_POST['keyword']);
$no=0;
$cek=mysqli_query($connew,"SELECT MAX(no_urut) AS jumrek FROM retur_beli_mas ");
$datcek=mysqli_fetch_assoc($cek);
$no=$datcek['jumrek'];
//echo '$no='.$no;
$xtgl=strtotime($_SESSION['tgl_set']);
if ($no>0){
 $no=$no+1;	
  $no_tran='RB.'.$no.'-'.$kd_toko.'.'.date('d',$xtgl).date('m',$xtgl).date('y',$xtgl);	
}else {
  $no_tran='RB.1'.'-'.$kd_toko.'.'.date('d',$xtgl).date('m',$xtgl).date('y',$xtgl);
}
unset($cek,$datcek);
?><script>
	document.getElementById('no_tran').value='<?=$no_tran?>';
    document.getElementById('tgl_tran').value='<?=$_SESSION['tgl_set']?>';
    kosongkan2();
</script><?php
mysqli_close($connew);

$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
ob_end_clean();
// Buat array dengan index hasil dan value nya $html
// Lalu konversi menjadi JSON
echo json_encode(array('hasil'=>$html));
?>
