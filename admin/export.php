
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
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=hasil.xls");
        $kolom = 4;
        $x=0;
       echo "<table>";
        for ($i=0; $i < $copies ; $i++) 
        { 
          $x++;
          if ($i % $kolom == 0) echo "<tr>"; 
          ?> 
           <td style="padding: 3px" class="hrf_barcode"><?='*'.$kd_bar.'*'?></td>
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