<?php 
  $jenis=$_POST['jenis'];
  $n_size=$_POST['n_size'];
  $kd_bar=$_POST['kd_barsay'];
  $copies=$_POST['copies'];
  $cetprint=$kd_bar.';'.$n_size.';'.$copies;
  $cetxls=$cetprint;
  if ($jenis=='PRINTER'){
    header("location:f_masbrg_cetkdbar.php?pesan=$cetprint");
  }else{
    header("location:f_masbrg_xls.php?pesan=$cetxls");
  }
?>