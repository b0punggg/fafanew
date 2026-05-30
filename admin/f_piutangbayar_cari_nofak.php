<?php
  $keyword = $_POST['keyword'];
  $keyword2 = $_POST['keyword2'];
  ob_start();
?>

<div class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 500px;min-height: 150px;">
    <table class="table table-bordered table-sm table-striped table-hover" style="font-size:9pt;">
      <tr align="middle" class="yz-theme-l4">
        <th>NO.</th>
        <th>NO.FAKTUR
          &nbsp;<button class="btn-primary" type="button" style="padding: 3px;width: 30px" id="btn-okpfaktur"><i class="fa fa-search"></i></button>
          <div class="row">
            <div class="col">
              <div id="boxpfaktur" class="container" style="display:none;position: absolute;z-index: 1;">
                <div class="input-group" style="width: 250px;margin-top: 26px">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="okp_faktur" name="okp_faktur" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="No. Faktur" onkeypress="if(event.keyCode==13){document.getElementById('cari_nofak_p').value='AND mas_jual.no_fakjual LIKE ;%'+this.value+'%';carinofak_p(1,true);}">
                  <div class="input-group-btn w3-card-4">
                    <button class="btn btn-primary" onclick="
                      document.getElementById('cari_nofak_p').value='';document.getElementById('boxpfaktur').style.display='none';carinofak_p(1,true);
                      " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                    </button>
                  </div>    
                </div>  
              </div>
            </div>
          </div>    
            <script>
              $(document).ready(function(){
                $("#btn-okpfaktur").click(function(){
                  $("#boxpfaktur").slideToggle("fast");
                  $("#boxokpel").slideUp("fast");
                  $("#okp_faktur").focus();
                });
              });
            </script>
        </th>
        <th>TGL.FAKTUR</th>
        <th>PELANGGAN &nbsp;<button class="btn-primary" type="button" style="padding: 3px;width: 30px" id="btn-okpel"><i class="fa fa-search"></i></button>
          <div class="row">
            <div class="col">
              <div id="boxokpel" class="container" style="display:none;position: absolute;z-index: 1;">
                <div class="input-group" style="width: 250px;margin-top: 26px">
                  <input type="text" class="yz-theme-l4 w3-card-4" id="okd_pel" name="okd_pel" style="border:1px solid black;font-size: 9pt;background-image: url('img/searchico.png');background-repeat: no-repeat;background-position: 10px 3px;padding: 0px 20px 5px 40px;" placeholder="Supplier" onkeypress="if(event.keyCode==13){document.getElementById('cari_nofak_p').value='AND pelanggan.nm_pel LIKE ;%'+this.value+'%';carinofak_p(1,true);}">
                  <div class="input-group-btn w3-card-4">
                    <button class="btn btn-primary" onclick="
                      document.getElementById('cari_nofak_p').value='';document.getElementById('boxokpel').style.display='none';carinofak_p(1,true);
                      " style="border:1px solid black"><i class="fa fa-undo" style="cursor: pointer"></i>
                    </button>
                  </div>    
                </div>  
              </div>
            </div>
          </div>    
            <script>
              $(document).ready(function(){
                $("#btn-okpel").click(function(){
                  $("#boxokpel").slideToggle("fast");
                  $("#boxpfaktur").slideUp("fast");
                  $("#okd_pel").focus();
                });
              });
            </script>
        </th>
        <th>TGL.TEMPO</th>
        <th>SISA PIUTANG</th>
        <th>OPSI</th>
      </tr>
      <?php
      include "config.php";
      session_start();
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

         //echo '$params'.$params;
          if ($params=="") {   
            $sql1 = mysqli_query($connect, "SELECT mas_jual.tgl_jt,mas_jual.no_fakjual,mas_jual.tgl_jual,mas_jual.saldo_hutang,mas_jual.trf,pelanggan.nm_pel FROM mas_jual
                 LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel
                 WHERE mas_jual.kd_toko='$kd_toko' AND mas_jual.kd_bayar='TEMPO' $params2 ORDER BY mas_jual.no_urut ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_jual WHERE kd_toko='$kd_toko' AND kd_bayar='TEMPO' $params2 ORDER BY no_urut");
            $sql3 = mysqli_query($connect, "SELECT SUM(saldo_hutang) AS jumtot FROM mas_jual WHERE kd_toko='$kd_toko' AND kd_bayar='TEMPO' $params2");
          }
          else {
            $sql1 =mysqli_query($connect, "SELECT mas_jual.tgl_jt,mas_jual.no_fakjual,mas_jual.tgl_jual,mas_jual.saldo_hutang,mas_jual.trf,pelanggan.nm_pel FROM mas_jual
                 LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel
                 WHERE mas_jual.kd_toko='$kd_toko' AND mas_jual.kd_bayar='TEMPO' $params $params2 ORDER BY mas_jual.no_urut ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_jual 
              LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel
              WHERE kd_toko='$kd_toko' $params $params2"); 
            $sql3 = mysqli_query($connect, "SELECT SUM(saldo_hutang) AS jumtot FROM mas_jual 
              LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel
              WHERE kd_toko='$kd_toko' $params $params2");
          } 
        $get_jumlah = mysqli_fetch_array($sql2);
      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        // $id_apt=$_SESSION['id_apt'];
        // $sql1 = mysqli_query($connect, "SELECT mas_jual.tgl_jt,mas_jual.no_fakjual,mas_jual.tgl_jual,mas_jual.saldo_hutang,pelanggan.nm_pel FROM mas_jual
        //          LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel
        //          WHERE mas_jual.kd_toko='$kd_toko' AND mas_jual.kd_bayar='TEMPO' ORDER BY mas_jual.no_urut ASC LIMIT $limit_start, $limit");
        // $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_jual WHERE kd_toko='$kd_toko'  ORDER BY no_urut");
              
        // $get_jumlah = mysqli_fetch_array($sql2);
        
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
          <td align="right"><?php echo $no?></td>
          <td align="left"><?php echo $data['no_fakjual']; ?></td>
          <td align="left"><?php echo gantitgl($data['tgl_jual']); ?></td>
          <td align="left"><?php echo $data['nm_pel']; ?></td>
          <td align="middle"><?php echo gantitgl($data['tgl_jt']); ?></td>
          <td align="right"><?php echo gantitides($data['saldo_hutang']); ?>&nbsp;</td>
          <td>
              <button onclick="  
              document.getElementById('nm_pel').value='<?=$data['nm_pel']?>';
              if('<?=$data['nm_pel']?>'=='BUMDES'){
                document.getElementById('linkkirim').style.display='block';
              }else{
                document.getElementById('linkkirim').style.display='none'
              }
              document.getElementById('no_fakjual').value='<?=mysqli_escape_string($connect,$data['no_fakjual']) ?>';
              document.getElementById('tgl_jual').value='<?=mysqli_escape_string($connect,$data['tgl_jual']) ?>';
              document.getElementById('tgl_jt').value='<?=gantitgl($data['tgl_jt']) ?>';
              document.getElementById('tgl_tran').value='<?=$_SESSION['tgl_set'] ?>';
              document.getElementById('byr_hutang').value='';
              document.getElementById('fnotapiutang').style.display='none'; 
              document.getElementById('byr_hutang').focus();caripiutang(1,true);
                 " class="btn-primary fa fa-edit" style="cursor: pointer;font-size: 12pt" title="Edit Data">
              </button>     
          </td>    
        </tr>   
      <?php  
      //$subtot=$no; 
      } 
      ?>
      <tr align="right" class="yz-theme-l3">
        <td colspan="5">TOTAL SISA PIUTANG PELANGGAN &nbsp;</td>
        <td><?=gantitides($tot)?> &nbsp;</td>
        <td></td>
      </tr>
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
          <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinofak_p(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carinofak_p(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinofak_p(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>

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
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinofak_p(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinofak_p(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
  
<?php
  mysqli_close($connect);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>