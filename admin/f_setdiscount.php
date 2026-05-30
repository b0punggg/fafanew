<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/keranjang.png">
  <title>Setting Discount Otomatis</title>
</head>
<body>
  
  <div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
  <?php 
   include 'starting.php';
   $kd_toko=$_SESSION['id_toko'];
   $connect=opendtcek();
  ?>

  <div id="main">
    <script>
      function simpanRule() {
        var formData = {
          nama_rule: $("#nama_rule").val(),
          kondisi: $("#kondisi").val(),
          nilai_kondisi: $("#nilai_kondisi").val(),
          disc_rupiah: $("#disc_rupiah_rule").val() || 0,
          disc_persen: $("#disc_persen_rule").val() || 0,
          status: $("#status_rule").val(),
          kd_toko: '<?php echo $kd_toko; ?>'
        };
        
        if (!formData.nama_rule || !formData.kondisi || !formData.nilai_kondisi) {
          alert("Lengkapi semua field!");
          return;
        }
        
        $.ajax({
          url: 'f_setdiscount_act.php',
          type: 'POST',
          data: formData,
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response) {
            if (response.status == "success") {
              alert(response.message);
              loadRules();
              $("#formRule")[0].reset();
            } else {
              alert(response.message);
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert("Error: " + xhr.responseText);
          }
        });
      }
      
      function loadRules() {
        $.ajax({
          url: 'f_setdiscount_list.php',
          type: 'POST',
          data: {kd_toko: '<?php echo $kd_toko; ?>'},
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response) {
            $("#listRules").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert("Error: " + xhr.responseText);
          }
        });
      }
      
      function hapusRule(no_urut) {
        if (confirm("Hapus rule discount ini?")) {
          $.ajax({
            url: 'f_setdiscount_hapus.php',
            type: 'POST',
            data: {no_urut: no_urut, kd_toko: '<?php echo $kd_toko; ?>'},
            dataType: "json",
            success: function(response) {
              if (response.status == "success") {
                alert(response.message);
                loadRules();
              } else {
                alert(response.message);
              }
            },
            error: function (xhr, ajaxOptions, thrownError) {
              alert("Error: " + xhr.responseText);
            }
          });
        }
      }
      
      function toggleStatus(no_urut, status) {
        var newStatus = status == '1' ? '0' : '1';
        $.ajax({
          url: 'f_setdiscount_toggle.php',
          type: 'POST',
          data: {no_urut: no_urut, status: newStatus, kd_toko: '<?php echo $kd_toko; ?>'},
          dataType: "json",
          success: function(response) {
            if (response.status == "success") {
              loadRules();
            } else {
              alert(response.message);
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert("Error: " + xhr.responseText);
          }
        });
      }
      
      function applyAutoDiscount() {
        if (confirm("Terapkan discount otomatis ke semua barang yang sesuai dengan rules aktif?")) {
          $.ajax({
            url: 'f_discount_auto_apply.php',
            type: 'POST',
            data: {kd_toko: '<?php echo $kd_toko; ?>'},
            dataType: "json",
            beforeSend: function() {
              $("#btnApply").prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> Memproses...");
            },
            success: function(response) {
              $("#btnApply").prop("disabled", false).html("<i class='fa fa-check'></i> Terapkan Discount");
              if (response.status == "success") {
                alert(response.message);
              } else {
                alert(response.message);
              }
            },
            error: function (xhr, ajaxOptions, thrownError) {
              $("#btnApply").prop("disabled", false).html("<i class='fa fa-check'></i> Terapkan Discount");
              alert("Error: " + xhr.responseText);
            }
          });
        }
      }
      
      $(document).ready(function() {
        loadRules();
      });
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
        }
      } 
    ?>
    
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, darkblue 0%, cyan 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
        <i class='fa fa-cog' style="font-size: 18px;color:whitesmoke">&nbsp;SETTING DISCOUNT OTOMATIS &nbsp;</i> <i class='fa fa-angle-double-right w3-text-white'></i>&nbsp;<span style="font-size: 18px;color:white">Atur Discount Otomatis</span>
    </div>
    
    <div id="inputdata" style="border-style: ridge;border-color: white; margin-top: 10px;">
      <div class="w3-row">
        <div class="w3-col m12 l12">
          <div class="w3-container" style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;border-style: ridge;border-color: white">
            <a href="#" class="w3-text-white"><i class="fa fa-plus-circle" style="color:orange"></i>&nbsp; Tambah Rule Discount</a>
          </div>
          <div class="w3-container w3-card" style="border-style: ridge;border-color: white; padding: 20px;">
            <form id="formRule">
              <div class="w3-row">
                <div class="w3-col s12 m6 l4">
                  <div class="form-group">
                    <label class="hrf_arial"><b>Nama Rule</b></label>
                    <input type="text" id="nama_rule" class="form-control hrf_arial" required style="border: 1px solid black;" placeholder="Contoh: Discount Brand SKINTIFIC">
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l3">
                  <div class="form-group">
                    <label class="hrf_arial"><b>Kondisi</b></label>
                    <select id="kondisi" class="form-control hrf_arial" required style="border: 1px solid black;">
                      <option value="">Pilih Kondisi</option>
                      <option value="nama_brg">Nama Barang Mengandung</option>
                      <option value="kd_brg">Kode Barang Mengandung</option>
                      <option value="harga_min">Harga Jual >=</option>
                      <option value="harga_max">Harga Jual <=</option>
                      <option value="stok_min">Stok >=</option>
                      <option value="stok_max">Stok <=</option>
                    </select>
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l2">
                  <div class="form-group">
                    <label class="hrf_arial"><b>Nilai Kondisi</b></label>
                    <input type="text" id="nilai_kondisi" class="form-control hrf_arial" required style="border: 1px solid black;" placeholder="Nilai">
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l1.5">
                  <div class="form-group">
                    <label class="hrf_arial"><b>Disc Rupiah</b></label>
                    <input type="number" id="disc_rupiah_rule" class="form-control hrf_arial" value="0" min="0" step="0.01" style="border: 1px solid black;">
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l1.5">
                  <div class="form-group">
                    <label class="hrf_arial"><b>Disc %</b></label>
                    <input type="number" id="disc_persen_rule" class="form-control hrf_arial" value="0" min="0" max="100" step="0.01" style="border: 1px solid black;">
                  </div>
                </div>
              </div>
              
              <div class="w3-row">
                <div class="w3-col s12 m6 l3">
                  <div class="form-group">
                    <label class="hrf_arial"><b>Status</b></label>
                    <select id="status_rule" class="form-control hrf_arial" style="border: 1px solid black;">
                      <option value="1">Aktif</option>
                      <option value="0">Nonaktif</option>
                    </select>
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l9" style="padding-top: 25px;">
                  <button type="button" onclick="simpanRule()" class="btn btn-primary hrf_arial">
                    <i class="fa fa-save"></i> Simpan Rule
                  </button>
                  <button type="button" onclick="applyAutoDiscount()" id="btnApply" class="btn btn-success hrf_arial" style="margin-left: 10px;">
                    <i class="fa fa-check"></i> Terapkan Discount
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <div class="w3-row" style="margin-top: 20px;">
        <div class="w3-col m12 l12">
          <div class="w3-container" style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;border-style: ridge;border-color: white">
            <a href="#" class="w3-text-white"><i class="fa fa-list" style="color:orange"></i>&nbsp; Daftar Rules</a>
          </div>
          <div class="w3-container w3-card" style="border-style: ridge;border-color: white; max-height: 500px; overflow-y: auto;">
            <table class="table table-bordered table-striped" style="font-size: 12px;">
              <thead>
                <tr style="background-color: #4CAF50; color: white;">
                  <th>No</th>
                  <th>Nama Rule</th>
                  <th>Kondisi</th>
                  <th>Nilai</th>
                  <th>Disc Rupiah</th>
                  <th>Disc %</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="listRules">
                <tr>
                  <td colspan="8" style="text-align: center; padding: 20px;">
                    <i class="fa fa-spinner fa-spin"></i> Memuat data...
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

