<?php
  ob_start();
  include "config.php";
  session_start();
  $conopname=opendtcek();
  $no_urut = mysqli_escape_string($conopname,trim($_POST['keyword1']));
  $ket     = mysqli_escape_string($conopname,nl2br(trim($_POST['keyword2'])));
  $df      = false;
  $sql     = mysqli_query($conopname,"SELECT * FROM mutasi_adj WHERE no_urut='$no_urut'");
  if(mysqli_num_rows($sql)){
    $df=mysqli_query($conopname,"UPDATE mutasi_adj SET ket='$ket' WHERE no_urut='$no_urut'");
    if($df){
      ?><script>popnew_ok("Update data berhasil")</script><?php
    }else{ ?><script>popnew_error("Gagal Simpan Data")</script><?php }
  }else{
    ?><script>popnew_warning("Data tidak ditemukan")</script><?php
  }
?>
<script>carimutasinote(1,true);</script>
<?php
  mysqli_close($conopname);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>