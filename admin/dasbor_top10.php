<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Seller</title>
    <link rel="shortcut icon" href="img/keranjang.png">
    <link rel="stylesheet" href="../assets/css/paper.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
    <link rel="stylesheet" href="../assets/css/blue-themes.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
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
</style>
   
<?php
session_start();
include 'config.php';
$concet=opendtcek(); 
$cbln=date('m',strtotime($_SESSION['tgl_set']));
$cthn=date('Y',strtotime($_SESSION['tgl_set']));

$kd_toko=$_SESSION['id_toko'];     

// Ambil parameter POST dengan nilai default untuk mencegah undefined index
$pilihbulan = isset($_POST['pilihbulan']) ? $_POST['pilihbulan'] : $cbln;
$ctkpil     = isset($_POST['ctkpil']) ? $_POST['ctkpil'] : '0';
$pilihst    = isset($_POST['pilihst']) ? $_POST['pilihst'] : '';
$endyear    = isset($_POST['pilihtahun']) ? $_POST['pilihtahun'] : $cthn;
$endbln     = $pilihbulan;

if($pilihbulan=='1'){
    $ktbln  = 'Januari';  
}
if($pilihbulan=='2'){
    $ktbln  = 'Februari';  
}
if($pilihbulan=='3'){
    $ktbln  = 'Maret';  
}
if($pilihbulan=='4'){
    $ktbln  = 'April';  
}
if($pilihbulan=='5'){
    $ktbln  = 'Mei';  
}
if($pilihbulan=='6'){
    $ktbln  = 'Juni';  
}
if($pilihbulan=='7'){
    $ktbln  = 'Juli';  
}
if($pilihbulan=='8'){
    $ktbln  = 'Agustus';  
}
if($pilihbulan=='9'){
    $ktbln  = 'September';  
}
if($pilihbulan=='10'){
    $ktbln  = 'Oktober';  
}
if($pilihbulan=='11'){
    $ktbln  = 'Nopember';  
} 
if($pilihbulan=='12'){
    $ktbln  = 'Desember';  
}
if($ctkpil=='0'){
   $pil=''; 
   $nmpil='(SEMUA BAGIAN)';
}else{
   $pil=' AND dum_jual.id_bag='.$ctkpil; 
   $ss=$ctkpil;
   $dd=mysqli_query($concet,"SELECT nm_bag FROM bag_brg WHERE no_urut='$ss'");
   $qq=mysqli_fetch_assoc($dd);
   $nmpil='('.$qq['nm_bag'].')';
   mysqli_free_result($dd);unset($qq);
}

// Batas stok: jika tidak diisi, pakai nilai besar supaya semua tampil
if($pilihst === '' || $pilihst === null){
  $pil_st = 1000000; // hampir tanpa batas
}else{
  $pil_st = (int)$pilihst;
}
$cq=mysqli_query($concet,"SELECT * FROM toko WHERE kd_toko='$kd_toko'");
$dtt=mysqli_fetch_assoc($cq);
$nm_toko=$dtt['nm_toko'];  
mysqli_free_result($cq);unset($dtt);
?>
<body class="F4">      
  <section class="sheet padding-10mm">  
    <div style="page-break-before: always;">
      <h5 style="text-align: center;margin-bottom:5px ">BEST SELLER TOKO FAFA <?='BULAN '.strtoupper($ktbln).'  TAHUN '.$endyear.' '.$nmpil ?></h5>
      <table cellspacing="0" style="width: 100%; border: solid 1px black; text-align: center; font-size: 8pt;">
        <thead >
          <tr>
            <th style="width:5%;">NO</th>
            <th >NAMA BARANG</th>
            <th style="width:15%">TRANSAKSI</th>
            <th style="width:10%">STOK</th>
            <th style="width:15%">RANGKING</th>
            <th style="width:15%">CEK DATA</th>
          </tr> 
        </thead>   
        <?php 
        $cek=mysqli_query($concet,"SELECT dum_jual.kd_brg,dum_jual.nm_brg,count(nm_brg) as jmlbrg FROM dum_jual 
        WHERE month(dum_jual.tgl_jual)='$endbln' AND year(dum_jual.tgl_jual)='$endyear' AND INSTR(dum_jual.nm_brg,'JASA')=0 $pil AND kd_toko='$kd_toko'
        GROUP BY dum_jual.nm_brg ORDER BY COUNT(*) DESC ");
        $a=0;
        while($data=mysqli_fetch_array($cek)){
          $kdbrg=$data['kd_brg'];
           $stok=0;
          //stok jual
          $c=mysqli_query($concet,"SELECT sum(stok_jual) as jmls FROM beli_brg WHERE kd_brg='$kdbrg' AND kd_toko='$kd_toko'");
          $d=mysqli_fetch_assoc($c);
          $stok=round($d['jmls'],0);
          if($stok<0){$stok=0;}

          //kemasan
          $ex=explode(";",carisatkecil2($kdbrg,$concet));
          $sat=strtolower(ceknmkem2($ex[0],$concet));
          unset($c,$d,$ex); 
          
          //cek stok opname
          $cektgl='';$x='';$userx='';
          $c=mysqli_query($concet,"SELECT tgl_input,ket FROM mutasi_adj WHERE kd_brg='$kdbrg' AND kd_toko='$kd_toko'  ORDER BY tgl_input DESC limit 1");
          if(mysqli_num_rows($c)>0){
            $d=mysqli_fetch_assoc($c);
            $x = $d['ket'];
            $jm='';

            // Ambil jam dari teks ket: cari "Jam :" sampai sebelum "<br>"
            $posJam = strpos($x,"Jam :");
            $posBr  = strpos($x,"<br>");
            if($posJam !== false && $posBr !== false && $posBr > $posJam+5){
              $jm = trim(substr($x,$posJam+5,$posBr-($posJam+5)));
            }

            // Ambil user dari teks ket: antara "User :" dan ", Jam"
            $posUser = strpos($x,'User :');
            $posJamLabel = strpos($x,', Jam');
            if($posUser !== false && $posJamLabel !== false && $posJamLabel > $posUser+6){
              $userx = trim(substr($x,$posUser+6,$posJamLabel-($posUser+6)));
            }

            // Hitung timeago jika jam tersedia, kalau tidak pakai tanggal saja
            if($jm != ''){
              $cektgl=gantitgl($d['tgl_input'])."<br>"."( ".timeago(date("Y-m-d H:i:s",strtotime($d['tgl_input'].' '.$jm)))." )";
            }else{
              $cektgl=gantitgl($d['tgl_input']);
            }
            
          }
          unset($c,$d); 
            if($stok<=$pil_st ){ $a++;?>
              <tr style="font-size: 10pt">
                <td align="right"><?=$a?>.</td>
                <td><?=$data['nm_brg']?></td>
                <td style="text-align:center"><?=$data['jmlbrg']?>&nbsp;kali</td>
                <td style="text-align:center"><?=$stok.' '.$sat?></td>
                <td style="text-align: center"><i class="fa fa-star" style="color: orange"></i> <?=$a?></td> 
                <td style="text-align:center"><?=$cektgl."<br>".$userx?></td>
              </tr> <?php 
            }     
        } ?> 
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