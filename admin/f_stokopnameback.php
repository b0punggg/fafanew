<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">

<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>

<?php 
 include 'starting.php';
 $kd_toko=$_SESSION['id_toko'];
?>
<style>
  th {
    position: sticky;
    top: 0px; 
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    border: 1px solid lightgrey;
    padding: 3px;
  }

  table, td {
    border: 1px solid grey;
    padding: 1px;
    border-spacing: 2px;
  }
  
</style>

<script>
function caribrgstok(page_number, search){
  $(this).html("ketik pencarian").attr("disabled", "disabled");
  
  $.ajax({
    url: 'f_stokopname_cari.php', // File tujuan
    type: 'POST', // Tentukan type nya POST atau GET
    data: {keyword:$("#keycari").val(),page: page_number, search: search}, 
    dataType: "json",
    beforeSend: function(e) {
      if(e && e.overrideMimeType) {
        e.overrideMimeType("application/json;charset=UTF-8");
      }
    },
    success: function(response){ 
      // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
      
      $("#viewcaribrgstok").html(response.hasil);
    },
    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
      alert(xhr.responseText); // munculkan alert
    }
  });
}
function savedata(){
  $(this).html("ketik pencarian").attr("disabled", "disabled");
  
  $.ajax({
    url: 'f_stokopname_save.php', // File tujuan
    type: 'POST', // Tentukan type nya POST atau GET
    data: {keyword1:$("#keyadjust").val(),keyword2:$("#ket_ad").val()}, 
    dataType: "json",
    beforeSend: function(e) {
      if(e && e.overrideMimeType) {
        e.overrideMimeType("application/json;charset=UTF-8");
      }
    },
    success: function(response){ 
      // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
      
      $("#viewsavedata").html(response.hasil);
    },
    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
      alert(xhr.responseText); // munculkan alert
    }
  });
}

function carimutasinote(page,search){
  $.ajax({
    url: 'f_stokopname_carinote.php', // File tujuan
    type: 'POST', // Tentukan type nya POST atau GET
    data: {keyword:$("#keynote").val(),page:page,search:search}, 
    dataType: "json",
    beforeSend: function(e) {
      if(e && e.overrideMimeType) {
        e.overrideMimeType("application/json;charset=UTF-8");
      }
    },
    success: function(response){     
      $("#viewnote").html(response.hasil);
    },
    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
      alert(xhr.responseText); // munculkan alert
    }
  });
}
	
</script>

<div id="main" style="font-size: 10pt">
  <div id="snackbar"></div>
  <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
    <i class='fa fa-briefcase' style="font-size: 18px">&nbsp;Stok Opname &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px"><?=$kd_toko?></span><span class="w3-right" style="font-size: 16px"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span>
  </div>	
  <input type="hidden" id="keycari">
  <input type="hidden" id="keyadjust">
  <input type="hidden" id="keynote">
  <div id="viewcaribrgstok"><script>caribrgstok(1,true)</script></div>
  <div id="viewsavedata"></div>
</div>   

<div id="fnote" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.2) ">
  <div class="w3-modal-content w3-card-4 w3-animate-top" style="border-style: ridge;border-color: white;width:600px ">
    <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
      &nbsp; <i class="fa fa-desktop"></i>&nbsp;Catatan penyesuain stok barang
      <span onclick="document.getElementById('fnote').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
    </div>
    <div id="viewnote"><script>carimutasinote(1,true)</script></div>
  </div>
</div>        
<div id="fproses" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.2) ">
  <div class="w3-modal-content w3-card-4 w3-animate-top" style="border-style: ridge;border-color: white;width:600px ">
    <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
      &nbsp; <i class="fa fa-desktop"></i>&nbsp;Proses adjustment stok barang
      <span onclick="document.getElementById('fproses').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
    </div>
    <div class="container mt-2" id="ketbrg"></div>
    <div class="row container mt-3" style="font-size:9pt"> 
      <div class="col-sm-6">
          <div class="form-group row">
            <label for="nm_sat1 " class="col-sm-4  col-form-label"><b>Stok Awal</b></label>
            <div class="col-sm-8">
              <input class="form-control hrf_arial" id="stok_awal" type="text" name="stok_awal" autofocus style="border: 1px solid black;font-size: 10pt;" disabled>
            </div>
          </div>	
      </div>
      <div class="col-sm-6">
          <div class="form-group row">
            <label for="nm_sat1 " class="col-sm-4  col-form-label"><b>Penyesuaian</b></label>
            <div class="col-sm-8">
              <input class="form-control hrf_arial" id="stok_akhir" type="text" name="stok_akhir" autofocus style="border: 1px solid black;font-size: 10pt;" disabled>
            </div>
          </div>	
      </div>
      <div class="row container">
        <div class="col-sm">
          <b>Silahkan isi keterangan penyesuaian</b>
          <textarea name="ket_ad" id="ket_ad" cols="82" rows="5"></textarea>
        </div>
      </div>

      <div class="row container mt-3">
        <div class="col-sm-6 offset-sm-3">
          <div class="form-group row">
            <div class="col-sm">
              <button class="btn btn-md btn-primary form-control" 
                onclick="if (confirm('Yakin akan dilakukan penyesuaian ?')){
								savedata();caribrgstok(1,true);document.getElementById('ket_ad').value='';document.getElementById('fproses').style.display='none';}else{document.getElementById('keyadjust').value='';
								} "
                >Lanjutkan</button>
            </div>
            <div class="col-sm">
              <button class="btn btn-md btn-warning form-control" onclick="document.getElementById('fproses').style.display='none'">Batal</button>
            </div>
          <div>
        </div>
      </div>

    </div>        
    
    <!-- <div id="viewnote"><script>carimutasinote(1,true)</script></div> -->
  </div>
</div>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     