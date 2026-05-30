<!DOCTYPE html>
<html lang="id">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php 
 include 'starting.php';
?>
<style>
  th {
  position: sticky;
  top: 0px; 
  border: 1px solid lightgrey;
  padding: 4px; 
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }
  td {
    border: 1px solid lightgrey;
    padding: 2px;
  }
  table {
    border-spacing: 1px;
  }
</style>
<div id="main" style="font-size: 10pt;background: linear-gradient(185deg, #FAFAD2 10%, white 80%)">
  <script>  
        
    function listaset(page_number, search){
    
	    $.ajax({
	      url: 'f_stokaset_cari.php', // File tujuan
	      type: 'POST', // Tentukan type nya POST atau GET
	      data: {keyword:$("#kd_cari").val(), keyword2:$("#key_cari2").val(), page: page_number, search: search}, 
	      dataType: "json",
	      beforeSend: function(e) {
	        if(e && e.overrideMimeType) {
	          e.overrideMimeType("application/json;charset=UTF-8");
	        }
	      },
	      success: function(response){ 
	        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
	        
	        $("#viewaset").html(response.hasil);
	      },
	      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
	        alert(xhr.responseText); // munculkan alert
	      }
	    });
    }

    
   </script>
   
  <div class="w3-container" style="background: linear-gradient(165deg, magenta 0%, yellow 36%, white 80%);position: sticky;top:43px;margin-top:-6px;z-index: 1;font-size: 18px">
  	<i class='fa fa-database'></i> &nbsp;TRANSAKSI &nbsp;<i class='fa fa-angle-double-right'></i>&nbsp;<span style="font-size: 15px">ASSET BARANG</span><span class="w3-right" style="font-size: 16px"><i class="fa fa-calendar-check-o"></i>&nbsp;<?=gantitgl($_SESSION['tgl_set'])?></span>
  </div>
  <input type="hidden" id="kd_cari">
  <input type="hidden" id="key_cari2">
  <div id="viewaset"><script>listaset(1,true)</script></div>  
  <!-- key -->
  <!-- Form cetak retur beli barang-->
    <div id="formcetaset" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Aset Barang
          <span onclick="document.getElementById('formcetaset').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_cekaset.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <!-- <div class="form-group row">
                    <label for="tglretbel1" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglretbel1" name="tglretbel1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tglretbel2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglretbel2" name="tglretbel2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div> -->

                  <div class="form-group row">
                    <div class="col-sm-12">
                      <p>Pilih list cetak mutasi:</p>
                       <!-- pilih semua -->
                        <input type="radio" id="pil_allaset" name="pilihaset" value="alldata" checked="">
                        <label for="pil_allaset" style="cursor: pointer">Semua Data</label><br>
                        
                        <!-- pilih pelanggan -->
                        <input type="radio" id="pil_tokoaset" name="pilihaset" value="toko" onclick="document.getElementById('kd_tokoaset').value='';document.getElementById('nm_tokoaset').value=''">
                        <label for="pil_tokoaset" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                        <!-- box pil kategori -->
                        <div id="listaset" style="display: none;position: relative;z-index: 1;">
                          <input type="text" id="nm_tokoaset" onkeyup="caraset()" name="nm_tokoaset" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                          <input type="hidden" id="kd_tokoaset" name="kd_tokoaset" >
                          <div id="tabaset" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                            <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                              <tr align="middle" class="yz-theme-l3">
                                <th>NAMA TOKO</th>
                                <!-- <th>OPSI</th> -->
                              </tr>
                              <?php 
                              $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                              while ($datakat = mysqli_fetch_array($sql2)){
                              ?>
                              <tr>
                                <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokoaset').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokoaset').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                              </tr>  
                              <?php   
                              }
                              unset($datakat,$sql2);
                              ?>
                            </table>
                          </div>  <!-- tabbrand -->
                        </div> <!--listbrand-->
                        <script>
                          $(document).ready(function(){
                            $("#pil_tokoaset").click(function(){
                              $("#listaset").slideToggle("fast,swing");
                            });
                            $("#pil_allaset").click(function(){
                              $("#listaset").slideUp("fast,swing");
                            });
                            
                          });
                          function caraset() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_tokoaset");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabaset");
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
                        </script>    
                        <br>
                        <!-- end pilih supplier -->
                    </div>                   
                  </div>

                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form cetak-->
</div>  
