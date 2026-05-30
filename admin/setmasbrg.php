<?php
//digunakan untuk set master barang -> jml_brg,brg_msk,brg_klr sama dengan beli_brg
include "config.php";
$connect=opendtcek();
//$cek1=mysqli_query($connect,"SELECT * FROM mas_brg ORDER BY no_urut ASC");
$cek1=mysqli_query($connect,"SELECT kd_brg,kd_sat,sum(stok_jual) as stokis FROM beli_brg group by kd_brg");
$hrg_b1=0;
while($dt=mysqli_fetch_assoc($cek1)){
    $kd_brg =$dt['kd_brg'];
    $stok=$dt['stokis'];
    // $no_urut=$dt['no_urut'];
    // $hrg_1  =$dt['hrg_jum1'];
    // $hrg_2  =$dt['hrg_jum2'];
    // $hrg_3  =$dt['hrg_jum3'];
    // $per    = 24/100;
    // if ($dt['hrg_jum1']>0){
    //    $hrg_b1=$dt['hrg_jum1']-($dt['hrg_jum1']*$per);
    // }
    
    // $cek2=mysqli_query($connect,"SELECT kd_brg,kd_sat,sum(stok_jual) as stokis FROM beli_brg WHERE kd_brg='$kd_brg' group by kd_brg");
    $cek2=mysqli_query($connect,"SELECT * FROM mas_brg where kd_brg='$kd_brg' ORDER BY no_urut ASC");
    if (mysqli_num_rows($cek2)>=1){
        
        $dt2=mysqli_fetch_assoc($cek2);
        // $stok=$dt2['stokis'];
        //mysqli_query($connect,"UPDATE beli_brg SET hrg_beli='$hrg_b1' WHERE kd_brg='$kd_brg'");   
            mysqli_query($connect,"UPDATE mas_brg SET jml_brg='$stok',brg_msk='$stok',brg_klr='0' WHERE kd_brg='$kd_brg'");  
        
        
    } else {
        //mysqli_query($connect,"DELETE FROM mas_brg WHERE no_urut='$no_urut'");
        echo 'kode brg='.$kd_brg.' dihapus..'.'<br>';  
    }
    unset($dt2,$cek2);
}
?>