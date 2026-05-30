<?php 
  // mysqli_close($conseek);
  include 'config.php';
  session_start();
  $conseek=opendtcek();

  $kd_toko    = $_POST['kd_tokolist'];
  $id_user    = $_SESSION['id_user'];
  $nm_user    = $_SESSION['nm_user']; 
  $tgl_biaya  = $_POST['tgl_biaya'];
  $nominal    = backnum($_POST['nominal']);     
  $ket_biaya  = nl2br($_POST['ket_biaya']);
  $id_rec     = $_POST['id_rec'];
  $id_jenis   = $_POST['id_jenis'];
  $d          = false;
  // Insert biaya_ops
  $cek_it=mysqli_query($conseek,"SELECT * FROM biaya_ops WHERE id='$id_rec'");    
  if (mysqli_num_rows($cek_it)>=1){
    //update
    $d=mysqli_query($conseek,"UPDATE biaya_ops SET tgl_biaya='$tgl_biaya',nominal='$nominal',ket_biaya='$ket_biaya',kd_toko='$kd_toko',id_jenis='$id_jenis' WHERE id='$id_rec'");
  } else {
    // SIMPAN
    $d=mysqli_query($conseek,"INSERT INTO biaya_ops VALUES('','$kd_toko','$tgl_biaya','$nominal','$ket_biaya','$id_jenis')");
  }
  mysqli_free_result($cek_it);

  if ($d){
    ?>
    <script type="text/javascript">popnew_ok("Data berhasil disimpan");kosongin();caribiaya(1,true);</script>
    <?php
  } else { ?>
    <script type="text/javascript">popnew_warning("Data gagal disimpan");kosongin();caribiaya(1,true);</script>
  <?php 
  }
?>
