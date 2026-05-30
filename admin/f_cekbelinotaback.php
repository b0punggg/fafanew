
<?php 
  include 'config.php';
  session_start();
  $connect=opendtcek();
  $no_urutnota = $_POST['no_urutnota'];
  $jump_file   = $_POST['jump_file'];  
  $nm_sup      = strtoupper($_POST['nm_sup']);
  $nm_sat      = strtoupper($_POST['nm_sat']);
  // $kd_brg=ltrim(strtoupper($_POST['kd_brg']));
  $nm_brg      = ltrim(strtoupper($_POST['nm_brg']));
  $jml_brg     = $_POST['jml_brg'];
  $kd_bar      = trim(strtoupper($_POST['kd_bar']));
  $ketbel      = trim(strtoupper($_POST['ketbel']));
  $expdate     = $_POST['expdate'];
  //------------------------
  $kd_sat=$_POST['kd_sat'];
  //-----------------------
  $kd_sat1=$_POST['kd_sat1'];
  $jum_sat1=$_POST['jum_sat1'];
  $hrg_jum1=$_POST['hrg_jum1'];
  $nm_sat1=$_POST['kd_sat1']; 

  $kd_sat2=$_POST['kd_sat2'];
  $jum_sat2=$_POST['jum_sat2'];
  $hrg_jum2=$_POST['hrg_jum2'];
  $nm_sat2=$_POST['kd_sat2'];

  $kd_sat3=$_POST['kd_sat3'];
  $jum_sat3=$_POST['jum_sat3'];
  $hrg_jum3=$_POST['hrg_jum3'];
  $nm_sat3=$_POST['kd_sat3'];

  $lim_jual1=$_POST['discttp1'];
  $kd_sat4=$_POST['kd_sat4'];
  $hrg_jum4=$_POST['hrg_jum4'];

  $lim_jual2=$_POST['discttp2'];
  $kd_sat5=$_POST['kd_sat5'];
  $hrg_jum5=$_POST['hrg_jum5'];

  $lim_jual3=$_POST['discttp3'];
  $kd_sat6=$_POST['kd_sat6'];
  $hrg_jum6=$_POST['hrg_jum6'];
  //--------------------------
  $id_bag=$_POST['id_bag'];
  $no_fak=strtoupper($_POST['no_fak']);
  $tgl_fak=$_POST['tgl_fak'];
  $kd_sup=$_POST['kd_sup'];
  $hrg_beli=$_POST['hrg_beli'];
  $discitem1=$_POST['discitem1'];
  $discitem2=$_POST['discitem2'];
  $lanjutsave=$_POST['lanjutsave'];

  if(!empty($_POST['kd_brg'])){
    $kd_brg=strtoupper($_POST['kd_brg']);
    $kd_brg=str_replace("'", "", $kd_brg);
    $kd_brg=str_replace('"', '', $kd_brg);
    if(empty($_POST['kd_bar'])){$kd_bar=kd_barc39(trim($kd_brg));}else{$kd_bar=trim($_POST['kd_bar']);}
  }else{
    $kd_brg=kd_barc39(substr(str_replace(":","",date("H:m:s",time()*rand(1,100))),2,4));
    if(empty($_POST['kd_bar'])){$kd_bar=trim($kd_brg);}else{$kd_bar=trim($_POST['kd_bar']);}
  }
 
  $no_urutnota = trim($no_urutnota);
  $nm_sup      = trim(mysqli_real_escape_string($connect,$nm_sup));
  $nm_sat      = mysqli_real_escape_string($connect,$nm_sat);
  
  $jump_file   = mysqli_real_escape_string($connect,$jump_file);  
  $lanjutsave  = mysqli_real_escape_string($connect,$lanjutsave);  

  $nm_brg      = ltrim(mysqli_real_escape_string($connect,$nm_brg));
  $jml_brg     = mysqli_real_escape_string($connect,$jml_brg);
  $kd_bar      = ltrim(mysqli_real_escape_string($connect,$kd_bar));

  //------------------------
  $kd_sat      = ltrim(mysqli_real_escape_string($connect,$kd_sat));
  //-----------------------
  $kd_sat1     = ltrim(mysqli_real_escape_string($connect,$kd_sat1));
  $jum_sat1    = mysqli_real_escape_string($connect,$jum_sat1);
  $hrg_jum1    = backnumdes(mysqli_real_escape_string($connect,$hrg_jum1));
  $nm_sat1     = ceknmkem2(mysqli_real_escape_string($connect,$kd_sat1),$connect);

  $kd_sat2     = mysqli_real_escape_string($connect,$kd_sat2);
  $jum_sat2    = mysqli_real_escape_string($connect,$jum_sat2);
  $hrg_jum2    = backnumdes(mysqli_real_escape_string($connect,$hrg_jum2));
  $nm_sat2     = ceknmkem2(mysqli_real_escape_string($connect,$kd_sat2),$connect);

  $kd_sat3     = mysqli_real_escape_string($connect,$kd_sat3);
  $jum_sat3    = mysqli_real_escape_string($connect,$jum_sat3);
  $hrg_jum3    = backnumdes(mysqli_real_escape_string($connect,$hrg_jum3));
  $nm_sat3     = ceknmkem2(mysqli_real_escape_string($connect,$kd_sat3),$connect);

  $lim_jual1   = mysqli_real_escape_string($connect,$lim_jual1);
  $kd_sat4     = mysqli_real_escape_string($connect,$kd_sat4);
  $hrg_jum4    = backnumdes(mysqli_real_escape_string($connect,$hrg_jum4));

  $lim_jual2   = mysqli_real_escape_string($connect,$lim_jual2);
  $kd_sat5     = mysqli_real_escape_string($connect,$kd_sat5);
  $hrg_jum5    = backnumdes(mysqli_real_escape_string($connect,$hrg_jum5));

  $lim_jual3   = mysqli_real_escape_string($connect,$lim_jual3);
  $kd_sat6     = mysqli_real_escape_string($connect,$kd_sat6);
  $hrg_jum6    = backnumdes(mysqli_real_escape_string($connect,$hrg_jum6));
  //--------------------------

  $kd_toko     = $_SESSION['id_toko']; 
  $no_fak      = trim(mysqli_real_escape_string($connect,$no_fak));
  $tgl_fak     = mysqli_real_escape_string($connect,$tgl_fak);
  $hrg_beli    = backnumdes(mysqli_real_escape_string($connect,$hrg_beli));
  $discitem1   = mysqli_real_escape_string($connect,$discitem1);
  $discitem2   = backnum(mysqli_real_escape_string($connect,$discitem2));
  $ketbel      = mysqli_real_escape_string($connect,$ketbel);
  // if(empty($kd_bar)){$kd_bar=$kd_brg;}
  $koreksi     =0;$satkecil=0;
    // echo 'no_urutnota='.$no_urutnota.'<br>';
    // echo '$nm_sup='.$nm_sup.'<br>';
    // echo '$nm_brand='.$nm_brand.'<br>';
    // echo '$nm_kat='.$nm_kat.'<br>';
    // echo '$nm_sat='.$nm_sat.'<br>';
    // echo '$kd_brg='.$kd_brg.'<br>';
    // echo '$nm_brg='.$nm_brg.'<br>';
    // echo '$jml_brg='.$jml_brg.'<br>';
    // echo '$kd_bar='.$kd_bar.'<br>';
    // echo '$kd_sat='.$kd_sat.'<br>';

    // echo '$kd_sat1='.$kd_sat1.'<br>';
    // echo '$jum_sat1='.$jum_sat1.'<br>';
    // echo '$hrg_jum1='.$hrg_jum1.'<br>';
    // echo '$nm_sat1='.$nm_sat1.'<br>';

    // echo '$kd_sat2='.$kd_sat2.'<br>';
    // echo '$jum_sat2='.$jum_sat2.'<br>';
    // echo '$hrg_jum2='.$hrg_jum2.'<br>';
    // echo '$nm_sat2='.$nm_sat2.'<br>';
    
    // echo '$kd_sat3='.$kd_sat3.'<br>';
    // echo '$jum_sat3='.$jum_sat3.'<br>';
    // echo '$hrg_jum3='.$hrg_jum3.'<br>';
    // echo '$nm_sat3='.$nm_sat3.'<br>';

    // echo '$kd_kat='.$kd_kat.'<br>';
    // echo '$kd_brand='.$kd_brand.'<br>';
    // echo '$no_fak='.$no_fak.'<br>';
    //echo '$tgl_fak='.$tgl_fak.'<br>';
    // echo '$lim_jual2='.$lim_jual2.'<br>';
    // echo '$lim_jual3='.$lim_jual3.'<br>';
  //echo '$ketbel='.$ketbel.'<br>';

  // cek kode satuan beli hrs ada pd satuan konversi
     $pesan5=0;
     if ($kd_sat==$kd_sat1){
       $pesan5=1;
     }
     if ($kd_sat==$kd_sat2){
       $pesan5=1;
     }
     if ($kd_sat==$kd_sat3){
       $pesan5=1;
     }
  //-----------------------------------------------

  $pesan1="";$pesan2="";$pesan3="";$pesan4="";$pesan9="";
  if($jum_sat1<=$jum_sat2 || $jum_sat1<=$jum_sat3 ){
  	$pesan1="Jumlah barang pada kemasan I harus paling besar.";
  }else{$pesan1="";}

  if($jum_sat2>=0 && $jum_sat3>0 ){
  	if($jum_sat2<=$jum_sat3){
  	  $pesan2="Jumlah barang pada kemasan 2 harus Lebih Besar dari kemasan barang 3.";
  	}else{$pesan2="";}
  }

  if($hrg_jum1<=$hrg_jum2 || $hrg_jum1<=$hrg_jum3 ){
  	$pesan3="Harga jual barang 1 harus paling besar.";
  }else{$pesan3="";}	

  if($hrg_jum2>=0 && $hrg_jum3>0 ){
  	if($hrg_jum2<=$hrg_jum3){
  	  $pesan4="Harga jual barang 2 harus lebih besar dari barang 3." ;
  	} else{$pesan4="";}		
  }
  
  if($kd_sat4 > 1 && $hrg_jum4==0 ){
    $pesan6="Pada Discount, Jika satuan barang 1 bukan -NONE-, maka harga jual harus diisi !";
  }  
  if($kd_sat5 >1 && $hrg_jum5==0 ){
    $pesan7="Pada Discount, Jika satuan barang 2 bukan -NONE-, maka harga jual harus diisi !";
  }  
  if($kd_sat6 >1 && $hrg_jum6==0 ){
    $pesan8="Pada Discount, Jika satuan barang 3 bukan -NONE-, maka harga jual harus diisi !";
  }  
  
  $cek=mysqli_query($connect,"SELECT kd_brg,nm_brg,kd_bar,no_urut from mas_brg where kd_brg='$kd_brg' AND kd_bar <> '$kd_bar'");
  if (mysqli_num_rows($cek)>=1){
  	while($data=mysqli_fetch_assoc($cek)){
	    $ada_kd_brg1=$data['kd_brg'];
	    $ada_nm_brg1=$data['nm_brg'];
	    $ada_kd_bar1=$data['kd_bar'];	
	  }
  }else{$ada_kd_brg1="";$ada_nm_brg1="";$ada_kd_bar1="";}
  unset($cek,$data);

  $cek=mysqli_query($connect,"SELECT kd_brg,nm_brg,kd_bar,no_urut from mas_brg where kd_bar = '$kd_bar' AND kd_brg <> '$kd_brg' ");
  if (mysqli_num_rows($cek)>=1){
  	
    if (!empty($lanjutsave)){
     $ada_kd_brg3="";$ada_nm_brg3="";$ada_kd_bar3=""; 
    }else{
      while($data=mysqli_fetch_assoc($cek)){
      $ada_kd_brg3=$data['kd_brg'];
      $ada_nm_brg3=$data['nm_brg'];
      $ada_kd_bar3=$data['kd_bar']; 
      }  
    }
  }else{$ada_kd_brg3="";$ada_nm_brg3="";$ada_kd_bar3="";}
   
  unset($cek,$data);

  $cek=mysqli_query($connect,"SELECT kd_brg,nm_brg,kd_bar,no_urut from mas_brg where kd_bar='$kd_bar' AND kd_brg='$kd_brg' AND nm_brg <> '$nm_brg'");
  if (mysqli_num_rows($cek)>=1){
  	//echo "Terdapat data yang sama pada database ";
    while($data=mysqli_fetch_assoc($cek)){
      $ada_kd_brg2=$data['kd_brg'];
  	  $ada_nm_brg2=$data['nm_brg'];	
  	  $ada_kd_bar2=$data['kd_bar'];	
    }	
  }else{$ada_kd_brg2="";$ada_nm_brg2="";$ada_kd_bar2="";}
  unset($cek,$data);

  //cek nomer faktur dan tanggal beli
  $cekfak=mysqli_query($connect,"SELECT no_fak,tgl_fak FROM beli_bay WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak<>'$tgl_fak' ");
  if (mysqli_num_rows($cekfak)>=1){
    $dacek=mysqli_fetch_assoc($cekfak);
    $tgl_faklama=$dacek['tgl_fak'];
    $pesan9=$no_fak." tanggal ".gantitgl($tgl_faklama);
  } else { $pesan9=""; }
  unset($cekfak,$dacek);

  //cek keterangan pembelian
  if (empty($ketbel)){$ketbel='PEMBELIAN BARANG';}
  $ketawal="";$pesan10="";
  if (!empty($no_fak)){ 
    $sqlcek=mysqli_query($connect,"SELECT * FROM beli_brg WHERE no_fak='$no_fak'");
    if (mysqli_num_rows($sqlcek)){
      $dat=mysqli_fetch_assoc($sqlcek);
      $ketawal=$dat['ket'];
    }
    mysqli_free_result($sqlcek);unset($dat);

    $sql=mysqli_query($connect,"SELECT * FROM beli_brg WHERE no_fak='$no_fak' AND ket='$ketbel'");  
    if (mysqli_num_rows($sql)>=1){
      $ketbel="";
      $pesan10="";  
    } else {
      if (strpos($ketbel,'UTASI')>0) {
        $pesan10=" Jangan Gunakan KETERANGAN Mengandung Kata 'MUTASI' ";  
      } else {
        if (empty($ketawal)){
          $ketbel="";
          $pesan10="";  
        } else {
          $pesan10="Default KETERANGAN pada nota ini : '". $ketawal ."'"."<br>"." Jika ganti KETERANGAN menjadi '".$ketbel."' Silahkan Buat Nota Baru..";  
        } 
      }
    }
    mysqli_free_result($sql);
  }
  
  //keterangan jangan MUTASI


  if(!empty($pesan1) || !empty($pesan2) || !empty($pesan3) || !empty($pesan4) || !empty($ada_kd_brg3) || !empty($ada_nm_brg3) || $pesan5==0 || !empty($pesan6) || !empty($pesan7) || !empty($pesan8) || !empty($pesan9) || !empty($pesan10) ){
    ?> <script>
      document.getElementById('fnotacek').style.display='block';
      </script>
    <?php
  }else{
    //proses simpan	
    ?>
      <form id="form-simpan" action="f_belinota_act.php" method="post">     
        <input type="hidden" name="kd_toko" value="<?=$kd_toko?>">          
        <input type="hidden" name="expdate" value="<?=$expdate?>">      
        <input type="hidden" name="no_urutnota" value="<?=$no_urutnota?>">          
        <input type="hidden" name="nm_sup" value="<?=$nm_sup?>">          
        <input type="hidden" name="nm_sat" value="<?=$nm_sat?>">
        <input type="hidden" name="kd_brg" value="<?=$kd_brg?>">
        <input type="hidden" name="nm_brg" value="<?=$nm_brg?>">
        <input type="hidden" name="jml_brg" value="<?=$jml_brg?>">
        <input type="hidden" name="kd_bar" value="<?=$kd_bar?>">
        <input type="hidden" name="kd_sat" value="<?=$kd_sat?>">
        <input type="hidden" name="kd_sat1" value="<?=$kd_sat1?>">
        <input type="hidden" name="jum_sat1" value="<?=$jum_sat1?>">
        <input type="hidden" name="hrg_jum1" value="<?=$hrg_jum1?>">
        <input type="hidden" name="nm_sat1" value="<?=$nm_sat1?>">
        <input type="hidden" name="kd_sat2" value="<?=$kd_sat2?>">
        <input type="hidden" name="jum_sat2" value="<?=$jum_sat2?>">
        <input type="hidden" name="hrg_jum2" value="<?=$hrg_jum2?>">
        <input type="hidden" name="nm_sat2" value="<?=$nm_sat2?>">
        <input type="hidden" name="kd_sat3" value="<?=$kd_sat3?>">
        <input type="hidden" name="jum_sat3" value="<?=$jum_sat3?>">
        <input type="hidden" name="hrg_jum3" value="<?=$hrg_jum3?>">
        <input type="hidden" name="nm_sat3" value="<?=$nm_sat3?>">

        <input type="hidden" name="lim_jual1" value="<?=$lim_jual1?>">
        <input type="hidden" name="hrg_jum4" value="<?=$hrg_jum4?>">
        <input type="hidden" name="kd_sat4" value="<?=$kd_sat4?>">

        <input type="hidden" name="lim_jual2" value="<?=$lim_jual2?>">
        <input type="hidden" name="hrg_jum5" value="<?=$hrg_jum5?>">
        <input type="hidden" name="kd_sat5" value="<?=$kd_sat5?>">

        <input type="hidden" name="lim_jual3" value="<?=$lim_jual3?>">
        <input type="hidden" name="hrg_jum6" value="<?=$hrg_jum6?>">
        <input type="hidden" name="kd_sat6" value="<?=$kd_sat6?>">

        <input type="hidden" name="no_fak" value="<?=$no_fak?>">
        <input type="hidden" name="tgl_fak" value="<?=$tgl_fak?>">
        <input type="hidden" name="kd_sup" value="<?=$kd_sup?>">
        <input type="hidden" name="hrg_beli" value="<?=$hrg_beli?>">
        <input type="hidden" name="discitem1" value="<?=$discitem1?>">
        <input type="hidden" name="discitem2" value="<?=$discitem2?>">
        <input type="hidden" name="jump_file" value="<?=$jump_file?>">
        <input type="hidden" name="ketbel" value="<?=$ketbel?>">
        <input type="hidden" name="id_bag" value="<?=$id_bag?>">
      </form>  
      <div class="loader1"><div class="loader2"><div class="loader3"></div></div></div>
      <script>
      simpanlah();  
      function simpanlah(){
        $.ajax({
          url: $('#form-simpan').attr('action'), // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: $("#form-simpan").serialize(), 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            $(".loader1").fadeOut();
            $('#viewcek').html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }  
     </script>
        
    <?php 
  }  
?>

<!-- Form nota-->

<!-- <script>document.getElementById('fnotacek').style.display='block';</script> -->
<div id="fnotacek" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.6);border-style: ridge; ">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:700px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

    <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 12pt">&nbsp;<i class="fa fa-tv"></i>
      Validasi input data
    </div>

    <div class="w3-center">
      <span onclick="document.getElementById('fnotacek').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px;cursor:pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
    </div>
  
    <div class="modal-body">   
      <?php 
        if(!empty($pesan1)){
          ?> 
            <p> 
              <span class="fa-stack fa-lg" style="color: red">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b><?=$pesan1?></b>
            </p>
          <?php  	
        }
        if(!empty($pesan2)){
          ?> 
            <p> 
              <span class="fa-stack fa-lg" style="color: orange">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b><?=$pesan2?></b>
            </p>
          <?php  	
        }	
        if(!empty($pesan3)){
          ?> 
            <p> 
              <span class="fa-stack fa-lg" style="color: red">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b><?=$pesan3?></b>
            </p>
          <?php  	
        }
        if(!empty($pesan4)){
          ?> 
            <p> 
              <span class="fa-stack fa-lg" style="color: orange">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b><?=$pesan4?></b>
            </p>
          <?php  	
        }

        if($pesan5==0){
          ?> 
            <p> 
              <span class="fa-stack fa-lg" style="color: orange">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b><?='Satuan pembelian barang harus ada pada konversi barang'?></b>
            </p>
          <?php  	
        }

        if(!empty($pesan6)){
          ?> 
            <p> 
              <span class="fa-stack fa-lg" style="color: orange">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b><?=$pesan6?></b>
            </p>
          <?php   
        }
        if(!empty($pesan7)){
          ?> 
            <p> 
              <span class="fa-stack fa-lg" style="color: orange">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b><?=$pesan7?></b>
            </p>
          <?php   
        }
        if(!empty($pesan8)){
          ?> 
            <p> 
              <span class="fa-stack fa-lg" style="color: orange">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b><?=$pesan8?></b>
            </p>
          <?php   
        } 
        if(!empty($ada_kd_brg1) || !empty($ada_kd_bar1) ){
          ?> 
            <span class="fa-stack fa-lg" >
                <i class="fa fa-database fa-stack-1x"></i>
                <i class="fa fa-ban fa-stack-2x text-danger" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b>Terdapat data sejenis pada database mohon diperiksa</b>
            <div class="row" style="margin-top: -10px">
            	<div class="col-sm-1">Input</div>
            	<div class="col">
            	  <div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
				    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;width: 100%">
					    <tr align="middle" class="yz-theme-l3">
					      <th width="28%">BARCODE</th>
					      <th width="25%">KODE BARANG</th>
					      <th width="47%">NAMA BARANG</th>
					    </tr>
					    <tr>
					      <td><?=$kd_bar?></td>	
					      <td><?=$kd_brg?></td>	
					      <td><?=$nm_brg?></td>	
					    </tr>
                    </table>
                  </div>  
            	</div>
            </div>
            <div class="row">
            	<div class="col-sm-1">Database</div>
            	<div class="col">
            	  <div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
				    <table class="table table-bordered table-sm table-hover" style="font-size:9pt; width: 100%">
					    <tr>
					      <td width="28%"><?=$ada_kd_bar1?></td>	
					      <td width="25%"><?=$ada_kd_brg1?></td>	
					      <td width="47%"><?=$ada_nm_brg1?></td>	
					    </tr>
                    </table>
                  </div>  
            	</div>
            </div>
          <?php  	
        }

        if(!empty($ada_kd_brg2) || !empty($ada_nm_brg2) ){
          ?> 
            <span class="fa-stack fa-lg" >
                <i class="fa fa-database fa-stack-1x"></i>
                <i class="fa fa-ban fa-stack-2x text-danger" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b>Terdapat data sejenis pada database mohon diperiksa</b>
            <div class="row" style="margin-top: -10px">
            	<div class="col-sm-1">Input</div>
            	<div class="col">
            	  <div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
				    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;width: 100%">
					    <tr align="middle" class="yz-theme-l3">
					      <th width="28%">BARCODE</th>
					      <th width="25%">KODE BARANG</th>
					      <th width="47%">NAMA BARANG</th>
					    </tr>
					    <tr>
					      <td><?=$kd_bar?></td>	
					      <td><?=$kd_brg?></td>	
					      <td><?=$nm_brg?></td>	
					    </tr>
                    </table>
                  </div>  
            	</div>
            </div>
            <div class="row" >
            	<div class="col-sm-1">Database</div>
            	<div class="col">
            	  <div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
				    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;width: 100%">
					    <tr>
					      <td width="28%"><?=$ada_kd_bar2?></td>	
					      <td width="25%"><?=$ada_kd_brg2?></td>	
					      <td width="47%"><?=$ada_nm_brg2?></td>	
					    </tr>
                    </table>
                  </div>  
            	</div>
            </div>
          <?php  	
        }

        if(!empty($ada_kd_brg3) || !empty($ada_nm_brg3) ){  	
          ?> 
              <span class="fa-stack fa-lg" >
                <i class="fa fa-database fa-stack-1x"></i>
                <i class="fa fa-ban fa-stack-2x text-danger" style="color:black"></i>
              </span> <i class="fa fa-arrow-right"></i> &nbsp;<b>Terdapat data sejenis pada database mohon diperiksa</b>
            <div class="row" style="margin-top: -10px">
            	<div class="col-sm-1">Input</div>
            	<div class="col">
            	  <div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
				          <table class="table table-bordered table-sm table-hover" style="font-size:9pt;width: 100%">
      					    <tr align="middle" class="yz-theme-l3">
      					      <th width="28%">BARCODE</th>
      					      <th width="25%">KODE BARANG</th>
      					      <th width="44%">NAMA BARANG</th>
                      <th width="3%">ACTION</th>
      					    </tr>
      					    <tr>
      					      <td><?=$kd_bar?></td>	
      					      <td><?=$kd_brg?></td>	
      					      <td><?=$nm_brg?></td>	
                      <td><button id="btn-ignore" style="font-size: 8pt;background-color: yellow" onclick="document.getElementById('lanjutsave').value='lanjut';document.getElementById('fnotacek').style.display='none';document.getElementById('btn-save').click();">Ignore</button></td> 
      					    </tr>
                  </table>
                </div>  
            	</div>
            </div>
            <div class="row" >
            	<div class="col-sm-1">Database</div>
            	<div class="col">
            	  <div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
      				    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;width: 100%">
      					    <tr>
      					      <td width="28%"><?=$ada_kd_bar3?></td>	
      					      <td width="25%"><?=$ada_kd_brg3?></td>	
      					      <td width="47%"><?=$ada_nm_brg3?></td>	
      					    </tr>
                  </table>
                </div>  
            	</div>
            </div>
          <?php  	
        }
        if (!empty($pesan9)){
        ?>
        <div class="row">
          <div class="col-sm-2 w3-large">
            <span class="fa-stack fa-lg">
              <i class="fa fa-circle-o fa-stack-2x"></i>
              <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:red"></i>
            </span> 
          </div>
          <div class="col">
            <p><b>No.faktur</b> <b style="color:blue"><?=$no_fak?></b> <b>sudah ada pada database.</b></p>
            <b>Pada database <i class="fa fa-arrow-right">&nbsp;</i><i class="fa fa-database" style="color:blue"></i></b> <b style="color:red"><?=$pesan9?></b>
          </div>
        </div>
      <?php 
      } 
      if (!empty($pesan10)){ ?>
        <div class="row">
          <div class="col-sm-2 w3-large">
            <span class="fa-stack fa-lg">
              <i class="fa fa-circle-o fa-stack-2x"></i>
              <i class="fa fa-exclamation fa-stack-1x fa-inverse" style="color:red"></i>
            </span> 
          </div>
          <div class="col">
            <b>Pada database <i class="fa fa-arrow-right">&nbsp;</i><i class="fa fa-database" style="color:blue"></i></b> <b style="color:red"><?=$pesan10?></b>
          </div>
        </div>
      <?php  
      }
      ?>
    </div> <!--Modal-body-->
  </div><!--Modal content-->
</div>
<!-- <div id="viewcek"></div> -->
<!-- End Form Nota -->  

    