
<?php 
$keyword=$_POST['keyword']; //discon %
$bagi=$_POST['bagi'];//total tagihan gtot
$ppn=$_POST['ppn'];//ppn
$id1=$_POST['field1'];// 
$id2=$_POST['field2'];//
ob_start();
include 'config.php';
session_start();
$keyword=str_replace(',','.',$keyword);
$spn=str_replace(',','.',$ppn);

if ($keyword==0 || $keyword==''){
  if ($ppn>0){
   //$x=$bagi-($bagi*($keyword/100));  
   $hasil1=$bagi+($bagi*($ppn/100));   
  }else {
    $hasil1=$bagi;  
  } 
} else {
  if ($ppn>0){
   $x=$bagi-($bagi*($keyword/100));  
   $hasil1=$x+($x*($ppn/100));   
  }else{
    $hasil1=$bagi-($bagi*($keyword/100));  
  }  
}
?>
<script>
  document.getElementById('byr_tag_fak').value='<?=gantitides($hasil1) ?>'; 
  document.getElementById('<?=$id2?>').value='<?=gantitides($hasil1)?>';
</script>

<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>