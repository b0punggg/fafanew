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

 //judul paling atas
 $kd_toko  = $_SESSION['id_toko'];
 $nm_toko  = "";
 $cektoko=mysqli_query($connect,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
 $sql=mysqli_fetch_assoc($cektoko);
 $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
 $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
 unset($cektoko,$sql); 
 $a=0;
 //------------
 function caribrgmsk($kd_brg,$kd_toko){
	  // $tgl_hi=date('Y-m-d');
	  $jml_brg=0;$jml_brg_kov=0;
	  $con = mysqli_connect("localhost","root", "", "toko_retail");
	  $cek=mysqli_query($con,"SELECT * FROM beli_brg where kd_brg='$kd_brg' and kd_toko='$kd_toko'order by no_urut ASC");
	  if(mysqli_num_rows($cek)>=1){
	  	while ($data=mysqli_fetch_assoc($cek)) {
	  	  // $jml_brg_kov=konjumbrg($data['kd_sat'],$kd_brg,$kd_toko)*$data['jml_brg'];
	  	  // $jml_brg=$jml_brg+$jml_brg_kov;
	  	  $jml_brg=$jml_brg+$data['stok_jual'];	
	  	}
	  }
	  return $jml_brg;
	  unset($data,$cek);
	  mysqli_close($con);
	}		
 $pilih=$_POST['pilih3'];
 $kd_pelcari=$_POST['kd_pel3'];
 $nm_pel=$_POST['nm_pel3'];
 $ket=$_POST['ketput'];
 
 if ($ket=='' || $ket=='semua'){
   $param="AND mas_jual.kd_bayar='TEMPO'";   
 } 
 if ($ket=='lunas'){
   $param="AND mas_jual.saldo_hutang=0 AND mas_jual.kd_bayar='TEMPO'";   
 } 
 if ($ket=='belum'){
   $param="AND mas_jual.saldo_hutang>0 AND mas_jual.kd_bayar='TEMPO' ";   
 } 

 if($pilih=='alldata')
 {
   // echo 'alldata'.' jml_stok='.$jml_stok;
  $datain=mysqli_query($connect,"SELECT mas_jual.no_fakjual,mas_jual.tgl_jual,mas_jual.tot_jual,mas_jual.tot_disc,mas_jual.tgl_jt,mas_jual.saldo_hutang,pelanggan.nm_pel,pelanggan.nm_pel,pelanggan.al_pel  FROM mas_jual
    LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel 
       WHERE mas_jual.kd_toko='$kd_toko' $param
       ORDER BY pelanggan.nm_pel");

  $judul=' (semua piutang)';
 }

 if($pilih=='pelanggan'){
   
  $datain=mysqli_query($connect,"SELECT mas_jual.no_fakjual,mas_jual.tgl_jual,mas_jual.tot_jual,mas_jual.tot_disc,mas_jual.tgl_jt,mas_jual.saldo_hutang,pelanggan.nm_pel,pelanggan.al_pel  FROM mas_jual
    LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel 
       WHERE mas_jual.kd_toko='$kd_toko' AND mas_jual.kd_pel='$kd_pelcari' $param
       ORDER BY mas_jual.tgl_jual");
  $judul= $nm_pel;
 }
 
 // proses pencetakan

 if(mysqli_num_rows($datain)>=1)
 { ?>
   <body class="F4 ">      
     <section class="sheet padding-10mm">  
        <div style="page-break-before: always;">
       	  <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
            <thead>
                <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>Daftar Piutang Pelanggan <?=$judul?></b></td></tr>   
                <tr style="background-color: lightgrey;color: black">
                    <th style="width:3%;">NO</th>
                    <th style="width:9%">TANGGAL</th>
                    <th style="width:18%">NO. KWITANSI</th>
                    <th>PELANGGAN</th>
                    <th style="width:12%">INVOICE</th>
                    <th style="width:12%">SISA PIUTANG</th>
                    <th style="width:9%">JATUH TEMPO</th>
                </tr>       
            </thead> 
            <?php 

            $no=0;$stok1=0;$stok2=0;
            while($sql=mysqli_fetch_assoc($datain))
            { 
              $no++;
              $stok1=$stok1+($sql['tot_jual']-$sql['tot_disc']);
              $stok2=$stok2+$sql['saldo_hutang'];
            	?>
    		      
                <tr>
                  <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
                  <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sql['tgl_jual']);?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['no_fakjual']; ?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['nm_pel'].' d/a '.$sql['al_pel']; ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['tot_jual']-$sql['tot_disc']); ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['saldo_hutang']); ?></td>
                  <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo gantitgl($sql['tgl_jt']); ?></td>
                </tr>
                     
	            <?php             
            }
            ?>
            <tr>
              <th colspan="4">Total</th>
              <th style="text-align: right"><?=gantitides($stok1)?></th>
              <th style="text-align: right"><?=gantitides($stok2)?></th>
              <th></th>
            </tr>
          </table>  
        </div>
     </section>
    </body>    
    <script>window.print()</script>
 <?php 
}
mysqli_close($connect);
?>
