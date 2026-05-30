<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="img/keranjang.png">

<div id="main" style="font-size: 10pt;">
  <?php 
    include "starting.php";
    $connect=opendtcek();
    $kd_toko=$_SESSION['id_toko'];
   ?>
  <body >
     <script>
      function carihutang(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_hutangbayar_cari.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: $("#no_fak").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#listcarihutang").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }

      function hapushutang(nourut){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_hutangbayar_hapus.php', // File tujuan
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


      function ceksaldo(no_fak){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_hutangbayar_ceksaldo.php', // File tujuan
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
            $("#listceksaldo").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }
      
      function carinofak(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        $.ajax({
          url: 'f_hutangbayar_cari_nofak.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword: $("#cari_nofak").val(),keyword2:$("#cari_lunas").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#listnofak").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }

      function kosongkan(){
        document.getElementById('no_fak').value="";
        document.getElementById('tgl_fak').value="";
        document.getElementById('tgl_tran').value="";
        document.getElementById('tgl_jt').value="";
        document.getElementById('saldo_awal').value="";
        document.getElementById('saldo_hutang').value="";
        document.getElementById('byr_hutang').value="";
        document.getElementById('via').value="";
        document.getElementById('byr_hutang').focus();
        document.getElementById('no_fak').focus();
        carihutang(1,true);
      }    

      function kosong_save(){
        // document.getElementById('no_fak').value="";
        // document.getElementById('tgl_fak').value="";
        // document.getElementById('tgl_tran').value="";
        document.getElementById('saldo_awal').value="";
        document.getElementById('saldo_hutang').value="";
        document.getElementById('byr_hutang').value="";
        document.getElementById('via').value="";
        document.getElementById('byr_hutang').focus();
        document.getElementById('no_fak').focus();
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
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;padding:3px">
      <i class='fa fa-cart-arrow-down' style="font-size: 18px">&nbsp;TRANSAKSI &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 16px">Bayar Hutang Supplier</span><span class="w3-right" style="font-size: 16px"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span>
    </div>
      <form id="form-input" class="w3-row" action="f_hutangbayar_act.php" method="post" style="padding-right: 10px;padding-left: 10px;border-style: ridge;border-color: white;margin-top: 0">
        <!-- <input type="text" id="no_urutbay_beli" name="no_urutbay_beli">   --> 
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group row" style="margin-top: 12px">
              <label for="no_faktur" class="col-sm-4 col-form-label"><b>No. Faktur Beli</b></label>
              <div class="col-sm-8">

                <div class="input-group" style="border-radius: 5px;"> 
                  <input id="no_fak" style="border: 1px solid black;font-size: 10pt;" type="text" class="form-control hrf_arial" name="no_fak" onblur="carihutang(1,true);ceksaldo(this.value);" autofocus required tabindex="1">
                  <span>
                    <button onclick="carinofak(1,true);document.getElementById('fnota').style.display='block';document.getElementById('cari_nofak').focus()" style="height: 32px;border-radius: 5px;cursor: pointer;box-shadow: 0px 1px 4px" type="button" class="yz-theme-l4">
                      <img src="../admin/img/supplierkk.png" alt="" title="cari no faktur">
                    </button>
                  </span>
                </div>
                
              </div>  
            </div>

            <div class="form-group row" style="margin-top: -10px">
              <label for="tgl_fak" class="col-sm-4 col-form-label" style="text-align: left"><b>Tgl.Beli</b></label>
              <div class="col-sm-8">
                <input id="tgl_fak" style="border: 1px solid black;font-size: 10pt;" type="date" class="form-control hrf_arial" name="tgl_fak" required tabindex="2" disabled="" >
              </div> 
            </div>   

            <div class="form-group row" style="margin-top: -10px">
              <label for="tgl_jt" class="col-sm-4 col-form-label" style="text-align: left"><b>Jatuh Tempo</b></label>
              <div class="col-sm-8">
                <input id="tgl_jt" style="border: 1px solid black;font-size: 10pt;" type="date" class="form-control hrf_arial" name="tgl_jt" required tabindex="2" disabled="" >
              </div>
              
            </div>   

          </div><!-- col-sm-4 -->
          <div class="col-sm-4">
            <div class="form-group row" style="margin-top: 12px" >
              <label for="saldo_awal" class="col-sm-4 col-form-label" style="text-align: right"><b>Hutang Awal</b></label>
              <div class="col-sm-8">
                <input id="saldo_awal" style="border: 1px solid black;font-size: 10pt;text-align: right" type="text" class="form-control hrf_arial" name="saldo_awal" required tabindex="4" disabled="">
              </div>
            </div>   
            <div class="form-group row" style="margin-top: -10px">
              <label for="saldo_hutang" class="col-sm-4 col-form-label" style="text-align: right"><b>Sisa Hutang</b></label>
              <div class="col-sm-8">
                <input id="saldo_hutang" style="border: 1px solid black;font-size: 10pt;text-align: right" type="text" class="form-control hrf_arial" name="saldo_hutang" required tabindex="5" disabled="" >
              </div>
            </div>   
          </div>
          
          <div class="col-sm-4">
            <div class="form-group row" style="margin-top: 12px">
              <label for="tgl_tran" class="col-sm-4 col-form-label" style="text-align: right"><b>Tgl.Angsur</b></label>
              <div class="col-sm-8">
                <input id="tgl_tran" style="border: 1px solid black;font-size: 10pt;" type="date" class="form-control hrf_arial" name="tgl_tran" required tabindex="3">
              </div>
            </div>  
            <div class="form-group row" style="margin-top: -10px">
              <label for="byr_hutang" class="col-sm-4 col-form-label" style="text-align: right"><b>Bayar Hutang</b></label>
              <div class="col-sm-8">
                <input id="byr_hutang" style="border: 1px solid black;font-size: 10pt;text-align: right" type="text" class="form-control hrf_arial money" name="byr_hutang" required tabindex="6" >
              </div>
            </div>   
            <div class="form-group row" style="margin-top: -10px">
              <label for="via" class="col-sm-4 col-form-label" style="text-align: right"><b>Keterangan</b></label>
                <div class="col-sm-8">
                  <input id="via" style="border: 1px solid black;font-size: 10pt;" type="text" class="form-control" name="via" required tabindex="6" >
                </div>
            </div>    
          </div>
          <div class="col-sm-4">
          </div>
          <div class="col-sm-4">
            
          </div>  
          <div class="col-sm-4">
            
          </div>  
        </div> <!-- row --> 

        <div class="row">
          <div class="col-sm-4">
            <button type="button" onclick="kosongkan()" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom btn-warning"><i class="fa fa-undo">&nbsp;&nbsp;</i><b>R E S E T</b></button>
          </div>
          <div class="col-sm-4">
            <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 10pt;" class="w3-margin-bottom w3-card-2 yz-theme-l1"><i class="fa fa-save">&nbsp;&nbsp;</i><b>S I M P A N</b></button>
          </div>
          <div class="col-sm-4">
            <a id="linkcetak" type="button" target="_blank" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;font-size: 11pt; text-align: center" class="w3-margin-bottom yz-theme-d1 w3-card-2"><i class="fa fa-print">&nbsp;&nbsp;</i><b>C E T A K</b></a>
          </div>
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
                  kosong_save();carihutang(1,true);
                  ceksaldo(document.getElementById('no_fak').value);
              }
          })
          return false;
        });
      })
    </script> 
    <?php 
     //jika pembayaran dari pesan jatuh tempo
      if(isset($_GET['bayarhut'])){
        $pesanedit=mysqli_real_escape_string($connect,$_GET['bayarhut']);
        $x=explode(';', $pesanedit);
          $no_fakedit=$x[0];
          $tgl_fakedit=$x[1];    
        ?>
          <script>
             document.getElementById('no_fak').value='<?=$no_fakedit?>';
            // document.getElementById('tgl_fak').value='<?=$tgl_fakedit?>';
            // document.getElementById('no_fak').focus();
            document.getElementById('byr_hutang').focus();
            ceksaldo('<?=$no_fakedit?>');
            carihutang(1,true);
          </script>
        <?php  
      } else {
        ?>
        <script>carihutang(1,true);</script>
        <?php
      }
    ?>
    <div id="viewcek"></div> 
    <div style="margin-top: -12px">
      <div id="listcarihutang"></div>
    </div>

    <!-- Form nota-->
    <div id="fnota" class="w3-modal" style="padding-top:50px;border:2px solid white;background-color: rgba(1, 1, 1,0);">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom w3-border" style="max-width:850px;border-radius:5px;">

        <div style="background: linear-gradient(165deg, #4e1358 20%, magenta 60%, white 80%);border:ridge;color:white;">&nbsp;<i class="fa fa-search"></i>
          Cari No Faktur Pembelian
        </div>

        <!-- <div class="w3-margin-top w3-container w3-text-white">
        <i class="fa fa-search "></i>&nbsp;Cari No Faktur Pembelian
        <hr class="w3-yellow">
        </div> -->
        <div class="w3-center">
          <span onclick="document.getElementById('fnota').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <input id="cari_nofak" type="hidden">             
        <div class="modal-body">
          <div class="w3-row">
            <div class="w3-col l2 s5 m12">
              <label for="cari_lunas" style="font-size: 11pt">Pilih Kondisi:</label>    
            </div>
            <div class="w3-col l4 s7 m12">
              <select name="cari_lunas" id="cari_lunas" class="form-control" style="font-size: 11pt" onchange="carinofak(1,true);">
                <option value="AND beli_bay.saldo_hutang>0">Belum Lunas</option>
                <option value="AND beli_bay.saldo_hutang=0">Lunas</option>
                <option value="">Semua</option>
              </select>
            </div>
          </div>
          <div id="listnofak"><script>carinofak(1,true);</script></div>
        </div> <!--Modal-body-->
      </div><!--Modal content-->
    </div>
    <!-- End Form Nota -->
  </body>
</div>
