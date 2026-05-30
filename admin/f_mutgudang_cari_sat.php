<?php
$kd_brg = $_POST['keyword2']; // Ambil data keyword yang dikirim dengan AJAX	
$kd_toko= $_POST['keyword1'];
$no_urut= $_POST['keyword3'];
ob_start();
// echo '$no_urut='.$no_urut;
include 'config.php';
session_start();
$connect=opendtcek();
$kd_brg=mysqli_escape_string($connect,$kd_brg);
$kd_toko=mysqli_escape_string($connect,$kd_toko);
$no_urut=mysqli_escape_string($connect,$no_urut);
// echo '$kd_brg='. $_POST['keyword1'].'<br>';
// echo '$kd_toko='.$_POST['keyword2'].'<br>';
$cek=mysqli_query($connect,"SELECT * from mas_brg where kd_brg='$kd_brg'");
$data=mysqli_fetch_assoc($cek);
?>
<select name="kd_sat" id="kd_sat" class="form-control" onchange="document.getElementById('sat').value=this.value;maxjumbrg('<?=$kd_brg?>','<?=$kd_toko?>',this.value,'<?=$no_urut?>')">
	<?php if ($data['kd_kem1']<>1){ ?>
	<option value="<?=$data['kd_kem1']?>"><?=$data['nm_kem1']?></option>
    <?php } ?>
    <?php if ($data['kd_kem2']<>1){ ?>
    <option value="<?=$data['kd_kem2']?>"><?=$data['nm_kem2']?></option>
    <?php } ?>
    <?php if ($data['kd_kem3']<>1){ ?>
    <option value="<?=$data['kd_kem3']?>"><?=$data['nm_kem3']?></option>
    <?php } ?>
</select>
<input type="hidden" id="sat" name="sat">
<input type="hidden" id="kd_brgmut" name="kd_brgmut" value='<?=$kd_brg?>'>
<input type="hidden" id="kd_tokomut" name="kd_tokomut" value='<?=$kd_toko?>'>

<script>document.getElementById('sat').value=document.getElementById('kd_sat').value;maxjumbrg('<?=$kd_brg?>','<?=$kd_toko?>',document.getElementById('kd_sat').value,'<?=$no_urut?>')</script>
<?php
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>