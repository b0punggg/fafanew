
<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
	session_start();
	include 'config.php';
	
    $connect=opendtcek();  
	$kd_toko=$_SESSION['id_toko'];
?>
<style>
	input.largerCheckbox {
	    width: 18px;
	    height: 18px;
	}
</style>

<div style="overflow:auto;border-style: ridge;border-color:white;">
	<table class="table-bordered table-hover" style="font-size:9pt;width: 100%;height: 400px;border-collapse: collapse;white-space: nowrap;">
    <thead>
  	  <tr align="middle" class="yz-theme-l2">
  		<th style="width: 1%">No.</th>
  		
  		<th style="width: 10%">BARCODE &nbsp;		
		  <span id="btn-kdbar" class="fa-stack fa-lg" style="cursor:pointer">
            <i class="fa fa-square-o fa-stack-2x text-primary"></i>
            <i class="fa fa-arrow-circle-down fa-stack-1x" style="margin-top:-2px"></i>
          </span>
          <div class="row">
            <div class="col">
              <div id="boxkdbar" style="display:none;position: absolute;z-index: 1;" >
		  		<div class="input-group w3-card-4" style="width: 300px;height:45px;;margin-top: 26px">
			       <input type="text" class="yz-theme-l4 form-control" id="kd_bar" name="kd_bar" style="border:1px solid black;font-size: 9pt;" placeholder="BARCODE" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value='mas_brg.kd_bar = '+this.value;listbrg(1,true);}">
			       	<button class="btn yz-theme-d2 yz-hover-theme" onclick="
			          document.getElementById('kd_cari').value='mas_brg.kd_bar = '+document.getElementById('kd_bar').value;listbrg(1,true);
			          " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i>
			        </button>
					<button class="btn btn-warning" onclick="
			          document.getElementById('kd_cari').value='';listbrg(1,true);
			          " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
			        </button>
			       
			    </div>	
              </div>
            </div>
          </div>  	
        </th>	
          <script>
            $(document).ready(function(){
              $("#btn-kdbar").click(function(){
                $("#boxbrg").slideUp("fast");
                $("#boxkdbar").slideToggle("fast");
                $("#kd_brg").focus();
              });
            });
          </script>
  		<th id="carinmbrg" style="width: 23%">NAMA BARANG &nbsp;
		  <span id="btn-brg" class="fa-stack fa-lg" style="cursor:pointer">
            <i class="fa fa-square-o fa-stack-2x text-primary"></i>
            <i class="fa fa-arrow-circle-down fa-stack-1x" style="margin-top:-2px"></i>
          </span>
			<div id="boxbrg" style="display:none;position: absolute;z-index: 1;">
		  		<div class="input-group w3-card-4" style="width: 300px;height:45px;;margin-top: 26px">
			       <input type="text" class="yz-theme-l4 form-control" id="nm_brg" name="nm_brg" style="border:1px solid black;font-size: 9pt;" placeholder="NM.BARANG" onkeypress="if(event.keyCode==13){document.getElementById('kd_cari').value=cr_nmbrg(this.value);listbrg(1,true);}">
			       	 <button class="btn yz-theme-d2" onclick="
			           document.getElementById('kd_cari').value=cr_nmbrg(document.getElementById('nm_brg').value);listbrg(1,true);
			           " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i></button>
					 <button class="btn btn-warning" onclick="
			           document.getElementById('kd_cari').value='';listbrg(1,true);
			           " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>  
			      
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
        <th colspan="3" style="width: 15%">KONVERSI STOK BARANG & HARGA JUAL</th>  
      <th width="2%">CETAK</th>
      <th style="width: 1%">COPIES</th>		
  	</tr>
  </thead>

   	<?php
		$kd_toko=$_SESSION['id_toko'];
		$page = (isset($_POST['page']))? $_POST['page'] : 1;
		$limit = 10; // Jumlah data per halamannya
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
			  }else {
				$pecah=explode('=', $params);
			    $kunci=$pecah[0];
			    $kunci2=$pecah[1];
			    $params=$kunci."='".trim($kunci2)."'";
			  }	
		  	  
			}else{
			  $kunci='';
			  $kunci2='';	
			}
			//echo "$params";
			if ($params=="") 
			{	 
				$sql =mysqli_query($connect, "SELECT mas_brg.jml_brg,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.cetak,mas_brg.pilih,mas_brg.kd_bar,mas_brg.copy FROM mas_brg
			    	ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
				$sql2=mysqli_query($connect,"SELECT count(*) AS jumlah FROM mas_brg"); 
			}
			else 
			{
				$sql =mysqli_query($connect, "SELECT mas_brg.jml_brg,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.cetak,mas_brg.pilih,mas_brg.kd_bar,mas_brg.copy 
				FROM mas_brg
				WHERE $params
			    ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
				$sql2=mysqli_query($connect,"SELECT count(*) AS jumlah FROM mas_brg WHERE $params"); 
			}	
            $get_jumlah = mysqli_fetch_array($sql2);  		    
		}
		$no=$limit_start;$no_fak='';$tgl_fak='';
		$stok1=0;$stok2=0;$stok3=0;
		while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
			$no++;
			$kd_brg=$data['kd_brg'];
	        if (!empty($data['ukuran'])){
		        $ukuran=', U-'.mysqli_escape_string($connect,$data['ukuran']);
		    } else { $ukuran=''; }
		    if (!empty($data['warna'])){
		        $warna=', W-'.mysqli_escape_string($connect,$data['warna']);
		    } else { $warna=''; }

		    $brg_msk_hi=$data['jml_brg'];
	        //echo '$brg_msk='.$brg_msk_hi.'<br>';
	        if ($data['jum_kem1']>0) {
	          $stok1= str_replace(".",",",round($brg_msk_hi/$data['jum_kem1'],2)).' '.$data['nm_kem1'];	
	        }else{
	          $stok1='NONE';
	        }
	        if ($data['jum_kem2']>0) {
	          $stok2=str_replace(".",",",round($brg_msk_hi/$data['jum_kem2'],2)).' '.$data['nm_kem2'];  
	        }else{
	          $stok2='NONE';
	        }
	        if ($data['jum_kem3']>0) {
	          $stok3=str_replace(".",",",round($brg_msk_hi/$data['jum_kem3'],0)).' '.$data['nm_kem3'];  	
	        }else{
	          $stok3='NONE';
	        } ?> 
            
		  <tr>
			<td align="right" ><?php echo $no ?></td>
			<td align="left" style="border-right: none" id="<?='kd_bar'.$no?>"><?php echo $data['kd_bar']; ?></td>
			<td align="left" style="border-right: none;display:none"><input id="<?='kd_brg'.$no?>" type="text" value="<?=$data['kd_brg']?>" style="width: 200px;border:none;background:transparent;" readonly></td>
			<td align="left" style="border-right: none;border-left: none"><?php echo $data['nm_brg'].$ukuran.''.$warna?></td>
            <td align="right" class="yz-theme-l2"><?php echo $stok1.' -@ '.gantitides($data['hrg_jum1']);?>&nbsp;</td>
			<td align="right" class="yz-theme-l3"><?php echo $stok2.' -@ '.gantitides($data['hrg_jum2']); ?>&nbsp;</td>
			<td align="right" class="yz-theme-l4"><?php echo $stok3.' -@ '.gantitides($data['hrg_jum3']); ?>&nbsp;</td>
			    <?php 
			    if ($data['cetak']=="1"){ ?>
			        <td style="text-align: center;"><input type="checkbox" class="largerCheckbox" id="<?=$no.'cek'?>" name="<?=$no.'cek'?>" value="1" style="accent-color: #e74c3c;" onchange="
	                    if (this.checked){
				           cekkdbaron('<?=$kd_brg?>');
						   document.getElementById('<?='copies'.$no?>').value='2';
						   cekkdbarcop('<?=$kd_brg.';'?>'+document.getElementById('<?='copies'.$no?>').value) 
						} else { 
						   cekkdbaroff('<?=$kd_brg?>');document.getElementById('<?='copies'.$no?>').value='0'; cekkdbarcop('<?=$kd_brg.';'?>'+document.getElementById('<?='copies'.$no?>').value) 
						}">
			        </td> <?php  
                    if ($data['pilih']==1){ ?> 
                        <script> document.getElementById('<?=$no?>'+'cek').checked=true; </script> <?php 
					} else { ?>
                        <script> document.getElementById('<?=$no?>'+'cek').checked=false; </script> <?php 
					}?>	
	                <td>
                       <input type="number" id="<?='copies'.$no?>" name="copies" min="2" value="<?=$data['copy']?>" onchange="cekkdbarcop('<?=$kd_brg.';'?>'+this.value)" class="yz-theme-l3" style="width: 60px;padding: 1px;">	
                    </td><?php  
				} else { ?>
	                <td style="text-align: center;"><input type="checkbox" class="largerCheckbox" id="<?=$no.'cek'?>" name="<?=$no.'cek'?>" value="1" style="accent-color: green;" onchange="
	                	if (this.checked){
					    	cekkdbaron('<?=$kd_brg?>');
							document.getElementById('<?='copies'.$no?>').value='2'; 
							cekkdbarcop('<?=$kd_brg.';'?>'+document.getElementById('<?='copies'.$no?>').value) 
						} else { 
					    	cekkdbaroff('<?=$kd_brg?>');
							document.getElementById('<?='copies'.$no?>').value='0';
							cekkdbarcop('<?=$kd_brg.';'?>'+document.getElementById('<?='copies'.$no?>').value) 
						}">
	                </td> <?php  
						if ($data['pilih']==1){ ?> 
							<script> document.getElementById('<?=$no?>'+'cek').checked=true; </script> <?php 
						} else { ?>
							<script> document.getElementById('<?=$no?>'+'cek').checked=false; </script>	<?php 
						}?>	
	                <td>
                        <input type="number" id="<?='copies'.$no?>" name="copies" min="1" onchange="cekkdbarcop('<?=$kd_brg.';'?>'+this.value)" value="<?=$data['copy']?>" style="width: 60px;padding: 3px">	
                   </td> <?php 
	        } ?> 
		  </tr> <?php
		} ?>
	  <script type="text/javascript">var limit=Number('<?=$limit_start?>') + 1; </script>
	  <tr align="right" class="yz-theme-d1">
		<td colspan="5" class="w3-center" style="padding: 3px"><b>Total Item Barang : <?=$no?></b></td>
		<td align="center"><a href="f_cetbarcode-res.php" class="yz-theme-l3 btn" style="font-size: 10pt;">Reset Pilih</a></td>
		<td colspan="2" class="w3-center" style="padding: 3px;">
		  <input type="checkbox" class="largerCheckbox " id="cekall" name="cekall" value="1" onchange=" 
		  if (this.checked) { 
		  	 for (let i = limit; i <= Number('<?=$no?>'); i++) { 
		  	   document.getElementById(i+'cek').checked=true;
		  	   cekkdbaron(document.getElementById('kd_brg'+i).value);
		  	   document.getElementById('copies'+i).value='1'; 
		  	   cekkdbarcop(document.getElementById('kd_brg'+i).value+';'+document.getElementById('copies'+i).value); 
		     } 
		  } else {for (let i = limit; i <= Number('<?=$no?>'); i++) { document.getElementById(i+'cek').checked=false;cekkdbaroff(document.getElementById('kd_brg'+i).value);document.getElementById('copies'+i).value='0'; cekkdbarcop(document.getElementById('kd_brg'+i).value+';'+document.getElementById('copies'+i).value) } }" 
		  style="accent-color:green;"> <label for="cekall">&nbsp;<b>Pilih Semua</b></label>	
		</td>
	  </tr>
	</table> 
