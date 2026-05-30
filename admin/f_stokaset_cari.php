<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	$keyword2 = $_POST['keyword2'];
	ob_start();
	include 'config.php';
	session_start();
    $connect=opendtcek();  
	$kd_toko=$_SESSION['id_toko'];
?>
<div style="overflow:auto;border-style: ridge;">
	<table class="table-bordered table-hover" style="font-size:9pt;background: linear-gradient(180deg, #FAFAD2 10%, white 90%);width: 100%;min-height: 100px;border-collapse: collapse;white-space: nowrap;">
    <thead>
  	  <tr align="middle" class="yz-theme-l1">
  		<th style="width: 3%">No.</th>
  		<th style="width: 6%" id="carikdtoko">KD. TOKO &nbsp;<button type="button" id="btn-kdtokos" class="btn fa fa-search yz-hover-theme" style="padding:3px"></button> 
  			<div id="boxkdtokos" style="display:none;position: absolute;z-index: 1;">
		  		<div class="input-group w3-card-4" style="width: 250px;margin-top: 26px">
			       <input type="text" class="yz-theme-l4 form-control" id="kd_tokos" name="kd_tokos" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kd. Toko" onkeypress="if(event.keyCode==13){document.getElementById('key_cari2').value=this.value;listaset(1,true);}">
			       <div class="input-group-append">
			       	 <button id='btn-canceltk' class="btn yz-theme-d1" onclick="
			           document.getElementById('key_cari2').value=document.getElementById('kd_tokos').value;listaset(1,true);
			           " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i></button>
			       </div>		
				   <div class="input-group-append">
			       	 <button id='btn-canceltk' class="btn btn-warning" onclick="
			           document.getElementById('key_cari2').value='';listaset(1,true);
			           " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
			       </div>		
			    </div>	
			</div>       
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-kdtokos").click(function(){
                $("#boxbrgs").slideUp("fast");
                $("#boxkdtokos").slideToggle("fast");
                $("#kd_tokos").focus();
              });
              $("#btn-canceltk").click(function(){
                $("#boxkdtokos").slideUp("fast");
                
              });
            });
          </script>  
  		
  		<th style="width: 5%">TGL.BELI</th>
  		<th style="width: 14%">NO.FAKTUR</th>
        <th style="width: 16%">SUPPLIER</th>    

  		<th id="carinmbrgs" style="width:24%">NAMA BARANG &nbsp;<button type="button" id="btn-brgs" class="btn fa fa-search w3-hover-shadow" style="padding:3px"></button> 
    	  	<div id="boxbrgs" style="display:none;position: absolute;z-index: 1;">
		  		<div class="input-group w3-card-4" style="width: 250px;margin-top: 26px">
			       <input type="text" class="yz-theme-l4 form-control" id="nm_brgs" name="nm_brgs" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="NM.BARANG" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value=cr_nmbrgs(this.value);listaset(1,true);}">
			       <div class="input-group-append">
			       	 <button class="btn yz-theme-d1" onclick="
			           document.getElementById('kd_cari').value=cr_nmbrgs(document.getElementById('nm_brgs').value);listaset(1,true);
			           " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i></button>
					   <button class="btn btn-warning" onclick="
			           document.getElementById('kd_cari').value='';listaset(1,true);
			           " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
			       </div>		
			    </div>	
			</div>       
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-brgs").click(function(){
                $("#boxkdtokos").slideUp("fast");
                $("#boxbrgs").slideToggle("fast");
                $("#nm_brgs").focus();
              });
            });
          </script>  
      <th style="width: 10%">HRG. BELI</th>    
      <th style="width: 6%">STOK</th>
      <th style="width: 3%">SATUAN</th>
      <th style="width: 4%">DISC</th>    
      <th style="width: 12%">SUB TOTAL</th>    
  	  <!-- <th colspan="3">KONVERSI STOK BARANG & HARGA BELI</th>
      <th width="2%">NOTE</th>		 -->
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
			$para2 = mysqli_real_escape_string($connect, $keyword2);
			if (!empty($para2)){
				 $params2="AND beli_brg.kd_toko LIKE '%".trim($para2)."%'";
				//$params2="beli_brg.kd_toko='$para2'";
			} else {$params2="";}

			if(!empty($params)){
			  $xada=strpos($params,"like");
			  if ($xada <> false){
                $pecah=explode('like', $params);
			    $kunci=$pecah[0];
			    $kunci2=$pecah[1];
			    $params=" AND ".$kunci." like '%".trim($kunci2)."%'";
			  }	
		  	  
			}else{
			  $kunci='';
			  $kunci2='';	
			  $params='';
			}
			$sql=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.kd_toko,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,supplier.nm_sup FROM beli_brg 
		    	LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
		    	LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
		    	WHERE beli_brg.stok_jual > 0 $params $params2
				ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
			$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg 
				    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
				    WHERE beli_brg.stok_jual>0 $params $params2");
            $get_jumlah = mysqli_fetch_array($sql2);  		    
		}
		$hal=($limit_start+$limit)/$limit;	
		$no=$limit_start;$tot=0;$disc1=0;$disc2=0;$jmlsub=0;
		$brg_msk_hi=0.00;$hrg_beli=0;$hrg_belidisc=0.00;$hrg_belidef=0.00;
		$kd_kem_kcl=0;$jum_kem_kcl=0;$tothrg_awal=0;$tothrg_akhir=0;$a=0;
		while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
			$no++;
			$brg_msk_hi=$data['stok_jual'];
	        //echo '$brg_msk='.$brg_msk_hi.'<br>';
	        $x=explode(';',carisatkecil2($data['kd_brg'],$connect));
	        $kd_kem_kcl=$x[0];
	        $jum_kem_kcl=$x[1];

            $disc1=$data['disc1']/100;
			$disc2=$data['disc2'];

			//konversi satuan terkecil
			  if($data['kd_sat']==$data['kd_kem3']){
			    $hrg_beli=$data['hrg_beli']/$data['jum_kem3'];
			  }
			  if($data['kd_sat']==$data['kd_kem2']){
			    $hrg_beli=$data['hrg_beli']/$data['jum_kem2'];
			  }
			  if($data['kd_sat']==$data['kd_kem1']){
			    $hrg_beli=$data['hrg_beli']/$data['jum_kem1'];
			  }
			 // ------------------------  


			// ** jika proses potong maka semua input satuan besar output konversi ke satuan kecil
			// $hrg_beli=$data['hrg_beli']/konjumbrg2($data['kd_sat'],$data['kd_brg'],$connect);
			
			if ($data['disc1']==0.00 && $data['disc2']==0){
			  $hrg_belidisc=$hrg_beli*$data['stok_jual'];
			} else if ($data['disc1'] > 0.00 && $data['disc2']==0) {
              $hrg_belidisc=($hrg_beli-($hrg_beli*$disc1))*$data['stok_jual'];
			} else if ($data['disc1'] == 0.00 && $data['disc2']>0) {
              $hrg_belidisc=($hrg_beli-$disc2)*$data['stok_jual'];
          	}
            $hrg_belidisc=$hrg_belidisc+($hrg_belidisc*($data['ppn']/100));
            $tothrg_akhir=$tothrg_akhir+$hrg_belidisc;
	        $tothrg_awal=$tothrg_awal+$hrg_beli;
	        ?> 
            
		  <tr>
			<td align="right"><?php echo $no ?>&nbsp;</td>
			<td align="center" style="border-right: none"><?php echo $data['kd_toko']; ?></td>
			<td align="center" style="border-right: none;border-left: none"><?php echo gantitgl($data['tgl_fak']); ?></td>
			<td align="left" style="border-right: none">&nbsp;<?php echo $data['no_fak']; ?></td>
			<!-- <td align="left" style="border-right: none">&nbsp;<?php echo $data['kd_brg']; ?></td> -->
			<td align="center" style="border-right: none;border-left: none"><?php echo $data['nm_sup']; ?></td>
			<td align="left" style="border-right: none;border-left: none">&nbsp;<?php echo $data['nm_brg']; ?></td>
			<td align="right" style="border-right: none;border-left: none"><?php echo gantitides($hrg_beli); ?>&nbsp;</td>		
			<td align="right" style="border-right: none;border-left: none"><?php echo gantitides($data['stok_jual']); ?>&nbsp;</td>
			<td align="center" style="border-right: none;border-left: none"><?php echo ceknmkem2($kd_kem_kcl, $connect); ?>&nbsp;</td>

			<?php if ($data['disc2']>0) { ?>
			<td align="center" style="border-right: none;border-left: none"><?php echo gantiti($data['disc2']) ?>&nbsp;</td>
			<?php } else { ?>
			<td align="right" style="border-right: none;border-left: none"><?php echo gantitides($data['disc1']).' %'?></td>  	
            <?php } ?>
			<td align="right" style="border-right: none;border-left: none"><?php echo gantitides($hrg_belidisc); ?>&nbsp;</td>		
		  </tr>

		<?php
		}
		$sq=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.kd_toko,beli_brg.hrg_beli,beli_brg.kd_sat,beli_brg.stok_jual,mas_brg.nm_brg,supplier.nm_sup FROM beli_brg
		    	LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
			    LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
			    WHERE  beli_brg.stok_jual > 0 $params $params2
		    	ORDER by mas_brg.nm_brg ASC LIMIT 0,$no");
		$hrg_tot_a=0;$hrg_tot_ah=0;$hrg_beli_a=0;$dis1=0;$dis2=0;$tot_ah=0;$hrg_x=0;
		while ($dt=mysqli_fetch_assoc($sq)){
			$dis1=$dt['disc1']/100;
			$dis2=$dt['disc2'];
			$hrg_beli_a=$dt['hrg_beli']/konjumbrg2($dt['kd_sat'],$dt['kd_brg'],$connect);
			$hrg_tot_a=$hrg_tot_a+$hrg_beli_a;

   		    if ($dt['disc1']==0.00 && $dt['disc2']==0){
			  $hrg_x=$hrg_beli_a*$dt['stok_jual'];
			} else if ($dt['disc1'] > 0.00 && $dt['disc2']==0) {
              $hrg_x=($hrg_beli_a-($hrg_beli_a*$dis1))*$dt['stok_jual'];
			} else if ($dt['disc1'] == 0.00 && $dt['disc2']>0) {
              $hrg_x=($hrg_beli_a-$dis2)*$dt['stok_jual'];
          	}
	        $hrg_tot_ah=$hrg_tot_ah+$hrg_x;
			if (konjumbrg2($dt['kd_sat'],$dt['kd_brg'],$connect)==0){
				echo $dt['kd_brg'].' '.$hrg_beli.'<br>';     
			}
	        
		}
		unset($dt);mysqli_free_result($sq);	
		?>
	  <tr align="right" class="yz-theme-l1">
		<td colspan="6" style="padding: 4px"><b>SUB TOTAL HALAMAN : <?=$hal?>&nbsp;</b></td>
		<td style="padding: 4px"><b><?=gantitides($tothrg_awal)?>&nbsp;</b></td>
		<td colspan="3"></td>
		<td colspan="4" style="padding: 4px"><b><?=gantitides($tothrg_akhir)?>&nbsp;</b></td>
	  </tr>
	  <tr align="right" class="yz-theme-d1">
		<td colspan="6" style="padding: 4px;"><b>TOTAL HALAMAN : 1 - - <?=$hal?>&nbsp;</b></td>
		<td style="padding: 4px;"><b><?=gantitides($hrg_tot_a)?>&nbsp;</b></td>
		<td colspan="3"></td>
		<td  style="padding: 4px;"><b><?=gantitides($hrg_tot_ah)?>&nbsp;</b></td>
	  </tr>
	</table> 
