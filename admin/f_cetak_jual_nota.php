<link rel="stylesheet" href="../assets/css/paper.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
<link rel="stylesheet" href="../assets/css/blue-themes.css">
<?php
    session_start();
    include 'config.php';
    require_once 'f_cetak_jual_item_helper.php';
    $connect=opendtcek();
?>
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
<body class="F4">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
        <?php 
        //ini_set('memory_limit', '1024M'); // or you could use 1G 
        $tglhi    = $_SESSION['tgl_set'];
        $xx=explode("-",$_SESSION['tgl_set']);
        $blnhi =$xx[1];
        $thnhi =$xx[0];
    	  $pesan    = explode(';',$_GET['pesan']);
        $tgl1     = $pesan[0];
        $tgl2     = $pesan[1];
        $cr_bay   = $pesan[2];
        $brand_filter = isset($pesan[3]) ? $pesan[3] : '';
        $kd_toko  = $_SESSION['id_toko'];
        $nm_toko  = "";$ket='';
        $brand_filter = mysqli_real_escape_string($connect, $brand_filter);
        $allowed_brands = array('OMG','EMINA','WARDAH','MAKE OVER','KAHF','SKINTIFIC MARINA','G2G','HANASUI','SLAVINA','SCARLET','HADALABO','IMPLORA','VIVA','GLOW & LOVELY','PONDS','GARNIER','CUSSONS','NIVEA','PIXY','MAKARIZO','YOU','NYU','MIRANDA','DAZZLE ME','ANIMATE','MY BABY','MOELL','NPURE','SCORA','FACETOLOGY','JHONSONS','EUREKA','EVANGELINE','VITALIS','GATSBY','PUCELLE','ANDO','PRO ATT','CARVIL','BENING', 'LOGO','NEW ERA','SPEED','ARMOD','LUBRENA','VAUSTIN');
        if(!in_array($brand_filter, $allowed_brands)){
          $brand_filter = '';
        }
        $brand_text = ($brand_filter === '') ? 'SEMUA' : $brand_filter;
        $brand_filter_sql = '';
        if($brand_filter !== ''){
          $brand_filter_sql = " AND EXISTS (
            SELECT 1 FROM dum_jual dj
            WHERE dj.no_fakjual=mas_jual.no_fakjual
            AND dj.tgl_jual=mas_jual.tgl_jual
            AND dj.kd_toko=mas_jual.kd_toko
            AND UPPER(dj.nm_brg) LIKE UPPER('%$brand_filter%')
          ) ";
        }
        $brand_filter_ret_sql = ($brand_filter === '') ? '' : " AND UPPER(dum_jual.nm_brg) LIKE UPPER('%$brand_filter%') ";
        $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
        $sql=mysqli_fetch_assoc($cektoko);
        $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
        $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
        unset($cektoko,$sql); 

        //hitung jumlah bayar piutang masuk
        $piutcash=0;$piutrf=0;
        $cekpi=mysqli_query($connect,"SELECT SUM(byr_hutang) AS ptrf FROM mas_jual_hutang WHERE mas_jual_hutang.kd_toko='$kd_toko' and mas_jual_hutang.tgl_tran>='$tgl1' and mas_jual_hutang.tgl_tran<='$tgl2' AND mas_jual_hutang.trf='TRANSFER'");
        $dpit=mysqli_fetch_assoc($cekpi);
        $piutrf=$dpit['ptrf'];
        unset($cekpi,$dpit);

        $cekpi=mysqli_query($connect,"SELECT SUM(byr_hutang) AS pcash FROM mas_jual_hutang WHERE mas_jual_hutang.kd_toko='$kd_toko' and mas_jual_hutang.tgl_tran>='$tgl1' and mas_jual_hutang.tgl_tran<='$tgl2' AND mas_jual_hutang.trf=''");
        $dpit=mysqli_fetch_assoc($cekpi);
        $piutcash=$dpit['pcash'];
        unset($cekpi,$dpit);
        
        // hitung kas toko
        $cek=mysqli_query($connect,"SELECT SUM(uang_kas) as uangkas FROM kas_harian WHERE kd_toko='$kd_toko' AND MONTH(tgl_kas)='$blnhi' AND YEAR(tgl_kas)='$thnhi'");
        $dcek=mysqli_fetch_assoc($cek);
        $uangkas=$dcek['uangkas'];
        unset($cek,$dcek);

        $cek=mysqli_query($connect,"SELECT SUM(nominal) as totbiaya FROM biaya_ops WHERE kd_toko='$kd_toko' AND MONTH(tgl_biaya)='$blnhi' AND YEAR(tgl_biaya)='$thnhi'");
        $dcek=mysqli_fetch_assoc($cek);
        $totbiaya=$dcek['totbiaya'];
        unset($cek,$dcek);
        
        if($cr_bay=="TUNAI")
        {
          $ket='TUNAI';$hcol=11;$bcol=2;
          $cek=mysqli_query($connect,"SELECT * FROM mas_jual LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel where mas_jual.kd_toko='$kd_toko' and mas_jual.tgl_jual>='$tgl1' and mas_jual.tgl_jual<='$tgl2' and kd_bayar='TUNAI' $brand_filter_sql ORDER BY mas_jual.no_urut ASC");   

          $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
          LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
          LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
          LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
          LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
          LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
          WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND retur_jual.kd_toko='$kd_toko' $brand_filter_ret_sql
          ORDER BY retur_jual.no_urutretur ASC ");
        }else { 
          if($cr_bay=="TEMPO")
          {
           $ket='TEMPO';$hcol=11;$bcol=1;
           $cek=mysqli_query($connect,"SELECT * FROM mas_jual 
            LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel 
            where mas_jual.kd_toko='$kd_toko' and mas_jual.tgl_jual>='$tgl1' and mas_jual.tgl_jual<='$tgl2' and mas_jual.kd_bayar='TEMPO' $brand_filter_sql ORDER BY mas_jual.no_urut ASC");     

            $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
            LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
            LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
            LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
            LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
            LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
            WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' AND dum_jual.kd_bayar='TEMPO' $brand_filter_ret_sql
            ORDER BY retur_jual.no_urutretur ASC ");
          } else {
           $ket='TUNAI / TEMPO';$hcol=12;$bcol=2;
           $cek=mysqli_query($connect,"SELECT mas_jual.tgl_jual,mas_jual.no_fakjual,pelanggan.nm_pel,mas_jual.tot_jual,mas_jual.tot_disc,mas_jual.kd_bayar,mas_jual.saldo_hutang,mas_jual.ket_bayar,mas_jual.trf,mas_jual.bayar_uang,mas_jual.execut FROM mas_jual 
            LEFT JOIN pelanggan pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel 
            WHERE mas_jual.kd_toko='$kd_toko' and mas_jual.tgl_jual>='$tgl1' and mas_jual.tgl_jual<='$tgl2' $brand_filter_sql ORDER BY mas_jual.no_urut ASC");     

            $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
            LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
            LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
            LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
            LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
            LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
            WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' $brand_filter_ret_sql
            ORDER BY retur_jual.no_urutretur ASC ");
          } 
        }  
          
          if(mysqli_num_rows($cek)>=1){
            ?>
             
    	      <table cellspacing="0" style="width: 100%; font-size: 8pt;">
              <thead>
                <tr><td colspan="<?=$hcol?>" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="<?=$hcol?>" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="<?=$hcol?>" style="text-align: left;font-size: 9pt"><b>Laporan penjualan barang per nota dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?>, pembayaran <?=$ket?></b></td></tr>
                <tr> <td colspan="<?=$hcol?>" style="text-align: left;font-size: 9pt"><b>Filter Brand : <?=$brand_text?></b></td></tr>
               <tr class="yz-theme-l3">
    	            <th class="w3-padding-small" style="width:5%;">NO</th>
    	            <th style="width:20%">TGL. JUAL</th>
    	            <th >NO. NOTA</th>
    	            <th>PELANGGAN</th>
    	            <th style="width:7%">JNS. BRG</th>
                  <th style="width:11%">TOT.HARGA</th>
                  <th style="width:10%">TOT.DISC</th>
                  <th style="width:11%">SUB.TOTAL</th>
                  <?php if($cr_bay=="TEMPO"){ ?>
                  <th style="width:10%">UANG MUKA</th>     
                  <?php }else if($cr_bay=="TUNAI"){ ?>  
                     <th style="width:8%">CR.BAYAR</th>     
                  <?php }else{ ?>
                     <th style="width:10%">UANG MUKA</th>            
                     <th style="width:10%">CR. BAYAR</th>
                  <?php } ?>  
                  <th style="width:7%">KET</th>
                  <th style="width:7%">TGL/WAKTU</th>
    	         </tr> 
              </thead>   
    	        <?php
    	        $no=0;$totbeli=0;$no_fakjual='';$tgl_fakjual='0000-00-00';$disc=0;$jumlah=0;$dp=0;$totpit=0;$tot_disc=0;$jumtrf=0;$jumtun=0; $nofak="";
              $totdp=0;$totjual=0;
    	      	while($databay=mysqli_fetch_assoc($cek)){
      	      	  $no++;	
                  $ex=explode(' ',$databay['execut']);
                  $etgl=gantitgl($ex[0]).' '.$ex[1];
                  $totjual=$totjual+round($databay['tot_jual']);
                  $jumlah=round($databay['tot_jual']-$databay['tot_disc']);
      	      	  $totbeli=$totbeli+$jumlah;
      	      	  $jml_brg=hitjmlbrg($databay['no_fakjual'],$databay['tgl_jual'],$kd_toko);
                  $tot_disc=$tot_disc+$databay['tot_disc'];
      	      	  if($databay['trf']=='TRANSFER'){
                    if($databay['kd_bayar']=='TEMPO') {
                      $totdp=$totdp+$databay['bayar_uang'];
                      $dp=$databay['bayar_uang'];
                    }else{
                      $jumtrf=$jumtrf+$jumlah;
                    }
                    $trfk='TRF'; 
                  }else{
                    $trfk='CASH'; 
                    if ($databay['kd_bayar']=='TEMPO'){
                      if($databay['no_fakjual']<>$nofak){
                        $dp=$databay['bayar_uang'];
                        $totdp=$totdp+$databay['bayar_uang'];
                      }else{$dp=0;}
                    }else {
                      $jumtun=$jumtun+$jumlah;     
                      $dp=0; 
                    }
                  }
      	      	  if($databay['kd_bayar']=="TEMPO"){
                       $bayar=carisaldo($databay['no_fakjual'],$databay['tgl_jual'],$kd_toko); 
      	      	  }else{
      	      	  	$bayar=$databay['tot_jual'];
                    $dp=0; 
      	      	  }
                  $nofak=$databay['no_fakjual'];  
                  ?>
                  <tr>
    	            <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
    	            <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($databay['tgl_jual']);?></td>
    	            <td style="text-align:left;font-size: 8pt">&nbsp;<?php echo $databay['no_fakjual'];?></td>
    	            <td style="text-align:center;font-size: 8pt"><?php echo $databay['nm_pel']; ?></td>
    	            <td style="text-align:center;font-size: 8pt"><?php echo $jml_brg; ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($databay['tot_jual']); ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($databay['tot_disc']); ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($jumlah); ?></td>
                  <?php if($cr_bay=="TEMPO"){ ?>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($dp); ?></td>
                  <?php }else if($cr_bay=="TUNAI"){ ?>  
                    <td style="text-align:center;font-size: 8pt"><?php echo $trfk; ?></td>    	                 
                  <?php }else{ ?>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($dp); ?></td>
                    <td style="text-align:center;font-size: 8pt"><?php echo $trfk; ?></td>    	                 
                  <?php } ?>  
    	            <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $databay['kd_bayar'] ?></td>
                  <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $etgl?></td>
                  </tr>	
    	        <?php           
    	      	}

              // untuk data retur
              $totsub=0;$ret=0;$jmlsub=0;
              while($dret=mysqli_fetch_assoc($sqlret)){
                $no++;
                $ex=explode(' ',$dret['execut']);
                $etglr=gantitgl($ex[0]).' '.$ex[1];
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
                $diskon=$ditem+$dnota+$divo;
                $jmlsub=round($hrgjl*$dret['qty_retur'],0); 
                $totsub=$totsub+$jmlsub;
                   
                if ($dret['saldo_hutang']<=$jmlsub){
                  $ret=$ret+$jmlsub;
                }
                // if($databay['trf']=='TRANSFER'){
                //   $trfk='-TRF';
                // }else{
                //   $trfk='-CASH';              
                // }   
                ?>
                 <tr style="color:red" >
                    <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                    <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($dret['tgl_retur']);?></td>
                    <td style="text-align:left;font-size: 8pt"><?php echo $dret['no_fakjual'].' **';?></td>
                    <td style="text-align:center;font-size: 8pt"><?php echo $dret['nm_pel']; ?></td>
                    <td style="text-align:center;font-size: 8pt"><?php echo $dret['qty_retur'].' '.$dret['nm_sat1'] ?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($dret['hrg_jual']*$dret['qty_brg']); ?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides(round($diskon*$dret['qty_retur'],0)); ?></td>
                    <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jmlsub); ?></td>
                    <?php if($cr_bay !="TUNAI"){ ?>
                      <td style="text-align:right;font-size: 8pt"><?php echo 0 ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $dret['kd_bayar'].$trfk; ?></td>
                    <?php } else { ?>
                      <td style="text-align:center;font-size: 8pt"><?php echo $dret['kd_bayar'].$trfk; ?></td>
                      <?php }?>  
                    <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $dret['ket']; ?></td>
                    <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $etglr; ?></td>  

                  </tr>
                <?php
              }
    	        ?>
    	        <tr class="yz-theme-l3">
                <th colspan=5 style="text-align: right" class="w3-padding-small">TOTAL&nbsp;&nbsp;</th>
                <th style="text-align:right"><?php echo gantitides($totjual) ?></th>
                <th style="text-align:right"><?php echo gantitides($tot_disc) ?></th>
                <th style="text-align:right"><?php echo gantitides($totbeli) ?></th>
                <th style="text-align:right"><?php echo gantitides($totdp) ?></th>
                <!-- <?php if($cr_bay != "TUNAI"){?>
                  <th style="text-align:right"><?php echo gantitides($totpit) ?></th>
                <?php } ?> -->
                <th colspan="<?=$bcol+1?>" align="center"></th>
              </tr>


              <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>

              <tr style="font-weight:bold">
                <td colspan="2" style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Pembayaran Cash</td>
                <td style="border:none;text-align:right"><?=gantitides($jumtun)?></td>
                <td colspan="2" style="border:none"></td>
                <td colspan="2" style="border:none;text-align:left"><i class="fa fa-check-square-o">&nbsp;</i>Total Bayar Cash</td>
                <td style="border:none;text-align:right"><?=gantitides($jumtun+$piutcash-$ret)?></td>
                <td style="border:none"></td>
              </tr>   
              <tr style="font-weight:bold">
                <td colspan="2" style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Pembayaran Transfer</td>
                <td style="border:none;text-align:right"><?=gantitides($jumtrf)?></td>
                <td colspan="2" style="border:none"></td>
                <td colspan="2" style="border:none;text-align:left"><i class="fa fa-check-square-o">&nbsp;</i>Total Bayar Transfer</td>
                <td style="border:none;text-align:right"><?=gantitides($jumtrf+$piutrf)?></td>
                <td style="border:none"></td>
              </tr> 
              <tr style="font-weight:bold">
                <th colspan="2"style="border:none;width:25%;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Piutang Bayar Cash</th>
                <th style="border:none;width:10%;text-align:right"><?=gantitides($piutcash)?></th>
                <td colspan="2" style="border:none"></td>
                <td colspan="2" style="border:none;text-align:left"><i class="fa fa-check-square-o">&nbsp;</i>Uang Kas Toko</td>
                <td style="border:none;text-align:right;"><?=gantitides($uangkas-$totbiaya)?></td>
                <td style="border:none"></td>
              </tr>     
              <tr style="font-weight:bold">
                <th colspan="2" style="border:none;width:25%;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Piutang Bayar Transfer</th>
                <th style="border:none;width:10%;text-align:right"><?=gantitides($piutrf)?></th>
                <td colspan="2" style="border:none"></td>
                <td colspan="2" style="border:none;text-align:left"><i class="fa fa-check-square-o">&nbsp;</i>Total Penjualan</td>
                <td style="border:none;text-align:right;"><?=gantitides($totbeli-$totsub)?></td>
                <td style="border:none"></td>
              </tr>
              <tr style="font-weight:bold">
                <th colspan="2" style="border:none;width:25%;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Retur Barang</th>
                <th style="border:none;width:10%;text-align:right"><?=gantitides($totsub)?></th>
              </tr>

              <tr><td style="border:none">&nbsp;</td></tr>
                <?php
                $totbag=$totrt=0;
                $sqlbag=mysqli_query($connect,"SELECT * FROM bag_brg ORDER by no_urut ASC");
                while($dtbag=mysqli_fetch_assoc($sqlbag)){
                  $id_bag=$dtbag['no_urut'];
                  $nm_bag=$dtbag['nm_bag'];
                  $x=explode(";",caritotbag($id_bag,$kd_toko,$tgl1,$tgl2,$cr_bay,$connect));
                  $totbag=$x[0];
                  $totrt=$x[1]; 
                  if($totbag>0){
                    ?>
                    <tr style="font-weight:bold">
                      <td colspan="2" style="border:none;text-align:left;"><i class="fa fa-bullseye">&nbsp;</i>Penjualan &emsp13;&emsp13;<?=ucwords(strtolower($nm_bag))?></td>
                      <td style="border:none;text-align:right;"><?=gantitides($totbag)?></td>
                      <!-- Bag Retur -->
                      <td colspan="2" style="border:none;"></td>
                      <td colspan="2" style="border:none;text-align:left;"><i class="fa fa-check-square-o"></i>&nbsp;Total Penjualan &nbsp;<?=ucwords(strtolower($nm_bag))?></td>
                      <td style="border:none;text-align:right;"><?=gantitides($totbag-$totrt)?></td>
                    </tr>
                    <tr style="font-weight:bold">
                      <td colspan="2" style="border:none;text-align:left;"><i class="fa fa-bullseye">&nbsp;</i>Retur&nbsp;<?=ucwords(strtolower($nm_bag))?></td>
                      <td style="border:none;text-align:right;"><?=gantitides($totrt)?></td>
                    </tr>
                    <?php
                  }
                }
                ?>
    	      </table>
    	    <?php  	
          }?>
    </div>
    <div class="w3-row w3-margin-top">
      <div class="w3-col w3-center">
        <button id="printPageButton" class="w3-btn w3-green" onclick="window.print();">Cetak PDF</button>      
      </div>
    </div>
  </section>
  
</body>             
	<?php
	function hitjmlbrg($no_fakjual,$tgl_jual,$kd_toko){
      $connect1 = opendtcek(1);
      $cek=mysqli_query($connect1,"SELECT COUNT(*) AS jumlah FROM dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko'");
      $getjml = mysqli_fetch_array($cek);
      return mysqli_escape_string($connect1,$getjml['jumlah']);
      mysqli_close($connect1);unset($cek,$getjml);
	}

	function carisaldo($no_fakjual,$tgl_jual,$kd_toko){
      $connect2 = opendtcek(1);
      $cek=mysqli_query($connect2,"SELECT saldo_awal FROM mas_jual_hutang where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko' ORDER BY no_urut ASC LIMIT 1");
      $getsld = mysqli_fetch_array($cek);
      return mysqli_escape_string($connect2,$getsld['saldo_awal']);
      mysqli_close($connect2);unset($cek,$getsld);
	}
  mysqli_close($connect);
?>
</page>
