<?php 
	include 'config.php';
	$con=opendtcek();
	$cek=mysqli_query($con,"SELECT * FROM beli_brg GROUP BY no_fak order by no_fak ");
	// $cek2=mysqli_query($con,"SELECT * FROM beli_bay order by no_fak ");
	while ($dacek=mysqli_fetch_assoc($cek)){
        $no_fak=$dacek['no_fak']; 
        $ket=$dacek['ket']; 
        $cek2=mysqli_query($con,"UPDATE beli_bay SET ketbeli='$ket' WHERE no_fak='$no_fak' order by no_fak ");
        if ($cek2){ echo 'simpan ok - '.$no_fak.'<br>';}
	}
?>
