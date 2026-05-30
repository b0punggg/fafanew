<?php 
  $no_fakjual = $_POST['no_fakjual']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();
  include "config.php";
  session_start();
  $conhnot=opendtcek();
  date_default_timezone_set('Asia/Jakarta');
  $tghi   = date("Y-m-d H:i:s");
  $kd_toko= $_SESSION['id_toko'];
  $id_user= $_SESSION['id_user'];
  $nm_user= $_SESSION['nm_user'];
  $oto    = TRIM($_SESSION['kodepemakai']); 
  $q      = mysqli_query($conhnot,"SELECT * FROM seting WHERE nm_per='POTONG'");
  $d      = mysqli_fetch_assoc($q);
  $potong = $d['kode'];
  $tglhr  = date('Y-m-d');
  unset($q,$d);

  $cek=mysqli_query($conhnot,"SELECT dum_jual.kd_sat,dum_jual.kd_brg,dum_jual.qty_brg,dum_jual.no_item,dum_jual.no_fakjual,dum_jual.bayar,dum_jual.discitem,dum_jual.discrp,dum_jual.discvo,beli_brg.stok_jual,mas_brg.no_urut,mas_brg.jml_brg,mas_brg.brg_klr FROM dum_jual 
  LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg 
  LEFT JOIN beli_brg ON dum_jual.no_item=beli_brg.no_urut 
  WHERE dum_jual.kd_toko='$kd_toko' AND dum_jual.no_fakjual='$no_fakjual' ORDER BY dum_jual.no_urut ASC");

  if(mysqli_num_rows($cek)>=1){    
    if($oto=='2'){
      while ( $data=mysqli_fetch_array($cek)) { 
        $brg_klrawal = $stok_jualawal=$jml_brgawal=0;   
        $kd_satawal  = $data['kd_sat'];
        $kd_brgawal  = mysqli_escape_string($conhnot,$data['kd_brg']);
        $qty_brgawal = $data['qty_brg'];
        $bayarawal   = $data['bayar'];
        $no_itemawal = $data['no_item'];
        $no_fakjual  = mysqli_escape_string($conhnot,$data['no_fakjual']); 
          
        //konversikan jml.brang satuan kecil;
        $kon_qty       = 0;
        $kon_qty       = konjumbrg2($kd_satawal,$kd_brgawal,$conhnot)*$qty_brgawal;

        // update pada file masing2
        if ($bayarawal=="SUDAH"){
          $f=mysqli_query($conhnot, "DELETE from mas_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'" ); 
          $f=mysqli_query($conhnot, "DELETE from mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'" ); 
        } 
        if ($potong==1){
          $cbl           = mysqli_query($conhnot,"SELECT stok_jual FROM beli_brg WHERE no_urut='$no_itemawal'");
          $dbl           = mysqli_fetch_assoc($cbl);
          $stok_jualawal = $dbl['stok_jual']+$kon_qty;
          mysqli_free_result($cbl);unset($dbl);
          $f=mysqli_query($conhnot, "UPDATE beli_brg set stok_jual='$stok_jualawal' WHERE no_urut='$no_itemawal' ");  

          //pd mas_brg
          $conhnot2=opendtcek();
          $cbrg        = mysqli_query($conhnot2,"SELECT jml_brg,brg_klr,no_urut FROM mas_brg WHERE kd_brg='$kd_brgawal'");
          $dbrg        = mysqli_fetch_assoc($cbrg);
          $no_urutbrg  = $dbrg['no_urut'];
          $brg_klrawal = $dbrg['brg_klr']-$kon_qty;
          $jml_brgawal = $dbrg['jml_brg']+$kon_qty;        
          mysqli_free_result($cbrg);unset($dbrg);
          $f=mysqli_query($conhnot2, "UPDATE mas_brg SET brg_klr='$brg_klrawal',jml_brg='$jml_brgawal' WHERE no_urut='$no_urutbrg' ");
          mysqli_close($conhnot2);
        }
      } 
      $f=mysqli_query($conhnot, "DELETE from dum_jual WHERE no_fakjual='$no_fakjual'" ); 
        ?><script>caribrgjual(1,true);kosongkan2();</script><?php    
    }else{
      if($oto=='1'){
        $qcroto=mysqli_query($conhnot,"SELECT dum_jual.*,kemas.nm_sat1 FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        WHERE no_fakjual='$no_fakjual' ORDER BY no_urut");
        if(mysqli_num_rows($qcroto)>0){
          
          ?><script>
          if(confirm('Transaksi Hapus Nota tidak dapat dilakukan, klik Oke untuk kirim permintaan ke Admin'))
          { 
            <?php
            $cla=mysqli_query($conhnot,"SELECT * FROM file_log WHERE no_fak='$no_fakjual'");
            if(mysqli_num_rows($cla)>0){
              $f=mysqli_query($conhnot,"DELETE FROM file_log WHERE no_fak='$no_fakjual'");
            }
            mysqli_free_result($cla);

            $cl=mysqli_query($conhnot,"SELECT * FROM file_log_cari WHERE no_fakjual='$no_fakjual'");
            if(mysqli_num_rows($cl)>0){
              mysqli_query($conhnot,"DELETE FROM file_log_cari WHERE no_fakjual='$no_fakjual'");
            }
            mysqli_free_result($cl);
            $no_l=0;
            while($ds=mysqli_fetch_assoc($qcroto)){
              $no_l++;
              $no_urut_l  = $ds['no_urut'];
              $kd_brg_l   = $ds['kd_brg'];
              $nm_brg_l   = $ds['nm_brg'];
              $qty_l      = $ds['qty_brg']; 
              $nm_sat_l   = $ds['nm_sat1']; 
              $hrg_jual_l = $ds['hrg_jual']; 
              if ($ds['discrp']>0){
                $discrp_l=$ds['discrp'];
              }else{
                $discrp_l=0;
              }
              if ($ds['discitem']>0){
                $discitem_l=$ds['hrg_jual']*($ds['discitem']/100);
              }else{
                $discitem_l=0;
              } 
              if($ds['discvo']>0){
                $discvo_l=$ds['hrg_jual']*($ds['discvo']/100);
              }else{
                $discvo_l=0;
              }
              $disc_l = $discrp_l+$discitem_l+$discvo_l;
              if($no_l==1){
                mysqli_query($conhnot,"INSERT INTO file_log VALUES('','$tglhr','Hapus Nota Jual','$no_fakjual','$kd_brg_l','$tghi','$kd_toko','$nm_user','T','')");
              }
              mysqli_query($conhnot,"INSERT INTO file_log_cari VALUES('','Hapus Nota Jual','$no_fakjual','$kd_brg_l','$no_urut_l','$nm_brg_l','$qty_l','$nm_sat_l','$hrg_jual_l','$disc_l','T','0')");
            }  
            unset($kd_brg_l,$nm_brg_l,$qty_l,$nm_sat_l,$hrg_jual_l,$ds,$no_l,$no_urut_l);
            ?>        
            popnew_warning("Menunggu konfirmasi ...");
          }
          </script><?php
        }
        mysqli_free_result($qcroto);
      }
    }
  }else {
    ?><script>popnew_ok("Wis Angel..kih. angel.. <?=$_SESSION['nm_user']?>, nota kosong !! ")</script><?php
  }
  unset($cek,$data);  
    
?>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     
<?php
  mysqli_close($conhnot);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>