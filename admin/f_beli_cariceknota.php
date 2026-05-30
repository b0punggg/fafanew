 <?php 
 ob_start();
 include 'config.php';
 session_start();
 $concari=opendtcek();
 $kd_toko=$_SESSION['id_toko'];
 ?>
    
<!-- Proses list nota -->
  <div style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
    <table class=" table-hover" style="font-size:9pt;width: 100%;border-collapse: collapse;white-space: nowrap;">
      <tr align="middle" class="yz-theme-l1">
        <th width="3%">No.</th>
        <th>NO.FAKTUR</th>
        <th width="5%">TGL.FAKTUR</th>
        <th>SUPPLIER</th>
        <th width="1%">OPSI</th>
      </tr>
      <?php 
      $x=0;$no_fakx="";$tgl_fakx="";$kd_supx="";$nm_supx="";
       $cek3=mysqli_query($concari,"SELECT beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,supplier.nm_sup FROM beli_brg
       LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup 
       WHERE beli_brg.kd_toko='$kd_toko' AND INSTR(beli_brg.ket,'MUTASI')<=0 
       GROUP BY beli_brg.no_fak
       ORDER BY beli_brg.no_urut");  

       while($dcari=mysqli_fetch_assoc($cek3)){
        $no_fak=$dcari['no_fak'];
        $tgl_fak=$dcari['tgl_fak'];

        $ceknota=mysqli_query($concari,"SELECT * FROM beli_bay WHERE no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko' AND INSTR(ketbeli,'MUTASI')<=0");
        if (mysqli_num_rows($ceknota)<1){
           $x++;
          ?>
          <tr>
            <td align="right"><?php echo $x.'.' ?>&nbsp;</td>
            <td align="left">&nbsp;<?php echo $dcari['no_fak']; ?></td>
            <td align="middle">&nbsp;<?php echo gantitgl($dcari['tgl_fak']); ?></td>
            <td align="left">&nbsp;<?php echo $dcari['nm_sup']; ?></td>
            <td><button onclick="
                document.getElementById('form-ceknota').style.display='none';
                document.getElementById('no_fak').value='<?=mysqli_real_escape_string($concari,$dcari['no_fak'])?>';
                document.getElementById('tgl_fak').value='<?=mysqli_real_escape_string($concari,$dcari['tgl_fak'])?>';
                document.getElementById('kd_sup').value='<?=mysqli_real_escape_string($concari,$dcari['kd_sup'])?>';
                document.getElementById('nm_sup').value='<?=mysqli_real_escape_string($concari,$dcari['nm_sup'])?>';
                document.getElementById('kd_bar').focus();carinota(1,true);
                " class="btn-success fa fa-edit" style="cursor: pointer; font-size: 12pt">
            </button></td>
          </tr>  
          <?php
        }
        mysqli_free_result($ceknota);

       }
       mysqli_free_result($cek3);
      ?>
    </table>
  </div>    
<!--  -->
<?php 
  mysqli_close($concari);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>

    