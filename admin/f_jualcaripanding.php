<?php
ob_start();
?>

<div class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;border-style: ridge;border-color: white">
  <table class="table-hover" style="font-size:9pt;width: 100%">
    <tr align="middle" class="yz-theme-l1">
      <th>No.</th>
      <th>STRUK/NOTA</th>
      <th>TANGGAL</th>
      <th>JML. ITEM</th>
      <th>TOT. BELANJA</th>
      <th>OPSI</th>
    </tr>
    <?php
    include "config.php";
    session_start();
    $connect=opendtcek();
    $kd_toko=$_SESSION['id_toko'];
    $id_user=$_SESSION['id_user'];
    $page = (isset($_POST['page']))? $_POST['page'] : 1;

    $limit = 10; // Jumlah data per halamannya

    $limit_start = ($page - 1) * $limit;
    // echo '$limit_start='.$limit_start;
    if($_SESSION['kodepemakai']=='2'){
      $cid='';
    } else{
      $cid='AND id_user='.$id_user;
    }
    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
    	// echo $tgl_fak;
       $sql = mysqli_query($connect, "SELECT tgl_jual,no_fakjual,kd_pel,sum(hrg_jual*qty_brg) as totjual,count(*) as jmlitem FROM dum_jual WHERE panding=true AND kd_toko='$kd_toko' $cid group by no_fakjual order by no_urut ASC LIMIT $limit_start, $limit");

       //$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM dum_jual WHERE kd_toko='$kd_toko' AND panding=true ORDER BY no_urut");
       $xnum=0;
       $sqlcek = mysqli_query($connect, "SELECT tgl_jual,no_fakjual,kd_pel,sum(hrg_jual*qty_brg) as totjual,count(*) as jmlitem FROM dum_jual WHERE panding=true AND kd_toko='$kd_toko' $cid group by no_fakjual order by no_urut ASC");
       while($datas=mysqli_fetch_array($sqlcek)){
       $xnum++; 
       }
       unset($datas,$sqlcek);
       $get_jumlah=$xnum; 
       
    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
      // $id_apt=$_SESSION['id_apt'];
      $sql = mysqli_query($connect, "SELECT tgl_jual,no_fakjual,kd_pel,sum(hrg_jual*qty_brg) as totjual,count(*) as jmlitem FROM dum_jual WHERE panding=true AND kd_toko='$kd_toko' $cid group by no_fakjual order by no_urut ASC LIMIT $limit_start, $limit");

       //$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM dum_jual WHERE kd_toko='$kd_toko' AND panding=true ORDER BY no_urut");
       $xnum=0;
       $sqlcek = mysqli_query($connect, "SELECT tgl_jual,no_fakjual,kd_pel,sum(hrg_jual*qty_brg) as totjual,count(*) as jmlitem FROM dum_jual WHERE panding=true AND kd_toko='$kd_toko' $cid group by no_fakjual order by no_urut ASC");
       while($datas=mysqli_fetch_array($sqlcek)){
       $xnum++; 
       }
       unset($datas,$sqlcek);
       $get_jumlah=$xnum; 

    }
    $no=0;
    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
      $no++;
      $no_fakjual=$data['no_fakjual'];$tgl_jual=$data['tgl_jual'];
    ?>
      <tr>
        <td align="right"><?php echo $no ?></td>
        <td align="left"><?php echo $data['no_fakjual']; ?></td>
        <td align="left"><?php echo gantitgl($data['tgl_jual']); ?></td>
        <td align="right"><?php echo gantitides($data['jmlitem']); ?></td>
        <td align="right"><?php echo gantitides($data['totjual']); ?></td>
        <td>
          	<button onclick="
               setunpanding('<?=$no_fakjual?>','<?=$tgl_jual?>');
          	   document.getElementById('no_fakjual').value='<?=mysqli_escape_string($connect,$data['no_fakjual']) ?>';
          	   document.getElementById('tgl_fakjual').value='<?=mysqli_escape_string($connect,$data['tgl_jual']) ?>';
          	   document.getElementById('kd_pel').value='<?=mysqli_escape_string($connect,$data['kd_pel']) ?>';
          	   document.getElementById('form-panding').style.display='none';
          	   document.getElementById('no_fakjual').focus();
               document.getElementById('no_fakjual').blur();
          	   
          	   " class="btn-primary fa fa-edit" style="cursor: pointer; font-size: 12pt" title="Edit Data">
            </button>	    

           <?php 
          //  $param=mysqli_escape_string($connect,$data['no_fakjual']); 
           ?>
           <!-- <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){hapuspanding('<?=$param?>');document.getElementById('form-panding').style.display='none';}" class="btn-danger fa fa-trash" style="cursor: pointer;font-size: 12pt" title="Hapus Data"></button> -->
           
        </td>    
      </tr>
      
    <?php
    }
    ?>
    
  </table> 
</div>

	<div class="w3-border">
		<nav  aria-label="Page navigation example" style="margin-top:15px;font-size: 9pt">
		  <ul class="pagination justify-content-center hrf_arial">
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
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinopanding(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carinopanding(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NUMBER -->
		    <?php
		    $jumlah_page = ceil($get_jumlah / $limit); // Hitung jumlah halamannya
		    $jumlah_number = 1; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
		    $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
		    $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
		    
		    for($i = $start_number; $i <= $end_number; $i++){
		      $link_active = ($page == $i)? ' class="active"' : '';
		    ?>
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinopanding(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NEXT AND LAST -->
		    <?php
		    if($page == $jumlah_page || $get_jumlah==0){ // Jika page terakhir
		    ?>
		      <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&raquo;</a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
		    <?php
		    }else{ // Jika Bukan page terakhir
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinopanding(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinopanding(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>	
<?php
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>