<link rel="stylesheet" href="../assets/css/paper.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
<link rel="stylesheet" href="../assets/css/blue-themes.css">
<style>
  body,h2,h3,h4,h5,h6 {font-family: Times,Helvetica}
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
    $pesan    = explode(';',$_GET['pesan']);
    $tgl1     = $pesan[0];
    $tgl2     = $pesan[1];
    $kd_toko  = $_SESSION['id_toko'];
    $nm_toko  = "";
    $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
    $sql=mysqli_fetch_assoc($cektoko);
    $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
    $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
    unset($cektoko,$sql); 
    $a=0;
  ?>  

<body class="F4 landscape">      

    <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
          <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
            <thead>
                <tr><td colspan="12" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="12" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="12" style="text-align: left;font-size: 10pt"><b>Gross Profit penjualan barang per item dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?></b></td></tr>   
                <tr class="yz-theme-l1">
                    <th class="w3-padding-small" style="width:5%;">NO</th>
                    <th style="width:8%">TGL. JUAL</th>
                    <th style="width:12%">NO.NOTA</th>
                    <th style="width:19%">NAMA BARANG</th>
                    <th style="width:9%">HARGA BELI</th>
                    <th style="width:9%">HARGA JUAL</th>
                    <th style="width:4%">QTY</th>
                    <th style="width:4%">SAT</th>
                    <th style="width:4%">DISC</th>
                    <th style="width:10%">SUB TOTAL</th>
                    <th style="width:9%">GROSS PROFIT</th>
                    <th style="width:5%">GPM</th>
                </tr>       
            </thead>

            <?php          
               $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.laba,dum_jual.discvo,dum_jual.ket as ketret,kemas.nm_sat1,mas_jual.ket_bayar,mas_jual.saldo_hutang FROM dum_jual
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual
                WHERE dum_jual.kd_toko='$kd_toko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false ORDER BY dum_jual.no_fakjual,dum_jual.no_urut ASC");

               if(mysqli_num_rows($cekjual)>=1){
                    // cari laba ditahan
                    $tot_as_th_d=0;$tot_as_ms_d=0;$tot_laba_th_d=0;$tot_laba_ms_d=0;$hrg_beli_d=0;$uang_ms_d=0;$aset_ms_d=0;$laba_ms_d=0;$tot_jual_d=0;$laba_th=0;
                    $ceks=mysqli_query($connect,"SELECT * FROM mas_jual where mas_jual.kd_toko='$kd_toko' AND  mas_jual.tgl_jual>='$tgl1' AND mas_jual.tgl_jual<='$tgl2' AND ket_bayar='BELUM' ORDER BY mas_jual.no_urut ASC");
                    while($databays=mysqli_fetch_assoc($ceks)){
                      $hrg_beli_d=($databays['tot_jual']-$databays['tot_disc'])-$databays['tot_laba'];
                      $uang_ms_d =($databays['tot_jual']-$databays['tot_disc']-$databays['saldo_hutang']);
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
                      $tot_laba_th_d=$tot_laba_th_d+$databays['tot_laba'];
                      $tot_jual_d=$tot_jual_d+($databays['tot_jual']-$databays['tot_disc']);      
                      //echo $databays['no_fakjual'].'<br>';
                    }
                    unset($ceks,$databays);  

                    // cari laba piutang penjualan bulan lalu, masuk bulan ini
                    $x        = explode('-', $_SESSION['tgl_set']);
                    $endbln   = $x[1];
                    $endyear  = $x[0];
                    $tglhi    = $_SESSION['tgl_set'];
                    $tglcari  = $endyear.'-'.$endbln.'-'.'01';
                    $labamsk  = 0;

                    $cek=mysqli_query($connect,"SELECT sum(mas_jual_hutang.laba) as labamsk
                    FROM mas_jual_hutang 
                    WHERE MONTH(mas_jual_hutang.tgl_tran)='$endbln' AND YEAR(mas_jual_hutang.tgl_tran)='$endyear' 
                    AND mas_jual_hutang.byr_hutang>0 AND mas_jual_hutang.tgl_jual < '$tglcari' AND mas_jual_hutang.kd_toko='$kd_toko'
                    ORDER BY mas_jual_hutang.tgl_tran,mas_jual_hutang.no_fakjual ASC");
                    if (mysqli_num_rows($cek)>=1){
                      $dtcek=mysqli_fetch_assoc($cek);
                      $labamsk=$dtcek['labamsk'];
                    }
                    unset($cek,$dtcek);

                    $no=0;$totbeli1=0;$totbeli2=0;$jumlah=0.00;$disc=0;$subtot1=0;$subtot2=0;$nethrgjual=0; $tot_beli_pi=0;$tot_jum_pi=0;$tot_laba_pi=0;
                    $tothrgjual=0;$tothrgbeli=0;$diskon=0;
                    while ($sqljual=mysqli_fetch_assoc($cekjual)) {
                      $no++;
                      if ($sqljual['discitem']=='0' && $sqljual['discrp']=='0' ) {
                        $jumlah=$sqljual['hrg_jual']*$sqljual['qty_brg'];
                        $nethrgjual=$sqljual['hrg_jual'];
                        $diskon=0;
                        if ($sqljual['ket_bayar']=='BELUM'){ 
                          $tot_jum_pi=$tot_jum_pi+$jumlah;
                        }  
                      } 
                      if ($sqljual['discitem'] > 0 && $sqljual['discrp']==0 ) {
                        $disc=$sqljual['hrg_jual']*($sqljual['discitem']/100);
                        $jumlah=($sqljual['hrg_jual']-$disc)*$sqljual['qty_brg'];
                        $nethrgjual=$sqljual['hrg_jual']-$disc;
                        $jual=($sqljual['hrg_jual']-$disc);
                        $diskon=gantitides($disc);
                      }
                      if ($sqljual['discitem'] == 0 && $sqljual['discrp'] > 0 ) {
                        $jumlah=($sqljual['hrg_jual']-$sqljual['discrp'])*$sqljual['qty_brg'];
                        $nethrgjual=$sqljual['hrg_jual']-$sqljual['discrp'];
                        $diskon=gantitides($sqljual['discrp']);
                      }  
                      if ($sqljual['discitem'] > 0 && $sqljual['discrp'] > 0 ) {
                        // $ditem=$sqljual['hrg_jual']-$sqljual['discrp'];
                        // $disc=$ditem-($ditem*($sqljual['discitem']/100));
                        $disc=$sqljual['hrg_jual']-($sqljual['hrg_jual']*($sqljual['discitem']/100)+$sqljual['discrp']);
                        $jumlah    =$disc*$sqljual['qty_brg'];
                        $nethrgjual=$disc;
                        $diskon    =gantitides($sqljual['discrp']+$disc);
                      } 
                      if($sqljual['discvo'] > 0){
                        $ditem     =$sqljual['discrp'];
                        $disc      =$sqljual['hrg_jual']*($sqljual['discitem']/100);
                        $voucher    =$sqljual['hrg_jual']*($sqljual['discvo']/100);
                        $jumlah    =($sqljual['hrg_jual']-($ditem+$disc+$voucher)) *$sqljual['qty_brg'];
                        $nethrgjual=$sqljual['hrg_jual']-($ditem+$disc+$voucher);
                        $diskon    =gantitides($ditem+$disc+$voucher);
                      }

                      if ($sqljual['ket_bayar']=='BELUM'){ 
                        $tot_jum_pi=$tot_jum_pi+$jumlah;
                      }
                      $totbeli1=$totbeli1+$sqljual['laba'];
                      $totbeli2=$totbeli2+$jumlah;
                      $tothrgjual=$tothrgjual+$sqljual['hrg_jual'];
                      $tothrgbeli=$tothrgbeli+$sqljual['hrg_beli'];
                       
                          if ($sqljual['ket_bayar']=='BELUM'){ 
                            
                            ?>
                            <tr style="color: blue;"> 
                            <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></b></td>
                            <td style="text-align:center;font-size: 8pt"><b><?php echo gantitgl($sqljual['tgl_jual']);?></td>
                            <td style="text-align:center;font-size: 8pt"><b><?php echo $sqljual['no_fakjual']?></b></td>
                            <td style="text-align:left;font-size: 8pt;">&nbsp;<b><?php echo $sqljual['nm_brg'].'(TEMPO)'; ?></b></td>
                            <td style="text-align:right;font-size: 8pt"><b><?php echo gantitides($sqljual['hrg_beli']); ?>&nbsp;</b></td>
                            <td style="text-align:right;font-size: 8pt"><b><?php echo gantitides($sqljual['hrg_jual']); ?>&nbsp;</b></td>
                            <td style="text-align:center;font-size: 8pt"><b><?php echo gantitides($sqljual['qty_brg']) ?></b></td>
                            <td style="text-align:center;font-size: 8pt"><b><?php echo $sqljual['nm_sat1']; ?></b></td>
                            <td style="text-align:right;font-size: 8pt"><b><?php echo $diskon; ?>&nbsp;</b></td>
                            <td style="text-align:right;font-size: 8pt;"><b><?php echo gantitides(round($jumlah,0)); ?>&nbsp;</b></td>
                            <td style="text-align:right;font-size: 8pt;"><b><?php echo gantitides($sqljual['laba']) ?>&nbsp;</b></td>
                            <?php if ($nethrgjual==0) { ?>
                                <?php if ($sqljual['laba']==0) { ?>
                                <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><b><?php echo '0.00'.'%'; ?>&nbsp;</b></td>
                                <?php }else{ ?>
                                <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><b><?php echo round((($sqljual['hrg_beli']*$sqljual['qty_brg'])/$sqljual['laba'])*100,2).'%'; ?>&nbsp;</b></td>
                                <?php } ?> 
                                
                            <?php } else { ?>
                                <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><b><?php echo round((($sqljual['laba']/$sqljual['qty_brg'])/$nethrgjual)*100,2).'%'; ?>&nbsp;</b></td>
                            <?php } ?>
                                </tr>       
                            <?php 
                          } else { ?>
                            <tr>
                            <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqljual['tgl_jual']);?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['no_fakjual']?></td>
                            <td style="text-align:left;font-size: 8pt;">&nbsp;<?php echo $sqljual['nm_brg']; ?></td>
                            <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqljual['hrg_beli']); ?>&nbsp;</td>
                            <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqljual['hrg_jual']); ?>&nbsp;</td>
                            <td style="text-align:center;font-size: 8pt"><?php echo gantitides($sqljual['qty_brg']) ?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['nm_sat1']; ?></td>
                            <td style="text-align:right;font-size: 8pt"><?php echo $diskon; ?>&nbsp;</td>
                            <td style="text-align:right;font-size: 8pt;"><?php echo gantitides(round($jumlah,0)); ?>&nbsp;</td>
                            <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($sqljual['laba']) ?>&nbsp;</td>
                            <?php if ($nethrgjual==0) { ?>
                                <?php if ($sqljual['laba']==0) { ?>
                                  <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo '0.00'.'%'; ?>&nbsp;</td>
                                <?php }else{ ?>
                                  <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo round((($sqljual['hrg_beli']*$sqljual['qty_brg'])/$sqljual['laba'])*100,2).'%'; ?>&nbsp;</td>
                                <?php } ?> 
                                
                            <?php } else { ?>
                                <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo round((($sqljual['laba']/$sqljual['qty_brg'])/$nethrgjual)*100,2).'%'; ?>&nbsp;</td>
                            <?php } 
                          }
                        ?>
                           </tr>
                        <?php     
                    } // while

                    // untuk data retur
                    $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
                    LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
                    LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
                    LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                    WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' 
                    ORDER BY retur_jual.no_urutretur ASC ");

                    $totsub=0;$ret=0;$totlabaret=0;
                    while($dret=mysqli_fetch_assoc($sqlret)){
                      $no++;
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
                      $hrgjl  = $dret['hrg_jual']-($ditem+$dnota+$divo);  
                      $diskon = gantitides($ditem+$dnota+$divo);
                      $jmlsub = round($hrgjl*$dret['qty_retur'],0); 
                      $totsub = $totsub+$jmlsub;
                      $labaret= ($hrgjl-$dret['hrg_beli'])*$dret['qty_retur']; 
                      if ($dret['saldo_hutang']==0){
                        $ret=$ret+$jmlsub;
                      }
                      $totlabaret=$totlabaret+$labaret;
                      ?>
                        <tr style="color:red;font-weight:bold">
                          <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($dret['tgl_retur']);?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo $dret['no_fakjual'];?></td>
                          <td style="text-align:left;font-size: 8pt"><?php echo $dret['nm_brg']?><b style="color:red">(RETUR)</b></td>
                          <td style="text-align:right;font-size: 8pt"><?php echo gantitides($dret['hrg_beli']); ?></td>
                          <td style="text-align:right;font-size: 8pt"><?php echo gantitides($dret['hrg_jual']); ?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo $dret['qty_retur']?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo $dret['nm_sat1'] ?></td>
                          <td style="text-align:right;font-size: 8pt"><?php echo $diskon; ?></td>
                          <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jmlsub); ?>&nbsp;</td>
                          <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($dret['laba']); ?>&nbsp;</td>
                          <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo '0,00' ?>&nbsp;</td>
                        </tr>
                      <?php
                    }
                    ?>
                    <tr cellspacing="2" class="yz-theme-l1">
                        <th class="w3-padding-small" colspan=4 align="center" style="font-size: 10pt"><b>T O T A L &nbsp; P E N J U A L A N &nbsp; B A R A N G</b></th>
                        <th style="text-align:right;font-size: 10pt"><b><?php echo gantitides($tothrgbeli) ?></b></th>
                        <th style="text-align:right;font-size: 10pt"><b><?php echo gantitides($tothrgjual) ?></b></th>
                        <th style="text-align:right;font-size: 10pt" colspan="3"></th>
                        <th style="text-align:right;font-size: 10pt"><b><?php echo gantitides($totbeli2) ?></b></th>
                        <th style="text-align:right;font-size: 10pt"><b><?php echo gantitides($totbeli1) ?></b></th>
                        <?php if ($totbeli2==0){?>
                          <th style="text-align:right;font-size: 10pt"><?=gantitides(($totbeli1/$tothrgbeli)*100).'%'?><b></th>  
                          <?php } else { ?>
                          <th style="text-align:right;font-size: 10pt"><?=gantitides(($totbeli1/$totbeli2)*100).'%'?><b></th>      
                          <?php } ?>  
                      </tr>
                      <tr><td style="border: none"></td></tr>
                      <tr><td style="border: none"></td></tr>
                      <tr ><td style="border: none"></td></tr>
                      <tr ><th colspan="3" style="border-top: none;border-left: none;border-right: none;border-bottom: 1px solid ;font-size: 10pt;text-align: left">CATATAN</th></tr>
                      <tr><td style="border: none"></td></tr>
                      <tr><td style="border: none"></td></tr>
                      <tr ><td style="border: none"></td></tr>
                      
                      <tr style="font-size: 9pt">
                        <th colspan="2" style="border: none;text-align: right">Retur Barang = Rp.</th>
                        <th style="border: none;text-align: right"><?=gantitides($totlabaret)?></th>
                      </tr>
                      <tr style="font-size: 9pt">
                        <th colspan="2" style="border: none;text-align: right">Laba Piutang = Rp.</th>
                        <th style="border: none;text-align: right"><?=gantitides($labamsk)?></th>
                      </tr>

                      <tr style="font-size: 9pt">
                        <th colspan="2" style="border: none;text-align: right">Laba Ditahan = Rp.</th>
                        <th style="border: none;text-align: right"><?=gantitides($tot_laba_th_d-$tot_laba_ms_d)?></th>
                      </tr>
                      
                      <tr style="font-size: 9pt">
                        <th colspan="2" style="border: none;text-align: right">Laba Dibukukan = Rp.</th>
                        <th style="border: none;text-align: right"><?=gantitides($totbeli1-($tot_laba_th_d-$tot_laba_ms_d)-$totlabaret+$labamsk)?></th>
                      </tr>

                      <?php
                } // if  
            ?>  
          </table>    
      </div>  
      <div class="w3-row w3-margin-top">
        <div class="w3-col w3-center">
          <button id="printPageButton" class="w3-btn w3-green" onclick="window.print();">Cetak PDF</button>      
        </div>
      </div>
    </section>      
    
</body>       
<?php mysqli_close($connect); ?>
<script>
window.print();
</script>

