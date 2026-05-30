<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
<?php 
 include 'starting.php';
 $connect=opendtcek();
?>

<div id="main" style="font-size: 10pt;background: linear-gradient(565deg, #FAFAD2 30%, white 100%);">
  	<script>	
	  	function carimembertukar(){
		  $.ajax({
		    url: 'f_tukarpoin_cari.php',
		    type: 'POST',
		    data: {keyword: $("#keycari_member").val()}, 
		    dataType: "json",
		    beforeSend: function(e) {
		      if(e && e.overrideMimeType) {
		        e.overrideMimeType("application/json;charset=UTF-8");
		      }
		    },
		    success: function(response){ 
		      $("#viewcari_member").html(response.hasil);
		    },
		    error: function (xhr, ajaxOptions, thrownError) {
		      alert(xhr.responseText);
		    }
		  });
	    }

      function cekpoinmember(){
        var kd_member = document.getElementById('kd_member_tukar').value;
        if(kd_member == '' || kd_member == null) {
          document.getElementById('poin_saat_ini').value = '0';
          document.getElementById('display_poin').innerHTML = '0 poin';
          return;
        }
        
        $.ajax({
          url: 'f_tukarpoin_cekpoin.php',
          type: 'POST',
          data: {kd_member: kd_member}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            document.getElementById('poin_saat_ini').value = response.poin;
            document.getElementById('display_poin').innerHTML = number_format(response.poin, 0, ',', '.') + ' poin';
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
          }
        });
      }

      function hitungsisapoin(){
        var poin_saat_ini = parseFloat(document.getElementById('poin_saat_ini').value) || 0;
        var poin_tukar_str = document.getElementById('poin_tukar').value.replace(/\./g, '');
        var poin_tukar = parseFloat(poin_tukar_str) || 0;
        
        if(poin_tukar > 0 && poin_tukar <= poin_saat_ini){
          var sisa_poin = poin_saat_ini - poin_tukar;
          document.getElementById('poin_sisa').value = sisa_poin;
          document.getElementById('display_sisa').innerHTML = number_format(sisa_poin, 0, ',', '.') + ' poin';
        } else {
          document.getElementById('poin_sisa').value = poin_saat_ini;
          document.getElementById('display_sisa').innerHTML = number_format(poin_saat_ini, 0, ',', '.') + ' poin';
        }
      }

      function validasitukar(){
        var poin_saat_ini = parseFloat(document.getElementById('poin_saat_ini').value) || 0;
        var poin_tukar_str = document.getElementById('poin_tukar').value.replace(/\./g, '');
        var poin_tukar = parseFloat(poin_tukar_str) || 0;
        
        if(isNaN(poin_tukar) || poin_tukar <= 0){
          alert('Masukkan jumlah poin yang akan ditukar!');
          document.getElementById('poin_tukar').focus();
          return false;
        }
        
        if(poin_tukar > poin_saat_ini){
          alert('Poin yang akan ditukar melebihi poin yang dimiliki!\nPoin saat ini: ' + number_format(poin_saat_ini, 0, ',', '.') + ' poin');
          document.getElementById('poin_tukar').focus();
          return false;
        }
        
        var keterangan = document.getElementById('keterangan_tukar').value.trim();
        if(keterangan == ''){
          alert('Masukkan keterangan/reward yang ditukar!');
          document.getElementById('keterangan_tukar').focus();
          return false;
        }
        
        return true;
      }

      function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
          prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
          sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
          dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
          s = '',
          toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
          };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
          s[1] = s[1] || '';
          s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
      }

      function kosongkan(){
        document.getElementById('kd_member_tukar').value = '';
        document.getElementById('nm_member_tukar').value = '';
        document.getElementById('poin_saat_ini').value = '0';
        document.getElementById('poin_tukar').value = '';
        document.getElementById('poin_sisa').value = '0';
        document.getElementById('keterangan_tukar').value = '';
        document.getElementById('display_poin').innerHTML = '0 poin';
        document.getElementById('display_sisa').innerHTML = '0 poin';
        document.getElementById('keycari_member').value = '';
        document.getElementById('kd_member_tukar').focus();
      }

  $(document).ready(function(){
    $('.money').mask('000.000.000.000.000', {reverse: true});
  });
    </script> 

    <div id="snackbar" style="z-index: 1"></div>
    <?php 
    if(isset($_GET['pesan'])){
      $pesan=$_GET['pesan'];
      if($pesan=="simpan"){
        ?>
          <script>popnew_ok("Penukaran poin berhasil dilakukan");</script>
        <?php
      }else if($pesan=="gagal"){
        ?>
          <script>popnew_error("Ops.. gagal untuk melakukan penukaran poin");</script>
        <?php
      }
    } 
    ?>
  
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
      <i class='fa fa-exchange' style="font-size: 18px">&nbsp;TRANSAKSI &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Penukaran Poin Member</span>
    </div>
      <div class="w3-row" style="background: linear-gradient(565deg, #FFFACD 10%, white 90%);">
        <div class="col-sm-12 ">
      	  <form id="form1" class="w3-container" action="f_tukarpoin_act.php" method="post" onsubmit="return validasitukar();">
      	  	<div class="row w3-margin-top">
      	  		<div class="col-sm-6">
      	  		  <div class="form-group row">
      	   	      <label for="kd_member_tukar" class="col-sm-4 col-form-label"><b>Kode Member</b></label>
      	   	      <div class="col-sm-8">
      	   	        <div class="input-group">
      	   	          <input class="form-control hrf_arial" id="kd_member_tukar" type="text" name="kd_member_tukar" autofocus required style="border: 1px solid black;font-size: 10pt;" onkeyup="cekpoinmember();carimembertukar();" placeholder="Ketik kode atau nama member">
      	   	          <input type="hidden" id="nm_member_tukar" name="nm_member_tukar">
      	   	          <span class="input-group-btn">
      	   	            <button type="button" class="btn btn-primary" onclick="carimembertukar();" style="font-size: 10pt;"><i class="fa fa-search"></i></button>
      	   	          </span>
      	   	        </div>
      	   	        <div id="viewcari_member" style="position:absolute;z-index: 20;overflow: auto;display: none;border-style: ridge;border-color: white;max-height:300px;width:400px;background-color: white;" class="w3-card">
      	   	          <script>carimembertukar()</script>
      	   	        </div>
      	   	      </div>
        	   	 </div>	
               <div class="form-group row" style="margin-top: -10px" >
                  <label for="poin_saat_ini" class="col-sm-4 col-form-label"><b>Poin Saat Ini</b></label>
                  <div class="col-sm-8">
                    <input type="hidden" id="poin_saat_ini" name="poin_saat_ini" value="0">
                    <div class="form-control" style="border: 1px solid black;font-size: 12pt;font-weight: bold;color: #ff6b00;background-color: #fff3cd;" readonly>
                      <span id="display_poin">0 poin</span>
                    </div>
                  </div>
               </div> 
             	</div>
                 
      	  		<div class="col-sm-6 ">
      	  		  <div class="form-group row" >
                  <label for="poin_tukar" class="col-sm-4 col-form-label"><b>Jumlah Poin Ditukar</b></label>
                  <div class="col-sm-8">
                    <input class="form-control hrf_arial money" id="poin_tukar" type="text" name="poin_tukar" required style="border: 1px solid black;font-size: 10pt;" onkeyup="hitungsisapoin();" placeholder="0">
                  </div>
                </div> 
                <div class="form-group row" style="margin-top: -10px">
                  <label for="poin_sisa" class="col-sm-4 col-form-label"><b>Poin Sisa</b></label>
                  <div class="col-sm-8">
                    <input type="hidden" id="poin_sisa" name="poin_sisa" value="0">
                    <div class="form-control" style="border: 1px solid black;font-size: 12pt;font-weight: bold;color: #28a745;background-color: #d4edda;" readonly>
                      <span id="display_sisa">0 poin</span>
                    </div>
                  </div>
                </div>  
      	  		</div>
      	  	</div>
      	  	
      	  	<div class="row w3-margin-top">
      	  		<div class="col-sm-12">
      	  		  <div class="form-group row">
                  <label for="keterangan_tukar" class="col-sm-2 col-form-label"><b>Keterangan / Reward</b></label>
                  <div class="col-sm-10">
                    <textarea class="form-control hrf_arial" id="keterangan_tukar" name="keterangan_tukar" rows="3" required style="border: 1px solid black;font-size: 10pt;" placeholder="Contoh: Voucher Diskon 10%, Barang Gratis, dll"></textarea>
                  </div>
               </div>
      	  		</div>
      	  	</div>
      	  	
      	  	<!--Tombol reset/simpan  -->
  	        <div class="row">
              <div class="col-sm-6">
                  <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l1"><i class="fa fa-exchange">&nbsp;&nbsp;</i><b>PROSES TUKAR POIN</b></button>
              </div>	
              <div class="col-sm-6" style="padding-bottom: 2px">
                  <button onclick="kosongkan();" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom btn-warning"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
              </div>
            </div>  
  	        <!-- End tombol -->
      	  </form>	

        	<div class=" yz-theme-l5 w3-border" style="margin-top: 20px">
            <div class="w3-row">
              <div class="w3-half" >
                <div id="ket_rec" class="fa fa-television" style="margin-top: 15px;margin-left: 10px;font-size: 13pt">  
                </div>
              </div>
              <div class="w3-half">
                <div class="input-group" style="margin-top: 15px">
                  <input onkeyup="if(event.keyCode==13){carimembertukar();}" style="font-size: 10pt;height: 30px" type="text" class="form-control hrf_arial" placeholder="ketik pencarian [nama member]" id="keycari_member">&nbsp;
                  <span class="input-group-btn w3-margin-bottom">
                    <button onclick="carimembertukar();" class="btn btn-primary" type="button" id="btn-ktmember" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i></button>
                    <a style="font-size: 10pt;" title="Reset cari" onclick="document.getElementById('keycari_member').value='';document.getElementById('btn-ktmember').click();" href="#" class="btn btn-warning"><i class="fa fa-undo"></i></a>
                  </span>
                </div>    
              </div>
            </div>  
          </div>  
        </div>  
      </div>
</div>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     


