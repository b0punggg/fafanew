<?php
  $nm_sup = $_POST['keyword'];
  ob_start();
  include 'config.php';
  session_start();
?>
  <div id="tabsup" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 350px">
    <table class="arrow-nav table table-bordered table-sm table-hover" style="font-size:8pt; ">
      <tr align="middle" class="yz-theme-l3">
        <th>SUPPLIER</th>
        <th>OPSI</th>
      </tr>
      <?php 
      $consup=opendtcek();
      $sql = mysqli_query($consup, "SELECT kd_sup,nm_sup from supplier WHERE nm_sup like '%$nm_sup%' ORDER BY nm_sup ASC ");
      $i=0;
      while ($datasup = mysqli_fetch_array($sql)){
        $i++;
      ?>
      <tr>
        <td align="middle" ><input id="<?='insup'.$i?>" class="w3-input" type="text" readonly="" value="<?=$datasup['nm_sup']?>" style="border: none;background-color: transparent;cursor: pointer" tabindex="3" onkeydown="if(event.keyCode==13){document.getElementById('<?='btnsup'.$i?>').click()}" onclick="document.getElementById('<?='btnsup'.$i?>').click()"></td>
        <td ><button id="<?='btnsup'.$i?>" type="button" onclick="document.getElementById('nm_sup').value='<?=$datasup['nm_sup'] ?>';document.getElementById('kd_sup').value='<?=$datasup['kd_sup'] ?>';">Pilih</button></td>
      </tr>  
      <?php   
      }
      unset($datasup);mysqli_close($consup);
      ?>
    </table>
  </div>  <!-- tabsub -->
  
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>