<link rel="stylesheet" href="../assets/css/paper.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
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
    include 'config.php';
    session_start();   
    $connect=opendtcek();
    
    ini_set('memory_limit', '1024M'); // or you could use 1G
    $pesan    = explode(';',$_GET['pesan']);
    $tgl1     = $pesan[0];
    $tgl2     = $pesan[1];
    $cr_bay   = $pesan[2];
    $kd_toko  = $_SESSION['id_toko'];
    $nm_toko  = "";
    $tglhi    = $_SESSION['tgl_set'];
    $xx=explode("-",$_SESSION['tgl_set']);
    $blnhi =$xx[1];
    $thnhi =$xx[0];
    $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
    $sql=mysqli_fetch_assoc($cektoko);
    $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
    $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
    unset($cektoko,$sql); 
    $a=0;$ket='';
    if($cr_bay=="TUNAI"){
      $hspan=10;    
      $ket='TUNAI';
      $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang FROM dum_jual
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual
                WHERE dum_jual.kd_toko='$kd_toko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' and dum_jual.kd_bayar='TUNAI' AND panding=false ORDER BY dum_jual.no_fakjual ASC");

      $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
      LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' 
      ORDER BY retur_jual.no_urutretur ASC ");

    }else if($cr_bay=="TEMPO"){
      $hspan=11;    
      $ket='TEMPO';
      $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang FROM dum_jual
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
                WHERE dum_jual.kd_toko='$kd_toko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' and dum_jual.kd_bayar='TEMPO' AND panding=false ORDER BY dum_jual.no_fakjual ASC");

      $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
      LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' AND dum_jual.kd_bayar='TEMPO'
      ORDER BY retur_jual.no_urutretur ASC ");          
    }else{
      $hspan=11;  
      $ket='TUNAI / TEMPO';
      $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang FROM dum_jual
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
      WHERE dum_jual.kd_toko='$kd_toko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false ORDER BY dum_jual.no_fakjual ASC");

      $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' 
      ORDER BY retur_jual.no_urutretur ASC ");
    } 
    
  ?>  

