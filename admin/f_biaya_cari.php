<?php
	ob_start();
	include 'config.php';
	session_start();

    $connect=opendtcek();  
	$kd_toko=$_SESSION['id_toko'];
?>
<div style="overflow:auto;border-style: ridge;">
	<div  class="w3-container" id="filter" style="font-size: 10pt;"></div>
	<table class="table-bordered table-hover" style="font-size:9pt;width: 100%;min-height: 100px;border-collapse: collapse;white-space: nowrap;">
    <thead>
  	  <tr align="middle" class="yz-theme-l1">
  		<th style="width: 1%" class="p-2">No.</th>
  		
  		<th style="width: 15%">TOKO &nbsp;<button type="button" id="btn-kdtoko" style="padding:3px" class="btn fa fa-search w3-hover-shadow"></button>
    	  	<div id="boxkdtoko" style="display:none;position: absolute;z-index: 1;" >
		  		<div class="input-group" style="width: 250px;margin-top: 26px">
			        <input type="text" class="yz-theme-l4 w3-card-4" 
			           id="in_kdtoko" name="in_kdtoko" 
			           style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kode toko" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari1').value='toko.nm_toko like '+this.value;caribiaya(1,true);}"
			        autofocus>
			       <div class="input-group-append w3-card-4">
			       	<button class="btn btn-primary" onclick="
			          document.getElementById('kd_cari1').value='';caribiaya(1,true);
			          " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
			        </button>
			       </div>		
			    </div>	
            </div>
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-kdtoko").click(function(){
                $("#boxtglbiaya").slideUp("fast");
                $("#boxketbiaya").slideUp("fast");
                $("#boxkdtoko").slideToggle("fast");
                $("#in_kdtoko").focus();
              });
            });
          </script>
  		<th style="width: 8%">TANGGAL &nbsp;<button type="button" id="btn-tglbiaya" class="btn fa fa-search w3-hover-shadow" style="padding:3px"></button> 
    	  	<div id="boxtglbiaya" style="display:none;position: absolute;z-index: 1;">
		  		<div class="input-group " style="width: 250px;margin-top: 26px">
			       <input type="date" class="yz-theme-l4 w3-card-4" id="in_tglbiaya" name="in_tglbiaya" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Tanggal" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari2').value='biaya_ops.tgl_biaya like '+this.value;caribiaya(1,true);}">
			       <div class="input-group-append w3-card-4">
			       	 <button class="btn btn-primary" onclick="
			           document.getElementById('kd_cari2').value='';caribiaya(1,true);
			           " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
			       </div>		
			    </div>	
			</div>       
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-tglbiaya").click(function(){
              $("#boxketbiaya").slideUp("fast");
              $("#boxkdtoko").slideUp("fast");
              $("#boxtglbiaya").slideToggle("fast");
              $("#in_tglbiaya").focus();
              });
            });
          </script>  
     
      <th style="width: 20%">JENIS BIAYA </th>    
      <th>KETERANGAN &nbsp;<button type="button" id="btn-ketbiaya" class="btn fa fa-search w3-hover-shadow" style="padding:3px"></button>
      	<div id="boxketbiaya" style="display:none;position: absolute;z-index: 1;">
	  		<div class="input-group " style="width: 250px;margin-top: 26px">
		       <input type="text" class="yz-theme-l4 w3-card-4" id="in_ketbiaya" name="in_ketbiaya" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Keterangan" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari3').value='biaya_ops.ket_biaya like '+this.value;caribiaya(1,true);}">
		       <div class="input-group-append w3-card-4">
		       	 <button class="btn btn-primary" onclick="
		           document.getElementById('kd_cari3').value='';caribiaya(1,true);
		           " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
		       </div>		
		    </div>	
		</div>
	   </th> 	
	   <th style="width: 12%">NOMINAL </th>    
		 <script>
            $(document).ready(function(){
              $("#btn-ketbiaya").click(function(){
              $("#boxtglbiaya").slideUp("fast");
              $("#boxkdtoko").slideUp("fast");
              $("#boxketbiaya").slideToggle("fast");
              $("#in_ketbiaya").focus();
              });
            });
          </script>  
      <th style="width: 4%" colspan="2">NOTE</th>		
  	</tr>
  </thead>

   	<?php
		$kd_toko=$_SESSION['id_toko'];
		$page = (isset($_POST['page']))? $_POST['page'] : 1;
		$limit = 10; // Jumlah data per halamannya
		$limit_start = ($page - 1) * $limit;
		// echo '$limit_start='.$limit_start;

		if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
			$params1='';$params2='';$params3='';
			$kunci2="";$kunci3="";$kunci4="";
			if (!empty(mysqli_real_escape_string($connect,$_POST['keyword1']))){
			  $xada1=strpos(mysqli_real_escape_string($connect, $_POST['keyword1']),"like");
			  if ($xada1 <> false){
                $pecah1=explode('like', mysqli_real_escape_string($connect, $_POST['keyword1']));
			    $kunci1=$pecah1[0];
			    $kunci2=strtoupper($pecah1[1]);
			    $params1=$kunci1." like '%".trim($kunci2)."%'";
			  }	
			  unset($xada1,$pecah1,$kunci1);
			}
			if (!empty(mysqli_real_escape_string($connect, $_POST['keyword2']))){
			  $xada1=strpos(mysqli_real_escape_string($connect,$_POST['keyword2']),"like");
			  if ($xada1 <> false){
                $pecah1=explode('like', mysqli_real_escape_string($connect, $_POST['keyword2']));
			    $kunci1=$pecah1[0];
			    $kunci3=$pecah1[1];
			    $params2=$kunci1." like '%".trim($kunci3)."%'";
			  }	
			  unset($xada1,$pecah1,$kunci1);
			}
			if (!empty(mysqli_real_escape_string($connect, $_POST['keyword3']))){
			  $xada1=strpos(mysqli_real_escape_string($connect, $_POST['keyword3']),"like");
			  if ($xada1 <> false){
                $pecah1=explode('like', mysqli_real_escape_string($connect, $_POST['keyword3']));
			    $kunci1=$pecah1[0];
			    $kunci4=strtoupper($pecah1[1]);
			    $params3=$kunci1." like '%".trim($kunci4)."%'";
			  }	
			  unset($xada1,$pecah1,$kunci1);
			}			
             
            if (!empty($params1) && !empty($params2) && !empty($params3)){
               $param=$params1.' AND '.$params2.' AND '.$params3;	
            } elseif (!empty($params1) && !empty($params2) && empty($params3)) {
               $param=$params1.' AND '.$params2;	
            } elseif (!empty($params1) && empty($params2) && empty($params3)) {
               $param=$params1;	
            } elseif (!empty($params1) && empty($params2) && !empty($params3)) {
               $param=$params1 .' AND '.$params3;	   
            } elseif (empty($params1) && empty($params2) && empty($params3)) {
               $param='';	
            } elseif (empty($params1) && !empty($params2) && !empty($params3)) {
               $param=$params2.' AND '.$params3;
            } elseif (empty($params1) && empty($params2) && !empty($params3) ) {
               $param=$params3;
            } elseif (empty($params1) && !empty($params2) && empty($params3)) {
               $param=$params2;
            } 
              
           
			if ($param=="") 
			{	 
			    $sql =mysqli_query($connect, "SELECT toko.nm_toko,biaya_jns.jns_biaya, biaya_ops.* FROM biaya_ops 
			    	LEFT JOIN toko ON biaya_ops.kd_toko=toko.kd_toko
			    	LEFT JOIN biaya_jns ON biaya_ops.id_jenis=biaya_jns.id
					WHERE biaya_ops.kd_toko='$kd_toko'
					ORDER BY biaya_ops.tgl_biaya ASC
			    	LIMIT $limit_start, $limit");
				$sql2=mysqli_query($connect,"SELECT count(*) AS jumlah FROM biaya_ops WHERE kd_toko='$kd_toko'"); 

			}
			else
			{
				$sql =mysqli_query($connect, "SELECT toko.nm_toko,biaya_jns.jns_biaya, biaya_ops.* FROM biaya_ops
					LEFT JOIN toko ON biaya_ops.kd_toko=toko.kd_toko
			    	LEFT JOIN biaya_jns ON biaya_ops.id_jenis=biaya_jns.id
			    	WHERE $param
			    	ORDER BY biaya_ops.tgl_biaya ASC LIMIT $limit_start, $limit");
				$sql2=mysqli_query($connect,"SELECT count(*) AS jumlah FROM biaya_ops LEFT JOIN toko ON biaya_ops.kd_toko=toko.kd_toko
			    	LEFT JOIN biaya_jns ON biaya_ops.id_jenis=biaya_jns.id WHERE $param"); 
			}	
            $get_jumlah = mysqli_fetch_array($sql2);  		    
		}
		$no=$limit_start;$totnom=0;
		?><script type="text/javascript">
			document.getElementById('filter').innerHTML="<i class='fa fa-desktop fa-lg' style='color:blue;font-weight:bold'>&nbsp;</i><?='FILTER PENCARIAN >> TOKO : '.$kunci2.' > TANGGAL : '.$kunci3.' > KETERANGAN : '.$kunci4 ?>";		
		</script><?php
	
		while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
			$no++;
			$totnom=$totnom+$data['nominal'];
			if ($data['nm_toko']==''){
                $nmtoko='GLOBAL';
			} else {
				$nmtoko=$data['nm_toko'];
			}
			$ket_biaya='';
			$br=substr_count($data['ket_biaya'],"<br />");
			if($br>0){
			  $exe=explode('<br />',$data['ket_biaya']);	
			  for ($c = 0; $c <= $br; $c++) {
				$ket_biaya=$ket_biaya.trim($exe[$c]).'\n';	
			  }
			}else{
			  $ket_biaya=$data['ket_biaya'];
			}
		    ?>    
            
		  <tr>
			<td align="right"><?php echo $no ?></td>
			<td align="left" style="border-right: none"><?php echo $nmtoko; ?></td>
			<td align="center" style="border-right: none;border-left: none"><?php echo gantitgl($data['tgl_biaya']); ?></td>
			<td align="center" style="border-right: none;border-left: none">&nbsp;<?php echo $data['jns_biaya']; ?></td>
			<td align="left" style="border-right: none;border-left: none">&nbsp;<?php echo $data['ket_biaya']; ?></td>
			<td align="right" style="border-right: none;border-left: none"><?php echo gantitides($data['nominal']); ?>&nbsp;</td>
			<td>
			   <?php $idcari=$data['id'] ?>
	           <button class="btn-primary fa fa-edit" style="cursor: pointer;font-size: 12pt" title="edit Data" onclick="
	              document.getElementById('tgl_biaya').value='<?=$data['tgl_biaya']?>';
	              document.getElementById('ket_biaya').value='<?php echo $ket_biaya; ?>';
	              document.getElementById('nominal').value='<?=gantitides($data['nominal'])?>';
	              document.getElementById('kd_tokolist').value='<?=$data['kd_toko']?>';
	              document.getElementById('id_rec').value='<?=$data['id']?>';
	              document.getElementById('nm_jenislist').value='<?=$data['jns_biaya']?>';
                  document.getElementById('id_jenis').value='<?=$data['id_jenis']?>';
	              document.getElementById('nm_tokolist').value='<?=$nmtoko?>';
	           "></button>
			</td>
			<td>
			   <?php $idcari=$data['id'] ?>
	           <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){delbiaya('<?=$idcari?>')}" class="btn-danger fa fa-trash" style="cursor: pointer;font-size: 12pt" title="Hapus Data"></button>
			</td>
		  </tr>
		<?php
		}
		?>
	  <tr  class="yz-theme-l1">
	    <td colspan="2" style="padding: 5px;text-align: left"><b>Total record : <?=$no?></b></td>
	  	<td colspan="3" style="padding: 5px;text-align: right"> <b>SUB TOTAL</b></td>
	  	<td style="padding: 5px;text-align: right"><b><?=gantitides($totnom)?></b></td>
		<td colspan="2"></td>
	  </tr>
	  <table> 
