<?php
  error_reporting(0); // Disable error reporting untuk mencegah error muncul di JSON
  ob_start();
  
  // Pastikan session sudah dimulai sebelum include config
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
?>
<script>
  function backangkades(b)
  {
    var _minus = false;
    b = b.toString();
    b=b.replace("-","");
    panjang = b.length;
    for (i = 0; i < panjang; i++){
      b = b.replace(".","");
    }
    b = b.replace(",",".");
    if (_minus) b = "-" + b ;
    return b;
  }

  function angkatitik(b)
  {
    var _minus = false;
    if (b<0) _minus = true;
      b = b.toString();
      b=b.replace(".","");
      b=b.replace("-","");
      c = "";
      panjang = b.length;
      j = 0;
      for (i = panjang; i > 0; i--){
        j = j + 1;
        if (((j % 3) == 1) && (j != 1)){
          c = b.substr(i-1,1) + "." + c;
        } else {
          c = b.substr(i-1,1) + c;
        }
      }
    if (_minus) c = "-" + c ;
      return c;
  }
  
  function angkatitikdes(b)
  {
    var _minus = false;
    if (b<0) _minus = true;
      b = b.toString();
      b=b.replace(".",",");
      b=b.replace(".","");
      b=b.replace("-","");
      c = "";
      //cek ada koma tdk
      koma=b.search(",");
      if (koma>0) {
        cc=b;
        b=b.substr(0,koma);
        xkoma=cc.substr(koma,3);
      } else {
        xkoma=",00";
      }
      panjang = b.length;
      j = 0;
      for (i = panjang; i > 0; i--){
        j = j + 1;
        if (((j % 3) == 1) && (j != 1)){
          c = b.substr(i-1,1) + "." + c;
        } else {
          c = b.substr(i-1,1) + c;
        }
      }
    if (_minus) c = "-" + c ;
      return c + xkoma;
  }  
</script>  
<style>
  .retur-table {
    font-size: 9pt;
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
  }
  .retur-table th {
    position: sticky;
    top: 0px;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    border: 1px solid #ddd;
    padding: 6px 4px;
    text-align: center;
    background-color: #f0f8ff;
    font-weight: bold;
    vertical-align: middle;
  }
  .retur-table td {
    border: 1px solid #ddd;
    padding: 6px 4px;
    vertical-align: middle;
  }
  .retur-table tbody tr:hover {
    background-color: #f5f5f5;
  }
  .retur-table tbody tr:nth-child(even) {
    background-color: #fafafa;
  }
  .retur-table tbody tr:nth-child(even):hover {
    background-color: #f0f0f0;
  }
