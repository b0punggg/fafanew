<?php 
  include 'config.php';
  session_start();
  $connect=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
  $byr_hutang=backnumdes($_POST['byr_hutang']);
  $no_fak=$_POST['no_fak'];
  $tgl_tran=$_POST['tgl_tran'];
  $via=ucwords($_POST['via']);
  $d=false;
  $saldo_awal_lalu=0;$saldo_hutang_sek=0;$no_urutmas=0;
  $cek=mysqli_query($connect,"SELECT * from beli_bay where no_fak='$no_fak' and kd_toko='$kd_toko'");
  if(mysqli_num_rows($cek)>=1){
  	$data=mysqli_fetch_assoc($cek);
    $tot_beli        =$data['tot_beli'];
  	$saldo_awal_lalu =$data['saldo_hutang'];
  	$no_urutmas      =$data['no_urut'];
  	$kd_sup          =$data['kd_sup'];
  	$tgl_fak         =$data['tgl_fak'];
  	$ket             =$data['ket'];
  	$tgl_jt          =$data['tgl_jt'];  
  }else{
  	header("location:f_hutangbayar.php?pesan=gagal");
  }
  unset($cek,$data);
  // echo '$no_fak='.$no_fak.'<br>';
  // echo '$tgl_fak='.$tgl_fak.'<br>';
  //  echo '$tgl_jt='.$no_jt.'<br>';
  // echo '$byr_hutang='.$byr_hutang.'<br>';
  // echo '$tgl_tran='.$tgl_tran.'<br>';
  // echo '$saldo_awal_lalu='.$saldo_awal_lalu.'<br>';
  // echo '$no_urutmas='.$no_urutmas.'<br>';
  if($saldo_awal_lalu==0){
  	// header("location:f_hutangbayar.php?pesan=lunas");	
    ?><script>popnew_warning("Hutang Lunas");</script><?php

  }else{
    if ($saldo_awal_lalu>=$byr_hutang){
      $saldo_hutang_sek=$saldo_awal_lalu-$byr_hutang;
      if($saldo_hutang_sek==0){$ket='LUNAS';}else{$ket='TEMPO';}
      $d=mysqli_query($connect,"INSERT INTO beli_bay_hutang VALUES('','$kd_sup','$no_fak','$tgl_fak','$tgl_tran','$tot_beli','$saldo_awal_lalu','$byr_hutang','$saldo_hutang_sek','$ket','$kd_toko','$tgl_jt','$via')");
      $d=mysqli_query($connect,"UPDATE beli_bay set saldo_awal='$saldo_awal_lalu',byr_hutang='$byr_hutang',saldo_hutang='$saldo_hutang_sek',tgl_tran='$tgl_tran',tgl_jt='$tgl_jt' where no_urut='$no_urutmas' ");
      if($d){?> <script>popnew_ok("Update data berhasil");carinews(1,true)</script><?php }
      else{?> <script>popnew_error("Gagal update data...");carinews(1,true)</script><?php }    
    }else{
      ?><script>popnew_warning("Input data salah !"+"<br>"+"Pastikan pembayaran tidak melebihi sisa hutang");</script><?php
    }
  }

  // if($d){header("location:f_hutangbayar.php?pesan=simpan");}
  // else{header("location:f_hutangbayar.php?pesan=gagal");}    
  mysqli_close($connect);
?>