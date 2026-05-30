<?php
  $tgl = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
  ob_start();
  include 'config.php';
  session_start();
  $_SESSION['tgl_set']=$tgl;
?>
<script>popnew_warning('Tanggal sistem menjadi '+'&nbsp;'+' <i class="fa fa-calendar-check-o"></i> '+' <?=gantitgl($tgl)?>');</script>
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>
