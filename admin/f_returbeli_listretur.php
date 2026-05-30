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
    padding: 5px;
  }
  table {
    border-spacing: 2px;
  }
</style>
<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	<table class="arrow-nav2 table-hover" style="font-size:9pt;width: 100%">
	    <tr align="middle" class="yz-theme-l4">
	      <th width="3%">NO.</th>	
	      <th width="10%">FAKTUR BELI</th>
	      <th width="15%">KD. BARANG</th>
	      <th>NAMA BARANG</th>
	      <th>HARGA</th>
	      <th>QTY</th>
	      <th width="5%">SATUAN</th>
	      <th width="5%">DISC %</th>
	      <th width="5%">TAX %</th>
	      <th width="15%">SUB TOTAL</th>
	      <th width="2%">OPSI</th>
	    </tr>
	    <?php
	    include "config.php";
	    session_start(); 
     	    
	    $connid=opendtcek();
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 5; // Jumlah data per halamannya
	    $limit_start = ($page - 1) * $limit;
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
   		    $kd_toko=$_SESSION['id_toko'];
	    	$param = mysqli_real_escape_string($connid, $keyword);
	    	//$param='%'.$params.'%';  	
	    	//echo $param;
          if ($param=="") {	 
          	  $sql1 = mysqli_query($connid, "SELECT retur_beli.no_urut,retur_beli.tgl_retur,retur_beli.kd_sup,retur_beli.no_fak,retur_beli.tgl_fak,retur_beli.kd_brg,retur_beli.qty_brg,retur_beli.kd_sat,retur_beli.hrg_beli,retur_beli.disc,retur_beli.tax,retur_beli.ketretur,mas_brg.nm_brg FROM retur_beli
          	  		LEFT JOIN mas_brg ON retur_beli.kd_brg=mas_brg.kd_brg
          	  		WHERE retur_beli.kd_toko=''
          	  	  ORDER BY retur_beli.no_urut ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connid, "SELECT COUNT(*) AS jumlah FROM retur_beli WHERE retur_beli.kd_toko=''  ORDER BY retur_beli.no_urut");
          }
          else {
	          $sql1 =mysqli_query($connid, "SELECT retur_beli.no_urut,retur_beli.tgl_retur,retur_beli.kd_sup,retur_beli.no_fak,retur_beli.tgl_fak,retur_beli.kd_brg,retur_beli.qty_brg,retur_beli.kd_sat,retur_beli.hrg_beli,retur_beli.disc,retur_beli.tax,retur_beli.ketretur,mas_brg.nm_brg FROM retur_beli
	          	LEFT JOIN mas_brg ON retur_beli.kd_brg=mas_brg.kd_brg
	          	WHERE retur_beli.no_retur = '$param' AND retur_beli.kd_toko='$kd_toko' 
	          	ORDER BY retur_beli.no_urut  ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connid, "SELECT COUNT(*) AS jumlah FROM retur_beli 
		      	LEFT JOIN mas_brg ON retur_beli.kd_brg=mas_brg.kd_brg 
		      	WHERE retur_beli.no_retur = '$param' AND retur_beli.kd_toko='$kd_toko'");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];
          $sql1 = mysqli_query($connid, "SELECT * from supplier  ORDER BY no_urut  ASC LIMIT $limit_start, $limit");
	      // Buat query untuk menghitung semua jumlah data
	      $sql2 = mysqli_query($connid, "SELECT COUNT(*) AS jumlah FROM supplier ORDER BY no_urut");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
	    $no=$limit_start;$hrg_beli=0;$subtot=0;$totpot=0;$tottax=0;$totretur=0;$totawal=0;
	    while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
	      $disc=$databrg['hrg_beli']-($databrg['hrg_beli']*($databrg['disc']/100));
	      $tax=($databrg['hrg_beli']*($databrg['tax']/100));
          $subtot=round(($disc+$tax)*$databrg['qty_brg'],2);
          $totpot=$totpot+($databrg['hrg_beli']*($databrg['disc']/100));
          $tottax=$tottax+$tax;$totretur=$totretur+$subtot;
          $totawal=$totawal+($databrg['hrg_beli']*$databrg['qty_brg']);
	    ?>
	      <tr>
	      
	        <td align="middle"><?php echo $no; ?></td>
	        <td align="middle"><?php echo $databrg['no_fak'] ?></td>
	        <td align="middle"><?php echo $databrg['kd_brg'] ?></td>
	        <td align="middle"><?php echo $databrg['nm_brg'] ?></td>
	        <td align="middle"><?php echo gantitides($databrg['hrg_beli']) ?></td>
	        <td align="middle"><?php echo $databrg['qty_brg'] ?></td>
	        <td align="middle"><?php echo ceknmkem2($databrg['kd_sat'], $connid); ?></td>
	        <td align="middle"><?php echo $databrg['disc'] ?></td>
            <td align="middle"><?php echo $databrg['tax'] ?></td>
            <td align="right"><?php echo gantitides($subtot) ?></td> 
	        <td>	
          	 <button id="<?='btnpilbrg'.$no?>" onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){hapusreturbrg(<?=$databrg['no_urut']?>);}" class="btn-danger fa fa-trash" type="button" style="cursor: pointer; border-style;font-size: 12pt" title="Hapus Data">
             </button>
	        </td>    
	       
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

	<!--Say pada info -->
	<script>
	  document.getElementById('totawal').value='<?=gantitides($totawal)?>';
	  document.getElementById('totpot').value='<?=gantitides($totpot)?>';
	  document.getElementById('tottax').value='<?=gantitides($tottax)?>';
	  document.getElementById('totretur').value='<?=gantitides($totretur)?>';
	  
	</script>

	<!-- <div class="w3-border yz-theme-l5"> -->
		<nav aria-label="Page navigation example" style="margin-top:1px;font-size: 8pt;">
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="caribrgretur(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="caribrgretur(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="caribrgretur(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="caribrgretur(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="caribrgretur(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
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