<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">

<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
<?php 
 include 'starting.php';
 $connect=opendtcek();
 $cek=mysqli_query($connect,"SELECT * FROM mas_brg_back ORDER BY kd_brg");
 while($dt=mysqli_fetch_assoc($cek)){
    $kd_brg=$dt['kd_brg'];
    $nm_brg=$dt['nm_brg'];
    $jml_brg='';
    $kd_bar=$dt['kd_bar'];
    $kd_kem1=$dt['kd_kem1'];
    $jum_kem1=$dt['jum_kem1'];
    $hrg_jum1=$dt['hrg_jum1'];
    $kd_kem2=$dt['kd_kem2'];
    $jum_kem2=$dt['jum_kem2'];
    $hrg_jum2=$dt['hrg_jum2'];
    $kd_kem3=$dt['kd_kem3'];
    $jum_kem3=$dt['jum_kem3'];
    $hrg_jum3=$dt['hrg_jum3'];
    $brg_msk='';
    $brg_klr='';
    $kd_toko=$dt['kd_toko'];
    $nm_kem1=$dt['nm_kem1'];
    $nm_kem2=$dt['nm_kem2'];
    $nm_kem3=$dt['nm_kem3'];
    $cetak='';
    $pilih='';
    $copy='';
    mysqli_query($connect,"INSERT INTO mas_brg VALUE('','$kd_brg','$nm_brg','$jml_brg','$kd_bar','$kd_kem1','$jum_kem1','$hrg_jum1','$kd_kem2','$jum_kem2','$hrg_jum2','$kd_kem3','$jum_kem3','$hrg_jum3','$brg_msk','$brg_klr','$kd_toko','$nm_kem1','$nm_kem2','$nm_kem3','','','')");
    
    echo $kd_brg.'<br>';
 }
 
?>

