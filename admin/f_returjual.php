<title>Retur Jual</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
<?php 
 include 'starting.php';
 $connect=opendtcek();
 $tgl_set=$_SESSION['tgl_set'];
 $kd_toko=$_SESSION['id_toko'];
 $id_user=$_SESSION['id_user'];
?>

<div id="main" style="font-size: 10pt">
  <script>		
    function getAjaxHtml(response){
      try {
        var parsed = JSON.parse(response);
        if(parsed && typeof parsed.hasil !== "undefined"){
          return parsed.hasil;
        }
      } catch (e) {}
      return response;
    }
  	
    function resetinput(kd_toko){
      $.ajax({
        url: 'f_returjualreset.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword1:$("#no_returjual").val(),keyword2:kd_toko}, 
        dataType: "text",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#viewdelretur").html(getAjaxHtml(response));
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          popnew_error("Gagal memuat data reset retur");
        }
      });
    }

	 	function carinotaretur(page_number, search){
      $.ajax({
        url: 'f_returjualcari.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#keycariretur").val(),page:page_number,search:search}, 
        dataType: "text",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#viewcarinotaretur").html(getAjaxHtml(response));
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          popnew_error("Gagal memuat list nota jual");
        }
      });
    }
    
    function jualreturtake(no_urut){
      $.ajax({
        url: 'f_returjual_act.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword1:no_urut,keyword2:$("#tgl_retur").val(),keyword3:$("#no_returjual").val()}, 
        dataType: "text",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#viewtake").html(getAjaxHtml(response));
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          popnew_error("Gagal mengambil data retur");
        }
      });
    }

    

    function returjualstart(kd_toko,id_user){
      $.ajax({
        url: 'f_returjualstart.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword1:kd_toko,keyword2:id_user}, 
        dataType: "text",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#viewstart").html(getAjaxHtml(response));
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          popnew_error("Gagal memulai retur jual");
        }
      });
    }

    function upretur(no_urut,no_item,qty,netto){
      $.ajax({
        url: 'f_returjualup.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword1:no_urut,keyword2:no_item,keyword3:qty,keyword4:netto}, 
        dataType: "text",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#viewupretur").html(getAjaxHtml(response));
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          popnew_error("Gagal update item retur");
        }
      });
    }

    function delretur(no_urut){
      $.ajax({
        url: 'f_returjualhapus.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:no_urut}, 
        dataType: "text",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#viewdelretur").html(getAjaxHtml(response));
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          popnew_error("Gagal menghapus item retur");
        }
      });
    }  
    
    function cariretur(no_ret,page_number,search){
      $.ajax({
        url: 'f_returjcari.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword1:$("#tgl_retur1").val(), keyword2:no_ret, page:page_number,search:search}, 
        dataType: "text",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#retjual").html(getAjaxHtml(response));
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          if (window.console) {
            console.error("Gagal memuat data retur:", xhr.status, xhr.responseText);
          }
          $("#retjual").html(getAjaxHtml(xhr.responseText || ""));
        }
      });
    }
    returjualstart('<?=$kd_toko?>','<?=$id_user?>');

    function carilistretur(page_number, search){
      $.ajax({
        url: 'f_returjuallist.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:$("#keycarilist").val(),page:page_number,search:search}, 
        dataType: "text",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#viewlistretur").html(getAjaxHtml(response));
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          if (window.console) {
            console.error("Gagal memuat list retur:", xhr.status, xhr.responseText);
          }
          $("#viewlistretur").html(getAjaxHtml(xhr.responseText || ""));
        }
      });
    }

  </script> 

  <div id="snackbar" style="z-index: 1"></div>
    <?php 
    if(isset($_GET['pesan'])){
      $pesan=$_GET['pesan'];
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
  
  <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;color:white;text-shadow: 1px 1px 2px black;font-size: 18px">
    <i class='fa fa-briefcase w3-text-orange' style="font-size: 18px"></i>&nbsp;TRANSAKSI &nbsp; <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Retur Jual</span><span style="font-size: 12pt" class="w3-right w3-text-orange"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set']);?></span>
  </div>
    <!-- <hr class="w3-black" style="margin-top: 0px"> -->
      <div class="w3-container" style="background: linear-gradient(565deg, #FFFACD 10%, white 90%);">
    		<div class="w3-row w3-margin-top">
  	  		<div class="w3-col l4 m3">
  	  		  <div class="w3-row">
              <label class="w3-col l3 col-form-label"><b>Tanggal</b></label>
  	   	      <div class="w3-col l6">
  	   	        <input class="form-control hrf_arial" id="tgl_retur1" type="date" value="<?=$_SESSION['tgl_set']?>" autofocus required style="border: 1px solid black;font-size: 10pt;">
  	   	      </div>
    	   	  </div>	
          </div>

  	  		<div class="w3-col l5 m5 ">
  	  		  <div class="w3-row" >
              <label class="w3-col l3  col-form-label"><b>No. Retur Jual</b></label>
              <div class="w3-col l8">
                <div class="input-group">
                  <input class="form-control hrf_arial" id="no_returjual1" type="text" autofocus required style="border: 1px solid black;font-size: 10pt;" onblur="cariretur(document.getElementById('no_returjual1').value,1,true);" onkeypress="if(event.keyCode==13){this.blur();}">
                  <div class="input-group-btn">
                    <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('fcari_notaret').style.display='block';carilistretur(1,true);" style="border:1px solid black;font-size: 8pt;padding-top: 9px;padding-bottom: 9px"><i class="fa fa-search" style="cursor: pointer;"></i>
                    </button>
                  </div>  
                </div>
              </div>
            </div> 
      		</div>

          <div class="w3-col l3 m4 w3-margin-bottom">
            <div class="w3-row" >
              <label class="w3-col l3  col-form-label">&nbsp;</label>
              <div class="w3-col l8">
                <button type="button" class="btn btn-warning" style="width: 100%" onclick="document.getElementById('fcari_nota').style.display='block'"><i class="fa fa-search"></i>&nbsp; Cari Barang</button>
              </div>    
            </div>
          </div>

  	  	</div>

        <div class=" yz-theme-l5 w3-border">
          <div class="w3-row">
            <div class="w3-col" >
              <div id="ket_rec" class="fa fa-television" style="margin-top: 10px;margin-left: 10px;font-size: 13pt">  
              </div>
            </div>
            <div class="w3-col" >
              <div id="retjual"></div>
            </div>
          </div>  
        </div>  

        <form id="form1" class="w3-container" action="f_returjual_pro.php" method="post">
          <input class="form-control hrf_arial" id="tgl_retur"    type="hidden" name="tgl_retur">
          <input class="form-control hrf_arial" id="no_returjual" type="hidden" name="no_returjual">

    	  	<!--Tombol reset/simpan  -->
	        <div class="row">
            <div class="col-sm-6">
                <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-d4"><i class="fa fa-save">&nbsp;&nbsp;</i><b>S I M P A N</b></button>
            </div>	
            <div class="col-sm-6" style="padding-bottom: 2px">
                <button onclick="kosongkan()" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-d4"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
            </div>
          </div>  
	        <!-- End tombol -->
    	  </form>	

        <!-- Form cari nota-->
        <div id="fcari_nota" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge;border-color: white ">
          <div class="w3-modal-content w3-card-4 w3-animate-left" style="width:80%;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

            <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);color:white;text-shadow: 1px 1px 2px black">&nbsp;<i class="fa fa-search"></i>
              LIST PENJUALAN BARANG
            </div>
             <input type="hidden" id="keycariretur" name="keycariretur"> 
             <input type="hidden" id="keycarilist" name="keycarilist"> 
            <div class="w3-center">
              <span onclick="document.getElementById('fcari_nota').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
            </div>
            <div id="viewcarinotaretur"><script>carinotaretur(1,true);</script></div> 
            
          </div><!--Modal content-->
        </div>
        <!-- End Form Nota -->  

        <!-- Form cari nota Retur-->
        <div id="fcari_notaret" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge;border-color: white ">
          <div class="w3-modal-content w3-card-4 w3-animate-top" style="width:80%;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

            <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);color:white;text-shadow: 1px 1px 2px black">&nbsp;<i class="fa fa-search"></i>
              LIST NOTA RETUR JUAL
            </div>
             <input type="hidden" id="keycarilist" name="keycarilist"> 
            <div class="w3-center">
              <span onclick="document.getElementById('fcari_notaret').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
            </div>
            <div id="viewlistretur"><script>carilistretur(1,true);</script></div> 
            
          </div><!--Modal content-->
        </div>
        <!-- End Form Nota -->  

        <div id="viewstart"></div>
        <div id="viewtake"></div>
        <div id="viewupretur"></div>
        <div id="viewdelretur"></div>
      </div>
</div>
<script>
  function kosongkan(){
      resetinput('<?=$kd_toko?>');    
      document.getElementById('no_returjual').value='';
      document.getElementById('no_returjual1').value='';
      document.getElementById('tgl_retur').value='';
      document.getElementById('tgl_retur1').value='';
      returjualstart('<?=$kd_toko?>','<?=$id_user?>')
      document.getElementById('no_returjual1').focus();

    }   
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     