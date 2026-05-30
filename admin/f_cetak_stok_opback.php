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
      #printPerek {
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
 $tgl1        = $_POST['tglop1'];
 $tgl2        = $_POST['tglop2'];
 $pilih       = $_POST['pilihstokop'];
 $kd_tokocari = $_POST['kd_tokostokop'];
 $id_user     = $_SESSION['id_user'];

 if(isset($_POST['urut'])){
   $curut=$_POST['urut'];
 }else{$curut="mutasi_adj.tgl_input"; }
 if ($pilih=='alldata'){
   $param="";   
 } else {
   $param=" AND mutasi_adj.kd_toko="."'".$kd_tokocari."' ";   
 }
 if($_POST['cuser']=='SEMUA'){$cuser="";
 }else{
  $cuser = " AND INSTR(UPPER(mutasi_adj.ket),'".$_POST['cuser']."')>0 "; 
 }
$datain=mysqli_query($connect,"SELECT mutasi_adj.*,mas_brg.nm_brg,toko.nm_toko FROM mutasi_adj
LEFT JOIN mas_brg ON mutasi_adj.kd_brg=mas_brg.kd_brg
LEFT JOIN toko ON mutasi_adj.kd_toko=toko.kd_toko
WHERE mutasi_adj.tgl_input >='$tgl1' AND mutasi_adj.tgl_input <='$tgl2' AND INSTR(UPPER(mutasi_adj.ket),'LINE')=0 $cuser $param
ORDER BY $curut");
 
//proses rekap
// $hp=false;
// $hp=mysqli_query($connect,"DELETE FROM dum_stokop WHERE id_user='$id_user'");

// proses pencetakan
if(mysqli_num_rows($datain)>=1)
{ ?>
<body class="F4">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
      <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
        <thead>
          <tr> <td colspan="10" style="text-align: left;font-size: 10pt;border:none"><b>List Penyesuaian Stok Barang Toko FAFA</b></td></tr> 
          <tr> <td colspan="10" style="text-align: left;font-size: 10pt;border:none"><b>Dari Tanggal <?=gantitgl($tgl1)?> sampai tanggal <?=gantitgl($tgl2)?> </b></td></tr>   
          <tr style="background-color: lightgrey;color: black;font-size: 8pt">
            <th style="width:3%;">NO</th>
            <th style="width:6%;cursor:pointer" onclick="document.getElementById('curut').value='mutasi_adj.tgl_input';document.getElementById('btnurut').click()">TANGGAL</th>
            <th style="cursor:pointer" onclick="document.getElementById('curut').value='mas_brg.nm_brg';document.getElementById('btnurut').click()">NAMA BARANG</th>
            <th style="width:4%">AWAL</th>
            <!-- <th style="width:6%">KELUAR</th> -->
            <th style="width:6%">PENYE-<br>SUAIAN</th>
            <th style="width:8%">TOKO</th>
            <th style="width:25%">KET</th>
          </tr>       
        </thead> <?php 
        $no=$tot=$awal=$akhir=0;$tot2=0;$nm_brg='';$tgl='0000-00-00';
        while($sql=mysqli_fetch_assoc($datain))
        { 
          $string = strtolower($sql['ket']);
          $x      = strpos($string, "men");
          $awal   = str_replace(",",".",substr($string,strpos($string, ":")+1,$x-(strlen($string)+2)));
          
          $y      = strpos($string, ")");
          $akhir  = str_replace(",",".",substr($string,$x+10,$y-strlen($string)));
          
          if($awal<$akhir){
            // echo $sql['no_urut'].'/'.$sql['kd_brg'].'<br>';
            // echo 'akhir='.$akhir.'<br>';
            // echo 'awal='.$awal.'<br>';
            $jml=$akhir-$awal;
          }else{$jml=$awal-$akhir;}     
          $nm_brg = mysqli_escape_string($connect,$sql['nm_brg']);
          $tgl    = $sql['tgl_input'];       
          $kd_brg = mysqli_escape_string($connect,$sql['kd_brg']);       
          $tot=$tot+$jml;
          // mysqli_query($connect,"INSERT INTO dum_stokop VALUES('','$kd_brg','$nm_brg','$jml','$tgl','$kd_toko','$id_user')");
          $no++; 
       
          if(round($awal,0)==round($akhir,0)){ ?>
            <tr style="color:black"> <?php     
          }else{ ?>
            <tr style="color:red"> <?php        
          } ?> 
              <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
              <td style="text-align:center;font-size: 8pt"><?php echo gantitgl($sql['tgl_input']);?></td>
              <td style="text-align:left;font-size: 8pt;padding-ledt:5px"><?php echo $sql['nm_brg']; ?></td>
              <td style="text-align:center;font-size: 8pt"><?php echo gantitides($awal); ?></td>
              <td style="text-align:center;font-size: 8pt"><?php echo gantitides($akhir); ?></td>
              <td style="text-align:center;font-size: 8pt"><?php echo $sql['nm_toko']; ?></td> 
              <td style="text-align:left;font-size: 8pt;border-right: 1px solid">&nbsp;<?php echo $sql['ket']; ?></td>
            </tr> <?php    
        } ?>       
      </table>  
    </div>
    <div style="display:none">
      <form action="f_cetak_stok_opex.php" method="POST" target="_blank">
        <input type="date" name="tgleop1" value="<?=$tgl1?>">
        <input type="date" name="tgleop2" value="<?=$tgl2?>">
        <input type="text" name="pilihestokop" value="<?=$pilih?>">
        <input type="text" name="kd_tokoestokop" value="<?=$kd_tokocari?>">
        <button id="btnrek" type="submit">cet</button>
      </form>
      <form action="f_cetak_stok_op.php" method="POST">
        <input type="date" name="tglop1" value="<?=$tgl1?>">
        <input type="date" name="tglop2" value="<?=$tgl2?>">
        <input type="text" name="pilihstokop" value="<?=$pilih?>">
        <input type="text" name="kd_tokostokop" value="<?=$kd_tokocari?>">
        <input type="text" id="curut" name="urut" value="mutasi_adj.tgl_input">
        <button id="btnurut" type="submit">cet</button>
      </form>  
    </div>
    
    <div class="w3-row w3-margin-top">
      <div class="w3-col w3-center">
          <button type="button" id="printPageButton" class="w3-btn w3-green" onclick="window.print();" style="border-radius:5px;font-size:9pt">Cetak PDF</button>      
          <button type="button" id="printPerek" class="w3-btn w3-yellow" onclick="document.getElementById('btnrek').click()" style="border-radius:5px;font-size:9pt">Export Exel</button>      
      </div>
    </div>
  </section>
</body> <?php 
}
mysqli_close($connect);
?>
