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
<script>   
  function listbrg(page_number, search){
    $.ajax({
      url: 'f_cetbarcode_cari.php', // File tujuan
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
        
        $("#viewbar").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }

  function cekkdbaroff (kd_brg){
    $.ajax({
      url: 'f_cetbarcode_off.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword:kd_brg}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        
        $("#viewcekkd").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }

  function cekkdbaron (kd_brg){
  
    $.ajax({
      url: 'f_cetbarcode_on.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword:kd_brg}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        
        $("#viewcekkd").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  
  function cekkdbarcop (kd_brg){
  
    $.ajax({
      url: 'f_cetbarcode_cop.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword:kd_brg}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        
        $("#viewcekkd").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }

  function docek(bcodes){   
      $.ajax({
      url: 'f_cetbarcode_cek.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {bcode:bcodes}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){  
        $("#viewcekbar").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    }); 
  }

	function cetakgo(bcodes){   
    $.ajax({
      url: 'f_cetbarcodego.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {bcodes:bcodes}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){  
        $("#viewcetakgo").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    }); 
  }

  function pilihprint(bcodes){   
    $.ajax({
      url: 'f_cetbarcodepilih.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {bcodes:bcodes}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){  
        $("#viewcetakgo").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    }); 
  }

    // const encoder = new TextEncoder();  
    // function generateQrCmd(qrText, size = 10) {
    // 	const storeLen = qrText.length + 3;
    // 	const pL = storeLen % 256;
    // 	const pH = Math.floor(storeLen / 256);

    // 	return [
    // 	// Set QR model
    // 	0x1D, 0x28, 0x6B, 0x04, 0x00, 0x31, 0x41, 0x32, 0x00,
    // 	// Set QR size
    // 	0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x43, size,
    // 	// Error correction
    // 	0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x45, 0x31,
    // 	// Store data
    // 	0x1D, 0x28, 0x6B, pL, pH, 0x31, 0x50, 0x30,
    // 	...encoder.encode(qrText),
    // 	// Print QR
    // 	0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x51, 0x30
    // 	];
    // }
</script>
<div id="main" style="font-size: 10pt;background: linear-gradient(185deg, #FAFAD2 10%, white 80%)">
  <div id="snackbar" style="z-index: 1"></div> 
  <div style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position:sticky;top:44px;margin-top: -14px;z-index: 1; color:white;text-shadow:1px 1px 2px black;padding:4px" class="mb-2">
    <div class="hrf_rale w3-container">
      <i class='fa fa-briefcase' style="color:yellow"></i>&nbsp;
      MAINTENANCE&nbsp;
      <i class='fa fa-angle-double-right'></i>&nbsp;
      CETAK BARCODE
      <span class="w3-right w3-text-blue" style="margin-right:0px;text-shadow:none"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span>
    </div>
  </div>
  <input type="hidden" id="kd_cari">
  <div id="viewbar"><script>listbrg(1,true)</script></div>  
  <div id="viewcekkd"></div>
  <div id="viewcekbar"></div>  
  <div id="viewcetakgo"></div>
  <!-- key -->
  <input type="hidden" id="keybrgmsk">
  <input type="hidden" id="key_cari">
  <input type="hidden" id="key_cari2">
</div>  
