<?php
  require __DIR__.'/../assets/vendor/autoload.php';
  use Spipu\Html2Pdf\Html2Pdf;
  use Spipu\Html2Pdf\Exception\Html2PdfException;
  use Spipu\Html2Pdf\Exception\ExceptionFormatter; 
  ob_start();
  session_start();
  include 'config.php';
  $id_user=$_SESSION['id_user'];
  $concet=opendtcek(); 
   if($_SESSION['pilprint']=='CETAK-CK'){
    $jbar=2;
   }else{$jbar=3;}
   ?>
<style>
    table {
    width:  100%;
    /*border: solid 1px black;*/
    text-align: center;
    }
    th {
      text-align: center;
      border: solid 1px black;
      background: white;
    }

    td {
      border: solid 1px black;
      background: white;
      font-size: 9pt;
      border-left: none;
      border-right: none;
      border-top: none;
      border-bottom: none;
    }

</style>
<page backtop="-5mm" backbottom="-10mm" backleft="0mm" backright="0mm">
  <table>    
     <?php 
      $i=0;$x=0;
      if(isset($_GET['bcode'])){
        $cek=mysqli_query($concet,"SELECT * from mas_brg where pilih='1' AND id_user='$id_user'");  
        if($_GET['bcode']=='1'){
          while($data=mysqli_fetch_array($cek)){
            $nm_brg=$data['nm_brg'];
            $no_urut=$data['no_urut'];
            $copies=$data['copy'];
            mysqli_query($concet,"UPDATE mas_brg SET cetak='1' WHERE no_urut='$no_urut'");
            for ($z=0; $z < $copies ; $z++) {
              if ($x == 0) {echo "<tr>";}  ?>
              <td style="width: 100px">
                <br>
                  <p style='font-size:6pt;text-align: center'><b><?=$nm_brg?></b></p>
                  <qrcode value="<?= $data['kd_bar'] ?>" style="border: none;width: 20mm; background-color: white; color: black;"></qrcode> 
                  <p style='font-size:6pt;text-align: center'><?='Rp.'.gantiti(round($data['hrg_jum1'],0))?></p> 
              </td> <?php              
              $x=$x+1; 
              if ( $x == $jbar ) { echo "</tr>";$x=0;}
            } 
          }     
          if ($x<8 && $x!=0) {echo "</tr>";}
        }else{
          while($data=mysqli_fetch_array($cek)){
            $nm_brg=$data['nm_brg'];
            $no_urut=$data['no_urut'];
            $copies=$data['copy'];
            mysqli_query($concet,"UPDATE mas_brg SET cetak='1' WHERE no_urut='$no_urut'");
            for ($z=0; $z < $copies ; $z++) {
              if ($x == 0) {echo "<tr>";}  
              ?>
              <tr>
                <td style="width: 90px">
                  <p style='font-size:6pt;text-align: center'><b><?=$nm_brg?></b></p>
                  <barcode dimension="1D" type="C39" value="<?=$data['kd_bar']?>" label="label" style="width:35mm; height:10mm; color: black; font-size: 2mm"></barcode>
                  <!-- <p style="font-size:6pt;text-align: center"><?="Rp.".gantiti(round($data['hrg_jum1'],0))?></p> -->
                </td> 
              </tr>
              <?php          
              $x=$x+1; 
              if ( $x == 8 ) { echo "</tr>";$x=0;}
            } 
          }     
          if ($x<8 && $x!=0) {echo "</tr>";} 
        }
        mysqli_free_result($cek);unset($data);
      }  
     ?> 
  </table>
</page>

<?php
    unset($data,$cek);
    mysqli_close($concet);
    $c_nmfile='barkode.pdf'; 
    $content = ob_get_clean();
    try
    {
      if($_SESSION['pilprint']=='CETAK-CK'){
        $html2pdf = new Html2Pdf('P', array(58,3700), 'en' );
      }else{
        $html2pdf = new Html2Pdf('P', array(80,3700), 'en' );
      }
      
      $html2pdf->pdf->SetDisplayMode('fullpage');
      $html2pdf->writeHTML($content);
      $html2pdf->Output($c_nmfile);
    }
    catch(Html2PdfException $e) {
      $html2pdf->clean();
      $formatter = new ExceptionFormatter($e);
      echo $formatter->getHtmlMessage();
      exit;
    }
?>
<script>window.print()</script>