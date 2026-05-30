<?php 
  ob_start();
  include "config.php";
  session_start();
   
  date_default_timezone_set('Asia/Jakarta');
  $tghi       = date("Y-m-d H:i:s");
  $id_user    = $_SESSION['id_user'];
  $kd_toko    = $_SESSION['id_toko'];
  $nm_user    = $_SESSION['nm_user'];
  $oto        = trim($_SESSION['kodepemakai']); 
  $tglhr      = date('Y-m-d');
  $id         = $_POST['id'];
  //$qty_brg    = $kd_sat=0;$kd_brg='';

  $conhps     = opendtcek();
  $cek1       = mysqli_query($conhps,"SELECT * FROM dum_jual where no_urut='$id'");
  if(mysqli_num_rows($cek1)>0){
    $data1       = mysqli_fetch_assoc($cek1);
    $qty_brg    = $data1['qty_brg'];
    $kd_sat     = $data1['kd_sat'];
    $kd_brg     = trim($data1['kd_brg']);
    $no_item    = $data1['no_item'];
    $bayar      = $data1['bayar'];
    $no_fakjual = trim($data1['no_fakjual']);
    $tgl_jual   = $data1['tgl_jual'];
    $jml_brg    = konjumbrg2($kd_sat,$kd_brg,$conhps)*$qty_brg;

    //logfile   
    $nm_brg_l   = $data1['nm_brg'];
    $nm_sat_l   = ceknmkem($kd_sat,$conhps);
    $hrg_jual_l = $data1['hrg_jual']; 
    if ($data1['discrp']>0){
      $discrp_l=$data1['discrp'];
    }else{
      $discrp_l=0;
    }
    if ($data1['discitem']>0){
      $discitem_l=$data1['hrg_jual']*($data1['discitem']/100);
    }else{
      $discitem_l=0;
    } 
    if($data1['discvo']>0){
      $discvo_l=$data1['hrg_jual']*($data1['discvo']/100);
    }else{
      $discvo_l=0;
    }
    $disc_l = $discrp_l+$discitem_l+$discvo_l;
    //**cek jika potong stok atau tidak
    $qp      = mysqli_query($conhps,"SELECT * FROM seting WHERE nm_per='POTONG'");
    $dp      = mysqli_fetch_assoc($qp);
    $potong = $dp['kode'];
    mysqli_free_result($qp);unset($dp);
   
    $cek2     = mysqli_query($conhps,"SELECT brg_klr,jml_brg FROM mas_brg where kd_brg='$kd_brg'");
    $data2    = mysqli_fetch_assoc($cek2);
    $brg_klr  = $data2['brg_klr'];
    $jumbrg   = $data2['jml_brg'];
    unset($data2);mysqli_free_result($cek2);

    $brg_klr=$brg_klr-$jml_brg;
    if ($brg_klr<0){
      $brg_klr=0;
      $jumbrg=0;
    }
    $jumbrg=$jumbrg+$jml_brg;
    
    $cek3        = mysqli_query($conhps,"SELECT stok_jual FROM beli_brg where no_urut='$no_item'");
    $data3       = mysqli_fetch_assoc($cek3);
    $jual_stok  = $data3['stok_jual'];
    $jual_stok  = $jual_stok+$jml_brg;
    unset($data3);mysqli_free_result($cek3);
  
    if($oto=='2'){ 
      if ($potong==1){
        $conhps2=opendtcek(); 
        $xf=mysqli_query($conhps2, "UPDATE beli_brg set stok_jual='$jual_stok' WHERE no_urut='$no_item' ");  
        $f=mysqli_query($conhps2, "UPDATE mas_brg SET brg_klr='$brg_klr',jml_brg='$jumbrg' WHERE kd_brg='$kd_brg'");
      }
      $f=mysqli_query($conhps2, "DELETE from dum_jual WHERE no_urut='$id'" );
      mysqli_close($conhps2);
    
      //cek pd mas_jual 
      $ada=0;
      $cari=mysqli_query($conhps,"SELECT * FROM dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' ");
      if(mysqli_num_rows($cari)>=1){
        $ada=1;
      }else{
        $ada=0;
        mysqli_query($conhps,"DELETE FROM mas_jual WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' AND kd_toko='$kd_toko'");
        mysqli_query($conhps,"DELETE FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' AND kd_toko='$kd_toko'");
      }
      unset($cari);

      if($f){
        if($ada==1){
          ?><script>
          popnew_warning("Data terhapus, silahkan update bayar nota");
          document.getElementById("edit-warning").value=0;
          kosongkan();
          caribrgjual(1,true);
          </script><?php  
        }else{ 
          ?><script>
          popnew_warning("Data telah terhapus...");
          document.getElementById("edit-warning").value=0;
          kosongkan();
          caribrgjual(1,true);
          </script><?php  
        }
      }else {
        ?><script>popnew_warning("Data gagal dihapus..");
        document.getElementById("edit-warning").value=0;
        kosongkan();
        caribrgjual(1,true);
        </script><?php  
      }
    }else{
      if($oto=='1'){
        //log file
        ?><script>
        if(confirm('Transaksi Hapus Per Item tidak dapat dilakukan, klik Oke untuk kirim permintaan ke Admin')){ <?php
          $conhpsl=opendtcek();
          $cla=mysqli_query($conhpsl,"SELECT * FROM file_log WHERE no_fak='$no_fakjual'");
            if(mysqli_num_rows($cla)>0){
              $f=mysqli_query($conhpsl,"DELETE FROM file_log WHERE no_fak='$no_fakjual'");
            }
            mysqli_free_result($cla);
          //hapus jika ada faktur yang sama tp hapus nota  
          $cl=mysqli_query($conhpsl,"SELECT * FROM file_log_cari WHERE no_fakjual='$no_fakjual' AND ket='Hapus Nota Jual'");
            if(mysqli_num_rows($cl)>0){
              mysqli_query($conhpsl,"DELETE FROM file_log_cari WHERE no_fakjual='$no_fakjual' AND ket='Hapus Nota Jual'");
            }
            mysqli_free_result($cl);  

          $cl=mysqli_query($conhpsl,"SELECT * FROM file_log_cari WHERE no_item='$id'");
            if(mysqli_num_rows($cl)>0){
              $df=mysqli_query($conhpsl,"DELETE FROM file_log_cari WHERE no_item='$id'");
            }
            mysqli_free_result($cl);
            mysqli_close($conhpsl);  

          $conhpsn=opendtcek();  
            mysqli_query($conhpsn,"INSERT INTO file_log VALUES('','$tglhr','Hapus Item Jual','$no_fakjual','$kd_brg','$tghi','$kd_toko','$nm_user','T','')");

            mysqli_query($conhpsn,"INSERT INTO file_log_cari VALUES('','Hapus Item Jual','$no_fakjual','$kd_brg','$id','$nm_brg_l','$qty_brg','$nm_sat_l','$hrg_jual_l','$disc_l','T','0')");
            unset($nm_brg_l,$nm_sat_l,$hrg_jual_l);
          mysqli_close($conhpsn); ?>
          popnew_warning("Menunggu konfirmasi..");
        } </script> <?php  
      } 
    }
  }else{
    ?><script>popnew_error("Terjadi kesalahan, gagal dihapus")</script><?php
  }
  mysqli_free_result($cek1);unset($data1);
  mysqli_close($conhps);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>