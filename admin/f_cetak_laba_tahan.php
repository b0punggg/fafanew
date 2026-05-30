<link rel="stylesheet" href="../assets/css/paper.css">
<?php
    session_start();
    include 'config.php';
    $connect=opendtcek();
?>
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
<body class="F4 ">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
        <?php 
        //ini_set('memory_limit', '1024M'); // or you could use 1G 
        $pilihan  = $_POST['pilihmutth'];
    	  $kd_toko  = $_SESSION['id_toko'];
        $nm_toko  = "";$toko  = "";
        $x        = explode('-', $_SESSION['tgl_set']);
        $endbln   = $x[1];
        $endyear  = $x[0];
        $tglhi    = $_SESSION['tgl_set'];
        unset($x);

        $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
        $sql=mysqli_fetch_assoc($cektoko);
        $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
        $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
        unset($cektoko,$sql); 
        if ($pilihan=='alldata'){
          $cek=mysqli_query($connect,"SELECT * FROM mas_jual where kd_bayar='TEMPO' ORDER BY mas_jual.no_urut ASC");   
          // $cekmas2=mysqli_query($connect,"SELECT mas_jual_hutang.tgl_tran,mas_jual_hutang.byr_hutang AS angsur,mas_jual.* FROM mas_jual_hutang 
          //   LEFT JOIN mas_jual ON mas_jual_hutang.no_fakjual= mas_jual.no_fakjual 
          //   WHERE 
          //   mas_jual.kd_bayar='TEMPO' AND 
          //   MONTH(mas_jual_hutang.tgl_tran)='$endbln' AND 
          //   YEAR(mas_jual_hutang.tgl_tran)='$endyear'
          //   ORDER BY mas_jual_hutang.no_fakjual,mas_jual_hutang.no_urut ASC");

          // $cekas=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.kd_toko,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.ppn,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3 FROM beli_brg
          // LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
          // WHERE  beli_brg.stok_jual > 0
          // ORDER by beli_brg.kd_toko ASC");
        }else {
          $toko=$_POST['kd_tokotahan'];
          $cek=mysqli_query($connect,"SELECT * FROM mas_jual where ket_bayar='BELUM' AND kd_toko='$toko' ORDER BY mas_jual.no_urut ASC");   

          // $cekmas2=mysqli_query($connect,"SELECT mas_jual_hutang.tgl_tran,mas_jual_hutang.byr_hutang AS angsur,mas_jual.* FROM mas_jual_hutang 
          //   LEFT JOIN mas_jual ON mas_jual_hutang.no_fakjual= mas_jual.no_fakjual 
          //   WHERE mas_jual_hutang.kd_toko='$toko' AND 
          //   mas_jual.kd_bayar='TEMPO' AND 
          //   MONTH(mas_jual_hutang.tgl_tran)='$endbln' AND 
          //   YEAR(mas_jual_hutang.tgl_tran)='$endyear'
          //   ORDER BY mas_jual_hutang.no_fakjual,mas_jual_hutang.no_urut ASC");

          // $cekas=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.kd_toko,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.ppn,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3 FROM beli_brg
          // LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
          // WHERE  beli_brg.stok_jual > 0 AND beli_brg.kd_toko='$toko'
          // ORDER by beli_brg.kd_toko ASC");
        }
          //hitung total asset barang
          // $hrg_belidiscx=0;$hrg_belidisc=0;$hrg_asal=0;$totas=0;
          // while($data=mysqli_fetch_assoc($cekas)){
          //   $disc1=$data['disc1']/100;
          //   $disc2=$data['disc2'];
          //    if($data['kd_sat']==$data['kd_kem3']){
          //     $hrg_asal=$data['hrg_beli']/$data['jum_kem3'];
          //   }
          //   if($data['kd_sat']==$data['kd_kem2']){
          //     $hrg_asal=$data['hrg_beli']/$data['jum_kem2'];
          //   }
          //   if($data['kd_sat']==$data['kd_kem1']){
          //     $hrg_asal=$data['hrg_beli']/$data['jum_kem1'];
          //   }
          //   // ------------------------  

          //   if ($data['disc1']==0.00 && $data['disc2']==0){
          //     $hrg_belidisc=$hrg_asal*$data['stok_jual'];
          //   } else if ($data['disc1'] > 0.00 && $data['disc2']==0) {
          //     $hrg_belidisc=($hrg_asal-($hrg_asal*$disc1))*$data['stok_jual'];
          //   } else if ($data['disc1'] == 0.00 && $data['disc2']>0) {
          //     $hrg_belidisc=($hrg_asal-$disc2)*$data['stok_jual'];
          //   }
          //   $hrg_belidiscx=$hrg_belidisc+($hrg_belidisc*($data['ppn']/100));
          //   $totas=$totas+$hrg_belidiscx;  
          // } 
          // unset($data,$cekas);

          if(mysqli_num_rows($cek)>=1){
            ?>
             
    	      <table cellspacing="0" style="width: 100%; font-size: 8pt;">
              <thead>
                <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>Laporan modal & Laba ditahan (Piutang)</b></td></tr>
               <tr style="background-color: lightgrey;">
    	            <th style="width:5%;">NO</th>
    	            <th style="width:10%">TGL. JUAL</th>
    	            <th >NO. NOTA</th>
    	            <!-- <th style="width:8%">JML. BRG</th> -->
                  <th style="width:12%">TOT. OMSET PIUTANG</th>
                  <!-- <th style="width:8%">TOT. DISC</th> -->
                  <!-- <th style="width:12%">TOT. JUAL NETT</th> -->
                  <th style="width:12%">TOT. MODAL DITAHAN</th>
                  <th style="width:12%">TOT. LABA DITAHAN</th>
                  <th style="width:12%">MODAL MASUK BERJALAN</th>
                  <th style="width:12%">LABA MASUK BERJALAN</th>
                  <!-- <th style="width:10%">ASSET MASUK BULAN INI</th>
                  <th style="width:10%">LABA MASUK BULAN INI</th> -->
                  
    	         </tr> 
              </thead>   
    	        <?php
    	        $no=0;$totbeli=0;$no_fakjual='';$tot_as_th=0;$tot_as_ms=0;$tot_laba_th=0;$tot_laba_ms=0;$hrg_beli=0;$uang_ms=0;$aset_ms=0;$laba_ms=0;$tot_jual=0;$tot_lb_bi=0;$tot_as_bi=0;
    	      	while($databay=mysqli_fetch_assoc($cek)){
      	      	  $no++;	
                  $hrg_beli=($databay['tot_jual']-$databay['tot_disc'])-$databay['tot_laba'];
                  $uang_ms =($databay['tot_jual']-$databay['tot_disc']-$databay['saldo_hutang']);
                  if ($uang_ms<=$hrg_beli) {
                     $aset_ms    =$uang_ms; 
                     $laba_ms    =0;
                     
                  }else {
                     $aset_ms    =$hrg_beli; 
                     $laba_ms    =$uang_ms-$hrg_beli;
                     
                  }
                  $xthn=date('Y', strtotime($databay['tgl_jual']));
                  $xbln=date('m', strtotime($databay['tgl_jual']));
                  
                  if ($xbln==$endbln && $xthn==$endyear){
                    $tot_lb_bi=$tot_lb_bi+ $laba_ms;
                    $tot_as_bi=$tot_as_bi+ $aset_ms;
                  }
                  $tot_as_ms  =$tot_as_ms+$aset_ms;   
                  $tot_laba_ms=$tot_laba_ms+$laba_ms;
      	          $tot_as_th=$tot_as_th+$hrg_beli;
                  $tot_laba_th=$tot_laba_th+$databay['tot_laba'];
                  $tot_jual=$tot_jual+($databay['tot_jual']-$databay['tot_disc']);
                  ?>
                  <tr>
    	            <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?></td>
    	            <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($databay['tgl_jual']);?></td>
    	            <td style="text-align:left;font-size: 8pt"><?php echo $databay['no_fakjual'];?></td>
    	            <td style="text-align:right;font-size: 8pt"><?php echo gantitides($databay['tot_jual']-$databay['tot_disc']); ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($hrg_beli)?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($databay['tot_laba']);?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($aset_ms); ?></td>
    	            <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($laba_ms); ?></td>
                  
                  </tr>	
    	        <?php           
    	      	}
    	        ?>
    	        <tr cellspacing="2" style="background-color: lightgrey;color: black;font-size: 8pt">
                <th colspan=3 align="center">TOTAL</th>
                <th style="text-align:right"><?php echo gantitides($tot_jual) ?></th>
                <th style="text-align:right"><?php echo gantitides($tot_as_th) ?></th>
                <th style="text-align:right"><?php echo gantitides($tot_laba_th) ?></th>
                <th style="text-align:right"><?php echo gantitides($tot_as_ms) ?></th>
                <th style="text-align:right"><?php echo gantitides($tot_laba_ms) ?></th>
              </tr>   
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">Total Piutang Awal Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($tot_jual)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">Total Pembayaran Piutang Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($tot_as_ms+$tot_laba_ms)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">Total Modal Ditahan Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides( $tot_as_th- $tot_as_ms)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">Total Laba Ditahan Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides( $tot_laba_th- $tot_laba_ms)?></th>
              </tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">Total Modal Masuk Bulan Ini Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($tot_as_bi)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">Total Laba Masuk Bulan Ini Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($tot_lb_bi)?></th>
              </tr> 
              <!-- <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>
              <tr><td style="border: none"></td></tr>           
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">UPDATE BULAN INI</th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">Total Modal Piutang Masuk Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($x_aset)?></th>
              </tr>
              <tr style="font-size: 10pt;">
                <th colspan="7" style="border: none;text-align: right">Total Laba Piutang Masuk Rp.</th>
                <th style="border: none;text-align: right"><?=gantitides($x_bunga)?></th>
              </tr> -->
              
            </table>
    	    <?php  	
          }?>
    </div>
  </section>
</body>             

<?php mysqli_close($connect); ?>
<script>window.print();</script>