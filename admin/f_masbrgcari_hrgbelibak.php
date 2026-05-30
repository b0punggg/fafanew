<?php
  $keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();
  
?>

<div class="table-responsive hrf_res2" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 430px">
    <table class="table-bordered table-striped table-hover" style="border-collapse: collapse;white-space: nowrap;width:100% ;" >
      <tr align="middle" >
        <th class="yz-theme-l3">NO.</th>
        <th class="yz-theme-l3">TGL.FAKTUR</th>
        <th class="yz-theme-l3">NO.FAKTUR</th>
        <th class="yz-theme-l3">SUPPLIER</th>
        <th class="yz-theme-l3">NAMA BARANG</th>
        <th class="yz-theme-l3">EXP.DATE</th>
        <th class="yz-theme-l3">HARGA NETT</th>
        <th class="yz-theme-l3">JML.BRG</th>
        <th class="yz-theme-l3">STOK AKHIR</th> 
        <th class="yz-theme-l3 " colspan="5">OPSI</th>
      </tr>
      <?php
      include "config.php";
      session_start();      
      $conhrg=opendtcek();
      $kd_toko=$_SESSION['id_toko'];
      $page = (isset($_POST['page']))? $_POST['page'] : 1;

      $limit = 3; // Jumlah data per halamannya

      $limit_start = ($page - 1) * $limit;
      // echo '$limit_start='.$limit_start;
         
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $params = mysqli_real_escape_string($conhrg, $keyword);
         //echo '$params='.$params;

          if ($params=="") {   
             $sql = mysqli_query($conhrg, "SELECT beli_brg.no_urut,beli_brg.jml_brg,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sat,mas_brg.nm_brg,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.expdate,supplier.kd_sup,supplier.nm_sup,beli_brg.stok_jual,beli_brg.kd_sat from beli_brg
                LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
                LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
                WHERE beli_brg.kd_toko='*'
                ORDER BY no_urut DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($conhrg, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE kd_toko='' ORDER BY no_urut");
          }
          else {
            $sql =mysqli_query($conhrg, "SELECT beli_brg.no_urut,beli_brg.jml_brg,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sat,mas_brg.nm_brg,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.expdate,supplier.kd_sup,supplier.nm_sup,beli_brg.stok_jual,beli_brg.kd_sat from beli_brg
                LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup
                LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
                WHERE beli_brg.kd_brg='$params' AND beli_brg.kd_toko='$kd_toko'  
                ORDER BY beli_brg.no_urut DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($conhrg,"SELECT COUNT(*) AS jumlah FROM beli_brg WHERE beli_brg.kd_brg='$params' AND beli_brg.kd_toko='$kd_toko'");  
          } 
        $get_jumlah = mysqli_fetch_array($sql2);

      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        $sql =mysqli_query($conhrg, "SELECT beli_brg.tgl_fak,beli_brg.no_fak,beli_brg.kd_sat,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_brg,beli_brg.kd_sup,beli_brg.expdate,supplier.nm_sup,mas_brg.nm_brg FROM beli_brg
                LEFT JOIN supplier ON beli_brg_jml.kd_sup=supplier.kd_sup
                LEFT JOIN mas_brg ON beli_brg.kd_brg=beli_brg.kd_brg
                WHERE beli_brg.kd_brg='$params' AND beli_brg.kd_toko='$kd_toko' 
                ORDER BY beli_brg.no_urut DESC LIMIT $limit_start, $limit");
        $sql2 = mysqli_query($conhrg, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE beli_brg.kd_brg='$params' AND beli_brg.kd_toko='$kd_toko' "); 
              
        $get_jumlah = mysqli_fetch_array($sql2);
      }

      $no=0;$tot=0;$no_fakjual='';$tgl_jual='';$disc1=0;$disc2=0;$jmlsub=0;$totlaba=0;
      $kd_pel='';$nm_pel='';$kd_bayar='';$kd_satuan=0;$jum_kem=0;
      if(mysqli_num_rows($sql)>0){
        while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          $no++;
          $x=explode(';',carisatkecil2($data['kd_brg'],$conhrg));
          $kd_satuan=$x[0];
          $jum_kem=$x[1];
  
          $nm_sat2=ceknmkem($data['kd_sat'],$conhrg);
          $nm_sat1=ceknmkem2($kd_satuan,$conhrg);
          $no_item=$data['no_urut'];
          $kd_sup=$data['kd_sup'];
          $nm_sup=$data['nm_sup'];
          $disc1=mysqli_escape_string($conhrg,$data['disc1'])/100;
          $disc2=mysqli_escape_string($conhrg,$data['disc2']);
  
          if ($data['disc1']=='0.00'){
            // echo gantiti($data['disc2']);
            $jmlsub=(mysqli_escape_string($conhrg,$data['hrg_beli'])-$disc2);
          }else{
            $jmlsub=(mysqli_escape_string($conhrg,$data['hrg_beli'])-(mysqli_escape_string($conhrg,$data['hrg_beli'])*$disc1));
          }
          if ($data['disc1']=='0.00' && $data['disc2']=='0'){
            $jmlsub=mysqli_escape_string($conhrg,$data['hrg_beli']);
          }
          $param=mysqli_escape_string($conhrg,$data['no_urut']); ?>
          <tr>
            <td align="right"><?php echo $no ?></td>
            <td align="left"><?php echo gantitgl($data['tgl_fak']); ?></td>
            <td align="middle"><?php echo $data['no_fak']; ?></td>
            <td align="middle"><?php echo $data['nm_sup']; ?></td>
            <td align="left"><?php echo $data['nm_brg']; ?></td>
            <td align="middle"><?php echo gantitgl($data['expdate']); ?></td>
            <td align="right"><?php echo gantitides($jmlsub); ?></td>
            <td align="middle"><?php echo gantitides($data['jml_brg']).' '.$nm_sat2; ?></td>
            <td align="middle"><?php echo gantitides($data['stok_jual']).' '.$nm_sat1; ?></td>   
            <td>
              <a href="f_beli.php?pesanedit=<?=$no_item,';'.$data['no_fak'].';'.$data['tgl_fak']?>" class="btn btn-lg btn-primary fa fa-edit" type="button" style="cursor: pointer; border-style;font-size: 12pt;padding:1px;padding-left: 5px;border:1px solid black;" title="Edit Data"></a>
            </td>
            <td>&nbsp;</td>  
            <td>
              <a href="#" onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){hapushrgbeli(<?=$param?>)}" class="btn btn-lg btn-danger fa fa-trash" type="button" style="cursor: pointer; color: white;font-size: 12pt;padding:1px;padding-left: 5px;padding-right: 5px;border:1px solid black;" title="Hapus"></a>
            </td>
            <td>&nbsp;</td>  
            <td>
              <a href="#" onclick="document.getElementById('formedit').style.display='block';
                document.getElementById('kd_sup22').value='<?=$kd_sup?>';
                document.getElementById('nm_sup22').value='<?=$nm_sup?>';
                document.getElementById('kd_brg1').value='<?=$data['kd_brg']?>';
                document.getElementById('nm_brg1').value='<?=$data['nm_brg']?>';
                document.getElementById('hrg_beli1').value='<?=gantitides($data['hrg_beli'])?>';
                document.getElementById('nm_sat11').value='<?=$nm_sat2?>';
                document.getElementById('kd_sat11').value='<?=$data['kd_sat']?>';
                document.getElementById('no_fak1').focus();
                " class="btn btn-lg btn-warning fa fa-bullhorn" type="button" style="cursor: pointer;font-size: 12pt;padding:1px;padding-left: 5px;border:1px solid black;" title="Pembelian barang">
              </a>
            </td>
          </tr> <?php  
        }
      } ?>
    </table> 
  </div>
  
    <nav  aria-label="Page navigation example" style="font-size: 8pt">
      <ul class="pagination justify-content-center">
        <!-- LINK FIRST AND PREV -->
        <?php
        if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
        ?>
          <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
          <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&laquo;</a></li>
        <?php
        }else{ // Jika page bukan page ke 1
          $link_prev = ($page > 1)? $page - 1 : 1;
        ?>
          <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carihrgbeli(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carihrgbeli(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
        <?php
        }
        ?>
        
        <!-- LINK NUMBER -->
        <?php
        $jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamannya
        $jumlah_number = 1; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
        $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
        $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
        
        for($i = $start_number; $i <= $end_number; $i++){
          $link_active = ($page == $i)? ' class="active"' : '';
        ?>
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carihrgbeli(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
        <?php
        }
        ?>
        
        <!-- LINK NEXT AND LAST -->
        <?php
        if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir
        ?>
          <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&raquo;</a></li>
          <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
        <?php
        }else{ // Jika Bukan page terakhir
          $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
        ?>
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carihrgbeli(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carihrgbeli(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
    
  </div>  
  <?php 
  function carinmsat($kd_sat){
  $conhrg1=opendtcek(1);
    $cek=mysqli_query($conhrg1,"select * from kemas where no_urut='$kd_sat'");
    $data=mysqli_fetch_assoc($cek);
    $nm_sat2=mysqli_escape_string($conhrg1,$data['nm_sat2']);
    unset($data,$cek);
    mysqli_close($conhrg1);
    return $nm_sat2;
  }
  ?>

<!-- Form edit hrgbeli -->
  <div id="formedit" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.5) ">
    <div class="w3-modal-content w3-card-4 w3-animate-top" style="border-style: ridge;border-color: white;">
      <div class="yz-theme-d1" style="color:white;font-size: 14px;padding:4px">
        &nbsp; <i class="fa fa-search"></i>&nbsp;INPUT PEMBELIAN BARANG
        <span onclick="document.getElementById('formedit').style.display='none'" class="w3-display-topright" title="Close form" style="margin-top: -3px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
      </div>
      <input type="hidden" id="no_itemcari" name="no_itemcari">  
      <div class="w3-container w3-margin-top">
        <form action="f_masbrginput_act.php" method="post">
          <div class="w3-row">
            
            <div class="w3-col m6 l6">
              <div class="form-group row">
                <label for="tgl_fak1" class="col-sm-3 col-form-label">Tanggal Beli</label>
                <div class="col-sm-8 hrf_arial">
                  <input class="form-control hrf_arial" type="date" id="tgl_fak1" name="tgl_fak1" value="<?=date('Y-m-d')?>" disabled="" style="border: 1px solid black; font-size: 9pt;">
                  <input type="hidden" id="tgl_fak12" name="tgl_fak12" value="<?=date('Y-m-d')?>">
                </div>
              </div>
              <div class="form-group row" style="margin-top: -10px">
                <label for="no_fak1" class="col-sm-3 col-form-label">No Faktur</label>
                <div class="col-sm-8 hrf_arial">
                  <input class="form-control hrf_arial" type="text" id="no_fak1" name="no_fak1" required="" placeholder="ketik no.faktur" style="border: 1px solid black; font-size: 9pt;"> 
                </div>
              </div>
              <div class="form-group row" style="margin-top: -10px">
                <label for="nm_sup1" class="col-sm-3 col-form-label">Supplier</label>
                <div class="col-sm-8 hrf_arial">
                  <input class="form-control" required="" type="text" id="nm_sup22" name="nm_sup2" disabled="" style="border: 1px solid black; font-size: 9pt;">
                  <input type="hidden" id="kd_sup22" name="kd_sup2">
                </div>
              </div>

              <div class="form-group row" style="margin-top: -10px">
                <label for="nm_brg1" class="col-sm-3 col-form-label">Nama Barang</label>
                <div class="col-sm-8 hrf_arial">
                  <input class="form-control" required="" type="text" id="nm_brg1" name="nm_brg1" disabled="" style="border: 1px solid black; font-size: 9pt;">
                  <input class="form-control" required="" type="hidden" id="kd_brg1" name="kd_brg1">
                </div>
              </div>  
            </div><!-- <div class="w3-col m6 l6"> -->

            <div class="w3-col m6 l6">
              <div class="form-group row">
                <label for="jml_brg1" class="col-sm-3 col-form-label">Jmlh Barang</label>
                <div class="col-sm-8 hrf_arial">
                  <input class="form-control hrf_arial" type="number" step="0.05" id="jml_brg1" name="jml_brg1" required="" style="border: 1px solid black; font-size: 10pt;">
                </div>
              </div>  

              <div class="form-group row" style="margin-top: -10px"> 
                <label for="nm_sat" class="col-sm-3 col-form-label">Sat. Barang</label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <input id="nm_sat11" onkeyup="carkem()" type="text" style="border: 1px solid black; font-size: 9pt;" class="form-control hrf_arial" name="nm_sat11" required=""placeholder="ketik jenis kemasan">
                    <span><button id="btn-nmsat" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 32px;cursor: pointer;border:1px solid black" type="button"><i class="fa fa-caret-down"></i></button></span>
                  </div>
                  <input type="hidden" name="kd_sat11" id="kd_sat11">

                  <!-- box search kemasan -->
                    <div id="boxkem" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px">
                      <div id="tabkem" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 250px">
                        <table class="table table-bordered table-sm table-hover" style="font-size:8pt; ">
                          <tr align="middle" class="yz-theme-l3">
                            <th>SATUAN</th>
                            <th>OPSI</th>
                          </tr>
                          <?php 
                          $sql1 = mysqli_query($conhrg, "SELECT * from kemas ORDER BY nm_sat2 ASC ");
                          $iii=0;
                          while ($datakem = mysqli_fetch_array($sql1)){
                            $iii++;
                          ?>
                          <tr>
                            <td align="left" class="button" style="cursor:pointer;" onclick="document.getElementById('<?='btnsat1'.$iii?>').click()"><?php echo $datakem['nm_sat2']; ?></td>
                            <td ><button id="<?='btnsat1'.$iii?>" type="button" onclick="document.getElementById('nm_sat11').value='<?=mysqli_escape_string($conhrg,$datakem['nm_sat2']) ?>';document.getElementById('kd_sat11').value='<?=mysqli_escape_string($conhrg,$datakem['no_urut']) ?>';">Pilih</button></td>
                          </tr>  

                          <?php   
                          }
                          unset($datakem);
                          ?>
                        </table>
                      </div>  <!-- tabsub -->
                    </div> <!-- boxsub -->
                   
                    <script>
                      $(document).ready(function(){
                        $("#btn-nmsat").click(function(){
                          $("#nm_sat").focus();
                          $("#boxkem").slideToggle("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxnmbrg").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          $("#boxbrand").slideUp("fast");
                        });
                        $("#nm_sat").keyup(function(){
                          $("#boxkem").slideDown("fast");
                          $("#boxkat").slideUp("fast");
                          $("#boxnmbrg").slideUp("fast");
                          $("#boxidbrg").slideUp("fast");
                          $("#boxsup").slideUp("fast");
                          $("#boxbrand").slideUp("fast");
                        });
                        $("#boxkem").click(function(){
                          $("#boxkem").slideUp("fast");
                        });
                        $("#tabkem").mouseleave(function(){
                          $("#boxkem").slideUp("fast");
                        });
                      });

                      function carkem() {
                        var input, filter, table, tr, td, i, txtValue;
                        input = document.getElementById("nm_sat");
                        filter = input.value.toUpperCase();
                        table = document.getElementById("tabkem");
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
                </div>    
              </div>  

              <div class="form-group row" style="margin-top: -10px">
                <label for="hrg_beli1" class="col-sm-3 col-form-label">Harga Beli</label>
                <div class="col-sm-8 hrf_arial">
                  <input class="form-control uang" required="" type="text" id="hrg_beli1" name="hrg_beli1" style="border: 1px solid black; font-size: 10pt;">
                </div>
              </div>
              
              <div class="form-group row" style="margin-top: -10px">
                <label for="discitem1" class="col-sm-3 col-form-label" style="margin-top: 2px">Disc. peritem</label>

                <div class="col-sm-8">
                  <div class="input-group">
                    <input id="discitem11" type="text" onfocus="document.getElementById('discitem22').value=''"  style="border: 1px solid black; font-size: 10pt;margin-top: 3px" class="form-control desimal hrf_arial" name="discitem11" placeholder="persen"><span><input id="discitem22" type="text" onfocus="document.getElementById('discitem11').value='';" style="border: 1px solid black; font-size: 10pt;margin-top: 3px" class="form-control uang hrf_arial" name="discitem22"  placeholder="Rupiah"></span>
                  </div>  
                </div>

              </div>  
            </div>
           
          <div class="row justify-content-center">
            <div class="w3-col m3 l2 w3-container ">
              <button type="submit" class="btn form-control yz-theme-d1 w3-hover-shadow" style="border: 1px solid black;cursor: pointer"><i class="fa fa-save"></i> Simpan</button>  
            </div>
            <div class="w3-col m3 l2 mb-3 w3-container">
              <button type="button" onclick="document.getElementById('formedit').style.display='none'" class="btn btn-warning form-control w3-hover-shadow" style="border: 1px solid black;cursor: pointer"><i class="fa fa-undo"></i> Batal</button>     
            </div>  
          </div>  

        </form>        
      </div>
      
    </div>
  </div>
  <!-- End Form list barang-->
  <script>
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

  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>