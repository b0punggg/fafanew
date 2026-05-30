<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/keranjang.png">
  <title>Mater Barang</title>
</head>
<body>
  
  <div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
  <?php 
   include 'starting.php';
   $kd_toko=$_SESSION['id_toko'];
   $connect=opendtcek();
  ?>

  <div id="main" >
  	<script>	
   	function kosongkan1(){
        document.getElementById('saycode').innerHTML='';
        document.getElementById('kd_brg').value='';
        document.getElementById('lanjutsavemas').value='';
        document.getElementById('kd_bar').focus();  
   	}  
    
    function carihrgbeli(page_number, search){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'f_masbrgcari_hrgbeli.php', // File tujuan
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
          
          $("#viewhrgbeli").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

    function carilistdata(page_number, search){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'f_masbrgcari_listdata.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword: $("#ktcarilist").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#listdata").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }

   	function cariidbrg(page_number, search){
  	  // $(this).html("ketik pencarian").attr("disabled", "disabled");
  	  
  	  $.ajax({
  	    url: 'f_masbrgcari_idbrg.php', // File tujuan
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

      function listbrg(page_number, search){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'f_masbrgcari.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#carinmbrg").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          $("#viewdatabrg").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
      }

      function caribarkode(page_number, search){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'f_masbrgcrkdbar.php', // File tujuan
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
      
      function hapus(kd_brg,kdtoko){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_masbrghapus_act.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:kd_brg, kdtoko:kdtoko}, 
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

      function hapushrgbeli(param){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_masbrghapusbeli_act.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:param}, 
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
            <script>popnew_error("Ops.. gagal untuk transaksi");</script>
          <?php
        }
      } 
    ?>
    
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, darkblue 0%, cyan 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
        <i class='fa fa-cart-arrow-down' style="font-size: 18px;color:whitesmoke">&nbsp;MASTER BARANG &nbsp;</i> <i class='fa fa-angle-double-right w3-text-white'></i>&nbsp;<span style="font-size: 18px;color:white">Barang</span>
    </div>
    <!-- <hr class="w3-black" style="margin-top: 0px"> -->
    <div id="inputdata" style="border-style: ridge;border-color: white ">
      <form id="form-inputan" action="f_masbrg_cek.php" method="post" style="padding-right: 10px;padding-left: 10px">
        <!-- key for redundancy barcode -->
        <input type="hidden" id="lanjutsavemas" name="lanjutsavemas">
        <div class="w3-row">
          <!--Data Barang -->
          <div class="w3-col m12 l12 ">
            <div class="w3-container" style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;border-style: ridge;border-color: white ">
            <a href="#" class="w3-text-white"><i class="fa fa-cubes" style="color:orange"></i>&nbsp; Data Barang</a>
          </div>
          <div class="w3-container w3-card" style="border-style: ridge;border-color: white ">
            <div class="w3-row">
              <div class="w3-col s12 m12 l4">
                <div class="form-group row w3-margin-top">
                  <input type="hidden" id="no_urutbrg" name="no_urutbrg">    
                  <label for="kd_bar" class="col-sm-3 col-form-label hrf_arial"><b>Barcode</b></label>
                  <div class="col-sm-8" >
                    <input id="kd_bar" onmouseover="this.focus()" onkeypress="if(event.keyCode==13){caribarkode();}" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="kd_bar" tabindex="1" placeholder="ketik barcode scan barcode">
                  </div>  
                </div>                

                <div class="form-group row w3-hide-small w3-hide-medium" style="margin-top: -12px">
                  <!--say bacode  -->
                  <label for="nm_brg" class="col-sm-3 col-form-label hrf_arial"><b>Gbr.Barcode</b></label>
                  <div class="col-sm-8 hrf_barcode" >
                    <div id="saycode"onclick="document.getElementById('formcetkdbar').style.display='block';" style="font-size: 22pt;margin-top: -5px;cursor: pointer;"></div>
                  </div>
                </div>  
              </div>

              <div class="w3-col s12 m12 l5">
                <div class="form-group row w3-margin-top">
                  <label for="kd_brg" class="col-sm-3 col-form-label hrf_arial"><b>Kd. Barang</b></label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input class="form-control hrf_arial" onkeyup="cariidbrg(1, true);" onkeypress="return event.keyCode != 13;" id="kd_brg" type="text" name="kd_brg" style="border: 1px solid black;" placeholder="ketik kode barang" required="" tabindex="2"> 
                      <span><button id="btn-kdbrg" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black" onclick="cariidbrg(1,true)" type="button"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    <div id="boxidbrg" class="w3-col" style="display:none;position:absolute;z-index: 1; ">
                        <div id="viewidbrg" class="w3-card-4" style="background-color: white;"><script>cariidbrg(1,true)</script></div>
                    </div>
                  </div>  
                  <script>
                    $(document).ready(function(){
                      $("#btn-kdbrg").click(function(){
                        $("#boxidbrg").slideToggle("fast");
                        $("#kd_brg").focus();
                        $("#boxsup").slideUp("fast");
                        $("#boxkem").slideUp("fast");
                      });
                      $("#kd_brg").keyup(function(){
                        //$("#boxidbrg").slideDown("slow,swing");
                        $("#boxsup").slideUp("fast");
                        $("#boxkem").slideUp("fast");
                      });
                      $("kd_brg").keydown(function (e) {
                          if (e.keyCode == 13) {
                              e.preventDefault();
                              return false;
                          }
                      });
                      $("#boxidbrg").click(function(){
                        $("#boxsup").slideUp("slow");
                      });
                      $("#viewidbrg").mouseleave(function(){
                        $("#boxidbrg").slideUp("fast");
                      });
                    });
                  </script>                  
                </div> 

                <div class="form-group row" style="margin-top: -12px">
                  <label for="nm_brg" class="col-sm-3 col-form-label hrf_arial"><b>Nama.Barang</b></label>
                  <div class="col-sm-8" >
                    <input id="nm_brg" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_brg" required="" tabindex="6" placeholder="ketik nama barang">
                  </div> 
                </div>  
              </div> 

              <div class="w3-col s12 m12 l3 w3-margin-top">
                <button type='button' onclick="document.getElementById('formlist').style.display='block';document.getElementById('ktcarilist').focus();carilistdata(1,true);" style="border-radius: 4px;cursor: pointer;width: 300px" class="btn btn-primary w3-margin-bottom hrf_arial"><i class="fa fa-save">&nbsp;&nbsp;</i>Tampilkan semua data</button>    
              </div>
            </div> 
          </div>

          <!--KONVERSI BARANG  -->
          <div class="w3-col s12 m12 l6">
            <div class="" style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;border-style: ridge;border-color: white">
              <a href="#" class="w3-text-white w3-padding-small"><i class="fa fa-gears" style="color:orange"></i>&nbsp; Konversi Isi Kemasan & Harga Jual Barang</a>
            </div>
            <div class="w3-container w3-card" style="border-style: ridge;border-color: white ">
              <!-- Konversi harga satuan -->
              <div class="form-group row w3-margin-top">

                <!--  -->
                  <label for="nm_sat1" class="col-sm-2 col-form-label hrf_arial"><b>Kemasan 1</b></label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <input id="nm_sat1" onkeyup="carisat(this.value,'nm_sat1','kd_sat1','jum_sat1','mar1','hrg_jum1','viewsat1','12');" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat1" required="" tabindex="12" placeholder="ketik satuan 1">

                      <span><button id="btn-kem1" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat1').value,'nm_sat1','kd_sat1','jum_sat1','mar1','hrg_jum1','viewsat1','12');"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat1" id="kd_sat1">
                    <!-- box search kemasan -->
                      <div id="boxkem1" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                        <div id="tabkem1" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 200px">
                          <div id="viewsat1"></div>
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

                        function carkem1() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_sat1");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabkem1");
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
                      <!-- ---- -->
                  </div>

                  <!-- <label for="nm_sat2" class="col-sm-2 col-form-label"><b>Satuan II</b></label> -->
                  <div class="col-sm-2">
                    <input id="jum_sat1" onkeyup="" type="number" style="border: 1px solid black;" class="form-control hrf_arial" step="1.00" name="jum_sat1" required="" tabindex="13" placeholder="Jml brg">
                  </div>
                  <input type="hidden" id="hrg_def1">
                  <input type="hidden" id="hrg_def2">
                  <input type="hidden" id="hrg_def3">
                  <div class="col-sm-1">        
                    <input id="mar1" onkeyup="document.getElementById('hrg_jum1').value=hitmar(this.value,document.getElementById('hrg_def1').value,'0');" type="number" step="0.01" style="border: 1px solid black; font-size: 9pt;width: 55px;display:none" class="form-control " tabindex="14" placeholder="Mar" onchange="this.onkeyup();">
                  </div>
                  <div class="col-sm-3">
                    <input id="hrg_jum1" onkeyup="document.getElementById('mar1').value=0;" type="text" style="border: 1px solid black;text-align: right" class="form-control hrf_arial money" name="hrg_jum1" required="" tabindex="15" placeholder="Harga jual [Rp.]">
                  </div>

                </div>
                
                <div class="form-group row" style="margin-top: -12px">
                  <label for="nm_sat2" class="col-sm-2 col-form-label hrf_arial"><b>Kemasan 2</b></label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <input id="nm_sat2" onkeyup="carisat(this.value,'nm_sat2','kd_sat2','jum_sat2','mar2','hrg_jum2','viewsat2','16');" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat2" required="" tabindex="16" placeholder="ketik satuan 2">

                      <span><button id="btn-kem2" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat2').value,'nm_sat2','kd_sat2','jum_sat2','mar2','hrg_jum2','viewsat2','16');"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat2" id="kd_sat2">
                    <!-- box search kemasan -->
                      <div id="boxkem2" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                        <div id="tabkem2" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 170px">
                          <div id="viewsat2"></div>
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
                    <input id="jum_sat2" onkeyup="" type="number" step="1.00" style="border: 1px solid black;" class="form-control hrf_arial" name="jum_sat2" required="" tabindex="17" placeholder="Jml brg">
                  </div>
                  
                  <div class="col-sm-1">
                    <input id="mar2" onkeyup="document.getElementById('hrg_jum2').value=hitmar(this.value,document.getElementById('hrg_def2').value,'0');" type="number" step="0.01" style="border: 1px solid black; font-size: 9pt;width: 55px;display:none" class="form-control " tabindex="18" placeholder="Mar" onchange="this.onkeyup();">
                  </div>

                  <div class="col-sm-3">
                    <input id="hrg_jum2" onkeyup="document.getElementById('mar2').value=0" type="text" style="border: 1px solid black;text-align: right" class="form-control hrf_arial money" name="hrg_jum2" required="" tabindex="19" placeholder="Harga jual [Rp.]">
                  </div>

                </div>

                <div class="form-group row" style="margin-top: -12px">
                  <label for="nm_sat3" class="col-sm-2 col-form-label hrf_arial"><b>Kemasan 3</b></label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <input id="nm_sat3" onkeyup="carisat(this.value,'nm_sat3','kd_sat3','jum_sat3','mar3','hrg_jum3','viewsat3','20');" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat3" required="" tabindex="20" placeholder="ketik satuan 3">

                      <span><button id="btn-kem3" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat3').value,'nm_sat3','kd_sat3','jum_sat3','mar3','hrg_jum3','viewsat3','20');"><i class="fa fa-caret-down"></i></button></span>
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
                    <input id="jum_sat3" onkeyup="" type="number" step="1.00" style="border: 1px solid black;" class="form-control hrf_arial" name="jum_sat3" required="" tabindex="21" placeholder="Jml brg">
                  </div>

                  <div class="col-sm-1">
                    <input id="mar3" onkeyup="document.getElementById('hrg_jum3').value=hitmar(this.value,document.getElementById('hrg_def3').value,'0');" type="number" step="0.01" style="border: 1px solid black; font-size: 9pt;width: 55px;display:none" class="form-control" tabindex="22" placeholder="Mar" onchange="this.onkeyup()">
                  </div>

                  <!-- <label for="hrg_jum3" class="col-sm-2 col-form-label"><b class="w3-right">Harga Jual III</b></label> -->
                  <div class="col-sm-3">
                    <input id="hrg_jum3" onkeyup="document.getElementById('mar3').value=0;" type="text" style="border: 1px solid black;text-align: right" class="form-control money hrf_arial" name="hrg_jum3" required="" tabindex="23" placeholder="Harga jual [Rp.]">
                  </div>
                <!--  -->
              </div>
            </div>
          </div>  

          <div class="w3-col s12 m12 l6 w3-hide-small">
            <div class="" style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;border-style: ridge;border-color: white">
                <a href="#" class="w3-text-white w3-padding-small"><i class="fa fa-gears" style="color:orange"></i>&nbsp; Discount Berdasar Qty Penjualan</a>
            </div>
            <div class="w3-container w3-card" style="border-style: ridge;border-color: white ">
              <!-- Konversi harga satuan -->
              <div class="form-group row w3-margin-top">

                  <label for="discttp1" class="col-sm-1 col-form-label hrf_arial"><b>Lebih</b></label>
                  <div class="col-sm-2">
                    <input id="discttp1" style="border: 1px solid black;font-size:13px;" type="number" class="form-control hrf_arial" value="0" name="discttp1" tabindex="24">
                  </div>  

                  <div class="col-sm-4">
                    <div class="input-group">
                      <input id="nm_sat4" onkeyup="carisat(this.value,'nm_sat4','kd_sat4','discttp1','discttp1%','hrg_jum4','viewsat4','25')" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat4" required="" tabindex="25" placeholder="ketik satuan">

                      <span><button id="btn-kem4" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat4').value,'nm_sat4','kd_sat4','discttp1','discttp1%','hrg_jum4','viewsat4','25')"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat4" id="kd_sat4">
                    <!-- box search kemasan -->
                      <div id="boxkem4" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                        <div id="tabkem4" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 200px">
                          <div id="viewsat4"></div>
                        </div>  <!-- tabsub -->
                      </div> <!-- boxsub -->
                     
                      <script>
                        $(document).ready(function(){
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
                    <input id="discttp1%" onkeyup="hit1(this.value);" type="number" step="0.01" style="border: 1px solid black;" class="form-control" name="discttp1%"  tabindex="26" placeholder="disc" >
                  </div>
                  <div class="col-sm-3" >
                    <input id="hrg_jum4" onkeyup="document.getElementById('discttp1%').value=0;" type="text" style="border: 1px solid black;" class="form-control hrf_arial money" name="hrg_jum4" required="" tabindex="27" placeholder="Harga jual [Rp.]">
                    
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
                      <input id="nm_sat5" onkeyup="carisat(this.value,'nm_sat5','kd_sat5','discttp2','discttp2%','hrg_jum5','viewsat5','29');" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat5" required="" tabindex="29" placeholder="ketik satuan">

                      <span><button id="btn-kem5" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat5').value,'nm_sat5','kd_sat5','discttp2','discttp2%','hrg_jum5','viewsat5','29')"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat5" id="kd_sat5">
                    <!-- box search kemasan -->
                      <div id="boxkem5" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                        <div id="tabkem5" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 170px">
                          <div id="viewsat5"></div>
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
                    <input id="discttp2%" onkeyup="hit2(this.value);" type="number" step="0.01" style="border: 1px solid black;" class="form-control hrf_arial" name="discttp2%"  tabindex="30" placeholder="disc" onchange="">
                  </div>

                  <div class="col-sm-3" >
                    <input id="hrg_jum5" onkeyup="document.getElementById('discttp2%').value=0;" type="text" style="border: 1px solid black;" class="form-control hrf_arial money" name="hrg_jum5" required="" tabindex="31" placeholder="Harga jual [Rp.]">
                    
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
                      <input id="nm_sat6" onkeyup="carisat(this.value,'nm_sat6','kd_sat6','discttp3','discttp3%','hrg_jum6','viewsat6','33')" type="text" style="border: 1px solid black;" class="form-control hrf_arial" name="nm_sat6" required="" tabindex="33" placeholder="ketik satuan">
                      <span><button id="btn-kem6" class="form-control yz-theme-l4 w3-hover-shadow hrf_arial" style="cursor: pointer;border:1px solid black" type="button" onclick="carisat(document.getElementById('nm_sat6').value,'nm_sat6','kd_sat6','discttp3','discttp3%','hrg_jum6','viewsat6','33')"><i class="fa fa-caret-down"></i></button></span>
                    </div>
                    
                    <input type="hidden" name="kd_sat6" id="kd_sat6">
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
                    <input id="discttp3%" onkeyup="hit3(this.value);" type="number" step="0.01" style="border: 1px solid black;" class="form-control" name="discttp3%" tabindex="34" placeholder="disc" >
                  </div>
                  <div class="col-sm-3" >
                    <input id="hrg_jum6" onkeyup="document.getElementById('discttp3%').value=0;" type="text" style="border: 1px solid black;" class="form-control hrf_arial money" name="hrg_jum6" required="" tabindex="35" placeholder="Harga jual [Rp.]">
                    
                  </div>
                
                <!--  -->

              </div>
            </div>

          </div>  
            
        </div><!-- row -->

        <!-- LIST Harga Beli -->
        <div class="w3-row">
          <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;border-style: ridge;border-color: white">
            <a href="#" class="w3-text-white w3-padding-small"><i class="fa fa-television" style="color:orange"></i>&nbsp; Harga beli sesuai nota pembelian barang</a>
          </div>
          <div class="w3-card" style="border-style: ridge;border-color: white ">
            <div id="viewhrgbeli"><script>carihrgbeli(1,true);</script></div>
          </div>  
        </div>

        <!--Tombol reset/simpan  -->
        <div class="w3-row w3-margin-top w3-margin-bottom w3-center">
          <div class="w3-col l12 s12">
            <button id="tmb_simpan" type="submit" style="cursor: pointer;" class="btn btn-success hrf_arial"><i class="fa fa-save">&nbsp;&nbsp;</i><b>Simpan</b></button>
            <button id="tmb-reset" onclick="kosongkan1();carihrgbeli(1,true)" type="reset" style="cursor: pointer;" class="btn btn-warning hrf_arial"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>Reset</b></button>
            <button id="tmb-hapus" onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){hapus(document.getElementById('kd_brg').value,'<?=$kd_toko?>')}" type="button" style="cursor: pointer;" class="btn btn-danger hrf_arial"><i class="fa fa-trash-o">&nbsp;&nbsp;</i><b>Delete</b></button>
          </div>  
          <!-- <div class="w3-col l6 s12 w3-center">
            <button type='button' onclick="document.getElementById('formlist').style.display='block';document.getElementById('ktcarilist').focus();carilistdata(1,true);" style="border-radius: 4px;font-size: 11pt;cursor: pointer;width: 300px" class="btn btn-primary w3-hide-small"><i class="fa fa-save">&nbsp;&nbsp;</i>Tampilkan semua data</button>

            <button type='button' onclick="document.getElementById('formlist').style.display='block';document.getElementById('ktcarilist').focus();carilistdata(1,true);" style="border-radius: 4px;font-size: 11pt;cursor: pointer;width: 300px" class="btn btn-primary w3-hide-large w3-hide-medium w3-margin-top w3-margin-bottom"><i class="fa fa-save">&nbsp;&nbsp;</i>Tampilkan semua data</button>
          </div> -->
        </div>  
        <!-- End tombol -->  

      </form> 
      <script type="text/javascript">
      $(document).ready(function() {
          $('#form-inputan').submit(function() {
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
      <div id="viewcek"></div>
    </div>
    
    <!-- Form barang-->
    <div id="formlist" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.5) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width: 100%">
        <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-search"></i>&nbsp;LIST DATA BARANG
          <span onclick="document.getElementById('formlist').style.display='none'" class="w3-display-topright" title="Close form" style="margin-top: -3px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <input id=ktcarilist onkeyup="carilistdata(1,true)" class="w3-input" placeholder="cari nama barang" type="text" style="border:1px solid blue;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" autofocus="">
        <div id="listdata"><script>carilistdata(1,true)</script></div>     
      </div>
    </div>
    <!-- End Form list barang-->

    <!-- Form cetak-->
    <div id="formcetkdbar" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0) ">
      <div class="w3-modal-content w3-card-4 w3-animate-top" style="border-style: ridge;border-color: white;width:600px ">
        <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;CETAK BARCODE
          <span onclick="document.getElementById('formcetkdbar').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -20px;margin-right: -17px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>

        <div class="w3-container">
          <!-- <div class="row">
            <div class="w3-col hrf_barcode w3-jumbo w3-center" id="kd_barsay_view"></div>
          </div> -->
          <div id="kd_barsay_view" class="hrf_barcode w3-jumbo w3-center"></div>
          <div id="kd_barsay_view_kd" class="hrf_arial w3-wide w3-center" style="margin-top: -35px"></div>
          <hr style="height:0px;border-width:2px; color:black;background-color: black">
          <form  action="f_masbrg_pilih.php" method="post" target="_blank" >
            <input class="" id='kd_barsay' name="kd_barsay" type="hidden"> 
            <div class="row w3-container" style="margin-left: 0px">
              <div class="col-sm-12">
                <div class="form-group row">

                  <label for="copies" class="col-sm-1 col-form-label hrf_arial"><b>Jml</b></label>
                  <div class="col-sm-2">
                    <input id="copies" onkeyup="" type="number" style="border: 1px solid black;" class="form-control hrf_arial" name="copies" required>
                  </div>  

                  <label for="n_size" class="col-sm-2 col-form-label"><b>Ukuran</b></label>
                  <div class="col-sm-3">
                    <select class="form-control" name="n_size" id="n_size" style="border: 1px solid black;font-size:12px ;height: 30px" required>
                      <option value="KECIL">KECIL</option>
                      <option value="SEDANG">SEDANG</option>
                      <option value="BESAR">BESAR</option>
                    </select>
                  </div>    

                  <label for="jenis" class="col-sm-1 col-form-label hrf_arial"> <b>Jenis</b></label>
                  <div class="col-sm-3">
                    <select class="form-control hrf_arial" name="jenis" id="jenis" style="border: 1px solid black; ;height: 30px" required>
                      <option value="PRINTER">PRINTER</option>
                      <option value="EXEL">XLS</option>
                    </select>
                  </div>          

                </div><!-- form-group -->          
              </div> <!-- col-sm-12 -->
            </div><!-- row -->
           <button class="w3-center btn btn-primary" type="submit">Cetak</button>      
          </form>
        </div>

      </div>
    </div>
     <!-- End Form list barang-->
  <div id="viewkdbar"></div>
  </div>
  <?php 
  if(isset($_GET['pesankoreksi'])){
    $con=opendtcek();
        
      $kd_brg=mysqli_real_escape_string($con,$_GET['pesankoreksi']);
        ?> 
        <script>
          document.getElementById('kd_brg').value='<?=$kd_brg?>';
        </script>
        <?php
        $sql1 =mysqli_query($con, "SELECT * from mas_brg 
               WHERE mas_brg.kd_brg ='$kd_brg' ORDER BY mas_brg.no_urut ASC");
        
        $data=mysqli_fetch_assoc($sql1);
        $nm_kem1=ceknmkem(mysqli_escape_string($con,$data['kd_kem1']),$con);
        $nm_kem2=ceknmkem(mysqli_escape_string($con,$data['kd_kem2']),$con);
        $nm_kem3=ceknmkem(mysqli_escape_string($con,$data['kd_kem3']),$con);
        $kd_brg=mysqli_escape_string($con,$data['kd_brg']);
        //cek disc tetap tokushima reiko
        $limit1=cekdisc($kd_brg,mysqli_escape_string($con,$data['kd_kem1']),$con);
            //echo '$lim1'.$lim1;
          
        if(empty($limit1)){
          $kd_sat4="1";$hrg_jum4=0;$lim1=0;$nm_kem4="-NONE-"; 
          $percen1=0;
        }else{
          $x1=explode(';', $limit1);
          $kd_sat4=$x1[0];
          $hrg_jum4=$x1[1];
          $lim1=$x1[2];
          $nm_kem4=ceknmkem($kd_sat4,$con);
          $percen1=str_replace('.',',',round(($data['hrg_jum1']-$hrg_jum4)/$hrg_jum4*100,2));
        }  
          //
          $limit1=cekdisc($kd_brg,$data['kd_kem2'],$con);
          if(empty($limit1)){
            $kd_sat5="1";$hrg_jum5=0;$lim2=0;$nm_kem5="-NONE-"; 
            $percen2=0;
          }else{
          $x1=explode(';', $limit1);
          $kd_sat5=$x1[0];
          $hrg_jum5=$x1[1];
          $lim2=$x1[2];
          $nm_kem5=ceknmkem($kd_sat5,$con);
          $percen2=str_replace('.',',',round(($data['hrg_jum2']-$hrg_jum5)/$hrg_jum5*100,2));
        }  
          $limit1=cekdisc($kd_brg,$data['kd_kem3'],$con);
          if(empty($limit1)){
            $kd_sat6="1";$hrg_jum6=0;$lim3=0;$nm_kem6="-NONE-"; 
            $percen3=0;
          }else{
          $x1=explode(';', $limit1);
          $kd_sat6=$x1[0];
          $hrg_jum6=$x1[1];
          $lim3=$x1[2];
          $nm_kem6=ceknmkem($kd_sat6,$con);
          $percen3=str_replace('.',',',round(($data['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2));
        }
        // 
        ?>
        <script>

         document.getElementById('kd_barsay').value='<?=mysqli_escape_string($con,$data['kd_bar']) ?>';
         document.getElementById('kd_barsay_view').innerHTML='<?=mysqli_escape_string($con,$data['kd_bar']) ?>';
         document.getElementById('kd_barsay_view_kd').innerHTML='<?=mysqli_escape_string($con,$data['kd_bar']) ?>';
         document.getElementById('no_urutbrg').value='<?=mysqli_escape_string($con,$data['no_urut']) ?>';
         document.getElementById('kd_brg').value='<?=mysqli_escape_string($con,$data['kd_brg']) ?>';
         document.getElementById('nm_brg').value='<?=mysqli_escape_string($con,$data['nm_brg']) ?>';
         document.getElementById('kd_bar').value='<?=mysqli_escape_string($con,$data['kd_bar']) ?>';

         // Satuan jual satu
         document.getElementById('hrg_def1').value='<?=gantitides(mysqli_escape_string($con,$data['hrg_jum1'])) ?>';
         document.getElementById('kd_sat1').value='<?=mysqli_escape_string($con,$data['kd_kem1']) ?>';
         document.getElementById('nm_sat1').value='<?=$nm_kem1?>';document.getElementById('jum_sat1').value='<?=mysqli_escape_string($con,$data['jum_kem1']) ?>';
         document.getElementById('hrg_jum1').value='<?=gantitides($data['hrg_jum1']) ?>';

         //---- satuan jual dua
         document.getElementById('hrg_def2').value='<?=gantitides(mysqli_escape_string($con,$data['hrg_jum2'])) ?>';
         document.getElementById('kd_sat2').value='<?=mysqli_escape_string($con,$data['kd_kem2']) ?>';
         document.getElementById('nm_sat2').value='<?=$nm_kem2?>';document.getElementById('jum_sat2').value='<?=mysqli_escape_string($con,$data['jum_kem2']) ?>';document.getElementById('hrg_jum2').value='<?=gantitides(mysqli_escape_string($con,$data['hrg_jum2'])) ?>';

         //--Satuan 3
         document.getElementById('hrg_def3').value='<?=gantitides(mysqli_escape_string($con,$data['hrg_jum3'])) ?>';
         document.getElementById('kd_sat3').value='<?=mysqli_escape_string($con,$data['kd_kem3']) ?>';
         document.getElementById('nm_sat3').value='<?=$nm_kem3?>';
         document.getElementById('jum_sat3').value='<?=mysqli_escape_string($con,$data['jum_kem3']) ?>';
         document.getElementById('hrg_jum3').value='<?=gantitides(mysqli_escape_string($con,$data['hrg_jum3'])) ?>';

         document.getElementById('discttp1').value='<?=$lim1 ?>';
         document.getElementById('nm_sat4').value='<?=$nm_kem4?>';
         document.getElementById('kd_sat4').value='<?=$kd_sat4 ?>';
         document.getElementById('hrg_jum4').value='<?=gantitides($hrg_jum4) ?>';
         document.getElementById('discttp2').value='<?=$lim2 ?>';
         document.getElementById('nm_sat5').value='<?=$nm_kem5?>';
         document.getElementById('kd_sat5').value='<?=$kd_sat5 ?>';
         document.getElementById('hrg_jum5').value='<?=gantitides($hrg_jum5) ?>';
         document.getElementById('discttp3').value='<?=$lim3 ?>';
         document.getElementById('nm_sat6').value='<?=$nm_kem6?>';
         document.getElementById('kd_sat6').value='<?=$kd_sat6 ?>';
         document.getElementById('hrg_jum6').value='<?=gantitides($hrg_jum6) ?>';
         document.getElementById('saycode').innerHTML='<?=mysqli_escape_string($connect,$data['kd_bar'])?>';carihrgbeli(1,true);
        </script>
        <?php
        unset($sql1,$data);
        mysqli_close($con);
     }
   ?>

   <script>  
    $(document).ready(function(){
      $(".loader1").fadeOut();
    })
   </script>
</body>
</html>
