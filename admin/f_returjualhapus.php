<?php 
 ob_start();
 include 'config.php';
 session_start();
 $oto        = $_SESSION['kodepemakai'];
 $hub        = opendtcek();
 $kd_toko    = $_SESSION['id_toko'];
 $id_user    = $_SESSION['id_user'];
 $no_urut    = mysqli_real_escape_string($hub,$_POST['keyword']);

 $sql        = mysqli_query($hub,"SELECT retur_jual.*,dum_jual.*,mas_brg.no_urut AS nourutmas 
               FROM retur_jual 
               LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
               LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
               WHERE no_urutretur='$no_urut'");
 $data       = mysqli_fetch_assoc($sql);
 $jml_sub    = $ditem=0;
    
 $proses     = $data['proses'];
 $no_urutjual= $data['no_urutjual'];
 $kd_sat     = $data['kd_sat'];
 $qty_brg    = $data['qty_brg'];
 $kd_brg     = $data['kd_brg'];
 $no_fakjual = $data['no_fakjual'];
 $tgl_jual   = $data['tgl_jual'];
 $no_urutbeli= $data['no_urutbeli'];
 $jml_ret    = konjumbrg2($kd_sat,$kd_brg,$hub)*$qty_brg;
 $disc1      = $data['discitem']/100;
 $nourutmas  = $data['nourutmas'];

 if($data['discrp'] > 0){
  $ditem=$data['discrp'];
 }else{
  $ditem=0;
 }
 if($data['discitem'] > 0){
  $dnota=$data['hrg_jual']*$data['discitem']/100;
 }else{
  $dnota=0;
 }
 if ($data['discvo']>0){
  $divo =$data['hrg_jual']*($data['discvo']/100);
 }else{
  $divo =0;  
 }     

 $hrgjl=$data['hrg_jual']-($ditem+$dnota+$divo); 
 $diskon=gantitides($ditem+$dnota+$divo);
 $jmlsub=round($hrgjl*$data['qty_brg'],0); 
 mysqli_free_result($sql);unset($data);
 
 if ($proses==0){
    mysqli_query($hub,"DELETE FROM retur_jual WHERE no_urutretur='$no_urut'");
    mysqli_query($hub,"UPDATE dum_jual SET ket='PEMBELIAN BARANG' WHERE no_urut='$no_urutjual'");
 } else {
    //ambil jml_brg,brg_klr pd mas_brg
    $cmas        = mysqli_query($hub,"SELECT jml_brg,brg_klr FROM mas_brg WHERE no_urut='$nourutmas'");
    $dmas        = mysqli_fetch_assoc($cmas);
    $jml_brgawal = $dmas['jml_brg']-$jml_ret;
    $brg_klrawal = $dmas['brg_klr']+$jml_ret;
    mysqli_query($hub,"UPDATE mas_brg SET jml_brg='$jml_brgawal',brg_klr='$brg_klrawal' WHERE no_urut='$nourutmas'");
    mysqli_free_result($cmas);unset($dmas);

    //ambil stok_jual awal pd beli_brg
    $cdum1       = mysqli_query($hub,"SELECT stok_jual FROM beli_brg WHERE no_urut='$no_urutbeli' ");
    $ddum1       = mysqli_fetch_assoc($cdum1);
    $stok_awal   = $ddum1['stok_jual'];
    $jml_brg     = $stok_awal-$jml_ret;
    mysqli_query($hub,"UPDATE beli_brg SET stok_jual='$jml_brg' WHERE no_urut='$no_urutbeli'");
    mysqli_free_result($cdum1);unset($ddum1);
    
    //update log_file
    // $cc=mysqli_query($hub,"SELECT beli_brg.kd_brg,SUM(beli_brg.stok_jual) as jmlstok,mas_brg.jml_brg,mas_brg.nm_brg,mas_brg.brg_klr FROM beli_brg
    // LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
    // WHERE beli_brg.kd_brg='$kd_brg' AND beli_brg.kd_toko='$kd_toko'
    // GROUP BY beli_brg.kd_brg");
    // $dd         = mysqli_fetch_assoc($cc);
    // $stok_jualx = $dd['jmlstok'];
    // $jml_brgx   = $dd['jml_brg'];
    // $brg_klrx   = $dd['brg_klr'];
    // $nm_brgx    = $dd['nm_brg'];
    // if($stok_jualx!=$jml_brgx){
    //   $tghi=date("Y-m-d h:i:sa");
    //   $nm_user=$_SESSION['nm_user'];
    //   mysqli_query($connect,"INSERT INTO file_log VALUES('','returjual_pro','$kd_brg','$nm_brgx','$tghi','$kd_toko','$nm_user')");
    // }
    // mysqli_free_result($cc);unset($dd,$stok_jualx,$jml_brgx,$brg_klrx);

    //update stok_jual
    mysqli_query($hub,"DELETE FROM retur_jual WHERE no_urutretur='$no_urut'");
    mysqli_query($hub,"UPDATE dum_jual SET ket='PEMBELIAN BARANG' WHERE no_urut='$no_urutjual'");
    
    //update angsuran piutang
    $tot_jual=0;$tot_jual_d=0;$tot_laba=0;$tot_disc=0;
    $cek=mysqli_query($hub,"SELECT * FROM dum_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");
    while($dcek=mysqli_fetch_assoc($cek))
    {
       //hitung disckon
       if($dcek['discitem']>0){
        $ditem=$dcek['hrg_jual']*($dcek['discitem']/100);
       }else{
        $ditem=0;
       }
       if($dcek['discrp']>0){
        $drp=$dcek['discrp'];
       }else{
        $drp=0;
       }
       if($dcek['discvo']>0){
        $dvo=$dcek['hrg_jual']*($dcek['discvo']/100);
       }else{
        $dvo=0;
       }  
       $hrgnet     = ($dcek['hrg_jual']-($ditem+$drp+$dvo))*$dcek['qty_brg'];      
       $tot_jual   = $tot_jual+($dcek['hrg_jual']*$dcek['qty_brg']);
       $tot_jual_d = $tot_jual_d+$hrgnet;
       $tot_disc   = $tot_disc+(($ditem+$drp+$dvo)*$dcek['qty_brg']);  
       $tot_laba   = $tot_laba+$dcek['laba']; 
    }
    unset($dcek,$cek);

    $saldo=0;$x=0;$saldo_awal=$tot_jual_d;
    $qm=mysqli_query($hub,"SELECT * FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko' ORDER BY no_urut ASC");
    while ($dm=mysqli_fetch_assoc($qm)){
      $x++;
      $saldo=$saldo_awal-$dm['byr_hutang'];
      $no_urutpi=$dm['no_urut'];
      mysqli_query($hub,"UPDATE mas_jual_hutang SET saldo_awal='$saldo_awal',saldo_hutang='$saldo',totjual='$tot_jual_d' WHERE no_urut='$no_urutpi'");	
      $saldo_awal   = $saldo;
    }
    //update mas_jual
    if ($saldo <= 0) { $ket='LUNAS'; } else { $ket='BELUM'; }
    $f=mysqli_query($hub,"UPDATE mas_jual SET saldo_hutang='$saldo' WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");
    mysqli_free_result($qm);unset($dm);
 }
 unset($stok_awal,$kd_brg,$kd_sat,$qty_brg,$no_item,$proses,$jml_brgawal,$brg_klrawal);
 ?><script>returjualstart('<?=$kd_toko?>','<?=$id_user?>');</script>
<?php
  mysqli_close($hub);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?> 