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
 
 $concet=opendtcek();
 //judul paling atas
 $kd_toko  = $_SESSION['id_toko'];
 $nm_toko  = "";
 $cektoko=mysqli_query($concet,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
 $sql=mysqli_fetch_assoc($cektoko);
 $nm_toko=mysqli_escape_string($concet,$sql['nm_toko']);
 $al_toko=mysqli_escape_string($concet,$sql['al_toko']);

 unset($cektoko,$sql); 
 $a=0;
 // $tgl1=$_POST['tglretbel1'];
 // $tgl2=$_POST['tglretbel2'];
 $pilih=$_POST['pilihaset'];
 $kd_tokocari=$_POST['kd_tokoaset'];
 //$nm_toko=$_POST['nm_tokomut'];

 if ($pilih=='alldata'){
   $param="";   
 } else {
   $param=" AND beli_brg.kd_toko="."'".$kd_tokocari."'";   
 }
  $datain=mysqli_query($concet,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.kd_toko,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,mas_brg.nm_brg,supplier.nm_sup FROM beli_brg
          LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
          LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
          WHERE  beli_brg.stok_jual > 0 $param
          ORDER by beli_brg.kd_toko ASC");
  
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
                <tr><td colspan="10" style="text-align: center;font-size: 9pt;border:none"><b>[ <?=$kd_tokocari?> ]</b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="10" style="text-align: left;font-size: 10pt"><b>List Cek Asset Barang </b></td></tr>   
                <tr style="background-color: lightgrey;color: black">
                  <th style="width: 3%">NO.</th>
                  <th style="width: 7%">KD.TOKO</th>
                  <th style="width: 9%">TGL.BELI</th>
                  <!-- <th style="width: 14%">NO.FAKTUR</th> -->
                  <!-- <th style="width: 16%">SUPPLIER</th>     -->
                  <th>NAMA BARANG 
                  <th style="width: 9%">HRG. BELI</th>    
                  <th style="width: 6%">STOK</th>
                  <th style="width: 3%">SATUAN</th>
                  <th style="width: 7%">DISC</th>    
                  <th style="width: 11%">SUB TOTAL</th> 
                </tr>       
            </thead> 
            <?php 

            $no=0;$tothrg_akhir=0;$tothrg_awal=0;$hrg_beli=0;$hrg_belidisc=0;
            while($data=mysqli_fetch_assoc($datain))
            { 
              $no++;
              $brg_msk_hi=$data['stok_jual'];
                //echo '$brg_msk='.$brg_msk_hi.'<br>';
              $x=explode(';',carisatkecil2($data['kd_brg'],$concet));
              $kd_kem_kcl=$x[0];
              $jum_kem_kcl=$x[1];

              $disc1=$data['disc1']/100;
              $disc2=$data['disc2'];
              $hrg_beli=$data['hrg_beli']/konjumbrg2($data['kd_sat'],$data['kd_brg'],$concet);
              
              if ($data['disc1']==0.00 && $data['disc2']==0){
                $hrg_belidisc=$hrg_beli*$data['stok_jual'];
              } else if ($data['disc1'] > 0.00 && $data['disc2']==0) {
                      $hrg_belidisc=($hrg_beli-($hrg_beli*$disc1))*$data['stok_jual'];
              } else if ($data['disc1'] == 0.00 && $data['disc2']>0) {
                      $hrg_belidisc=($hrg_beli-$disc2)*$data['stok_jual'];
              }
              $tothrg_akhir=$tothrg_akhir+$hrg_belidisc;
              $tothrg_awal=$tothrg_awal+$hrg_beli;
            	?>
    		      <tbody >
                <tr>
                  <td align="right" style="border-left:1px solid"><?php echo $no.'.' ?>&nbsp;</td>
                  <td align="center" style="border-right: none"><?php echo $data['kd_toko']; ?></td>
                  <td align="center" style="border-right: none;border-left: none"><?php echo gantitgl($data['tgl_fak']); ?></td>
                  <!-- <td align="left" style="border-right: none">&nbsp;<?php echo $data['no_fak']; ?></td> -->
                  <!-- <td align="left" style="border-right: none">&nbsp;<?php echo $data['kd_brg']; ?></td> -->
                  <!-- <td align="center" style="border-right: none;border-left: none"><?php echo $data['nm_sup']; ?></td> -->
                  <td align="left" style="border-right: none;border-left: none">&nbsp;<?php echo $data['nm_brg']; ?></td>
                  <td align="right" style="border-right: none;border-left: none"><?php echo gantitides($hrg_beli); ?>&nbsp;</td>    
                  <td align="right" style="border-right: none;border-left: none"><?php echo gantitides($data['stok_jual']); ?>&nbsp;</td>
                  <td align="center" style="border-right: none;border-left: none"><?php echo ceknmkem2($kd_kem_kcl, $concet); ?>&nbsp;</td>

                  <?php if ($data['disc2']>0) { ?>
                  <td align="center" style="border-right: none;border-left: none"><?php echo gantiti($data['disc2']) ?>&nbsp;</td>
                  <?php } else { ?>
                  <td align="right" style="border-right: none;border-left: none"><?php echo gantitides($data['disc1']).' %'?></td>    
                        <?php } ?>
                  <td align="right" style="border-right: 1px solid;border-left: none"><?php echo gantitides($hrg_belidisc); ?>&nbsp;</td>    
                </tr>
              </tbody>       
            
	            <?php             
            }
            ?>
              <tr>
                <th colspan="4" style="text-align: right;border-left:1px solid"><b>TOTAL &nbsp;<b></th>
                <th style="text-align: right"><?=gantitides($tothrg_awal)?></th>
                <th colspan="3"></th>
                <th style="text-align: right;border-right:1px solid"><?=gantitides($tothrg_akhir)?></th>
              </tr>
          </table>  
        </div>
     </section>
    </body>    
    <script>window.print()</script>
 <?php 
}
mysqli_close($concet);
?>
