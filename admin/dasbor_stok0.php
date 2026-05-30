<?php 
ob_start();
session_start();
$kd_toko=$_SESSION['id_toko'];
include 'config.php';
$constok=opendtcek();
?>
<div style="overflow:auto;">
  <table class="table-bordered table-hover" style="font-size:9pt;background: transparent;width: 100%;min-height: 100px;border-collapse: collapse;white-space: nowrap;color:white">
    <thead>
  	  <tr align="middle" >
  		<th style="width: 1%;padding:5px">No.</th>	
  		<th style="width: 23%">KD. BARANG</th>	
        <th>NAMA BARANG</th>	
      	<th colspan="3">KONVERSI STOK BARANG & HARGA JUAL</th>        
  	  </tr>
    </thead>
    <?php
		$page = (isset($_POST['page']))? $_POST['page'] : 1;
		$limit = 15; // Jumlah data per halamannya
		$limit_start = ($page - 1) * $limit;
		//echo '$limit_start='.$limit_start;

		if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	      $sql =mysqli_query($constok, "SELECT SUM(beli_brg.stok_jual) AS stok_juals, beli_brg.kd_brg,beli_brg.kd_toko,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.brg_msk,mas_brg.brg_klr FROM beli_brg
	    	LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
	    	WHERE beli_brg.kd_toko='$kd_toko'
	    	GROUP BY beli_brg.kd_brg
	    	HAVING stok_juals=0
	    	ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
		  $sql2=mysqli_query($constok,"SELECT count(*) AS jumlah FROM (SELECT COUNT(*) FROM beli_brg 
		  	WHERE beli_brg.kd_toko='$kd_toko' GROUP BY kd_brg,kd_toko HAVING sum(stok_jual)=0) jumlah"); 
			$get_jumlah = mysqli_fetch_array($sql2);  		    
		}

		$no=$limit_start;$tot=0;$no_fak='';$tgl_fak='';$disc1=0;$disc2=0;$jmlsub=0;
		$kd_sup='';$nm_sup='';$brg_msk_hi=0.00;$brg_klr_hi=0;$cekada=0;$satkecil='';
		$stok1=0;$stok2=0;$stok3=0;
		while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql

		  // if ($data['stok_juals']==0){
			$no++;
	        $brg_msk_hi=$data['stok_juals'];
	        //echo '$brg_msk='.$brg_msk_hi.'<br>';
	        if ($data['jum_kem1']>0) {
	          $stok1=gantitides(round($brg_msk_hi/$data['jum_kem1'],2)).' '.$data['nm_kem1'];	
	        }else{
	          $stok1='NONE';
	        }
	        if ($data['jum_kem2']>0) {
	          $stok2=gantitides(round($brg_msk_hi/$data['jum_kem2'],2)).' '.$data['nm_kem2'];  
	        }else{
	          $stok2='NONE';
	        }
	        if ($data['jum_kem3']>0) {
	          $stok3=gantitides(round($brg_msk_hi/$data['jum_kem3'],2)).' '.$data['nm_kem3'];  	
	        }else{
	          $stok3='NONE';
	        }
	        
	        ?> 
            
		  <tr>
			<td align="right" style="padding: 3px"><?php echo $no ?></td>
			<td align="left" style="border-right: none"><?php echo $data['kd_brg']; ?></td>
			<td align="left" style="border-right: none;border-left: none"><?php echo $data['nm_brg']; ?></td>
			<td align="right" ><?php echo $stok1.' -@ '.gantitides($data['hrg_jum1']);?>&nbsp;</td>
			<td align="right" ><?php echo $stok2.' -@ '.gantitides($data['hrg_jum2']); ?>&nbsp;</td>
			<td align="right" ><?php echo $stok3.' -@ '.gantitides($data['hrg_jum3']); ?>&nbsp;</td>
		  </tr>

		<?php
		  // }
		}
		?>
	  <tr align="right">
		<td colspan="10" class="w3-center" style="padding: 5px"><b>Total Item Barang : <?=$no?></b></td>
	  </tr>
  </table>
  <nav  aria-label="Page navigation example" style="font-size: 9pt">
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
          <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="caristok0(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="caristok0(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="caristok0(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="caristok0(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="caristok0(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
</div>    
<?php
  mysqli_close($constok);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>