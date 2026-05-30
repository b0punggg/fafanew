<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php 
 include 'starting.php';
?>

<div id="main" style="font-size: 10pt;">
  <script>  
        
    function liststok555(page_number, search){
    
	    $.ajax({
	      url: 'f_mutgudang_cari.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword:$("#kd_carimut").val(), crkdtoko:$("#keytoko").val(), page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewstok555").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    function carisatuan(toko,brg,nourut){
    
	    $.ajax({
	      url: 'f_mutgudang_cari_sat.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword1:toko, keyword2:brg, keyword3:nourut}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewsat").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    function maxjumbrg(brg,toko,kdsat,nourut){
    
	    $.ajax({
	      url: 'f_mutgudang_maxjum.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {kdbrg:brg, kdtoko:toko, kdsat:kdsat, nourut:nourut }, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewmaxjum").html(response.hasil);
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
        <script>popnew_error("Gagal update data !!");</script>
      <?php
    }else if($pesan=="ada"){
      ?>
        <script>popnew_error("Gagal update, karena Sudah ada mutasi");</script>
      <?php
    }else if($pesan=="zonk"){
      ?>
        <script>popnew_error("Jumlah Barang tidak boleh kosong ");</script>
      <?php
    }
  } 
  ?>

  <div class="w3-container w3-padding-small" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);position: sticky;top:43px;margin-top:-7px;z-index: 1;">
  	<h5><i class='fa fa-database'></i> &nbsp;TRANSAKSI &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Mutasi Barang Antar Toko/Gudang</span> <span class="w3-right"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set']);?></span> </h5>
  </div>
  <input type="hidden" id="kd_carimut">
  <input type="hidden" id="keytoko">
  <div id="viewstok555"><script>liststok555(1,true)</script></div>  
  <!-- key -->
  
</div>  