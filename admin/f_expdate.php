<!DOCTYPE html>
<html lang="id">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php 
 include 'starting.php';
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
<div id="main" style="font-size: 10pt;">
  <script>    
    function listexp(page_number, search){
    
	    $.ajax({
	      url: 'f_expdate_cari.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword:$("#kd_cari").val(),blncr:$("#exbln").val(),thncr:$("#exthn").val(), page: page_number, search: search}, 
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
   </script>
  <div class="w3-container" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);position: sticky;top:43px;margin-top:-6px;z-index: 1;font-size: 18px">
  	<i class='fa fa-database'></i> &nbsp;TRANSAKSI &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 15px">EXPIRED DATE BARANG</span><span class="w3-right"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set']);?></span>
  </div>
  <input type="hidden" id="kd_cari">
  <div class="w3-container mt-3">
    <div class="row">
        <div class="col-sm-4 mb-2">
            <select name="blncr" id="exbln" class="form-control">
                <option value="1">JANUARI</option>
                <option value="2">FEBRUARI</option>
                <option value="3">MARET</option>
                <option value="4">APRIL</option>
                <option value="5">MEI</option>
                <option value="6">JUNI</option>
                <option value="7">JULI</option>
                <option value="8">AGUSTUS</option>
                <option value="9">SEPTEMBER</option>
                <option value="10">OKTOBER</option>
                <option value="11">NOPEMBER</option>
                <option value="12">DESEMBER</option>
            </select>
        </div>
        <div class="col-sm-3">
            <input id="exthn" name="thncr" class=" form-control mb-2" type="number" min="2023"  >
        </div>
        <div class="col-sm-1">
            <button class="btn btn-outline-success form-control w3-margin-bottom" style="min-width:50px" onclick="listexp(1,true)"><i class="fa fa-search"></i></button>
        </div>
        <div class="col-sm-1">
            <button class="btn btn-outline-primary form-control w3-margin-bottom" style="min-width:50px" onclick="
                document.getElementById('cet_cr').value=document.getElementById('kd_cari').value;
                document.getElementById('bln_cet_cr').value=document.getElementById('exbln').value;
                document.getElementById('thn_cet_cr').value=document.getElementById('exthn').value;
                document.getElementById('gooo').click();"
                ><i class="fa fa-print"></i>
            </button>
        </div>
    </div>      
    <script>
        document.getElementById("exbln").value=<?=date('m',strtotime($_SESSION['tgl_set']))?>;
        document.getElementById("exthn").value=<?=date('Y',strtotime($_SESSION['tgl_set']))?>;
    </script>
  </div>  
  <form action="f_expdate_cet.php"  method="post" target="_blank">
    <input type="hidden" id="cet_cr" name="cet_cr">
    <input type="hidden" id="bln_cet_cr" name="bln_cet_cr">
    <input type="hidden" id="thn_cet_cr" name="thn_cet_cr">
    <button type="submit" style="display:none" id="gooo">cetak</button>
  </form>
  <div id="viewstok"><script>listexp(1,true)</script></div>      
</div>  
