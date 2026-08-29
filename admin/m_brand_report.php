<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
<?php 
 include 'starting.php';
 include 'f_cetak_jual_item_helper.php';
 ensureBrandReportTable($connect);
?>

<div id="main" style="font-size: 10pt">
  	<script>	
	  function cekid(){
	  	  <?php
          $cek=mysqli_query($connect,"SELECT MAX(no_urut) AS mx FROM brand_report");
          $max=mysqli_fetch_assoc($cek);
          $id=((int)$max['mx'])+1;
          mysqli_free_result($cek);
          ?>
	      document.getElementById("no_urut").value='<?=$id?>';
		}
			
	  function kosongkan(){
	    document.getElementById('nm_brand').value="";
	    document.getElementById('no_urut').value="";
	    document.getElementById('nm_brand').focus();
	 	}  

	 	function caribrand(page_number, search){
		  $.ajax({
		    url: 'm_brand_report_cari.php',
		    type: 'POST',
		    data: {keyword: $("#keyktbrand").val(), page: page_number, search: search}, 
		    dataType: "json",
		    beforeSend: function(e) {
		      if(e && e.overrideMimeType) {
		        e.overrideMimeType("application/json;charset=UTF-8");
		      }
		    },
		    success: function(response){ 
		      $("#viewdtbrand").html(response.hasil);
		    },
		    error: function (xhr) {
		      alert(xhr.responseText);
		    }
		  });
	    }
    </script> 

    <div id="snackbar" style="z-index: 1"></div>
    <?php 
    if(isset($_GET['pesan'])){
      $pesan=$_GET['pesan'];
      if($pesan=="simpan"){
        ?><script>popnew_ok("Data berhasil disimpan");</script><?php
      }else if($pesan=="hapus"){
        ?><script>popnew_warning("Data berhasil dihapus");</script><?php
      }else if($pesan=="gagal"){
        ?><script>popnew_error("Ops.. gagal untuk transaksi");</script><?php
      }else if($pesan=="duplikat"){
        ?><script>popnew_warning("Nama brand sudah ada");</script><?php
      }
    } 
    ?>
  
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
    	<i class='fa fa-briefcase' style="font-size: 18px">&nbsp;MASTER DATA &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Brand Report Penjualan</span>
    </div>
    <div class="w3-row" style="background: linear-gradient(565deg, #FAFAD2 30%, white 100%);">
      <div class="col-sm-12 w3-card-4">
    	  <form id="form1" class="w3-container" action="m_brand_report_act.php" method="post">
    	  	<div class="row w3-margin-top">
    	  		<div class="col-sm-6">
    	  		    <div class="form-group row">
                        <input type="hidden" name="no_urut" id="no_urut">
    	   	            <label for="nm_brand" class="col-sm-4 col-form-label"><b>Nama Brand</b></label>
    	   	            <div class="col-sm-8">
    	   	               <input class="form-control hrf_arial" id="nm_brand" type="text" name="nm_brand" autofocus required style="border: 1px solid black;font-size: 10pt;" placeholder="Contoh: WARDAH">
    	   	            </div>
      	   	        </div>	
    	  		</div>
    	  	</div>
	        <div class="row">
	          <div class="col-sm-6">
                  <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l1"><i class="fa fa-save">&nbsp;&nbsp;</i><b>S I M P A N</b></button>
              </div>	
              <div class="col-sm-6" style="padding-bottom: 2px">
                  <button onclick="kosongkan();cekid()" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 btn-warning"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
              </div>
          </div>  
    	  </form>	
  	    <div class=" yz-theme-l5 w3-border">
          <div class="w3-row">
          	<div class="w3-half">
          		<div id="ket_rec" class="fa fa-television" style="margin-top: 15px;margin-left: 10px;font-size: 13pt">Brand untuk filter cetak penjualan</div>
          	</div>
          	<div class="w3-half">
              <div class="input-group" style="margin-top: 15px">
	 	  	        <input onkeyup="if(event.keyCode==13){caribrand(1, true);}" style="font-size: 10pt;height: 30px" type="text" class="form-control hrf_arial" placeholder="Cari nama brand" id="keyktbrand">&nbsp;
                <span class="input-group-btn w3-margin-bottom">
                  <button onclick="caribrand(1, true);" class="btn btn-primary" type="button" id="btn-ktbrand" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i></button>
                  <a style="font-size: 10pt;" title="Reset cari" onclick="document.getElementById('keyktbrand').value='';document.getElementById('btn-ktbrand').click();" href="#" class="btn btn-warning"><i class="fa fa-undo"></i></a>
                </span>
              </div>		
          	</div>
          </div>	
        </div>  
        <div class="hrf_arial" id="viewdtbrand" style="margin-top: 0px;"><script>caribrand(1,true)</script></div>
	    </div>  
    </div>
</div>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>