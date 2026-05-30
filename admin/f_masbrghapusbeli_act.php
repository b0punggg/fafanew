<?php 
  $id=$_POST['keyword'];
  ob_start();
  include 'config.php';

  session_start();
  $kd_toko=$_SESSION['id_toko'];
  $f=false;
  $connect=opendtcek(); 

      $cari=mysqli_query($connect,"SELECT * from beli_brg where no_urut='$id'");
        $data=mysqli_fetch_array($cari);
        $kd_brg=mysqli_escape_string($connect,$data['kd_brg']);
        $kd_sat=mysqli_escape_string($connect,$data['kd_sat']);
        $jml_brg=mysqli_escape_string($connect,$data['jml_brg']); 
        $no_fak=mysqli_escape_string($connect,$data['no_fak']);  
        $tgl_fak=mysqli_escape_string($connect,$data['tgl_fak']);  
        $no_item=mysqli_escape_string($connect,$data['no_item']);  
      unset($cari,$data);
  $cekjual=mysqli_query($connect,"SELECT COUNT(kd_brg) AS jumjual FROM dum_jual WHERE no_item='$id'");
  $dcekjual=mysqli_fetch_assoc($cekjual);
  if ($dcekjual['jumjual']==0){
      //hapus pada nota beli  
        $f=mysqli_query($connect, "Delete from beli_brg where no_urut='$id'" );
      //----------------------
       
      //cek sdh ada pembelian sebelumnya tdk
        $adabeli=0;
        $cekbeli=mysqli_query($connect,"SELECT COUNT(*) AS jmlbeli from beli_brg where kd_brg='$kd_brg' AND kd_toko='$kd_toko'");
        $adadata=mysqli_fetch_assoc($cekbeli); 
        $adabeli=$adadata['jmlbeli'];
        unset($adadata,$cekbeli);
      
      //cek sudah dilakukan pembayaran belum
        $cek=mysqli_query($connect,"SELECT * FROM beli_bay where no_fak='$no_fak' and tgl_fak='$tgl_fak' AND kd_toko='$kd_toko' ");
        if (mysqli_num_rows($cek)>=1){
          $cek1=mysqli_fetch_assoc($cek);
          $ket=$cek1['ket'];
        } else{$ket='';}
        unset($cek,$cek1);

      //cek dan update pada mas_brg -> brg_msk,jml_brg
        $cari=mysqli_query($connect,"SELECT * from mas_brg where kd_brg='$kd_brg'");
        $data=mysqli_fetch_array($cari);
        $kd_sat1=mysqli_escape_string($connect,$data['kd_kem1']);
        $kd_sat2=mysqli_escape_string($connect,$data['kd_kem2']);
        $kd_sat3=mysqli_escape_string($connect,$data['kd_kem3']);
        $brg_msk=mysqli_escape_string($connect,$data['brg_msk']);
        $brg_klr=mysqli_escape_string($connect,$data['brg_klr']);
        if ($kd_sat==$data['kd_kem1']) {
          $jum_sat=mysqli_escape_string($connect,$data['jum_kem1']);
        } 
        if ($kd_sat==$data['kd_kem2']) {
          $jum_sat=mysqli_escape_string($connect,$data['jum_kem2']);
        } 
        if ($kd_sat==$data['kd_kem3']) {
          $jum_sat=mysqli_escape_string($connect,$data['jum_kem3']);
        } 
        
        $brg_msk=$brg_msk-($jml_brg*$jum_sat);
        $jmlbrg=$brg_msk-$brg_klr;
        if($brg_msk<=0){$brg_msk=0;}
        $hub1=opendtcek();
        // echo $jml_brgstok.'#####'.$brg_msk;
        //echo $adabeli;
          if ($adabeli==0){
            $f=mysqli_query($hub1,"DELETE from mas_brg where kd_brg='$kd_brg'");  
            $cek2=mysqli_query($connect,"SELECT * FROM disctetap WHERE kd_brg='$kd_brg'");
            if (mysqli_num_rows($cek2)>0){
               $f=mysqli_query($hub1,"DELETE FROM disctetap WHERE kd_brg='$kd_brg'");
            }
            unset($cek2);   
          }else{
            $f=mysqli_query($hub1,"UPDATE mas_brg set brg_msk='$brg_msk',jml_brg='$jmlbrg' where kd_brg='$kd_brg'");    
          }
        mysqli_close($hub1);
        
        //cek pada pembayaran nota masih ada field jika terakhir hapus file beli_bay dan Mas_hutang
        $cek=mysqli_query($connect, "SELECT COUNT(tgl_fak) AS jmlitem from beli_brg where no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko' ");
        $cari=mysqli_fetch_assoc($cek);
        if($cari['jmlitem']==0){
          $hub1=opendtcek();
          $f=mysqli_query($hub1, "DELETE from beli_bay where no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'" );             
          $f=mysqli_query($hub1, "DELETE from beli_bay_hutang where no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'" );           
          mysqli_close($hub1);
        }else {

         // jika data masih ada pd beli_brg lakukan update pembayaran
          if (!empty($ket)){
            $connect1=opendtcek(); 
            $disc1=0;$disc2=0;$jmlsub=0;$gtot=0;    
            $cek = mysqli_query($connect1, "SELECT disc1,disc2,hrg_beli,jml_brg FROM beli_brg WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC ");
            while ($data1=mysqli_fetch_array($cek)) {
              $disc1=mysqli_escape_string($connect1,$data1['disc1'])/100;
              $disc2=mysqli_escape_string($connect1,$data1['disc2']);
              if ($data1['disc1']=='0.00'){
                // echo gantiti($data['disc2']);
                $jmlsub=(mysqli_escape_string($connect1,$data1['hrg_beli'])-$disc2)*mysqli_escape_string($connect1,$data1['jml_brg']);
              }else{
                $jmlsub=(mysqli_escape_string($connect1,$data1['hrg_beli'])-(mysqli_escape_string($connect1,$data1['hrg_beli'])*$disc1))*mysqli_escape_string($connect1,$data1['jml_brg']);
              }
              if ($data1['disc1']=='0.00' && $data1['disc2']=='0'){
                $jmlsub=mysqli_escape_string($connect1,$data1['jml_brg'])*mysqli_escape_string($connect1,$data1['hrg_beli']);
              }   
              $gtot=$gtot+$jmlsub;  
              //echo '$gtot='.$gtot."<br>";
            }
            unset($cek,$data1);
            $tag=$gtot;

            if($ket=='TUNAI'){
              mysqli_query($connect1,"UPDATE beli_bay SET saldo_awal='$tag',byr_hutang='$tag',saldo_hutang='0' WHERE no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'");     
            }else {
              $cek=mysqli_query($connect1,"SELECT * from beli_bay WHERE no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'");
              $data=mysqli_fetch_assoc($cek);
              $saldo=$tag-$data['byr_hutang'];
              unset($cek,$data);     

              $byr_hutang=0;$saldo_hutang=0;
              $cek1=mysqli_query($connect1,"SELECT * from beli_bay_hutang WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC");
              $bayar=0;$sisa=0;
              while($data1=mysqli_fetch_assoc($cek1)){
                $no_urut=$data1['no_urut'];
                $bayar=$data1['byr_hutang'];
                $sisa=$gtot-$bayar;
                mysqli_query($connect1,"UPDATE beli_bay_hutang set saldo_awal='$gtot',saldo_hutang='$sisa' WHERE no_urut='$no_urut'");      
                $gtot=$sisa;
              }
              mysqli_query($connect1,"UPDATE beli_bay SET saldo_awal='$tag',saldo_hutang='$sisa' WHERE no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'");
              unset($cek1,$data1);
              mysqli_close($connect1); 
            }
          }             
          //-----------------------------------------------   
        }
  }
  mysqli_close($connect);
  if($f){?><script>popnew_ok("Data telah dihapus !!");</script><?php }
  else {?><script>popnew_error("Gagal dihapus !!" +"<br>"+" Kemungkinan Sudah Ada Penjualan");</script><?php }
?>
<script>caribarkode();carihrgbeli(1,true);</script>
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>