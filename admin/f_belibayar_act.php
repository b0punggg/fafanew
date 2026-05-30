<?php 
  // mysqli_close($connect);
  include 'config.php';
  session_start();
  $connect=opendtcek();
  $kd_sup=strtoupper($_POST['byr_kd_sup']);
  $no_faks=strtoupper($_POST['byr_no_fak']);
  $byr_tag=backnumdes($_POST['byr_tag_fak']); // hidden
  $tgl_fak=$_POST['byr_tgl_fak'];
  $tgl_tran=$_POST['byr_tgl_hi'];
  if (!empty($_POST['byr_tgl_jt'])) {
    $tgl_jt=$_POST['byr_tgl_jt'];  
  }else{$tgl_jt='';}
  if(!empty($_POST['byr_tag_dp'])){
    $byr_dp=backnumdes($_POST['byr_tag_dp']);  
  }else{$byr_dp=0;}

  $kd_toko=$_SESSION['id_toko'];
  $cr_bay=$_POST['byr_cr_bay'];
  $ketbeli=$_POST['ketbeli'];
  $disctot=str_replace(',','.',$_POST['disctot']);
  $ppn=str_replace(',','.',$_POST['tax']);
  $sisanew=0;$sisalama=0;$sisa=0;
  
  //update discount total pada beli_brg
  if ($disctot>0){
    $cek=mysqli_query($connect,"UPDATE beli_brg SET disc1='$disctot' WHERE no_fak='$no_faks' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'");
    unset($cek);
  }
  
  if ($ppn>0){
    $cek=mysqli_query($connect,"UPDATE beli_brg SET ppn='$ppn' WHERE no_fak='$no_faks' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'");
    unset($cek);
  }
  $cek=mysqli_query($connect,"UPDATE beli_brg SET kd_sup='$kd_sup' WHERE no_fak='$no_faks' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'");
    unset($cek);

  $ceknota1=mysqli_query($connect,"select * from beli_bay where no_fak='$no_faks' and tgl_fak='$tgl_fak' and kd_toko='$kd_toko' ");
   if(mysqli_num_rows($ceknota1)>=1){
    //cek untuk ada hutang bay_beli
     $data=mysqli_fetch_array($ceknota1);
     $ket=$data['ket'];
     $sisalama=$data['saldo_hutang'];
     $no_urutbay=$data['no_urut'];

     //hapus news
      //mysqli_query($connect,"DELETE FROM news WHERE no_item='$no_urut'");
        
     // jika data pembayaran diubah dari tunai ke tempo atau sebaliknya
     if($cr_bay=='TUNAI'){
        $d=mysqli_query($connect,"UPDATE beli_bay set tgl_tran='$tgl_tran',tot_beli='$byr_tag',saldo_awal='$byr_tag',byr_hutang='$byr_tag',tgl_jt='$tgl_jt',saldo_hutang='0',pay='1',ket='TUNAI',ppn='$ppn',disc='$disctot',ketbeli='$ketbeli',kd_sup='$kd_sup' WHERE no_urut='$no_urutbay'");  
        $cekhutang=mysqli_query($connect,"SELECT * FROM beli_bay_hutang WHERE kd_toko='$kd_toko' AND no_fak='$no_faks' AND tgl_fak='$tgl_fak'");
        if (mysqli_num_rows($cekhutang)>=1){
          $d=mysqli_query($connect,"DELETE FROM beli_bay_hutang where kd_toko='$kd_toko' AND no_fak='$no_faks' AND tgl_fak='$tgl_fak'");    
        }
        unset($cekhutang);
     }else{
        // jika pembayaran tempo
        $sisa=$byr_tag-$byr_dp;
        $connect2=opendtcek();
        $cek=mysqli_query($connect2,"SELECT * FROM beli_bay_hutang WHERE kd_toko='$kd_toko' AND no_fak='$no_faks' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC limit 1");
        if(mysqli_num_rows($cek)>=1){
          $dat=mysqli_fetch_assoc($cek);
          $no_x=$dat['no_urut'];
          $sd=mysqli_query($connect2,"UPDATE beli_bay_hutang SET totbeli='$byr_tag', saldo_awal='$byr_tag', byr_hutang='$byr_dp', saldo_hutang='$sisa',kd_sup='$kd_sup' WHERE no_urut='$no_x'");
        }
        mysqli_free_result($cek);
        mysqli_close($connect2);unset($dat);

        $connect2=opendtcek();
        $cek=mysqli_query($connect2,"SELECT * FROM beli_bay_hutang WHERE kd_toko='$kd_toko' AND no_fak='$no_faks' AND tgl_fak='$tgl_fak'  ORDER BY no_urut ASC limit 1");
        if(mysqli_num_rows($cek)>=1){
          $dat=mysqli_fetch_assoc($cek);
          $no_x=$dat['no_urut'];
          // $sd=mysqli_query($connect2,"UPDATE beli_bay_hutang SET totbeli='$byr_tag', saldo_awal='$byr_tag', byr_hutang='$byr_dp', saldo_hutang='$sisa' WHERE no_urut='$no_x'");
        
          $connect1=opendtcek(); 
          $disc1=0;$disc2=0;$jmlsub=0;$gtot=0;$ket='';    
          $cek = mysqli_query($connect1, "SELECT disc1,disc2,hrg_beli,jml_brg,ket FROM beli_brg WHERE kd_toko='$kd_toko' AND no_fak='$no_faks' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC ");
          while ($data1=mysqli_fetch_array($cek)) {
            $disc1=mysqli_escape_string($connect1,$data1['disc1'])/100;
            $disc2=mysqli_escape_string($connect1,$data1['disc2']);
            $ketbeli=mysqli_escape_string($connect1,$data1['ket']);
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
          $byr_hutang=0;$saldo_hutang_hutang=0;
          $cek1=mysqli_query($connect1,"SELECT * from beli_bay_hutang WHERE kd_toko='$kd_toko' AND no_fak='$no_faks' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC");
          $bayar=0;$sisa=0;$xsisa=0;$xgtot=0;
          $xgtot=$gtot+($gtot*($ppn/100));
          while($data1=mysqli_fetch_assoc($cek1)){
            $no_urut=$data1['no_urut'];
            $bayar=$data1['byr_hutang'];
            $sisa=$xgtot-$bayar;
            mysqli_query($connect1,"UPDATE beli_bay_hutang set saldo_awal='$xgtot',saldo_hutang='$sisa',,kd_sup='$kd_sup' WHERE no_urut='$no_urut'");      
            $xgtot=$sisa;
            if ($sisa>0){
              $xsisa=$sisa;  
            }
          }
          $d=mysqli_query($connect1,"UPDATE beli_bay set tgl_tran='$tgl_tran',tot_beli='$byr_tag',saldo_awal='$byr_tag',byr_hutang='$byr_dp',saldo_hutang='$xsisa',pay='1',ket='TEMPO',disc='$disctot',ppn='$ppn',tgl_jt='$tgl_jt',ketbeli='$ketbeli',kd_sup='$kd_sup' where no_urut='$no_urutbay'");  
          unset($cek1,$data1);
          mysqli_close($connect1);   

        }else{ // jika beli_bay_hutang blm ada...
          $sisa=$byr_tag-$byr_dp;
          $d=mysqli_query($connect2,"UPDATE beli_bay set tgl_tran='$tgl_tran',tot_beli='$byr_tag',saldo_awal='$byr_tag',byr_hutang='$byr_dp',saldo_hutang='$sisa',pay='1',ket='TEMPO',disc='$disctot',ppn='$ppn',tgl_jt='$tgl_jt',ketbeli='$ketbeli',kd_sup='$kd_sup' where no_urut='$no_urutbay'");  
          $d=mysqli_query($connect2,"INSERT INTO beli_bay_hutang VALUES('','$kd_sup','$no_faks','$tgl_fak','$tgl_tran','$byr_tag','$byr_tag','$byr_dp','$sisa','TEMPO','$kd_toko','$tgl_jt','')");
        }
        mysqli_close($connect2);
        unset($cek,$dat);
        // echo '$no_x='.$no_x.'<br>';
        // echo 'no_faks='.$no_faks.'<br>';
          
        //-----------------------------------------------
     }
   } else {
      if($cr_bay=="TUNAI"){
       $d=mysqli_query($connect,"insert into beli_bay values('','$kd_sup','$no_faks','$tgl_fak','$tgl_tran','$byr_tag','$byr_tag','$byr_tag','$sisa','$cr_bay','$kd_toko','$tgl_jt','1','$disctot','$ppn','$ketbeli')");
       
      }else{
        // echo '$cr_bay='.$cr_bay.'- $no_faks='.$no_faks.'- $byr_dp='.$byr_dp.'- $tgl_jt='.$tgl_jt;
       $sisa=$byr_tag-$byr_dp;
       // if($sisa==0){$ketss="LUNAS";}else{$ketss="";}
       $d=mysqli_query($connect,"INSERT INTO beli_bay values('','$kd_sup','$no_faks','$tgl_fak','$tgl_tran','$byr_tag','$byr_tag','$byr_dp','$sisa','$cr_bay','$kd_toko','$tgl_jt','1','$disctot','$ppn','$ketbeli')");
       $d=mysqli_query($connect,"INSERT INTO beli_bay_hutang VALUES('','$kd_sup','$no_faks','$tgl_fak','$tgl_tran','$byr_tag','$byr_tag','$byr_dp','$sisa','$cr_bay','$kd_toko','$tgl_jt','')");
      } 
   }

   if($d){header("location:f_beli.php?pesan=simpan");}
   else{header("location:f_beli.php?pesan=gagal");}    
?>
