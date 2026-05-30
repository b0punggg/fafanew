<?php 
ob_start();
include 'config.php';
session_start();
mysqli_report(MYSQLI_REPORT_OFF);

$consim=opendtcek();
if(!$consim){
  echo json_encode(array('hasil'=>'<script>popnew_error("Koneksi database gagal");</script>'));
  exit;
}
$kd_toko=$_SESSION['id_toko'];
$no_tran=mysqli_real_escape_string($consim,$_POST['keyword1']);
$tgl_tran=mysqli_real_escape_string($consim,$_POST['keyword2']);
$kembali=mysqli_real_escape_string($consim,$_POST['keyword3']);
try {

$returMasCols = array();
$returCols = array();
$cekColsMas = mysqli_query($consim, "SHOW COLUMNS FROM retur_beli_mas");
if($cekColsMas){
  while($rowColsMas = mysqli_fetch_assoc($cekColsMas)){
    $returMasCols[$rowColsMas['Field']] = true;
  }
  mysqli_free_result($cekColsMas);
}
$cekColsRetur = mysqli_query($consim, "SHOW COLUMNS FROM retur_beli");
if($cekColsRetur){
  while($rowColsRetur = mysqli_fetch_assoc($cekColsRetur)){
    $returCols[$rowColsRetur['Field']] = true;
  }
  mysqli_free_result($cekColsRetur);
}
$lastSqlError = '';

if(!function_exists('num_only')){
  function num_only($v){
    if (is_numeric($v)) {
      return floatval($v);
    }
    if (is_string($v)) {
      $v = trim($v);
      if ($v === '') return 0;
      $v = str_replace(array('.', ','), array('', '.'), $v);
      return is_numeric($v) ? floatval($v) : 0;
    }
    return 0;
  }
}
// echo '$no_tran='.$no_tran.'<br>';
// echo '$tgl_tran='.$tgl_tran.'<br>';
// echo '$kembali='.$kembali.'<br>';
$hrg_beli=0;$subtot=0;$totpot=0;$tottax=0;$totretur=0;$totawal=0;$jml_brgakhir=0;$jml_brg_klr=0;$no=0;$d=false;
//apakah sudah disave atau belum
$cekmas=mysqli_query($consim,"SELECT * FROM retur_beli_mas WHERE kd_toko='$kd_toko' AND no_retur='$no_tran' AND tgl_retur='$tgl_tran'");
if($cekmas===false){
  $lastSqlError = mysqli_error($consim);
} else if (mysqli_num_rows($cekmas)>=1){
   $datmas=mysqli_fetch_assoc($cekmas);
   $no_urutmas=$datmas['no_urut'];
   $cek=mysqli_query($consim,"SELECT * FROM retur_beli WHERE kd_toko='$kd_toko' AND no_retur='$no_tran' AND tgl_retur='$tgl_tran'");
	if($cek===false){
    $lastSqlError = mysqli_error($consim);
  } else if (mysqli_num_rows($cek)>=1){
	  while ($datcek=mysqli_fetch_assoc($cek)){
	  	//konstanta total
      $hrg_beli_num = num_only(isset($datcek['hrg_beli']) ? $datcek['hrg_beli'] : 0);
      $disc_num = num_only(isset($datcek['disc']) ? $datcek['disc'] : 0);
      $tax_num = num_only(isset($datcek['tax']) ? $datcek['tax'] : 0);
      $qty_num = num_only(isset($datcek['qty_brg']) ? $datcek['qty_brg'] : 0);
	    $disc=$hrg_beli_num-($hrg_beli_num*($disc_num/100));
		$tax=($hrg_beli_num*($tax_num/100));
		$subtot=$subtot+round(($disc+$tax)*$qty_num,2);
		$totpot=$totpot+($hrg_beli_num*($disc_num/100));
		$tottax=$tottax+$tax;$totretur=$totretur+$subtot;
		$totawal=$totawal+$hrg_beli_num;
		$no++;

	    //proses cek pada beli_brg dan mas_brg
	    $kd_brg=$datcek['kd_brg'];
	    $no_urut=$datcek['no_urut'];
	    $no_item=$datcek['no_item'];
	    $kd_sup=$datcek['kd_sup'];
      $konj = num_only(konjumbrg($datcek['kd_sat'],$kd_brg));
      if($konj == 0){ $konj = 1; }
	    $jml_retur=$qty_num*$konj;
	    $stok_akhir=num_only(caristokbeli($no_item,$kd_brg))-$jml_retur;
	    $x=explode(';',caristokmas($kd_brg));
	    $jml_brgakhir=num_only(isset($x[0]) ? $x[0] : 0)-$jml_retur;
	    $jml_brg_klr=num_only(isset($x[2]) ? $x[2] : 0)+$jml_retur;

	    //proses simpan pengembalian
      if(isset($returCols['kembali'])){
	      $d=mysqli_query($consim,"UPDATE retur_beli SET kembali='$kembali' where no_urut='$no_urut'");
        if($d===false){ $lastSqlError = mysqli_error($consim); break; }
      }
	    //$d=mysqli_query($consim,"UPDATE beli_brg SET jml_stok='$stok_akhir' where no_urut='$no_item'");
	    //$d=mysqli_query($consim,"UPDATE mas_brg SET jml_brg='$jml_brgakhr',brg_klr='$jml_brg_klr' where kd_brg='$kd_brg'");
	  }	
	  //proses simpan pada retur_beli_mas
    $setMas = array();
    if(isset($returMasCols['tot_qty'])){ $setMas[] = "tot_qty='$no'"; }
    if(isset($returMasCols['tot_hrg_beli'])){ $setMas[] = "tot_hrg_beli='$totawal'"; }
    if(isset($returMasCols['tot_potongan'])){ $setMas[] = "tot_potongan='$totpot'"; }
    if(isset($returMasCols['tot_tax'])){ $setMas[] = "tot_tax='$tottax'"; }
    if(isset($returMasCols['tot_retur'])){ $setMas[] = "tot_retur='$subtot'"; }
    if(isset($returMasCols['tot_kembali'])){ $setMas[] = "tot_kembali='$kembali'"; }
    if(isset($returMasCols['kembali'])){ $setMas[] = "kembali='$kembali'"; }
    if(count($setMas) > 0){
	    $d=mysqli_query($consim,"UPDATE retur_beli_mas SET ".implode(',', $setMas)." WHERE no_urut='$no_urutmas'");
      if($d===false){ $lastSqlError = mysqli_error($consim); }
    }
	}     
	unset($cek,$datcek);
} else {
	$cek=mysqli_query($consim,"SELECT * FROM retur_beli WHERE kd_toko='$kd_toko' AND no_retur='$no_tran' AND tgl_retur='$tgl_tran'");
	if($cek===false){
    $lastSqlError = mysqli_error($consim);
  } else if (mysqli_num_rows($cek)>=1){
	  while ($datcek=mysqli_fetch_assoc($cek)){
	  	//konstanta total
      $hrg_beli_num = num_only(isset($datcek['hrg_beli']) ? $datcek['hrg_beli'] : 0);
      $disc_num = num_only(isset($datcek['disc']) ? $datcek['disc'] : 0);
      $tax_num = num_only(isset($datcek['tax']) ? $datcek['tax'] : 0);
      $qty_num = num_only(isset($datcek['qty_brg']) ? $datcek['qty_brg'] : 0);
	    $disc=$hrg_beli_num-($hrg_beli_num*($disc_num/100));
		$tax=($hrg_beli_num*($tax_num/100));
		$subtot=$subtot+round(($disc+$tax)*$qty_num,2);
		$totpot=$totpot+($hrg_beli_num*($disc_num/100));
		$tottax=$tottax+$tax;$totretur=$totretur+$subtot;
		$totawal=$totawal+$hrg_beli_num;
		$no++;	

	    //proses cek pada beli_brg dan mas_brg
	    $kd_brg=$datcek['kd_brg'];
	    $no_urut=$datcek['no_urut'];
	    $no_item=$datcek['no_item'];
	    $kd_sup=$datcek['kd_sup'];
      $konj = num_only(konjumbrg($datcek['kd_sat'],$kd_brg));
      if($konj == 0){ $konj = 1; }
	    $jml_retur=$qty_num*$konj;
	    $stok_akhir=num_only(caristokbeli($no_item,$kd_brg))-$jml_retur;
	    //echo '$stok_akhir='.$stok_akhir.'<br>';
	    $x=explode(';',caristokmas($kd_brg));
	    $jml_brgakhir=num_only(isset($x[0]) ? $x[0] : 0)-$jml_retur;
	    $jml_brg_klr=num_only(isset($x[2]) ? $x[2] : 0)+$jml_retur;

	    //proses simpan pengembalian
      if(isset($returCols['kembali'])){
	    $d=mysqli_query($consim,"UPDATE retur_beli SET kembali='$kembali' where no_urut='$no_urut'"); 
      if($d===false){ $lastSqlError = mysqli_error($consim); break; }
      }
	    $d=mysqli_query($consim,"UPDATE beli_brg SET stok_jual='$stok_akhir' where no_urut='$no_item'");
      if($d===false){ $lastSqlError = mysqli_error($consim); break; }
	    $d=mysqli_query($consim,"UPDATE mas_brg SET jml_brg='$jml_brgakhir',brg_klr='$jml_brg_klr' where kd_brg='$kd_brg'");
      if($d===false){ $lastSqlError = mysqli_error($consim); break; }
	  }	
	  //proses simpan pada retur_beli_mas
    $insertCols = array();
    $insertVals = array();
    if(isset($returMasCols['tgl_retur'])){ $insertCols[] = 'tgl_retur'; $insertVals[] = "'$tgl_tran'"; }
    if(isset($returMasCols['no_retur'])){ $insertCols[] = 'no_retur'; $insertVals[] = "'$no_tran'"; }
    if(isset($returMasCols['kd_sup'])){ $insertCols[] = 'kd_sup'; $insertVals[] = "'$kd_sup'"; }
    if(isset($returMasCols['tot_qty'])){ $insertCols[] = 'tot_qty'; $insertVals[] = "'$no'"; }
    if(isset($returMasCols['tot_hrg_beli'])){ $insertCols[] = 'tot_hrg_beli'; $insertVals[] = "'$totawal'"; }
    if(isset($returMasCols['tot_potongan'])){ $insertCols[] = 'tot_potongan'; $insertVals[] = "'$totpot'"; }
    if(isset($returMasCols['tot_tax'])){ $insertCols[] = 'tot_tax'; $insertVals[] = "'$tottax'"; }
    if(isset($returMasCols['tot_retur'])){ $insertCols[] = 'tot_retur'; $insertVals[] = "'$subtot'"; }
    if(isset($returMasCols['tot_kembali'])){ $insertCols[] = 'tot_kembali'; $insertVals[] = "'$kembali'"; }
    if(isset($returMasCols['kembali'])){ $insertCols[] = 'kembali'; $insertVals[] = "'$kembali'"; }
    if(isset($returMasCols['kd_toko'])){ $insertCols[] = 'kd_toko'; $insertVals[] = "'$kd_toko'"; }

    if(count($insertCols) > 0){
      $d=mysqli_query($consim,"INSERT INTO retur_beli_mas (".implode(',', $insertCols).") VALUES(".implode(',', $insertVals).")");
      if($d===false){ $lastSqlError = mysqli_error($consim); }
    } else {
      $d=false;
      $lastSqlError = 'Kolom tabel retur_beli_mas tidak sesuai.';
    }
	}
}
unset($cekmas,$datmas);

if ($d) {
 ?> <script>popnew_ok("Simpan berhasil");kosongkan();carinoretur(1,true);</script> <?php   	
}else {
  $errMsg = ($lastSqlError!='') ? addslashes($lastSqlError) : 'Simpan gagal';
  ?> <script>popnew_error("Simpan gagal: <?=$errMsg?>");kosongkan();carinoretur(1,true);</script> <?php
}
} catch (Throwable $e) {
  $errMsg = addslashes($e->getMessage());
  ?> <script>popnew_error("Simpan gagal: <?=$errMsg?>");kosongkan();carinoretur(1,true);</script> <?php
}
?>
<?php
  mysqli_close($consim);
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>
