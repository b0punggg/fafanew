<?php
  $no_fakjual = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();

  include "config.php";
  session_start();
  $connect=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
  $nm_pel='';$al_pel='';$no_telp='-';
  $cek=mysqli_query($connect,"SELECT mas_jual.kd_pel,pelanggan.nm_pel,pelanggan.al_pel,pelanggan.nm_pel,pelanggan.no_telp from mas_jual LEFT JOIN pelanggan ON mas_jual.kd_pel=pelanggan.kd_pel where kd_toko='$kd_toko' AND no_fakjual='$no_fakjual'");
  if(mysqli_num_rows($cek)>0){
    $data=mysqli_fetch_assoc($cek);
    $nm_pel=$data['nm_pel'];
    $al_pel=$data['al_pel'];
    $no_telp=$data['no_telp'];
  }
  unset($cek,$data); 
?>
<div class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 430px ;background-color:linear-gradient(565deg, #FAFAD2 30%, white 100%);">
  <h6><center>Transaksi pembayaran piutang pelanggan <?=$nm_pel?></center></h6>
  <h6><center>Alamat: <?=$al_pel .' , No.telp/Hp : '.$no_telp?></center></h6>
    <table class=" table-sm table-bordered table-hover" style="font-size:10pt; width: 100%">
      <tr align="middle" class="yz-theme-l1">
        <th width="3%">NO.</th>
        <th>TGL. BAYAR</th>
        <th>SISA AWAL</th>
        <th>BAYAR</th>
        <th>SISA AKHIR</th> 
        <th>KETERANGAN</th> 
        <th width="2%">OPSI</th>
      </tr>
      <?php
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 10;
      $limit_start = ($page - 1) * $limit;
         
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $params = mysqli_real_escape_string($connect, $no_fakjual);
        // $pecah=explode(';', $params);
        // $no_fakjual=strtoupper($pecah[0]);
        // $tgl_fakjual=gantitglsave($pecah[1]);
        // echo $tgl_fak;

        if ($params=="") {   
          $sql = mysqli_query($connect, "SELECT * from mas_jual_hutang WHERE kd_toko=''
              ORDER BY no_urut ASC LIMIT $limit_start, $limit");
          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_jual_hutang WHERE kd_toko='' ORDER BY no_urut");
        }
        else {
          $sql =mysqli_query($connect, "SELECT * from mas_jual_hutang 
              WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'
              ORDER BY no_urut ASC LIMIT $limit_start, $limit");
          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");  
        } 
        $get_jumlah = mysqli_fetch_array($sql2);

      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        // $id_apt=$_SESSION['id_apt'];
        $sql =mysqli_query($connect, "SELECT * from mas_jual_hutang 
                WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'
                ORDER BY no_urut ASC LIMIT $limit_start, $limit");
        $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_jual_hutang WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");  
              
        $get_jumlah = mysqli_fetch_array($sql2);
      }

      $no=$limit_start;$max=0;$tgl_jual='0000-00-00';$kd_pel='';$tot=0;
      $totlaba=0;$totmodal=0;
      while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
        $tgl_jual=$data['tgl_jual'];$kd_pel=$data['kd_pel'];      
        $tot=$tot+$data['byr_hutang'];
        $totmodal=$totmodal+$data['modal'];
        $totlaba=$totlaba+$data['laba'];
        if($data['trf']=='TRANSFER'){
          $piltrf='TRANSFER';
          $cektf =true;
        }else{
          $piltrf='';
          $cektf =false;
        }
      ?>
        <tr>
          <td align="right"><?php echo $no ?></td>
          <td align="center"><?php echo gantitgl($data['tgl_tran']); ?></td>
          <td align="right"><?php echo gantitides($data['saldo_awal']); ?></td>
          <td align="right"><?php echo gantitides($data['byr_hutang']); ?></td>
          <td align="right"><?php echo gantitides($data['saldo_hutang']); ?></td>
          <td align="center"><?php echo $data['ket']; ?></td>
          <!-- <td align="right"><?php echo gantitides($data['modal']); ?></td>
          <td align="right"><?php echo gantitides($data['laba']); ?></td> -->
             <?php 
              $sql3=mysqli_query($connect,"SELECT MAX(no_urut) AS maxid from mas_jual_hutang where no_fakjual='$no_fakjual'");
              $id=mysqli_fetch_array($sql3);
              $maxid=$id['maxid'];
              unset($sql3);unset($id);
              if($data['no_urut']==$maxid && $no>1){
              ?>
              <td>      
                 <?php $param=mysqli_escape_string($connect,$data['no_urut']).';'.mysqli_escape_string($connect,$data['no_fakjual']); ?>
                 <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){hapuspiutang('<?=$param?>')}" class="btn-danger fa fa-trash" style="cursor: pointer;font-size: 12pt" title="Hapus Data"></button>      
              </td>  
              <?php } ?>  
        </tr>  
      <?php  
      }
      ?>
      <tr align="right" class="yz-theme-d2">
        <th colspan="3" > TOTAL</th>
        <th ><?=gantitides($tot) ?></th>
        <th colspan="4"></th>
        <!-- <th></th>
        <th ><?=gantitides($totmodal) ?></th>
        <th ><?=gantitides($totlaba) ?></th>
        <th></th> -->
      </tr>
      
    </table> 
  </div>
  <script>document.getElementById("linkcetak").setAttribute("href","f_piutangbayar_cetak.php?pesan=<?=$no_fakjual.';'.$tgl_jual.';'.$kd_pel?>")</script>

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
          <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="caripiutang(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="caripiutang(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="caripiutang(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="caripiutang(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="caripiutang(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
    
<?php
  mysqli_close($connect);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>