<!DOCTYPE html>
<html lang="id">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php 
 include 'starting.php';
 $kd_toko=$_SESSION['id_toko'];
?>
<style>
  th {
  position: sticky;
  top: 0px; 
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }
  td {
    border: 1px solid lightgrey;
    padding: 2px;
  }
  th {
    border: 1px solid lightgrey;
    padding: 4px;
  }
  table {
    border-spacing: 1px;
  }
</style>
<div id="main" style="font-size: 10pt;background: linear-gradient(185deg, #FAFAD2 10%, white 80%)">
<div id="snackbar" style="z-index: 1"></div> 
  <script>     
    function listbag(page_number, search){ 
	    $.ajax({
	      url: 'f_setbag_cari.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword1:$("#kd_cari").val(),keyword2:$("#kd_cari2").val(), page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewbag").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }
    
    function listpilbag(search){ 
	    $.ajax({
	      url: 'f_setbag_pilcari.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {search:search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        $("#box-bag").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    function setbagrep(kd_brg,id_bag){
      $(".loader1").fadeIn();
      $.ajax({
        url: 'f_setbag_rep.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword1:kd_brg, keyword2:id_bag}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $(".loader1").fadeOut();
          $("#viewrepbag").html(response.hasil);
        
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }    
  </script>
  <div class="loader1"><div class="loader2"><div class="loader3"></div></div></div>
  <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;color:white;text-shadow: 1px 1px 2px black;font-size: 18px">
    <i class='fa fa-server w3-text-orange' style="font-size: 18px"></i>&nbsp;MAINTENANCE &nbsp; <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Set bagian penjualan</span><span style="font-size: 12pt" class="w3-right w3-text-orange"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set']);?></span>
  </div>
  <input type="hidden" id="kd_cari">
  <input type="hidden" id="kd_cari2">
  <div id="viewbag"><script>listbag(1,true)</script></div>  
  <div id="viewcekkd"></div>  
  <div id="viewrepbag"></div>
  
</div>  
<script>
  $(document).ready(function(){
      $(".loader1").fadeOut();
    })
</script>
