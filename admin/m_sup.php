<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>

<?php 
 include 'starting.php';
?>

<div id="main" style="font-size: 10pt">
  	<script>	
	  	function cekid(){
	  	  <?php 
        $hub=opendtcek();
        $cek=mysqli_query($hub,"select MAX(no_urut) as jum from supplier");
	            $max=mysqli_fetch_assoc($cek); 
		        $id=$max['jum']+1;
        mysqli_close($hub);     
	      ?>

	      <?php if (mysqli_num_rows($cek)>0) {
	      	 ?> 
	      	   document.getElementById("no_urut").value='<?=$id?>';
             document.getElementById("kd_sup").value='IDPEM-'+'<?=$id?>';
	      	 <?php 
	      	   unset($cek);unset($max);
	      	 } else { ?> document.getElementById("no_urut").value='1';
                       document.getElementById("kd_sup").value='IDPEM-1';
	      	 <?php
	      } ?>    
		  }
			
	  	function kosongkan(){
	      document.getElementById('kd_sup').value="";
	      document.getElementById('nm_sup').value="";
        document.getElementById('nm_sales').value="";
        document.getElementById('al_sup').value="";
        document.getElementById('no_telp').value="";
        document.getElementById('no_urut').value="";
	      document.getElementById('kd_sup').focus();
	 	}  

	 	function carisup(page_number, search){
		  $(this).html("ketik pencarian").attr("disabled", "disabled");
		  
		  $.ajax({
		    url: 'm_supcari.php', // File tujuan
		    type: 'POST', // Tentukan type nya POST atau GET
		    data: {keyword: $("#keyktsup").val(), page: page_number, search: search}, 
		    dataType: "json",
		    beforeSend: function(e) {
		      if(e && e.overrideMimeType) {
		        e.overrideMimeType("application/json;charset=UTF-8");
		      }
		    },
		    success: function(response){ 
		      // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
		      
		      $("#viewdtsup").html(response.hasil);
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
  
  <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
    <i class='fa fa-briefcase' style="font-size: 18px">&nbsp;MASTER DATA &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Supplier / Sales</span>
  </div>

  <div class="w3-row" style="background: linear-gradient(565deg, #FAFAD2 30%, white 100%);">
      	<!-- #F0E68C -->
    <div class="col-sm-12 w3-card-4">
  	  <form id="form1" class="w3-container" action="m_sup_act.php" method="post">
  	  	<div class="row w3-margin-top">
  	  		<div class="col-sm-6">
  	  		  <div class="form-group row">
              <input type="hidden" name="no_urut" id="no_urut">
  	   	      <label for="kd_sup" class="col-sm-4 col-form-label"><b>Kode Supplier</b></label>
  	   	      <div class="col-sm-8">
  	   	        <input class="form-control hrf_arial" id="kd_sup" type="text" name="kd_sup" autofocus required style="border: 1px solid black;font-size: 10pt;" >
  	   	      </div>
    	   	 </div>	
           <script>cekid();</script>
           <div class="form-group row" style="margin-top: -10px">
              <label for="nm_sup" class="col-sm-4 col-form-label"><b>Nama Supplier</b></label>
              <div class="col-sm-8">
                <input class="form-control hrf_arial" id="nm_sup" type="text" name="nm_sup" autofocus required style="border: 1px solid black;font-size: 10pt;">
              </div>
           </div>   
           <div class="form-group row " style="margin-top: -10px">
              <label for="al_sup" class="col-sm-4 col-form-label"><b>Alamat</b></label>
              <div class="col-sm-8">
                <input class="form-control hrf_arial" id="al_sup" type="text" name="al_sup" required style="border: 1px solid black; font-size: 10pt" >
              </div>
            </div>  
  	  		</div>

  	  		<div class="col-sm-6 ">
  	  		  <div class="form-group row " >
              <label for="no_telp " class="col-sm-4 col-form-label"><b>No.Telp</b></label>
              <div class="col-sm-8">
                <input class="form-control hrf_arial telp" id="no_telp" type="text" name="no_telp" required style="border: 1px solid black; font-size: 10pt" >
              </div>
            </div>
            <div class="form-group row"  style="margin-top: -10px">
              <label for="nm_sales" class="col-sm-4 col-form-label"><b>Contack Person</b></label>
              <div class="col-sm-8">
                <input class="form-control hrf_arial hp" id="nm_sales" type="text" name="nm_sales" autofocus required style="border: 1px solid black;font-size: 10pt;">
              </div>
           </div>   
  	  		</div>
  	  	</div>

  	  	<!--Tombol reset/simpan  -->
        <div class="row">
          <div class="col-sm-6">
            <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l1"><i class="fa fa-save">&nbsp;&nbsp;</i><b>S I M P A N</b></button>
          </div>	
          <div class="col-sm-6" style="padding-bottom: 2px">
            <button onclick="kosongkan();cekid()" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="btn-warning"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
          </div>
        </div>  
        <!-- End tombol -->
  	  </form>	

      <div class=" yz-theme-l5 w3-border">
          <div class="w3-row">
            <div class="w3-half" >
              <div id="ket_rec" class="fa fa-television" style="margin-top: 15px;margin-left: 10px;font-size: 13pt">  
              </div>
            </div>
            <div class="w3-half">
              <div class="input-group" style="margin-top: 15px">
                <input onkeyup="if(event.keyCode==13){carisup(1, true);}" style="font-size: 10pt;height: 30px" type="text" class="form-control hrf_arial" placeholder="ketik pencarian [nama supplier]" id="keyktsup">&nbsp;
                <span class="input-group-btn w3-margin-bottom">
                  <button onclick="carisup(1, true);" class="btn btn-primary" type="button" id="btn-ktsup" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i></button>
                  <a style="font-size: 10pt;" title="Reset cari" onclick="document.getElementById('keyktsup').value='';document.getElementById('btn-ktsup').click();" href="#" class="btn btn-warning"><i class="fa fa-undo"></i></a>
                </span>
              </div>    
            </div>
          </div>  
        </div>  
        <div class="hrf_arial" id="viewdtsup" style="margin-top: 0px;"><script>carisup(1,true)</script></div>
      </div>  

    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     