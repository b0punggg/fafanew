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
    $tgl1     = $_POST['tglretur1'];
    $tgl2     = $_POST['tglretur2'];
    $pilihan  = $_POST['pilihretur'];
    $piltoko  = $_POST['kd_tokoretur'];
    $kd_toko  = $_SESSION['id_toko'];   
    $nm_toko  = "";
    $tglhi    = $_SESSION['tgl_set'];
    $xx       = explode("-",$_SESSION['tgl_set']);
    $blnhi    = $xx[1];
    $thnhi    = $xx[0];
    $cektoko  = mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
    $sql      = mysqli_fetch_assoc($cektoko);
    $nm_toko  = mysqli_escape_string($connect,$sql['nm_toko']);
    $al_toko  = mysqli_escape_string($connect,$sql['al_toko']);
    unset($cektoko,$sql); 
    $a=0;$ket='';
    $hspan=11; 
    if ($pilihan=='alldata'){
        $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
        LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
        LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        LEFT JOIN toko ON dum_jual.kd_toko=toko.kd_toko
        WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' 
        ORDER BY retur_jual.kd_toko,retur_jual.no_urutretur ASC ");
    }else{
        $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
        LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
        LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel 
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        LEFT JOIN toko ON dum_jual.kd_toko=toko.kd_toko
        WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$piltoko' 
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
            <tr> <td colspan="<?=$hspan?>" style="text-align: left;font-size: 9pt"><b>Laporan Retur penjualan dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?></b></td></tr>   
            <tr style="background-color: lightgrey">
                <th style="width:3%;">NO</th>
                <th style="width:8%">TGL. JUAL</th>
                <th style="width:8%">TGL. RETUR</th>
                <th style="width:8%">TOKO</th>
                <th >NO.NOTA</th>
                <th >NAMA BARANG</th>
                <th style="width:9%">HARGA JUAL</th>
                <th style="width:8%">DISC</th>
                <th style="width:4%">QTY</th>
                <th style="width:9%">SUB TOTAL</th>
                <th style="width:3%">KET</th> 
            </tr>       
        </thead>

        <?php 
            if(mysqli_num_rows($sqlret)>=1){
                $no=0;$totbeli1=0;$jumlah=0;$disc=0;$diskon=0;$totsub=0;$ret=0;
                $nofak="";
                while ($sqljual=mysqli_fetch_assoc($sqlret)) {
                  $no++;                   
                  //$nofak=$sqljual['no_fakjual'];
                  if($sqljual['discrp'] > 0){
                    $ditem=$sqljual['discrp'];
                  }else{
                    $ditem=0;
                  }
                  if($sqljual['discitem'] > 0){
                    $dnota=$sqljual['hrg_jual']*$sqljual['discitem']/100;
                  }else{
                    $dnota=0;
                  }
                  if ($sqljual['discvo']>0){
                    $divo =$sqljual['hrg_jual']*($sqljual['discvo']/100);
                  }else{
                    $divo =0;  
                  }     
                  $hrgjl    = $sqljual['hrg_jual']-($ditem+$dnota+$divo);  
                  $diskon   = gantitides($ditem+$dnota+$divo);
                  $jmlsub   = round($hrgjl*$sqljual['qty_retur'],0); 
                  $totsub   = $totsub+$jmlsub;
                  $totbeli1 = $totbeli1+$sqljual['hrg_jual'];
                  $kets     = $sqljual['kd_bayar'];
                  if ($sqljual['saldo_hutang']==0){
                    $ret=$ret+$jmlsub;
                  } 
                  ?>
                    <tr >
                      <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqljual['tgl_jual']);?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sqljual['tgl_retur']);?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['nm_toko'];?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['no_fakjual'];?></td>
                      <td style="text-align:left;font-size: 8pt"><?php echo $sqljual['nm_brg']; ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sqljual['hrg_jual']); ?></td>
                      <td style="text-align:right;font-size: 8pt"><?php echo $diskon; ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo $sqljual['qty_retur'].' '.$sqljual['nm_sat1'] ?></td>
                      <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jmlsub); ?>&nbsp;</td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $kets; ?></td>
                    </tr>
                    <?php     
                } // while
                ?>
                
                <tr cellspacing="2" style="background-color: lightgrey;color: black">
                  <th colspan=6 style="font-size: 10pt;text-align:right"><b>T O T A L &nbsp; R E T U R &nbsp; B A R A N G</b></th>
                  <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totbeli1) ?></b></th>
                  <th colspan="2"></th>
                  <th style="text-align:right;font-size: 9pt"><b><?php echo gantitides($totsub) ?></b>&nbsp;</th>
                  <th colspan="2"></th>
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

