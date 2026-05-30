<?php
   $keyword1=$_POST['keyword1'];
   $keyword2=$_POST['keyword2'];
   ob_start();
   include 'config.php';
   session_start();
   $kd_toko     = $_SESSION['id_toko'];
   $consave     = opendtcek();
   $x           = explode(';',mysqli_real_escape_string($consave,$keyword1));
   $kd_brginput = $x[1];
   $jml_input   = $x[0];
   $kettmb      = mysqli_real_escape_string($consave,nl2br($keyword2));
   //echo $kd_brginput.'<br>';
   // echo $jml_input.'<br>';

   //--Cek jml barang yang ada dan inputan
   $jml_brgawalbeli=0;$jumadjust=0;
   $cekbeli=mysqli_query($consave,"SELECT SUM(stok_jual) AS jumbrg FROM beli_brg WHERE kd_toko='$kd_toko' AND kd_brg='$kd_brginput'");
   $datcekbeli=mysqli_fetch_assoc($cekbeli);
   $jml_brgawalbeli=$datcekbeli['jumbrg'];
   unset($cekbeli,$datcekbeli);
   //----

  if ($jml_input>=0){
   	//** jika stok ditambah
   	$no_urut=0;$stok_jual=0;$jml_brg=0;$brg_msk=0;

    //***setting utk beli_brg
    $d=mysqli_query($consave,"UPDATE mas_brg SET jml_brg='$jml_brg' WHERE kd_brg='$kd_brginput'");
    $jumadjust=$jml_input-$jml_brgawalbeli;
    if ($jumadjust<0){
      $jumadjust=$jml_brgawalbeli-$jml_input;
    } else {
      $jumadjust=$jml_input-$jml_brgawalbeli;
    } 
    if ($jml_brgawalbeli>$jml_input){ 
      // echo "kurang";
      // echo 'jumadjust='.$jumadjust.'<br>';
      // echo '$jml_brgawalbeli='.$jml_brgawalbeli.'<br>';
      // cek pada beli_brg
      $cekbelik=mysqli_query($consave,"SELECT * FROM beli_brg WHERE kd_brg='$kd_brginput' AND kd_toko='$kd_toko' AND stok_jual>0 ORDER BY no_urut DESC");
      if (mysqli_num_rows($cekbelik)>=1){  
        $hitjum=$jumadjust;
        // echo '$hitjum'.$hitjum.'<br>';
        while($datcekbelik=mysqli_fetch_assoc($cekbelik))
        {
          $no_urutbeli=$datcekbelik['no_urut']; 
          if ($hitjum>$datcekbelik['stok_jual']){
              $d=mysqli_query($consave,"UPDATE beli_brg SET stok_jual='0' WHERE no_urut='$no_urutbeli'"); 
              $hitjum=$hitjum-$datcekbelik['stok_jual'];      
          } else {
            if ($hitjum<0){$hitjum=0;}
              $x=$datcekbelik['stok_jual']-$hitjum;
              $d=mysqli_query($consave,"UPDATE beli_brg SET stok_jual='$x' WHERE no_urut='$no_urutbeli'"); 
              $hitjum=0;
          }
          
        }
      } else {
       ?><script>popnew_warning('Nilai input tdk valid, terjadi kesalahan !');</script><?php
      } 
      unset($cekbeli,$datcekbelik);  
    }else {    
      //** jika tambah 
        //echo 'jumadjust='.$jumadjust.'<br>';
        $cekbelik=mysqli_query($consave,"SELECT * FROM beli_brg WHERE kd_brg='$kd_brginput' AND kd_toko='$kd_toko' ORDER BY no_urut DESC");
        if (mysqli_num_rows($cekbelik)>=1){  
          while($datcekbelik=mysqli_fetch_assoc($cekbelik))
          {
            $no_urutbeli=$datcekbelik['no_urut']; 
            $jml_brg=$datcekbelik['jml_brg']*konjumbrg($datcekbelik['kd_sat'],$datcekbelik['kd_brg']);
            // cek pilih stok_jual yg masih kurang pd pembeliannya
            if ($datcekbelik['stok_jual']<$jml_brg){
               $xx=$jml_brg-$datcekbelik['stok_jual'];
              if ($jumadjust>$xx){
                $jumadjust=$jumadjust-$xx;    
                $d=mysqli_query($consave,"UPDATE beli_brg SET stok_jual='$jml_brg' WHERE no_urut='$no_urutbeli'"); 
              }else{
                $repl=$datcekbelik['stok_jual']+$jumadjust;
                $d=mysqli_query($consave,"UPDATE beli_brg SET stok_jual='$repl' WHERE no_urut='$no_urutbeli'"); 
                //$jumadjust=$jumadjust-$datcekbelik['stok_jual'];
                $jumadjust=0;
                if ($jumadjust<0){$jumadjust=0;}
              }
            }
          }

        } else {
         ?><script>popnew_warning('Nilai input tdk valid, terjadi kesalahan !');</script><?php
        } 
        unset($cekbeli,$datcekbelik);  
        //-----------------------------------------
      // }
        
    }

    //**setting utk mas brg
    $jumawal=0;
    $cekm=mysqli_query($consave,"SELECT SUM(stok_jual) AS jumstok FROM beli_brg WHERE kd_brg='$kd_brginput'");
    $datm=mysqli_fetch_assoc($cekm);
    $jumawal=$datm['jumstok'];
    unset($datm,$cekm);
    
    $csave=opendtcek();
    $d=mysqli_query($csave,"UPDATE mas_brg SET jml_brg='$jumawal' WHERE kd_brg='$kd_brginput'");
    date_default_timezone_set('Asia/Jakarta');
    $tglhi=$_SESSION['tgl_set'];
    $ket='Penyesuaian ( stok awal : '.gantitides($jml_brgawalbeli).', menjadi : '.gantitides($jml_input).' )'.' User : '.strtoupper($_SESSION['nm_user']).', Jam : '.date("h:i:sa").'<br /> '.$kettmb; 
    $d=mysqli_query($csave,"INSERT INTO mutasi_adj VALUES('','$tglhi','$kd_brginput','$kd_toko','$ket')");
    mysqli_close($csave);
  } 
    
function seekstokbrg($kdbrg,$hub){
  $cekcaribrg=mysqli_query($hub,"SELECT * FROM mas_brg WHERE kd_brg='$kdbrg'");
  // $datcekbrg=mysqli_fetch_array($cekcaribrg);
  while($datcekbrg=mysqli_fetch_array($cekcaribrg)){
    echo $datcekbrg['jml_brg'].'<br>';
    echo $datcekbrg['brg_klr'].'<br>';
  }
  $cekout=$datcekbrg['jml_brg'].';'.$datcekbrg['brg_msk'];
  return $cekout;
}
?>

<script>caribrgstok(1,true);</script>

<?php
  mysqli_close($consave);
	$html = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>