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
 session_start();
 include 'config.php';
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
 $tgl1=$_POST['tglretbel1'];
 $tgl2=$_POST['tglretbel2'];
 $pilih=$_POST['pilihretbel'];
 $kd_tokocari=$_POST['kd_tokoretbel'];
 //$nm_toko=$_POST['nm_tokomut'];

 if ($pilih=='alldata'){
   $param="";   
 } else {
   $param=" AND retur_beli.kd_toko="."'".$kd_tokocari."'";   
 }
 
  $datain=mysqli_query($connect,"SELECT retur_beli.kembali,retur_beli.kd_toko,retur_beli.tgl_retur,retur_beli.no_retur,retur_beli.kd_sup,retur_beli.no_fak,retur_beli.kd_brg,retur_beli.qty_brg,retur_beli.kd_sat,retur_beli.ketretur,mas_brg.nm_brg,supplier.nm_sup FROM retur_beli
    LEFT JOIN mas_brg ON retur_beli.kd_brg=mas_brg.kd_brg
    LEFT JOIN supplier ON retur_beli.kd_sup=supplier.kd_sup
    WHERE retur_beli.tgl_retur>='$tgl1' AND retur_beli.tgl_retur <='$tgl2' $param
    ORDER BY retur_beli.tgl_retur");
  
 
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
                <tr><td colspan="10" style="text-align: center;font-size: 9pt;border:none"><b>[ <?=$kd_toko?> ]</b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>List Return Pembelian Barang </b></td></tr>   
                <tr style="background-color: lightgrey;color: black">
                    <th style="width:4%;">NO</th>
                    <th style="width:9%">TANGGAL</th>
                    <th>NO.RETUR</th>
                    <th>SUPPLIER</th>
                    <th>FAK.BELI</th>
                    <th>NAMA BRG</th>
                    <th style="width:8%">QTY</th>
                    <th style="width:5%">SAT</th>
                    <th>KETERANGAN</th>
                </tr>       
            </thead> 
            <?php 

            $no=0;$stok1=0;$stok2=0;
            while($sql=mysqli_fetch_assoc($datain))
            { 
              $no++;
              $nm_sat=ceknmkem2($sql['kd_sat'],$connect);
            	?>
    		      <tbody >
                <tr>
                  <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
                  <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sql['tgl_retur']);?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['no_retur']; ?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['nm_sup']; ?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['no_fak']; ?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['nm_brg']; ?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['qty_brg']; ?></td> 
                  <td style="text-align:center;font-size: 8pt"><?php echo $nm_sat; ?></td>
                  <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $sql['ketretur'].'--Kembali '.$sql['kembali']; ?></td>
                </tr>
              </tbody>       
	            <?php             
            }
            ?>
            <!-- <tr>
              <th colspan="4">Total</th>
              <th style="text-align: right"><?=gantiti($stok1)?></th>
              <th style="text-align: right"><?=gantiti($stok2)?></th>
              <th></th>
            </tr> -->
          </table>  
        </div>
     </section>
    </body>    
    <script>window.print()</script>
 <?php 
}
mysqli_close($connect);
?>
