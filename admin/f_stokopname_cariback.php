<?php
   $keyword=$_POST['keyword'];
	ob_start();
?>

<div class="table-responsive" style="overflow-x: auto;border-style: ridge;border-color: white;min-height:100px">
	  <table id="table" class="table-bordered table-hover arrow-nav" style="font-size:9pt; position: sticky;width: 100%;border-collapse: collapse;white-space: nowrap;">
	    <tr align="middle" class="yz-theme-l1">
	      <th width="4%">No.</th>
		  <th >BARCODE  &nbsp;<button type="button" id="btn-barcode" class="btn yz-hover-theme p-1" ><i class="fa fa-search"></i></button>
	      	<div class="row">
  				<div class="col">
  				  	<div id="boxbarcode" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px;margin-top: 7px" >
  				  		<div class="input-group w3-card-4" style="width: 300px;margin-top: 26px">
  					       <input type="text" class="yz-theme-l4 form-control" id="in_barcode" name="in_barcode" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kode Barang" onkeypress="if(event.keyCode==13){document.getElementById('keycari').value='AND beli_brg.kd_bar like ;%'+this.value+'%';caribrgstok(1,true);}">
  					       <div class="input-group-append">
  					       	<button class="btn yz-theme-d1" onclick="
  					          document.getElementById('boxbarcode').style.display='none';document.getElementById('keycari').value='AND beli_brg.kd_bar like ;%'+document.getElementById('in_barcode').value+'%';caribrgstok(1,true);
  					       " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i></button>
  					       <button class="btn btn-warning" onclick="
  					          document.getElementById('keycari').value='';document.getElementById('boxbarcode').style.display='none';caribrgstok(1,true);
  					       " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
  					       </div>		
  					    </div>	
  	                </div>
  	            </div>
  	        </div>    
  	        <script>
            $(document).ready(function(){
              $("#btn-barcode").click(function(){
				$("#boxbarcode").slideToggle("fast");
              	$("#boxokdbrg").slideup("fast");
                $("#boxonmbrg").slideUp("fast");
                $("#okd_brg").focus();
              });
            });
            </script>
	      </th>
	      <th>ID. BARANG  &nbsp;<button type="button" id="btn-okdbrg" class="btn yz-hover-theme p-1" ><i class="fa fa-search"></i></button>
	      	<div class="row">
  				<div class="col">
  				  	<div id="boxokdbrg" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px;margin-top: 7px" >
  				  		<div class="input-group w3-card-4" style="width: 300px;margin-top: 26px">
  					       <input type="text" class="yz-theme-l4 form-control" id="okd_brg" name="okd_brg" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kode Barang" onkeypress="if(event.keyCode==13){document.getElementById('keycari').value='AND beli_brg.kd_brg like ;%'+this.value+'%';caribrgstok(1,true);}">
  					       <div class="input-group-append">
  					       	<button class="btn yz-theme-d1" onclick="
  					          document.getElementById('boxokdbrg').style.display='none';document.getElementById('keycari').value='AND beli_brg.kd_brg like ;%'+document.getElementById('okd_brg').value+'%';caribrgstok(1,true);
  					       " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i></button>
  					       <button class="btn btn-warning" onclick="
  					          document.getElementById('keycari').value='';document.getElementById('boxokdbrg').style.display='none';caribrgstok(1,true);
  					       " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
  					       </div>		
  					    </div>	
  	                </div>
  	            </div>
  	        </div>    
  	        <script>
            $(document).ready(function(){
              $("#btn-okdbrg").click(function(){
              	$("#boxokdbrg").slideToggle("fast");
                $("#boxonmbrg").slideUp("fast");
				$("#boxbarcode").slideUp("fast");
                $("#okd_brg").focus();
              });
            });
          </script>
	      </th>
	      <th >NAMA BARANG &nbsp;<button type="button" id="btn-onmbrg" class="btn p-1 yz-hover-theme"><i class="fa fa-search"></i></button>
	      	<div class="row">
  				<div class="col">
  				  	<div id="boxonmbrg" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px;margin-top: 7px" >
  				  		<div class="input-group w3-card-4 " style="width: 300px;margin-top: 26px">
  					       <input type="text" class="yz-theme-l4 form-control" id="onm_brg" name="onm_brg" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Nama Barang" onkeypress="if(event.keyCode==13){document.getElementById('keycari').value='AND mas_brg.nm_brg like ;%'+this.value+'%';caribrgstok(1,true);}">
  					       <div class="input-group-append">
  					       	<button class="btn yz-theme-d1" onclick="
  					       document.getElementById('boxonmbrg').style.display='none';document.getElementById('keycari').value='AND mas_brg.nm_brg like ;%'+document.getElementById('onm_brg').value+'%';caribrgstok(1,true);
  					       " style="border:1px solid black"><i class="fa fa-search" style="cursor: pointer"></i></button>
  					       <button class="btn btn-warning" onclick="
  					       document.getElementById('keycari').value='';document.getElementById('boxonmbrg').style.display='none';caribrgstok(1,true);
  					       " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
  					       </div>		
  					    </div>	
  	                
                    </div>    
  	                    
  					
  	            </div>
  	        </div>    
  	        <script>
            $(document).ready(function(){
              $("#btn-onmbrg").click(function(){
              	$("#boxonmbrg").slideToggle("fast");
              	$("#boxokdbrg").slideUp("fast");
				$("#boxbarcode").slideUp("fast");
              	$("#onm_brg").focus();
              });
            });
            </script>

	      </th>
	      <th width="10%">ID. TOKO</th>
	      <th width="8%">STOK</th>
	      <th width="7%">SATUAN</th>
	      <th width="10%">ADJUST</th>
	      <th width="4%">NOTE</th>
	    </tr>
	    <?php
	    include "config.php";
	    session_start();
	    $kd_toko=$_SESSION['id_toko'];
	    $conopname=opendtcek();
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 15; // Jumlah data per halamannya
	    $limit_start = ($page - 1) * $limit;
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	    	// $id_apt=$_SESSION['id_apt'];
	    	$param = mysqli_real_escape_string($conopname, $keyword);
	    	if (!empty($param)){
	    	  $x=explode(';', $param);
		      $x1=$x[0];
		      $x2="'".$x[1]."'";
		      $params=$x1.$x2;	
	    	}else{$params='';}
	    	
	    	//$param='%'.$params.'%';  	
	    	//echo $params;
          if ($params=="") {	 
          	  $sql = mysqli_query($conopname, "SELECT SUM(beli_brg.stok_jual) AS stok_juals, beli_brg.kd_brg,beli_brg.kd_bar,beli_brg.no_urut,beli_brg.kd_toko,beli_brg.stok_jual,mas_brg.nm_brg FROM beli_brg
          	  	LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
          	  	WHERE beli_brg.kd_toko='$kd_toko'
                GROUP BY beli_brg.kd_brg   
          	  	ORDER BY beli_brg.kd_brg ASC LIMIT $limit_start, $limit");
	          $sql2=mysqli_query($conopname,"SELECT count(*) AS jumlah FROM (SELECT COUNT(*) FROM beli_brg WHERE beli_brg.kd_toko='$kd_toko' GROUP BY kd_brg) jumlah"); 
          }
          else {
	          $sql =mysqli_query($conopname, "SELECT SUM(beli_brg.stok_jual) AS stok_juals, beli_brg.kd_brg,beli_brg.kd_bar,beli_brg.no_urut,beli_brg.kd_toko,beli_brg.stok_jual,mas_brg.nm_brg FROM beli_brg
          	  	LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
          	  	WHERE beli_brg.kd_toko='$kd_toko' $params
                GROUP BY beli_brg.kd_brg   
          	  	ORDER BY beli_brg.kd_brg ASC LIMIT $limit_start, $limit");
		      $sql2=mysqli_query($conopname,"SELECT count(*) AS jumlah FROM (SELECT COUNT(*) FROM beli_brg LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg WHERE beli_brg.kd_toko='$kd_toko' $params GROUP BY beli_brg.kd_brg) jumlah"); 
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];
	    }
	    $no=$limit_start;$cekada=0;
	    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;	
	      $xsat=explode(';',carisatkecil($data['kd_brg']));	
	      $nmsat=ceknmkem($xsat[0],$conopname);
	      $cekada=carinote($data['kd_brg'],$kd_toko,$conopname);
	      ?>
	      <tr>
	        <td align="right"><?php echo $no.'.' ?>&nbsp;</td>
			<td align="left"><?= $data['kd_bar'];?></td>
	        <td align="left">
	        	<!-- <input class="w3-input" type="text" value="<?php echo $data['kd_brg']; ?>" readonly style="border: none;background-color: transparent;" > -->
	        	<?php echo $data['kd_brg']; ?>
	        </td>
	        <td align="left">
	        	<!-- <input class="w3-input" type="text" value="<?php echo $data['nm_brg']; ?>" readonly style="border: none;background-color: transparent;" > -->
	        	<?php echo $data['nm_brg']; ?>
	        </td>
	        <td align="middle">
	        	<!-- <input class="w3-input" type="text" value="<?php echo $data['kd_toko']; ?>" readonly style="border: none;background-color: transparent;text-align: center" > -->
	        	<?php echo $data['kd_toko']; ?>
	        </td>
	        <td align="middle">
	        	<!-- <input class="w3-input" type="text" value="<?php echo $data['stok_juals']; ?>" readonly style="border: none;background-color: transparent;text-align: center" > -->
	        	<?php echo $data['stok_juals']; ?>
	        </td>
	        <td align="middle">
	        	<!-- <input class="w3-input" type="text" value="<?php echo $nmsat; ?>" readonly style="border: none;background-color: transparent;text-align: center" > -->
	        	<?php echo $nmsat; ?>
	        </td>
	        <td>
			    <div class="input-group">
					<!-- <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" > -->
					<input id="<?=$no.'adpil'?>" class="form-control" type="number" min="0" max="<?=cekmaks($data['kd_brg'],$kd_toko,'$conopname');?>"  value="" style="border: none;background-color: transparent;text-align: center;font-size:9pt" 
					onkeyup="
					if (event.keyCode==13) {
						if (this.value <= <?=cekmaks($data['kd_brg'],$kd_toko,$conopname);?>){
							if (confirm('Apakah Kode Barang <?=$data['kd_brg']?> akan dilakukan penyesuaian ?')){
								document.getElementById('keyadjust').value=this.value+';<?=$data['kd_brg']?>';savedata();caribrgstok(1,true);}else{document.getElementById('keyadjust').value='';
								} 
						} else {
							popnew_error('Maksimal penyesuaian '+'<?=cekmaks($data['kd_brg'],$kd_toko,$conopname).' '.$nmsat ?>');
						} 	
						
					}" aria-describedby="button-addon2">
					<div class="input-group-append">
						<button class="btn btn-sm btn-outline-success fa fa-hdd-o" type="button" id="<?=$no.'btn-pil'?>" onclick="
						if (document.getElementById('<?=$no.'adpil'?>').value <= <?=cekmaks($data['kd_brg'],$kd_toko,$conopname);?> && document.getElementById('<?=$no.'adpil'?>').value !=''){
							document.getElementById('keyadjust').value=document.getElementById('<?=$no.'adpil'?>').value+';<?=$data['kd_brg']?>';
						    document.getElementById('fproses').style.display='block';
						    document.getElementById('stok_awal').value='<?=$data['stok_juals']?>';
						    document.getElementById('stok_akhir').value=document.getElementById('<?=$no.'adpil'?>').value;
							document.getElementById('ketbrg').innerHTML='<p> Nama Barang : '+'<?=$data['nm_brg']?>'+'</p>';
							document.getElementById('ket_ad').focus();
						}else{
							popnew_error('Maksimal penyesuaian '+'<?=cekmaks($data['kd_brg'],$kd_toko,$conopname).' '.$nmsat ?>');
						}	
						"></button>
					</div>
				</div>
	        </td>

		    <?php if ($cekada>0){ ?>
               <td style="text-align: center;"><button class="btn-warning fa fa-warning form-control" style="cursor: pointer" onclick="document.getElementById('keynote').value='<?=$data['kd_brg']?>';document.getElementById('fnote').style.display='block';carimutasinote(1,true);"></button></td> 
	        <?php  } else { ?>
               <td style="text-align: center;"><button class="fa fa-warning form-control" style="cursor: pointer"></button></td>
	        <?php  } ?>	        
	      </tr>
	    <?php	     
	    }
	    ?>
	  </table>
	</div>
	<div class="w3-border">
		<nav  aria-label="Page navigation example" style="margin-top:5px;font-size: 9pt">
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="caribrgstok(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="caribrgstok(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="caribrgstok(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="caribrgstok(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="caribrgstok(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>
<!--  -->
<?php 
function cekmaks($kdbrg,$kdtoko,$hub){
  $hub=opendtcek();	
  $cekbel=mysqli_query($hub,"SELECT * FROM beli_brg WHERE kd_brg='$kdbrg' AND kd_toko='$kdtoko'");
  $maks=0;$jmlbrg=0;
  while ($datcekbel=mysqli_fetch_assoc($cekbel)){
  	$jmlbrg=$datcekbel['jml_brg']*konjumbrg2($datcekbel['kd_sat'],$datcekbel['kd_brg'],$hub);
    $maks=$maks+$jmlbrg;
  }
  return $maks;
  unset($cekbel,$datcekbel);	
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
$('table.arrow-nav').keydown(function(e){
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
<!--  -->				

<?php
    mysqli_close($conopname);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>