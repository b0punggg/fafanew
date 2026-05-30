<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
  th {
  position: sticky;
  top: -1px; 
  color:#fff;
  background-color:#6271c8;
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }
</style>

<?php 
 include 'starting.php';
 $kd_toko=$_SESSION['id_toko'];
 $connect=opendtcek();
?>

<div id="main" style="font-size: 10pt;background: linear-gradient(565deg, #E6E6FA 0%, white 80%)">
  <div class="w3-container w3-padding" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);position: sticky;top:40px;margin-top:-10px;z-index: 1;">
  	<h5><i class='fa fa-database'></i> &nbsp;DATA BASE &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Cek data record</span></h5>
  </div>	
  <div class="w3-row">
  	<div class="w3-col l6 sm12 m12 w3-container w3-margin-bottom">
  	  <div class="table-responsive hrf_arial w3-card-4" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 70%">
  	  	<p class="w3-large w3-center">Cek Duplikat BARCODE </p>	
	    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
	    <thead>
	      <tr align="middle" class="yz-theme-l1">
	        <th>No.</th>
	        <th>BARCODE</th>
	        <th>KODE BARANG</th>
	        <th>NAMA BARANG</th>
	      </tr>
	    </thead>	  
	      <?php 
	      $no=0;$x=0;
          $kd_bar="";
	      $cek2=mysqli_query($connect,"SELECT kd_bar,kd_brg,nm_brg
			FROM mas_brg
			WHERE kd_bar IN (SELECT kd_bar  FROM mas_brg GROUP BY kd_bar HAVING count(*) > 1) 
	   		ORDER BY kd_bar");
            while($data=mysqli_fetch_assoc($cek2)) { 
             if($data['kd_bar']==$kd_bar){
	  	       $no++;	
	  	       $x++;	
               $jump_file=$data['kd_brg'];     
	             ?>
	           <tr>
	         	 <td align="right"><?=$no.'.';?></td>
	         	 <td style="color:red;"><?=$data['kd_bar'];?><i class="w3-text-blue w3-right fa fa-check-square-o"></td>
	         	 <td><?=$data['kd_brg'];?></td>
	         	 <td><?=$data['nm_brg'];?></td>
	           </tr>
	           <?php
	         }else{ $no=0;$no++;$x++; ?>
	           <tr><td></td></tr>	
	           
               <tr>
               	  <td align="right"><?=$no.'.';?></td>
		          <td style="color:red"><?=$data['kd_bar'];?><i class="w3-text-blue w3-right fa fa-check-square-o"></i></td>
		          <td id="<?=$x?>"><?=$data['kd_brg'];?></td>
		          <td><?=$data['nm_brg'];?></td>
	           </tr>

               <?php
	         }//if 
	         $kd_bar=$data['kd_bar'];  
	        }//while
	        if ($no>0){$ket='Ditemukan '.$x.' duplikat Barcode';}else{$ket='Tidak Ditemukan Duplikat Data';}
	      ?>
	      <tr align="right" class="yz-theme-l1">
		    <th colspan="4" style="text-align: center;position: sticky;bottom: 0;"><?=$ket?></th>
	      </tr>
	    </table>
	  </div>    
  	</div>

	<div class="w3-col l6 sm12 m12 w3-container w3-margin-bottom">
  	  <div class="table-responsive hrf_arial w3-card-4" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 70%">
  	  	<p class="w3-large w3-center">Cek Duplikat KODE BARANG </p>	
	    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
	    <thead>
	      <tr align="middle" class="yz-theme-l1">
	        <th>No.</th>
	        <th>BARCODE</th>
	        <th>KODE BARANG</th>
	        <th>NAMA BARANG</th>
	      </tr>
	    </thead>	  
	      <?php 
	      $no=0;$x=0;
          $kd_bar="";
	      $cek2=mysqli_query($connect,"SELECT kd_bar,kd_brg,nm_brg
			FROM mas_brg
			WHERE kd_brg IN (SELECT kd_brg FROM mas_brg GROUP BY kd_brg HAVING count(*) > 1) 
	   		ORDER BY kd_brg");
            while($data=mysqli_fetch_assoc($cek2)) { 
             if($data['kd_brg']==$kd_brg){
	  	       $no++;	
	  	       $x++;	
               $jump_file=$data['kd_brg'];     
	             ?>
	           <tr>
	         	 <td align="right"><?=$no.'.';?></td>
	         	 <td style="color:red;"><?=$data['kd_bar'];?><i class="w3-text-blue w3-right fa fa-check-square-o"></td>
	         	 <td><?=$data['kd_brg'];?></td>
	         	 <td><?=$data['nm_brg'];?></td>
	           </tr>
	           <?php
	         }else{ $no=0;$no++;$x++; ?>
	           <tr><td></td></tr>	
	           
               <tr>
               	  <td align="right"><?=$no.'.';?></td>
		          <td style="color:red"><?=$data['kd_bar'];?><i class="w3-text-blue w3-right fa fa-check-square-o"></i></td>
		          <td id="<?=$x?>"><?=$data['kd_brg'];?></td>
		          <td><?=$data['nm_brg'];?></td>
	           </tr>

               <?php
	         }//if 
	         $kd_brg=$data['kd_brg'];  
	        }//while
	        if ($no>0){$ket='Ditemukan '.$x.' duplikat kode barang';}else{$ket='Tidak Ditemukan Duplikat Data';}
	      ?>
	      <tr align="right" class="yz-theme-l1">
		    <th colspan="4" style="text-align: center;position: sticky;bottom: 0;"><?=$ket?></th>
	      </tr>
	    </table>
	  </div>    
  	</div>

  	<div class="w3-col l6 sm12 m12 w3-container w3-margin-bottom">
  	  <div class="table-responsive hrf_arial w3-card-4" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 70%">
  	  	<p class="w3-large w3-center">Cek Duplikat nama barang </p>	
	    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
	      <tr align="middle" class="yz-theme-l1">
	        <th>No.</th>
	        <th>BARCODE</th>
	        <th>KODE BARANG</th>
	        <th>NAMA BARANG</th>
	        <th>JML.BRG</th>
	      </tr>
	      <?php 
	      $no=0;$x=0;
          $nm_brg="DOWNY 10ML SUNRISE FRESH";
	      $cek2=mysqli_query($connect,"SELECT kd_bar,kd_brg,nm_brg,jml_brg
			FROM mas_brg
			WHERE nm_brg IN (SELECT nm_brg  FROM mas_brg GROUP BY nm_brg HAVING count(*) > 1) 
	   		ORDER BY kd_brg");
            while($data=mysqli_fetch_assoc($cek2)) { 
             if($data['nm_brg']==$nm_brg){
	  	       $no++;	
	  	       $x++;	
               $jump_file=$data['nm_brg'];     
	             ?>
	           <tr>
	         	 <td align="right"><?=$no.'.';?></td>
	         	 <td style="color:blue;"><?=$data['kd_bar'];?></td>
	         	 <td><?=$data['kd_brg'];?></td>
	         	 <td style="color:red;"><?=$data['nm_brg'];?><i class="w3-text-blue w3-right fa fa-check-square-o"></i></td>
	         	 <td align="center"><?=$data['jml_brg'];?></td>
	           </tr>
	           <?php
	         }else{ $no=0;$no++;$x++; ?>
	           <tr><td></td></tr>	
	           
               <tr>
               	  <td align="right"><?=$no.'.';?></td>
		          <td style="color:blue"><?=$data['kd_bar'];?></td>
		          <td id="<?=$x?>"><?=$data['kd_brg'];?></td>
		          <td style="color:red;"><?=$data['nm_brg'];?><i class="w3-text-blue w3-right fa fa-check-square-o"></i></td>
		          <td align="center"><?=$data['jml_brg'];?></td>
	           </tr>

               <?php
	         }//if 
	         $nm_brg=$data['nm_brg'];  
	        }//while
	        if ($no>0){$ket='Ditemukan '.$x.' duplikat nama barang';}else{$ket='Data valid';}
	      ?>
	      <tr align="right" class="yz-theme-l1">
		    <th colspan="6" style="text-align: center;position: sticky;bottom: 0;"><?=$ket?></th>
	      </tr>
	    </table>
	  </div>    	
  	</div>

	<div class="w3-col l6 sm12 m12 w3-container w3-margin-bottom">
  	  <div class="table-responsive hrf_arial w3-card-4" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 70%;">
  	  	<p class="w3-large w3-center">Cek Stok Pembelian dengan Master Jumlah Barang</p>	
	    <table class="table table-bordered table-sm table-hover" style="font-size:9pt;position: sticky;top:30px ">
	      <thead >
	      <tr align="middle" class="yz-theme-l1">
	        <th class="yz-theme-l1">No.</th>
	        <th>KODE BARANG</th>
            <th>NAMA BARANG</th> 
            <th>STOK BELI</th>
            <th>BRG.MSK</th>
            <th>BRG.KLR</th>
            <th>JML.BRG</th>
	      </tr>
	      </thead>	
	      <?php 
	      $no=0;
          $kd_bar="";
          $cek2=mysqli_query($connect,"SELECT sum(beli_brg.stok_jual) as jumbeli,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_brg,mas_brg.jml_brg,mas_brg.brg_msk,mas_brg.brg_klr,mas_brg.nm_brg from beli_brg 
          	  left join mas_brg on beli_brg.kd_brg=mas_brg.kd_brg
          	  group by beli_brg.kd_brg
          	  " );
            while($data=mysqli_fetch_assoc($cek2)){ 
             if($data['jumbeli'] !== $data['jml_brg']){
               $no++;		
	             ?>
	            <tr>
					<td align="right"><?=$no.'.';?></td>
					<td><?=$data['kd_brg']?><i class="w3-text-red fa fa-check-square-o" style="margin-left: 20px"></i></td>
					<td><?=$data['nm_brg'];?></td>
					<td style="text-align: center"><?=$data['jumbeli'];?></td>
					<td style="text-align: center"><?=$data['brg_msk'];?></td>
					<td style="text-align: center"><?=$data['brg_klr'];?></td>
					<td style="text-align: center"><?=$data['jml_brg'];?></td>
	            </tr>
	           <?php 
	          }//if 
	        }//while
	        if ($no>0){$ket='Ditemukan '.$no.' record data tdk valid';}else{$ket='Tidak Ditemukan data menyimpang';}
	      ?>
	      <tr align="right" class="yz-theme-l1">
		    <th colspan="9" style="text-align: center;position: sticky;bottom: 0px"><?=$ket?></th>
	      </tr>
	    </table>
	  </div>    	
  	</div>

  </div> 
  <!--  -->
  
 
<?php mysqli_close($connect); ?>
</div>	