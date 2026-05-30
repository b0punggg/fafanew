<?php
  $id = $_POST['keyword'];
  ob_start();
  include "config.php";
  session_start();
  $conhps=opendtcek();
  date_default_timezone_set('Asia/Jakarta');
  $tghi   = date("Y-m-d H:i:s");
  $kd_toko= $_SESSION['id_toko'];
  $id_user= $_SESSION['id_user'];
  $nm_user= $_SESSION['nm_user'];
  $oto    = $_SESSION['kodepemakai']; 
  $tglhr  = date('Y-m-d');
  
  $q=mysqli_query($conhps,"SELECT dum_jual.*,kemas.nm_sat1 FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut WHERE dum_jual.no_urut='$id' ORDER BY dum_jual.no_urut");
  $no_l=0;
  $ds=mysqli_fetch_assoc($q);  
  $no_l++;
  $no_urut_l  = $ds['no_urut'];
  $kd_brg_l   = $ds['kd_brg'];
  $nm_brg_l   = $ds['nm_brg'];
  $qty_l      = $ds['qty_brg']; 
  $nm_sat_l   = $ds['nm_sat1']; 
  $hrg_jual_l = $ds['hrg_jual']; 
  $no_fak_l   = mysqli_escape_string($conhps,$ds['no_fakjual']); 

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
  mysqli_query($conhps,"DELETE FROM file_log WHERE ket='Hapus Item Jual' AND no_fak='$no_fak_l'");
  mysqli_query($conhps,"DELETE FROM file_log_cari WHERE ket='Hapus Item Jual' AND no_fakjual='$no_fak_l' AND no_item='$id'");
  mysqli_query($conhps,"INSERT INTO file_log VALUES('','$tglhr','Hapus Item Jual','$no_fak_l','$kd_brg_l','$tghi','$kd_toko','$nm_user','T','')");
  mysqli_query($conhps,"INSERT INTO file_log_cari VALUES('','Hapus Item Jual','$no_fak_l','$kd_brg_l','$no_urut_l','$nm_brg_l','$qty_l','$nm_sat_l','$hrg_jual_l','$disc_l','T','0')");
  unset($kd_brg_l,$nm_brg_l,$qty_l,$nm_sat_l,$hrg_jual_l,$ds,$no_l,$no_fak_l);
  mysqli_free_result($q);

  ?><script>popnew_ok("Menunggu Konfirmasi...")</script><?php
  mysqli_close($conhps);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>  