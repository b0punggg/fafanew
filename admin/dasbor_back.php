<link rel="shortcut icon" href="img/keranjang.png">
<script src="../assets/js/chart.min.js"></script>
<script src="../assets/js/utils.js"></script>
<div class="loader1"><div class="loader2"><div class="loader3"></div></div></div>
<style>
  option{color:dodgerblue;}
</style> 

<script>
  function caristok0 (page_number, search){      
    $.ajax({
      url: 'dasbor_stok0.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {page: page_number, search: search}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        $("#viewstok0").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }

  function caristok5 (page_number, search){      
    $.ajax({
      url: 'dasbor_stok5.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {page: page_number, search: search}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        $("#viewstok5").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
</script>

<?php include 'starting.php'; 
$connect=opendtcek();
$kd_toko=$_SESSION['id_toko'];
$oto=$_SESSION['kodepemakai'];
$xtgl=explode('-', $_SESSION['tgl_set']);
$endbln=$xtgl[1];
$endyear=$xtgl[0];
$tglhi=$_SESSION['tgl_set'];

$tot=0;$totitem=0;$totbeli=0;$stok_0=0;$stok_limit=0;$hrg_beliawal=0;$x=0;$hrg_asal=0;$hrg_belidisc=0;
$cek=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.kd_toko,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.ppn,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3 FROM beli_brg
    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
    WHERE  beli_brg.stok_jual > 0 AND beli_brg.kd_toko='$kd_toko'
    ORDER by beli_brg.kd_toko ASC");

while($data=mysqli_fetch_assoc($cek)){
  $x++;
  $disc1=$data['disc1']/100;
  $disc2=$data['disc2'];
  //konversi satuan terkecil
  if($data['kd_sat']==$data['kd_kem3']){
    $hrg_asal=$data['hrg_beli']/$data['jum_kem3'];
  }
  if($data['kd_sat']==$data['kd_kem2']){
    $hrg_asal=$data['hrg_beli']/$data['jum_kem2'];
  }
  if($data['kd_sat']==$data['kd_kem1']){
    $hrg_asal=$data['hrg_beli']/$data['jum_kem1'];
  }
  // ------------------------  

  if ($data['disc1']==0.00 && $data['disc2']==0){
    $hrg_belidisc=$hrg_asal*$data['stok_jual'];
  } else if ($data['disc1'] > 0.00 && $data['disc2']==0) {
    $hrg_belidisc=($hrg_asal-($hrg_asal*$disc1))*$data['stok_jual'];
  } else if ($data['disc1'] == 0.00 && $data['disc2']>0) {
    $hrg_belidisc=($hrg_asal-$disc2)*$data['stok_jual'];
  }
  
  $hrg_belidiscx=$hrg_belidisc+($hrg_belidisc*($data['ppn']/100));
  $tot=$tot+$hrg_belidiscx;  
} 

//laba masuk bulan ini
$tglcari=$endyear.'-'.$endbln.'-'.'01';
$cekmas2=mysqli_query($connect,"SELECT SUM(laba) AS labahi FROM mas_jual_hutang 
         WHERE MONTH(mas_jual_hutang.tgl_tran)='$endbln' AND YEAR(mas_jual_hutang.tgl_tran)='$endyear' 
         AND mas_jual_hutang.byr_hutang>0 AND mas_jual_hutang.tgl_jual < '$tglcari' AND kd_toko='$kd_toko'");
if (mysqli_num_rows($cekmas2)>0){
  $data=mysqli_fetch_assoc($cekmas2);
  $laba_pi_bini=$data['labahi'];
}else{
  $laba_pi_bini=0;
}         
unset($data,$cekmas2);

// Total laba ditahan seluruhnya
$cekmas=mysqli_query($connect,"SELECT * FROM mas_jual where kd_bayar='TEMPO' AND kd_toko='$kd_toko' ORDER BY mas_jual.no_urut ASC");
$tot_as_th_d=0;$tot_as_ms_d=0;$tot_laba_th_d=0;$tot_laba_ms_d=0;$hrg_beli_d=0;$uang_ms_d=0;$aset_ms_d=0;$laba_ms_d=0;$tot_jual_d=0;$laba_th=0;
while($databay=mysqli_fetch_assoc($cekmas)){
    $hrg_beli_d=($databay['tot_jual']-$databay['tot_disc'])-$databay['tot_laba'];
    $uang_ms_d =($databay['tot_jual']-$databay['tot_disc']-$databay['saldo_hutang']);
    if ($uang_ms_d<=$hrg_beli_d) {
       $aset_ms_d    =$uang_ms_d; 
       $laba_ms_d    =0;
    }else {
       $aset_ms_d    =$hrg_beli_d; 
       $laba_ms_d    =$uang_ms_d-$hrg_beli_d;      
    }
    $tot_as_ms_d  =$tot_as_ms_d+$aset_ms_d;   
    $tot_laba_ms_d=$tot_laba_ms_d+$laba_ms_d;
    $tot_as_th_d  =$tot_as_th_d+$hrg_beli_d;
    $tot_laba_th_d=$tot_laba_th_d+$databay['tot_laba'];
    $tot_jual_d=$tot_jual_d+($databay['tot_jual']-$databay['tot_disc']);
    
    $xthn=date('Y', strtotime($databay['tgl_jual']));
    $xbln=date('m', strtotime($databay['tgl_jual']));
    if($xthn==$endyear && $xbln==$endbln){
      $laba_th=$laba_th+($databay['tot_laba']-$laba_ms_d);
    }
}
unset($cekmas,$databay);

// total jenis barang
$cari0=mysqli_query($connect,"SELECT count(kd_brg) FROM beli_brg WHERE kd_toko='$kd_toko' AND stok_jual>0  group by kd_brg");
while($data0=mysqli_fetch_assoc($cari0)){
    $totitem++;
}
unset($cari1,$data1);

// cari stok barang
$cari1=mysqli_query($connect,"SELECT sum(stok_jual) AS jmlstok FROM beli_brg WHERE kd_toko='$kd_toko' group by kd_brg");
while($data1=mysqli_fetch_assoc($cari1)){
  if($data1['jmlstok']==0){
    $stok_0++;    
  } 
}
unset($cari1,$data1);

//mutasi online bulan berjalan
$wer=mysqli_query($connect,"SELECT * FROM beli_brg WHERE MONTH(tgl_fak)='$endbln' AND YEAR(tgl_fak)='$endyear' AND kd_toko='$kd_toko'");
while($rew=mysqli_fetch_assoc($wer)){
  $xcacah=konjumbrg2($rew['kd_sat'],$rew['kd_brg'],$connect)*$rew['jml_brg'];
  $nor=$rew['no_urut'];
  mysqli_query($connect,"UPDATE beli_brg SET no_item='$xcacah',no_item_del=0 WHERE no_urut='$nor'");
}
unset($wer,$rew,$xcacah,$nor);

$de = mysqli_query($connect,"SELECT mutasi_adj.* FROM mutasi_adj
    WHERE mutasi_adj.kd_toko='$kd_toko' AND MONTH(mutasi_adj.tgl_input) ='$endbln' AND YEAR(mutasi_adj.tgl_input) ='$endyear' AND UPPER(mutasi_adj.ket) LIKE '%LINE%' ORDER BY kd_brg ASC");
$jum =$xc=$c=0;$xkdbrg=$kdbr_a='';    
while($edi=mysqli_fetch_assoc($de)){
  $string=$x=$y='';$awal=$akhir=0;
  $string = strtolower($edi['ket']);
  $x      = strpos($string, "men");
  $awal   = str_replace(",",".",substr($string,strpos($string, ":")+1,$x-(strlen($string)+2)));
          
  $y      = strpos($string, ")");
  $akhir  = str_replace(",",".",substr($string,$x+10,$y-strlen($string)));
  $jum=($awal-$akhir);
  $xkdbrg=$edi['kd_brg'];

  //cari di beli_brg
  $crsq=mysqli_query($connect,"SELECT kd_sat,hrg_beli,kd_brg,disc1,disc2,ppn,jml_brg,no_urut,no_item FROM beli_brg WHERE kd_brg='$xkdbrg' AND kd_toko='$kd_toko' AND MONTH(tgl_fak)='$endbln' AND YEAR(tgl_fak)='$endyear' ORDER BY no_urut DESC");
  $noitem=$urut=$xsisa=0;
  while($drg=mysqli_fetch_assoc($crsq)){
    $noitem=$drg['no_item'];
    $urut=$drg['no_urut'];
    $c++;
    if($jum>0){
      $xsisa=$noitem-$jum;
      if($xsisa<=0){
        $sx=mysqli_query($connect,"SELECT no_urut,no_item,no_item_del FROM beli_brg WHERE no_urut='$urut'");
        $cch=0;
        if(mysqli_num_rows($sx)>0){
          $dc=mysqli_fetch_assoc($sx);
          $cch=$dc['no_item_del'];
        }
        unset($sx,$dc);
        $xc=$cch+$noitem*hitung_ol($drg['kd_sat'],$xkdbrg,$drg['disc1'],$drg['disc2'],$drg['ppn'],$drg['hrg_beli'],$connect); 
          mysqli_query($connect,"UPDATE beli_brg SET no_item='0',no_item_del='$xc'  WHERE no_urut='$urut'");
          $xsisa=$xsisa * -1;
          $jum=$xsisa;
      }else{
        if($noitem>0){
          $sx=mysqli_query($connect,"SELECT no_urut,no_item,no_item_del FROM beli_brg WHERE no_urut='$urut'");
          $cch=0;
          if(mysqli_num_rows($sx)>0){
            $dc=mysqli_fetch_assoc($sx);
            $cch=$dc['no_item_del'];
          }
          unset($sx,$dc);
          $xc=$cch+$jum*hitung_ol($drg['kd_sat'],$xkdbrg,$drg['disc1'],$drg['disc2'],$drg['ppn'],$drg['hrg_beli'],$connect); 
          mysqli_query($connect,"UPDATE beli_brg SET no_item='$xsisa',no_item_del='$xc'  WHERE no_urut='$urut'");
          if($jum==$noitem){$jum=0;} 
        } 
      }   
    }  
  }
  unset($crsq,$drg);  
}

function hitung_ol($kdsat_o,$kdbrg_o,$disc1_o,$disc2_o,$ppn_o,$hrg_o,$hub_o){
  $jumbrg = $disc1=$disc2=$hrg=0;
  $jumbrg = konjumbrg2($kdsat_o,$kdbrg_o,$hub_o);
  $hrg    = $hrg_o/$jumbrg ;
  if($disc1_o>0){
    $disc1  = $hrg*$disc1_o;
  }
  if($disc2_o>0){
    $disc2  = $hrg-$disc2_o;
  }
  $hrg=$hrg-($disc1+$disc2);
  if($ppn_o>0){
    $hrg  = $hrg*$ppn_o;
  }
  return $hrg;
} 
unset($jum,$xc,$xkdbrg,$kdbr_a,$de,$edi);

$wer=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_item_del FROM beli_brg 
 WHERE MONTH(beli_brg.tgl_fak)='$endbln' AND YEAR(beli_brg.tgl_fak)='$endyear' AND beli_brg.kd_toko='$kd_toko'");
$m_ol_t=$m_ol_h=0;
while($der=mysqli_fetch_assoc($wer)){
    $m_ol_t=$m_ol_t+$der['no_item_del'];
}
unset($wer,$der);

//cari pembelian bulan ini
$totbeli=0;
$cbeli=mysqli_query($connect,"SELECT * FROM beli_bay WHERE MONTH(tgl_fak)='$endbln' AND YEAR(tgl_fak)='$endyear' AND kd_toko='$kd_toko' AND ketbeli='PEMBELIAN BARANG'");
$totbeli_h=0;$totbeli_t=0;
while($dbeli=mysqli_fetch_assoc($cbeli)){
  if ($dbeli['ket']=='TUNAI'){
    $ppnbeli = $dbeli['ppn']/100;
    $discbeli= $dbeli['disc']/100;
    $belidisc= $dbeli['tot_beli']-($dbeli['tot_beli']*$discbeli);
    $totbeli_t = $totbeli_t+($belidisc+($belidisc*$ppnbeli));
  }else {
    $ppnbeli = $dbeli['ppn']/100;
    $discbeli= $dbeli['disc']/100;
    $belidisc= $dbeli['tot_beli']-($dbeli['tot_beli']*$discbeli);
    $totbeli_h = $totbeli_h+($belidisc+($belidisc*$ppnbeli));
  }
  $totbeli=(($totbeli_h-$m_ol_h)+($totbeli_t-$m_ol_t));
}

//stok limit 5
$cari2=mysqli_query($connect,"SELECT SUM(beli_brg.stok_jual) as jmlstoklim FROM beli_brg WHERE beli_brg.kd_toko='$kd_toko' GROUP BY beli_brg.kd_brg");
while($data2=mysqli_fetch_assoc($cari2)){
  if($data2['jmlstoklim']>0 AND $data2['jmlstoklim']<=5){
    $stok_limit++;  
  }
}
unset($cari2,$data2);
//

// Jual tempo
$jualcash=0;$jualtempo=0;
$jual=0;$laba=0;$profit=0;$disc=0;$jumlah=0;$ongkir=0;$laba_t_bln=0;
$cek=mysqli_query($connect,"SELECT SUM(tot_jual) AS totjual,SUM(tot_disc) AS totdisc FROM mas_jual 
  WHERE mas_jual.kd_toko='$kd_toko' AND MONTH(tgl_jual)='$endbln' AND YEAR(tgl_jual)='$endyear' AND kd_bayar='TEMPO'"); 
$data      = mysqli_fetch_assoc($cek);
$jualtempo = $data['totjual']-$data['totdisc'];
unset($data,$cek);

//jual tunai
$cek=mysqli_query($connect,"SELECT SUM(tot_jual) AS totjual,SUM(tot_disc) AS totdisc FROM mas_jual 
  WHERE mas_jual.kd_toko='$kd_toko' AND MONTH(tgl_jual)='$endbln' AND YEAR(tgl_jual)='$endyear' AND kd_bayar='TUNAI'"); 
$data      = mysqli_fetch_assoc($cek);
$jualcash  = $data['totjual']-$data['totdisc'];
unset($data,$cek);

//laba + ongkir
$cek=mysqli_query($connect,"SELECT SUM(tot_laba) AS totlaba,SUM(ongkir) AS totongkir FROM mas_jual 
  WHERE mas_jual.kd_toko='$kd_toko' AND MONTH(tgl_jual)='$endbln' AND YEAR(tgl_jual)='$endyear'"); 
$data      = mysqli_fetch_assoc($cek);
$laba      = $data['totlaba'];
$ongkir    = $data['totongkir'];
unset($data,$cek);

// piutang pelanggan bulan ini
$hut_pel=0;
$cek=mysqli_query($connect,"SELECT SUM(saldo_hutang) AS hut_pel FROM mas_jual where kd_toko='$kd_toko' AND ket_bayar='BELUM' ");
$data=mysqli_fetch_assoc($cek);
$hut_pel=$data['hut_pel'];
unset($cek,$data);

//hutang supplier
$hut_sup=0;$tot_byr=0;
$cek=mysqli_query($connect,"SELECT no_fak,tgl_tran,byr_hutang,saldo_hutang AS hut_sup FROM beli_bay where kd_toko='$kd_toko' AND ket='TEMPO'");
while($data=mysqli_fetch_assoc($cek)){
  $xthn=date('Y', strtotime($data['tgl_tran']));
  $xbln=date('m', strtotime($data['tgl_tran']));

  if($xthn==$endyear && $xbln==$endbln){
    $tot_byr=$tot_byr+$data['byr_hutang'];
  }  
  $hut_sup=$hut_sup+$data['hut_sup'];
}
unset($cek,$data);

//retur barang
$sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
WHERE MONTH(retur_jual.tgl_retur)='$endbln' AND YEAR(retur_jual.tgl_retur)='$endyear' AND retur_jual.kd_toko='$kd_toko' 
ORDER BY retur_jual.no_urutretur ASC ");

$totsub=$ret=$totlabaret=$totret=$rethi=0;
while($dret=mysqli_fetch_assoc($sqlret)){

  if($dret['discrp'] > 0){
    $ditem=$dret['discrp'];
  }else{
    $ditem=0;
  }
  if($dret['discitem'] > 0){
    $dnota=$dret['hrg_jual']*$dret['discitem']/100;
  }else{
    $dnota=0;
  }
  if ($dret['discvo']>0){
    $divo =$dret['hrg_jual']*($dret['discvo']/100);
  }else{
    $divo =0;  
  }     
  $hrgjl   = $dret['hrg_jual']-($ditem+$dnota+$divo);  
  $diskon  = gantitides($ditem+$dnota+$divo);
  $jmlsub  = $hrgjl*$dret['qty_retur']; 
  $totsub  = $totsub+$jmlsub;
  $labaret = ($hrgjl-$dret['hrg_beli'])*$dret['qty_retur']; 
  $totret = $totret+$jmlsub; // omset retur
  $totlabaret=$totlabaret+$labaret;  // labaretur
  if($dret['tgl_retur']==$tglhi){
    $rethi=$rethi+$jmlsub;
  }
}
unset($dret,$sqlret);

?>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     

<!-- Header -->
<div class="w3-padding-small w3-margin-bottom" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);z-index: 1;margin-top: -7px ">
  <h5><b><i class="fa fa-dashboard"></i> Dashboard &nbsp;<?=$kd_toko?></b></h5>
</div>  

  <!-- tampilkan pada layar kecil dan besar  -->
  <div class="w3-row-padding w3-margin-bottom w3-hide-medium">
    <div class="w3-quarter w3-margin-bottom">
      <div class="w3-container yz-theme-l1 w3-padding-16 w3-card-4" style="text-shadow: 2px 2px 3px black;border-radius: 10px">
        <div class="w3-left"><i class="fa fa-diamond w3-xxlarge"></i></div>
        <div class="w3-center">
          <h5>Total Asset Barang</h5> 
        </div>
        <div class="w3-clear"></div>
        <div class="w3-center">
          <h4>Rp. <?=gantitides($tot)?></h4>
        </div>
        <?php if ($oto==2) { ?>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Modal Sendiri
          Rp. <?=gantitides($tot-$hut_sup)?> &nbsp;
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Modal Hutang
          Rp. <?=gantitides($hut_sup)?>
        </div>
        <?php } ?>
      </div>
    </div>

    <?php if ($oto==2) { ?>
    <div class="w3-quarter w3-margin-bottom">
      <div class="w3-container yz-theme-d4 w3-text-white w3-padding-16 w3-card-4" style="text-shadow: 2px 2px 5px black;border-radius: 10px">
        <div class="w3-left"><i class="fa fa-shopping-cart w3-xxlarge"></i></div>
        <div class="w3-center">
           <h5>Penjualan Bulan Ini</h5>  
        </div>
        <div class="w3-clear"></div>
        <div class="w3-center">
          <h4>Rp. <?=gantitides($jualcash+$jualtempo-$totret)?></h4>
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Tunai
          Rp. <?=gantitides($jualcash-$totret)?> &nbsp;
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Tempo
          Rp. <?=gantitides($jualtempo)?>
        </div>
      </div>
    </div>
    <?php } ?>

    <div class="w3-quarter w3-margin-bottom">
      <div class="w3-container yz-theme-d2 w3-padding-16 w3-card-4" style="text-shadow: 2px 2px 5px black;border-radius: 10px;">
        <div class="w3-left"><i class="fa fa-cart-arrow-down w3-xxlarge"></i></div>
        <div class="w3-center">
          <h5>Pembelian Bulan Ini</h5>
        </div>
        <div class="w3-clear"></div>
        <div class="w3-center">
          <h4>Rp. <?=gantitides($totbeli)?></h4>
        </div>
        <?php if ($oto==2) { ?>
        <div class="w3-left " style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Tunai
          Rp. <?=gantitides($totbeli_t-$m_ol_t)?> &nbsp;
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Tempo
          Rp. <?=gantitides($totbeli_h-$m_ol_h)?> 
        </div>  
        <?php }?>

      </div>
     </div>
     
    <?php if ($oto==2) { ?>
    <div class="w3-quarter">
      <div class="w3-container yz-theme-d1 w3-padding-16 w3-card-4" style="text-shadow: 2px 2px 5px black;border-radius: 10px;">
        <div class="w3-left"><i class="fa fa-database w3-xxlarge" ></i></div>
        <div class="w3-center">
          <h5 >Laba Bulan Ini</h5>
        </div>
        <div class="w3-clear"></div>

        <div class="w3-center">
          <h4>Rp. <?=gantitides(($laba+$laba_pi_bini)-($laba_th+$totlabaret))?></h4>
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Laba ditahan
          Rp. <?=gantitides($laba_th)?>
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Laba piutang lalu
          Rp. <?=gantitides($laba_pi_bini)?>
        </div>
      </div>
    </div>
    <?php } ?>

  </div>

  <!-- tampilkan pada layar medium  -->
  <div class="w3-row-padding w3-margin-bottom w3-hide-large w3-hide-small">
    <div class="w3-half w3-margin-bottom">
      <div class="w3-container yz-theme-l1 w3-padding-16 w3-card-4" style="text-shadow: 2px 2px 3px black;border-radius: 10px">
        <div class="w3-left"><i class="fa fa-diamond w3-xlarge"></i></div>
        <div class="w3-center">
          <h5>Total Asset Barang</h5> 
        </div>
        <div class="w3-clear"></div>
        <div class="w3-center">
          <h4>Rp. <?=gantitides($tot)?></h4>
        </div>
        <?php if ($oto==2) { ?>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Modal Sendiri
          Rp. <?=gantitides($tot-$hut_sup)?> &nbsp;
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Modal Hutang
          Rp. <?=gantitides($hut_sup)?>
        </div>
        <?php } ?>
      </div>
    </div>

    <?php if ($oto==2) { ?>
    <div class="w3-half w3-margin-bottom">
      <div class="w3-container yz-theme-d1 w3-padding-16 w3-card-4" style="text-shadow: 2px 2px 5px black;border-radius: 10px;">
        <div class="w3-left"><i class="fa fa-database w3-xxlarge" ></i></div>
        <div class="w3-center">
          <h5 >Laba & Piutang Bulan Ini</h5>
        </div>
        <div class="w3-clear"></div>
        <div class="w3-center">
          <h4>Rp. <?=gantitides(($laba+$laba_pi_bini)-($laba_th+$totlabaret))?></h4>
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Laba ditahan
          Rp. <?=gantitides($laba_th)?>
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Laba piutang lalu
          Rp. <?=gantitides($laba_pi_bini)?>
        </div> 
      </div>
    </div>
    <?php } ?>

    <div class="w3-half">
      <div class="w3-container yz-theme-d2 w3-padding-16 w3-card-4 w3-margin-bottom" style="text-shadow: 2px 2px 5px black;border-radius: 10px;">
        <div class="w3-left"><i class="fa fa-cart-arrow-down w3-xxlarge"></i></div>
        <div class="w3-center">
          <h5>Pembelian Bulan Ini</h5>
        </div>
        <div class="w3-clear"></div>
        
        <div class="w3-center">
          <h4>Rp. <?=gantitides($totbeli)?></h4>
        </div>
        <?php if ($oto==2) { ?>
        <div class="w3-left " style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Tunai
          Rp. <?=gantitides($totbeli_t-$m_ol_t)?> &nbsp;
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Tempo
          Rp. <?=gantitides($totbeli_h-$m_ol_h)?> 
        </div>  
        <?php }?>

      </div>
    </div>

    <?php if ($oto==2){ ?>
    <div class="w3-half ">
      <div class="w3-container yz-theme-d4 w3-text-white w3-padding-16 w3-card-4 w3-margin-bottom" style="text-shadow: 2px 2px 5px black;border-radius: 10px">
        <div class="w3-left"><i class="fa fa-shopping-cart w3-xxlarge"></i></div>
        <div class="w3-left"><i class="fa fa-shopping-cart w3-xxlarge"></i></div>
        <div class="w3-center">
           <h5>Penjualan Bulan Ini</h5>  
        </div>
        <div class="w3-clear"></div>
        <div class="w3-center">
          <h4>Rp. <?=gantitides($jualcash+$jualtempo-$totret)?></h4>
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Tunai
          Rp. <?=gantitides($jualcash-$totret)?> &nbsp;
        </div>
        <div class="w3-left" style="color:cyan">
          <i class="fa fa-tag"></i>&nbsp;&nbsp;Tempo
          Rp. <?=gantitides($jualtempo)?>
        </div>
      </div>
    </div>
    <?php } ?>

  </div>
  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-col s12 m12 l6 w3-margin-bottom" >
        <div class="input-group">
          <h5><i class="fa fa-star" style="color: orange"></i><b> Top 10 Best Seller </b></h5> &nbsp; <span><button id="btn-top10" class="btn yz-theme-d1 w3-border-black w3-left" style="margin-top:-5px"><i class="fa fa-caret-down"></i></button></span>
        </div>
        <?php 
        //TOP FIVE SERING LAKU
        $btop=date('m');
        $cek=mysqli_query($connect,"SELECT dum_jual.kd_brg,dum_jual.nm_brg,count(nm_brg) as jmlbrg FROM dum_jual 
          WHERE month(dum_jual.tgl_jual)='$endbln' AND year(dum_jual.tgl_jual)='$endyear' AND kd_toko='$kd_toko'
          GROUP BY dum_jual.nm_brg ORDER BY COUNT(*) DESC LIMIT 10");
         ?> 
        <div class="w3-row">
          <div class="w3-col l6 s1 m6">
            &nbsp;
          </div>
          <div class="w3-col l6 s11 m6">
            <div id="tabtop10" class="w3-container w3-card-4"
              style="display:none;position:absolute;border:1px solid white;border-radius:7px; background-color:rgba(0, 0, 0, 0.7)">
              <form action="dasbor_top10.php" method="post" target="blank">
                <select name="pilihbulan" id="pilihbulan" class="form-control w3-margin-top w3-margin-bottom w3-border-blue" style="background-color: transparent;color:white" >
                  <option value="1">JANUARI</option>
                  <option value="2">FEBRUARI</option>
                  <option value="3">MARET</option>
                  <option value="4">APRIL</option>
                  <option value="5">MEI</option>
                  <option value="6">JUNI</option>
                  <option value="7">JULI</option>
                  <option value="8">AGUSTUS</option>
                  <option value="9">SEPTEMBER</option>
                  <option value="10">OKTOBER</option>
                  <option value="11">NOPEMBER</option>
                  <option value="12">DESEMBER</option>
                </select>
             
                <input id="pilihtahun" name="pilihtahun" class="w3-margin-bottom w3-text-white w3-border-blue form-control" type="number" min="2023" value="2023" style="background-color:transparent;">

                <select name="ctkpil" id="ctkpil" class="form-control w3-margin-top w3-margin-bottom w3-border-blue" style="background-color: transparent;color:white">
                  <?php $nctk=0; $ds=mysqli_query($connect,"SELECT * FROM bag_brg ORDER BY no_urut");
                  while($qs=mysqli_fetch_assoc($ds)){
                    $nctk++;
                    if($nctk==1){ ?>
                       <option value="0">SEMUA</option>
                      <?php 
                    } ?>
                    <option value="<?=$qs['no_urut']?>"><?=$qs['nm_bag']?></option>
                    <?php
                  }
                  ?>
                </select>
                
                <input id="pilihst" name="pilihst" class="w3-margin-bottom w3-text-white w3-border-blue form-control" type="number" min="0"  style="background-color:transparent;" placeholder="Limit stok">
                
                <button class="btn btn-md w3-border-white w3-margin-bottom form-control w3-hover" style="background-color:transparent;color:yellow" type="submit" onclick="document.getElementById('tabtop10').style.display='none'"><i class="fa fa-print"></i>&ensp;Cetak</button>

              </form>
              <!-- <script type="text/javascript">
                $(document).ready(function() {
                  $('#form-input').submit(function() {
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function(data) {
                            $('#viewcek').html(data);
                        }
                    })
                    return false;
                  });
                })
              </script>  -->
            </div>          
          </div>
        </div>
        <script>
          document.getElementById("pilihbulan").value=<?=date('m',strtotime($_SESSION['tgl_set']))?>;
          document.getElementById("pilihtahun").value=<?=date('Y',strtotime($_SESSION['tgl_set']))?>;
          $(document).ready(function(){
            $("#btn-top10").click(function(){
              $("#tabtop10").slideToggle("fast");
            });
          });      
        </script>  
        <table class="w3-table w3-striped w3-white hrf_arial" style="font-size: 11pt">
          <tr class="w3-border yz-theme-l3" style="font-size: 11pt">
            <!-- <td style="text-align: center">Kode Barang</td> -->
            <td style="text-align: center">Nama Barang</td>
            <td style="text-align: center">Jml</td>
            <td style="text-align: center">Stok</td>
            <td style="text-align: center">Rank</td>
          </tr>
          <?php $a=0; 
            while ($data=mysqli_fetch_assoc($cek)){
             $kdbrg=$data['kd_brg'];
             $a++; $stok=0;
             $c=mysqli_query($connect,"SELECT sum(stok_jual) as jmls FROM beli_brg WHERE kd_brg='$kdbrg'");
             $d=mysqli_fetch_assoc($c);
             $stok=round($d['jmls'],0);
             
             $ex=explode(";",carisatkecil2($kdbrg,$connect));
             $sat=strtolower(ceknmkem2($ex[0],$connect));
             unset($c,$d,$ex);
            ?>
            <tr style="font-size: 10pt">
              <!-- <td><?=$data['kd_brg']?></td> -->
              <td><?=$data['nm_brg']?></td>
              <td style="text-align:right"><?=$data['jmlbrg']?></td>
              <td style="text-align:right"><?=$stok.' '.$sat?></td>
              <td style="text-align: center"><i class="fa fa-star" style="color: orange"></i> <?=$a?></td> 
            </tr>
          <?php } 
          unset($cek,$data);?>
        </table>  
        
      </div>

      <div class="w3-col s12 m12 l6">
        <h5><b>Catatan <?= ucwords(strtolower(nm_harini($_SESSION['tgl_set']))).', '.gantitgl($_SESSION['tgl_set']) ?></b></h5>
        <table class="w3-table w3-striped w3-white" style="font-size: 11pt">
          <tr>
            <td><i class="fa fa-tasks text-primary w3-large" ></i></td>
            <td>Jumlah Jenis Barang</td>
            <td class="w3-right"><i><?=gantitides(round($totitem,0)).' item'?></i></td>
          </tr>
          <tr>
            <td  style="cursor:pointer"><i id="btnstok0" class="fa fa-tasks text-primary w3-large" ></i>
              <div id="pilstok0" class="w3-container w3-card-4 mt-2" style="display:none;position:absolute;border:1px solid 
                white;border-radius:7px; background-color:rgba(0, 0, 0, 0.7);z-index:100">
                <form action="dasbor_stok_0_3.php" method="post" target="_blank" >
                  <div class="text-light mt-3">Cek stok barang 2 bulan terakhir</div>
                  <select name="ctkpil0" id="ctkpil0" class="form-control w3-margin-top w3-margin-bottom w3-border-blue" style="background-color: transparent;color:white"><?php 
                    $nc=0; $dw=mysqli_query($connect,"SELECT * FROM bag_brg ORDER BY no_urut");
                    while($qw=mysqli_fetch_assoc($dw)){
                      $nc++;
                      if($nc==1){ ?>
                        <option value="0">SEMUA</option> <?php 
                      } ?>
                      <option value="<?=$qw['no_urut']?>"><?=$qw['nm_bag']?></option> <?php
                    } ?>
                  </select>
                  <input id="pilihst0" name="pilihst0" class="w3-margin-bottom w3-text-white w3-border-blue form-control" type="number" min="0" value="0"  style="background-color:transparent;" placeholder="Limit stok">

                  <button class="btn btn-md w3-border-white w3-margin-bottom form-control w3-hover" style="background-color:transparent;color:yellow" type="submit" onclick="document.getElementById('pilstok0').style.display='none'"><i class="fa fa-print"></i>&ensp;Cetak</button>
                </form>
                <?php unset($dw,$qw);?>
              </div>
              <script>
                $(document).ready(function(){
                  $("#btnstok0").click(function(){
                    $("#pilstok0").slideToggle("fast");
                    $("#p-beli").slideUp("fast");
                  });
                });
              </script>
            </td>
            <td>Stok 0 ( kosong )</td>
            <td class="w3-right"><i onmouseenter="this.style.fontSize='13pt';this.style.color='blue'" onmouseleave="this.style.fontSize='11pt';this.style.color='black'" style="cursor: pointer" onclick="document.getElementById('forminfostok1').style.display='block';caristok0(1,true);"><?=gantitides($stok_0).' item'?></i></td>
          </tr>
          <!-- <tr>
            <td><i class="fa fa-bell text-primary w3-large" ></i></td>
            <td>Cek Jumlah Pembelian Barang</td>
            <td>Stok Limit <=5 </td>
            <td class="w3-right"><i onmouseenter="this.style.fontSize='13pt';this.style.color='blue'" onmouseleave="this.style.fontSize='11pt';this.style.color='black'" style="cursor: pointer"
            onclick="document.getElementById('forminfostok2').style.display='block';caristok5(1,true);"><?=gantitides($stok_limit).' item'?></i></td>
          </tr> -->
          <?php if ($oto==2){ ?>
            <tr>
            <td><i class="fa fa-desktop w3-large text-primary" ></i></td>
            <td style="color: black">Hutang Supplier </td>
            <td class="w3-right" style="color: black"><i><?='Rp.'.gantitides($hut_sup)?></i></td> 
            </tr>
            <tr>
            <td><i class="fa fa-desktop w3-large text-primary" ></i></td>
            <td style="color: black">Piutang Pelanggan </td>
            <td class="w3-right" style="color: black"><i><?='Rp.'.gantitides($hut_pel)?></i></td> 
            </tr> 
          <?php }?>  
          <tr>
            <td style="cursor:pointer"><i id="btn-p-beli" class="fa fa-cubes w3-text-orange w3-large" ></i> 
              <div id="p-beli" class="w3-container w3-card-4 mt-2" style="display:none;position:absolute;border:1px solid 
                white;border-radius:7px; background-color:rgba(0, 0, 0, 0.7);z-index:100">
                <form action="dasbor_cek_beli.php" method="post" target="_blank" >
                  <div class="text-light mt-3">Cek Jumlah Pembelian Barang</div>
                  <select name="bag_p_beli" id="bag_p_beli" class="form-control w3-margin-top w3-margin-bottom w3-border-blue" style="background-color: transparent;color:white"><?php 
                    $nc=0; $dw=mysqli_query($connect,"SELECT * FROM bag_brg ORDER BY no_urut");
                    while($qw=mysqli_fetch_assoc($dw)){
                      $nc++;
                      if($nc==1){ ?>
                        <option value="0">SEMUA</option> <?php 
                      } ?>
                      <option value="<?=$qw['no_urut']?>"><?=$qw['nm_bag']?></option> <?php
                    } ?>
                  </select>

                  <input id="tgl_p_beli1" name="tgl_p_beli1" class="w3-margin-bottom w3-text-white w3-border-blue form-control" type="date" style="background-color:transparent;" placeholder="Tanggal">

                  <input id="tgl_p_beli2" name="tgl_p_beli2" class="w3-margin-bottom w3-text-white w3-border-blue form-control" type="date" style="background-color:transparent;" placeholder="Tanggal">

                  <button class="btn btn-md w3-border-white w3-margin-bottom form-control w3-hover" style="background-color:transparent;color:yellow" type="submit" onclick="document.getElementById('pilbeli').style.display='none'"><i class="fa fa-print"></i>&ensp;Cetak</button>
                </form>
                <?php unset($dw,$qw);?>
              </div>
              <script>
                $(document).ready(function(){
                  $("#btn-p-beli").click(function(){
                    $("#p-beli").slideToggle("fast");
                    $("#pilstok0").slideUp("fast");
                  });
                });
              </script>
            </td>
            <td>Cek Pembelian Barang</td>
          </tr>
          <tr>
            <?php if ($ongkir>0 && $oto==2) { ?>
            <td><i class="fa fa-truck w3-large text-primary" ></i></td>
            <td style="color: black">Jasa Kirim </td>
            <td class="w3-right" style="color: black"><i><?='Rp.'.gantitides($ongkir)?></i></td> 
            <?php }?>         
          </tr>
          
          

          <!-- Penjualan hari ini -->
          <?php
          $cekj=mysqli_query($connect,"SELECT sum(mas_jual.tot_jual) as totjual,SUM(mas_jual.tot_disc) as totdisc,toko.nm_toko FROM mas_jual LEFT JOIN toko ON mas_jual.kd_toko=toko.kd_toko 
          WHERE mas_jual.tgl_jual='$tglhi' GROUP BY mas_jual.kd_toko
          ORDER BY toko.kd_toko");
          ?>
          
            <?php while($datj=mysqli_fetch_assoc($cekj)){ ?>
            <tr>  
            <td><i class="fa fa-shopping-basket w3-large text-success" ></i></td>
            <td style="color: black">Penjualan Toko <?=$datj['nm_toko']?> </td>
            <td class="w3-right" style="color: black"><i><?='Rp.'.gantitides(($datj['totjual']-$datj['totdisc'])-$rethi)?></i></td> 
            </tr>
            <?php } ?>
          
         
        </table>
      </div>
    </div>
  </div>
  <hr>

<div class="w3-container">
  <?php 
  $tgl=getdate(date("U"));
  $thn=$tgl['year'];
  $tglhi=date('Y-m-d');
   
  if ($oto==2) { ?>
  <div class="col-sm-8 offset-sm-2 text-center w3-margin-bottom">
    Grafik Omset & Laba Penjualan Tahun <?=$thn?>
    <canvas id="canvas" class="w3-card-2 w3-container"></canvas>  
  </div>  
  <?php 
  }
  ?>
  <script>
    var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var color = Chart.helpers.color;
    var chartData = {
      labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Juni", "Juli", "Agst", "Sep", "Okt", "Nov", "Des"],
      datasets: [{
        type: 'line',
        label: 'Laba',
        borderColor: window.chartColors.orange,
        borderWidth: 2,
        fill: true,
        data: [
          <?php 
            $tglawal1=$thn.'-'.'01'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'01'.'-'.'31'; 
            $jml_bayar1=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar1;
           ?>, 
           <?php
            $tglawal1=$thn.'-'.'02'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'02'.'-'.'30'; 
            $jml_bayar2=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar2;
          ?>,
          <?php    
            $tglawal1=$thn.'-'.'03'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'03'.'-'.'31'; 
            $jml_bayar3=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar3;
          ?>,
          <?php   
            $tglawal1=$thn.'-'.'04'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'04'.'-'.'30'; 
            $jml_bayar4=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar4;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'05'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'05'.'-'.'31'; 
            $jml_bayar5=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar5;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'06'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'06'.'-'.'30'; 
            $jml_bayar6=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar6;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'07'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'07'.'-'.'31'; 
            $jml_bayar7=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar7;
          ?>,  
          <?php   
            $tglawal1=$thn.'-'.'08'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'08'.'-'.'31'; 
            $jml_bayar8=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar8;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'09'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'09'.'-'.'30'; 
            $jml_bayar9=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar9;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'10'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'10'.'-'.'31'; 
            $jml_bayar10=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar10;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'11'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'11'.'-'.'30'; 
            $jml_bayar11=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar11;
          ?>,
          <?php   
            $tglawal1=$thn.'-'.'12'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'12'.'-'.'31'; 
            $jml_bayar12=caridata2($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar12;
          ?>
        ]
      }, {
        type: 'bar',
        label: 'Penjualan',
        backgroundColor: [
          '#3949a3'
          ],
        data: [
          <?php 
            $tglawal1=$thn.'-'.'01'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'01'.'-'.'31'; 
            $jml_bayar1=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar1;
           ?>, 
           <?php
            $tglawal1=$thn.'-'.'02'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'02'.'-'.'30'; 
            $jml_bayar2=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar2;
          ?>,
          <?php    
            $tglawal1=$thn.'-'.'03'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'03'.'-'.'31'; 
            $jml_bayar3=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar3;
          ?>,
          <?php   
            $tglawal1=$thn.'-'.'04'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'04'.'-'.'30'; 
            $jml_bayar4=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar4;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'05'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'05'.'-'.'31'; 
            $jml_bayar5=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar5;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'06'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'06'.'-'.'30'; 
            $jml_bayar6=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar6;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'07'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'07'.'-'.'31'; 
            $jml_bayar7=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar7;
          ?>,  
          <?php   
            $tglawal1=$thn.'-'.'08'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'08'.'-'.'31'; 
            $jml_bayar8=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar8;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'09'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'09'.'-'.'30'; 
            $jml_bayar9=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar9;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'10'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'10'.'-'.'31'; 
            $jml_bayar10=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar10;
          ?>, 
          <?php   
            $tglawal1=$thn.'-'.'11'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'11'.'-'.'30'; 
            $jml_bayar11=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar11;
          ?>,
          <?php   
            $tglawal1=$thn.'-'.'12'.'-'.'1'; 
            $tglakhir1=$thn.'-'.'12'.'-'.'31'; 
            $jml_bayar12=caridata($tglawal1,$tglakhir1,$kd_toko,$connect);
            echo $jml_bayar12;
          ?>
        ],
        borderColor: 'white',
        borderWidth: 2
      }]

    }; 

    window.onload = function() {
      var canvasElement = document.getElementById('canvas');
      if (canvasElement) {
        var ctx = canvasElement.getContext('2d');
        window.myMixedChart = new Chart(ctx, {
          type: 'bar',
          data: chartData,
          options: {
            responsive: true,
            title: {
              display: true,
              text: 'Chart.js Combo Bar Line Chart'
            },
            tooltips: {
              mode: 'index',
              intersect: true
            }
          }
        });
      }
   };   
  </script>

  <div id="forminfostok1" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0);">
    <div class="w3-modal-content w3-card-4 w3-animate-right" style="border-style: ridge;border-color: white;border-radius: 10px;background-color:rgba(1, 1, 1, 0.8);">
      <div style="color:yellow;font-size: 14px;padding:4px;border-radius:7px;border-bottom: 1px solid white">
        &nbsp; <i class="fa fa-desktop"></i>&nbsp;List Stok Barang Kosong
        <span id="brandclose" onclick="document.getElementById('forminfostok1').style.display='none';" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
      </div>
      <div id="viewstok0"><script>caristok0(1,true);</script></div>
    </div>  
  </div>      

  <div id="forminfostok2" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0);">
    <div class="w3-modal-content w3-card-4 w3-animate-left" style="border-style: ridge;border-color: white;border-radius: 10px;background-color:rgba(1, 1, 1, 0.8);">
      <div style="color:yellow;font-size: 14px;padding:4px;border-radius:7px;border-bottom: 1px solid white">
        &nbsp; <i class="fa fa-desktop"></i>&nbsp;List Stok Barang <= 5 
        <span id="brandclose" onclick="document.getElementById('forminfostok2').style.display='none';" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
      </div>
      <div id="viewstok5"><script>caristok5(1,true);</script></div>
    </div>  
  </div>      
<?php 

function caridata($tgl1,$tgl2,$kdtoko,$hub){
  $sqlret=mysqli_query($hub,"SELECT *  FROM retur_jual 
  LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut  
  WHERE retur_jual.tgl_retur BETWEEN '$tgl1' AND '$tgl2' AND retur_jual.kd_toko='$kdtoko' ");
  
  $totsub=0;$ret=0;$jmlsub=0;
  while($dret=mysqli_fetch_assoc($sqlret)){
    
    if($dret['discrp'] > 0){
      $ditem=$dret['discrp'];
    }else{
      $ditem=0;
    }
    if($dret['discitem'] > 0){
      $dnota=$dret['hrg_jual']*$dret['discitem']/100;
    }else{
      $dnota=0;
    }
    if ($dret['discvo']>0){
      $divo =$dret['hrg_jual']*($dret['discvo']/100);
    }else{
      $divo =0;  
    }     
    $hrgjl=$dret['hrg_jual']-($ditem+$dnota+$divo);  
    $jmlsub=round($hrgjl*$dret['qty_brg'],0); 
    $totsub=$totsub+$jmlsub;
  }  

  unset($dtret,$sqlret);


  $des = mysqli_query($hub,"SELECT SUM(tot_jual-tot_disc) AS jum from mas_jual where tgl_jual between '$tgl1' and '$tgl2' and kd_toko='$kdtoko'");
  $totx=mysqli_fetch_assoc($des);
  if ($totx['jum']==0){
    $jml_byr=0;
  }else {
    $jml_byr=$totx['jum']-$totsub;   
  }
  unset($des,$totx);
  return $jml_byr;
}

function caridata2($tgl1,$tgl2,$kdtoko,$hub){
  $jml_byr=0;
  // cari laba ditahan
  
  $cekmas=mysqli_query($hub,"SELECT * FROM mas_jual where kd_bayar='TEMPO' AND kd_toko='$kdtoko' AND tgl_jual BETWEEN '$tgl1' AND '$tgl2' ORDER BY mas_jual.no_urut ASC");
  $tot_as_th_d=0;$tot_as_ms_d=0;$tot_laba_th_d=0;$tot_laba_ms_d=0;$hrg_beli_d=0;$uang_ms_d=0;$aset_ms_d=0;$laba_ms_d=0;$tot_jual_d=0;$laba_th=0;
  while($databay=mysqli_fetch_assoc($cekmas)){
      $hrg_beli_d=($databay['tot_jual']-$databay['tot_disc'])-$databay['tot_laba'];
      $uang_ms_d =($databay['tot_jual']-$databay['tot_disc']-$databay['saldo_hutang']);
      if ($uang_ms_d<=$hrg_beli_d) {
        $aset_ms_d    =$uang_ms_d; 
        $laba_ms_d    =0;
      }else {
        $aset_ms_d    =$hrg_beli_d; 
        $laba_ms_d    =$uang_ms_d-$hrg_beli_d;      
      }
      $tot_as_ms_d  =$tot_as_ms_d+$aset_ms_d;   
      $tot_laba_ms_d=$tot_laba_ms_d+$laba_ms_d;
      $tot_as_th_d  =$tot_as_th_d+$hrg_beli_d;
      $tot_laba_th_d=$tot_laba_th_d+$databay['tot_laba'];
      $tot_jual_d=$tot_jual_d+($databay['tot_jual']-$databay['tot_disc']);
      $laba_th=$laba_th+($databay['tot_laba']-$laba_ms_d);
      
  }
  unset($ceks,$databays);  
  
  $cekmas2=mysqli_query($hub,"SELECT SUM(laba) AS labahi FROM mas_jual_hutang 
          WHERE mas_jual_hutang.tgl_tran BETWEEN '$tgl1' AND '$tgl2' AND mas_jual_hutang.byr_hutang>0 AND mas_jual_hutang.tgl_jual < '$tgl1' AND kd_toko='$kdtoko'");
  if (mysqli_num_rows($cekmas2)>0){
    $data=mysqli_fetch_assoc($cekmas2);
    $laba_pi_bini=$data['labahi'];
  }else{
    $laba_pi_bini=0;
  }         
  unset($data,$cekmas2);
  
  $dtret=mysqli_query($hub,"SELECT retur_jual.no_urutjual,SUM(dum_jual.laba) as totret FROM retur_jual 
  LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut  
  WHERE retur_jual.tgl_retur BETWEEN '$tgl1' AND '$tgl2' AND retur_jual.kd_toko='$kdtoko' ");

  $totlr=mysqli_fetch_assoc($dtret);
  $jmlret=$totlr['totret'];
  unset($dtret,$totlr);

  $des = mysqli_query($hub,"SELECT SUM(tot_laba) AS jum from mas_jual where tgl_jual between '$tgl1' and '$tgl2' and kd_toko='$kdtoko'");
  $totx=mysqli_fetch_assoc($des);
  if ($totx['jum']==0){
    $jml_byr=0;
  }else {
    //$jml_byr=$totx['jum']-($tot_laba_th_d-$tot_laba_ms_d)-$jmlret;   
    $jml_byr=($totx['jum']+$laba_pi_bini)-($laba_th+$jmlret);
  }
  unset($des,$totx);
  return $jml_byr;
}
?>

</div>