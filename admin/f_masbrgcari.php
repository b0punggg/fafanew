<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();

?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	  <table class="table table-bordered table-sm table-striped table-hover hrf_arial;" style="border-collapse: collapse;white-space: nowrap;">
	    <tr align="middle" class="yz-theme-l3">
	      <th>NO.</th> 	
	      <th>KD. BARANG</th>
	      <th>NAMA BARANG</th>
	      <!-- <th>MASUK</th>
	      <th>KELUAR</th> -->
	      <th>OPSI</th>
	    </tr>
	    <?php
	    include "config.php";
        session_start(); 
        $connect=opendtcek();      	    
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 10; // Jumlah data per halamannya
	    $limit_start = ($page - 1) * $limit;
	    
	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
   		    $id_toko=$_SESSION['id_toko'];
	    	$params = mysqli_real_escape_string($connect, $keyword);
	    	$param='%'.$params.'%';  	
	    	// echo $params;
          if ($params=="") {	 
          	  $sql1 = mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.jml_brg,mas_brg.kd_bar,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko,mas_brg.hrg_beli FROM mas_brg 
          	  	ORDER BY mas_brg.kd_brg ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg  ORDER BY kd_brg");
          }
          else {
	          $sql1 =mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.jml_brg,mas_brg.kd_bar,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko,mas_brg.hrg_beli
	          	FROM mas_brg 
	          	WHERE nm_brg LIKE '$param' ORDER BY mas_brg.kd_brg ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg WHERE kd_brg LIKE '$param'");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];
          $sql1 = mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.jml_brg,mas_brg.kd_bar,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko,mas_brg.hrg_beli,brand.nm_brand,kategori.nm_kat FROM mas_brg  
          ORDER BY mas_brg.kd_brg ASC LIMIT $limit_start, $limit");
	      // Buat query untuk menghitung semua jumlah data
	      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg ORDER BY kd_brg");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
        $no=0;	    
	    while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
	      $nm_kem1=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem1']),$connect);
	      $nm_kem2=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem2']),$connect);
	      $nm_kem3=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem3']),$connect);
	    ?>
	      <tr>
	      	<td align="right"><?php echo $no; ?></td>
	        <td align="left"><?php echo $databrg['kd_brg']; ?></td>
	        <td align="left"><?php echo $databrg['nm_brg']; ?></td>
	        <!-- <td align="left"><?php echo $databrg['brg_msk']; ?></td>
	        <td align="left"><?php echo $databrg['brg_klr']; ?></td> -->
	        <td>
	          	<button onclick="document.getElementById('no_urutbrg').value='<?=mysqli_escape_string($connect,$databrg['no_urut']) ?>';
	          	document.getElementById('kd_brg').value='<?=mysqli_escape_string($connect,$databrg['kd_brg']) ?>';
	          	   document.getElementById('nm_brg').value='<?=mysqli_escape_string($connect,$databrg['nm_brg']) ?>';
	          	   document.getElementById('kd_bar').value='<?=mysqli_escape_string($connect,$databrg['kd_bar']) ?>';
	          	  
	          	   document.getElementById('kd_sat1').value='<?=mysqli_escape_string($connect,$databrg['kd_kem1']) ?>';document.getElementById('nm_sat1').value='<?=$nm_kem1?>';document.getElementById('jum_sat1').value='<?=mysqli_escape_string($connect,$databrg['jum_kem1']) ?>';document.getElementById('hrg_jum1').value='<?=gantitides(mysqli_escape_string($connect,$databrg['hrg_jum1'])) ?>';

	          	   document.getElementById('kd_sat2').value='<?=mysqli_escape_string($connect,$databrg['kd_kem2']) ?>';document.getElementById('nm_sat2').value='<?=$nm_kem2?>';document.getElementById('jum_sat2').value='<?=mysqli_escape_string($connect,$databrg['jum_kem2']) ?>';document.getElementById('hrg_jum2').value='<?=gantitides(mysqli_escape_string($connect,$databrg['hrg_jum2'])) ?>';
	          	   
	          	   document.getElementById('kd_sat3').value='<?=mysqli_escape_string($connect,$databrg['kd_kem3']) ?>';document.getElementById('nm_sat3').value='<?=$nm_kem3?>';document.getElementById('jum_sat3').value='<?=mysqli_escape_string($connect,$databrg['jum_kem3']) ?>';document.getElementById('hrg_jum3').value='<?=gantitides(mysqli_escape_string($connect,$databrg['hrg_jum3'])) ?>';
	          	   document.getElementById('saycode').innerHTML='<?=$databrg['kd_bar'] ?>' ;" class="btn-primary fa fa-edit" type="button" style="cursor: pointer; border-style;font-size: 12pt" title="Edit Data">
	            </button>
	            <?php $param=mysqli_escape_string($connect,$databrg['no_urut']); ?>
	            <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){ location.href='f_masbrghapus_act.php?param=<?php echo $param ?>'}" class="btn-danger fa fa-trash" style="cursor: pointer;font-size: 12pt" title="Hapus Data"></button>
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="listbrg(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="listbrg(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="listbrg(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="listbrg(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="listbrg(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>
		

<?php
    mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>