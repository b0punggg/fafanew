<?php	
  $param=$_POST['keyword'];
	ob_start();
  include 'config.php';
  session_start();
  $kd_toko=$_SESSION['id_toko'];
  $conretur=opendtcek();
?>
<style>
  th {
  position: sticky;
  top: 0px; 
  
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }
  table, td {
    border: 1px solid grey;
    padding: 1px;
  }
  th {
    border: 1px solid lightgrey;
    padding: 5px;
  }
  table {
    border-spacing: 2px;
  }
</style>
<div style="position:relative;font-size: 10pt"><i class="fa fa-desktop"></i><b>&nbsp;LIST RETUR BELI BARANG</b></div>
<div class="table-responsive" style="overflow-x: auto;border-style: ridge;min-height: 120px">
	<table class="table-hover" style="font-size:9pt;width: 100%;border-collapse: collapse;white-space: nowrap;">
    <tr align="middle" class="yz-theme-l4">
      <th width="3%">NO. </th>	
      <th  style="cursor: pointer" >TGL <i id="piltgl" class="fa fa-caret-down"></i>
        <div class="row">
          <div class="col">
            <div id="boxtglret" class="container" style="display:none;position: absolute;z-index: 1123;margin-left: -15px;margin-top:7px">
              <div class="input-group" style="width: 250px;margin-top: 10px">
                 <input type="date" class="yz-theme-l4 w3-card-4" id="tgl_ret" name="tgl_ret" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Cari tgl retur" onkeypress="if(event.keyCode==13){document.getElementById('keycari').value= setcari('AND retur_beli_mas.tgl_retur =',this.value);carinoretur(1,true)}">
                 <div class="input-group-btn w3-card-4">
                  <button class="btn btn-primary" onclick="
                    document.getElementById('keycari').value='';
                    document.getElementById('boxtglret').style.display='none';carinoretur(1,true);
                 " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
                 </div>    
              </div>  
            </div>
          </div>
        </div>    
          <script>
            $(document).ready(function(){
              $("#piltgl").click(function(){
                $("#boxtglret").slideToggle("fast");
                $("#boxcaribrg").slideUp("fast");
                $("#boxcarisup").slideUp("fast");
                $("#boxnoret").slideUp("fast");
                $("#tgl_ret").focus();
              });
            });
          </script>
      </th>
      <th>NO.RETUR <i class="fa fa-caret-down" id="pilnoret" style="cursor: pointer"></i>
        <div class="row">
          <div class="col">
            <div id="boxnoret" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px;margin-top:7px">
              <div class="input-group" style="width: 250px;margin-top: 10px">
                 <input type="text" class="yz-theme-l4 w3-card-2" id="no_ret" name="no_ret" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Cari nomer retur" onkeypress="if(event.keyCode==13){document.getElementById('keycari').value= setcari('AND retur_beli_mas.no_retur like','%'+this.value+'%');carinoretur(1,true)}">
                 <div class="input-group-btn w3-card-4">
                  <button class=" btn btn-primary" onclick="
                 document.getElementById('keycari').value='';
                 document.getElementById('boxnoret').style.display='none';carinoretur(1,true);
                 " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
                 </div>    
              </div>  
            </div>
          </div>
        </div>    
          <script>
            $(document).ready(function(){
              $("#pilnoret").click(function(){
                $("#boxnoret").slideToggle("fast");
                $("#boxtglret").slideUp("fast");
                $("#boxcaribrg").slideUp("fast");
                $("#boxcarisup").slideUp("fast");
                $("#no_ret").focus();
              });
            });
          </script>
      </th>
      <th>SUPPLIER <i class="fa fa-caret-down" id="pil_sup" style="cursor: pointer"></i>
        <div class="row">
          <div class="col">
            <div id="boxcarisup" class="container" style="display:none;position: absolute;z-index: 1;margin-left: -15px;margin-top:7px">
              <div class="input-group" style="width: 250px;margin-top: 10px">
                 <input type="text" class="yz-theme-l4 w3-card-2" id="kd_carisup" name="kd_carisup" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Cari supplier" onkeypress="if(event.keyCode==13){document.getElementById('keycari').value= setcari('AND supplier.nm_sup like','%'+this.value+'%');carinoretur(1,true)}">
                 <div class="input-group-btn w3-card-4" >
                  <button class="btn btn-primary" onclick="
                 document.getElementById('keycari').value='';
                 document.getElementById('boxcarisup').style.display='none';carinoretur(1,true)
                 " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
                 </div>    
              </div>  
            </div>
          </div>
        </div>    
          <script>
            $(document).ready(function(){
              $("#pil_sup").click(function(){
                $("#boxcarisup").slideToggle("fast");
                $("#boxnoret").slideUp("fast");
                $("#boxtglret").slideUp("fast");
                $("#boxcaribrg").slideUp("fast");
                $("#kd_carisup").focus();
              });
            });
          </script>
      </th>
      <!-- <th>NM.BRG <i class="fa fa-caret-down" id="pilnmbrg" style="cursor: pointer"></i> -->
        <!-- <div class="row">
          <div class="col">
            <div id="boxcaribrg" class="container" style="display:none;position: fixed;z-index: 1;margin-left: -15px;margin-top:7px">
              <div class="input-group">
                 <input type="text" class="yz-theme-l4 w3-card-2" id="kd_caribrg" name="kd_caribrg" style="width:250px;border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Cari barang" onkeypress="if(event.keyCode==13){document.getElementById('keycari').value= setcari('AND mas_brg.nm_brg like','%'+this.value+'%');carinoretur(1,true)}">
                 <span><button class="w3-card-2 btn btn-primary" onclick="
                 document.getElementById('keycari').value='';
                 document.getElementById('boxcaribrg').style.display='none';
                 " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i></button>
                 </span>    
              </div>  
            </div>
          </div>
        </div>    
          <script>
            $(document).ready(function(){
              $("#pilnmbrg").click(function(){
                $("#boxcaribrg").slideToggle("fast");
                $("#boxcarisup").slideUp("fast");
                $("#boxnoret").slideUp("fast");
                $("#boxtglret").slideUp("fast");
                $("#kd_caribrg").focus();
              });
            });
          </script> -->
      <!-- </th> -->
      <th>QTY</th> 
      <th width="2%">OPSI</th>
    </tr>
    <?php 
     $page = (isset($_POST['page']))? $_POST['page'] : 1;

      $limit = 5; // Jumlah data per halamannya

      $limit_start = ($page - 1) * $limit;
      // echo '$limit_start='.$limit_start;
      //echo $param;     
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
          
        $sql1 =mysqli_query($conretur, "SELECT retur_beli_mas.no_retur,retur_beli_mas.tgl_retur,retur_beli_mas.kd_sup,retur_beli_mas.tot_qty,retur_beli_mas.tot_retur,supplier.nm_sup,supplier.al_sup,supplier.nm_sales,supplier.no_telp FROM retur_beli_mas
              LEFT JOIN supplier ON retur_beli_mas.kd_sup=supplier.kd_sup
              WHERE retur_beli_mas.kd_toko='$kd_toko' $param
              ORDER BY retur_beli_mas.no_urut  ASC LIMIT $limit_start, $limit");
        $sql2 = mysqli_query($conretur, "SELECT COUNT(*) AS jumlah FROM retur_beli_mas 
              LEFT JOIN supplier ON retur_beli_mas.kd_sup=supplier.kd_sup
              WHERE retur_beli_mas.kd_toko='$kd_toko' $param ");   
        $get_jumlah = mysqli_fetch_array($sql2);
      } 
      $no=$limit_start;
      while($datcari=mysqli_fetch_assoc($sql1)){
        //$sat=ceknmkem2($datcari['kd_sat']);
        $no++;
      ?>
       <tr>
        <td align="right"><?php echo $no ?>&nbsp;</td>
        <td align="middle"><?php echo gantitgl($datcari['tgl_retur']); ?></td>
        <td align="middle"><?php echo $datcari['no_retur']; ?></td>
        <td align="middle"><?php echo $datcari['nm_sup']; ?></td>
        <td align="middle"><?php echo $datcari['tot_qty'] ?></td>
        <td><button onclick="document.getElementById('no_tran').value='<?=$datcari['no_retur']?>';
        document.getElementById('tgl_tran').value='<?=$datcari['tgl_retur']?>';
        document.getElementById('kd_sup').value='<?=$datcari['kd_sup']?>';
        document.getElementById('info1').innerHTML='&nbsp;SUPPLIER <?=$datcari['nm_sup']?>';
        document.getElementById('info2').innerHTML='&nbsp;ALAMAT <?=$datcari['al_sup']?>';
        document.getElementById('info3').innerHTML='&nbsp;SALES <?=$datcari['nm_sales']?>';
        document.getElementById('info4').innerHTML='&nbsp;CONTACK PERSON <?=$datcari['no_telp']?>';
        caribrgretur(1,true);
        " class="btn-primary fa fa-edit" style="cursor: pointer;font-size: 10pt" title="edit"></button></td> 
      </tr>
      <?php  
      } 
    ?>
  </table>

  <?php if ($no>=5) { ?>
  <nav aria-label="Page navigation example" style="margin-top:1px;font-size: 8pt;">
    <ul class="pagination justify-content-start">
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
        <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinoretur(1, true)">First</a></li>
        <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carinoretur(<?php echo $link_prev; ?>, true)">&laquo;</a></li>
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
        <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinoretur(<?php echo $i; ?>, true)"><?php echo $i; ?></a></li>
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
        <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinoretur(<?php echo $link_next; ?>, true)" style="cursor: pointer">&raquo;</a></li>
        <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinoretur(<?php echo $jumlah_page; ?>, true)" style="cursor: pointer">Last</a></li>
      <?php
      }
      ?>
    </ul>
  </nav>
<?php } ?>
</div>  

<script>
  function setcari(subcari,cari){
    var kom=String.fromCharCode(39);
    return subcari+" '"+cari+kom;
  }
</script>
<?php 
  mysqli_close($conretur);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>