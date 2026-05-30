<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
<?php 
 include 'starting.php';
 include 'cekmasuk.php';
 $connect=opendtcek();
 $kd_toko=$_SESSION['id_toko'];
?>

<div id="main" style="font-size: 10pt">
  <script>	
    // Flag untuk menandai apakah sudah diklik tombol cari persediaan barang
    var filterBulanTahunAktif = false;
    
    function caripersediaan(page_number, search){
      // Show loading indicator
      var snackbar = document.getElementById('snackbar');
      if(snackbar){
        snackbar.className = 'show';
        snackbar.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memuat data...';
      }
      
      $.ajax({
        url: 'm_persediaan_bulan_cari.php',
        type: 'POST',
        data: {
          keyword: $("#keyktpersediaan").val(),
          bulan: $("#bulan").val(),
          tahun: $("#tahun").val(),
          cek_stok_kosong: $("#cek_stok_kosong").is(':checked') ? 1 : 0,
          filter_bulan_tahun: filterBulanTahunAktif ? 1 : 0, // Flag apakah filter bulan/tahun aktif
          page: page_number,
          search: search
        }, 
        dataType: "json",
        timeout: 180000, // 3 minutes timeout
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // Hide loading
          if(snackbar){
            snackbar.className = '';
          }
          
          if(response && response.hasil){
            $("#viewdtpersediaan").html(response.hasil);
          } else {
            console.error('Invalid response:', response);
            alert('Error: Response tidak valid');
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // Hide loading
          if(snackbar){
            snackbar.className = '';
          }
          
          console.error('AJAX Error:', xhr);
          console.error('Response Text:', xhr.responseText);
          
          var errorMsg = 'Error: ';
          if(xhr.status === 0){
            errorMsg += 'Timeout atau koneksi terputus. Data mungkin terlalu banyak.';
          } else if(xhr.status === 500){
            errorMsg += 'Server error: ' + xhr.responseText;
          } else {
            errorMsg += xhr.responseText || thrownError;
          }
          alert(errorMsg);
        }
      });
    }

    function cariPersediaanBarang(){
      var bulan = $("#bulan").val();
      var tahun = $("#tahun").val();
      
      if(!bulan || !tahun){
        alert('Pilih bulan dan tahun terlebih dahulu');
        return;
      }
      
      // Aktifkan flag filter bulan/tahun
      filterBulanTahunAktif = true;
      
      // Langsung panggil fungsi caripersediaan untuk menampilkan data yang difilter
      caripersediaan(1, true);
    }

    function kosongkan(){
      document.getElementById('bulan').value="<?=date('m')?>";
      document.getElementById('tahun').value="<?=date('Y')?>";
      document.getElementById('keyktpersediaan').value="";
      document.getElementById('cek_stok_kosong').checked = false;
      
      // Nonaktifkan flag filter bulan/tahun untuk menampilkan semua data
      filterBulanTahunAktif = false;
      
      caripersediaan(1, true);
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

  <div class="w3-container w3-card" style="background: linear-gradient(165deg, magenta 0%, yellow 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
    <i class='fa fa-briefcase' style="font-size: 18px">&nbsp;MASTER DATA &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">Persediaan Barang per Bulan</span>
  </div>

  <div class="w3-row" style="background: linear-gradient(565deg, #FFFACD 10%, white 90%);">
    <div class="col-sm-12">
      <!-- Filter Bulan dan Tahun -->
      <div class="w3-container w3-margin-top">
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label><b>Bulan</b></label>
              <select class="form-control hrf_arial" id="bulan" name="bulan" style="border: 1px solid black;font-size: 10pt;">
                <option value="01">Januari</option>
                <option value="02">Februari</option>
                <option value="03">Maret</option>
                <option value="04">April</option>
                <option value="05">Mei</option>
                <option value="06">Juni</option>
                <option value="07">Juli</option>
                <option value="08">Agustus</option>
                <option value="09">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12" selected>Desember</option>
              </select>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label><b>Tahun</b></label>
              <input class="form-control hrf_arial" id="tahun" type="number" name="tahun" value="<?=date('Y')?>" style="border: 1px solid black;font-size: 10pt;">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label>&nbsp;</label><br>
              <label style="font-weight: normal; cursor: pointer;">
                <input type="checkbox" id="cek_stok_kosong" name="cek_stok_kosong" value="1" style="margin-right: 5px;">
                <span style="font-size: 10pt;">Sertakan Stok Kosong</span>
              </label>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label>&nbsp;</label><br>
              <button onclick="cariPersediaanBarang()" class="btn btn-success" style="font-size: 10pt;"><i class="fa fa-search"></i> Cari Persediaan Barang</button>
              <button onclick="kosongkan()" class="btn btn-warning" style="font-size: 10pt;"><i class="fa fa-undo"></i> Reset</button>
            </div>
            <div class="col-sm-12 w3-margin-top">
              <label style="font-weight: normal; cursor: pointer; margin-right: 12px;">
                <input type="checkbox" id="cek_cetak_semua" name="cek_cetak_semua" value="1" style="margin-right: 5px;">
                <span style="font-size: 10pt;">Cetak semua data (abaikan bulan & tahun)</span>
              </label>
              <button onclick="cetakPDF()" class="btn btn-danger" style="font-size:10pt">
                <i class="fa fa-file-pdf-o"></i> Cetak PDF
              </button>
              <button onclick="cetakExcel()" class="btn btn-success" style="font-size:10pt">
                <i class="fa fa-file-excel-o"></i> Export Excel
              </button>
          </div>
        </div>
      </div>

      <!-- Pencarian -->
      <div class="yz-theme-l5 w3-border">
        <div class="w3-row">
          <div class="w3-half">
            <div id="ket_rec" class="fa fa-television" style="margin-top: 15px;margin-left: 10px;font-size: 13pt">  
            </div>
          </div>
          <div class="w3-half">
            <div class="input-group" style="margin-top: 15px">
              <input onkeyup="if(event.keyCode==13){caripersediaan(1, true);}" style="font-size: 10pt;height: 30px" type="text" class="form-control hrf_arial" placeholder="ketik pencarian [nama barang/kode]" id="keyktpersediaan">&nbsp;
              <span class="input-group-btn w3-margin-bottom">
                <button onclick="caripersediaan(1, true);" class="btn btn-primary" type="button" id="btn-ktpersediaan" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i></button>
                <a style="font-size: 10pt;" title="Reset cari" onclick="document.getElementById('keyktpersediaan').value='';document.getElementById('btn-ktpersediaan').click();" href="#" class="btn btn-warning"><i class="fa fa-undo"></i></a>
              </span>
            </div>    
          </div>
        </div>  
      </div>
      <div class="hrf_arial" id="viewdtpersediaan" style="margin-top: 0px;"><script>caripersediaan(1,false)</script></div>
    </div>  
  </div>
</div>

<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
    
    // Set bulan dan tahun default
    var d = new Date();
    $("#bulan").val(("0" + (d.getMonth() + 1)).slice(-2));
    $("#tahun").val(d.getFullYear());
  })
  function validasiCetak(){
  var cetakSemua = $("#cek_cetak_semua").is(':checked');
  if(cetakSemua) return true;
  var bulan = $("#bulan").val();
  var tahun = $("#tahun").val();
  if(!bulan || !tahun){
    alert("Pilih bulan dan tahun terlebih dahulu, atau centang 'Cetak semua data'");
    return false;
  }
  return true;
}

function cetakPDF(){
  if(!validasiCetak()) return;
  var cetakSemua = $("#cek_cetak_semua").is(':checked') ? 1 : 0;
  var stok = $("#cek_stok_kosong").is(':checked') ? 1 : 0;
  var url = "cetak_persediaan_pdf.php?stok="+stok;
  if(cetakSemua){
    url += "&semua=1";
  } else {
    url += "&bulan="+$("#bulan").val()+"&tahun="+$("#tahun").val();
  }
  window.open(url, "_blank");
}

function cetakExcel(){
  if(!validasiCetak()) return;
  var cetakSemua = $("#cek_cetak_semua").is(':checked') ? 1 : 0;
  var stok = $("#cek_stok_kosong").is(':checked') ? 1 : 0;
  var url = "cetak_persediaan_excel.php?stok="+stok;
  if(cetakSemua){
    url += "&semua=1";
  } else {
    url += "&bulan="+$("#bulan").val()+"&tahun="+$("#tahun").val();
  }
  window.location = url;
}

</script>

