<?php
  $keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();

    include 'config.php';
    session_start();
    $oto=$_SESSION['kodepemakai'];
    $hub=opendtcek();
    
    $keyword=mysqli_real_escape_string($hub,$keyword);
    $x=explode(';',$keyword);
    $kd_toko=$x[0];
    $id_user=$x[1];
    $no1=0;$x1='';$x2='';$no2=0;$max_nofak=0;
    $sx  = '0000-00-00';$ntk='IDTOKO-0';
    $ntk = explode('-',$kd_toko);
    $tk  = $ntk[1];

    $cari=mysqli_query($hub,"SELECT kode from seting WHERE nm_per='$kd_toko'");
    $dcari=mysqli_fetch_assoc($cari);
    if($dcari['kode']>1000000000){
      $max_nofak=1;
    }else {
      $max_nofak=$dcari['kode'];
    }
    mysqli_free_result($cari);unset($dcari);
    
   // --------------

    $cek1=mysqli_query($hub,"SELECT dum_jual.no_urut,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.bayar,dum_jual.kd_pel,dum_jual.id_user,dum_jual.nm_user,pelanggan.nm_pel,pelanggan.al_pel,dum_jual.panding FROM dum_jual 
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      WHERE dum_jual.kd_toko='$kd_toko' AND dum_jual.id_user='$id_user' AND dum_jual.bayar='BELUM' AND dum_jual.panding='0' ORDER BY dum_jual.no_urut DESC limit 1");
      
    //bool 0->false
    $data1=mysqli_fetch_array($cek1); 
    if(mysqli_num_rows($cek1)>=1){
        $x2        = explode('-',mysqli_escape_string($hub,$data1['no_fakjual']));   
        $no        = $x2[1];
        $tgl_jual  = $data1['tgl_jual'];
        $kd_pel    = mysqli_escape_string($hub,$data1['kd_pel']);
        $sx        = strtotime($tgl_jual);
        $no_fakjual=mysqli_escape_string($hub,$data1['no_fakjual']);
    } else {
        if (!empty($max_nofak)){
          $no      = $max_nofak;    
        } else { $no=1;}
        $tgl_jual  = $_SESSION['tgl_set'];
        $kd_pel    = "IDPEL-0";
        
        $sx  = strtotime($tgl_jual);
        $no_fakjual='FAFA'.$tk.'.'.date('d',$sx).date('m',$sx).date('y',$sx).'-'.$id_user.$no; 
    }   
    mysqli_free_result($cek1);unset($data1);   
    // --------------------------------

    $con=$hub;
      $tglhi=$_SESSION['tgl_set'];
      $xjual=0;$xdisc=0;$tot_omset=0;$tot_laba=0;$brg_keluar=0;
      // Hanya hitung barang yang sudah dibayar (bayar='SUDAH')
      $sql = mysqli_query($con, "SELECT * FROM dum_jual WHERE tgl_jual='$tglhi' AND kd_toko='$kd_toko' AND panding=false AND ket<>'RETUR BARANG' AND bayar='SUDAH'");
      if(mysqli_num_rows($sql)>=1){  
        while($cek=mysqli_fetch_array($sql)){
          
          if ($cek['discitem']==0 && $cek['discrp']==0 ) {
            $jumlah=$cek['hrg_jual']*$cek['qty_brg'];
            $diskon=0;
          } 
          if ($cek['discitem'] > 0 && $cek['discrp']==0 ) {
            $disc=$cek['hrg_jual']-($cek['hrg_jual'] * ($cek['discitem']/100));
            $jumlah=$disc*$cek['qty_brg'];
            $diskon=gantitides($cek['hrg_jual'] * ($cek['discitem']/100));
          }
          if ($cek['discitem'] == '0' && $cek['discrp'] > 0 ) {
            $disc=$cek['hrg_jual']-$cek['discrp'];
            $jumlah=$disc*$cek['qty_brg'];
            $diskon=gantitides($cek['discrp']);
          } 
          if ($cek['discitem'] > 0 && $cek['discrp'] > 0 ) {
            $ditem=$cek['discrp'];
            $xz=$cek['hrg_jual']*$cek['discitem']/100;
            $jumlah=($cek['hrg_jual']-($ditem+$xz))*$cek['qty_brg']; 
            $diskon=gantitides($cek['discrp']+$xz);
          } 
          if ($cek['discvo']>0){
            $ditem=$cek['discrp'];
            $dnot =$cek['hrg_jual']*($cek['discitem']/100);
            $divo =$cek['hrg_jual']*($cek['discvo']/100);
            $hrgjl=$cek['hrg_jual']-($ditem+$dnot+$divo); 
            $jumlah=round($hrgjl*$cek['qty_brg'],0); 
            $diskon=gantitides($ditem+$dnot+$divo);
          }
          $tot_omset=$tot_omset+$jumlah;
          $tot_laba=$tot_laba+$cek['laba'];
          $brg_keluar=$brg_keluar+$cek['qty_brg'];
        }
      }
      unset($cek);mysqli_free_result($sql);

      // $adap=0;  
      // $tglhi=$_SESSION['tgl_set'];
      // $h_jt_1=tglingat($tglhi,-1);
      // $h_jt=tglingat($tglhi,2);
      // $date=date_create($tglhi);
      // date_add($date,date_interval_create_from_date_string("3 days"));
      // $hut_jt=date_format($date,"Y-m-d");
      
      // $sql = mysqli_query($con, "SELECT count(no_fakjual) AS adap,tgl_jt from mas_jual WHERE kd_toko='$kd_toko' AND saldo_hutang > 0 
      //   HAVING tgl_jt<='$h_jt'");
      // $datajt=mysqli_fetch_assoc($sql);
      // $adap=$datajt['adap'];
      // unset($datajt);mysqli_free_result($sql);

      // $adah=0;  
      // $sql = mysqli_query($con, "SELECT count(*) as adah,tgl_jt from beli_bay WHERE kd_toko='$kd_toko' AND saldo_hutang > 0 
      //   HAVING tgl_jt<='$h_jt'");
      // $datajt=mysqli_fetch_assoc($sql);
      // $adah=$datajt['adah'];

      // unset($datajt);mysqli_free_result($sql);
      // mysqli_close($con);
      
  if ($oto==1){
    //$tot_omset=0;
    $tot_laba=0;
  }     
?>

<script>
   if (document.getElementById('vcari').value==""){
     document.getElementById('no_fakjual').value='<?=$no_fakjual?>'; 
     document.getElementById('tgl_fakjual').value='<?=$tgl_jual?>'; 
   }
   document.getElementById('brg_kel1').innerHTML='<?=round($brg_keluar,0)?>'; 
   document.getElementById('not_klr').innerHTML='<?=round($brg_keluar,0)?>'; 
   document.getElementById('tot_om').innerHTML='<?=gantitides(round($tot_omset),0)?>'; 
   document.getElementById('tot_lab').innerHTML='<?=gantitides(round($tot_laba),0)?>';  
  
   caribrgjual(1,true);
   document.getElementById('kd_brg').focus();
</script>

<?php
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>