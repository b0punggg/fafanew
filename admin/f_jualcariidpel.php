<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
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
	  <table class= table-hover" style="font-size:11px; background-color:;width: 100%">
	    <tr align="middle" class="yz-theme-l3">
	      <th>KD. PEL.</th>
	      <th>NAMA</th>
	      <th>ALAMAT</th>
	      <th>OPSI</th>
	    </tr>
	    <?php
	    include "config.php";
	    session_start(); 
     	    
	    $con1=opendtcek();
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;

	    $limit = 8; // Jumlah data per halamannya

	    $limit_start = ($page - 1) * $limit;
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
   		    $id_toko=$_SESSION['id_toko'];
	    	$params = mysqli_real_escape_string($con1, $keyword);
	    	$param='%'.$params.'%';  	
	    	// echo $params;
          if ($params=="") {	 
          	  $sql1 = mysqli_query($con1, "SELECT * FROM pelanggan ORDER BY kd_pel ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($con1, "SELECT COUNT(*) AS jumlah FROM pelanggan  ORDER BY nm_pel");
          }
          else {
	          $sql1 =mysqli_query($con1, "SELECT * FROM pelanggan WHERE nm_pel LIKE '$param' ORDER BY kd_pel ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($con1, "SELECT COUNT(*) AS jumlah FROM pelanggan WHERE nm_pel LIKE '$param'");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];
          $sql1 = mysqli_query($con1, "SELECT * from pelanggan  ORDER BY kd_pel ASC LIMIT $limit_start, $limit");
	      // Buat query untuk menghitung semua jumlah data
	      $sql2 = mysqli_query($con1, "SELECT COUNT(*) AS jumlah FROM pelanggan ORDER BY kd_pel");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
	    $no=0;
	    // Tambahkan unique_suffix untuk menghindari duplikasi ID
	    $unique_suffix = '_' . str_replace([' ', '.'], '', microtime(true)) . '_' . rand(10000, 99999);
	    while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
	      
	    ?>
	      <tr>
	        <td align="left"><?php echo $databrg['kd_pel']; ?></td>
	        <td align="left"><input class="w3-input" type="text" onkeydown="if(event.keyCode==13){document.getElementById('<?='tmb23'.$no.$unique_suffix?>').click();}" value="<?php echo $databrg['nm_pel']; ?>" readonly tabindex='3' style="border: none;outline: none;background-color: transparent;"></td>
	        <td align="left"><?php echo $databrg['al_pel']; ?></td>
	        <td>
	          	<button id="<?='tmb23'.$no.$unique_suffix?>" onclick="
	          	    document.getElementById('kd_pel_byr').value='<?=mysqli_escape_string($con1,$databrg['kd_pel']) ?>';
	          	    document.getElementById('nm_pelbayar').value='<?=mysqli_escape_string($con1,$databrg['nm_pel']) ?>';
	          	    document.getElementById('viewidpelbayar').style.display='none'" class="btn-primary fa fa-edit" type="button" style="cursor: pointer; border-style;font-size: 12pt" title="Edit Data">
	            </button>
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="bayarcariidpel(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="bayarcariidpel(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="bayarcariidpel(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="bayarcariidpel(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="bayarcariidpel(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>
		

<?php
    mysqli_close($con1);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>