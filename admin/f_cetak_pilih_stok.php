<link rel="stylesheet" href="../assets/css/paper.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
<link rel="stylesheet" href="../assets/css/blue-themes.css">
<style>
  body,h2,h3,h4,h5,h6 {font-family: Times,Helvetica}
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
 $sql=mysqli_fetch_assoc($cektoko);
 $nm_toko=mysqli_escape_string($connect,$sql['nm_toko']);
 $al_toko=mysqli_escape_string($connect,$sql['al_toko']);
 unset($cektoko,$sql); 
 $a=0;
 //------------
 function caribrgmsk($kd_brg,$kd_toko){
	  // $tgl_hi=date('Y-m-d');
	  $jml_brg=0;$jml_brg_kov=0;
	  $con = mysqli_connect("localhost","root", "", "toko_retail");
	  $cek=mysqli_query($con,"SELECT * FROM beli_brg where kd_brg='$kd_brg' and kd_toko='$kd_toko'order by no_urut ASC");
	  if(mysqli_num_rows($cek)>=1){
	  	while ($data=mysqli_fetch_assoc($cek)) {
	  	  // $jml_brg_kov=konjumbrg($data['kd_sat'],$kd_brg,$kd_toko)*$data['jml_brg'];
	  	  // $jml_brg=$jml_brg+$jml_brg_kov;
	  	  $jml_brg=$jml_brg+$data['stok_jual'];	
	  	}
	  }
	  return $jml_brg;
	  unset($data,$cek);
	  mysqli_close($con);
	}		
$cek        = 0; 
$pilih      = isset($_POST['pilih']) ? $_POST['pilih'] : '';
$kd_supcari = isset($_POST['kd_sup1']) ? $_POST['kd_sup1'] : '';
$nm_sup1    = isset($_POST['nm_sup1']) ? $_POST['nm_sup1'] : '';
$datain     = false; // Initialize to prevent undefined variable
$judul      = ''; // Initialize judul
$is_export  = (isset($_GET['export']) && $_GET['export']==='excel');
 

 if (isset($_POST['jml_stok'])){
    $jml_stok   = $_POST['jml_stok'];
 } else {$jml_stok   = -1;}

 if (empty($_POST['jml_stok'])){$jml_stok= -1;}
 
 if (isset($_POST['cektampil1'])){
   $cek = $_POST['cektampil1']; 
 } else { $cek=0;}

 if ($cek==1){
   $tampil='';
 } else {
   $tampil=" HAVING stok_juals > 0 ";
 }

 if($pilih=='alldata')
 {
    $judul=' semua barang '; 

    if ($jml_stok>0){
       $query = "SELECT beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.kd_brg,SUM(beli_brg.stok_jual) AS stok_juals,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,mas_brg.nm_brg,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,supplier.nm_sup,AVG(beli_brg.hrg_beli) AS hrg_beli,mas_brg.hrg_jum1 AS hrg_jual,bag_brg.nm_bag
        FROM beli_brg beli_brg
           LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
           LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
           LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut
           WHERE beli_brg.kd_toko='$kd_toko'
           GROUP BY beli_brg.kd_brg
           HAVING stok_juals>=1 and stok_juals<='$jml_stok'
           ORDER BY mas_brg.nm_brg ASC";
      $datain=mysqli_query($connect,$query);
      if(!$datain) {
        die('Query Error (alldata jml_stok>0): ' . mysqli_error($connect));
      }
    } else {
       $query = "SELECT beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.kd_brg,SUM(beli_brg.stok_jual) AS stok_juals,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,mas_brg.nm_brg,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,supplier.nm_sup,AVG(beli_brg.hrg_beli) AS hrg_beli,mas_brg.hrg_jum1 AS hrg_jual,bag_brg.nm_bag
        FROM beli_brg beli_brg
           LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
           LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
           LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut
           WHERE beli_brg.kd_toko='$kd_toko' 
           GROUP BY beli_brg.kd_brg
           $tampil
           ORDER BY mas_brg.nm_brg ASC";
      $datain=mysqli_query($connect,$query);
      if(!$datain) {
        die('Query Error (alldata else): ' . mysqli_error($connect));
      }
    } 
 }

 if($pilih=='supplier'){   
    $judul=' berdasar Supplier '.$nm_sup1; 
    if ($jml_stok>0){
       $query = "SELECT beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.kd_brg,SUM(beli_brg.stok_jual) AS stok_juals,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,mas_brg.nm_brg,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,supplier.nm_sup,AVG(beli_brg.hrg_beli) AS hrg_beli,mas_brg.hrg_jum1 AS hrg_jual,bag_brg.nm_bag
        FROM beli_brg beli_brg
           LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
           LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
           LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut
           WHERE beli_brg.kd_toko='$kd_toko' AND beli_brg.kd_sup='$kd_supcari' 
           GROUP BY beli_brg.kd_brg
           HAVING stok_juals>=1 and stok_juals<='$jml_stok'
           ORDER BY mas_brg.nm_brg ASC";
      $datain=mysqli_query($connect,$query);
      if(!$datain) {
        die('Query Error (supplier jml_stok>0): ' . mysqli_error($connect));
      }
    } else {
       $query = "SELECT beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.kd_brg,SUM(beli_brg.stok_jual) AS stok_juals,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,mas_brg.nm_brg,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,supplier.nm_sup,AVG(beli_brg.hrg_beli) AS hrg_beli,mas_brg.hrg_jum1 AS hrg_jual,bag_brg.nm_bag
        FROM beli_brg beli_brg
           LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
           LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
           LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut
           WHERE beli_brg.kd_toko='$kd_toko' AND beli_brg.kd_sup='$kd_supcari' 
           GROUP BY beli_brg.kd_brg
           $tampil
           ORDER BY mas_brg.nm_brg ASC";
      $datain=mysqli_query($connect,$query);
      if(!$datain) {
        die('Query Error (supplier else): ' . mysqli_error($connect));
      }
    }

  }

