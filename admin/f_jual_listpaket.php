<?php
	ob_start();
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	  <table class="arrow-nav2 table-hover" style="font-size:9pt;width: 100%">
	    <tr align="middle" class="yz-theme-l2">
	      <th style="width: 5%">NO.</th> 	
	      <th>NAMA BARANG</th>
	      <th style="width: 3%">OPSI</th>
	    </tr>
	    <?php
	    session_start(); 
        include "config.php";
	    $connect=opendtcek();
	    $kd_toko=$_SESSION['id_toko'];
	    	
        $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 8; // Jumlah data per halamannya
	    $limit_start = ($page - 1) * $limit;
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
   		 //    $params = mysqli_real_escape_string($connect, $keyword);
	    	// $param='%'.$params.'%';  	
	    	// echo $params;
          	  $sql1 = mysqli_query($connect, "SELECT * FROM paket_mas
          	  	  WHERE kd_toko='$kd_toko'
          	  	  ORDER BY nm_paket ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM paket_mas  WHERE kd_toko='$kd_toko' ORDER BY nm_paket");
          $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];

          $sql1 = mysqli_query($connect, "SELECT * FROM paket_mas
          	  WHERE kd_toko='$kd_toko' ORDER BY nm_paket ASC LIMIT $limit_start, $limit");
	      // Buat query untuk menghitung semua jumlah data
	      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM paket_mas WHERE kd_toko='$kd_toko'  ORDER BY nm_paket");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
        $no=$limit_start;	    
        $x1="";$kd_sat4="";$hrg_jum4=0;$lim1=0;
	    while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
	    
	    ?>

	      <tr>
	      	<td align="right"><?= $no.'. '?></td>
	      	<td align="left" style="cursor: pointer" onclick="document.getElementById('<?='btnnmbrg'.$no?>').click();">
	      		<input class="w3-input" type="text" tabindex="6" style="border: none;background-color: transparent;cursor: pointer" readonly 
	      		  value="<?=$databrg['nm_paket']; ?>" 
	      		  onkeydown="if(event.keyCode==13){document.getElementById('<?='btnnmbrg'.$no?>').click()}">
	      	</td>
	        <td>
	        	<?php $param=mysqli_real_escape_string($connect,$databrg['no_urut']) ?>
	          	<button id="<?='btnnmbrg'.$no?>" onclick="
	               extrackpaket('<?=$param?>');document.getElementById('form-paket').style.display='none';
	               " class="yz-theme-d2 fa fa-edit" type="button" style="cursor: pointer;font-size: 12pt" title="Edit Data">
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinmbrg(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carinmbrg(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinmbrg(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinmbrg(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinmbrg(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>
<script>

$(document).ready(function(){
	$("#btn-updown").click(function(){
	  $("#inputdata").slideToggle("");
	});
	$("#btn-geser").click(function(){
	  $("#inputdata").slideDown("");
	});
}); 

$('table.arrow-nav2').keydown(function(e){
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
  <!--  -->		

<?php
    mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>