</style>
<div class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;border-style: ridge;border-color:white;max-height: 430px;min-height: 100px">
    <table class="retur-table table-bordered table-hover">
      <thead>
      <tr align="middle" class="yz-theme-l3">
        <th style="width: 3%;min-width: 40px;">NO.</th>
        <th style="width: 5%;min-width: 80px;">TGL.</th>
        <th style="width: 12%;min-width: 120px;">FAKTUR JUAL</th>
        <th style="min-width: 200px;">NAMA BARANG</th>
        <th style="width: 5%;min-width: 60px;">QTY</th>
        <th style="width: 3%;min-width: 60px;">SATUAN</th>
        <!-- <th >HPP</th> -->
        <th style="width: 8%;min-width: 100px;">HARGA JUAL</th>
        <th style="width: 8%;min-width: 100px;">DISC ITEM</th>
        <th style="width: 8%;min-width: 100px;">DISC NOTA</th>
        <th style="width: 8%;min-width: 100px;">VOUCHER</th>
        <th style="width: 8%;min-width: 100px;">NETTO</th>
        <th style="width: 8%;min-width: 100px;">SUB TOTAL</th>
        <!-- <th >LABA KOTOR</th> -->
        <th colspan="2" style="width: 5%;min-width: 80px;">OPSI</th>
      </tr>
      </thead>
      <tbody>
      <?php
      include "config.php";
      $connect=opendtcek();
      $tgl_retur=mysqli_real_escape_string($connect,$_POST['keyword1']);
      $no_returjual=mysqli_real_escape_string($connect,$_POST['keyword2']);
      $kode=$_SESSION['kode'];
      $kd_toko=$_SESSION['id_toko'];
      $oto=$_SESSION['kodepemakai'];
      $no_fak='';
      $gtot=0;$dnota=0;
      //echo '$no_returjual='.$no_returjual;
      $limit = 10; // Jumlah data per halamannya
      // $page = (isset($_POST['page']))? $_POST['page'] : 1;  
      // $limit_start = ($page - 1) * $limit;
      // echo '$limit_start='.$limit_start;
      
      $search=mysqli_real_escape_string($connect, $_POST['search']);
      if($search == 'true'){ // Jika ada data search yg 
        // echo $tgl_fak;
        //cek dulu arahkan halman ke terakhir
          $cek = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM retur_jual WHERE retur_jual.no_returjual='$no_returjual' AND retur_jual.tgl_retur='$tgl_retur' AND retur_jual.kd_toko='$kd_toko' ");  
          $get_jum = mysqli_fetch_array($cek);
          $page = ceil($get_jum['jumlah']/$limit);
          if($page==0){$page=1;}
          $limit_start = ($page - 1) * $limit;   
       
            $sql  = mysqli_query($connect,"SELECT * FROM retur_jual LEFT JOIN dum_jual 
                    ON retur_jual.no_urutjual=dum_jual.no_urut 
                    WHERE retur_jual.no_returjual='$no_returjual' AND retur_jual.tgl_retur='$tgl_retur' AND  retur_jual.kd_toko='$kd_toko' 
                    ORDER BY retur_jual.no_urutretur ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM retur_jual WHERE retur_jual.no_returjual='$no_returjual' AND retur_jual.tgl_retur='$tgl_retur' AND retur_jual.kd_toko='$kd_toko' ");  
          // } 
        $get_jumlah = mysqli_fetch_array($sql2);
      
      } else { // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        $page = (isset($_POST['page']))? $_POST['page'] : 1;  
        $limit_start = ($page - 1) * $limit;
       
          $sql  = mysqli_query($connect,"SELECT * FROM retur_jual LEFT JOIN dum_jual 
                    ON retur_jual.no_urutjual=dum_jual.no_urut 
                    WHERE retur_jual.no_returjual='$no_returjual' AND retur_jual.tgl_retur='$tgl_retur' AND  retur_jual.kd_toko='$kd_toko' 
                    ORDER BY retur_jual.no_urutretur ASC LIMIT $limit_start, $limit");
          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM retur_jual WHERE retur_jual.no_returjual='$no_returjual' AND retur_jual.tgl_retur='$tgl_retur' AND retur_jual.kd_toko='$kd_toko' ");  
        $get_jumlah = mysqli_fetch_array($sql2);
      }

      //*** Hitung grand total untuk list penjualan
      $disc1=0;$disc2=0;$jmlsub=0;$totlaba=0;$item=0;$bayar='';$ditem=0;$tdisc2=0;
      $gtotawal=0;
     
      $gtotnota=0;$jmlsub=0;$jumhrg=0;$gdisc1=0;$ongkir=0;
      $no=$limit_start;$tot=0;$no_fakjual='';$tgl_jual='';$disc1=0;$disc2=0;$jmlsub=0;$totlaba=0;
      $kd_pel='';$nm_pel='';$kd_bayar='';$netto=0;$tgl_jt='2021-01-01';$diskon=0;$ditem=0;$jmlsub=0;
      $x=0;
      while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;
        if($data['discrp'] > 0){
          $ditem=$data['discrp'];
        }else{
          $ditem=0;
        }
        if($data['discitem'] > 0){
          $dnota=$data['hrg_jual']*$data['discitem']/100;
        }else{
          $dnota=0;
        }
        if ($data['discvo']>0){
          $divo =$data['hrg_jual']*($data['discvo']/100);
        }else{
          $divo =0;  
        }     
        $hrgjl=$data['hrg_jual']-($ditem+$dnota+$divo);  
        $diskon=gantitides($ditem+$dnota+$divo);
        $jmlsub=round($hrgjl*$data['qty_brg'],0); 

        // $dnota=round($dnota,0);
        $tot=$tot+round($jmlsub,2);
        $nm_sat=ceknmkem2(mysqli_escape_string($connect,$data['kd_sat']),$connect);
        $no_fakjual=mysqli_escape_string($connect,$data['no_fakjual']);
        $x++;

      ?>
        <tr>
          <td align="right"><b><?php echo $no.'.'?>&nbsp;</b></td>
          <td align="left"><b><?php echo gantitgl($data['tgl_jual']) ?></b></td>
          <td align="left"><b>&nbsp;<?php echo $data['no_fakjual'] ?></b></td>
          <td align="left"><b>&nbsp;<?php echo $data['nm_brg'] ?></b></td>
          <td align="middle"><input class="w3-input" type="number" min=1 max="<?=$data['qty_brg'] ?>" value="<?=round($data['qty_brg'],0) ?>" style="width: 100%;text-align: center;border: 1px solid #ccc;border-radius: 3px;padding: 2px;" onchange="
           document.getElementById('<?='sub'.$x?>').innerHTML='<b>'+angkatitikdes('<?=$hrgjl?>'*this.value)+'<b>';upretur('<?=$data['no_urutretur']?>','<?=$data['no_urutjual']?>',this.value,'<?=$hrgjl?>');
          "></td>
          <td align="middle"><b><?php echo $nm_sat; ?></b></td>
          <td align="right"><b><?php echo gantitides($data['hrg_jual']); ?>&nbsp;</b></td>
          <td align="right"><b><?php echo gantitides($data['discrp']); ?>&nbsp;</b></td>
          <td align="right"><b><?php echo gantitides($dnota); ?>&nbsp;</b></td>
          <td align="right"><b><?php echo gantitides($divo); ?>&nbsp;</b></td>
          <td id="<?='net'.$x?>" align="right"><b><?php echo gantitides(round($hrgjl,0)); ?>&nbsp;</b></td>
          <td id="<?='sub'.$x?>" align="right"><b><?php echo gantitides(round($jmlsub,0)); ?>&nbsp;</b></td>
          <td style="text-align: center;">
             <?php $param=mysqli_escape_string($connect,$data['no_urutretur']); ?>
             <button type="button" onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){delretur(<?=$param?>);}" class="btn-danger fa fa-trash" style="cursor: pointer;font-size: 10pt;padding: 6px 8px;border: 1px solid #ddd;border-radius: 3px;background-color: #dc3545;color: white;" title="Hapus Data"></button>
          </td>    
        </tr>
        
      <?php  
      }

      ?>
      </tbody>
      <tfoot>
      <tr align="right" class="yz-theme-l1" style="background-color: #e6f2ff;font-weight: bold;">
        <th colspan="11" style="padding: 8px;">TOTAL</th>
        <th style="padding: 8px;"><?=gantitides($tot) ?></th>
        <th colspan="1" style="padding: 8px;"></th>
      </tr>
      </tfoot>
    </table> 
  </div>
  <!-- saya angka total -->
  <?php 

  ?>
  
     <nav  aria-label="Page navigation example" style="margin-top:5px;font-size: 9pt;padding: 5px 0;">
      <ul class="pagination pagination-sm justify-content-center" style="margin-bottom: 0;">
        <!-- LINK FIRST AND PREV -->
        <?php
        if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
        ?>
          <li class="page-item disabled "><a class="page-link  yz-theme-d2" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">First</a></li>
          <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">&laquo;</a></li>
        <?php
        }else{ // Jika page bukan page ke 1
          $link_prev = ($page > 1)? $page - 1 : 1;
        ?>
          <li><a class="page-link yz-theme-d2" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" href="javascript:void(0);" onclick="cariretur(1, false)" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" href="javascript:void(0);" onclick="cariretur(<?php echo $link_prev; ?>, false)" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;<?php echo ($page == $i) ? 'background-color: #0066cc;color: white;font-weight: bold;' : ''; ?>" onclick="cariretur(<?php echo $i; ?>, false)" onmouseover="this.style.backgroundColor='<?php echo ($page == $i) ? '#0052a3' : '#0066cc'; ?>';this.style.color='white';" onmouseout="this.style.backgroundColor='<?php echo ($page == $i) ? '#0066cc' : ''; ?>';this.style.color='<?php echo ($page == $i) ? 'white' : ''; ?>';"><?php echo $i; ?></a></li>
        <?php
        }
        ?>
        
        <!-- LINK NEXT AND LAST -->
        <?php
        if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir
        ?>
          <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">&raquo;</a></li>
          <li class="page-item disabled "><a class="page-link yz-theme-d2" href="javascript:void(0)" style="cursor: no-drop;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;">Last</a></li>
        <?php
        }else{ // Jika Bukan page terakhir
          $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
        ?>
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="cariretur(<?php echo $link_next; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d2" href="javascript:void(0)" onclick="cariretur(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer;font-size: 9pt;padding : 4px 10px;border-radius: 3px;margin: 0 2px;transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0066cc';this.style.color='white';" onmouseout="this.style.backgroundColor='';this.style.color='';">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
    
  </div>  
    
<script>
  document.getElementById('tgl_retur').value='<?=$tgl_retur?>'
  document.getElementById('no_returjual').value='<?=$no_returjual?>'
</script>

<?php
  if(isset($connect) && $connect){
    mysqli_close($connect);
  }
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Pastikan output JSON valid
  if($html === false || $html === ''){
    $html = '<tr><td colspan="13" align="center" style="padding: 20px;"><i class="fa fa-exclamation-triangle"></i> Tidak ada data atau terjadi kesalahan</td></tr>';
  }
  echo json_encode(array('hasil'=>$html));
?>