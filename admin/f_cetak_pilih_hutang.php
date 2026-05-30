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
 $pilih=$_POST['pilih2'];
 $kd_supcari=$_POST['kd_sup2'];
 $nm_sup=$_POST['nm_sup2'];
 $ket=$_POST['kethut'];

 if ($ket=='' || $ket=='semua'){
   $param="AND beli_bay.ket='TEMPO'";   
 } 
 if ($ket=='lunas'){
   $param="AND beli_bay.saldo_hutang=0 AND beli_bay.ket='TEMPO'";   
 } 
 if ($ket=='belum'){
   $param="AND beli_bay.saldo_hutang>0 AND beli_bay.ket='TEMPO'";   
 } 


 if($pilih=='alldata')
 {
  $datain=mysqli_query($connect,"SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tot_beli,beli_bay.tgl_jt,beli_bay.saldo_hutang,supplier.nm_sup FROM beli_bay
    LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup 
    WHERE beli_bay.kd_toko='$kd_toko' $param ORDER BY supplier.nm_sup");
  $judul=' (semua hutang)';
 }

 if($pilih=='supplier'){ 
  $datain=mysqli_query($connect,"SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tot_beli,beli_bay.tgl_jt,beli_bay.saldo_hutang,supplier.nm_sup FROM beli_bay
    LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
       WHERE beli_bay.kd_toko='$kd_toko' AND beli_bay.kd_sup='$kd_supcari' $param
       ORDER BY beli_bay.tgl_fak");
  $judul= $nm_sup;
 }
 
 // proses pencetakan

 if(mysqli_num_rows($datain)>=1)
 { 
  ?>
   <body class="F4 ">      
     <section class="sheet padding-10mm">  
        <div style="page-break-before: always;">
       	  <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
            <thead>
                <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>Daftar Hutang Pada Supplier <?=$judul?></b></td></tr>   
                <tr style="background-color: lightgrey;color: black">
                    <th style="width:3%;">NO</th>
                    <th style="width:9%">TANGGAL</th>
                    <th style="width:18%">NO. FAKTUR</th>
                    <th>SUPPLIER</th>
                    <th style="width:12%">INVOICE</th>
                    <th style="width:12%">SISA HUTANG</th>
                    <th style="width:9%">JATUH TEMPO</th>
                </tr>       
            </thead> 
            <?php 

            $no=0;$stok1=0;$stok2=0;
            while($sql=mysqli_fetch_assoc($datain))
            { 
              $no++;
              $stok1=$stok1+$sql['tot_beli'];
              $stok2=$stok2+$sql['saldo_hutang'];
            	?>
    		      <tbody >
                <tr>
                  <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
                  <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sql['tgl_fak']);?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['no_fak']; ?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['nm_sup']; ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['tot_beli']); ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['saldo_hutang']); ?></td>
                  <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo gantitgl($sql['tgl_jt']); ?></td>
                </tr>
              </tbody>       
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
