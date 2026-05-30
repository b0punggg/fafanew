<link rel="stylesheet" href="../assets/css/paper.css">
<link rel="stylesheet" type="text/css" href="../assets/css/w3.css">

<style>
	th
    {
        text-align: center;
        border: solid 1px #113300;
        padding:10px;
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
      #exl {
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
 $sql=mysqli_fetch_assoc($cektoko);
 $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
 $al_toko=mysqli_escape_string($connect,$sql['al_toko']);

 unset($cektoko,$sql); 
 $a           = 0;
 $tgl1        = $_POST['tglrek1'];
 $tgl2        = $_POST['tglrek2'];
 $id_user     = $_POST['user'];
 
$datain=mysqli_query($connect,"SELECT dum_mutol.nm_brg,dum_mutol.kd_brg,dum_mutol.kd_toko, SUM(dum_mutol.keluar) AS jumkel, toko.nm_toko FROM dum_mutol 
LEFT JOIN toko ON dum_mutol.kd_toko=toko.kd_toko
WHERE dum_mutol.id_user='$id_user' GROUP BY dum_mutol.nm_brg,dum_mutol.kd_toko ORDER BY dum_mutol.nm_brg ASC");

// proses pencetakan
if(mysqli_num_rows($datain)>0) { ?>
  <body class="F4">      
    <section class="sheet padding-10mm">  
        <div style="page-break-before: always;">
            <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
                <thead>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt;border:none"><b>Rekap Penjualan Online Toko FAFA</b></td></tr> 
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt;border:none"><b>Dari Tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?> </b></td></tr>   
                <tr style="background-color: lightgrey;color: black;font-size: 8pt">
                    <th style="width:3%;">NO</th>
                    <th>NAMA BARANG</th>
                    <th style="width:4%">KELUAR</th>
                    <th style="width:4%">STOK AKHIR</th>
                    <th style="width:8%">TOKO</th>
                </tr>       
                </thead> <?php 
                $no=0;$tot=0;$tot2=0;$nm_brg='';$tgl='0000-00-00';
                while($sql=mysqli_fetch_assoc($datain))
                { 
                $nm_brg  = $sql['nm_brg'];
                $nm_toko = $sql['nm_toko'];
                $stok    = carist_ak($sql['kd_brg'],$sql['kd_toko'],$connect);
                $tot     = $tot+$sql['jumkel'];
                $no++; ?>
                    <tr>
                      <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
                      <td style="text-align:left;font-size: 8pt">&nbsp;<?php echo $sql['nm_brg']; ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitides($sql['jumkel']); ?></td>
                      <td style="text-align:center;font-size: 8pt"><?php echo gantitides($stok); ?></td>
                      <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $sql['nm_toko']; ?></td> 
                    </tr><?php             
                } ?>
                <tr style="background-color: lightgrey;color: black;font-size: 8pt">
                  <th colspan=2>JUMLAH &nbsp;</th>
                  <th  align="center"><?=gantitides($tot)?> &nbsp;</th>
                  <th colspan=2></th>
                </tr>
            </table>  
        </div>
        
        <form action="f_cetak_mutasi_ol_ex.php" method="POST" target="_blank" style="display: none;">
          <input type="text" name="tgl1" value="<?=$tgl1?>">
          <input type="text" name="tgl2" value="<?=$tgl2?>">
          <button type="submit" id="btnexp"></button>
        </form>
        <div class="w3-row w3-margin-top">
            <div class="w3-col w3-center">
                <button type="button" id="printPageButton" class="w3-button w3-green" onclick="window.print();" style="border-radius:5px;font-size:9pt">Cetak PDF</button>      
                <button type="button" id="exl" class="w3-button w3-yellow" onclick="document.getElementById('btnexp').click()" style="border-radius:5px;font-size:9pt">Export Exel</button>      
            </div>
        </div>
    </section>
  </body> <?php 
}
mysqli_close($connect);
?>
