<?php
	ob_start();
?>

<?php 
  include 'config.php';
  session_start();
  $oto       = $_SESSION['kodepemakai'];
  $tgl_retur = $_SESSION['tgl_set'];
  $kd_toko   = $_SESSION['id_toko'];
  $id_user   = $_SESSION['id_user'];
  $hubr      = opendtcek();
  $no_urut   = $_POST['keyword1'];
  $tgl_retur = $_POST['keyword2'];
  $no_returjual = mysqli_real_escape_string($hubr,$_POST['keyword3']);
  $s1=mysqli_query($hubr,"SELECT * FROM dum_jual WHERE no_urut='$no_urut'");
  if (mysqli_num_rows($s1)>=1){
    $d1=mysqli_fetch_assoc($s1);
    $no_fakjual = $d1['no_fakjual'];
    $tgl_jual   = $d1['tgl_jual'];
    $no_urutbeli= $d1['no_item'];
    $no_urutjual= $d1['no_urut'];
    $qty_brg    = $d1['qty_brg'];

    // cek pada retur_jual data sdh ada tdk
    $c1=mysqli_query($hubr,"SELECT * FROM retur_jual WHERE no_urutjual='$no_urutjual' AND no_fakjual='$no_fakjual' AND kd_toko='$kd_toko' AND tgl_jual='$tgl_jual'");
    if (mysqli_num_rows($c1)>=1){
      $dc1=mysqli_fetch_assoc($c1);
      $cno_urut=$dc1['no_urutretur'];
      mysqli_query($hubr,"UPDATE retur_jual SET $qty_brg='$qty_brg',$no_urutbeli,$no_urutjual WHERE no_urut='$cno_urut'"); 
    } else {
      mysqli_query($hubr,"INSERT INTO retur_jual VALUES('','$no_returjual','$tgl_retur','$no_fakjual','$tgl_jual','$no_urutbeli','$no_urutjual','$qty_brg','$kd_toko','$id_user','0')");
    }

    //UPDATE PADA DUM_JUAL SET KET = RETUR JUAL
    mysqli_query($hubr,"UPDATE dum_jual SET ket='RETUR JUAL' WHERE no_urut='$no_urut'");
  } else {
    ?><script>popnew_warning("Data tidak ditemukan !")</script><?php
  }

?>    
<script>returjualstart('<?=$kd_toko?>','<?=$id_user?>');</script>    
<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>