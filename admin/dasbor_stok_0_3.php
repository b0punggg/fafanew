<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cek Stok</title>
  <link rel="shortcut icon" href="img/keranjang.png">
  <link rel="stylesheet" href="../assets/css/paper.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
  <link rel="stylesheet" href="../assets/css/blue-themes.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
  <script src="../assets/js/utils.js"></script>
</head>
<style>  
  th
    {
      text-align: center;
      border: solid 1px #113300;
      padding:5px;
      /*background: #EEFFEE;*/
    }

  td
    {
      border: solid 1px #113300;
      background: white;
      font-size: 9pt;
      border-left: none;
      border-right: none;
      border-top: none;      
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
  }
  @media print {
    #printexel {
      display: none;
    }
  }
</style>
   
<?php
session_start();
include 'config.php';
$concet   = opendtcek(); 
$kd_toko  = $_SESSION['id_toko'];     
$xeplo    = explode("-",kurangtgl(date("Y-m-d"),-60));
$tglakhir = $xeplo[0]."-".$xeplo[1]."-01"; 
$tglhi    = date("Y-m-d");
$j_stok   = $_POST['pilihst0'];
if($_POST['ctkpil0']=='0'){
  $pil=''; 
  $nmpil='(SEMUA BAGIAN)';
}else{
  $pil=' AND dum_jual.id_bag='.$_POST['ctkpil0']; 
  $ss=$_POST['ctkpil0'];
  $dd=mysqli_query($concet,"SELECT nm_bag FROM bag_brg WHERE no_urut='$ss'");
  $qq=mysqli_fetch_assoc($dd);
  $nmpil='('.$qq['nm_bag'].')';
  mysqli_free_result($dd);unset($qq);
}

$cq=mysqli_query($concet,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
$dtt=mysqli_fetch_assoc($cq);
$nm_toko=$dtt['nm_toko'];  
mysqli_free_result($cq);unset($dtt);
?>
<body class="F4">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
      <h5 style="text-align: center;margin-bottom:5px "><?="CEK STOK DARI ".gantitgl($tglakhir). " SAMPAI ".gantitgl(date("Y-m-d")).'&nbsp;'. $nmpil ?></h5>
      <table cellspacing="0" style="width: 100%; border: solid 1px black; text-align: center; font-size: 8pt;">
        <thead >
          <tr>
            <th style="width:5%;">NO</th>
            <th >NAMA BARANG</th>
            <th style="width:8%">TRANSAKSI</th>
            <th style="width:7%">STOK</th>
            <th style="width:5%">RANGKING</th>
            <th style="width:15%">AWAL BELI</th>
            <th style="width:15%">TERAKHIR JUAL</th>
            <th style="width:15%">CEK DATA</th>
          </tr> 
        </thead>   
        <?php 
        // dum_jual
        $cek=mysqli_query($concet,"SELECT kd_brg,nm_brg,count(nm_brg) as jmlbrg,max(tgl_jual) AS jualakhir FROM dum_jual 
        WHERE tgl_jual>='$tglakhir' $pil AND INSTR(nm_brg,'JASA')=0 AND INSTR(ket,'RETUR')=0 AND kd_toko='$kd_toko'
        GROUP BY nm_brg ORDER BY COUNT(*) DESC");
        $a=0;
        
        while($data=mysqli_fetch_array($cek)){
          $stok      = $jmlbrg=0;
          $kdbrg     = $data['kd_brg'];
          $nmbrg     = mysqli_escape_string($concet,$data['nm_brg']);
          $jualakhir = $data['jualakhir'];
          $jmlbrg    = $data['jmlbrg'];
          
          //stok jual beli_brg
          $c=mysqli_query($concet,"SELECT sum(beli_brg.stok_jual) as jmls,MIN(beli_brg.tgl_fak) AS beliakhir,mas_brg.nm_brg FROM beli_brg
            LEFT JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg
            WHERE mas_brg.nm_brg='$nmbrg' AND beli_brg.kd_toko='$kd_toko'");
          if(mysqli_num_rows($c)>0){
            $d=mysqli_fetch_assoc($c);
            if($d['jmls']!=NULL){
              $stok=round($d['jmls'],0);
              $beliakhir=$d['beliakhir'];
              if($stok<0){$stok=0;}

              //kemasan
              // $ex=explode(";",carisatkecil2($kdbrg,$concet));
              // if($ex[0]==NULL){
              //   echo $kdbrg.'<br>';
              // }
              // $sat=strtolower(ceknmkem_2($ex[0],$concet));
              unset($c,$d,$ex); 
            
              //cek stok opname
              $cektgl=$x=$userx='';
              $c=mysqli_query($concet,"SELECT tgl_input,ket FROM mutasi_adj WHERE kd_brg='$kdbrg' AND kd_toko='$kd_toko'  ORDER BY tgl_input DESC limit 1");
              if(mysqli_num_rows($c)>0){
                $d=mysqli_fetch_assoc($c);
                $x = $d['ket'];
                $jm=substr($x,strpos($x,"Jam :")+5,strpos($x,"<br>")-strlen($x));
                $userx = substr($x,strpos($x,'User :'),strpos($x,', Jam')-strlen($x));
                $cektgl=gantitgl($d['tgl_input'])."<br>"."( ".timeago(date("Y-m-d h:m:s",strtotime($d['tgl_input'].' '.$jm)))." )";
              }
              unset($c,$d); 
              if($stok<=$j_stok ){ $a++;?>
                <tr style="font-size: 10pt">
                  <td align="right" class="p-4"><?=$a?>.</td>
                  <td><?=$data['nm_brg']?></td>
                  <td style="text-align:center"><?=$jmlbrg?>&nbsp;kali</td>
                  <td style="text-align:center"><?=$stok?></td>
                  <td style="text-align: center"><i class="fa fa-star" style="color: orange"></i> <?=$a?></td>
                  <td style="text-align: center"><?=gantitgl($beliakhir)?></td> 
                  <td style="text-align: center"><?=gantitgl($jualakhir)?></td> 
                  <td style="text-align:center"><?=$cektgl."<br>".$userx?></td>
                </tr> <?php 
              }
            }  
          }     
        } ?> 
      </table>
    </div>
    <div style="display:none">
      <form action="dasbor_stok_0_3ex.php" method="POST" target="_blank">
        ctkpil0
        <input type="text" name="pilihcstok" value="<?=$j_stok?>">
        <input type="text" name="pilihbag" value="<?=$_POST['ctkpil0']?>">
        <button id="btnsexr" type="submit">cet</button>
      </form>
    </div>
    <div class="w3-row w3-margin-top">
      <div class="w3-col w3-center">
        <button id="printPageButton" class="btn btn-sm btn-success w3-margin-top " onclick="window.print();">Cetak PDF</button> &nbsp;     
        <button id="printexel" class="btn btn-sm btn-warning w3-margin-top " onclick="document.getElementById('btnsexr').click();">Export Exel</button>      
      </div>
      
    </div>
  </section>
</body>      
</html>