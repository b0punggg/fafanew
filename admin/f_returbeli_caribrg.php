<?php
	$keyword1 = $_POST['keyword1']; // Ambil data keyword yang dikirim dengan AJAX	
	$keyword2 = $_POST['keyword2'];
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
    padding: 5px;
  }
  table {
    border-spacing: 2px;
  }
</style>
<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	<table class="arrow-nav2 table-hover" style="font-size:8pt;width: 100%">
	    <tr align="middle" class="yz-theme-l4">
	      <th width="18%">No. FAKTUR</th>
	      <th width="20%">SUPPLIER</th>
	      <!-- <th width="20%">KD. BARANG</th> -->
	      <th>NAMA BARANG</th>
	      <th width="5%">SAT</th>
	      <th width="2%">OPSI</th>
	    </tr>
	    <?php
	    include "config.php";
	    session_start(); 
     	    
	    $connid=opendtcek();
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;

	    $limit = 10; // Jumlah data per halamannya

	    $limit_start = ($page - 1) * $limit;
	    $sql1 = false;
	    $sql2 = false;
	    $get_jumlah = array('jumlah' => 0);
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
   		    $kd_toko=$_SESSION['id_toko'];
	    	$param2 = mysqli_real_escape_string($connid, $keyword2);
	    	$param1='%'.mysqli_real_escape_string($connid, $keyword1).'%';  	
	    	// echo $params;
          if ($param1=="%%" && $param2=='') {	 
          	  $sql1 = mysqli_query($connid, "SELECT beli_brg.no_urut,beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.kd_sup,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.kd_brg,beli_brg.ket,mas_brg.nm_brg,supplier.nm_sup FROM beli_brg 
          	  		LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
          	  		LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
          	  		WHERE beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
          	  	  ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connid, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0 ORDER BY beli_brg.no_urut");
          }
          if ($param1 <> "%%" && $param2 == ''){
          	$sql1 =mysqli_query($connid, "SELECT beli_brg.no_urut,beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.kd_sup,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.kd_brg,beli_brg.ket,mas_brg.nm_brg,supplier.nm_sup FROM beli_brg 
          	  		LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
          	  		LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
	          	WHERE mas_brg.nm_brg LIKE '$param1' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
	          	ORDER BY beli_brg.no_urut  ASC LIMIT $limit_start, $limit");
		    $sql2 = mysqli_query($connid, "SELECT COUNT(*) AS jumlah FROM beli_brg LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg WHERE mas_brg.nm_brg LIKE '$param1' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.kd_sup='$param2' AND beli_brg.stok_jual>0");
          }	
          if ($param1 == "%%" && $param2 <> ''){
          	$sql1 =mysqli_query($connid, "SELECT beli_brg.no_urut,beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.kd_sup,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.kd_brg,beli_brg.ket,mas_brg.nm_brg,supplier.nm_sup FROM beli_brg 
          	  		LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
          	  		LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
	          	WHERE beli_brg.kd_sup LIKE '$param2' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
	          	ORDER BY beli_brg.no_urut  ASC LIMIT $limit_start, $limit");
		    $sql2 = mysqli_query($connid, "SELECT COUNT(*) AS jumlah FROM beli_brg LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg WHERE beli_brg.kd_sup LIKE '$param2' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.kd_sup='$param2' AND beli_brg.stok_jual>0");
          }	
          if ($param1<>"%%" && $param2<>''){
	          $sql1 =mysqli_query($connid, "SELECT beli_brg.no_urut,beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.kd_sup,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.kd_brg,beli_brg.ket,mas_brg.nm_brg,supplier.nm_sup FROM beli_brg 
          	  		LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
          	  		LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
	          	WHERE mas_brg.nm_brg LIKE '$param1' AND beli_brg.kd_sup='$param2' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
	          	ORDER BY beli_brg.no_urut  ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connid, "SELECT COUNT(*) AS jumlah FROM beli_brg LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg WHERE mas_brg.nm_brg LIKE '$param1' AND beli_brg.kd_sup='$param2' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.kd_sup='$param2' AND beli_brg.stok_jual>0");	
          }	
	      if($sql2){
	      	$get_jumlah = mysqli_fetch_array($sql2);
	      }

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];
          $sql1 = mysqli_query($connid, "SELECT * from supplier  ORDER BY no_urut  ASC LIMIT $limit_start, $limit");
	      // Buat query untuk menghitung semua jumlah data
	      $sql2 = mysqli_query($connid, "SELECT COUNT(*) AS jumlah FROM supplier ORDER BY no_urut");
	      if($sql2){
	      	$get_jumlah = mysqli_fetch_array($sql2);
	      }
	    }
	    $no=0;$hrg_beli=0;
	    while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
	      $pembagi = konjumbrg($databrg['kd_sat'],$databrg['kd_brg']);
	      if(!is_numeric($pembagi) || floatval($pembagi) == 0){
	        $pembagi = 1;
	      }
	      $hrg_beli=$databrg['hrg_beli']/floatval($pembagi);
	      $ket_beli = isset($databrg['ket']) ? strval($databrg['ket']) : '';
          
	    ?>
	      <tr>
	      	<?php if ($ket_beli === '' || stripos($ket_beli, 'BELIAN BARANG') !== false) { ?>
	        <td><input class="w3-input" type="text" onkeydown="if(event.keyCode==13){document.getElementById('<?='btnpilbrg'.$no?>').click();}" onclick="document.getElementById('<?='btnpilbrg'.$no?>').click();" value="<?php echo $databrg['no_fak']; ?>" readonly tabindex="4" style="border: none;background-color: transparent;cursor: pointer"></td>

            <td align="middle" onclick="document.getElementById('<?='btnpilbrg'.$no?>').click()" style="cursor: pointer;color: blue"><?php echo $databrg['nm_sup']; ?></td>

	        <!-- <td align="middle" onclick="document.getElementById('<?='btnpilbrg'.$no?>').click()" style="cursor: pointer;"><?php echo $databrg['kd_brg']; ?></td> -->
	        
	        <td align="middle" onclick="document.getElementById('<?='btnpilbrg'.$no?>').click()" style="cursor: pointer;color: red"><?php echo $databrg['nm_brg']; ?></td>
	        
	        <td align="middle" onclick="document.getElementById('<?='btnpilbrg'.$no?>').click()" style="cursor: pointer;"><?php echo ceknmkem2($databrg['kd_sat'], $connid); ?></td>

	        <td>	
          	 <button id="<?='btnpilbrg'.$no?>" onclick=" 	                 
          	   document.getElementById('kd_brg').value='<?=$databrg['kd_brg']?>';
          	   document.getElementById('kd_sup').value='<?=$databrg['kd_sup']?>';
          	   document.getElementById('no_fak').value='<?=$databrg['no_fak']?>';
          	   document.getElementById('tgl_fak').value='<?=$databrg['tgl_fak']?>';
          	   document.getElementById('nm_brg').value='<?=$databrg['nm_brg']?>';
          	   document.getElementById('hrg_beli').value='<?=$hrg_beli?>';
          	   document.getElementById('no_item').value='<?=$databrg['no_urut']?>';
          	   document.getElementById('boxkdbrgretur').style.display='none';
          	   document.getElementById('nm_sat').focus();
               " class="btn-primary fa fa-edit" type="button" style="cursor: pointer; font-size: 12pt" title="Edit Data">
             </button>
	        </td>    
	       
	       <?php }?>
	      </tr>
	    <?php
	    }
	    if($no==0){
	    ?><script>
	       // document.getElementById('kd_brg').value='';
	      </script>
	    <?php	
	    }
	    ?>
	  </table>
	</div>
	
	<!-- <div class="w3-border yz-theme-l5"> -->
		<nav aria-label="Page navigation example" style="margin-top:1px;font-size: 8pt;">
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="cariidbrgretur(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="cariidbrgretur(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="cariidbrgretur(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="cariidbrgretur(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="cariidbrgretur(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	<!-- </div> -->

<script>
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

<?php
    mysqli_close($connid);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>