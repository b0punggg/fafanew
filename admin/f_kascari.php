<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	  <table class="table table-bordered table-sm table-striped table-hover" style="font-size:9pt; ">
	    <tr align="middle" class="yz-theme-l3">
	      <th>No.</th>
	      <th>TANGGAL</th>
	      <th>KAS</th>
	      <th colspan="2">OPSI</th>
	    </tr>
	    <?php
	    include "config.php";
	    session_start();
	    $connect=opendtcek();
	    $kd_toko=$_SESSION['id_toko'];
	    $id_user=$_SESSION['id_user'];
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 8; // Jumlah data per halamannya
	    $limit_start = ($page - 1) * $limit;
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	    	$param = mysqli_real_escape_string($connect, $keyword);
	    	//$param='%'.$params.'%';  	
	    	//echo $param;
          if ($param=="") {	 
          	  $sql = mysqli_query($connect, "SELECT * FROM kas_harian
          	  	     WHERE kd_toko='$kd_toko' AND id_user='$id_user'
          	  	     ORDER BY tgl_kas ASC LIMIT $limit_start, $limit");

	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM kas_harian WHERE kd_toko='$kd_toko' ");
          }
          else {
			  $xx=explode(';',$param);
			  $bln=$xx[0];$thn=$xx[1]; 
			  //echo $bln.''.$thn;
	          $sql =mysqli_query($connect, "SELECT * FROM kas_harian
	          	  WHERE MONTH(tgl_kas)='$bln' AND YEAR(tgl_kas)='$thn' and kd_toko='$kd_toko' ORDER BY tgl_kas ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM kas_harian WHERE MONTH(tgl_kas)='$bln' AND YEAR(tgl_kas)='$thn' AND kd_toko='$kd_toko' ");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];
          $sql = mysqli_query($connect, "SELECT * from kas_harian WHERE kd_toko='$kd_toko' ORDER BY tgl_kas ASC LIMIT $limit_start, $limit");

	      // Buat query untuk menghitung semua jumlah data
	      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM kas_harian WHERE kd_toko='$kd_toko'");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }

	    $no=$limit_start;
	    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
	       $no++;
	      
	      ?>
	      <tr>
	        <td align="right"><?php echo $no ?></td>
	        <td align="left"><?php echo gantitgl($data['tgl_kas']); ?></td>
	        <td align="right"><?php echo gantitides($data['uang_kas']); ?></td>
	        <td align="center">
	          	<button onclick="
	          	   document.getElementById('no_urut').value='<?=mysqli_escape_string($connect,$data['no_urut']) ?>';
	          	   document.getElementById('tgl_kas').value='<?=mysqli_escape_string($connect,$data['tgl_kas']) ?>';
	          	   document.getElementById('uang_kas').value='<?=mysqli_escape_string($connect,gantitides($data['uang_kas'])) ?>';
	        	   document.getElementById('keyktkas').value='';
	        	   document.getElementById('btn-ktkas').click()" class="btn-primary fa fa-edit" style="cursor: pointer;font-size: 9pt" title="Edit Data">
	            </button> &nbsp;
	        	<?php $param=mysqli_escape_string($connect,$data['no_urut']); ?>
	           <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){ location.href='f_kashapus_act.php?param=<?php echo $param ?>'}" class="btn-danger fa fa-trash" style="cursor: pointer;font-size: 9pt" title="Hapus Data"></button>
	        </td>
	        <!-- <td>
	           <button onclick="
	            document.getElementById('tgl_cari').value=
	            '<?=mysqli_escape_string($connect,$data['tgl_kas']).';'.$data['uang_kas'].';'.$data['id_user'] ?>';cariaruskas(1,true);" 
	           	class="btn-warning fa fa-bullhorn" style="cursor: pointer;font-size: 9pt" title="Arus Kas"></button>
	        </td> -->
	      </tr>
	      <?php
	    }
	    ?>
	  </table>
	</div>

	<?php 
	  if ($get_jumlah['jumlah']>=1){
	  	?>
	  	<script>document.getElementById("ket_rec").innerHTML=" Total Data "+<?php echo $get_jumlah['jumlah'] ?>+" Record" </script>	
	  	<?php
	  }else {
	  	?>
	  	<script>document.getElementById("ket_rec").innerHTML=" Belum Ada Data" </script>	
	  	<?php
	  }
	?>
	<div class="w3-border yz-theme-l5">
		<nav  aria-label="Page navigation example" style="margin-top:15px;font-size: 8pt">
		  <ul class="pagination justify-content-center">
		    <!-- LINK FIRST AND PREV -->
		    <?php
		    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)">First</a></li>
		      <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)">&laquo;</a></li>
		    <?php
		    }else{ // Jika page bukan page ke 1
		      $link_prev = ($page > 1)? $page - 1 : 1;
		    ?>
		      <li><a class="page-link yz-theme-d1" href="javascript:void(0);" onclick="carikas(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" href="javascript:void(0);" onclick="carikas(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" onclick="carikas(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NEXT AND LAST -->
		    <?php
		    if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)">&raquo;</a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)">Last</a></li>
		    <?php
		    }else{ // Jika Bukan page terakhir
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item "><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carikas(<?php echo $link_next; ?>, false)">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carikas(<?php echo $jumlah_page; ?>, false)">Last</a></li>
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