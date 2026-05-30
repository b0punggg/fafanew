<?php
  $no_fakjual = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();
  include "config.php";
  session_start();
  $connect=opendtcek();
  date_default_timezone_set('Asia/Jakarta');
  $tghi   = date("Y-m-d H:i:s");
  $kd_toko= $_SESSION['id_toko'];
  $id_user= $_SESSION['id_user'];
  $nm_user= $_SESSION['nm_user'];
  $oto    = $_SESSION['kodepemakai']; 
  $tglhr  = date('Y-m-d');
  
  // $csl=mysqli_query($connect,"SELECT * FROM file_log WHERE ket='Hapus Nota Jual' AND no_fak='$no_fakjual'");
  // $ada_l=false;
  // if(mysqli_num_rows($csl)>=1){
  //   $ada_l=true;
    mysqli_query($connect,"DELETE FROM file_log_cari WHERE ket='Hapus Nota Jual' AND no_fakjual='$no_fakjual'");
    mysqli_query($connect,"DELETE FROM file_log WHERE ket='Hapus Nota Jual' AND no_fak='$no_fakjual'");
  // }  
  $q=mysqli_query($connect,"SELECT dum_jual.*,kemas.nm_sat1 FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
  WHERE no_fakjual='$no_fakjual' ORDER BY no_urut");
  $no_l=0;
  while($ds=mysqli_fetch_assoc($q)){
    $no_l++;
    $no_urut_l   = $ds['no_urut'];
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
      // if($ada_l==false){
        mysqli_query($connect,"INSERT INTO file_log VALUES('','$tglhr','Hapus Nota Jual','$no_fakjual','$kd_brg_l','$tghi','$kd_toko','$nm_user','T','')");
      // }
    }
      mysqli_query($connect,"INSERT INTO file_log_cari VALUES('','Hapus Nota Jual','$no_fakjual','$kd_brg_l','$no_urut_l','$nm_brg_l','$qty_l','$nm_sat_l','$hrg_jual_l','$disc_l','T','0')");
  }
  unset($kd_brg_l,$nm_brg_l,$qty_l,$nm_sat_l,$hrg_jual_l,$ds,$no_l,$no_urut_l);
  mysqli_free_result($q);
  ?><script>popnew_ok("Menunggu Konfirmasi...")</script><?php
  mysqli_close($connect);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>  