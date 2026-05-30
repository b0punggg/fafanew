<?php 
session_start();
include 'admin/config.php';
$connect=opendtcek();
$nm_user=mysqli_escape_string($connect,$_POST['nm_user']);
$pass=mysqli_escape_string($connect,$_POST['pass']);
date_default_timezone_set('Asia/Jakarta');
$query = mysqli_query($connect,"SELECT * FROM pemakai WHERE nm_user='$nm_user'");
$user = mysqli_fetch_assoc($query);

if( password_verify($pass, $user['pass']) ) {
    $_SESSION['nm_user']=$user['nm_user'];
    $_SESSION['id_user']=$user['id_user'];
	$_SESSION['masuk']="ok";
	$_SESSION['kodepemakai']=$user['otoritas'];
	$_SESSION['foto']=$user['foto'];
	$_SESSION['id_toko']=$user['kd_toko'];
    $kd_toko=$user['kd_toko'];
    $_SESSION['tgl_set']=date('Y-m-d');
	$cek=mysqli_query($connect,"select nm_toko from toko where kd_toko='$kd_toko'");
	if(mysqli_num_rows($cek)>=1){
	  $data=mysqli_fetch_assoc($cek);
	  $_SESSION['nm_toko']=$data['nm_toko'];	
	}else{
	  $_SESSION['nm_toko']="NONE";		
	}
	unset($cek,$data);
	$cek=mysqli_query($connect,"select * from seting where nm_per='cet_nota_jual_print'");
	if(mysqli_num_rows($cek)>=1){
	  $data=mysqli_fetch_assoc($cek);
	  $_SESSION['kode']=$data['kode'];	
	}else{
	  $_SESSION['kode']="0";		
	}

	//hapus news
// 	$del_data=tglingat(date("Y-m-d"),-1);
//     mysqli_query($connect,"DELETE FROM news WHERE tgl_news <= '$del_data' AND kd_toko='$kd_toko'");

// 	unset($cek,$data);
	header("location:admin/dasbor.php"); 
} else {
    //login gagal
    session_destroy();
    header("location:index.php?pesan=gagal")or die(mysqli_error($connect));
}
mysqli_close($connect);
?>

