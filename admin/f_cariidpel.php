<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
?>
<style>
  th {
    position: sticky;
    top: 0px; 
    /*color:#fff;
    background-color:#6271c8;*/
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }

  table, td {
    border: 1px solid lightgrey;
    padding: 1px;
  }
  th {
    border: 1px solid lightgrey;
    padding: 3px;
  }
  table {
    border-spacing: 2px;
  }

</style>
<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	  <table id="table" class="arrow-nav table-hover" style="font-size:11px; width: 100%">
	    <tr align="middle" class="yz-theme-l3">
	      <th>KD. PEL.</th>
	      <th>NAMA</th>
	      <th>ALAMAT</th>
	      <th width="1%">OPSI</th>
	    </tr>
	    <?php
	    include "config.php";
	    session_start(); 
        $connect=opendtcek();
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;

	    $limit = 8; // Jumlah data per halamannya

	    $limit_start = ($page - 1) * $limit;
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
   		    $id_toko=$_SESSION['id_toko'];
	    	$params = mysqli_real_escape_string($connect, $keyword);
	    	$param='%'.$params.'%';  	
	    	// echo $params;
          if ($params=="") {	 
          	  $sql1 = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY kd_pel ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM pelanggan  ORDER BY nm_pel");
          }
          else {
	          $sql1 =mysqli_query($connect, "SELECT * FROM pelanggan WHERE nm_pel LIKE '$param' ORDER BY kd_pel ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM pelanggan WHERE nm_pel LIKE '$param'");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];
          $sql1 = mysqli_query($connect, "SELECT * from pelanggan  ORDER BY kd_pel ASC LIMIT $limit_start, $limit");
	      // Buat query untuk menghitung semua jumlah data
	      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM pelanggan ORDER BY kd_pel");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
	    $no=0;
	    // Tambahkan unique_suffix untuk menghindari duplikasi ID
	    $unique_suffix = '_' . str_replace([' ', '.'], '', microtime(true)) . '_' . rand(10000, 99999);
	    while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
	      
	    ?>
	      <tr style="cursor: pointer">
	        <td align="left"><?php echo $databrg['kd_pel']; ?></td>
	        <td align="left"><input class="w3-input" type="text" onkeydown="if(event.keyCode==13){document.getElementById('<?='tmb2'.$no.$unique_suffix?>').click();}" onclick="document.getElementById('<?='tmb2'.$no.$unique_suffix?>').click()" value="<?php echo $databrg['nm_pel']; ?>" readonly tabindex='3' style="border: none;background-color: transparent;cursor: pointer"></td>
	        <td align="left">
	        	<input type="text" class="w3-input" readonly value="<?php echo $databrg['al_pel']; ?>" style="border: none;background-color: transparent;cursor: pointer" 
	        	onkeydown="if(event.keyCode==13){document.getElementById('<?='tmb2'.$no.$unique_suffix?>').click();}" onclick="document.getElementById('<?='tmb2'.$no.$unique_suffix?>').click()"></td>
	        <td>
	          	<input id="<?='tmb2'.$no.$unique_suffix?>" type="button" class="btn btn-primary" style="cursor: pointer;font-size: 10pt;color: white;background-image: url('img/searchicok.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 5px 3px 26px;" onclick="
	          	   document.getElementById('kd_pel').value='<?=mysqli_escape_string($connect,$databrg['kd_pel']) ?>';
	          	   document.getElementById('kd_pel_byr').value='<?=mysqli_escape_string($connect,$databrg['kd_pel']) ?>';
	          	   document.getElementById('nm_pelbayar').value='<?=mysqli_escape_string($connect,$databrg['nm_pel']) ?>';
	          	   
	          	   document.getElementById('viewidpel').style.display='none'" >
	        </td>    

	      </tr>
	    <?php
	    }
	    ?>
	  </table>
	</div>
	
	<div class="w3-border yz-theme-l5">
		<nav  aria-label="Page navigation example" style="margin-top:15px;font-size: 8pt">
		  <ul class="pagination justify-content-center">
		    <!-- LINK FIRST AND PREV -->
		    <?php
		    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
		      <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&laquo;</a></li>
		    <?php
		    }else{ // Jika page bukan page ke 1
		      $link_prev = ($page > 1)? $page - 1 : 1;
		    ?>
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="cariidpel(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="cariidpel(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NUMBER -->
		    <?php
		    $jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamannya
		    $jumlah_number = 1; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
		    $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
		    $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
		    
		    for($i = $start_number; $i <= $end_number; $i++){
		      $link_active = ($page == $i)? ' class="active"' : '';
		    ?>
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="cariidpel(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NEXT AND LAST -->
		    <?php
		    if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir
		    ?>
		      <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&raquo;</a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
		    <?php
		    }else{ // Jika Bukan page terakhir
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="cariidpel(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="cariidpel(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>

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