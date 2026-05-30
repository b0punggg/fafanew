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
        <th width="4%">NO.</th>
        <th>NAMA BARANG</th>
        <th width="10%">HRG. JUAL</th>
        <th width="8%">QTY</th>
        <th width="8%">DISC</th>
        <th width="10%">SUB.TOT</th>
        <th width="3%" colspan="2">OPSI</th>
      </tr>
      <?php
      include "config.php";
      session_start();
      $concaripakbrg=opendtcek();
      $kd_toko=$_SESSION['id_toko'];
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 15; // Jumlah data per halamannya
      $limit_start = ($page - 1) * $limit;
      // echo '$limit_start='.$limit_start;
      if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
        $params = mysqli_real_escape_string($concaripakbrg, $keyword);
        $param=$params;   
          if ($params=="") {   
            $sql1 = mysqli_query($concaripakbrg, "SELECT * FROM paket_brg
                 WHERE kd_toko='' ORDER BY kd_paket DESC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($concaripakbrg, "SELECT COUNT(*) AS jumlah FROM paket_brg WHERE kd_toko='' ORDER BY kd_paket DESC");
          }
          else {
            $sql1 =mysqli_query($concaripakbrg, "SELECT paket_brg.no_urut,paket_brg.kd_paket,paket_brg.qty_brg,paket_brg.kd_brg,paket_brg.kd_sat,paket_brg.disc1,mas_brg.nm_brg FROM paket_brg
              LEFT JOIN mas_brg ON paket_brg.kd_brg=mas_brg.kd_brg 
              WHERE paket_brg.kd_paket = '$param' AND paket_brg.kd_toko='$kd_toko' ORDER BY paket_brg.kd_paket ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($concaripakbrg, "SELECT COUNT(*) AS jumlah FROM paket_brg WHERE kd_paket = '$param' AND kd_toko='$kd_toko' "); 
          } 
        $get_jumlah = mysqli_fetch_array($sql2);
      }
      
      // hitung total
      $sql3=mysqli_query($concaripakbrg, "SELECT paket_brg.no_urut,paket_brg.kd_paket,paket_brg.qty_brg,paket_brg.kd_brg,paket_brg.kd_sat,paket_brg.disc1,mas_brg.nm_brg FROM paket_brg
        LEFT JOIN mas_brg ON paket_brg.kd_brg=mas_brg.kd_brg
        WHERE paket_brg.kd_paket = '$param' AND paket_brg.kd_toko='$kd_toko' ORDER BY paket_brg.kd_paket ASC ");

      $tot=0;$hrgtot=0;$disctot=0;$totdisc=0;$tota=0;
      while($dasql=mysqli_fetch_assoc($sql3)){
        $hrgtot=carihrgjual(mysqli_real_escape_string($concaripakbrg,$dasql['kd_brg']),mysqli_real_escape_string($concaripakbrg,$dasql['kd_sat']));
        $disctot=$hrgtot-($hrgtot*(mysqli_real_escape_string($concaripakbrg,$dasql['disc1'])/100));
        //$disctot=$hrgtot-$dasql['disc1'];
        $tot=$tot+(round($disctot,2)*mysqli_real_escape_string($concaripakbrg,$dasql['qty_brg']));
        $totdisc=$totdisc+$hrgtot*(mysqli_real_escape_string($concaripakbrg,$dasql['disc1'])/100);
        //$totdisc=$totdisc+$dasql['disc1'];
        $tota=$tota+$hrgtot;
      }
      //-----


      $subtot=0;
      $no=$limit_start;
      $disc1=0;$hrg_jual=0;
      while($data = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
        $kd_sat=mysqli_real_escape_string($concaripakbrg,$data['kd_sat']);
        $kd_brg=mysqli_real_escape_string($concaripakbrg,$data['kd_brg']);
        $hrg_jual=carihrgjual($kd_brg,$kd_sat);
        $disc1=$hrg_jual-($hrg_jual*($data['disc1']/100));
        //$disc1=$hrg_jual-$data['disc1'];
        $subtot=$disc1*$data['qty_brg'];
        ?>
        <tr style="color:blue">
          <td align="right"><?php echo $no.'.' ?>&nbsp;</td>
          <td align="left">&nbsp;<?php echo $data['nm_brg']; ?></td>
          <td align="middle"><?php echo gantitides($hrg_jual); ?></td>
          <td align="middle"><?php echo gantitides($data['qty_brg']); ?></td>
          <td align="right"><?php echo gantitides($data['disc1']).' %'; ?>&nbsp;</td>
          <td align="right"><?php echo gantitides($subtot); ?>&nbsp;</td>
          <td>
              <button onclick="
              document.getElementById('kd_brg').value='<?=mysqli_escape_string($concaripakbrg,$data['kd_brg']) ?>';
              document.getElementById('nm_brg').value='<?=mysqli_escape_string($concaripakbrg,$data['nm_brg']) ?>';
              document.getElementById('qty_brg').value='<?=mysqli_escape_string($concaripakbrg,$data['qty_brg']) ?>';
              document.getElementById('kd_sat').value='<?=mysqli_escape_string($concaripakbrg,$data['kd_sat']) ?>';
              document.getElementById('nm_sat').value='<?=ceknmkem2(mysqli_escape_string($concaripakbrg,$data['kd_sat']),$concaripakbrg)?>'
                 " class="yz-theme-d2 fa fa-edit" style="cursor: pointer; font-size: 12pt" title="Edit Data">
              </button>     
          </td>  

          <?php $param=mysqli_escape_string($concaripakbrg,$data['no_urut']); ?>
          <td><button class="btn-danger fa fa-trash" style="cursor: pointer; border-style;font-size: 12pt" title="Hapus Data" onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){delpaketbrg('<?=$param?>')}"></button></td>  
        </tr>   
      <?php  
      //$subtot=$no; 
      } 
      ?>
      <tr align="right" class="yz-theme-l1">
        <th colspan="5">TOTAL HARGA PAKET</th>
        <th><?php echo gantitides($tot) ?></th>
        <th colspan="2"></th>
      </tr>
    </table> 
  </div>
  
<div class="row">
  <div class="col">
    <nav  aria-label="Page navigation example" style="font-size: 8pt">
      <ul class="pagination start-content-center">
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
          <li><a class="page-link yz-theme-d2" style="cursor: pointer" href="javascript:void(0);" onclick="caripaketbrg(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="caripaketbrg(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="caripaketbrg(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>

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
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="caripaketbrg(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d2" href="javascript:void(0)" onclick="caripaketbrg(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?> 
      </ul>
    </nav> 
  </div>  
  <div class="col w3-container">
    <?php if ($no>0){ ?>
    <button class="yz-theme-d4 w3-hover-shadow w3-card-2 w3-right" style="cursor:pointer;border-radius:3px;margin-top: 2px;width: 150px;" onclick="
      document.getElementById('formsetdisc').style.display='block';
      document.getElementById('totdisc').focus();
      document.getElementById('totawal').value='<?=gantitides($tota)?>';
      document.getElementById('totdisc').value='<?=gantitides($totdisc)?>';
      ">
      <i class="fa fa-database w3-text-yellow"></i>&nbsp;Set Discount</button>     
  <?php } ?>
  </div>
</div>
<script>
  function delpaketbrg(nourut){
    $.ajax({
      url: 'm_pakethapusbrg_act.php', // File tujuan
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

  function prodisc(){
    $.ajax({
      url: 'm_paketprodisc.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {kd_paket:$("#no_urut").val(),disctotal:$("#totdisc").val()}, 
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

function carihrgbeli($kd_brg,$hub){
  $hrgnet=0;
  $kd_toko=$_SESSION['id_toko'];
  $cek=mysqli_query($hub,"SELECT * FROM beli_brg WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko' AND stok_jual>0 ORDER BY tgl_fak ASC LIMIT 1");
  if (mysqli_num_rows($cek)>=1){
    $gethrg = mysqli_fetch_assoc($cek);  
    $disc1=mysqli_escape_string($hub,$gethrg['disc1'])/100;
    $disc2=mysqli_escape_string($hub,$gethrg['disc2']);
    if ($gethrg['disc1']==0.00 && $gethrg['disc2']==0){
      $jumlah=$gethrg['jml_brg']*$gethrg['hrg_beli'];
      $disc='0.00';
      $hrgnet=mysqli_escape_string($hub,$gethrg['hrg_beli']);
    } else if ($gethrg['disc1'] > 0.00 && $gethrg['disc2']==0) {
      $jumlah=(mysqli_escape_string($hub,$gethrg['hrg_beli'])-(mysqli_escape_string($hub,$gethrg['hrg_beli'])*$disc1))*mysqli_escape_string($hub,$gethrg['jml_brg']);
      $disc=$gethrg['disc1'].'%';
      $hrgnet=mysqli_escape_string($hub,$gethrg['hrg_beli'])-(mysqli_escape_string($hub,$gethrg['hrg_beli'])*$disc1); 
    } else if ($gethrg['disc1'] == 0.00 && $gethrg['disc2']>0) {
      $jumlah=(mysqli_escape_string($hub,$gethrg['hrg_beli'])-$disc2)*mysqli_escape_string($hub,$gethrg['jml_brg']);
      $disc=gantiti($gethrg['disc2']);
      $hrgnet=mysqli_escape_string($hub,$gethrg['hrg_beli'])-$disc2; 
    }
    $hrgnet=$hrgnet+(($hrgnet*$gethrg['ppn'])/100);
  }

  unset($gethrg);mysqli_free_result($cek);
  return $hrgnet;
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
<!-- Form nota-->
    <div id="formsetdisc" class="w3-modal" style="padding-top:150px;background-color:rgba(1, 1, 1, 0.2);border-style: ridge; ">
      <div class="w3-modal-content w3-card-4 w3-animate-top" style="max-width:400px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

        <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, #4e1358 20%, magenta 60%, yellow 80%);color:white;">&nbsp;<i class="fa fa-search"></i>
          Set discount Paket
        </div>

        <div class="w3-center">
          <span onclick="document.getElementById('formsetdisc').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
      
        <div class="modal-body">
          <div class="w3-row" style="font-size: 11pt">
            <div class="w3-col l4 s12 m4">
              <label for="totawal" class="col-form-label">Harga Awal</label>
            </div>
            <div class="w3-col l8 s12 m8">
              <input id="totawal" name="totawal" class="w3-margin-bottom form-control money" style="text-align: right;font-style: bold" type="text" autofocus="" >
            </div>  

          </div>
          <div class="w3-row" style="font-size: 11pt">
            <div class="w3-col l4 s12 m4">
              <label for="totdisc" class="col-form-label">Total Disc (Rp.)</label>
            </div>
            <div class="w3-col l8 s12 m8">
              <input id="totdisc" name="totdisc" class="w3-margin-bottom form-control money" style="text-align: right;font-style: bold" type="text" autofocus="" >
            </div>    
          </div>
          <div class="w3-row">
            <div class="w3-col">
            <button class="form-control yz-theme-d2" onclick="prodisc();" type="button" style="box-shadow: 1px 1px 5px black;font-size: 12px;cursor: pointer">PROSES</button>
          </div>  
          </div>
          
        </div>
      </div>
    </div>      
<script>
  $(document).ready(function(){
    $('.idsup').mask('IDPEM-00000000');
    $('.telp').mask('0000 00000000000');
    $('.hp').mask('000 00000000000');
    $('.uang').mask('000.000.000.000.000', {reverse: true});
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
    $('.money2').mask("#.##0,00", {reverse: true});
    $('.desimal').mask('000,00', {reverse: true});
    $('.desimal2').mask('00,00', {reverse: true});
    $('.angka').mask('000000', {reverse: true});
  });
</script>    
<?php
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>