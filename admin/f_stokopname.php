<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stok Opname</title>
  <link rel="shortcut icon" href="img/keranjang.png">
  <?php 
    include 'starting.php';
    $kd_toko=$_SESSION['id_toko'];
    $tgl_set = isset($_SESSION['tgl_set']) ? $_SESSION['tgl_set'] : date('Y-m-d');
    $tgl_op_default_awal = date('Y-m-01', strtotime($tgl_set));
    $tgl_op_default_akhir = $tgl_set;
  ?>
  <script src="../assets/js/html5-qrcode.min.js"></script>
</head>
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

  .opname-progress-wrap {
    background: #f8f9fa;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px 12px;
    margin: 8px 0;
  }
  .opname-progress-bar {
    height: 22px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 8px;
  }
  .opname-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #28a745, #5cb85c);
    text-align: center;
    color: #fff;
    font-size: 9pt;
    font-weight: bold;
    line-height: 22px;
    min-width: 2%;
    transition: width 0.3s ease;
  }

</style>

<script>
  function caribrgstok(page_number, search){
    $(this).html("ketik pencarian").attr("disabled", "disabled");

    $.ajax({
      url: 'f_stokopname_cari.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {
        keyword: $("#keycari").val(),
        page: page_number,
        search: search,
        filter_stok: $("#filter_stok").val()
      },
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
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
        $("#viewsavedata").html(response.hasil);
        loadProgressOpname();
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }

  function setPeriodeBulanIni() {
    var akhir = document.getElementById('tgl_op_akhir');
    var d = akhir && akhir.value ? akhir.value : '<?= $tgl_op_default_akhir ?>';
    var parts = d.split('-');
    document.getElementById('tgl_op_awal').value = parts[0] + '-' + parts[1] + '-01';
    if (!akhir.value) {
      akhir.value = d;
    }
    loadProgressOpname();
  }

  function setPeriodeHariIni() {
    var t = '<?= $tgl_set ?>';
    document.getElementById('tgl_op_awal').value = t;
    document.getElementById('tgl_op_akhir').value = t;
    loadProgressOpname();
  }

  function loadProgressOpname() {
    var t1 = document.getElementById('tgl_op_awal').value;
    var t2 = document.getElementById('tgl_op_akhir').value;
    var panel = document.getElementById('panel-progress-opname');
    if (!t1 || !t2) {
      if (panel) {
        panel.innerHTML = '<span class="text-danger">Isi periode tanggal terlebih dahulu.</span>';
      }
      return;
    }
    if (t1 > t2) {
      if (typeof popnew_error === 'function') {
        popnew_error('Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
      } else {
        alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
      }
      return;
    }
    if (panel) {
      panel.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memuat progress...';
    }
    $.ajax({
      url: 'f_stokopname_progress.php',
      type: 'POST',
      data: {
        tgl_awal: t1,
        tgl_akhir: t2,
        filter_stok: document.getElementById('filter_stok') ? document.getElementById('filter_stok').value : 'semua'
      },
      dataType: 'json',
      beforeSend: function(e) {
        if (e && e.overrideMimeType) {
          e.overrideMimeType('application/json;charset=UTF-8');
        }
      },
      success: function(r) {
        if (!r || !r.ok) {
          var msg = (r && r.msg) ? r.msg : 'Gagal memuat progress opname.';
          if (panel) {
            panel.innerHTML = '<span class="text-danger">' + msg + '</span>';
          }
          return;
        }
        var pct = Math.min(100, Math.max(0, parseFloat(r.persen) || 0));
        var pctLabel = pct.toString().replace('.', ',') + '%';
        var html = '<div class="row" style="align-items:center;margin:0">';
        html += '<div class="col-sm-12 col-md-7" style="padding:0">';
        html += '<strong><i class="fa fa-pie-chart"></i> Progress Stok Opname</strong>';
        if (r.filter_label) {
          html += ' <span class="badge badge-secondary" style="font-size:8pt;vertical-align:middle">' + r.filter_label + '</span>';
        }
        html += '<br>';
        html += '<small>Periode ' + r.tgl_awal_txt + ' s/d ' + r.tgl_akhir_txt + '</small><br>';
        html += '<span style="font-size:10pt">';
        html += '<b>' + pctLabel + '</b> &mdash; ';
        html += r.sudah + ' dari ' + r.total + ' barang sudah disesuaikan';
        html += ' &nbsp;|&nbsp; Belum: <b>' + r.belum + '</b>';
        html += '</span>';
        html += '<div class="opname-progress-bar"><div class="opname-progress-fill" style="width:' + pct + '%">' + pctLabel + '</div></div>';
        html += '</div></div>';
        if (panel) {
          panel.innerHTML = html;
        }
      },
      error: function(xhr) {
        if (panel) {
          panel.innerHTML = '<span class="text-danger">Gagal memuat progress opname.</span>';
        }
        if (window.console) {
          console.error(xhr.responseText);
        }
      }
    });
  }

  function saveedket(){
    $.ajax({
      url: 'f_stokopname_ed_ket.php', 
      type: 'POST',
      data: {keyword1:$("#noedit").val(),keyword2:$("#ket_ed").val()}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
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
  // scan via camera android
  // var html5QrcodeScanner = new Html5QrcodeScanner('qr-reader', { fps: 10, qrbox: 250 }); 
  // var lastResult, countResults = 0;
  // var resultContainer = document.getElementById('qr-reader-results');
  function docReady(fn) {
    // see if DOM is already available
    if (document.readyState === "complete"
      || document.readyState === "interactive") {
      // call on next available tick
      setTimeout(fn, 1);
    } else {
      document.addEventListener("DOMContentLoaded", fn);
    }
  }
  docReady(function () {
    var resultContainer = document.getElementById('qr-reader-results');
    var lastResult, countResults = 0;
    function onScanSuccess(decodedText, decodedResult) {
      if (decodedText !== lastResult) {
          ++countResults;
          lastResult = decodedText;
          // Handle on success condition with the decoded message.
          // console.log(`Scan result ${decodedText}`, decodedResult);
          document.getElementById('in_barcode').value=decodedText;
          document.getElementById('keycari').value='AND beli_brg.kd_bar = ;'+decodedText;caribrgstok(1,true);
          // html5QrcodeScanner.clear();
          //lastResult=0;
            document.getElementById('form-scancams').style.display='none';
          //document.getElementById('qr-reader')=remove();
      }
    }
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
  });

  var a=0;
  if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
    a=1;
  }else{a=0;}
</script>
<body>
  <div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
  <div id="main" style="font-size: 10pt">
  <div id="form-scancams" class="w3-modal" style="margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-radius:5px;background: linear-gradient(565deg, #E6E6FA 0%, white 80%);box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;width: 500px">
      <div class="w3-center w3-padding-small yz-theme-d1 w3-wide">
        <center><i class="fa fa-server"></i>SCAN CAMERA</center>
      </div>
      <!-- <span onclick="" class="close  w3-display-topright" title="Close Modal" style="margin-top: 0px;margin-right: 0px;z-index: 1"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>   -->
      <div class="w3-center">
        <div id="qr-reader"></div>
        <div id="qr-reader-results"></div>
        <button class="btn btn-md btn-warning w3-center" type="button" onclick="document.getElementById('form-scancams').style.display='none'">Close</button>
      </div>  
    </div>
  </div>  
  <script>
    if (a==0){
      document.getElementById('qr-reader').remove();
    }
  </script>
    <div id="snackbar"></div>
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
      <i class='fa fa-briefcase' style="font-size: 18px">&nbsp;Stok Opname &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px"><?=$kd_toko?></span><span class="w3-right" style="font-size: 16px"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span>
    </div>	
    <input type="hidden" id="keycari">
    <input type="hidden" id="keyadjust">
    <input type="hidden" id="keynote">

    <div class="w3-container opname-progress-wrap">
      <div class="row" style="align-items:flex-end;margin:0">
        <div class="col-sm-12 col-md-8">
          <span style="font-size:10pt;font-weight:bold"><i class="fa fa-calendar"></i> Periode laporan progress</span>
          <div class="form-inline" style="margin-top:6px;flex-wrap:wrap">
            <label class="mr-1 mb-1" style="font-size:9pt">Dari</label>
            <input type="date" id="tgl_op_awal" class="form-control form-control-sm mr-2 mb-1" style="font-size:9pt" value="<?= htmlspecialchars($tgl_op_default_awal) ?>">
            <label class="mr-1 mb-1" style="font-size:9pt">s/d</label>
            <input type="date" id="tgl_op_akhir" class="form-control form-control-sm mr-2 mb-1" style="font-size:9pt" value="<?= htmlspecialchars($tgl_op_default_akhir) ?>">
            <button type="button" class="btn btn-primary btn-sm mb-1 mr-1" style="font-size:9pt" onclick="loadProgressOpname()"><i class="fa fa-refresh"></i> Tampilkan</button>
            <button type="button" class="btn btn-outline-secondary btn-sm mb-1 mr-1" style="font-size:9pt" onclick="setPeriodeBulanIni()">Bulan ini</button>
            <button type="button" class="btn btn-outline-secondary btn-sm mb-1" style="font-size:9pt" onclick="setPeriodeHariIni()">Hari ini</button>
          </div>
        </div>
      </div>
      <div id="panel-progress-opname" style="margin-top:8px;font-size:9pt">
        <i class="fa fa-spinner fa-spin"></i> Memuat progress...
      </div>
    </div>

    <div class="w3-container" style="padding:6px 12px;background:#fff;border:1px solid #ddd;margin-bottom:4px">
      <div class="form-inline" style="flex-wrap:wrap">
        <label for="filter_stok" class="mr-2 mb-1" style="font-size:9pt;font-weight:bold"><i class="fa fa-filter"></i> Filter stok:</label>
        <select id="filter_stok" class="form-control form-control-sm mb-1" style="font-size:9pt;min-width:180px" onchange="caribrgstok(1, true);loadProgressOpname();">
          <option value="semua">Semua barang</option>
          <option value="ada_stok">Hanya ada stok</option>
          <option value="stok_nol">Hanya stok 0</option>
        </select>
      </div>
    </div>

    <div id="viewcaribrgstok"><script>caribrgstok(1,true)</script></div>
    <div id="viewsavedata"></div>
    
  </div>   
  
  <div id="feditnote" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.2);z-index:1023 ">
    <div class="w3-modal-content w3-card-4 w3-animate-top" style="border-style: ridge;border-color: white;width:400px ">
      <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
        &nbsp; <i class="fa fa-desktop"></i>&nbsp;Edit Keterangan Stok Opname
        <span onclick="document.getElementById('feditnote').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
      </div>
        <div style="font-size: 9pt;" class="container">
          <input type="hidden" id="noedit" name="noedit">
          <div id="ket_tgl"></div>
          <div id="nm_ed"></div>
        </div>
        <textarea class="w3-margin-top" name="ketedit" id="ket_ed" style="width: 100%;font-size:9pt" rows="5"></textarea>
        <div class="row justify-content-around mt-2 mb-2">

          <div class="cols-sm-4">
            <button style="font-size:9pt" class="btn btn-md btn-outline-success" type="submit" 
              onclick="document.getElementById('feditnote').style.display='none';
                       saveedket();
              ">SIMPAN
            </button></div>

          <div class="cols-sm-4"><button style="font-size:9pt" class="btn btn-md btn-outline-secondary" type="reset" onclick="document.getElementById('feditnote').style.display='none'">BATAL</button></div>

        </div>    
    </div>
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
              <label for="stok_awal" class="col-sm-4  col-form-label"><b>Stok Awal</b></label>
              <div class="col-sm-8">
                <input class="form-control hrf_arial" id="stok_awal" type="text" name="stok_awal" autofocus style="border: 1px solid black;font-size: 10pt;" disabled>
              </div>
            </div>	
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
              <label for="stok_akhir" class="col-sm-4  col-form-label"><b>Penyesuaian</b></label>
              <div class="col-sm-8">
                <input class="form-control hrf_arial" id="stok_akhir" type="text" name="stok_akhir" autofocus style="border: 1px solid black;font-size: 10pt;" disabled>
              </div>
            </div>	
        </div>
        <div class="row container">
          <div class="col-sm">
            <b>Silahkan isi keterangan penyesuaian</b>
            <textarea name="ket_ad" id="ket_ad" rows="5" style="width:100%"></textarea>
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
  
</body>
</html>

<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
    loadProgressOpname();
  });
</script>     