<?php
	error_reporting(0); // Disable error reporting untuk mencegah error muncul di JSON
	ob_start();
	
	// Pastikan session sudah dimulai sebelum include config
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
	include 'config.php';
	
	// Pastikan keyword terdefinisi
	$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

    $connect=opendtcek();  
	$kd_toko=isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
?>
<style>
  .stok-table {
    font-size: 9pt;
    width: 100%;
    min-height: 100px;
    border-collapse: collapse;
    white-space: nowrap;
  }
  .stok-table th {
    position: sticky;
    top: 0px;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    border: 1px solid #ddd;
    padding: 6px 4px;
    text-align: center;
    background-color: #f0f8ff;
    font-weight: bold;
    vertical-align: middle;
  }
  .stok-table td {
    border: 1px solid #ddd;
    padding: 4px;
    vertical-align: middle;
  }
  .stok-table tbody tr:hover {
    background-color: #f5f5f5;
  }
  .stok-table tbody tr:nth-child(even) {
    background-color: #fafafa;
  }
  .stok-table tbody tr:nth-child(even):hover {
    background-color: #f0f0f0;
  }
</style>
<div style="overflow:auto;border-style: ridge;">
	<table class="stok-table table-bordered table-hover">
    <thead>
  	  <tr align="middle" class="yz-theme-l3">
  		<th style="width: 3%;min-width: 40px;">No.</th>
  		<th style="width: 10%;min-width: 120px;">BARCODE &nbsp;<button type="button" id="btn-kdbar" style="padding:3px" class="btn fa fa-search yz-hover-theme p-1"></button>
    	  	<div id="boxkdbar" style="display:none;position: absolute;z-index: 1;" >
		  		<!-- screen large -->
		  		<div class="input-group w3-card-4" style="width: 250px;margin-top: 26px;">
			       <input type="text" class="yz-theme-l4 form-control" id="kd_bar" name="kd_bar" style="border:1px solid black;font-size: 9pt;" placeholder="Barcode" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value='mas_brg.kd_bar like '+this.value;liststok(1,true);}">
			       <div class="input-group-append ">
			       	<button class="btn yz-theme-d1" onclick="
			          document.getElementById('kd_cari').value='mas_brg.kd_bar like '+document.getElementById('kd_bar').value;liststok(1,true);
			          " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i>
			        </button>
					<button class="btn btn-warning" onclick="
			          document.getElementById('kd_cari').value='';liststok(1,true);
			          " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
			        </button>
			       </div>		
			    </div>	
            </div>
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-kdbar").click(function(){
                $("#boxkdbar").slideToggle("fast");
                $("#boxbrg").slideUp("fast");
                $("#boxkdbrg").slideUp("fast");
                $("#kd_bar").focus();
              });
            });
          </script>
  		<th style="width: 10%;min-width: 120px;">KD. BARANG &nbsp;<button type="button" id="btn-kdbrg" class="btn fa fa-search yz-hover-theme p-1"></button>
    	  	<div id="boxkdbrg" style="display:none;position: absolute;z-index: 1;" >
		  		<!-- screen large -->
		  		<div class="input-group w3-card-4" style="width: 250px;margin-top: 26px">
			       <input type="text" class="yz-theme-l4 form-control" id="kd_brg" name="kd_brg" style="border:1px solid black;font-size: 9pt;" placeholder="Kode Barang" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value='mas_brg.kd_brg like '+this.value;liststok(1,true);}">
			       <div class="input-group-append w3-card-4">
			       	<button class="btn yz-theme-d1" onclick="
			          document.getElementById('kd_cari').value='mas_brg.kd_brg like '+document.getElementById('kd_brg').value;liststok(1,true);
			          " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i>
			        </button>
					<button class="btn btn-warning" onclick="
			          document.getElementById('kd_cari').value='';liststok(1,true);
			          " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
			        </button>
			       </div>		
			    </div>	
            </div>
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-kdbrg").click(function(){
                $("#boxkdbar").slideUp("fast");
                $("#boxbrg").slideUp("fast");
                $("#boxkdbrg").slideToggle("fast");
                $("#kd_brg").focus();
              });
            });
          </script>
  		<th id="carinmbrg" style="min-width: 200px;">NAMA BARANG &nbsp;<button type="button" id="btn-brg" class="btn fa fa-search yz-hover-theme" style="padding:3px"></button> 
    	  	<div id="boxbrg" style="display:none;position: absolute;z-index: 1;">
		  		<div class="input-group w3-card-4" style="width: 250px;margin-top: 26px">
			       <input type="text" class="yz-theme-l4 form-control" id="nm_brg" name="nm_brg" style="border:1px solid black;font-size: 9pt;" placeholder="NM.BARANG" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value=cr_nmbrg(this.value);liststok(1,true);}">
			       <div class="input-group-append">
			       	 <button class="btn yz-theme-d1" onclick="
			           document.getElementById('kd_cari').value=cr_nmbrg(document.getElementById('nm_brg').value);liststok(1,true);
			           " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i></button>
					  <button class="btn btn-warning" onclick="
			           document.getElementById('kd_cari').value='';liststok(1,true);
			           " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button> 
			       </div>		
			    </div>	
			</div>       
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-brg").click(function(){
                $("#boxkdbar").slideUp("fast");
                $("#boxkdbrg").slideUp("fast");
                $("#boxbrg").slideToggle("fast");
                $("#nm_brg").focus();
              });
            });
          </script>  
      <th style="min-width: 100px;">BRG MASUK</th>    
      <th style="min-width: 100px;">BRG KELUAR</th>    
  	  <th colspan="3" style="min-width: 450px;">KONVERSI STOK BARANG & HARGA JUAL</th>
	  <th style="width: 10%;min-width: 120px;">CEK TERAKHIR</th>		
      <th style="width: 5%;min-width: 60px;">NOTE</th>		
  	</tr>
  </thead>

   	<?php
		$kd_toko=$_SESSION['id_toko'];
		$page = (isset($_POST['page']))? $_POST['page'] : 1;
		$limit = 15; // Jumlah data per halamannya
		$limit_start = ($page - 1) * $limit;
		// echo '$limit_start='.$limit_start;

		if(isset($_POST['search']) && ($_POST['search'] == true || $_POST['search'] === 'true' || $_POST['search'] == '1')){ // Jika ada data search yg 
			$params = mysqli_real_escape_string($connect, $keyword);
			if(!empty($params)){
			  $xada=strpos($params,"like");
			  if ($xada <> false){
                $pecah=explode('like', $params);
			    $kunci=$pecah[0];
			    $kunci2=$pecah[1];
			    $params=$kunci." like '%".trim($kunci2)."%'";
			  }	
		  	  
			}else{
			  $kunci='';
			  $kunci2='';	
			}
			//echo "$params";
			//setting tampilan stok 0
			$sqlstok=@mysqli_query($connect,"SELECT kode FROM seting WHERE nm_per='tampil_stok'");
			if($sqlstok && mysqli_num_rows($sqlstok)>0){
				$dtper=mysqli_fetch_assoc($sqlstok);
				$kodestok=$dtper['kode'];
			} else {
				$kodestok=0;
			}
			if ($kodestok==1) {
               $tampil_stok='';
               $tampil_stok1=''; 
               ?>
               <script>document.getElementById('cektampil').checked=true;</script>
               <?php
          	} else {
               $tampil_stok  = ' AND beli_brg.stok_jual > 0 ';
               $tampil_stok1 = ' AND beli_brg.stok_jual > 0 ';
               ?>
               <script>document.getElementById('cektampil').checked=false;</script>
               <?php
			}
			
			//-----------------------

			if ($params=="") 
			{	 
			    $query_sql = "SELECT SUM(beli_brg.stok_jual) AS stok_juals, beli_brg.kd_brg,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_bar FROM beli_brg
			    	INNER JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg AND beli_brg.kd_toko=mas_brg.kd_toko
			    	WHERE beli_brg.kd_toko='$kd_toko' $tampil_stok
			    	GROUP BY beli_brg.kd_brg
			    	ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit";
			    $sql = @mysqli_query($connect, $query_sql);
			    
			    if (!$sql) {
			    	$sql = false;
			    }
			    
				$query_sql2 = "SELECT count(*) AS jumlah FROM (SELECT COUNT(*) FROM beli_brg 
					INNER JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg AND beli_brg.kd_toko=mas_brg.kd_toko
					WHERE beli_brg.kd_toko='$kd_toko' $tampil_stok
					GROUP BY beli_brg.kd_brg) jumlah";
				$sql2 = @mysqli_query($connect, $query_sql2);
			}
			else 
			{
				//echo $params;
				$query_sql = "SELECT SUM(beli_brg.stok_jual) AS stok_juals, beli_brg.kd_brg,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_bar FROM beli_brg
			    	INNER JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg AND beli_brg.kd_toko=mas_brg.kd_toko
			    	WHERE $params AND beli_brg.kd_toko='$kd_toko' $tampil_stok1
			    	GROUP BY beli_brg.kd_brg
					ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit";
				$sql = @mysqli_query($connect, $query_sql);
				
				if (!$sql) {
					$sql = false;
				}
				
				$query_sql2 = "SELECT count(*) AS jumlah FROM (SELECT COUNT(*) FROM beli_brg 
					INNER JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg AND beli_brg.kd_toko=mas_brg.kd_toko
					WHERE $params AND beli_brg.kd_toko='$kd_toko' $tampil_stok1 
					GROUP BY beli_brg.kd_brg) jumlah";
				$sql2 = @mysqli_query($connect, $query_sql2);
			}
			
			// Cek apakah query sql2 berhasil sebelum fetch
			if ($sql2 !== false && mysqli_num_rows($sql2) > 0) {
				$get_jumlah = mysqli_fetch_array($sql2);
			} else {
				$get_jumlah = array('jumlah' => 0);
			}
		} else {
			// Jika tidak ada search, set default
			$sql = false;
			$get_jumlah = array('jumlah' => 0);
		}
		
		// Pastikan variabel $get_jumlah terdefinisi
		if(!isset($get_jumlah) || !is_array($get_jumlah)){
			$get_jumlah = array('jumlah' => 0);
		}
		
		$no=$limit_start;$tot=0;$no_fak='';$tgl_fak='';$disc1=0;$disc2=0;$jmlsub=0;
		$kd_sup='';$nm_sup='';$brg_msk_hi=0.00;$brg_klr_hi=0;$cekada=0;$satkecil='';
		$stok1=0;$stok2=0;$stok3=0;
		
		// Cek apakah query sql berhasil sebelum loop
		if ($sql !== false && mysqli_num_rows($sql) > 0) {
			while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
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
	        $cekada=carinote($data['kd_brg'],$kd_toko,$connect);
	        //cek stok opname
			$kdbrg=$data['kd_brg'];
			$cektgl=$xc=$userx='';
			$cc=@mysqli_query($connect,"SELECT tgl_input,ket FROM mutasi_adj WHERE kd_brg='$kdbrg' AND kd_toko='$kd_toko' ORDER BY tgl_input DESC limit 1");
			if($cc && mysqli_num_rows($cc)>0){
				$dd=mysqli_fetch_assoc($cc);
				$cektgl=gantitgl($dd['tgl_input']);
				$xc = $dd['ket'];
				$userx = substr($xc,strpos($xc,'User :'),strpos($xc,', Jam')-strlen($xc));
			}
			unset($cc,$dd,$xc);
	        ?> 
            
		  <tr>
			<td align="right" style="padding: 6px 4px;"><?php echo $no ?></td>
			<td align="left" style="padding: 6px 4px;"><?php echo $data['kd_bar']; ?></td>
			<td align="left" style="padding: 6px 4px;"><?php echo $data['kd_brg']; ?></td>
			<td align="left" style="padding: 6px 4px;"><?php echo $data['nm_brg']; ?></td>
			<td align="right" style="cursor: pointer;padding: 6px 4px;" onclick="document.getElementById('keybrgmsk').value='<?=$data['kd_brg'];?>';carimut_msk(1,true);document.getElementById('form-arus-masuk').style.display='block';" onmouseenter="this.style.fontSize='10pt';this.style.color='blue';this.style.fontWeight='bold'" onmouseleave="this.style.fontSize='9pt';this.style.color='black';this.style.fontWeight='normal'"> 
				<?php echo gantitides($data['brg_msk']).' '.satkecil($data['kd_brg'],$connect); ?>&nbsp;</td>
            <td align="right" style="cursor: pointer;padding: 6px 4px;" onclick="document.getElementById('keybrgmsk').value='<?=$data['kd_brg'];?>';carimut_klr(1,true);carimut_gud(1,true);carimut_ret(1,true);document.getElementById('form-arus-klr').style.display='block'" onmouseenter="this.style.fontSize='10pt';this.style.color='blue';this.style.fontWeight='bold'" onmouseleave="this.style.fontSize='9pt';this.style.color='black';this.style.fontWeight='normal'">
            	<?php echo gantitides($data['brg_klr']).' '.satkecil($data['kd_brg'],$connect); ?>&nbsp;</td>
			<td align="right" class="yz-theme-l4" style="padding: 6px 4px;"><?php echo $stok1.' -@ '.gantitides($data['hrg_jum1']);?>&nbsp;</td>
			<td align="right" class="yz-theme-light" style="padding: 6px 4px;"><?php echo $stok2.' -@ '.gantitides($data['hrg_jum2']); ?>&nbsp;</td>
			<td align="right" style="padding: 6px 4px;"><?php echo $stok3.' -@ '.gantitides($data['hrg_jum3']); ?>&nbsp;</td>
			<td align="center" style="padding: 6px 4px;font-size: 8pt;"><?php echo $cektgl.($userx ? '<br>'.$userx : '');?></td>
			<?php if ($cekada>0){ ?>
			   <td style="text-align: center;padding: 4px;"><button class="btn-warning fa fa-warning" style="cursor: pointer;padding: 6px 8px;border: 1px solid #ddd;border-radius: 3px;background-color: #ffc107;color: #000;" onclick="document.getElementById('keybrgmsk').value='<?=$data['kd_brg']?>';document.getElementById('form-note').style.display='block';document.getElementById('viewjust').style.display='block';infojust(1,true);infostok(1,true)"></button></td> 
	        <?php  } else { ?>
	           <td style="text-align: center;padding: 4px;"><button class="fa fa-warning" style="cursor: pointer;padding: 6px 8px;border: 1px solid #ddd;border-radius: 3px;color: #0066cc;background-color: #e6f2ff;" onclick="document.getElementById('keybrgmsk').value='<?=$data['kd_brg']?>';document.getElementById('form-note').style.display='block';document.getElementById('viewjust').style.display='none';infostok(1,true);infojust(1,true);"></button></td>
	        <?php  } ?> 
		  </tr>

		<?php
		}
		} else {
			// Jika query gagal atau tidak ada data
			?>
			<tr>
				<td colspan="11" align="center" style="padding: 30px;color: #666;">
					<i class="fa fa-exclamation-triangle" style="font-size: 24pt;color: #ff9800;margin-right: 10px;"></i> 
					<span style="font-size: 11pt;">Tidak ada data atau terjadi kesalahan</span>
				</td>
			</tr>
			<?php
		}
		?>
	  <tr align="right" class="yz-theme-l3" style="background-color: #e6f2ff;font-weight: bold;">
		<td colspan="11" class="w3-center" style="padding: 8px;"><b>Total Item Barang : <?=$no?></b></td>
		<!-- <th ><?=gantiti($no) ?></th> -->
	  </tr>
	  <!-- <tr align="right" class="yz-theme-l1">
		<td colspan="10" class="w3-center" ><b>Grand Total Item Barang : <?=$get_jumlah['jumlah']?></b></td> -->
		<!-- <th ><?=gantiti($gtot) ?></th> -->
	  <!-- </tr> -->
	</table> 
