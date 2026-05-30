<?php
  // Prevent direct access - must be POST request
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: text/html; charset=UTF-8');
    header('Location: f_jual.php');
    exit;
  }
  
  ob_start();
  error_reporting(0);
  ini_set('display_errors', 0);

include_once 'config.php';
date_default_timezone_set('Asia/Jakarta');
session_start();
$kd_toko      = $_SESSION['id_toko'];
$id_user      = $_SESSION['id_user'];
$tgl_jual     = isset($_POST['tgl_jual']) ? $_POST['tgl_jual'] : '';
$no_fakjual   = isset($_POST['no_fakjuals']) ? $_POST['no_fakjuals'] : '';
$kd_pel       = isset($_POST['kd_pel_byr']) ? $_POST['kd_pel_byr'] : '';
$kd_member    = isset($_POST['kd_member_byr']) ? $_POST['kd_member_byr'] : '';
$kd_bayar     = isset($_POST['kd_bayar']) ? $_POST['kd_bayar'] : '';
$byr_jual     = backnumdes(isset($_POST['byr_awal']) ? $_POST['byr_awal'] : '0');//**Tagihan penjualan awal
$byr_tot      = backnumdes(isset($_POST['tot_belanja']) ? $_POST['tot_belanja'] : '0');//**Tagihan awal + disc + ongkir
$bayar        = backnumdes(isset($_POST['bayar']) ? $_POST['bayar'] : '0');//dibayar sejumlah tagihan    
$susuk        = backnumdes(isset($_POST['kembali']) ? $_POST['kembali'] : '0');    
$disctot      = backnumdes(isset($_POST['disctot']) ? $_POST['disctot'] : '0'); // Discount nota
$disctotit    = backnumdes(isset($_POST['tdiscitem1']) ? $_POST['tdiscitem1'] : '0');// Discount item
$voucher      = backnumdes(isset($_POST['voucher']) ? $_POST['voucher'] : '0');// voucher
$disc_member  = backnumdes(isset($_POST['disc_member_hidden']) ? $_POST['disc_member_hidden'] : '0');// Diskon member
$ongkir       = backnumdes(isset($_POST['ongkir']) ? $_POST['ongkir'] : '0');
$tf           = isset($_POST['pil_tf']) ? $_POST['pil_tf'] : '';
$tgl_jt       = isset($_POST['tgl_jtnota']) ? $_POST['tgl_jtnota'] : '';
$pil_cetak    = isset($_POST['pil_cetak']) ? $_POST['pil_cetak'] : '';
$poin_earned  = isset($_POST['poin_earned_hidden']) ? floatval($_POST['poin_earned_hidden']) : 0; // Poin yang didapat dari transaksi
// Validasi server-side: setiap kelipatan Rp 50.000 mendapat 1 poin
$poin_earned = floor($byr_tot / 50000);
$poin_redeem  = isset($_POST['poin_redeem']) ? floatval(str_replace('.', '', $_POST['poin_redeem'])) : 0; // Jumlah poin yang ditukar
$poin_redeem_value = isset($_POST['poin_redeem_hidden']) ? floatval($_POST['poin_redeem_hidden']) : 0; // Nilai rupiah dari poin yang ditukar
$d            = false;
$tghi         = date("Y-m-d H:i:s");   
  
//echo $voucher;
$conbayar=opendtcek();              


//CARI NOURUT RETUR
$cekret=mysqli_query($conbayar,"SELECT no_urutretur FROM retur_jual WHERE no_fakjual='$no_fakjual'");
if(mysqli_num_rows($cekret)>=1){
  $dtret=mysqli_fetch_assoc($cekret);
  $no_urutretur=$dtret['no_urutretur'];
}else {
  $no_urutretur=0;
}

$copy = 1; // Default value
$kode_seting_toko = 1; // Nilai counter saat ini pada tabel seting

$sq_set=mysqli_query($conbayar,"SELECT * FROM seting");
while($dt_set=mysqli_fetch_assoc($sq_set)){
  if ($dt_set['nm_per']=='COPY'){
    $copy=$dt_set['kode'];  
  }
  if ($dt_set['nm_per']==$kd_toko){
    $kode_seting_toko=(int)$dt_set['kode'];  
  }
}
mysqli_free_result($sq_set);unset($dt_set);

// Cegah lompat nomor nota:
// - Alur normal (tanpa panding): counter dinaikkan saat bayar.
// - Alur panding: counter sudah dinaikkan saat panding, jadi saat bayar tidak dinaikkan lagi.
$counter_nota = 0;
$pecah_nota = explode('-', $no_fakjual);
$suffix_nota = end($pecah_nota); // format umum: {id_user}{counter}
$id_user_str = (string)$id_user;
if ($suffix_nota !== false && strpos($suffix_nota, $id_user_str) === 0){
  $counter_nota = (int)substr($suffix_nota, strlen($id_user_str));
}

if ($counter_nota <= 0){
  // Fallback jika format nomor nota tidak sesuai ekspektasi
  $counter_nota = (int)$kode_seting_toko;
}

if ($counter_nota >= (int)$kode_seting_toko){
  $nofak_awal = $counter_nota + 1;
  mysqli_query($conbayar,"UPDATE seting SET kode='$nofak_awal' WHERE nm_per='$kd_toko'");
}

//update simpan pada dum_jual
mysqli_query($conbayar,"UPDATE dum_jual set bayar='SUDAH',tgl_jt='$tgl_jt',kd_pel='$kd_pel',kd_bayar='$kd_bayar',trf='$tf' WHERE no_fakjual='$no_fakjual' AND tgl_jual='$tgl_jual' AND kd_toko='$kd_toko'");

$saldohut=$byr_tot-$bayar;  
if ($saldohut<0){$saldohut=0;}

if ($saldohut==0){
  $ket_bayar="LUNAS";
} else {
  $ket_bayar="BELUM";
}

//jika ada diskon total save ke dum_jual dengan prosentansi proposional;
$awaldisc=$disctot/$byr_jual; 
$disckov=$awaldisc*100;
$awaldiscvo=$voucher/$byr_jual; 
$disckovvo=$awaldiscvo*100;
$tot_discitem=0;$disc=0;$laba=0;
if($disctot>0 AND $disctotit==0){
 
  $ceksql=mysqli_query($conbayar,"SELECT * from dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko' order by no_urut");
  //echo '$byr_jual='.$byr_jual;
  while($datahit=mysqli_fetch_assoc($ceksql)){
    $no_urut=$datahit['no_urut'];
    $disc=$datahit['hrg_jual']-($datahit['hrg_jual']*($disckov/100));
    $laba=($disc-$datahit['hrg_beli'])*$datahit['qty_brg'];
    $tot_discitem=$tot_discitem+(($datahit['hrg_jual']-$disc)*$datahit['qty_brg']); 
    mysqli_query($conbayar,"UPDATE dum_jual set discitem='$disckov',laba='$laba',discrp='0.00',discvo='0' WHERE no_urut='$no_urut'"); 
  }
  $tot_discitem=round($tot_discitem,2);
  unset($datahit,$ceksql);
} 

