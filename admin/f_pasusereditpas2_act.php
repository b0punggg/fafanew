<?php 
	include 'config.php';
  session_start();
  $connect=opendtcek();
	$id_userpas=$_POST['id_userpas'];
  $paslama=$_POST['paslama'];
  $pasbaru=$_POST['pasbaru'];
  $pasbaru1=$_POST['pasbaru1'];

  $cek=mysqli_query($connect,"select id_user,pass from pemakai where id_user='$id_userpas'");
  if(mysqli_num_rows($cek)==1)
  {
    $user = mysqli_fetch_assoc($cek);
    if( password_verify($paslama, $user['pass']) ) {
      $b = password_hash($pasbaru, PASSWORD_DEFAULT); 
      if ($pasbaru==$pasbaru1) 
      {
         if (!empty($pasbaru) AND !empty($pasbaru1)) {
           if($id_userpas<>'1'){ 
            mysqli_query($connect,"update pemakai set pass='$b' where id_user='$id_userpas'");
            
            // $queries = array(
            //       "DROP USER '$nm_user'@'localhost'",
            //       "CREATE USER '$nm_user'@'localhost' IDENTIFIED BY '$b'",
            //       "GRANT USAGE ON *.* TO '$nm_user'@'localhost' IDENTIFIED BY '$b' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0",
            //         "GRANT ALL PRIVILEGES ON toko_fafa.* TO '$nm_user'@'localhost'",
            //       "FLUSH PRIVILEGES");
            //   foreach($queries as $query) {
            //     $rs = mysqli_query($connsql,$query);
            //   }
            //   $queries = array(
            //       "DROP USER '$nm_user'@'localhost'",
            //       "CREATE USER '$nm_user'@'127.0.0.1' IDENTIFIED BY '$b'",
            //       "GRANT USAGE ON *.* TO '$nm_user'@'localhost' IDENTIFIED BY '$b' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0",
            //         "GRANT ALL PRIVILEGES ON toko_fafa.* TO '$nm_user'@'127.0.0.1'",
            //       "FLUSH PRIVILEGES");
            //   foreach($queries as $query) {
            //     $rs = mysqli_query($connsql,$query);
            //   }
            //   mysqli_close($connsql);
             header("location:f_pasuser2.php?pesan=ok");        
           }else{
             header("location:f_pasuser2.php?pesan=gagal");      
           }
         } 
      }else{
        header("location:f_pasuser2.php?pesan=gagal2");  
      }  
    } else{
      header("location:f_pasuser2.php?pesan=gagal1");
    } 
  }  
  
  