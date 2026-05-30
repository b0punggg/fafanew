<?php
  error_reporting(0); // Disable error reporting untuk mencegah error muncul di JSON
  ob_start();
  
  // Pastikan session sudah dimulai sebelum include config
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  
  include "config.php";
  $con=opendtcek();
  $kd_toko=isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : ''; 
?>

<style>
  .retur-list-table {
    font-size: 9pt;
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
  }
  .retur-list-table th {
    position: sticky;
    top: 0px;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    border: 1px solid #ddd;
    padding: 8px 4px;
    text-align: center;
    background-color: #f0f8ff;
    font-weight: bold;
    vertical-align: middle;
  }
  .retur-list-table td {
    border: 1px solid #ddd;
    padding: 6px 4px;
    vertical-align: middle;
  }
  .retur-list-table tbody tr:hover {
    background-color: #f5f5f5;
  }
  .retur-list-table tbody tr:nth-child(even) {
    background-color: #fafafa;
  }
  .retur-list-table tbody tr:nth-child(even):hover {
    background-color: #f0f0f0;
  }
</style>
<div class="table-responsive hrf_arial" style="overflow-x: auto;border-style: ridge;border-color: white;max-height: 430px;min-height: 100px">
    <table class="retur-list-table table-bordered table-hover">
      <thead>
      <tr align="middle" class="yz-theme-l1">
        <th style="width: 3%;min-width: 40px;">NO.</th>
        <th style="width: 15%" class="yz-theme-l1">NO.RETUR &nbsp;<button class="btn yz-theme-l3" type="button" id="btn-fakret" style="font-size: 8pt;border-color: black"><i class="fa fa-search"></i></button>
          <div class="row" >
            <div class="col">
              <div id="boxfakret" class="container" style="display: none;position:absolute;z-index: 1;margin-top: 7px;margin-left: -15px;" >

                <div class="input-group" style="width: 300px;">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="fakret" name="fakret" style="border:1px solid black;font-size: 9pt;width: 200px;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 5px 20px 7px 40px;" placeholder="No.Retur" 
                    onkeypress="if(event.keyCode==13){document.getElementById('keycarilist').value='AND retur_jual.no_returjual like '+this.value;carilistretur(1,true);}">
                    <div class="input-group-btn">
                      <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('keycarilist').value='';carilistretur(1,true);
                        " style="border:1px solid black;font-size: 8pt;padding-top: 9px;padding-bottom: 9px"><i class="fa fa-undo" style="cursor: pointer;"></i>
                      </button>
                    </div>
                </div>  
              </div>
            </div>
          </div>    
        </th>
        <th style="width: 12%" class="yz-theme-l1">NOTA. JUAL &nbsp;<button type="button" class="btn yz-theme-l3" id="btn-fakjuall" style="font-size: 8pt;border-color: black"><i class="fa fa-search"></i></button>
          <div class="row" >
            <div class="col">
              <div id="boxfakjuall" class="container" style="display: none;position:absolute;z-index: 1;margin-top: 7px;" >

                <div class="input-group" style="width: 300px;">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="fak_juall" name="fak_juall" style="width: 200px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 5px 20px 7px 40px;" placeholder="Nota jual" onkeypress="if(event.keyCode==13){document.getElementById('keycarilist').value='AND retur_jual.no_fakjual like '+this.value;carilistretur(1,true);}">
                    <div class="input-group-btn">
                      <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('keycarilist').value='';carilistretur(1,true);
                        " style="border:1px solid black;font-size: 8pt;padding-top: 9px;padding-bottom: 9px"><i class="fa fa-undo" style="cursor: pointer;"></i>
                      </button>
                    </div>
                </div>  

              </div>
            </div>
          </div>    
        </th>
        <th class="yz-theme-l1">NAMA BARANG &nbsp;<button class="btn yz-theme-l3" type="button" id="btn-nmbrgl" style="font-size: 8pt;border-color: black"><i class="fa fa-search"></i></button>
          
          <div class="row" >
            <div class="col">
              <div id="boxnmbrgl" class="container" style="display: none;position:absolute;z-index: 1;margin-top: 7px" >

                <div class="input-group" style="width: 300px;">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="nm_brgcaril" name="nm_brgcaril" style="width:200px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 5px 20px 7px 40px;" placeholder="Nama barang" onkeypress="if(event.keyCode==13){document.getElementById('keycarilist').value=' AND dum_jual.nm_brg like '+this.value;carilistretur(1,true);}">
                    <div class="input-group-btn">
                      <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('keycarilist').value='';carilistretur(1,true);
                        " style="border:1px solid black;font-size: 8pt;padding-top:9px;padding-bottom: 9px"><i class="fa fa-undo" style="cursor: pointer;"></i>
                      </button>
                    </div>   
                </div>  

              </div>
            </div>
          </div>    
        </th> 
          
        <!-- <th style="width: 27%" class="yz-theme-l1">NM. PEMBELI &nbsp;<button  class="btn yz-theme-l3" type="button" id="btn-nmpell" style="font-size: 8pt;border-color: black"><i class="fa fa-search"></i></button>
          <div class="row" >
            <div class="col">
              <div id="boxnmpell" class="container" style="display: none;position:absolute;z-index: 1;margin-top: 7px" >

                <div class="input-group" style="width: 300px">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="nmpell" name="nmpell" style="width:200px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 5px 20px 7px 40px;" placeholder="Nama pelanggan" onkeypress="if(event.keyCode==13){document.getElementById('keycarilist').value='AND dum_jual.kd_pel like '+this.value;carilistretur(1,true);}">
                    <div class="input-group-btn">
                      <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('keycarilist').value='';carilistretur(1,true);
                        " style="border:1px solid black;font-size: 8pt;padding-top: 9px;padding-bottom: 9px"><i class="fa fa-undo" style="cursor: pointer;"></i>
                      </button>
                    </div>   
                </div>  

              </div>
            </div>
          </div>    
        </th>     -->
        <th style="width: 27%;min-width: 200px;">NM. PEMBELI</th>
        <!-- <th style="padding-top: 1px;padding-bottom: 1px;width: 5%">DISC</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 10% ">NETTO</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 10%">SUB TOTAL</th> -->
        <th style="width: 5%;min-width: 80px;">OPSI</th>
      </tr>
      </thead>
      <tbody>
          <script>
            $(document).ready(function(){
              $("#btn-nmbrgl").click(function(){
                $("#boxnmbrgl").slideToggle("fast");
                $("#boxfakjuall").slideUp("fast");
                //$("#boxnmpell").slideUp("fast");
                $("#nm_brgcaril").focus();
              });
              $("#btn-fakret").click(function(){
                $("#boxfakret").slideToggle("fast");
                $("#boxnmbrgl").slideUp("fast");
                $("#boxfakjuall").slideUp("fast");
                //$("#boxnmpell").slideUp("fast");
                $("#fakret").focus();
              });
              $("#btn-fakjuall").click(function(){
                $("#boxfakjuall").slideToggle("fast");
                $("#boxnmbrgl").slideUp("fast");
                $("#boxfakret").slideUp("fast");
                //$("#boxnmpell").slideUp("fast");
                $("#fak_juall").focus();
              });
              // $("#btn-nmpell").click(function(){
              //   $("#boxnmpell").slideToggle("fast");
              //   $("#boxnmbrgl").slideUp("fast");
              //   $("#boxfakret").slideUp("fast");
              //   $("#boxfakjuall").slideUp("fast");
              //   $("#nmpell").focus();
              // });
            });
          </script>  

      <?php
        $page = (isset($_POST['page']))? $_POST['page'] : 1;
        $limit = 15; // Jumlah data per halamannya
        $limit_start = ($page - 1) * $limit; 
       

      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
         $param = mysqli_real_escape_string($con, $keyword);
        //$search=mysqli_real_escape_string($con, $_POST['search']);
        if(!empty($param)){
          $xada=strpos($param,"like");
          if ($xada <> false){
            $pecah=explode('like', $param);
            $kunci=$pecah[0];
            $kunci2=$pecah[1];
            $params=$kunci." like '%".trim($kunci2)."%'";
          } 
            
        }else{
          $kunci='';
          $kunci2=''; 
          $params="";
        }
       // echo '$params='.$params;
          if ($params=="") {   
            $sql = mysqli_query($con, "SELECT * FROM retur_jual LEFT JOIN dum_jual 
                ON retur_jual.no_urutjual=dum_jual.no_urut AND dum_jual.kd_toko='$kd_toko'
                WHERE retur_jual.kd_toko='$kd_toko'
                ORDER BY retur_jual.tgl_retur,SUBSTR(retur_jual.no_returjual, LOCATE('-',retur_jual.no_returjual)+1,(LENGTH(retur_jual.no_returjual)+1)-LOCATE('-',retur_jual.no_returjual)+1) DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM retur_jual WHERE kd_toko='$kd_toko'");
          }
          else {
            $sql =mysqli_query($con, "SELECT * FROM retur_jual LEFT JOIN dum_jual 
                ON retur_jual.no_urutjual=dum_jual.no_urut AND dum_jual.kd_toko='$kd_toko'
                WHERE retur_jual.kd_toko='$kd_toko' $params
                ORDER BY retur_jual.tgl_retur,SUBSTR(retur_jual.no_returjual, LOCATE('-',retur_jual.no_returjual)+1,(LENGTH(retur_jual.no_returjual)+1)-LOCATE('-',retur_jual.no_returjual)+1) DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM retur_jual LEFT JOIN dum_jual 
                ON retur_jual.no_urutjual=dum_jual.no_urut AND dum_jual.kd_toko='$kd_toko'
              WHERE retur_jual.kd_toko='$kd_toko' $params ");  
          } 
        $get_jumlah = mysqli_fetch_array($sql2);
      
      } 
      $no=$limit_start;
      while ($data=mysqli_fetch_assoc($sql)){
       $no++;
       $netto=$data['hrg_jual']-($data['hrg_jual']*($data['discitem']/100));
       $jmlsub=$netto*$data['qty_brg'];
       $no_urut=$data['no_urut'];
       $nm_pel=ceknmpel($data['kd_pel'],$con);       
      ?>
        <tr onclick="document.getElementById('<?=$no.'pil2'?>').click()" style="cursor: pointer">
          <td align="right"><?php echo $no ?></td>
          <td align="left"><?php echo $data['no_returjual']; ?></td>
          <td align="right"><?php echo $data['no_fakjual']; ?></td>
          <td align="left"><?php echo $data['nm_brg']; ?></td>
          <td align="middle"><?php echo $nm_pel; ?></td>
          <td style="text-align: center;">
            <button id="<?=$no.'pil2'?>"onclick="
            document.getElementById('fcari_notaret').style.display='none';
            document.getElementById('tgl_retur').value='<?=$data['tgl_retur']?>';
            document.getElementById('tgl_retur1').value='<?=$data['tgl_retur']?>';
            document.getElementById('no_returjual').value='<?=$data['no_returjual']?>';
            document.getElementById('no_returjual1').value='<?=$data['no_returjual']?>';
            document.getElementById('no_returjual1').focus();
            document.getElementById('no_returjual1').blur();
            " class="yz-theme-d2" style="cursor: pointer;font-size: 10pt;padding: 6px 10px;border: 1px solid #ddd;border-radius: 3px;background-color: #0066cc;color: white;" title="Edit Data" type="button"><i class="fa fa-edit"></i>
            </button>               

          </td>    
        </tr>
        
      <?php  
       }

      ?>
      </tbody>
    </table> 
  </div>
    <?php if($no>0){ ?>
    <nav  aria-label="Page navigation example" style="margin-top:5px;font-size: 9pt;padding: 5px 0;">
      <ul class="pagination pagination-sm justify-content-center" style="margin-bottom: 0;">
        <!-- LINK FIRST AND PREV -->
        <?php
        if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
        ?>
          <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">First</a></li>
          <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">&laquo;</a></li>
        <?php
        }else{ // Jika page bukan page ke 1
          $link_prev = ($page > 1)? $page - 1 : 1;
        ?>
          <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" href="javascript:void(0);" onclick="carilistretur(1, true)" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" href="javascript:void(0);" onclick="carilistretur(<?php echo $link_prev; ?>, true)" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;<?php echo ($page == $i) ? 'background-color: #0066cc;color: white;font-weight: bold;' : ''; ?>" onclick="carilistretur(<?php echo $i; ?>, true)" onmouseover="this.style.backgroundColor='<?php echo ($page == $i) ? '#0052a3' : '#0066cc'; ?>';this.style.color='white';" onmouseout="this.style.backgroundColor='<?php echo ($page == $i) ? '#0066cc' : ''; ?>';this.style.color='<?php echo ($page == $i) ? 'white' : ''; ?>';"><?php echo $i; ?></a></li>
        <?php
        }
        ?>
        
        <!-- LINK NEXT AND LAST -->
        <?php
        if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir
        ?>
          <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">&raquo;</a></li>
          <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">Last</a></li>
        <?php
        }else{ // Jika Bukan page terakhir
          $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
        ?>
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carilistretur(<?php echo $link_next; ?>, true)" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carilistretur(<?php echo $jumlah_page; ?>, true)" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
    <?php } ?>

<?php
  unset($data,$sql,$sql1);
  if(isset($con) && $con){
    mysqli_close($con);
  }
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Pastikan output JSON valid
  if($html === false || $html === ''){
    $html = '<tr><td colspan="6" align="center" style="padding: 20px;"><i class="fa fa-exclamation-triangle"></i> Tidak ada data atau terjadi kesalahan</td></tr>';
  }
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>