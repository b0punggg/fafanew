<?php
  $no_fak = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();

  include "config.php";
  session_start();
  $kd_toko=$_SESSION['id_toko'];
  $connect=opendtcek();
  $tgl_fak="0000-00-00";$tgl_tran="0000-00-00";
  $cek=mysqli_query($connect,"SELECT * from beli_bay where no_fak='$no_fak' and kd_toko='$kd_toko'");
  if(mysqli_num_rows($cek)>0){
    $data=mysqli_fetch_assoc($cek);
    $tgl_fak=$data['tgl_fak'];$tgl_tran=$data['tgl_tran'];
  }
  
?>
  <script>
    document.getElementById('tgl_fak').value='<?=mysqli_escape_string($connect,$tgl_fak)?>';
    document.getElementById('tgl_tran').value='<?=$_SESSION['tgl_set']?>';
    document.getElementById('tgl_jt').value='<?=$data['tgl_jt']?>';
    document.getElementById('saldo_awal').value='<?=mysqli_escape_string($connect,gantitides($data['tot_beli']))?>';
    document.getElementById('byr_hutang').value='';
    document.getElementById('saldo_hutang').value='<?=mysqli_escape_string($connect,gantitides($data['saldo_hutang']))?>';
    document.getElementById('byr_hutang').focus();
  </script>
<?php
  unset($cek,$data);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>