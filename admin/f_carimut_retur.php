<?php
  $keyword = $_POST['keyword1']; // Ambil data keyword yang dikirim dengan AJAX 
  $key_cari2 = $_POST['keyword2'];
  ob_start();
  // echo 'keyword='.$keyword;
  include "config.php";
  session_start();
  
  $connect=opendtcek();
    $kd_toko=$_SESSION['id_toko'];
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;">
  <div style="border:1px grey solid;color:white;background: linear-gradient(165deg, #4e1358 20%, magenta 60%, white 80%)">&nbsp;<i class="fa fa-television">&nbsp;PENGEMBALIAN / RETURN PEMBELIAN BARANG </i></div>
    <table class="table-hover" style="font-size:9pt;width:100%;">
      <tr align="middle" class="yz-theme-l4">
        <th width="3%">NO</th> 
        <th>TGL. KLR</th>
        <th>KODE TOKO</th>
        <th>SUPPLIER</th> 
        <th>NAMA BARANG</th>
        <th colspan="3">KONVERSI JUMLAH BARANG</th>
      </tr>

      <?php
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 5; // Jumlah data per halamannya
      $limit_start = ($page - 1) * $limit;
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $para1 = mysqli_real_escape_string($connect, $keyword); 
        $para2 = mysqli_real_escape_string($connect, $key_cari2);
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
        $sql=mysqli_query($connect,"SELECT * FROM retur_beli 
             LEFT JOIN supplier ON retur_beli.kd_sup=supplier.kd_sup 
             LEFT JOIN mas_brg ON retur_beli.kd_brg=mas_brg.kd_brg 
             WHERE retur_beli.kd_brg='$para1' 
             ORDER BY retur_beli.tgl_retur ASC LIMIT $limit_start, $limit ");   
        $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM retur_beli WHERE retur_beli.kd_brg='$para1'");
        // $sql3=mysqli_query($connect, "SELECT sum(qty_brg) AS jumjual FROM retur_beli WHERE retur_beli.kd_brg='$para1'");
       $get_jumlah = mysqli_fetch_array($sql2);
      }
      $no=$limit_start;$sub1=0;$sub2=0;$sub3=0;$totr1=0;$totr2=0;$totr3=0;
      $sub11=0;$sub22=0;$sub33=0;$totr11=0;$totr22=0;$totr33=0;
      $stok1='';$stok2='';$stok3='';
      //total jumlah barang
      $sqltotr=mysqli_query($connect,"SELECT * FROM retur_beli 
             LEFT JOIN mas_brg ON retur_beli.kd_brg=mas_brg.kd_brg 
             WHERE retur_beli.kd_brg='$para1' ");   
      while ($datotr=mysqli_fetch_assoc($sqltotr)){
        $brg_msk_hi=$datotr['qty_brg']*konjumbrg2($datotr['kd_sat'],$datotr['kd_brg'],$connect);
        if ($datotr['jum_kem1']>0) {
          $totr1=$totr1+($brg_msk_hi/$datotr['jum_kem1']);
          $totr11=gantitides(round($totr1,2)).' '.$datotr['nm_kem1']; 
        }else{
          $totr11='NONE';
        }
        if ($datotr['jum_kem2']>0) {
          $totr2=$totr2+($brg_msk_hi/$datotr['jum_kem2']);
          $totr22=gantitides(round($totr2,2)).' '.$datotr['nm_kem2']; 
        }else{
          $totr22='NONE';
        }
        if ($datotr['jum_kem3']>0) {
          $totr3=$totr3+($brg_msk_hi/$datotr['jum_kem3']);
          $totr33=gantitides(round($totr3,2)).' '.$datotr['nm_kem3']; 
        }else{
          $totr33='NONE';
        }
      }
      unset($sqltotr,$datotr);
      
      while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
        $brg_msk_hi=$data['qty_brg']*konjumbrg2($data['kd_sat'],$data['kd_brg'],$connect);
        if ($data['jum_kem1']>0) {
          $stok1=gantitides(round($brg_msk_hi/$data['jum_kem1'],2)).' '.$data['nm_kem1']; 
          $sub1=$sub1+($brg_msk_hi/$data['jum_kem1']);
          $sub11=gantitides(round($sub1,2)).' '.$data['nm_kem1']; 
        }else{
          $stok1='NONE';
          $sub11='NONE';
        }
        if ($data['jum_kem2']>0) {
          $stok2=gantitides(round($brg_msk_hi/$data['jum_kem2'],2)).' '.$data['nm_kem2'];  
          $sub2=$sub2+($brg_msk_hi/$data['jum_kem2']);
          $sub22=gantitides(round($sub2,2)).' '.$data['nm_kem2']; 
        }else{
          $stok2='NONE';
          $sub22='NONE';
        }
        if ($data['jum_kem3']>0) {
          $stok3=gantitides(round($brg_msk_hi/$data['jum_kem3'],2)).' '.$data['nm_kem3'];   
          $sub3=$sub3+($brg_msk_hi/$data['jum_kem3']);
          $sub33=gantitides(round($sub3,2)).' '.$data['nm_kem3']; 
        }else{
          $stok3='NONE';
          $sub33='NONE';
        }        
      ?>
        <tr>
          <td align="right" style="border-right:none"><?php echo $no.'.'; ?>&nbsp;</td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo gantitgl($data['tgl_retur']); ?></td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo $data['kd_toko']; ?></td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_sup']; ?></td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_brg']; ?></td>
          <td align="right" class="yz-theme-l3" style="border-left:none;border-right:none"><?php echo $stok1 ?>&nbsp;</td>
          <td align="right" class="yz-theme-l4" style="border-left:none;border-right:none"><?php echo $stok2 ?>&nbsp;</td>
          <td align="right" class="yz-theme-light" style="border-left:none;border-right:none"><?php echo $stok3 ?>&nbsp;</td>
        </tr>
      <?php
      }
      ?>
      <tr class="yz-theme-l4">
        <td colspan="5" style="text-align: right;font-style: bold"><b>SUB JUMLAH RETURN</b>&nbsp;</td>
        <td style="text-align: right"><b><?=$sub11?>&nbsp;</b></td>
        <td style="text-align: right"><b><?=$sub22?>&nbsp;</b></td>
        <td style="text-align: right"><b><?=$sub33?>&nbsp;</b></td>
      </tr>
      <tr class="yz-theme-l3">
        <td colspan="5" style="text-align: right;font-style: bold"><b>TOTAL JUMLAH RETURN</b>&nbsp;</td>
        <td style="text-align: right"><b><?=$totr11?>&nbsp;</b></td>
        <td style="text-align: right"><b><?=$totr22?>&nbsp;</b></td>
        <td style="text-align: right"><b><?=$totr33?>&nbsp;</b></td>
      </tr>
    </table>
</div>

<?php if ($no>=1){ ?>
  <nav  aria-label="Page navigation example" style="margin-top:1px;font-size: 8pt">
    <ul class="pagination pagination-sm justify-content-end">
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
        <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="carimut_ret(1, false)">First</a></li>
        <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="carimut_ret(<?php echo $link_prev; ?>, false)"></a></li>
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
        <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" onclick="carimut_ret(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
        <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="carimut_ret(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
        <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carimut_ret(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
      <?php
      }
      ?>
    </ul>
  </nav>
  <script>document.getElementById('viewmutasiretur').style.display='block';</script>
<?php } else { ?>
  <script>document.getElementById('viewmutasiretur').style.display='none';</script>
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
  mysqli_close($connect);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>