<?php
  include_once 'config.php';
  session_start();
  
  $cDtc  = $_POST['dtc'];
  $nKali = isset($_POST['kopi']) ? $_POST['kopi'] : 1;
  
  // Parse dtc untuk mendapatkan data yang diperlukan
  // Format dtc: no_fakjual;tgl_jual;kd_toko;nm_pel;alamat;tgltime;disctot;voucher;ongkir;kd_bayar;bayar;susuk;saldohut;tgl_jt
  $xd = explode(';', $cDtc);
  $no_fakjual = isset($xd[0]) ? $xd[0] : '';
  $tgl_jual   = isset($xd[1]) ? $xd[1] : '';
  $kd_toko    = isset($xd[2]) ? $xd[2] : '';
  $nm_pel     = isset($xd[3]) ? $xd[3] : '';
  $alamat     = isset($xd[4]) ? $xd[4] : '';
  $tgltime    = isset($xd[5]) ? $xd[5] : '';
  $disctot    = isset($xd[6]) ? floatval($xd[6]) : 0;
  $voucher    = isset($xd[7]) ? floatval($xd[7]) : 0;
  $ongkir     = isset($xd[8]) ? floatval($xd[8]) : 0;
  $kd_bayar   = isset($xd[9]) ? $xd[9] : 'TUNAI';
  $bayar      = isset($xd[10]) ? floatval($xd[10]) : 0;
  $susuk      = isset($xd[11]) ? floatval($xd[11]) : 0;
  $saldohut   = isset($xd[12]) ? floatval($xd[12]) : 0;
  $tgl_jt     = isset($xd[13]) ? $xd[13] : '';
  
  // Ambil kd_pel dari database berdasarkan mas_jual atau dum_jual
  $connect = opendtcek();
  $kd_pel = 'IDPEL-0'; // Default
  $kd_member = ''; // Default
  $poin_earned = 0; // Default
  $nm_member = ''; // Default
  $poin_saldo = 0; // Default
  
  // Cari kd_pel dan kd_member dari mas_jual jika ada
  $cekpel = mysqli_query($connect, "SELECT kd_pel, kd_member, poin_earned FROM mas_jual WHERE no_fakjual='$no_fakjual' AND tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' LIMIT 1");
  if (mysqli_num_rows($cekpel) > 0) {
    $dtpel = mysqli_fetch_assoc($cekpel);
    $kd_pel = $dtpel['kd_pel'];
    $kd_member = isset($dtpel['kd_member']) ? $dtpel['kd_member'] : '';
    $poin_earned = isset($dtpel['poin_earned']) ? floatval($dtpel['poin_earned']) : 0;
  } else {
    // Jika tidak ada di mas_jual, cek dari dum_jual
    $cekpel2 = mysqli_query($connect, "SELECT kd_pel FROM dum_jual WHERE no_fakjual='$no_fakjual' AND tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' LIMIT 1");
    if (mysqli_num_rows($cekpel2) > 0) {
      $dtpel2 = mysqli_fetch_assoc($cekpel2);
      $kd_pel = $dtpel2['kd_pel'];
    }
    mysqli_free_result($cekpel2);
  }
  mysqli_free_result($cekpel);
  
  // Ambil data toko dan pelanggan
  $sqlcari=mysqli_query($connect,"SELECT * from toko where kd_toko='$kd_toko'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  $nm_toko=$datacari['nm_toko'];
  $al_toko=$datacari['al_toko'];
  unset($sqlcari,$datacari);
  
  $sqlcari=mysqli_query($connect,"SELECT * from pelanggan where kd_pel='$kd_pel'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  $nm_pel=$datacari['nm_pel'];
  $alamat=$datacari['al_pel'];
  unset($sqlcari,$datacari);
  
  // Ambil data member jika ada
  if (!empty($kd_member)) {
    $sqlmember=mysqli_query($connect,"SELECT nm_member, poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko' LIMIT 1");
    if (mysqli_num_rows($sqlmember) > 0) {
      $datamember=mysqli_fetch_assoc($sqlmember);
      $nm_member = $datamember['nm_member'];
      $poin_saldo = isset($datamember['poin']) ? floatval($datamember['poin']) : 0;
    }
    mysqli_free_result($sqlmember);
    unset($sqlmember,$datamember);
  }
  
  include_once 'thermal_printer.php';

  // CEK: localhost ATAU Windows PC kasir (bisa pakai domain lokal seperti tokofafa.dhe51.id)
  $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
  $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
  $server_addr = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
  
  $isLocalHost = (
    $http_host == 'localhost' || 
    $http_host == '127.0.0.1' || 
    strpos($http_host, 'localhost') !== false ||
    strpos($http_host, '127.0.0.1') !== false ||
    $server_name == 'localhost' ||
    $server_name == '127.0.0.1' ||
    $server_addr == '127.0.0.1' ||
    $server_addr == '::1'
  );

  $isDirectPrint = $isLocalHost || (thermal_is_windows() && thermal_print_available());
  
  error_log("Print Debug - HTTP_HOST: $http_host, isLocalHost: " . ($isLocalHost ? 'YES' : 'NO') . ", isDirectPrint: " . ($isDirectPrint ? 'YES' : 'NO') . ", printer_open: " . (function_exists('printer_open') ? 'YES' : 'NO') . ", shell_copy: " . (thermal_shell_available() ? 'YES' : 'NO'));
  
  if ($isDirectPrint) {
    // MODE LOCAL: Gunakan thermal printing langsung via PHP (seperti sebelumnya)
    $keyword = $kd_toko.';'.$kd_pel.';'.$no_fakjual.';'.$tgl_jual.';1;'.$kd_bayar.';'.$bayar.';'.$saldohut.';'.$tgl_jt.';'.$susuk;
    $_POST['keyword'] = $keyword;
    
    // Clear any previous output
    if (ob_get_level()) {
      ob_end_clean();
    }
    ob_start();
    
    try {
      include 'f_jualcetaknota.php';
      if (isset($GLOBALS['thermal_last_print'])) {
        $thermal_success = $GLOBALS['thermal_last_print']['success'];
      } else {
        $thermal_success = false;
      }
    } catch (Exception $e) {
      $thermal_success = false;
      error_log("Thermal printing error: " . $e->getMessage());
    } catch (Error $e) {
      $thermal_success = false;
      error_log("Thermal printing fatal error: " . $e->getMessage());
    }
    
    // Clear output buffer (tidak perlu output HTML untuk print langsung)
    ob_end_clean();
    
    // Return JSON response tanpa membuka window baru
    header('Content-Type: application/json');
    if ($thermal_success) {
      $script_content = '<script>console.log("✅ Thermal printing dilakukan langsung ke printer tanpa window");</script>';
    } else {
      $script_content = '<script>console.log("⚠️ Thermal printing mungkin gagal, cek error log");</script>';
    }
    echo json_encode(array('hasil'=>$script_content));
    mysqli_close($connect);
    exit; // Keluar langsung setelah print, jangan lanjut ke mode online
    
  } else {
    include_once 'nota_data.php';

    $notaData = build_nota_data($connect, array(
      'no_fakjual'  => $no_fakjual,
      'tgl_jual'    => $tgl_jual,
      'kd_toko'     => $kd_toko,
      'nm_pel'      => $nm_pel,
      'alamat'      => $alamat,
      'tgltime'     => $tgltime,
      'disctot'     => $disctot,
      'voucher'     => $voucher,
      'ongkir'      => $ongkir,
      'kd_bayar'    => $kd_bayar,
      'bayar'       => $bayar,
      'susuk'       => $susuk,
      'saldohut'    => $saldohut,
      'jtempo'      => $tgl_jt,
      'nm_toko'     => $nm_toko,
      'al_toko'     => $al_toko,
      'nm_member'   => $nm_member,
      'poin_earned' => $poin_earned,
      'poin_saldo'  => $poin_saldo,
    ));

    header('Content-Type: application/json');
    echo json_encode(array('notaData' => $notaData), JSON_UNESCAPED_UNICODE);
    mysqli_close($connect);
  }
?>
