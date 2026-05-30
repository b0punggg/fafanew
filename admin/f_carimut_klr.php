<?php
	$keyword = $_POST['keyword1']; // Ambil data keyword yang dikirim dengan AJAX	
	$key_cari2 = $_POST['keyword2'];
	ob_start();
	// echo 'keyword='.$keyword;
	include "config.php";
  session_start();
  
	$connect=opendtcek();
	$kd_toko=$_SESSION['id_toko'];
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;">
	<div style="border:1px grey solid;color:black;">&nbsp;<i class="fa fa-television">&nbsp;PENJUALAN BARANG </i></div>
	  <table class="table-hover" style="font-size:9pt;width: 100% ;border-collapse: collapse;white-space: nowrap;">
	    <tr align="middle" class="yz-theme-l4">
	      <th width="3%">NO</th>
          
	      <th>TGL. KLR &nbsp;<button type="button" id="btn-tgl_klr" class="btn w3-hover-shadow fa fa-search yz-theme-l1" style="padding:3px"></button>
        	<div id="boxtgl_klr" style="display:none;position: fixed;z-index: 1;margin-top: 20px" >
			  		<div class="input-group" style="width:250px;">
				       <input type="date" class="yz-theme-l4 w3-card-4" id="tgl_klr" name="tgl_klr" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Tgl.Jual" 
				       onchange="document.getElementById('key_cari2').value='dum_jual.tgl_jual like '+this.value;carimut_klr(1,true);" 
				       onkeypress="if(event.keyCode==13){
				       	document.getElementById('key_cari2').value='dum_jual.tgl_jual like '+this.value;carimut_klr(1,true);}">
				      <div class="input-group-btn w3-card-4">
                <button class="btn btn-primary" 
				         onclick="
				         document.getElementById('key_cari2').value='';carimut_klr(1,true);
				         " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                </button>
				      </div>		
				    </div>	
          </div>
  	       <script>
            $(document).ready(function(){
              $("#btn-tgl_klr").click(function(){
                $("#boxtgl_klr").slideToggle("fast");
                $("#tgl_klr").focus();
                $("#boxnofak_jual").slideUp("fast");
                $("#boxnm_pel").slideUp("fast");
                $("#boxnm_brg").slideUp("fast");
                $("#boxnotoko").slideUp("fast");
              });
            });
          </script>
	      </th>
           
        <th width="15%">KODE TOKO &nbsp;<button type="button" id="btn-notoko" class="btn w3-hover-shadow fa fa-search yz-theme-l1" style="padding:3px"></button>
          <div id="boxnotoko" class="container" style="display:none;position: absolute;z-index: 1;" >
            <div class="input-group" style="width: 250px;margin-top: 20px">
  				    <input type="text" class="yz-theme-l4 w3-card-4" id="notoko" name="notoko" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kode Toko" 
  				    onkeypress="if(event.keyCode==13){
  				    document.getElementById('key_cari2').value='dum_jual.kd_toko like '+this.value;carimut_klr(1,true);}">
  				    <div class="input-group-btn w3-card-4">
                <button class="btn btn-primary" 
  				      onclick="
  				      document.getElementById('key_cari2').value='';carimut_klr(1,true);
  				       " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                </button>
  				    </div>		
				    </div>	
          </div>
          <script>
            $(document).ready(function(){
              $("#btn-notoko").click(function(){
                $("#boxnotoko").slideToggle("fast");
                $("#notoko").focus();
                $("#boxtgl_klr").slideUp("fast");
                $("#boxnm_pel").slideUp("fast");
                $("#boxnm_brg").slideUp("fast");
                $("#boxnofak_jual").slideUp("fast");
              });
            });
          </script>
	      </th> 
            
	      <th width="15%">FAKTUR JUAL &nbsp;<button type="button" id="btn-no_fakjual" class="btn w3-hover-shadow fa fa-search yz-theme-l1" style="padding:3px"></button>
          <div id="boxnofak_jual" class="container" style="display:none;position: absolute;z-index: 1;" >
            <!-- large screen -->
            <div class="input-group " style="width:250px;margin-top: 20px">
				      <input type="text" class="yz-theme-l4 w3-card-4" id="nofak_jual" name="nofak_jual" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Faktur Jual" 
				      onkeypress="if(event.keyCode==13){
				      document.getElementById('key_cari2').value='dum_jual.no_fakjual like '+this.value;
              carimut_klr(1,true);}">
				      <div class="input-group-btn w3-card-4">
                <button class="btn btn-primary" 
				          onclick="
				          document.getElementById('key_cari2').value='';carimut_klr(1,true);
				          " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                </button>
				      </div>		
				    </div>	

            <!-- small screen -->
            <!-- <div class="input-group w3-hide-medium w3-hide-large" style="width:250px;margin-top: 17px">
              <input type="text" class="yz-theme-l4 w3-card-4" id="nofak_jual2" name="nofak_jual2" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Faktur Jual" 
              onkeypress="if(event.keyCode==13){
              document.getElementById('key_cari2').value='dum_jual.no_fakjual like '+this.value;
              carimut_klr(1,true);}">
              <div class="input-group-btn w3-card-4">
                <button class="btn btn-primary" 
                  onclick="
                  document.getElementById('key_cari2').value='';carimut_klr(1,true);
                  " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                </button>
              </div>    
            </div>   -->
          </div>
          <script>
            $(document).ready(function(){
              $("#btn-no_fakjual").click(function(){
                $("#boxnofak_jual").slideToggle("fast");
                $("#nofak_jual").focus();
                $("#boxtgl_klr").slideUp("fast");
                $("#boxnm_pel").slideUp("fast");
                $("#boxnm_brg").slideUp("fast");
                $("#boxnotoko").slideUp("fast");
              });
            });
          </script>
	      </th>

	      <th>PELANGGAN &nbsp;<button type="button" id="btn-nm_pel" class="btn yz-theme-l1 w3-hover-shadow fa fa-search" style="padding:3px"></button>
	      	<div id="boxnm_pel" class="container" style="display:none;position:absolute;z-index: 1;" >
            <!-- large screen  -->
	  	      <div class="input-group " style="margin-top: 20px;width: 250px" >
		          <input type="text" class="yz-theme-l4 w3-card-4" id="nm_pel" name="nm_pel" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Nama Pelanggan" 
		            onkeypress="if(event.keyCode==13){
		            document.getElementById('key_cari2').value='pelanggan.nm_pel like '+this.value;carimut_klr(1,true);}">
		            <div class="input-group-btn w3-card-4">
                  <button class="btn btn-primary" 
		               onclick="
		               document.getElementById('key_cari2').value='';carimut_klr(1,true);
		               " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                  </button>
		            </div>		
		        </div>	

            <!-- small screen  -->
	  	      <!-- <div class="input-group w3-hide-medium w3-hide-large" style="width: 250px;margin-top: 17px;">
		          <input type="text" class="yz-theme-l4 w3-card-4" id="nm_pel2" name="nm_pel2" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Nama Pelanggan" 
    				    onkeypress="if(event.keyCode==13){
    				    document.getElementById('key_cari2').value='pelanggan.nm_pel like '+this.value;carimut_klr(1,true);}">
    				    <div class="input-group-btn w3-card-4">
                  <button class="btn btn-primary" 
    				       onclick="
    				       document.getElementById('key_cari2').value='';carimut_klr(1,true);
    				       " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                  </button>
		            </div>		
		        </div>	  	 -->
          </div>
            <script>
            $(document).ready(function(){
              $("#btn-nm_pel").click(function(){
              	$("#boxnm_pel").slideToggle("fast");
              	$("#nm_pel").focus();
                $("#boxnofak_jual").slideUp("fast");
                $("#boxtgl_klr").slideUp("fast");
                $("#boxnm_brg").slideUp("fast");
                $("#boxnotoko").slideUp("fast");
              });
            });
            </script>
	      </th>

	      <th>NAMA BARANG</th>
	      <th colspan="3">KONVERSI JUMLAH BARANG</th>
	    </tr>

	    <?php
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;

	    $limit = 5; // Jumlah data per halamannya

	    $limit_start = ($page - 1) * $limit;
	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	    	$para1 = mysqli_real_escape_string($connect, $keyword);	
        $para2 = mysqli_real_escape_string($connect, $key_cari2);
        if(!empty($para2)){
			    $xada=strpos($para2,"like");
  			  if ($xada <> false){
                  $pecah=explode('like', $para2);
  			    $kunci=$pecah[0];
  			    $kunci2=$pecah[1];
  			    $para2=$kunci." like '%".trim($kunci2)."%'";
  			  }	
  			}else{
  			  $kunci='';
  			  $kunci2='';	
  			}
        // echo '$para1='.$para1.'<br>';
        // echo '$para2='.$para2.'<br>';
            if ($para2==""){
          	    $sql = mysqli_query($connect, "SELECT dum_jual.kd_toko,dum_jual.no_fakjual,dum_jual.tgl_jual,dum_jual.kd_brg,dum_jual.qty_brg,dum_jual.kd_sat,dum_jual.nm_brg,pelanggan.nm_pel,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3 FROM dum_jual 
          	  	  LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
                  LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg    
          	  	  WHERE dum_jual.kd_brg='$para1' 
          	  	  ORDER BY dum_jual.tgl_jual ASC LIMIT $limit_start, $limit");
	            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM dum_jual WHERE dum_jual.kd_brg='$para1'");
              $sql3=mysqli_query($connect, "SELECT dum_jual.kd_toko,dum_jual.no_fakjual,dum_jual.tgl_jual,dum_jual.kd_brg,dum_jual.qty_brg,dum_jual.kd_sat,dum_jual.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3 FROM dum_jual 
                LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg 
                WHERE dum_jual.kd_brg='$para1'");
            } else{
          	 $sql =mysqli_query($connect, "SELECT dum_jual.kd_toko,dum_jual.no_fakjual,dum_jual.tgl_jual,dum_jual.kd_brg,dum_jual.qty_brg,dum_jual.kd_sat,dum_jual.nm_brg,pelanggan.nm_pel,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3 from dum_jual 
          	  	  LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
                  LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg    
          	  	  WHERE dum_jual.kd_brg='$para1' AND $para2 
          	  	  ORDER BY dum_jual.tgl_jual ASC LIMIT $limit_start, $limit");

    		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) as jumlah, dum_jual.kd_toko, dum_jual.no_fakjual,dum_jual.tgl_jual,dum_jual.kd_brg,dum_jual.qty_brg,dum_jual.kd_sat,dum_jual.nm_brg,pelanggan.nm_pel from dum_jual 
          	  	  LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
                  WHERE dum_jual.kd_brg='$para1' AND $para2 ");	

              $sql3=mysqli_query($connect, "SELECT * FROM dum_jual 
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
                LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg    
                WHERE dum_jual.kd_brg='$para1' AND $para2");
            } 
                $get_jumlah = mysqli_fetch_array($sql2);

	    }

	    $no=$limit_start;$totklr=0;$total=0;
      $totk1=0;$totk2=0;$totk3=0;$totk11=0;$totk22=0;$totk33=0;
      
      //jumlah total 
      $totk1=0;$totk2=0;$totk3=0;
      while ($datjum=mysqli_fetch_assoc($sql3)){
        $brg_msk_hi=$datjum['qty_brg']*konjumbrg2($datjum['kd_sat'],$datjum['kd_brg'],$connect);
        if ($datjum['jum_kem1']>0) {
          $totk1=$totk1+($brg_msk_hi/$datjum['jum_kem1']);
          $totk11=gantitides($totk1).' '.$datjum['nm_kem1']; 
        }else{
          $totk11='NONE';
        }
        if ($datjum['jum_kem2']>0) {
          $totk2=$totk2+($brg_msk_hi/$datjum['jum_kem2']);
          $totk22=gantitides($totk2).' '.$datjum['nm_kem2']; 
        }else{
          $totk22='NONE';
        }
        if ($datjum['jum_kem3']>0) {
          $totk3=$totk3+($brg_msk_hi/$datjum['jum_kem3']);
          $totk33=gantitides($totk3).' '.$datjum['nm_kem3']; 
        }else{
          $totk33='NONE';
        }

      }
      unset($sqltot,$sql3);
      $stokk1=0;$stokk2=0;$stokk3=0;   
      $subk1=0;$subk2=0;$subk3=0;$subk11=0;$subk22=0;$subk33=0;
	    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
	      $nmkem=ceknmkem2($data['kd_sat'], $connect);
	      $no++;
        $totklr=$totklr+($data['qty_brg']*konjumbrg($data['kd_sat'],$data['kd_brg']));
        $x=carisatkecil($data['kd_brg']);
        $nmkem2=ceknmkem2($x[0], $connect);
        //$jmlbrg=$data['qty_brg']*konjumbrg($data['kd_sat'],$data['kd_brg']);

        $brg_msk_hi=$data['qty_brg']*konjumbrg($data['kd_sat'],$data['kd_brg'],$connect);
        if ($data['jum_kem1']>0) {
          $stokk1=gantitides(round($brg_msk_hi/$data['jum_kem1'],2)).' '.$data['nm_kem1']; 
          $subk1=$subk1+round($brg_msk_hi/$data['jum_kem1'],2);
          $subk11=gantitides($subk1).' '.$data['nm_kem1']; 
        }else{
          $stokk1='NONE';
          $subk11='NONE';
        }
        if ($data['jum_kem2']>0) {
          $stokk2=gantitides(round($brg_msk_hi/$data['jum_kem2'],2)).' '.$data['nm_kem2'];  
          $subk2=$subk2+round($brg_msk_hi/$data['jum_kem2'],2);
          $subk22=gantitides($subk2).' '.$data['nm_kem2']; 
        }else{
          $stokk2='NONE';
          $subk22='NONE';
        }
        if ($data['jum_kem3']>0) {
          $stokk3=gantitides(round($brg_msk_hi/$data['jum_kem3'],2)).' '.$data['nm_kem3'];   
          $subk3=$subk3+round($brg_msk_hi/$data['jum_kem3'],2);
          $subk33=gantitides($subk3).' '.$data['nm_kem3']; 
        }else{
          $stokk3='NONE';
          $subk33='NONE';
        }
	    ?>
	      <tr>
	      	<td align="right" style="border-right:none"><?php echo $no.'.'; ?>&nbsp;</td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo gantitgl($data['tgl_jual']); ?></td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['kd_toko']; ?></td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['no_fakjual']; ?></td>
	        <!-- <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_sup']; ?></td> -->
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_pel']; ?></td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_brg']; ?></td>
	         <td align="right" class="yz-theme-l3" style="border-left:none;border-right:none"><?php echo $stokk1 ?>&nbsp;</td>
          <td align="right" class="yz-theme-l4" style="border-left:none;border-right:none"><?php echo $stokk2 ?>&nbsp;</td>
          <td align="right" class="yz-theme-light" style="border-left:none;border-right:none"><?php echo $stokk3 ?>&nbsp;</td>
	      </tr>
	    <?php
	    }
	    ?>
      <tr class="yz-theme-l4">
        <td colspan="6" style="text-align: right;font-style: bold"><b>SUB JUMLAH TERJUAL</b>&nbsp;</td>
        <td style="text-align: right;"><b><?=$subk11?>&nbsp;</b></td>
        <td style="text-align: right;"><b><?=$subk22?>&nbsp;</b></td>
        <td style="text-align: right;"><b><?=$subk33?>&nbsp;</b></td>
      </tr>
      <tr class="yz-theme-l3">
        <td colspan="6" style="text-align: right;font-style: bold"><b>TOTAL JUMLAH TERJUAL </b>&nbsp;</td>
        <td style="text-align: right;"><b><?=$totk11?>&nbsp;</b></td>
        <td style="text-align: right;"><b><?=$totk22?>&nbsp;</b></td>
        <td style="text-align: right;"><b><?=$totk33?>&nbsp;</b></td>
      </tr>
	  </table>
