<?php
    // get the HTML
    require_once(dirname(__FILE__).'../../assets/html2pdf/html2pdf.class.php'); 
    ob_start();
 
    session_start();
    include 'config.php';
    $connect=opendtcek()

?>
    
<style>
    table
    {
    width:  100%;
    border: solid 1px black;
    text-align: center;
    }
    th
    {
        text-align: center;
        border: solid 1px black;
        background: #EEFFEE;
    }

    td
    {
        border: solid 1px black;
        background: white;
        font-size: 9pt;
        border-left: none;
        border-right: none;
        border-top: none;
        border-bottom: none;
        
    }



</style>

<page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm">
    <!-- <h4 style="text-align: center">BARCODE</h4> -->
    <!-- <h4 style="text-align: center;margin-top: -15px"><?php echo $_SESSION['nm_apotik'] ?></h4>
    <h6 style="text-align: center;margin-top: -15px"><?php echo 'KODE APOTIK : '.$_SESSION['id_apt'] ?></h6> -->
    <?php 
      $x=explode(';', $_GET['pesan']);
      $kd_bar=$x[0];
      $n_size=$x[1];
      $copies=$x[2];
      $kd_toko=$_SESSION['id_toko'];     
      
      $cek=mysqli_query($connect,"select * from mas_brg where kd_bar='$kd_bar' and kd_toko='$kd_toko'");
      $data=mysqli_fetch_array($cek);
      $nm_brg=$data['nm_brg'];
      unset($cek,$data);
      
      echo $nm_brg;   
      if ($n_size=="KECIL") {
        $kolom = 4;
        $x=0;
       echo "<table>";
        for ($i=0; $i < $copies ; $i++) 
        { 
          $x++;
          if ($i % $kolom == 0) echo "<tr>"; 
          ?> 
           <td style="padding: 3px"><barcode dimension='1D' type='C39' value='<?=$kd_bar?>' label='label' style='width:45mm; height:8mm; color: black; font-size: 2mm'></barcode></td>
          <?php 
          if ($x==$kolom || $i==$copies-1){echo "</tr>";$x=0;}
          
        }
        echo "</table>";  
      }
      if ($n_size=="SEDANG") {
        $kolom = 3;
        $x=0;
       echo "<table>";
        for ($i=0; $i < $copies ; $i++) 
        { 
          $x++;
          if ($i % $kolom == 0) echo "<tr>"; 
          ?> 
           <td style="padding: 3px"><barcode dimension='1D' type='c39' value='<?=$kd_bar?>' label='label' style='width:50mm; height:12mm; color: black; font-size: 2mm'></barcode></td>
          <?php 
          if ($x==$kolom || $i==$copies-1){echo "</tr>";$x=0;}
          
        }
        echo "</table>";  
      }
      if ($n_size=="BESAR") {
        $kolom = 2;
        $x=0;
       echo "<table>";
        for ($i=0; $i < $copies ; $i++) 
        { 
          $x++;
          if ($i % $kolom == 0) echo "<tr>"; 
          ?> 
           <td style="padding: 3px"><barcode dimension='1D' type='C39' value='<?=$kd_bar?>' label='label' style='width:80mm; height:20mm; color: black; font-size: 2mm'></barcode></td>
          <?php 
          if ($x==$kolom || $i==$copies-1){echo "</tr>";$x=0;}
          
        }
        echo "</table>";  
      }
    ?>           
    
</page>


<?php
    //include(dirname('__FILE__').'../../../admin/cet_jualprint.php');
    mysqli_close($connect);
    $c_nmfile='barkode-'.$kd_bar.'.pdf'; 
    $content = ob_get_clean();

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'en' );
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($c_nmfile);
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
?>