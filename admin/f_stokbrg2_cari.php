<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
	include 'config.php';
	session_start();
	
    $connect=opendtcek();  
	$kd_toko=$_SESSION['id_toko'];
?>

<div class="table-responsive hrf_arial" style="overflow-x:auto;border-style: ridge;">
	<table class="table table-bordered table-sm table-hover" style="font-size:9pt;border-collapse: collapse;white-space: nowrap; ">
    <thead >
  	  <tr align="middle" class="yz-theme-l1">
  		<th>No.</th>
  		
  		<th id="carinmbrg">NAMA BARANG &nbsp;<button type="button" id="btn-brg" class="btn yz-hover-theme p-1"><i class="fa fa-search"></i></button> 

  		  <div class="row">
  				<div class="col">
  				  	<div id="boxbrg" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px;margin-top:7px">
  				  		<div class="input-group w3-card-4" style="width: 250px;margin-top: 26px">
  					       <input type="text" class="yz-theme-l4 form-control" id="nm_brg1" name="nm_brg1" style="border:1px solid black;font-size: 9pt;" placeholder="NM.BARANG" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value=cr_nmbrg(this.value);liststok2(1,true);liststok3(1,true);}">
  					       <div class="input-group-append">
								<button class="btn yz-theme-d1" onclick="
									document.getElementById('kd_cari').value=cr_nmbrg(document.getElementById('nm_brg1').value);liststok2(1,true);liststok3(1,true);document.getElementById('boxbrg').style.display='none';
									" style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i>
							    </button>
								<button class="btn btn-warning" onclick="
									document.getElementById('kd_cari').value='';liststok2(1,true);liststok3(1,true);document.getElementById('boxbrg').style.display='none';
									" style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
								</button>
  					       </div>		
  					    </div>	
  	                </div>
  	            </div>
  	      </div>    
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-brg").click(function(){
                $("#boxkdbrg").slideUp("fast");
                $("#boxbrg").slideToggle("fast");
                $("#nm_brg1").focus();
              });
            });
          </script>

        <!-- <th>SUPPLIER &nbsp;<button type="button" id="btn-kdbrg"><i class="fa fa-search"></i></button>
  		  <div class="row">
  				<div class="col">
  				  	<div id="boxkdbrg" class="container" style="display:none;position: fixed;z-index: 1;margin-left: -15px;margin-top: 7px" >
  				  		<div class="input-group">
  					       <input type="text" class="yz-theme-l4 w3-card-2" id="kd_brg" name="kd_brg" style="width:250px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kode Barang" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value='supplier.nm_sup like '+this.value;liststok2(1,true);liststok3(1,true);}">
  					       <span><button class="w3-card-2 btn btn-primary" onclick="
  					       document.getElementById('kd_cari').value='';liststok2(1,true);liststok3(1,true);document.getElementById('boxbrg').style.display='none';
  					       " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
  					       </span>		
  					    </div>	
  	          </div>
  	      </div>
  	    </div>    

        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-kdbrg").click(function(){
                $("#boxbrg").slideUp("fast");
                $("#boxbarcode").slideUp("fast");
                $("#boxkdbrg").slideToggle("fast");
                $("#kd_brg").focus();
              });
            });
          </script> --> 
        <th >SUPPLIER</th>  
        <th >TGL BELI</th>      
        <th >HARGA BELI</th>    
        <th colspan="3">KONVERSI JML STOK & HARGA BARANG</th>
  	</tr>
  </thead>

   	<?php
		$kd_toko=$_SESSION['id_toko'];
		$page = (isset($_POST['page']))? $_POST['page'] : 1;
		$limit = 15; // Jumlah data per halamannya
		$limit_start = ($page - 1) * $limit;
		// echo '$limit_start='.$limit_start;

		if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
			$params = mysqli_real_escape_string($connect, $keyword);
			if(!empty($params)){
			  $xada=strpos($params,"like");
			  if ($xada <> false){
                $pecah=explode('like', $params);
			    $kunci=$pecah[0];
			    $kunci2=$pecah[1];
			    $params=$kunci." like '%".trim($kunci2)."%'";
			  }else{
			  	//$xada=strpos($params,"=");
			  	$pecah=explode('=', $params);
			    $kunci=$pecah[0];
			    $kunci2=$pecah[1];
			    $params=$kunci." = '".trim($kunci2)."'";
			  }	
		  	  
			}else{
			  $kunci='';
			  $kunci2='';	
			}
			// echo "$params";
			if ($params=="") 
			{	 
			    $sql =mysqli_query($connect,"SELECT beli_brg.ket,beli_brg.tgl_fak,beli_brg.kd_brg,beli_brg.kd_sup,beli_brg.hrg_beli,beli_brg.stok_jual,supplier.nm_sup,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3
				    FROM beli_brg 
				    LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
				    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
					WHERE INSTR(beli_brg.ket,'MUTASI') = 0 
				    ORDER BY beli_brg.no_urut ASC LIMIT $limit_start, $limit");
				$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE INSTR(beli_brg.ket,'MUTASI') = 0");
			}
			else 
			{
				$sql =mysqli_query($connect, "SELECT beli_brg.ket,beli_brg.tgl_fak,beli_brg.kd_brg,beli_brg.kd_sup,beli_brg.hrg_beli,beli_brg.stok_jual,supplier.nm_sup,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3
				    FROM beli_brg 
				    LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
				    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
					WHERE $params AND INSTR(beli_brg.ket,'MUTASI') = 0
					-- and beli_brg.kd_toko='$kd_toko' 
					ORDER BY beli_brg.hrg_beli ASC LIMIT $limit_start, $limit");
				$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg 
					LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
				    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
					WHERE $params AND INSTR(beli_brg.ket,'MUTASI') = 0 ");
			}	
            $get_jumlah = mysqli_fetch_array($sql2);  		    
		}else{ 
			// Jika user belum mengklik tombol search (PROSES TANPA AJAX)
			$sql = mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_bar,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.jml_brg,mas_brg.brg_msk,mas_brg.brg_klr
				FROM mas_brg 
				ORDER BY mas_brg.no_urut ASC LIMIT $limit_start, $limit");
			$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg ");
			$get_jumlah = mysqli_fetch_array($sql2);
		}

		$no=$limit_start;$tot=0;$no_fak='';$tgl_fak='';$disc1=0;$disc2=0;$jmlsub=0;
		$kd_sup='';$nm_sup='';$brg_msk_hi=0.00;$brg_klr_hi=0;
		while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
			$no++;
	        $brg_msk_hi=$data['stok_jual'];
	        //echo '$brg_msk='.$brg_msk_hi.'<br>';
	        if ($data['jum_kem1']>0) {
	          $stok1=gantitides($brg_msk_hi/$data['jum_kem1']).' '.$data['nm_kem1'];	
	        }else{
	          $stok1='NONE';
	        }
	        if ($data['jum_kem2']>0) {
	          $stok2=gantitides($brg_msk_hi/$data['jum_kem2']).' '.$data['nm_kem2'];  
	        }else{
	          $stok2='NONE';
	        }
	        if ($data['jum_kem3']>0) {
	          $stok3=gantitides($brg_msk_hi/$data['jum_kem3']).' '.$data['nm_kem3'];  	
	        }else{
	          $stok3='NONE';
	        }
            ?> 
		  <tr>
			<td align="right"><?php echo $no ?></td>
			<td align="left" style="cursor: pointer" onclick="
			    document.getElementById('kd_cari').value='mas_brg.nm_brg = <?php echo $data['nm_brg']; ?>';
			    liststok2(1,true);liststok3(1,true);">
				&nbsp;<?php echo $data['nm_brg']; ?>
			</td>
			<td align="left">&nbsp;<?php echo $data['nm_sup']; ?></td>
			<td align="middle"><?php echo gantitgl($data['tgl_fak']); ?></td>
			<td align="right"><?php echo gantiti($data['hrg_beli']); ?>&nbsp;</td>
			<td align="right" class="yz-theme-l2"><?php echo $stok1.' -@ '.gantitides($data['hrg_jum1']);; ?></td>
			<td align="right" class="yz-theme-l3"><?php echo $stok2.' -@ '.gantitides($data['hrg_jum2']); ?></td>
			<td align="right" class="yz-theme-l4"><?php echo $stok3.' -@ '.gantitides($data['hrg_jum3']); ?></td>
		  </tr>

		<?php
		}
		?>
	  <tr align="right" class="yz-theme-l1">
		<th colspan="10" class="w3-center">Sub Total Item Barang : <?=$no?></th>
		<!-- <th ><?=gantiti($no) ?></th> -->
	  </tr>
	  <tr align="right" class="yz-theme-l1">
		<th colspan="10" class="w3-center" >Grand Total Item Barang : <?=$get_jumlah['jumlah']?></th>
		<!-- <th ><?=gantiti($gtot) ?></th> -->
	  </tr>
	</table> 
</div>

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
	      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="liststok2(1, false)">First</a></li>
	      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="liststok2(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
	      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="liststok2(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
	    <?php
	    }
	    ?>
	    
	    <!-- LINK NEXT AND LAST -->
	    <?php
	    if($page == $jumlah_page || $get_jumlah['jumlah']==0){
	    //if($page == $jumlah_page || $jum==0){
	    ?>
	      <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&raquo;</a></li>
	      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
	    <?php
	    }else{ // Jika Bukan page terakhir
	      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
	    ?>
	      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="liststok2(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
	      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="liststok2(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
	    <?php
	    }
	    ?>
	  </ul>
	</nav>

    
<?php
	function caribrgmsk($kd_brg,$kd_toko,$hub){
	  $tgl_hi=date('Y-m-d');
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
?>
<script>
	function cr_nmbrg(nmbrg) {
		nmbrg="mas_brg.nm_brg like "+nmbrg;
		return nmbrg;
	}
</script>
<?php
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>