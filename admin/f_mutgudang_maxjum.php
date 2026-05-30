<?php
$kd_brg = $_POST['kdbrg']; // Ambil data keyword yang dikirim dengan AJAX	
$kd_toko= $_POST['kdtoko'];
$kd_sat = $_POST['kdsat'];
$no_urut = $_POST['nourut'];

ob_start();
// echo '$kd_brg='. $_POST['kdbrg'].'<br>';
// echo '$kd_toko='.$_POST['kdtoko'].'<br>';
// echo '$kd_sat='.$_POST['kdsat'].'<br>';

include 'config.php';
session_start();
$connect=opendtcek();
$cek=mysqli_query($connect, "SELECT * FROM beli_brg Where no_urut='$no_urut'");
$data=mysqli_fetch_assoc($cek);
$jumkem=konjumbrg($kd_sat,$kd_brg);
$brg_msk_hi=$data['stok_jual'];
$stok=round($brg_msk_hi/$jumkem,2);
// echo '$jumkem='.$jumkem;
?>
<script>
   document.getElementById('qty_brg').min="0";
   document.getElementById('qty_brg').max='<?=$stok?>';
   document.getElementById('qty_brg').value='0';
</script>
<?php
  mysqli_close($connect); 
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>