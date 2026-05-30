<?php 
session_start();
include 'config.php';
$tgl_retur    = $_POST['tgl_retur'];
$no_returjual = $_POST['no_returjual'];
$kd_toko      = $_SESSION['id_toko'];
$id_user      = $_SESSION['id_user'];
$hub          = opendtcek();
$d            = false;
$c = mysqli_query($hub, "SELECT retur_jual.*,dum_jual.kd_brg,dum_jual.qty_brg,dum_jual.hrg_jual,dum_jual.discrp,dum_jual.discitem,dum_jual.discvo,dum_jual.kd_sat,dum_jual.hrg_beli,mas_brg.no_urut AS nourutmas 
FROM retur_jual 
LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut
LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg 
WHERE retur_jual.tgl_retur='$tgl_retur' AND retur_jual.no_returjual='$no_returjual' AND retur_jual.proses=0");

if (mysqli_num_rows($c)>=1){
  while ($c1=mysqli_fetch_assoc($c)){
  	$jml_sub=0;$netto=0;$ditem=0;
    $no_urutretur= $c1['no_urutretur'];
    $no_urutbeli = $c1['no_urutbeli'];
    $no_urutjual = $c1['no_urutjual'];
    $kd_sat      = $c1['kd_sat'];
    $qty_brg     = $c1['qty_retur'];
    $kd_brg      = $c1['kd_brg'];
    $no_fakjual  = $c1['no_fakjual'];
    $tgl_jual    = $c1['tgl_jual'];
    $jml_ret     = konjumbrg2($kd_sat,$kd_brg,$hub)*$qty_brg;
    $nourutmas   = $c1['nourutmas'];

    if($c1['discrp'] > 0){
      $ditem=$c1['discrp'];
    }else{
      $ditem=0;
    }
    if($c1['discitem'] > 0){
      $dnota=$c1['hrg_jual']*$c1['discitem']/100;
    }else{
      $dnota=0;
    }
    if ($c1['discvo']>0){
      $divo =$c1['hrg_jual']*($c1['discvo']/100);
    }else{
      $divo =0;  
    }     
       
    $hrgjl   = $c1['hrg_jual']-($ditem+$dnota+$divo); 
    $diskon  = $ditem+$dnota+$divo;
    $jmlsub  = $hrgjl*$qty_brg; 
    $laba    = $jmlsub-($c1['hrg_beli']*$qty_brg);  
    $hrgjual = $c1['hrg_jual']*$qty_brg;  
    // cari penjualan tempo
    $qm=mysqli_query($hub,"SELECT * FROM mas_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko' AND kd_bayar='TEMPO' AND ket_bayar='BELUM' ");
    if (mysqli_num_rows($qm)>=1){
      $dm           = mysqli_fetch_assoc($qm);
      $tot_jual     = $dm['tot_jual']-$hrgjual;
      $tot_jual_d   = $dm['tot_jual']- $jmlsub;
      $tot_discitem = $dm['tot_disc']-($diskon*$qty_brg);
      $tot_laba     = $dm['tot_laba']-$laba;
      $saldo_awal   = $dm['saldo_hutang'];
      $bayar        = $dm['bayar_uang'];
      $trf          = $dm['trf']; 
      $ket_bayar    = $dm['ket_bayar']; 
      $tgl_jt       = $dm['tgl_jt'];	
      $bayar_hutang = $jmlsub;
      $saldo_hutang = $dm['saldo_hutang']-$jmlsub ;	
      $kd_pel       = $dm['kd_pel'];
      $defmodal     = ($tot_jual-$tot_discitem)-$tot_laba;
      $ket_bayar    = $dm['ket_bayar'];
      
      //mysqli_query($hub,"UPDATE mas_jual SET tot_jual='$tot_jual',tot_disc='$tot_discitem',tot_laba='$tot_laba', saldo_hutang='$saldo_hutang' WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");
      if($dm['saldo_hutang']>0 || $dm['saldo_hutang']<0 ){
        mysqli_query($hub,"UPDATE mas_jual SET saldo_hutang='$saldo_hutang' WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");  
      }

      //**Jika sudah ada pembayaran harus UPDATE pembayaran
      // cek apakah tagihan total > pembayaran angsuran yg sdh dilakukan    
      $carihut=mysqli_query($hub,"SELECT * from mas_jual_hutang WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko' ORDER BY no_urut ASC");
      if (mysqli_num_rows($carihut)>=1) 
      {
        
          $x=0;$saldoawal=0;$bayard=0;$byrmasuk=0;$modal=0;$setor=0;$totmodal=0; 
          $saldoawal=$saldo_awal;
          
          while ($dcarihut=mysqli_fetch_assoc($carihut))
          {
            $setor    = $setor+$dcarihut['byr_hutang'];
            $no_urut  = $dcarihut['no_urut'];
            if($setor <= $defmodal){
                $modalmsk = $dcarihut['byr_hutang'];
                $labamsk  = 0;
                $totmodal = $totmodal+$modalmsk; 
                
            }else{   
                if($totmodal <= $defmodal){
                    $modalmsk = $defmodal-$totmodal;
                    $labamsk  = $dcarihut['byr_hutang']-$modalmsk;
                }else{
                    $modalmsk = 0;
                    $labamsk  = $dcarihut['byr_hutang'];
                }
                $totmodal = $totmodal+$modalmsk;
                
            }
            $x++;
            
            if ($x==1){
              $tot_jual=$dcarihut['totjual']-$jmlsub;
              $sld_hut=$tot_jual-$dcarihut['byr_hutang'];
              $d=mysqli_query($hub,"UPDATE mas_jual_hutang SET tgl_jt='$tgl_jt',totjual='$tot_jual',saldo_awal='$tot_jual',byr_hutang='$bayar',
              saldo_hutang='$sld_hut',ket='$ket_bayar',kd_pel='$kd_pel',modal='$modalmsk',laba='$labamsk',trf='$trf' WHERE no_urut='$no_urut'"); 

              $saldo_awal=$sld_hut; 
              $xx=mysqli_query($hub,"SELECT SUM(modal) as jmodal FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual'");
              $dtr=mysqli_fetch_assoc($xx);
              $modal    = $dtr['jmodal'];
              unset($xx,$dtr);
            } else {    
              $bayard=$dcarihut['byr_hutang'];
              $sld_hut=$saldo_awal-$bayard;  
              $d=mysqli_query($hub,"UPDATE mas_jual_hutang SET tgl_jt='$tgl_jt',totjual='$tot_jual',saldo_awal='$saldo_awal',byr_hutang='$bayard',saldo_hutang='$sld_hut',ket='$ket_bayar',kd_pel='$kd_pel',modal='$modalmsk',laba='$labamsk',trf='$trf' WHERE no_urut='$no_urut'");    
            
              $saldo_awal=$saldo_awal-$bayard;
            }
            
          }
      }
    }
    mysqli_free_result($qm);unset($dm);

    //ambil stok jual awal utk update
    $cdum1       = mysqli_query($hub,"SELECT stok_jual FROM beli_brg WHERE no_urut='$no_urutbeli' ");
    $ddum1       = mysqli_fetch_assoc($cdum1);
    $stok_awal   = $ddum1['stok_jual'];
    $jml_brg     = $jml_ret+$stok_awal;
    mysqli_query($hub,"UPDATE beli_brg SET stok_jual='$jml_brg' WHERE no_urut='$no_urutbeli'");  
    mysqli_free_result($cdum1);unset($ddum1);
  
    //ambil jml_brg,brg_klr pd mas_brg
    $cmas        = mysqli_query($hub,"SELECT no_urut,jml_brg,brg_klr FROM mas_brg WHERE no_urut='$nourutmas'");
    $dmas        = mysqli_fetch_assoc($cmas);
    $jml_brgawal = $dmas['jml_brg']+$jml_ret;
    $brg_klrawal = $dmas['brg_klr']-$jml_ret;
    mysqli_query($hub,"UPDATE mas_brg SET jml_brg='$jml_brgawal',brg_klr='$brg_klrawal' WHERE no_urut='$nourutmas'");
    mysqli_free_result($cmas);unset($dmas,$jml_brgawal,$brg_klrawal,$nourutmas);

     //update log_file
    //   $cc=mysqli_query($hub,"SELECT beli_brg.kd_brg,SUM(beli_brg.stok_jual) as jmlstok,mas_brg.jml_brg,mas_brg.nm_brg,mas_brg.brg_klr FROM beli_brg
    //   LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
    //   WHERE beli_brg.kd_brg='$kd_brg' AND beli_brg.kd_toko='$kd_toko'
    //   GROUP BY beli_brg.kd_brg");
    //   $dd         = mysqli_fetch_assoc($cc);
    //   $stok_jualx = $dd['jmlstok'];
    //   $jml_brgx   = $dd['jml_brg'];
    //   $brg_klrx   = $dd['brg_klr'];
    //   $nm_brgx    = $dd['nm_brg'];
    //   if($stok_jualx!=$jml_brgx){
    //     $tghi=date("Y-m-d h:i:sa");
    //     $nm_user=$_SESSION['nm_user'];
    //     mysqli_query($connect,"INSERT INTO file_log VALUES('','returjual_pro','$kd_brg','$nm_brgx','$tghi','$kd_toko','$nm_user')");
    //   }
    //   mysqli_free_result($cc);unset($dd,$stok_jualx,$jml_brgx,$brg_klrx);

    //update dum_jual
    mysqli_query($hub,"UPDATE dum_jual SET ket='RETUR BARANG' WHERE no_urut = '$no_urutjual'");
    $jml_brg=0;$stok_awal=0;$jml_ret=0;
  }  
  $d = mysqli_query($hub,"UPDATE retur_jual SET proses='1' WHERE tgl_retur='$tgl_retur' AND no_returjual='$no_returjual'");	
}else{
  $d=false;	
}
mysqli_free_result($c);unset($c1);

if($d){header("location:f_returjual.php?pesan=simpan");}
else{header("location:f_returjual.php?pesan=gagal");}    
?>