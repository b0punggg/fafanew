<?php 
include 'config.php';
session_start();
$connect=opendtcek();
//input data mutasi barang
$kd_brg      = mysqli_escape_string($connect,$_POST['kd_brgmut']);
$kd_tokoasal = mysqli_escape_string($connect,$_POST['kd_tokomut']);
$kd_tokotuj  = $_SESSION['id_toko'];
$kd_sat      = $_POST['sat'];
$qty_brg     = $_POST['qty_brg'];
$tgl_fak     = $_SESSION['tgl_set'];
$no_urut     = $_POST['no_urut'];
$id_bag      = $_POST['id_bag'];
$qty=0;$n_stok_jual=0;$stok=0;$jml=0;$hrg_beliawal=0;$no=0;

//cari satuan terkecil brg konversi yg dimutasi    
$x           = explode(';', carisatkecil($kd_brg));
$sat_kecil   = $x[0]; 
$jum_kecil   = $x[1]; 

//ambil data barang yang diambil
$cek_it=mysqli_query($connect,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ket_mut,beli_brg.ppn,beli_brg.expdate,mas_brg.nm_brg FROM beli_brg 
   LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
   WHERE beli_brg.no_urut='$no_urut'
   ORDER BY beli_brg.no_urut ASC");    
$cari=mysqli_fetch_array($cek_it);//ambil info dt brg yg diambil   
// Cari satuan pembelian barang yg diambil
$kd_satawal = $cari['kd_sat'];
$hrg_beli   = $cari['hrg_beli'];
$no_fak     = $cari['no_fak'];
$no_urut    = $cari['no_urut'];
$nm_brg     = $cari['nm_brg'];
$disc1      = $cari['disc1'];
$disc2      = $cari['disc2'];
$kd_sup     = $cari['kd_sup'];
$kd_bar     = $cari['kd_bar'];
$ppn        = $cari['ppn'];
$expdate    = $cari['expdate'];
$ketawal    = strpos($cari['ket'],"BELIAN BARANG");
$ket1       = 'MUTASI DARI '.$kd_tokoasal;
$ket2       = 'MUTASI KE '.$kd_tokotuj;
$tglhi      = $_SESSION['tgl_set'];
$ketmut     = $kd_tokoasal.';'.$no_urut;
//-------------------------------------

// variable data input mutasi save mutasi_brg
$jum_kem_a  = konjumbrg($kd_sat,$cari['kd_brg']);
$hrg_beli_m = $cari['hrg_beli']/konjumbrg($cari['kd_sat'],$cari['kd_brg']);
$jml_brg    = $qty_brg/$jum_kem_a;
$stok_jual  = $qty_brg*$jum_kem_a; // satuan terkecil
$stok_lama  = $cari['stok_jual']-$stok_jual;

if($qty_brg>0){
  if ($ketawal>0){ // jika yang diambil dari pembelian barang
    $d=mysqli_query($connect,"UPDATE beli_brg SET stok_jual='$stok_lama' WHERE no_urut='$no_urut'");
    $d=mysqli_query($connect,"INSERT INTO beli_brg VALUES('','$tgl_fak','$no_fak','$kd_brg','$kd_bar','$kd_tokotuj','$kd_sup','$kd_satawal','$hrg_beli','$disc1','$disc2','$jml_brg','$stok_jual','$ket1','','','$ketmut','$ppn','$id_bag','$expdate')");   
    $d=mysqli_query($connect,"INSERT INTO mutasi_brg VALUES('','$tglhi','$tgl_fak','$no_fak','$no_urut','$kd_tokoasal','$kd_brg','$kd_sat','$qty_brg','$ket2')");      
  }else {
    // jika diambil dari data mutasi juga
    $no_urutasal="";$stokasal=0;$stokkembali=0;$kd_tokoawal="";
    $d=mysqli_query($connect,"UPDATE beli_brg SET stok_jual='$stok_lama' WHERE no_urut='$no_urut'");
    if($cari['ket_mut']<>""){
      $x=explode(';',$cari['ket_mut']);
      $no_urutasal=$x[1];
      $kd_tokoawal=trim($x[0]);
      // echo ' $no_urutasal='. $no_urutasal.'<br>';
      // echo ' $kd_tokoawal='. $kd_tokoawal.'<br>';
      if($kd_tokoawal==$kd_tokotuj){
        $cek=mysqli_query($connect,"SELECT stok_jual from beli_brg where no_urut='$no_urutasal'");
        if(mysqli_num_rows($cek)>=1){
          $datcek=mysqli_fetch_assoc($cek);
          $stokasal=$datcek['stok_jual'];
          $stokkembali=$stokasal+$stok_jual;
          $d=mysqli_query($connect,"UPDATE beli_brg SET stok_jual='$stokkembali' WHERE no_urut='$no_urutasal'");
        }else{
          $d=mysqli_query($connect,"INSERT INTO beli_brg VALUES('','$tgl_fak','$no_fak','$kd_brg','$kd_bar','$kd_tokotuj','$kd_sup','$kd_satawal','$hrg_beli','$disc1','$disc2','$jml_brg','$stok_jual','$ket1','','','$ketmut','$ppn','$id_bag','$expdate')");
        }
        unset($cek,$datacek);
      }else{
        $d=mysqli_query($connect,"INSERT INTO beli_brg VALUES('','$tgl_fak','$no_fak','$kd_brg','$kd_bar','$kd_tokotuj','$kd_sup','$kd_satawal','$hrg_beli','$disc1','$disc2','$jml_brg','$stok_jual','$ket1','','','$ketmut','$ppn','$id_bag','$expdate')");
      }
    
    }
    $d=mysqli_query($connect,"INSERT INTO mutasi_brg VALUES('','$tglhi','$tgl_fak','$no_fak','$no_urut','$kd_tokoasal','$kd_brg','$kd_sat','$qty_brg','$ket2')"); 
  }
  if($d){header("location:f_mutgudang.php?pesan=simpan");}
  else{header("location:f_mutgudang.php?pesan=gagal");}  
}else{
  header("location:f_mutgudang.php?pesan=zonk");
}
mysqli_close($connect);

?>