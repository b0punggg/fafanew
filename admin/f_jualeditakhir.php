<?php 
  ob_start();
  session_start();
  include "config.php";
  $connect=opendtcek(); 
  $kode=$_SESSION['kode'];
  $kd_toko=$_SESSION['id_toko'];
  $tgl_fakjual=mysqli_real_escape_string($connect,$_POST['keyword1']);
  $no_fakjual=mysqli_real_escape_string($connect,$_POST['keyword2']);
  $key=mysqli_real_escape_string($connect,$_POST['keyword3']);
  $s=mysqli_query($connect,"SELECT * FROM dum_jual 
  	WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_fakjual' AND  dum_jual.kd_toko='$kd_toko' ORDER BY dum_jual.no_urut DESC LIMIT 1");
  if (mysqli_num_rows($s)>=1){
  	$d=mysqli_fetch_assoc($s);
  	$nm_sat=ceknmkem2(mysqli_escape_string($connect,$d['kd_sat']),$connect);
    if (mysqli_escape_string($connect,$d['ket'])=="PEMBELIAN BARANG"){
      $ket="-";
    } else {   
      $ax=""; 
      $ax=substr($d['ket'],0,6);
      if ($ax=="MUTASI") {
        $ket="-";
      }else{
        $ket=mysqli_escape_string($connect,$d['ket']);  
      }
    }
  	?>
  	<script>
  		  document.getElementById('no_urutjual').value='<?=mysqli_escape_string($connect,$d['no_urut']) ?>';
        document.getElementById('cr_bay').value='<?=mysqli_escape_string($connect,$d['kd_bayar']) ?>';
        document.getElementById('kd_pel').value='<?=mysqli_escape_string($connect,$d['kd_pel']) ?>';
        document.getElementById('kd_brg').value='<?=mysqli_escape_string($connect,$d['kd_brg']) ?>';
        document.getElementById('kd_sat').value='<?=mysqli_escape_string($connect,$d['kd_sat']) ?>';
        document.getElementById('nm_sat').value='<?=$nm_sat ?>';
        document.getElementById('qty_brg').value='<?=mysqli_escape_string($connect,$d['qty_brg'])?>';
        //document.getElementById('discitem').value='<?=gantitides(round(mysqli_escape_string($connect,$d['discitem']),0))?>';
        document.getElementById('discitem').value='<?=gantitides(round(mysqli_real_escape_string($connect,$d['discrp']),0))?>';
        document.getElementById('tgl_jt').value='<?=mysqli_escape_string($connect,$d['tgl_jt'])?>';
        document.getElementById('ketjual').value='<?=$ket?>';
        
        // document.getElementById('kd_brg').setAttribute('disabled',true);
        // document.getElementById('kd_bar').setAttribute('disabled',true);
        // document.getElementById('cr_bay').setAttribute('disabled',true);
        // document.getElementById('kd_pel').setAttribute('disabled',true);
        // document.getElementById('no_fakjual').setAttribute('disabled',true);
        // document.getElementById('tgl_jt').setAttribute('disabled',true);
        // document.getElementById('tgl_fakjual').setAttribute('disabled',true);
        <?php if ($key==1){ ?>
          document.getElementById('qty_brg').focus();document.getElementById('qty_brg').select();
        <?php } else { ?>
          document.getElementById('nm_sat').focus();carisatbrg(1,true);
        <?php } ?>
        // cekjmlstok(document.getElementById('kd_sat').value,document.getElementById('kd_brg').value);

  	</script>
  	<?php
  }
  mysqli_free_result($s);unset($d);
  mysqli_close($connect);
 ?>	

<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>