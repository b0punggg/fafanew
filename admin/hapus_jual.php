<?php 
include 'config.php';
session_start();
$tgl1=$_POST['tglhap1'];
$tgl2=$_POST['tglhap2'];
if ($_POST['tglhap1']>$_POST['tglhap1']) {
   $tgl1=$_POST['tglhap2'];
   $tgl2=$_POST['tglhap1'];
}

$con=opendtcek();
$arr=mysqli_query($con,"SELECT * FROM mas_jual WHERE tgl_jual>='$tgl1' AND tgl_jual<='$tgl2' AND ket_bayar='LUNAS' ");

if (mysqli_num_rows($arr)>=1){
    while ($data=mysqli_fetch_assoc($arr)) {
		$numrow     = $data['no_urut'];
		$no_fakjual = $data['no_fakjual'];
		 
	    echo ' Nota '. $data['no_fakjual'].', Tgl. '.gantitgl($data['tgl_jual']);
	    $d=false;$dd=false;$ddd=false;
	    $d=mysqli_query($con,"DELETE FROM dum_jual WHERE no_fakjual='$no_fakjual'");
	    $dd=mysqli_query($con,"DELETE FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND saldo_hutang=0");
		$ddd=mysqli_query($con,"DELETE FROM mas_jual WHERE no_urut='$numrow'");
		if ($d && $dd && $ddd){
			echo 'Berhasil dihapus'.'<br>';
		} else {
			echo 'gagal dihapus '.'<br>';
			if ($d==false){
			   echo 'File dum_jual gagal'.'<br>';	
			}
			if ($dd==false){
			   echo 'File mas_jual_hutang gagal'.'<br>';	
			}
			if ($ddd==false){
			   echo 'File mas_jual gagal'.'<br>';	
			}
		}
	}
	echo "Proses Selesai";
} else {
	echo "Data tidak ditemukan !";
}
	
?>
