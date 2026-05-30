<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
<?php 
 include 'starting.php';
 $connect1=opendtcek();
 // $sql22 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
?>

<div id="main" style="font-size: 10pt">
  	<script>	
	  			
	  function kosongkan(){
	      document.getElementById('nm_sat1').value="";
	      document.getElementById('nm_sat2').value="";
        document.getElementById('no_urut').value="";
	      document.getElementById('nm_sat1').focus();
	 	}  

	 	function caribiaya(page_number, search){
		  $.ajax({
		    url: 'f_biaya_cari.php', // File tujuan
		    type: 'POST', // Tentukan type nya POST atau GET
		    data: {keyword1:$("#kd_cari1").val(),keyword2:$("#kd_cari2").val(),keyword3:$("#kd_cari3").val(), page: page_number, search: search}, 
		    dataType: "json",
		    beforeSend: function(e) {
		      if(e && e.overrideMimeType) {
		        e.overrideMimeType("application/json;charset=UTF-8");
		      }
		    },
		    success: function(response){ 
		      $("#viewdtbiaya").html(response.hasil);
		    },
		    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
		      alert(xhr.responseText); // munculkan alert
		    }
		  });
	  }

    function cartokolist(){
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("nm_tokolist");
      filter = input.value.toUpperCase();
      table = document.getElementById("tablist");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }       
      }
    }
    
    function fcek(){
      var chkin
      chkin=document.getElementById('tk_global');
      if (chkin.checked==true){
        document.getElementById('nm_tokolist').value="GLOBAL";
        document.getElementById('kd_tokolist').value="GLOBAL";
      }
    } 
    
    function kosongin(){
      document.getElementById("id_rec").value="";
      document.getElementById("nominal").value="";
      document.getElementById("ket_biaya").value="";
      document.getElementById("id_jenis").value="11";
      document.getElementById("nm_jenislist").value="LAIN-LAIN";
      document.getElementById("nominal").focus();
    }

    function delbiaya(nourut){
    $.ajax({
      url: 'f_biaya_hapus.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keydel:nourut}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        $("#viewdtbiaya").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
    </script> 

    <div id="snackbar" style="z-index: 1"></div>
    
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
    	<i class='fa fa-briefcase' style="font-size: 18px">&nbsp;TRANSAKSI &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Biaya Operasional</span>
    </div>
    <div class="w3-row">
      <div class="col-sm-12 w3-card-4">
        <form id="form-biaya" class="w3-container" action="f_biaya_act.php" method="post" enctype="multipart/form-data">
    	  	<div class="row w3-margin-top">

    	  		<div class="col-sm-3">
    	  		  <div class="form-group row">
                <label for="nm_tokolist" class="col-sm-3 col-form-label"><b>Toko</b></label>
                <div class="col-sm-9">
                  <div class="input-group"> 
                      <input class="form-control hrf_arial" id="nm_tokolist" type="text" name="nm_tokolist" required style="border: 1px solid black;font-size: 10pt;" placeholder="ketik nama toko" value="">
                        <button class="btn btn-outline-secondary" type="button" id="btn-cari-toko"><i class="fa fa-caret-down"></i></button>
                      <input type="hidden" id="kd_tokolist" name="kd_tokolist" value="">
                      <input type="hidden" id="id_rec" name="id_rec">
                  </div>    
                  <div id="listtoko" style="display:none;position:absolute;z-index: 1" onmouseleave="document.getElementById('btn-cari-toko').click()">
                      
                      <div id="tablist" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;">
                        <table class="table table-bordered table-sm table-hover" style="font-size:9pt; ">
                          <tr align="middle" class="yz-theme-l3">
                            <th>NAMA TOKO</th>
                          </tr>
                          <?php 

                          $sql2 = mysqli_query($connect1, "SELECT * from toko ORDER BY no_urut ASC ");
                          while ($datakat = mysqli_fetch_array($sql2)){
                          ?>
                          <tr>
                            <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokolist').value='<?=$datakat['kd_toko'] ?>';document.getElementById('nm_tokolist').value='<?= $datakat['nm_toko'] ?>';" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                          </tr>  
                          <?php   
                          }
                          unset($datakat,$sql2);
                          ?>
                        </table>
                      </div>  <!-- tabbonus -->
                  </div> 
    	   	      </div>
                <script>
                  $(document).ready(function(){
                    $("#btn-cari-toko").click(function(){
                      $("#listtoko").slideToggle("fast");
                    });
                  });
                </script>    
              </div>	
    	  		</div>
            <div class="col-3 ">
              <div class="form-group row ">
                <label for="tgl_biaya" class="col-sm-3 col-form-label"><b>Tanggal</b></label>
                <div class="col-sm-9">
                  <input id="tgl_biaya" class="form-control" type="date" name="tgl_biaya"  value="<?=$_SESSION['tgl_set']?>"  style="border: 1px solid black;font-size: 10pt;">
                </div>
              </div>  
            </div>
    	  		<!-- <div class="col-sm-1 ">
    	  		  <div class="form-group row " >
  	   	        <div class="col-sm-4">
  	   	          <input class="form-check-input" id="tk_global" type="checkbox" name="tk_global"  style="height: 22px;width: 22px" onclick="fcek()" checked>
                  <label for="tk_global" class="col-sm-3 col-form-label"><b>Global</b></label>
  	   	        </div>
      	   	  </div>	
    	  		</div> -->
            
            <div class="col-sm-3">
              <div class="form-group row">
                <label for="nm_jenislist" class="col-sm-3 col-form-label"><b>Jenis</b></label>
                <div class="col-sm-9">
                  <div class="input-group"> 
                      <input class="form-control hrf_arial" id="nm_jenislist" type="text" name="nm_jenislist" required style="border: 1px solid black;font-size: 10pt;" placeholder="Pilih jenis biaya" value="LAIN-LAIN" >
                        <button class="btn btn-outline-secondary" type="button" id="btn-cari-jenis"><i class="fa fa-caret-down"></i></button>
                      <input type="hidden" id="id_jenis" name="id_jenis" value="9">
                  </div>    
                  <div id="listjenis" style="display:none;position:absolute;z-index: 1" onmouseleave="document.getElementById('btn-cari-jenis').click()">
                      
                      <div id="tablistjenis" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;">
                        <table class="table table-bordered table-sm table-hover" style="font-size:9pt; ">
                          <tr align="middle" class="yz-theme-l3">
                            <th>JENIS BIAYA</th>
                          </tr>
                          <?php 

                          $sqljenis = mysqli_query($connect1, "SELECT * from biaya_jns ORDER BY id ASC ");
                          while ($datakats = mysqli_fetch_array($sqljenis)){
                          ?>
                          <tr>
                            <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('id_jenis').value='<?=$datakats['id'] ?>';document.getElementById('nm_jenislist').value='<?= $datakats['jns_biaya'] ?>';" style="cursor: pointer">
                              <?php echo $datakats['jns_biaya']; ?> 
                            </td>
                          </tr>  
                          <?php   
                          }
                          unset($datakats,$sqljenis);
                          ?>
                        </table>
                      </div>  <!-- tabbonus -->
                  </div> 
                </div>
                <script>
                  $(document).ready(function(){
                    $("#btn-cari-jenis").click(function(){
                      $("#listjenis").slideToggle("fast");
                    });
                  });
                </script>    
              </div>  
            </div>              

            <div class="col-sm-3 ">
              <div class="form-group row ">
                <label for="nominal" class="col-sm-3 col-form-label"><b>Nominal</b></label>
                <div class="col-sm-9">
                  <input id="nominal" class="form-control money" type="text" name="nominal" required style="border: 1px solid black;font-size: 10pt;" autofocus>
                </div>
              </div>  
            </div>  
    	  	</div>

          <div class="row" style="margin-top: -10px">
             
            
            <div class="col-sm-3 ">
              <div class="form-group row ">
                <label for="ket_biaya" class="col-sm-3 col-form-label"><b>Keterangan</b></label>
                <div class="col-sm-9">
                <textarea id="ket_biaya" name="ket_biaya" rows="4" cols="66" style="white-space: pre-wrap;"></textarea>
                </div>
              </div>  
            </div>            
          </div>  
    	  	<!--Tombol reset/simpan  -->
	        <div class="row">
	            <div class="col-sm-6">
                  <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l1"><i class="fa fa-save">&nbsp;&nbsp;</i><b>S I M P A N</b></button>
              </div>	
              <div class="col-sm-6" style="padding-bottom: 2px">
                  <button type="reset" onclick="document.getElementById('nominal').focus()" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 w3-yellow"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
              </div>
              <!-- <div class="col-sm-4" style="padding-bottom: 2px">
                  <button type="button" onclick="document.getElementById('cetbiaya').style='block'" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 btn-primary"><i class="fa fa-print">&nbsp;&nbsp;</i><b>C E T A K</b></button>
              </div> -->
          </div>  
	        <!-- End tombol -->
    	  </form>	
    	  <script type="text/javascript">
          $(document).ready(function() {
            $('#form-biaya').submit(function() {
              $.ajax({
                  type: 'POST',
                  url: $(this).attr('action'),
                  data: $(this).serialize(),
                  success: function(data) {
                      $('#viewsave').html(data);
                  }
              })
              return false;
            });
          })
        </script> 
    	  <div id="viewsave"></div>

        <!-- list data biaya -->
        <input type="hidden" id="kd_cari1">
        <input type="hidden" id="kd_cari2">
        <input type="hidden" id="kd_cari3">
        <div class="hrf_arial" id="viewdtbiaya" style="margin-top: 0px;">
          <script>caribiaya(1,true)</script>
        </div>
        <!--EOF list biaya  -->
	    </div>  
    </div>
</div>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     