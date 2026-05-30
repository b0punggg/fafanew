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
    $tgl1     = $_POST['tglbag1'];
    $tgl2     = $_POST['tglbag2'];
    $kd_toko  = $_POST['pilihtoko'];
    $id_bag   = $_POST['pilihbag'];
    $nm_toko  = "";
    $tglhi    = $_SESSION['tgl_set'];
    $xx=explode("-",$_SESSION['tgl_set']);
    $blnhi =$xx[1];
    $thnhi =$xx[0];
    $cekbag=mysqli_query($connect,"SELECT * FROM bag_brg WHERE no_urut='$id_bag'");
    $cekdt=mysqli_fetch_assoc($cekbag);
    $nmbag=$cekdt['nm_bag'];
    mysqli_free_result($cekbag);unset($cekdt);

    $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
    if(mysqli_num_rows($cektoko)>=1){
        $sql=mysqli_fetch_assoc($cektoko);
        $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
        $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
    }else{
        $nm_toko='TOKO FAFA (SEMUA)';
        $al_toko='KOMPLEK PASAR NGROMPAK';
    }
    unset($cektoko,$sql); 

    $a=0;$ket='';
    $hspan=13;
   
    if($kd_toko=='NONE'){
        $ket='TUNAI / TEMPO';
        $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.execut,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang,dum_jual.id_bag,bag_brg.nm_bag FROM dum_jual
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
        LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
        WHERE dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false AND dum_jual.id_bag='$id_bag' 
        ORDER BY dum_jual.tgl_jual,dum_jual.no_fakjual ASC");

        $sqlret  = mysqli_query($connect,"SELECT retur_jual.*,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.execut,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang,dum_jual.id_bag,bag_brg.nm_bag FROM retur_jual 
        LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
        LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
        LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND dum_jual.id_bag='$id_bag'
        ORDER BY retur_jual.no_urutretur ASC ");
    }else{
        $ket='TUNAI / TEMPO';
        $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.execut,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang,dum_jual.id_bag,bag_brg.nm_bag FROM dum_jual
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
        LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
        WHERE dum_jual.kd_toko='$kd_toko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false AND dum_jual.id_bag='$id_bag'
        ORDER BY dum_jual.tgl_jual,dum_jual.no_fakjual ASC");
  
        $sqlret  = mysqli_query($connect,"SELECT retur_jual.*,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.execut,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang,dum_jual.id_bag,bag_brg.nm_bag FROM retur_jual 
        LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
        LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
        LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' AND dum_jual.id_bag='$id_bag'
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
            <tr> <td colspan="<?=$hspan?>" style="text-align: left;font-size: 9pt"><b>Penjualan barang Bagian <?=$nmbag?> per item dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?>, Pembayaran <?=$ket?></b></td></tr>   
            <tr style="background-color: lightgrey">
                <th style="width:3%;">NO</th>
                <th style="width:8%">TGL. JUAL</th>
                <th style="width:10%">NO.NOTA</th>
                <th style="width:19%">NAMA BARANG</th>
                <th style="width:8%">HARGA JUAL</th>
                <th style="width:6%">DISC</th>
                <th style="width:6%">VOUCHER</th>
                <th style="width:4%">QTY</th>
                <th style="width:8%">SUB TOTAL</th>
                <!-- <th style="width:8%">UANG MUKA</th> -->
                <th style="width:5%">Cr.BAYAR</th> 
                <th style="width:3%">KET</th>
                <th style="width:6%">BAGIAN</th> 
                <th style="width:5%">TGL/WAKTU</th>
            </tr>       
        </thead>

        <?php         
            if(mysqli_num_rows($cekjual)>=1){
                $no=0;$tot_hrgjual=0;$tot_jual=0;$jumlah=0;
                $disc=0;$subtot1=0;$subtot2=0;$totpit=0;$diskon=0;$totdp=0;$dp=0;$jumtrf=0;$jumtun=0;$totdivo=0;$totdisc=0;$totdpf=$totdpt=0;
                $nofak="";$tottempo=0;$tottunai=0;
                while ($sqljual=mysqli_fetch_assoc($cekjual)) {
                  $no++;                   
                  $ex=explode(' ',$sqljual['execut']);
                  $etgl=gantitgl($ex[0]).' '.$ex[1];
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
                  $diskon=$xditem+$xdirp;
                  $totdivo=$totdivo+($xdivo*$sqljual['qty_brg']);
                  $totdisc=$totdisc+($diskon*$sqljual['qty_brg']);

                  $jumlah=round(($sqljual['hrg_jual']-($xditem+$xdirp+$xdivo))*$sqljual['qty_brg'],0);

                  $tot_hrgjual=$tot_hrgjual+$sqljual['hrg_jual'];
                  $tot_jual=$tot_jual+$jumlah;
                  
                  if($sqljual['saldo_hutang']==0){$kets="LUNAS";}else{$kets="BELUM";}

                  if($sqljual['trf']=='TRANSFER'){
                    if($sqljual['kd_bayar']=='TEMPO') {
                      $totdp=$totdp+$sqljual['bayar_uang'];
                      $totdpf=$totdpf+$sqljual['bayar_uang'];
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
                        $totdpt=$totdpt+$sqljual['bayar_uang'];
                      }else{$dp=0;}
                    }else {
                      $jumtun=$jumtun+$jumlah;     
                      $dp=0; 
                    }
                  }
                  $nofak=$sqljual['no_fakjual'];   
                  if ($sqljual['kd_bayar']=='TEMPO'){
                    $tottempo=$tottempo+$jumlah;
                    $nm_pel=' an,'.ucwords(strtolower($sqljual['nm_pel']));
                  ?>
                    <tr style="color:blue;font-weight:bold" > 
                    <?php
                  }else{ 
                    $nm_pel="";
                    $tottunai=$tottunai+$jumlah;  
                    if ($sqljual['trf']=='TRANSFER'){
                      ?>
                    <tr style="color:coral;font-weight:bold" > 
                      <?php
                    }else{
                      ?>
                      <tr> 
                      <?php  
                    }
                  } ?>  
                      <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqljual['tgl_jual']);?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['no_fakjual'].$nm_pel;?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['nm_brg']; ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqljual['hrg_jual']); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides($diskon); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides(round($xdivo,0)); ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['qty_brg'].' '.$sqljual['nm_sat1'] ?></td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jumlah); ?>&nbsp;</td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['kd_bayar'].$trfk; ?></td>
                      <td style="text-align:center;font-size: 8pt;"><?php echo $kets; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $sqljual['nm_bag']; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $etgl; ?></td>
                    </tr>  <?php     
                } // while

                // untuk data retur
                $totsub=0;$ret=0;$totdisc_r=0;$totdivo_r=0;$divo_r=0;$diskon_r=0;$jmlsub=0;$totdivor=0;$totdiscr=0;
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
                    $divo_r =$dret['hrg_jual']*($dret['discvo']/100);
                  }else{
                    $divo_r =0;  
                  }     
                  $disc_r=$ditem+$dnota;
                  $hrgjl=$dret['hrg_jual']-($ditem+$dnota+$divo_r);  
                  $jmlsub=round($hrgjl*$dret['qty_retur'],0); 
                  $totsub=$totsub+$jmlsub;
                  
                  if ($dret['saldo_hutang']<=$jmlsub ){
                    $ret=$ret+$jmlsub;
                    $totdiscr=$totdiscr+($disc_r*$dret['qty_retur']);
                    $totdivor=$totdivor+($divo_r*$dret['qty_retur']);
                  }
                  if($dret['trf']=="TRANSFER"){
                    $ketr="-TRF";
                  }else{
                    $ketr="";
                  }
                  if ($sqljual['kd_bayar']=='TEMPO'){
                    $nm_pel=' an,'.ucwords(strtolower($sqljual['nm_pel']));
                  }else{
                    $nm_pel="";
                  }
                  ?>
                    <tr style="color:red;font-weight:bold">
                      <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($dret['tgl_retur']);?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $dret['no_fakjual'].$nm_pel;?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $dret['nm_brg'].' **'; ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides($dret['hrg_jual']); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides(round($disc_r,0)); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides(round($divo_r,0)); ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $dret['qty_retur'].' '.$dret['nm_sat1'] ?></td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jmlsub); ?>&nbsp;</td>
                      <!-- <td style="text-align:right;font-size: 8pt;"><?php echo 0 ?>&nbsp;</td>    -->
                      <td style="text-align:center;font-size: 8pt"><?php echo $dret['kd_bayar'].$ketr; ?></td>
                      <td style="text-align:center;font-size: 8pt;"><?php echo $dret['ket']; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $dret['nm_bag']; ?></td>
                    </tr>
                  <?php
                }
                ?>
                
                <tr cellspacing="2" style="background-color: lightgrey;color: black">
                  <th colspan=8 style="font-size: 10pt;text-align:right"><b>T O T A L &nbsp; P E N J U A L A N &nbsp; B A R A N G</b></th>
                  <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($tot_jual-$totsub) ?></b>&nbsp;</th>
                  <!-- <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totdp) ?></b>&nbsp;</th> -->
                  <th colspan="4"></th>
                </tr>
                
                
                <!-- Keterangan Penjualan -->
                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>
                <tr>
                  <th style="border:none"></th>
                    <th style="background-color: lightgrey;color: black" colspan="4">P E N J U A L A N</th>
                </tr>
                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>

                <tr style="font-weight:bold">
                  <td style="border:none;text-align:left"></td>
                  <td style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Penjualan Tunai</td>
                  <td style="border:none;text-align:right;"><?=gantitides($tottunai)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                  <td style="border:none;text-align:right"><i class="fa fa-check-square-o">&nbsp;</i>Total Discount</td>
                  <td  style="border:none;text-align:right;border-bottom: 1px solid black"><?=gantitides(round($totdisc-$totdiscr),0)?></td>
                  <td style="border:none"></td>
                </tr>
                
                <tr style="font-weight:bold">
                  <td style="border:none;text-align:left"></td>
                  <td style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Penjualan Tempo</td>
                  <td style="border:none;text-align:right;"><?=gantitides($tottempo)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                  <td style="border:none;text-align:right"><i class="fa fa-check-square-o">&nbsp;</i>Total Voucher</td>
                  <td  style="border:none;text-align:right;border-bottom: 1px solid black"><?=gantitides(round($totdivo-$totdivor),0)?></td>
                  <td style="border:none"></td>
                </tr>
                
                <tr style="font-weight:bold">
                  <td style="border:none;text-align:left"></td>
                  <td style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Retur Penjualan</td>
                  <td style="border:none;text-align:right;"><?=gantitides($totsub)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                  <td style="border:none;text-align:right"><i class="fa fa-check-square-o">&nbsp;</i>Total Penjualan</td>
                  <td  style="border:none;text-align:right;border-bottom: 1px solid black"><?=gantitides($tottunai+$tottempo-$ret)?></td>
                  <td style="border:none"></td>
                </tr>
                <tr>
                  <td style="border:none"></td>
                  <th style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Pembayaran Cash</th>
                  <th style="border:none;text-align:right;"><?=gantitides($jumtun)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</th>
                </tr>
                <tr>
                  <td style="border:none"></td>
                  <th style="border:none;text-align:left"><i class="fa fa-bullseye">&nbsp;</i>Pembayaran Transfer</th>
                  <th style="border:none;text-align:right;"><?=gantitides($jumtrf)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</th>
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

