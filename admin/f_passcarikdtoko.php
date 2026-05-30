<?php
ob_start();
// echo '$no_urut='.$no_urut;
include 'config.php';
session_start();
$connect=opendtcek();
$id_user=mysqli_escape_string($connect,$_POST['keyword']);

$cek=mysqli_query($connect,"SELECT * from pemakai where id_user='$id_user'");
$data=mysqli_fetch_assoc($cek);
?>
<script>
    if (<?=$data['otoritas']?>=='2'){
      document.getElementById('pilotor').value="<?=$data['otoritas']?>";
    }
    document.getElementById('id_user').value='<?=mysqli_escape_string($connect,$data['id_user']) ?>';  
    document.getElementById('kd_tokoi').value="<?=mysqli_escape_string($connect,$data['kd_toko']);?>";    
    document.getElementById('nm_user').value="<?=$data['nm_user']?>";
    document.getElementById('alamat').value="<?=$data['alamat']?>";
    document.getElementById('no_hp').value="<?=$data['no_hp']?>";
</script>

<?php
  unset($cek,$data);
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>