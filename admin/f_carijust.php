<?php
  $keyword = $_POST['keyword1']; // Ambil data keyword yang dikirim dengan AJAX 
  $key_cari2 = $_POST['keyword2'];
  ob_start();
  // echo 'keyword='.$keyword;
  include "config.php";
  session_start();
  
  $conjust=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;">
  <div style="border:1px grey solid;color:black;">&nbsp;<i class="fa fa-television">&nbsp;ADJUSMENT / PENYESUAI STOK BARANG </i></div>
    <table class="table-hover" style="font-size:9pt;width: 100%;border-collapse: collapse;white-space: nowrap; ">
      <tr align="middle" class="yz-theme-l3">
        <th width="3%">NO</th> 
        <th>TANGGAL</th>
        <th>KODE TOKO</th>
        <th>NAMA BARANG</th>
        <th>KETERANGAN</th>
      </tr>

      <?php
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 10; // Jumlah data per halamannya
      $limit_start = ($page - 1) * $limit;
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $para1 = mysqli_real_escape_string($conjust, $keyword); 
        $para2 = mysqli_real_escape_string($conjust, $key_cari2);
        if (!empty($para2)){
          $xada=strpos($para2,"like");
          if ($xada <> false){
                  $pecah=explode('like', $para2);
            $kunci=$pecah[0];
            $kunci2=$pecah[1];
            $para2=$kunci." like '%".trim($kunci2)."%'";
          } 
        }else{
          $kunci='';
          $kunci2=''; 
        }
         //echo '$para1='.$para1.'<br>';
        // echo '$para2='.$para2.'<br>';
        $sql=mysqli_query($conjust,"SELECT mutasi_adj.tgl_input,mutasi_adj.kd_brg,mutasi_adj.kd_toko,mutasi_adj.ket,mas_brg.nm_brg FROM mutasi_adj
             LEFT JOIN mas_brg ON mutasi_adj.kd_brg=mas_brg.kd_brg 
             WHERE mutasi_adj.kd_brg='$para1' 
             ORDER BY mutasi_adj.tgl_input ASC LIMIT $limit_start, $limit ");   
        $sql2 = mysqli_query($conjust, "SELECT COUNT(*) AS jumlah FROM mutasi_adj WHERE mutasi_adj.kd_brg='$para1'");
        // $sql3=mysqli_query($connect, "SELECT sum(qty_brg) AS jumjual FROM retur_beli WHERE retur_beli.kd_brg='$para1'");
       $get_jumlah = mysqli_fetch_array($sql2);
      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        
      }
      
      $no=$limit_start;$totklr=0;$nmkem='';$nmkem2='';$gtot=0;
      // $datjum=mysqli_fetch_assoc($sql3);
      // $gtot=$datjum['jumjual']  ;
      
      while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
      ?>
        <tr>
          <td align="right" style="border-right:none"><?php echo $no.'.'; ?>&nbsp;</td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo gantitgl($data['tgl_input']); ?></td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo $data['kd_toko']; ?></td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_brg']; ?></td>
          <td align="left" style="border-left:none;border-right:none;color:blue;"><b><?php echo $data['ket']; ?></b></td>
        </tr>
      <?php
      }
      ?>
    </table>
</div>

<?php if ($no>=1){ ?>
  <nav  aria-label="Page navigation example" style="margin-top:1px;font-size: 8pt">
    <ul class="pagination pagination-sm justify-content-start">
      <!-- LINK FIRST AND PREV -->
      <?php
      if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
      ?>
        <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px">First</a></li>
        <li class="page-item disabled "><a class="page-link fa fa-chevron-left yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
      <?php
      }else{ // Jika page bukan page ke 1
        $link_prev = ($page > 1)? $page - 1 : 1;
      ?>
        <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="infojust(1, false)">First</a></li>
        <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="infojust(<?php echo $link_prev; ?>, false)"></a></li>
      <?php
      }
      ?>
      
      <!-- LINK NUMBER -->
      <?php
      $jumlah_page = ceil($get_jumlah['jumlah'] / $limit);
      //$jumlah_page = ceil($jum / $limit);
      $jumlah_number = 1; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
      $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
      $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
      
      for($i = $start_number; $i <= $end_number; $i++){
        $link_active = ($page == $i)? ' class="active"' : '';
      ?>
        <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" onclick="infojust(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
      <?php
      }
      ?>
      
      <!-- LINK NEXT AND LAST -->
      <?php
      if($page == $jumlah_page || $get_jumlah['jumlah']==0){
      //if($page == $jumlah_page || $jum==0){
      ?>
        <li class="page-item disabled " ><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
        <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
      <?php
      }else{ // Jika Bukan page terakhir
        $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
      ?>
        <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="infojust(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
        <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="infojust(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
      <?php
      }
      ?>
    </ul>
  </nav>
 <?php } ?>
<script>
  $(document).on('click', '.hapus_data', function(){
        var id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: "f_pashapus_act.php",
            data: {id:id},
            success: function() {
             caridtpass(1,true); 
             popnew_ok("Data terhapus");
            }
        });
    });
</script>

<?php
  unset($datjum,$sql1,$sql2,$sql3);
  mysqli_close($conjust);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>