<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
    $blncr  = $_POST['blncr'];
    $thncr  = $_POST['thncr'];
	ob_start();
	include 'config.php';
	session_start();
    $connect=opendtcek();  
	$kd_toko=$_SESSION['id_toko'];
    
?>
<div style="overflow:auto;border-style: ridge;border-color:white">
  <table class="table-bordered table-hover" style="font-size:9pt;width: 100%;min-height: 100px;border-collapse: collapse;white-space: nowrap;">
    <thead>
  	  <tr align="middle" class="yz-theme-l3">
  		<th style="width: 1%">No.</th>
        <th style="width: 8%">TGL.BELI</th>
        <th style="width: 12%">FAKTUR</th>
        <th style="width: 15%">SUPPLIER</th>
  		<th style="width: 10%">BARCODE &nbsp;<button type="button" id="btn-kdbar" style="padding:3px" class="btn fa fa-search yz-hover-theme p-1"></button>
    	  	<div id="boxkdbar" style="display:none;position: absolute;z-index: 1;" >
		  		<!-- screen large -->
		  		<div class="input-group w3-card-4" style="width: 250px;margin-top: 26px;">
			       <input type="text" class="yz-theme-l4 form-control" id="kd_bar" name="kd_bar" style="border:1px solid black;font-size: 9pt;" placeholder="Barcode" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value='beli_brg.kd_bar = '+this.value;listexp(1,true);}">
			       <div class="input-group-append ">
			       	<button class="btn yz-theme-d1" onclick="
			          document.getElementById('kd_cari').value='mas_brg.kd_bar = '+document.getElementById('kd_bar').value;listexp(1,true);
			          " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i>
			        </button>
					<button class="btn btn-warning" onclick="
			          document.getElementById('kd_cari').value='';listexp(1,true);
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
                $("#kd_bar").focus();
              });
            });
          </script>
  		<th id="carinmbrg">NAMA BARANG &nbsp;<button type="button" id="btn-brg" class="btn fa fa-search yz-hover-theme" style="padding:3px"></button> 
    	  	<div id="boxbrg" style="display:none;position: absolute;z-index: 1;">
		  		<div class="input-group w3-card-4" style="width: 250px;margin-top: 26px">
			       <input type="text" class="yz-theme-l4 form-control" id="nm_brg" name="nm_brg" style="border:1px solid black;font-size: 9pt;" placeholder="NM.BARANG" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value=cr_nmbrg(this.value);listexp(1,true);}">
			       <div class="input-group-append">
			       	 <button class="btn yz-theme-d1" onclick="
			           document.getElementById('kd_cari').value=cr_nmbrg(document.getElementById('nm_brg').value);listexp(1,true);
			           " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i></button>
					  <button class="btn btn-warning" onclick="
			           document.getElementById('kd_cari').value='';listexp(1,true);
			           " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button> 
			       </div>		
			    </div>	
			</div>       
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-brg").click(function(){
                $("#boxkdbar").slideUp("fast");
                $("#boxbrg").slideToggle("fast");
                $("#nm_brg").focus();
              });
            });
          </script>  
        <th style="width:5%">STOK</th>  
        <th style="width:8%">EXPIRED</th>
        <th width="2%">NOTE</th>		
  	  </tr>
    </thead> <?php
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
			  }	else{
                $pecah=explode('=',$params);
                $kunci=$pecah[0];
			    $kunci2=$pecah[1];
                $params=$kunci." = '".trim($kunci2)."'";
              }
		  	}else{
			  $kunci='';
			  $kunci2='';	
			}
			
			if ($params=="") 
			{	 
                $sql =mysqli_query($connect, "SELECT beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,beli_brg.kd_bar,beli_brg.kd_brg,beli_brg.stok_jual,beli_brg.expdate,supplier.nm_sup,mas_brg.nm_brg FROM beli_brg
                LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
                LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
                WHERE MONTH(beli_brg.expdate)<='$blncr' AND YEAR(beli_brg.expdate)<='$thncr' AND beli_brg.expdate<>'0000-00-00' AND beli_brg.stok_jual>0 AND beli_brg.kd_toko='$kd_toko'
                ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");

				$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE MONTH(beli_brg.expdate)<='$blncr' AND YEAR(beli_brg.expdate)<='$thncr' AND beli_brg.expdate<>'0000-00-00' AND beli_brg.stok_jual>0 AND beli_brg.kd_toko='$kd_toko'");		
			}
			else 
			{
				//echo $params;
                $sql =mysqli_query($connect, "SELECT beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,beli_brg.kd_bar,beli_brg.kd_brg,beli_brg.stok_jual,beli_brg.expdate,supplier.nm_sup,mas_brg.nm_brg FROM beli_brg
                LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
                LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
                WHERE $params AND MONTH(beli_brg.expdate)<='$blncr' AND YEAR(beli_brg.expdate)<='$thncr' AND beli_brg.expdate<>'0000-00-00' AND beli_brg.stok_jual>0 AND beli_brg.kd_toko='$kd_toko'
                ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
                
				$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah,mas_brg.nm_brg FROM beli_brg LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg  WHERE $params AND MONTH(beli_brg.expdate)<='$blncr' AND YEAR(beli_brg.expdate)<='$thncr' AND beli_brg.expdate<>'0000-00-00' AND beli_brg.stok_jual>0 AND beli_brg.kd_toko='$kd_toko'");
			}	
            $get_jumlah = mysqli_fetch_array($sql2);  		    
		}
		$no=$limit_start;$no_fak='';$tgl_fak='';$disc1=0;$disc2=0;$jmlsub=0;
		$kd_sup='';$nm_sup='';$cekada=0;
		$stok1=0;
		while($data = mysqli_fetch_array($sql)){
			$no++;
	        $stok1=gantitides($data['stok_jual']);	
	        $cekada=carinote($data['kd_brg'],$kd_toko,$connect); ?> 
            
		  <tr>
			<td align="right"><?php echo $no ?></td>
            <td align="center" style="border-right: none"><?php echo gantitgl($data['tgl_fak']); ?></td>
            <td align="left" style="border-right: none">&nbsp;<?php echo $data['no_fak']; ?></td>
            <td align="left" style="border-right: none">&nbsp;<?php echo $data['nm_sup']; ?></td>
            <td align="center" style="border-right: none"><?php echo $data['kd_bar']; ?></td>
			<td align="left" style="border-right: none;">&nbsp;<?php echo $data['nm_brg']; ?></td>
            <td align="right"><?php echo $stok1;?>&nbsp;</td>
            <td align="center" style="border-right: none;"><?php echo gantitgl($data['expdate']); ?></td>
			 <?php
			if ($cekada>0){ ?>
			   <td style="text-align: center;"><button class="btn-warning fa fa-warning form-control" style="cursor: pointer;padding: 4px;" onclick="document.getElementById('keybrgmsk').value='<?=$data['kd_brg']?>';document.getElementById('form-note').style.display='block';document.getElementById('viewjust').style.display='block';infojust(1,true);infostok(1,true)"></button></td> <?php  
            } else { ?>
	           <td style="text-align: center;"><button class=" form-control fa fa-warning" style="cursor: pointer;padding: 4px;color: blue;background-color: lightblue" onclick="document.getElementById('keybrgmsk').value='<?=$data['kd_brg']?>';document.getElementById('form-note').style.display='block';document.getElementById('viewjust').style.display='none';infostok(1,true);infojust(1,true);"></button></td> <?php  
            } ?> 
		  </tr>	<?php
		} ?>
	  <tr align="right" class="yz-theme-l3">
		<td colspan="10" class="w3-center" style="padding: 5px"><b>Total Item Barang : <?=$no?></b></td>
		<!-- <th ><?=gantiti($no) ?></th> -->
	  </tr>
	  
  </table> 
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="listexp(1, false)">First</a></li>
		      <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="listexp(<?php echo $link_prev; ?>, false)"></a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" onclick="listexp(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
		      <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="listexp(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="listexp(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>	
	  	</div>
	  	
	  </div>	
		  
	  
	</nav>

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
	 $cek=mysqli_query($hub,"SELECT SUM(stok_jual) AS jml FROM beli_brg_jml where kd_brg='$kd_brg' and kd_toko='$kd_toko' order by no_urut ASC");
	  $data=mysqli_fetch_assoc($cek);
	  $jml_brg=$data['jml'];
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
  mysqli_close($connect);
	$html = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>