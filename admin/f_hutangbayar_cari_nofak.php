<?php
  $keyword = $_POST['keyword'];
  $keyword2 = $_POST['keyword2'];
  ob_start();
?>
<style>
  
  th {
    border: 1px solid lightgrey;
    padding: 5px;
  }
  td.tt {
   padding: 5px; 
  }
</style>
<div class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;max-height: 500px;min-height: 150px">
    <table class="table-hover table-bordered" style="font-size:9pt; width:100%;border-collapse: collapse;white-space: nowrap">
      <tr align="middle" class="yz-theme-l3">
        <th width="3%" style="padding:2px">NO.</th>
        <th>NO.FAKTUR &nbsp;<button class="btn-primary" type="button" style="padding: 3px;width: 30px" id="btn-okfaktur"><i class="fa fa-search"></i></button>
          <div class="row">
            <div class="col">
              <div id="boxokfaktur" class="container" style="display:none;position: absolute;z-index: 1;">
                <div class="input-group" style="width: 250px;margin-top: 26px">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="okd_faktur" name="okd_faktur" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="No. Faktur" onkeypress="if(event.keyCode==13){document.getElementById('cari_nofak').value='beli_bay.no_fak LIKE ;%'+this.value+'%';carinofak(1,true);}">
                  <div class="input-group-btn w3-card-4">
                    <button class="btn btn-primary" onclick="
                      document.getElementById('cari_nofak').value='';document.getElementById('boxokfaktur').style.display='none';carinofak(1,true);
                      " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                    </button>
                  </div>    
                </div>  
              </div>
            </div>
          </div>    
            <script>
              $(document).ready(function(){
                $("#btn-okfaktur").click(function(){
                  $("#boxokfaktur").slideToggle("fast");
                  $("#boxoksup").slideUp("fast");
                  $("#okd_faktur").focus();
                });
              });
            </script>
        </th>
        <th>TGL.FAKTUR</th>
        <th width="40%">SUPPLIER &nbsp;<button class="btn-primary" type="button" style="padding: 3px;width: 30px" id="btn-oksup"><i class="fa fa-search"></i></button>
          <div class="row">
            <div class="col">
              <div id="boxoksup" class="container" style="display:none;position: absolute;z-index: 1;">
                <div class="input-group" style="width: 250px;margin-top: 26px">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="okd_sup" name="okd_sup" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Supplier" onkeypress="if(event.keyCode==13){document.getElementById('cari_nofak').value='supplier.nm_sup LIKE ;%'+this.value+'%';carinofak(1,true);}">
                  <div class="input-group-btn w3-card-4">
                    <button class="btn btn-primary" onclick="
                      document.getElementById('cari_nofak').value='';document.getElementById('boxoksup').style.display='none';carinofak(1,true);
                      " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                    </button>
                  </div>    
                </div>  
              </div>
            </div>
          </div>    
            <script>
              $(document).ready(function(){
                $("#btn-oksup").click(function(){
                  $("#boxoksup").slideToggle("fast");
                  $("#boxokfaktur").slideUp("fast");
                  $("#okd_sup").focus();
                });
              });
            </script>
        </th>
        <th>TGL.TEMPO</th>
        <th>SISA HUTANG</th>
        <th width="1%">OPSI</th>
      </tr>
      <?php
      session_start();
      include "config.php";
      $connect=opendtcek();
      $kd_toko=$_SESSION['id_toko'];
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 12; // Jumlah data per halamannya
      $limit_start = ($page - 1) * $limit;
      // echo '$limit_start='.$limit_start;
      $subtot=0;
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $param = trim(mysqli_real_escape_string($connect, $keyword));
        $params2 = trim(mysqli_real_escape_string($connect, $keyword2));
        if (!empty($param)){
          $x=explode(';', $param);
          $x1=$x[0];
          $x2="'".$x[1]."'";
          $params=$x1.$x2;  
        }else{$params='';}
         
        if ($params=="") {   
          $sql1 = mysqli_query($connect, "SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tgl_tran,beli_bay.tot_beli,beli_bay.saldo_hutang,beli_bay.byr_hutang,beli_bay.saldo_awal,beli_bay.tgl_jt,supplier.nm_sup FROM beli_bay
               LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
               WHERE beli_bay.kd_toko='$kd_toko' AND beli_bay.ket='TEMPO' $params2 ORDER BY beli_bay.no_urut ASC LIMIT $limit_start, $limit");
          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay WHERE kd_toko='$kd_toko' AND ket='TEMPO' $params2 ORDER BY no_urut");
          $sql3 = mysqli_query($connect, "SELECT SUM(saldo_hutang) AS jumtot FROM beli_bay WHERE kd_toko='$kd_toko' AND ket='TEMPO' $params2 ORDER BY no_urut");
        }
        else {
          $sql1 =mysqli_query($connect, "SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tgl_tran,beli_bay.saldo_hutang,beli_bay.byr_hutang,beli_bay.tgl_jt,beli_bay.saldo_awal,beli_bay.tot_beli,supplier.nm_sup FROM beli_bay
               LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
               WHERE $params AND beli_bay.kd_toko='$kd_toko' AND beli_bay.ket='TEMPO' $params2 ORDER BY beli_bay.no_urut ASC LIMIT $limit_start, $limit");
          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay 
            LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
            WHERE $params AND kd_toko='$kd_toko' AND ket='TEMPO' $params2");
          $sql3 = mysqli_query($connect, "SELECT SUM(saldo_hutang) AS jumtot FROM beli_bay 
            LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
            WHERE $params AND kd_toko='$kd_toko' AND ket='TEMPO' $params2 "); 
        } 
        $get_jumlah = mysqli_fetch_array($sql2);
      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        // $id_apt=$_SESSION['id_apt'];
        $sql1 = mysqli_query($connect, "SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tgl_tran,beli_bay.saldo_hutang,beli_bay.byr_hutang,beli_bay.tot_beli,beli_bay.saldo_awal,supplier.nm_sup FROM beli_bay
                 LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
                 WHERE beli_bay.kd_toko='$kd_toko' ORDER BY beli_bay.no_urut ASC LIMIT $limit_start, $limit");
        $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay WHERE kd_toko='$kd_toko'  ORDER BY no_urut");
              
        $get_jumlah = mysqli_fetch_array($sql2);
        
      }

      $tot=0;$sub=0;   
      $no=$limit_start;
      $datot=mysqli_fetch_assoc($sql3);
      $tot=$datot['jumtot'];
      unset($datot,$sql3);
      
      while($data = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
      ?>
        <tr>
          <td align="right"><?php echo $no ?></td>
          <td align="middle"><?php echo $data['no_fak']; ?></td>
          <td align="middle"><?php echo gantitgl($data['tgl_fak']); ?></td>
          <td align="middle"><?php echo $data['nm_sup']; ?></td>
          <td align="middle"><?php echo gantitgl($data['tgl_jt']); ?></td>
          <td align="right"><?php echo gantitides($data['saldo_hutang']); ?>&nbsp;</td>
          <td>
              <button onclick="
              document.getElementById('no_fak').value='<?=mysqli_escape_string($connect,$data['no_fak']) ?>';
              document.getElementById('tgl_fak').value='<?=mysqli_escape_string($connect,$data['tgl_fak']) ?>';
              document.getElementById('tgl_tran').value='<?=$_SESSION['tgl_set'] ?>';
              document.getElementById('tgl_jt').value='<?=mysqli_escape_string($connect,$data['tgl_jt']) ?>';
              document.getElementById('saldo_awal').value='<?=gantitides($data['tot_beli']); ?>';
              document.getElementById('byr_hutang').value='';
              document.getElementById('saldo_hutang').value='<?=gantitides(mysqli_escape_string($connect,$data['saldo_hutang'])) ?>';
              document.getElementById('fnota').style.display='none'; carihutang(1,true);
              document.getElementById('byr_hutang').focus();
                 " class="btn-primary fa fa-edit" style="cursor: pointer; border-style;font-size: 12pt" title="Edit Data">
              </button>     
          </td>    
        </tr>   
      <?php  
      } 
      ?>
      <tr align="right" class="yz-theme-l3">
        <td class="tt" colspan="5">TOTAL SISA HUTANG SUPPLIER &nbsp;</td>
        <td class="tt"><?=gantitides($tot)?></td>
        <td class="tt"></td>
      </tr>
    </table> 
  </div>
  
    <nav  aria-label="Page navigation example" style="font-size: 8pt;margin-top: 10px;">
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
          <li><a class="page-link yz-theme-d1" style="cursor: pointer;" href="javascript:void(0);" onclick="carinofak(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer;" href="javascript:void(0);" onclick="carinofak(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinofak(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>

        <?php
        }
        ?>
        
        <!-- LINK NEXT AND LAST -->
        <?php
        if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir

        ?>
          <li class="page-item disabled " ><a class="page-link yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;">&raquo;</a></li>
          <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;">Last</a></li>
        <?php
        }else{ // Jika Bukan page terakhir
          $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
        ?>
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinofak(<?php echo $link_next; ?>, false)" style="cursor: pointer;">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinofak(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
  <?php mysqli_close($connect);?>
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>