</div>

<div class="row ">
  <div class="col">
  	<nav  aria-label="Page navigation example" style="margin-top:1px;">
	  <ul class="pagination pagination-sm justify-content-start">
	    <!-- LINK FIRST AND PREV -->
	    <?php
	    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
	    ?>
	      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 5px 10px 6px 10px">First</a></li>
	      <li class="page-item disabled "><a class="page-link fa fa-chevron-left yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 5px 10px 6px 10px"></a></li>
	    <?php
	    }else{ // Jika page bukan page ke 1
	      $link_prev = ($page > 1)? $page - 1 : 1;
	    ?>
	      <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 5px 10px 6px 10px" href="javascript:void(0);" onclick="listaset(1, false)">First</a></li>
	      <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 5px 10px 6px 10px" href="javascript:void(0);" onclick="listaset(<?php echo $link_prev; ?>, false)"></a></li>
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
	      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 5px 10px 6px 10px" onclick="listaset(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
	    <?php
	    }
	    ?>
	    
	    <!-- LINK NEXT AND LAST -->
	    <?php
	    if($page == $jumlah_page || $get_jumlah['jumlah']==0){
	    //if($page == $jumlah_page || $jum==0){
	    ?>
	      <li class="page-item disabled " ><a class="page-link fa fa-chevron-right  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 5px 10px 6px 10px"></a></li>
	      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 5px 10px 6px 10px">Last</a></li>
	    <?php
	    }else{ // Jika Bukan page terakhir
	      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
	    ?>
	      <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="listaset(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 5px 10px 6px 10px"></a></li>
	      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="listaset(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 5px 10px 6px 10px">Last</a></li>
	    <?php
	    }
	    ?>
	  </ul>
	  
	</nav>
  </div>	
  <div class="col w3-container">
    <button class="btn-warning w3-hover-shadow w3-card-2" style="cursor:pointer;border-radius:3px;margin-top: 2px;width: 100px;" onclick="document.getElementById('formcetaset').style.display='block'"><i class="fa fa-print"></i>&nbsp;Cetak</button>   	
  </div>
</div>
<?php
    function satkecil($kdbrg,$hub){
      $x=explode(';',carisatkecil($kdbrg));
      $xx=$x[0];
      $sqlc=mysqli_query($hub,"SELECT nm_sat1 FROM kemas WHERE no_urut='$xx'");
      $datc=mysqli_fetch_assoc($sqlc);
      $nm=$datc['nm_sat1'];
      return $nm;
      unset($sqlc,$datc);
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
	function cr_kdtokos(kdtoko) {
		kd_toko=" AND beli_brg.kd_toko like '%"+kdtoko+"%'";
		return kd_toko;
	}
	function cr_nmbrgs(nmbrg) {
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