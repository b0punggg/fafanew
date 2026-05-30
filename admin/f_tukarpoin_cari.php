<?php
	$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : ''; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
?>
<style>
  th {
  position: sticky;
  top: -1px; 
  color:#fff;
  background-color:#6271c8;
  box-shadow: 0 2px 2px -1px black;
  }
  table, td {
    border: 1px solid lightgrey;
    padding: 1px;
  }
  th {
    border: 1px solid grey;
    padding: 3px;
  }
  table {
    border-spacing: 2px;
  }
</style>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	  <table id="tabmembertukar" class="table-bordered table-hover" style="font-size:10pt;background-color: white;width: 100%;border-collapse: collapse;white-space: nowrap;font-size:9pt">
	    <tr align="middle" class="yz-theme-l4" style="background-color: white;position:sticky;top:1px">
	      <th style="width: 2%">NO</th>
	      <th>NAMA</th>
	      <th style="width: 30%">ALAMAT</th>
	      <th style="width: 15%">POIN</th>
	    </tr>
	    <?php
	    include "config.php";
	    session_start(); 
     	    
	    $con1=opendtcek();
      $kd_toko = isset($_SESSION['id_toko']) ? mysqli_real_escape_string($con1, $_SESSION['id_toko']) : '';
	    
	    // Ambil semua data member tanpa pagination
	    $params = isset($keyword) ? mysqli_real_escape_string($con1, $keyword) : '';
	    $param = '%'.$params.'%';
	    
	    if($params != ""){
	      $sql1 = mysqli_query($con1, "SELECT * FROM member WHERE kd_toko='$kd_toko' AND (nm_member LIKE '$param' OR kd_member LIKE '$param') ORDER BY nm_member ASC");
	    } else {
	      $sql1 = mysqli_query($con1, "SELECT * from member WHERE kd_toko='$kd_toko' ORDER BY nm_member ASC LIMIT 20");
	    }
	    
	    $no=0;
	    while($databrg = mysqli_fetch_array($sql1)){
	      $no++;
	      $poin_member = isset($databrg['poin']) ? number_format($databrg['poin'], 0, ',', '.') : '0';
	      
	    ?>
	      <tr>
	        <td style="text-align: right"><?php echo $no?>&nbsp;</td>
	        <td>
	          <input class="w3-input" type="text" readonly value="<?=$databrg['nm_member']; ?>"
	          style="border: none;background-color: transparent;cursor: pointer"
	          onkeydown="if(event.keyCode==13){this.click()}" 
	          onclick="document.getElementById('<?='pilmembertukar'.$no?>').click();">
	        </td>

	        <td align="left" class="button" style="cursor:pointer;">
	          <input id="<?='pilmembertukar'.$no?>" class="w3-input" type="text" readonly="" value="<?=$databrg['al_member']; ?>" 
	          style="border: none;background-color: transparent;cursor: pointer"
	          onkeydown="if(event.keyCode==13){this.click()}" 
	          onclick="document.getElementById('kd_member_tukar').value='<?=mysqli_escape_string($con1,$databrg['kd_member']) ?>';document.getElementById('nm_member_tukar').value='<?=mysqli_escape_string($con1,$databrg['nm_member']) ?>';document.getElementById('viewcari_member').style.display='none';setTimeout(function(){cekpoinmember();}, 100);">
	        </td>
	        <td align="right" style="font-weight: bold; color: #ff6b00;"><?php echo $poin_member; ?></td>
	      </tr>
	    <?php
	    }
	    ?>
	  </table>
</div>

<script>
  function carinmmembertukar() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("keycari_member");
    filter = input.value.toUpperCase();
    table = document.getElementById("tabmembertukar");
    if(table) {
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("input")[0];
        if (td) {
          txtValue = td.textContent || td.value;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }       
      }
    }
  }
</script>

<?php
    mysqli_close($con1);
	$html = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>

