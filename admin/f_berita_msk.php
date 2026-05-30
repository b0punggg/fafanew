<?php
  include 'hubungkan.php';
  $conh      = hubung('ADMIN1');
  $no_fak    = $_POST['no_fak'];
  $tgl       = $_POST['tgl'];
  $kirim     = $_POST['kirim'];
  $berita    = $_POST['berita'];
  mysqli_query($conh,"INSERT INTO file_kirim VALUES('','$no_fak','$tgl','$kirim','$berita')");
?>
<script> 
  popnew_warning('<?=$berita?>');
</script>