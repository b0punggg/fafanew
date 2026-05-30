<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="img/keranjang.png">

<div id="main" style="font-size: 10pt;background: linear-gradient(180deg, #FAFAD2 10%, white 70%)">
  <?php 
    include "starting.php";
    $connect=opendtcek();
    $kd_toko=$_SESSION['id_toko'];
   ?>
  <body >
     <script>
      
      function caribrgretur(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_returbeli_listretur.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: $("#no_tran").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#listbrgretur").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }

      function cariidsup(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_returbeli_carisup.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: $("#kd_sup").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#viewidsup").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }

      function cariidbrgretur(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_returbeli_caribrg.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword1: $("#nm_brg").val(),keyword2: $("#kd_sup").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#viewkdbrgretur").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }
      
      function carisatretur(){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_returbeli_carisat.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:$("#kd_brg").val()}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            
            $("#viewsatretur").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      } 
       
      function hapusreturbrg(nourut){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_returbeli_hapusbr.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:nourut}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");          
            $("#viewuni").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }  

      function cekjmlstokbeli(nourut,kdbrg,kdsat){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_returbeli_cekstok.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword1:nourut, keyword2: kdbrg, keyword3: kdsat}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");          
            $("#viewjmlstok").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      } 

      function prosessimpan(notran,tgltran,kembali){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_returbeli_simpan.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword1:notran, keyword2: tgltran, keyword3:kembali}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");          
            $("#viewuni").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      } 

      function prosesbatal(notran,tgltran){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_returbeli_batal.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword1:notran, keyword2: tgltran}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");          
            $("#viewuni").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      } 
      
      function databaru(notran){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_returbeli_databaru.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:notran}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");          
            $("#viewuni1").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      } 
      
      function carinoretur(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_returbeli_carinoretur.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:$("#keycari").val(),page:page_number, search:search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");          
            $("#viewcarinoretur").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }  
      function kosongkan(){
        document.getElementById('no_tran').value="";
        document.getElementById('tgl_tran').value="";
        document.getElementById('kd_sup').value="";
        document.getElementById('kd_brg').value="";
        document.getElementById('tgl_fak').value="";
        document.getElementById('no_fak').value="";
        document.getElementById('hrg_beli').value="0";
        document.getElementById('qtyretur').value="";
        document.getElementById('discretur').value="";
        document.getElementById('nm_brg').value="";
        document.getElementById('nm_sat').value="";
        document.getElementById('tax').value="";
        document.getElementById('ketretur').value="";
        document.getElementById('no_item').value="";
        document.getElementById('kembali').value="";
        document.getElementById('info1').innerHTML=" SUPPLIER";
        document.getElementById('info2').innerHTML=" ALAMAT";
        document.getElementById('info3').innerHTML=" SALES";
        document.getElementById('info4').innerHTML=" CONTACK PERSON";
        document.getElementById('no_tran').focus();
        caribrgretur(1,true);        
      }    
      function kosongkan2(){
        // document.getElementById('no_tran').value="";
        // document.getElementById('tgl_tran').value="";
        // document.getElementById('kd_sup').value="";
        document.getElementById('kd_brg').value="";
        document.getElementById('tgl_fak').value="";
        document.getElementById('no_fak').value="";
        document.getElementById('hrg_beli').value="0";
        document.getElementById('qtyretur').value="";
        document.getElementById('discretur').value="";
        document.getElementById('nm_brg').value="";
        document.getElementById('nm_sat').value="";
        document.getElementById('tax').value="";
        document.getElementById('ketretur').value="";
        document.getElementById('no_item').value="";
        document.getElementById('kembali').value="";
        document.getElementById('info1').innerHTML=" SUPPLIER";
        document.getElementById('info2').innerHTML=" ALAMAT";
        document.getElementById('info3').innerHTML=" SALES";
        document.getElementById('info4').innerHTML=" CONTACK PERSON";
        document.getElementById('no_tran').focus();
        caribrgretur(1,true);        
      }    

      function kosongkan2(){
        //document.getElementById('no_tran').value="";
        // document.getElementById('tgl_tran').value="";
        //document.getElementById('kd_sup').value="";
        document.getElementById('kd_brg').value="";
        document.getElementById('tgl_fak').value="";
        document.getElementById('no_fak').value="";
        document.getElementById('hrg_beli').value="0";
        document.getElementById('qtyretur').value="";
        document.getElementById('discretur').value="";
        document.getElementById('nm_brg').value="";
        document.getElementById('nm_sat').value="";
        document.getElementById('tax').value="";
        document.getElementById('ketretur').value="";
        document.getElementById('no_item').value="";
        document.getElementById('kembali').value="";
        // document.getElementById('info1').innerHTML=" SUPPLIER";
        // document.getElementById('info2').innerHTML=" ALAMAT";
        // document.getElementById('info3').innerHTML=" SALES";
        // document.getElementById('info4').innerHTML=" CONTACK PERSON";
        document.getElementById('nm_brg').focus();
        caribrgretur(1,true);        
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
            <script>popnew_error("Terjadi kesalahan !");</script>
          <?php
        }else if($pesan=="lunas"){
          ?>
            <script>popnew_error("Tagihan Sudah Lunas");</script>
          <?php
        }

      } 
    ?>

    <div id="listceksaldo"></div> 
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
      <i class='fa fa-cart-arrow-down' style="font-size: 18px">&nbsp;TRANSAKSI &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 16px">Retur Pembelian Barang</span>
    </div>

      <form id="form1" class="w3-row" action="f_returbeli_act.php" method="post" style="padding-right: 10px;padding-left: 10px;border-style: ridge;border-color: white;background: linear-gradient(180deg, #FAFAD2 30%, white 70%);margin-top: 0">
        <!-- <input type="text" id="no_urutbay_beli" name="no_urutbay_beli">   --> 
        <div class="row" style="font-size: 10pt;">
          <div class="col-sm-4">
        
            <div class="form-group row" style="margin-top: 12px">
              <label for="no_tran" class="col-sm-4 col-form-label" style="text-align: left"><b>No. Transaksi</b></label>
              <div class="col-sm-8">
                <div class="input-group" style="border-radius: 5px;"> 
                  <input id="no_tran" style="border: 1px solid black;font-size: 10pt;" type="text" class="form-control" name="no_tran" placeholder="AUTO" autofocus required tabindex="1" onblur="caribrgretur(1,true)">
                  <span>
                    <button id="btn-new" onclick="datanew('<?=$kd_toko?>');" style="height: 32px;border-radius: 5px;cursor: pointer;box-shadow: 0px 1px 4px" type="button" title="Data baru">
                      <!-- <img src="../admin/img/backup.png" alt="" title="Nota baru"> -->
                      <i class="fa fa-database"></i>
                    </button>
                  </span>
                </div>  
                
              </div>  
            </div>   
            <div class="form-group row" style="margin-top: -10px">
              <label for="tgl_tran" class="col-sm-4 col-form-label" style="text-align: left"><b>Tanggal</b></label>
              <div class="col-sm-8">
                <input id="tgl_tran" style="border: 1px solid black;font-size: 10pt;" type="date" class="form-control " name="tgl_tran" required tabindex="2" onblur="caribrgretur(1,true)">
              </div> 
            </div>   
            <div class="form-group row" style="margin-top: -10px;">
              <label for="kd_sup" class="col-sm-4 col-form-label"><b>Supplier</b></label>
              <div class="col-sm-8">

                <div class="input-group" style="border-radius: 5px;"> 
                  <input id="kd_sup" style="border: 1px solid black;border-radius: 2px;padding:2px;padding-left:10px;font-size: 9pt" type="text" class="form-control" name="kd_sup" required tabindex="3" onkeyup="cariidsup(1, true);" onkeypress="return event.keyCode != 13;">
                  <span>
                    <button id="btn-idsup" onclick="cariidsup(1,true)" style="height: 32px;border-radius: 5px;cursor: pointer;box-shadow: 0px 1px 4px" type="button" title="cari supplier">
                      <!-- <img src="../admin/img/supplierkk.png" alt="" title="cari no faktur"> -->
                      <i class="fa fa-shopping-cart"></i>
                    </button>
                  </span>
                </div>
                <div id="boxidsup" class="w3-col" style="display:none;position:absolute;z-index: 1; ">
                  <div id="viewidsup" class="w3-card-4" style="background-color: white;">
                    <script>cariidsup(1,true)</script>
                  </div>
                </div>  
                    <script>
                      $(document).ready(function(){
                        $("#btn-idsup").click(function(){
                          $("#boxidsup").slideToggle("fast");
                          $("#boxkdbrgretur").slideUp("fast");
                          $("#kd_sup").focus();
                        });
                        $("#kd_sup").keyup(function(){
                         $("#boxidsup").slideDown("slow,swing");
                        });
                        $("kd_sup").keydown(function (e) {
                            if (e.keyCode == 13) {
                                e.preventDefault();
                                return false;
                            }
                        });
                        $("#viewidsup").mouseleave(function(){
                          $("#boxidsup").slideUp("fast");
                        });
                      });
                    </script>                  
                    
                  <!-- END Box Kategori -->
              </div>    
            </div>
          </div><!-- col-sm-4 -->
          
          <!-- Informasi supplier -->
          <div class="col-sm-8 w3-section w3-large">
            <div id="info1" class="fa fa-shopping-cart">&nbsp; SUPPLIER</div><br>
            <div id="info2" class="fa fa-street-view " style="margin-top: 10px">&nbsp; ALAMAT</div><br>
            <div id="info3" class="fa fa-user" style="margin-top: 10px">&nbsp; SALES</div><br>
            <div id="info4" class="fa fa-volume-control-phone" style="margin-top: 10px">&nbsp; CONTACK PERSON</div>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
          </div>
        </div> <!-- row --> 

        <h5 style="color:blue"><i class="fa fa-desktop w3-large w3-text-black">&nbsp;&nbsp;</i>INPUT DATA RETUR</h5>
        <div class="w3-row" >
          <div class="w3-col s12 l3 w3-container">
            <input type="hidden" id="kd_brg" name="kd_brg">
            <input type="hidden" id="no_fak" name="no_fak">
            <input type="hidden" id="tgl_fak" name="tgl_fak">
            <input type="hidden" id="hrg_beli" name="hrg_beli">
            <input type="hidden" id="no_item" name="no_item">
            <div class="input-group">
              <input id="nm_brg" name="nm_brg" type="text" placeholder="Nama Barang" required="" tabindex="4" class="form-control" onkeyup="cariidbrgretur(1,true);document.getElementById('kd_sat').value=0;document.getElementById('nm_sat').value='';" style="font-size: 9pt;padding: 4px;padding-left: 10px">
              <span><button id="btn-kdbrgretur" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" onclick="cariidbrgretur(1,true);document.getElementById('kd_sat').value=0;document.getElementById('nm_sat').value='';" type="button">
                <i class="fa fa-caret-down"></i></button></span>  
            </div>

            <div id="boxkdbrgretur" class="w3-row" style="display:none;position:absolute;z-index: 1; ">
              <div id="viewkdbrgretur" class="w3-card-4 w3-col l6 s12" style="background-color: white;">
                <script>cariidbrgretur(1,true)</script>
              </div>
            </div>  
                <script>
                  $(document).ready(function(){
                    $("#btn-kdbrgretur").click(function(){
                      $("#boxkdbrgretur").slideToggle("fast");
                      $("#boxidsup").slideUp("fast");
                      $("#boxsatretur").slideUp("fast");
                      $("#nm_brg").focus();
                    });

                    $("#nm_brg").focus(function(){
                     $("#boxsatretur").slideUp("fast");
                     $("#boxidsup").slideUp("fast");
                     });

                    $("#nm_brg").keyup(function(){
                     $("#boxkdbrgretur").slideDown("fast,swing");
                    });

                    $("nm_brg").keydown(function (e) {
                        if (e.keyCode == 13) {
                            e.preventDefault();
                            return false;
                        }
                    });
                    $("#viewkdbrgretur").mouseleave(function(){
                      $("#boxkdbrgretur").slideUp("fast");
                    });
                  });
                </script>                  
                
              <!-- END Box Kategori -->
          </div>

          <div class="w3-col s12 l2 w3-container">
            <input id="kd_sat" name="kd_sat" type="hidden" >
            <div class="input-group">
              <input id="nm_sat" name="nm_sat" type="text" placeholder="Satuan" required="" tabindex="5" class="form-control" style="font-size: 9pt;" onkeyup="carisatretur(1,true);">
              <span><button id="btn-satretur" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" onclick="carisatretur(1,true)" type="button">
                <i class="fa fa-caret-down"></i></button></span>  
            </div>

            <div id="boxsatretur" class="w3-row" style="display:none;position:absolute;z-index: 1; ">
              <div id="viewsatretur" class="w3-card-4 w3-col l3 s12" style="background-color: white;">
                <script>carisatretur(1,true)</script>
              </div>
            </div>  
                <script>
                  $(document).ready(function(){
                    $("#btn-satretur").click(function(){
                      $("#boxsatretur").slideToggle("fast");
                      $("#boxkdbrgretur").slideUp("fast");
                      $("#boxidsup").slideUp("fast");
                      $("#nm_sat").focus();
                    });
                    $("#nm_sat").keyup(function(){
                     $("#boxsatretur").slideDown("fast");
                    });

                    $("#nm_sat").focus(function(){
                     $("#boxkdbrgretur").slideUp("fast");
                     $("#boxidsup").slideUp("fast");
                    });

                    $("nm_sat").keydown(function (e) {
                        if (e.keyCode == 13) {
                            e.preventDefault();
                            return false;
                        }
                    });
                    $("#viewsatretur").mouseleave(function(){
                      $("#boxsatretur").slideUp("fast");
                    });
                  });
                </script>                  
          </div>
          <div class="w3-col s12 l1 w3-container">
            <input id="qtyretur" name="qtyretur" type="number" step="0.25" placeholder="Jml Brg" required="" tabindex="6" class="form-control" style="font-size: 9pt;" onfocus="document.getElementById('boxsatretur').style.display='none';document.getElementById('boxkdbrgretur').style.display='none';">
            <div id="viewjmlstok"></div>
          </div>   
          <div class="w3-col s12 l1 w3-container">
            <input id="discretur" name="discretur" type="number" step="0.05" placeholder="Disc %" required="" tabindex="7" class="form-control" style="font-size: 9pt;" onfocus="document.getElementById('boxsatretur').style.display='none';document.getElementById('boxkdbrgretur').style.display='none';">
          </div>   
          <div class="w3-col s12 l1 w3-container">
            <input id="tax" name="tax" type="number" step="0.05" placeholder="Tax %" required="" tabindex="8" class="form-control" style="font-size: 9pt;" onfocus="document.getElementById('boxsatretur').style.display='none';document.getElementById('boxkdbrgretur').style.display='none';">
          </div>   
          <div class="w3-col s12 l3 w3-container">
            <input id="ketretur" name="ketretur" type="text" placeholder="Keterangan" required="" tabindex="9" class="form-control" style="font-size: 9pt;" onfocus="document.getElementById('boxsatretur').style.display='none';document.getElementById('boxkdbrgretur').style.display='none';">
          </div>   

          <div class="w3-col s12 l1 w3-container">
            <button class="btn btn-success fa fa-plus w3-hover-shadow" type="submit" style="font-size: 9pt">&nbsp;ADD</button>
          </div>   
        </div>   

      </form>  

    <script type="text/javascript">
      $(document).ready(function() {
        $('#form1').submit(function() {
          $.ajax({
              type: 'POST',
              url: $(this).attr('action'),
              data: $(this).serialize(),
              success: function(data) {
                  $('#viewuni').html(data);
                  kosongkan2();              
              }
          })
          return false;
        });
      })
    </script> 
      
    <div class="" style="background: linear-gradient(0deg, #FAFAD2 0%,white 30%, white 100%);margin-top: -12px">
      <div id="listbrgretur"><script>caribrgretur(1,true);</script></div>
      <div id="viewuni"></div>
      <div id="viewuni1"></div>
    </div>

  <div class="row container " style="font-size: 9pt;">
    
    <!-- <div class="col-3"> -->
      <!-- <div class="row ">
        <div class="col-6 offset-sm-3" >
          <button class="btn-primary form-control fa fa-save w3-hover-shadow" style="font-size: 10pt" onclick="cekinput();">&nbsp;Simpan
          </button>   
        </div>
      </div>    
      <div class="row">
        <div class="col-6 offset-sm-3 w3-margin-top">
          <button class="btn-danger form-control fa fa-trash w3-hover-shadow" style="font-size: 10pt" onclick="batal();carinoretur(1,true);">&nbsp;Batal</button>    
        </div>
      </div>    
    </div> -->

    <div class="col-md-auto ">
      <div class="form-group row" style="margin-top: 2px">
       <label for="totawal" class="col-sm-3 col-form-label" style="text-align: left"><b>Tot.Awal</b></label>
        <div class="col-sm-8">
         <input id="totawal" style="border: 1px solid black;font-size: 9pt;padding: 3px;text-align: right" type="text" class="form-control" readonly="" value="">
        </div>  
      </div>

      <div class="form-group row" style="margin-top: -15px">
        <label for="totpot" class="col-sm-3 col-form-label" style="text-align: left"><b>Tot.Disc</b></label>
        <div class="col-sm-8">
          <input id="totpot" style="border: 1px solid black;font-size: 9pt;padding: 3px;text-align: right" type="text" class="form-control" readonly="" value="">
        </div>  
      </div>
      <div class="form-group row" style="margin-top: -18px">
        <label for="tottax" class="col-sm-3 col-form-label" style="text-align: left"><b>Tot.Tax</b></label>
        <div class="col-sm-8">
          <input id="tottax" style="border: 1px solid black;font-size: 9pt;padding: 3px;text-align: right" type="text" class="form-control" readonly="" value="">
        </div>  
      </div>   
      <div class="form-group row" style="margin-top: -15px">
        <label for="totretur" class="col-sm-3 col-form-label" style="text-align: left"><b>Tot.Retur</b></label>
        <div class="col-sm-8">
          <input id="totretur" style="border: 1px solid black;font-size: 9pt;padding: 3px;text-align: right" type="text" class="form-control" readonly="" value="">
        </div>  
      </div>
    </div>
    <div class="col col-lg-2" style="font-size: 9pt;font-weight:bold">
      <input type="radio" id="pil_uang" name="pilih" value="uang" onclick="document.getElementById('kembali').value=this.value">
      <label for="pil_uang" style="cursor: pointer">Pengembalian uang</label><br>
      <input type="radio" id="pil_barang" name="pilih" value="barang" onclick="document.getElementById('kembali').value=this.value">
      <label for="pil_barang" style="cursor: pointer">Ganti dengan barang</label><br>
      <input type="hidden" id="kembali">
      <button class="btn-primary form-control fa fa-save w3-hover-shadow w3-margin-botom" style="font-size: 10pt" onclick="cekinput();">&nbsp;Simpan
          </button>   
      <button class="btn-danger form-control fa fa-trash w3-hover-shadow w3-margin-botom" style="font-size: 10pt" onclick="batal();carinoretur(1,true);">&nbsp;Batal</button>       
    </div>

    <div class="w3-col">
      <input type="hidden" id="keycari">
      <!-- table cari no retur  -->
      <div id="viewcarinoretur"><script>carinoretur(1,true)</script></div>
      <!--  -->
    </div>
  </div>
