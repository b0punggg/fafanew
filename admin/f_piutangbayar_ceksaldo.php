<?php
  $no_fakjual = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();

  include "config.php";
  session_start();
  $kd_toko=$_SESSION['id_toko'];
  $connect=opendtcek();
  $tgl_jual="0000-00-00";$tgl_tran="0000-00-00";$tgl_jt="0000-00-00";
  $cek=mysqli_query($connect,"SELECT * from mas_jual_hutang where no_fakjual='$no_fakjual' and kd_toko='$kd_toko' ORDER BY no_urut DESC LIMIT 1");
  if(mysqli_num_rows($cek)>0){
    $data=mysqli_fetch_assoc($cek);
    $tgl_jual=$data['tgl_jual'];$tgl_tran=$data['tgl_tran'];$tgl_jt=$data['tgl_jt'];
  }  
?>
  <script>
    document.getElementById('tgl_jual').value='<?=mysqli_escape_string($connect,$tgl_jual)?>';
    document.getElementById('tgl_jt').value='<?=gantitgl($tgl_jt)?>';
    document.getElementById('tgl_tran').value='<?=$_SESSION['tgl_set']?>';
    document.getElementById('byr_hutang').value='';
    // document.getElementById('saldo_hutang').value='<?=mysqli_escape_string($connect,gantitides($data['saldo_hutang']))?>';
    document.getElementById('byr_hutang').focus();
  </script>
<?php
  unset($cek,$data);mysqli_close($connect);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>