<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	  <table class="arrow-nav2 table-bordered table-striped table-hover hrf_res" style="width: 100%">

	    <tr align="middle" class="yz-theme-l3">
	      <th>BAGIAN PENJUALAN</th>
	      <th style="width: 1%;">OPSI</th>
	    </tr>
	    <?php
	    session_start(); 
        include "config.php";
	    $connect=opendtcek();
        $page = (isset($_POST['page']))? $_POST['page'] : 1;

	    $limit = 8;

	    $limit_start = ($page - 1) * $limit;
	    
	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
   		    $id_toko=$_SESSION['id_toko'];
	    	$params = mysqli_real_escape_string($connect, $keyword);
	    	$param='%'.$params.'%';  	
	    	// echo $params;
          if ($params=="") {	 
          	  $sql1 = mysqli_query($connect, "SELECT * FROM bag_brg 
          	  	  ORDER BY nm_bag ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM bag_brg  ORDER BY nm_bag");
          }
          else {
	          $sql1 =mysqli_query($connect, "SELECT *
	            FROM bag_brg 
	          	WHERE nm_bag LIKE '$param'  ORDER BY nm_bag ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM bag_brg WHERE nm_bag LIKE '$param' ");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ 
	      $sql1 = mysqli_query($connect, "SELECT * FROM bag_brg  
          	  ORDER BY nm_bag ASC LIMIT $limit_start, $limit");
	      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM bag_brg  ORDER BY nm_bag");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
        $no=0;	    
        $x1="";$kd_sat4="";$hrg_jum4=0;$lim1=0;
	    while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
	    ?>

	      <tr>
	        <td align="left" style="cursor: pointer" onclick="document.getElementById('<?='btnnmbag'.$no?>').click();"><input class="w3-input" type="text" tabindex="7" style="border: none;background-color: transparent;cursor: pointer" readonly value="<?=$databrg['nm_bag']; ?>" onkeydown="if(event.keyCode==13){document.getElementById('<?='btnnmbag'.$no?>').click()}"></td>
	        <td>
	          	<button id="<?='btnnmbag'.$no?>" onclick="
	               document.getElementById('id_bag').value='<?=mysqli_escape_string($connect,$databrg['no_urut']) ?>';
	          	   document.getElementById('nm_bag').value='<?=mysqli_escape_string($connect,$databrg['nm_bag']) ?>';
	               document.getElementById('boxnmbag').style.display='none';" class="btn btn-sm btn-primary fa fa-edit" type="button" style="cursor: pointer;font-size: 12pt" title="Edit Data">
	            </button>
	           
	        </td>    
	      </tr>
	    <?php
	    }
	    ?>
	  </table>
	</div>
	
	<div class="w3-border yz-theme-l5">
		<nav  aria-label="Page navigation example" style="margin-top:15px;">
		  <ul class="pagination justify-content-center hrf_res">
		    <!-- LINK FIRST AND PREV -->
		    <?php
		    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
		      <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:17px;padding-right:17px"><i class="fa fa-caret-left"></i></a></li>
		    <?php
		    }else{ // Jika page bukan page ke 1
		      $link_prev = ($page > 1)? $page - 1 : 1;
		    ?>
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinmbag(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer;padding-left:17px;padding-right:17px" href="javascript:void(0);" onclick="carinmbag(<?php echo $link_prev; ?>, false)"><i class="fa fa-caret-left"></i></a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinmbag(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NEXT AND LAST -->
		    <?php
		    if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir
		    ?>
		      <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:17px;padding-right:17px"><i class="fa fa-caret-right"></i></a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
		    <?php
		    }else{ // Jika Bukan page terakhir
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinmbag(<?php echo $link_next; ?>, false)" style="cursor: pointer;padding-left:17px;padding-right:17px"><i class="fa fa-caret-right"></i></a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinmbag(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
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
	$html = ob_get_contents(); 
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>