</div>

	<nav  aria-label="Page navigation example" style="margin-top:5px;font-size: 9pt;padding: 5px 0;">
	  <div class="row" style="margin: 0;">
	  	<div class="col">
	  	   <ul class="pagination pagination-sm justify-content-start" style="margin-bottom: 0;">
		    <!-- LINK FIRST AND PREV -->
		    <?php
		    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">First</a></li>
		      <li class="page-item disabled "><a class="page-link fa fa-chevron-left yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;"></a></li>
		    <?php
		    }else{ // Jika page bukan page ke 1
		      $link_prev = ($page > 1)? $page - 1 : 1;
		    ?>
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" href="javascript:void(0);" onclick="liststok(1, false)" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';" >First</a></li>
		      <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" href="javascript:void(0);" onclick="liststok(<?php echo $link_prev; ?>, false)" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';"></a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;<?php echo ($page == $i) ? 'background-color: #0066cc;color: white;font-weight: bold;' : ''; ?>" onclick="liststok(<?php echo $i; ?>, false)" onmouseover="this.style.backgroundColor='<?php echo ($page == $i) ? '#0052a3' : '#0066cc'; ?>';this.style.color='white';" onmouseout="this.style.backgroundColor='<?php echo ($page == $i) ? '#0066cc' : ''; ?>';this.style.color='<?php echo ($page == $i) ? 'white' : ''; ?>';"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NEXT AND LAST -->
		    <?php
		    if($page == $jumlah_page || $get_jumlah['jumlah']==0){
		    //if($page == $jumlah_page || $jum==0){
		    ?>
		      <li class="page-item disabled " ><a class="page-link fa fa-chevron-right  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;"></a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">Last</a></li>
		    <?php
		    }else{ // Jika Bukan page terakhir
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="liststok(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';"></a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="liststok(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';" >Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>	
	  	</div>
	  	<div class="col d-flex w3-text-blue justify-content-end mr-2" style="align-items: center;"> 
	  		<div class="form-check" style="font-size: 10pt;margin-top: 5px;">
			  <input class="form-check-input" type="checkbox" id="cektampil" name="cektampil" onchange=" if (this.checked){
                tampilkanstok(1);
			  } else {
			  	tampilkanstok(0);
			  } " style="height: 16px;width: 16px;cursor: pointer;margin-top: 3px;">
			  <label class="form-check-label" for="cektampil" style="cursor: pointer;margin-left: 5px;user-select: none;">
			    Tampilkan stok kosong
			  </label>
			</div>
	  	</div>
	  </div>	
		  
	  
	</nav>

    <!-- form-arus masuk-->
    <div id="form-arus-masuk" class="w3-modal" style="padding-top:40px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge;">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;width: 100%">
        <div style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);color:black;font-size: 11pt">&nbsp;<i class="fa fa-search"></i>
          MUTASI BARANG MASUK
        </div>
 
        <div class="w3-center">
          <span onclick="document.getElementById('form-arus-masuk').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div id="viewmutasimasuk"></div>
        
      </div><!--Modal content-->
    </div>
    <!--  -->  

    <!-- form-arus keluar -->
    <div id="form-arus-klr" class="w3-modal" style="padding-top:40px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge; ">
      <div class="w3-modal-content w3-card-4 w3-animate-top" style="max-width:100%;max-height:600px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;overflow-y: auto;overflow-x:hidden;width: 100%">
        <div style="font-size:11pt;background-color: orange;background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);color:black;position: sticky;top:0px;z-index: 100" class="w3-card-2">&nbsp;<i class="fa fa-search"></i>
          MUTASI BARANG KELUAR
          <span onclick="document.getElementById('form-arus-klr').style.display='none';document.getElementById('key_cari').value='';document.getElementById('key_cari2').value='';" class="w3-display-topright" title="Close Modal" style="margin-top:-5px;margin-right: 0px;cursor:pointer"><img style="width: 108%" src="img/tomexit2.png" alt="">
          </span>    
        </div>
 
        <div id="viewmutasikeluar" class="w3-margin-bottom"></div>
        <div id="viewmutasiretur" class="w3-margin-bottom"></div>           
        <div id="viewmutasigudang"></div>
        
      </div><!--Modal content-->
    </div>
    <!-- End Form Nota -->  

    <div id="form-note" class="w3-modal" style="padding-top:40px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge; ">
      <div class="w3-modal-content w3-card-4 w3-animate-top" style="max-width:100%;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;background: linear-gradient(180deg, #FAFAD2 10%, white 90%)">
        <div style="font-size:11pt;background-color: orange;background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);color:black;position: sticky;top:0px">&nbsp;<i class="fa fa-search"></i>
          CATATAN STOK BARANG
        </div>
        <div class="w3-center">
          <span onclick="document.getElementById('form-note').style.display='none';document.getElementById('key_cari').value='';document.getElementById('key_cari2').value='';" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div id="viewjust" class="w3-margin-bottom"></div>
        <div id="viewinfostok"></div>
        
      </div><!--Modal content-->
    </div>
    <!--  -->  

