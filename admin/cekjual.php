<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <?php include "starting.php"; 
 ?>

</head>
<body>
  <form id="f-aja" action="cekjuals.php" method="post" class="form-control">
    <p>
      <label for="bln"> Bulan</label>
      <input type="text" name="bln" >
    </p>
    <p>
      <label for="thn"> Tahun</label>  
      <input type="text" name="thn" >
    </p>
    <p>
      <label for="toko">Toko</label>  
      <input type="text" name="toko">  
    </p>
    <div><button type="submit" style="padding: 10px;">Proses</button></div>
  </form>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#f-aja').submit(function() {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {
              $('#viewcekjual').html(data);
            }
        })
        return false;
      });
    })
  </script> 
  <div id="viewcekjual"></div>
</body>
</html>

<?php

// $sql=mysqli_query($con,"SELECT no_fakjual,tgl_jual,hrg_jual,qty_brg,kd_toko,kd_sat,kd_brg FROM dum_jual WHERE MONTH(tgl_jual)='06' AND YEAR(tgl_jual)='2025' AND kd_toko='IDTOKO-2' AND trf='' GROUP BY (no_fakjual) ORDER BY tgl_jual ASC");
// $jum_y=0;$jum_x=0;  
// while($cr=mysqli_fetch_assoc($sql)){
//     $fak=$cr['no_fakjual'];
//     $tglfak=$cr['tgl_jual'];
//     $q=mysqli_query($con,"SELECT no_fakjual,hrg_jual,qty_brg,discrp,discitem,discvo FROM dum_jual WHERE no_fakjual='$fak' AND panding='0'  ORDER BY tgl_jual ASC");
    
//     while($d=mysqli_fetch_assoc($q)){
//         if ($d['discitem'] > 0){
//           $xditem=$d['hrg_jual']*($d['discitem']/100);
//         }else{
//           $xditem=0;
//         }

//         if ($d['discrp']>0){
//           $xdirp=$d['discrp'];
//         }else{
//           $xdirp=0;   
//         }
        
//         if($d['discvo']>0){
//           $xdivo=$d['hrg_jual']*($d['discvo']/100);
//         }else{
//           $xdivo=0;  
//         }
//         $faks=$d['no_fakjual'];
//         $jum_x=$jum_x+(($d['hrg_jual']-($xditem+$xdirp+$xdivo))*$d['qty_brg']);
      
        
//     }
//     $dd=mysqli_query($con,"SELECT no_fakjual,tgl_jual,tot_jual,tot_disc,kd_toko FROM mas_jual WHERE no_fakjual='$fak' ORDER BY tgl_jual ASC");
        
//         while($ss=mysqli_fetch_assoc($dd)){
//             $jum_y=$jum_y+($ss['tot_jual']-$ss['tot_disc']);
//         }
//         unset($dd,$ss);
       
//     unset($q,$d);
//     //echo '$jum_x='. gantitides($jum_x).'<br>' ;
// }
// unset($sql,$cr);
// echo gantitides($jum_x).' -> '.gantitides($jum_y).'<br>';

// $sql=mysqli_query($con,"SELECT * FROM mas_jual WHERE MONTH(tgl_jual)='05' AND YEAR(tgl_jual)='2025' AND kd_toko='IDTOKO-2'");
// while($ss=mysqli_fetch_assoc($sql)){
//     $jum_y=0;
//     $jum_y=$jum_y+($ss['tot_jual']-$ss['tot_disc']);
//     $fak=$ss['no_fakjual'];

//     $q=mysqli_query($con,"SELECT no_fakjual,hrg_jual,qty_brg,discrp,discitem,discvo FROM dum_jual WHERE no_fakjual='$fak' AND panding='0'  ORDER BY tgl_jual ASC");
//     $jum_x=0;
//     while($d=mysqli_fetch_assoc($q)){
        
//         if ($d['discitem'] > 0){
//           $xditem=$d['hrg_jual']*($d['discitem']/100);
//         }else{
//           $xditem=0;
//         }

//         if ($d['discrp']>0){
//           $xdirp=$d['discrp'];
//         }else{
//           $xdirp=0;   
//         }
        
//         if($d['discvo']>0){
//           $xdivo=$d['hrg_jual']*($d['discvo']/100);
//         }else{
//           $xdivo=0;  
//         }
//         $faks=$d['no_fakjual'];
//         $jum_x=$jum_x+(($d['hrg_jual']-($xditem+$xdirp+$xdivo))*$d['qty_brg']);    
//     }
//     if($jum_y!=$jum_x){
//         echo $fak .'<br>';
//         echo gantitides($jum_x).' -> '.gantitides($jum_y).'<br>';
//     }
// }

?>