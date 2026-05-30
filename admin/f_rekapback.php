<link rel="stylesheet" href="../assets/css/paper.css">
<style>
	th
    {
        text-align: center;
        border: solid 1px #113300;
        /*background: #EEFFEE;*/
    }

    td
    {
        border: solid 1px #113300;
        background: white;
        font-size: 8pt;
        border-left: none;
        border-right: none;
        border-top: none;
        /*border-style:dotted; */
    }
    .sheet {
      overflow: visible;
      height: auto !important;
     
    }
    tbody {page-break-before:always;}
    @page { size: F4 landscape }

    #content {
    display: table;
    }

    #pageFooter {
        display: table-footer-group;
    }

    #pageFooter:after {
        counter-increment: page;
        content: counter(page);
    }

    @page {
      @bottom-right {
        content: counter(page) ' of ' counter(pages);
      }
    }

</style>
  <?php 
    session_start();    
    include 'config.php';
    $connect=opendtcek();
    ini_set('memory_limit', '1024M'); // or you could use 1G  
    
  //***proses hitung rekap
  $endbln   = $_POST['blnrek'];
  $endyear  = $_POST['thnrek'];
  $bulan    = nm_bln($_POST['blnrek']); 
  $cektoko=mysqli_query($connect,"SELECT kd_toko from toko ORDER BY kd_toko ASC");
  while ($datko=mysqli_fetch_assoc($cektoko)) {
    $tokocari=$datko['kd_toko']; 
    $hrg_asal=0;$disc1=0;$disc2=0;$hrg_belidisc=0;$hrg_belidiscx=0;$tot=0;$toko='';
    $cek=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.kd_toko,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.ppn,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3 FROM beli_brg
              LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
              WHERE  beli_brg.stok_jual > 0 AND beli_brg.kd_toko='$tokocari' 
              ORDER by beli_brg.kd_toko ASC");
    //hitung asset         
    while($data=mysqli_fetch_assoc($cek)){
      
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
    } // while asset
    unset($data,$cek);
    
    //cari pembelian bulan ini
    $cbeli=mysqli_query($connect,"SELECT * FROM beli_bay WHERE MONTH(tgl_fak)='$endbln' AND YEAR(tgl_fak)='$endyear' AND kd_toko='$tokocari'");
    $totbeli_h=0;$totbeli_t=0;$totbeli=0;
    while($dbeli=mysqli_fetch_assoc($cbeli)){
      if ($dbeli['ket']=='TUNAI'){
        $ppnbeli = $dbeli['ppn']/100;
        $discbeli= $dbeli['disc']/100;
        $belidisc= $dbeli['tot_beli']-($dbeli['tot_beli']*$discbeli);
        //$belidisc= $belidisc+($belidisc*$ppnbeli);
        $totbeli_t = $totbeli_t+($belidisc+($belidisc*$ppnbeli));
      }else {
        $ppnbeli = $dbeli['ppn']/100;
        $discbeli= $dbeli['disc']/100;
        $belidisc= $dbeli['tot_beli']-($dbeli['tot_beli']*$discbeli);
        //$belidisc= $belidisc+($belidisc*$ppnbeli);
        $totbeli_h = $totbeli_h+($belidisc+($belidisc*$ppnbeli));
      }
      $totbeli=($totbeli_h+$totbeli_t);
    }
    unset($cbeli,$dbeli); 

    // Total asset ditahan seluruhnya
    $cekmas=mysqli_query($connect,"SELECT * FROM mas_jual where ket_bayar='BELUM' AND kd_toko='$tokocari' ORDER BY mas_jual.no_urut ASC");
    $tot_as_th_d=0;$tot_as_ms_d=0;$tot_laba_th_d=0;$tot_laba_ms_d=0;$hrg_beli_d=0;$uang_ms_d=0;$aset_ms_d=0;$laba_ms_d=0;$tot_jual_d=0;$laba_th=0;
    while($databay=mysqli_fetch_assoc($cekmas)){
      $xthn=date('Y', strtotime($databay['tgl_jual']));
      $xbln=date('m', strtotime($databay['tgl_jual']));
        $hrg_beli_d=($databay['tot_jual']-$databay['tot_disc'])-$databay['tot_laba'];
        $uang_ms_d =($databay['tot_jual']-$databay['tot_disc']-$databay['saldo_hutang']);
        if ($uang_ms_d<=$hrg_beli_d) {
           $aset_ms_d  = $uang_ms_d; 
           $laba_ms_d  = 0;
        }else {
           $aset_ms_d  = $hrg_beli_d; 
           $laba_ms_d  = $uang_ms_d-$hrg_beli_d;      
        }
        $tot_as_ms_d   = $tot_as_ms_d+$aset_ms_d;   
        $tot_laba_ms_d = $tot_laba_ms_d+$laba_ms_d;
        $tot_as_th_d   = $tot_as_th_d+$hrg_beli_d;
        $tot_laba_th_d = $tot_laba_th_d+$databay['tot_laba'];
        $tot_jual_d    = $tot_jual_d+($databay['tot_jual']-$databay['tot_disc']);

        
        if($xthn==$endyear && $xbln==$endbln){
          $laba_th=$laba_th+($databay['tot_laba']-$laba_ms_d);
         
        }
    } // while asset ditahan
    unset($cekmas,$databay);    

    //laba piutang masuk bulan ini
    $tglcari=$endyear.'-'.$endbln.'-'.'01';
    $cekmas2=mysqli_query($connect,"SELECT SUM(laba) AS labahi FROM mas_jual_hutang 
            WHERE MONTH(mas_jual_hutang.tgl_tran)='$endbln' AND YEAR(mas_jual_hutang.tgl_tran)='$endyear' 
            AND mas_jual_hutang.byr_hutang>0 AND mas_jual_hutang.tgl_jual < '$tglcari' AND kd_toko='$tokocari'");
    if (mysqli_num_rows($cekmas2)>0){
      $data=mysqli_fetch_assoc($cekmas2);
      $laba_pi_bini=$data['labahi'];
    }else{
      $laba_pi_bini=0;
    }         
    unset($data,$cekmas2);
   
    
    // piutang pelanggan bulan ini
    $hut_pel=0;
    $cekpi=mysqli_query($connect,"SELECT SUM(tot_jual-tot_disc) AS hut_pel FROM mas_jual where kd_toko='$tokocari' AND MONTH(tgl_jual)='$endbln' AND YEAR(tgl_jual)='$endyear' AND ket_bayar='BELUM' ");
    $datapi=mysqli_fetch_assoc($cekpi);
    $hut_pel=$datapi['hut_pel']; // total piutang
    unset($cekpi);

    //hutang supplier
    $hut_sup=0;$tot_byr=0;
    $ceksup=mysqli_query($connect,"SELECT no_fak,tgl_tran,byr_hutang,saldo_hutang AS hut_sup FROM beli_bay where kd_toko='$tokocari' AND ket='TEMPO'");
    while($datasup=mysqli_fetch_assoc($ceksup)){
      $xthn=date('Y', strtotime($datasup['tgl_tran']));
      $xbln=date('m', strtotime($datasup['tgl_tran']));

      if($xthn==$endyear && $xbln==$endbln){
        $tot_byr=$tot_byr+$datasup['byr_hutang'];
      }  
      $hut_sup=$hut_sup+$datasup['hut_sup'];//total hutang supplier
    }
    unset($ceksup,$datasup);

    // OMSET BULAN INI
    $cekom=mysqli_query($connect,"SELECT * FROM mas_jual 
      where mas_jual.kd_toko='$tokocari' AND MONTH(tgl_jual)='$endbln' AND YEAR(tgl_jual)='$endyear'"); 
    $jual=0;$laba=0;$profit=0;$disc=0;$jumlah=0;$ongkir=0;
    while($dataom=mysqli_fetch_assoc($cekom)){
        $disc=$disc+$dataom['tot_disc'];
        $jumlah=$jumlah+$dataom['tot_jual'];
        $jual=$jumlah-$disc; // total omset 
        $laba=$laba+$dataom['tot_laba'];
        $ongkir=$ongkir+$dataom['ongkir'];  
    }
    unset($dataom,$cekom);

    //Biaya operasinal pertoko
    $totbiop=0;
    $cekbiop=mysqli_query($connect,"SELECT * FROM biaya_ops 
      WHERE kd_toko='$tokocari' AND MONTH(tgl_biaya)='$endbln' AND YEAR(tgl_biaya)='$endyear' ORDER BY kd_toko"); 
    while ($dtbiop=mysqli_fetch_assoc($cekbiop)){
        $totbiop=$totbiop+$dtbiop['nominal']; // total biaya operasional
    }
    unset($cekbiop,$dtbiop);
    
    //retur barang laba
    $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
    LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
    LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
    LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
    WHERE MONTH(retur_jual.tgl_retur)='$endbln' AND YEAR(retur_jual.tgl_retur)='$endyear' AND retur_jual.kd_toko='$tokocari' 
    ORDER BY retur_jual.no_urutretur ASC ");

    $totsub=0;$ret=0;$totlabaret=0;$totret=0; 
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
      $totret  = $totret+$jmlsub; // omset retur
      $totlabaret=$totlabaret+$labaret;  // labaretur
    }
    unset($dret,$sqlret);
    
    //update ke rekap
    $totup=0;$totsipi=0;$totom=0;$totlab=0;$totjual=0;
    $totup   = ($tot+$tot_as_th_d)-$tot_as_ms_d;// asset
    $totsipi = ($tot_as_th_d+$tot_laba_th_d)-($tot_as_ms_d+$tot_laba_ms_d);//sisa piutang
    $totlab  = ($laba+$laba_pi_bini)-($tot_laba_th_d-$tot_laba_ms_d)-$totlabaret;
    $totjual = $jual-$totret;
    if($totjual==0){
      $totlab=0;
    }
    $dtrek   = mysqli_query($connect,"SELECT kd_toko,asset FROM rekap WHERE kd_toko='$tokocari'");
    if(mysqli_num_rows($dtrek)>=1){
      //update
      mysqli_query($connect,"UPDATE rekap SET kd_toko='$tokocari',asset='$tot',piutang='$totsipi',hutang='$hut_sup',omset='$totjual',laba='$totlab',ongkir='$ongkir',biaya='$totbiop',beli='$totbeli' WHERE kd_toko='$tokocari'");
    }else {
      //insert data
      mysqli_query($connect,"INSERT INTO rekap VALUES('','$tokocari','$tot','$totsipi','$hut_sup','$totjual','$totlab','$ongkir','$totbiop','$totbeli') ");
    }  
    //----

  } // while toko 
  unset($dtrek);

  //biaya global
  $totbiglo=0;
  $cekbiop=mysqli_query($connect,"SELECT * FROM biaya_ops 
    WHERE kd_toko='GLOBAL' AND MONTH(tgl_biaya)='$endbln' AND YEAR(tgl_biaya)='$endyear' ORDER BY kd_toko"); 
  while ($dtbiop=mysqli_fetch_assoc($cekbiop)){
      $totbiglo=$totbiglo+$dtbiop['nominal']; // total biaya operasional
  }
  unset($cekbiop,$dtbiop);
  //------
 
  $cekrekap=mysqli_query($connect,"SELECT rekap.*,toko.nm_toko FROM rekap 
    LEFT JOIN toko ON rekap.kd_toko=toko.kd_toko
    ORDER BY kd_toko");
  $no=0;$rek_asset=0;$rek_piutang=0;$rek_hutang=0;$rek_omset=0;$rek_laba=0;$rek_ongkir=0;$rek_biaya=0;$rek_beli=0;
  ?>  

<body class="F4 landscape">      
    <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
          <table id="content" cellspacing="0" style="width: 100%; font-size: 10pt;page-break-before: always">
            <thead>
                <tr> <td colspan="12" style="text-align: left;font-size: 11pt"><b>REKAPITULASI SEMUA TOKO BULAN <?=strtoupper($bulan).' '.$endyear?> </b></td></tr>   
                <tr style="background-color: lightgrey;">
                    <th style="width:2%;padding:3px;">NO</th>
                    <th >TOKO</th>
                    <th style="width: 10%">ASSET</th>
                    <th style="width: 10%">PIUTANG</th>
                    <th style="width: 10%">HUTANG</th>
                    <th style="width: 10%">PEMBELIAN</th>
                    <th style="width: 10%">PENJUALAN</th>
                    <th style="width: 10%">LABA</th>
                    <th style="width: 10%">ONGKIR</th>
                    <th style="width: 10%">BIAYA</th>
                </tr>       
            </thead>
                
              <?php while($dtrekap=mysqli_fetch_assoc($cekrekap)){ 
                $no++;  
                $rek_asset=$rek_asset+$dtrekap['asset'];
                $rek_piutang=$rek_piutang+$dtrekap['piutang'];
                $rek_hutang=$rek_hutang+$dtrekap['hutang'];
                $rek_omset=$rek_omset+$dtrekap['omset'];
                $rek_laba=$rek_laba+$dtrekap['laba'];
                $rek_ongkir=$rek_ongkir+$dtrekap['ongkir'];
                $rek_biaya=$rek_biaya+$dtrekap['biaya'];
                $rek_beli=$rek_beli+$dtrekap['beli'];
              ?>
               
              <tr>
                 <td style="padding:2px;font-size: 10pt;text-align: right;border-left: 1px solid"><?=$no?>&nbsp;</td>
                 <td style="font-size: 10pt;text-align: center"><?=$dtrekap['nm_toko']?></td>
                 <td style="font-size: 10pt;text-align: right"><?=gantitides($dtrekap['asset'])?>&nbsp;</td>
                 <td style="font-size: 10pt;text-align: right"><?=gantitides($dtrekap['piutang'])?>&nbsp;</td>
                 <td style="font-size: 10pt;text-align: right"><?=gantitides($dtrekap['hutang'])?>&nbsp;</td>
                 <td style="font-size: 10pt;text-align: right"><?=gantitides($dtrekap['beli'])?>&nbsp;</td>
                 <td style="font-size: 10pt;text-align: right"><?=gantitides($dtrekap['omset'])?>&nbsp;</td>
                 <td style="font-size: 10pt;text-align: right"><?=gantitides($dtrekap['laba'])?>&nbsp;</td>
                 <td style="font-size: 10pt;text-align: right"><?=gantitides($dtrekap['ongkir'])?>&nbsp;</td>
                 <td style="font-size: 10pt;text-align: right;border-right: 1px solid"><?=gantitides($dtrekap['biaya'])?>&nbsp;</td>
              </tr>   
              <?php } ?> 
              <tr style="background-color: lightgrey">
                <th colspan="2" style="text-align: center;border-right:1px solid;padding:3px;"> TOTAL</th>
                <th style="text-align: right;"><?= gantitides($rek_asset)?>&nbsp;</th>
                <th style="text-align: right;"><?= gantitides($rek_piutang)?>&nbsp;</th>
                <th style="text-align: right;"><?= gantitides($rek_hutang)?>&nbsp;</th>
                <th style="text-align: right;"><?= gantitides($rek_beli)?>&nbsp;</th>
                <th style="text-align: right;"><?= gantitides($rek_omset)?>&nbsp;</th>
                <th style="text-align: right;"><?= gantitides($rek_laba)?>&nbsp;</th>
                <th style="text-align: right;"><?= gantitides($rek_ongkir)?>&nbsp;</th>
                <th style="text-align: right;"><?= gantitides($rek_biaya)?>&nbsp;</th>
              </tr>

              <!-- CATATATAN -->
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr style="font-size: 10pt;">
                <th colspan="3" style="border: none;border-bottom:1px solid;text-align: center">CATATAN</th>
              </tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr style="font-size: 10pt;">
                <th colspan="2" style="border: none;text-align: right">Total Biaya Toko Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($rek_biaya)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="2" style="border: none;text-align: right">Total Biaya Global Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($totbiglo)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="2" style="border: none;text-align: right">Jumlah Biaya Rp.</th>
                <th style="border: none;text-align: right;border-top:1px dotted"><?=gantitides($totbiglo+$rek_biaya)?></th>
              </tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr style="font-size: 10pt;">
                <th colspan="2" style="border: none;text-align: right">Total Pendapatan Toko Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($rek_laba)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="2" style="border: none;text-align: right">Total Jasa Kirim Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($rek_ongkir)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="2" style="border: none;text-align: right">Jumlah Pendapatan Rp.</th>
                <th style="border: none;text-align: right;border-top:1px dotted"><?=gantitides($rek_ongkir+$rek_laba)?></th>
              </tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr style="font-size: 10pt;">
                <th colspan="2" style="border: none;text-align: right;">PENDAPATAN USAHA Rp.</th>
                <th style="border: none;text-align: right;font-size: 11pt"><?=gantitides(($rek_ongkir+$rek_laba)-($rek_biaya+$totbiglo))?></th>
              </tr>
              <!-- Bagian penjualan -->
              <tr><td style="border: none">&nbsp;</td></tr>
              <tr style="font-size: 10pt;">
                 <th align="middle" colspan="3" style="border: none;border-bottom:1px solid black">BAGIAN PENJUALAN</th>
              </tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
                <?php
                $totbags=$totrts=$totsm=0;
                $sqlbags=mysqli_query($connect,"SELECT * FROM bag_brg ORDER by no_urut ASC");
                while($dtbag=mysqli_fetch_assoc($sqlbags)){
                  $id_bag=$dtbag['no_urut'];
                  $nm_bag=$dtbag['nm_bag'];
                  $x=explode(";",cartbag($id_bag,$endbln,$endyear,$connect));
                  $totbags=$x[0];
                  $totrts=$x[1]; 
                  $totsm=$totsm+($totbags-$totrts);
                  if($totbags>0){
                  ?>
                  <tr style="font-weight:bold;">
                    <td colspan="2" style="border:none;text-align:right;font-size:10pt">Penjualan &emsp13;<?=ucwords(strtolower($nm_bag))?>&nbsp;Rp.</td>
                    <td style="border:none;text-align:right;font-size:10pt"><?=gantitides($totbags-$totrts)?></td>
                  </tr>
                  <?php
                  }
                }
                ?>
                <tr style="font-weight:bold;">
                  <td colspan="2" style="border:none;text-align:right;font-size:10pt">Total Bagian Penjualan &nbsp;Rp.</td>
                  <td style="border:none;border-top:1px solid grey;text-align:right;font-size:10pt"><?=gantitides($totsm)?></td>
                </tr>
          </table>    
      </div>  
    </section>      
    
</body>       

<?php 
 function cartbag($id_bag,$tgl1,$tgl2,$hub){
  $stls=mysqli_query($hub,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.id_bag,bag_brg.nm_bag 
  FROM dum_jual
  LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
  WHERE MONTH(dum_jual.tgl_jual)='$tgl1' AND YEAR(dum_jual.tgl_jual)='$tgl2' AND dum_jual.id_bag='$id_bag'
  ORDER BY dum_jual.tgl_jual,dum_jual.no_fakjual ASC");

  $srets  = mysqli_query($hub,"SELECT retur_jual.*,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.id_bag,bag_brg.nm_bag 
  FROM retur_jual 
  LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
  LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
  WHERE MONTH(retur_jual.tgl_retur)='$tgl1' AND YEAR(retur_jual.tgl_retur)='$tgl2' AND dum_jual.id_bag='$id_bag'
  ORDER BY retur_jual.no_urutretur ASC ");

  $jumbag=$tot=0;
  while($dtl=mysqli_fetch_assoc($stls)){
    if ($dtl['discitem'] > 0){
      $xditem=$dtl['hrg_jual']*($dtl['discitem']/100);
    }else{
      $xditem=0;
    }

    if ($dtl['discrp']>0){
      $xdirp=$dtl['discrp'];
    }else{
      $xdirp=0;   
    }
    
    if($dtl['discvo']>0){
      $xdivo=$dtl['hrg_jual']*($dtl['discvo']/100);
    }else{
      $xdivo=0;  
    }
    $jumbag=($dtl['hrg_jual']-($xditem+$xdirp+$xdivo))*$dtl['qty_brg'];
    $tot=$tot+$jumbag;
  }

  $hrgjl=$jmlret=$totret=0;
  while($dtr=mysqli_fetch_assoc($srets)){
    if($dtr['discrp'] > 0){
      $ditem=$dtr['discrp'];
    }else{
      $ditem=0;
    }
    if($dtr['discitem'] > 0){
      $dnota=$dtr['hrg_jual']*$dtr['discitem']/100;
    }else{
      $dnota=0;
    }
    if ($dtr['discvo']>0){
      $divo_r =$dtr['hrg_jual']*($dtr['discvo']/100);
    }else{
      $divo_r =0;  
    }     
    $hrgjl=$dtr['hrg_jual']-($ditem+$dnota+$divo_r);  
    $jmlret=$hrgjl*$dtr['qty_retur']; 
    $totret=$totret+$jmlret;
  }
  mysqli_free_result($stls);mysqli_free_result($srets);

  //laba piutang masuk bulan ini
  // $tglcari=$tgl2.'-'.$tgl1.'-'.'01';
  // $cekmas2=mysqli_query($hub,"SELECT SUM(laba) AS labahi FROM mas_jual_hutang 
  //         WHERE MONTH(mas_jual_hutang.tgl_tran)='$tgl1' AND YEAR(mas_jual_hutang.tgl_tran)='$tgl2' 
  //         AND mas_jual_hutang.byr_hutang>0 AND mas_jual_hutang.tgl_jual < '$tglcari' AND kd_toko='$tokocari'");
  // if (mysqli_num_rows($cekmas2)>0){
  //   $data=mysqli_fetch_assoc($cekmas2);
  //   $laba_pi_bini=$data['labahi'];
  // }else{
  //   $laba_pi_bini=0;
  // }         
  // unset($data,$cekmas2);
  return $tot.';'.$totret;
}

mysqli_close($connect); 
?>
<script>
window.print();
</script>

