<?php 
  ob_start();

  include 'config.php';
  session_start();
  $con=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
  $kd_satinput = isset($_POST['keyword1']) ? $_POST['keyword1'] : ''; // Ambil data keyword yang dikirim dengan AJAX	
  $kd_brginput = isset($_POST['keyword2']) ? $_POST['keyword2'] : '';
  $no_urutjual = isset($_POST['keyword3']) ? $_POST['keyword3'] : '';
  
  // Pastikan tidak ada output yang tidak seharusnya
  if(!empty($kd_satinput) && !empty($kd_brginput)) 
  {
	  //---------------------------
	  $jum_kemasan=konjumbrg2($kd_satinput,$kd_brginput,$con);

	  // baca stok barang saat ini
	  $stok=0;$brg_msk=0;$brg_klr=0;
	  $stok=caristok($kd_brginput,$con);   
	  $min=1;
	  // Cegah division by zero
	  if ($jum_kemasan > 0) {
	    $max=$stok/$jum_kemasan;
	  } else {
	    $max = $stok; // Jika jum_kemasan 0, gunakan stok langsung
	  } 
	   ?>
	   <script>
	   	if(document.getElementById('no_urutjual').value == ""){
       //    document.getElementById('qty_brg').min='<?=$min?>';
	   	  // document.getElementById('qty_brg').max='<?=$max?>';
	   	} else {
	   	  <?php	
	   	    $xc=mysqli_query($con,"SELECT * FROM dum_jual WHERE no_urut='$no_urutjual'");
	   	    if (mysqli_num_rows($xc)>=1){
	   	      $dx=mysqli_fetch_assoc($xc);
	   	      $kd_brg=$dx['kd_brg'];
	   	      $kd_sat=$dx['kd_sat'];
	   	      $qty_brg=$dx['qty_brg'];
	   	      $jml_awal=$qty_brg*konjumbrg2($kd_sat,$kd_brg,$con);
	   	      $min=1;
	   	      // Cegah division by zero
	   	      if ($jum_kemasan > 0) {
	   	        $max=($stok+$jml_awal)/$jum_kemasan;
	   	      } else {
	   	        $max = $stok + $jml_awal; // Jika jum_kemasan 0, gunakan stok langsung
	   	      }	
	   	    }	
	   	  ?>
	   	  // document.getElementById('qty_brg').min='<?=$min?>';
	   	  // document.getElementById('qty_brg').max='<?=$max?>';
	   	}
	   </script>
	<?php 
   } else {
     // Jika parameter kosong, kembalikan string kosong
     echo '';
   }
  mysqli_close($con);    
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>