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
    include 'config.php';
    session_start();   
    $connect=opendtcek();
    
    ini_set('memory_limit', '1024M'); // or you could use 1G
    $tgl1     = $_POST['tglbonus1'];
    $tgl2     = $_POST['tglbonus2'];
    $pilbonus = $_POST['pilihbonus'];
    $piltoko  = $_POST['kd_tokobonus'];

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
    $hspan=12;  
    $ket='TUNAI / TEMPO';
    if ($pilbonus=='alldata'){
      $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.laba,dum_jual.kd_toko,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.saldo_hutang FROM dum_jual
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
                WHERE dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false AND nm_brg like '%BONUS%' ORDER BY dum_jual.no_urut ASC");
    }else {
      $cekjual=mysqli_query($connect,"SELECT dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.nm_brg,dum_jual.qty_brg,dum_jual.hrg_beli,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.kd_bayar,dum_jual.ket,dum_jual.laba,dum_jual.kd_toko,pelanggan.nm_pel,kemas.nm_sat1,mas_jual.saldo_hutang FROM dum_jual
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual and dum_jual.tgl_jual=mas_jual.tgl_jual
                WHERE dum_jual.kd_toko='$piltoko' and dum_jual.tgl_jual>='$tgl1' and dum_jual.tgl_jual<='$tgl2' AND panding=false AND nm_brg like '%BONUS%' ORDER BY dum_jual.no_urut ASC");
    }
  ?>  

<body class="F4 landscape">      

    <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
          <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;">
            <thead>
                <tr><td colspan="<?=$hspan?>" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="<?=$hspan?>" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr><td colspan="<?=$hspan?>" style="text-align: left;font-size: 9pt"><b>Laporan pemberian Bonus ke pelanggan dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?></b></td></tr>   
                <tr style="background-color: lightgrey">
                    <th style="width:2%;">NO</th>
                    <th style="width:5%">TGL. JUAL</th>
                    <th style="width:4%">KD.TOKO</th>
                    <th style="width:12%">NO.NOTA</th>
                    <th >NAMA BARANG</th>
                    <th style="width:4%">QTY</th>
                    <th style="width:4%">SAT</th>
                    <th style="width:4%">Cr.BAYAR</th>
                    <th style="width:8%">HARGA JUAL</th> 
                    <th style="width:8%">SUB TOTAL</th>
                    <th style="width:8%">LABA</th>
                    <th style="width:5%">KET</th> 
                    
                </tr>       
            </thead>

            <?php           
               
                if(mysqli_num_rows($cekjual)>=1){
                    $no=0;$totbeli1=0;$totbeli2=0;$jumlah=0;$disc=0;$totpit=0;$diskon=0;$totlaba=0;
                    while ($sqljual=mysqli_fetch_assoc($cekjual)) {
                      $no++;                   
                      // if ($sqljual['discitem']==0 && $sqljual['discrp']==0 ) {
                      //   $jumlah=$sqljual['hrg_jual']*$sqljual['qty_brg'];
                      //   $diskon=0;
                      // } 
                      // if ($sqljual['discitem'] > 0 && $sqljual['discrp']==0 ) {
                      //   $disc=$sqljual['hrg_jual']-($sqljual['hrg_jual'] * ($sqljual['discitem']/100));
                      //   $jumlah=$disc*$sqljual['qty_brg'];
                      //   $diskon=gantitides($sqljual['discitem']).' %';
                      // }
                      // if ($sqljual['discitem'] == '0' && $sqljual['discrp'] > 0 ) {
                      //   $disc=$sqljual['hrg_jual']-$sqljual['discrp'];
                      //   $jumlah=$disc*$sqljual['qty_brg'];
                      //   $diskon=gantitides($sqljual['discrp']);
                      // } 
                      // if ($sqljual['discitem'] > 0 && $sqljual['discrp'] > 0 ) {
                      //   $ditem=$sqljual['hrg_jual']-$sqljual['discrp'];
                      //   $jumlah=($ditem-($ditem*($sqljual['discitem']/100)))*$sqljual['qty_brg']; 
                      //   $diskon=gantitides($sqljual['discrp']+($ditem*($sqljual['discitem']/100)));
                      // }  
                      $jumlah=$sqljual['hrg_beli']*$sqljual['qty_brg'];
                      $totbeli1=$totbeli1+$sqljual['hrg_beli'];
                      $totbeli2=$totbeli2+$jumlah;
                      $totlaba=$totlaba+$sqljual['laba'];
                      if ($cr_bay!="TUNAI"){
                        if($sqljual['saldo_hutang']==""){
                          $sld_hut=0;    
                        }else{
                          $sld_hut=$sqljual['saldo_hutang'];    
                        }
                        
                      }
                      $totpit=$totpit+$sqljual['saldo_hutang'];
                      if($sld_hut==0){$kets="LUNAS";}else{$kets="BELUM";}
                      ?>
                        <tr >
                          <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqljual['tgl_jual']);?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['kd_toko']; ?></td>
                          <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['no_fakjual'];?></td>
                          <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['nm_brg']; ?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['qty_brg'] ?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['nm_sat1']; ?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['kd_bayar']; ?></td>
                          <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqljual['hrg_beli']); ?></td>
                          <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jumlah); ?></td>
                          <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($sqljual['laba']); ?></td>
                          <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $kets; ?></td>
                      </tr>
                    <?php     
                    } // while
                    ?>
                    
                    <tr style="background-color: lightgrey;color: black">
                        <th colspan=8 align="center" style="font-size: 10pt"><b>T O T A L &nbsp; P E N J U A L A N &nbsp; B A R A N G</b></th>
                        <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totbeli1) ?></b></th>
                        <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totbeli2) ?></b></th>
                        <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totlaba) ?></b></th>
                        <th colspan="1"></th>
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

