<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
	  <table class="arrow-nav2 table-bordered table-striped table-hover hrf_res" style="width: 100%">

	    <tr align="middle" class="yz-theme-l3">
	      <th>NAMA BARANG</th>
	      <th style="width: 1%;">OPSI</th>
	    </tr>
	    <?php
	    session_start(); 
        include "config.php";
	    $connect=opendtcek();
        $page = (isset($_POST['page']))? $_POST['page'] : 1;

	    $limit = 8; // Jumlah data per halamannya

	    $limit_start = ($page - 1) * $limit;
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
   		    $id_toko=$_SESSION['id_toko'];
	    	$params = mysqli_real_escape_string($connect, $keyword);
	    	$param='%'.$params.'%';  	
	    	// echo $params;
          if ($params=="") {	 
          	  $sql1 = mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.jml_brg,mas_brg.kd_bar,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko FROM mas_brg 
          	  	  ORDER BY mas_brg.kd_brg ASC LIMIT $limit_start, $limit");
	          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg  ORDER BY kd_brg");
          }
          else {
	          $sql1 =mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.jml_brg,mas_brg.kd_bar,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko 
	            FROM mas_brg 
	          	WHERE nm_brg LIKE '$param'  ORDER BY mas_brg.kd_brg ASC LIMIT $limit_start, $limit");
		      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg WHERE nm_brg LIKE '$param' ");	
          }	
	      $get_jumlah = mysqli_fetch_array($sql2);

	    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
	      // $id_apt=$_SESSION['id_apt'];
          $sql1 = mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.jml_brg,mas_brg.kd_bar,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.kd_toko FROM mas_brg  
          	  ORDER BY mas_brg.kd_brg ASC LIMIT $limit_start, $limit");
	      // Buat query untuk menghitung semua jumlah data
	      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg  ORDER BY kd_brg");
	      $get_jumlah = mysqli_fetch_array($sql2);
	    }
        $no=0;	    
        $x1="";$kd_sat4="";$hrg_jum4=0;$lim1=0;
	    while($databrg = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
	      $no++;
	      $nm_kem1=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem1']),$connect);
	      $nm_kem2=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem2']),$connect);
	      $nm_kem3=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem3']),$connect);
          $kd_brg=mysqli_escape_string($connect,$databrg['kd_brg']);
          //cek disc tetap tokushima reiko
            $limit1=cekdisc($kd_brg,mysqli_escape_string($connect,$databrg['kd_kem1']),$connect);
            //echo '$lim1'.$lim1;
          
            if(empty($limit1)){
              $kd_sat4="1";$hrg_jum4=0;$lim1=0;$nm_kem4="-NONE-";	
              $percen1=0;
            }else{
	          $x1=explode(';', $limit1);
		      $kd_sat4=$x1[0];
		      $hrg_jum4=$x1[1];
		      $lim1=$x1[2];
		      $nm_kem4=ceknmkem($kd_sat4,$connect);
		      if ($hrg_jum4 <=0){
		      	$persen1=0;
		      }else{
		        //$percen1=str_replace(',','.',round(($databrg['hrg_jum1']-$hrg_jum4)/$hrg_jum4*100,2));
		        $percen1=round(($databrg['hrg_jum1']-$hrg_jum4)/$hrg_jum4*100,2);
		      }
		      
		    }  
          //
            $limit1=cekdisc($kd_brg,$databrg['kd_kem2'],$connect);
            if(empty($limit1)){
              $kd_sat5="1";$hrg_jum5=0;$lim2=0;$nm_kem5="-NONE-";	
              $percen2=0;
            }else{
	          $x1=explode(';', $limit1);
		      $kd_sat5=$x1[0];
		      $hrg_jum5=$x1[1];
		      $lim2=$x1[2];
		      $nm_kem5=ceknmkem($kd_sat5,$connect);
		      if ($hrg_jum5 <=0){
		      	$persen2=0;
		      }else{
		        //$percen2=str_replace('.',',',round(($databrg['hrg_jum2']-$hrg_jum5)/$hrg_jum5*100,2));
		        $percen2=round(($databrg['hrg_jum2']-$hrg_jum5)/$hrg_jum5*100,2);
		      }
		      
		    }  
            $limit1=cekdisc($kd_brg,$databrg['kd_kem3'],$connect);
            if(empty($limit1)){
              $kd_sat6="1";$hrg_jum6=0;$lim3=0;$nm_kem6="-NONE-";	
              $percen3=0;
            }else{
	          $x1=explode(';', $limit1);
		      $kd_sat6=$x1[0];
		      $hrg_jum6=$x1[1];
		      $lim3=$x1[2];
		      $nm_kem6=ceknmkem($kd_sat6,$connect);
		      if ($hrg_jum6 <=0){
		      	$persen3=0;
		      }else{
		        //$percen3=str_replace('.',',',round(($databrg['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2));
		        $percen3=round(($databrg['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2);
		      }
		      
		    }
	      // 
	    ?>

	      <tr>
	        <td align="left" style="cursor: pointer" onclick="document.getElementById('<?='btnnmbrg'.$no?>').click();"><input class="w3-input" type="text" tabindex="6" style="border: none;background-color: transparent;cursor: pointer" readonly value="<?=$databrg['nm_brg']; ?>" onkeydown="if(event.keyCode==13){document.getElementById('<?='btnnmbrg'.$no?>').click()}"></td>
	        <td>
	          	<button id="<?='btnnmbrg'.$no?>" onclick="
	               document.getElementById('kd_brg').value='<?=mysqli_escape_string($connect,$databrg['kd_brg']) ?>';
	          	   document.getElementById('nm_brg').value='<?=mysqli_escape_string($connect,$databrg['nm_brg']) ?>';
	          	   document.getElementById('kd_bar').value='<?=mysqli_escape_string($connect,$databrg['kd_bar']) ?>';
	          	   document.getElementById('kd_sat1').value='<?=mysqli_escape_string($connect,$databrg['kd_kem1']) ?>';document.getElementById('nm_sat1').value='<?=$nm_kem1?>';document.getElementById('jum_sat1').value='<?=mysqli_escape_string($connect,$databrg['jum_kem1']) ?>';document.getElementById('hrg_jum1').value='<?=gantitides(mysqli_escape_string($connect,$databrg['hrg_jum1'])) ?>';
	          	   document.getElementById('kd_sat2').value='<?=mysqli_escape_string($connect,$databrg['kd_kem2']) ?>';document.getElementById('nm_sat2').value='<?=$nm_kem2?>';document.getElementById('jum_sat2').value='<?=mysqli_escape_string($connect,$databrg['jum_kem2']) ?>';document.getElementById('hrg_jum2').value='<?=gantitides(mysqli_escape_string($connect,$databrg['hrg_jum2'])) ?>';
	          	   document.getElementById('kd_sat3').value='<?=mysqli_escape_string($connect,$databrg['kd_kem3']) ?>';document.getElementById('nm_sat3').value='<?=$nm_kem3?>';document.getElementById('jum_sat3').value='<?=mysqli_escape_string($connect,$databrg['jum_kem3']) ?>';document.getElementById('hrg_jum3').value='<?=gantitides(mysqli_escape_string($connect,$databrg['hrg_jum3'])) ?>';

	          	   document.getElementById('discttp1').value='<?=$lim1 ?>';document.getElementById('nm_sat4').value='<?=$nm_kem4?>';document.getElementById('kd_sat4').value='<?=$kd_sat4 ?>';document.getElementById('hrg_jum4').value='<?=gantitides($hrg_jum4) ?>';
	          	       document.getElementById('discttp1%').value='<?=$percen1 ?>';

	          	   document.getElementById('discttp2').value='<?=$lim2 ?>';document.getElementById('nm_sat5').value='<?=$nm_kem5?>';document.getElementById('kd_sat5').value='<?=$kd_sat5 ?>';document.getElementById('hrg_jum5').value='<?=gantitides($hrg_jum5) ?>';
	          	       document.getElementById('discttp2%').value='<?=$percen2 ?>';

	          	   document.getElementById('discttp3').value='<?=$lim3 ?>';document.getElementById('nm_sat6').value='<?=$nm_kem6?>';document.getElementById('kd_sat6').value='<?=$kd_sat6 ?>';document.getElementById('hrg_jum6').value='<?=gantitides($hrg_jum6) ?>';
	          	       document.getElementById('discttp3%').value='<?=$percen3?>';              
	          	   

                   document.getElementById('boxnmbrg').style.display='none';" class="btn btn-sm btn-primary fa fa-edit" type="button" style="cursor: pointer;font-size: 12pt" title="Edit Data">
	            </button>
	        </td>    
	      </tr>
	    <?php
	    }
	    ?>
	  </table>
	</div>
	
	<div class="w3-border yz-theme-l5">
		<nav  aria-label="Page navigation example" style="margin-top:15px;">
		  <ul class="pagination justify-content-center hrf_res">
		    <!-- LINK FIRST AND PREV -->
		    <?php
		    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
		      <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:18px;padding-right:18px"><i class="fa fa-caret-left"></i></a></li>
		    <?php
		    }else{ // Jika page bukan page ke 1
		      $link_prev = ($page > 1)? $page - 1 : 1;
		    ?>
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinmbrg2(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer;padding-left:18px;padding-right:18px" href="javascript:void(0);" onclick="carinmbrg2(<?php echo $link_prev; ?>, false)"><i class="fa fa-caret-left"></i></a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinmbrg2(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NEXT AND LAST -->
		    <?php
		    if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir
		    ?>
		      <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:18px;padding-right:18px"><i class="fa fa-caret-right"></i></a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
		    <?php
		    }else{ // Jika Bukan page terakhir
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinmbrg2(<?php echo $link_next; ?>, false)" style="cursor: pointer;padding-left:18px;padding-right:18px"><i class="fa fa-caret-right"></i></a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinmbrg2(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>
<script>

  $(document).ready(function(){
    $("#btn-updown").click(function(){
      $("#inputdata").slideToggle("");
    });
    $("#btn-geser").click(function(){
      $("#inputdata").slideDown("");
    });
  }); 

$('table.arrow-nav2').keydown(function(e){
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
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>