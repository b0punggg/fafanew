<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/keranjang.png">
  <title>Pembelian Barang</title>
</head>
<style>
  /* @media (max-width:1366px) and (min-width:1024px){.spec-tab{display:block!important}} */
</style>
<body>

  <?php  
  include 'starting.php';
  $connect=opendtcek();
  ?>

  <div id="main" style="font-size: 10pt;">
  
  <script src="../assets/js/html5-qrcode.min.js"></script>
  <script>
      // Global error handler to suppress specific browser extension errors
      window.addEventListener('error', function(event) {
        if (event.message && (event.message.includes("MessagePort") || event.message.includes("postMessage") || event.message.includes("message channel") || event.message.includes("asynchronous response"))) {
          event.stopImmediatePropagation();
          event.preventDefault();
          console.warn("Suppressed browser extension error:", event.message);
        }
      }, true); // Use capture phase

      window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && event.reason.message && (event.reason.message.includes("MessagePort") || event.reason.message.includes("postMessage") || event.reason.message.includes("message channel") || event.reason.message.includes("asynchronous response"))) {
          event.stopImmediatePropagation();
          event.preventDefault();
          console.warn("Suppressed unhandled promise rejection from browser extension:", event.reason.message);
        }
      });

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
              document.getElementById('kd_bar').value=decodedText;
              // html5QrcodeScanner.clear();
              carikdbar();
              //lastResult=0;
              // document.getElementById('form-scancam').style.display='none';
              //document.getElementById('qr-reader')=remove();
          }
        }
        // Cek apakah elemen qr-reader ada sebelum membuat scanner
        var qrReaderElement = document.getElementById('qr-reader');
        if (qrReaderElement) {
          try {
            var html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", { fps: 10, qrbox: 250 });
            html5QrcodeScanner.render(onScanSuccess);
          } catch (error) {
            console.log("QR Scanner tidak dapat diinisialisasi:", error);
          }
        }
      });
        
    function hitdiscbayar(nvaluep,nvaluea,ppn,field1,field2){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      $.ajax({
        url: 'f_hitdiscbayar.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:nvaluep, bagi:nvaluea, ppn:ppn, field1: field1, field2: field2}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewbaydisc").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function hitppnbayar(nvaluep,nvaluea,nvalued,field1,field2){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      $.ajax({
        url: 'f_hitppnbayar.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:nvaluep, bagi:nvaluea, disc:nvalued ,field1: field1, field2: field2}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewbayppn").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }  
      
    function carinmbrg2(page_number, search){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'f_beli_cari_nmbrg2.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#nm_brg").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewnmbrg").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }
      
    function cariidbrg2(page_number, search){
      $.ajax({
        url: 'f_beli_cari_idbrg2.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#kd_brg").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewidbrg").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function carinmbag(page_number, search){
      $.ajax({
        url: 'f_beli_cari_nmbag.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#nm_bag").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
         $("#viewnmbag").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function carinota(page_number, search){
      $.ajax({
        url: 'f_belicari.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#no_fak").val()+';'+$("#tgl_fak").val()+';'+$("#caribrg").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewnota").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function carikdbar(page_number, search){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'f_carikdbar.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#kd_bar").val()}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewkdbar").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function carinofak2(page_number, search){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      $.ajax({
        url: 'f_belicari_nofak.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword: $("#cari_nofakbeli").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          $("#listnofak2").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function seekno_fak(){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      $.ajax({
        url: 'f_beli_start.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword: $("#no_fak").val()+";"+$("#tgl_fak").val()}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          $("#viewstart").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function dftsup(){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      $.ajax({
        url: 'f_belicari_sup.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#nm_sup").val()} , 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          $("#viewdftsup").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function dftsup2(){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      $.ajax({
        url: 'f_belicari_sup2.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword1:$("#kd_sup").val(),keyword2:$("#nm_sup").val()} , 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          $("#viewdftsup2").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }
      
    function cariceknota(page_number, search){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'f_beli_cariceknota.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewcariceknota").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }
    function hitmar(xdisc,xnums1,xnums2){
      var hrg_jual1,hrg_jualmar;
      xnums1=xnums1.replace(".","");
      xnums1=xnums1.replace(",",".");
      hrg_jual1=xnums1;
      if (xnums2 != "" || xnum2 !=0 ){
        hrg_jual1=(Number(xnums1-Number(xnums1)*xnums2/100)); 
      } else {
        hrg_jual1=Number(xnums1);
      } 
      
      if (hrg_jual1 != "" || hrg_jual1 !=0 ){ 
        hrg_jualmar=(hrg_jual1*xdisc/100)+hrg_jual1;
         return angkatitikdes(roundToTwo(hrg_jualmar));
      } else {return angkatitikdes(hrg_jual1);}
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
          <script>popnew_error("Tagihan Sudah Lunas");</script>
        <?php
      }else if($pesan=="ada"){
        ?>
          <script>popnew_error("Gagal update, karena Sudah ada mutasi");</script>
        <?php
      }
    } 
  ?>
  
   <!-- Scan camera -->
    <div id="form-scancam" class="w3-modal" style="margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-radius:5px;background: linear-gradient(565deg, #E6E6FA 0%, white 80%);box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;width: 500px">
        <div class="w3-center w3-padding-small yz-theme-d1 w3-wide">
          <center><i class="fa fa-server"></i>SCAN CAMERA</center>
        </div>
        <!-- <span onclick="" class="close  w3-display-topright" title="Close Modal" style="margin-top: 0px;margin-right: 0px;z-index: 1"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>   -->
        <div class="w3-center">
          <div id="qr-reader"></div>
          <div id="qr-reader-results"></div>
          <button class="btn btn-md btn-warning w3-center" type="button" onclick="document.getElementById('form-scancam').style.display='none'">Close</button>
        </div>  
      </div>
    </div>  
    <script>
      var a=0;
      if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        a=1;
      }else{a=0;}
      if (a==0){
        // Tunggu sampai DOM benar-benar siap sebelum menghapus
        setTimeout(function() {
          var qrReaderElement = document.getElementById('qr-reader');
          if (qrReaderElement) {
            qrReaderElement.remove();
          }
        }, 100);
      }
      
    </script>
    <!-- Form nota-->
    
      <div id="fnotabeli" class="w3-modal hrf_arial" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge; ">
        <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:700px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;background: linear-gradient(180deg, #FAFAD2 10%, white 90%)">

          <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;">&nbsp;<i class="fa fa-search"></i>
            Cari No Faktur Pembelian        
          </div>
          <div class="w3-center">
            <span onclick="document.getElementById('fnotabeli').style.display='none';html5QrcodeScanner.clear();lastResult=0;" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
          </div>
        
          <div class="modal-body">
              <div class="input-group">
                <input id="cari_nofakbeli" onkeyup="if(event.keyCode==13){document.getElementById('btn-cari_nofak2').click()}" style="box-shadow: 1px 1px 5px;" type="text" class="form-control" placeholder="ketik No. Nota">             
                <span class="input-group-btn">
                  <button class="btn btn-primary" onclick="carinofak2(1,true);" type="button" id="btn-cari_nofak2" style="box-shadow: 1px 1px 5px black;">SEARCH</button>
                  <button style="box-shadow: 1px 1px 5px;" onclick="
                  document.getElementById('cari_nofakbeli').value='';
                  carinofak2(1,true);
                  document.getElementById('cari_nofakbeli').focus()" 
                  type="button" class="btn btn-warning">RESET</button></span>
                <!-------------------------------------------->            
              </div>
            <br>
            <div id="listnofak2"><script>carinofak2(1,true);</script></div>
          </div> <!--Modal-body-->
        </div><!--Modal content-->
      </div>
      <!-- End Form Nota -->  

    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
        <i class='fa fa-cart-arrow-down' style="font-size: 18px">&nbsp;TRANSAKSI &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Pembelian barang</span><span class="w3-right" style="font-size: 16px"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span>
    </div>
    <!-- <hr class="w3-black" style="margin-top: 0px"> -->
    
    <div id="inputdata" style="border-style: ridge;border-color: white ">
      <form id="form-input" action="f_cekbelinota.php" method="post" style="padding-right: 10px;padding-left: 10px">
        <div class="w3-row w3-margin-top" >

          <!-- key pilihan untuk barcode sama -->
            <input type="hidden" id="lanjutsave" name="lanjutsave">
            <input type="hidden" id="keyedit" name="keyedit">
          <!--  -->

          <div class="w3-col s12 m12 l4 " >
            <div class="w3-container w3-card " style="border-style: ridge;border-color: white;">
              <p style="font-size: 16px;"><span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x text-primary"></i>
                <i class="fa fa-book fa-stack-1x"></i>
              </span><strong>Faktur Pembelian Barang</strong> 
              <a href="#" id="tmb-updown2" class=" w3-hover-shadow btn w3-right w3-hide-small w3-hide-medium" style="border-radius: 10%;margin-top:5px" title="geser atas/bawah"><i class="fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-up"></i></a>

              <a href="#" id="tmb-upfak" class=" w3-hover-shadow btn w3-right w3-hide-large" style="border-radius: 10%;margin-top:5px" title="geser atas/bawah"><i class="fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-up"></i></a>
              </p>
              <hr style="margin-top: -8px;background: linear-gradient(165deg, darkblue 5%, blue 40%, cyan 70%);height: 1px">
              <div id="boxfak">
                <div class="form-group row w3-margin-top hrf_arial" >
                  
                  <!-- Key Hidden -->
                  <input type="hidden" name="no_urutnota" id="no_urutnota">
                  <input type="hidden" name="jump_file" id="jump_file">
                  <a id="kembali" href="" style="display: none"></a>
                  <!--  -->

                  <label for="tgl_fak" class="hrf_arial col-sm-4 col-form-label"><b>Tgl.Faktur</b></label>
                  <div class="col-sm-8">
                    <input id="tgl_fak" style="border: 1px solid black;" type="date" class="form-control hrf_arial" name="tgl_fak" autofocus required tabindex="1" onblur="document.getElementById('caribrg').value='';carinota(1,true);">
                  </div>
                </div> 
                <div class=" form-group row hrf_arial" style="margin-top: -12px">            
                  <label for="no_fak" class="col-sm-4 col-form-label"><b>No. Faktur</b></label>
                  <div class="col-sm-8">
                    <div class="input-group" style="border-radius: 5px;"> 
                      <input id="no_fak" style="border: 1px solid black;" type="text" class="form-control hrf_arial" name="no_fak" onblur="document.getElementById('caribrg').value='';carinota(1,true);" autofocus required tabindex="1">
                      <span><button id="btn-faktur" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black" type="button" onclick="document.getElementById('fnotabeli').style.display='block';document.getElementById('cari_nofakbeli').focus()"><i class="fa fa-caret-down"></i></button></span>                    
                    </div>
                  </div>
                </div>
              
                <div class=" form-group row hrf_arial" style="margin-top: -12px" >
                  <label for="nm_sup" class="col-sm-4 col-form-label"><b>Supplier</b></label>
                  <div class="col-sm-8">
                    <input class="form-control" id="kd_sup" type="hidden" name="kd_sup" value='<?=$kd_sup?>'>
                    <div class="input-group ">
                      <input class="form-control hrf_arial" onkeyup="dftsup()" id="nm_sup" type="text" name="nm_sup"  style="border: 1px solid black;" placeholder="ketik nama supplier" required="" tabindex="3">
                      <span><button id="btn-nmsup" class="hrf_arial form-control yz-theme-l4 w3-hover-shadow " style="cursor: pointer;border:1px solid black" type="button" onclick="dftsup();"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    <!-- box search supplier -->
                    <div id="boxsup" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                      <div id="viewdftsup"></div>
                    </div> <!-- boxsub -->
                  
                    <script>
                      $(document).ready(function(){
                        $("#tmb-upfak").click(function(){
                          $("#boxfak").slideToggle("fast");
                        });
                        $("#btn-nmsup").click(function(){
                          $("#boxsup").slideToggle("fast");
                          $("#boxkat").slideUp("fast");
                          $("#nm_sup").focus();
                          $("#boxnmbrg").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxbrand").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                        });
                        $("#nm_sup").keyup(function(){
                          $("#boxnmbrg").slideUp("fast");
                          $("#boxsup").slideDown("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxbrand").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                        });
                        $("#boxsup").click(function(){
                          $("#boxsup").slideUp("fast");
                        });
                        $("#viewdftsup").mouseleave(function(){
                          $("#boxsup").slideUp("fast");
                        });

                      });
                    </script>
                    <!-- ---- -->
                  </div><!--  col-sm-8 -->         
                </div>  <!-- form-group row -->

                <div class=" form-group row hrf_arial" style="margin-top: -12px">            
                  <label for="ketbel" class="col-sm-4 col-form-label"><b>Keterangan</b></label>
                  <div class="col-sm-8">
                    <div class="input-group" style="border-radius: 5px;"> 
                      <input type="text" id="ketbel" name="ketbel" style="border: 1px solid black;" class="form-control hrf_arial" tabindex="3">    
                    </div>
                  </div>
                </div>  
              </div>      
            </div> <!-- div bingkai -->
          </div><!-- col-sm-4 -->

          <div class="w3-col s12 m12 l4 ">
            <div class="w3-container w3-card" style="border-style: ridge;border-color: white;">
              <p style="font-size: 16px;"><span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x text-primary"></i>
                <i class="fa fa-briefcase fa-stack-1x"></i>
                </span><strong>Data Barang</strong>
                <a href="#" id="tmb-upbrg" class=" w3-hover-shadow btn w3-right w3-hide-large" style="border-radius: 10%;margin-top:5px" title="geser atas/bawah"><i class="fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-up"></i></a>
              </p>   
              <hr style="margin-top: -8px;background: linear-gradient(165deg, darkblue 5%, blue 40%, cyan 70%);height: 1px">

              <div id="boxdtbrg" class="w3-row">
                <div class="w3-col l12" >
                  <div class="form-group row hrf_arial">
                    <label for="kd_bar" class="col-sm-3 col-form-label "><b>Barcode</b></label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <input id="kd_bar" onmouseover="this.focus()" onkeypress="if(event.keyCode==13){carikdbar();}"  type="text" style="border: 1px solid black; " class="form-control hrf_arial" name="kd_bar" tabindex="4" placeholder="ketik barcode scan barcode" aria-describedby="button-addon2">
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="button-addon2" onclick="
                        // html5QrcodeScanner.render(onScanSuccess);
                        document.getElementById('form-scancam').style.display='block'; 
                        ">scan</button>
                      </div>
                    </div>
                  </div>

                  <input class="form-control hrf_arial" onkeyup="cariidbrg2(1, true);" id="kd_brg" type="hidden" name="kd_brg" style="border: 1px solid black;" placeholder="ketik id. barang"  tabindex="5">

                  <div class="form-group row hrf_arial" style="margin-top: -11px;display:none">
                    <label for="kd_brg" class="col-sm-3 col-form-label" style="display:none"><b>ID. Barang</b></label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        
                        <span><button id="btn-idbrg" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black;display:none" type="button"><i class="fa fa-caret-down"></i></button></span>
                      </div>

                      <div id="boxidbrg" style="display:none;position:absolute;z-index: 1;width:100%; ">
                          <div id="viewidbrg" class="w3-card" style="background-color: white"><script>cariidbrg2(1,true)</script></div>
                      </div>
                    </div>  
                    <script>
                    
                      $(document).ready(function(){
                        $("#btn-idbrg").click(function(){
                          $("#kd_brg").focus();
                          $("#boxidbrg").slideToggle("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          $("#boxnmbag").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxnmbrg").slideUp("fast");
                        });
                        $("#kd_brg").keyup(function(){
                          $("#boxidbrg").slideDown("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          $("#boxbrand").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxnmbrg").slideUp("fast");
                        });

                        $("#boxidbrg").click(function(){
                          $("#boxsup").slideUp("fast");
                        });
                        $("#viewidbrg").mouseleave(function(){
                          $("#boxidbrg").slideUp("fast");
                        });
                      });
                    </script>            
                    <!--end idbrg -->
                  </div>  

                  <div class="form-group row" style="margin-top: -11px">
                    <label for="nm_brg" class="hrf_arial col-sm-3 col-form-label"><b>Nm.Barang</b></label>
                    <div class="col-sm-9" >
                      <div class="input-group">
                        <input id="nm_brg" type="text" style="border: 1px solid black; " class="form-control hrf_arial" onkeyup="carinmbrg2(1,true)" name="nm_brg" required="" tabindex="5" placeholder="ketik nama barang">
                        <span><button id="btn-nmbrg" class="hrf_arial form-control yz-theme-l4 w3-hover-shadow" style="cursor: pointer;border:1px solid black" type="button"><i class="fa fa-caret-down"></i></button></span>
                      </div>
                      
                      <div id="boxnmbrg"style="display:none;position:absolute;z-index: 1;width: 100%">
                        <div id="viewnmbrg" class="w3-card" style="background-color: white;">
                          <script>carinmbrg2(1,true)</script>
                        </div>
                      </div>
                    </div>  
                    
                    <script>
                      $(document).ready(function(){
                        $("#btn-nmbrg").click(function(){
                          $("#nm_brg").focus();
                          $("#boxnmbrg").slideToggle("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          $("#boxnmbag").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                        });
                        $("#nm_brg").keyup(function(){
                          $("#boxnmbrg").slideDown("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          $("#boxbrand").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                        });
                        $("#boxnmbrg").click(function(){
                          $("#boxsup").slideUp("fast");
                        });
                        $("#viewnmbrg").mouseleave(function(){
                          $("#boxnmbrg").slideUp("fast");
                        });
                      });
                    </script>            
                  </div>
                  
                  <div class="form-group row" style="margin-top: -12px">
                    <input type="hidden" id="id_bag" name="id_bag">
                    <label for="nm_bag" class="col-sm-3 col-form-label hrf_arial"><b>Bag. Jual</b></label>
                    <div class="col-sm-9" >
                      <div class="input-group">
                        <input id="nm_bag" type="text" style="border: 1px solid black; " class="form-control hrf_arial" onkeyup="carinmbag(1,true)" name="nm_bag" required="" tabindex="6" placeholder="ketik bagian penjualan">
                        <span><button id="btn-nmbag" class="hrf_arial form-control yz-theme-l4 w3-hover-shadow" style="cursor: pointer;border:1px solid black" type="button"><i class="fa fa-caret-down"></i></button></span>
                      </div>
                      
                      <div id="boxnmbag"style="display:none;position:absolute;z-index: 1;width: 100%">
                        <div id="viewnmbag" class="w3-card" style="background-color: white;">
                          <script>carinmbag(1,true)</script>
                        </div>
                      </div>
                    </div>  
                    
                    <script>
                      $(document).ready(function(){
                        $("#tmb-upbrg").click(function(){
                          $("#boxdtbrg").slideToggle("fast");
                        });
                        $("#btn-nmbag").click(function(){
                          $("#nm_bag").focus();
                          $("#boxnmbag").slideToggle("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          $("#boxbrg").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                        });
                        $("#nm_bag").keyup(function(){
                          $("#boxnmbag").slideDown("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          $("#boxbrand").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                        });
                        $("#boxnmbag").click(function(){
                          $("#boxsup").slideUp("fast");
                        });
                        $("#viewnmbag").mouseleave(function(){
                          $("#boxnmbag").slideUp("fast");
                        });
                      });
                    </script>            
                  </div>

                  <div class="form-group row" style="margin-top: -11px">
                    <label for="expdate" class="col-sm-3 col-form-label hrf_arial"><b>EXP.Date</b></label>
                    <div class="col-sm-9" style="margin-bottom: 0px">
                      <div class="input-group">
                        <input id="expdate" type="date" style="border: 1px solid black; " class="form-control hrf_arial" name="expdate" tabindex="7" placeholder="Exp date">
                      </div>
                    </div>           
                  </div>
                </div> <!-- class="w3-col" -->
              </div><!-- class="w3-row" -->
            </div> <!-- bingkai -->  
          </div><!-- class="w3-col s12 m12 l4 -->   

          <!-- ini Qty -->
          <div class="w3-col m12 l4 ">    
            <div class="w3-container w3-card" style="border-style: ridge;border-color: white ">
              <p style="font-size: 16px;">
                <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x text-primary"></i>
                <i class="fa fa-archive fa-stack-1x  "></i>
                </span><strong>QTY & Harga Beli</strong>
                <a href="#" id="tmb-upqty" class=" w3-hover-shadow btn w3-right w3-hide-large" style="border-radius: 10%;margin-top:5px" title="geser atas/bawah"><i class="fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-up"></i></a>
              </p>    
              <hr style="margin-top: -8px;background: linear-gradient(165deg, darkblue 5%, blue 40%, cyan 70%);height: 1px">

              <div id="boxqtyhrg">        
                <div class="form-group row">
                  <label for="jml_brg" class="hrf_arial col-sm-4 col-form-label"><b>Jumlah Barang</b></label>
                  <div class="col-sm-8">
                    <input id="jml_brg" type="number" step="1.00" min="1" style="border: 1px solid black; " class="form-control hrf_arial" name="jml_brg" required="" tabindex="9" placeholder="ketik jumlah barang">
                  </div>    
                </div>      

                <div class="form-group row" style="margin-top: -12px">
                  <label for="nm_sat" class="hrf_arial col-sm-4 col-form-label"><b>Satuan Barang</b></label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id="nm_sat" onkeyup="carkem()" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat" required="" tabindex="10" placeholder="ketik jenis kemasan">
                      <span><button id="btn-nmsat" class="hrf_arial form-control yz-theme-l4 w3-hover-shadow" style="cursor: pointer;border:1px solid black" type="button"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat" id="kd_sat">
                    <!-- box search kemasan -->
                      <div id="boxkem" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                        <div id="tabkem" class="table-responsive w3-white w3-card" 
                          style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 250px;">
                          <table class="hrf_res arrow-nav1 table-bordered table-striped table-hover" style="width:100%;">
                            <tr align="middle" class="yz-theme-l3" style="position: sticky;top:0px">
                              <th>SATUAN</th>
                            </tr>

                            <?php 
                            $sql1 = mysqli_query($connect, "SELECT * from kemas ORDER BY nm_sat2 ASC ");
                            $iii=0;
                            while ($datakem = mysqli_fetch_array($sql1)){
                              $iii++;
                            ?>
                            <tr>
                              <td align="left" class="button" style="cursor:pointer;">
                                <input class="w3-input" type="text" readonly="" value="<?=$datakem['nm_sat2']; ?>" 
                                style="border: none;background-color: transparent;cursor: pointer" tabindex="10" 
                                onkeydown="if(event.keyCode==13){this.click()}" 
                                onclick="document.getElementById('nm_sat').value='<?=mysqli_escape_string($connect,$datakem['nm_sat2']) ?>';document.getElementById('kd_sat').value='<?=mysqli_escape_string($connect,$datakem['no_urut']) ?>';"></td>
                            </tr>  
                            <?php   
                            }
                            unset($datakem);
                            ?>
                          </table>
                        </div>  <!-- tabsub -->
                      </div> <!-- boxsub -->
                    
                      <script>
                        $(document).ready(function(){
                          $("#btn-nmsat").click(function(){
                            $("#nm_sat").focus();
                            $("#boxkem").slideToggle("fast");
                            $("#boxkat").slideUp("fast");
                            $("#boxnmbrg").slideUp("fast");
                            $("#boxidbrg").slideUp("fast");
                            $("#boxsup").slideUp("fast");
                            $("#boxbrand").slideUp("fast");
                          });
                          $("#nm_sat").keyup(function(){
                            $("#boxkem").slideDown("fast");
                            $("#boxkat").slideUp("fast");
                            $("#boxnmbrg").slideUp("fast");
                            $("#boxidbrg").slideUp("fast");
                            $("#boxsup").slideUp("fast");
                            $("#boxbrand").slideUp("fast");
                          });
                          $("#boxkem").click(function(){
                            $("#boxkem").slideUp("fast");
                          });
                          $("#tabkem").mouseleave(function(){
                            $("#boxkem").slideUp("fast");
                          });
                        });

                        function carkem() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_sat");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabkem");
                          tr = table.getElementsByTagName("tr");
                          for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("input")[0];
                            if (td) {
                              txtValue = td.textContent || td.value;
                              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                              } else {
                                tr[i].style.display = "none";
                              }
                            }       
                          }
                        }
                      </script>
                  </div>
                </div>  

                <div class="form-group row" style="margin-top: -12px">
                  <label for="hrg_beli" class="col-sm-4 col-form-label hrf_arial"><b>Harga beli</b></label>
                  <div class="col-sm-8">
                    <input id="hrg_beli" type="text" style="border: 1px solid black; " class="form-control money hrf_arial" name="hrg_beli" required="" tabindex="11" placeholder="ketik harga beli barang" >
                  </div>
                  <label for="discitem1" class="hrf_arial col-sm-4 col-form-label" style="margin-top: 2px"><b>Disc peritem</b></label>
                  <div class="col-sm-3">
                    <input id="discitem1" type="number" step="0.25" min="0.00" onfocus="document.getElementById('discitem2').value='0'" style="border: 1px solid black; font-size: 10pt;margin-top: 3px" class="hrf_arial form-control" value="0.00" name="discitem1"  tabindex="11" placeholder="persen">
                  </div>
                  <div class="col-sm-5" style="margin-bottom: 3px">
                    <input id="discitem2" type="text" onfocus="document.getElementById('discitem1').value='0.00';" style="border: 1px solid black; font-size: 10pt;margin-top: 3px" class="form-control money hrf_arial" name="discitem2" value="0" tabindex="11" placeholder="Rupiah">
                  </div>
                </div>  
              </div>  
            </div>  <!-- bingkai -->
          </div> <!-- w3-col m12 l4-->      
        </div><!-- row -->

        <div class="w3-row w3-margin-top">
          <!--Konversi harga jual  -->
          <div class="w3-col l6 ">
            <div class="w3-container w3-card" style="border-style: ridge;border-color: white ">
              <p style="font-size: 16px;"><span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x text-primary"></i>
                <i class="fa fa-wrench fa-stack-1x"></i>
                </span><strong>Konversi Jumlah & Harga Jual</strong>
                <a href="#" id="tmb-kov" class=" w3-hover-shadow btn w3-right w3-hide-large" style="border-radius: 10%;margin-top:5px" title="geser atas/bawah"><i class="fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-up"></i></a>
              </p>    
              <hr style="margin-top: -8px;background: linear-gradient(165deg, darkblue 5%, blue 40%, cyan 70%);height: 1px">  
              <div id="boxkov">
                <div class="form-group row">
                  <label for="nm_sat1" class="col-sm-1 col-form-label hrf_arial"><b>Sat.1</b></label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <input id="nm_sat1" onkeyup="carisat(this.value,'nm_sat1','kd_sat1','jum_sat1','mar1','hrg_jum1','viewsat1','12')" type="text" style="border: 1px solid black; " class="form-control hrf_arial" name="nm_sat1" required="" tabindex="12" placeholder="ketik satuan 1">
                      <span><button id="btn-kem1" class="hrf_arial form-control yz-theme-l4 w3-hover-shadow" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat1').value,'nm_sat1','kd_sat1','jum_sat1','mar1','hrg_jum1','viewsat1','12')"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat1" id="kd_sat1">
                    <!-- box search kemasan -->
                      <div id="boxkem1" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                        <div id="tabkem1" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 200px">
                          <div id="viewsat1" class="hrf_res"></div>
                        </div>  <!-- tabsub -->
                      </div> <!-- boxsub -->
                    
                      <script>
                        $(document).ready(function(){
                          $("#btn-kem1").click(function(){
                            $("#boxkem1").slideToggle("fast");
                            $("#nm_sat1").focus();
                            $("#boxnmbrg").slideUp("fast");
                            $("#boxkem").slideUp("fast");
                            $("#boxkem4").slideUp("fast");
                            $("#boxidbrg").slideUp("fast");
                            $("#boxsup").slideUp("fast");
                            $("#boxkem5").slideUp("fast");
                            $("#boxkem2").slideUp("fast");
                            $("#boxkem3").slideUp("fast");
                          });
                          $("#nm_sat1").keyup(function(){
                            $("#boxkem1").slideDown("fast");
                            $("#boxkem2").slideUp("fast");
                            $("#boxkem3").slideUp("fast");
                            $("#boxnmbrg").slideUp("fast");
                            $("#boxkem").slideUp("fast");
                            $("#boxkem4").slideUp("fast");
                            $("#boxidbrg").slideUp("fast");
                            $("#boxsup").slideUp("fast");
                            $("#boxkem5").slideUp("fast");
                          });
                          $("#boxkem1").click(function(){
                            $("#boxkem1").slideUp("fast");
                          });
                          $("#tabkem1").mouseleave(function(){
                            $("#boxkem1").slideUp("fast");
                          });
                        });

                      </script>
                      <!-- ---- -->
                  </div>

                  <!-- <label for="nm_sat2" class="col-sm-2 col-form-label"><b>Satuan II</b></label> -->
                  <div class="col-sm-2">
                    <input id="jum_sat1" onkeyup="" type="number" step="0.01" min="0" style="border: 1px solid black;" class="form-control hrf_arial" name="jum_sat1" required="" tabindex="13" placeholder="Jml brg">
                  </div>
                  <div class="col-sm-2">
                    <input id="mar1" onkeyup="document.getElementById('hrg_jum1').value=hitmar(this.value,document.getElementById('hrg_beli').value,document.getElementById('discitem1').value);" type="number" step="1.00" style="border: 1px solid black;" class="form-control hrf_arial w3-hide-small" tabindex="14" placeholder="Mar">
                  </div>
                  
                  <div class="col-sm-3">
                    <input id="hrg_jum1" onkeyup="document.getElementById('mar1').value=0;" type="text" style="border: 1px solid black;" class="form-control hrf_arial money" name="hrg_jum1" required="" tabindex="15" placeholder="Harga jual [Rp.]">
                  </div>

                </div>
                
                <div class="form-group row" style="margin-top: -12px">
                  <label for="nm_sat2" class="col-sm-1 col-form-label hrf_arial"><b>Sat.2</b></label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <input id="nm_sat2" onkeyup="carisat(this.value,'nm_sat2','kd_sat2','jum_sat2','mar2','hrg_jum2','viewsat2','16')" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat2" required="" tabindex="16" placeholder="ketik satuan 2">
                      <span><button id="btn-kem2" class="hrf_arial form-control yz-theme-l4 w3-hover-shadow" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat2').value,'nm_sat2','kd_sat2','jum_sat2','mar2','hrg_jum2','viewsat2','16')"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat2" id="kd_sat2">
                    <!-- box search kemasan -->
                      <div id="boxkem2" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                        <div id="tabkem2" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 170px">
                          <div id='viewsat2'></div>
                        </div>  <!-- tabsub -->
                      </div> <!-- boxsub -->
                    
                      <script>
                        $(document).ready(function(){
                          $("#btn-kem2").click(function(){
                            $("#boxkem2").slideToggle("fast");
                            $("#nm_sat2").focus();
                            $("#boxkem1").slideUp("fast");
                            $("#boxkem3").slideUp("fast");
                            $("#boxnmbrg").slideUp("fast");
                            $("#boxkem").slideUp("fast");
                            $("#boxkem4").slideUp("fast");
                            $("#boxidbrg").slideUp("fast");
                            $("#boxsup").slideUp("fast");
                            $("#boxkem5").slideUp("fast");
                          });
                          $("#nm_sat2").keyup(function(){
                            $("#boxkem2").slideDown("fast");
                            $("#boxkem1").slideUp("fast");
                            $("#boxkem3").slideUp("fast");
                            $("#boxkem").slideUp("fast");
                            $("#boxkem4").slideUp("fast");
                            $("#boxidbrg").slideUp("fast");
                            $("#boxsup").slideUp("fast");
                            $("#boxkem5").slideUp("fast");
                          });
                          $("#boxkem2").click(function(){
                            $("#boxkem2").slideUp("fast");
                          });
                          $("#tabkem2").mouseleave(function(){
                            $("#boxkem2").slideUp("fast");
                          });
                        });

                      </script>
                      <!-- ---- -->
                  </div>

                  <!-- <label for="nm_sat2" class="col-sm-2 col-form-label"><b>Satuan II</b></label> -->
                  <div class="col-sm-2">
                    <input id="jum_sat2" onkeyup="" type="number" step="1.00" style="border: 1px solid black;" class="form-control hrf_arial`" name="jum_sat2" required="" tabindex="17" placeholder="Jml brg">
                  </div>
                  
                  <div class="col-sm-2">
                    <input id="mar2" onkeyup="document.getElementById('hrg_jum2').value=hitmar(this.value,document.getElementById('hrg_beli').value,document.getElementById('discitem1').value);" type="number" step="1.00" style="border: 1px solid black; " class="form-control hrf_arial w3-hide-small" tabindex="18" placeholder="Mar">
                  </div>

                  <!-- <label for="hrg_jum2" class="col-sm-2 col-form-label"><b class="w3-right">Harga Jual II</b></label> -->
                  <div class="col-sm-3">
                    <input id="hrg_jum2" onkeyup="document.getElementById('mar2').value=0" type="text" style="border: 1px solid black;" class="form-control hrf_arial money" name="hrg_jum2" required="" tabindex="19" placeholder="Harga jual [Rp.]">
                  </div>

                </div>

                <div class="form-group row" style="margin-top: -12px">
                  <label for="nm_sat3" class="col-sm-1 col-form-label hrf_arial"><b>Sat.3</b></label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <input id="nm_sat3" onkeyup="carisat(this.value,'nm_sat3','kd_sat3','jum_sat3','mar3','hrg_jum3','viewsat3','20')" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat3" required="" tabindex="20" placeholder="ketik satuan 3">
                      <span><button id="btn-kem3" class="hrf_arial form-control yz-theme-l4 w3-hover-shadow" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat3').value,'nm_sat3','kd_sat3','jum_sat3','mar3','hrg_jum3','viewsat3','20')"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat3" id="kd_sat3">
                    <!-- box search kemasan -->
                      <div id="boxkem3" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                        <div id="tabkem3" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 140px">
                          <div id="viewsat3"></div>
                        </div>  <!-- tabsub -->
                      </div> <!-- boxsub -->
                    
                      <script>
                        $(document).ready(function(){
                          $("#tmb-kov").click(function(){
                            $("#boxkov").slideToggle("fast");
                          });
                          $("#btn-kem3").click(function(){
                            $("#boxkem3").slideToggle("fast");
                            $("#nm_sat3").focus();
                            $("#boxkem2").slideUp("fast");
                            $("#boxkem1").slideUp("fast");
                            $("#boxkem").slideUp("fast");
                            $("#boxkem4").slideUp("fast");
                            $("#boxkem5").slideUp("fast");
                            $("#boxidbrg").slideUp("fast");
                            $("#boxsup").slideUp("fast");
                            
                          });
                          $("#nm_sat3").keyup(function(){
                            $("#boxkem3").slideDown("fast");
                            $("#boxkem2").slideUp("fast");
                            $("#boxkem1").slideUp("fast");
                            $("#boxkem").slideUp("fast");
                            $("#boxkem4").slideUp("fast");
                            $("#boxkem5").slideUp("fast");
                            $("#boxidbrg").slideUp("fast");
                            $("#boxsup").slideUp("fast");
                            
                          });
                          $("#boxkem3").click(function(){
                            $("#boxkem3").slideUp("fast");
                          });
                          $("#tabkem3").mouseleave(function(){
                            $("#boxkem3").slideUp("fast");
                          });
                        });

                      </script>
                      <!-- ---- -->
                  </div>
                  
                  <!-- <label for="nm_sat2" class="col-sm-2 col-form-label"><b>Satuan II</b></label> -->
                  <div class="col-sm-2">
                    <input id="jum_sat3" onkeyup="" type="number" step="1.00" style="border: 1px solid black;" class="hrf_arial form-control" name="jum_sat3" required="" tabindex="21" placeholder="Jml brg">
                  </div>

                  <div class="col-sm-2">
                    <input id="mar3" onkeyup="document.getElementById('hrg_jum3').value=hitmar(this.value,document.getElementById('hrg_beli').value,document.getElementById('discitem1').value);" type="number" step="1.00" style="border: 1px solid black;" class="hrf_arial form-control w3-hide-small" tabindex="22" placeholder="Mar">
                  </div>

                  <!-- <label for="hrg_jum3" class="col-sm-2 col-form-label"><b class="w3-right">Harga Jual III</b></label> -->
                  <div class="col-sm-3">
                    <input id="hrg_jum3" onkeyup="document.getElementById('mar3').value=0;" type="text" style="border: 1px solid black;" class="hrf_arial form-control money" name="hrg_jum3" required="" tabindex="23" placeholder="Harga jual [Rp.]">
                    
                  </div>
                </div>
              </div>            
            </div> <!-- bingkai --> 
          </div><!-- col-sm-8 -->
          <!-- end konversi harga -->

          <!-- Discount tetap qty barang  -->
          <div class="w3-col l6 w3-hide-small w3-hide-medium">
            <div class="w3-container w3-card" style="border-style: ridge;border-color: white ">
              <p style="font-size: 16px;"><span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x text-primary"></i>
                <i class="fa fa-wrench fa-stack-1x"></i>
              </span><strong>Discount Berdasar Qty Penjualan </strong></p>    
              <hr style="margin-top: -8px;background: linear-gradient(165deg, darkblue 5%, blue 40%, cyan 70%);height: 1px">  

              <!-- 1 -->
              <div class="form-group row">
                <label for="discttp1" class="col-sm-1 col-form-label"><b>Lebih</b></label>
                <div class="col-sm-2">
                  <input id="discttp1" style="border: 1px solid black;font-size:13px;" type="number" class="form-control" value="0" name="discttp1" step="1.00" tabindex="24">
                </div>  

                <div class="col-sm-4">
                  <div class="input-group">
                    <input id="nm_sat4" onkeyup="carisat(this.value,'nm_sat4','kd_sat4','discttp1','discttp1%','hrg_jum4','viewsat4','25')" type="text" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial" name="nm_sat4" required="" tabindex="25" placeholder="ketik satuan" VALUE="-NONE-">
                    <span><button id="btn-kem4" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat4').value,'nm_sat4','kd_sat4','discttp1','discttp1%','hrg_jum4','viewsat4','25')"><i class="fa fa-caret-down"></i></button></span>
                  </div>
                  
                  <input type="hidden" name="kd_sat4" id="kd_sat4" value="1">
                  <!-- box search kemasan -->
                    <div id="boxkem4" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                      <div id="tabkem4" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 200px">
                        <div id="viewsat4"></div>
                      </div>  <!-- tabsub -->
                    </div> <!-- boxsub -->
                    <script>
                      $(document).ready(function(){
                        $("#tmb-upqty").click(function(){
                          $("#boxqtyhrg").slideToggle("fast");
                        });
                        $("#btn-kem4").click(function(){
                          $("#boxkem4").slideToggle("fast");
                          $("#nm_sat4").focus();
                          $("#boxkem5").slideUp("fast");
                          $("#boxkem6").slideUp("fast");
                          $("#boxkem3").slideUp("fast");
                          $("#boxkem2").slideUp("fast");
                          $("#boxkem1").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          
                        });
                        $("#nm_sat4").keyup(function(){
                          $("#boxkem4").slideDown("fast");
                          $("#boxkem5").slideUp("fast");
                          $("#boxkem6").slideUp("fast");
                          $("#boxkem3").slideUp("fast");
                          $("#boxkem2").slideUp("fast");
                          $("#boxkem1").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          
                        });
                        $("#boxkem4").click(function(){
                          $("#boxkem4").slideUp("fast");
                        });
                        $("#tabkem4").mouseleave(function(){
                          $("#boxkem4").slideUp("fast");
                        });
                      });

                      
                    </script>
                    <!-- ---- -->
                </div>
                <div class="col-sm-2" >
                  <input id="discttp1pct" onkeyup="hit1(this.value,document.getElementById('hrg_jum1').value);" type="number" step="1.00" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial " name="discttp1%"  tabindex="26" placeholder="disc" value="0" >
                </div>
                <div class="col-sm-3" >
                  <input id="hrg_jum4" onkeyup="document.getElementById('discttp1pct').value=0;" type="text" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial money" name="hrg_jum4" required="" tabindex="27" placeholder="Harga jual [Rp.]" value="0">
                  
                </div>
              </div> 

              <!-- 2 -->
              <div class="form-group row" style="margin-top: -12px">

                <label for="discttp2" class="col-sm-1 col-form-label"><b>Lebih</b></label>
                <div class="col-sm-2">
                  <input id="discttp2" style="border: 1px solid black;font-size:13px;" type="number" class="form-control" value="0" name="discttp2" tabindex="28">
                </div>  
                
                <div class="col-sm-4">
                  <div class="input-group">
                    <input id="nm_sat5" onkeyup="carisat(this.value,'nm_sat5','kd_sat5','discttp2','discttp2%','hrg_jum5','viewsat5','29')" type="text" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial" name="nm_sat5" required="" tabindex="29" value="-NONE-" placeholder="ketik satuan">
                    <span><button id="btn-kem5" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat5').value,'nm_sat5','kd_sat5','discttp2','discttp2%','hrg_jum5','viewsat5','29')"><i class="fa fa-caret-down"></i></button></span>
                  </div>
                  
                  <input type="hidden" name="kd_sat5" id="kd_sat5" value="1">
                  <!-- box search kemasan -->
                    <div id="boxkem5" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                      <div id="tabkem5" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 170px">
                        <div id='viewsat5'></div>
                      </div>  <!-- tabsub -->
                    </div> <!-- boxsub -->
                  
                    <script>
                      $(document).ready(function(){
                        $("#btn-kem5").click(function(){
                          $("#boxkem5").slideToggle("fast");
                          $("#nm_sat5").focus();
                          $("#boxkem6").slideUp("fast");
                          $("#boxkem4").slideUp("fast");
                          $("#boxkem3").slideUp("fast");
                          $("#boxkem2").slideUp("fast");
                          $("#boxkem1").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                        });
                        $("#nm_sat5").keyup(function(){
                          $("#boxkem5").slideDown("fast");
                          $("#boxkem6").slideUp("fast");
                          $("#boxkem4").slideUp("fast");
                          $("#boxkem3").slideUp("fast");
                          $("#boxkem2").slideUp("fast");
                          $("#boxkem1").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                        });
                        $("#boxkem5").click(function(){
                          $("#boxkem5").slideUp("fast");
                        });
                        $("#tabkem5").mouseleave(function(){
                          $("#boxkem5").slideUp("fast");
                        });
                      });

                      
                    </script>
                    <!-- ---- -->
                </div>

                <div class="col-sm-2" >
                  <input id="discttp2pct" onkeyup="hit2(this.value);" type="number" step="1.00" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial" name="discttp2%"  tabindex="30" placeholder="disc" value="0" >
                </div>
                
                <div class="col-sm-3" >
                  <input id="hrg_jum5" onkeyup="document.getElementById('discttp2pct').value=0;" type="text" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial money" name="hrg_jum5" required="" tabindex="31" placeholder="Harga jual [Rp.]" value="0">
                  
                </div>
              </div> 

              <!-- 3 -->
              <div class="form-group row" style="margin-top: -12px">

                <label for="discttp3" class="col-sm-1 col-form-label"><b>Lebih</b></label>
                <div class="col-sm-2">
                  <input id="discttp3" style="border: 1px solid black;font-size:13px;" type="number" class="form-control" value="0" name="discttp3" tabindex="32">
                </div>  
                     
                <div class="col-sm-4">
                  <div class="input-group">
                    <input id="nm_sat6" onkeyup="carisat(this.value,'nm_sat6','kd_sat6','discttp3','discttp3%','hrg_jum6','viewsat6','33')" type="text" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial" name="nm_sat6" required="" value="-NONE-" tabindex="33" placeholder="ketik satuan">
                    <span><button id="btn-kem6" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat6').value,'nm_sat6','kd_sat6','discttp3','discttp3%','hrg_jum6','viewsat6','33')"><i class="fa fa-caret-down"></i></button></span>
                  </div>
                  
                  <input type="hidden" name="kd_sat6" id="kd_sat6" value="1">
                  <!-- box search kemasan -->
                    <div id="boxkem6" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                      <div id="tabkem6" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 143px">

                        <div id="viewsat6"></div>
                      </div>  <!-- tabsub -->
                    </div> <!-- boxsub -->
                  
                    <script>
                      $(document).ready(function(){
                        $("#btn-kem6").click(function(){
                          $("#boxkem6").slideToggle("fast");
                          $("#nm_sat6").focus();
                          $("#boxkem5").slideUp("fast");
                          $("#boxkem4").slideUp("fast");
                          $("#boxkem3").slideUp("fast");
                          $("#boxkem2").slideUp("fast");
                          $("#boxkem1").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                        });
                        $("#nm_sat6").keyup(function(){
                          $("#boxkem6").slideDown("fast");
                          $("#boxkem5").slideUp("fast");
                          $("#boxkem4").slideUp("fast");
                          $("#boxkem3").slideUp("fast");
                          $("#boxkem2").slideUp("fast");
                          $("#boxkem1").slideUp("fast");
                          $("#boxkem").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                        });
                        $("#boxkem6").click(function(){
                          $("#boxkem6").slideUp("fast");
                        });
                        $("#tabkem6").mouseleave(function(){
                          $("#boxkem6").slideUp("fast");
                        });
                      });

                  
                    </script>
                    <!-- ---- -->
                </div>
                <div class="col-sm-2">
                  <input id="discttp3pct" onkeyup="hit3(this.value);" type="number" step="0.01" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial" name="discttp3%"  tabindex="34" placeholder="disc" value="0" >
                </div>
                <div class="col-sm-3" >
                  <input id="hrg_jum6" onkeyup="document.getElementById('discttp3pct').value=0;" type="text" style="border: 1px solid black; font-size: 10pt;" class="form-control hrf_arial money" name="hrg_jum6" required="" tabindex="35" placeholder="Harga jual [Rp.]" value="0">
                  
                </div>
              </div> 

            </div>      
          </div>  
          <!-- End Discount tetap qty barang-->
        </div><!-- row -->
        

        <!--Tombol reset/simpan  -->
        <div class="row w3-margin-top">
          <div class="col-sm-6">
              <button id="btn-save" type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l1"><i class="fa fa-save">&nbsp;&nbsp;</i><b class="w3-wide">TAMBAH KE NOTA</b></button>
          </div>  
          <div class="col-sm-3" style="padding-bottom: 2px">
              <button onclick="document.getElementById('keyedit').value='';document.getElementById('no_fak').focus();kosfaktur();carinota(1,true);" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l1"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
          </div>
          <div class="col-sm-3" style="padding-bottom: 2px">
              <button onclick="document.getElementById('form-ceknota').style.display='block';cariceknota(1,true);" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 btn-warning"><i class="fa fa-server">&nbsp;&nbsp;</i><b>CEK NOTA</b></button>
          </div>
        </div>  
        <!-- End tombol --> 
      </form> 
      <script type="text/javascript">
      $(document).ready(function() {
        $('#form-input').submit(function() {
          $.ajax({
              type: 'POST',
              url: $(this).attr('action'),
              data: $(this).serialize(),
              success: function(data) {
                  $('#viewcek').html(data);
              }
          })
          return false;
        });
      })
      </script> 
    </div>
    
    <div id="viewcek"></div>    
    <div id="viewstart"></div>
    <div id="viewkdbar"></div>
    

    <!-- Bagian nota pembelian -->
    <div id="inputnota" style="border-style: ridge;border-color: white ">    
      <div class="w3-container yz-theme-d1" style="padding: 2px 0px 2px 10px "> 
        <div class="w3-row">
          <div class="w3-col l9 m7 s12">
            <h7 href="#" id="btn-geser" class="fa fa-television"  style="color:yellow;font-size:11pt ; "></h7>    
            <a href="#" id="tmb-updown" class=" w3-hover-shadow btn yz-theme-d1" style="margin-left: 30px;border-radius: 10%" title="geser atas/bawah"><i class="fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-up"></i></a>

            <a href="#" id="btn-cetak" onclick="dftsup2();document.getElementById('form-bayar').style.display='block';document.getElementById('disctot').focus()" class=" w3-hover-shadow w3-text-white btn yz-theme-d1" style="border-radius: 50%;margin-left: 20px;" title="bayar nota "><i class="fa fa-money "></i></i></a>

            <a id="link" target="_blank"  id="btn-cetak" class="w3-hover-shadow w3-text-white btn yz-theme-d1" style="border-radius: 50%;margin-left: 20px" title="cetak nota "><i class="fa fa-print "></i></i></a>

          </div>
          <div class="w3-col l3 m5 s12">
            <div class="input-group">
              <input id="caribrg" type="text" onkeypress="if(event.keyCode==13){document.getElementById('btn-caribrg').click();}" style="border: 1px solid black; font-size: 10pt;" class="hrf_arial form-control" placeholder="cari nama barang">
              <span><button id="btn-caribrg" onclick="carinota(1,true);" class="form-control btn-primary w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" type="button"><i class="fa fa-search"></i></button></span>
              <span><button id="btn-caribrg" onclick="document.getElementById('caribrg').value='';carinota(1,true);"  class="form-control btn-warning w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" type="button"><i class="fa fa-undo"></i></button></span>
            </div>    
          </div>
        </div>      
      </div>
      <div id="viewnota"><script>carinota(1,true);</script> </div>
    </div>  

    <!-- cek form nota bayar -->
    <div id="form-ceknota" class="w3-modal" style="margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-radius:5px;background: linear-gradient(565deg, #E6E6FA 0%, white 80%);box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;width: 60%">
        <div class="w3-center w3-padding-small yz-theme-d1 w3-wide">
          <center><i class="fa fa-server"></i>CEK NOTA BELUM DIBAYAR</center>
        </div>
          <span onclick="document.getElementById('form-ceknota').style.display='none'" class="close  w3-display-topright" title="Close Modal" style="margin-top: -20px;margin-right: -17px;z-index: 1"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>      
        <!-- <div class="modal-body">  -->
          <div id="viewcariceknota"></div>      
        <!-- </div>   -->
      </div>
    </div>    

  </div>

  <?php 
    if(isset($_GET['pesanedit'])){
      $pesanedit=mysqli_real_escape_string($connect,$_GET['pesanedit']);
      $x=explode(';', $pesanedit);
        $no_urutnota=$x[0];
        $no_fakedit=$x[1];
        $tgl_fakedit=$x[2];
        ?> 
        <script>
          document.getElementById('no_urutnota').value='<?=$no_urutnota?>';
          document.getElementById('tgl_fak').value='<?=$tgl_fakedit?>';
          document.getElementById('no_fak').value='<?=$no_fakedit?>';
          document.getElementById('tgl_fak').focus();
          document.getElementById('no_fak').focus();
          document.getElementById('jml_brg').focus();
        </script>
        <?php
        $cek=mysqli_query($connect,"SELECT beli_brg.tgl_fak,beli_brg.no_urut,beli_brg.kd_brg,beli_brg.jml_brg,beli_brg.kd_bar,beli_brg.kd_toko,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,beli_brg.kd_sat,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.expdate,kemas.nm_sat1,kemas.nm_sat2,supplier.nm_sup,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_brg,beli_brg.id_bag,bag_brg.nm_bag
                FROM beli_brg 
                LEFT JOIN kemas ON beli_brg.kd_sat=kemas.no_urut 
                LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup 
                LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
                LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut
          WHERE beli_brg.no_urut='$no_urutnota'");
        $data=mysqli_fetch_assoc($cek);
        $kd_kem1=ceknmkem(mysqli_escape_string($connect,$data['kd_kem1']),$connect);
        $kd_kem2=ceknmkem(mysqli_escape_string($connect,$data['kd_kem2']),$connect);
        $kd_kem3=ceknmkem(mysqli_escape_string($connect,$data['kd_kem3']),$connect);
        $kd_brg=mysqli_escape_string($connect,$data['kd_brg']);
          $limit1=cekdisc($kd_brg,mysqli_escape_string($connect,$data['kd_kem1']),$connect);
              //echo '$lim1'.$lim1;
            
          if(empty($limit1)){
            $kd_sat4="1";$hrg_jum4=0;$lim1=0;$nm_kem4="-NONE-"; 
            $percen1=0;
          }else{
            $x1=explode(';', $limit1);
            $kd_sat4=$x1[0];
            $hrg_jum4=$x1[1];
            $lim1=$x1[2];
            $nm_kem4=ceknmkem($kd_sat4,$connect);
            if ($hrg_jum4 <=0){
              $persen1=0;
            }else{
              $percen1=str_replace('.',',',round(($data['hrg_jum1']-$hrg_jum4)/$hrg_jum4*100,2));
            }  
            
          }  
          //
          $limit1=cekdisc($kd_brg,$data['kd_kem2'],$connect);
          if(empty($limit1)){
            $kd_sat5="1";$hrg_jum5=0;$lim2=0;$nm_kem5="-NONE-"; 
            $percen2=0;
          }else{
            $x1=explode(';', $limit1);
            $kd_sat5=$x1[0];
            $hrg_jum5=$x1[1];
            $lim2=$x1[2];
            $nm_kem5=ceknmkem($kd_sat5,$connect);
            if ($hrg_jum5 <=0){
              $persen2=0;
            }else{
              $percen2=str_replace('.',',',round(($data['hrg_jum2']-$hrg_jum5)/$hrg_jum5*100,2));
            }  
          }  
          $limit1=cekdisc($kd_brg,$data['kd_kem3'],$connect);
          if(empty($limit1)){
            $kd_sat6="1";$hrg_jum6=0;$lim3=0;$nm_kem6="-NONE-"; 
            $percen3=0;
          }else{
            $x1=explode(';', $limit1);
            $kd_sat6=$x1[0];
            $hrg_jum6=$x1[1];
            $lim3=$x1[2];
            $nm_kem6=ceknmkem($kd_sat6,$connect);
            if ($hrg_jum6 <=0){
              $persen3=0;
            }else{
              $percen3=str_replace('.',',',round(($data['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2));
            }   
          }
          // 
        ?>
        <script>document.getElementById('jump_file').value='<?=mysqli_escape_string($connect,$data['kd_brg'])?>';
            document.getElementById('kd_brg').value='<?=mysqli_escape_string($connect,$data['kd_brg']) ?>';
            document.getElementById('nm_brg').value='<?=mysqli_escape_string($connect,$data['nm_brg']) ?>';
            document.getElementById('id_bag').value='<?=mysqli_escape_string($connect,$data['id_bag']) ?>';
            document.getElementById('nm_bag').value='<?=mysqli_escape_string($connect,$data['nm_bag']) ?>';
            document.getElementById('expdate').value='<?=mysqli_escape_string($connect,$data['expdate']) ?>';
            document.getElementById('jml_brg').value='<?=mysqli_escape_string($connect,$data['jml_brg']) ?>';
            document.getElementById('kd_bar').value='<?=mysqli_escape_string($connect,$data['kd_bar']) ?>';
            document.getElementById('kd_sup').value='<?=mysqli_escape_string($connect,$data['kd_sup']) ?>';
            document.getElementById('nm_sup').value='<?=mysqli_escape_string($connect,$data['nm_sup'])?>';
            document.getElementById('kd_sat').value='<?=mysqli_escape_string($connect,$data['kd_sat']) ?>';
            document.getElementById('nm_sat').value='<?=mysqli_escape_string($connect,$data['nm_sat2']) ?>';
            document.getElementById('hrg_beli').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_beli'])) ?>';
            document.getElementById('kd_sat1').value='<?=mysqli_escape_string($connect,$data['kd_kem1']) ?>';document.getElementById('jum_sat1').value='<?=mysqli_escape_string($connect,$data['jum_kem1']) ?>';document.getElementById('hrg_jum1').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum1'])) ?>';document.getElementById('nm_sat1').value='<?=$kd_kem1?>';
            document.getElementById('kd_sat2').value='<?=mysqli_escape_string($connect,$data['kd_kem2']) ?>';document.getElementById('jum_sat2').value='<?=mysqli_escape_string($connect,$data['jum_kem2']) ?>';document.getElementById('hrg_jum2').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum2'])) ?>';document.getElementById('nm_sat2').value='<?=$kd_kem2?>';
            document.getElementById('kd_sat3').value='<?=mysqli_escape_string($connect,$data['kd_kem3']) ?>';document.getElementById('jum_sat3').value='<?=mysqli_escape_string($connect,$data['jum_kem3']) ?>';document.getElementById('hrg_jum3').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum3'])) ?>';document.getElementById('nm_sat3').value='<?=$kd_kem3?>';
            document.getElementById('discitem1').value='<?=mysqli_escape_string($connect,$data['disc1']) ?>';document.getElementById('discitem2').value='<?=mysqli_escape_string($connect,$data['disc2']) ?>'
            document.getElementById('discttp1').value='<?=$lim1 ?>';document.getElementById('nm_sat4').value='<?=$nm_kem4?>';document.getElementById('kd_sat4').value='<?=$kd_sat4 ?>';document.getElementById('hrg_jum4').value='<?=gantitides($hrg_jum4) ?>';
              document.getElementById('discttp1pct').value='<?=$percen1 ?>';
            document.getElementById('discttp2').value='<?=$lim2 ?>';document.getElementById('nm_sat5').value='<?=$nm_kem5?>';document.getElementById('kd_sat5').value='<?=$kd_sat5 ?>';document.getElementById('hrg_jum5').value='<?=gantitides($hrg_jum5) ?>';
              document.getElementById('discttp2pct').value='<?=$percen2 ?>';
            document.getElementById('discttp3').value='<?=$lim3 ?>';document.getElementById('nm_sat6').value='<?=$nm_kem6?>';document.getElementById('kd_sat6').value='<?=$kd_sat6 ?>';document.getElementById('hrg_jum6').value='<?=gantitides($hrg_jum6) ?>';
              document.getElementById('discttp3pct').value='<?=$percen3?>';              
        </script>
        <?php
    }else{
    ?><script>seekno_fak();</script><?php
    }
  ?>

  <script>
    function kosfaktur()
    {
      document.getElementById('tgl_fak').value='<?=date('Y-m-d')?>';
      document.getElementById('no_urutnota').value='';
      document.getElementById('expdate').value='';
      document.getElementById('lanjutsave').value='';
      document.getElementById('no_fak').value='';
      document.getElementById('kd_brg').value='';
      document.getElementById('nm_brg').value='';
      document.getElementById('jml_brg').value='';
      document.getElementById('kd_bar').value='';
      document.getElementById('kd_sup').value='';
      document.getElementById('nm_sup').value='';
      document.getElementById('kd_sat').value='';
      document.getElementById('nm_sat').value='';
      document.getElementById('hrg_beli').value='';
      document.getElementById('kd_sat1').value='';
      document.getElementById('jum_sat1').value='';
      document.getElementById('hrg_jum1').value='';
      document.getElementById('nm_sat1').value='';
      document.getElementById('mar1').value='';
      document.getElementById('kd_sat2').value='';
      document.getElementById('jum_sat2').value='';
      document.getElementById('hrg_jum2').value='';
      document.getElementById('nm_sat2').value='';
      document.getElementById('mar2').value='';
      document.getElementById('kd_sat3').value='';
      document.getElementById('jum_sat3').value='';
      document.getElementById('hrg_jum3').value='';
      document.getElementById('nm_sat3').value='';
      document.getElementById('mar3').value='';

      document.getElementById('discitem1').value='';
      document.getElementById('discitem2').value='';

      document.getElementById('discttp1pct').value='0';
      document.getElementById('nm_sat4').value='-NONE-';
      document.getElementById('kd_sat4').value='1';
      document.getElementById('discttp1').value='0';
      document.getElementById('hrg_jum4').value='0';
      
      document.getElementById('discttp2').value='0';
      document.getElementById('nm_sat5').value='-NONE-';
      document.getElementById('kd_sat5').value='1';
      document.getElementById('discttp2pct').value='0';
      document.getElementById('hrg_jum5').value='0';
      
      document.getElementById('discttp3').value='0';
      document.getElementById('nm_sat6').value='-NONE-';
      document.getElementById('kd_sat6').value='1';
      document.getElementById('discttp3pct').value='0';
      document.getElementById('hrg_jum6').value='0';
     
      document.getElementById('ketbel').value='';
      document.getElementById('id_bag').value='';
      document.getElementById('nm_bag').value='';
      seekno_fak();
    }
    function kosfaktur2()
    {
      document.getElementById('tgl_fak').value='<?=date('Y-m-d')?>';
      document.getElementById('no_urutnota').value='';
      document.getElementById('expdate').value='';
      document.getElementById('lanjutsave').value='';
      document.getElementById('no_fak').value='';
      document.getElementById('kd_brg').value='';
      document.getElementById('nm_brg').value='';
      document.getElementById('jml_brg').value='';
      // document.getElementById('kd_bar').value='';
      document.getElementById('kd_sup').value='';
      document.getElementById('nm_sup').value='';
      document.getElementById('kd_sat').value='';
      document.getElementById('nm_sat').value='';
      document.getElementById('hrg_beli').value='';
      document.getElementById('kd_sat1').value='';
      document.getElementById('jum_sat1').value='';
      document.getElementById('hrg_jum1').value='';
      document.getElementById('nm_sat1').value='';
      document.getElementById('mar1').value='';
      document.getElementById('kd_sat2').value='';
      document.getElementById('jum_sat2').value='';
      document.getElementById('hrg_jum2').value='';
      document.getElementById('nm_sat2').value='';
      document.getElementById('mar2').value='';
      document.getElementById('kd_sat3').value='';
      document.getElementById('jum_sat3').value='';
      document.getElementById('hrg_jum3').value='';
      document.getElementById('nm_sat3').value='';
      document.getElementById('mar3').value='';

      document.getElementById('discitem1').value='';
      document.getElementById('discitem2').value='';

      document.getElementById('discttp1pct').value='0';
      document.getElementById('nm_sat4').value='-NONE-';
      document.getElementById('kd_sat4').value='1';
      document.getElementById('discttp1').value='0';
      document.getElementById('hrg_jum4').value='0';
      
      document.getElementById('discttp2').value='0';
      document.getElementById('nm_sat5').value='-NONE-';
      document.getElementById('kd_sat5').value='1';
      document.getElementById('discttp2pct').value='0';
      document.getElementById('hrg_jum5').value='0';
      
      document.getElementById('discttp3').value='0';
      document.getElementById('nm_sat6').value='-NONE-';
      document.getElementById('kd_sat6').value='1';
      document.getElementById('discttp3pct').value='0';
      document.getElementById('hrg_jum6').value='0';
     
      document.getElementById('ketbel').value='';
      document.getElementById('id_bag').value='';
      document.getElementById('nm_bag').value='';
      seekno_fak();
     
    }
    function angkades(b,dec)
    {
      var _minus = false;
      if (b<0) _minus = true;
        b = b.toString();
        b=b.replace(",","");
        b=b.replace("-","");
        c = "";
        panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--){
          j = j + 1;
          if (((j % dec) == 1) && (j != 1)){
            c = b.substr(i-1,1) + "," + c;
          } else {
            c = b.substr(i-1,1) + c;
          }
        }
        if (b=="") c = "0";
      if (_minus) c = "0";
        return c;
    }
    
    $('table.arrow-nav1').keydown(function(e){
      var $table = $(this);
      var $active = $('input:focus,select:focus',$table);
      var $next = null;
      var focusableQuery = 'input:visible,select:visible,textarea:visible';
      var position = parseInt( $active.closest('td').index()) + 1;
      console.log('position :',position);
      switch(e.keyCode){
          case 37: // <Left>
              $next = $active.parent('td').prev().find(focusableQuery);   
              break;
          case 38: // <Up>                    
              $next = $active
                  .closest('tr')
                  .prev()                
                  .find('td:nth-child(' + position + ')')
                  .find(focusableQuery)
              ;
              
              break;
          case 39: // <Right>
              $next = $active.closest('td').next().find(focusableQuery);            
              break;
          case 40: // <Down>
              $next = $active
                  .closest('tr')
                  .next()                
                  .find('td:nth-child(' + position + ')')
                  .find(focusableQuery)
              ;
              break;
      }       
      if($next && $next.length)
      {        
          $next.focus();
      }
    });


    $(document).ready(function(){
      $("#tmb-updown").click(function(){
        $("#inputdata").slideToggle("");
      });
      $("#tmb-updown2").click(function(){
        $("#inputdata").slideToggle("");
      });
      $("#btn-geser").click(function(){
        $("#inputdata").slideDown("");
        $('html, body').animate({
          scrollTop: $('#main').offset().top-50,
          //event.preventDefault();
          },1000);
            return false;
      });
    }); 

    $(document).ready(function(){
      $(".loader1").fadeOut();
    })

    function isNumberKey(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57) && !(charCode == 46 || charCode == 8))
        return false;
      else {
        var len = $(element).val().length;
        var index = $(element).val().indexOf('.');
        if (index > 0 && charCode == 46) {
          return false;
        }
        if (index > 0) {
          var CharAfterdot = (len + 1) - index;
          if (CharAfterdot > 3) {
            return false;
          }
        }

      }
      return true;
    } 

    function formatRupiah(angka, prefix) {
      var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
      }

      rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
      return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
    }
  </script>     
</body>  
</html>