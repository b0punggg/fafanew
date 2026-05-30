<?php
  // File untuk mengambil discount promo untuk barang tertentu
  ob_start();
  include 'config.php';
  session_start();
  $connect = opendtcek();
  
  $kd_brg = isset($_POST['kd_brg']) ? mysqli_real_escape_string($connect, $_POST['kd_brg']) : '';
  $tgl_jual = isset($_POST['tgl_jual']) ? mysqli_real_escape_string($connect, $_POST['tgl_jual']) : date('Y-m-d');
  $hrg_jual = isset($_POST['hrg_jual']) ? floatval($_POST['hrg_jual']) : 0;
  
  $discitem_value = 0;
  $disc_info = array('disc_rupiah' => 0, 'disc_persen' => 0, 'discitem' => 0);
  
  if (!empty($kd_brg)) {
    // Ambil discount promo
    $disc_promo = getdiscpromoitem($kd_brg, $tgl_jual, $connect);
    
    if ($disc_promo !== false) {
      $disc_rp = floatval($disc_promo['disc_rupiah']);
      $disc_pr = floatval($disc_promo['disc_persen']);
      
      // Hitung total discount dalam rupiah
      if ($disc_pr > 0 && $hrg_jual > 0) {
        // Jika ada discount persen dan harga jual sudah diketahui
        $discitem_value = ($hrg_jual * $disc_pr / 100) + $disc_rp;
      } else {
        // Jika hanya discount rupiah atau harga jual belum diketahui
        $discitem_value = $disc_rp;
      }
      
      $disc_info = array(
        'disc_rupiah' => $disc_rp,
        'disc_persen' => $disc_pr,
        'discitem' => $discitem_value
      );
    }
  }
  
  mysqli_close($connect);
  ob_end_clean();
  echo json_encode($disc_info);
?>

