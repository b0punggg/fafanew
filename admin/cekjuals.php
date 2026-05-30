<?php

include "config.php";
$con=opendtcek();   
    $bln=$_POST['bln'];$thn=$_POST['thn'];$toko=$_POST['toko'];
    //echo $bln.' '.$thn.' '.$toko;
    $sql=mysqli_query($con,"SELECT * FROM mas_jual WHERE MONTH(tgl_jual)='$bln' AND YEAR(tgl_jual)='$thn' AND kd_toko='$toko'");
    $jum_1=$jum_2=0;
    while($ss=mysqli_fetch_assoc($sql)){
      $jum_y=0;
      $jum_y=$jum_y+($ss['tot_jual']-$ss['tot_disc']);
      $jum_1=$jum_1+($ss['tot_jual']-$ss['tot_disc']);
      $fak=$ss['no_fakjual'];
      
      $q=mysqli_query($con,"SELECT no_fakjual,hrg_jual,qty_brg,discrp,discitem,discvo FROM dum_jual WHERE no_fakjual='$fak' AND panding='0'  ORDER BY tgl_jual ASC");
      $jum_x=0;
      while($d=mysqli_fetch_assoc($q)){
        if ($d['discitem'] > 0){
          $xditem=$d['hrg_jual']*($d['discitem']/100);
        }else{
          $xditem=0;
        }

        if ($d['discrp']>0){
          $xdirp=$d['discrp'];
        }else{
          $xdirp=0;   
        }
          
        if($d['discvo']>0){
          $xdivo=$d['hrg_jual']*($d['discvo']/100);
        }else{
          $xdivo=0;  
        }
      
        $jum_x=$jum_x+(($d['hrg_jual']-($xditem+$xdirp+$xdivo))*$d['qty_brg']);    
        $jum_2=$jum_2+(($d['hrg_jual']-($xditem+$xdirp+$xdivo))*$d['qty_brg']);    
      }
      if(gantitides($jum_y)!=gantitides($jum_x)){
        echo $fak .'<br>';
        echo 'dum_jual='.gantitides($jum_x).' <<>> mas_jual='.gantitides($jum_y).'<br><br>';
      } 
    }
    unset($ss,$sql);

    echo 'SESI KE-1'.'<br>';
    if(gantitides($jum_1)==gantitides($jum_2)){
        echo 'mas_jual = '.gantitides($jum_1).'<br>';
        echo 'dum_jual = '.gantitides($jum_2).'<br>';
        echo "SAMA".'<br><br>';
    }else{
      echo 'mas_jual = '.gantitides($jum_1).'<br>';
      echo 'dum_jual = '.gantitides($jum_2).'<br>';
      echo "TIDAK SAMA".'<br><br>';  
    }    

$sql=mysqli_query($con,"SELECT no_fakjual FROM dum_jual WHERE MONTH(tgl_jual)='$bln' AND YEAR(tgl_jual)='$thn' AND kd_toko='$toko' GROUP BY no_fakjual ORDER BY tgl_jual ASC");
$jum_1=$jum_2=0;
while($ss=mysqli_fetch_assoc($sql)){
  $faks=$ss['no_fakjual'];
  $jum_y=0;$jum_x=0;
  //mas_jual
  $sql1=mysqli_query($con,"SELECT * FROM mas_jual WHERE no_fakjual='$faks'");
  while($ss1=mysqli_fetch_assoc($sql1)){
    $jum_y=$jum_y+($ss1['tot_jual']-$ss1['tot_disc']);
    $jum_1=$jum_1+($ss1['tot_jual']-$ss1['tot_disc']);
  }
  mysqli_free_result($sql1);unset($ss1);

  //dum_jual
  $sql2=mysqli_query($con,"SELECT * FROM dum_jual WHERE no_fakjual='$faks' AND panding='0'");
  while($ss2=mysqli_fetch_assoc($sql2)){
    if ($ss2['discitem'] > 0){
      $xditem=$ss2['hrg_jual']*($ss2['discitem']/100);
    }else{
      $xditem=0;
    }

    if ($ss2['discrp']>0){
      $xdirp=$ss2['discrp'];
    }else{
      $xdirp=0;   
    }
      
    if($ss2['discvo']>0){
      $xdivo=$ss2['hrg_jual']*($ss2['discvo']/100);
    }else{
      $xdivo=0;  
    }
    
    $jum_x=$jum_x+(($ss2['hrg_jual']-($xditem+$xdirp+$xdivo))*$ss2['qty_brg']);    
    $jum_2=$jum_2+(($ss2['hrg_jual']-($xditem+$xdirp+$xdivo))*$ss2['qty_brg']);    
  }
  if(gantitides($jum_y)!=gantitides($jum_x)){
    echo $faks .'<br>';
    echo 'dum_jual='.gantitides($jum_x).' <<>> mas_jual='.gantitides($jum_y).'<br><br>';
  } 
}    
echo 'SESI KE-2'.'<br>';
    if(gantitides($jum_1)==gantitides($jum_2)){
        echo 'mas_jual = '.gantitides($jum_1).'<br>';
        echo 'dum_jual = '.gantitides($jum_2).'<br>';
        echo "SAMA".'<br><br>';
    }else{
      echo 'mas_jual = '.gantitides($jum_1).'<br>';
      echo 'dum_jual = '.gantitides($jum_2).'<br>';
      echo "TIDAK SAMA".'<br><br>';  
    }  
?>  