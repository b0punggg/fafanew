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
    @page { size: F4 }

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
 $sql2=mysqli_fetch_assoc($cektoko);
 $nm_toko=mysqli_escape_string($connect,$sql2['nm_toko']);
 $al_toko=mysqli_escape_string($connect,$sql2['al_toko']);
 unset($cektoko,$sql2); 

 $a=0;$no_fak='';$tgl_fak='0000-00-00';
 //------------
 if(isset($_GET['pesan'])){
  $pesan=$_GET['pesan'];
  $x=explode(';',$pesan);
  $no_fak=$x[0];
  $tgl_fak=$x[1];
  $kd_sup=$x[2];
 } 
 
 $cek=mysqli_query($connect,"SELECT * FROM supplier WHERE kd_sup='$kd_sup'");
 $data=mysqli_fetch_array($cek);
 $nm_sup=$data['nm_sup'];
 unset($cek,$data);

 $datain =mysqli_query($connect, "SELECT * from beli_bay_hutang 
        WHERE no_fak='$no_fak' AND kd_toko='$kd_toko'
        ORDER BY no_urut");
?>
 
 
  <!-- proses pencetakan -->
   <body class="F4">      
     <section class="sheet padding-10mm">  
        <div style="page-break-before: always;">
       	  <table id="content" cellspacing="0" style="width: 100%; font-size: 9pt;page-break-before: always">
            <thead>
                <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="6" style="text-align: left;font-size: 9pt;border: none"><b>List Pembayaran Hutang </b></td></tr>   
                <tr> <td colspan="6" style="text-align: left;font-size: 9pt;border: none"><b>Supplier &nbsp;<?=$nm_sup?></b></td></tr>   
                <tr> <td colspan="6" style="text-align: left;font-size: 9pt;border: none"><b>No. Faktur/Kwitansi &nbsp;<?=$no_fak?></b></td></tr>   
                <tr style="background-color: lightgrey;color: black">
                    <th style="width:4%;">NO</th>
                    <th style="width:9%">TGL. BAYAR</th>
                    <th style="width:15%">SISA AWAL</th>
                    <th style="width:15%">BAYAR</th>
                    <th style="width:15%">SISA AKHIR</th>
                    <th >KETERANGAN</th>
                </tr>       
            </thead> 
            <?php 

            $no=0;$stok1=0;$stok2=0;$stok3=0;$nm_kem1='';$nm_kem2='';$nm_kem3='';$tot=0;$tgl_jt="0000-00-00";
            while($sql=mysqli_fetch_assoc($datain))
            { $no++; $tot=$tot+$sql['byr_hutang'];$tgl_jt=$sql['tgl_jt'];?>
            	<tbody >
                  <tr>
                    <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
                    <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sql['tgl_tran']);?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['saldo_awal']);?>&nbsp;</td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['byr_hutang']); ?>&nbsp;</td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['saldo_hutang']); ?>&nbsp;</td>
                    <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $sql['via']; ?></td>
                  </tr>
              </tbody>     
            <?php
            }
            ?>
            <tfoot>
              <tr>
                <th colspan="3" style="text-align: right;"> Total Bayar &nbsp;</th>
                <th style="text-align: right"><?=gantitides($tot);?>&nbsp;</th>
                <th colspan="2"></th>
              </tr>
              <tr><th colspan="6" style="text-align: left"><i><?='# '.terbilang($tot).' #'?></i></th></tr>
              <tr><th colspan="6" style="border:none;font-size: 8pt;text-align: left">* Jatuh tempo pelunasan pembayaran <?=gantitgl($tgl_jt);?></th></tr>
            </tfoot>
          </table>  
        </div>
     </section>
    </body>    
    <script>window.print()</script>
<?php mysqli_close($connect); ?>