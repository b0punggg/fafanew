<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;border-color:white;">
	  <table class="table table-bordered table-sm table-striped table-hover" style="font-size:9pt; ">
	    <tr align="middle" class="yz-theme-l3">
	      <th>No.</th>
	      <th>KETERANGAN</th>
	      <th>KAS AWAL</th>
	      <th>MASUK</th>
	      <th>KELUAR</th>
	      <th>KAS AKHIR</th>
	      
	    </tr>
	    <?php
	    include "config.php";
	    session_start();
	    $connect=opendtcek();
	    $kd_toko=$_SESSION['id_toko'];
	    $oto=$_SESSION['kodepemakai'];
	    $page = (isset($_POST['page']))? $_POST['page'] : 1;
	    $limit = 8; // Jumlah data per halamannya
	    $limit_start = ($page - 1) * $limit;
	    // echo '$limit_start='.$limit_start;

	    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
	    	$param = mysqli_real_escape_string($connect, $keyword);
	    	if(!empty($param)){
	    	$x=explode(';', $param);
	    	$tgl_kas=$x[0];
	    	$uang_kas=$x[1];
	    	$id_user=$x[2];
	        }else{$tgl_kas='0000-00-00';$uang_kas='0';}
	    	//$param='%'.$params.'%';  	
	    	// echo $param."<br>";
	    	// echo $tgl_kas."<br>";
	    	// echo $uang_kas."<br>";
	      $totb=0;$tots=0;	
          if ($param=="") {	 
          	  $sql = mysqli_query($connect, "SELECT tgl_jual,bayar_uang,susuk_uang FROM mas_jual
          	  	     WHERE kd_toko=''
          	  	     ORDER BY tgl_jual ASC LIMIT $limit_start, $limit");
          	  $sql1 =mysqli_query($connect, "SELECT tgl_tran,byr_hutang,no_fakjual FROM mas_jual_hutang
            	WHERE tgl_tran = '$tgl_kas' AND kd_toko='' ORDER BY no_urut ASC");
			  $sql2 =mysqli_query($connect, "SELECT * FROM biaya_ops
			  WHERE tgl_biaya = '$tgl_kas' AND kd_toko='$kd_toko'   ORDER BY id ASC");	
            
          }
          else {
          	// $s =mysqli_query($connect, "SELECT SUM(bayar_uang) AS totb,SUM(susuk_uang) AS tots FROM mas_jual WHERE tgl_jual = '$tgl_kas' AND kd_toko='$kd_toko' AND id_user='$id_user'");
           //  $d=mysqli_fetch_assoc($s);
           //  $totb=$d['totb'];$tots=$d['tots'];
            // unset($d);mysqli_free_result($s);  
			
            // if ($oto='1') {
            //   $sql =mysqli_query($connect, "SELECT mas_jual.tgl_jual,mas_jual.no_fakjual,mas_jual.bayar_uang,mas_jual.susuk_uang FROM mas_jual
            // 	 WHERE mas_jual.tgl_jual = '$tgl_kas' AND mas_jual.kd_toko='$kd_toko' AND mas_jual.kd_bayar='TUNAI' ORDER BY no_urut ASC");
            // } else {
            //   $sql =mysqli_query($connect, "SELECT mas_jual.tgl_jual,mas_jual.no_fakjual,mas_jual.bayar_uang,mas_jual.susuk_uang FROM mas_jual
            // 	 WHERE mas_jual.tgl_jual = '$tgl_kas' AND mas_jual.kd_toko='$kd_toko' AND mas_jual.id_user='$id_user'  ORDER BY no_urut ASC");	
            // }
           
            // $sql1 =mysqli_query($connect, "SELECT mas_jual_hutang.no_fakjual,mas_jual_hutang.tgl_tran,mas_jual_hutang.byr_hutang,mas_jual_hutang.kd_pel,pelanggan.nm_pel FROM mas_jual_hutang
            // 	LEFT JOIN pelanggan ON pelanggan.kd_pel=mas_jual_hutang.kd_pel
            // 	WHERE mas_jual_hutang.tgl_tran = '$tgl_kas' AND mas_jual_hutang.kd_toko='$kd_toko' ORDER BY mas_jual_hutang.no_urut ASC");
			$sql2 =mysqli_query($connect, "SELECT * FROM biaya_ops
			WHERE tgl_biaya = '$tgl_kas' AND kd_toko='$kd_toko'   ORDER BY id ASC");	
          }	
	    }
        
	    $no=$limit_start;$kas_awal=0;$kas_akhir=0;$susuk_uang=0;
		    $totawal=0;$totakhir=0;$totmsk=0;$totklr=0;
	    

	    

		//biaya
        while($data2 = mysqli_fetch_array($sql2)){ // Ambil semua data dari hasil eksekusi $sql
			$no++;
			$susuk_uang=0;
				if($no==1){
					 $kas_awal=$uang_kas;
					 $kas_akhir=$kas_awal-($data2['nominal']-$susuk_uang);
					 $totawal=$totawal+$kas_awal;
					 $totakhir=$totakhir+$kas_akhir;
				}else{
				  $kas_awal=$kas_akhir;
				  $kas_akhir=$kas_awal-($data2['nominal']-$susuk_uang);
				  $totawal=$totawal+$kas_awal;
					 $totakhir=$totakhir+$kas_akhir;
				}
				$totmsk=$totmsk+$susuk_uang;
				$totklr=$totklr+$data2['nominal'];
		   ?>
		   <tr>
			 <td align="left"><?php echo $no ?></td>
			 <td align="left">BIAYA-<?=strtoupper($data2['ket_biaya'])?></td>
			 <td align="right"><?php echo gantitides($kas_awal); ?></td>
			 <td align="right"><?php echo gantitides($susuk_uang); ?></td>
			 <td align="right"><?php echo gantitides($data2['nominal']); ?></td>
			 <td align="right" style="color: blue"><b><?php echo gantitides($kas_akhir); ?></b></td>
		   </tr>
		   <?php
		 } 
	    ?>
	    <tr class="yz-theme-l1">
			<th style="text-align: center;" colspan="3">KAS AKHIR</th>	
			<!-- <th style="text-align: right;"><?=gantitides($totawal)?></th>	 -->
			<th style="text-align: right;"><?=gantitides($totmsk)?></th>
			<th style="text-align: right;"><?=gantitides($totklr)?></th>	
			<th style="text-align: right;"><?=gantitides(($totmsk-$totklr)+$uang_kas)?></th>
			    
	    </tr>
	  </table>
	</div>

	<?php 
	  if ($totb>=1){
	  	?>
	  	<script>document.getElementById("ket_rec1").innerHTML=" Total Data "+<?php echo $get_jumlah['jumlah'] ?>+" Record" </script>	
	  	<?php
	  }else {
	  	?>
	  	<script>document.getElementById("ket_rec1").innerHTML=" Belum Ada Data" </script>	
	  	<?php
	  }
	?>

	<div class="row ">
      <div class="col-sm-9">
      	
      </div>
    	
	  <div class="col w3-container">
		<?php if($tgl_kas <> "0000-00-00"){ ?>
	      <a href="f_kas_cetak.php?param=<?php echo $tgl_kas.';'.$uang_kas ?>" type="button" class="form-control yz-theme-d2 w3-hover-shadow" target="_blank" style="font-size: 10pt;text-align: center"><i class="fa fa-print" ></i> Cetak</a>
	    <?php } ?>
	  </div>	
	  	
	</div>
		

<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>