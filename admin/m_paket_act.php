<?php 
session_start();
include 'config.php';
$conspaket=opendtcek();
$kd_toko=$_SESSION['id_toko'];
$kd_brg=$_POST['kd_brg'];
$kd_paket=$_POST['kd_paket'];
$nm_paket=trim(strtoupper($_POST['nm_paket']));
$qty_brg=backnum($_POST['qty_brg']);
$kd_sat=$_POST['kd_sat'];
$nm_sat=$_POST['nm_sat'];
$d=false;
// cek input data
if (!empty($kd_paket)){
  $d=mysqli_query($conspaket,"UPDATE paket_mas SET nm_paket='$nm_paket' WHERE no_urut='$kd_paket'");
} else {
  $paket=mysqli_query($conspaket,"SELECT * FROM paket_mas WHERE nm_paket='$nm_paket'");  
  if (mysqli_num_rows($paket)>=1){
    $dtpaket=mysqli_fetch_assoc($paket);
    $no_urut =$dtpaket['no_urut'];	
    $d=mysqli_query($conspaket,"UPDATE paket_mas SET nm_paket='$nm_paket' WHERE no_urut='$no_urut'");
  } else {
    $d=mysqli_query($conspaket,"INSERT INTO paket_mas VALUES('','$nm_paket','$kd_toko')");	
    $cex=mysqli_query($conspaket,"SELECT no_urut FROM paket_mas WHERE kd_toko='$kd_toko' Order by no_urut DESC LIMIT 1");
    $dat=mysqli_fetch_assoc($cex);
    $kd_paket=$dat['no_urut'];
    mysqli_free_result($cex);unset($dat);
  }
  mysqli_free_result($paket);unset($dtpaket); 
}
if ($d){
	?>
   <script>popnew_ok("Update Nama Paket berhasil disimpan..")</script>
	<?php
} else { ?>
   <script>popnew_ok("Update data gagal..")</script>
<?php }	

if (!empty($kd_brg)){
  $paket_m=mysqli_query($conspaket,"SELECT * FROM paket_brg WHERE kd_brg='$kd_brg' AND kd_paket='$kd_paket' AND kd_toko='$kd_toko'");	
  if (mysqli_num_rows($paket_m)>=1){
  	$d=mysqli_query($conspaket,"UPDATE paket_brg SET qty_brg='$qty_brg',kd_sat='$kd_sat' WHERE kd_brg='$kd_brg' AND kd_paket='$kd_paket' AND kd_toko='$kd_toko'");
  } else {
    $d=mysqli_query($conspaket,"INSERT INTO paket_brg VALUES('','$kd_paket','$kd_brg','$qty_brg','0','$kd_sat','$kd_toko')"); 		
  }	
  mysqli_free_result($paket_m);
}

if ($d){
	?>
   <script>popnew_ok("Update Barang Paket berhasil disimpan..");document.getElementById('keyktpaket').value='<?=$nm_paket?>';document.getElementById('no_urut').value='<?=$kd_paket?>';caripaket(1,true);caripaketbrg(1,true);</script>
	<?php
} else { ?>
   <script>popnew_ok("Update data gagal..")</script>
<?php }	
mysqli_close($conspaket);
?>
