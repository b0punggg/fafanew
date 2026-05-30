<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
    session_start();
    // echo 'keyword='.$keyword.'<br>';
    // echo 'session='.$_SESSION['id_toko'].'<br>';
?>
<script>
   
   if("<?=$keyword?>" != "<?=$_SESSION['nm_user']?>"){
     alert("Terdapat user aktif lainnya, proses tidak dapat dilanjutkan Silahkan tutup TAB terbuka ini");
     let origin = window.location.origin;
     window.open(origin, '_self', ''); window.close();
   }
</script>

<?php
   $html = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>        