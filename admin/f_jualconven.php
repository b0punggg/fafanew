<!DOCTYPE html>
<html lang="id">
  <?php 
   include 'cekmasuk.php';
   include 'config.php';
   setlocale(LC_MONETARY , "ID");
   $kd_toko=$_SESSION['id_toko'];
   $id_user=$_SESSION['id_user'];
  ?> 
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/keranjang.png">
    <title>Toko Retail</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">  
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/indigo-theme.css">
    <link rel="stylesheet" href="../assets/css/alertyaz.min.css">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway|Cinzel Decorative&effect=outline|emboss">   -->
    <script src="../assets/js/alertyaz.min.js"></script>
    <script type="text/javascript" src="../assets/js/jquery-3.3.1.min.js"></script> 
    <script type="text/javascript" src="../assets/js/jquery.mask.min.js"></script> 
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
  </head>
  <style>
    body,h2,h3,h4,h5,h6 {font-family: "Helvetica", arial}
    body, html {
       /*height: 100%;*/
       line-height: 1.8;
       /*margin-top: 25px;*/
    }
    .hrf_cinzel {font-family: "Cinzel Decorative", Helvetica;}
    .hrf_arial {font-family: Arial, Helvetica;}
    .hrf_helv {font-family: Helvetica, arial;}
    .customb {
      border: 1px solid black;
      width:29px;height: 28px;
      border-radius: 4px;
      cursor: pointer;
      box-shadow: 0px 0px 2px
    }
  </style>
  <body onkeydown="tekantombol()">
    <div id="main" style="font-size: 10pt;background: linear-gradient(565deg, #E6E6FA 0%, white 80%)">
      <script>  
        
        function carikd_bar(){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_jual_carikdbar.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {kd_bar:$("#kd_bar").val(),
          no_fakjual:$("#no_fakjual").val(),
          tgl_jual:$("#tgl_fakjual").val(),
          tgl_jt:$("#tgl_jt").val(),
          kd_bayar:$("#cr_bay").val(),
          kd_pel:$("#kd_pel").val(),
          no_urutjual:$("#no_urutjual").val()
          }, 
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
        
        function carinmbrg(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
          $.ajax({
            url: 'f_jual_caribrg.php', // File tujuan
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
              
              $("#viewnmbrg").html(response.hasil);
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
              alert(xhr.responseText); // munculkan alert
            }
          });
        }

        function caribrgjual(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_jualcari.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:$("#no_fakjual").val()+';'+$("#tgl_fakjual").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            
            $("#viewbrgjual").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
        } 

        function carisatbrg(){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_jual_carisat.php', // File tujuan
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
            
            $("#tabkem").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
        } 

        function cekjmlstok(kdsatuan,kdbrg){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_jual_cekstok.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword1:kdsatuan, keyword2: kdbrg}, 
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

        function carinmpel(){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_jualcarinmpel.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:$("#no_fakjual").val()}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            
            $("#viewnmpel").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
        }

        function cariidpel(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_cariidpel.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {keyword:$("#kd_pel").val(), page: page_number, search: search}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            
            $("#viewidpel").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
        }

        function bayarcariidpel(page_number, search){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
          $.ajax({
            url: 'f_jualcariidpel.php', // File tujuan
            type: 'POST', // Tentukan type nya POST atau GET
            data: {keyword:$("#nm_pel_byr").val(), page: page_number, search: search}, 
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response){ 
              // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
              
              $("#viewidpelbayar").html(response.hasil);
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
              alert(xhr.responseText); // munculkan alert
            }
          });
        } 
        
        function tekantombol(){  
          if (event.keyCode==118) {
            event.preventDefault()
            document.getElementById('kd_bar').focus();
            // scan bar code focus
          }
          if (event.keyCode==119) {
            event.preventDefault()
            document.getElementById('kd_brg').focus();
            // kd_brg focus
          }
          if (event.keyCode==120) {
            event.preventDefault()
            document.getElementById('tmb-add').click();
            // kd_brg focus
          }
          if (event.keyCode==121) {
            event.preventDefault()
            document.getElementById('tmb-bayar').click();
            // kd_brg focus
          }
        }
      
      function hapusnota(){
        // $(this).html("ketik pencarian").attr("disabled", "disabled");
        
        $.ajax({
          url: 'f_jualhapusnota_act.php', // File tujuan
          type: 'POST', // Tentukan type nya POST atau GET
          data: {no_fakjual:$("#no_fakjual").val()}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response){ 
            // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
            $("#viewhapusnota").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
            alert(xhr.responseText); // munculkan alert
          }
        });
      }
      
      $(document).ready(function(){
        $( '.idsup' ).mask('IDPEM-00000000');
        $( '.telp' ).mask('0000 00000000000');
        $( '.hp' ).mask('000 00000000000');
        $( '.uang' ).mask('000.000.000.000.000', {reverse: true});
        $('.desimal').mask('00.00', {reverse: true});
        $('.angka').mask('000000', {reverse: true});
      });  
      </script> 

    <?php       
      function ceknota($kd,$hub,$id_user) {
        
        $cek=mysqli_query($hub,"SELECT dum_jual.no_fakjual,dum_jual.bayar,dum_jual.kd_pel,dum_jual.id_user,dum_jual.nm_user,pelanggan.nm_pel,pelanggan.al_pel FROM dum_jual 
          LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel WHERE id_user='$id_user' ORDER BY dum_jual.no_urut DESC limit 1");
        $data=mysqli_fetch_array($cek); 
        $no=0;$x='';
        if(mysqli_num_rows($cek)>=1)
        {
          $x=explode('-',mysqli_escape_string($hub,$data['no_fakjual']));
          if($data['bayar']=='SUDAH')
          {
           $no=$x[1]+1;  
          }else{
           $no=$x[1];   
          } 
          $kd_pel=mysqli_escape_string($hub,$data['kd_pel']);
        }else{
          $no=1;
        }  
        
        $no_fakjual='NFJ'.'.'.$id_user.'.'.date('d').date('m').date('y').'-'.$no; 
        return $no_fakjual;                 
        // mysqli_close($connect3);
        
      }

      $tglhi=date('Y-m-d');
      $cek=mysqli_query($connect,"SELECT dum_jual.no_fakjual,dum_jual.bayar,dum_jual.kd_pel,pelanggan.nm_pel,pelanggan.al_pel FROM dum_jual 
          LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel WHERE kd_toko='$kd_toko' order by dum_jual.no_urut DESC limit 1");
      $data=mysqli_fetch_array($cek); 
      $kd_pel='';$nm_pel='';$al_pel='';
      $kd_pel=mysqli_escape_string($connect,$data['kd_pel']);
      unset($cek,$data);  

      $xjual=0;$xdisc=0;$tot_omset=0;$tot_laba=0;
      $sql = mysqli_query($connect, "SELECT * FROM dum_jual WHERE tgl_jual='$tglhi' AND kd_toko='$kd_toko'");
      if(mysqli_num_rows($sql)>=1){  
        while($cek=mysqli_fetch_array($sql)){
          if($cek['discitem']>0){
            $xdisc=($cek['discitem']/100)*$cek['hrg_jual'];
            $xjual=$cek['hrg_jual']-$xdisc;
          }else{$xjual=$cek['hrg_jual'];}
          $tot_omset=$tot_omset+($xjual*$cek['qty_brg']);
          $tot_laba=$tot_laba+$cek['laba'];
        }
      }
      unset($sql,$cek);
      
      $sql = mysqli_query($connect, "SELECT sum(qty_brg) AS jumlah FROM dum_jual WHERE tgl_jual='$tglhi' AND kd_toko='$kd_toko'");          
      $get_jumlah = mysqli_fetch_array($sql);
      $brg_keluar = mysqli_escape_string($connect,$get_jumlah['jumlah']);
      unset($sql,$get_jumlah);
    ?>

      <!-- tulis default field -->
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
            <script>popnew_error("Simpan gagal");</script>
          <?php
        }else if($pesan=="hapusupdate"){
          ?>
          <script>popnew_error("Data terupdate, silahkan utk melakukan proses bayar nota kembali");</script>
          <?php
        }
      } 
      ?>

      <div class="w3-padding-small" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);z-index: 1; ">
        <div class="w3-row">
          <div class="w3-col m3 w3-hide-small">
            <h5 style="margin-top: 7px"><i class='fa fa-cart-arrow-down'></i> &nbsp;TRANSAKSI &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Penjualan Barang</span></h5> 
          </div>
          <div class="w3-col s4 m7">
            <a href="#" id="tmb-info" class="btn w3-text-black w3-right w3-hover-shadow">
              <span><?=gantitgl(date('Y-m-d'));?>&nbsp;<i class="fa fa-database"></i></span>
              <span id="not_klr" class="w3-badge w3-tiny" style="position: absolute;top: 0px;background-color: blue;  color: white;"><?=$brg_keluar?></span>
              <div class="w3-container w3-card-4" id="info"  style="z-index: 1;display: none;position: absolute;top:45px;background: linear-gradient(140deg,#E6E6FA 0%, white 80%)">
                  <table class="table table-bordered table-sm table-hover w3-margin-top" style="font-size:10pt; background-color:;">
                    <tr><td><img src="img/krj.png" alt="">&nbsp; Item barang terjual  </td><td align="right"><?=$brg_keluar?></td></tr>    
                    <tr><td><img src="img/dmp.png" alt="">&nbsp; Total Omset Rp.      </td><td align="right"><?=gantiti($tot_omset)?></td></tr>    
                    <tr><td><img src="img/dlr.png" alt="">&nbsp; Total Laba  Rp.      </td><td align="right"><?=gantiti($tot_laba)?></td></tr>    
                  </table>
                </div>
            </a>
              <script>
                  $(document).ready(function(){
                    $("#tmb-info").click(function(){
                      $("#info").slideToggle("fast");
                      // $("#boxkem").slideUp("fast");
                    });  
                    $("#tmb-info").blur(function(){
                      $("#info").slideUp("fast");
                      // $("#boxkem").slideUp("fast");
                    });  
                  });
              </script>
            <!-- </div> -->
          </div>
          <div class="w3-col s8 m2">
             <a href="dasbor.php" class="w3-right w3-text-black btn w3-hover-shadow w3-wide" style="margin-top: 0px"><i class="fa fa-desktop"></i> Exit</a> 
          </div>
        </div>
        
      </div>
        
        <form id="form1" action="f_jual_act.php" method="post" style="font-size: 12px"> 
          <div class="w3-row">
            <div class="w3-col s12 m12 l6 w3-container" style="border-style: ridge;border-color: white">
              <div class="w3-row">
                <div class="w3-col m6 l6">

                  <div class="form-group row w3-margin-top">
                    <input type="hidden" name="no_urutjual" id="no_urutjual">
                    <label for="tgl_fakjual" class="col-sm-3 col-form-label" ><b>Tanggal</b></label>
                    <div class="col-sm-8">
                      <input id="tgl_fakjual" onblur="caribrgjual(1,true);carinmpel();"  style="border: 1px solid black;font-size: 12px" type="date" class="form-control hrf_arial" name="tgl_fakjual" value="<?php echo date('Y-m-d'); ?>" required autofocus tabindex="1" >
                    </div>
                  </div> 

                  <div class="form-group row "style="margin-top: -10px">
                    <label for="tgl_fakjual" class="col-sm-3 col-form-label" ><b>Fak-Jual</b></label>
                    <div class="col-sm-8">
                      <input id="no_fakjual" onblur="caribrgjual(1,true);carinmpel();" style="border: 1px solid black;font-size: 12px" type="text" class="form-control hrf_arial" name="no_fakjual" value="<?=ceknota($kd_toko,$connect,$id_user)?>" required tabindex="2" >
                    </div>
                  </div>    
                </div><!-- Col-sm-3 -->

                <div class="w3-col m6 l6" >
                  <div class="form-group row w3-margin-top">
                    <script>
                     carinmpel(); 
                    </script>
                    <label for="kd_pel" class="col-sm-4 col-form-label "><b>ID.Pelanggan</b></label>
                    <div class="col-sm-8" >
                      <div id='viewnmpel'></div>
                      <input id="kd_pel" name="kd_pel" onkeyup="cariidpel(1, true);" type="text" class="form-control hrf_arial" required style="border: 1px solid black;font-size: 11px" tabindex="3">
                      <div class="row">
                      <div id="viewidpel" class="w3-card-4" style="background-color: white;display: none;position: absolute;z-index: 1;width: 450px;font-size: 11px"><script>cariidpel(1,true)</script></div>
                      </div>
                    </div>  
                    <!-- <label for="nm_pel" class="col-sm-3 col-form-label "><b>Nama</b></label>
                    <div class="col-sm-8">
                      <input type="text" id="nm_pel" class="form-control" style="border:none;background-color: transparent;font-size: 11px" readonly=""> 
                    </div> 
                    
                    <label for="al_pel" class="col-sm-3 col-form-label"><b>Alamat</b></label>
                    <div class="col-sm-8">
                      <input type="text" id="al_pel" class="form-control" style="border:none;background-color: transparent;font-size: 11px" readonly="">
                    </div>  -->
                  </div>          
                  <script>
                    $(document).ready(function(){
                      $("#kd_pel").click(function(){
                        $("#viewidpel").slideToggle("fast");
                        $("#viewnmbrg").slideUp("fast");
                        $("#tabkem").slideUp("fast");
                      });
                      $("#viewidpel").mouseleave(function(){
                        $("#viewidpel").slideUp("fast");
                      });
                      $("#kd_pel").keyup(function(){
                        $("#viewidpel").slideDown("fast");
                      });
                    });
                  </script>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="cr_bay" class="col-sm-4 col-form-label "><b>Pembayaran</b></label>
                    <div class="col-sm-8" >
                      <select class="form-control" name="cr_bay" id="cr_bay" style="border: 1px solid black;font-size:12px ;height: 30px" required="" tabindex="4">
                        <option value="TUNAI">TUNAI</option>
                        <option value="TEMPO">TEMPO</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tgl_jt" class="col-sm-4 col-form-label "><b>Tgl.Tempo</b></label>
                    <div class="col-sm-8" >
                      <input id="tgl_jt" style="border: 1px solid black;font-size: 12px" type="date" class="form-control hrf_arial w3-margin-bottom" name="tgl_jt" tabindex="5" >
                    </div>
                  </div>
                <!--   <script>
                    if (document.getElementById('cr_bay').value=='TEMPO'){document.getElementById('tgl_jt').removeAttribute('disabled',true);}else{document.getElementById('tgl_jt').setAttribute('disabled',true);}
                  </script> -->
                </div><!-- Col-sm-3 -->
              </div>
            </div>
            <div class="w3-col s12 m12 l6" style="border-style: ridge;border-color: white">
              <div class="w3-row w3-container">
                <div class="w3-col s12 m12 l12 hrf_arial">
                  <div id="angka_bay1" class="w3-xxlarge w3-hide-medium w3-hide-large">
                  </div>
                  <div id="angka_bay2" class="w3-jumbo w3-hide-small" style="text-align: center;margin-top: -4px">
                  </div>
                  
                  <div id="keterangan1" class="hrf_helv w3-hide-small w3-margin-bottom" style="font-size: 14px;text-align: center">
                  </div>
                  <div id="keterangan2" class="hrf_helv w3-hide-medium w3-hide-large w3-margin-bottom" style="font-size: 14px">
                  </div>
                </div><!-- Col-sm-6 -->
              </div>
            </div>
                
          </div><!-- row -->

          <!-- tombol -->
          <div class="w3-row w3-section">
            <div class="w3-col s12 m6 l2 w3-margin-bottom w3-container">
              <input id="kd_bar" name="kd_bar" onkeypress="if(event.keyCode==13){carikd_bar();}" type="text" class="form-control hrf_arial" placeholder="Barcode [F7]"  style="border: 1px solid black;font-size: 11px" tabindex="6">
              <div id="viewkdbar"></div>
            </div>

            <div class="w3-col s12 m6 l2 w3-margin-bottom w3-container">
              <input id="kd_brg" name="kd_brg" onkeyup="carinmbrg(1, true);" onkeydown="
              if(event.keyCode==40){document.getElementById('viewnmbrg').style.display='block';} if(event.keyCode==38){document.getElementById('viewnmbrg').style.display='none';}"  type="text" class="form-control hrf_arial" placeholder="Kd.Barang [F8]" required  style="border: 1px solid black;font-size: 11px" tabindex="7">
              <div class="w3-row">
                 <div id="viewnmbrg" class="w3-card-4" style="background-color: white;display: none;position: absolute;z-index: 1;width: 700px;"><script>carinmbrg(1,true)</script></div>
              </div>
              <script>
                $(document).ready(function(){
                  $("#kd_bar").focus(function(){
                    $("#viewnmbrg").slideUp("fast");
                    $("#tabkem").slideUp("fast");
                    $("#viewidpel").slideUp("fast");
                  });
                  $("#kd_brg").click(function(){
                    $("#viewnmbrg").slideToggle("fast");
                    $("#tabkem").slideUp("fast");
                    $("#viewidpel").slideUp("fast");
                  });
                  // $("#kd_brg").keyup(function(){
                  //   $("#viewnmbrg").slideDown("fast");
                  // });
                  $("#viewnmbrg").mouseleave(function(){
                    $("#viewnmbrg").slideUp("fast");
                  });
                  $("#kd_brg").focus(function(){
                    $("#tabkem").slideUp("fast");
                    $("#viewidpel").slideUp("fast");
                  });
                });
              </script> 
            </div>
            <div class="w3-col s4 m4 l1 w3-margin-bottom w3-container">
              <input id="nm_sat" onkeyup="carisatbrg(1,true);" type="text" style="border: 1px solid black; font-size: 11px;" class="form-control hrf_arial" name="nm_sat" required="" placeholder="Satuan" tabindex="8">
              <input type="hidden" name="kd_sat" id="kd_sat">
              <!-- box search kemasan -->
               <div class="w3-row">
                 <div id="tabkem" class="w3-card-4" style="background-color: white;display: none;position: absolute;z-index: 1"><script>carisatbrg(1,true);</script></div>
               </div> 
                
                <script>
                  $(document).ready(function(){
                    $("#nm_sat").click(function(){
                      $("#tabkem").slideToggle("fast");
                      $("#viewnmbrg").slideUp("fast");
                      $("#viewidpel").slideUp("fast");
                    });
                    $("#nm_sat").keyup(function(){
                      $("#tabkem").slideDown("fast");
                    });
                    $("#tabkem").mouseleave(function(){
                      $("#tabkem").slideUp("fast");
                    });
                    $("#nm_sat").focus(function(){
                      $("#viewnmbrg").slideUp("fast");
                      $("#viewidpel").slideUp("fast");
                    });
                  });
                </script>
            </div>
            <div class="w3-col s4 m4 l1 w3-margin-bottom w3-container">
              <div id="viewjmlstok"></div>
              <input id="qty_brg"  name="qty_brg" type="number" class="form-control hrf_arial" required tabindex="9" style="border: 1px solid black;font-size: 11px" placeholder="jml.Brg">
            </div>
            <div class="w3-col s4 m4 l1 w3-margin-bottom w3-container">
              <input id="discitem" name="discitem" type="text" class="form-control hrf_arial desimal" required tabindex="10" style="border: 1px solid black;font-size: 11px" value="00.00" placeholder="Disc">
            </div>

            <div class="w3-col s4 m4 l1 w3-margin-bottom w3-container">
              <button id="tmb-add" type="submit" class="btn btn-primary hrf_arial w3-hover-shadow w3-card" tabindex="11" style="border: 1px solid black;font-size: 11px;width: 110px"><i class="fa fa-cart-plus"></i> ADD  &nbsp;[F9]</button>
            </div>
            <div class="w3-col s4 m4 l2 w3-margin-bottom w3-container">
              <button id="tmb-bayar" type="button" onclick="document.getElementById('form-bayar').style.display='block';document.getElementById('bayar').focus();document.getElementById('disctot').value='0'" class="btn btn-success hrf_arial w3-hover-shadow w3-card" tabindex="12" style="border: 1px solid black;font-size: 11px;width: 110px"><i class="fa fa-money"></i> BAYAR &nbsp; [F10]</button>
            </div>

            <div class="w3-col s4 m4 l1 w3-margin-bottom w3-container w3-hide-small w3-hide-medium">
              <button id="tmb-batal" type="button" class="tooltips w3-red customb" onclick="if(confirm('Nota akan dibatalkan??')){hapusnota();}" tabindex="13"><i class="fa fa-trash"></i><span class="tooltiptexts" style="color: black">Batalkan nota</span>
              </button>
            <!-- </div>  
            <div class="w3-col s1 m3 l3 w3-margin-bottom">   -->
              <button id="tmb-reset" type="button" class="tooltips w3-yellow customb" tabindex="14" onclick="
                        document.getElementById('kd_bar').value='';
                        document.getElementById('cr_bay').value='';
                        document.getElementById('kd_pel').value='';
                        document.getElementById('no_fakjual').value='<?=ceknota($kd_toko,$connect,$id_user)?>';
                        document.getElementById('tgl_fakjual').value='<?= $tglhi?>';
                        document.getElementById('kd_brg').value='';
                        document.getElementById('kd_sat').value='';
                        document.getElementById('nm_sat').value='';
                        document.getElementById('qty_brg').value='';
                        document.getElementById('discitem').value='';
                        document.getElementById('no_urutjual').value='';

                        document.getElementById('kd_bar').removeAttribute('disabled',true);
                        document.getElementById('cr_bay').removeAttribute('disabled',true);
                        document.getElementById('kd_pel').removeAttribute('disabled',true);
                        document.getElementById('no_fakjual').removeAttribute('disabled',true);
                        document.getElementById('tgl_fakjual').removeAttribute('disabled',true);
                        document.getElementById('tgl_jt').removeAttribute('disabled',true);
                        document.getElementById('kd_brg').removeAttribute('disabled',true);
                        carinmpel();caribrgjual(1,true);
                      "><i class="fa fa-undo"></i><span class="tooltiptexts">Mengosongkan input data</span>
              </button>
              <div id="viewhapusnota"></div> 
            </div>
            <div class="w3-col s4 m4 l3 w3-margin-bottom w3-container w3-hide-large">
              <button id="tmb-batal" type="button" class="w3-red customb" onclick="if(confirm('Nota akan dibatalkan??')){hapusnota();}" tabindex="13"><i class="fa fa-trash"></i>
              </button>
            <!-- </div>  
            <div class="w3-col s1 m3 l3 w3-margin-bottom">   -->
              <button id="tmb-reset" type="button" class="w3-yellow customb" tabindex="14" onclick="
                        document.getElementById('kd_bar').value='';
                        document.getElementById('cr_bay').value='';
                        document.getElementById('kd_pel').value='';
                        document.getElementById('no_fakjual').value='<?=ceknota($kd_toko,$connect,$id_user)?>';
                        document.getElementById('tgl_fakjual').value='<?= $tglhi?>';
                        document.getElementById('kd_brg').value='';
                        document.getElementById('kd_sat').value='';
                        document.getElementById('nm_sat').value='';
                        document.getElementById('qty_brg').value='';
                        document.getElementById('discitem').value='';
                        document.getElementById('no_urutjual').value='';

                        document.getElementById('kd_bar').removeAttribute('disabled',true);
                        document.getElementById('cr_bay').removeAttribute('disabled',true);
                        document.getElementById('kd_pel').removeAttribute('disabled',true);
                        document.getElementById('no_fakjual').removeAttribute('disabled',true);
                        document.getElementById('tgl_fakjual').removeAttribute('disabled',true);
                        document.getElementById('tgl_jt').removeAttribute('disabled',true);
                        document.getElementById('kd_brg').removeAttribute('disabled',true);
                        carinmpel();caribrgjual(1,true);
                      "><i class="fa fa-undo"></i>
              </button>
              <div id="viewhapusnota"></div> 
            </div>
          </div>
        </form>
        <div id="viewbrgjual" style="margin-top: -14px"><script>caribrgjual(1,true);</script></div>      
    </div><!-- Div.Main -->

  </body>
</html>  
    