<?php 
include 'config.php';
session_start();
$kd_toko=$_SESSION['id_toko'];

$tgl_fak = gantitglsave($_POST['tgl_fak12']);
$no_fak  = trim(strtoupper($_POST['no_fak1']));
$jml_brg = $_POST['jml_brg1'];
$kd_sat  = trim($_POST['kd_sat11']);
$kd_sup  = trim($_POST['kd_sup2']);
$hrg_beli= backnum($_POST['hrg_beli1']);
$kd_brg  = trim($_POST['kd_brg1']);
$discitem1   = $_POST['discitem11'];
$discitem2   = backnum($_POST['discitem22']);
$kd_bar=$kd_brg;
// echo '$tgl_fak1='.$tgl_fak.'<br>';
// echo '$no_fak1='.$no_fak.'<br>';
// echo '$jml_brg='.$jml_brg.'<br>';
// echo '$kd_sat='.$kd_sat.'<br>';
// echo '$kd_sup='.$kd_sup.'<br>';
// echo '$hrg_beli='.$hrg_beli.'<br>';
// echo '$kd_brg='.$kd_brg.'<br>';
// echo '$disc1='.$discitem1.'<br>';
// echo '$disc2='.$discitem2.'<br>';

//cek pd nm_brg
$satx=carisatkecil($kd_brg,$kd_toko);
$x=explode(';', $satx);
$kd_sat_kecil=$x[0];
$jum_kem_kecil=$x[1];
$jumbeli=konjumbrg($kd_sat,$kd_brg,$kd_toko);
$jml_brgkov=$jml_brg*$jumbeli;

// Periksa utk replace pd jml brg mas_brg
  $hub4b=opendtcek();$jml_brgsek=0;$brg_msksek=0;

$d=mysqli_query($hub4b,"INSERT INTO beli_brg values('','$tgl_fak','$no_fak','$kd_brg','$kd_bar','$kd_toko','$kd_sup','$kd_sat','$hrg_beli','$discitem1','$discitem2','$jml_brg','$jml_brgkov','PEMBELIAN BARANG','','','')");

  $cek=mysqli_query($hub4b,"SELECT jml_brg,brg_msk,kd_brg,no_urut from mas_brg WHERE kd_brg='$kd_brg'");
  if (mysqli_num_rows($cek)>=1){
    $data=mysqli_fetch_assoc($cek);
    $jml_brgsek=$data['jml_brg']+$jml_brgkov;
    $brg_msksek=$data['brg_msk']+$jml_brgkov;
    $no_urut=$data['no_urut'];
  }
  unset($cek,$data);
  $d=mysqli_query($hub4b,"UPDATE mas_brg set jml_brg='$jml_brgsek',brg_msk='$brg_msksek' WHERE no_urut='$no_urut' ");  
  mysqli_close($hub4b);
//-----------------------------------------------
  
if($d){header("location:f_masbrg.php?pesan=simpan");}
else{header("location:f_masbrg.php?pesan=gagal");}    
?>
