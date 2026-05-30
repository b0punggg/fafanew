<?php
  $keyword = $_POST['keyword'];
  ob_start();
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
    padding: 3px;
  }
  table {
    border-spacing: 2px;
  }
</style>

<div class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 490px">
    <table class="table-hover" style="font-size:9pt;width:100%;">
      <tr align="middle" class="yz-theme-l2">
        <th width="3%">NO.</th>
        <th>NAMA PAKET</th>
        <th width="3%" colspan="2">OPSI</th>
      </tr>
      <?php
      include "config.php";
      session_start();
      $concaripak=opendtcek();
      $kd_toko=$_SESSION['id_toko'];
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 10; // Jumlah data per halamannya
      $limit_start = ($page - 1) * $limit;
      // echo '$limit_start='.$limit_start;
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $params = mysqli_real_escape_string($concaripak, $keyword);
        $param='%'.$params.'%';   
          if ($params=="") {   
            $sql1 = mysqli_query($concaripak, "SELECT * FROM paket_mas
                 WHERE kd_toko='$kd_toko' ORDER BY no_urut DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($concaripak, "SELECT COUNT(*) AS jumlah FROM paket_mas WHERE kd_toko='$kd_toko' ORDER BY no_urut DESC");
          }
          else {
            $sql1 =mysqli_query($concaripak, "SELECT* FROM paket_mas
                 WHERE nm_paket LIKE '$param' AND kd_toko='$kd_toko' ORDER BY no_urut DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($concaripak, "SELECT COUNT(*) AS jumlah FROM paket_mas WHERE nm_paket LIKE '$param' AND kd_toko='$kd_toko' "); 
          } 
        $get_jumlah = mysqli_fetch_array($sql2);
      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        // $id_apt=$_SESSION['id_apt'];
        $sql1 = mysqli_query($concaripak, "SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tgl_tran,beli_bay.saldo_hutang,beli_bay.byr_hutang,beli_bay.saldo_awal,beli_bay.ket,beli_bay.kd_sup,supplier.nm_sup FROM beli_bay
                 LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
                 WHERE beli_bay.kd_toko='$kd_toko' ORDER BY beli_bay.tgl_fak DESC LIMIT $limit_start, $limit");
        $sql2 = mysqli_query($concaripak, "SELECT COUNT(*) AS jumlah FROM beli_bay WHERE kd_toko='$kd_toko' ORDER BY tgl_fak DESC");
              
        $get_jumlah = mysqli_fetch_array($sql2);
        
      }
      
      $tot=0;$sub=0;$saldoawal=0;   
      $no=$limit_start;
      
      while($data = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
        // if($data['ket']=='TUNAI'){
        //   $saldo_awal=$data['saldo_awal'];
        // }else{
        //   $saldo_awal=carisaldo($data['no_fak'],$data['tgl_fak'],$kd_toko);
        // }
      ?>
        <tr>
          <td align="right"><?php echo $no ?></td>
          <td align="middle"><?php echo $data['nm_paket']; ?></td>
          <td>
              <button onclick="
              document.getElementById('no_urut').value='<?=mysqli_escape_string($concaripak,$data['no_urut']) ?>';
              document.getElementById('nm_paket').value='<?=mysqli_escape_string($concaripak,$data['nm_paket']) ?>';caripaketbrg(1,true);
                 " class="yz-theme-d2 fa fa-edit" style="cursor: pointer; font-size: 12pt" title="Edit Data">
              </button>     
          </td>  
          <?php $param=mysqli_escape_string($concaripak,$data['no_urut']); ?>
          <td><button class="btn-danger fa fa-trash" style="cursor: pointer; border-style;font-size: 12pt" title="Hapus Data" onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){delpaket('<?=$param?>')}"></button></td>  
        </tr>   
      <?php  
      //$subtot=$no; 
      } 
      ?>
      <!-- <tr align="middle" class="yz-theme-l1">
        <th colspan="7">TOTAL <?=gantiti($tot) ?> ITEM</th>
      </tr> -->
    </table> 
  </div>
  
    <nav  aria-label="Page navigation example" style="font-size: 8pt">
      <ul class="pagination justify-content-center">
        <!-- LINK FIRST AND PREV -->
        <?php
        if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
        ?>
          <li class="page-item disabled "><a class="page-link  yz-theme-d2" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
          <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&laquo;</a></li>
        <?php
        }else{ // Jika page bukan page ke 1
          $link_prev = ($page > 1)? $page - 1 : 1;
        ?>
          <li><a class="page-link yz-theme-d2" style="cursor: pointer" href="javascript:void(0);" onclick="caripaket(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="caripaket(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="caripaket(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>

        <?php
        }
        ?>
        
        <!-- LINK NEXT AND LAST -->
        <?php
        if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir

        ?>
          <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&raquo;</a></li>
          <li class="page-item disabled "><a class="page-link yz-theme-d2" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
        <?php
        }else{ // Jika Bukan page terakhir
          $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
        ?>
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="caripaket(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d2" href="javascript:void(0)" onclick="caripaket(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 

<script>
  function delpaket(nourut){
    $.ajax({
      url: 'm_pakethapus_act.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keydel:nourut}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        $("#viewhapus").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
</script>    
<?php 
function carisaldo($no_fak,$tgl_fak,$kd_toko){
      $connect2 = opendtcek();
      $cek=mysqli_query($connect2,"SELECT saldo_awal FROM beli_bay where no_fak='$no_fak' and tgl_fak='$tgl_fak' and kd_toko='$kd_toko' ORDER BY no_urut ASC LIMIT 1");
      $getsld = mysqli_fetch_array($cek);
      return mysqli_escape_string($connect2,$getsld['saldo_awal']);
      mysqli_close($connect2);unset($cek,$getsld);
  }
function caritot($tgl_fak,$no_fak,$kd_toko,$hub){
 //untuk total pebelian semua gtot
      $gtots=0;$disc1s=0;$disc2s=0;$jmlsubs=0;
      $cekbeli=mysqli_query($hub,"SELECT * FROM beli_brg WHERE kd_toko='$kd_toko' AND beli_brg.no_fak='$no_fak' AND beli_brg.tgl_fak='$tgl_fak' AND INSTR(beli_brg.ket,'MUTASI')=0");
      while ($datcekbeli=mysqli_fetch_assoc($cekbeli)){
        $disc1s=mysqli_escape_string($hub,$datcekbeli['disc1'])/100;
        $disc2s=mysqli_escape_string($hub,$datcekbeli['disc2']);
        if ($datcekbeli['disc1']=='0.00'){
          // echo gantiti($data['disc2']);
          $jmlsubs=(mysqli_escape_string($hub,$datcekbeli['hrg_beli'])-$disc2s)*mysqli_escape_string($hub,$datcekbeli['jml_brg']);
        }else{
          $jmlsubs=(mysqli_escape_string($hub,$datcekbeli['hrg_beli'])-(mysqli_escape_string($hub,$datcekbeli['hrg_beli'])*$disc1s))*mysqli_escape_string($hub,$datcekbeli['jml_brg']);
        }
        if ($datcekbeli['disc1']=='0.00' && $datcekbeli['disc2']=='0'){
          $jmlsubs=mysqli_escape_string($hub,$datcekbeli['jml_brg'])*mysqli_escape_string($hub,$datcekbeli['hrg_beli']);
        }     
        $gtots=$gtots+$jmlsubs;
      } 
      unset($cekbeli,$datcekbeli);
      return $gtots;
  //  
} 

?>  
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>