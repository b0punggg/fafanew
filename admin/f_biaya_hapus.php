<?php 
  $id=$_POST['keydel'];
  ob_start();
  include "config.php";
  session_start();
  $kd_toko=$_SESSION['id_toko'];
  $connect=opendtcek(); 
  $id= $_POST['keydel'];

 $data=mysqli_query($connect,"SELECT * FROM biaya_ops WHERE id='$id'");
  if (mysqli_num_rows($data)>=1){
    // Hapus
    mysqli_query($connect,"DELETE FROM biaya_ops WHERE id='$id'")
    ?> <script type="text/javascript">popnew_ok("Data telah dihapus !!");caribiaya(1,true);</script> <?php 
  } else {
    // gagal
    ?> <script type="text/javascript">popnew_error("Data gagal dihapus !!");caribiaya(1,true);</script> <?php 
  }
  
  mysqli_close($connect);unset($data);

    
 $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
 ob_end_clean();
 echo json_encode(array('hasil'=>$html));
?>  