if($disctot==0 AND $disctotit>0){
   $ceksql=mysqli_query($conbayar,"SELECT * from dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko'");
   $tot=0;$tot_discitem=0;
   while($datahit=mysqli_fetch_assoc($ceksql)){
     $tot=$datahit['discrp']*$datahit['qty_brg']; 
     $tot_discitem=$tot_discitem+$tot;
     $hrgnet=$datahit['hrg_jual']-$datahit['discrp']; 
     $laba=($hrgnet-$datahit['hrg_beli'])*$datahit['qty_brg'];
     $no_urut=$datahit['no_urut'];
     mysqli_query($conbayar,"UPDATE dum_jual set laba='$laba',discitem=0,discvo='0' WHERE no_urut='$no_urut'"); 
   }
   unset($datahit,$ceksql);
} 

if($disctot>0 AND $disctotit>0){
   $ceksql=mysqli_query($conbayar,"SELECT * from dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko'");
   $tot_discitem=0;$x=0;$y=0;
   while($datahit=mysqli_fetch_assoc($ceksql)){
     $no_urut=$datahit['no_urut'];
     $discrp=$datahit['discrp'];
     $discitem=$datahit['hrg_jual']*$disckov/100;
     $tdisc=$datahit['hrg_jual']-($discitem+$discrp);
     $laba=($tdisc-$datahit['hrg_beli'])*$datahit['qty_brg'];

     $x=$x+($discitem*$datahit['qty_brg']);
     $y=$y+($discrp*$datahit['qty_brg']);
     $tot_discitem=$x+$y;
     mysqli_query($conbayar,"UPDATE dum_jual set laba='$laba',discitem='$disckov',discvo='0' WHERE no_urut='$no_urut'"); 
   }   
   unset($datahit,$ceksql);
} 

if($disctot==0 AND $disctotit==0){
  $tot_discitem=0;
  $ceksql=mysqli_query($conbayar,"SELECT * from dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko'");
   while($datahit=mysqli_fetch_assoc($ceksql)){
     $no_urut=$datahit['no_urut'];
     $laba=($datahit['hrg_jual']-$datahit['hrg_beli'])*$datahit['qty_brg'];
     mysqli_query($conbayar,"UPDATE dum_jual set laba='$laba',discitem='0',discrp='0',discvo='0' WHERE no_urut='$no_urut'"); 
   }
   unset($datahit,$ceksql);
}  

if($voucher>0){
  $disc=0;$laba=0;$tot_discitem=0;$dicvoucer=0;$x=0;$y=0;$z=0;
  $ceksql=mysqli_query($conbayar,"SELECT * from dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko' order by no_urut");
  //echo '$byr_jual='.$byr_jual;

  while($datahit=mysqli_fetch_assoc($ceksql)){
    $no_urut=$datahit['no_urut'];
    $discrp=$datahit['discrp'];
    $discitem=$datahit['hrg_jual']*$disckov/100;
    $dicvoucer=$datahit['hrg_jual']*$disckovvo/100;
    $disc=$datahit['hrg_jual']-($dicvoucer+$discitem+$discrp);
    $laba=($disc-$datahit['hrg_beli'])*$datahit['qty_brg'];
    
    mysqli_query($conbayar,"UPDATE dum_jual set discvo='$disckovvo',laba='$laba' WHERE no_urut='$no_urut'"); 
    $x=$x+($discitem*$datahit['qty_brg']);
    $y=$y+($discrp*$datahit['qty_brg']);
    $z=$z+($dicvoucer*$datahit['qty_brg']);
    $tot_discitem=$x+$y+$z;
  }
  $tot_discitem=round($tot_discitem,2);
  unset($datahit,$ceksql);
}
//------------
// Tambahkan diskon member ke tot_discitem
$tot_discitem = $tot_discitem + $disc_member;
$tot_discitem = round($tot_discitem, 2);
//echo $tot_discitem;

//ambil tanggal 
$totlaba=0;$tot_sale=0;
$connect1 = opendtcek();
$ceksql=mysqli_query($connect1,"SELECT * from dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko' order by no_urut");
while($datahit=mysqli_fetch_assoc($ceksql)){
 // $tgl_jual=$datahit['tgl_jual'];
 $tgl_jth=gantitglsave($datahit['tgl_jt']);
 $no_urut=$datahit['no_urut'];
 $totlaba=$totlaba+$datahit['laba'];

 //untuk mas_jual tot_jual harus sebelum disckon
 $tot_sale=$tot_sale+($datahit['hrg_jual']*$datahit['qty_brg']);

 mysqli_query($connect1,"UPDATE dum_jual set bayar='SUDAH',panding=false,kd_pel='$kd_pel',kd_bayar='$kd_bayar',trf='$tf' WHERE no_urut='$no_urut'");
}	
mysqli_close($connect1);
unset($datahit);unset($ceksql);
//---------------------------------

$update=0;$d=false;
$error_msg=''; // Variabel untuk menyimpan error message        
$defmodal=($tot_sale-$tot_discitem)-$totlaba; 

// Escape variabel untuk query cek
$kd_toko_cek = mysqli_real_escape_string($conbayar, $kd_toko);
$no_fakjual_cek = mysqli_real_escape_string($conbayar, $no_fakjual);
$tgl_jual_cek = mysqli_real_escape_string($conbayar, $tgl_jual);

// Perbaiki query cek: tambahkan tgl_jual untuk memastikan deteksi yang tepat
$cek=mysqli_query($conbayar,"SELECT * FROM mas_jual WHERE kd_toko='$kd_toko_cek' AND no_fakjual='$no_fakjual_cek' AND tgl_jual='$tgl_jual_cek'");

//ke atas ok

