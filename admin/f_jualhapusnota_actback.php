
<?php 
  $no_fakjual = $_POST['no_fakjual']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();
  include "config.php";
  session_start();
  $connect=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
  $id_user=$_SESSION['id_user'];
  $cek=mysqli_query($connect,"SELECT * from dum_jual
  WHERE kd_toko='$kd_toko' AND no_fakjual='$no_fakjual' ORDER BY no_urut ASC");
  if(mysqli_num_rows($cek)>=1){    
    while ( $data=mysqli_fetch_array($cek)) {
      update($data['no_urut'],$kd_toko,$connect);   
       ?><div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div><?php
    } 
    
   $f=mysqli_query($connect, "DELETE from dum_jual WHERE no_fakjual='$no_fakjual'" ); 
   ?><script>caribrgjual(1,true);</script><?php   
  }else {
    ?><script>popnew_ok("Wis Angel..kih. angel.. <?=$_SESSION['nm_user']?>, nota kosong !! ")</script><?php
  }
  unset($cek,$data);  
 
  function update($no_urut,$kd_toko,$hub){
    // $connect3 = opendtcek();
    //**cek jika potong stok atau tidak
    $q=mysqli_query($hub,"SELECT * FROM seting WHERE nm_per='POTONG'");
    $d=mysqli_fetch_assoc($q);
    $potong=$d['kode'];
    unset($q,$d);

    $kd_toko=$_SESSION['id_toko'];
    $dat=mysqli_query($hub,"SELECT dum_jual.kd_sat,dum_jual.kd_brg,dum_jual.qty_brg,mas_brg.brg_klr,dum_jual.no_item ,dum_jual.no_fakjual,dum_jual.bayar,beli_brg.stok_jual,mas_brg.no_urut,mas_brg.jml_brg FROM dum_jual 
    LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg 
    LEFT JOIN beli_brg ON dum_jual.no_item=beli_brg.no_urut 
    WHERE dum_jual.no_urut='$no_urut' ORDER BY dum_jual.no_urut ASC");

    $cek=mysqli_fetch_array($dat);
    $brg_klrawal=0;$jual_stokawal=0;$jumbrg=0;   
    $kd_satawal=mysqli_escape_string($hub,$cek['kd_sat']);
    $kd_brgawal=mysqli_escape_string($hub,$cek['kd_brg']);
    $qty_brgawal=mysqli_escape_string($hub,$cek['qty_brg']);
    $brg_klrawal=mysqli_escape_string($hub,$cek['brg_klr']);
    $jumbrg=mysqli_escape_string($hub,$cek['jml_brg']);
    $jual_stokawal=mysqli_escape_string($hub,$cek['stok_jual']);
    $bayarawal=mysqli_escape_string($hub,$cek['bayar']);
    $no_itemawal=mysqli_escape_string($hub,$cek['no_item']);
    $no_fakjual= mysqli_escape_string($hub,$cek['no_fakjual']); 
            
    //konversikan jml.brang satuan kecil;
    $jml_brgawal=0;$jumbrgawal=0;
    $jml_brgawal=konjumbrg2($kd_satawal,$kd_brgawal,$hub)*$qty_brgawal;
    
    //ambil variable utk replace brg_klr pada mas_brg  

    // echo '$brg_klrawal='.$brg_klrawal.'-$jml_brgawal='.$jml_brgawal.'<br>'  ;  
    $xbrg_klrawal=$brg_klrawal-$jml_brgawal;
    if ($xbrg_klrawal < 0){
      $xbrg_klrawal=0;
      //$jml_brgawal=0;
    }
    $xjumbrg=$jumbrg+$jml_brgawal;
    // ambil variable utk replace stok_jual pada beli_brg_jml
    $xjual_stokawal=$jual_stokawal+$jml_brgawal;
    
    // update pada file masing2
    if ($bayarawal=="SUDAH"){
       $f=mysqli_query($hub, "DELETE from mas_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'" ); 
       $f=mysqli_query($hub, "DELETE from mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'" ); 
    } 
    if ($potong==1){
     $f=mysqli_query($hub, "UPDATE beli_brg set stok_jual='$xjual_stokawal' WHERE no_urut='$no_itemawal' ");  
     $f=mysqli_query($hub, "UPDATE mas_brg SET brg_klr='$xbrg_klrawal',jml_brg='$xjumbrg' WHERE kd_brg='$kd_brgawal' ");
    }
  }     
  unset($datsql,$cek);    
?>
<script>
  kosongkan2();
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>