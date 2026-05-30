<?php
  $keyword = $_POST['keyword'];
  ob_start();
?>
<style>
  th {
  position: sticky;
  top: 0px; 
  /*color:#fff;
  background-color:#6271c8;*/
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

<div class="table-responsive" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 500px">
    <table class="table-hover" style="font-size:9pt;width:100%;">
      <tr align="middle" class="yz-theme-l1">
        <th>NO.</th>
        <th>NO.FAKTUR</th>
        <th>TGL.FAKTUR</th>
        <th>SUPPLIER</th>
        <th>TOT.BELI</th>
        <th width="2%">OPSI</th>
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
        $params = mysqli_real_escape_string($connect, $keyword);
        $param='%'.$params.'%';   
          if ($params=="") {   
            $sql1 = mysqli_query($connect, "SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tgl_tran,beli_bay.saldo_hutang,beli_bay.byr_hutang,beli_bay.saldo_awal,beli_bay.ket,beli_bay.kd_sup,supplier.nm_sup FROM beli_bay
                 LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
                 WHERE beli_bay.kd_toko='$kd_toko' ORDER BY beli_bay.no_urut DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay WHERE kd_toko='$kd_toko' ORDER BY no_urut DESC");
          }
          else {
            $sql1 =mysqli_query($connect, "SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tgl_tran,beli_bay.saldo_hutang,beli_bay.byr_hutang,beli_bay.saldo_awal,beli_bay.ket,beli_bay.kd_sup,supplier.nm_sup FROM beli_bay
                 LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
                 WHERE beli_bay.no_fak LIKE '$param' AND beli_bay.kd_toko='$kd_toko' ORDER BY beli_bay.tgl_fak DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay WHERE no_fak LIKE '$param' AND kd_toko='$kd_toko' "); 
          } 
        $get_jumlah = mysqli_fetch_array($sql2);
      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        // $id_apt=$_SESSION['id_apt'];
        $sql1 = mysqli_query($connect, "SELECT beli_bay.no_fak,beli_bay.tgl_fak,beli_bay.tgl_tran,beli_bay.saldo_hutang,beli_bay.byr_hutang,beli_bay.saldo_awal,beli_bay.ket,beli_bay.kd_sup,supplier.nm_sup FROM beli_bay
                 LEFT JOIN supplier ON beli_bay.kd_sup=supplier.kd_sup
                 WHERE beli_bay.kd_toko='$kd_toko' ORDER BY beli_bay.tgl_fak DESC LIMIT $limit_start, $limit");
        $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_bay WHERE kd_toko='$kd_toko' ORDER BY tgl_fak DESC");
              
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
          <td align="middle"><?php echo $data['no_fak']; ?></td>
          <td align="middle"><?php echo gantitgl($data['tgl_fak']); ?></td>
          <td align="middle"><?php echo $data['nm_sup']; ?></td>
          <td align="right"><?php echo gantitides(caritot($data['tgl_fak'],$data['no_fak'],$kd_toko,$connect)); ?></td>
          <td>
              <button onclick="
              document.getElementById('no_fak').value='<?=mysqli_escape_string($connect,$data['no_fak']) ?>';
              document.getElementById('tgl_fak').value='<?=mysqli_escape_string($connect,$data['tgl_fak']) ?>';
              document.getElementById('kd_sup').value='<?=mysqli_escape_string($connect,$data['kd_sup']) ?>';
              document.getElementById('nm_sup').value='<?=mysqli_escape_string($connect,$data['nm_sup']) ?>';
              document.getElementById('keyedit').value='<?=mysqli_escape_string($connect,$data['tgl_fak'].';'.$data['no_fak']) ?>';
              document.getElementById('fnotabeli').style.display='none'; document.getElementById('caribrg').value='';carinota(1,true);
                 " class="btn btn-sm btn-primary fa fa-edit" style="cursor: pointer; font-size: 12pt" title="Edit Data">
              </button>     
          </td>    
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
  
    <nav  aria-label="Page navigation example" class="hrf_arial">
      <ul class="pagination justify-content-center">
        <!-- LINK FIRST AND PREV -->
        <?php
        if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
        ?>
          <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
          <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:20px;padding-right:20px;"><i class="fa fa-caret-left"></i></a></li>
        <?php
        }else{ // Jika page bukan page ke 1
          $link_prev = ($page > 1)? $page - 1 : 1;
        ?>
          <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinofak2(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer;padding-left:20px;padding-right:20px;" href="javascript:void(0);" onclick="carinofak2(<?php echo $link_prev; ?>, false)"><i class="fa fa-caret-left"></i></a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinofak2(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>

        <?php
        }
        ?>
        
        <!-- LINK NEXT AND LAST -->
        <?php
        if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir

        ?>
          <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:20px;padding-right:20px;"><i class="fa fa-caret-right"></i></a></li>
          <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
        <?php
        }else{ // Jika Bukan page terakhir
          $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
        ?>
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinofak2(<?php echo $link_next; ?>, false)" style="cursor: pointer;padding-left:20px;padding-right:20px;"><i class="fa fa-caret-right"></i></a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinofak2(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
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