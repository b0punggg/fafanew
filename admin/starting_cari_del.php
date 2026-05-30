<?php
  $keyword = $_POST['keyword'];
  ob_start();
?>

<div class="table-responsive" style="overflow:auto;">
  <table id="table" class="table-hover hrf_res3" style="width: 100%;border:none;"> 
    <?php
    include "config.php";
    session_start();
    $kd_toko=$_SESSION['id_toko']; 
    $condel=opendtcek();
    $page = (isset($_POST['page']))? $_POST['page'] : 1;
    $limit = 5; // Jumlah data per halamannya
    $limit_start = ($page - 1) * $limit;

    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
      
      $params = mysqli_real_escape_string($condel, $keyword);
      $param='%'.$params.'%';   
      if ($params=="") {  
        $sql=mysqli_query($condel,"SELECT * FROM file_log
          LEFT JOIN file_log_cari ON file_log.no_fak=file_log_cari.no_fakjual AND file_log.ket=file_log_cari.ket 
          WHERE file_log.konfir='T'  
          GROUP BY file_log.no_fak,file_log.ket
          ORDER BY file_log.jam ASC LIMIT $limit_start, $limit ");

        $sql2=mysqli_query($condel, "SELECT COUNT(*) AS jumlah FROM file_log
              WHERE konfir='T'");   
      } else {
        $sql=mysqli_query($condel,"SELECT * FROM file_log
        LEFT JOIN file_log_cari ON file_log.no_fak=file_log_cari.no_fakjual AND file_log.ket=file_log_cari.ket 
        WHERE file_log.no_fak like '$param' AND file_log.konfir='T' 
        GROUP BY file_log.no_fak,file_log.ket
        ORDER BY file_log.jam ASC LIMIT $limit_start, $limit ");  
        
        $sql2=mysqli_query($condel, "SELECT COUNT(*) AS jumlah FROM file_log WHERE konfir='T' AND no_fak LIKE '$param'");
      } 
      $get_jumlah = mysqli_fetch_array($sql2);
    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
      
    }
    $no=$limit_start;
    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
      $no++;
      $xs=explode(" ",$data['jam']);
      $tgl=gantitgl($xs[0]);$jam=$xs[1];
      // if($data['ket']=='Hapus Item Jual'){
      //   $ketnews=" Faktur : ".$data['no_fak']." , Tgl : ".$tgl." , Jam : ".$jam." , Brg : ".$data['nm_brg']." , Jml : ".gantiti(round($data['qty'])).$data['satuan']." , Hrg : ".gantiti(round($data['hrg_jual'],0))." , Disc: ".gantiti(round($data['discount'],0));    
      // }else if($data['ket']=='Hapus Nota Jual'){
        $ketnews=" Faktur : ".$data['no_fak'].", Tgl : ".$tgl.", Jam : ".$jam;   
      // }
      
      $idcari=$data['no_fak'].';'.$data['ket'].';'.$data['kd_toko'];
      ?>
      <tr>
        <td colspan="2" style="padding: 5px;border:none;">
          <span class="fa-stack fa-lg w3-text-blue" style="font-size:9pt">
            <i class="fa fa-square-o fa-stack-2x"></i>
            <i class="fa fa-exclamation fa-stack-1x"></i>
          </span>
        
          <a href="#" onclick="document.getElementById('info').style.display='none'; cari_log('<?= $idcari?>')" class="w3-text-white" style="cursor: pointer;" title="Click Review"><i style="color:red;font-weight:bold;"><?=$no.". ".strtoupper($data['ket'])?></i>&nbsp;<?=$ketnews?></a>
        </td>
      </tr> 
      <?php
    } ?>
  </table>
</div>
<nav aria-label="Page navigation example" style="margin-top:5px;font-size: 8pt;z-index: 1">
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
	  <li><a class="page-link yz-theme-d1" style="cursor: pointer;padding : 3px 13px 3px 13px" href="javascript:void(0);" onclick="cinfodel(1, false)">Awal</a></li>&nbsp;
	  <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;padding : 3px 13px 3px 13px;border-radius:4px" href="javascript:void(0);" onclick="cinfodel(<?php echo $link_prev; ?>, false)"></a></li>&nbsp;
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
	  <li class="page-item" <?php echo $link_active; ?>><a class="page-link w3-hover-shadow w3-border-blue" href="javascript:void(0);" style="cursor: pointer;padding : 3px 13px 3px 13px;border-radius:5px" onclick="cinfodel(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>&nbsp;
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
	  <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="cinfodel(<?php echo $link_next; ?>, false)" style="cursor: pointer;padding : 3px 13px 3px 13px;border-radius:4px"></a></li>&nbsp;
	  <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="cinfodel(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;padding : 3px 13px 3px 13px">Akhir</a></li>&nbsp;
	  <?php
	}
	?>
  </ul>
</nav>

<?php
  mysqli_close($condel);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>