</div>
<a href="" id="linkcetak" target="_blank"></a>
<div class="row">
  <div class="col-sm w3-container">
  	<nav aria-label="Page navigation example" style="margin-top:6px;font-size: 10pt">
		<ul class="pagination pagination-sm justify-content-end">
		    <!-- LINK FIRST AND PREV --><?php
			if($page == 1){ ?>
				<li class="page-item disabled "><a class="page-link  yz-theme-dark" href="javascript:void(0)" style="cursor: no-drop;padding : 3px 13px 3px 13px">Awal</a></li>&nbsp;
				<li class="page-item disabled "><a class="page-link fa fa-chevron-left yz-theme-dark" href="javascript:void(0)" style="cursor: no-drop;padding : 3px 15px 3px 15px;border-radius:4px"></a></li>&nbsp;	<?php
			}else{ // Jika page bukan page ke 1
				$link_prev = ($page > 1)? $page - 1 : 1; ?>
				<li><a class="page-link yz-theme-d1" style="cursor: pointer;padding : 3px 13px 3px 13px" href="javascript:void(0);" onclick="listbrg(1, false)">Awal</a></li>&nbsp;
				<li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;padding : 3px 15px 3px 15px;border-radius:4px" href="javascript:void(0);" onclick="listbrg(<?php echo $link_prev; ?>, false)"></a></li>&nbsp; <?php
			}
			// LINK NUMBER
			$jumlah_page = ceil($get_jumlah['jumlah'] / $limit);
			$jumlah_number = 1; 
			$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1;
			$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page;
	
			for($i = $start_number; $i <= $end_number; $i++){
				$link_active = ($page == $i)? ' class="active"' : '';?>
				<li class="page-item" <?php echo $link_active; ?>><a class="page-link w3-hover-shadow w3-border-blue" href="javascript:void(0);" style="cursor: pointer;padding : 3px 13px 3px 13px;border-radius:5px" onclick="listbrg(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>&nbsp; <?php
			}?>
	
			<!-- LINK NEXT AND LAST -->	<?php
			if($page == $jumlah_page || $get_jumlah['jumlah']==0){ ?>
				<li class="page-item disabled " ><a class="page-link fa fa-chevron-right  yz-theme-dark" href="javascript:void(0)" style="cursor: no-drop;padding : 3px 15px 3px 15px;border-radius:4px"></a></li>&nbsp;
				<li class="page-item disabled "><a class="page-link yz-theme-dark" href="javascript:void(0)" style="cursor: no-drop;padding : 3px 13px 3px 13px">Akhir</a></li>&nbsp;	<?php
			}else{ // Jika Bukan page terakhir
				$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page; ?>
				<li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="listbrg(<?php echo $link_next; ?>, false)" style="cursor: pointer;padding : 3px 15px 3px 15px;border-radius:4px"></a></li>&nbsp;
				<li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="listbrg(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;padding : 3px 13px 3px 13px">Akhir</a></li>&nbsp; <?php
			} ?>
		</ul>	
	</nav>
  </div>
  <div class="col-sm w3-container">
	<button class="btn btn-sm btn-warning" onclick="document.getElementById('pilkertas').style.display='block'"><i class="fa fa-print"></i>&nbsp;Ukuran</button>   

	<a href="#" id="tmb-qr" class="btn btn-sm" title="Cetak QR Code" onclick="docek(1)"><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-qrcode fa-stack-1x w3-text-black"></i></span></a> 

	<a href="#" id="tmb-bar" class="btn btn-sm" title="Cetak Bar Code" onclick="docek(2)"><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-barcode fa-stack-1x w3-text-black"></i></span></a> 
	 
  </div>	
