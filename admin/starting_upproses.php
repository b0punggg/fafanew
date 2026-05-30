<?php
  ob_start();
  session_start();
  include 'config.php';

  $conup    = opendtcek();
  $qc       = mysqli_query($conup,"SELECT * FROM seting ");

  while($d=mysqli_fetch_assoc($qc)) 
  {
    if ($d['nm_per']=='CETAK'){
      $cet_nota = $d['kode'];  
    }
    if ($d['nm_per']=='COPY'){
      $copy     = $d['kode'];  
    }
    if ($d['nm_per']=='POTONG'){
      $potong   = $d['kode'];  
    }
    if ($d['nm_per']=='PROSES'){
      $p_proses = $d['kode'];  
    }
    if ($d['nm_per']=='NOFAKTUR'){
      $nofaktur = $d['kode'];  
    }
  }

?>

  <script>
    document.getElementById("pilih_cetak").selectedIndex = "<?=$cet_nota?>";
    document.getElementById("cet_copy").value = "<?=$copy?>";
    document.getElementById("pilih_potong").selectedIndex = "<?=$potong?>";
    document.getElementById("p_proses").selectedIndex = "<?=$p_proses?>";
    //document.getElementById("nofakturs").value = "<?=$nofaktur?>";
  </script>
  
<?php
  mysqli_close($conup);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>