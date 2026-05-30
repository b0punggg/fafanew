<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
    session_start();
    
?>
<script>
   
   if("<?=$keyword?>" != "<?=$_SESSION['nm_user']?>"){
     alert("Terdapat user aktif lainnya, proses tidak dapat dilanjutkan Silahkan tutup TAB terbuka ini");
     window.open('../index.php', '_self', ''); window.close();
   }
</script>

<?php
   $html = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>        