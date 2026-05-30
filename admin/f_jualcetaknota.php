 
<?php
  // File ini di-include dari f_jual_cetnota.php
  // Pastikan config.php sudah di-include
  if (!function_exists('opendtcek')) {
    include_once 'config.php';
  }
  
  // Pastikan session sudah dimulai
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  
  // Pastikan $connect tersedia, jika belum ada buat baru
  if (!isset($connect) || !$connect) {
    $connect = opendtcek();
  }
  
  // Ambil keyword dari $_POST yang sudah diset oleh f_jual_cetnota.php
  $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
  $keyword = mysqli_real_escape_string($connect, $keyword);
  //echo $keyword;
  $x=explode(';', $keyword);
  $kd_toko=$x[0];
  $kd_pel=$x[1];
  $no_fakjual=$x[2];
  $tgl_jual=$x[3];
  $d=$x[4];
  $kd_bayar=$x[5];
  $bayar=$x[6];
  $saldohut=$x[7];
  $tgl_jt=$x[8];
  $susuk=$x[9];

    $condensed = Chr(27) . Chr(33) . Chr(4);
    $bold1 = Chr(27) . Chr(69);
    $bold0 = Chr(27) . Chr(70);
    $italic1= Chr(27).Chr(ord("4"));
    $italic0= Chr(27).Chr(ord("5"));
    $spasiver = chr(27).chr(ord("I"));
    $fontsizebig=chr(27).chr(ord("W")).chr(ord("1"));
    $fontkecil=chr(27).chr(ord("g"));
    $superscript1=chr(27).chr(83);
    $superscript0=chr(27).chr(84); 
    $condensed1 = chr(15);
    $condensed0 = chr(18);
    $initialized = chr(27).chr(64);
    $open = chr(27).chr(112).chr(48).chr(25).chr(250);
    $cutPaper = chr(29) . "V" . chr(48) . chr(0);

    $sqlcari=mysqli_query($connect,"SELECT * from toko where kd_toko='$kd_toko'");
    $datacari=mysqli_fetch_assoc($sqlcari);
    $nm_toko=$datacari['nm_toko'];
    $al_toko=$datacari['al_toko'];
    unset($sqlcari,$datacari); 

    $sqlcari=mysqli_query($connect,"SELECT * from pelanggan where kd_pel='$kd_pel'");
    $datacari=mysqli_fetch_assoc($sqlcari);
    $nm_pel=$datacari['nm_pel'];
    unset($sqlcari,$datacari);
    
    // Ambil data member jika ada
    $kd_member = '';
    $nm_member = '';
    $poin_earned = 0;
    $poin_saldo = 0;
    
    // Cari kd_member dari mas_jual
    $cek_member = mysqli_query($connect, "SELECT kd_member, poin_earned FROM mas_jual WHERE no_fakjual='$no_fakjual' AND tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' LIMIT 1");
    if (mysqli_num_rows($cek_member) > 0) {
      $dt_member = mysqli_fetch_assoc($cek_member);
      $kd_member = isset($dt_member['kd_member']) ? $dt_member['kd_member'] : '';
      $poin_earned = isset($dt_member['poin_earned']) ? floatval($dt_member['poin_earned']) : 0;
      
      // Ambil nama member dan poin saldo jika ada kd_member
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
    }
    mysqli_free_result($cek_member);
    unset($cek_member,$dt_member);

    $sql=mysqli_query($connect,"SELECT *,sum(dum_jual.qty_brg) AS qty_brg FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_jual' AND dum_jual.kd_toko='$kd_toko' GROUP BY dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.discitem,dum_jual.hrg_jual ORDER BY dum_jual.no_urut ASC");
    if(mysqli_num_rows($sql)>=1){
      $no=0;$subtot=0;$total=0;$def=1;
      // $Text  = $superscript1;
      $Text  = $open;  
      $Text .= spasistr('',$def).spasicenter($nm_toko,47)."\n";
      $Text .= spasicenter($al_toko,47+$def)."\n\n";
      $Text .= spasistr('',$def)."No.Struk ".spasistr('',5).":".spasistr($no_fakjual,20)."\n";
      $Text .= spasistr('',$def)."Tanggal  ".spasistr('',5).":".spasistr(gantitgl($tgl_jual),10)."\n";
      //$Text .= spasistr('',$def)."Pembeli  ".spasistr('',5).":".spasistr($nm_pel,10)."\n";
      // Tampilkan data member jika ada
      if (!empty($kd_member) && !empty($nm_member)) {
        $Text .= spasistr('',$def)."Member   ".spasistr('',5).":".spasistr($nm_member,30)."\n";
        if ($poin_earned > 0) {
          $Text .= spasistr('',$def)."Poin Dapat".spasistr('',3).":".spasistr(number_format($poin_earned, 0, ',', '.')." Poin",30)."\n";
        }
        $Text .= spasistr('',$def)."Poin Saldo".spasistr('',3).":".spasistr(number_format($poin_saldo, 0, ',', '.')." Poin",30)."\n";
      }
      $Text .= spasistr('',$def)."-----------------------------------------------\n";
      $Text .= spasistr('',$def)."No.".spasistr('',5)."Nama Barang\n";
      $Text .= spasistr('',$def).spasistr('',5).spasistr("Jml",10).spasistr("Disc%",12).spasistr("Harga",11).spasistr("SubTotal",12)."\n";
      $Text .= spasistr('',$def)."-----------------------------------------------\n"; 

      while($data=mysqli_fetch_assoc($sql)){
        $no++;
        $nm_sat=' '.$data['nm_sat1'];
        $hrg_jual=gantiti($data['hrg_jual']);

        if ($data['discitem']>0){
          $disc=$data['hrg_jual']*($data['discitem']/100);
          $subtot=round(($data['hrg_jual']-$disc)*$data['qty_brg'],0);
        }else{
          $subtot=$data['hrg_jual']*$data['qty_brg'];
        }

        $total=$total+$subtot; 
        $Text .= spasistr('',$def).spasinum($no,3).'.'.spasistr('',1).$data['nm_brg']."\n";

        $Text .= spasistr('',4+$def).spasinum($data['qty_brg'],3).spasistr($nm_sat,5).spasinum($data['discitem'],9).spasistr('',2).spasinum(gantiti($data['hrg_jual']),9).spasistr('',2).spasinum(gantiti($subtot),12)."\n";

      }
      $Text .= spasistr('',$def)."-----------------------------------------------\n"; 
      $Text .= spasistr('',7+$def).spasinum("Total",15).spasistr('',4).'Rp. '.spasinum(gantiti($total),16)."\n";
      
      // Ambil disctot, voucher, ongkir dari mas_jual
      $cek_tot = mysqli_query($connect, "SELECT tot_disc, ongkir FROM mas_jual WHERE no_fakjual='$no_fakjual' AND tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' LIMIT 1");
      $disctot = 0;
      $voucher = 0;
      $ongkir = 0;
      if ($cek_tot && mysqli_num_rows($cek_tot) > 0) {
        $dt_tot = mysqli_fetch_assoc($cek_tot);
        $disctot = isset($dt_tot['tot_disc']) ? floatval($dt_tot['tot_disc']) : 0;
        $ongkir = isset($dt_tot['ongkir']) ? floatval($dt_tot['ongkir']) : 0;
        // Voucher biasanya termasuk dalam tot_disc, atau bisa diambil dari kolom terpisah jika ada
        // Untuk sementara, anggap voucher = 0 atau bagian dari disctot
      }
      if ($cek_tot) {
        mysqli_free_result($cek_tot);
      }
      unset($cek_tot, $dt_tot);
      
      // Tampilkan disctot jika ada
      if ($disctot > 0) {
        $Text .= spasistr('',7+$def).spasinum("Disc Nota",15).spasistr('',4).'Rp. '.spasinum(gantiti(round($disctot,0)),16)."\n";
      }
      
      // Tampilkan voucher jika ada (biasanya sudah termasuk dalam disctot, tapi jika terpisah)
      // if ($voucher > 0) {
      //   $Text .= spasistr('',7+$def).spasinum("Voucher",15).spasistr('',4).'Rp. '.spasinum(gantiti(round($voucher,0)),16)."\n";
      // }
      
      // Tampilkan ongkir jika ada
      if ($ongkir > 0) {
        $Text .= spasistr('',7+$def).spasinum("Ongkir",15).spasistr('',4).'Rp. '.spasinum(gantiti(round($ongkir,0)),16)."\n";
      }
      
      if($kd_bayar=="TUNAI"){
        $Text .= spasistr('',7+$def).spasinum("Uang Tunai",15).spasistr('',4).'Rp. '.spasinum(gantiti($bayar),16)."\n";
        $Text .= spasistr('',7+$def).spasinum("Kembali",15).spasistr('',4).'Rp. '.spasinum(gantiti($susuk),16)."\n";
      }else{
        $Text .= spasistr('',7+$def).spasinum("Uang Tunai",15).spasistr('',4).'Rp. '.spasinum(gantiti($bayar),16)."\n";
        $Text .= spasistr('',7+$def).spasinum("Kekurangan",15).spasistr('',4).'Rp. '.spasinum(gantiti($saldohut),16)."\n";
        $Text .= spasistr('',7+$def).spasinum("Jatuh Tempo",15).spasistr('',4).'Rp. '.spasinum(gantitgl($tgl_jt),16)."\n";
      }
      ////$Text .= spasistr('',$def).$no.spasistr('',2)."item".spasistr('',1).spasistr('Pembayaran',11).$kd_bayar."\n\n";
      $Text .= "\n";
      $Text .= spasicenter('BARANG YG.SUDAH DIBELI TDK BISA DIKEMBALIKAN',46+$def)."\n";
      // $Text .= spasicenter('*TERIMA KASIH*',47+$def)."\n\n\n\n\n\n\n\n\n\n\n";
      $Text .= spasicenter('*TERIMA KASIH*',47+$def)."\n\n\n\n\n\n";
      $Text .= $cutPaper;
      
      // Cek apakah fungsi printer tersedia
      if (function_exists('printer_open')) {
        // Definisikan konstanta PRINTER_MODE jika belum ada
        if (!defined('PRINTER_MODE')) {
          define('PRINTER_MODE', 2); // PRINTER_MODE = 2 untuk RAW mode
        }
        
        // Gunakan call_user_func untuk menghindari linter error
        //$printer_name = "GP-80250N Series"; // Alternatif 1
        //$printer_name = "POS-80C"; // Alternatif 2
        $printer_name = "GP-80220(Cut) Series"; // Printer default yang digunakan sebelumnya
        
        $printer = call_user_func('printer_open', $printer_name);
        
        if ($printer) {
          try {
            call_user_func('printer_set_option', $printer, PRINTER_MODE, "RAW");
            call_user_func('printer_write', $printer, $Text);    
            call_user_func('printer_close', $printer);
            // Log success untuk debugging
            error_log("Thermal printing berhasil: Nota $no_fakjual dicetak ke printer $printer_name");
          } catch (Exception $e) {
            error_log("Error saat mencetak ke printer: " . $e->getMessage());
            call_user_func('printer_close', $printer);
          }
        } else {
          // Jika printer tidak ditemukan, coba printer alternatif
          $alt_printers = ["GP-80250N Series", "POS-80C", "ZJ-80"];
          $printed = false;
          
          foreach ($alt_printers as $alt_printer) {
            $printer = call_user_func('printer_open', $alt_printer);
            if ($printer) {
              try {
                call_user_func('printer_set_option', $printer, PRINTER_MODE, "RAW");
                call_user_func('printer_write', $printer, $Text);    
                call_user_func('printer_close', $printer);
                error_log("Thermal printing berhasil dengan printer alternatif: $alt_printer");
                $printed = true;
                break;
              } catch (Exception $e) {
                error_log("Error saat mencetak ke printer $alt_printer: " . $e->getMessage());
                call_user_func('printer_close', $printer);
              }
            }
          }
          
          if (!$printed) {
            // Jika semua printer gagal, log error
            error_log("Semua printer tidak ditemukan atau tidak dapat dibuka. Printer yang dicoba: $printer_name, " . implode(", ", $alt_printers));
          }
        }
      } else {
        // Jika extension printer tidak tersedia, log error
        error_log("PHP Printer extension tidak tersedia. Pastikan extension php_printer.dll sudah diaktifkan di php.ini");
      }
    }
    
    // File ini di-include oleh f_jual_cetnota.php
    // Output buffering dan JSON encoding ditangani oleh file yang meng-include
    // Tidak perlu ob_get_contents, ob_end_clean, atau json_encode di sini
?>