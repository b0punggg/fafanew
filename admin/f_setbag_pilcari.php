
<?php
  $keyword1 = $_POST['search'];
  ob_start();
  session_start();
  include 'config.php';
  $no=0;
  $connect  = opendtcek();  
  $kd_toko  = $_SESSION['id_toko'];
  $sql      = mysqli_query($connect,"SELECT * FROM bag_brg ORDER BY no_urut"); 
  if(mysqli_num_rows($sql)>0) { ?>
    <style>
  tr{
    border:none;color:black;font-weight:lighter
  }
</style>
   
    <div style="overflow:auto;max-height:600px;min-height:100px;width:100%;">
      <table class="table-hover hrf_res2" style="width: 100%;border-collapse: collapse;white-space: nowrap;cursor:pointer"> <?php
        while($dt=mysqli_fetch_assoc($sql)) { $no++; ?>
          <tr>
            <td style="border:none" onclick="document.getElementById('<?=$no?>').click()">&nbsp; <?=$dt['nm_bag']?></td>
            <td id="<?=$no?>" align="right" style="border:none" onclick="document.getElementById('kd_cari2').value='<?=$dt['no_urut']?>';listbag(1, true)"><i class="fa fa-arrow-circle-right text-secondary" style="font-size:12pt"></i></td>
          </tr><?php
        } ?>
        <tr>
          <td style="border:none" onclick="document.getElementById('nb1').click()">&nbsp; SEMUA</td>
          <td id="nb1" align="right" style="border:none" onclick="document.getElementById('kd_cari2').value='';listbag(1, true)"><i class="fa fa-arrow-circle-right text-secondary" style="font-size:12pt"></i></td>
        </tr>
      </table> 
    </div> <?php		
  } 

  mysqli_close($connect);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?> 	