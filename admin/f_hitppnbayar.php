
<?php 
$keyword=$_POST['keyword'];
$id1=$_POST['field1'];
$id2=$_POST['field2'];
$bagi=$_POST['bagi'];
$disc=$_POST['disc'];
ob_start();
include 'config.php';
session_start();
$keyword=str_replace(',','.',$keyword);
$disc=str_replace(',','.',$disc);

if ($keyword==0 || $keyword==''){
  if ($disc>0){
   //$x=$bagi-($bagi*($keyword/100));  
   $hasil1=$bagi-($bagi*($disc/100));   
  }else {
    $hasil1=$bagi;  
  } 
} else {
  if ($disc>0){
   $x=$bagi-($bagi*($disc/100));    
   $hasil1=$x+($x*($keyword/100));  
  }else{
    $hasil1=$bagi+($bagi*($keyword/100));  
  }  
}
//echo '$hasil1'.$hasil1;

?>
<script>
  document.getElementById('<?=$id1?>').value='<?=gantitides($hasil1)?>';
  document.getElementById('<?=$id2?>').value='<?=gantitides($hasil1)?>';
</script>
<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>