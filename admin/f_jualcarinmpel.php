<?php
  $keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();
  include "config.php";
  session_start();
  $connect=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
  $id_user=$_SESSION['id_user'];
  $cek=mysqli_query($connect,"SELECT * from dum_jual where no_fakjual='$keyword' and kd_toko='$kd_toko'");
  $data=mysqli_fetch_array($cek);
  $nm_pel='';$al_pel='';$kd_pel='';
  $kd_pel=mysqli_escape_string($connect,$data['kd_pel']); 
  $kd_bayar=mysqli_escape_string($connect,$data['kd_bayar']); 	
  // $nm_pel=mysqli_escape_string($connect,$data['nm_pel']);   
  $tgl_jt=mysqli_escape_string($connect,$data['tgl_jt']);   
  unset($cek,$data);
  $cek=mysqli_query($connect,"SELECT * from pelanggan where kd_pel='$kd_pel'");
  
  if(mysqli_num_rows($cek)>=1){
  	$data=mysqli_fetch_array($cek);
    $kd_pel=mysqli_escape_string($connect,$data['kd_pel']); 
    $nm_pel=mysqli_escape_string($connect,$data['nm_pel']); 
    // $al_pel=mysqli_escape_string($connect,$data['al_pel']); 	

  }else{
  	$kd_pel='IDPEL-0';$kd_bayar="TUNAI";$tgl_jt=date('Y-m-d');  
    $nm_pel="UMUM";
  }
  unset($cek,$data);
  
?>
<script>
	document.getElementById("kd_pel").value='<?=$kd_pel?>';
	// document.getElementById("nm_pelbayar").value='<?=$nm_pel?>';
 //  document.getElementById("kd_pel_byr").value='<?=$kd_pel?>';
  //document.getElementById("kd_bayar2").value='TUNAI';
	// document.getElementById("al_pel").value='<?=$al_pel?>';
  document.getElementById("tgl_jt").value='<?=$tgl_jt?>';
	document.getElementById("cr_bay").value='<?=$kd_bayar?>';
	document.getElementById("kd_bar").focus();
</script>
<?php
mysqli_close($connect);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>