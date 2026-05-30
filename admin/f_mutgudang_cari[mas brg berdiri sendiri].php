<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
	include 'config.php';
    $connect=opendtcek();  
	session_start();
	$kd_toko=$_SESSION['id_toko'];
?>

<div class="table-responsive hrf_arial" style="overflow-y:auto;border-style: ridge;">
	<table class="table table-bordered table-sm table-hover" style="font-size:9pt;background: linear-gradient(180deg, #FAFAD2 10%, white 90%) ">
    <thead >
  	  <tr align="middle" class="yz-theme-l1">
  		<th style="width: 1%">No.</th>
  		<th style="width: 15%">TOKO/GUDANG &nbsp;<button type="button" id="btn-toko"><i class="fa fa-search"></i></button> 
  			<div class="row" >
  				<div class="col">
  				  	<div id="boxtoko" class="container" style="display: none;position:fixed;z-index: 1;margin-left: -15px;margin-top: 7px" >
  				  		<div class="input-group">
  					      <input type="text" class="yz-theme-l4 w3-card-2" id="kd_toko" name="kd_toko" style="width:250px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Toko/Gudang" onkeypress="if(event.keyCode==13){document.getElementById('keytoko').value='toko.nm_toko like '+this.value;liststok5(1,true);}">
  					          <span>
                                <button class="w3-card-2 btn btn-primary" onclick="
  					              document.getElementById('keytoko').value='';liststok5(1,true);
  					              " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                                </button>
  					          </span>		
  					    </div>	
  	                </div>
  	            </div>
  	        </div>    
        </th>	
        <script>
          $(document).ready(function(){
            $("#btn-toko").click(function(){
              $("#boxtoko").slideToggle("fast");	
              $("#boxkdbrg").slideUp("fast");
              $("#boxbrg").slideUp("fast");
              $("#kd_toko").focus();
            });
          });
        </script>  
  		<th style="width: 15%">KD. BARANG &nbsp;<button type="button" id="btn-kdbrg"><i class="fa fa-search"></i></button>
  		    <div class="row">
  				<div class="col">
  				  	<div id="boxkdbrg" class="container" style="display:none;position: fixed;z-index: 1;margin-left: -15px;margin-top: 7px" >
  				  		<div class="input-group w3-hide-small">
  					       <input type="text" class="yz-theme-l4 w3-card-2" id="kd_brg" name="kd_brg" style="width:250px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kode Barang" onkeypress="if(event.keyCode==13){document.getElementById('kd_carimut').value='mas_brg.kd_brg like '+this.value;liststok5(1,true);}">
  					       <span><button class="w3-card-2 btn btn-primary" onclick="
  					       document.getElementById('kd_carimut').value='';document.getElementById('boxkdbrg').style.display='none';liststok5(1,true);
  					       " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
  					       </span>		
  					    </div>	

  					    <!-- small screen -->
  					    <div class="input-group w3-hide-large w3-hide-medium" style="left: -115px;">
  					       <input type="text" class="yz-theme-l4 w3-card-2" id="kd_brg1" name="kd_brg1" style="width:250px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kode Barang" onkeypress="if(event.keyCode==13){document.getElementById('kd_carimut').value='mas_brg.kd_brg like '+this.value;liststok5(1,true);}">
  					       <span><button class="w3-card-2 btn btn-primary" onclick="
  					       document.getElementById('kd_carimut').value='';document.getElementById('boxkdbrg').style.display='none';liststok5(1,true);
  					       " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
  					       </span>		
  					    </div>	
  					    <!--  -->
  	                </div>
  	            </div>
  	        </div>    
      </th>	
          <script>
            $(document).ready(function(){
              $("#btn-kdbrg").click(function(){
              	$("#boxkdbrg").slideToggle("fast");
                $("#boxbrg").slideUp("fast");
                $("#boxtoko").slideUp("fast");
                $("#kd_brg").focus();
                $("#kd_brg1").focus();
              });
            });
          </script>
  		<th id="carinmbrg" style="width: 20%">NAMA BARANG &nbsp;<button type="button" id="btn-brg"><i class="fa fa-search"></i></button> 

  		    <div class="row">
  				<div class="col">

  				  	<div id="boxbrg" class="container" style="display:none;position:fixed;z-index: 1;margin-left: -15px;margin-top:7px">
  				  		<!-- large medium screen -->
  				  		<div class="input-group w3-hide-small">
  					       <input type="text" class="yz-theme-l4 w3-card-2" id="nm_brg" name="nm_brg" style="width:250px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px" placeholder="NM.BARANG" onkeypress="if(event.keyCode==13){document.getElementById('kd_carimut').value=cr_nmbrg(this.value);liststok5(1,true);}">
  					        <span>
  					       	  <button class="w3-card-2 btn btn-primary" onclick="
  					            document.getElementById('kd_carimut').value='';
  					            document.getElementById('boxbrg').style.display='none';liststok5(1,true);
  					              " style="border:1px solid black">
  					            <i class="fa fa-undo" style="cursor: pointer"></i>
  					          </button>
  					        </span>		
  					    </div>	

  					    <!-- small screen -->
  					    <div class="input-group w3-hide-large w3-hide-medium" style="left: -180px;">
  					       <input type="text" class="yz-theme-l4 w3-card-2" id="nm_brg2" name="nm_brg2" style="width:250px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px" placeholder="NM.BARANG" onkeypress="if(event.keyCode==13){document.getElementById('kd_carimut').value=cr_nmbrg(this.value);liststok5(1,true);}">
  					        <span>
  					       	  <button class="w3-card-2 btn btn-primary" onclick="
  					            document.getElementById('kd_carimut').value='';
  					            document.getElementById('boxbrg').style.display='none';liststok5(1,true);
  					              " style="border:1px solid black">
  					            <i class="fa fa-undo" style="cursor: pointer"></i>
  					          </button>
  					        </span>		
  					    </div>	
  					    <!--  -->
  	                </div>

  	            </div>
  	        </div>    

        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-brg").click(function(){
                $("#boxtoko").slideUp("fast");
                $("#boxkdbrg").slideUp("fast");
                $("#boxbrg").slideToggle("fast");
                $("#nm_brg").focus();
                $("#nm_brg2").focus();
              });
            });
          </script>  
      	<th colspan="3">KONVERSI JML STOK & HARGA BARANG</th>
      	<th width="6%" colspan="2">OPSI</th>
  	</tr>
  </thead>
   	<?php
		//$kd_toko=$_SESSION['id_toko'];
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
			  }	
		  	  
			}else{
			  $kunci='';
			  $kunci2='';	
			}	

            if(isset($_POST['crkdtoko'])){
              $xada=strpos($_POST['crkdtoko'],"like");
			  if ($xada <> false){
                $pecah=explode('like', $_POST['crkdtoko']);
			    $kunci3=$pecah[0];
			    $kunci4=$pecah[1];
			    $crkdtoko=$kunci3." like '%".trim($kunci4)."%'";
			  }else{$crkdtoko="";}
            }else{$crkdtoko="";}

            // echo '$params='.$params.'<br>';
            // echo '$crkdtoko='.$crkdtoko.'<br>';

			if ($params=="" && $crkdtoko=="")  
			{	 
			    $sql =mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_bar,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.jml_brg,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko,toko.nm_toko
				    FROM mas_brg 
					LEFT JOIN toko ON mas_brg.kd_toko=toko.kd_toko  
				    ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
				$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg");
			}
			if ($params=="" && $crkdtoko <> ""){
			    $sql =mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_bar,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.jml_brg,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko,toko.nm_toko
				    FROM mas_brg 
				    LEFT JOIN toko ON mas_brg.kd_toko=toko.kd_toko
					WHERE $crkdtoko ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");  		
			    $sql2 =mysqli_query($connect, "SELECT COUNT(*) AS jumlah
				    FROM mas_brg 
				    LEFT JOIN toko ON mas_brg.kd_toko=toko.kd_toko
					WHERE $crkdtoko ");
			}
			if ($params<>"" && $crkdtoko == ""){
			    $sql =mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_bar,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.jml_brg,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko,toko.nm_toko
				    FROM mas_brg 
				    LEFT JOIN toko ON mas_brg.kd_toko=toko.kd_toko
					WHERE $params ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");  		
			    $sql2 =mysqli_query($connect, "SELECT COUNT(*) AS jumlah
				    FROM mas_brg 
				    LEFT JOIN toko ON mas_brg.kd_toko=toko.kd_toko
					WHERE $params ");
			}	
            if ($params<>"" && $crkdtoko <> ""){
			    $sql =mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_bar,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.jml_brg,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko,toko.nm_toko
				    FROM mas_brg 
				    LEFT JOIN toko ON mas_brg.kd_toko=toko.kd_toko
					WHERE $params and $crkdtoko ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");  		
			    $sql2 =mysqli_query($connect, "SELECT COUNT(*) AS jumlah
				    FROM mas_brg 
				    LEFT JOIN toko ON mas_brg.kd_toko=toko.kd_toko
					WHERE $params and $crkdtoko ");
			}

            $get_jumlah = mysqli_fetch_array($sql2);  		    
		}else
		   { // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
			$sql = mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_bar,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.jml_brg,mas_brg.brg_msk,mas_brg.brg_klr
				FROM mas_brg 
					WHERE mas_brg.kd_toko='$kd_toko' ORDER BY mas_brg.no_urut ASC LIMIT $limit_start, $limit");
			$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg WHERE kd_toko='$kd_toko' ");
			$get_jumlah = mysqli_fetch_array($sql2);
		}

		$no=$limit_start;$tot=0;$no_fak='';$tgl_fak='';$disc1=0;$disc2=0;$jmlsub=0;
		$kd_sup='';$nm_sup='';$brg_msk_hi=0.00;$brg_klr_hi=0;
		while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
			$no++;
	        $brg_msk_hi=$data['jml_brg'];
	        //echo '$brg_msk='.$brg_msk_hi.'<br>';
	        if ($data['jum_kem1']>0) {
	          $stok1=round($brg_msk_hi/$data['jum_kem1'],2).' '.$data['nm_kem1'];	
	        }else{
	          $stok1='NONE';
	        }
	        if ($data['jum_kem2']>0) {
	          $stok2=round($brg_msk_hi/$data['jum_kem2'],2).' '.$data['nm_kem2'];  
	        }else{
	          $stok2='NONE';
	        }
	        if ($data['jum_kem3']>0) {
	          $stok3=round($brg_msk_hi/$data['jum_kem3'],2).' '.$data['nm_kem3'];  	
	        }else{
	          $stok3='NONE';
	        }
	        $kd_tokomut= mysqli_real_escape_string($connect, $data['kd_toko']);
	        $kd_brgmut= mysqli_real_escape_string($connect, $data['kd_brg']);
	        $nm_brgmut= mysqli_real_escape_string($connect, $data['nm_brg']);
	        $jum_mut1=mysqli_real_escape_string($connect, $data['hrg_jum1']);
	        $jum_mut2=mysqli_real_escape_string($connect, $data['hrg_jum2']);
	        $jum_mut3=mysqli_real_escape_string($connect, $data['hrg_jum3']);
	        ?> 

		  <tr>
			<td align="right"><?php echo $no ?></td>
			<td align="left"><?php echo $data['nm_toko']; ?></td>
			<td align="left"><?php echo $data['kd_brg']; ?></td>
			<td align="left"><?php echo $data['nm_brg']; ?></td>	
			<td align="right" class="yz-theme-l2"><?php echo $stok1.' -@ '.gantiti($data['hrg_jum1']); ?></td>
			<td align="right" class="yz-theme-l3"><?php echo $stok2.' -@ '.gantiti($data['hrg_jum2']); ?></td>
			<td align="right" class="yz-theme-l4"><?php echo $stok3.' -@ '.gantiti($data['hrg_jum3']); ?></td>
			<td>
			  <button class="btn-warning fa fa-edit" style="cursor: pointer;font-size: 12pt" title="Mutasi Barang Stok" onclick="
			    if (<?=$kd_toko!=$data['kd_toko']?>){
			      document.getElementById('form-mutasi-stok').style.display='block';
			      document.getElementById('kd_tokomut').innerHTML='<?=$data['kd_toko']?>';
			      document.getElementById('kd_brgmut').innerHTML='<?=$data['kd_brg']?>';
			      document.getElementById('nm_brgmut').innerHTML='<?=$data['nm_brg']?>';
			      document.getElementById('stok1mut').innerHTML=' <?=$stok1.' -@ '.gantiti($data['hrg_jum1'])?>';
			      document.getElementById('stok2mut').innerHTML=' <?=$stok2.' -@ '.gantiti($data['hrg_jum2'])?>';
			      document.getElementById('stok3mut').innerHTML=' <?=$stok3.' -@ '.gantiti($data['hrg_jum3'])?>';
			      carisatuan('<?=$data['kd_toko']?>','<?=$data['kd_brg']?>'); 
			    }">
			  </button>
			</td>
			<td><button class="btn-primary fa fa-edit" style="cursor: pointer;font-size: 12pt" title="Mutasi Barang Jual"></button></td>
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
      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="liststok(1, false)">First</a></li>
      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="liststok(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="liststok(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="liststok(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="liststok(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
    <?php
    }
    ?>
  </ul>
</nav>

<!-- form-mutasi stok -->
<div id="form-mutasi-stok" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge; ">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:900px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;background: linear-gradient(180deg, #FAFAD2 10%, white 90%)">
    <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;">&nbsp;<i class="fa fa-search"></i>
      MUTASI BARANG STOK
    </div>

    <div class="w3-center">
      <span onclick="document.getElementById('form-mutasi-stok').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
    </div>
    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;background: linear-gradient(180deg, #FAFAD2 10%, white 90%) ">
      <thead >
  	    <tr align="middle" class="yz-theme-l1">
  		  <!-- <th style="width: 1%">No.</th> -->
  		  <th>Kode Toko</th>
  		  <th>Kode Brg</th>
  		  <th>Nama Brg</th>
  		  <th colspan="3">Konversi Stok & Hrg Jual</th>
        </tr>
      </thead>
      <tr>
        <td id="kd_tokomut"></td>
        <td id="kd_brgmut"></td>
        <td id="nm_brgmut"></td>
        <td align="middle" id="stok1mut"></td>
        <td align="middle" id="stok2mut"></td>
        <td align="middle" id="stok3mut"></td>
      </tr>
    </table>
    
    <!-- Input data -->
    <div class="row">
		<div class="col-sm-4 offset-sm-4 text-center"> 
		 <form id="form-input_brg" class="w3-row w3-padding" action="f_mutgudang_stok_act.php" method="post" style="padding-right: 10px;padding-left: 10px;border-style: ridge;border-color: white;background: linear-gradient(565deg, #FAFAD2 30%, white 100%)">
		  <!-- <div class="input-group">
		    <input type="text" name="kd_sat" class="form-control" placeholder="Satuan Barang">
		    <span>
		      <button class="yz-theme-l4 w3-hover-shadow form-control" type="button" style="height: 34px" onclick="carisatuan(document.getElementById('kd_brgmut').value,document.getElementById('kd_tokomut').value)"><i class="fa fa-caret-down"></i></button>
		    </span>	
		    <div id="viewsat"></div>
		  </div>	 -->
          <div id="viewsat"></div>
		  <br>
		  <input id="qty_brg" type="number" step="0.01" name="qty_brg" class="form-control" placeholder="Jumlah Barang"  style="text-align: center">	
		  <div id="viewmaxjum"></div>
		  <br>
		  <div class="row">
		  	<div class="col"><button type="submit" class="btn btn-primary form-control w3-hover-shadow w3-margin-bottom" style="padding: 2px"><i class="fa fa-save"></i>&nbsp;Proses</button></div>
		  	<div class="col"><button type="button" class="btn btn-warning form-control w3-hover-shadow" style="padding: 2px" onclick="document.getElementById('form-mutasi-stok').style.display='none'"><i class="fa fa-undo"></i>&nbsp;Batal</button></div>
		  </div>
	     </form> 	  
		</div>	
	</div>
    
     
  </div><!--Modal content-->
</div>

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