<?php 
  ob_start();
  include 'config.php';
  session_start();
  $conseek=opendtcek();  
  $kd_toko    = $_SESSION['id_toko'];
  $id_user    = $_SESSION['id_user'];
  $nm_user    = $_SESSION['nm_user']; 
  $cekpotong  = 0;
  $ketjual    = trim(strtoupper(mysqli_escape_string($conseek,$_POST['ketjual'])));
  ?><script>document.getElementById("edit-warning").value=0</script><?php
  date_default_timezone_set('Asia/Jakarta');
  $tghi=date("Y-m-d H:i:s");   
  if ($ketjual=='-'){
    $ketjual='';
  }else {
    $ketjual="( ".$ketjual." )";
  }

  if(isset($_POST['tgl_fakjual'])){
    $tgl_jual = $_POST['tgl_fakjual'];  
  }else{$tgl_jual='0000-00-00';}

  if(isset($_POST['tgl_jt'])){
    $tgl_jt   = $_POST['tgl_jt'];
  }else{$tgl_jt='0000-00-00';}

  if(isset($_POST['no_fakjual'])){
    $no_fakjual = strtoupper($_POST['no_fakjual']);
  }else{$no_fakjual='';}

  if(isset($_POST['cr_bay'])){
    $kd_bayar   = strtoupper($_POST['cr_bay']);
  }else{$kd_bayar='';}
  
  if(isset($_POST['kd_pel'])){
    $kd_pel = $_POST['kd_pel'];
  }else{$kd_pel='';}
   
  if(isset($_POST['kd_bar'])){
    $kd_bar = $_POST['kd_bar'];
  }else{$kd_bar='';}

  $kd_brg     = mysqli_escape_string($conseek,$_POST['kd_brg']);
  $qty_brg    = $_POST['qty_brg'];
  $discitem   = backnumdes($_POST['discitem']);
  $kd_sat     = $_POST['kd_sat'];
  $kd_kat     = $_POST['kd_kat'];
  $no_urutjual= mysqli_escape_string($conseek,$_POST['no_urutjual']);
  
  //**cek jika potong stok atau tidak
  $q=mysqli_query($conseek,"SELECT * FROM seting ORDER BY no_urut");
  while ($d=mysqli_fetch_assoc($q)){
    if ($d['nm_per']=="POTONG"){
      $potong=$d['kode'];  
    }
    if ($d['nm_per']=="PROSES"){
      $c_proses=$d['kode'];  
    }
  }
  mysqli_free_result($q);unset($d);

  $hrg_jual=0;$jum_kem=0;$subtot=0;$diskon=0;$brg_klr=0;$d=false;
  //cek barang ditemukan pada mas_brg 
  $cekjual=mysqli_query($conseek,"select * from mas_brg where kd_brg='$kd_brg'");
  if(mysqli_num_rows($cekjual)>=1){
    //divinisi variable utk transaksi dum_jual
      $databrg=mysqli_fetch_array($cekjual);
      if($kd_sat == $databrg['kd_kem3']){
        $hrg_jual = $databrg['hrg_jum3'];
        $jum_kem  = $databrg['jum_kem3'];
        $nmkem    = mysqli_escape_string($conseek,$databrg['nm_kem3']);
        $nm_brg   = mysqli_escape_string($conseek,$databrg['nm_brg']);
      }
      if($kd_sat == $databrg['kd_kem2']){
        $hrg_jual = $databrg['hrg_jum2'];
        $jum_kem  = $databrg['jum_kem2'];
        $nmkem    = mysqli_escape_string($conseek,$databrg['nm_kem2']);
        $nm_brg   = mysqli_escape_string($conseek,$databrg['nm_brg']);
      }
      if($kd_sat  == $databrg['kd_kem1']){
        $hrg_jual = $databrg['hrg_jum1'];
        $jum_kem  = $databrg['jum_kem1'];
        $nmkem    = mysqli_escape_string($conseek,$databrg['nm_kem1']);
        $nm_brg   = mysqli_escape_string($conseek,$databrg['nm_brg']);
      }
    //end difinisi

    //**--- bagian save data 
      $satkecil_result = carisatkecil($kd_brg);
      $x = !empty($satkecil_result) ? explode(';', $satkecil_result) : array();
      $sat_kecil = isset($x[0]) ? $x[0] : '';
      $jum_kecil = isset($x[1]) ? $x[1] : 0;
      $nmkem     = ceknmkem2($sat_kecil,$conseek);
    //--  

    // cari satuan besar
      $satbesar_result = carisatbesar($kd_brg);
      $x = !empty($satbesar_result) ? explode(";", $satbesar_result) : array();
      $bigsat  = isset($x[0]) ? $x[0] : '';
      $bigjum  = isset($x[1]) ? $x[1] : 0;
    //end cari satuan

    // Apakah ada discount tetap   
      $cekdisc=mysqli_query($conseek,"SELECT * from disctetap where kd_brg='$kd_brg' order by no_urut");
      if (mysqli_num_rows($cekdisc)>=1){    
        while($datadisc=mysqli_fetch_assoc($cekdisc)){
          if ($kd_sat==$datadisc['kd_sat'] && $datadisc['hrg_jual']>0){
            if ($qty_brg>=$datadisc['lim_jual']){
              $hrg_jual=$datadisc['hrg_jual'];
            } 
          }
        }
      }
      unset($datadisc);mysqli_free_result($cekdisc);
    //end discount tetap
    
    // Discount promo sudah dihandle di frontend (f_jual.php - fungsi getdiscpromo)
    // Tidak perlu ditambahkan lagi di backend karena akan menyebabkan discount terkalikan 2
    // Jika discitem dari POST sudah berisi discount promo, gunakan langsung tanpa menambahkan lagi  

    //cek data apakah edit data 
      $sbl     = mysqli_query($conseek,"SELECT sum(stok_jual) AS cekstok FROM beli_brg WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko'");
      $ds      = mysqli_fetch_assoc($sbl);
      $cekstok = $ds['cekstok'];
      mysqli_free_result($sbl);unset($ds);

      $discnot = 0; $discvo = 0;
      $cekedit = mysqli_query($conseek,"SELECT * FROM dum_jual WHERE no_urut='$no_urutjual'");
      if (mysqli_num_rows($cekedit)>=1 ){
        //cek data awal sebelum edit
        $dtcekedit   = mysqli_fetch_assoc($cekedit);
        $a_kd_brg    = $dtcekedit['kd_brg'];
        $a_tgl_jual  = $dtcekedit['tgl_jual'];
        $a_no_fakjual= $dtcekedit['no_fakjual']; 
        $a_kd_sat    = $dtcekedit['kd_sat'];
        $a_qty_brg   = $dtcekedit['qty_brg'];
        $a_no_item   = $dtcekedit['no_item'];
        $jml_stokkov = konjumbrg2($a_kd_sat,$a_kd_brg,$conseek)*$a_qty_brg;
        $discnot     = $dtcekedit['discitem'];
        $discvo      = $dtcekedit['discvo'];

        if ($potong=='1' && $qty_brg>0 && $cekstok+$jml_stokkov>=$qty_brg*$jum_kem){
        //kembalikan nilai pada mas_brg
          $con=opendtcek();
          $a_no_urut=0;
          $sqlmas    = mysqli_query($con,"SELECT jml_brg,brg_klr,no_urut FROM mas_brg WHERE kd_brg='$kd_brg' ");
          $dtmas     = mysqli_fetch_assoc($sqlmas);
          $xklr      = $dtmas['brg_klr'];
          $xjmlbrg   = $dtmas['jml_brg'];
          $a_brg_klr = $xklr-$jml_stokkov;
          $a_jml_brg = $xjmlbrg+$jml_stokkov;
          $a_no_urut = $dtmas['no_urut'];

          $xup=mysqli_query($con,"UPDATE mas_brg SET brg_klr='$a_brg_klr',jml_brg='$a_jml_brg' WHERE no_urut='$a_no_urut'");
          mysqli_free_result($sqlmas);
          unset($dtmas,$a_brg_klr,$a_jml_brg,$a_no_urut,$xklr,$xjmlbrg);  
          
        //kembalian nilai pada beli_brg
          $a_no_urut=0;
          $sqlmas      = mysqli_query($con,"SELECT stok_jual,no_urut FROM beli_brg WHERE no_urut='$a_no_item' AND kd_toko='$kd_toko'");
          $dtmas       = mysqli_fetch_assoc($sqlmas);
          $a_stok_jual = $dtmas['stok_jual']+$jml_stokkov;
          $a_no_urut   = $dtmas['no_urut'];
          mysqli_query($con,"UPDATE beli_brg SET stok_jual='$a_stok_jual' WHERE no_urut='$a_no_urut'");
          mysqli_free_result($sqlmas);unset($dtmas,$a_stok_jual,$a_no_urut);  
          unset($a_kd_brg,$a_tgl_jual,$a_no_fakjual,$a_kd_sat,$a_qty_brg,$a_no_item,$jml_stokkov);
          mysqli_close($con);
           
          //hapus data lama pada dum_jual
          mysqli_query($conseek,"DELETE FROM dum_jual WHERE no_urut='$no_urutjual'");  
        }
        if ($potong=='0'){
          mysqli_query($conseek,"DELETE FROM dum_jual WHERE no_urut='$no_urutjual'");  
        } 
      } 
      mysqli_free_result($cekedit);unset($dtcekedit);
    //End jika edit data 
    //Transaksi ke dum_jual
      if ($potong==0){
       //Jika transaksi tidak mengurangi stok $potong=0
        $q=mysqli_query($conseek,"SELECT * FROM beli_brg WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' ORDER BY no_urut DESC LIMIT 1");
        $xr=0;$rhrg_beli=0;$hrg_beli=0;$ket='';
        //cari hrg beli rata-rata
        $d=mysqli_fetch_assoc($q);
          $xr++;
          $disc1   = $d['disc1']/100;
          $disc2   = $d['disc2'];
          $ppn     = $d['ppn']/100;
          $no_urut = $d['no_urut'];
          $id_bag  = $d['id_bag'];

          if ($d['disc1']=='0.00'){
            $hrg_beli=($d['hrg_beli']-$disc2);
          }else{
            $hrg_beli=($d['hrg_beli'])-(($d['hrg_beli'])*$disc1);
            $hrg_beli=round($hrg_beli,0);
          }
          if ($d['disc1']=='0.00' && $d['disc2']=='0'){
            $hrg_beli=$d['hrg_beli'];
          }
          $hrg_beli  = ($hrg_beli/konjumbrg2($d['kd_sat'],$kd_brg,$conseek))*$jum_kem;
          $rhrg_beli = $rhrg_beli+($hrg_beli+($hrg_beli*$ppn));
          $ket       = $d['ket'];
          $rhrg_beli = round($rhrg_beli/$xr,0);    

        if (strpos($ketjual,'BONUS')>0){
          $hrg_jual=0;
        }  
        unset($d,$q);
        
        //proses penjualan
        $disckov=$discnot;$x1=0;$x2=0;$jml_brg=0;$x3=0;
        $jml_brg=($qty_brg*$jum_kem)/$jum_kem;
        if ($discitem==0 AND $discnot==0){
          $laba=($hrg_jual-$rhrg_beli)*$jml_brg ;  
        }  

        if ($discitem>0 AND $discnot==0){
          $laba=(($hrg_jual-$discitem)-$rhrg_beli)*$jml_brg;  
        }  

        if ($discitem==0 AND $discnot>0){
          $hrgdisc=$hrg_jual-($hrg_jual*($discnot/100));
          $laba=($hrgdisc-$rhrg_beli)*$jml_brg;  
        }

        if ($discitem>0 AND $discnot>0){
          $x1=$hrg_jual*($discnot/100);
          $x2=$x1+$discitem;
          $hrgdisc=$hrg_jual-$x2;
          $laba=($hrgdisc-$rhrg_beli)*$jml_brg;  
        }

        if ($discvo>0){
          $x1=$hrg_jual*($discnot/100);
          $x2=$hrg_jual*($discvo/100);
          $x3=$x1+$discitem+$x2;
          $hrgdisc=$hrg_jual-$x3;
          $laba=($hrgdisc-$rhrg_beli)*$jml_brg;  
        }
        if (strpos($ketjual,'BONUS')>0){
          $laba=0;
        }  
        $nm_brg=mysqli_real_escape_string($conseek,$nm_brg)." ".$ketjual;
        $consave=opendtcek();
        $d=mysqli_query($consave,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$rhrg_beli','$jml_brg','$disckov','$discitem','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','','$tgl_jt','$id_user','$nm_user',false,'$ket','$discvo','','$id_bag','$tghi')");
        mysqli_close($consave);    
       //end transaksi $potong=0
      } else { 
        $cs   = mysqli_query($conseek,"SELECT SUM(stok_jual) AS stok FROM beli_brg WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko'");
        $ds   = mysqli_fetch_assoc($cs);
        $stok = $ds['stok'];
        mysqli_free_result($cs);unset($ds);

        if ($stok>0 && $qty_brg*$jum_kem<=$stok){
          // **jika proses fifo lifo rata Update stok
          if ($c_proses==0){
               $cek_it=mysqli_query($conseek,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag 
               FROM beli_brg 
                    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
                    WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
                    ORDER BY beli_brg.no_urut ASC");    
          }
          if ($c_proses==1){
            $cek_it=mysqli_query($conseek,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag 
            FROM beli_brg 
                LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
                WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
                ORDER BY beli_brg.no_urut DESC");    
          }
          if ($c_proses==2){
            //cek hrg beli utk dijadikan rata
            $qr=mysqli_query($conseek,"SELECT hrg_beli,kd_brg,disc1,disc2,ppn,kd_sat FROM beli_brg WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko' AND stok_jual>0");
            $xc=0;$jum_hrg=0;$hrgx=0;$hrg_rata=0;
            while ($dr=mysqli_fetch_assoc($qr)){
              $xc++;
              $disc1 = $dr['disc1']/100;
              $disc2 = $dr['disc2'];
              $ppn   = $dr['ppn']/100;
              if ($dr['disc1']=='0.00'){
                  // echo gantiti($data['disc2']);
                $hrgx=($dr['hrg_beli']-$disc2);
              }else{
                $hrgx=($dr['hrg_beli'])-(($dr['hrg_beli'])*$disc1);
                $hrgx=round($hrgx,0);
              }
              if ($dr['disc1']=='0.00' && $dr['disc2']=='0'){
                $hrgx=$dr['hrg_beli'];
              } 
              $hrg_rata = ($hrgx/konjumbrg2($dr['kd_sat'],$kd_brg,$conseek))*$jum_kem;
              $jum_hrg  = $jum_hrg+($hrg_rata+($hrg_rata*$ppn));        
            }
            $hrg_rata = round($jum_hrg/$xc,0);
            mysqli_free_result($qr);unset($dr,$xc,$jum_hrg,$hrgx);

            $cek_it=mysqli_query($conseek,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag FROM beli_brg 
                LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
                WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
                ORDER BY beli_brg.no_urut ASC");    
          }    
         
          $qty=$n_stok_jual=$jml=$hrg_beliawal=$x=0;
          $qty=$qty_brg*$jum_kem; 
          while ($cari=mysqli_fetch_assoc($cek_it)) {
            if ($qty>0){
              if ($qty>=$cari['stok_jual']){
                if ($cari['stok_jual']-$qty<=0){
                  $stok_jual = 0; // utk replace pada beli_brg
                  $jml_brg   = $cari['stok_jual']/$jum_kem;
                  if ($qty>$cari['stok_jual']){
                    ?><script>popnew_warning("STOK MAKSIMAL "+'<?=$jml_brg.' '.$nmkem?>')</script><?php
                  }
                } else{
                  $stok_jual=$cari['stok_jual']-$qty; // utk replace pada beli_brg
                  $jml_brg=$qty/$jum_kem;
                } 
              }else{
                $stok_jual=$cari['stok_jual']-$qty;  
                $jml_brg=$qty/$jum_kem;
              }
              //divinisi variable data pembelian
                $hrg_beli_k = $hrg_jual_k=0;
                $disc1      = $cari['disc1']/100;
                $disc2      = $cari['disc2'];
                $ppn        = $cari['ppn']/100;
                $hrg_beli   = $cari['hrg_beli'];
              
                if ($cari['disc1']=='0.00'){
                  // echo gantiti($data['disc2']);
                  $hrg_beliawal=($hrg_beli-$disc2);
                }else{
                  $hrg_beliawal=($hrg_beli)-(($hrg_beli)*$disc1);
                  $hrg_beliawal=round($hrg_beliawal,0);
                }
                if ($cari['disc1']=='0.00' && $cari['disc2']=='0'){
                  $hrg_beliawal=$hrg_beli;
                } 

                if ($c_proses==2){
                  $hrg_beli=$hrg_rata;
                } else {
                  $jum_kemasan_conv = konjumbrg2($cari['kd_sat'],$kd_brg,$conseek);
                  // Cegah division by zero
                  if ($jum_kemasan_conv > 0) {
                    $hrg_beli=($hrg_beliawal/$jum_kemasan_conv)*$jum_kem;
                  } else {
                    $hrg_beli = $hrg_beliawal * $jum_kem; // Jika jum_kemasan 0, gunakan langsung
                  }
                  $hrg_beli=$hrg_beli+($hrg_beli*$ppn);  
                } 
              //end difinisi

              // difinisi varible discount data penjualan  
                if (strpos($ketjual,'BONUS')>0){
                  $hrg_jual=0;
                }
                $disckov=$discnot;
                if ($discitem==0 AND $discnot==0){
                  $laba=($hrg_jual-$hrg_beli)*$jml_brg;  
                }  

                if ($discitem>0 AND $discnot==0){
                  $laba=(($hrg_jual-$discitem)-$hrg_beli)*$jml_brg;  
                }  

                if ($discitem==0 AND $discnot>0){
                  $hrgdisc=$hrg_jual-($hrg_jual*($discnot/100));
                  $laba=($hrgdisc-$hrg_beli)*$jml_brg;  
                }

                if ($discitem>0 AND $discnot>0){
                  $x1=$hrg_jual*($discnot/100);
                  $x2=$x1+$discitem;
                  $hrgdisc=$hrg_jual-$x2;
                  $laba=($hrgdisc-$hrg_beli)*$jml_brg;  
                }  
                if ($discvo>0){
                  $x1=$hrg_jual*($discnot/100);
                  $x2=$hrg_jual*($discvo/100);
                  $x3=$x1+$discitem+$x2;
                  $hrgdisc=$hrg_jual-$x3;
                  $laba=($hrgdisc-$rhrg_beli)*$jml_brg;  
                }
                if (strpos($ketjual,'BONUS')>0){
                  $laba=0;
                }  
              //------------------------------------
              
              $qty=$qty-$cari['stok_jual'];  
              $no_urut=$cari['no_urut'];
              $nm_brg=mysqli_real_escape_string($conseek,$cari['nm_brg'])." ".$ketjual;
              $ket=$cari['ket'];
              $id_bag=$cari['id_bag'];
              //counter utk mas_brg
              $stok=$stok-($jml_brg*$jum_kem);
              $consave=opendtcek(); 
              $d=mysqli_query($consave,"UPDATE beli_brg SET stok_jual='$stok_jual' WHERE no_urut='$no_urut'");
              $d=mysqli_query($consave,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$hrg_beli','$jml_brg','$disckov','$discitem','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','$no_urut','$tgl_jt','$id_user','$nm_user',false,'$ket','$discvo','','$id_bag','$tghi')");
              mysqli_close($consave);
            }//qty>0
          }//while  
          //echo '$jml_brg='.$jml_brg;
          //update mas_brg jml_brg,brg_klr
          $conmasbrg=opendtcek();
          $cekbrg      = mysqli_query($conmasbrg,"SELECT no_urut,jml_brg,brg_klr,nm_brg FROM mas_brg WHERE kd_brg='$kd_brg'");
          $dc          = mysqli_fetch_assoc($cekbrg);
          $jml_brg_mas = $brg_klr_mas=0;
          $no_urut_a   = $dc['no_urut'];
          $klr         = $dc['brg_klr'];
          $jmlbrg      = $dc['jml_brg'];
          $jml_brg_mas = $jmlbrg-$jml_brg;
          $brg_klr_mas = $klr+$jml_brg;
          $nm_brg_mas  = $dc['nm_brg'];   
          
          $dm          = mysqli_query($conmasbrg,"UPDATE mas_brg SET jml_brg='$jml_brg_mas',brg_klr='$brg_klr_mas' WHERE no_urut='$no_urut_a'");   
          
          mysqli_free_result($cekbrg);unset($dc);
          mysqli_close($conmasbrg);
          mysqli_close($conseek);mysqli_free_result($cek_it);
          
        } else {
          ?><script>
            document.getElementById("form-warning").style.display='block';
            document.getElementById("kd_brg").value='';
            document.getElementById("edit-warning").value=1;
            document.getElementById("igt-txt1").value='<?=$nm_brg?>';
            document.getElementById("igt-txt2").value='Stok '+'<?=round($stok,0).strtolower($nmkem)?>'+', Silahkan Cek Stok & input ulang barang yg dijual';
            document.getElementById("igt-txt2").focus();
          </script><?php
        }
        unset($stok,$jml_brg_mas,$brg_klr_mas,$stok_jual,$no_urut,$no_urut_a,$tgl_jual,$no_fakjual,$kd_toko,$hrg_jual,$hrg_beli,$jml_brg,$disckov,$discitem,$laba,$kd_bayar,$kd_pel,$kd_brg,$kd_sat,$nm_brg,$tgl_jt,$id_user,$nm_user,$ket,$discvo); 
      }    
  } else {
    //$_SESSION['warning']=2;
    ?><script>
      //alert('ssss');
      document.getElementById("form-warning").style.display='block';
      document.getElementById("kd_brg").value='';
      document.getElementById("edit-warning").value=2;
      document.getElementById("igt-txt1").value='<?='Kode Barang '.$kd_brg?>';
      document.getElementById("igt-txt2").value='Uppss.. Barang Tidak Ditemukan !!';
      document.getElementById("igt-txt2").focus();
    </script><?php
  }
  unset($cekjual);  
?>

<script>caribrgjual(1,true)</script>
<?php  
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>