<?php 
  include 'config.php';
  session_start();
  $connect=opendtcek();
  $kd_toko = $_SESSION['id_toko'];
        
  $kd_member = strtoupper($_POST['kd_member_tukar']);
  $nm_member = strtoupper($_POST['nm_member_tukar']);
  $poin_saat_ini = floatval($_POST['poin_saat_ini']);
  $poin_tukar = floatval(str_replace('.', '', str_replace(',', '.', $_POST['poin_tukar'])));
  $poin_sisa = floatval($_POST['poin_sisa']);
  $keterangan = strtoupper(trim($_POST['keterangan_tukar']));
  $tgl_tukar = date('Y-m-d');
  $tghi = date("Y-m-d H:i:s");
  
  // Validasi
  if($kd_member == '' || $poin_tukar <= 0){
    header("location:f_tukarpoin.php?pesan=gagal");
    exit;
  }
  
  // Cek poin member saat ini
  $cekpoin = mysqli_query($connect,"SELECT poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
  if(mysqli_num_rows($cekpoin) > 0){
    $datapoin = mysqli_fetch_assoc($cekpoin);
    $poin_sekarang = floatval($datapoin['poin']);
    
    // Validasi poin cukup
    if($poin_tukar > $poin_sekarang){
      header("location:f_tukarpoin.php?pesan=gagal");
      exit;
    }
    
    // Update poin member
    $poin_baru = $poin_sekarang - $poin_tukar;
    $update = mysqli_query($connect,"UPDATE member SET poin = '$poin_baru' WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
    
    if($update){
      // Simpan riwayat penukaran poin
      $no_tukar = "TUKAR-".date('YmdHis');
      mysqli_query($connect,"INSERT INTO member_poin_history values('','$kd_member','$no_tukar','$tgl_tukar','0','$poin_tukar','$poin_baru','Penukaran Poin - $keterangan','$kd_toko','$tghi')");
      
      header("location:f_tukarpoin.php?pesan=simpan");
    } else {
      header("location:f_tukarpoin.php?pesan=gagal");
    }
    
    mysqli_free_result($cekpoin);
    unset($datapoin);
  } else {
    header("location:f_tukarpoin.php?pesan=gagal");
  }
   
  mysqli_close($connect);     
?>