<body class="F4 landscape">      

    <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
      <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
        <thead>
            <tr><td colspan="<?=$hspan?>" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
            <tr><td colspan="<?=$hspan?>" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
            <tr><td style="border: none">&nbsp;</td></tr>
            <tr> <td colspan="<?=$hspan?>" style="text-align: left;font-size: 9pt"><b>Laporan penjualan barang per item dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?>, Pembayaran <?=$ket?></b></td></tr>   
            <tr style="background-color: lightgrey">
                <th style="width:3%;">NO</th>
                <th style="width:9%">TGL. JUAL</th>
                <th style="width:10%">NO.NOTA</th>
                <th style="width:19%">NAMA BARANG</th>
                <th style="width:8%">HARGA JUAL</th>
                <th style="width:6%">DISC</th>
                <th style="width:4%">QTY</th>
                <th style="width:8%">SUB TOTAL</th>
                <th style="width:8%">UANG MUKA</th>
                <th style="width:5%">Cr.BAYAR</th> 
                <th style="width:3%">KET</th> 
            </tr>       
        </thead>

        <?php 
            
            // hitung kas toko
            $cek=mysqli_query($connect,"SELECT SUM(uang_kas) as uangkas FROM kas_harian WHERE kd_toko='$kd_toko' AND MONTH(tgl_kas)='$blnhi' AND YEAR(tgl_kas)='$thnhi'");
            $dcek=mysqli_fetch_assoc($cek);
            $uangkas=$dcek['uangkas'];
            unset($cek,$dcek);

            $cek=mysqli_query($connect,"SELECT SUM(nominal) as totbiaya FROM biaya_ops WHERE kd_toko='$kd_toko' AND MONTH(tgl_biaya)='$blnhi' AND YEAR(tgl_biaya)='$thnhi'");
            $dcek=mysqli_fetch_assoc($cek);
            $totbiaya=$dcek['totbiaya'];
            unset($cek,$dcek);
            
            //hitung jumlah bayar piutang masuk
            $piutcash=0;$piutrf=0;
            $cekpi=mysqli_query($connect,"SELECT SUM(byr_hutang) AS ptrf FROM mas_jual_hutang WHERE mas_jual_hutang.kd_toko='$kd_toko' and mas_jual_hutang.tgl_tran>='$tgl1' and mas_jual_hutang.tgl_tran<='$tgl2' AND mas_jual_hutang.trf='TRANSFER' AND ket <> 'RETUR'");
            $dpit=mysqli_fetch_assoc($cekpi);
            $piutrf=$dpit['ptrf'];
            unset($cekpi,$dpit);

            $cekpi=mysqli_query($connect,"SELECT SUM(byr_hutang) AS pcash FROM mas_jual_hutang WHERE mas_jual_hutang.kd_toko='$kd_toko' and mas_jual_hutang.tgl_tran>='$tgl1' and mas_jual_hutang.tgl_tran<='$tgl2' AND mas_jual_hutang.trf='' AND ket <> 'RETUR'");
            $dpit=mysqli_fetch_assoc($cekpi);
            $piutcash=$dpit['pcash'];
            unset($cekpi,$dpit);
            
            if(mysqli_num_rows($cekjual)>=1){
                $no=0;$totbeli1=0;$totbeli2=0;$jumlah=0;$disc=0;$subtot1=0;$subtot2=0;$totpit=0;$diskon=0;$totdp=0;$dp=0;$jumtrf=0;$jumtun=0;
                $nofak="";
                while ($sqljual=mysqli_fetch_assoc($cekjual)) {
                  $no++;                   
                  //$nofak=$sqljual['no_fakjual'];
                  if ($sqljual['discitem']==0 && $sqljual['discrp']==0 ) {
                    $jumlah=$sqljual['hrg_jual']*$sqljual['qty_brg'];
                    $diskon=0;
                  } 
                  if ($sqljual['discitem'] > 0 && $sqljual['discrp']==0 ) {
                    $xz=$sqljual['discitem']/100;
                    $disc=$sqljual['hrg_jual']-($sqljual['hrg_jual'] * $xz);
                    $jumlah=$disc*$sqljual['qty_brg'];
                    $diskon=gantitides($sqljual['hrg_jual'] * ($sqljual['discitem']/100));
                  }
                  if ($sqljual['discitem'] == '0' && $sqljual['discrp'] > 0 ) {
                    $disc=$sqljual['hrg_jual']-$sqljual['discrp'];
                    $jumlah=$disc*$sqljual['qty_brg'];
                    $diskon=gantitides($sqljual['discrp']);
                  } 
                  if ($sqljual['discitem'] > 0 && $sqljual['discrp'] > 0 ) {
                    $ditem=$sqljual['discrp'];
                    $xz=$sqljual['hrg_jual']*$sqljual['discitem']/100;
                    $jumlah=($sqljual['hrg_jual']-($ditem+$xz))*$sqljual['qty_brg']; 
                    $diskon=gantitides($sqljual['discrp']+$xz);
                  }  
                  if ($sqljual['discvo']>0){
                    $ditem=$sqljual['discrp'];
                    $xz=$sqljual['discitem']/100;
                    $xzz=$sqljual['discvo']/100;
                    $dnot =$sqljual['hrg_jual']*$xz;
                    $divo =$sqljual['hrg_jual']*$xzz;
                    $hrgjl=$sqljual['hrg_jual']-($ditem+$dnot+$divo); 
                    $jumlah=round($hrgjl*$sqljual['qty_brg'],2); 
                    $diskon=gantitides($ditem+$dnot+$divo);
                  }

                  $totbeli1=$totbeli1+$sqljual['hrg_jual'];
                  $totbeli2=$totbeli2+$jumlah;
                  
                  if($sqljual['saldo_hutang']==0){$kets="LUNAS";}else{$kets="BELUM";}

                  if($sqljual['trf']=='TRANSFER'){
                    if($sqljual['kd_bayar']=='TEMPO') {
                      $totdp=$totdp+$sqljual['bayar_uang'];
                      $dp=$sqljual['bayar_uang'];
                    }else{
                      $jumtrf=$jumtrf+$jumlah;
                    }
                    $trfk='-TRF'; 
                  }else{
                    $trfk=''; 
                    if ($sqljual['kd_bayar']=='TEMPO'){
                      if($sqljual['no_fakjual']<>$nofak){
                        $dp=$sqljual['bayar_uang'];
                        $totdp=$totdp+$sqljual['bayar_uang'];
                      }else{$dp=0;}
                    }else {
                      $jumtun=$jumtun+$jumlah;     
                      $dp=0; 
                    }
                  }
                  $nofak=$sqljual['no_fakjual'];   
                  ?>
                    <tr >
                      <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqljual['tgl_jual']);?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['no_fakjual'];?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['nm_brg']; ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqljual['hrg_jual']); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo $diskon; ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['qty_brg'].' '.$sqljual['nm_sat1'] ?></td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jumlah); ?>&nbsp;</td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($dp); ?>&nbsp;</td>   
                      <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['kd_bayar'].$trfk; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $kets; ?></td>
                    </tr>
                    <?php     
                } // while

                // untuk data retur
                $totsub=0;$ret=0;
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
                  $hrgjl=$dret['hrg_jual']-($ditem+$dnota+$divo);  
                  $diskon=gantitides($ditem+$dnota+$divo);
                  $jmlsub=round($hrgjl*$dret['qty_brg'],0); 
                  $totsub=$totsub+$jmlsub;

                  if ($dret['saldo_hutang']==0){
                    $ret=$ret+$jmlsub;
                  }

                  ?>
                    <tr style="color:red;font-weight:bold">
                      <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($dret['tgl_jual']);?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $dret['no_fakjual'];?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $dret['nm_brg'].' **'; ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides($dret['hrg_jual']); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo $diskon; ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $dret['qty_brg'].' '.$dret['nm_sat1'] ?></td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jmlsub); ?>&nbsp;</td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo 0 ?>&nbsp;</td>   
                      <td style="text-align:center;font-size: 8pt"><?php echo $dret['kd_bayar'].$trfk; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $dret['ket']; ?></td>
                    </tr>
                  <?php
                }
                ?>
                
                <tr cellspacing="2" style="background-color: lightgrey;color: black">
                  <th colspan=4 align="center" style="font-size: 10pt"><b>T O T A L &nbsp; P E N J U A L A N &nbsp; B A R A N G</b></th>
                  <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totbeli1) ?></b></th>
                  <th colspan="2"></th>
                  <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totbeli2-$totsub) ?></b>&nbsp;</th>
                  <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totdp) ?></b>&nbsp;</th>
                  <th colspan="2"></th>
                </tr>

                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>

                <tr style="font-weight:bold">
                  <td style="border:none;text-align:left"></td>
                  <td style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Pembayaran Cash</td>
                  <td style="border:none;text-align:right;"><?=gantitides($jumtun)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                  <!-- <td colspan="1" style="border:none"></td> -->
                  <td style="border:none;text-align:right"><i class="fa fa-check-square-o">&nbsp;</i>Total Bayar Cash</td>
                  <td  style="border:none;text-align:right;border-bottom: 1px solid black"><?=gantitides($jumtun+$piutcash-$ret)?></td>
                  <td style="border:none"></td>
                </tr>   
                <tr style="font-weight:bold">
                  <td style="border:none"></td>
                  <td style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Pembayaran Transfer</td>
                  <td style="border:none;text-align:right;"><?=gantitides($jumtrf)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                  <!-- <td colspan="1" style="border:none"></td> -->
                  <td style="border:none;text-align:right;"><i class="fa fa-check-square-o">&nbsp;</i>Total Bayar Transfer</td>
                  <td style="border:none;text-align:right;border-bottom: 1px solid black"><?=gantitides($jumtrf+$piutrf)?></td>
                  <td style="border:none"></td>
                </tr> 
                <tr style="font-weight:bold">
                    <td style="border:none"></td>
                  <td style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Piutang Bayar Cash</td>
                  <td style="border:none;text-align:right"><?=gantitides($piutcash)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                  <!-- <td colspan="1" style="border:none"></td> -->
                  <td style="border:none;text-align:right;"><i class="fa fa-check-square-o">&nbsp;</i>Uang Kas Toko</td>
                  <td style="border:none;text-align:right;border-bottom: 1px solid black"><?=gantitides($uangkas-$totbiaya)?></td>
                  <td style="border:none"></td>
                </tr>     
                <tr style="font-weight:bold">
                  <td style="border:none"></td>
                  <td style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Piutang Bayar Transfer</td>
                  <td style="border:none;text-align:right"><?=gantitides($piutrf)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                  <!-- <td style="border:none;text-align:right;"><i class="fa fa-check-square-o">&nbsp;</i>Retur Barang</td>
                  <td style="border:none;text-align:right;border-bottom: 1px solid black"><?=gantitides($totsub)?></td>
                  <td style="border:none"></td> -->
                </tr>
                <tr style="font-weight:bold">
                  <td style="border:none"></td>
                  <td style="border:none;text-align:left;"><i class="fa fa-bullseye">&nbsp;</i>Retur Barang</td>
                  <td style="border:none;text-align:right;"><?=gantitides($totsub)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                
                </tr>    

            <?php
            } // if  
        ?>  
      </table>    
    </div>  
    </section>      
    
</body>       

<script>
window.print();
</script>

