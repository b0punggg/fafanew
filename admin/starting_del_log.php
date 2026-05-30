<?php 
  ob_start();
  include "config.php";
  session_start();
  $connect=opendtcek();
  date_default_timezone_set('Asia/Jakarta');
  $tghi       = date("Y-m-d H:i:s");
  $id_user    = $_SESSION['id_user'];
  $oto        = $_SESSION['kodepemakai'];
  $xs         = explode(";",mysqli_escape_string($connect,$_POST['keyword']));
  $no_fakjual = trim(mysqli_escape_string($connect,$xs[0]));
  $ket        = trim(mysqli_escape_string($connect,$xs[1]));
  $kd_toko    = trim(mysqli_escape_string($connect,$xs[2]));
  $pildel     = trim(mysqli_escape_string($connect,$xs[3]));
  $q          = mysqli_query($connect,"SELECT * FROM seting WHERE nm_per='POTONG'");
  $d          = mysqli_fetch_assoc($q);
  $potong     = $d['kode'];
  unset($q,$d);
  
  if($ket=='Hapus Nota Jual'){
    $cek=mysqli_query($connect,"SELECT dum_jual.kd_sat,dum_jual.kd_brg,dum_jual.qty_brg,dum_jual.no_item,dum_jual.no_fakjual,dum_jual.bayar,beli_brg.stok_jual,mas_brg.no_urut,mas_brg.jml_brg,mas_brg.brg_klr FROM dum_jual 
    LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg 
    LEFT JOIN beli_brg ON dum_jual.no_item=beli_brg.no_urut 
    WHERE dum_jual.kd_toko='$kd_toko' AND dum_jual.no_fakjual='$no_fakjual' ORDER BY dum_jual.no_urut ASC");   
  }
  if($ket=='Hapus Item Jual'){
    $cek=mysqli_query($connect,"SELECT file_log_cari.no_item AS noitem_l,dum_jual.kd_sat,dum_jual.kd_brg,dum_jual.qty_brg,dum_jual.no_item,dum_jual.no_fakjual,dum_jual.bayar,beli_brg.stok_jual,mas_brg.no_urut,mas_brg.jml_brg,mas_brg.brg_klr FROM file_log_cari 
    LEFT JOIN dum_jual on file_log_cari.no_item=dum_jual.no_urut 
    LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg 
    LEFT JOIN beli_brg ON dum_jual.no_item=beli_brg.no_urut 
    WHERE file_log_cari.pilih='1' AND file_log_cari.konfir='T'
    ORDER BY dum_jual.no_urut ASC");
  }
  
  if(mysqli_num_rows($cek)>=1){    
    if($pildel=='D'){
      if($ket=='Hapus Nota Jual'){
        while ( $data=mysqli_fetch_array($cek)) {
          $brg_klrawal = $stok_jualawal=$jml_brgawal=0;   
          $kd_satawal  = $data['kd_sat'];
          $kd_brgawal  = mysqli_escape_string($connect,$data['kd_brg']);
          $qty_brgawal = $data['qty_brg'];
          $bayarawal   = $data['bayar'];
          $no_itemawal = $data['no_item'];
          $no_fakjual  = mysqli_escape_string($connect,$data['no_fakjual']); 
            
          //konversikan jml.brang satuan kecil;
          $kon_qty       = 0;
          $kon_qty       = konjumbrg2($kd_satawal,$kd_brgawal,$connect)*$qty_brgawal;
          
          // update pada file masing2
          if ($bayarawal=="SUDAH"){
            $f=mysqli_query($connect, "DELETE from mas_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'" ); 
            $f=mysqli_query($connect, "DELETE from mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'" ); 
          } 
        
          if ($potong==1){
            $cbl           = mysqli_query($connect,"SELECT stok_jual FROM beli_brg WHERE no_urut='$no_itemawal'");
            $dbl           = mysqli_fetch_assoc($cbl);
            $stok_jualawal = $dbl['stok_jual']+$kon_qty;
            mysqli_free_result($cbl);unset($dbl);
            $f=mysqli_query($connect, "UPDATE beli_brg set stok_jual='$stok_jualawal' WHERE no_urut='$no_itemawal' ");  

            //pd mas_brg
            $connect2=opendtcek();
            $cbrg        = mysqli_query($connect2,"SELECT jml_brg,brg_klr,no_urut FROM mas_brg WHERE kd_brg='$kd_brgawal'");
            $dbrg        = mysqli_fetch_assoc($cbrg);
            $no_urutbrg  = $dbrg['no_urut'];
            $brg_klrawal = $dbrg['brg_klr']-$kon_qty;
            $jml_brgawal = $dbrg['jml_brg']+$kon_qty;        
            mysqli_free_result($cbrg);unset($dbrg);
            $f=mysqli_query($connect2, "UPDATE mas_brg SET brg_klr='$brg_klrawal',jml_brg='$jml_brgawal' WHERE no_urut='$no_urutbrg' ");
            mysqli_close($connect2);
          }
          ?><div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div><?php
        } 
        mysqli_query($connect,"UPDATE file_log SET konfir='D',w_konfir='$tghi' WHERE no_fak='$no_fakjual' AND ket='$ket' ");
        mysqli_query($connect,"UPDATE file_log_cari SET konfir='D' WHERE no_fakjual='$no_fakjual' AND ket='$ket' ");
        $f=mysqli_query($connect, "DELETE from dum_jual WHERE no_fakjual='$no_fakjual'" ); 
        if($f){
          ?><script> document.getElementById('formcarilog').style.display='none';popnew_ok("Nota berhasil dihapus ");</script><?php   
        }
      }  
      if($ket=='Hapus Item Jual'){ 
        $f=false;
        while ($data=mysqli_fetch_array($cek)) {
          $brg_klrawal = $stok_jualawal=$jml_brgawal=0;   
          $kd_satawal  = $data['kd_sat'];
          $kd_brgawal  = mysqli_escape_string($connect,$data['kd_brg']);
          $qty_brgawal = $data['qty_brg'];
          $bayarawal   = $data['bayar'];
          $no_itemawal = $data['no_item'];
          $noitem_l    = $data['noitem_l']; 
          $faktur      = $data['no_fakjual']; 
          //konversikan jml.brang satuan kecil;
          $kon_qty       = 0;
          $kon_qty       = konjumbrg2($kd_satawal,$kd_brgawal,$connect)*$qty_brgawal;
          if ($potong==1){
            $cbl           = mysqli_query($connect,"SELECT stok_jual FROM beli_brg WHERE no_urut='$no_itemawal'");
            $dbl           = mysqli_fetch_assoc($cbl);
            $stok_jualawal = $dbl['stok_jual']+$kon_qty;
            mysqli_free_result($cbl);unset($dbl);
            $f=mysqli_query($connect, "UPDATE beli_brg set stok_jual='$stok_jualawal' WHERE no_urut='$no_itemawal' ");  

            //pd mas_brg
            $connect2=opendtcek();
            $cbrg        = mysqli_query($connect2,"SELECT jml_brg,brg_klr,no_urut FROM mas_brg WHERE kd_brg='$kd_brgawal'");
            $dbrg        = mysqli_fetch_assoc($cbrg);
            $no_urutbrg  = $dbrg['no_urut'];
            $brg_klrawal = $dbrg['brg_klr']-$kon_qty;
            $jml_brgawal = $dbrg['jml_brg']+$kon_qty;        
            mysqli_free_result($cbrg);unset($dbrg);
            $f=mysqli_query($connect2, "UPDATE mas_brg SET brg_klr='$brg_klrawal',jml_brg='$jml_brgawal' WHERE no_urut='$no_urutbrg' ");
            mysqli_close($connect2);
          }
          $cond=opendtcek();
          $f=mysqli_query($cond,"DELETE FROM dum_jual WHERE no_urut='$noitem_l'");
          mysqli_close($cond);
          ?><div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div><?php
        } 
        if($f){
          ?><script> document.getElementById('formcarilog').style.display='none';popnew_ok("Data berhasil dihapus ");</script><?php   
        }
        $cari=mysqli_query($connect,"SELECT * FROM dum_jual where no_fakjual='$no_fakjual' AND kd_toko='$kd_toko' ");
        if(mysqli_num_rows($cari)>0){
          mysqli_query($connect,"UPDATE dum_jual SET bayar='BELUM' WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");
          mysqli_query($connect,"DELETE FROM mas_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");

          mysqli_query($connect,"DELETE FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");

          mysqli_query($connect,"UPDATE file_log_cari SET konfir='D' WHERE no_fakjual='$no_fakjual' AND ket='$ket' ");
          
          mysqli_query($connect,"UPDATE file_log SET konfir='D',w_konfir='$tghi' WHERE no_fak='$no_fakjual' AND ket='$ket' ");
        }else{
          mysqli_query($connect,"DELETE FROM mas_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");
          mysqli_query($connect,"DELETE FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");
          mysqli_query($connect,"UPDATE file_log_cari SET konfir='A' WHERE no_fakjual='$no_fakjual' AND ket='$ket' AND pilih='1' ");
          mysqli_query($connect,"UPDATE file_log SET konfir='A',w_konfir='$tghi' WHERE no_fak='$no_fakjual' AND ket='$ket' ");
        }
        unset($cari);
      }
    }else{
      $cos=opendtcek();
      $f=false;
      if($ket=='Hapus Item Jual'){
        $f=mysqli_query($cos,"UPDATE file_log_cari SET konfir='A' WHERE no_fakjual='$no_fakjual' AND ket='$ket' AND pilih='1' AND konfir='T'");
        $sq=mysqli_query($cos,"SELECT COUNT(*) AS jmlg FROM file_log_cari WHERE no_fakjual='$no_fakjual' AND ket='$ket' AND pilih='0' AND konfir='T'");
        $cd=mysqli_fetch_assoc($sq);
        echo "$cd[jmlg]=".$cd['jmlg'];
        if($cd['jmlg']==0){
          $f=mysqli_query($cos,"UPDATE file_log SET konfir='A',w_konfir='$tghi' WHERE no_fak='$no_fakjual' AND ket='$ket'");
        }
        mysqli_free_result($sq);unset($cd);
      }
      if($ket=='Hapus Nota Jual'){
        $f=mysqli_query($cos,"UPDATE file_log SET konfir='A',w_konfir='$tghi' WHERE no_fak='$no_fakjual' AND ket='$ket'");
        $f=mysqli_query($cos,"UPDATE file_log_cari SET konfir='A',pilih='1' WHERE no_fakjual='$no_fakjual' AND ket='$ket'");
      }
      mysqli_close($cos);

      if($f){
        ?><script> document.getElementById('formcarilog').style.display='none';popnew_warning("Notify diabaikan ")</script><?php
      }else{
        ?><script> document.getElementById('formcarilog').style.display='none';popnew_warning("Gagal Update")</script><?php
      }
    }
  }else {
    $cos=opendtcek();
    $f=mysqli_query($cos,"UPDATE file_log SET konfir='A',w_konfir='$tghi' WHERE no_fak='$no_fakjual' AND ket='$ket'");
    mysqli_close($cos);
    ?><script>popnew_error("Data Tidak ditemukan ! ")</script><?php
  }
  unset($cek,$data);  
  
?>
<script>
//   kosongkan2();
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     
<?php
  mysqli_close($connect);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>