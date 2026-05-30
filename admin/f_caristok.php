<?php
  $keyword = $_POST['keyword1']; // Ambil data keyword yang dikirim dengan AJAX 
  $key_cari2 = $_POST['keyword2'];
  ob_start();
  // echo 'keyword='.$keyword;
  include "config.php";
  session_start();
  
  $constok=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
?>

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;">
  <div style="border:1px grey solid;">&nbsp;<i class="fa fa-television">&nbsp;STOK BARANG AKHIR ( PER TOKO ) </i></div>
    <table class="table-hover" style="font-size:9pt;width: 100%;border-collapse: collapse;white-space: nowrap; ">
      <tr align="middle" class="yz-theme-l3">
        <th width="3%">NO</th> 
        <th>KODE TOKO</th>
        <th>NAMA BARANG</th>
        <th colspan="3">KONVERSI STOK BARANG</th>
      </tr>

      <?php
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 5; // Jumlah data per halamannya
      $limit_start = ($page - 1) * $limit;

      //setting tampilan stok 0
      $sqlstok=mysqli_query($constok,"SELECT kode FROM seting WHERE nm_per='tampil_stok'");
      $dtper=mysqli_fetch_assoc($sqlstok);
      $kodestok=$dtper['kode'];
      if ($kodestok==1) {
               $tampil_stok='';
               $tampil_stok1=''; 
               ?>
               <!-- <script>document.getElementById('cektampil').checked=true;</script> -->
               <?php
            } else {
               $tampil_stok  = ' WHERE beli_brg.stok_jual > 0 ';
               $tampil_stok1 = ' AND beli_brg.stok_jual > 0 ';
               ?>
               <!-- <script>document.getElementById('cektampil').checked=false;</script> -->
               <?php
      }
      
      //-----------------------   

      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $para1 = mysqli_real_escape_string($constok, $keyword); 
        $para2 = mysqli_real_escape_string($constok, $key_cari2);
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
        $sql=mysqli_query($constok,"SELECT SUM(beli_brg.stok_jual) AS stok_juals,beli_brg.stok_jual,beli_brg.kd_toko, beli_brg.kd_brg,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.brg_msk,mas_brg.brg_klr FROM beli_brg
            LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
            WHERE beli_brg.kd_brg='$para1' $tampil_stok1
            GROUP BY beli_brg.kd_toko 
            LIMIT $limit_start, $limit");   
        
        $sql2=mysqli_query($constok, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE beli_brg.kd_brg='$para1' $tampil_stok1 GROUP BY beli_brg.kd_toko ");
        $get_jumlah = mysqli_fetch_array($sql2);
      }
      
      $no=$limit_start;$totklr=0;$nmkem='';$nmkem2='';
      $stokakhir1=0;$stokakhir2=0;$stokakhir3=0;

      //utk total stok barang
      $sqltot=mysqli_query($constok,"SELECT SUM(beli_brg.stok_jual) AS stok_juals,beli_brg.stok_jual,beli_brg.kd_toko, beli_brg.kd_brg,beli_brg.jml_brg,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,mas_brg.nm_kem1,mas_brg.nm_kem2,mas_brg.nm_kem3,mas_brg.brg_msk,mas_brg.brg_klr FROM beli_brg
            LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
            WHERE beli_brg.kd_brg='$para1' $tampil_stok1");  
      while ($datjum=mysqli_fetch_assoc($sqltot)){
        if ($datjum['jum_kem1']>0) {
            $stokakhir1=$stokakhir1+round($datjum['stok_juals']/$datjum['jum_kem1'],2);
            $sat1=gantitides($stokakhir1).' '.$datjum['nm_kem1'];
        }else{
          $stokakhir1=0;
          $sat1='NONE';
        }
        if ($datjum['jum_kem2']>0) {
          $stokakhir2=$stokakhir2+round($datjum['stok_juals']/$datjum['jum_kem2'],2);
          $sat2=gantitides($stokakhir2).' '.$datjum['nm_kem2'];
        }else{
          $stokakhir2=0;
          $sat2='NONE';
        }
        if ($datjum['jum_kem3']>0) {
          $stokakhir3=$stokakhir3+round($datjum['stok_juals']/$datjum['jum_kem3'],2);
          $sat3=gantitides($stokakhir3).' '.$datjum['nm_kem3'];
        }else{
          $stokakhir3=0;
          $sat3='NONE';
        }        
      }
      unset($sqltot,$datjum);

      while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
        $brg_msk_hi=$data['stok_juals'];
          //echo '$brg_msk='.$brg_msk_hi.'<br>';
          if ($data['jum_kem1']>0) {
            $stok1=gantitides(round($brg_msk_hi/$data['jum_kem1'],2)).' '.$data['nm_kem1']; 
            // $stokakhir1=$stokakhir1+round($brg_msk_hi/$data['jum_kem1'],2);
            // $sat1=$stokakhir1.' '.$data['nm_kem1'];
          }else{
            $stok1='NONE';
            // $stokakhir1=0;
            // $sat1='NONE';
          }
          if ($data['jum_kem2']>0) {
            $stok2=gantitides(round($brg_msk_hi/$data['jum_kem2'],2)).' '.$data['nm_kem2'];  
            // $stokakhir2=$stokakhir2+round($brg_msk_hi/$data['jum_kem2'],2);
            // $sat2=$stokakhir2.' '.$data['nm_kem2'];
          }else{
            $stok2='NONE';
            // $stokakhir2=0;
            // $sat2='NONE';
          }
          if ($data['jum_kem3']>0) {
            $stok3=gantitides(round($brg_msk_hi/$data['jum_kem3'],2)).' '.$data['nm_kem3'];   
            // $stokakhir3=$stokakhir3+round($brg_msk_hi/$data['jum_kem3'],2);
            // $sat3=$stokakhir3.' '.$data['nm_kem3'];
          }else{
            $stok3='NONE';
            // $stokakhir3=0;
            // $sat3='NONE';
          }        
      ?>
        <tr>
          <td align="right" style="border-right:none"><?php echo $no.'.'; ?>&nbsp;</td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo $data['kd_toko']; ?></td>
          <td align="middle" style="border-left:none;border-right:none"><?php echo $data['nm_brg']; ?></td>
          <td align="right" class="yz-theme-l4"><?php echo $stok1;?>&nbsp;</td>
          <td align="right" class="yz-theme-light"><?php echo $stok2; ?>&nbsp;</td>
          <td align="right"><?php echo $stok3; ?>&nbsp;</td>    
        </tr>
      <?php
      }
      ?>
      <tr class="yz-theme-l3">
        <td colspan="3" style="text-align: right;font-style: bold;padding: 3px"><b>STOK AKHIR &nbsp;</b></td>
        <td align="right"><b><?php echo $sat1;?>&nbsp;</b></td>
        <td align="right"><b><?php echo $sat2;?>&nbsp;</b></td>
        <td align="right"><b><?php echo $sat3;?>&nbsp;</b></td>    
      </tr>
      <!-- <tr class="yz-theme-l1">
        <td colspan="3" style="text-align: center;font-style: bold"><b>TOTAL STOK ( SATUAN TERKECIL )</b></td>
        <td style="text-align: right;"><b><?=gantitides($gtot)?></b></td>
        <td style="text-align: center;"><b><?=$nmkem2?></b></td>
      </tr> -->
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
        <li><a class="page-link yz-theme-d1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="infostok(1, false)">First</a></li>
        <li><a class="page-link fa fa-chevron-left yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" href="javascript:void(0);" onclick="infostok(<?php echo $link_prev; ?>, false)"></a></li>
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
        <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px" onclick="infostok(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
        <li class="page-item"><a class="page-link fa fa-chevron-right yz-theme-l1" href="javascript:void(0)" onclick="infostok(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px"></a></li>
        <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="infostok(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 2px 8px 2px 8px">Last</a></li>
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
  mysqli_close($constok);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>