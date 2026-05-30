<?php 
   include "config.php";
   session_start();
   $connect=opendtcek();
   $id=$_POST['id'];
   $q=mysqli_query($connect,"SELECT nm_user from pemakai WHERE id_user='$id'");
   if(mysqli_num_rows($q)>0){
    $f=mysqli_query($connect, "DELETE FROM pemakai WHERE id_user='$id'" );    	    
   }else{
    echo 'data tidak ada';
   }
  //  $dc=mysqli_fetch_assoc($q);
  //  $nm_user=$dc['nm_user'];  
  //  $connsql=mysqli_connect('localhost','root','','toko_fafa');
  //  $queries = array(
  //       "DROP USER '$nm_user'@'localhost'",
  //       "DROP USER '$nm_user'@'127.0.0.1'",
  //       "FLUSH PRIVILEGES");
  //   foreach($queries as $query) {
  //     $rs = mysqli_query($connsql,$query);
  //   }   	   
  //   mysqli_close($connsql);
    
?>    
