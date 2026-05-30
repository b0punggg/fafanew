<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<?php 
 include 'starting.php';
 $kd_toko=$_SESSION['id_toko'];
 $conpaket=opendtcek();
?>

<div id="main" style="font-size: 10pt">
  <script>			
	  function kosongkan(){
	      document.getElementById('nm_paket').value="";
        document.getElementById('kd_brg').value="";
        document.getElementById('nm_brg').value="";
        document.getElementById('no_urut').value="";
        document.getElementById('qty_brg').value="";
        document.getElementById('kd_sat').value="";
        document.getElementById('nm_sat').value="";
	      document.getElementById('nm_paket').focus();
        caripaket(1,true);caripaketbrg(1,true);
	 	}
    function kosongkan2(){
        //document.getElementById('nm_paket').value="";
        document.getElementById('kd_brg').value="";
        document.getElementById('nm_brg').value="";
        //document.getElementById('no_urut').value="";
        document.getElementById('qty_brg').value="";
        document.getElementById('kd_sat').value="";
        document.getElementById('nm_sat').value="";
        document.getElementById('nm_brg').focus();
    }  

	 	function carinmbrg(page_number, search){
		  $(this).html("ketik pencarian").attr("disabled", "disabled");
		  
		  $.ajax({
		    url: 'm_paket_nmbrg.php', // File tujuan
		    type: 'POST', // Tentukan type nya POST atau GET
		    data: {keyword: $("#nm_brg").val(), page: page_number, search: search}, 
		    dataType: "json",
		    beforeSend: function(e) {
		      if(e && e.overrideMimeType) {
		        e.overrideMimeType("application/json;charset=UTF-8");
		      }
		    },
		    success: function(response){ 
		      // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
		      
		      $("#viewnmbrgp").html(response.hasil);
		    },
		    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
		      alert(xhr.responseText); // munculkan alert
		    }
		  });
	    }

      function caripaket(page_number, search){
      $(this).html("ketik pencarian").attr("disabled", "disabled");
      
      $.ajax({
        url: 'm_paket_cari.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword: $("#keyktpaket").val(), page: page_number, search: search}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $("#viewdtpaket").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
      }

      function caripaketbrg(page_number, search){
        
        $.ajax({
          url: 'm_paket_caribrg.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: $("#no_urut").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            
            $("#viewdtpaketbrg").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }
      function carisatpak(){
        $.ajax({
          url: 'm_paket_carisat.php', // File tujuan
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
            $("#viewsatpak").html(response.hasil);
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
      $pesan=mysqli_real_escape_string($conpaket,$_GET['pesan']);
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
    mysqli_close($conpaket);
  ?>
    <div class="w3-container" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;z-index: 1; padding: 0px;margin-top: -5px;text-shadow: 1px 1px 2px black;color:white">
    	<h5 style="margin-top: 7px;margin-left: 20px"><i class='fa fa-briefcase'></i> &nbsp;MASTER DATA &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Inisial Jual Perpaket</span><span class="w3-right" style="font-size: 16px;color:orange"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span></h5>
    </div>
    
    <!-- <hr class="w3-black" style="margin-top: 0px"> -->
      <div class="w3-row" style="border-style: ridge;border-radius: 5px;background: linear-gradient(565deg, #FAFAD2 30%, white 100%);">
      	<!-- #F0E68C -->
        <div class="col-sm-12 w3-card-4">
      	  <form id="form1" class="w3-container" action="m_paket_act.php" method="post">
            <!-- key edit -->
            <input type="hidden" name="kd_paket" id="no_urut">
            <!-- -------- -->
      	  	<div class="row w3-margin-top">
      	  		<div class="col-sm-4">
      	  		  <div class="form-group row" style="font-size: 9pt;margin-top: 16px">
    	   	        <label for="nm_paket" class="col-sm-4 col-form-label"><b>Nama Paket</b></label>
    	   	        <div class="col-sm-8">
    	   	          <input class="form-control hrf_arial" id="nm_paket" type="text" name="nm_paket"  autofocus required style="border: 1px solid black;font-size: 9pt;">
    	   	        </div>
    	   	      </div>	
      	  		</div>

      	  		<div class="col-sm-8 w3-border w3-margin-bottom">
      	  		  <div class="container" style="font-size: 9pt;">
                  <div class="form-group w3-row" style="margin-top: 15px">
                    <!-- <i class="fa fa-2x fa-shopping-bag" style="color:orange;text-shadow: 2px 2px 3px black"></i> -->
                    <input type="hidden" id="kd_brg" name="kd_brg">
                    <label for="kd_brg" class="w3-col l2 s12 m12 form-label "><b class="w3-margin-left">Nm. Barang</b></label>
                    <div class="w3-col l5 s12 m12">
                      <div class="input-group">
                        <input class="form-control hrf_arial" onkeyup="carinmbrg(1, true);" id="nm_brg" type="text" name="nm_brg" style="border: 1px solid black; font-size: 10pt;" placeholder="ketik nama barang" required="" tabindex="5">
                        <span><button id="btn-nmbrgp" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" type="button"><i class="fa fa-caret-down"></i></button></span>
                      </div>
                      <div class="row">
                        <div id="boxnmbrgp" class="col-sm-8" style="display:none;position:absolute;z-index: 1;width:100%; ">
                             <div id="viewnmbrgp" class="w3-card" style="background-color: white"><script>carinmbrg(1,true)</script></div>
                        </div>
                      </div>
                    </div>  
                    <script>
                    
                      $(document).ready(function(){
                        $("#btn-nmbrgp").click(function(){
                          $("#nm_brg").focus();
                          $("#boxnmbrgp").slideToggle("fast")
                          $("#tabkempak").slideUp("fast");
                        });
                        
                        $("#nm_brg").keyup(function(){
                          $("#boxnmbrgp").slideDown("fast");
                          $("#tabkempak").slideUp("fast");
                        });

                        $("#viewnmbrgp").mouseleave(function(){
                          $("#boxnmbrgp").slideUp("fast");
                        });

                        $("#nm_brg").focus(function(){
                          $("#tabkempak").slideUp("fast");
                          
                        });
                      });
                    </script>            
                    <!--end idbrg -->

                    <label for="kd_sat" class="w3-col l1 s12 m12 col-form-label"><b class="w3-margin-left">Satuan</b></label>
                    <input type="hidden" id="kd_sat" name="kd_sat">
                    <div class="w3-col l2 s12 m12">
                      <input class="form-control hrf_arial" id="nm_sat" type="text" name="nm_sat" required style="border: 1px solid black; font-size: 9pt" onkeyup="carisatpak(1,true);" onclick="this.onkeyup">
                      <div class="w3-row">
                       <div id="tabkempak" class="col-sm-4" style="display:none;position:absolute;z-index: 1;width:100%;">
                        <div id="viewsatpak" class="w3-card-2"></div>
                       </div>
                      </div> 
                    </div>
                    
                      
                      <script>
                        $(document).ready(function(){
                          $("#nm_sat").click(function(){
                            $("#tabkempak").slideToggle("fast");
                            $("#box").slideUp("fast");
                          });
                          $("#nm_sat").keyup(function(){
                            $("#tabkempak").slideDown("fast");
                            $("#boxnmbrgp").slideUp("fast");
                          });
                          $("#tabkempak").mouseleave(function(){
                            $("#tabkempak").slideUp("fast");
                          });
                          $("#nm_sat").focus(function(){
                            $("#boxnmbrgp").slideUp("fast");
                          });
                        });
                      </script>

                    <label for="qty_brg" class="w3-col l1 s12 m12 col-form-label"><b class="w3-margin-left">qty</b></label>
                    <div class="w3-col l1 s12 m12">
                      <input class="form-control hrf_arial" id="qty_brg" type="number" step="0.01" name="qty_brg" required style="border: 1px solid black; font-size: 9pt" >
                    </div>
                  </div>
      	   	    </div>	
                
      	  		</div>
      	  	</div>
      	  	<!--Tombol reset/simpan  -->
  	        <div class="row">
  	            <div class="col-sm-6" style="padding-bottom: 2px">
                    <button onclick="kosongkan()" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l3"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>DATA BARU / R E S E T</b></button>
                </div>
                <div class="col-sm-6">
                  <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" 
                    class=" w3-margin-bottom w3-card-2 yz-theme-l2"><i class="fa fa-save">&nbsp;&nbsp;</i>
                    <b>TAMBAH KE DAFTAR</b>
                  </button>
                </div>  
            </div>  
  	        <!-- End tombol -->
      	  </form>	

          <script type="text/javascript">
            $(document).ready(function() {
              $('#form1').submit(function() {
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#viewhapus').html(data);
                        caripaket(1,true);caripaketbrg(1,true)
                        kosongkan2();
                    }
                })
                return false;
              });
            })
          </script> 

      	  <hr class="w3-black">
          <div class="row">
            <div class="col-sm-4" style="overflow-y: auto;border-style: ridge; padding-left: -19px;height: 390px;padding: 2px 2px 2px 2px ">
              <div class=" yz-theme-l5 w3-border">
                <div class="w3-row">
                  <div class="w3-container" >
                   <!--  <div id="ket_rec" class="fa fa-television" style="margin-top: 15px;margin-left: 10px;font-size: 10pt">
                    </div> -->
                    <div class="input-group" style="margin-top: 15px">
                       <input onkeyup="if(event.keyCode==13){caripaket(1, true);}" style="font-size: 10pt;height: 30px" type="text" class="form-control hrf_arial" placeholder="ketik nama paket" id="keyktpaket">&nbsp;
                      <span class="input-group-btn w3-margin-bottom">
                        <button onclick="caripaket(1, true);" class="btn btn-primary" type="button" id="btn-ktpaket" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i></button>
                        <a style="font-size: 10pt;" title="Reset cari" onclick="document.getElementById('keyktpaket').value='';document.getElementById('btn-ktpaket').click();" href="#" class="btn btn-warning"><i class="fa fa-undo"></i></a>
                      </span>
                    </div>    
                  </div>
                </div>  
              </div>  
              <div id="viewdtpaket" style="margin-top: 0px;"><script>caripaket(1,true)</script>
            </div>
            
          </div>        
            <div class="col-sm-8"  style="overflow-y: auto;border-style: ridge; padding-left: -19px;height: 390px;padding: 2px 2px 2px 2px ">
              <div class=" yz-theme-l5 w3-border">
                <div class="w3-row">
                  <div class="w3-container">
                    <div id="ket_rec1" class="fa fa-television" style="margin-left: 10px;font-size: 10pt">&nbsp; LIST ITEM BARANG
                    </div>
                  </div>
                </div>  
              </div>  
              <div class="hrf_arial" id="viewdtpaketbrg" style="margin-top: 0px;">
                <script>caripaketbrg(1,true)</script>

              </div>
              <br>
            </div>          
            </div>
          </div><!-- row -->    
        </div>
      </div>
      <div id='viewhapus'></div>
</div>

          
          