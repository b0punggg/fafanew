
<?php
  $no_fak = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();

  include "config.php";
  session_start();
  $connect=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
  $nm_sup='NONE';$tgltempo='00-00-0000';
  $cek=mysqli_query($connect,"SELECT beli_bay.tgl_jt,beli_bay.kd_sup,supplier.nm_sup from beli_bay LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup where beli_bay.kd_toko='$kd_toko' AND beli_bay.no_fak='$no_fak'");
  if(mysqli_num_rows($cek)>0){
    $data=mysqli_fetch_assoc($cek);
    $nm_sup=$data['nm_sup'];
    $tgltempo=$data['tgl_jt'];
  }
  unset($cek,$data);
  
?>

<style>
  th {
  position: sticky;
  top: 0px; 
  /*color:#fff;*/
  /*background-color:#6271c8;*/
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }
  
</style>

<div class="table-responsive " style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 430px">
  <h6 class="yz-theme-d1"><center>Transaksi pembayaran hutang supplier <?=$nm_sup?> , Jatuh Tempo <?=gantitgl($tgltempo)?></center></h6>
    <table class="table-hover" style="font-size:10pt; width: 100%;padding: 1px;border-spacing: 2px;">
      <tr align="middle" class="yz-theme-l4">
        <th width="3%" style="padding: 5px;">NO.</th>
        <th width="7%">TGL. BAYAR</th>
        <th width="15%">SISA AWAL</th>
        <th width="15%">BAYAR</th>
        <th width="15%">SISA AKHIR</th> 
        <th>KETERANGAN</th> 
        <th width="1%">OPSI</th>
      </tr>
      <?php
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 10; // Jumlah data per halamannya
      $limit_start = ($page - 1) * $limit;
      // echo '$limit_start='.$limit_start;
         
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $params = mysqli_real_escape_string($connect, $no_fak);
        // $pecah=explode(';', $params);
        // $no_fakjual=strtoupper($pecah[0]);
        // $tgl_fakjual=gantitglsave($pecah[1]);
        // echo $tgl_fak;

        if ($params=="") {   
          $sql = mysqli_query($connect, "SELECT * from beli_bay_hutang WHERE kd_toko=''
              ORDER BY no_urut ASC LIMIT $limit_start, $limit");
          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay_hutang WHERE kd_toko='' ORDER BY no_urut");
        }
        else {
          $sql =mysqli_query($connect, "SELECT * from beli_bay_hutang
              WHERE no_fak='$no_fak' AND kd_toko='$kd_toko'
              ORDER BY no_urut ASC LIMIT $limit_start, $limit");
          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay_hutang WHERE no_fak='$no_fak' AND kd_toko='$kd_toko'");  
        } 
        $get_jumlah = mysqli_fetch_array($sql2);

      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        // $id_apt=$_SESSION['id_apt'];
        $sql =mysqli_query($connect, "SELECT * from beli_bay_hutang 
                WHERE no_fak='$no_fak' AND kd_toko='$kd_toko'
                ORDER BY no_urut ASC LIMIT $limit_start, $limit");
        $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay_hutang WHERE no_fak='$no_fak' AND kd_toko='$kd_toko'");  
              
        $get_jumlah = mysqli_fetch_array($sql2);
      }

      $no=$limit_start;$max=0;
      while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
        $tgl_fak=$data['tgl_fak'];$kd_sup=$data['kd_sup'];      
      ?>
        <tr>
          <td align="right"><?php echo $no.' ' ?>&nbsp;</td>
          <td align="center"><?php echo gantitgl($data['tgl_tran']).' '; ?>&nbsp;</td>
          <td align="right"><?php echo gantitides($data['saldo_awal']).' '; ?>&nbsp;</td>
          <td align="right"><?php echo gantitides($data['byr_hutang']).' '; ?>&nbsp;</td>
          <td align="right"><?php echo gantitides($data['saldo_hutang']).' '; ?>&nbsp;</td>
          <td align="left">&nbsp;<?php echo $data['via']; ?></td>
             <?php 
              $sql3=mysqli_query($connect,"SELECT MAX(no_urut) AS maxid from beli_bay_hutang where no_fak='$no_fak'");
              $id=mysqli_fetch_array($sql3);
              $maxid=$id['maxid'];
              unset($sql3);unset($id);
              if($data['no_urut']==$maxid && $no>1){
              ?>
          <td>
            <!-- <button class="btn-primary fa fa-edit" style="cursor: pointer; border-style;font-size: 12pt" title="Edit Data" onclick="
              document.getElementById('no_urutbay_beli').value='<?=mysqli_escape_string($connect,$data['no_urut']) ?>';
              document.getElementById('tgl_fak').value='<?=mysqli_escape_string($connect,$data['tgl_fak']) ?>';
              document.getElementById('tgl_tran').value='<?=mysqli_escape_string($connect,$data['tgl_tran']) ?>';
              document.getElementById('saldo_awal').value='<?=gantiti(mysqli_escape_string($connect,$data['saldo_awal'])) ?>';
              document.getElementById('byr_hutang').value='<?=gantiti(mysqli_escape_string($connect,$data['byr_hutang'])) ?>';
              document.getElementById('saldo_hutang').value='<?=gantiti(mysqli_escape_string($connect,$data['saldo_hutang'])) ?>';
              ">EDIT</button> -->
             <?php $param=mysqli_escape_string($connect,$data['no_urut']).';'.mysqli_escape_string($connect,$data['no_fak']); ?>
             <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){ hapushutang('<?=$param ?>'); }" class="btn-danger fa fa-trash" style="cursor: pointer;font-size: 12pt" title="Hapus Data"></button>      
          </td>  
        <?php } ?>  
        </tr>  
      <?php  
      }
      ?>
      <!-- <tr align="right" class="yz-theme-l1">
        <th colspan="7" >SUB TOTAL</th>
        <th ><?=gantiti($tot) ?></th>
        
        <th></th>
      </tr>
      <tr align="right" class="yz-theme-l1">
        <th colspan="7" >GRAND TOTAL</th>
        <th ><?=gantiti($gtot) ?></th>
        
        <th></th>
      </tr> -->

    </table> 
  </div>
  <script>document.getElementById("linkcetak").setAttribute("href","f_hutangbayar_cetak.php?pesan=<?=$no_fak.';'.$tgl_fak.';'.$kd_sup?>")</script>

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
          <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carihutang(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carihutang(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carihutang(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carihutang(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carihutang(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
    
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>