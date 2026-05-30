<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php 
 include 'starting.php';
?>
<style>
  th {
    border: 1px solid lightgrey;
    padding: 6px;
  }
  table {
    border-spacing: 1px;
  }
</style>
<div id="main" style="font-size: 10pt;">
  <script>  
        
    function liststok2(page_number, search){
    
	    $.ajax({
	      url: 'f_stokbrg2_cari.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword:$("#kd_cari").val(), page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewstok").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    function liststok3(page_number, search){
	  $.ajax({
	    url: 'f_stokbrg3_cari.php', // File tujuan
	    type: 'POST', // Tentukan type nya POST atau GET
	    data: {keyword:$("#kd_cari").val(), page: page_number, search: search}, 
	    dataType: "json",
	    beforeSend: function(e) {
	    if(e && e.overrideMimeType) {
	      e.overrideMimeType("application/json;charset=UTF-8");
	      }
	    },
	    success: function(response){ 
	      $("#viewstok3").html(response.hasil);
	    },
	    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	      alert(xhr.responseText); // munculkan alert
	    }
	  });
    }

    function carimut_msk(page_number, search){
    
	    $.ajax({
	      url: 'f_carimut_msk.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword:$("#keybrgmsk").val(),page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewmutasimasuk").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    function carimut_klr(page_number, search){
    
	    $.ajax({
	      url: 'f_carimut_klr.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword:$("#keybrgmsk").val(),page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewmutasikeluar").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }
   </script>     

  <div class="w3-container w3-padding-small" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);position: sticky;top:43px;margin-top:-7px;z-index: 1;">
  	<h5><i class='fa fa-database'></i> &nbsp;TRANSAKSI &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Perbandingan Harga Beli Perbarang </span><span class="w3-right" style="font-size: 16px"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span></h5>
  </div>
  <input type="hidden" id="kd_cari">
  <div id="viewstok3"><script>liststok3(1,true)</script></div>  
  <br>
  <div id="viewstok"><script>liststok2(1,true)</script></div>  
  <!-- key -->
  <input type="hidden" id="keybrgmsk">
</div>  