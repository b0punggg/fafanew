<?php 
  // mysqli_close($connect);
  ob_start();
  include 'config.php';
  session_start();
  $connect     = opendtcek();

  $jump_file   = $_POST['jump_file'];  
  $no_urutnota = $_POST['no_urutnota'];
  $nm_sup      = strtoupper($_POST['nm_sup']);
  $nm_sat      = strtoupper($_POST['nm_sat']);
  $kd_brg      = trim(strtoupper($_POST['kd_brg']));
  $kd_bar      = trim(strtoupper($_POST['kd_bar']));
  $nm_brg      = trim(strtoupper($_POST['nm_brg']));
  $jml_brg     = $_POST['jml_brg'];
  $ketbel      = trim(strtoupper($_POST['ketbel']));
  $kd_toko     = $_SESSION['id_toko']; 
  $no_fak      = trim(strtoupper($_POST['no_fak']));
  $tgl_fak     = $_POST['tgl_fak'];
  $kd_sup      = $_POST['kd_sup'];
  $hrg_beli    = $_POST['hrg_beli'];
  $discitem1   = $_POST['discitem1'];
  $discitem2   = $_POST['discitem2'];
  $koreksi     =$satkecil=0;
  if(empty($kd_bar)){$kd_bar=$kd_brg;}
  
  $kd_sat      = $_POST['kd_sat'];
  $kd_sat1     = $_POST['kd_sat1'];
  $jum_sat1    = $_POST['jum_sat1'];
  $hrg_jum1    = $_POST['hrg_jum1'];
  $nm_sat1     = ceknmkem2($_POST['kd_sat1'],$connect); 

  $kd_sat2     = $_POST['kd_sat2'];
  $jum_sat2    = $_POST['jum_sat2'];
  $hrg_jum2    = $_POST['hrg_jum2'];
  $nm_sat2     = ceknmkem2($_POST['kd_sat2'],$connect);

  $kd_sat3     = $_POST['kd_sat3'];
  $jum_sat3    = $_POST['jum_sat3'];
  $hrg_jum3    = $_POST['hrg_jum3'];
  $nm_sat3     = ceknmkem2($_POST['kd_sat3'],$connect);

  $lim_jual1   = $_POST['lim_jual1'];
  $kd_sat4     = $_POST['kd_sat4'];
  $hrg_jum4    = $_POST['hrg_jum4'];

  $lim_jual2   = $_POST['lim_jual2'];
  $kd_sat5     = $_POST['kd_sat5'];
  $hrg_jum5    = $_POST['hrg_jum5'];
  
  $lim_jual3   = $_POST['lim_jual3'];
  $kd_sat6     = $_POST['kd_sat6'];
  $hrg_jum6    = $_POST['hrg_jum6'];
  $id_bag      = $_POST['id_bag'];

  //Cek pembelian kondisikan satuan terkecil
  if($kd_sat3==1 && $kd_sat2==1){
    $satkecil=$kd_sat1;
  }
  if($kd_sat3==1 && $kd_sat2>1){
    $satkecil=$kd_sat2;
  }
  if($kd_sat3>1){
    $satkecil=$kd_sat3;
  }

  //Cek jumlah satuan sesuai input
  $jumbeli=$satjual=0;
  if ($kd_sat==$kd_sat1) {
    $satjual=$kd_sat1;
    $jumbeli=$jum_sat1;
  }
  if ($kd_sat==$kd_sat2) {
    $satjual=$kd_sat2;
    $jumbeli=$jum_sat2;
  }
  if ($kd_sat==$kd_sat3) {
    $satjual=$kd_sat3;
    $jumbeli=$jum_sat3;
  }
  // jml_brg beli dikonversikan ke jumlah barang satuan terkecil
   $jml_brgkov=$hrg_belikov=0;
   $jml_brgkov=$jml_brg*$jumbeli;
   $hrg_belikov=($hrg_beli*$jml_brg)/$jml_brgkov;//konversi hrg beli barang ke satuan terkecil

  //cek jml barang lalu utk edit data pembelian barang dan dikonversikan satuan terkecil
   $jml_brglalukov=$jml_brglalu=$kd_satlalu=$jumsatlalu=0;
   $ceknota=mysqli_query($connect,"select * from beli_brg where no_urut='$no_urutnota'");
   if(mysqli_num_rows($ceknota)>0){
    $cari=mysqli_fetch_array($ceknota);
    $jml_brglalu=$cari['jml_brg'];
    $kd_satlalu=$cari['kd_sat'];  
   }
   
   unset($cari); mysqli_free_result($ceknota);
   
  if ($kd_satlalu==$kd_sat1) {
    $jumsatlalu=$jum_sat1;
  }
  if ($kd_satlalu==$kd_sat2) {
    $jumsatlalu=$jum_sat2;
  }
  if ($kd_satlalu==$kd_sat3) {
    $jumsatlalu=$jum_sat3;
  }
  $jml_brglalukov=$jml_brglalu*$jumsatlalu; // konversi jumlah barang satuan terkecil
    
  //cek sdh ada mutasi apa blm
    $adamutasi=0;
    $cekjual   = mysqli_query($connect,"SELECT COUNT(*) AS jmlitem from dum_jual where no_item='$no_urutnota'");
    $dada      = mysqli_fetch_assoc($cekjual);
    $adamutasi = $dada['jmlitem'];
    unset($cekjual,$dada); 
  //---------------------
  
  // cek mas_brg semua konversi ke stok terkecil
   $kon=opendtcek();
   $mskawal=$msknew=$jml_brgawal=$jml_brgnew=$brg_klr=0;
   $cek         = mysqli_query($kon,"select * from mas_brg where kd_brg='$kd_brg'");
   if(mysqli_num_rows($cek)>0){
    $data        = mysqli_fetch_array($cek);
    $brg_klr     = $data['brg_klr'];
    $mskawal     = $data['brg_msk'];
    $msknew      = $mskawal+$jml_brgkov;//utk replace brg_msk baru
    $jml_brgawal = $data['jml_brg'];
    $jml_brgnew  = $jml_brgawal+$jml_brgkov;//utk replace jml_brg baru
   }
   mysqli_close($kon);

   // tentukan jml barang jika koreksi nota
   $x=$mskkor=0;
   $x=$mskawal-$jml_brglalukov;
   if($x<0){$x=0;}
   $mskkor=$x+$jml_brgkov;//utk repl brg msk koreksi

   $xx=0;$jml_brgkor=0;
   $xx=$jml_brgawal-$jml_brglalukov;
   if($xx<0){$xx=0;}
   $jml_brgkor=$xx+$jml_brgkov;//utk repl jml_brg koreksi
  //------------------------------------------------------

  // Insert beli_brg  
  $xx       = $stok_jual_kor=$stok_jual_lalu=$last_kd_sat=$edit=0;  
  $ceknota1 = mysqli_query($connect,"select * from beli_brg where no_urut='$no_urutnota'");
  if(mysqli_num_rows($ceknota1)>=1){
    //cek untuk barang awal pd nota utk koreksi mas_brg
    $kons=opendtcek();
    $d=mysqli_query($kons,"UPDATE beli_brg SET kd_bar='$kd_bar',kd_sup='$kd_sup',kd_sat='$kd_sat',hrg_beli='$hrg_beli',disc1='$discitem1',disc2='$discitem2',jml_brg='$jml_brg',kd_sup='$kd_sup',id_bag='$id_bag' WHERE no_urut='$no_urutnota'");
    $d=mysqli_query($kons,"UPDATE beli_brg SET id_bag='$id_bag' WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko' AND ket='PEMBELIAN BARANG'");
      if (empty($ketbel)){
        $ketbel="PEMBELIAN BARANG";
      }  
      if($adamutasi==0){
        $d=mysqli_query($kons,"UPDATE beli_brg SET stok_jual='$jml_brgkov',ket='$ketbel' WHERE no_urut='$no_urutnota' AND id_bag='$id_bag' AND kd_toko='$kd_toko'"); 
      }else{
        //$stok_jual_kor=$jml_brgkov-$brg_klr;  
        $d=mysqli_query($kons,"UPDATE beli_brg SET ket='$ketbel',id_bag='$id_bag' WHERE no_urut='$no_urutnota' AND kd_toko='$kd_toko'");  
      }
    $edit=1;
    $rek_end=$no_urutnota;
    mysqli_close($kons);

    //update master barang
    $conn=opendtcek();
    $ceknota=mysqli_query($conn,"select * from mas_brg where kd_brg='$kd_brg'");
    if(mysqli_num_rows($ceknota)>=1){    
      if($adamutasi==0){ 
        //echo '$jml_brgkor='.$jml_brgkor.'<br>';
        $jmlstok=0;
        $cek12      = mysqli_query($conn,"SELECT sum(stok_jual) as jmlstok from beli_brg where kd_brg='$kd_brg'");
        $datacek12  = mysqli_fetch_assoc($cek12);
        $jmlstok    = $datacek12['jmlstok'];
        $totjumbeli = $jmlstok+$brg_klr;
        unset($cek12,$datacek12);

        $d=mysqli_query($conn,"UPDATE mas_brg set nm_brg='$nm_brg',jml_brg='$jmlstok',kd_bar='$kd_bar',kd_kem1='$kd_sat1',jum_kem1='$jum_sat1',hrg_jum1='$hrg_jum1',kd_kem2='$kd_sat2',jum_kem2='$jum_sat2',hrg_jum2='$hrg_jum2',kd_kem3='$kd_sat3',jum_kem3='$jum_sat3',hrg_jum3='$hrg_jum3',brg_msk='$totjumbeli',nm_kem1='$nm_sat1',nm_kem2='$nm_sat2',nm_kem3='$nm_sat3' WHERE kd_brg='$kd_brg'");
      }else{
        $d=mysqli_query($conn,"UPDATE mas_brg set nm_brg='$nm_brg',kd_bar='$kd_bar',kd_kem1='$kd_sat1',jum_kem1='$jum_sat1',hrg_jum1='$hrg_jum1',kd_kem2='$kd_sat2',jum_kem2='$jum_sat2',hrg_jum2='$hrg_jum2',kd_kem3='$kd_sat3',jum_kem3='$jum_sat3',hrg_jum3='$hrg_jum3',nm_kem1='$nm_sat1',nm_kem2='$nm_sat2',nm_kem3='$nm_sat3' WHERE kd_brg='$kd_brg'");
      }
      // update pada disctetap
      $cekawal=mysqli_query($conn,"SELECT * from disctetap WHERE kd_brg='$kd_brg'");
      if(mysqli_num_rows($cekawal)>=1){
        $dele=opendtcek();
        $d=mysqli_query($dele,"DELETE FROM disctetap WHERE kd_brg='$kd_brg'");
        mysqli_close($dele);
      }  
      //echo 'delete disctetap'; 
      if ($kd_sat4 >1){
        $d=mysqli_query($conn,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat4','$hrg_jum4','$lim_jual1','$kd_toko')");
      }
      if ($kd_sat5 >1){
        $d=mysqli_query($conn,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat5','$hrg_jum5','$lim_jual2','$kd_toko')");
      }
      if ($kd_sat6 >1){
        $d=mysqli_query($conn,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat6','$hrg_jum6','$lim_jual3','$kd_toko')");
      }
      unset($cekawal);
    }else{ 
      // JIKA PD MAS_BRG BLM ADA
      $d=mysqli_query($conn,"INSERT INTO mas_brg VALUES('','$kd_brg','$nm_brg','$jml_brgkov','$kd_bar','$kd_sat1','$jum_sat1','$hrg_jum1','$kd_sat2','$jum_sat2','$hrg_jum2','$kd_sat3','$jum_sat3','$hrg_jum3','$jml_brgkov','0','$kd_toko','$nm_sat1','$nm_sat2','$nm_sat3','','','')");
      if ($kd_sat4 >1){
        $d=mysqli_query($conn,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat4','$hrg_jum4','$lim_jual1','$kd_toko')");
      }
      if ($kd_sat5 >1){
        $d=mysqli_query($conn,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat5','$hrg_jum5','$lim_jual2','$kd_toko')");
      }
      if ($kd_sat6 >1){
        $d=mysqli_query($conn,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat6','$hrg_jum6','$lim_jual3','$kd_toko')");
      }
    }  
    unset($ceknota);
    mysqli_close($conn);
    //-------------------------------
    
    //Update pembayaran nota -----------------
    $disc1=0;$disc2=0;$jmlsub=0;$gtot=0;$ket='';
    $cek = mysqli_query($connect, "SELECT disc1,disc2,hrg_beli,jml_brg FROM beli_brg WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC ");
    while ($data1=mysqli_fetch_array($cek)) {
      $disc1=mysqli_escape_string($connect,$data1['disc1'])/100;
      $disc2=mysqli_escape_string($connect,$data1['disc2']);
      if ($data1['disc1']=='0.00'){
        // echo gantiti($data['disc2']);
        $jmlsub=(mysqli_escape_string($connect,$data1['hrg_beli'])-$disc2)*mysqli_escape_string($connect,$data1['jml_brg']);
      }else{
        $jmlsub=(mysqli_escape_string($connect,$data1['hrg_beli'])-(mysqli_escape_string($connect,$data1['hrg_beli'])*$disc1))*mysqli_escape_string($connect,$data1['jml_brg']);
      }
      if ($data1['disc1']=='0.00' && $data1['disc2']=='0'){
        $jmlsub=mysqli_escape_string($connect,$data1['jml_brg'])*mysqli_escape_string($connect,$data1['hrg_beli']);
      }   
      $gtot=$gtot+$jmlsub;  
      //echo '$gtot='.$gtot."<br>";
    }
    unset($cek,$data1);

    $cek=mysqli_query($connect,"SELECT ket FROM beli_bay WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC");
    if(mysqli_num_rows($cek)>0){
      $data=mysqli_fetch_assoc($cek);
      $ket=$data['ket'];
    }else{$ket="";}
    unset($cek,$data);

    $tgl_tran=date("Y-m-d");
    if($ket=='TUNAI'){
      mysqli_query($connect,"UPDATE beli_bay set tgl_tran='$tgl_tran',tot_beli='gtot',saldo_awal='$gtot',byr_hutang='$gtot',saldo_hutang='0',pay='0',kd_sup='$kd_sup' WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak'");

    }else{
      $byr_hutang=0;$saldo_hutang_hutang=0;
      $cek=mysqli_query($connect,"SELECT * from beli_bay WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC");
      if(mysqli_num_rows($cek)>0){
        $data=mysqli_fetch_assoc($cek);
        $byr_hutang=$data['byr_hutang'];
        $no_urutbay=$data['no_urut'];
        $saldo_hutang=$gtot-$byr_hutang;
        $d=mysqli_query($connect,"UPDATE beli_bay set tgl_tran='$tgl_tran',tot_beli='gtot',saldo_awal='$gtot',saldo_hutang='$saldo_hutang',ket='TEMPO',pay='0',kd_sup='$kd_sup' WHERE no_urut='$no_urutbay'");

        $cek1=mysqli_query($connect,"SELECT * from beli_bay_hutang WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC");
        $bayar=0;$sisa=0;
        while($data1=mysqli_fetch_assoc($cek1)){
          $no_urut=$data1['no_urut'];
          $bayar=$data1['byr_hutang'];
          $sisa=$gtot-$bayar;
          mysqli_query($connect,"UPDATE beli_bay_hutang set tgl_tran='$tgl_tran',totbeli='$gtot',saldo_awal='$gtot',saldo_hutang='$sisa',kd_sup='$kd_sup' WHERE no_urut='$no_urut'");      
          $gtot=$sisa;
        }
        unset($cek1,$data1);
      }
      unset($cek,$data);
    }

    //Jika edit dari mas_brg tentukan pay=1
    if(!empty($jump_file)){
      $d=mysqli_query($connect,"UPDATE beli_bay SET pay='1' WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak'");  
    }
    
  }else{ 
    // jika input data baru
    if (empty($ketbel)){
      $d=mysqli_query($connect,"INSERT INTO beli_brg values('','$tgl_fak','$no_fak','$kd_brg','$kd_bar','$kd_toko','$kd_sup','$kd_sat','$hrg_beli','$discitem1','$discitem2','$jml_brg','$jml_brgkov','PEMBELIAN BARANG','','','','','$id_bag')");
    } else {
      $d=mysqli_query($connect,"INSERT INTO beli_brg values('','$tgl_fak','$no_fak','$kd_brg','$kd_bar','$kd_toko','$kd_sup','$kd_sat','$hrg_beli','$discitem1','$discitem2','$jml_brg','$jml_brgkov','$ketbel','','','','','$id_bag')");
    }
    
    // insert master barang
    $cekdata=mysqli_query($connect,"SELECT * from mas_brg where kd_brg='$kd_brg'");
    if (mysqli_num_rows($cekdata)>=1){
      // echo '$jml_brgnew='.$jml_brgnew.'<br>';
      // echo '$msknew='.$msknew.'<br>';
      $d=mysqli_query($connect,"UPDATE mas_brg SET nm_brg='$nm_brg',jml_brg='$jml_brgnew',kd_bar='$kd_bar',kd_kem1='$kd_sat1',jum_kem1='$jum_sat1',hrg_jum1='$hrg_jum1',kd_kem2='$kd_sat2',jum_kem2='$jum_sat2',hrg_jum2='$hrg_jum2',kd_kem3='$kd_sat3',jum_kem3='$jum_sat3',hrg_jum3='$hrg_jum3',brg_msk='$msknew',nm_kem1='$nm_sat1',nm_kem2='$nm_sat2',nm_kem3='$nm_sat3' WHERE kd_brg='$kd_brg' ");
    }else{
      $d=mysqli_query($connect,"INSERT INTO mas_brg VALUES('','$kd_brg','$nm_brg','$jml_brgkov','$kd_bar','$kd_sat1','$jum_sat1','$hrg_jum1','$kd_sat2','$jum_sat2','$hrg_jum2','$kd_sat3','$jum_sat3','$hrg_jum3','$jml_brgkov','0','$kd_toko','$nm_sat1','$nm_sat2','$nm_sat3','','','')");
    }
    
    // insert discount tetap
    $cekdata=mysqli_query($connect,"SELECT * from disctetap where kd_brg='$kd_brg' AND kd_sat='$kd_sat1'");    
    // echo 'kd_sat1='.$kd_sat1.'<br>';
    // echo mysqli_num_rows($cekdata);
    if (mysqli_num_rows($cekdata)>=1){
      $d=mysqli_query($connect,"UPDATE disctetap SET hrg_jual='$hrg_jum4',lim_jual='$lim_jual1' WHERE kd_brg='$kd_brg' AND kd_sat='$kd_sat1'");
    }else {
      if ($kd_sat4 > 1){ // jika kd_sat4 bukan -NONE-
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat4','$hrg_jum4','$lim_jual1')");
      }
    }   
    
    $cekdata=mysqli_query($connect,"SELECT * from disctetap where kd_brg='$kd_brg' AND kd_sat='$kd_sat2'");    
    if (mysqli_num_rows($cekdata)>=1){
      $d=mysqli_query($connect,"UPDATE disctetap SET hrg_jual='$hrg_jum5',lim_jual='$lim_jual2' WHERE kd_brg='$kd_brg' AND kd_sat='$kd_sat2'");
    }else {
      if ($kd_sat5 > 1){ // jika kd_sat5 bukan -NONE-
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat5','$hrg_jum5','$lim_jual2')");
      }
    }

    $cekdata=mysqli_query($connect,"SELECT * from disctetap where kd_brg='$kd_brg' AND kd_sat='$kd_sat3' ");    
    if (mysqli_num_rows($cekdata)>=1){
      $d=mysqli_query($connect,"UPDATE disctetap SET hrg_jual='$hrg_jum6',lim_jual='$lim_jual3' WHERE kd_brg='$kd_brg' AND kd_sat='$kd_sat3'");
    }else {
      if ($kd_sat6 > 1){ // jika kd_sat6 bukan -NONE-
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat6','$hrg_jum6','$lim_jual3')");
      }
    }

  }  
  unset($ceknota1,$rakhir,$dataku);
  mysqli_close($connect);

  // jika edit form mas_brg
  if(!empty($jump_file)){
    // header("location:f_masbrg.php?pesankoreksi=$jump_file"); 
    ?><script>
     document.getElementById("kembali").href="f_masbrg.php?pesankoreksi=<?=$jump_file?>";
     document.getElementById("kembali").click();

      </script>
    <?php  
  }else{
    if($d){
      if ($edit==1){
        ?><script>popnew_warning("Pastikan anda lakukan bayar nota ulang"+"<br>"+"Dan periksa Transaksi hutang jika pembayaran tempo ! ");kosfaktur();</script><?php
      } else {
        ?><script>popnew_ok("Data berhasil disimpan");kosfaktur();</script><?php   
      } 
      
    } else {?><script>popnew_error("Data gagal disimpan");kosfaktur();</script><?php }    
  }

  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>