<?php 
  ob_start();
  session_start();
  include 'config.php';
  $kd_toko=$_SESSION['id_toko'];
  $id_user    = $_SESSION['id_user'];
  $nm_user    = $_SESSION['nm_user']; 
  	      
  $conext=opendtcek();
  $kd_paket=mysqli_real_escape_string($conext,$_POST['keyword']);
  $tgl_jual=mysqli_real_escape_string($conext,$_POST['tgl_jual']);
  $no_fakjual=mysqli_real_escape_string($conext,$_POST['no_fakjual']);
  $kd_pel=mysqli_real_escape_string($conext,$_POST['kd_pel']);
  $kd_bayar='TUNAI';
  $tgl_jt=mysqli_real_escape_string($conext,$_POST['tgl_jt']);

  //**cek jika potong stok atau tidak
  $q=mysqli_query($conext,"SELECT * FROM seting ORDER BY no_urut");
  while ($d=mysqli_fetch_assoc($q)){
	if ($d['nm_per']=="POTONG"){
	  $potong=$d['kode'];  
	}
	if ($d['nm_per']=="PROSES"){
	  $c_proses=$d['kode'];  
    }
  }
  mysqli_free_result($q);unset($d);

  //sementara discitem=0
  //$discitem=0;
  $ketjual='';
  //---------

  $cekpak=mysqli_query($conext,"SELECT paket_brg.*,SUM(beli_brg.stok_jual) AS stok,mas_brg.nm_brg FROM paket_brg 
	LEFT JOIN beli_brg ON paket_brg.kd_brg=beli_brg.kd_brg 
	LEFT JOIN mas_brg ON mas_brg.kd_brg=beli_brg.kd_brg 
	WHERE paket_brg.kd_paket='$kd_paket' AND paket_brg.kd_toko='$kd_toko'
	GROUP BY beli_brg.kd_brg");
  
  if (mysqli_num_rows($cekpak)>=1){
  	while ($datpak=mysqli_fetch_assoc($cekpak)){
  	  $kd_brg  = mysqli_escape_string($conext,$datpak['kd_brg']);
  	  $qty_brg = $datpak['qty_brg'];	
  	  $kd_sat  = $datpak['kd_sat'];
  	  $discpk  = $datpak['disc1'];
	  $stok    = $datpak['stok'];
      if ($stok>0){
	    //inisialisasi
		$hrg_jual=0;$jum_kem=0;$subtot=0;$diskon=0;$brg_klr=0;$d=false;
		$cekjual=mysqli_query($conext,"select * from mas_brg where kd_brg='$kd_brg' ");
		$databrg=mysqli_fetch_array($cekjual);
		if($kd_sat==$databrg['kd_kem3']){
		  $hrg_jual=$databrg['hrg_jum3'];
		  $jum_kem=$databrg['jum_kem3'];
		}
		if($kd_sat==$databrg['kd_kem2']){
		  $hrg_jual=$databrg['hrg_jum2'];
		  $jum_kem=$databrg['jum_kem2'];
		}
		if($kd_sat==$databrg['kd_kem1']){
		  $hrg_jual=$databrg['hrg_jum1'];
		  $jum_kem=$databrg['jum_kem1'];
		}
		unset($cekjual,$databrg); 
		//**--- bagian save data ----
		  $x=explode(';', carisatkecil2($kd_brg,$conext));
		  $sat_kecil=$x[0]; 
		  $jum_kecil=$x[1]; 

		  // cari satuan besar
		  $x=explode(";",carisatbesar2($kd_brg,$conext));
		  $bigsat=$x[0];
		  $bigjum=$x[1];
		//-------------------------

		// jika ada disckon tetap     
		$cekdisc=mysqli_query($conext,"SELECT * from disctetap where kd_brg='$kd_brg' order by no_urut");
		if (mysqli_num_rows($cekdisc)>=1){    
		  while($datadisc=mysqli_fetch_assoc($cekdisc)){
			if ($kd_sat==$datadisc['kd_sat'] && $datadisc['hrg_jual']>0){
			  if ($qty_brg>=$datadisc['lim_jual']){
				$hrg_jual=$datadisc['hrg_jual'];
			  } 
			}
		  } 
		}
		unset($datadisc);mysqli_free_result($cekdisc);

		// Insert dum_jual
		if ($c_proses==0){
		  $cek_it=mysqli_query($conext,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag FROM beli_brg 
			LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
			WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
			ORDER BY beli_brg.no_urut ASC");    
	    }
	    if ($c_proses==1){
		  $cek_it=mysqli_query($conext,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag FROM beli_brg 
		  LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
		  WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
		  ORDER BY beli_brg.no_urut DESC");    
	   	}
	   	if ($c_proses==2){
		  //cek hrg beli utk dijadikan rata
		  $qr=mysqli_query($conext,"SELECT hrg_beli,kd_brg,disc1,disc2,ppn,kd_sat FROM beli_brg WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko' AND stok_jual>0");
		  $xc=0;$jum_hrg=0;$hrgx=0;$hrg_rata=0;
		  while ($dr=mysqli_fetch_assoc($qr)){
		  	$xc++;
		    $disc1 = $dr['disc1']/100;
			$disc2 = $dr['disc2'];
			$ppn   = $dr['ppn']/100;
			if ($dr['disc1']=='0.00'){
				// echo gantiti($data['disc2']);
				$hrgx=($dr['hrg_beli']-$disc2);
			}else{
				$hrgx=($dr['hrg_beli'])-(($dr['hrg_beli'])*$disc1);
				$hrgx=round($hrgx,0);
			}
			if ($dr['disc1']=='0.00' && $dr['disc2']=='0'){
				$hrgx=$dr['hrg_beli'];
			} 
			$hrg_rata = ($hrgx/konjumbrg2($dr['kd_sat'],$kd_brg,$conext))*$jum_kem;
			$jum_hrg  = $jum_hrg+($hrg_rata+($hrg_rata*$ppn));        
		  }
		  $hrg_rata = round($jum_hrg/$xc,0);
		  mysqli_free_result($qr);unset($dr,$xc,$jum_hrg,$hrgx);

		  $cek_it=mysqli_query($conext,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag FROM beli_brg 
			LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
			WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
			ORDER BY beli_brg.no_urut ASC");    
	    }    
		
		$qty=0;$n_stok_jual=0;$stok=0;$jml=0;$hrg_beliawal=0;
		$qty=$qty_brg*$jum_kem; // konven      
		while ($cari=mysqli_fetch_assoc($cek_it)) {
		  if ($qty>0){	
			if ($qty>=$cari['stok_jual']){
			  if ($cari['stok_jual']-$qty<=0){
				$stok_jual=0; // utk replace pada beli_brg
				$jml_brg=$cari['stok_jual']/$jum_kem;
				
			  } else{
				$stok_jual=$cari['stok_jual']-$qty; // utk replace pada beli_brg
				$jml_brg=$qty/$jum_kem;
			  } 
			} else{
			  $stok_jual=$cari['stok_jual']-$qty;  
			  $jml_brg=$qty/$jum_kem;	         
			}
			//divinisi variable untuk dum_jual
			$disc1=$discpk;
			$disc2=mysqli_escape_string($conext,$cari['disc2']);
			$ppn=mysqli_escape_string($conext,$cari['ppn']/100);
				
			// ** jika proses potong maka semua input satuan besar output konversi ke satuan kecil
			$hrg_beli_k=0;$hrg_jual_k=0;
			$hrg_beli=$cari['hrg_beli'];
			if ($cari['disc1']=='0.00'){
			  $hrg_beliawal=($hrg_beli-$disc2);
			}else{
			  $hrg_beliawal=($hrg_beli)-(($hrg_beli)*$disc1);
			  $hrg_beliawal=round($hrg_beliawal,0);
			}
			if ($cari['disc1']=='0.0x0' && $cari['disc2']=='0'){
			  $hrg_beliawal=$hrg_beli;
			} 
			if ($c_proses==2){
			  $hrg_beli=$hrg_rata;
			} else {
			  $hrg_beli=($hrg_beliawal/konjumbrg2($cari['kd_sat'],$kd_brg,$conext))*$jum_kem;
			  $hrg_beli=$hrg_beli+($hrg_beli*$ppn);  
			}

			if($disc1=='0'){
			  $laba=($hrg_jual-$hrg_beli)*$jml_brg;  
			}else{
			  //konversi satuan terbesar
			  $laba=(($hrg_jual-$disc1)-$hrg_beli)*$jml_brg;  
			
			}
			//------------------------------------
			
			$qty     = $qty-$cari['stok_jual'];  
			$no_urut = $cari['no_urut'];
			$nm_brg  = $cari['nm_brg']." ".$ketjual;
			$ket     = $cari['ket'];
			$id_bag  = $cari['id_bag'];
			$consave = opendtcek();
			$d=mysqli_query($consave,"UPDATE beli_brg SET stok_jual='$stok_jual' WHERE no_urut='$no_urut'");
			
			$d=mysqli_query($consave,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$hrg_beli','$jml_brg','$disc1','0','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','$no_urut','$tgl_jt','$id_user','$nm_user',false,'$ket','','','$id_bag')");
			mysqli_close($consave); 
		  }	
	    }//while   
	    mysqli_free_result($cek_it);
		
		//update ms brg 
		$cekcon=opendtcek();
		$brg_klrawal=0;$jumbrg=0;
		$cekbrg=mysqli_query($cekcon,"SELECT * from mas_brg where kd_brg='$kd_brg'");
		$mas=mysqli_fetch_array($cekbrg);
		$brg_klrawal=$mas['brg_klr']+($qty_brg*$jum_kem);
		$jumbrg=$mas['jml_brg']-($qty_brg*$jum_kem);
		
		if ($mas['jml_brg']>0) {
		  $d=mysqli_query($cekcon,"UPDATE mas_brg SET brg_klr='$brg_klrawal',jml_brg='$jumbrg' WHERE kd_brg='$kd_brg' ");
		}else{
		  $d=false;
		}

		mysqli_free_result($cekbrg);
		unset($mas);    
		mysqli_close($cekcon);  
	  } else {
		?><script>alert("<?=$datpak['nm_brg']?>"+" stok tidak cukup");</script><?php 
	  } 
  	} // while
  }
?>
<script>caribrgjual(1,true);</script>
<?php 
  mysqli_close($conext);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>