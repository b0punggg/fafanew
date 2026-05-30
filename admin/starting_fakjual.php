<?php
  ob_start();
  include "config.php";
  include "cekmasuk.php";
  $con=opendtcek();
  $sq=mysqli_query($con,"SELECT * FROM toko");
  while($dt=mysqli_fetch_assoc($sq)){
    $kd_toko=$dt['kd_toko'];
    
    $ck=mysqli_query($con,"SELECT * FROM seting WHERE nm_per='$kd_toko'");
    if(mysqli_num_rows($ck)<1){
      $f=mysqli_query($con,"INSERT INTO seting VALUES('','$kd_toko','1')");
      if($f){ ?>
        <div class="form-group row">
          <label for="<?=$kd_toko?>" class="col-sm-4 col-form-label"><b>Fak-<?=$kd_toko?></b></label>
          <div class="col-sm-8">
            <input class="form-control" type="number" max="1000000000" min="1" id="<?=$kd_toko?>" name="<?=$kd_toko?>" value="1" style="border: 1px solid black;font-size: 10pt;height: 33px;">
          </div>   
        </div>  
       <?php 
      }  
    }else{ ?>
      <?php
      $dc=mysqli_query($con,"SELECT kode FROM seting WHERE nm_per='$kd_toko'");
      $dt=mysqli_fetch_assoc($dc);
      $kode=$dt['kode'];
      mysqli_free_result($dc);unset($dt);
      ?>
      <div class="form-group row">
        <label for="<?=$kd_toko?>" class="col-sm-4 col-form-label"><b>Fak-<?=$kd_toko?></b></label>
        <div class="col-sm-8">
          <input class="form-control" type="number" max="1000000000" min="1" id="<?=$kd_toko?>" name="<?=$kd_toko?>" value="<?=$kode?>" style="border: 1px solid black;font-size: 10pt;height: 33px;">
        </div>   
      </div>  
      <?php
    }
    mysqli_free_result($ck);
  }
  mysqli_free_result($sq);unset($dt);
?>
<?php
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>