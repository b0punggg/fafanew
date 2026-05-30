<link rel="stylesheet" href="../assets/css/paper.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
<link rel="stylesheet" href="../assets/css/blue-themes.css">
<?php
    session_start();
    include 'config.php';
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
    @page { size: F4  }

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
<body class="F4 ">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
        <?php 
        //ini_set('memory_limit', '1024M'); // or you could use 1G 
    	  $pesan    = explode(';',$_GET['pesan']);
        $tgl1     = $pesan[0];
        $tgl2     = $pesan[1];
        $kd_toko  = $_SESSION['id_toko'];
        // Escape input untuk mencegah SQL injection
        $kd_toko = mysqli_real_escape_string($connect, $kd_toko);
        $tgl1 = mysqli_real_escape_string($connect, $tgl1);
        $tgl2 = mysqli_real_escape_string($connect, $tgl2);
        
        $nm_toko  = "";
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
        
          $cek=mysqli_query($connect,"SELECT * FROM mas_jual where mas_jual.kd_toko='$kd_toko' and mas_jual.tgl_jual>='$tgl1' and mas_jual.tgl_jual<='$tgl2' ORDER BY mas_jual.no_urut ASC");
        if (!$cek) {
          echo "<div style='padding:20px;color:red;'>Error: " . mysqli_error($connect) . "</div>";
        }
        if($cek && mysqli_num_rows($cek)>=1){
            ?>
             
    	      <table cellspacing="0" style="width: 100%; font-size: 8pt;">
              <thead>
                <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>Rekapitulasi gross profit penjualan barang dari tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?></b></td></tr>
               <tr class="yz-theme-l2">
    	            <th style="width:5%;">NO</th>
    	            <th style="width:9%">TGL. JUAL</th>
    	            <th >NO. NOTA</th>
    	            <th style="width:8%">JML. BRG</th>
                  <th style="width:12%">TOT. JUAL</th>
                  <th style="width:8%">TOT. DISC</th>
                  <th style="width:12%">TOT. JUAL NETT</th>
                  <th style="width:12%">GROSS PROFIT</th>
                  <th style="width:8%">GPM</th>
                  <th style="width:10%">JASA KRM</th>
                  <!-- <th style="width:10%">KET</th> -->
    	         </tr> 
              </thead>   
    	        <?php
              $tot_as_th_d=0;$tot_as_ms_d=0;$tot_laba_th_d=0;$tot_laba_ms_d=0;$hrg_beli_d=0;$uang_ms_d=0;$aset_ms_d=0;$laba_ms_d=0;$tot_jual_d=0;$laba_th=0;
              $ceks=mysqli_query($connect,"SELECT * FROM mas_jual where mas_jual.kd_toko='$kd_toko' and mas_jual.tgl_jual>='$tgl1' and mas_jual.tgl_jual<='$tgl2' AND ket_bayar='BELUM' ORDER BY mas_jual.no_urut ASC");
              if ($ceks && $ceks !== false) {
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
                
                }
                mysqli_free_result($ceks);
              }
              unset($ceks,$databays);
              //laba masuk bulan ini
               // cari laba piutang penjualan bulan lalu masuk bulan ini
               $x        = explode('-', $_SESSION['tgl_set']);
               $endbln   = $x[1];
               $endyear  = $x[0];
               $tglhi    = $_SESSION['tgl_set'];
               $tglcari  = $endyear.'-'.$endbln.'-'.'01';
               $labamsk  = 0;

               $endbln = mysqli_real_escape_string($connect, $endbln);
               $endyear = mysqli_real_escape_string($connect, $endyear);
               $tglcari = mysqli_real_escape_string($connect, $tglcari);
               
               $cek3=mysqli_query($connect,"SELECT sum(mas_jual_hutang.laba) as labamsk
               FROM mas_jual_hutang 
               WHERE MONTH(mas_jual_hutang.tgl_tran)='$endbln' AND YEAR(mas_jual_hutang.tgl_tran)='$endyear' 
               AND mas_jual_hutang.byr_hutang>0 AND mas_jual_hutang.tgl_jual < '$tglcari' AND mas_jual_hutang.kd_toko='$kd_toko'
               ORDER BY mas_jual_hutang.tgl_tran,mas_jual_hutang.no_fakjual ASC");
               if ($cek3 && $cek3 !== false && mysqli_num_rows($cek3)>=1){
                 $dtcek3=mysqli_fetch_assoc($cek3);
                 $labamsk=$dtcek3['labamsk'];
                 mysqli_free_result($cek3);
               }
               unset($cek3,$dtcek3);

    	        $no=0;$totbeli=0;$no_fakjual='';$tgl_fakjual='0000-00-00';$disc=0;$jumlah=0;$totlaba=0;$totongkir=0;$mar=0;
    	      	if ($cek && $cek !== false) {
    	      	  while($databay=mysqli_fetch_assoc($cek)){
      	      	  $no++;	
      	          $jumlah=$databay['tot_jual']-$databay['tot_disc'];
                  $totbeli=$totbeli+$jumlah;
                  $totlaba=$totlaba+$databay['tot_laba'];
                  $totongkir=$totongkir+$databay['ongkir'];
      	      	  $jml_brg=hitjmlbrg($databay['no_fakjual'],$databay['tgl_jual'],$kd_toko);
                  
                  if ($totlaba>0){
                    if ($jumlah!=0){
                      $mar=($databay['tot_laba']/$jumlah)*100;    
                    } else {
                      $mar=0;
                    }   
                  }else {
                    $mar=0;  
                  }
                  
                  if ($databay['ket_bayar']=='BELUM'){ 
                   
      	      	  ?>

                  <tr style="color: blue;font-weight: bold">
      	            <td style="text-align:right;border-left: 1px solid"><?php echo $no.'.';?></td>
      	            <td style="text-align:center;"><?php echo gantitgl($databay['tgl_jual']);?></td>
      	            <td style="text-align:left;"><?php echo $databay['no_fakjual'].' (TEMPO)';?></td>
      	            <td style=";text-align:center;"><?php echo $jml_brg; ?></td>
                    <td style="text-align:right;"><?php echo gantitides($databay['tot_jual']); ?></td>
                    <td style="text-align:right;"><?php echo gantitides($databay['tot_disc'])?></td>
                    <td style="text-align:right;"><?php echo gantitides($jumlah); ?></td>
      	            <td style=";text-align:right;"><?php echo gantitides($databay['tot_laba']);?></td>
                    <td style="font-style: bold;text-align:right;"><?php echo gantitides($mar).'%'; ?></td>
                    <td style="font-style: bold;text-align:right;border-right: 1px solid"><?php echo gantitides($databay['ongkir']);?></td>
                  </tr>	
    	        <?php           
                  }else{ ?>
                   <tr>
                    <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
                    <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($databay['tgl_jual']);?></td>
                    <td style="text-align:left;font-size: 8pt"><?php echo $databay['no_fakjual'];?></td>
                    <td style="text-align:center;font-size: 8pt"><?php echo $jml_brg; ?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($databay['tot_jual']); ?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($databay['tot_disc'])?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($jumlah); ?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($databay['tot_laba']);?></td>
                    <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($mar).'%'; ?></td>
                    <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo gantitides($databay['ongkir']);?></td>
                  </tr>   
              <?php 
                  }     
    	      	  }
    	      	}
              
              // untuk data retur
                    $sqlret  = mysqli_query($connect,"SELECT * FROM retur_jual 
                    LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut 
                    LEFT JOIN mas_jual ON retur_jual.no_fakjual=mas_jual.no_fakjual
                    LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                    WHERE retur_jual.tgl_retur>='$tgl1' AND retur_jual.tgl_retur<='$tgl2' AND  retur_jual.kd_toko='$kd_toko' 
                    ORDER BY retur_jual.no_urutretur ASC ");

                    $totsub=0;$ret=0;$totlabaret=0;
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
                          <td style="text-align:left;font-size: 8pt"><?php echo $dret['no_fakjual'].' (RETUR)';?></td>
                          <td style="text-align:center;font-size: 8pt"><?php echo $dret['qty_retur'].strtolower($dret['nm_sat1'])?></td>
                          <td style="text-align:right;font-size: 8pt"><?php echo gantitides($dret['hrg_jual']); ?></td>
                          <td style="text-align:right;font-size: 8pt"><?php echo $diskon; ?></td>
                          <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($jmlsub); ?>&nbsp;</td>
                          <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($dret['laba']); ?>&nbsp;</td>
                          <td style="text-align:right;font-size: 8pt;"><?php echo '0,00' ?>&nbsp;</td>
                          <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo '0,00' ?>&nbsp;</td>
                        </tr>
                      <?php
                      }
                      mysqli_free_result($sqlret);
                    }
              ?>
    	        <tr cellspacing="2" class="yz-theme-l2">
                <th colspan=6 align="center" class="w3-padding-small">Total</th>
                <th style="text-align:right"><?php echo gantitides($totbeli) ?></th>
                <th style="text-align:right"><?php echo gantitides($totlaba) ?></th>
                <th></th>
                <th style="text-align:right"><?php echo gantitides($totongkir) ?></th>
              </tr>
              
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr ><td style="border: none"></td></tr>
              <tr ><th colspan="7" style="border:none"></th>
                   <th colspan="3" style="border-top: none;border-left: none;border-right: none;border-bottom: 1px solid ;font-size: 10pt;text-align: left">CATATAN</th>
              </tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr ><td style="border: none"></td></tr>
            
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr ><td style="border: none"></td></tr>
              <tr style="font-size: 9pt">
                <th colspan="7" style="border: none"></th>
                <th colspan="2" style="border: none;text-align: right">Retur Barang = Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($totlabaret)?></th>
              </tr>
              <tr style="font-size: 9pt">
                <th colspan="7" style="border: none"></th>
                <th colspan="2" style="border: none;text-align: right">Laba Piutang Masuk = Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($labamsk)?></th>
              </tr>
              <tr style="font-size: 9pt;border: none">
                <th colspan="7" style="border: none"></th>
                <th colspan="2" style="border: none;text-align: right">Laba Ditahan = Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($tot_laba_th_d-$tot_laba_ms_d)?></th>
              </tr>
              <tr style="font-size: 9pt">
                <th colspan="7" style="border: none"></th>
                <th colspan="2" style="border: none;text-align: right">Laba Dibukukan = Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($totlaba-($tot_laba_th_d-$tot_laba_ms_d)-$totlabaret+$labamsk)?></th>
              </tr>

    	      </table>
    	    <?php  	
          } else {
            // Tampilkan pesan jika tidak ada data
            ?>
            <div style="padding:20px;text-align:center;">
              <h3>Tidak ada data penjualan untuk periode <?=gantitgl($tgl1)?> sampai <?=gantitgl($tgl2)?></h3>
              <?php if (!$cek) { ?>
                <p style="color:red;">Error: <?=mysqli_error($connect)?></p>
              <?php } ?>
            </div>
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
      $connect1 = opendtcek();
      // Escape input untuk mencegah SQL injection
      $no_fakjual = mysqli_real_escape_string($connect1, $no_fakjual);
      $tgl_jual = mysqli_real_escape_string($connect1, $tgl_jual);
      $kd_toko = mysqli_real_escape_string($connect1, $kd_toko);
      
      $cek=mysqli_query($connect1,"SELECT COUNT(*) AS jumlah FROM dum_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko'");
      if ($cek && $cek !== false) {
        $getjml = mysqli_fetch_array($cek);
        $result = mysqli_escape_string($connect1,gantitides($getjml['jumlah']));
        mysqli_free_result($cek);
        mysqli_close($connect1);
        unset($cek,$getjml);
        return $result;
      }
      mysqli_close($connect1);
      return '0,00';
	}
	function carisaldo($no_fakjual,$tgl_jual,$kd_toko){
      $connect2 = opendtcek();
      // Escape input untuk mencegah SQL injection
      $no_fakjual = mysqli_real_escape_string($connect2, $no_fakjual);
      $tgl_jual = mysqli_real_escape_string($connect2, $tgl_jual);
      $kd_toko = mysqli_real_escape_string($connect2, $kd_toko);
      
      $cek=mysqli_query($connect2,"SELECT saldo_awal FROM mas_hutang_jual where no_fakjual='$no_fakjual' and tgl_jual='$tgl_jual' and kd_toko='$kd_toko' ORDER BY no_urut ASC LIMIT 1");
      if ($cek && $cek !== false) {
        $getsld = mysqli_fetch_array($cek);
        $result = mysqli_escape_string($connect2,$getsld['saldo_awal']);
        mysqli_free_result($cek);
        mysqli_close($connect2);
        unset($cek,$getsld);
        return $result;
      }
      mysqli_close($connect2);
      return '0';
	}
?>
</page>
<?php mysqli_close($connect); ?>
