<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
	// echo 'keyword='.$keyword;
	include "config.php";
	session_start();
	$connect=opendtcek();
?>
<style>
  .la {
    box-shadow: 1px 1px 5px;
  }
</style> 

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	  <table id="dataservis" class="table table-bordered table-sm table-striped table-hover" style="font-size:10pt;">
	  	<center> USER LIST</center>	
	    <tr align="middle" class="yz-theme-l1">
	      <th>NO</th>
	      <th>USER ID</th>
	      <th>NAMA USER</th>
	      <th>ALAMAT</th>
	      <!-- <th>DIVISI</th> -->
	      <th>OTORITAS</th>
	      <th colspan="1">OPSI</th>
	    </tr>

	    <?php
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;

	    $limit = 10; // Jumlah data per halamannya

	    $limit_start = ($page - 1) * $limit;
	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	    	$params = mysqli_real_escape_string($connect, $keyword);
	    		
            //echo $params;
          if ($params=="") {	 
          	  $sql = mysqli_query($connect, "SELECT * from pemakai
          	  	  ORDER BY nm_user ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM pemakai ORDER BY nm_user");
          }
          else {
          	  $param='%'.$params.'%';
	          $sql =mysqli_query($connect, "SELECT * from pemakai
          	  	  WHERE pemakai.nm_user like '$param' 
	          	  ORDER BY pemakai.nm_user ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM pemakai WHERE pemakai.nm_user like '$param' ");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      $sql = mysqli_query($connect, "SELECT * from pemakai
          	  	  ORDER BY pemakai.nm_user ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM pemakai ORDER BY nm_user");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
	    $no=0;
	    echo $params;
	    
	    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
	      
	      if ($data['otoritas']=='1') {
	      	$oto1='OPERATOR';
	      } else {
	      	$oto1='ADMINISTRATOR';
	      }
	      
	    ?>
	     <?php $id=mysqli_escape_string($connect,$data['id_user']) ?>
         <?php if($id<>'1' ){ $no++;?>
	      <tr>
	      	<td align="middle"><?php echo $no; ?></td>
	        <td align="middle"><?php echo $data['id_user']; ?></td>
	        <td align="middle"><?php echo $data['nm_user']; ?></td>
	        <td align="middle"><?php echo $data['alamat']; ?></td>
	        <td align="middle"><?php echo $oto1 ?></td>
	        <td>
	        	<a onclick="document.getElementById('id_user').value='<?=mysqli_escape_string($connect,$data['id_user']) ?>';
	        	        document.getElementById('id_userpas').value='<?=mysqli_escape_string($connect,$data['id_user']) ?>';
	        	        document.getElementById('nm_user').value='<?=mysqli_escape_string($connect,$data['nm_user']) ?>';
	        		    document.getElementById('alamat').value='<?=mysqli_escape_string($connect,$data['alamat']) ?>';
	        		    document.getElementById('no_hp').value='<?=mysqli_escape_string($connect,$data['no_hp']) ?>';
	        		    document.getElementById('pilotor').value='<?=mysqli_escape_string($connect,$data['otoritas']) ?>';
	        		    document.getElementById('kd_tokoi').value='<?=mysqli_escape_string($connect,$data['kd_toko']) ?>';
	        		    document.getElementById('keyktpass').value='';
	        		    document.getElementById('btn-ktpass').click()" class="btn btn-warning btn-sm delete_data" style="cursor: pointer;box-shadow: 1px 1px 5px black"><i class="fa fa-edit"></i>
	        		    
	        	</a>		   
	        	<a id="<?=$id?>" class="btn btn-danger btn-sm hapus_data" style="cursor: pointer;box-shadow: 1px 1px 5px black"><i class="fa fa-trash"></i>
	        	</a>
	        </td>    
	    <?php } ?>
	      </tr>
	    <?php
	    }
	    ?>
	  </table>
	</div>
	<nav  aria-label="Page navigation example" style="margin-top:15px;font-size: 12px">
	  <ul class="pagination justify-content-center">
	    <!-- LINK FIRST AND PREV -->
	    <?php
	    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
	    ?>
	      <li class="page-item disabled la"><a class="page-link" href="#">First</a></li>
	      <li class="page-item disabled la"><a class="page-link" href="#">&laquo;</a></li>
	    <?php
	    }else{ // Jika page bukan page ke 1
	      $link_prev = ($page > 1)? $page - 1 : 1;
	    ?>
	      <li><a class="page-link la" href="javascript:void(0)" onclick="caridtpass(1, false)">First</a></li>
	      <li><a class="page-link la" href="javascript:void(0);" onclick="caridtpass(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
	    <?php
	    }
	    ?>
	    
	    <!-- LINK NUMBER -->
	    <?php
	    $jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamannya
	    $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
	    $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
	    $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
	    
	    for($i = $start_number; $i <= $end_number; $i++){
	      $link_active = ($page == $i)? ' class="active"' : '';
	    ?>
	      <li class="page-item la" <?php echo $link_active; ?>><a class="page-link" href="javascript:void(0);" onclick="caridtpass(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
	    <?php
	    }
	    ?>
	    
	    <!-- LINK NEXT AND LAST -->
	    <?php
	    // Jika page sama dengan jumlah page, maka disable link NEXT nya
	    // Artinya page tersebut adalah page terakhir 
	    if($page == $jumlah_page){ // Jika page terakhir
	    ?>
	      <li class="page-item disabled la"><a class="page-link" href="#">&raquo;</a></li>
	      <li class="page-item disabled la"><a class="page-link" href="#">Last</a></li>
	    <?php
	    }else{ // Jika Bukan page terakhir
	      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
	    ?>
	      <li class="page-item la"><a class="page-link" class="page-link" href="javascript:void(0);" onclick="caridtpass(<?php echo $link_next; ?>, false)">&raquo;</a></li>
	      <li class="page-item la"><a class="page-link" href="javascript:void(0);" onclick="caridtpass(<?php echo $jumlah_page; ?>, false)">Last</a></li>
	    <?php
	    }
	    ?>
	  </ul>
	<div style="font-size: 9px" align="center">f_passcari</div>  
	</nav>

<script>
	$(document).on('click', '.hapus_data', function(){
        var id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: "f_pashapus_act.php",
            data: {id:id},
            success: function() {
             caridtpass(1,true); 
             popnew_ok("Data terhapus");
            }
        });
    });
</script>

<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>