</div>

	<nav  aria-label="Page navigation example" style="margin-top:1px;font-size: 8pt">
	  <div class="row">
	  	<div class="col">
	  	   <ul class="pagination pagination-sm justify-content-start">
		    <!-- LINK FIRST AND PREV -->
		    <?php
		    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px">First</a></li>
		      <li class="page-item disabled "><a class="page-link fa fa-chevron-left yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
		    <?php
		    }else{ // Jika page bukan page ke 1
		      $link_prev = ($page > 1)? $page - 1 : 1;
		    ?>
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="caribiaya(1, false)">First</a></li>
		      <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="caribiaya(<?php echo $link_prev; ?>, false)"></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NUMBER -->
		    <?php
		    $jumlah_page = ceil($get_jumlah['jumlah'] / $limit);
		    //$jumlah_page = ceil($jum / $limit);
		    $jumlah_number = 1; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
		    $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
		    $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
		    
		    for($i = $start_number; $i <= $end_number; $i++){
		      $link_active = ($page == $i)? ' class="active"' : '';
		    ?>
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" onclick="caribiaya(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NEXT AND LAST -->
		    <?php
		    if($page == $jumlah_page || $get_jumlah['jumlah']==0){
		    //if($page == $jumlah_page || $jum==0){
		    ?>
		      <li class="page-item disabled " ><a class="page-link fa fa-chevron-right  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
		    <?php
		    }else{ // Jika Bukan page terakhir
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="caribiaya(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="caribiaya(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>	
	  	</div>
	  </div>	  
	</nav>
<?php
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>