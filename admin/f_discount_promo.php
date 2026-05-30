<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/keranjang.png">
  <title>Discount Promo</title>
</head>
<body>
  
  <div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
  <?php 
   include 'starting.php';
   // File ini dapat diakses oleh semua user termasuk operator (otoritas 1) dan administrator (otoritas 2)
   // Tidak ada pembatasan berdasarkan otoritas
   $kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
   $connect = opendtcek();
   
   // Pastikan kd_toko ada
   if (empty($kd_toko)) {
     echo '<div style="padding: 20px; text-align: center; color: red;"><i class="fa fa-exclamation-triangle"></i> Session tidak valid. Silakan login kembali.</div>';
     exit;
   }
   
   // Hapus promo yang periode sudah berakhir secara otomatis
   $jumlah_dihapus = hapusPromoBerakhir($connect, $kd_toko);
   
   // Generate nomor promo
   $tahun = date('y');
   $bulan = date('m');
   $max_no = 0;
   
   // Cek apakah tabel sudah ada, jika belum skip query
   $table_check = mysqli_query($connect, "SHOW TABLES LIKE 'disc_promo'");
   if (mysqli_num_rows($table_check) > 0) {
     $query_no = mysqli_query($connect, "SELECT MAX(CAST(SUBSTRING(no_promo, 10) AS UNSIGNED)) as max_no FROM disc_promo WHERE no_promo LIKE 'JS-DIS $tahun$bulan.%' AND kd_toko='$kd_toko'");
     if ($query_no) {
       $data_no = mysqli_fetch_assoc($query_no);
       if ($data_no && isset($data_no['max_no'])) {
         $max_no = intval($data_no['max_no']);
       }
     }
   }
   
   $no_urut = $max_no + 1;
   $no_promo = "JS-DIS $tahun$bulan." . str_pad($no_urut, 4, '0', STR_PAD_LEFT);
  ?>

  <div id="main">
    <script>
      // Sembunyikan loader setelah halaman selesai dimuat
      $(document).ready(function() {
        $(".loader1").fadeOut();
        // Pastikan list promo dimuat saat halaman siap
        loadListPromo(1);
      });
      
      function loadBarang() {
        var by_nama = $("#by_nama").val();
        var disc_rupiah = $("#disc_rupiah").val() || 0;
        var disc_persen = $("#disc_persen").val() || 0;
        
        if (!by_nama) {
          alert("Masukkan nama brand/kategori terlebih dahulu");
          return;
        }
        
        $.ajax({
          url: 'f_discount_promo_caribrg.php',
          type: 'POST',
          data: {
            by_nama: by_nama,
            disc_rupiah: disc_rupiah,
            disc_persen: disc_persen,
            kd_toko: '<?php echo $kd_toko; ?>'
          },
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response) {
            $("#listbarang").html(response.hasil);
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert("Error: " + xhr.responseText);
          }
        });
      }
      
      function hapusBarang(no_urut) {
        if (confirm("Hapus barang dari list?")) {
          $("#row_" + no_urut).remove();
        }
      }
      
      function loadListPromo(page) {
        page = page || 1;
        // Tampilkan loading indicator
        $("#listpromo").html('<div style="text-align: center; padding: 20px; color: #999;"><i class="fa fa-spinner fa-spin"></i> Memuat data promo...</div>');
        
        $.ajax({
          url: 'f_discount_promo_list.php',
          type: 'POST',
          data: { 
            page: page,
            _t: new Date().getTime() // Cache busting
          },
          dataType: "json",
          cache: false, // Disable cache
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response) {
            if (response && response.hasil) {
              $("#listpromo").html(response.hasil);
            } else {
              console.error("Invalid response:", response);
              $("#listpromo").html('<div class="empty-state"><i class="fa fa-exclamation-triangle"></i><div class="empty-state-text">Gagal memuat data promo</div></div>');
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.error("Error loading promo list:", xhr.responseText);
            $("#listpromo").html('<div class="empty-state"><i class="fa fa-exclamation-triangle"></i><div class="empty-state-text">Error: ' + xhr.statusText + '</div></div>');
          }
        });
      }
      
      function hapusPromo(no_promo) {
        if (!confirm("Yakin ingin menghapus promo " + no_promo + "?")) {
          return;
        }
        
        $.ajax({
          url: 'f_discount_promo_hapus.php',
          type: 'POST',
          data: { no_promo: no_promo },
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response) {
            if (response.status == "success") {
              alert(response.message);
              loadListPromo(1);
            } else {
              alert(response.message);
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert("Error: " + xhr.responseText);
          }
        });
      }
      
      function togglePromo(promoId) {
        var content = document.getElementById(promoId);
        if (!content) return;
        
        var item = document.getElementById('item_' + promoId);
        var chevron = document.getElementById('chevron_' + promoId);
        
        if (content.classList.contains('show')) {
          content.classList.remove('show');
          if (item) item.classList.remove('expanded');
          if (chevron) chevron.style.transform = 'rotate(0deg)';
        } else {
          content.classList.add('show');
          if (item) item.classList.add('expanded');
          if (chevron) chevron.style.transform = 'rotate(90deg)';
        }
      }
      
      function simpanData(aksi) {
        var formData = {
          no_promo: $("#no_promo").val(),
          nama_promo: $("#nama_promo").val(),
          tgl_awal: $("#tgl_awal").val(),
          tgl_akhir: $("#tgl_akhir").val(),
          disc_rupiah: $("#disc_rupiah").val() || 0,
          disc_persen: $("#disc_persen").val() || 0,
          by_nama: $("#by_nama").val(),
          aksi: aksi,
          kd_toko: '<?php echo $kd_toko; ?>'
        };
        
        // Collect all items - kirim sebagai JSON string untuk menghindari max_input_vars limit
        var items = [];
        
        $("tr[id^='row_']").each(function() {
          var kd_brg = $(this).find("input[name='kd_brg[]']").val();
          var disc_rupiah_item = $(this).find("input[name='disc_rupiah_item[]']").val() || 0;
          var disc_persen_item = $(this).find("input[name='disc_persen_item[]']").val() || 0;
          
          if (kd_brg) {
            items.push({
              kd_brg: kd_brg,
              disc_rupiah: disc_rupiah_item,
              disc_persen: disc_persen_item
            });
          }
        });
        
        // Kirim items sebagai JSON string untuk menghindari max_input_vars limit
        formData.items = JSON.stringify(items);
        
        if (!formData.nama_promo || !formData.tgl_awal || !formData.tgl_akhir) {
          alert("Lengkapi data promo terlebih dahulu!");
          return;
        }
        
        if (items.length == 0) {
          alert("Tidak ada barang yang dipilih!");
          return;
        }
        
        $.ajax({
          url: 'f_discount_promo_act.php',
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
              // Jangan tampilkan alert dulu, reload list dulu
              // Reload list promo setelah simpan - tunggu sebentar untuk memastikan data tersimpan
              setTimeout(function() {
                loadListPromo(1);
                // Tampilkan alert setelah reload
                setTimeout(function() {
                  alert(response.message);
                }, 200);
              }, 500);
              
              if (aksi == "simpan_tutup") {
                // Untuk simpan_tutup, cukup reload list saja (tidak perlu reload halaman)
                // List akan tetap muncul karena sudah di-reload di atas
              } else {
                // Simpan & Baru: Reset form tanpa reload halaman
                // Clear form inputs
                $("#nama_promo").val("");
                $("#tgl_awal").val("");
                $("#tgl_akhir").val("");
                $("#disc_rupiah").val("0");
                $("#disc_persen").val("0");
                $("#by_nama").val("");
                $("#listbarang").html('<tr><td colspan="6" style="text-align: center; padding: 30px; color: #666;"><i class="fa fa-info-circle" style="font-size: 18px; margin-right: 5px;"></i> Klik "Load Barang" untuk memuat daftar barang</td></tr>');
                
                // Generate nomor promo baru via AJAX
                $.ajax({
                  url: 'f_discount_promo_getno.php',
                  type: 'GET',
                  dataType: 'json',
                  success: function(response) {
                    if (response.no_promo) {
                      $("#no_promo").val(response.no_promo);
                    }
                  }
                });
              }
            } else {
              alert(response.message);
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert("Error: " + xhr.responseText);
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
    
    <div class="w3-container w3-card" style="background: linear-gradient(165deg, darkblue 0%, cyan 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;padding: 10px 15px;">
        <i class='fa fa-percent' style="font-size: 18px;color:whitesmoke">&nbsp;DISCOUNT PROMO &nbsp;</i> <i class='fa fa-angle-double-right w3-text-white'></i>&nbsp;<span style="font-size: 18px;color:white">Setting Discount</span>
    </div>
    
    <!-- Form input data promo - Dapat diakses oleh semua user termasuk operator (otoritas 1) dan administrator (otoritas 2) -->
    <div id="inputdata" style="margin-top: 15px; margin-bottom: 15px;">
      <form id="form-inputan">
        <div class="w3-row">
          <div class="w3-col m12 l12">
            <div class="w3-container" style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;padding: 10px 15px;border-radius: 5px 5px 0 0;border: 1px solid #ddd;border-bottom: none;">
              <a href="#" class="w3-text-white"><i class="fa fa-tag" style="color:orange"></i>&nbsp; Data Promo</a>
            </div>
            <div class="w3-container w3-card" style="border: 1px solid #ddd;border-top: none;padding: 20px;border-radius: 0 0 5px 5px;background-color: #fff;">
              <div class="w3-row" style="margin-bottom: 15px;">
                <div class="w3-col s12 m6 l3" style="padding: 5px 10px;">
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="no_promo" class="hrf_arial" style="display: block;margin-bottom: 5px;font-weight: bold;color: #333;"><b>Nomor</b></label>
                    <input type="text" id="no_promo" name="no_promo" class="form-control hrf_arial" value="<?php echo $no_promo; ?>" readonly style="width: 100%;padding: 8px;border: 1px solid #ccc;background-color: #f5f5f5;border-radius: 3px;font-size: 14px;">
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l4" style="padding: 5px 10px;">
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="nama_promo" class="hrf_arial" style="display: block;margin-bottom: 5px;font-weight: bold;color: #333;"><b>Nama Promo</b></label>
                    <input type="text" id="nama_promo" name="nama_promo" class="form-control hrf_arial" required style="width: 100%;padding: 8px;border: 1px solid #ccc;border-radius: 3px;font-size: 14px;" placeholder="Nama Promo">
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l5" style="padding: 5px 10px;">
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="periode" class="hrf_arial" style="display: block;margin-bottom: 5px;font-weight: bold;color: #333;"><b>Periode</b></label>
                    <div style="display: flex;align-items: center;gap: 8px;">
                      <input type="date" id="tgl_awal" name="tgl_awal" class="form-control hrf_arial" required style="flex: 1;padding: 8px;border: 1px solid #ccc;border-radius: 3px;font-size: 14px;">
                      <span style="font-weight: bold;color: #333;white-space: nowrap;">s/d</span>
                      <input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control hrf_arial" required style="flex: 1;padding: 8px;border: 1px solid #ccc;border-radius: 3px;font-size: 14px;">
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="w3-row" style="margin-top: 10px;">
                <div class="w3-col s12 m6 l3" style="padding: 5px 10px;">
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="disc_rupiah" class="hrf_arial" style="display: block;margin-bottom: 5px;font-weight: bold;color: #333;"><b>Disc Rupiah</b></label>
                    <input type="number" id="disc_rupiah" name="disc_rupiah" class="form-control hrf_arial" value="0" min="0" style="width: 100%;padding: 8px;border: 1px solid #ccc;border-radius: 3px;font-size: 14px;" placeholder="0">
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l3" style="padding: 5px 10px;">
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="disc_persen" class="hrf_arial" style="display: block;margin-bottom: 5px;font-weight: bold;color: #333;"><b>Disc Persen</b></label>
                    <input type="number" id="disc_persen" name="disc_persen" class="form-control hrf_arial" value="0" min="0" max="100" style="width: 100%;padding: 8px;border: 1px solid #ccc;border-radius: 3px;font-size: 14px;" placeholder="0">
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l4" style="padding: 5px 10px;">
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label for="by_nama" class="hrf_arial" style="display: block;margin-bottom: 5px;font-weight: bold;color: #333;"><b>By Nama</b></label>
                    <input type="text" id="by_nama" name="by_nama" class="form-control hrf_arial" style="width: 100%;padding: 8px;border: 1px solid #ccc;border-radius: 3px;font-size: 14px;" placeholder="Nama Brand/Kategori">
                  </div>
                </div>
                
                <div class="w3-col s12 m6 l2" style="padding: 5px 10px;">
                  <div class="form-group" style="margin-bottom: 10px;">
                    <label class="hrf_arial" style="display: block;margin-bottom: 5px;font-weight: bold;color: transparent;"><b>&nbsp;</b></label>
                    <button type="button" onclick="loadBarang()" class="btn btn-primary hrf_arial" style="width: 100%;padding: 8px;border-radius: 3px;font-size: 14px;font-weight: normal;">
                      <i class="fa fa-search"></i> Load Barang
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="w3-row" style="margin-top: 20px;">
          <div class="w3-col m12 l12">
            <div class="w3-container" style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;padding: 10px 15px;border-radius: 5px 5px 0 0;border: 1px solid #ddd;border-bottom: none;">
              <a href="#" class="w3-text-white"><i class="fa fa-list" style="color:orange"></i>&nbsp; Daftar Barang</a>
            </div>
            <div class="w3-container w3-card" style="border: 1px solid #ddd;border-top: none;max-height: 500px;overflow-y: auto;border-radius: 0 0 5px 5px;background-color: #fff;">
              <table class="table table-bordered table-striped" style="font-size: 12px;margin-bottom: 0;width: 100%;">
                <thead>
                  <tr style="background-color: #4CAF50; color: white;">
                    <th style="padding: 10px;text-align: center;">No</th>
                    <th style="padding: 10px;">SKU</th>
                    <th style="padding: 10px;">Nama Barang</th>
                    <th style="padding: 10px;text-align: right;">Disc Rupiah</th>
                    <th style="padding: 10px;text-align: center;">Disc Persen</th>
                    <th style="padding: 10px;text-align: center;">Aksi</th>
                  </tr>
                </thead>
                <tbody id="listbarang">
                  <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #666;">
                      <i class="fa fa-info-circle" style="font-size: 18px; margin-right: 5px;"></i> Klik "Load Barang" untuk memuat daftar barang
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        
        <div class="w3-row" style="margin-top: 20px; margin-bottom: 20px;">
          <div class="w3-col m12 l12" style="text-align: center;padding: 10px;">
            <button type="button" onclick="simpanData('simpan_baru')" class="btn btn-success hrf_arial" style="margin-right: 10px;padding: 10px 25px;border-radius: 3px;font-weight: bold;font-size: 14px;">
              <i class="fa fa-save"></i> Simpan & Baru
            </button>
            <button type="button" onclick="simpanData('simpan_tutup')" class="btn btn-primary hrf_arial" style="padding: 10px 25px;border-radius: 3px;font-weight: bold;font-size: 14px;">
              <i class="fa fa-save"></i> Simpan & Tutup
            </button>
          </div>
        </div>
      </form>
    </div>
    
    <!-- Section untuk menampilkan data promo yang sudah disimpan -->
    <!-- Section ini ditampilkan untuk semua user termasuk operator (otoritas 1) dan administrator (otoritas 2) -->
    <div class="w3-row" style="margin-top: 20px; margin-bottom: 20px;">
      <div class="w3-col m12 l12">
        <div class="w3-container" style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:darkblue;font-size: 16px;padding: 10px 15px;border-radius: 5px 5px 0 0;border: 1px solid #ddd;border-bottom: none;">
          <a href="#" class="w3-text-white"><i class="fa fa-list-alt" style="color:orange"></i>&nbsp; Data Promo yang Sudah Disimpan</a>
        </div>
        <div class="w3-container w3-card" style="border: 1px solid #ddd;border-top: none;max-height: 600px;overflow-y: auto;border-radius: 0 0 5px 5px;padding: 15px;background-color: #fff;">
          <div id="listpromo">
            <div style="text-align: center; padding: 20px; color: #999;">
              <i class="fa fa-spinner fa-spin"></i> Memuat data promo...
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

