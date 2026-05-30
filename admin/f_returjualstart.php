<?php
	ob_start();
?>

<?php 
    include 'config.php';
    session_start();
    $oto=$_SESSION['kodepemakai'];
    $hub=opendtcek();
    
    $kd_toko=mysqli_real_escape_string($hub,$_POST['keyword1']);
    $id_user=mysqli_real_escape_string($hub,$_POST['keyword2']);
    $no1=0;$x1='';$x2='';$no2=0;$max_nofak=0;
    $i=explode("-",$kd_toko);
    $tk=$i[1];

    $cari=mysqli_query($hub,"SELECT SUBSTR(no_returjual, LOCATE('-',no_returjual)+1,(LENGTH(no_returjual)+1)-LOCATE('-',no_returjual)+1) AS field FROM retur_jual ORDER BY SUBSTR(no_returjual, LOCATE('-',no_returjual)+1,(LENGTH(no_returjual)+1)-LOCATE('-',no_returjual)+1)*1 DESC LIMIT 1");
    $max_noretur=0;
    if (mysqli_num_rows($cari)>0){
      $dcari=mysqli_fetch_assoc($cari);
      $max_noretur=$dcari['field'];
    }
    mysqli_free_result($cari);unset($dcari);
    //--------------

    $cek1=mysqli_query($hub,"SELECT * FROM retur_jual 
        WHERE retur_jual.proses='0' AND retur_jual.kd_toko='$kd_toko' AND retur_jual.id_user='$id_user'
        ORDER BY retur_jual.no_urutretur DESC limit 1");
    $data1=mysqli_fetch_array($cek1); 
    if(mysqli_num_rows($cek1)>=1){
        $x2          = explode('-',mysqli_escape_string($hub,$data1['no_returjual']));   
        $tgl_retur   = $data1['tgl_retur'];
        //$kd_pel      = mysqli_escape_string($hub,$data1['kd_pel']);
        $sx          = strtotime($tgl_retur);
        $no_returjual= mysqli_escape_string($hub,$data1['no_returjual']);
    } else {
        $no        = $max_noretur+1;    
        $tgl_retur  = $_SESSION['tgl_set'];
        //$kd_pel    = "IDPEL-0";
        // if (strlen($data1['no_returjual'])>=35){
        //   $no = 1;  
        // }
        $sx  = strtotime($tgl_retur);
        $no_returjual=trim('JR'.$tk.'.'.$id_user.'.'.date('d',$sx).date('m',$sx).date('y',$sx).'-'.$no); 
        if (strlen($no_returjual)>=35){
          $no = 1;
          $no_returjual=trim('JR'.$tk.'.'.$id_user.'.'.date('d',$sx).date('m',$sx).date('y',$sx).'-'.$no);   
        }
     }   
    mysqli_free_result($cek1);unset($data1);
    mysqli_close($hub);

    // --------------------------------

?>

<script>
  document.getElementById('no_returjual1').value='<?=$no_returjual?>'; 
  document.getElementById('tgl_retur1').value='<?=$tgl_retur?>'; 
  cariretur(document.getElementById('no_returjual1').value,1,true);
</script>

<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>