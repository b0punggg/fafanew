<?php 
  $keyword=$_POST['keyword'];
  ob_start();
	include 'config.php';
	session_start();
  $connect=opendtcek();
	$kd_toko=$_SESSION['id_toko'];
	$x=explode(';', mysqli_real_escape_string($connect,$keyword));
	$no_urut=$x[0];
	$no_fakjual=$x[1];
  //echo '$no_urut='.$no_urut;
  $d=mysqli_query($connect,"DELETE FROM mas_jual_hutang where no_urut='$no_urut'");

  $conca=opendtcek();
  $cek=mysqli_query($conca,"SELECT * from mas_jual_hutang where kd_toko='$kd_toko' AND no_fakjual='$no_fakjual' order by no_urut DESC limit 1");
  $data=mysqli_fetch_assoc($cek);
    $saldo_awal_lalu = $data['saldo_awal'];
    $tgl_tran        = $data['tgl_tran'];
    $byr_hutang      = $data['byr_hutang']; 
    $saldo_hutang_sek= $data['saldo_hutang'];
  unset($cek,$data);mysqli_close($conca);
  if ($saldo_hutang_sek==0){$ket="LUNAS";} else{$ket="BELUM";}
  $d=mysqli_query($connect,"UPDATE mas_jual set saldo_hutang='$saldo_hutang_sek',ket_bayar='$ket' where kd_toko='$kd_toko' AND no_fakjual='$no_fakjual' ");
  mysqli_close($connect);

  if($d){
    ?> 
      <script>
        popnew_warning("Data telah terhapus");
        kosong_save();
        ceksaldo_p(document.getElementById('no_fakjual').value);
        caripiutang(1,true);
        carinews(1,true);
      </script>
    <?php 
  }
   else{
     ?><script>
         popnew_error("Data gagal dihapus !");
         kosong_save();
         ceksaldo_p(document.getElementById('no_fakjual').value);
         caripiutang(1,true);
         carinews(1,true);
       </script>
     <?php 
   }    

  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>