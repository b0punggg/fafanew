
<?php 
  include 'config.php';
  session_start();
  $connect=opendtcek();
  $no_urutbrg=$_POST['no_urutbrg'];
  $kd_brg=ltrim(strtoupper($_POST['kd_brg']));
  $nm_brg=ltrim(strtoupper($_POST['nm_brg']));
  
  if(!empty($_POST['kd_bar'])){
    $kd_bar=ltrim($_POST['kd_bar']);  
  }else{
    $kd_bar=$kd_brg;
  }

  $kd_sat1=$_POST['kd_sat1'];
  $jum_sat1=$_POST['jum_sat1'];
  $hrg_jum1=backnumdes($_POST['hrg_jum1']);
  $nm_sat1=ceknmkem2($_POST['kd_sat1'], $connect); 

  $kd_sat2=$_POST['kd_sat2'];
  $jum_sat2=$_POST['jum_sat2'];
  $hrg_jum2=backnumdes($_POST['hrg_jum2']);
  $nm_sat2=ceknmkem2($_POST['kd_sat2'], $connect); 

  $kd_sat3=$_POST['kd_sat3'];
  $jum_sat3=$_POST['jum_sat3'];
  $hrg_jum3=backnumdes($_POST['hrg_jum3']);
  $nm_sat3=ceknmkem2($_POST['kd_sat3'], $connect); 

  $lim_jual1=$_POST['discttp1'];
  $kd_sat4=$_POST['kd_sat4'];
  $hrg_jum4=backnumdes($_POST['hrg_jum4']);

  $lim_jual2=$_POST['discttp2'];
  $kd_sat5=$_POST['kd_sat5'];
  $hrg_jum5=backnumdes($_POST['hrg_jum5']);

  $lim_jual3=$_POST['discttp3'];
  $kd_sat6=$_POST['kd_sat6'];
  $hrg_jum6=backnumdes($_POST['hrg_jum6']);

  $kd_toko=$_SESSION['id_toko']; 

  
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
    // echo '$tgl_fak='.$tgl_fak.'<br>';

  $lanjutsavemas=$_POST['lanjutsavemas'];
  $pesan1="";$pesan2="";$pesan3="";$pesan4="";
  if($jum_sat1<=$jum_sat2 || $jum_sat1<=$jum_sat3 ){
  	$pesan1="Jumlah barang pada kemasan I harus paling besar.";
  }else{$pesan1="";}

  if($jum_sat2>=0 && $jum_sat3>0 ){
	if($jum_sat2<=$jum_sat3){
	  $pesan2="Jumlah barang pada kemasan II harus Lebih Besar dari kemasan barang III.";
	}else{$pesan2="";}
  }

  if($hrg_jum1<=$hrg_jum2 || $hrg_jum1<=$hrg_jum3 ){
  	$pesan3="Harga jual barang I harus paling besar.";
  }else{$pesan3="";}	

  if($hrg_jum2>=0 && $hrg_jum3>0 ){
  	if($hrg_jum2<=$hrg_jum3){
  	  $pesan4="Harga jual barang II harus lebih besar dari barang III." ;
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
  
  $cek=mysqli_query($connect,"SELECT kd_brg,nm_brg,kd_bar,no_urut FROM mas_brg WHERE kd_brg='$kd_brg' AND kd_bar <> '$kd_bar'");
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
  	if (!empty($lanjutsavemas)){
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
  	echo "Terdapat data yang sama pada database ";
    while($data=mysqli_fetch_assoc($cek)){
      $ada_kd_brg2=$data['kd_brg'];
  	  $ada_nm_brg2=$data['nm_brg'];	
  	  $ada_kd_bar2=$data['kd_bar'];	
    }	
  }else{$ada_kd_brg2="";$ada_nm_brg2="";$ada_kd_bar2="";}
  unset($cek,$data);
  
  if(!empty($pesan1) || !empty($pesan2) || !empty($pesan3) || !empty($pesan4) || !empty($ada_kd_brg3) || !empty($ada_nm_brg3) || !empty($pesan6) || !empty($pesan7) || !empty($pesan8) ){
    ?> <script>document.getElementById('fmascek').style.display='block';</script><?php
  }else{
    //proses simpan	
    ?>

    <form id="form-simpan" action="f_masbrg_act.php" method="post">     

    <input type="hidden" name="kd_toko" value="<?=$kd_toko?>">	        
	  <input type="hidden" name="no_urutbrg" value="<?=$no_urutbrg?>">	        
    <input type="hidden" name="kd_brg" value="<?=$kd_brg?>">
    <input type="hidden" name="nm_brg" value="<?=$nm_brg?>">
    <input type="hidden" name="kd_bar" value="<?=$kd_bar?>">
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
    <button type="submit" id="simpanok" style="display: none"></button>  
	</form>  
	<script>document.getElementById('simpanok').click();</script>
	  <!-- <script>simpan();</script> -->
    <?php
  }

?>

<!-- Form nota-->

<!-- <script>document.getElementById('fnotacek').style.display='block';</script> -->
<div id="fmascek" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.6);border-style: ridge; ">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:700px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

    <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 12pt">&nbsp;<i class="fa fa-tv"></i>
      Validasi input data
    </div>

    <div class="w3-center">
      <span onclick="document.getElementById('fmascek').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px;cursor:pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
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
            <div class="row" >
            	<div class="col-sm-1">Database</div>
            	<div class="col">
            	  <div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
				    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;width: 100%">
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
                      <td><button id="btn-ignore" style="font-size: 8pt;background-color: yellow" onclick="document.getElementById('lanjutsavemas').value='lanjut';document.getElementById('fmascek').style.display='none';document.getElementById('tmb_simpan').click();">Ignore</button></td> 
      					    </tr>
                  </table>
                </div>  
            	</div>
            </div>
            <div class="row" >
            	<div class="col-sm-1">Database</div>
            	<div class="col">
            	  <div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
      				    <table class="table table-bordered table-sm table-hover" style="font-size:9pt; width: 100%">
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
      ?>

    </div> <!--Modal-body-->
  </div><!--Modal content-->
</div>
<div id="viewcek"></div>
<!-- End Form Nota -->  
<?php mysqli_close($connect) ?>
    