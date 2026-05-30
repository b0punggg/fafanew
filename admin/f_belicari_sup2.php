<?php
  ob_start();
    
?>
  <script>
    document.getElementById('byr_nm_sup').value="<?=$_POST['keyword2']?>";
    document.getElementById('byr_kd_sup').value="<?=$_POST['keyword1']?>";
    
  </script>
 
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>