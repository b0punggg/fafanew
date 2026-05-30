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
    @media print {
      #printPageButton {
        display: none;
      }
    }
</style>
  <?php 
    include 'config.php';
    session_start();   
    $connect=opendtcek();
    
    ini_set('memory_limit', '1024M'); // or you could use 1G
    $tgl1       = $_POST['tglvoucher1'];
    $tgl2       = $_POST['tglvoucher2'];
    $pilvoucher = $_POST['pilihvoucher'];
    $piltoko    = $_POST['kd_tokovoucher'];

    // echo $piltoko;
    $kd_toko  = $_SESSION['id_toko'];
    $nm_toko  = "";
    $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
    $sql=mysqli_fetch_assoc($cektoko);
    $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
    $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
    unset($cektoko,$sql); 
    $a=0;$ket='';
    $cr_bay='ALL';
    $hspan=11;  
    $ket='TUNAI / TEMPO';
    if ($pilvoucher=='alldata'){
      $cekvoucher=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.laba,dum_jual.kd_toko,dum_jual.discvo,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.saldo_hutang FROM dum_jual
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
                WHERE dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false
                ORDER BY dum_jual.tgl_jual,dum_jual.no_fakjual  ASC");

      $sqlret  = mysqli_query($connect,"SELECT retur_jual.*,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang FROM retur_jual 
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2'
      ORDER BY retur_jual.no_urutretur ASC ");          
    }else {
      $cekvoucher=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.laba,dum_jual.kd_toko,dum_jual.discvo,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.saldo_hutang FROM dum_jual
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
                WHERE dum_jual.kd_toko='$piltoko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false ORDER BY dum_jual.tgl_jual,dum_jual.no_fakjual  ASC");

      $sqlret  = mysqli_query($connect,"SELECT retur_jual.*,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang FROM retur_jual 
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND retur_jual.kd_toko='$piltoko' 
      ORDER BY retur_jual.no_urutretur ASC ");          
    }
  ?>  

<body class="F4 ">      

    <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
          <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;">
            <thead>
                <tr><td colspan="<?=$hspan?>" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="<?=$hspan?>" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr><td colspan="<?=$hspan?>" style="text-align: left;font-size: 9pt"><b>Laporan pemberian voucher ke pelanggan dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?></b></td></tr>   
                <tr class="yz-theme-l2">
                    <th style="width:3%;">NO</th>
                    <th style="width:8%">TANGGAL</th>
                    <th style="width:8%">KD.TOKO</th>
                    <th style="width:15%">NO.NOTA</th>
                    <th >NAMA BARANG</th>
                    <th style="width:5%">QTY</th>
                    <th style="width:9%">JML HRG JUAL</th> 
                    <th style="width:8%">JML DISC NOTA</th> 
                    <th style="width:8%">JML DISC ITEM</th> 
                    <th style="width:8%">JML VOUCHER</th> 
                    <th style="width:11%">TOTAL DISC</th>
                </tr>       
            </thead>
            <?php
                       
                // totalan retur baarang
                $totsub=0;$ret=0;$totdisc_r=0;$totdivo_r=0;$divo_r=0;$diskon_r=0;$jmlsub=0;$totdivor=0;$totdnota=0;$totditemr=0;
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
                    $divo_r =$dret['hrg_jual']*($dret['discvo']/100);
                  }else{
                    $divo_r =0;  
                  }     
                  $hrgjl=$dret['hrg_jual']-($ditem+$dnota+$divo_r);  
                  $jmlsub=round($hrgjl*$dret['qty_retur'],0); 
                  $totsub=$totsub+$jmlsub;
                  
                  if ($dret['saldo_hutang']<=$jmlsub ){
                    $ret=$ret+$jmlsub;
                    $totditemr=$totditemr+($ditem*$dret['qty_retur']);
                    $totdnota=$totdnota+($dnota*$dret['qty_retur']);
                    $totdivor=$totdivor+($divo_r*$dret['qty_retur']);
                  }
                  
                }  
                //  
                if(mysqli_num_rows($cekvoucher)>=1){
                    $no=0;$totdivo=0;$totdirp=0;$totditem=0;$jmldivo=0;$jmldirp=0;$jmlditem=0;$totjum=0;
                    while ($sqljual=mysqli_fetch_assoc($cekvoucher)) {
                      if($sqljual['discitem']>0 || $sqljual['discrp']>0 || $sqljual['discvo']>0 ){
                        $no++;                   
                        if ($sqljual['discitem'] > 0){
                          $xditem=$sqljual['hrg_jual']*($sqljual['discitem']/100);
                        }else{
                          $xditem=0;
                        }
      
                        if ($sqljual['discrp']>0){
                          $xdirp=$sqljual['discrp'];
                        }else{
                          $xdirp=0;   
                        }
                        
                        if($sqljual['discvo']>0){
                          $xdivo=$sqljual['hrg_jual']*($sqljual['discvo']/100);
                        }else{
                          $xdivo=0;  
                        }
                          
                        $jmldivo  = ($xdivo*$sqljual['qty_brg']);
                        $jmldirp  = ($xdirp*$sqljual['qty_brg']);
                        $jmlditem = ($xditem*$sqljual['qty_brg']);
                        $totdivo  = $totdivo+($xdivo*$sqljual['qty_brg']);
                        $totdirp  = $totdirp+($xdirp*$sqljual['qty_brg']);
                        $totditem = $totditem+($xditem*$sqljual['qty_brg']);
                        $totjum   = $totjum + ($jmldivo+$jmldirp+$jmlditem);

                        if ($cr_bay!="TUNAI")
                        {
                          if($sqljual['saldo_hutang']==""){
                            $sld_hut=0;    
                          }else{
                            $sld_hut=$sqljual['saldo_hutang'];    
                          }
                        }
                        
                        if($sld_hut==0){$kets="LUNAS";}else{$kets="BELUM";}
                        ?>
                          <tr >
                            <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqljual['tgl_jual']);?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['kd_toko']; ?></td>
                            <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['no_fakjual'];?></td>
                            <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['nm_brg']; ?></td>
                            <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['qty_brg'] ?></td>
                            <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqljual['hrg_jual']*$sqljual['qty_brg']); ?></td>
                            <td style="text-align:right;font-size: 8pt"><?php echo gantitides(round($jmlditem,0)); ?></td>
                            <td style="text-align:right;font-size: 8pt"><?php echo gantitides(round($jmldirp,0)); ?></td>
                            <td style="text-align:right;font-size: 8pt"><?php echo gantitides(round($jmldivo,0)); ?></td>
                            <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo gantitides(round($jmlditem+$jmldirp+$jmldivo,0)); ?>&nbsp;</td>
                        </tr>
                        <?php     
                      }
                    } // while
                    ?>
                    
                    <tr class="yz-theme-l2">
                        <th colspan=7 style="font-size: 9pt;text-align:right"><b>T O T A L &nbsp; P E M B E R I A N &nbsp; D I S C O U N T & V O U C H E R</b>&nbsp;</th>
                        <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides(round($totditem-$totdnota,0)) ?></b>&nbsp;</th>
                        <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides(round($totdirp-$totditemr,0)) ?></b>&nbsp;</th>
                        <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides(round($totdivo-$totdivor,0)) ?></b>&nbsp;</th>
                        <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides(round($totjum-($totdnota+$totditemr+$totdivor),0)) ?></b>&nbsp;</th>
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

