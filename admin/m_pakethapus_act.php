<?php 
  $id=$_POST['keydel'];
  ob_start();
  include "config.php";
  session_start();
  $kd_toko=$_SESSION['id_toko'];
  
 //cek sdh ada mutasi dum_jual apa blm
  $condelpak=opendtcek(); 
  $id=mysqli_real_escape_string($condelpak,$id);
  //hapus pada paket_mas
    $f=mysqli_query($condelpak, "Delete from paket_mas where no_urut='$id'" );
  //----------------------

  //Hapus pada paket_brg  
   $cekit=mysqli_query($condelpak,"SELECT * FROM paket_brg WHERE kd_paket='$id'");
   if (mysqli_num_rows($cekit)>=1){
    $f=mysqli_query($condelpak, "DELETE from paket_brg where kd_paket='$id'" );
   } 
  mysqli_close($condelpak);  
   
  if($f){
     ?><script>popnew_warning("Data telah dihapus.. ");kosongkan();caripaket(1,true);</script><?php
  }else {
     ?><script>popnew_error("Gagal hapus data.. ");kosongkan();caripaket(1,true);</script><?php
  }
    
 $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
 ob_end_clean();
 echo json_encode(array('hasil'=>$html));
?>  