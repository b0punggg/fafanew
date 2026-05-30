<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<?php 
  include 'starting.php';
  $kd_toko=$_SESSION['id_toko'];
  $connect=opendtcek();
  //echo 'tanggal'.date('m',strtotime($_SESSION['tgl_set']));
?>

<div id="main" style="font-size: 10pt">
  	<script>	
	  	
			
	  function kosongkan(){
	      document.getElementById('tgl_kas').value="<?=date('Y-m-d')?>";
        document.getElementById('uang_kas').value="";
        document.getElementById('no_urut').value="";
        // document.getElementById('tgl_cari').value="";
	      document.getElementById('uang_kas').focus();
        cariaruskas(1,true);
	 	}  

	 	function carikas(page_number, search){
		  $(this).html("ketik pencarian").attr("disabled", "disabled");
		  
		  $.ajax({
		    url: 'f_kascari.php', // File tujuan
		    type: 'POST', // Tentukan type nya POST atau GET
		    data: {keyword: $("#keyktkas").val(), page: page_number, search: search}, 
		    dataType: "json",
		    beforeSend: function(e) {
		      if(e && e.overrideMimeType) {
		        e.overrideMimeType("application/json;charset=UTF-8");
		      }
		    },
		    success: function(response){ 
		      // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
		      
		      $("#viewdtkas").html(response.hasil);
		    },
		    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
		      alert(xhr.responseText); // munculkan alert
		    }
		  });
	    }

      function cariaruskas(page_number, search){
      $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'f_kascariarus.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword: $("#tgl_cari").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewdtaruskas").html(response.hasil);
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
      $pesan=mysqli_real_escape_string($connect,$_GET['pesan']);
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
  
    <div class="w3-container" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;z-index: 1; padding: 0px;margin-top: -5px">
    	<h5 style="margin-top: 7px;margin-left: 20px"><i class='fa fa-briefcase'></i> &nbsp;MASTER DATA &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Kas harian</span><span class="w3-right" style="font-size: 16px"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span></h5>
    </div>
    <hr class="w3-black" style="margin-top: 0px">
      <div class="w3-row" style="border-style: ridge;border-radius: 5px;">
      	<!-- #F0E68C -->
        <div class="col-sm-12 w3-card-4">
      	  <form id="form1" class="w3-container" action="f_kas_act.php" method="post">
            <!-- key edit -->
            <input type="hidden" name="no_urut" id="no_urut">
            <input type="hidden" name="tgl_cari" id="tgl_cari">
            <!-- -------- -->
      	  	<div class="row w3-margin-top">
      	  		<div class="col-sm-6">
      	  		  <div class="form-group row" style="font-size: 9pt;">
    	   	        <label for="tgl_kas" class="col-sm-4 col-form-label"><b>Tanggal</b></label>		
    	   	        <div class="col-sm-8">
    	   	          <input class="form-control hrf_arial" id="tgl_kas" type="date" name="tgl_kas" value="<?=date('Y-m-d')?>" autofocus required style="border: 1px solid black;font-size: 9pt;">
    	   	        </div>
    	   	      </div>	
      	  		</div>

      	  		<div class="col-sm-6 ">
      	  		  <div class="form-group row " style="font-size: 9pt;">
    	   	        <label for="uang_kas" class="col-sm-4 col-form-label"><b>Kas</b></label>
    	   	        <div class="col-sm-8">
    	   	          <input class="form-control hrf_arial money" id="uang_kas" type="text" name="uang_kas" required style="border: 1px solid black; font-size: 9pt" >
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
                    <button onclick="kosongkan()" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l1"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
                </div>
              </div>  
  	        <!-- End tombol -->
      	  </form>	
      	  <hr class="w3-black">
          <div class="row">
            <div class="col-sm-5" style="overflow-y: auto;border-style: ridge; padding-left: -19px;height: 400px;padding: 2px 2px 2px 2px ">
              <div class=" yz-theme-l5 w3-border">
                <div class="w3-row">
                  <div class="w3-container" >
                    <div id="ket_rec" class="fa fa-television" style="margin-top: 15px;margin-left: 10px;font-size: 10pt">
                      
                    </div>
                    <div class="input-group w3-margin-bottom" style="margin-top: 15px">
                       <input id="keyktkas" type="hidden">
                       <select class="form-control" name="bulan" id="bulan" style="border: 1px solid black;font-size: 9pt;height:30px" value="<?=date('m',strtotime($_SESSION['tgl_set']))?>">
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">Nopember</option>
                        <option value="12">Desember</option>
                      </select>
                      
                      <input class="form-control hrf_arial" type="number" id="tahun" name="tahun" style="border: 1px solid black;font-size: 9pt;height:30px" value="<?=date('Y',strtotime($_SESSION['tgl_set']))?>">
                      <div class="input-group-prepend">
                        <span class="input-group-append">
                        <button onclick="
                           document.getElementById('keyktkas').value=document.getElementById('bulan').value+';'+document.getElementById('tahun').value;carikas(1, true);
                           document.getElementById('tgl_cari').value=document.getElementById('bulan').value+';'+document.getElementById('tahun').value;cariaruskas(1, true);" 
                           class="btn btn-primary" type="button" id="btn-ktkas" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i>
                        </button>
                        <button style="font-size: 10pt;" title="Reset cari" onclick="document.getElementById('keyktkas').value='';carikas(1, true);" class="btn btn-warning"><i class="fa fa-undo"></i></buton>
                        </span>
                      </div>
                     <script>
                        document.getElementById("bulan").value="<?=date('m',strtotime($_SESSION['tgl_set']))?>"
                        document.getElementById("tgl_cari").value="<?=date('m',strtotime($_SESSION['tgl_set'])).';'.date('Y',strtotime($_SESSION['tgl_set']))?>"
                    </script>
                    </div>    
                  </div>
                </div>  
              </div>  
              <div class="hrf_arial" id="viewdtkas" style="margin-top: 0px;"><script>carikas(1,true)</script>
              </div>
            
            </div>        
            <div class="col-sm-7"  style="overflow-y: auto;border-style: ridge; padding-left: -19px;height: 400px;padding: 2px 2px 2px 2px ">
              <div class=" yz-theme-l5 w3-border">
                <div class="w3-row">
                  <div class="w3-container" >
                    <div id="ket_rec1" class="fa fa-television w3-margin-bottom" style="margin-top: 15px;margin-left: 10px;font-size: 10pt">
                    </div>

                   <!--  <div class="input-group" style="margin-top: 15px">
                       <input onkeyup="if(event.keyCode==13){carikas(1, true);}" style="font-size: 10pt;height: 30px" type="date" class="form-control hrf_arial" placeholder="ketik Tanggal" id="keyktkas">&nbsp;
                      <span class="input-group-btn w3-margin-bottom">
                        <button onclick="carikas(1, true);" class="btn btn-primary" type="button" id="btn-ktkas" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i></button>
                        <a style="font-size: 10pt;" title="Reset cari" onclick="document.getElementById('keyktkas').value='';document.getElementById('btn-ktkas').click();" href="#" class="btn btn-warning"><i class="fa fa-undo"></i></a>
                      </span>
                    </div>     -->
                  </div>
                </div>  
              </div>  
              <div class="hrf_arial" id="viewdtaruskas" style="margin-top: 0px;"><script>cariaruskas(1,true)</script>
              </div>
              <br>
            </div>          
            </div>
          </div><!-- row -->    
        </div>
      </div>
</div>

          
          