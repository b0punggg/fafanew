<?php 
  $id = $_POST['id']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();

   include "config.php";
   session_start();
   
   $conhps=opendtcek();
   $id_user=$_SESSION['id_user'];
   $kd_toko=$_SESSION['id_toko'];
   $id=mysqli_real_escape_string($conhps,$id);
    // $tt=explode(";", $params);	
   $qty_brg=0;$kd_brg='';$kd_sat=0;
   $cek=mysqli_query($conhps,"SELECT * FROM dum_jual where no_urut='$id'");
   $data=mysqli_fetch_array($cek);
   $qty_brg=mysqli_escape_string($conhps,$data['qty_brg']);
   $kd_sat=mysqli_escape_string($conhps,$data['kd_sat']);
   $kd_brg=trim(mysqli_escape_string($conhps,$data['kd_brg']));
   $no_item=mysqli_escape_string($conhps,$data['no_item']);
   $bayar=mysqli_escape_string($conhps,$data['bayar']);
   $no_fakjual=mysqli_escape_string($conhps,$data['no_fakjual']);
   $tgl_jual=mysqli_escape_string($conhps,$data['tgl_jual']);
   $jml_brg=konjumbrg2($kd_sat,$kd_brg,$conhps)*$qty_brg;
   unset($cek,$data);

   //**cek jika potong stok atau tidak
    $q=mysqli_query($conhps,"SELECT * FROM seting WHERE nm_per='POTONG'");
    $d=mysqli_fetch_assoc($q);
    $potong=$d['kode'];
    mysqli_free_result($q);unset($d);

   $cek=mysqli_query($conhps,"SELECT brg_klr,jml_brg FROM mas_brg where kd_brg='$kd_brg'");
   $data=mysqli_fetch_array($cek);
   $brg_klr=mysqli_escape_string($conhps,$data['brg_klr']);
   $jumbrg=mysqli_escape_string($conhps,$data['jml_brg']);
   
   $xbrg_klr=$brg_klr-$jml_brg;
   if ($xbrg_klr<0){
    $xbrg_klr=0;
    //$jml_brg=0;
    $jumbrg=0;
   }
   
   $xjumbrg=$jumbrg+$jml_brg;
   unset($data);mysqli_free_result($cek);
   
   $cek=mysqli_query($conhps,"SELECT stok_jual FROM beli_brg where no_urut='$no_item'");
   $data=mysqli_fetch_array($cek);
   $jual_stok=mysqli_escape_string($conhps,$data['stok_jual']);
   $xjual_stok=$jual_stok+$jml_brg;
   unset($data);mysqli_free_result($cek);
  //  echo 'no_item='.$no_item.'<br>';
  //  echo 'xjual_stok='.$xjual_stok.'<br>';
  if ($potong==1){
   $f=mysqli_query($conhps, "UPDATE beli_brg set stok_jual='$xjual_stok' WHERE no_urut='$no_item' ");  
  
   $f=mysqli_query($conhps, "UPDATE mas_brg SET brg_klr='$xbrg_klr',jml_brg='$xjumbrg' WHERE kd_brg='$kd_brg'");
  }
  $f=mysqli_query($conhps, "DELETE from dum_jual WHERE no_urut='$id'" );
  //mysqli_close($conhps); 

  //cek pd mas_jual 
   $ada=0;
   //$conhps=opendtcek();
   $cari=mysqli_query($conhps,"SELECT * FROM dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' ");
   if(mysqli_num_rows($cari)>=1){
     $ada=1;
   }else{
     $ada=0;
     mysqli_query($conhps,"DELETE FROM mas_jual WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' AND kd_toko='$kd_toko'");
     mysqli_query($conhps,"DELETE FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' AND kd_toko='$kd_toko'");
   }
   unset($cari);
   // mysqli_close($conhps);  

   if($f){
    if($ada==1){
       ?><script>
        popnew_warning("Data terhapus, silahkan update bayar nota");
        document.getElementById("edit-warning").value=0;
       kosongkan();
       caribrgjual(1,true);
       </script><?php  
     }else{ 
       ?><script>
      popnew_warning("Data telah terhapus...");
       document.getElementById("edit-warning").value=0;
       kosongkan();
       caribrgjual(1,true);
       </script><?php  
     }
   }else {
     ?><script>popnew_warning("Data gagal dihapus..");
     document.getElementById("edit-warning").value=0;
       kosongkan();
       caribrgjual(1,true);
     </script><?php  
   }
   
 ?>    

 <!-- <script>document.getElementById("tmb-reset").click();carinmpel();</script> -->
<?php
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>