</div>

<?php if ($no>=1){ ?>
	<nav  aria-label="Page navigation example" style="margin-top:1px;font-size: 8pt">
	  <ul class="pagination pagination-sm justify-content-end">
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
	      <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="carimut_klr(1, false)">First</a></li>
	      <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="carimut_klr(<?php echo $link_prev; ?>, false)"></a></li>
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
	      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" onclick="carimut_klr(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
	    <?php
	    }
	    ?>
	    
	    <!-- LINK NEXT AND LAST -->
	    <?php
	    if($page == $jumlah_page || $get_jumlah['jumlah']==0){
	    //if($page == $jumlah_page || $jum==0){
	    ?>
	      <li class="page-item disabled " ><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
	      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
	    <?php
	    }else{ // Jika Bukan page terakhir
	      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
	    ?>
	      <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="carimut_klr(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
	      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carimut_klr(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
	    <?php
	    }
	    ?>
	  </ul>
	</nav>
 <?php } ?>
<script>
	$(document).on('click', '.hapus_data', function(){
        var id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: "f_pashapus_act.php",
            data: {id:id},
            success: function() {
             caridtpass(1,true); 
             popnew_ok("Data terhapus");
            }
        });
    });
</script>

<?php
  unset($datjum,$sql1,$sql2,$sql3);
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>