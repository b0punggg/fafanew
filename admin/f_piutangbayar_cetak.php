<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pembayaran Piutang</title>
    <link rel="shortcut icon" href="img/keranjang.png">
    <link rel="stylesheet" href="../assets/css/paper.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
    <link rel="stylesheet" href="../assets/css/blue-themes.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</head>
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
    @media print {
      #printPageButton {
        display: none;
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

 $a=0;$no_fakjual='';$tgl_jual='0000-00-00';
 //------------
 if(isset($_GET['pesan'])){
  $pesan=$_GET['pesan'];
  $x=explode(';',$pesan);
  $no_fakjual=$x[0];
  $tgl_jual=$x[1];
  $kd_pel=$x[2];
 } 
 
 $cek=mysqli_query($connect,"SELECT * FROM pelanggan WHERE kd_pel='$kd_pel'");
 $data=mysqli_fetch_array($cek);
 $nm_pel=$data['nm_pel'];
 $al_pel=$data['al_pel'];
 $no_telp=$data['no_telp'];
 unset($cek,$data);

 $datain =mysqli_query($connect, "SELECT * from mas_jual_hutang 
        WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'
        ORDER BY no_urut");
?>
 
  <!-- proses pencetakan -->
   <body class="F4">      
     <section class="sheet padding-10mm">  
        <div style="page-break-before: always;">
       	  <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
            <thead>
              
                <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                 <tr> <td colspan="6" style="text-align: left;font-size: 8pt;border: none"><b>List Pembayaran PIUTANG </b></td></tr>
                 <tr> <td colspan="6" style="text-align: left;font-size: 8pt;border: none"><b>Pelanggan &nbsp;<?=$nm_pel?></b></td></tr>        
                 <tr> <td colspan="6" style="text-align: left;font-size: 8pt;border: none"><b>No. Faktur/Kwitansi &nbsp;<?=$no_fakjual?>, Tanggal <?=gantitgl($tgl_jual)?> </b></td></tr>   
                 <tr> <td colspan="6" style="text-align: left;font-size: 8pt;border: none"><b>Alamat :&nbsp;<?=$al_pel?>&nbsp; , No.TELP/HP : &nbsp;<?=$no_telp?> </b></td></tr>   
                <tr class="yz-theme-l3">
                    <th style="width:4%;">NO</th>
                    <th style="width:9%">TGL. BAYAR</th>
                    <th style="width:9%">SISA AWAL</th>
                    <th style="width:9%">BAYAR</th>
                    <th style="width:9%">SISA AKHIR</th>
                </tr>       
            </thead> 
            <?php 

            $no=0;$stok1=0;$stok2=0;$stok3=0;$nm_kem1='';$nm_kem2='';$nm_kem3='';$tot=0;$tgl_jt="0000-00-00";$tgllunas='';
            while($sql=mysqli_fetch_assoc($datain)){ 
              $no++; 
              $tot=$tot+$sql['byr_hutang'];
              $tgl_jt=$sql['tgl_jt'];
              if ($sql['saldo_hutang']==0){ 
                 $tgllunas='* Telah Lunas Tanggal '. gantitgl($sql['tgl_tran']);
              } else { $tgllunas='* Piutang Pelanggan Masih Berjalan'; }
             ?>
            	<tbody >
                  <tr>
                    <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitgl($sql['tgl_tran']);?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['saldo_awal']);?></td>
                    <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['byr_hutang']); ?></td>
                    <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo gantitides($sql['saldo_hutang']); ?></td>
                  </tr>
              </tbody>     
            <?php
            }
            ?>
            <tfoot >
              <tr class="yz-theme-l3">
                <th colspan="3" style="text-align: right;"> Total Bayar</th>
                <th style="text-align: right"><?=gantitides($tot);?></th>
                <th></th>
              </tr>
              <tr><th colspan="5" style="text-align: left"><i><?='# '.terbilang($tot).' #'?></i></th></tr>
              <tr><th colspan="5" style="border:none;font-size: 8pt;text-align: left">* Jatuh tempo pelunasan pembayaran <?=gantitgl($tgl_jt);?></th></tr>
              <tr><th colspan="5" style="border:none;font-size: 8pt;text-align: left"><?=$tgllunas?></th></tr>

            </tfoot>
          </table>  
        </div>
        <div class="row">
          <div class="col-sm w3-center">
            <button id="printPageButton" class="btn btn-sm btn-success w3-margin-top " onclick="window.print();">Cetak PDF</button>      
          </div>
        </div>
     </section>
    </body>    
</html> 
<?php mysqli_close($connect); ?>