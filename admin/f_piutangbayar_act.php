<?php 
  include 'config.php';
  session_start();
  $byr_hutang = 0;
  $connect    = opendtcek();
  $kd_toko    = $_SESSION['id_toko'];
  $byr_hutang = backnumdes($_POST['byr_hutang']);
  $no_fakjual = $_POST['no_fakjual'];
  $tgl_tran   = $_POST['tgl_tran'];
  $d          = false;
  $trf        = $_POST['pil_tf2'];
  $saldo_awal_lalu=0;$saldo_hutang_sek=0;$no_urutmas=0;
  $cek=mysqli_query($connect,"SELECT mas_jual_hutang.*,mas_jual.tot_jual,mas_jual.tot_disc,mas_jual.tot_laba FROM mas_jual_hutang LEFT JOIN mas_jual ON mas_jual_hutang.no_fakjual=mas_jual.no_fakjual WHERE mas_jual_hutang.no_fakjual='$no_fakjual' AND mas_jual_hutang.kd_toko='$kd_toko' ORDER BY no_urut ");
  $totmodal=$saldo_awal_lalu=0;
  if(mysqli_num_rows($cek)>=1){
  	while($data=mysqli_fetch_assoc($cek)){
      $saldo_awal_lalu = $data['saldo_hutang'];
      $no_urutmas      = $data['no_urut'];
      $totjual         = $data['totjual'];
      $kd_pel          = $data['kd_pel'];
      $tgl_jual        = $data['tgl_jual'];
      $ket             = $data['ket'];
      $tgl_jt          = $data['tgl_jt'];  
      $defmodal        = ($data['tot_jual']-$data['tot_disc'])-$data['tot_laba'];  
      $deflaba         = $data['tot_laba'];
      $totmodal        = $totmodal+$data['modal'];  
      
    }
  }else{ 
  	?><script>popnew_ok("Data tidak ditemukan....");</script><?php
  }
  unset($cek,$data);
  
  if($saldo_awal_lalu==0){
  	?><script>popnew_warning("Hutang Lunas");</script><?php
  }else{
  	$saldo_hutang_sek=$saldo_awal_lalu-$byr_hutang;
    $setor=$totmodal+$byr_hutang;
    if($setor<=$defmodal){
      $modalmsk=$byr_hutang;
      $labamsk =0;
    }else{
      if($totmodal<=$defmodal){
         $modalmsk=$defmodal-$totmodal;
         $labamsk =$byr_hutang-$modalmsk;
      }else{
        $modalmsk=0;
        $labamsk =$byr_hutang;
      }
    }
    if ($saldo_hutang_sek<0){
      ?><script>popnew_warning("Input data salah !"+"<br>"+"Pastikan pembayaran tidak melebihi sisa hutang");</script><?php
    }else{
      if($saldo_hutang_sek==0){$ket='LUNAS';}else{$ket='BELUM';}
      $d=mysqli_query($connect,"INSERT INTO mas_jual_hutang VALUES('','$kd_pel','$no_fakjual','$tgl_jual','$tgl_tran','$totjual','$saldo_awal_lalu','$byr_hutang','$saldo_hutang_sek','$ket','$kd_toko','$tgl_jt','$modalmsk','$labamsk','$trf','')");
      $d=mysqli_query($connect,"UPDATE mas_jual set saldo_hutang='$saldo_hutang_sek', ket_bayar='$ket',trf='$trf' WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko' ");  
      if($d){?> <script>popnew_ok("Update data berhasil");carinews(1,true);</script><?php }
      else{?> <script>popnew_error("Gagal update data...");carinews(1,true);</script><?php }
    }
    
  }
  mysqli_close($connect);
  // if($d){header("location:f_piutangbayar.php?pesan=simpan");}
  //  else{header("location:f_piutangbayar.php?pesan=gagal");}    
?>