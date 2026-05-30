<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();

?>
<style>
  th {
    position: sticky;
    top: 0px; 
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }

  table, td {
    border: 1px solid lightgrey;
    padding: 0px;
  }
  th {
    border: 1px solid lightgrey;
    padding: 3px;
  }
  table {
    border-spacing: 0px;
  }
</style>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge; ">
  <table class="arrow-navsat table-hover" style="font-size:9pt; width: 100%">
    <tr align="middle" class="yz-theme-l3">
      <th>KD.SATUAN</th>
      <th>KET</th>
      <th>OPSI</th>
    </tr>
    <?php
    include "config.php";
    session_start();
    $connect=opendtcek();
    $kd_toko=$_SESSION['id_toko'];
    $param=mysqli_escape_string($connect,$keyword); 
    
    $sql=mysqli_query($connect,"SELECT * FROM mas_brg WHERE kd_brg='$param' ");
    $no=0;
    // Tambahkan timestamp dan random untuk membuat id lebih unik dan menghindari duplikasi
    // Gunakan microtime untuk memastikan unik setiap kali file dipanggil
    $unique_suffix = '_' . str_replace([' ', '.'], '', microtime(true)) . '_' . rand(10000, 99999);
    while ($data=mysqli_fetch_array($sql)) { 	
      //echo $data['kd_kem1'];
      $no=$no+1;
      $satkecil1=ceknmkem2(mysqli_escape_string($connect,$data['kd_kem1']),$connect);
      $satkecil1_1=ceknmkem(mysqli_escape_string($connect,$data['kd_kem1']),$connect);
	    $satkecil2=ceknmkem2(mysqli_escape_string($connect,$data['kd_kem2']),$connect);
	    $satkecil2_1=ceknmkem(mysqli_escape_string($connect,$data['kd_kem2']),$connect);
	    $satkecil3=ceknmkem2(mysqli_escape_string($connect,$data['kd_kem3']),$connect);
	    $satkecil3_1=ceknmkem(mysqli_escape_string($connect,$data['kd_kem3']),$connect);
    ?>
      <tr>
        <td align="left">
          <input class="w3-input" type="text" 
          onkeydown="if(event.keyCode==13){document.getElementById('<?='tmb1'.$no.$unique_suffix?>').click();}" 
          onclick="document.getElementById('<?='tmb1'.$no.$unique_suffix?>').click();"
          value="<?php echo $satkecil1; ?>" 
          readonly tabindex='8' id="<?='nm_satu1'.$no.$unique_suffix?>" 
          style="border: none;background-color: transparent;cursor:pointer">
        </td>

        <td align="left">
          <input class="w3-input" type="text" 
          onkeydown="if(event.keyCode==13){document.getElementById('<?='tmb1'.$no.$unique_suffix?>').click();}" 
          onclick="document.getElementById('<?='tmb1'.$no.$unique_suffix?>').click();"
          value="<?php echo $satkecil1_1; ?>" 
          readonly id="<?='nm_satu1'.$no.$unique_suffix?>" 
          style="border: none;background-color: transparent;cursor: pointer">
        </td>

        <td>
          <input type="button" class="btn btn-primary"
          onkeydown="if(event.keyCode==13){this.click();}" 
          onclick="document.getElementById('kd_sat').value='<?=mysqli_escape_string($connect,$data['kd_kem1']) ?>';
               document.getElementById('nm_sat').value='<?=$satkecil1 ?>';
               cekjmlstok(document.getElementById('kd_sat').value,document.getElementById('kd_brg').value);
               // Ambil discount promo dengan harga jual
               getdiscpromo(document.getElementById('kd_brg').value, <?=isset($data['hrg_jum1']) ? floatval($data['hrg_jum1']) : 0 ?>);
               document.getElementById('tabkem').style.display='none';
               document.getElementById('tmb-add').focus()"          
          readonly id="<?='tmb1'.$no.$unique_suffix?>" 
          style="cursor: pointer;font-size: 10pt;color: white;background-image: url('img/searchicok.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 5px 0px 26px;">
        </td>    
      </tr>

      <?php if($data['kd_kem2']>1){ ?>
      <tr>
        <td>
          <input class="w3-input" type="text" 
          onkeydown="if(event.keyCode==13){document.getElementById('<?='tmb4'.$no.$unique_suffix?>').click();}" 
          onclick="document.getElementById('<?='tmb4'.$no.$unique_suffix?>').click();"
          value="<?php echo $satkecil2; ?>" 
          readonly tabindex='8' id="<?='nm_satu2'.$no.$unique_suffix?>" 
          style="border: none;background-color: transparent;cursor:pointer">
        </td>

        <td>
          <input class="w3-input" type="text" 
          onkeydown="if(event.keyCode==13){document.getElementById('<?='tmb4'.$no.$unique_suffix?>').click();}" 
          onclick="document.getElementById('<?='tmb4'.$no.$unique_suffix?>').click();"
          value="<?php echo $satkecil2_1; ?>" 
          readonly 
          style="border: none;background-color: transparent;cursor: pointer">    
        </td>

        <td>
          <input type="button" class="btn btn-primary"
          onkeydown="if(event.keyCode==13){this.click();}" 
          onclick="
               document.getElementById('kd_sat').value='<?=mysqli_escape_string($connect,$data['kd_kem2']) ?>';
               document.getElementById('nm_sat').value='<?=$satkecil2 ?>';
               cekjmlstok(document.getElementById('kd_sat').value,
               document.getElementById('kd_brg').value);
               // Ambil discount promo dengan harga jual
               getdiscpromo(document.getElementById('kd_brg').value, <?=isset($data['hrg_jum2']) ? floatval($data['hrg_jum2']) : 0 ?>);
               document.getElementById('tabkem').style.display='none';
               document.getElementById('tmb-add').focus()"
          readonly id="<?='tmb4'.$no.$unique_suffix?>" 
          style="cursor: pointer;font-size: 10pt;color: white;background-image: url('img/searchicok.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 5px 0px 26px;">
        </td>    
      </tr>
      <?php } ?>

      <?php if($data['kd_kem3']>1){ ?>
      <tr>
        <td align="left"><input class="w3-input" type="text" onkeydown="if(event.keyCode==13){document.getElementById('<?='tmb3'.$no.$unique_suffix?>').click();}" value="<?php echo $satkecil3; ?>" readonly tabindex='8' id="<?='nm_satu3'.$no.$unique_suffix?>" style="border: none;outline: none;background-color: transparent;"></td>
        <td align="left"><?php echo $satkecil3_1; ?></td>
        <td>
          	<button id="<?='tmb3'.$no.$unique_suffix?>" onclick="document.getElementById('kd_sat').value='<?=mysqli_escape_string($connect,$data['kd_kem3']) ?>';
          	   document.getElementById('nm_sat').value='<?=$satkecil3?>';
               cekjmlstok(document.getElementById('kd_sat').value,document.getElementById('kd_brg').value);
               // Ambil discount promo dengan harga jual
               getdiscpromo(document.getElementById('kd_brg').value, <?=isset($data['hrg_jum3']) ? floatval($data['hrg_jum3']) : 0 ?>);
          	   document.getElementById('tabkem').style.display='none';document.getElementById('tmb-add').focus()" class="btn-primary fa fa-edit" type="button" style="cursor: pointer; font-size: 12pt" title="Edit Data">
            </button>
        </td>    
      </tr>
      <?php } ?>
    <?php } 
    if (!empty($param)){
      if ($no>=1){
        // cek jika satuan awal renteng pakai default 
        $satbesar_result = carisatbesar3($param,$connect);
        $xx = !empty($satbesar_result) ? explode(';', $satbesar_result) : array();
        $kd_satbesar = isset($xx[0]) ? $xx[0] : '';
        $nm_satbesar = isset($xx[1]) ? $xx[1] : '';
        
        if (!empty($nm_satbesar) && $nm_satbesar=="RTG"){
          $hrg_jual_auto = carihrgjual($param, $kd_satbesar);
          ?><script>
            document.getElementById('kd_sat').value='<?=$kd_satbesar?>';
            document.getElementById('nm_sat').value='<?=$nm_satbesar?>';
            cekjmlstok('<?=$kd_satbesar?>','<?=$param?>');
            // Ambil discount promo dengan harga jual
            getdiscpromo('<?=$param?>', <?=floatval($hrg_jual_auto)?>);
            </script>
          <?php 
        } else {
          $satkecil_result = carisatkecil2($param,$connect);
          $x = !empty($satkecil_result) ? explode(';', $satkecil_result) : array();
          $kd_satkecil = isset($x[0]) ? $x[0] : '';
          if (!empty($kd_satkecil)) {
            $hrg_jual_auto = carihrgjual($param, $kd_satkecil);
            ?><script>
              document.getElementById('kd_sat').value='<?=$kd_satkecil?>';
              document.getElementById('nm_sat').value='<?=ceknmkem2($kd_satkecil,$connect)?>';
              cekjmlstok('<?=$kd_satkecil?>','<?=$param?>');
              // Ambil discount promo dengan harga jual
              getdiscpromo('<?=$param?>', <?=floatval($hrg_jual_auto)?>);
              </script>
            <?php
          }
        }
      }
    }  
    ?>  

  </table>
</div>
<script>
$('table.arrow-navsat').keydown(function(e){
    var $table = $(this);
    var $active = $('input:focus,select:focus',$table);
    var $next = null;
    var focusableQuery = 'input:visible,select:visible,textarea:visible';
    var position = parseInt( $active.closest('td').index()) + 1;
    console.log('position :',position);
    switch(e.keyCode){
        case 37: // <Left>
            $next = $active.parent('td').prev().find(focusableQuery);   
            break;
        case 38: // <Up>                    
            $next = $active
                .closest('tr')
                .prev()                
                .find('td:nth-child(' + position + ')')
                .find(focusableQuery)
            ;
            
            break;
        case 39: // <Right>
            $next = $active.closest('td').next().find(focusableQuery);            
            break;
        case 40: // <Down>
            $next = $active
                .closest('tr')
                .next()                
                .find('td:nth-child(' + position + ')')
                .find(focusableQuery)
            ;
            break;
    }       
    if($next && $next.length)
    {        
        $next.focus();
    }
});
</script>   	
<?php
  mysqli_close($connect);
	$html = ob_get_contents(); 
  ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>