if(mysqli_num_rows($cek)>=1){ //** data ditemukan
  $data=mysqli_fetch_assoc($cek);  
  $no_urutx=$data['no_urut'];
  $simpandt=opendtcek();
  
  // Escape variabel untuk keamanan
  $ket_bayar_esc = mysqli_real_escape_string($simpandt, $ket_bayar);
  $kd_bayar_esc = mysqli_real_escape_string($simpandt, $kd_bayar);
  $kd_pel_esc = mysqli_real_escape_string($simpandt, $kd_pel);
  $kd_member_esc = mysqli_real_escape_string($simpandt, $kd_member);
  $tf_esc = mysqli_real_escape_string($simpandt, $tf);
  $tgl_jt_esc = mysqli_real_escape_string($simpandt, $tgl_jt);
  
  $d=mysqli_query($simpandt,"UPDATE mas_jual set tot_jual='$tot_sale',tot_disc='$tot_discitem',tot_laba='$totlaba',ket_bayar='$ket_bayar_esc',kd_bayar='$kd_bayar_esc',bayar_uang='$bayar',susuk_uang='$susuk',cetak='0',ongkir='$ongkir',saldo_hutang='$saldohut',tgl_jt='$tgl_jt_esc',kd_pel='$kd_pel_esc',trf='$tf_esc',kd_member='$kd_member_esc',poin_earned='$poin_earned' WHERE no_urut='$no_urutx'");
  // Pastikan $d adalah boolean true/false
  $d = ($d !== false);
  
  // Log error jika UPDATE gagal dan simpan error untuk ditampilkan
  if (!$d) {
    $error_msg = mysqli_error($simpandt);
    error_log("GAGAL UPDATE mas_jual: " . $error_msg . " | no_fakjual: $no_fakjual | tgl_jual: $tgl_jual | no_urut: $no_urutx");
  }
  
  unset($data);

  if ($kd_bayar=='TEMPO'){
    //**CEK DAHULU PADA MAS_JUAL_HUTANG apakah angsuran TEMPO/TUNAI
    $carih=mysqli_query($simpandt,"SELECT * FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko' ORDER BY no_urut ASC LIMIT 1");
    if(mysqli_num_rows($carih)>0){
      $dat=mysqli_fetch_assoc($carih);
      $no_uruts=$dat['no_urut'];
      $d1=mysqli_query($simpandt,"UPDATE mas_jual_hutang SET tgl_jt='$tgl_jt',totjual='$byr_tot',saldo_awal='$byr_tot',byr_hutang='$bayar',
      saldo_hutang='$saldohut',ket='$ket_bayar',kd_pel='$kd_pel',trf='$tf' WHERE no_urut='$no_uruts'"); 
      // Pastikan $d tetap true jika query utama sudah berhasil sebelumnya
      if ($d && $d1 !== false) {
        $d = true;
      } else {
        $d = false;
      }
    }
    unset($carih,$dat);

    $carihut=mysqli_query($conbayar,"SELECT * from mas_jual_hutang WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko' ORDER BY no_urut ASC");
    if (mysqli_num_rows($carihut)>=1) {
      //echo 'ketemu';
      //**Jika sudah ada pembayaran harus UPDATE pembayaran
      // cek apakah tagihan total > pembayaran angsuran yg sdh dilakukan    
        $x=0;$saldoawal=0;$bayard=0;$byrmasuk=0;$modal=0;$setor=0;$totmodal=0; 
        
        if ($saldohut<0){$saldohut=0;}

        if ($saldohut==0){
          $ket_bayar="LUNAS";
        } else {
          $ket_bayar="BELUM";
        }
        $saldoawal=$saldohut;
        while ($dcarihut=mysqli_fetch_assoc($carihut)){
          $setor    = $setor+$dcarihut['byr_hutang'];
          $no_urut  = $dcarihut['no_urut'];
          if($setor <= $defmodal){
              $modalmsk = $dcarihut['byr_hutang'];
              $labamsk  = 0;
              $totmodal = $totmodal+$modalmsk; 
              // echo '$setor'.$setor.'<br>';
              // echo '$defmodal'.$defmodal.'<br>';
              // echo '$modalsmk'.$modalmsk.'<br>';
          }else{   
              if($totmodal <= $defmodal){
                  $modalmsk = $defmodal-$totmodal;
                  $labamsk  = $dcarihut['byr_hutang']-$modalmsk;
              }else{
                  $modalmsk = 0;
                  $labamsk  = $dcarihut['byr_hutang'];
              }
              $totmodal = $totmodal+$modalmsk;
              // echo '$setor'.$setor.'<br>';
              // echo '$defmodal'.$defmodal.'<br>';
              // echo '$modalsmk'.$modalmsk.'<br>';
          }
          $x++;
          
          if ($x==1){
            $d1=mysqli_query($conbayar,"UPDATE mas_jual_hutang SET tgl_jt='$tgl_jt',totjual='$byr_tot',saldo_awal='$byr_tot',byr_hutang='$bayar',
            saldo_hutang='$saldohut',ket='$ket_bayar',kd_pel='$kd_pel',modal='$modalmsk',laba='$labamsk',trf='$tf' WHERE no_urut='$no_urut'"); 
            $d2=mysqli_query($conbayar,"UPDATE mas_jual SET ket_bayar='$ket_bayar',saldo_hutang='$saldohut',trf='$tf' WHERE no_fakjual='$no_fakjual'"); 
            // Pastikan $d tetap true jika query utama sudah berhasil sebelumnya
            if ($d && $d1 !== false && $d2 !== false) {
              $d = true;
            } else {
              $d = false;
            }

            $xx=mysqli_query($conbayar,"SELECT SUM(modal) as jmodal FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual'");
            $dtr=mysqli_fetch_assoc($xx);
            $modal    = $dtr['jmodal'];
            unset($xx,$dtr);
          } else {    
            $bayard=$dcarihut['byr_hutang'];
            
            $sld_hut=$saldoawal-$bayard;  
            $d1=mysqli_query($conbayar,"UPDATE mas_jual_hutang SET tgl_jt='$tgl_jt',totjual='$byr_tot',saldo_awal='$saldoawal',byr_hutang='$bayard',saldo_hutang='$sld_hut',ket='$ket_bayar',kd_pel='$kd_pel',modal='$modalmsk',laba='$labamsk',trf='$tf' WHERE no_urut='$no_urut'");    
            $d2=mysqli_query($conbayar,"UPDATE mas_jual SET ket_bayar='$ket_bayar',saldo_hutang='$sld_hut',trf='$tf' WHERE no_fakjual='$no_fakjual'"); 
            // Pastikan $d tetap true jika query utama sudah berhasil sebelumnya
            if ($d && $d1 !== false && $d2 !== false) {
              $d = true;
            } else {
              $d = false;
            }

            $saldoawal=$saldoawal-$bayard;
          }
          
        }
      $update=1;  
      $simpanup='simpanup;'.$no_fakjual;  
    } else {
      // jika tidak ditemukan mas_jual_hutang untuk TEMPO, insert data baru
      // Catatan: mas_jual sudah di-UPDATE di baris 205, jadi tidak perlu INSERT lagi di sini
      $saldohut=$byr_tot-$bayar;
      if($defmodal>=$bayar){
        $modalmsk=$bayar;
        $labamsk =0;
      }else{
        $modalmsk=$defmodal;
        $labamsk =$bayar-$defmodal;
      }  
      $d1=mysqli_query($conbayar,"INSERT INTO mas_jual_hutang values('','$kd_pel','$no_fakjual','$tgl_jual','$tgl_jual','$byr_tot','$byr_tot','$bayar','$saldohut','$ket_bayar','$kd_toko','$tgl_jt','$modalmsk','$labamsk','$tf','$no_urutretur')");
      // Pastikan $d tetap true jika query utama sudah berhasil sebelumnya
      if ($d && $d1 !== false) {
        $d = true;
      } else {
        $d = false;
      }
      
      // Proses poin: tambah poin yang didapat dan kurangi poin yang ditukar
      if($kd_member != ''){
        // Kurangi poin yang ditukar terlebih dahulu
        if($poin_redeem > 0){
          mysqli_query($conbayar,"UPDATE member SET poin = poin - $poin_redeem WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
          $cekpoin = mysqli_query($conbayar,"SELECT poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
          $datapoin = mysqli_fetch_assoc($cekpoin);
          $poin_saldo = isset($datapoin['poin']) ? $datapoin['poin'] : 0;
          mysqli_query($conbayar,"INSERT INTO member_poin_history values('','$kd_member','$no_fakjual','$tgl_jual','0','$poin_redeem','$poin_saldo','Penukaran Poin Transaksi - $no_fakjual','$kd_toko','$tghi')");
          mysqli_free_result($cekpoin);
          unset($datapoin);
        }
        // Tambah poin yang didapat dari transaksi
        if($poin_earned > 0){
          mysqli_query($conbayar,"UPDATE member SET poin = poin + $poin_earned WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
          $cekpoin = mysqli_query($conbayar,"SELECT poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
          $datapoin = mysqli_fetch_assoc($cekpoin);
          $poin_saldo = isset($datapoin['poin']) ? $datapoin['poin'] : 0;
          mysqli_query($conbayar,"INSERT INTO member_poin_history values('','$kd_member','$no_fakjual','$tgl_jual','$poin_earned','0','$poin_saldo','Transaksi Penjualan - $no_fakjual','$kd_toko','$tghi')");
          mysqli_free_result($cekpoin);
          unset($datapoin);
        }
      }    
    }  
  } else {
    // jika edit menjadi cash hapus data lama
    $d_delete=mysqli_query($conbayar,"DELETE FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko'");
    // Pastikan $d tetap true jika query utama sudah berhasil
    if ($d && $d_delete !== false) {
      $d = true;
    } else {
      $d = false;
    }
  }     
  mysqli_close($simpandt);
} else {
  $saldohut=$byr_tot-$bayar;
  if ($saldohut<0){$saldohut=0;}
  if ($saldohut==0){
    $ket_bayar="LUNAS";
  } else {
    $ket_bayar="BELUM";
  }
  // Validasi variabel penting
  if (empty($tgl_jual) || empty($kd_toko) || empty($no_fakjual)) {
    $error_msg = "Variabel penting kosong: tgl_jual=$tgl_jual, kd_toko=$kd_toko, no_fakjual=$no_fakjual";
    error_log("VALIDASI GAGAL: " . $error_msg);
    $d = false;
  } else {
    // Escape variabel untuk keamanan
    $tgl_jual_esc = mysqli_real_escape_string($conbayar, $tgl_jual);
    $kd_toko_esc = mysqli_real_escape_string($conbayar, $kd_toko);
    $no_fakjual_esc = mysqli_real_escape_string($conbayar, $no_fakjual);
    $kd_pel_esc = mysqli_real_escape_string($conbayar, $kd_pel);
    $kd_member_esc = mysqli_real_escape_string($conbayar, $kd_member);
    $ket_bayar_esc = mysqli_real_escape_string($conbayar, $ket_bayar);
    $kd_bayar_esc = mysqli_real_escape_string($conbayar, $kd_bayar);
    $tf_esc = mysqli_real_escape_string($conbayar, $tf);
    $tgl_jt_esc = mysqli_real_escape_string($conbayar, $tgl_jt);
    
    // Pastikan nilai numerik tidak NULL
    $tot_sale = isset($tot_sale) ? $tot_sale : 0;
    $tot_discitem = isset($tot_discitem) ? $tot_discitem : 0;
    $totlaba = isset($totlaba) ? $totlaba : 0;
    $bayar = isset($bayar) ? $bayar : 0;
    $susuk = isset($susuk) ? $susuk : 0;
    $poin_earned = isset($poin_earned) ? $poin_earned : 0;
    $ongkir = isset($ongkir) ? $ongkir : 0;
    $saldohut = isset($saldohut) ? $saldohut : 0;
    
    // Gunakan INSERT dengan nama kolom eksplisit untuk menghindari error "Column count doesn't match"
    // Struktur berdasarkan backup yang berhasil + kolom baru (kd_member, poin_earned)
    // Urutan: tgl_jual, kd_toko, no_fakjual, tot_jual, tot_disc, tot_laba, ket_bayar, kd_bayar, bayar_uang, susuk_uang, kd_pel, cetak, kd_member, poin_earned, ongkir, saldo_hutang, tgl_jt, trf, execut
    $d=mysqli_query($conbayar,"INSERT INTO mas_jual (tgl_jual,kd_toko,no_fakjual,tot_jual,tot_disc,tot_laba,ket_bayar,kd_bayar,bayar_uang,susuk_uang,kd_pel,cetak,kd_member,poin_earned,ongkir,saldo_hutang,tgl_jt,trf,execut) VALUES('$tgl_jual_esc','$kd_toko_esc','$no_fakjual_esc','$tot_sale','$tot_discitem','$totlaba','$ket_bayar_esc','$kd_bayar_esc','$bayar','$susuk','$kd_pel_esc','0','$kd_member_esc','$poin_earned','$ongkir','$saldohut','$tgl_jt_esc','$tf_esc','$tghi')");
    
    // Jika masih error, coba dengan struktur lama tanpa kd_member dan poin_earned
    if (!$d) {
      $error_msg_temp = mysqli_error($conbayar);
      error_log("INSERT dengan kd_member gagal, mencoba struktur lama: " . $error_msg_temp);
      // Coba INSERT tanpa kd_member dan poin_earned (struktur lama)
      $d=mysqli_query($conbayar,"INSERT INTO mas_jual (tgl_jual,kd_toko,no_fakjual,tot_jual,tot_disc,tot_laba,ket_bayar,kd_bayar,bayar_uang,susuk_uang,kd_pel,cetak,ongkir,saldo_hutang,tgl_jt,trf,execut) VALUES('$tgl_jual_esc','$kd_toko_esc','$no_fakjual_esc','$tot_sale','$tot_discitem','$totlaba','$ket_bayar_esc','$kd_bayar_esc','$bayar','$susuk','$kd_pel_esc','0','$ongkir','$saldohut','$tgl_jt_esc','$tf_esc','$tghi')");
      if ($d) {
        // Jika berhasil dengan struktur lama, update kd_member dan poin_earned secara terpisah
        if (!empty($kd_member_esc) || $poin_earned > 0) {
          mysqli_query($conbayar,"UPDATE mas_jual SET kd_member='$kd_member_esc',poin_earned='$poin_earned' WHERE no_fakjual='$no_fakjual_esc' AND tgl_jual='$tgl_jual_esc' AND kd_toko='$kd_toko_esc'");
        }
      }
    }
    // Pastikan $d adalah boolean true/false
    $d = ($d !== false);
    
    // Log error jika INSERT gagal dan simpan error untuk ditampilkan
    if (!$d) {
      $error_msg = mysqli_error($conbayar);
      error_log("GAGAL INSERT mas_jual: " . $error_msg . " | no_fakjual: $no_fakjual | tgl_jual: $tgl_jual | Query: INSERT INTO mas_jual values('','$tgl_jual_esc','$kd_toko_esc','$no_fakjual_esc','$tot_sale','$tot_discitem','$totlaba','$ket_bayar_esc','$kd_bayar_esc','$bayar','$susuk','$kd_pel_esc','$kd_member_esc','$poin_earned','$ongkir','$saldohut','$tgl_jt_esc','$tf_esc','$tghi')");
    }
  }

  // Proses poin: tambah poin yang didapat dan kurangi poin yang ditukar (hanya jika INSERT berhasil)
  if($d && $kd_member != ''){
    // Kurangi poin yang ditukar terlebih dahulu
    if($poin_redeem > 0){
      mysqli_query($conbayar,"UPDATE member SET poin = poin - $poin_redeem WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
      $cekpoin = mysqli_query($conbayar,"SELECT poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
      $datapoin = mysqli_fetch_assoc($cekpoin);
      $poin_saldo = isset($datapoin['poin']) ? $datapoin['poin'] : 0;
      mysqli_query($conbayar,"INSERT INTO member_poin_history values('','$kd_member','$no_fakjual','$tgl_jual','0','$poin_redeem','$poin_saldo','Penukaran Poin Transaksi - $no_fakjual','$kd_toko','$tghi')");
      mysqli_free_result($cekpoin);
      unset($datapoin);
    }
    // Tambah poin yang didapat dari transaksi
    if($poin_earned > 0){
      mysqli_query($conbayar,"UPDATE member SET poin = poin + $poin_earned WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
      $cekpoin = mysqli_query($conbayar,"SELECT poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko'");
      $datapoin = mysqli_fetch_assoc($cekpoin);
      $poin_saldo = isset($datapoin['poin']) ? $datapoin['poin'] : 0;
      mysqli_query($conbayar,"INSERT INTO member_poin_history values('','$kd_member','$no_fakjual','$tgl_jual','$poin_earned','0','$poin_saldo','Transaksi Penjualan - $no_fakjual','$kd_toko','$tghi')");
      mysqli_free_result($cekpoin);
      unset($datapoin);
    }
  }

  if($kd_bayar=='TEMPO'){
    // echo 'if...' .$defmodal.' '.$bayar.'<br>';
    if($defmodal>=$bayar){
      $modalmsk=$bayar;
      $labamsk =0;
    }else{
      $modalmsk=$defmodal;
      $labamsk =$bayar-$defmodal;
    }
    $d_hutang=mysqli_query($conbayar,"INSERT INTO mas_jual_hutang VALUES('','$kd_pel','$no_fakjual','$tgl_jual','$tgl_jual','$byr_tot','$byr_tot','$bayar','$saldohut','$ket_bayar','$kd_toko','$tgl_jt','$modalmsk','$labamsk','$tf','$no_urutretur')");
    // Pastikan $d tetap true jika semua query berhasil
    if ($d && $d_hutang !== false) {
      $d = true;
    } else {
      $d = false;
    }
  }
}
unset($cek);
//bawah ok

// Hanya cetak jika penyimpanan berhasil
if ($d && $pil_cetak =="CETAK"){
  $open = chr(27).chr(112).chr(48).chr(25).chr(250); //open cash drawer
  $cutPaper = chr(29) . "V" . chr(48) . chr(0); //cut paper

  $sqlcari=mysqli_query($conbayar,"SELECT * from toko where kd_toko='$kd_toko'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  $nm_toko=$datacari['nm_toko'];
  
  $al_toko=$datacari['al_toko'];
  unset($sqlcari,$datacari); 

  $sqlcari=mysqli_query($conbayar,"SELECT * from pelanggan where kd_pel='$kd_pel'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  $nm_pel=$datacari['nm_pel'];
  $alamat=$datacari['al_pel'];
  unset($sqlcari,$datacari);

  $sqlcari=mysqli_query($conbayar,"SELECT execut FROM mas_jual WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' AND kd_toko='$kd_toko'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  
  $dd=explode(" ",$datacari['execut']);
  $tgltime=gantitgl($tgl_jual)." ".$dd[1];
  unset($sqlcari,$datacari);
//   if($id_user=='23'){
  $bayar=round($bayar,0);$susuk=round($susuk,0);
    $saldohut=gantiti(round($saldohut,0));$ongkir=round($ongkir,0);$disctot=round($disctot,0);$voucher=round($voucher,0);
    $dtc=$no_fakjual.';'.$tgl_jual.';'.$kd_toko.';'.$nm_pel.';'.$alamat.';'.$tgltime.';'.$disctot.';'.$voucher.';'.$ongkir.';'.$kd_bayar.';'.$bayar.';'.$susuk.';'.$saldohut.';'.gantitgl($tgl_jt);
    ?><script type="text/javascript">cetaknota('<?=$dtc?>','<?=$copy?>')</script><?php  
//   }else{
    // $sql=mysqli_query($conbayar,"SELECT *,sum(dum_jual.qty_brg) AS qty_brg FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_jual' AND dum_jual.kd_toko='$kd_toko' GROUP BY dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.discrp,dum_jual.hrg_jual ORDER BY dum_jual.no_urut ASC");
    
    //   if(mysqli_num_rows($sql)>=1){
    //     $bayar=round($bayar,0);$susuk=round($susuk);
    //     $saldohut=round($saldohut,0);$ongkir=round($ongkir,0);$disctot=round($disctot,0);
    //     $no=0;$subtot=0;$total=0;$def=1;$ypos=145;$gdisc1=0; 
    
    //     //initial printer
    //     for ($kali=0; $kali < $copy ; $kali++) {           
    //       ?> 
            
    //         <script type="text/javascript">
    //           var printer = new Recta('2158190222', '1811')
    //           printer.open().then(function () {
    //               printer.align('center')
    //               .mode('A', true, true, true, false)
    //               .text('F A F A')
    //               .mode('A', false, false, true, false)
    //               .text('Fashion & Galery')
    //               .mode('A', false, false, false, false)
    //               .text('Komplek Pasar Ngrompak Jatisrono')
    //               .raw('\n')
    //               .print()
    //               .reset()
    //           })
              
    //           printer.open().then(function () {
    //             printer.align('left')
    //               .text('Struk   : <?=$no_fakjual?>')
    //               .text('Tanggal : <?=$tgltime?>')
    //               .print()
    //           })
    //           if ('<?=$kd_pel?>' != 'IDPEL-0'){
    //             printer.open().then(function () {
    //               printer.align('left')
    //                 .text('Pembeli : <?=$nm_pel?>')
    //                 .text('Alamat  : <?=$alamat?>')
    //                 .print()
    //             })
    //           } else {
    //             printer.open().then(function () {
    //               printer.align('left')
    //                 .text('Pembeli : <?=$nm_pel?>')
    //                 .print()
    //             })
    //           }
    //           printer.open().then(function () {
    //             printer.align('left')
    //               .text('------------------------------------------------')
    //               .text('No.   Nama Barang')
    //               .text('       Qty         Harga      Disc      SubTotal')
    //               .text('------------------------------------------------')
    //               .print()
                 
    //           })  
           
    //           <?php
    //           while($data=mysqli_fetch_assoc($sql)){
    //               $no++;
    //               $nm_sat=' '.$data['nm_sat1'];
    //               $hrg_jual=round($data['hrg_jual'],0);
                  
    //               if ($data['discrp']>0){
    //                 $subtot=($data['hrg_jual']-$data['discrp'])*$data['qty_brg'];
    //               }else{
    //                 $subtot=$data['hrg_jual']*$data['qty_brg'];
    //               }
          
    //               $qty_brg=gantitides($data['qty_brg']);   
    //               $discrp=round($data['discrp'],0);
    //               $hrg_jual=round($data['hrg_jual'],0);
    //               $total=$total+round($subtot,0); 
    //               $total=round($total,0);
    //               $subtot=round($subtot,0);
    //             ?>  
                
    //               printer.open().then(function () {
    //               printer.align('left')
    //                 .text('<?=spasinum($no.'.',4).spasistr(" ",1).spasistr($data['nm_brg'],30)?>')
    //                 .text('<?=spasistr(" ",4).spasistr($qty_brg.strtolower($nm_sat),10)." ".spasinum(gantiti($hrg_jual),12)." ".spasinum(gantiti($discrp),7)." ".spasinum(gantiti($subtot),12)?>')
    //                 .print()
    //               })
               
    //             <?php
    //           } // while 
    //           unset($data);
    //           ?>
    //              printer.open().then(function () {
    //               printer.align('left')
    //               .text('------------------------------------------------')
    //               .text('<?=spasistr(" ",16).spasistr("Total",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantiti($total),13)?>') 
    //               .print()
    //             })
    
    //             if ('<?=$disctot?>' > 0){
    //               printer.open().then(function () {
    //                 printer.align('left')
    //                 .text('<?=spasistr(" ",16).spasistr("Disc nota",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantiti($disctot),13)?>') 
    //                 .print()
    //               })
    //             }
                
    //             if ('<?=$voucher?>' > 0){
    //               printer.open().then(function () {
    //                 printer.align('left')
    //                 .text('<?=spasistr(" ",16).spasistr("Disc Voucher",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantiti(round($voucher,0)),13)?>') 
    //                 .print()
    //               })
    //             }
                
    //             if ('<?=$ongkir?>' > 0){
    //               printer.open().then(function () {
    //                 printer.align('left')
    //                 .text('<?=spasistr(" ",16).spasistr("Ongkir",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantiti($ongkir),13)?>') 
    //                 .print()
    //               })
    //             }    
                
    //             if('<?=$kd_bayar?>' == 'TUNAI'){
    //               printer.open().then(function () {
    //                 printer.align('left')
    //                 .text('<?=spasistr(" ",16).spasistr("Uang Tunai",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantiti($bayar),13)?>')
    //                 .text('<?=spasistr(" ",16).spasistr("Kembali",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantiti($susuk),13)?>')
    //                 .print()
    //               })
    //             } else {
    //               printer.open().then(function () {
    //                 printer.align('left')
    //                   .text('<?=spasistr(" ",16).spasistr("Uang Tunai",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantiti($bayar),13)?>')
    //                   .text('<?=spasistr(" ",16).spasistr("Kekurangan",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantiti($saldohut),13)?>')
    //                   .text('<?=spasistr(" ",16).spasistr("Jatuh Tempo",12).spasistr(" ",2).spasistr('Rp.',3).spasistr(" ",2).spasinum(gantitgl($tgl_jt),13)?>')
    //                 .print()
    //               })
                  
    //             }  
                 
    //             printer.open().then(function () {
    //               printer.align('center')
    //               .raw('\n')
    //               .text('TERIMA KASIH ATAS KUNJUNGAN ANDA')
    //               .feed(4)
    //               .cut(false,1)
    //               .print()
    //               .flush()
    //             })
    //           </script>    
    //         <?php
    //     }
    //   }  
      
//   }
}

// Hanya cetak jika penyimpanan berhasil
if ($d && $pil_cetak =="CETAK-CK"){

  $open = chr(27).chr(112).chr(48).chr(25).chr(250); //open cash drawer
  $cutPaper = chr(29) . "V" . chr(48) . chr(0); //cut paper

  $sqlcari=mysqli_query($conbayar,"SELECT * from toko where kd_toko='$kd_toko'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  $nm_toko=$datacari['nm_toko'];
  
  $al_toko=$datacari['al_toko'];
  unset($sqlcari,$datacari); 

  $sqlcari=mysqli_query($conbayar,"SELECT * from pelanggan where kd_pel='$kd_pel'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  $nm_pel=$datacari['nm_pel'];
  $alamat=$datacari['al_pel'];
  unset($sqlcari,$datacari);

  $sqlcari=mysqli_query($conbayar,"SELECT execut FROM mas_jual WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' AND kd_toko='$kd_toko'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  
  $dd=explode(" ",$datacari['execut']);
  $tgltime=gantitgl($tgl_jual)." ".$dd[1];
  unset($sqlcari,$datacari);
//   if($id_user=="13"){
  $bayar=round($bayar,0);$susuk=round($susuk,0);
  $saldohut=round($saldohut,0);$ongkir=round($ongkir,0);$disctot=round($disctot,0);$voucher=round($voucher,0);
  $dtc=$no_fakjual.';'.$tgl_jual.';'.$kd_toko.';'.$nm_pel.';'.$alamat.';'.$tgltime.';'.$disctot.';'.$voucher.';'.$ongkir.';'.$kd_bayar.';'.$bayar.';'.$susuk.';'.$saldohut.';'.gantitgl($tgl_jt);
    ?><script type="text/javascript">cetaknota('<?=$dtc?>','<?=$copy?>')</script><?php  
      
//   }else{
      
//   $sql=mysqli_query($conbayar,"SELECT *,sum(dum_jual.qty_brg) AS qty_brg FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_jual' AND dum_jual.kd_toko='$kd_toko' GROUP BY dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.discrp,dum_jual.hrg_jual ORDER BY dum_jual.no_urut ASC");
  
//   $bayar=round($bayar,0);$susuk=round($susuk);
//   $saldohut=round($saldohut,0);$ongkir=round($ongkir,0);$disctot=round($disctot,0);
//   $no=0;$subtot=0;$total=0;$def=1;$ypos=145;$gdisc1=0; 
    
//   if(mysqli_num_rows($sql)>=1){
//     //initial printer
//     for ($kali=0; $kali < $copy ; $kali++) { ?> 
//       <script type="text/javascript">
//         var printer = new Recta('2158190222', '1811')
//         printer.open().then(function () {
//           printer.align('center')
//           .mode('A', true, true, true, false)
//           .text('F A F A')
//           .mode('A', false, true, false, false)
//           .text('Fashion & Galery')
//           .mode('A', false, false, false, false)
//           .text('Kios Pasar Ngrompak Jatisrono')
//           .raw('\n')
//           .print()
//           .reset()  
//         })
        
//         printer.open().then(function () {
//           printer.align('left')
//             .text('Struk   : <?=$no_fakjual?>')
//             .text('Tanggal : <?=$tgltime?>')
//             .print()
//         })
//         if ('<?=$kd_pel?>' != 'IDPEL-0'){
//           printer.open().then(function () {
//             printer.align('left')
//               .text('Pembeli : <?=$nm_pel?>')
//               .text('Alamat  : <?=$alamat?>')
//               .print()
//           })
//         } else {
//           printer.open().then(function () {
//             printer.align('left')
//               .text('Pembeli : <?=$nm_pel?>')
//               .print()
//           })
//         }
//         printer.open().then(function () {
//           printer.align('left')
//             .text('--------------------------------')
//             .text('No.  Nama Barang')
//             .text(' Jml     Harga   Disc   SubTotal')
//             .text('--------------------------------')
//             .print()
//         }) <?php
//         while($data=mysqli_fetch_assoc($sql)){
//             $no++;
//             $nm_sat=' '.$data['nm_sat1'];
//             $hrg_jual=round($data['hrg_jual'],0);
            
//             if ($data['discrp']>0){
//               $subtot=($data['hrg_jual']-$data['discrp'])*$data['qty_brg'];
//             }else{
//               $subtot=$data['hrg_jual']*$data['qty_brg'];
//             }
    
//             $qty_brg=gantitides($data['qty_brg']);   
//             $discrp=round($data['discrp'],0);
//             $hrg_jual=round($data['hrg_jual'],0);
//             $total=$total+round($subtot,0); 
//             $total=round($total,0);
//             $subtot=round($subtot,0);
//           ?>  
          
//               printer.open().then(function () {
//               printer.align('left')
//               .text('<?=spasinum($no.'.',3).spasistr(" ",1).spasistr($data['nm_brg'],28)?>')
//               .text('<?=spasistr(" ",0).spasistr($qty_brg.strtolower($nm_sat),7)." ".spasinum(gantiti($hrg_jual),7)." ".spasinum(gantiti($discrp),7)." ".spasinum(gantiti($subtot),7)?>')
//               .print()
//             })
          
//           <?php
//         } // while 
//         unset($data);?>
//         printer.open().then(function () {
//           printer.align('left')
//           .text('--------------------------------')
//           .text('<?=spasistr(" ",3).spasistr("Total",12).spasistr(" ",3).'Rp. '.spasinum(gantiti($total),10)?>') 
//           .print()
//         })

//         if ('<?=$disctot?>' > 0){
//           printer.open().then(function () {
//             printer.align('left')
//             .text('<?=spasistr(" ",3).spasistr("Disc nota",12).spasistr(" ",3).'Rp. '.spasinum(gantiti($disctot),10)?>') 
//             .print()
//           })
//         }
        
//         if ('<?=$voucher?>' > 0){
//           printer.open().then(function () {
//             printer.align('left')
//             .text('<?=spasistr(" ",3).spasistr("Disc Voucher",12).spasistr(" ",3).'Rp. '.spasinum(gantiti(round($voucher,0)),10)?>') 
//             .print()
//           })
//         }
        
//         if ('<?=$ongkir?>' > 0){
//           printer.open().then(function () {
//             printer.align('left')
//             .text('<?=spasistr(" ",3).spasistr("Ongkir",12).spasistr(" ",3).'Rp. '.spasinum(gantiti($ongkir),10)?>') 
//             .print()
//           })
//         }    
        
//         if('<?=$kd_bayar?>' == 'TUNAI'){
//           printer.open().then(function () {
//             printer.align('left')
//             .text('<?=spasistr(" ",3).spasistr("Uang Tunai",12).spasistr(" ",3).'Rp. '.spasinum(gantiti($bayar),10)?>')
//             .text('<?=spasistr(" ",3).spasistr("Kembali",12).spasistr(" ",3).'Rp. '.spasinum(gantiti($susuk),10)?>')
//             .print()
//           })
//         } else {
//           printer.open().then(function () {
//             printer.align('left')
//               .text('<?=spasistr(" ",3).spasistr("Uang Tunai",12).spasistr(" ",3).'Rp. '.spasinum(gantiti($bayar),10)?>')
//               .text('<?=spasistr(" ",3).spasistr("Kekurangan",12).spasistr(" ",3).'Rp. '.spasinum(gantiti($saldohut),10)?>')
//               .text('<?=spasistr(" ",3).spasistr("Jatuh Tempo",12).spasistr(" ",3).'Rp. '.spasinum(gantitgl($tgl_jt),10)?>')
//             .print()
//           })
          
//         }  
//         printer.open().then(function () {
//             printer.align('center')
//             .raw('\n')
//             .text('TERIMA KASIH ATAS KUNJUNGAN ANDA')
//             .feed(2)
//             .cut(false,1)
//             .print()
//             .flush()
//         })
//       </script> <?php
//     }
//   } // mysqli_num_rows 
  
//   }
}

// Hanya cetak jika penyimpanan berhasil
if ($d && $pil_cetak =="CETAK-SM"){
  $sqlcari=mysqli_query($conbayar,"SELECT * from toko where kd_toko='$kd_toko'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  $nm_toko=$datacari['nm_toko'];
  
  $al_toko=$datacari['al_toko'];
  unset($sqlcari,$datacari); 

  $sqlcari=mysqli_query($conbayar,"SELECT * from pelanggan where kd_pel='$kd_pel'");
  $datacari=mysqli_fetch_assoc($sqlcari);
  $nm_pel=$datacari['nm_pel'];
  $alamat=$datacari['al_pel'];
  unset($sqlcari,$datacari);
  $jam=date('d-m-Y / H:m:s');
  
  // Ambil data member untuk print
  $kd_member_print = '';
  $nm_member_print = '';
  $poin_earned_print = 0;
  $poin_saldo_print = 0;
  
  // Cari kd_member dari mas_jual
  $cek_member_print = mysqli_query($conbayar, "SELECT kd_member, poin_earned FROM mas_jual WHERE no_fakjual='$no_fakjual' AND tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' LIMIT 1");
  if ($cek_member_print && mysqli_num_rows($cek_member_print) > 0) {
    $dt_member_print = mysqli_fetch_assoc($cek_member_print);
    $kd_member_print = isset($dt_member_print['kd_member']) ? $dt_member_print['kd_member'] : '';
    $poin_earned_print = isset($dt_member_print['poin_earned']) ? floatval($dt_member_print['poin_earned']) : 0;
    
    if (!empty($kd_member_print)) {
      $sqlmember_print=mysqli_query($conbayar,"SELECT nm_member, poin FROM member WHERE kd_member='$kd_member_print' AND kd_toko='$kd_toko' LIMIT 1");
      if ($sqlmember_print && mysqli_num_rows($sqlmember_print) > 0) {
        $datamember_print=mysqli_fetch_assoc($sqlmember_print);
        $nm_member_print = isset($datamember_print['nm_member']) ? $datamember_print['nm_member'] : '';
        $poin_saldo_print = isset($datamember_print['poin']) ? floatval($datamember_print['poin']) : 0;
      }
      if ($sqlmember_print) {
        mysqli_free_result($sqlmember_print);
      }
      unset($sqlmember_print,$datamember_print);
    }
  }
  if ($cek_member_print) {
    mysqli_free_result($cek_member_print);
  }
  unset($cek_member_print,$dt_member_print);
  
  // Format dtc sesuai dengan script lama untuk get_nota.php
  $dtc = $no_fakjual.';'.$tgl_jual.';'.$kd_toko.';'.$nm_pel.';'.$alamat.';'.$jam.';'.$disctot.';'.$voucher.';'.$ongkir.';'.$kd_bayar.';'.$bayar.';'.$susuk.';'.$saldohut.';'.gantitgl($tgl_jt);
  ?>
  <script>
    // Print langsung menggunakan metode script lama yang terbukti bekerja
    // Mengirim JSON data langsung ke print server tanpa membuka window
    async function fetchJSON(url, options = {}) {
      try {
        const res = await fetch(url, options);
        try {
          return await res.json();
        } catch (jsonErr) {
          const raw = await res.text();
          console.error("❌ JSON Parse Error:", jsonErr.message);
          console.log("📜 RAW RESPONSE:\n", raw);
          throw jsonErr;
        }
      } catch (err) {
        console.error("❌ Fetch Error:", err);
        throw err;
      }
    }
    
    fetch("get_nota.php?dts=<?=addslashes($dtc)?>")
    .then(res => res.json())
    .then(data => {
      console.log("✅ Parsed JSON:", data);
      fetch("http://localhost:3000/print/nota", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data.data)
      })
      .then(res => {
        const ct = res.headers.get("content-type") || "";
        if (!res.ok) {
          if (ct.includes("application/json")) {
            return res.json().then(obj => Promise.reject({ status: res.status, body: obj }));
          } else {
            return res.text().then(txt => Promise.reject({ status: res.status, body: txt }));
          }
        }
        if (ct.includes("application/json")) return res.json();
        return res.text();
      })
      .then(result => {
        console.log("✅ Response from print bridge:", result);
      })
      .catch(err => {
        console.error("❌ Print request failed:", err);
        // Fallback: coba print server alternatif
        fetch("get_nota.php?dts=<?=addslashes($dtc)?>")
        .then(res => res.json())
        .then(data => {
          fetch("http://localhost:8080/print/nota", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data.data)
          })
          .then(res => {
            if (res.ok) {
              console.log("✅ Nota dikirim ke print server alternatif");
            }
          })
          .catch(err => {
            console.error("❌ Print server alternatif juga gagal:", err);
          });
        });
      });
    })
    .catch(err => {
      console.error("❌ Error fetching nota data:", err);
    });
  </script>
  <?php 
}  
//mysqli_close($conbayar);

if ($d)
{
  if ($update==1) {
   ?><script type="text/javascript">popnew_ok('Berhasil disimpan silahkan update piutang');</script><?php
  } else {
   $msg_cetak = (!empty($pil_cetak)) ? 'Berhasil disimpan....' . $pil_cetak : 'Berhasil disimpan';
   ?><script type="text/javascript">popnew_ok('<?= $msg_cetak; ?>');</script><?php
  }
} else { 
  // Tampilkan error detail untuk debugging
  $error_detail = !empty($error_msg) ? " Error SQL: " . htmlspecialchars($error_msg, ENT_QUOTES) : "";
  $debug_info = "no_fakjual: " . htmlspecialchars($no_fakjual, ENT_QUOTES) . " | tgl_jual: " . htmlspecialchars($tgl_jual, ENT_QUOTES) . " | kd_toko: " . htmlspecialchars($kd_toko, ENT_QUOTES);
  // Escape untuk JavaScript dengan json_encode untuk menghindari syntax error
  $debug_info_js = json_encode($debug_info);
  $error_msg_js = !empty($error_msg) ? json_encode($error_msg) : '""';
  $error_detail_js = json_encode($error_detail);
  ?><script type="text/javascript">
    console.error('Gagal menyimpan data.', <?=$debug_info_js?>);
    <?php if (!empty($error_msg)) { ?>
    console.error('Error SQL:', <?=$error_msg_js?>);
    <?php } ?>
    popnew_error('Data gagal disimpan.' + <?=$error_detail_js?> + ' Silakan cek console browser (F12) untuk detail error atau hubungi administrator.');
  </script><?php
}    
?><script> kosongkan2();aktif();</script><?php
  $html = ob_get_contents(); 
  ob_end_clean();
  
  // Set header JSON before any output
  if (!headers_sent()) {
    header('Content-Type: application/json; charset=UTF-8');
  }
  
  echo json_encode(array('hasil'=>$html), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;