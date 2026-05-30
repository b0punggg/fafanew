<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
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
<?php
include "config.php";
session_start();
$kd_toko=$_SESSION['id_toko'];
$connect=opendtcek();
$page = (isset($_POST['page']))? $_POST['page'] : 1;
$limit = 10; // Jumlah data per halamannya
$limit_start = ($page - 1) * $limit;
$param = mysqli_real_escape_string($connect, $keyword);
// echo 'Kode Barang '.$param	;
// echo '$limit_start='.$limit_start;
$cnm=mysqli_query($connect,"SELECT nm_brg FROM mas_brg WHERE kd_brg='$param'");
$nm='';
if(mysqli_num_rows($cnm)>0){
   $dnm=mysqli_fetch_assoc($cnm);
   $nm=$dnm['nm_brg'];
}
echo $nm;
unset($cnm,$dnm);

if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	$sql =mysqli_query($connect, "SELECT * FROM mutasi_adj
      	  WHERE kd_brg = '$param' AND kd_toko='$kd_toko'  ORDER BY no_urut ASC LIMIT $limit_start, $limit");
    $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mutasi_adj WHERE kd_brg = '$param' AND kd_toko='$kd_toko' ");	
    $get_jumlah = mysqli_fetch_array($sql2);
}else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
  $sql =mysqli_query($connect, "SELECT * FROM mutasi_adj
      	  WHERE kd_brg = '$param' AND kd_toko='$kd_toko'  ORDER BY no_urut ASC LIMIT $limit_start, $limit");
  $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mutasi_adj WHERE kd_brg = '$param' AND kd_toko='$kd_toko' ");	
  $get_jumlah = mysqli_fetch_array($sql2);
}
?>
<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;">
	  <table id="table" class="table-hover arrow-nav" style="font-size:9pt; position: sticky;width: 100%;border-collapse: collapse;white-space: nowrap;">
	    <tr align="middle" class="yz-theme-l3">
	      <th width="3%">NO.</th>
	      <th width="12%">TANGGAL</th>
	      <th>KETERANGAN</th>
		  <th width="3%">OPSI</th>
	    </tr>
	    <?php 
	    $no=$limit_start;
	    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          $no++;		
		  $catpel='';
			$br=substr_count($data['ket'],"<br />");
			if($br>0){
			  $exe=explode('<br />',$data['ket']);	
				for ($c = 0; $c <= $br; $c++) {
					$catpel=$catpel.trim(ucwords(strtolower($exe[$c]))).'\n';	
				}
			}else{
			    $catpel=ucwords(strtolower($data['ket']));
			} 
			 
  	      ?>
	      <tr>
	        <td align="right" style="border-right:none;padding:5px"><?php echo $no.'.' ?>&nbsp;</td>
	        <td align="left" style="border-left:none;border-right:none"><?php echo gantitgl($data['tgl_input']); ?></td>
	        <td align="left" style="border-left:none;border-right:none"><?=ucwords(strtolower($data['ket']));?></td>
			<td align="center"style=" border-left:none;"><button class="btn-primary fa fa-edit" style="cursor: pointer;font-size: 9pt" title="Edit Data" onclick="
			document.getElementById('noedit').value='<?=$data['no_urut']?>';
			document.getElementById('ket_tgl').innerHTML='Tanggal Opname : '+'<?=gantitgl($data['tgl_input'])?>';
			document.getElementById('ket_ed').value='<?=trim($catpel)?>';
			document.getElementById('nm_ed').innerHTML='Nama Barang : '+'<?=$nm?>';
			document.getElementById('feditnote').style.display='block';
			"></button></td>
	      </tr>
	      <?php
	    }
	      ?>
	  </table>
	</div>

	<div class="w3-border">
		<nav  aria-label="Page navigation example" style="margin-top:5px;font-size: 9pt">
		  <ul class="pagination justify-content-start">
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carimutasinote(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carimutasinote(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carimutasinote(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carimutasinote(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carimutasinote(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>
<!--  -->
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
<!--  -->				

<?php
    mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>