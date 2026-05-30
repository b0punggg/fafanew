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
 $tgl1=$_POST['tglmut1'];
 $tgl2=$_POST['tglmut2'];
 $pilih=$_POST['pilihmut'];
 $kd_tokocari=$_POST['kd_tokomut'];
 //$nm_toko=$_POST['nm_tokomut'];

 if ($pilih=='alldata'){
   $param="";   
 } else {
   $param=" AND mutasi_brg.kd_toko="."'".$kd_tokocari."'";   
 }
 
  $datain=mysqli_query($connect,"SELECT mutasi_brg.no_urut,mutasi_brg.tgl_mut,mutasi_brg.no_fak,mutasi_brg.qty_brg,mutasi_brg.kd_sat,mutasi_brg.kd_brg,mutasi_brg.ket,mutasi_brg.kd_toko,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3 FROM mutasi_brg
    -- LEFT JOIN toko ON mutasi_brg.kd_toko=toko.kd_toko 
    LEFT JOIN mas_brg ON mutasi_brg.kd_brg=mas_brg.kd_brg
    WHERE mutasi_brg.tgl_mut>='$tgl1' AND mutasi_brg.tgl_mut <='$tgl2' $param
    ORDER BY mutasi_brg.tgl_mut");
  
 
 // proses pencetakan

 if(mysqli_num_rows($datain)>=1)
 { ?>
   <body class="F4 landscape">      
     <section class="sheet padding-10mm">  
        <div style="page-break-before: always;">
       	  <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
            <thead>
                <tr><td colspan="10" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td colspan="10" style="text-align: center;font-size: 9pt;border:none"><b>[ <?=$kd_toko?> ]</b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>List Mutasi Barang </b></td></tr>   
                <tr style="background-color: lightgrey;color: black;font-size: 9pt">
                    <th style="width:3%;">NO</th>
                    <th style="width:6%">TGL.MUTASI</th>
                    <th style="width:11%">FAKTUR BELI</th>
                    <th style="width:19%">KD. BARANG</th>
                    <th>BARANG</th>
                    <th style="width:6%">JML</th>
                    <th style="width:4%">SAT</th>
                    <th style="width:10%">HRG.JUAL</th>
                    <th style="width:6%">ASAL</th>
                    <th>TUJUAN</th>
                </tr>       
            </thead> 
            <?php 

            $no=0;$tot1=0;$tot2=0;
            while($sql=mysqli_fetch_assoc($datain))
            { 
              $no++;
              $nm_sat=ceknmkem2($sql['kd_sat'],$connect);
              
              // cek hrg jual
              if ($sql['kd_sat']==$sql['kd_kem1']){
                $hrg_beli=$sql['hrg_jum1']*$sql['qty_brg'];
              }
              if ($sql['kd_sat']==$sql['kd_kem2']){
                $hrg_beli=$sql['hrg_jum2']*$sql['qty_brg'];
              }
              if ($sql['kd_sat']==$sql['kd_kem3']){
                $hrg_beli=$sql['hrg_jum3']*$sql['qty_brg'];
              }
              $tot1=$tot1+$hrg_beli;
            	?>
    		      <tbody >
                <tr>
                  <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
                  <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sql['tgl_mut']);?></td>
                  <td style="text-align:left;font-size: 8pt"><?php echo $sql['no_fak']; ?></td>
                  <td style="text-align:left;font-size: 8pt"><?php echo $sql['kd_brg']; ?></td>
                  <td style="text-align:left;font-size: 8pt"><?php echo $sql['nm_brg']; ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($sql['qty_brg']); ?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $nm_sat; ?></td>
                  <td style="text-align:right;font-size: 8pt"><?php echo gantitides($hrg_beli); ?></td>
                  <td style="text-align:center;font-size: 8pt"><?php echo $sql['kd_toko']; ?></td> 
                  <td style="text-align:center;font-size: 8pt;border-right: 1px solid"><?php echo $sql['ket']; ?></td>
                </tr>
              </tbody>       
	            <?php             
            }
            ?>
            <tr style="background-color: lightgrey;color: black;font-size: 9pt">
              <th colspan="7" style="text-align: right">TOTAL &nbsp;</th>
              <th style="text-align: right"><?=gantitides($tot1)?></th>
              <!-- <th style="text-align: right"><?=gantiti($stok2)?></th> -->
              <th colspan="2"></th>
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
