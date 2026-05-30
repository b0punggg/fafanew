<?php
	$keyword1 = $_POST['keyword1']; // Ambil data keyword yang dikirim dengan AJAX	
	$key_cari = $_POST['keyword2'];
	ob_start();
	// echo 'keyword='.$keyword;
	include "config.php";
  session_start();
  
	$connect=opendtcek();
	$kd_toko=$_SESSION['id_toko'];
  $keycari=0;
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;min-height: 100px ">
	<div class="" style="border:1px grey solid;color:black;">&nbsp;<i class="fa fa-television">&nbsp;ANTAR GUDANG / TOKO </i></div>
	  <table class="table-hover" style="font-size:9pt;width: 100%;border-collapse: collapse;white-space: nowrap;">
	    <tr align="middle" class="yz-theme-l4">
	      <th width="5%">NO</th>

	      <th width="12%">TGL. KLR &nbsp;<button type="button" id="btn-tgl_klrmut" class="btn w3-hover-shadow fa fa-search yz-theme-l1" style="padding:3px"></button>
              
      	  	<div id="boxtglklrmut" style="display:none;z-index: 1;width: 100%; position:absolute ;width: 100%" >

              <div class="input-group" style="width:250px;margin-top: 15px;">
                <input type="date" class="yz-theme-l4 w3-card-4" id="tgl_klrmut" name="kd_klrmut" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Tgl.Mutasi" 
                 onchange="document.getElementById('key_cari').value='mutasi_brg.tgl_fak like '+this.value;carimut_gud(1,true);" 
                 onkeypress="if(event.keyCode==13){
                  document.getElementById('key_cari').value='mutasi_brg.tgl_fak like '+this.value;carimut_gud(1,true);  }">

                <div class="input-group-btn w3-card-4">
                  <button class="btn btn-primary" onclick="
                 document.getElementById('key_cari').value='';carimut_gud(1,true);
                 " style="border:1px solid black;cursor: pointer" type="button"><i class="fa fa-undo"></i></button>
                </div>  
              </div>  
            </div>

	        <script>
          $(document).ready(function(){
            $("#btn-tgl_klrmut").click(function(){
              $("#boxtglklrmut").slideToggle("fast");
              $("#tgl_klrmut").focus();
              $("#tgl_klrmut2").focus();
              $("#boxnofakmut").slideUp("fast");
              $("#boxnm_brgmutgud").slideUp("fast");
              $("#boxidtoko").slideUp("fast");
            });
          });
          </script>
	      </th>
         
        <th width="15%">KODE TOKO &nbsp;<button type="button" id="btn-idtoko" class="btn w3-hover-shadow fa fa-search yz-theme-l1" style="padding:3px"></button>
          <div id="boxidtoko" class="container" style="display:none;position:absolute;z-index: 1;width: 100%">  
            <div class="input-group" style="width: 250px;margin-top: 15px;">
               <input type="text" class="yz-theme-l4 w3-card-4" id="idtoko" name="idtoko" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Kode Toko" 
               onkeypress="if(event.keyCode==13){
                document.getElementById('key_cari').value='mutasi_brg.kd_toko like '+this.value;carimut_gud(1,true);}">

              <div class="input-group-btn w3-card-4">
                <button class="btn btn-primary" 
                  onclick="
                  document.getElementById('key_cari').value='';carimut_gud(1,true);
                  " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                </button>
              </div> 

            </div>  
          </div>
            <script>
            $(document).ready(function(){
              $("#btn-idtoko").click(function(){
                $("#boxidtoko").slideToggle("fast");
                $("#idtoko").focus();
                $("#idtoko2").focus();
                $("#boxnofakmut").slideUp("fast"); 
                $("#boxtglklrmut").slideUp("fast");
                $("#boxnm_brgmutgud").slideUp("fast");
              });
            });
            </script>
        </th> 

	      <th width="10%" >FAKTUR BELI &nbsp;<button type="button" id="btn-no_fakmutgud" class="btn w3-hover-shadow fa fa-search yz-theme-l1" style="padding:3px"></button>
    	    <div id="boxnofakmut" class="container" style="display:none;position:absolute;z-index: 1;" >
           	<div class="input-group" style="width:250px;margin-top: 15px;">
				       <input type="text" class="yz-theme-l4 w3-card-4" id="no_fakmutgud" name="no_fakmutgud" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Faktur Beli" 
				       onkeypress="if(event.keyCode==13){
				       	document.getElementById('key_cari').value='mutasi_brg.no_fak like '+this.value;carimut_gud(1,true);}">
				       <div class="input-group-btn w3-card-4">
                <button class="btn btn-primary" 
				         onclick="
				         document.getElementById('key_cari').value='';carimut_gud(1,true);
				         " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                </button>
				       </div>		
				    </div>	
          </div>
	        <script>
          $(document).ready(function(){
            $("#btn-no_fakmutgud").click(function(){
              $("#boxnofakmut").slideToggle("fast");
              $("#no_fakmutgud").focus();
              $("#no_fakmutgud2").focus();
              $("#boxtglklrmut").slideUp("fast");
              $("#boxnm_brgmutgud").slideUp("fast");
              $("#boxidtoko").slideUp("fast");
            });
          });
          </script>
	      </th>

	      <th>NAMA BARANG</th>
	      <th colspan="3">KONVERSI JUMLAH BARANG</th>
	      <th>KETERANGAN</th>
	    </tr>

	    <?php
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 5; // Jumlah data per halamannya
	    $limit_start = ($page - 1) * $limit;
	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	    	$para1 = mysqli_real_escape_string($connect, $keyword1);	
        $para2 = mysqli_real_escape_string($connect, $key_cari);
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

          if ($para2==""){
          	$sql =mysqli_query($connect, "SELECT mutasi_brg.qty_brg, mutasi_brg.kd_toko,mutasi_brg.no_fak,mutasi_brg.tgl_fak,mutasi_brg.kd_toko,mutasi_brg.kd_brg,mutasi_brg.kd_sat,mutasi_brg.qty_brg,mutasi_brg.ket,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3 FROM mutasi_brg
      	  	  LEFT JOIN mas_brg ON mutasi_brg.kd_brg=mas_brg.kd_brg 
      	  	  WHERE mutasi_brg.kd_brg='$para1'
              ORDER BY mutasi_brg.tgl_fak DESC LIMIT $limit_start, $limit");  
            // $sql2=mysqli_query($connect,"SELECT COUNT(*) AS jumlah FROM (SELECT COUNT(*) FROM mutasi_brg WHERE kd_brg = '$para1' GROUP BY mutasi_brg.no_fak,mutasi_brg.ket,mutasi_brg.kd_brg) jumlah"); 
              $sql2=mysqli_query($connect,"SELECT COUNT(*) AS jumlah FROM mutasi_brg WHERE kd_brg='$para1'");

              $sqltot =mysqli_query($connect, "SELECT mutasi_brg.qty_brg, mutasi_brg.kd_toko,mutasi_brg.no_fak,mutasi_brg.tgl_fak,mutasi_brg.kd_toko,mutasi_brg.kd_brg,mutasi_brg.kd_sat,mutasi_brg.qty_brg,mutasi_brg.ket,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3 FROM mutasi_brg
                LEFT JOIN mas_brg ON mutasi_brg.kd_brg=mas_brg.kd_brg 
                WHERE mutasi_brg.kd_brg='$para1'");

          } else{
          	$sql =mysqli_query($connect, "SELECT mutasi_brg.qty_brg,mutasi_brg.kd_toko, mutasi_brg.no_fak,mutasi_brg.tgl_fak,mutasi_brg.kd_toko,mutasi_brg.kd_brg,mutasi_brg.kd_sat,mutasi_brg.qty_brg,mutasi_brg.ket,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3 FROM mutasi_brg
      	  	  LEFT JOIN mas_brg ON mutasi_brg.kd_brg=mas_brg.kd_brg 
      	  	  WHERE mutasi_brg.kd_brg='$para1' AND $para2
              ORDER BY mutasi_brg.tgl_fak DESC LIMIT $limit_start, $limit");
            // $sql2=mysqli_query($connect,"SELECT COUNT(*) AS jumlah FROM (SELECT COUNT(*) FROM mutasi_brg WHERE kd_brg = '$para1' AND $para2 GROUP BY mutasi_brg.no_fak,mutasi_brg.ket,mutasi_brg.kd_brg) jumlah"); 
            $sql2=mysqli_query($connect,"SELECT COUNT(*) AS jumlah FROM mutasi_brg WHERE kd_brg='$para1' AND $para2 ");

            $sqltot =mysqli_query($connect, "SELECT mutasi_brg.qty_brg, mutasi_brg.kd_toko,mutasi_brg.no_fak,mutasi_brg.tgl_fak,mutasi_brg.kd_toko,mutasi_brg.kd_brg,mutasi_brg.kd_sat,mutasi_brg.qty_brg,mutasi_brg.ket,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3 FROM mutasi_brg
              LEFT JOIN mas_brg ON mutasi_brg.kd_brg=mas_brg.kd_brg 
              WHERE mutasi_brg.kd_brg='$para1' AND $para2 ");
          } 
            $get_jumlah = mysqli_fetch_array($sql2);
	    }
      
      //jumlah total 
      $tot1=0;$tot2=0;$tot3=0;$tot11=0;$tot22=0;$tot33=0;
      while ($datot=mysqli_fetch_assoc($sqltot)){
        $brg_msk_hi=$datot['qty_brg']*konjumbrg2($datot['kd_sat'],$datot['kd_brg'],$connect);
        if ($datot['jum_kem1']>0) {
          $tot1=$tot1+($brg_msk_hi/$datot['jum_kem1']);
          $tot11=gantitides(round($tot1,2)).' '.$datot['nm_kem1']; 
        }else{
          $tot11='NONE';
        }
        if ($datot['jum_kem2']>0) {
          $tot2=$tot2+($brg_msk_hi/$datot['jum_kem2']);
          $tot22=gantitides(round($tot2,2)).' '.$datot['nm_kem2']; 
        }else{
          $tot22='NONE';
        }
        if ($datot['jum_kem3']>0) {
          $tot3=$tot3+($brg_msk_hi/$datot['jum_kem3']);
          $tot33=gantitides(round($tot3,2)).' '.$datot['nm_kem3']; 
        }else{
          $tot33='NONE';
        }

      }
      unset($sqltot,$datot);

	    $no=$limit_start;$sub1=0;$sub2=0;$sub3=0;$sub11=0;$sub22=0;$sub33=0;
      $stok1='';$stok2='';$stok3='';
	    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
        $brg_msk_hi=$data['qty_brg']*konjumbrg2($data['kd_sat'],$data['kd_brg'],$connect);
        if ($data['jum_kem1']>0) {
          $stok1=gantitides(round($brg_msk_hi/$data['jum_kem1'],2)).' '.$data['nm_kem1']; 
          $sub1=$sub1+($brg_msk_hi/$data['jum_kem1']);
          $sub11=gantitides(round($sub1,2)).' '.$data['nm_kem1']; 
        }else{
          $stok1='NONE';
          $sub11='NONE';
        }
        if ($data['jum_kem2']>0) {
          $stok2=gantitides(round($brg_msk_hi/$data['jum_kem2'],2)).' '.$data['nm_kem2'];  
          $sub2=$sub2+($brg_msk_hi/$data['jum_kem2']);
          $sub22=gantitides($sub2).' '.$data['nm_kem2']; 
        }else{
          $stok2='NONE';
          $sub22='NONE';
        }
        if ($data['jum_kem3']>0) {
          $stok3=gantitides(round($brg_msk_hi/$data['jum_kem3'],2)).' '.$data['nm_kem3'];   
          $sub3=$sub3+($brg_msk_hi/$data['jum_kem3']);
          $sub33=gantitides(round($sub3,2)).' '.$data['nm_kem3']; 
        }else{
          $stok3='NONE';
          $sub33='NONE';
        }
        ?>
          <td align="right" style="border-left:none;border-right:none"><?php echo $no; ?></td>   
          <td align="middle" style="border-left:none;border-right:none"><?php echo gantitgl($data['tgl_fak']); ?></td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo $data['kd_toko']; ?></td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['no_fak']; ?></td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_brg']; ?></td>
	        <td align="right" class="yz-theme-l3" style="border-left:none;border-right:none"><?php echo $stok1 ?>&nbsp;</td>
          <td align="right" class="yz-theme-l4" style="border-left:none;border-right:none"><?php echo $stok2 ?>&nbsp;</td>
          <td align="right" class="yz-theme-light" style="border-left:none;border-right:none"><?php echo $stok3 ?>&nbsp;</td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['ket']; ?></td>
	      </tr>
	      <?php
	    }
	    ?>
      <tr class="yz-theme-l4">
        <td colspan="5" style="text-align: right"><b>SUB JUMLAH MUTASI</b>&nbsp;</td>
        <td style="text-align: right"><b><?=$sub11?>&nbsp;</b></td>
        <td style="text-align: right"><b><?=$sub22?>&nbsp;</b></td>
        <td style="text-align: right"><b><?=$sub33?>&nbsp;</b></td>
        <td></td>
      </tr>
      <tr class="yz-theme-l3">
        <td colspan="5" style="text-align: right"><b>TOTAL JUMLAH MUTASI</b>&nbsp;</td>
        <td style="text-align: right"><b><?=$tot11?>&nbsp;</b></td>
        <td style="text-align: right"><b><?=$tot22?>&nbsp;</b></td>
        <td style="text-align: right"><b><?=$tot33?>&nbsp;</b></td>
        <td></td>
      </tr>
	  </table>
	</div>

  <?php if($no>=1) { ?>
	<nav  aria-label="Page navigation example" style="margin-top:1px;z-index: 1">
	  <ul class="pagination pagination-sm justify-content-end" style="z-index: 1">
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
	      <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="carimut_gud(1, false)">First</a></li>
	      <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="carimut_gud(<?php echo $link_prev; ?>, false)"></a></li>
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
	      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" onclick="carimut_gud(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
	      <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="carimut_gud(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
	      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carimut_gud(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
	    <?php
	    }
	    ?>
	  </ul> 
	</nav>
  <script>document.getElementById('viewmutasigudang').style.display='block';</script>
<?php } else { ?>
  <script>
  if (document.getElementById('key_cari').value=="") {
    document.getElementById('viewmutasigudang').style.display='none';
  } else {
    document.getElementById('viewmutasigudang').style.display='block';
  }
  </script> 
<?php } ?>  

<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>
