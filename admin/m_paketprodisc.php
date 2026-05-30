<?php 
  ob_start();
  include "config.php";
  session_start();
  $kd_toko=$_SESSION['id_toko'];
  
  //cek sdh ada mutasi dum_jual apa blm
  $condiscpak=opendtcek(); 
  $kd_paket=mysqli_real_escape_string($condiscpak,$_POST['kd_paket']);
  $disctotal=backnumdes(mysqli_real_escape_string($condiscpak,$_POST['disctotal']));
  
 // echo $kd_paket.'<br>';echo $disctotal.'<br>';
  //cek awal semua pada paket_brg
  $hrgtot=0;$itemdisc=0;
  $cekpk=mysqli_query($condiscpak,"SELECT * FROM paket_brg
        WHERE paket_brg.kd_paket = '$kd_paket' AND paket_brg.kd_toko='$kd_toko'");
  while($dtpk=mysqli_fetch_assoc($cekpk)){
    $hrgtot=$hrgtot+carihrgjual(mysqli_real_escape_string($condiscpak,$dtpk['kd_brg']),mysqli_real_escape_string($condiscpak,$dtpk['kd_sat']));
  }
  mysqli_free_result($cekpk);unset($dtpk);   

  $itemdisc=($disctotal/$hrgtot)*100;

  $cekpk=mysqli_query($condiscpak,"SELECT * FROM paket_brg WHERE kd_paket='$kd_paket' AND kd_toko='$kd_toko'");
  while($dtpk=mysqli_fetch_assoc($cekpk)){
    $no_urut=$dtpk['no_urut'];
    $hrg=carihrgjual($dtpk['kd_brg'],$dtpk['kd_sat']);  
    $disc1=$hrg*($itemdisc/100);
    $f=mysqli_query($condiscpak,"UPDATE paket_brg SET disc1='$itemdisc' WHERE no_urut='$no_urut'");
  }
  mysqli_close($condiscpak);mysqli_free_result($cekpk);   

  if($f){
     ?><script>popnew_warning("Update data berhasil.. ");document.getElementById('formsetdisc').style.display='none';caripaketbrg(1,true);</script><?php
  }else {
     ?><script>popnew_error("Gagal update data.. ");kosongkan();caripaketbrg(1,true);</script><?php
  }

 
 $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
 ob_end_clean();
 echo json_encode(array('hasil'=>$html));
?>  