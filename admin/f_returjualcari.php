<?php
  $keyword=$_POST['keyword'];
  ob_start();
  include "config.php";
  session_start();
  $con=opendtcek();
  $kd_toko=$_SESSION['id_toko']; 
?>

<div class="table-responsive hrf_arial" style="overflow-x: auto;border-style: ridge;border-color: white;max-height: 430px;min-height: 100px">
    <table class="table table-bordered table-sm table-striped table-hover"  style="font-size:9pt;border-collapse: collapse;white-space: nowrap;">
      <tr align="middle" class="yz-theme-l1">
        <th style="padding-top:8px" class="yz-theme-l1">NO.</th>
        <th style="width: 10%" class="yz-theme-l1">TGL.JUAL &nbsp;<button class="btn yz-theme-l3" type="button" id="btn-fakjual" style="font-size: 8pt;border-color: black"><i class="fa fa-search"></i></button>
          <div class="row" >
            <div class="col">
              <div id="boxfakjual" class="container" style="display: none;position:absolute;z-index: 1;margin-top: 7px;margin-left: -15px;" >

                <div class="input-group" style="width: 300px;">
                  <input type="date" class="yz-theme-l4 w3-card-4" id="fakjual" name="fakjual" style="border:1px solid black;font-size: 9pt;width: 200px;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 5px 20px 7px 40px;" placeholder="Tgl jual" 
                    onchange="document.getElementById('keycariretur').value='AND dum_jual.tgl_jual like '+this.value;carinotaretur(1,true);" 
                    onkeypress="if(event.keyCode==13){document.getElementById('keycarijual').value='AND dum_jual.tgl_jual like '+this.value;carinotaretur(1,true);}">
                    <div class="input-group-btn">
                      <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('keycariretur').value='';carinotaretur(1,true);
                        " style="border:1px solid black;font-size: 8pt;padding-top: 9px;padding-bottom: 9px"><i class="fa fa-undo" style="cursor: pointer;"></i>
                      </button>
                    </div>
                </div>  

              </div>
            </div>
          </div>    
        </th>
        <th style="width: 12%" class="yz-theme-l1">NOTA. JUAL &nbsp;<button type="button" class="btn yz-theme-l3" id="btn-nojual" style="font-size: 8pt;border-color: black"><i class="fa fa-search"></i></button>
          <div class="row" >
            <div class="col">
              <div id="boxnojual" class="container" style="display: none;position:absolute;z-index: 1;margin-top: 7px;" >

                <div class="input-group" style="width: 300px;">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="no_jual" name="no_jual" style="width: 200px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 5px 20px 7px 40px;" placeholder="Nota jual" onkeypress="if(event.keyCode==13){document.getElementById('keycariretur').value='AND dum_jual.no_fakjual like '+this.value;carinotaretur(1,true);}">
                    <div class="input-group-btn">
                      <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('keycariretur').value='';carinotaretur(1,true);
                        " style="border:1px solid black;font-size: 8pt;padding-top: 9px;padding-bottom: 9px"><i class="fa fa-undo" style="cursor: pointer;"></i>
                      </button>
                    </div>
                </div>  

              </div>
            </div>
          </div>    
        </th>
        <th class="yz-theme-l1">NAMA BARANG &nbsp;<button class="btn yz-theme-l3" type="button" id="btn-nmbrg" style="font-size: 8pt;border-color: black"><i class="fa fa-search"></i></button>
          
          <div class="row" >
            <div class="col">
              <div id="boxnmbrg" class="container" style="display: none;position:absolute;z-index: 1;margin-top: 7px" >

                <div class="input-group" style="width: 300px;">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="nm_brgcari" name="nm_brgcari" style="width:200px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 5px 20px 7px 40px;" placeholder="Nama barang" onkeypress="if(event.keyCode==13){document.getElementById('keycariretur').value=' AND dum_jual.nm_brg like '+this.value;carinotaretur(1,true);}">
                    <div class="input-group-btn">
                      <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('keycariretur').value='';carinotaretur(1,true);
                        " style="border:1px solid black;font-size: 8pt;padding-top:9px;padding-bottom: 9px"><i class="fa fa-undo" style="cursor: pointer;"></i>
                      </button>
                    </div>   
                </div>  

              </div>
            </div>
          </div>    
        </th> 
          
        <th style="width: 27%" class="yz-theme-l1">NM. PEMBELI &nbsp;<button  class="btn yz-theme-l3" type="button" id="btn-nmpel" style="font-size: 8pt;border-color: black"><i class="fa fa-search"></i></button>
          <div class="row" >
            <div class="col">
              <div id="boxnmpel" class="container" style="display: none;position:absolute;z-index: 1;margin-top: 7px" >

                <div class="input-group" style="width: 300px">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="nmpel" name="nmpel" style="width:200px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 5px 20px 7px 40px;" placeholder="Nama barang" onkeypress="if(event.keyCode==13){document.getElementById('keycariretur').value='AND pelanggan.nm_pel like '+this.value;carinotaretur(1,true);}">
                    <div class="input-group-btn">
                      <button class="w3-card-4 btn btn-primary" onclick="
                        document.getElementById('keycariretur').value='';carinotaretur(1,true);
                        " style="border:1px solid black;font-size: 8pt;padding-top: 9px;padding-bottom: 9px"><i class="fa fa-undo" style="cursor: pointer;"></i>
                      </button>
                    </div>   
                </div>  

              </div>
            </div>
          </div>    
        </th>    
        <!-- <th style="padding-top: 1px;padding-bottom: 1px;width: 10%">HARGA JUAL</th> -->
        <!-- <th style="padding-top: 1px;padding-bottom: 1px;width: 5%">DISC</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 10% ">NETTO</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 10%">SUB TOTAL</th> -->
        <th style="padding-top: 8px;width: 2%" class="yz-theme-l1">OPSI</th>
      </tr>
          <script>
            $(document).ready(function(){
              $("#btn-nmbrg").click(function(){
                $("#boxnmbrg").slideToggle("fast");
                $("#boxnojual").slideUp("fast");
                $("#boxfakjual").slideUp("fast");
                $("#boxnmpel").slideUp("fast");
                $("#nm_brgcari").focus();
              });
              $("#btn-nojual").click(function(){
                $("#boxnojual").slideToggle("fast");
                $("#boxnmbrg").slideUp("fast");
                $("#boxfakjual").slideUp("fast");
                $("#boxnmpel").slideUp("fast");
                $("#no_jual").focus();
              });
              $("#btn-fakjual").click(function(){
                $("#boxfakjual").slideToggle("fast");
                $("#boxnmbrg").slideUp("fast");
                $("#boxnojual").slideUp("fast");
                $("#boxnmpel").slideUp("fast");
                $("#fakjual").focus();
              });
              $("#btn-nmpel").click(function(){
                $("#boxnmpel").slideToggle("fast");
                $("#boxnmbrg").slideUp("fast");
                $("#boxnojual").slideUp("fast");
                $("#boxfakjual").slideUp("fast");
                $("#nmpel").focus();
              });
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
          if ($params=="") {   
            $sql = mysqli_query($con, "SELECT dum_jual.no_urut,dum_jual.no_item,dum_jual.nm_brg, dum_jual.bayar,dum_jual.tgl_jt,dum_jual.tgl_jual,dum_jual.tgl_jt,dum_jual.no_fakjual,dum_jual.no_urut,dum_jual.hrg_jual,dum_jual.hrg_beli,dum_jual.kd_bayar,dum_jual.qty_brg,dum_jual.discitem,dum_jual.laba,dum_jual.kd_pel,dum_jual.kd_brg,dum_jual.kd_sat,kemas.nm_sat1,pelanggan.kd_pel,pelanggan.nm_pel,mas_jual.kd_bayar FROM dum_jual 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual
                WHERE dum_jual.kd_toko='$kd_toko'
                ORDER BY dum_jual.tgl_jual,SUBSTR(dum_jual.no_fakjual, LOCATE('-',dum_jual.no_fakjual)+1,(LENGTH(dum_jual.no_fakjual)+1)-LOCATE('-',dum_jual.no_fakjual)+1) DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM dum_jual 
                    LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual
                    WHERE dum_jual.kd_toko='$kd_toko' ");
          }
          else {
            $sql =mysqli_query($con, "SELECT dum_jual.no_urut,dum_jual.no_item,dum_jual.nm_brg, dum_jual.bayar,dum_jual.tgl_jt,dum_jual.tgl_jual,dum_jual.tgl_jt,dum_jual.no_fakjual,dum_jual.no_urut,dum_jual.hrg_jual,dum_jual.hrg_beli,dum_jual.kd_bayar,dum_jual.qty_brg,dum_jual.discitem,dum_jual.laba,dum_jual.kd_pel,dum_jual.kd_brg,dum_jual.kd_sat,kemas.nm_sat1,pelanggan.kd_pel,pelanggan.nm_pel,mas_jual.kd_bayar FROM dum_jual 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
                LEFT JOIN mas_jual ON dum_jual.no_fakjual=mas_jual.no_fakjual
                WHERE dum_jual.kd_toko='$kd_toko' $params
                ORDER BY dum_jual.tgl_jual,SUBSTR(dum_jual.no_fakjual, LOCATE('-',dum_jual.no_fakjual)+1,(LENGTH(dum_jual.no_fakjual)+1)-LOCATE('-',dum_jual.no_fakjual)+1) DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM dum_jual 
              LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
              WHERE dum_jual.kd_toko='$kd_toko' $params ");  
          } 
        $get_jumlah = mysqli_fetch_array($sql2);
      
      } 
      $no=$limit_start;
      while ($data=mysqli_fetch_assoc($sql)){
       $no++;
       $netto=$data['hrg_jual']-($data['hrg_jual']*($data['discitem']/100));
       $jmlsub=$netto*$data['qty_brg'];
       $no_urut=$data['no_urut'];
       
      ?>
        <tr onclick="document.getElementById('<?=$no.'pil'?>').click()" style="cursor: pointer">
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;padding-top: 5px"><?php echo $no ?></td>
          <td align="left" style="padding-top: 1px;padding-bottom: 1px;padding-top: 5px"><?php echo gantitgl($data['tgl_jual']); ?></td>
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;padding-top: 5px"><?php echo $data['no_fakjual']; ?></td>
          <td align="left" style="padding-top: 1px;padding-bottom: 1px;padding-top: 5px"><?php echo $data['nm_brg']; ?></td>
          <td align="middle" style="padding-top: 1px;padding-bottom: 1px;padding-top: 5px"><?php echo $data['nm_pel']; ?></td>
          <td style="padding-top: 1px;padding-bottom: 1px;">
            <button id="<?=$no.'pil'?>"onclick="
            document.getElementById('fcari_nota').style.display='none';jualreturtake('<?=$no_urut?>');
            " class="form-control yz-theme-d2" style="cursor: pointer;font-size: 10pt;padding: 3px" title="Edit Data" type="button"><i class="fa fa-edit"></i>
            </button>               

          </td>    
        </tr>
        
      <?php  
       }

      ?>
    </table> 
  </div>
    <?php if($no>0){ ?>
    <nav  aria-label="Page navigation example" style="font-size: 9pt">
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
          <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinotaretur(1, true)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carinotaretur(<?php echo $link_prev; ?>, true)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinotaretur(<?php echo $i; ?>, true)"><?php echo $i; ?></a></li>
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
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinotaretur(<?php echo $link_next; ?>, true)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinotaretur(<?php echo $jumlah_page; ?>, true)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
    <?php } ?>

<?php
  unset($data,$sql,$sql1);
  mysqli_close($con);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>