<script>
  function cekinput(){
    radio1=document.getElementById('pil_uang').checked;
    radio2=document.getElementById('pil_barang').checked;
    if (radio1 ==false && radio2==false){
      popnew_warning("Pilihan Pembalian barang belum dipilih"); 
      kosongkan2();
    } 
    if (radio1 ==true || radio2==true){
      document.getElementById('pil_uang').checked = false;
      document.getElementById('pil_barang').checked = false;  
      prosessimpan(document.getElementById('no_tran').value,document.getElementById('tgl_tran').value,document.getElementById('kembali').value);

    }    
  } 

  function batal(){
    
    if (document.getElementById('no_tran').value !== "" && document.getElementById('tgl_tran').value !== ""){
      if (confirm("Proses ini akan membatalkan nota retur pembelian, Data akan terhapus termasuk sudah dilakukan simpan data")) {
    document.getElementById('pil_uang').checked = false;
      document.getElementById('pil_barang').checked = false;  
      prosesbatal(document.getElementById('no_tran').value,document.getElementById('tgl_tran').value);
    } else {
    kosongkan();caribrgretur(1,true);
    }
    }else{
      //kosongkan();caribrgretur(1,true); 
    }
  }

  function datanew(kd_toko){
    kosongkan();caribrgretur(1,true);
    databaru(kd_toko);
  }
</script>

  </body>
</div>
