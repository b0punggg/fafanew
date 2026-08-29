<?php
	$keyword = $_POST['keyword'];
	ob_start();
?>
<style>
  th {
  position: sticky;
  top: 0px; 
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }
  table, td {
    border: 1px solid grey;
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
<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;border-color: white;height: 343px">
	<table id="table" class="arrow-nav table-hover" style="font-size:9pt;position: sticky;width: 100%">
	    <tr align="middle" class="yz-theme-l3" >
	      <th width="5%">No.</th>
	      <th width="45%">NAMA BRAND</th>
	      <th colspan="2" width="1%">OPSI</th>
	    </tr>
	    <?php
	    include "config.php";
	    session_start();
	    $connect=opendtcek();     
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 12;
	    $limit_start = ($page - 1) * $limit;

	    if(isset($_POST['search']) && $_POST['search'] == true){
	    	$params = mysqli_real_escape_string($connect, $keyword);
	    	$param='%'.$params.'%';  	
          if ($params=="") {	 
          	  $sql = mysqli_query($connect, "SELECT brand_report.no_urut,brand_report.nm_brand FROM brand_report
          	  	     ORDER BY brand_report.nm_brand ASC LIMIT $limit_start, $limit");

	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM brand_report ORDER BY nm_brand");
          }
          else {
	          $sql =mysqli_query($connect, "SELECT brand_report.no_urut,brand_report.nm_brand FROM brand_report
	          	  WHERE brand_report.nm_brand LIKE '$param'  ORDER BY brand_report.nm_brand ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM brand_report WHERE nm_brand LIKE '$param' ");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{
          $sql = mysqli_query($connect, "SELECT * from brand_report ORDER BY brand_report.nm_brand ASC LIMIT $limit_start, $limit");
	      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM brand_report ORDER BY nm_brand");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
	    $no=$limit_start;
	    while($data = mysqli_fetch_array($sql)){
	      $no++;	
	      ?>
	      <tr>
	        <td ><input class="w3-input" type="text" value="<?php echo $no.'.' ?>" readonly style="border: none;background-color: transparent;text-align: right"></td>
	        <td align="left"><input class="w3-input" type="text" value="<?php echo $data['nm_brand']; ?>" readonly style="border: none;background-color: transparent;"></td>
	        <td>
	          	<button onclick="
	          	   document.getElementById('nm_brand').value='<?=mysqli_escape_string($connect,$data['nm_brand']) ?>';
	          	   document.getElementById('no_urut').value='<?=mysqli_escape_string($connect,$data['no_urut']) ?>';
	        	   document.getElementById('keyktbrand').value='';
	        	   document.getElementById('btn-ktbrand').click()" class="btn-primary fa fa-edit" style="cursor: pointer; font-size: 12pt" title="Edit Data">
	            </button>	    
	        </td>
	        <td>  
	           <?php $param=mysqli_escape_string($connect,$data['no_urut']); ?>
	           <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){ location.href='m_brand_report_hapus_act.php?param=<?php echo $param ?>'}" class="btn-danger fa fa-trash" style="cursor: pointer;font-size: 12pt" title="Hapus Data"></button>
	           
	        </td>    
	      </tr>
	      <?php
	    }
	    ?>
	</table>
</div>
    <script>
	$('table.arrow-nav').keydown(function(e){
	    var $table = $(this);
	    var $active = $('input:focus,select:focus',$table);
	    var $next = null;
	    var focusableQuery = 'input:visible,select:visible,textarea:visible';
	    var position = parseInt( $active.closest('td').index()) + 1;
	    switch(e.keyCode){
	        case 37:
	            $next = $active.parent('td').prev().find(focusableQuery);   
	            break;
	        case 38:
	            $next = $active
	                .closest('tr')
	                .prev()                
	                .find('td:nth-child(' + position + ')')
	                .find(focusableQuery)
	            ;
	            break;
	        case 39:
	            $next = $active.closest('td').next().find(focusableQuery);            
	            break;
	        case 40:
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
	<div class="w3-border">
		<nav  aria-label="Page navigation example" style="margin-top:5px;font-size: 9pt">
		  <ul class="pagination justify-content-start">
		    <?php
		    if($page == 1){
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)">First</a></li>
		      <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)">&laquo;</a></li>
		    <?php
		    }else{
		      $link_prev = ($page > 1)? $page - 1 : 1;
		    ?>
		      <li><a class="page-link yz-theme-d1" href="javascript:void(0);" onclick="caribrand(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" href="javascript:void(0);" onclick="caribrand(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
		    <?php
		    }
		    ?>
		    <?php
		    $jumlah_page = ceil($get_jumlah['jumlah'] / $limit);
		    $jumlah_number = 1;
		    $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1;
		    $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page;
		    
		    for($i = $start_number; $i <= $end_number; $i++){
		      $link_active = ($page == $i)? ' class="active"' : '';
		    ?>
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" onclick="caribrand(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    <?php
		    if($page == $jumlah_page || $get_jumlah['jumlah']==0){
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)">&raquo;</a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)">Last</a></li>
		    <?php
		    }else{
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item "><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="caribrand(<?php echo $link_next; ?>, false)">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="caribrand(<?php echo $jumlah_page; ?>, false)">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>
		

<?php
    mysqli_close($connect);
	$html = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>