// Cek jika pilih tidak valid
if($pilih != 'alldata' && $pilih != 'supplier' && $datain === false) {
  ?>
  <body>
    <div style="padding: 20px; text-align: center;">
      <h3>Error: Parameter tidak valid</h3>
      <p>Parameter 'pilih' harus berisi 'alldata' atau 'supplier'</p>
      <p>Nilai yang diterima: <?php echo htmlspecialchars($pilih); ?></p>
      <p><a href="javascript:history.back()">Kembali</a></p>
    </div>
  </body>
  <?php
  mysqli_close($connect);
  exit;
}

// export excel jika diminta
if($is_export && $datain !== false && mysqli_num_rows($datain)>=1){
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=stok_barang.xls");
  echo "<table border='1' cellspacing='0' cellpadding='4'>";
  echo "<tr><th>NO</th><th>KD. BARANG</th><th>NAMA BARANG</th><th>SUPPLIER</th><th>BAGIAN</th><th>KONV 1</th><th>KONV 2</th><th>KONV 3</th><th>HARGA BELI</th><th>HARGA JUAL</th></tr>";
  $no=0;$stok1=0;$stok2=0;$stok3=0;$nm_kem1='';$nm_kem2='';$nm_kem3='';
  while($sql=mysqli_fetch_assoc($datain))
  {
        $kd_brg=$sql['kd_brg'];
        if(!empty($kd_brg)){
          $nm_kem1=$sql['nm_kem1'];
          $nm_kem2=$sql['nm_kem2'];
          $nm_kem3=$sql['nm_kem3'];
        }
        $brg_msk_hi=$sql['stok_juals'];
        if ($sql['jum_kem1']>0) {$stok1=gantitides($brg_msk_hi/$sql['jum_kem1']).' '.$nm_kem1;} else {$stok1='0,00 '.$nm_kem1;}
        if ($sql['jum_kem2']>0) {$stok2=gantitides($brg_msk_hi/$sql['jum_kem2']).' '.$nm_kem2;} else {$stok2='0,00 '.$nm_kem2;}
        if ($sql['jum_kem3']>0) {$stok3=gantitides($brg_msk_hi/$sql['jum_kem3']).' '.$nm_kem3;} else {$stok3='0,00 '.$nm_kem3;}
        $no++;
        echo "<tr>";
        echo "<td>".$no."</td>";
        echo "<td>".$sql['kd_brg']."</td>";
        echo "<td>".$sql['nm_brg']."</td>";
        echo "<td>".$sql['nm_sup']."</td>";
        echo "<td>".($sql['nm_bag'] ? $sql['nm_bag'] : '')."</td>";
        echo "<td style='text-align:right;'>".$stok1."</td>";
        echo "<td style='text-align:right;'>".$stok2."</td>";
        echo "<td style='text-align:right;'>".$stok3."</td>";
        echo "<td style='text-align:right;'>".gantitides($sql['hrg_beli'])."</td>";
        echo "<td style='text-align:right;'>".gantitides($sql['hrg_jual'])."</td>";
        echo "</tr>";
  }
  echo "</table>";
  mysqli_close($connect);
  exit;
}

