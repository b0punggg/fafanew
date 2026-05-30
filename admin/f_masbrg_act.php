<?php 
  // mysqli_close($connect);
  include 'config.php';
  session_start();
  $no_urutbrg=$_POST['no_urutbrg'];
 
  $kd_brg=ltrim(strtoupper($_POST['kd_brg']));
  $nm_brg=ltrim(strtoupper($_POST['nm_brg']));
  //$jml_brg=$_POST['jml_brg'];
  //$hrg_beli=backnum($_POST['hrg_beli']);
  
  if(!empty($_POST['kd_bar'])){
    $kd_bar=ltrim($_POST['kd_bar']);  
  }else{
    $kd_bar=$kd_brg;
  }

  // $kd_bar=strtoupper($_POST['kd_brg']);  
  $kd_sat1=$_POST['kd_sat1'];
  $jum_sat1=$_POST['jum_sat1'];
  $hrg_jum1=$_POST['hrg_jum1'];
  $nm_sat1=ceknmkem2($_POST['kd_sat1'], $connect); 

  $kd_sat2=$_POST['kd_sat2'];
  $jum_sat2=$_POST['jum_sat2'];
  $hrg_jum2=$_POST['hrg_jum2'];
  $nm_sat2=ceknmkem2($_POST['kd_sat2'], $connect); 

  $kd_sat3=$_POST['kd_sat3'];
  $jum_sat3=$_POST['jum_sat3'];
  $hrg_jum3=$_POST['hrg_jum3'];
  $nm_sat3=ceknmkem2($_POST['kd_sat3'], $connect); 
  
  $lim_jual1=$_POST['lim_jual1'];
  $kd_sat4=$_POST['kd_sat4'];
  $hrg_jum4=$_POST['hrg_jum4'];

  $lim_jual2=$_POST['lim_jual2'];
  $kd_sat5=$_POST['kd_sat5'];
  $hrg_jum5=$_POST['hrg_jum5'];
  // echo '$lim_jual2='.$lim_jual2.'<br>';

  $lim_jual3=$_POST['lim_jual3'];
  $kd_sat6=$_POST['kd_sat6'];
  $hrg_jum6=$_POST['hrg_jum6'];
  // echo '$lim_jual3='.$lim_jual3.'<br>';

  $kd_toko=$_SESSION['id_toko']; 

   // Insert mas_brg
  //echo $kd_brg;
  $connect=opendtcek();
   $ceknota=mysqli_query($connect,"select * from mas_brg where kd_brg='$kd_brg'");
   if(mysqli_num_rows($ceknota)>=1){    
    //echo 'ada';
    $d=mysqli_query($connect,"UPDATE mas_brg set nm_brg='$nm_brg',kd_bar='$kd_bar',kd_kem1='$kd_sat1',jum_kem1='$jum_sat1',hrg_jum1='$hrg_jum1',kd_kem2='$kd_sat2',jum_kem2='$jum_sat2',hrg_jum2='$hrg_jum2',kd_kem3='$kd_sat3',jum_kem3='$jum_sat3',hrg_jum3='$hrg_jum3',nm_kem1='$nm_sat1',nm_kem2='$nm_sat2',nm_kem3='$nm_sat3' WHERE kd_brg='$kd_brg'");
    // update pada disctetap
      $cekawal=mysqli_query($connect,"SELECT * from disctetap WHERE kd_brg='$kd_brg'");
      if(mysqli_num_rows($cekawal)>=1){
        $dele=opendtcek();
        $d=mysqli_query($dele,"DELETE FROM disctetap WHERE kd_brg='$kd_brg'");
        mysqli_close($dele);
      }  
      //echo 'delete disctetap'; 
      if ($kd_sat4 >1){
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat4','$hrg_jum4','$lim_jual1','$kd_toko')");
      }
      if ($kd_sat5 >1){
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat5','$hrg_jum5','$lim_jual2','$kd_toko')");
      }
      if ($kd_sat6 >1){
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat6','$hrg_jum6','$lim_jual3','$kd_toko')");
      }
      unset($cekawal);
   } else {
      //echo 'no';
       $d=mysqli_query($connect,"insert into mas_brg values('','$kd_brg','$nm_brg','0','$kd_bar','$kd_sat1','$jum_sat1','$hrg_jum1','$kd_sat2','$jum_sat2','$hrg_jum2','$kd_sat3','$jum_sat3','$hrg_jum3','0','0','$kd_toko','$nm_sat1','$nm_sat2','$nm_sat3','','','','')");

      if ($kd_sat4 >1){
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat4','$hrg_jum4','$lim_jual1','$kd_toko')");
      }
      if ($kd_sat5 >1){
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat5','$hrg_jum5','$lim_jual2','$kd_toko')");
      }
      if ($kd_sat6 >1){
        $d=mysqli_query($connect,"INSERT INTO disctetap VALUES('','$kd_brg','$kd_sat6','$hrg_jum6','$lim_jual3','$kd_toko')");
      }
   }
   unset($ceknota);

   if($d){header("location:f_masbrg.php?pesan=simpan");}
   else{header("location:f_masbrg.php?pesan=gagal");}    
?>
