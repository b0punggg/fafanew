<?php
  $keyword = $_POST['keyword'];
  ob_start();
?>
<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;">
  <table class="table-hover" style="font-size:9pt; width: 100%;border:none"> 
    <?php
    include "config.php";
    session_start();
    $kd_toko=$_SESSION['id_toko']; 
    $conpiut=opendtcek();
    $page = (isset($_POST['page']))? $_POST['page'] : 1;
    $limit = 5; // Jumlah data per halamannya
    $limit_start = ($page - 1) * $limit;

    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
      $tglhi=date("Y-m-d");
      $news_hut_1=tglingat($tglhi,2);
      $params = mysqli_real_escape_string($conpiut, $keyword);
      $param='%'.$params.'%';   
      if ($params=="") {  
        $sql=mysqli_query($conpiut,"SELECT * FROM beli_bay 
              LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
              WHERE beli_bay.kd_toko='$kd_toko' and beli_bay.tgl_jt <='$news_hut_1' AND beli_bay.saldo_hutang>0 
              ORDER BY beli_bay.tgl_jt ASC LIMIT $limit_start, $limit ");

        $sql2=mysqli_query($conpiut, "SELECT COUNT(*) AS jumlah FROM beli_bay
              WHERE beli_bay.kd_toko='$kd_toko' and beli_bay.tgl_jt <='$news_hut_1' AND beli_bay.saldo_hutang>0 ");   
      } else {
        $sql=mysqli_query($conpiut,"SELECT beli_bay.ket,beli_bay.saldo_hutang,beli_bay.tgl_jt,beli_bay.tgl_fak,beli_bay.no_fak,supplier.nm_sup FROM beli_bay
              LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
              WHERE beli_bay.kd_toko='$kd_toko' AND supplier.nm_sup LIKE '$param' AND ket='TEMPO'
              ORDER BY beli_bay.tgl_jt ASC LIMIT $limit_start, $limit ");

        $sql2=mysqli_query($conpiut, "SELECT COUNT(*) AS jumlah FROM beli_bay
              LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
              WHERE beli_bay.kd_toko='$kd_toko' AND supplier.nm_sup LIKE '$param' AND ket='TEMPO'");
      } 
      
      $get_jumlah = mysqli_fetch_array($sql2);
      
    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
      
    }
    $no=$limit_start;
    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
      $no++;
      $ketnews=$no.". ".$data['nm_sup'].", No.Faktur: ".$data['no_fak']." / ".gantitgl($data['tgl_fak']).", Jatuh tempo ".gantitgl($data['tgl_jt']).", Sisa Rp. ".gantitides($data['saldo_hutang']);
    ?>
      <tr>
        <td colspan="2" style="padding: 2px;border:none">
          <span class="fa-stack fa-lg w3-text-red" style="font-size: 9pt">
            <i class="fa fa-square-o fa-stack-2x"></i>
            <i class="fa fa-exclamation fa-stack-1x"></i>
          </span>
          <a href="f_hutangbayar.php?bayarhut=<?=$data['no_fak'].';'.$data['tgl_fak']?>" class="w3-text-white" style="cursor: pointer;font-size: 10pt" title="Click Bayar hutang"><?=$ketnews?></a>
        </td>
      </tr> 
    <?php
      }
    ?>
  </table>
</div>

<nav  aria-label="Page navigation example" style="margin-top:5px;font-size: 8pt;z-index: 1">
  <ul class="pagination pagination-sm justify-content-end">
	<!-- LINK FIRST AND PREV -->
  	  <?php
	if($page == 1){ 
	  ?>
	  <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;padding : 3px 13px 3px 13px">Awal</a></li>&nbsp;
	  <li class="page-item disabled "><a class="page-link fa fa-chevron-left yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding : 3px 13px 3px 13px;border-radius:4px"></a></li>&nbsp;
	  <?php
	}else{ // Jika page bukan page ke 1
	  $link_prev = ($page > 1)? $page - 1 : 1;
	  ?>
	  <li><a class="page-link yz-theme-d1" style="cursor: pointer;padding : 3px 13px 3px 13px" href="javascript:void(0);" onclick="cinfohut(1, false)">Awal</a></li>&nbsp;
	  <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;padding : 3px 13px 3px 13px;border-radius:4px" href="javascript:void(0);" onclick="cinfohut(<?php echo $link_prev; ?>, false)"></a></li>&nbsp;
	  <?php
	}?>

	<!-- LINK NUMBER -->
	<?php
	$jumlah_page = ceil($get_jumlah['jumlah'] / $limit);
	$jumlah_number = 1; 
	$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1;
	$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page;
	
	for($i = $start_number; $i <= $end_number; $i++){
	  $link_active = ($page == $i)? ' class="active"' : '';?>
	  <li class="page-item" <?php echo $link_active; ?>><a class="page-link w3-hover-shadow w3-border-blue" href="javascript:void(0);" style="cursor: pointer;padding : 3px 13px 3px 13px;border-radius:5px" onclick="cinfohut(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>&nbsp;
	  <?php
	}?>
	
	<!-- LINK NEXT AND LAST -->
	<?php
	if($page == $jumlah_page || $get_jumlah['jumlah']==0){ ?>
	  <li class="page-item disabled " ><a class="page-link fa fa-chevron-right  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding : 3px 13px 3px 13px;border-radius:4px"></a></li>&nbsp;
	  <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;padding : 3px 13px 3px 13px">Akhir</a></li>&nbsp;
	   	<?php
	}else{ // Jika Bukan page terakhir
	  $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
	  ?>
	  <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="cinfohut(<?php echo $link_next; ?>, false)" style="cursor: pointer;padding : 3px 13px 3px 13px;border-radius:4px"></a></li>&nbsp;
	  <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="cinfohut(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;padding : 3px 13px 3px 13px">Akhir</a></li>&nbsp;
	  <?php
	}
	?>
  </ul>
</nav>

<?php
    mysqli_close($conpiut);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>