<?php 
  $no_urut = $_POST['keyword1']; // Ambil data keyword yang dikirim dengan AJAX	
  $kd_brginput = $_POST['keyword2'];
  $kd_satinput = $_POST['keyword3'];
  ob_start();

  include 'config.php';
  session_start();

  $connect=opendtcek();
   $no_urut = mysqli_real_escape_string($connect,$_POST['keyword1']);
   $kd_brginput = mysqli_real_escape_string($connect,$_POST['keyword2']);
   $kd_satinput = mysqli_real_escape_string($connect,$_POST['keyword3']);
  $kd_toko=$_SESSION['id_toko'];
  if(!empty($no_urut) && !empty($kd_satinput)) 
  {
    $kd_satawal=0;$jml_brg;
    $cek=mysqli_query($connect,"SELECT kd_sat,jml_brg from beli_brg WHERE no_urut='$no_urut'");
    $datcek=mysqli_fetch_assoc($cek);
    $kd_satawal=$datcek['kd_sat'];
    $jml_brg=$datcek['jml_brg'];
	  //---------------------------
	  $max=($jml_brg*konjumbrg($kd_satawal,$kd_brginput))/konjumbrg($kd_satinput,$kd_brginput);
	  
	  // baca stok barang saat ini
	   $min=0.25;
     // echo '$nu_rut='.$no_urut.'<br>';
     // echo '$max='.$max.'<br>';
     // echo '$konjumbrg='.konjumbrg($kd_satinput,$kd_brginput).'<br>';
	   ?>
	   <script>
	   	document.getElementById('qtyretur').min='<?=$min?>';
	   	document.getElementById('qtyretur').max='<?=$max?>';
	   </script>
	<?php 
	
   }
  unset($cek,$datcek); 
  mysqli_close($connect); 
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>