<?php
    function satkecil($kdbrg,$hub){
      $x=explode(';',carisatkecil($kdbrg));
      $xx=$x[0];$nm='NONE';
      $sqlc=mysqli_query($hub,"SELECT nm_sat1 FROM kemas WHERE no_urut='$xx'");
	  if(mysqli_num_rows($sqlc)>0){
		$datc=mysqli_fetch_assoc($sqlc);
        $nm=$datc['nm_sat1'];
	  }
	  unset($sqlc,$datc);
      return $nm;
    }

	function caribrgmsk($kd_brg,$kd_toko,$hub){
	  // $tgl_hi=date('Y-m-d');
	  $jml_brg=0;$jml_brg_kov=0;
	  // $con = mysqli_connect("localhost","root", "", "toko_retail");
	  $cek=mysqli_query($hub,"SELECT SUM(stok_jual) AS jml FROM beli_brg_jml where kd_brg='$kd_brg' and kd_toko='$kd_toko' order by no_urut ASC");
	  $data=mysqli_fetch_assoc($cek);
	  $jml_brg=$data['jml'];
	  // if(mysqli_num_rows($cek)>=1){
	  // 	while ($data=mysqli_fetch_assoc($cek)) {
	  	  // $jml_brg_kov=konjumbrg($data['kd_sat'],$kd_brg,$kd_toko)*$data['jml_brg'];
	  	  // $jml_brg=$jml_brg+$jml_brg_kov;
	  	//   $jml_brg=$jml_brg+$data['stok_jual'];	
	  	// }
	  // }
	  return $jml_brg;
	  unset($data,$cek);
	  // mysqli_close($con);
	}		

	function caribrgklr($kd_brg,$kd_toko){
	  $tgl_hi=date('Y-m-d');
	  $jml_brg=0;
	  $con1 = opendtcek(1) ;
	  $cek=mysqli_query($con1,"SELECT * FROM dum_jual where tgl_jual<='$tgl_hi' and kd_brg='$kd_brg' and kd_toko='$kd_toko' order by no_urut ASC");
	  if(mysqli_num_rows($cek)>=1){
	  	while ($data=mysqli_fetch_assoc($cek)) {
	  	  $jml_brg_kov=konjumbrg($data['kd_sat'],$kd_brg,$kd_toko)*$data['qty_brg'];
	  	  $jml_brg=$jml_brg+$jml_brg_kov;
	  	}
	  }
	  return $jml_brg;
	  unset($data,$cek);
	}	

    function carinote($kdbrgs,$kdtokos,$hubs){
	   $ceknote=mysqli_query($hubs,"SELECT COUNT(*) AS jumnote FROM mutasi_adj where kd_brg='$kdbrgs' AND kd_toko='$kdtokos'");
	   $datceknote=mysqli_fetch_assoc($ceknote);
	   $x=$datceknote['jumnote'];
	   return $x;
	   unset($ceknote,$datceknote);
    }	
?>
<script>
	function cr_nmbrg(nmbrg) {
		nmbrg="mas_brg.nm_brg like "+nmbrg;
		return nmbrg;
	}
</script>
<?php
  if(isset($connect) && $connect){
    mysqli_close($connect);
  }
	$html = ob_get_contents();
	ob_end_clean();
	// Pastikan output JSON valid
	if($html === false || $html === ''){
		$html = '<tr><td colspan="11" align="center" style="padding: 20px;"><i class="fa fa-exclamation-triangle"></i> Tidak ada data atau terjadi kesalahan</td></tr>';
	}
	echo json_encode(array('hasil'=>$html));
?>