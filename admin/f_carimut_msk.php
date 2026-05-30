<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
	// echo 'keyword='.$keyword;
	include "config.php";
	session_start();
	
	$connect=opendtcek();
	$kd_toko=$_SESSION['id_toko'];
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;">
	<div style="border:1px grey solid;">&nbsp;<i class="fa fa-television">&nbsp;PEMBELIAN BARANG </i></div>
	  <table class="table-hover" style="font-size:9pt;width: 100% ">
	    <tr align="middle" class="yz-theme-l4">
	      <th>NO</th>
	      <th>KD.TOKO</th>
	      <th>TGL. MSK</th>
	      <th>FAKTUR</th>
	      <th>SUPPLIER</th>
	      <!-- <th>KD. BRG</th> -->
	      <th>NAMA BARANG</th>
	      <th colspan="3">KONVERSI JUMLAH BARANG</th>
	      <th>KETERANGAN</th>
	    </tr>

	    <?php
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 20; // Jumlah data per halamannya
	    $limit_start = ($page - 1) * $limit;
	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	    	$params = mysqli_real_escape_string($connect, $keyword);	
          
          if ($params=="") {	 
          	  $sql = mysqli_query($connect, "SELECT beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.kd_toko, beli_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,supplier.nm_sup FROM beli_brg 
          	  	  LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
          	  	  LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
          	  	  WHERE LEFT(beli_brg.ket,6) <> 'MUTASI'
          	  	  ORDER BY tgl_fak ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg AND LEFT(ket,6)<>'MUTASI'");
          }
          else {
          	  //echo '$params='.$params;
          	  $sql =mysqli_query($connect, "SELECT beli_brg.kd_sat,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.jml_brg,beli_brg.kd_toko,beli_brg.kd_brg,beli_brg.ket,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,supplier.nm_sup
          	  	  FROM beli_brg 
          	  	  LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
          	  	  LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
          	  	  WHERE beli_brg.kd_brg='$params' AND LEFT(beli_brg.ket,6) <> 'MUTASI'
          	  	  ORDER BY beli_brg.tgl_fak ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE kd_brg = '$params' AND LEFT(ket,6) <> 'MUTASI'");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }

       //utk total stok barang
	   $stokakhir1=0;$stokakhir2=0;$stokakhir3=0;$sat1=0;$sat2=0;$sat3=0;
       $sqltot=mysqli_query($connect,"SELECT beli_brg.kd_sat,beli_brg.jml_brg,beli_brg.stok_jual,beli_brg.kd_toko, beli_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.brg_msk,mas_brg.brg_klr FROM beli_brg
           LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
           WHERE beli_brg.kd_brg='$params' AND LEFT(beli_brg.ket,6)<>'MUTASI' ");   

	      while ($datjum=mysqli_fetch_assoc($sqltot)){
	      	$jumhr=$datjum['jml_brg']*konjumbrg2($datjum['kd_sat'],$datjum['kd_brg'],$connect);
	        if ($datjum['jum_kem1']>0) {
	            $stokakhir1=$stokakhir1+($jumhr/$datjum['jum_kem1']);
	            $sat1=gantitides(round($stokakhir1,2)).' '.$datjum['nm_kem1'];
	            //echo '$stokakhir1='.$stokakhir1.'<br>';
	        }else{
	          $stokakhir1=0;
	          $sat1='NONE';
	        }
	        if ($datjum['jum_kem2']>0) {
	          $stokakhir2=$stokakhir2+($jumhr/$datjum['jum_kem2']);
	          $sat2=gantitides(round($stokakhir2,2)).' '.$datjum['nm_kem2'];
	        }else{
	          $stokakhir2=0;
	          $sat2='NONE';
	        }
	        if ($datjum['jum_kem3']>0) {
	          $stokakhir3=$stokakhir3+($jumhr/$datjum['jum_kem3']);
	          $sat3=gantitides(round($stokakhir3,2)).' '.$datjum['nm_kem3'];
	        }else{
	          $stokakhir3=0;
	          $sat3='NONE';
	        }        
	      }
        unset($sqltot,$datjum);

	    $no=$limit_start;$unket='';$tot=0;
	    $stok1=0;$stok2=0;$stok3=0;  
	    $sub1=0;$sub2=0;$sub3=0;$sub11=0;$sub22=0;$sub33=0;  
	    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
	     if (strpos($data['ket'],"UTASI")!=1){
	      $no++;
	      $brg_msk_hi=$data['jml_brg']*konjumbrg2($data['kd_sat'],$data['kd_brg'],$connect);
          if ($data['jum_kem1']>0) {
          	$stok1=gantitides($brg_msk_hi/$data['jum_kem1']).' '.$data['nm_kem1']; 
            $sub1=$sub1+($brg_msk_hi/$data['jum_kem1']);
            $sub11=gantitides(round($sub1,2)).' '.$data['nm_kem1'];
          }else{
            $stok1='NONE';
            $sub11='NONE';
          }
          if ($data['jum_kem2']>0) {
            $stok2=gantitides(round($brg_msk_hi/$data['jum_kem2'],2)).' '.$data['nm_kem2'];  
            $sub2=$sub2+($brg_msk_hi/$data['jum_kem2']);
            $sub22=gantitides(round($sub2,2)).' '.$data['nm_kem2'];
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
	      <tr>
	      	<td align="right" style="border-right:none"><?php echo $no.'.'; ?>&nbsp;</td>
	      	<td align="middle" style="border-left:none;border-right:none"><?php echo $data['kd_toko']; ?></td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo gantitgl($data['tgl_fak']); ?></td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['no_fak']; ?></td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_sup']; ?></td>
	        <!-- <td align="middle"><?php echo $data['kd_brg']; ?></td> -->
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_brg']; ?></td>
	        <td align="right" class="yz-theme-l3" style="border-left:none;border-right:none"><?php echo $stok1;?>&nbsp;</td>
	        <td align="right" class="yz-theme-l4" style="border-left:none;border-right:none"><?php echo $stok2;?>&nbsp;</td>
	        <td align="right" class="yz-theme-light" style="border-left:none;border-right:none"><?php echo $stok3;?>&nbsp;</td>
	        <td align="middle" style="border-left:none;border-right:none"><?php echo $data['ket']; ?></td>
	      </tr>
	    <?php
	     }
	    }
	    ?>
	    <tr >
	     <th colspan="6" style="text-align: right" class="yz-theme-l4"> SUB JUMLAH BARANG &nbsp;</th>	
	     <th style="text-align: right;" class="yz-theme-l4"><?=$sub11?>&nbsp;</th>
	     <th style="text-align: right;" class="yz-theme-l4"><?=$sub22?>&nbsp;</th>
	     <th style="text-align: right;" class="yz-theme-l4"><?=$sub33?>&nbsp;</th>
	     <th class="yz-theme-l4"></th>
	    </tr>
	    <tr >
	     <th colspan="6" style="text-align: right" class="yz-theme-l3"> TOTAL JUMLAH BARANG &nbsp;</th>	
	     <th style="text-align: right;" class="yz-theme-l3"><?=$sat1?>&nbsp;</th>
	     <th style="text-align: right;" class="yz-theme-l3"><?=$sat2?>&nbsp;</th>
	     <th style="text-align: right;" class="yz-theme-l3"><?=$sat3?>&nbsp;</th>
	     <th class="yz-theme-l3"></th>
	    </tr>
	  </table>
	</div>

	<nav  aria-label="Page navigation example" style="margin-top:1px;">
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
	      <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="carimut_msk(1, false)">First</a></li>
	      <li><a class="page-link yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="carimut_msk(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
	      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" onclick="carimut_msk(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
	      <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="carimut_msk(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
	      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carimut_msk(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
	    <?php
	    }
	    ?>
	  </ul>
	</nav>

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
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>