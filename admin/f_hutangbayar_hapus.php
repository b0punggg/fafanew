<?php 
  $keyword=$_POST['keyword'];
  ob_start();
	include 'config.php';
	session_start();
  $connect=opendtcek();
	$kd_toko=$_SESSION['id_toko'];
	$x=explode(';', mysqli_real_escape_string($connect,$keyword));
	$no_urut=$x[0];
	$no_fak=$x[1];
  $d=false;
  $d=mysqli_query($connect,"DELETE from beli_bay_hutang where no_urut='$no_urut'");
  $cek=mysqli_query($connect,"SELECT * from beli_bay_hutang where kd_toko='$kd_toko' AND no_fak='$no_fak' order by no_urut DESC limit 1");
  $data=mysqli_fetch_assoc($cek);
    $saldo_awal_lalu = $data['saldo_awal'];
    $tgl_tran        = $data['tgl_tran'];
    $byr_hutang      = $data['byr_hutang']; 
    $saldo_hutang_sek= $data['saldo_hutang'];
  
  unset($cek,$data);
  $d=mysqli_query($connect,"UPDATE beli_bay set saldo_awal='$saldo_awal_lalu',tgl_tran='$tgl_tran',saldo_hutang='$saldo_hutang_sek' where kd_toko='$kd_toko' AND no_fak='$no_fak' ");
  mysqli_close($connect);

  if($d){
    ?> 
      <script>
        popnew_warning("Data telah terhapus");
        kosong_save();carihutang(1,true);carinews(1,true)
        ceksaldo(document.getElementById('no_fak').value);
      </script>
    <?php 
  }
   else{
     ?><script>
         popnew_error("Data gagal dihapus !");
         kosong_save();carihutang(1,true);carinews(1,true)
         ceksaldo(document.getElementById('no_fak').value);
       </script>
     <?php 
   }    
  // if($d){header("location:f_hutangbayar.php?pesan=hapus");}
  //  else{header("location:f_hutangbayar.php?pesan=gagal");}    
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>