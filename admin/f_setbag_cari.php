<?php
	$keyword1 = $_POST['keyword1'];
	$keyword2 = $_POST['keyword2'];
	ob_start();
	session_start();
	include 'config.php';
	
    $connect     = opendtcek();  
	$kd_toko     = $_SESSION['id_toko'];
	$page        = (isset($_POST['page']))? $_POST['page'] : 1;
	$limit       = 15; 
	$limit_start = ($page - 1) * $limit;
    $kunci       = $kunci2=$key1=$key2=$no_fak=$tgl_fak='';	
	$stok1=0;$stok2=0;$stok3=0;		
	$no          = $limit_start;
	
	if(isset($_POST['search']) && $_POST['search'] == true){ 
		$key1 = mysqli_real_escape_string($connect, trim($keyword1));
		if(!empty($key1)){
			$xada=strpos($key1,"like");
			$pecah=explode('like', $key1);
			$kunci=$pecah[0];
			$kunci2=$pecah[1];
			$key1="AND ".$kunci." like '%".trim($kunci2)."%'";
		}
		if(!empty($keyword2)){
			$key2="AND beli_brg.id_bag="."$keyword2";
		}
		
		$sql =mysqli_query($connect, "SELECT beli_brg.kd_brg,beli_brg.kd_toko,mas_brg.nm_brg,bag_brg.nm_bag FROM beli_brg 
			LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
			LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut
			WHERE beli_brg.kd_toko='$kd_toko' $key1 $key2
			GROUP BY beli_brg.kd_brg
			ORDER BY bag_brg.nm_bag,mas_brg.nm_brg ASC
			LIMIT $limit_start,$limit ");

		$sql2=mysqli_query($connect,"SELECT count(*) AS jumlah FROM (SELECT COUNT(*) FROM beli_brg 
		    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
			LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut
		WHERE beli_brg.kd_toko='$kd_toko' $key1 $key2 GROUP BY beli_brg.kd_brg)  jumlah"); 

		$get_jumlah = mysqli_fetch_array($sql2);  		    
	}
	$jmlup   = $get_jumlah['jumlah'];
	$limitup = $limit_start+$limit;
	$pageup  = ceil($limitup/$limit);
	if($pageup==0){$pageup=1;}
?>
<style>
	input.largerCheckbox {
	    width: 18px;
	    height: 18px;
	}
	td {border-left:none;border-right:none;border-color:black;}
</style>

<div style="overflow:auto;border-style: ridge;border-color:white;max-height:600px;min-height:100px">
	<table id="tab1" class="table-hover hrf_res2" style="width: 100%;border-collapse: collapse;white-space: nowrap;">
			<tr align="middle" class="yz-theme-l1">
				<th style="width: 1%">No.</th>
				<th width="15%" style="position: relative;">BAGIAN &nbsp;<button type="button" 
  					id="btn-bag" class="btn fa fa-search w3-hover-shadow" style="padding:5px"></button> 
                    <div id="box-bag" class="w3-card" style="display:none;position:absolute;width:100%;border-radius:10px;background-color:white;margin-top:7px">
					</div>
					<script>
					$(document).ready(function(){
						$("#btn-bag").click(function(){
							listpilbag(1);
							$("#box-bag").slideToggle("fast");
							$("#boxbrgs").slideUp("fast");
						});
					});
				</script>  
				</th>
				<th id="carinmbrgs" style="width: 23%">NAMA BARANG &nbsp;<button type="button" 
				    id="btn-brgs" class="btn fa fa-search w3-hover-shadow" style="padding:5px"></button> 
					<div id="boxbrgs" style="display:none;position: absolute;">
						<div class="input-group w3-card-4" style="width: 270px;margin-top: 26px">
							<input type="text" class="yz-theme-l4 form-control hrf_res2" id="nm_brgs" name="nm_brgs" style="border:1px solid black;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Nama Barang" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value=cr_nmbrg(this.value);listbag(1,true);}">
							<div class="input-group-append">
								<button class="btn yz-theme-d2 hrf_res2" onclick="
								document.getElementById('kd_cari').value=cr_nmbrg(document.getElementById('nm_brgs').value);listbag(1,true);
								" style="border:1px solid black;"><i class="fa fa-search" style="cursor: pointer"></i></button>
								<button class="btn btn-warning hrf_res2" onclick="
								document.getElementById('kd_cari').value='';listbag(1,true);
								" style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>  
							</div>		
						</div>	
					</div>       
				</th>	
				<script>
					$(document).ready(function(){
						$("#btn-brgs").click(function(){
						$("#boxbrgs").slideToggle("fast");
						$("#box-bag").slideUp("fast");
						$("#nm_brgs").focus();
						});
					});
				</script>  
				<th width="1%">SET</th>
			</tr>
		
   	    <?php
        if (mysqli_num_rows($sql)<=1){
           ?>
           <tr><td>&nbsp;</td></tr>
           <tr><td>&nbsp;</td></tr>
           <?php
        } else {
            while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
                $no++;
                $kd_brg=$data['kd_brg']; ?>
              <tr>
                <td align="right" ><?php echo $no ?></td>
                <td style="display:none"><input type="text" id="<?=$no.'kd_brg'?>" value="<?=$data['kd_brg']?>"></td>
                <td align="middle" id="<?='nm_bag'.$no?>" style="border-right: none"><?=$data['nm_bag']?></td>
                <td align="left" id="<?='nm_brg'.$no?>"  style="border-right: none"><?=$data['nm_brg']?></td>
			    <td style="text-align:center;"><input type="checkbox" class="largerCheckbox form-control" id="<?=$no.'cek'?>" name="<?=$no.'cek'?>" value="1" style="accent-color: #e74c3c;">
                </td>   
              </tr>
		
            <?php
            }
        }    
            ?>

	  <script type="text/javascript">var limit=Number('<?=$limit_start?>') + 1; </script>
	  <tr align="right" class="yz-theme-d1" style="position:sticky;bottom:0">
		<td colspan="2" class="w3-center" style="padding: 3px"><b>Total Item Barang : <?=$no?></b></td>
		<td align="center">
            <div class="row w3-hide-small">
                <div class="col">
                <select id="pilbag" class="form-control hrf_res2"> 
                  <?php $sqd=mysqli_query($connect,"SELECT * FROM bag_brg ORDER BY nm_bag ASC");
                    while($dtc=mysqli_fetch_assoc($sqd)){ ?> 
                    <option value=<?=$dtc['no_urut']?> > <?=$dtc['nm_bag'];?></option>
                  <?php } ?>
                </select>
                </div>
                <div class="col">
                   <button class="btn btn-lg btn-warning form-control hrf_res2"
                     onclick="
                       for (let i = limit; i <= Number('<?=$no?>'); i++) { 
                          if(document.getElementById(i+'cek').checked==true){
                            setbagrep(document.getElementById(i+'kd_brg').value,document.getElementById('pilbag').value);
                          }
                        } 
						listbag(1,true);listbag('<?=$pageup?>',true); "
                   >Replace</button>     
                </div>
            </div>
        </td>
        
		<td class="w3-center" style="padding: 3px;">
		  <input type="checkbox" class="largerCheckbox mt-2 " id="cekall" name="cekall" value="1" onchange=" if (this.checked) { 
		  	 for (let i = limit; i <= Number('<?=$no?>'); i++) { 
		  	   document.getElementById(i+'cek').checked=true;

		  	 } 
		    } else {
             for (let i = limit; i <= Number('<?=$no?>'); i++) {
               document.getElementById(i+'cek').checked=false; 
             }
            }" 
		  style="accent-color:green;"> <label for="cekall">&nbsp;<b>All</b></label>	
		</td>
	  </tr>
	</table> 
	
	<nav class="hrf_res2" aria-label="Page navigation example" style="margin-top:15px;">
		<ul class="pagination justify-content-center">
			<!-- LINK FIRST AND PREV -->
			<?php
			if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
			?>
			<li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;">First</a></li>
			<li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop; padding-left:15px;padding-right:15px"><i class="fa fa-caret-left"></i></a></li>
			<?php
			}else{ // Jika page bukan page ke 1
			$link_prev = ($page > 1)? $page - 1 : 1;
			?>
			<li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="listbag(1, false)">First</a></li>
			<li><a class="page-link yz-theme-l1" style="cursor: pointer; padding-left:15px;padding-right:15px" href="javascript:void(0);" onclick="listbag(<?php echo $link_prev; ?>, false)"><i class="fa fa-caret-left"></i></a></li>
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
			
			for($ii = $start_number; $ii <= $end_number; $ii++){
			$link_active = ($page == $ii)? ' class="active"' : '';
			?>
			<li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="listbag(<?php echo $ii; ?>, false)"><?php echo $ii; ?></a></li>
			<?php
			}
			?>
			
			<!-- LINK NEXT AND LAST -->
			<?php
			if($page == $jumlah_page || $get_jumlah['jumlah']==0){
			//if($page == $jumlah_page || $jum==0){
			?>
			<li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop; padding-left:15px;padding-right:15px"><i class="fa fa-caret-right"></i></a></li>
			<li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
			<?php
			}else{ // Jika Bukan page terakhir
			$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
			?>
			<li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="listbag(<?php echo $link_next; ?>, false)" style="cursor: pointer; padding-left:15px;padding-right:15px"><i class="fa fa-caret-right"></i></a></li>
			<li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="listbag(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
			<?php
			}
			?>
		</ul>
	</nav>
	   
	<div class="row w3-container w3-margin-top w3-margin-bottom w3-hide-medium w3-hide-large">
	    <div class="col">
			<select id="pilbag2" class="form-control hrf_res2"> 
				<?php $sqd=mysqli_query($connect,"SELECT * FROM bag_brg ORDER BY nm_bag ASC");
				while($dtc=mysqli_fetch_assoc($sqd)){ ?> 
				<option value=<?=$dtc['no_urut']?> > <?=$dtc['nm_bag'];?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col">
		
			<button class="btn btn-lg btn-warning form-control hrf_res2"
				onclick="
				for (let i = limit; i <= Number('<?=$no?>'); i++) { 
					if(document.getElementById(i+'cek').checked==true){
					setbagrep(document.getElementById(i+'kd_brg').value,document.getElementById('pilbag2').value);
					}
				} 
				listbag(1,true);listbag('<?=$pageup?>',true); "
			>Replace</button>     
		</div>
	</div>
</div>


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