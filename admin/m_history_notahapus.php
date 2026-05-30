<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/keranjang.png">
<div class="loader1" style="z-index: 10023"><div class="loader2"><div class="loader3"></div></div></div>
<?php
 include 'starting.php';
 include 'cekmasuk.php';
 if($bag!="Administrator"){
   echo "<script>window.location='dasbor.php';</script>";
   exit;
 }
 $connect=opendtcek();
?>

<div id="main" style="font-size: 10pt">
  <script>
    function carihistory(page_number, search){
      $.ajax({
        url: 'm_history_notahapus_cari.php',
        type: 'POST',
        data: {
          keyword: $("#key_history").val(),
          status: $("#status_history").val(),
          page: page_number,
          search: search
        },
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){
          $("#viewhistoryhapusnota").html(response.hasil);
        },
        error: function (xhr) {
          alert(xhr.responseText);
        }
      });
    }
  </script>

  <div class="w3-container w3-card" style="background: linear-gradient(165deg, #6666ff 0%, #99ccff 45%, white 85%);position: sticky;top:44px;margin-top: -6px;z-index: 1;">
    <i class='fa fa-history' style="font-size: 18px">&nbsp;MAINTENANCE &nbsp;</i> <i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 18px">History Hapus Nota</span>
  </div>

  <div class="w3-row" style="background: linear-gradient(565deg, #f0f8ff 10%, white 90%);">
    <div class="col-sm-12">
      <div class="w3-container w3-margin-top">
        <div class="row">
          <div class="col-sm-6">
            <div class="input-group w3-margin-bottom">
              <input onkeyup="if(event.keyCode==13){carihistory(1, true);}" id="key_history" type="text" class="form-control hrf_arial" placeholder="Cari no nota / user">
              <span class="input-group-btn">
                <button onclick="carihistory(1, true);" class="btn btn-primary" type="button" style="font-size: 10pt;" title="Cari"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </div>
          <div class="col-sm-3">
            <select id="status_history" class="form-control hrf_arial" onchange="carihistory(1, true);">
              <option value="ALL">Semua Status</option>
              <option value="D">Dihapus</option>
              <option value="A">Diabaikan</option>
              <option value="T">Pending</option>
            </select>
          </div>
          <div class="col-sm-3">
            <button onclick="document.getElementById('key_history').value='';document.getElementById('status_history').value='ALL';carihistory(1, true);" class="btn btn-warning form-control" type="button" style="font-size: 10pt;">
              <i class="fa fa-undo"></i> Reset
            </button>
          </div>
        </div>
      </div>
      <div class="hrf_arial" id="viewhistoryhapusnota" style="margin-top: 0px;"><script>carihistory(1,true)</script></div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>

<?php
  mysqli_close($connect);
?>
