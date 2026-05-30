<!DOCTYPE html>
<html lang="id">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php 
 include 'starting.php';
?>
<style>
  #viewstok {
    font-size: 10pt;
  }
  #viewstok .stok-table {
    font-size: 9pt;
  }
  #viewstok .stok-table th {
    position: sticky;
    top: 0px; 
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    background-color: #f0f8ff;
  }
</style>
<div id="main" style="font-size: 10pt;">
  <script>  
        
    function liststok(page_number, search){
    
	    $.ajax({
	      url: 'f_stokbrg_cari.php', // File tujuan
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
	      data: {keyword1:$("#keybrgmsk").val(),keyword2:$("#key_cari2").val(),page: page_number, search: search}, 
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

    function carimut_gud(page_number, search){
    
	    $.ajax({
	      url: 'f_carimutgud_klr.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword1:$("#keybrgmsk").val(),keyword2:$("#key_cari").val(),page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewmutasigudang").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    function carimut_ret(page_number, search){
    
	    $.ajax({
	      url: 'f_carimut_retur.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword1:$("#keybrgmsk").val(),keyword2:$("#key_cari").val(),page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewmutasiretur").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    function infojust(page_number, search){
	    $.ajax({
	      url: 'f_carijust.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword1:$("#keybrgmsk").val(),keyword2:$("#key_cari").val(),page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewjust").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }
    function infostok(page_number, search){
	    $.ajax({
	      url: 'f_caristok.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword1:$("#keybrgmsk").val(),keyword2:$("#key_cari").val(),page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewinfostok").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    function tampilkanstok(id){
	    $.ajax({
	      url: 'f_stokbrgtampil.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword:id}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        popnew_ok(response.hasil);
	        liststok(1,true);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }
   </script>
   
  <div class="w3-container" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);position: sticky;top:43px;margin-top:-6px;z-index: 1;font-size: 18px">
  	<i class='fa fa-database'></i> &nbsp;TRANSAKSI &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 15px">STOK BARANG</span><span class="w3-right"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set']);?></span>
  </div>
  <input type="hidden" id="kd_cari">
  <div id="viewstok"><script>liststok(1,true)</script></div>  
  <!-- key -->
  <input type="hidden" id="keybrgmsk">
  <input type="hidden" id="key_cari">
  <input type="hidden" id="key_cari2">
  
</div>  
