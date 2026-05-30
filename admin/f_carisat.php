<?php 
$keyword=$_POST['keyword'];
$nm_sat1=$_POST['kdfield1'];
$kd_sat1=$_POST['kdfield2'];
$kd_sat2=$_POST['kdfield3'];
$kd_sat3=$_POST['kdfield4'];
$kd_sat4=$_POST['kdfield5'];
$t_index=$_POST['t_index'];
ob_start();
include 'config.php';
session_start();
$connect=opendtcek();
$kd_toko=$_SESSION['id_toko'];

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
    padding: 3px;
  }
  th {
    border: 1px solid grey;
    padding: 5px;
  }
  table {
    border-spacing: 2px;
  }
</style>
<table class="arrow-nav table-hover table-striped hrf_res" style="width: 100%">
	<tr align="middle" class="yz-theme-l3">
	  <th>SATUAN</th>
	  <!-- <th width="2%">OPSI</th> -->
	</tr>
    <?php
      $params = mysqli_real_escape_string($connect, $keyword);
	  $param='%'.$params.'%';  	
    	// echo $params;
      if ($params=="") {	 
      	  $sql1 = mysqli_query($connect, "SELECT * FROM kemas  ORDER BY nm_sat2 ASC");
          
      }
      else {
          $sql1 =mysqli_query($connect, "SELECT * FROM kemas
          	WHERE nm_sat2 LIKE '$param' ORDER BY nm_sat2 ASC");    
      }	
      
      $no=0;
      while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
      $no++;
      ?>

       <tr>
        <td align="left"><input class="w3-input" type="text" onkeydown="if(event.keyCode==13){this.click();}" 
        	onclick="
        	  document.getElementById('<?=$nm_sat1?>').value='<?=mysqli_escape_string($connect,$databrg['nm_sat2']) ?>';
        	  document.getElementById('<?=$kd_sat1?>').value='<?=mysqli_escape_string($connect,$databrg['no_urut']) ?>';
            if ('<?=$databrg['nm_sat2']?>'=='-NONE-' ) {
              document.getElementById('<?=$kd_sat2?>').value='0';
              document.getElementById('<?=$kd_sat3?>').value='0';
              document.getElementById('<?=$kd_sat4?>').value='0';
            }"
        	value="<?php echo $databrg['nm_sat2']; ?>" readonly tabindex="<?=$t_index?>" style="border: none;background-color: transparent;cursor: pointer">
        </td>
	    </tr>    
      <?php
        
      }// while
	  ?>
</table>
<script>
	$('table.arrow-nav').keydown(function(e){
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
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>