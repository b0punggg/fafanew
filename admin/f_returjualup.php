<?php
  ob_start();
  include "config.php";
  session_start();

  $con     = opendtcek();
  $kd_toko = $_SESSION['id_toko']; 
  $no_urut = mysqli_real_escape_string($con,$_POST['keyword1']);
  $no_item = mysqli_real_escape_string($con,$_POST['keyword2']);
  $qty     = mysqli_real_escape_string($con,$_POST['keyword3']);
  $netto   = mysqli_real_escape_string($con,$_POST['keyword4']);
  $min     = 1;
  $max     = 1;
  $nm_brg  = "";
  $x1      = mysqli_query($con,"SELECT * FROM dum_jual WHERE no_urut='$no_item'");
  $dx1     = mysqli_fetch_assoc($x1);
  $max     = $dx1['qty_brg'];
  $nm_brg  = $dx1['nm_brg'];
  mysqli_free_result($x1);unset($dx1);
  
  $c1 = mysqli_query($con, "SELECT * FROM retur_jual WHERE no_urutretur='$no_urut' AND proses='0'"); 
  if (mysqli_num_rows($c1)>=1){
  	$d1    = mysqli_fetch_assoc($c1);
  	//$no_returjual = $d1['no_returjual'];
    if ($qty>=$min && $qty<=$max){
      $d=mysqli_query($con,"UPDATE retur_jual SET qty_retur='$qty' WHERE no_urutretur='$no_urut'");
        ?><script>popnew_ok("Jumlah barang retur <?=$qty?>")</script><?php 
      
    } else {
    	?><script>popnew_warning("Kwaaak...!"+"<br>"+'<?=$nm_brg?>'+" >> Jml. Barang MIN : "+('<?=$min?>')+", MAX : "+'<?=round($max,0)?>');
    	  cariretur('<?=$no_returjual?>',1,true);
    	</script><?php
    }
  } else {
    ?><script>popnew_error("Gagal update ! sudah dilakukan Proses data.."+"<?=$no_urut?>");
        cariretur(document.getElementById('no_returjual1').value,1,true);
      </script><?php
  }
?>

<?php
  mysqli_close($con);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>