</div>
<div id="pilkertas" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
	<div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:200px ">
		<div class="yz-theme-d1" style="font-size: 14px;padding:4px">
			&nbsp; <i class="fa fa-print"></i>&nbsp;Ukuran Kertas
			<span onclick="document.getElementById('pilkertas').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
		</div>
		<form id="pilihprint" action="f_cetbarcodepilih.php" method="post">
			<div class="vstack gap-2 col-md-8 mx-auto p-2">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="c_kertas" id="c_kertas1" value='A4' checked>
					<label class="form-check-label" for="c_kertas1">
						Kertas A4
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="c_kertas" id="c_kertas2" value='58'>
					<label class="form-check-label" for="c_kertas2">
						Kertas 58mm
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="c_kertas" id="c_kertas3" value='80'>
					<label class="form-check-label" for="c_kertas3">
						Kertas 80mm
					</label>
				</div>
				<div>
					<button class="btn btn-sm btn-primary form-control mt-3" type="submit" >PILIH</button>
				</div>
			</div>	
		</form>
	</div>
</div>		
<script>
	function cr_nmbrg(nmbrg) {
		nmbrg="mas_brg.nm_brg like "+nmbrg;
		return nmbrg;
	}
	  $(document).ready(function() {
        $('#pilihprint').submit(function() {
          $.ajax({
              type: 'POST',
              url: $(this).attr('action'),
              data: $(this).serialize(),
              success: function(data) {
                $('#viewcetakgo').html(data);
				document.getElementById('pilkertas').style.display='none';
              }
          })
          return false;
        });
      })
    
</script>
<?php
  mysqli_close($connect);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>