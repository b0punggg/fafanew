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
    include 'f_cetak_jual_item_helper.php';
    session_start();   
    $connect=opendtcek();
    
    ini_set('memory_limit', '1024M'); // or you could use 1G
    $pesan    = explode(';',$_GET['pesan']);
    $tgl1     = isset($pesan[0]) ? $pesan[0] : '';
    $tgl2     = isset($pesan[1]) ? $pesan[1] : '';
    $cr_bay   = isset($pesan[2]) ? $pesan[2] : '';
    $brand_filter = isset($pesan[3]) ? $pesan[3] : '';
    $kd_toko  = $_SESSION['id_toko'];
    $nm_toko  = "";
    $al_toko  = "";
    $tglhi    = $_SESSION['tgl_set'];
    $xx=explode("-",$_SESSION['tgl_set']);
    $blnhi =$xx[1];
    $thnhi =$xx[0];
    
    // Escape input untuk mencegah SQL injection
    $kd_toko = mysqli_real_escape_string($connect, $kd_toko);
    $tgl1 = mysqli_real_escape_string($connect, $tgl1);
    $tgl2 = mysqli_real_escape_string($connect, $tgl2);
    $cr_bay = mysqli_real_escape_string($connect, $cr_bay);
    $brand_filter = mysqli_real_escape_string($connect, $brand_filter);
    $allowed_brands = array('OMG','EMINA','WARDAH','MAKE OVER','KAHF','SKINTIFIC MARINA','G2G','HANASUI','SLAVINA','SCARLET','HADALABO','IMPLORA','VIVA','GLOW & LOVELY','PONDS','GARNIER','CUSSONS','NIVEA','PIXY','MAKARIZO','YOU','NYU','MIRANDA','DAZZLE ME','ANIMATE','MY BABY','MOELL','NPURE','SCORA','FACETOLOGY','JHONSONS','EUREKA','EVANGELINE','VITALIS','GATSBY','PUCELLE','ANDO','PRO ATT','CARVIL','BENING', 'LOGO','NEW ERA','SPEED','ARMOD','LUBRENA','VAUSTIN');
    if(!in_array($brand_filter, $allowed_brands)){
      $brand_filter = '';
    }
    $brand_text = ($brand_filter === '') ? 'SEMUA' : $brand_filter;
    $brand_sql = ($brand_filter === '') ? '' : " AND UPPER(dum_jual.nm_brg) LIKE UPPER('%$brand_filter%') ";
    
    $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
    if ($cektoko && $cektoko !== false) {
      $sql=mysqli_fetch_assoc($cektoko);
      if ($sql) {
        $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
        $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
      }
      mysqli_free_result($cektoko);
    }
    unset($cektoko,$sql); 
    $a=0;$ket='';
    $cekjual = false;
    $sqlret = false;
    
    if($cr_bay=="TUNAI"){
      $hspan=11;    
      $ket='TUNAI';
      $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.execut,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang,dum_jual.id_bag,bag_brg.nm_bag FROM dum_jual
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual
        LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
        LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
        WHERE dum_jual.kd_toko='$kd_toko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' and dum_jual.kd_bayar='TUNAI' AND panding=false $brand_sql ORDER BY dum_jual.tgl_jual,dum_jual.no_fakjual ASC");
      if (!$cekjual) {
        echo "Error query cekjual: " . mysqli_error($connect);
      }

      $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
      LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
      LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
      WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' $brand_sql
      ORDER BY retur_jual.no_urutretur ASC ");
      if (!$sqlret) {
        echo "Error query sqlret: " . mysqli_error($connect);
      }

    }else if($cr_bay=="TEMPO"){
      $hspan=12;    
      $ket='TEMPO';
      $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.execut,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang,dum_jual.id_bag,bag_brg.nm_bag FROM dum_jual
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
        LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
        LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
        WHERE dum_jual.kd_toko='$kd_toko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' and dum_jual.kd_bayar='TEMPO' AND panding=false $brand_sql ORDER BY dum_jual.tgl_jual,dum_jual.no_fakjual ASC");
      if (!$cekjual) {
        echo "Error query cekjual: " . mysqli_error($connect);
      }

      $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
      LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
      LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
      WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' AND dum_jual.kd_bayar='TEMPO' $brand_sql
      ORDER BY retur_jual.no_urutretur ASC ");
      if (!$sqlret) {
        echo "Error query sqlret: " . mysqli_error($connect);
      }
    }else{
      $hspan=14;  
      $ket='TUNAI / TEMPO';
      $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.execut,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang,dum_jual.id_bag,bag_brg.nm_bag FROM dum_jual
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
      LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
      LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
      WHERE dum_jual.kd_toko='$kd_toko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false $brand_sql
      ORDER BY dum_jual.tgl_jual,dum_jual.no_fakjual ASC");
      if (!$cekjual) {
        echo "Error query cekjual: " . mysqli_error($connect);
      }

      $sqlret  = mysqli_query($connect,"SELECT retur_jual.*,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.trf,dum_jual.discvo,dum_jual.execut,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.bayar_uang,mas_jual.saldo_hutang,dum_jual.id_bag,bag_brg.nm_bag FROM retur_jual 
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
      LEFT JOIN bag_brg ON dum_jual.id_bag=bag_brg.no_urut
      LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
      LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
      LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
      LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
      WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' $brand_sql
      ORDER BY retur_jual.no_urutretur ASC ");
      if (!$sqlret) {
        echo "Error query sqlret: " . mysqli_error($connect);
      }
    } 
    $sqlbag=mysqli_query($connect,"SELECT * FROM bag_brg ORDER by no_urut ASC");
    if (!$sqlbag) {
      echo "Error query sqlbag: " . mysqli_error($connect);
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
            <tr> <td colspan="<?=$hspan?>" style="text-align: left;font-size: 9pt"><b>Filter Brand : <?=$brand_text?></b></td></tr>
            <tr class="yz-theme-l3">
                <th style="width:3%;" class="w3-padding-small">NO</th>
                <th style="width:10%">TGL. JUAL</th>
                <th style="width:10%">NO.NOTA</th>
                <th style="width:19%">NAMA BARANG</th>
                <th style="width:8%">HARGA JUAL</th>
                <th style="width:6%">DISC</th>
                <th style="width:6%">VOUCHER</th>
                <th style="width:4%">QTY</th>
                <th style="width:8%">SUB TOTAL</th>
                <th style="width:8%">UANG MUKA</th>
                <th style="width:5%">Cr.BAYAR</th> 
                <th style="width:3%">KET</th>
                <th style="width:6%">BAGIAN</th>
                <th style="width:15%">TGL/WAKTU</th>
            </tr>       
        </thead>

        <?php 
            
            // hitung kas toko
            $uangkas = 0;
            $totbiaya = 0;
            $piutcash = 0;
            $piutrf = 0;
            
            $blnhi = mysqli_real_escape_string($connect, $blnhi);
            $thnhi = mysqli_real_escape_string($connect, $thnhi);
            
            $cek=mysqli_query($connect,"SELECT SUM(uang_kas) as uangkas FROM kas_harian WHERE kd_toko='$kd_toko' AND MONTH(tgl_kas)='$blnhi' AND YEAR(tgl_kas)='$thnhi'");
            if ($cek && $cek !== false) {
              $dcek=mysqli_fetch_assoc($cek);
              $uangkas=$dcek['uangkas'];
              mysqli_free_result($cek);
            }
            unset($cek,$dcek);

            $cek=mysqli_query($connect,"SELECT SUM(nominal) as totbiaya FROM biaya_ops WHERE kd_toko='$kd_toko' AND MONTH(tgl_biaya)='$blnhi' AND YEAR(tgl_biaya)='$thnhi'");
            if ($cek && $cek !== false) {
              $dcek=mysqli_fetch_assoc($cek);
              $totbiaya=$dcek['totbiaya'];
              mysqli_free_result($cek);
            }
            unset($cek,$dcek);
            
            //hitung jumlah bayar piutang masuk
            $cekpi=mysqli_query($connect,"SELECT SUM(byr_hutang) AS ptrf FROM mas_jual_hutang WHERE mas_jual_hutang.kd_toko='$kd_toko' and mas_jual_hutang.tgl_tran>='$tgl1' and mas_jual_hutang.tgl_tran<='$tgl2' AND mas_jual_hutang.trf='TRANSFER' AND ket <> 'RETUR'");
            if ($cekpi && $cekpi !== false) {
              $dpit=mysqli_fetch_assoc($cekpi);
              $piutrf=$dpit['ptrf'];
              mysqli_free_result($cekpi);
            }
            unset($cekpi,$dpit);

            $cekpi=mysqli_query($connect,"SELECT SUM(byr_hutang) AS pcash FROM mas_jual_hutang WHERE mas_jual_hutang.kd_toko='$kd_toko' and mas_jual_hutang.tgl_tran>='$tgl1' and mas_jual_hutang.tgl_tran<='$tgl2' AND mas_jual_hutang.trf='' AND ket <> 'RETUR'");
            if ($cekpi && $cekpi !== false) {
              $dpit=mysqli_fetch_assoc($cekpi);
              $piutcash=$dpit['pcash'];
              mysqli_free_result($cekpi);
            }
            unset($cekpi,$dpit);
            
            if($cekjual && $cekjual !== false && mysqli_num_rows($cekjual)>=1){
                $no=0;$tot_hrgjual=0;$tot_jual=0;$jumlah=0;
                $disc=0;$subtot1=0;$subtot2=0;$totpit=0;$diskon=0;$totdp=0;$dp=0;$jumtrf=0;$jumtun=0;$totdivo=0;$totdisc=0;
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
                    
                  }  
                    ?>  
                      <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqljual['tgl_jual']);?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['no_fakjual'].$nm_pel;?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['nm_brg']; ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqljual['hrg_jual']); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides($diskon); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides(round($xdivo,0)); ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['qty_brg'].' '.$sqljual['nm_sat1'] ?></td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jumlah); ?>&nbsp;</td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($dp); ?>&nbsp;</td>   
                      <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['kd_bayar'].$trfk; ?></td>
                      <td style="text-align:center;font-size: 8pt;"><?php echo $kets; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $sqljual['nm_bag']; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $etgl; ?></td>
                    </tr>
                    <?php     
                } // while
                mysqli_free_result($cekjual);

                // untuk data retur
                $totsub=0;$ret=0;$totdisc_r=0;$totdivo_r=0;$divo_r=0;$diskon_r=0;$jmlsub=0;$totdivor=0;$totdiscr=0;
                if ($sqlret && $sqlret !== false) {
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
                    $ex=explode(' ',$dret['execut']);
                    $etglr=gantitgl($ex[0]).' '.$ex[1];
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
                    if ($dret['kd_bayar']=='TEMPO'){
                      $nm_pel=' an,'.ucwords(strtolower($dret['nm_pel']));
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
                      <td style="text-align:right;font-size: 8pt;"><?php echo 0 ?>&nbsp;</td>   
                      <td style="text-align:center;font-size: 8pt"><?php echo $dret['kd_bayar'].$ketr; ?></td>
                      <td style="text-align:center;font-size: 8pt;"><?php echo $dret['ket']; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $dret['nm_bag']; ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $etglr; ?></td>
                    </tr>
                    <?php
                  }
                  mysqli_free_result($sqlret);
                }
                ?>
                
                <tr class="yz-theme-l1">
                  <th colspan=8 style="font-size: 9pt;text-align:right" class="w3-padding-small">T O T A L &nbsp; P E N J U A L A N &nbsp; B A R A N G</th>
                  <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($tot_jual) ?></b>&nbsp;</th>
                  <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totdp) ?></b>&nbsp;</th>
                  <th colspan="4"></th>
                </tr>
                
                
                <!-- Keterangan Penjualan -->
                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>
                <tr>
                  <th style="border:none"></th>
                    <th class="yz-theme-l3" colspan="4">P E N J U A L A N</th>
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

                <!-- Keterangan uang diterima -->
                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>
                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>
                <tr><th style="border:none"></th><th colspan="4" class="yz-theme-l3">U A N G &nbsp; D I T E R I M A</th></tr>
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

                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>
                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>
                <tr><th style="border:none"></th><th colspan="4" class="yz-theme-l3">BAGIAN PENJUALAN</th></tr>
                <tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr><tr><td style="border:none"></td></tr>
                <?php
                $totbag=$totrt=0;
                if ($sqlbag && $sqlbag !== false) {
                  while($dtbag=mysqli_fetch_assoc($sqlbag)){
                    $id_bag=$dtbag['no_urut'];
                    $nm_bag=$dtbag['nm_bag'];
                    $x=explode(";",caritotbag($id_bag,$kd_toko,$tgl1,$tgl2,$cr_bay,$connect));
                    $totbag=$x[0];
                    $totrt=$x[1]; 
                    if($totbag>0){
                    ?>
                    <tr style="font-weight:bold">
                      <td style="border:none"></td>
                      <td style="border:none;text-align:left;"><i class="fa fa-bullseye">&nbsp;</i>Penjualan &emsp13;&emsp13;<?=ucwords(strtolower($nm_bag))?></td>
                      <td style="border:none;text-align:right;"><?=gantitides($totbag)?> &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;</td>
                      <!-- Bag Retur -->
                      <td style="border:none;text-align:right;"><i class="fa fa-bullseye">&nbsp;</i>Retur&nbsp;<?=ucwords(strtolower($nm_bag))?>&emsp13;&emsp13;&emsp13;<?=gantitides($totrt)?>&emsp13;&emsp13;&emsp13;<i class="fa fa-check-square-o"></i>&nbsp;Jumlah</td>
                      <td style="border:none;text-align:right;border-bottom:1px solid black"><?=gantitides($totbag-$totrt)?></td>
                    </tr>
                    <?php
                    }
                  }
                  mysqli_free_result($sqlbag);
                }
                ?>
                
            <?php
            } else {
              // Tampilkan pesan jika tidak ada data
              ?>
              <tr>
                <td colspan="<?=$hspan?>" style="text-align:center;padding:20px;">
                  <h3>Tidak ada data penjualan untuk periode <?=gantitgl($tgl1)?> sampai <?=gantitgl($tgl2)?>, Pembayaran <?=$ket?></h3>
                  <?php if (!$cekjual) { ?>
                    <p style="color:red;">Error: <?=mysqli_error($connect)?></p>
                  <?php } ?>
                </td>
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
</html>
