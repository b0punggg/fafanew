<?php 
  // mysqli_close($connect);
  include 'config.php';
  
  session_start();
  $kd_toko    = $_SESSION['id_toko'];
  $id_user    = $_SESSION['id_user'];
  $nm_user    = $_SESSION['nm_user']; 
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

  $kd_brg     = $_POST['kd_brg'];
  $qty_brg    = $_POST['qty_brg'];
  $discitem   = gantitides($_POST['discitem']);
  $kd_sat     = $_POST['kd_sat'];
  $no_urutjual= $_POST['no_urutjual'];

  $connect=opendtcek();
  // cek pada mas_brg ambil jml stok awal , hrg_jual . dan satuan tekecil 
  $hrg_jual=0;$jum_kem=0;$subtot=0;$diskon=0;$brg_klr=0;$d=false;
  $cekjual=mysqli_query($connect,"select * from mas_brg where kd_brg='$kd_brg' and kd_toko='$kd_toko' ");
  $databrg=mysqli_fetch_array($cekjual);
  //$brg_klrawal=mysqli_escape_string($connect,$databrg['brg_klr']);

  if($kd_sat==$databrg['kd_kem3']){
   $hrg_jual=mysqli_escape_string($connect,$databrg['hrg_jum3']);
   $jum_kem=mysqli_escape_string($connect,$databrg['jum_kem3']);
  }
  if($kd_sat==$databrg['kd_kem2']){
   $hrg_jual=mysqli_escape_string($connect,$databrg['hrg_jum2']);
   $jum_kem=mysqli_escape_string($connect,$databrg['jum_kem2']);
  }
  if($kd_sat==$databrg['kd_kem1']){
   $hrg_jual=mysqli_escape_string($connect,$databrg['hrg_jum1']);
   $jum_kem=mysqli_escape_string($connect,$databrg['jum_kem1']);
  }

  //$brg_klrawal=stok brg keluar awal
  //$hrg_jual   = harga jual sesuai input satuan barang 
  //$jum_kem    = banyak isi dalam kemasannya
  unset($cekjual,$databrg);

  //cek apakah ada discount tetap
  $cekdisc=mysqli_query($connect,"SELECT * from disctetap where kd_brg='$kd_brg' order by no_urut");
  if (mysqli_num_rows($cekdisc)>=1){
    
    while($datadisc=mysqli_fetch_assoc($cekdisc)){
      if ($kd_sat==$datadisc['kd_sat'] && $datadisc['hrg_jual']>0){
        if ($qty_brg>=$datadisc['lim_jual']){
          $hrg_jual=$datadisc['hrg_jual'];
        }        
      }
    }

  }
  unset($datadisc,$cekdisc);

  // Insert dum_jual
  $cek  = mysqli_query($connect,"SELECT * from dum_jual where no_urut='$no_urutjual' ");
  $data = mysqli_fetch_array($cek);
  if (mysqli_num_rows($cek)>=1)
  {

    $tgl_jual_last=mysqli_escape_string($connect,$data['tgl_jual']);
    $no_fakjual_last=mysqli_escape_string($connect,$data['no_fakjual']);
    $kd_bayar_last=mysqli_escape_string($connect,$data['kd_bayar']);
    $kd_pel_last=mysqli_escape_string($connect,$data['kd_pel']);
    $kd_brg_last=mysqli_escape_string($connect,$data['kd_brg']);
    $kd_sat_last=mysqli_escape_string($connect,$data['kd_sat']);
    $qty_brg_last=mysqli_escape_string($connect,$data['qty_brg']);
    $bayar_last=mysqli_escape_string($connect,$data['bayar']);
    $no_item_last=mysqli_escape_string($connect,$data['no_item']);
    $tgl_jt_last=mysqli_escape_string($connect,$data['tgl_jt']);
    
   
    //lakukan hapus data sebelumnyaaa 
    $brg_klr=0;$jum_brg;
    $jml_brg=konjumbrg($kd_sat_last,$kd_brg_last,$kd_toko);
    $jml_brg=$jml_brg*$qty_brg_last; 
    $ceki=mysqli_query($connect,"SELECT brg_klr,jml_brg FROM mas_brg where kd_brg='$kd_brg_last' and kd_toko='$kd_toko'");
    $datai=mysqli_fetch_array($ceki);
    $brg_klr=mysqli_escape_string($connect,$datai['brg_klr']);
    $jum_brg=mysqli_escape_string($connect,$datai['jml_brg']);
    // echo '$brg_klr=mysqli_escape_string($connect,$datai[brg_klr])='.$brg_klr.'<br>';
    // echo '$jum_brg=mysqli_escape_string($connect,$datai[jml_brg])='.$jum_brg.'<br>';
    $brg_klr=$brg_klr-$jml_brg;
    $jumbrg=$jum_brg+$jml_brg;
    unset($ceki,$datai);
    // echo '$brg_klr=$brg_klr-$jml_brg='.$brg_klr.'<br>';
    // echo '$jumbrg=$jum_brg+$jml_brg='.$jumbrg.'<br>';

    $ceko=mysqli_query($connect,"SELECT stok_jual FROM beli_brg where no_urut='$no_item_last'");
    $datao=mysqli_fetch_array($ceko);
    $jual_stok=mysqli_escape_string($connect,$datao['stok_jual']);
    $jual_stok=$jual_stok+$jml_brg;
    unset($ceko,$datao);

    $d=mysqli_query($connect, "UPDATE beli_brg set stok_jual='$jual_stok' WHERE no_urut='$no_item_last' ");
    $d=mysqli_query($connect, "UPDATE mas_brg SET brg_klr='$brg_klr',jml_brg='$jumbrg' WHERE kd_brg='$kd_brg_last' AND kd_toko='$kd_toko' ");
    $d=mysqli_query($connect, "DELETE from dum_jual WHERE no_urut='$no_urutjual'" );

    //---Kondisikan data baru setelah pergantian data----
    $hub1=opendtcek();
    $brg_klrawal=0;$jumbrg=0;
    $cekbrg=mysqli_query($hub1,"SELECT brg_klr,jml_brg from mas_brg where kd_brg='$kd_brg' and kd_toko='$kd_toko'");
    //echo '<br>';
    $mas=mysqli_fetch_array($cekbrg);
    $brg_klrawal=$mas['brg_klr'];
    // echo '$brg_klrawal=$mas[brg_klr]='.$brg_klrawal.'<br>';
    $brg_klrawal=$brg_klrawal+($qty_brg*$jum_kem);
    // echo '$brg_klrawal=$brg_klrawal+($qty_brg*$jum_kem)='.$brg_klrawal.'<br>';
    //echo '<br>';

    $jumbrg=$mas['jml_brg'];
    //echo '$jumbrg=$mas[jml_brg]='.$jumbrg.'<br>';
    $jumbrg=$jumbrg-($qty_brg*$jum_kem); 
    //echo '$jumbrg=$jumbrg-($qty_brg*$jum_kem)='.$jumbrg.'<br>';
    if ($mas['jml_brg']>0){
      $d=mysqli_query($hub1,"UPDATE mas_brg SET brg_klr='$brg_klrawal',jml_brg='$jumbrg' WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko'");
    }else{
      $d=false;
    }
    unset($cekbrg,$mas);
    mysqli_close($hub1);

    //Proses tulis kembali data baru
    $x=explode(';', carisatkecil($kd_brg,$kd_toko));
    $sat_kecil=$x[0]; 
    $jum_kecil=$x[1]; 
    //-------------------------
    
    //ambil brg_klr pada awalnya utk insert brg_klr pad mas_brg
    $hub=opendtcek();
    $cek_it=mysqli_query($hub,"SELECT beli_brg.no_urut, beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,mas_brg.nm_brg FROM beli_brg 
       LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg                
       WHERE beli_brg.kd_brg='$kd_brg' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual > 0
       ORDER BY beli_brg.no_urut ASC");    

    $qty=0;$n_stok_jual=0;$stok=0;$jml=0;$hrg_beliawal=0;
    $qty=$qty_brg*$jum_kem;
    //echo '$qty='.$qty;
    //$hrg_jual=$hrg_jual/$jum_kem;
    while ($cari=mysqli_fetch_array($cek_it)) {
      if ($qty>0 ){
        //utk replace stok_jual pada beli_brg_jml
        $stok=$cari['stok_jual'];
        if($qty<=$stok){
          $stok=$cari['stok_jual']-$qty;
          $jml=$qty;
          $jml=$jml/$jum_kem;

          $disc1=mysqli_escape_string($hub,$cari['disc1'])/100;
          $disc2=mysqli_escape_string($hub,$cari['disc2']);
          
          if ($cari['disc1']=='0.00'){
            // echo gantiti($data['disc2']);
            $hrg_beliawal=(($cari['hrg_beli'])-$disc2);
          }else{
            $hrg_beliawal=($cari['hrg_beli'])-(($cari['hrg_beli'])*$disc1);
            $hrg_beliawal=round($hrg_beliawal,0);
          }
          if ($cari['disc1']=='0.00' && $cari['disc2']=='0'){
            $hrg_beliawal=$cari['hrg_beli'];
          } 
          // echo '$stok='.$stok."<br>";
          // echo '$stok='.$stok."<br>";
          //-------------------------------------
          $qty=$qty-$cari['stok_jual'];  
          // $no_item=$cari['no_item'];
          $no_urut=$cari['no_urut'];
          $nm_brg=$cari['nm_brg'];
          $hrg_beli= ($hrg_beliawal/konjumbrg($cari['kd_sat'],$kd_brg,$kd_toko))*$jum_kem;
          
          if($discitem=='00.00'){
            $laba=($hrg_jual-$hrg_beli)*$jml;  
          }else{
            $disc=0;
            $disc=$hrg_jual*($discitem/100);
            $laba=(($hrg_jual-$disc)-$hrg_beli)*$jml;
            $laba=round($laba,0);
            // $laba=(($hrg_jual-$disc)*$jml)-($hrg_beli*$jml);
          }
            $d=mysqli_query($hub,"UPDATE beli_brg SET stok_jual='$stok' WHERE no_urut='$no_urut'");
            $d=mysqli_query($hub,"INSERT INTO dum_jual VALUES('','$tgl_jual_last','$no_fakjual_last','$kd_toko','$hrg_jual','$hrg_beli','$jml','$discitem','$laba','$kd_bayar_last','$kd_pel_last','$kd_brg','$kd_sat','$nm_brg','$bayar_last','$no_urut','$tgl_jt_last','$id_user','$nm_user',false)"); 
        }else{
          if($jum_kem==$jum_kecil){
            $stok=$stok-$cari['stok_jual'];  
            $jml=$cari['stok_jual'];
            $jml=$jml/$jum_kecil;   
            $disc1=mysqli_escape_string($hub,$cari['disc1'])/100;
            $disc2=mysqli_escape_string($hub,$cari['disc2']);
            
            if ($cari['disc1']=='0.00'){
              // echo gantiti($data['disc2']);
              $hrg_beliawal=(($cari['hrg_beli'])-$disc2);
            }else{
              $hrg_beliawal=($cari['hrg_beli'])-(($cari['hrg_beli'])*$disc1);
              $hrg_beliawal=round($hrg_beliawal,0);
            }
            if ($cari['disc1']=='0.00' && $cari['disc2']=='0'){
              $hrg_beliawal=$cari['hrg_beli'];
            } 
            // echo '$stok='.$stok."<br>";
            // echo '$stok='.$stok."<br>";
            //-------------------------------------
            $qty=$qty-$cari['stok_jual'];  
            // $no_item=$cari['no_item'];
            $no_urut=$cari['no_urut'];
            $nm_brg=$cari['nm_brg'];
            $hrg_beli= ($hrg_beliawal/konjumbrg($cari['kd_sat'],$kd_brg,$kd_toko))*$jum_kem;
            

            if($discitem=='00.00'){
              $laba=($hrg_jual-$hrg_beli)*$jml;  
            }else{
              $disc=0;
              $disc=$hrg_jual*($discitem/100);
              $laba=(($hrg_jual-$disc)-$hrg_beli)*$jml;
              $laba=round($laba,0);
            }
            $d=mysqli_query($hub,"UPDATE beli_brg SET stok_jual='$stok' WHERE no_urut='$no_urut'");
            $d=mysqli_query($hub,"INSERT INTO dum_jual VALUES('','$tgl_jual_last','$no_fakjual_last','$kd_toko','$hrg_jual','$hrg_beli','$jml','$discitem','$laba','$kd_bayar_last','$kd_pel_last','$kd_brg','$kd_sat','$nm_brg','$bayar_last','$no_urut','$tgl_jt_last','$id_user','$nm_user',false)"); 
          }else{
            // echo '$stok='.$stok.'<br>';
            
            if($stok>=$jum_kem){
              $x=0;$xx=0;
              //echo 'stok_awal='.$stok.'<br>';
              $x=floor($stok/$jum_kem)*$jum_kem;
              $stok=$cari['stok_jual']-$x;
              $jml=$x/$jum_kem;
              // echo '$x=floor($stok/$jum_kem)*$jum_kem='.$x.'<br>';
              // echo '$jml=$x/$jum_kem='.$jml.'<br>';
              // echo '$stok=$cari[stok_jual]-$x;='.$stok.'<br>';

              $disc1=mysqli_escape_string($hub,$cari['disc1'])/100;
              $disc2=mysqli_escape_string($hub,$cari['disc2']);
              
              if ($cari['disc1']=='0.00'){
                // echo gantiti($data['disc2']);
                $hrg_beliawal=(($cari['hrg_beli'])-$disc2);
              }else{
                $hrg_beliawal=($cari['hrg_beli'])-(($cari['hrg_beli'])*$disc1);
                $hrg_beliawal=round($hrg_beliawal,0);
              }
              if ($cari['disc1']=='0.00' && $cari['disc2']=='0'){
                $hrg_beliawal=$cari['hrg_beli'];
              } 
              // echo '$stok='.$stok."<br>";
              // echo '$stok='.$stok."<br>";
              //-------------------------------------
              $qty=$qty-$x;  
              // $no_item=$cari['no_item'];
              $no_urut=$cari['no_urut'];
              $nm_brg=$cari['nm_brg'];
              $hrg_beli= ($hrg_beliawal/konjumbrg($cari['kd_sat'],$kd_brg,$kd_toko))*$jum_kem;
              
              if($discitem=='00.00'){
                $laba=($hrg_jual-$hrg_beli)*$jml;  
              }else{
                $disc=0;
                $disc=$hrg_jual*($discitem/100);
                $laba=(($hrg_jual-$disc)-$hrg_beli)*$jml;
                $laba=round($laba,0);
              }
              $d=mysqli_query($hub,"UPDATE beli_brg SET stok_jual='$stok' WHERE no_urut='$no_urut'");
              $d=mysqli_query($hub,"INSERT INTO dum_jual VALUES('','$tgl_jual_last','$no_fakjual_last','$kd_toko','$hrg_jual','$hrg_beli','$jml','$discitem','$laba','$kd_bayar_last','$kd_pel_last','$kd_brg','$kd_sat','$nm_brg','$bayar_last','$no_urut','$tgl_jt_last','$id_user','$nm_user',false)"); 
            }
            
          }
        }
      }// if qty>0
    }//while  
       
    unset($cek_it,$cari);  
    mysqli_close($hub); 
    //----------------
  }else{ // jika data baru
    //cari satuan terkecil brg,    
    $x=explode(';', carisatkecil($kd_brg,$kd_toko));
    $sat_kecil=$x[0]; 
    $jum_kecil=$x[1]; 
    //-------------------------

    // ambil brg_klr pada awalnya utk insert brg_klr pad mas_brg
    $brg_klrawal=0;$jumbrg=0;
    $cekbrg=mysqli_query($connect,"SELECT brg_klr,jml_brg from mas_brg where kd_brg='$kd_brg' and kd_toko='$kd_toko'");
    $mas=mysqli_fetch_array($cekbrg);
    $brg_klrawal=$mas['brg_klr'];
    $brg_klrawal=$brg_klrawal+($qty_brg*$jum_kem);
    $jumbrg=$mas['jml_brg'];
    $jumbrg=$jumbrg-($qty_brg*$jum_kem);
    if ($mas['jml_brg']>0) {
       $d=mysqli_query($connect,"UPDATE mas_brg SET brg_klr='$brg_klrawal',jml_brg='$jumbrg' WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko'");
    }else{
      $d=false;
    }
    unset($cekbrg,$mas);
    //--------------

    $cek_it=mysqli_query($connect,"SELECT beli_brg.no_urut, beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,mas_brg.nm_brg FROM beli_brg 
       LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
       WHERE beli_brg.kd_brg='$kd_brg' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual > 0
       ORDER BY beli_brg.no_urut ASC");    
    $qty=0;$n_stok_jual=0;$stok=0;$jml=0;$hrg_beliawal=0;
    $qty=$qty_brg*$jum_kem;
    //echo '$qty='.$qty;
    //$hrg_jual=$hrg_jual/$jum_kem;
    while ($cari=mysqli_fetch_array($cek_it)) {
      if ($qty>0 ){
        //utk replace stok_jual pada beli_brg_jml
        $stok=$cari['stok_jual'];
        if($qty<=$stok){
          $stok=$cari['stok_jual']-$qty;
          $jml=$qty;
          $jml=$jml/$jum_kem;

          $disc1=mysqli_escape_string($connect,$cari['disc1'])/100;
          $disc2=mysqli_escape_string($connect,$cari['disc2']);
          
          if ($cari['disc1']=='0.00'){
            // echo gantiti($data['disc2']);
            $hrg_beliawal=(($cari['hrg_beli'])-$disc2);
          }else{
            $hrg_beliawal=($cari['hrg_beli'])-(($cari['hrg_beli'])*$disc1);
            $hrg_beliawal=round($hrg_beliawal,0);
          }
          if ($cari['disc1']=='0.00' && $cari['disc2']=='0'){
            $hrg_beliawal=$cari['hrg_beli'];
          } 
          // echo '$stok='.$stok."<br>";
          // echo '$stok='.$stok."<br>";
          //-------------------------------------
          $qty=$qty-$cari['stok_jual'];  
          //$no_item=$cari['no_item'];
          $no_urut=$cari['no_urut'];
          $nm_brg=$cari['nm_brg'];
          $hrg_beli= ($hrg_beliawal/konjumbrg($cari['kd_sat'],$kd_brg,$kd_toko))*$jum_kem;
          
          if($discitem=='00.00'){
            $laba=($hrg_jual-$hrg_beli)*$jml;  
          }else{
            $disc=0;
            $disc=$hrg_jual*($discitem/100);
            $laba=(($hrg_jual-$disc)-$hrg_beli)*$jml;
            $laba=round($laba,0);
          }
            $d=mysqli_query($connect,"UPDATE beli_brg SET stok_jual='$stok' WHERE no_urut='$no_urut'");
            $d=mysqli_query($connect,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$hrg_beli','$jml','$discitem','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','$no_urut','$tgl_jt','$id_user','$nm_user',false)");   
        }else{
          if($jum_kem==$jum_kecil){
            $stok=$stok-$cari['stok_jual'];  
            $jml=$cari['stok_jual'];
            $jml=$jml/$jum_kecil;   
            $disc1=mysqli_escape_string($connect,$cari['disc1'])/100;
            $disc2=mysqli_escape_string($connect,$cari['disc2']);
            
            if ($cari['disc1']=='0.00'){
              // echo gantiti($data['disc2']);
              $hrg_beliawal=(($cari['hrg_beli'])-$disc2);
            }else{
              $hrg_beliawal=($cari['hrg_beli'])-(($cari['hrg_beli'])*$disc1);
              $hrg_beliawal=round($hrg_beliawal,0);
            }
            if ($cari['disc1']=='0.00' && $cari['disc2']=='0'){
              $hrg_beliawal=$cari['hrg_beli'];
            } 
            // echo '$stok='.$stok."<br>";
            // echo '$stok='.$stok."<br>";
            //-------------------------------------
            $qty=$qty-$cari['stok_jual'];  
            //$no_item=$cari['no_item'];
            $no_urut=$cari['no_urut'];
            $nm_brg=$cari['nm_brg'];
            $hrg_beli= ($hrg_beliawal/konjumbrg($cari['kd_sat'],$kd_brg,$kd_toko))*$jum_kem;
            

            if($discitem=='00.00'){
              $laba=($hrg_jual-$hrg_beli)*$jml;  
            }else{
              $disc=0;
              $disc=$hrg_jual*($discitem/100);
              $laba=(($hrg_jual-$disc)-$hrg_beli)*$jml;
              $laba=round($laba,0);
            }
            $d=mysqli_query($connect,"UPDATE beli_brg SET stok_jual='$stok' WHERE no_urut='$no_urut'");
            $d=mysqli_query($connect,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$hrg_beli','$jml','$discitem','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','$no_urut','$tgl_jt','$id_user','$nm_user',false)");   
          }else{
            // echo '$stok='.$stok.'<br>';  
            if($stok>=$jum_kem){
                $x=0;$xx=0;
                // echo 'stok_awal='.$stok.'<br>';
                $x=floor($stok/$jum_kem)*$jum_kem;
                $stok=$cari['stok_jual']-$x;
                $jml=$x/$jum_kem;
                // echo '$x=floor($stok/$jum_kem)*$jum_kem='.$x.'<br>';
                // echo '$jml=$x/$jum_kem='.$jml.'<br>';
                // echo '$stok=$cari[stok_jual]-$x;='.$stok.'<br>';

                $disc1=mysqli_escape_string($connect,$cari['disc1'])/100;
                $disc2=mysqli_escape_string($connect,$cari['disc2']);
                
                if ($cari['disc1']=='0.00'){
                  // echo gantiti($data['disc2']);
                  $hrg_beliawal=(($cari['hrg_beli'])-$disc2);
                }else{
                  $hrg_beliawal=($cari['hrg_beli'])-(($cari['hrg_beli'])*$disc1);
                  $hrg_beliawal=round($hrg_beliawal,0);
                }
                if ($cari['disc1']=='0.00' && $cari['disc2']=='0'){
                  $hrg_beliawal=$cari['hrg_beli'];
                } 
                // echo '$stok='.$stok."<br>";
                // echo '$stok='.$stok."<br>";
                //-------------------------------------
                $qty=$qty-$x;  
                //$no_item=$cari['no_item'];
                $no_urut=$cari['no_urut'];
                $nm_brg=$cari['nm_brg'];
                $hrg_beli= ($hrg_beliawal/konjumbrg($cari['kd_sat'],$kd_brg,$kd_toko))*$jum_kem;
                
                if($discitem=='00.00'){
                  $laba=($hrg_jual-$hrg_beli)*$jml;  
                }else{
                  $disc=0;
                  $disc=$hrg_jual*($discitem/100);
                  $laba=(($hrg_jual-$disc)-$hrg_beli)*$jml;
                  $laba=round($laba,0);
                }
                $d=mysqli_query($connect,"UPDATE beli_brg SET stok_jual='$stok' WHERE no_urut='$no_urut'");
                $d=mysqli_query($connect,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$hrg_beli','$jml','$discitem','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','$no_urut','$tgl_jt','$id_user','$nm_user',false)");   
            }
            
          }
        }
      }// if qty>0
    }//while    
    unset($cek1,$cari);
  }  
  // if($d){echo 'sukses';}
  // else{echo 'gagal';}      
  mysqli_close($connect);
  if($d){ }
  else{?><script>popnew_error("Gagal update data");</script><?php }    
  
?>
