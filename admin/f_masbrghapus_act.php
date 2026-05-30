<?php 
$kd_brg=$_POST['keyword'];
$kd_toko=$_POST['kdtoko'];
ob_start();
include 'config.php';
session_start();
$connect=opendtcek();
if (!empty($kd_brg)){

  // cek ada pembelian nota apa tidak
  $ada=false;$d=false;
  $cek=mysqli_query($connect,"SELECT * from beli_brg where kd_brg='$kd_brg' AND kd_toko='$kd_toko'");
  if(mysqli_num_rows($cek)){
     $ada=true; 
  }
  if($ada){
    ?><script>popnew_error("Kwaaakkk !..Gagal dihapus.."+'<br>'+"Sudah ada pembelian barang !");</script><?php 
  }else{

    $hub23=opendtcek(1);
    $d=mysqli_query($hub23,"DELETE FROM mas_brg WHERE kd_brg='$kd_brg' ");
    if(adadisc($kd_brg)){
      $d=mysqli_query($hub23,"DELETE FROM disctetap WHERE kd_brg='$kd_brg'");
    } 
    if($d){?><script>popnew_ok("Data terhapus..");document.getElementById('tmb-reset').click();</script><?php }
    else{ ?><script>popnew_warning("Data gagal dihapus");</script><?php }
    mysqli_close($hub23);
  }
}else{ ?><script>popnew_warning("Data masih kosong !!");</script><?php }
?>
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>
