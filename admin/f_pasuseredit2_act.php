<?php 
	include 'config.php';
  session_start();
  $connect=opendtcek();
  $oto=$_SESSION['kodepemakai'];
  $id_user=$_SESSION['id_user'];
  $id_user1=$_POST['id_user'];
  $kd_toko=$_POST['kd_tokoi'];
  $nm_user1=strtoupper($_POST['nm_user']);
  $alamat=strtoupper($_POST['alamat']);
  $no_hp=$_POST['no_hp'];
  $foto=$_FILES['foto']['name'];
  $otoritas=$_POST['otoritas'];
  $f=false;$d=false;
  //echo $id_user1;

  $cek1=mysqli_query($connect,"select id_user,foto from pemakai where id_user='$id_user1'");
   // Insert data 
   if(mysqli_num_rows($cek1)>=1)
   {
       if($oto<>'1'){
         $d=mysqli_query($connect,"update pemakai set alamat='$alamat',no_hp='$no_hp',otoritas='$otoritas',kd_toko='$kd_toko' where id_user='$id_user1'");              
       } else {
          $d=mysqli_query($connect,"update pemakai set alamat='$alamat',no_hp='$no_hp',kd_toko='$kd_toko' where id_user='$id_user1'");              
       }           
       if(!empty($foto))
       {
          $us=mysqli_fetch_array($cek1);
          if(file_exists("img/".$us['foto']))
          {
            unlink("img/".$us['foto']);
            move_uploaded_file($_FILES['foto']['tmp_name'], "img/".$_FILES['foto']['name']);
            mysqli_query($connect,"update pemakai set foto='$foto' where id_user='$id_user1'  "); 
          }else
          {
            move_uploaded_file($_FILES['foto']['tmp_name'], "img/".$_FILES['foto']['name']);
            mysqli_query($connect,"update pemakai set foto='$foto' where id_user='$id_user1' ");
          }  
       } 
   }else{
     
     if(!empty($foto))
     {
        $us=mysqli_fetch_array($cek1);
        if(file_exists("img/".$us['foto']))
        {
          unlink("img/".$us['foto']);
          move_uploaded_file($_FILES['foto']['tmp_name'], "img/".$_FILES['foto']['name']);
          // mysqli_query($connect,"update pemakai set foto='$foto' where id_user='$id_user1'  "); 
        }else
        {
          move_uploaded_file($_FILES['foto']['tmp_name'], "img/".$_FILES['foto']['name']);
          // mysqli_query($connect,"update pemakai set foto='$foto' where id_user='$id_user1' ");
        }  
     }
     $b = password_hash($nm_user1, PASSWORD_DEFAULT); 
     $f=mysqli_query($connect,"insert into pemakai values('','$nm_user1','$otoritas','$b','$no_hp','$alamat','$foto','$kd_toko')"); 
    //  
    //  $queries = array(
    //       "CREATE USER '$nm_user1'@'localhost' IDENTIFIED BY '$b'",
    //       "GRANT USAGE ON *.* TO '$nm_user1'@'localhost' IDENTIFIED BY '$b' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0",
    //         "GRANT ALL PRIVILEGES ON toko_fafa.* TO '$nm_user1'@'localhost'",
    //       "FLUSH PRIVILEGES");
    //   foreach($queries as $query) {
    //     $rs = mysqli_query($connsql,$query);
    //   }
    //   mysqli_close($connsql);

    //   $connsql=mysqli_connect('localhost','root','','toko_fafa');
    //   $queries = array(
    //       "CREATE USER '$nm_user1'@'127.0.0.1' IDENTIFIED BY '$b'",
    //       "GRANT USAGE ON *.* TO '$nm_user1'@'localhost' IDENTIFIED BY '$b' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0",
    //         "GRANT ALL PRIVILEGES ON toko_fafa.* TO '$nm_user1'@'127.0.0.1'",
    //       "FLUSH PRIVILEGES");
    //   foreach($queries as $query) {
    //     $rs = mysqli_query($connsql,$query);
    //   }
    //   mysqli_close($connsql); 
        
   }
   
   if($d){
     if($id_user==$id_user1){header("location:../index.php");
     }else{header("location:f_pasuser2.php?pesan=ok"); }
   }
   else if($f){
     header("location:f_pasuser2.php?pesan=ok");
   }
   else {
     header("location:f_pasuser2.php?pesan=gagal");
   }    
?>
