<?php
$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
ob_start();
include 'config.php';
session_start();

$connect=opendtcek();  
$kd_toko=$_SESSION['id_toko'];
?>

<div class="table-responsive " style="overflow-x:auto;border-style: ridge;">
	<div class="w3-center">RATA-RATA HARGA BELI BARANG</div>
	<table class="table table-bordered table-sm table-hover" style="font-size:10pt;background: linear-gradient(180deg, #FAFAD2 10%, white 90%);border-collapse: collapse;white-space: nowrap; ">

    <thead >  
  	  <tr align="middle" class="yz-theme-d1">
  		<th>No.</th>
  		<th>NAMA BARANG</th>	
        <th >HARGA BELI</th>    
        <th colspan="3">KONVERSI JML STOK & HARGA BARANG</th>
  	</tr>
  </thead>

   	<?php
		$kd_toko=$_SESSION['id_toko'];
		$page = (isset($_POST['page']))? $_POST['page'] : 1;
		$limit = 15; // Jumlah data per halamannya
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
			  } else{
			  	//$xada=strpos($params,"=");
			  	$pecah=explode('=', $params);
			    $kunci=$pecah[0];
			    $kunci2=$pecah[1];
			    $params=$kunci." = '".trim($kunci2)."'";
			  }	
		  	  
			}else{
			  $kunci='';
			  $kunci2='';	
			}
			//echo "$params";
			if ($params=="") 
			{	 
			    $sql =mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.kd_brg,beli_brg.kd_sup,beli_brg.hrg_beli,beli_brg.stok_jual,supplier.nm_sup,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3
				    FROM beli_brg 
				    LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
				    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
					  WHERE beli_brg.kd_toko=''
				    ORDER BY beli_brg.no_urut ASC LIMIT $limit_start, $limit");
				$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE kd_toko=''");
			}
			else 
			{
				$sql =mysqli_query($connect, "SELECT SUM(beli_brg.stok_jual) AS jumstok,AVG(beli_brg.hrg_beli) AS jumhrg, beli_brg.ket,beli_brg.tgl_fak,beli_brg.kd_brg,beli_brg.kd_sup,supplier.nm_sup,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3
				    FROM beli_brg 
				    LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
				    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
					WHERE $params AND INSTR(beli_brg.ket,'MUTASI') = 0
					-- and beli_brg.kd_toko='$kd_toko'
					GROUP BY beli_brg.kd_brg ORDER BY count(*)
					");
				$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg 
					LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
				    LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
					WHERE $params AND INSTR(beli_brg.ket,'MUTASI') = 0");
			}	
            $get_jumlah = mysqli_fetch_array($sql2);  		    
		}else
		   { // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
			$sql = mysqli_query($connect, "SELECT mas_brg.no_urut,mas_brg.kd_bar,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.jml_brg,mas_brg.brg_msk,mas_brg.brg_klr
				FROM mas_brg 
					ORDER BY mas_brg.no_urut ASC LIMIT $limit_start, $limit");
			$sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg ");
			$get_jumlah = mysqli_fetch_array($sql2);
		}

		$no=$limit_start;$tot=0;$no_fak='';$tgl_fak='';$disc1=0;$disc2=0;$jmlsub=0;
		$kd_sup='';$nm_sup='';$brg_msk_hi=0.00;$brg_klr_hi=0;
		while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
			$no++;
	        $brg_msk_hi=$data['jumstok'];
	        //echo '$brg_msk='.$brg_msk_hi.'<br>';
	        if ($data['jum_kem1']>0) {
	          $stok1=gantitides($brg_msk_hi/$data['jum_kem1']).' '.$data['nm_kem1'];	
	        }else{
	          $stok1='NONE';
	        }
	        if ($data['jum_kem2']>0) {
	          $stok2=gantitides($brg_msk_hi/$data['jum_kem2']).' '.$data['nm_kem2'];  
	        }else{
	          $stok2='NONE';
	        }
	        if ($data['jum_kem3']>0) {
	          $stok3=gantitides($brg_msk_hi/$data['jum_kem3']).' '.$data['nm_kem3'];  	
	        }else{
	          $stok3='NONE';
	        }
            ?> 
		  <tr>
			<td align="right" class="yz-theme-l4"><?php echo $no ?></td>
			<td align="left" class="yz-theme-l4">&nbsp;<?php echo $data['nm_brg']; ?></td>
			<td align="right" class="yz-theme-l1"><?php echo gantitides($data['jumhrg']); ?>&nbsp;</td>
			<td align="right" class="yz-theme-l2"><?php echo $stok1.' -@ '.gantitides($data['hrg_jum1']);; ?></td>
			<td align="right" class="yz-theme-l3"><?php echo $stok2.' -@ '.gantitides($data['hrg_jum2']); ?></td>
			<td align="right" class="yz-theme-l4"><?php echo $stok3.' -@ '.gantitides($data['hrg_jum3']); ?></td>
		  </tr>

		<?php
		}
		?>
	</table> 
</div>

<script>
	function cr_nmbrg(nmbrg) {
		nmbrg="mas_brg.nm_brg like "+nmbrg;
		return nmbrg;
	}
</script>
<?php
  mysqli_close($connect);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>