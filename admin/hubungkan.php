<?php
function hubung($user){
  $host     = "localhost";
  $username = "root";
  $password = "";
  $database = "fafa";

  $con=mysqli_connect($host,$username,$password,$database);
  $sql=mysqli_query($con,"SELECT * FROM pemakai WHERE nm_user='$user' ORDER BY nm_user ASC");
   
    if (mysqli_num_rows($sql)>=1){
      // Untuk localhost, gunakan root tanpa password
      $username="root";
      $password="";
     }
   }else{
      $username="root";
      $password=""; 
   }
    
  unset($data,$sql); 
  mysqli_close($con);
  
  $host = "localhost"; 
  $database = "fafa";
  return mysqli_connect($host,$username,$password,$database);
}
function gantitgl($tgl1)
{
  $pecah=explode('-', $tgl1);
  $x=$pecah[2].'-'.$pecah[1].'-'.$pecah[0];
  return $x;
}  
function gantiti($b){
  $_minus = false;
  $c='';
  if ($b<0) {$_minus = true; $b=$b*-1;}
    $panjang =strlen($b);
    $j = 0;
    for ($i = $panjang; $i > 0; $i--){
      $j = $j + 1;
      if ((($j % 3) == 1) && ($j != 1)){
        $c = substr($b,$i-1,1) . "." . $c;
      } else {
        $c = substr($b,$i-1,1) . $c;
      }
    }
  if ($_minus) {$c = "-".$c;} 
    return $c;
}  
function gantitides($b){
  $_minus = false;
  $c='';$x=0;$cek=0;
  $b=round($b,2); 
  
  if ($b>0){
    $cek=strpos($b,'.');  
    if ($cek>0){
      $x=explode('.',$b);
      if (strlen($x[1])==1){
        $b=$x[0].'.'.$x[1].'0';
      }  
    }else {
      $b=$b.'.00';  
    }
  }
  $des=substr($b,strlen($b)-3,1);
  if($des<>'.'){    
    $x=explode('.',$b);  
    $b=$x[0];
    $des='00';
  } else {
    $x=explode('.',$b);  
    $b=$x[0];
    $des=$x[1];
  }  
    if ($b<0) {$_minus = true; $b=$b*-1;}
      $panjang =strlen($b);
      $j = 0;
      for ($i = $panjang; $i > 0; $i--){
        $j = $j + 1;
        if ((($j % 3) == 1) && ($j != 1)){
          $c = substr($b,$i-1,1) . "." . $c;
        } else {
          $c = substr($b,$i-1,1) . $c;
        }
      }
      
    if ($_minus) {$c = "-".$c;} 
    return $c . ",".$des;
}
function spasinum($str,$pjg)
{
  $spa="";   
  if (strlen($str)>=$pjg){
    $strjadi=substr($str,0,$pjg);
    return $strjadi;
  } else {
    $a=$pjg-strlen($str);
    for ($i=0; $i < $a ; $i++) 
    { 
     $spa=$spa.' ';
    }
    $strjadi=$spa.$str;
    return $strjadi;
  }
}

function spasistr($str,$pjg)
{
  $spa="";   
  if (strlen($str)>=$pjg){
    $strjadi=substr($str,0,$pjg);
    return $strjadi;
  } else {
    $a=$pjg-strlen($str);
    for ($i=0; $i < $a ; $i++) 
    { 
     $spa=$spa.' ';
    }
    $strjadi=$spa.$str;
    return $strjadi;
  }
}

function spasi($pjg){
  $spa='';
  for ($i=0; $i < $pjg ; $i++) 
  { 
   $spa=$spa.'&nbsp;';
  }
  return $spa;
}
?>