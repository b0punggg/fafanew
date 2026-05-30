<?php
	$kd_bar = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
	ob_start();
	include 'config.php';
	session_start();
	
	$connect=opendtcek();
	$nm_kem1="";$nm_kem2="";$nm_kem3="";
	$kd_toko=$_SESSION['id_toko'];
    $no=$jml=0;
    $cek=mysqli_query($connect,"SELECT count(*) as jmlbarkode from mas_brg WHERE mas_brg.kd_bar='$kd_bar' AND mas_brg.kd_toko='$kd_toko' group by mas_brg.kd_bar");
    if(mysqli_num_rows($cek)>0){
	  $data=mysqli_fetch_assoc($cek);
	  $jml= $data['jmlbarkode'];	
	}
    
    if ($jml>1){ ?>	
      <script>document.getElementById('modalkdbar').style.display='block';</script> <?php
    }else{
       
       $sql1 =mysqli_query($connect, "SELECT * FROM mas_brg  WHERE kd_bar='$kd_bar'");
	    if (mysqli_num_rows($sql1)>=1) {
	    	//cek ada berapa dulu kd bar pada mas_brg
	    	
	    	$databrg=mysqli_fetch_array($sql1);
	    	  $nm_kem1=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem1']),$connect);
		      $nm_kem2=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem2']),$connect);
		      $nm_kem3=ceknmkem(mysqli_escape_string($connect,$databrg['kd_kem3']),$connect);
		      $kd_brg=mysqli_escape_string($connect,$databrg['kd_brg']);
          //cek disc tetap tokushima reiko
            $limit1=cekdisc($kd_brg,mysqli_escape_string($connect,$databrg['kd_kem1']),$connect);
            //echo '$lim1'.$lim1;
          
            if(empty($limit1)){
              $kd_sat4="1";$hrg_jum4=0;$lim1=0;$nm_kem4="-NONE-";	
              $percen1=0;
            }else{
	          $x1=explode(';', $limit1);
		      $kd_sat4=$x1[0];
		      $hrg_jum4=$x1[1];
		      $lim1=$x1[2];
		      $nm_kem4=ceknmkem($kd_sat4,$connect);
		      $percen1=str_replace('.',',',round(($databrg['hrg_jum1']-$hrg_jum4)/$hrg_jum4*100,2));
		    }  
          //
            $limit1=cekdisc($kd_brg,$databrg['kd_kem2'],$connect);
            if(empty($limit1)){
              $kd_sat5="1";$hrg_jum5=0;$lim2=0;$nm_kem5="-NONE-";	
              $percen2=0;
            }else{
	          $x1=explode(';', $limit1);
		      $kd_sat5=$x1[0];
		      $hrg_jum5=$x1[1];
		      $lim2=$x1[2];
		      $nm_kem5=ceknmkem($kd_sat5,$connect);
		      $percen2=str_replace('.',',',round(($databrg['hrg_jum2']-$hrg_jum5)/$hrg_jum5*100,2));
		    }  
            $limit1=cekdisc($kd_brg,$databrg['kd_kem3'],$connect);
            if(empty($limit1)){
              $kd_sat6="1";$hrg_jum6=0;$lim3=0;$nm_kem6="-NONE-";	
              $percen3=0;
            }else{
	          $x1=explode(';', $limit1);
		      $kd_sat6=$x1[0];
		      $hrg_jum6=$x1[1];
		      $lim3=$x1[2];
		      $nm_kem6=ceknmkem($kd_sat6,$connect);
		      $percen3=str_replace('.',',',round(($databrg['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2));
		    }
	      // 
	        ?>
	        <script>
	      	   document.getElementById('kd_brg').value='<?=mysqli_escape_string($connect,$databrg['kd_brg']) ?>';
		  	   document.getElementById('nm_brg').value='<?=mysqli_escape_string($connect,$databrg['nm_brg']) ?>';
		  	   
		  	   document.getElementById('kd_sat1').value='<?=mysqli_escape_string($connect,$databrg['kd_kem1']) ?>';document.getElementById('nm_sat1').value='<?=$nm_kem1?>';document.getElementById('jum_sat1').value='<?=mysqli_escape_string($connect,$databrg['jum_kem1']) ?>';document.getElementById('hrg_jum1').value='<?=gantitides($databrg['hrg_jum1']) ?>';
		  	   document.getElementById('kd_sat2').value='<?=mysqli_escape_string($connect,$databrg['kd_kem2']) ?>';document.getElementById('nm_sat2').value='<?=$nm_kem2?>';document.getElementById('jum_sat2').value='<?=mysqli_escape_string($connect,$databrg['jum_kem2']) ?>';document.getElementById('hrg_jum2').value='<?=gantitides($databrg['hrg_jum2']) ?>';
		  	   document.getElementById('kd_sat3').value='<?=mysqli_escape_string($connect,$databrg['kd_kem3']) ?>';document.getElementById('nm_sat3').value='<?=$nm_kem3?>';document.getElementById('jum_sat3').value='<?=mysqli_escape_string($connect,$databrg['jum_kem3']) ?>';document.getElementById('hrg_jum3').value='<?=gantitides($databrg['hrg_jum3']) ?>';
		  	   
		  	   document.getElementById('discttp1').value='<?=$lim1 ?>';document.getElementById('nm_sat4').value='<?=$nm_kem4?>';document.getElementById('kd_sat4').value='<?=$kd_sat4 ?>';document.getElementById('hrg_jum4').value='<?=gantitides($hrg_jum4) ?>';
	          	       document.getElementById('discttp1%').value='<?=$percen1 ?>';
          	   document.getElementById('discttp2').value='<?=$lim2 ?>';document.getElementById('nm_sat5').value='<?=$nm_kem5?>';document.getElementById('kd_sat5').value='<?=$kd_sat5 ?>';document.getElementById('hrg_jum5').value='<?=gantitides($hrg_jum5) ?>';
          	       document.getElementById('discttp2%').value='<?=$percen2 ?>';
          	   document.getElementById('discttp3').value='<?=$lim3 ?>';document.getElementById('nm_sat6').value='<?=$nm_kem6?>';document.getElementById('kd_sat6').value='<?=$kd_sat6 ?>';document.getElementById('hrg_jum6').value='<?=gantitides($hrg_jum6) ?>';
          	       document.getElementById('discttp3%').value='<?=$percen3?>';              			 
	        </script> <?php 	 	
	    }else{ ?>
           <script>kosfaktur2();</script> <?php
		}
	    unset($sql1,$databrg);
    }  
	unset($cek,$data);	
?>

<div id="modalkdbar" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge; ">
	    <div class="w3-modal-content w3-card-4 w3-animate-top" style="max-width:700px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

	        <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;">&nbsp;<i class="fa fa-search"></i>
	          CEK BARCODE 
	        </div>
	 
	        <div class="w3-center">
	          <span onclick="document.getElementById('modalkdbar').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
	        </div>
	      
	        <div class="modal-body">
	            <div class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
				  <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
				    <tr align="middle" class="yz-theme-l1">
				      <th>No.</th>
				      <th>BARCODE</th>
				      <th>KODE BARANG</th>
				      <th>NAMA BARANG</th>
				      <th>OPSI</th>
				    </tr>	
				  
			          <?php  
			            $no=0;
			            $cek=mysqli_query($connect,"SELECT mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_bar,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,mas_brg.hrg_jum1,mas_brg.hrg_jum2,mas_brg.hrg_jum3 FROM mas_brg  
						 WHERE mas_brg.kd_bar='$kd_bar'");
		            while($data=mysqli_fetch_assoc($cek)){
		              $no++;	
		               $nm_kem1=ceknmkem(mysqli_escape_string($connect,$data['kd_kem1']),$connect);
			           $nm_kem2=ceknmkem(mysqli_escape_string($connect,$data['kd_kem2']),$connect);
			           $nm_kem3=ceknmkem(mysqli_escape_string($connect,$data['kd_kem3']),$connect);
		            	  ?>
		            	  <tr>
		                     <td align="right"><?php echo $no ?></td>
		                     <td align="left"><?php echo $data['kd_bar'] ?></td>
		                     <td align="left"><?php echo $data['kd_brg'] ?></td>
		                     <td align="left"><?php echo $data['nm_brg'] ?></td>
		                     <td>
					          	<button onclick="document.getElementById('kd_brg').value='<?=mysqli_escape_string($connect,$data['kd_brg']) ?>';
							  	   document.getElementById('nm_brg').value='<?=mysqli_escape_string($connect,$data['nm_brg']) ?>';							  	   
							  	   document.getElementById('nm_kat').value='<?=mysqli_escape_string($connect,$data['nm_kat']) ?>';document.getElementById('kd_kat').value='<?=mysqli_escape_string($connect,$data['kd_kat']) ?>';
							  	   document.getElementById('nm_brand').value='<?=mysqli_escape_string($connect,$data['nm_brand']) ?>';document.getElementById('kd_brand').value='<?=mysqli_escape_string($connect,$data['kd_brand']) ?>';
							  	   document.getElementById('kd_sat1').value='<?=mysqli_escape_string($connect,$data['kd_kem1']) ?>';document.getElementById('nm_sat1').value='<?=$nm_kem1?>';document.getElementById('jum_sat1').value='<?=mysqli_escape_string($connect,$data['jum_kem1']) ?>';document.getElementById('hrg_jum1').value='<?=gantitides($data['hrg_jum1']) ?>';
							  	   document.getElementById('kd_sat2').value='<?=mysqli_escape_string($connect,$data['kd_kem2']) ?>';document.getElementById('nm_sat2').value='<?=$nm_kem2?>';document.getElementById('jum_sat2').value='<?=mysqli_escape_string($connect,$data['jum_kem2']) ?>';document.getElementById('hrg_jum2').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum2'])) ?>';
							  	   document.getElementById('kd_sat3').value='<?=mysqli_escape_string($connect,$data['kd_kem3']) ?>';document.getElementById('nm_sat3').value='<?=$nm_kem3?>';document.getElementById('jum_sat3').value='<?=mysqli_escape_string($connect,$data['jum_kem3']) ?>';document.getElementById('hrg_jum3').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum3'])) ?>';
							  	   document.getElementById('lanjutsave').value='lanjut';
							  	   document.getElementById('modalkdbar').style.display='none';
							  	   document.getElementById('boxidbrg').style.display='none';
							  	   document.getElementById('jml_brg').focus();" 
				  	               class="btn-primary fa fa-edit" style="cursor: pointer;font-size: 12pt" title="Edit Data"> 
					            </button>	    
					         </td>  
		                  </tr>   
		            	  <?php
		                }  
			          ?>
			        </table>
				</div>    
	        </div> <!--Modal-body-->
	    </div><!--Modal content-->
	  </div>

<?php
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	// Buat array dengan index hasil dan value nya $html
	// Lalu konversi menjadi JSON
	echo json_encode(array('hasil'=>$html));
?>