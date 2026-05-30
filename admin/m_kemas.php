<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
<?php 
 include 'starting.php';
 $connect=opendtcek();
?>

<div id="main" style="font-size: 10pt">
  	<script>	
	  	function cekid(){
	  	  <?php $cek=mysqli_query($connect,"select MAX(no_urut) from kemas");
	            $max=mysqli_fetch_row($cek); 
		        $id=$max[0]+1; 
	      ?>

	      <?php if (mysqli_num_rows($cek)>0) {
	      	 ?> 
	      	   document.getElementById("no_urut").value='<?=$id?>';
	      	 <?php 
	      	   unset($cek);unset($max);
	      	 } else { ?> document.getElementById("no_urut").value='1';
	      	 <?php
	      } 
        mysqli_close($connect); ?>    
		}
			
	  function kosongkan(){
	      document.getElementById('nm_sat1').value="";
	      document.getElementById('nm_sat2').value="";
        document.getElementById('no_urut').value="";
	      document.getElementById('nm_sat1').focus();
	 	}  

	 	function carikemas(page_number, search){
		  $(this).html("ketik pencarian").attr("disabled", "disabled");
		  
		  $.ajax({
		    url: 'm_kemascari.php', // File tujuan
		    type: 'POST', // Tentukan type nya POST atau GET
		    data: {keyword: $("#keyktkemas").val(), page: page_number, search: search}, 
		    dataType: "json",
		    beforeSend: function(e) {
		      if(e && e.overrideMimeType) {
		        e.overrideMimeType("application/json;charset=UTF-8");
		      }
		    },
		    success: function(response){ 
		      // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
		      
		      $("#viewdtkemas").html(response.hasil);
		    },
		    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
		      alert(xhr.responseText); // munculkan alert
		    }
		  });
	    }
    </script> 

    <div id="snackbar" style="z-index: 1"></div>
    <?php 
    if(isset($_GET['pesan'])){
      $pesan=$_GET['pesan'];
      if($pesan=="simpan"){
        ?>
          <script>popnew_ok("Data berhasil disimpan");</script>
        <?php
      }else if($pesan=="hapus"){
        ?>
          <script>popnew_warning("Data berhasil dihapus");</script>
        <?php
      }else if($pesan=="gagal"){
        ?>
          <script>popnew_error("Ops.. gagal untuk transaksi");</script>
        <?php
      }
    } 
    ?>
  
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;font-size:18px;color:white;text-shadow: 1px 1px 1px black;">
    	<i class='fa fa-briefcase' style="font-size: 18px;color:orange"></i> &nbsp;MASTER DATA &nbsp; <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Kemasan barang</span>
    </div>
    <div class="w3-row" style="background: linear-gradient(565deg, #FAFAD2 30%, white 100%);">
      <div class="col-sm-12 w3-card-4">
    	  <form id="form1" class="w3-container" action="m_kemas_act.php" method="post">
    	  	<div class="row w3-margin-top">
    	  		<div class="col-sm-4">
    	  		  <div class="form-group row">
                    <input type="hidden" name="no_urut" id="no_urut">
    	   	        <label for="nm_sat1 " class="col-sm-4 col-form-label"><b>Nama Ringkas</b></label>
    	   	        <div class="col-sm-8">
    	   	          <input class="form-control hrf_arial" id="nm_sat1" type="text" name="nm_sat1" autofocus required style="border: 1px solid black;font-size: 10pt;">
    	   	        </div>
      	   	      </div>	
    	  	    </div>

    	  		<div class="col-sm-4">
    	  		  <div class="form-group row ">
      	   	        <label for="nm_sat2" class="col-sm-4 col-form-label"><b>Keterangan</b></label>
      	   	        <div class="col-sm-8">
      	   	          <input class="form-control hrf_arial" id="nm_sat2" type="text" name="nm_sat2" required style="border: 1px solid black; font-size: 10pt" >
      	   	        </div>
      	   	      </div>	
    	  		</div>
				<div class="col-sm-2">
                  <button type="submit" class="btn-sm btn-primary form-control w3-margin-bottom w3-card-2"><i class="fa fa-save">&nbsp;&nbsp;</i><b>S I M P A N</b></button>
                </div>	
				<div class="col-sm-2">
                  <button onclick="kosongkan();cekid()" type="button" class="btn-sm btn-warning form-control w3-margin-bottom w3-card-2"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
                </div>
    	  	</div>
    	  	
    	  </form>	
    	  
    	  
  	    <div class=" yz-theme-l5 w3-border">
          <div class="w3-row">
          	<div class="w3-half" >
          		<div id="ket_rec" class="fa fa-television" style="margin-top: 15px;margin-left: 10px;font-size: 13pt">  
              </div>
          	</div>
          	<div class="w3-half">
              <div class="input-group" style="margin-top: 15px">
	 	  	        <input onkeyup="if(event.keyCode==13){carikemas(1, true);}" style="font-size: 10pt;height: 30px" type="text" class="form-control hrf_arial" placeholder="ketik pencarian [keterangan nama satuan]" id="keyktkemas">&nbsp;
                <span class="input-group-btn w3-margin-bottom">
                  <button onclick="carikemas(1, true);" class="btn btn-primary" type="button" id="btn-ktkemas" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i></button>
                  <a style="font-size: 10pt;" title="Reset cari" onclick="document.getElementById('keyktkemas').value='';document.getElementById('btn-ktkemas').click();" href="#" class="btn btn-warning"><i class="fa fa-undo"></i></a>
                </span>
              </div>		
          	</div>
          </div>	
        </div>  
        <div class="hrf_arial" id="viewdtkemas" style="margin-top: 0px;"><script>carikemas(1,true)</script></div>
	    </div>  
		  
    </div>
</div>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     