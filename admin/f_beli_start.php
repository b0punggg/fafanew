<?php 
 $keyword=$_POST['keyword'];
 ob_start();

?>
<?php 
  include 'config.php';
  session_start();
  $connect=opendtcek();  
  
  $kd_toko=$_SESSION['id_toko'];$ada=0;
  if (!empty($keyword)){
    $x=explode(';',$keyword);
    $ada=1;$no_fakcari=$x[0];
    $tgl_fakcari=$x[1];	
  }else{
  	$no_fakcari='';
    $tgl_fakcari='0000-00-00';	
  }
  
  $cek2=mysqli_query($connect,"SELECT beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,supplier.nm_sup,beli_bay.pay FROM beli_brg
    LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
    LEFT JOIN beli_bay ON beli_brg.no_fak=beli_bay.no_fak AND beli_brg.kd_toko='$kd_toko'
    WHERE beli_brg.kd_toko='$kd_toko' ORDER BY beli_brg.no_urut DESC LIMIT 1"); 
   $dat1=mysqli_fetch_array($cek2);
   $ada=0;
   if(mysqli_num_rows($cek2)>=1){
     if($dat1['pay']==1){
      $ada=0;
     }else{
      $ada=1;
     } 
   }else{
      $ada=0;
   }
   // echo '$ada='.$ada.'<br>'; 

   if($ada==1){ 
    ?><script>
      document.getElementById('no_fak').value='<?=mysqli_real_escape_string($connect,$dat1['no_fak'])?>';
      document.getElementById('tgl_fak').value='<?=mysqli_real_escape_string($connect,$dat1['tgl_fak'])?>';
      document.getElementById('kd_sup').value='<?=mysqli_real_escape_string($connect,$dat1['kd_sup'])?>';
      document.getElementById('nm_sup').value='<?=mysqli_real_escape_string($connect,$dat1['nm_sup'])?>';
      document.getElementById('kd_bar').focus();
    </script><?php
   }else{
    ?><script>
      if (document.getElementById('keyedit').value=="") {
        document.getElementById('tgl_fak').value='<?=$_SESSION['tgl_set']?>';
        document.getElementById('no_fak').value="";
        document.getElementById('kd_sup').value="";
        document.getElementById('nm_sup').value="";
        document.getElementById('no_fak').focus();  
      } else {
        let tgl=document.getElementById('keyedit').value;  
        document.getElementById('tgl_fak').value=tgl.substr(0,tgl.search(";"));
        document.getElementById('no_fak').value=tgl.substr(tgl.search(";")+1);
        document.getElementById('kd_bar').focus();   
      }
    </script><?php
   }
   unset($cek2,$dat1);  
   mysqli_close($connect);
?>
<script>carinota(1,true);</script>
<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>