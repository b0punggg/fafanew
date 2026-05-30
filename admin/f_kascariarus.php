<?php
	$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;border-color:white;">
	  <table class="table table-bordered table-sm table-striped table-hover" style="font-size:9pt; ">
	    <tr align="middle" class="yz-theme-l3">
	      <th>No.</th>
		  <th>TANGGAL</th>
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
	    
	    $param = mysqli_real_escape_string($connect, $keyword);
		if(!empty($param)){
			$x=explode(';', $param);
			$bln=$x[0];
			$thn=$x[1];
		}else{$bln=0;$thn=0;}
		$tglhi=$thn.'-'.$bln.'-01';
	    $no=0;$kas_awal=0;$kas_akhir=0;$susuk_uang=0;
		$totawal=0;$totakhir=0;$totmsk=0;$totklr=0;
		$tgl_pertama  = date('d',strtotime(date('Y-m-01', strtotime($tglhi))));
		$tgl_terakhir = date('d',strtotime(date('Y-m-t', strtotime($tglhi))));
        for ($x =0; $x <= $tgl_terakhir; $x++) {		
			$tanggal=$thn.'-'.$bln.'-'.$x;
			$sql1=mysqli_query($connect,"SELECT * FROM kas_harian WHERE tgl_kas='$tanggal' AND kd_toko='$kd_toko' ORDER BY tgl_kas ASC");
			if(mysqli_num_rows($sql1)>=1){
				while($data1=mysqli_fetch_assoc($sql1)){
					$no++;
					if($no==1){
						?>
						<tr>
						<td align="left"><?php echo $no ?></td>
						<td align="left"><?=gantitgl($data1['tgl_kas'])?></td>
						<td align="left">KAS</td>
						<td align="right"><?php echo 0 ?></td>
						<td align="right"><?php echo gantitides($data1['uang_kas']); ?></td>
						<td align="right"><?php echo 0 ?></td>
						<td align="right" style="color: blue"><b><?php echo gantitides($data1['uang_kas']); ?></b></td>
						</tr>
						<?php
						$kas_akhir=$kas_akhir+$data1['uang_kas'];
						$totmsk=$totmsk+$data1['uang_kas'];		
					}else{
						?>
						<tr>
						<td align="left"><?php echo $no ?></td>
						<td align="left"><?=gantitgl($data1['tgl_kas'])?></td>
						<td align="left">KAS</td>
						<td align="right"><?php echo gantitides($kas_akhir); ?></td>
						<td align="right"><?php echo gantitides($data1['uang_kas']); ?></td>
						<td align="right"><?php echo 0 ?></td>
						<td align="right" style="color: blue"><b><?php echo gantitides($data1['uang_kas']+$kas_akhir); ?></b></td>
						</tr>
						<?php
						$kas_akhir=$kas_akhir+$data1['uang_kas'];
						$totmsk=$totmsk+$data1['uang_kas'];					
					}
				}
				$totakhir=$totakhir+$kas_akhir;
			}
			unset($sql1,$data1);
			$cek2=mysqli_query($connect,"SELECT * FROM biaya_ops WHERE tgl_biaya='$tanggal' AND kd_toko='$kd_toko' ORDER BY tgl_biaya ASC");
			if(mysqli_num_rows($cek2)>=1){
				while($data2=mysqli_fetch_assoc($cek2)){
					$no++;
					?>
					<tr>
					<td align="left"><?php echo $no ?></td>
					<td align="left"><?=gantitgl($data2['tgl_biaya'])?></td>
					<td align="left">BIAYA - <?=ucwords(strtolower($data2['ket_biaya']))?></td>
					<td align="right"><?php echo gantitides($kas_akhir); ?></td>
					<td align="right"><?= 0 ?></td>
					<td align="right"><?=gantitides($data2['nominal'])?></td>
					<td align="right" style="color: blue"><b><?php echo gantitides($kas_akhir-$data2['nominal']); ?></b></td>
					</tr>
					<?php
					$kas_akhir=$kas_akhir-$data2['nominal'];
					$totklr=$totklr+$data2['nominal'];	

				}
			}

	    }
	    ?>
	    <tr class="yz-theme-l1">
			<th style="text-align: center;" colspan="4">KAS AKHIR</th>	
			<!-- <th style="text-align: right;"><?=gantitides($totawal)?></th>	 -->
			<th style="text-align: right;"><?=gantitides($totmsk)?></th>
			<th style="text-align: right;"><?=gantitides($totklr)?></th>	
			<th style="text-align: right;"><?=gantitides(($totmsk-$totklr)+$totawal)?></th>
	    </tr>
	  </table>
	</div>

	<?php 
	  if ($no>=1){
	  	?>
	  	<script>document.getElementById("ket_rec1").innerHTML=" Total Data "+<?php echo $no ?>+" Record" </script>	
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
		<?php if(!empty($param)){ ?>
	      <a href="f_kas_cetak.php?param=<?=$param?>" type="button" class="form-control yz-theme-d2 w3-hover-shadow" target="_blank" style="font-size: 10pt;text-align: center"><i class="fa fa-print" ></i> Cetak</a>
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