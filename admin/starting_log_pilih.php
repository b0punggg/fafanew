<?php
 ob_start();
 include "config.php";
 session_start();
 $conlog     = opendtcek();
 $xc         = explode(';',$_POST['keyword']);
 $jns        = trim($xc[0]);
 $idx        = trim($xc[1]);

 if($jns=='1'){
   $f=mysqli_query($conlog,"Update file_log_cari SET pilih='1' WHERE id='$idx'");
 }elseif($jns=='2'){
    $f=mysqli_query($conlog,"Update file_log_cari SET pilih='0' WHERE id='$idx'");
 }
 mysqli_close($conlog);
 $html = ob_get_contents(); 
 ob_end_clean();
 echo json_encode(array('hasil'=>$html));
?>