// proses pencetakan
if($datain !== false && mysqli_num_rows($datain)>=1)
{ 
?>
   <body class="F4 ">      
     <section class="sheet padding-10mm">  
        <div style="page-break-before: always;">
       	  <table id="content" cellspacing="0" style="width: 100%; font-size: 8pt;page-break-before: always">
            <thead>
                <tr><td colspan="12" style="text-align: center;font-size: 13pt;border:none"><b><?=$nm_toko?></b></td></tr>
                <tr><td colspan="12" style="text-align: center;font-size: 11pt;border:none"><b><?=$al_toko?></b></td></tr>
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr> <td colspan="12" style="text-align: left;font-size: 10pt"><b>Cetak stok barang <?=$judul?></b></td></tr>   
                <tr style="background-color: lightgrey;color: black">
                    <th style="width:3%;">NO</th>
                    <th style="width:12%">KD. BARANG</th>
                    <th style="width:15%">NAMA BARANG</th>
                    <th style="width:15%">SUPPLIER</th>
                    <th style="width:12%">BAGIAN</th>
                    <th colspan="3">KONVERSI SATUAN STOK</th>
                    <th style="width:10%">HARGA BELI</th>
                    <th style="width:10%">HARGA JUAL</th>
                </tr>       
            </thead> 
            <?php 

            $no=0;$stok1=0;$stok2=0;$stok3=0;$nm_kem1='';$nm_kem2='';$nm_kem3='';
            while($sql=mysqli_fetch_assoc($datain))
            {
            	  $kd_brg=$sql['kd_brg'];
                if(!empty($kd_brg)){
                  $nm_kem1=$sql['nm_kem1'];
                  $nm_kem2=$sql['nm_kem2'];
                  $nm_kem3=$sql['nm_kem3'];
                }
    		        $brg_msk_hi=$sql['stok_juals'];
                
                if ($sql['jum_kem1']>0) {
    		          $stok1=gantitides($brg_msk_hi/$sql['jum_kem1']).' '.$nm_kem1;	
    		        }else{
    		          $stok1='0,00 '.$nm_kem1; 
    		        }
    		        if ($sql['jum_kem2']>0) {
    		          $stok2=gantitides($brg_msk_hi/$sql['jum_kem2']).' '.$nm_kem2;	
    		        }else{
    		          $stok2='0,00 '.$nm_kem2; 
    		        }
    		        if ($sql['jum_kem3']>0) {
    		          $stok3=gantitides($brg_msk_hi/$sql['jum_kem3']).' '.$nm_kem3;	
    		        }else{
    		          $stok3='0,00 '.$nm_kem3; 
    		        }
                $no++;
                ?>
    		          
    	                <tr>
    		                <td style="text-align:right;font-size: 8pt;border-left: 1px solid"><?php echo $no.'.';?>&nbsp;</td>
    		                <td style="text-align:left;font-size: 8pt"><?php echo $sql['kd_brg'];?></td>
    		                <td style="text-align:left;font-size: 8pt"><?php echo $sql['nm_brg']; ?></td>
    		                <td style="text-align:center;font-size: 8pt"><?php echo $sql['nm_sup']; ?></td>
    		                <td style="text-align:center;font-size: 8pt"><?php echo $sql['nm_bag'] ? $sql['nm_bag'] : ''; ?></td>
    		                <td style="text-align:right;font-size: 8pt;"><?php echo $stok1; ?></td>
    		                <td style="text-align:right;font-size: 8pt;"><?php echo $stok2; ?></td>
    		                <td style="text-align:right;font-size: 8pt;"><?php echo $stok3; ?></td>
    		                <td style="text-align:right;font-size: 8pt;"><?php echo gantitides($sql['hrg_beli']); ?></td>
    		                <td style="text-align:right;font-size: 8pt;border-right: 1px solid"><?php echo gantitides($sql['hrg_jual']); ?></td>
    	                </tr>
		        <?php    
            }
            ?>
          </table>  
        </div>
        <div class="w3-row w3-margin-top">
        <div class="w3-col w3-center">
          <button id="printPageButton" class="w3-btn w3-green" onclick="window.print();">Cetak PDF</button>      
          <form method="post" action="f_cetak_pilih_stok.php?export=excel" target="_blank" style="display:inline;">
            <input type="hidden" name="pilih" value="<?php echo $pilih; ?>">
            <input type="hidden" name="kd_sup1" value="<?php echo $kd_supcari; ?>">
            <input type="hidden" name="nm_sup1" value="<?php echo $nm_sup1; ?>">
            <input type="hidden" name="jml_stok" value="<?php echo $jml_stok; ?>">
            <input type="hidden" name="cektampil1" value="<?php echo $cek; ?>">
            <button type="submit" class="w3-btn w3-blue">Export Excel</button>
          </form>
        </div>
      </div>
     </section>
    </body>    
    
 <?php 
} else {
  // Tampilkan pesan jika tidak ada data atau query gagal
  ?>
  <body>
    <div style="padding: 20px; text-align: center;">
      <h3>Tidak ada data stok barang yang ditemukan</h3>
      <?php
      if($datain === false) {
        echo '<p style="color: red;">Error: Query gagal dieksekusi. ' . mysqli_error($connect) . '</p>';
      } else {
        echo '<p>Data stok barang kosong atau tidak memenuhi kriteria filter.</p>';
      }
      if(empty($pilih)) {
        echo '<p style="color: orange;">Peringatan: Parameter pilih tidak ditemukan.</p>';
      }
      ?>
      <p><a href="javascript:history.back()">Kembali</a></p>
    </div>
  </body>
  <?php
}
mysqli_close($connect);
?>
