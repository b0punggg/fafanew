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
<?php 
   session_start();
   include 'config.php';
      $x=explode(';', $_GET['pesan']);
      $kd_bar=$x[0];
      $n_size=$x[1];
      $copies=$x[2];
      $kd_toko=$_SESSION['id_toko'];     
      
      $cek=mysqli_query($connect,"select * from mas_brg where kd_bar='$kd_bar' ");
      $data=mysqli_fetch_array($cek);
      $nm_brg=$data['nm_brg'];
      unset($cek,$data);
      
      echo $nm_brg;   
      if ($n_size=="KECIL") {
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=$nm_brg.xls");
        $kolom = 4;
        $x=0;
       echo "<table>";
        for ($i=0; $i < $copies ; $i++) 
        { 
          $x++;
          if ($i % $kolom == 0) echo "<tr>"; 
          ?> 
           <td style="padding: 3px" class="hrf_barcode" style="font-size: 20px"><?='*'.$kd_bar.'*'?><br><?=$kd_bar?></td>
          <?php 
          if ($x==$kolom || $i==$copies-1){echo "</tr>";$x=0;}
          
        }
        echo "</table>";  
      }

      if ($n_size=="SEDANG") {
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=$nm_brg.xls");
        $kolom = 3;
        $x=0;
       echo "<table>";
        for ($i=0; $i < $copies ; $i++) 
        { 
          $x++;
          if ($i % $kolom == 0) echo "<tr>"; 
          ?> 
           <td style="padding: 3px" class="hrf_barcode" style="font-size: 30px"><?='*'.$kd_bar.'*'?><br><?=$kd_bar?></td>
          <?php 
          if ($x==$kolom || $i==$copies-1){echo "</tr>";$x=0;}
          
        }
        echo "</table>";  
      }
      if ($n_size=="BESAR") {
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=$nm_brg.xls");
        $kolom = 2;
        $x=0;
       echo "<table>";
        for ($i=0; $i < $copies ; $i++) 
        { 
          $x++;
          if ($i % $kolom == 0) echo "<tr>"; 
          ?> 
           <td style="padding: 3px" class="hrf_barcode" style="font-size: 45px"><?='*'.$kd_bar.'*'?><br><?=$kd_bar?></td>
          <?php 
          if ($x==$kolom || $i==$copies-1){echo "</tr>";$x=0;}
          
        }
        echo "</table>";  
      }
    ?>           	