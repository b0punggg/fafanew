<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="img/keranjang.png">

<div id="main" style="font-size: 10pt;background: linear-gradient(565deg, #FAFAD2 30%, white 100%);">
  <?php 
    include "starting.php";
    $kd_toko=$_SESSION['id_toko'];
   ?>
  <body >
    <script>
      function caripiutang(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_piutangbayar_cari.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: $("#no_fakjual").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#listcaripiutang").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }

      function ceksaldo_p(no_fak){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_piutangbayar_ceksaldo.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: no_fak}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#listceksaldo_p").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }
      
      function carinofak_p(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_piutangbayar_cari_nofak.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: $("#cari_nofak_p").val(),keyword2: $("#crp_lunas").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#listnofak_p").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }

      function kirimres(nofak){
        $.ajax({
          url: 'f_piutangbayar_kirimres.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:nofak}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#listnofak_p").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }

      function hapuspiutang(nourut){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_piutangbayar_hapus.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: nourut}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#viewcek").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }

      function kosongkan(){
        document.getElementById('no_fakjual').value="";
        document.getElementById('tgl_jual').value="";
        document.getElementById('tgl_tran').value="";
        document.getElementById('tgl_jt').value="";
        document.getElementById('byr_hutang').value="";
        document.getElementById('nm_pel').value="";
        document.getElementById('linkkirim').style.display='none'
        document.getElementById('no_fakjual').focus();
        caripiutang(1,true);
      }    

      function kosong_save(){      
        document.getElementById('byr_hutang').value="";
        document.getElementById('byr_hutang').focus();
      }    

    </script> 
    
    <div id="snackbar" style="z-index: 1"></div>
    
    <div id="listceksaldo_p"></div> 
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);position: sticky;top:43px;margin-top: -7px;z-index: 1;padding: 3px"><h5><i class='fa fa-cart-arrow-down'></i> &nbsp;TRANSAKSI &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Bayar Piutang Pelanggan</span><span class="w3-right" style="font-size: 16px"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span></h5> 
    </div>
      <form id="form-input" class="w3-row" action="f_piutangbayar_act.php" method="post" style="padding-right: 10px;padding-left: 10px;border-style: ridge;border-color: white;background: linear-gradient(565deg, #FAFAD2 30%, white 100%);margin-top: 0">
        <input type="hidden" id="nm_pel">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group row" style="margin-top: 12px">
              <label for="no_fakjual" class="col-sm-4 col-form-label"><b>No.Faktur Jual</b></label>
              <div class="col-sm-8">

                <div class="input-group" style="border-radius: 5px;"> 
                  <input id="no_fakjual" style="border: 1px solid black;font-size: 10pt;" type="text" class="form-control hrf_arial" name="no_fakjual" onblur="caripiutang(1,true);ceksaldo_p(this.value);" autofocus required tabindex="1">
                  <span>
                    <button onclick="document.getElementById('fnotapiutang').style.display='block';carinofak_p(1,true);document.getElementById('cari_nofak_p').focus()" style="height: 32px;border-radius: 5px;cursor: pointer;box-shadow: 0px 1px 4px" type="button">
                      <img src="../admin/img/supplierkk.png" alt="" title="cari no faktur">
                    </button>
                  </span>
                </div>
                
              </div>  
            </div>

            <div class="form-group row" style="margin-top: -10px">
              <label for="tgl_tran" class="col-sm-4 col-form-label" style="text-align: right"><b>Tgl.Angsur</b></label>
              <div class="col-sm-8">
                <input id="tgl_tran" style="border: 1px solid black;font-size: 10pt;" type="date" class="form-control hrf_arial" name="tgl_tran" required tabindex="3">
              </div>
            </div>  

          </div><!-- col-sm-4 -->
          <div class="col-sm-4">
            <div class="form-group row" style="margin-top: 12px" >
              <label for="tgl_jual" class="col-sm-4 col-form-label" style="text-align: right"><b>Tgl.Jual</b></label>
              <div class="col-sm-8">
                <input id="tgl_jual" style="border: 1px solid black;font-size: 10pt;" type="date" class="form-control hrf_arial" name="tgl_jual" required tabindex="2" disabled="" >
              </div>
            </div>   
            <script>
              function cektf2() {
              var checkBox = document.getElementById("cek_tf2");
              var text = document.getElementById("pil_tf2");
              if (checkBox.checked == true){
                text.value = "TRANSFER";
              } else {
                text.value = "";
              }
            }
            </script>
            <div class="form-group row" style="margin-top: -10px">
              <label for="cek_tf2" class="col-sm-4 col-form-label" style="text-align: right"><b>Transfer</b></label>
              <div class="col-sm-8">
                <input type="checkbox" id="cek_tf2" style="height:20px;width:20px;margin-top:5px" onclick="cektf2()">
                <input type="hidden" id="pil_tf2" name="pil_tf2">
              </div>
            </div>      
          </div>

          <div class="col-sm-4">
          <div class="form-group row" style="margin-top: 12px">
              <label for="tgl_jt" class="col-sm-4 col-form-label" style="text-align: left"><b>Jatuh Tempo</b></label>
              <div class="col-sm-8">
                <input id="tgl_jt" style="border: 1px solid black;font-size: 10pt;" type="text" class="form-control hrf_arial" name="tgl_jt" required tabindex="4" disabled="">
              </div>
            </div>   
            <div class="form-group row" style="margin-top: -10px">
              <label for="byr_hutang" class="col-sm-4 col-form-label" style="text-align: right"><b>Bayar Hutang</b></label>
              <div class="col-sm-8">
                <input id="byr_hutang" style="border: 1px solid black;font-size: 10pt;" type="text" class="form-control hrf_arial money" name="byr_hutang" required tabindex="5" >
              </div>
            </div>   
          </div>
        </div> 
        <div class="row">
          <div class="col">
            <button type="button" onclick="kosongkan()" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 btn btn-warning"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
          </div>
          <div class="col">
            <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 btn btn-primary"><i class="fa fa-save">&nbsp;&nbsp;</i><b>S I M P A N</b></button>
          </div>
          <div class="col">
            <a id="linkcetak" type="button" target="_blank" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt; text-align: center" class="w3-margin-bottom w3-card-2 btn btn-success w3-text-white"><i class="fa fa-save">&nbsp;&nbsp;</i><b>CETAK PEMBAYARAN</b></a>
          </div>
          <div class="col">
            <a id="linkcetak" type="button" target="_blank" style="display:none;width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt; text-align: center" class="w3-margin-bottom w3-card-2 btn btn-success w3-text-white"><i class="fa fa-save">&nbsp;&nbsp;</i><b>CETAK STRUK</b></a>
          </div>
          <div class="col">
            <button id="linkkirim" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt; text-align: center;display:none" class="w3-margin-bottom w3-card-2 btn btn-danger w3-text-white" onclick="
            if(document.getElementById('no_fakjual').value !=''){if(confirm('Kirim Ke BUMDES ?')){kirimres(document.getElementById('no_fakjual').value);}}
            "><i class="fa fa-save">&nbsp;&nbsp;</i><b>KIRIM DATA KE BUMDES</b></button>
          </div>
          <script>
            if(document.getElementById('nm_pel').value=='BUMDES'){
              document.getElementById('linkkirim').style.display='block';
            }else{
              document.getElementById('linkkirim').style.display='none'
            }
            
          </script>
        </div>            
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
                  kosong_save();caripiutang(1,true);
                  ceksaldo_p(document.getElementById('no_fakjual').value);
              }
          })
          return false;
        });
      })
      </script> 
     <?php 
     
     //jika pembayaran dari pesan jatuh tempo
      if(isset($_GET['bayarpiut'])){
        $pesanedit=mysqli_real_escape_string($connect,$_GET['bayarpiut']);
        $x=explode(';', $pesanedit);
          $no_fakedit=$x[0];
          $tgl_fakedit=$x[1];    
        ?>
          <script>
            document.getElementById('no_fakjual').value='<?=$no_fakedit?>';
            ceksaldo_p('<?=$no_fakedit?>');
            caripiutang(1,true);
            document.getElementById('byr_hutang').focus();
          </script>
        <?php  
      } else {
        ?><script>caripiutang(1,true);</script><?php
      }
    ?>   
    <div id="viewcek"></div> 
    <div class="" style="margin-top: -12px">
      <div id="listcaripiutang"></div>
    </div>
    <!-- Form nota-->
    <div id="fnotapiutang" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0);border-style: ridge; ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:700px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

        <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;">&nbsp;<i class="fa fa-search"></i>
          Cari No Faktur Pembelian
        </div>

        <div class="w3-center">
          <span onclick="document.getElementById('fnotapiutang').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
      
        <div class="modal-body">
          <input id="cari_nofak_p" type="hidden">             
          <div class="w3-row">
            <div class="w3-col l2 s5 m12">
              <label for="crp_lunas" style="font-size: 11pt">Pilih Kondisi:</label>    
            </div>
            <div class="w3-col l4 s7 m12">
              <select name="crp_lunas" id="crp_lunas" class="form-control" style="font-size: 11pt" onchange="carinofak_p(1,true);">
                <option value="AND mas_jual.saldo_hutang>0">Belum Lunas</option>
                <option value="AND mas_jual.saldo_hutang=0">Lunas</option>
                <option value="">Semua</option>
              </select>
            </div>
          </div>
          <div id="listnofak_p"><script>carinofak_p(1,true);</script></div>
        </div> <!--Modal-body-->
      </div><!--Modal content-->
    </div>
    <!-- End Form Nota -->
  </body>
  <?php
   
  ?>
</div>
