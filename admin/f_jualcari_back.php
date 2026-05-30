<?php
  $keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX  
  ob_start();
?>

<div class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;border-style: ridge;border-color:white;max-height: 430px">
    <table class="table-bordered table-hover"  style="font-size:10pt;border-collapse: collapse;white-space: nowrap;">
      <tr align="middle" class="yz-theme-l4">
        <th style="padding-top: 1px;padding-bottom: 1px;width: 4%">NO.</th>
        <th style="padding-top: 1px;padding-bottom: 1px">NAMA BARANG</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 5%">QTY</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 3%">SATUAN</th>
        <!-- <th >HPP</th> -->
        <th style="padding-top: 1px;padding-bottom: 1px;width: 10%">HARGA JUAL</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 8%">DISC ITEM</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 8%">DISC NOTA</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 8%">VOUCHER</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 10% ">NETTO</th>
        <th style="padding-top: 1px;padding-bottom: 1px;width: 10%">SUB TOTAL</th>
        <!-- <th >LABA KOTOR</th> -->
        <th colspan="2" style="padding-top: 1px;padding-bottom: 1px;width: 2%">OPSI</th>
      </tr>
      <?php
      include "config.php";
      session_start();
      $connect=opendtcek();
      $kd_toko=$_SESSION['id_toko'];
      $oto=$_SESSION['kodepemakai'];
      $no_fak='';
      $gtot=0;
      
      $sq_set=mysqli_query($connect,"SELECT * FROM seting");
      while($dt_set=mysqli_fetch_assoc($sq_set)){
        if ($dt_set['nm_per']=='CETAK'){
          $kode=$dt_set['kode'];  
        }
      }
      mysqli_free_result($sq_set);unset($dt_set);

      $limit = 10; // Jumlah data per halamannya
      // $page = (isset($_POST['page']))? $_POST['page'] : 1;  
      // $limit_start = ($page - 1) * $limit;
      // echo '$limit_start='.$limit_start;
      $params = mysqli_real_escape_string($connect, $keyword);
      $pecah=explode(';', $params);
      $no_fakjual=strtoupper($pecah[0]);
      $tgl_fakjual=$pecah[1];
      $search=mysqli_real_escape_string($connect, $_POST['search']);
      

      if($search == 'true'){ // Jika ada data search yg 
         
        //cek dulu arahkan halman ke terakhir
          $cek = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM dum_jual WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_fakjual' AND dum_jual.kd_toko='$kd_toko' ");  
          $get_jum = mysqli_fetch_array($cek);
          $page = ceil($get_jum['jumlah']/$limit);
          if($page==0){$page=1;}
          $limit_start = ($page - 1) * $limit;   
        //---------
          //echo '$params='.$params;
          if ($params=="") {   
            $sql = mysqli_query($connect, "SELECT * from dum_jual WHERE kd_toko=''
                ORDER BY no_urut ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM dum_jual WHERE kd_toko='' ORDER BY no_urut");
          }
          else {
            $sql =mysqli_query($connect, "SELECT dum_jual.no_item,dum_jual.nm_brg, dum_jual.bayar,dum_jual.tgl_jt,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.no_urut,dum_jual.hrg_jual,dum_jual.hrg_beli,dum_jual.kd_bayar,dum_jual.qty_brg,dum_jual.discitem,dum_jual.discrp,dum_jual.laba,dum_jual.kd_pel,dum_jual.kd_brg,dum_jual.ket,dum_jual.kd_sat,dum_jual.discvo,kemas.nm_sat1,pelanggan.nm_pel 
              FROM dum_jual 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
                WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_fakjual' AND  dum_jual.kd_toko='$kd_toko' 
                ORDER BY dum_jual.no_urut ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM dum_jual WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_fakjual' AND dum_jual.kd_toko='$kd_toko' ");  
          } 
        $get_jumlah = mysqli_fetch_array($sql2);
      
      } else { // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        $page = (isset($_POST['page']))? $_POST['page'] : 1;  
        $limit_start = ($page - 1) * $limit;
        $sql =mysqli_query($connect, "SELECT dum_jual.no_item,dum_jual.nm_brg,dum_jual.bayar,dum_jual.tgl_jt,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.no_urut,dum_jual.hrg_jual,dum_jual.hrg_beli,dum_jual.kd_bayar,dum_jual.qty_brg,dum_jual.discitem,dum_jual.discrp,dum_jual.laba,dum_jual.kd_pel,dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.ket,dum_jual.discvo,kemas.nm_sat1,pelanggan.nm_pel FROM dum_jual 
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
                WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_fakjual' AND  dum_jual.kd_toko='$kd_toko' 
                ORDER BY dum_jual.no_urut ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM dum_jual WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_fakjual' AND dum_jual.kd_toko='$kd_toko' ");  
        $get_jumlah = mysqli_fetch_array($sql2);
      }

      //*** Hitung grand total untuk list penjualan
      $disc1=0;$disc2=0;$jmlsub=0;$totlaba=0;$item=0;$bayar='';$ditem=0;$tdisc2=0;$discvo=0;
      $gtotawal=0;$dnota=0;$totdnota=0;$totvo=0;
      $cek = mysqli_query($connect, "SELECT * FROM dum_jual WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_fakjual' AND dum_jual.kd_toko='$kd_toko'  order by no_urut ASC");
      while ($cari=mysqli_fetch_array($cek)) {
        // if($cari['ket']<>'RETUR BARANG'){
          $disc1=$cari['discitem']/100;
          $discvo=$cari['discvo']/100;
          if ($cari['discitem']=='0' && $cari['discrp']=='0' ) {
            $jmlsub=round($cari['hrg_jual']*$cari['qty_brg'],0);
          } 
          if ($cari['discitem'] >0 && $cari['discrp']==0 ) {
            $jmlsub=($cari['hrg_jual']-($cari['hrg_jual']*$disc1))*$cari['qty_brg'];
          }
          if ($cari['discitem'] == 0 && $cari['discrp'] > 0 ) {
            $jmlsub=($cari['hrg_jual']-$cari['discrp'])*$cari['qty_brg']; 
          }  
          if ($cari['discitem'] > 0 && $cari['discrp'] > 0 ) {
            $jmlsub=($cari['hrg_jual']-(($cari['hrg_jual']*$disc1)+$cari['discrp']))*$cari['qty_brg']; 
          }  
          if($cari['discvo']>0){
            $jmlsub=($cari['hrg_jual']-(($cari['hrg_jual']*$disc1)+($cari['hrg_jual']*$discvo)+$cari['discrp']))*$cari['qty_brg']; 
          }

          $dnota    = ($cari['hrg_jual']*$disc1)*$cari['qty_brg'];
          $totdnota = $totdnota+$dnota;
          $tdisc2   = $tdisc2+($cari['discrp']*$cari['qty_brg']);
          $totvo    = $totvo+(($cari['hrg_jual']*$discvo)*$cari['qty_brg']);
          $gtotawal = $gtotawal+($cari['hrg_jual']*$cari['qty_brg']); 
          $gtot     = $gtot+round($jmlsub,2);
          $bayar    = $cari['bayar'];
        // }
        $item=$item+1;

      }  
      unset($cek,$cari);
      //--------------------

      //**** grand total untuk nota pembayaran
      // jika sudah pernah bayar nota maka total pembayaran dihilangkan discount nota
      // jika belum total pembayaran memakai grand total list jual
      $gtotnota=0;$jmlsub=0;$jumhrg=0;$gdisc1=0;$ongkir=0;$gdisc2=0;
      $dtcek=mysqli_query($connect,"SELECT * FROM mas_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");
      if (mysqli_num_rows($dtcek)>=1){
        $sqljual=mysqli_fetch_assoc($dtcek);
        $ongkir=$sqljual['ongkir'];
        $cekjual=mysqli_query($connect,"SELECT * FROM dum_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko' AND tgl_jual='$tgl_fakjual'");
        while ($dtceks=mysqli_fetch_array($cekjual)) {
          if ($dtceks['discrp'] > 0 ) {
            $jmlsub=($dtceks['hrg_jual']-$dtceks['discrp'])*$dtceks['qty_brg'];
          } else {
            $jmlsub=$dtceks['hrg_jual']*$dtceks['qty_brg']; 
          }  
          
          $gtotnota = round($gtotnota+$jmlsub,2);
          $jumhrg=$jumhrg+$dtceks['hrg_jual'];
          if ($dtceks['discitem']>0){
            $gdisc1=$gdisc1+($dtceks['hrg_jual']*($dtceks['discitem']/100))*$dtceks['qty_brg'];
          }
          if ($dtceks['discvo']>0){
            $gdisc2=$gdisc2+($dtceks['hrg_jual']*($dtceks['discvo']/100))*$dtceks['qty_brg'];
          }
        }  
        $gtotnotas=($gtotnota+$ongkir)-round($gdisc1+$gdisc2,2);
        unset($dtceks,$cekjual);
      } else {
        $jumhrg=0;$gtotnota=0;
        $dumcek=mysqli_query($connect,"SELECT * FROM dum_jual WHERE no_fakjual='$no_fakjual' AND kd_toko='$kd_toko'");
        if(mysqli_num_rows($dumcek)>=1){
          while ($dtcekss=mysqli_fetch_array($dumcek)) {
            if ($dtcekss['discrp'] > 0 ) {
              $jmlsub=($dtcekss['hrg_jual']-$dtcekss['discrp'])*$dtcekss['qty_brg'];
            } else {
              $jmlsub=$dtcekss['hrg_jual']*$dtcekss['qty_brg']; 
            }  
            
            $gtotnota = round($gtotnota+$jmlsub,2);
            $jumhrg=$jumhrg+$dtcekss['hrg_jual'];
            if ($dtcekss['discitem']>0){
              $gdisc1=$gdisc1+($dtcekss['hrg_jual']*($dtcekss['discitem']/100))*$dtcekss['qty_brg'];
            }
            if ($dtcekss['discvo']>0){
              $gdisc2=$gdisc2+($dtcekss['hrg_jual']*($dtcekss['discvo']/100))*$dtcekss['qty_brg'];
            }
          }  
          $gtotnotas=($gtotnota)-round($gdisc1+$gdisc2,2);  
        }else {
          $gtotnota=$gtot;
          $gtotnotas=$gtot;  
        }
        unset($dtcekss,$dumcek);
      }
   
      //---------------------------------
      
      $no=$limit_start;$tot=0;$no_fakjual='';$tgl_jual='';$disc1=0;$disc2=0;$jmlsub=0;$totlaba=0;
      $kd_pel='';$nm_pel='';$kd_bayar='';$netto=0;$tgl_jt='2021-01-01';$diskon=0;$ditem=0;$jmlsub=0;$discvo=0;
      $ketnm='';
      while($data = mysqli_fetch_array($sql))
      { // Ambil semua data dari hasil eksekusi $sql
        $no++;
        $disc1=$data['discitem']/100;
        $discvo=$data['discvo']/100;
        if ($data['discrp']==0 && $data['discitem']==0 ) {
          $netto=mysqli_escape_string($connect,$data['hrg_jual']);
          $jmlsub=$data['hrg_jual']*$data['qty_brg'];
          // $diskon=0;
        }
        if ($data['discrp']>0 && $data['discitem']==0 ) {
          $netto=$data['hrg_jual']-($data['discrp']);
          $jmlsub=$netto*$data['qty_brg']; 
        } 
        if ($data['discrp']==0 && $data['discitem']>0 ) {
          $netto=$data['hrg_jual']-($data['hrg_jual']*$disc1);
          $jmlsub=$netto*$data['qty_brg']; 
        }
        if ($data['discrp']>0 && $data['discitem']>0 ) {
          $netto=$data['hrg_jual']-($data['discrp']+($data['hrg_jual']*$disc1));
          $jmlsub=$netto*$data['qty_brg']; 
        }
        if ($data['discvo']>0) {
          $netto=$data['hrg_jual']-($data['discrp']+($data['hrg_jual']*$disc1)+($data['hrg_jual']*$discvo));
          $jmlsub=$netto*$data['qty_brg']; 
        }
        $tot=$tot+round($jmlsub,2);
        $nm_sat=ceknmkem2($data['kd_sat'],$connect);
        $totlaba=$totlaba+$data['laba'];
        $no_fakjual=mysqli_escape_string($connect,$data['no_fakjual']);
        $kd_pel=mysqli_escape_string($connect,$data['kd_pel']);
        $nm_pel=mysqli_escape_string($connect,$data['nm_pel']);
        $kd_bayar=mysqli_escape_string($connect,$data['kd_bayar']);
        $tgl_jt=mysqli_escape_string($connect,$data['tgl_jt']);
        if (mysqli_escape_string($connect,$data['ket'])=="PEMBELIAN BARANG"){
          $ket="-";
          $ketnm="";
        } else {
          if (substr($data['ket'],0,6)=="MUTASI"){
            $ket="-";
            $ketnm="";
          } 
          // else {
          //   $ket=$data['ket'];   
          //   $ketnm=' ('.ucwords(strtolower($data['ket'])).')';   
          // }
        }

        ?>
        <tr>
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo $no.'.'?>&nbsp;</b></td>
          <td align="left" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo $data['nm_brg'].$ketnm ?></b></td>
          <td align="middle" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides($data['qty_brg']); ?></b></td>
          <td align="middle" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo $data['nm_sat1']; ?></b></td>
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($data['hrg_jual'],0)); ?>&nbsp;</b></td>
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($data['discrp'],0)); ?></b>&nbsp;</td>
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($data['discitem']*$data['hrg_jual']/100,0)); ?></b>&nbsp;</td>
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($data['discvo']*$data['hrg_jual']/100,0)); ?></b>&nbsp;</td>
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($netto,0)); ?>&nbsp;</b></td>
          <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($jmlsub,0)); ?>&nbsp;</b></td>
          <?php
            if($data['ket']<>"RETUR BARANG"){ ?>
              <td><button onclick="
                  aktif(); 
                  document.getElementById('no_urutjual').value='<?=mysqli_real_escape_string($connect,$data['no_urut'])?>';
                  document.getElementById('kd_brg').value='<?=mysqli_real_escape_string($connect,$data['kd_brg'])?>';
                  document.getElementById('kd_sat').value='<?=mysqli_real_escape_string($connect,$data['kd_sat'])?>';
                  document.getElementById('nm_sat').value='<?=mysqli_real_escape_string($connect,$nm_sat)?>';
                  document.getElementById('qty_brg').value='<?=mysqli_real_escape_string($connect,$data['qty_brg'])?>';
                  document.getElementById('discitem').value='<?=gantiti(round(mysqli_real_escape_string($connect,$data['discrp']),0))?>';
                  
                  document.getElementById('ketjual').value='<?=$ket?>';
                  document.getElementById('qty_brg').focus();
                  " class="btn-warning fa fa-edit" style="cursor: pointer;font-size: 10pt" title="Edit Barang"></button>&nbsp;
              </td>
              <td style="padding-top: 1px;padding-bottom: 1px;">
                <?php $param=mysqli_escape_string($connect,$data['no_urut']); ?>
                <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){hapusbrg(<?=$param?>);aktif();caribrgjual(1,true);}" class="btn-danger fa fa-trash hapus_data" style="cursor: pointer;font-size: 10pt" title="Hapus Data"></button>
              </td>
            
            <?php 
            }
          ?>  
              
        </tr>
        
       <?php  
      }
      //tampilkan retur barang
      $netret=0;$jmlsubret=0;$totret=0;$nor=$no;
      $j_hrg_jual = 0;$j_disctem=0; $j_discvo=0;$j_disrp=0; 
      if($bayar=="SUDAH")
      {
        $cekretur=mysqli_query($connect,"SELECT retur_jual.no_fakjual,retur_jual.qty_retur,dum_jual.no_item,dum_jual.nm_brg,dum_jual.bayar,dum_jual.tgl_jt,dum_jual.tgl_jual,dum_jual.no_fakjual,dum_jual.no_urut,dum_jual.hrg_jual,dum_jual.hrg_beli,dum_jual.kd_bayar,dum_jual.qty_brg,dum_jual.discitem,dum_jual.discrp,dum_jual.laba,dum_jual.kd_pel,dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.ket,dum_jual.discvo,kemas.nm_sat1,pelanggan.nm_pel FROM retur_jual
        LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut
        LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
        LEFT JOIN pelanggan ON dum_jual.kd_pel=pelanggan.kd_pel
        WHERE retur_jual.no_fakjual='$no_fakjual' AND retur_jual.kd_toko='$kd_toko'");
        
        if(mysqli_num_rows($cekretur)>=1){
          while($dq=mysqli_fetch_assoc($cekretur))
          {
            $nor++;
            if ($dq['discrp']>0){
              $discrp=$dq['discrp'];
            }else{
              $discrp=0;
            }

            if ($dq['discitem']>0){
              $discitem=$dq['hrg_jual']*($dq['discitem']/100);
            }else{
              $discitem=0;
            }

            if($dq['discvo']>0){
              $discvo=$dq['hrg_jual']*($dq['discvo']/100);
            }else{
              $discvo=0;
            }
            $netret     = $dq['hrg_jual']-($discvo+$discitem+$discrp);
            $jmlsubret  = $netret*$dq['qty_retur']; 
            $totret     = $totret+round($jmlsubret,2);
            $j_hrg_jual = $dq['hrg_jual']*$dq['qty_retur']; 
            $j_disctem  = $discitem * $dq['qty_retur']; 
            $j_discvo   = $discvo   * $dq['qty_retur']; 
            $j_disrp    = $discrp   * $dq['qty_retur']; 
            ?>
            <tr style="color:red">
              <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo $nor.'.'?>&nbsp;</b></td>
              <td align="left" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo $dq['nm_brg'].' ('.$dq['ket'].')' ?></b></td>
              <td align="middle" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides($dq['qty_retur']); ?></b></td>
              <td align="middle" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo $dq['nm_sat1']; ?></b></td>
              <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($dq['hrg_jual'],0)); ?>&nbsp;</b></td>
              <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($dq['discrp'],0)); ?></b>&nbsp;</td>
              <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($dq['discitem']*$data['hrg_jual']/100,0)); ?></b>&nbsp;</td>
              <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($dq['discvo']*$data['hrg_jual']/100,0)); ?></b>&nbsp;</td>
              <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($netret,0)); ?>&nbsp;</b></td>
              <td align="right" style="padding-top: 1px;padding-bottom: 1px;"><b><?php echo gantitides(round($jmlsubret,0)); ?>&nbsp;</b></td>
            </tr>   
            <?php
          }

        }
      }
      ?>
      <tr align="right" class="yz-theme-l3">
        <th colspan="9" style="padding-top: 1px;padding-bottom: 1px" >SUB TOTAL</th>
        <th style="padding-top: 1px;padding-bottom: 1px"><?=gantitides(round($tot-$totret,0)) ?></th>
       
        <th colspan="2" style="padding-top: 1px;padding-bottom: 1px"></th>
      </tr>
      <?php if ($item==$no){ 
        ?>
        <tr align="right" class="yz-theme-l2">
        <th colspan="9" style="padding-top: 1px;padding-bottom: 1px">GRAND TOTAL</th>
        <th style="padding-top: 1px;padding-bottom: 1px"><?=gantitides(round($gtot-$totret,0)) ?></th>
        <th colspan="2" style="padding-top: 1px;padding-bottom: 1px"></th>
      </tr>
    <?php } ?>
    </table> 
  </div>
  <!-- saya angka total -->
  <?php 
  //echo $bayar;
  if($bayar=="SUDAH"){
    $x='Total pembelian barang '.$item.' item '.' nomer nota '.$no_fakjual.' dibayar '.$kd_bayar;
    ?>
    <!-- <script>document.getElementById('tmb-bayar').setAttribute('disabled',true);</script>
    <script>document.getElementById('tmb-add').setAttribute('disabled',true);</script> -->
    <?php
  }else{
    $x='Total pembelian barang '.$item.' item';
    ?>
    <!-- <script>document.getElementById('tmb-bayar').removeAttribute('disabled',true);</script>
    <script>document.getElementById('tmb-add').removeAttribute('disabled',true);</script> -->
    <?php
  }  
  if ($no==0){
    $cekits=mysqli_query($connect,"SELECT kd_pel FROM pelanggan WHERE kd_pel='IDPEL-0' AND nm_pel='UMUM'");
    if (mysqli_num_rows($cekits)>=1){
      $kd_pel='IDPEL-0';$nm_pel="UMUM";   
    }else {
      mysqli_query($connect,"INSERT INTO pelanggan VALUES('','IDPEL-0','UMUM','UMUM','-')");
      $kd_pel='IDPEL-0';$nm_pel="UMUM";   
    }
    $kd_bayar="TUNAI";$tgl_jt=date('Y-m-d');  
    
  }   

  ?>
  <script>
    document.getElementById("kd_pel").value='<?=$kd_pel?>';
    document.getElementById("tgl_jt").value='<?=$tgl_jt?>';
    document.getElementById("cr_bay").value='<?=$kd_bayar?>';
    if (document.getElementById("edit-warning").value==0){
      document.getElementById("kd_bar").focus();  
    } 
    document.getElementById("angka_bay1").innerHTML="<?='Rp. '.gantiti(round($gtot,0))?>";
    document.getElementById("angka_bay2").innerHTML="<?='Rp. '.gantiti(round($gtot,0))?>";
    document.getElementById("keterangan1").innerHTML="<img src='img/box.png' alt=''> "+"<?=$x?>";
    document.getElementById("keterangan2").innerHTML="<img src='img/box.png' alt=''> "+"<?=$x?>";
  </script>

    <nav  aria-label="Page navigation example" style="font-size: 9pt">
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
          <li><a class="page-link yz-theme-d2" style="cursor: pointer" href="javascript:void(0);" onclick="caribrgjual(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="caribrgjual(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="caribrgjual(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
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
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="caribrgjual(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d2" href="javascript:void(0)" onclick="caribrgjual(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
    
  </div>  
    
  <!-- Form cari nota-->
  <div id="fcari_jual" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge;border-color: white ">
    <div class="w3-modal-content w3-card-4 w3-animate-left" style="width:80%;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

      <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg,darkblue 0%,cyan 50%,white 80%);color:white;text-shadow: 1px 1px 2px black">&nbsp;<i class="fa fa-search"></i>
        LIST PENJUALAN BARANG
      </div>
       <input type="hidden" id="keycarijual" name="keycarijual"> 
      <div class="w3-center">
        <span onclick="document.getElementById('fcari_jual').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
      </div>
      <div id="viewlistjual"><script>carinotajual(1,true);</script></div> 
      
    </div><!--Modal content-->
  </div>
  <!-- End Form Nota -->  

  <!-- form bayar  -->
    <div id="form-bayar" class="w3-modal" style="background-color:rgba(1, 1, 1, 0);padding-top: 0px"> 
      <?php date_default_timezone_set('Asia/Jakarta'); ?>
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-radius:5px;background: linear-gradient(565deg, #FAFAD2 10%, white 80%);border-style: ridge;border-color:white;max-width: 450px;">
        <div style="background-color: orange;border-style: ridge;border-color: white;text-shadow: 1px 1px 2px black" class="yz-theme-d1 p-1">&nbsp;<i class="fa fa-server"></i>
          BAYAR NOTA PENJUALAN BARANG
          <span onclick="document.getElementById('form-bayar').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        
        <div class="modal-body">
          <!-- <form id="form2" action="f_jualbayar_act.php" method="post" style="font-size: 10pt"> -->
            <div class="form-group row" style="border-style: ridge;border-color:white;margin-top:-15px">
              <input id="tgl_jual" type="hidden" name="tgl_jual" value='<?=$tgl_fakjual?>'>
              <input id="tgl_jtnota" type="hidden" name="tgl_jtnota" value='<?=$tgl_jt?>'>

              <label for="byr_no_fak" class="w3-col l4 s4 col-form-label w3-margin-top" style="margin-left: 15px"><b>Nomer Nota</b></label>
              <div class="w3-col l7 s7 w3-margin-top">
                <input id="byr_no_fakjual" type="text" style="font-size: 10pt;" class="form-control hrf_arial" name="byr_no_fakjual" value="<?=$no_fakjual?>" disabled >
                <input id="no_fakjuals" type="hidden" style="font-size: 9pt;" class="form-control hrf_arial" name="no_fakjuals" value="<?=$no_fakjual?>" required="" >
              </div>    

              <label for="nm_pelbayar" class="w3-col l4 s4 col-form-label" style="margin-left:15px"><b>Pelanggan</b></label>
              <div class="w3-col l7 s7">
                <div class="input-group">
                  <input id="nm_pelbayar" type="text" style="font-size: 10pt;" class="form-control" name="nm_pel_byr" value="<?=$nm_pel?>" onkeyup="carnmpel()">
                  <input type="hidden" id="kd_pel_byr" name="kd_pel_byr" value="<?=$kd_pel?>">
                  <div class="input-group-btn">
                    <button id="btn-fpel" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 31px;cursor: pointer;border:1px solid black" type="button"><i class="fa fa-caret-down"></i></button>
                  </div>  
                </div>  

                  <div id="boxpelbay" style="position:absolute;z-index: 20;overflow: auto;display: none;max-width: 260px;border-style: ridge;border-color: white" class="w3-card">
                    <table id="tabpelanggan" class="table-bordered table-hover" style="font-size:10pt;background-color: white;width: 100%;border-collapse: collapse;white-space: nowrap;">
                      <tr align="middle" class="yz-theme-l4">
                        <th style="width: 2%">NO</th>
                        <th style="width: 30%">NAMA</th>
                        <th style="width: 68%">ALAMAT</th>
                      </tr>
                      <?php 
                      $cekpel = mysqli_query($connect, "SELECT * from pelanggan ORDER BY nm_pel ASC ");
                      $xpel=0;
                      while ($datpel = mysqli_fetch_array($cekpel)){
                        $xpel++;
                      ?>
                      <tr>
                        <td style="text-align: right"><?php echo $xpel?>&nbsp;</td>
                        <td>
                          <input class="w3-input" type="text" readonly value="<?=$datpel['nm_pel']; ?>"
                          style="border: none;background-color: transparent;cursor: pointer"
                          onkeydown="if(event.keyCode==13){this.click()}" 
                          onclick="document.getElementById('<?='pilpel'.$xpel?>').click();">
                        </td>

                        <td align="left" class="button" style="cursor:pointer;">
                          <input id="<?='pilpel'.$xpel?>" class="w3-input" type="text" readonly="" value="<?=$datpel['al_pel']; ?>" 
                          style="border: none;background-color: transparent;cursor: pointer"
                          onkeydown="if(event.keyCode==13){this.click()}" 
                          onclick="document.getElementById('kd_pel_byr').value='<?=mysqli_escape_string($connect,$datpel['kd_pel']) ?>';document.getElementById('nm_pelbayar').value='<?=mysqli_escape_string($connect,$datpel['nm_pel']) ?>';document.getElementById('boxpelbay').style.display='none'">
                        </td>
                      </tr>  

                      <?php   
                      }
                      unset($datpel);mysqli_free_result($cekpel);
                      ?>
                    </table>
                    <script>
                      $(document).ready(function(){
                        $("#btn-fpel").click(function(){
                          $("#nm_pelbayar").focus();
                          $("#boxpelbay").slideToggle("fast");
                          $("#tabbay").slideUp("fast");
                        });
                        
                       });

                      function carnmpel() {
                        var input, filter, table, tr, td, i, txtValue, tdx;
                        input = document.getElementById("nm_pelbayar");
                        filter = input.value.toUpperCase();
                        table = document.getElementById("tabpelanggan");
                        tr = table.getElementsByTagName("tr");
                        

                        for (i = 0; i < tr.length; i++) {
                          td = tr[i].getElementsByTagName("input")[0];
                          if (td) {
                            txtValue = td.textContent || td.value;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                              tr[i].style.display = "";
                              table.style.display = "";
                            } else {
                              tr[i].style.display = "none";
                              table.style.display = "none";
                            }
                          }       
                        }
                      }
                    </script>
                  </div>
              </div>    
                
              <label for="byr_kd_bayar" class="w3-col l4 s4 col-form-label" style="margin-left: 15px"><b>Cara Bayar</b></label>
                <div class="w3-col l7 s7">
                  <div class="input-group">
                    <input id="kd_bayar2" style="font-size: 10pt" type="text" class="form-control" name="kd_bayar" value="<?=$kd_bayar?>" required>  
                    <span><button id="btn-baypil" class="form-control yz-theme-l4 w3-hover-shadow" style="height: 31px;cursor: pointer;border:1px solid black" type="button"><i class="fa fa-caret-down"></i></button></span>  
                  </div>  
                  
                  <!--  -->
                    <div id="tabbay" class="table-responsive w3-card" style="overflow:hidden;border-style: ridge; border-color: white;height: 62px;display: none;position: absolute;z-index: 1;max-width: 260px">
                      <table class="table table-hover" style="font-size:10pt;background-color: white ">
                        <tr>
                          <td align="middle" class="button" onclick="document.getElementById('kd_bayar2').value='TUNAI'" style="cursor: pointer;padding: 2px"><?php echo 'TUNAI' ?></td>
                        </tr>  
                        <tr>
                          <td align="middle" class="button" onclick="document.getElementById('kd_bayar2').value='TEMPO'" style="cursor: pointer;padding: 2px"><?php echo 'TEMPO' ?></td>
                        </tr> 
                      </table>    
                    </div>  
                    
                    <script>
                      $(document).ready(function(){
                        $("#btn-baypil").click(function(){
                          $("#tabbay").slideToggle("fast");
                          $("#boxpelbay").slideUp("fast");
                        });
                        // $("#tabbay").mouseleave(function(){
                        //   $("#tabbay").slideUp("fast");
                        // });
                        $("#tabbay").click(function(){
                          $("#tabbay").slideUp("fast");
                        });
                      });
                    </script>
                  <!--  -->
                </div>

              <label for="byr_no_fak" class="w3-col l4 s4 col-form-label" style="margin-left: 15px"><b>Tanggal Tempo</b></label>
              <div class="w3-col l7 s7">
                <input class="form-control" type="date" id="tgl_jtnotas" name="tgl_jtnotas" value="<?=date('Y-m-d',strtotime($tgl_jt))?>" style="font-size: 10pt">
              </div>    

              <label for="byr_tf" class="w3-col l4 s4 col-form-label" style="margin-left: 15px"><b>Pembayaran Transfer</b></label>
              <div class="w3-col l7 s7" style="font-size: 14pt">
                <input type="checkbox" id="cek_tf" style="height:20px;width:20px;margin-top:5px" onclick="cektf()">
                <input type="hidden" id="pil_tf" name="pil_tf">
              </div>  
              
              <label for="byr_jual" class="w3-col l4 s3 col-form-label w3-margin-top " style="font-size: 11pt;border-bottom: 1px solid lightgrey;margin-left: 5px"><i class="fa fa-shopping-bag" style="color:blue"></i>&nbsp;&nbsp;<b>Belanja</b></label >
              <div class="w3-col l7 s8 w3-margin-top" style="border-bottom: 1px solid lightgrey;margin-left: 15px">        
                <input id="byr_awal" type="text"  class="form-control hrf_arial money" value='<?=gantitides($gtotawal-$j_hrg_jual)?>' name="byr_awal" style="border:none;background-color: transparent;font-size: 14pt;text-align: right;">
                <input id="byr_jual1" type="hidden"  class="money" value='<?=gantitides($gtotnota-$totret)?>' required="">
                <input id="byr_jual" type="hidden"  class="" name="byr_jual" value='<?=gantitides($gtotnota-$totret)?>' required>
              </div>       
          
              <label for="discitem" class="w3-col l4 s5 col-form-label" style="font-size: 11pt;border-bottom: 1px solid lightgrey;margin-left: 5px"><i class="fa fa-tags" style="color:orange"></i>&nbsp;&nbsp;<b>Discount Item</b></label >
              <div class="w3-col l7 s6" style="border-bottom: 1px solid lightgrey;margin-left: 15px">
                <input id="tdiscitem" type="text" value="<?=gantitides(round($tdisc2-$j_disrp,0))?>" class= "form-control money" name="tdiscitem"  readonly="" style="border:none;background-color: transparent;font-size: 12pt;text-align: right;">
                <input type="hidden" id="tdiscitem1" name='tdiscitem1' value="<?=gantitides($tdisc2-$j_disrp)?>">
              </div>

              <label for="disctot" class="w3-col l4 s5 col-form-label" style="font-size: 11pt;border-bottom: 1px solid lightgrey;margin-left: 5px"><i class="fa fa-tags" style="color:orange"></i>&nbsp;&nbsp;<b>Discount Nota</b></label >
              <div class="w3-col l7 s6" style="border-bottom: 1px solid lightgrey;margin-left: 15px">
                <input id="disctot" type="text" value="<?=gantitides(round($gdisc1-$j_disctem,0))?>" class= "form-control money" name="disctot"  required="" style="border:none;background-color: transparent;font-size: 12pt;text-align: right;" 
                onkeyup="hitdisc()" 
                onfocus="document.getElementById('bayar').value='';
                hitbayar(document.getElementById('kd_bayar2').value);"
                >
              </div>
              
              <label for="voucher" class="w3-col l4 s5 col-form-label" style="font-size: 11pt;border-bottom: 1px solid lightgrey;margin-left: 5px"><i class="fa fa-tags" style="color:orange"></i>&nbsp;&nbsp;<b>Voucher</b></label >
              <div class="w3-col l7 s6" style="border-bottom: 1px solid lightgrey;margin-left: 15px">
                <input id="voucher" type="text" value="<?=gantitides(round($totvo-$j_discvo,0))?>" class= "form-control money" name="voucher"  style="border:none;background-color: transparent;font-size: 12pt;text-align: right;"
                onkeyup="hitdisc()" 
                onfocus="document.getElementById('bayar').value='';
                hitbayar(document.getElementById('kd_bayar2').value);"
                >
              </div>

              <label for="ongkir" class="w3-col l4 s5 col-form-label" style="font-size: 11pt;border-bottom: 1px solid lightgrey;margin-left: 5px"><i class="fa fa-truck" style="color:red"></i>&nbsp;&nbsp;<b>Jasa Kirim </b></label>
              <div class="w3-col l7 s6" style="border-bottom: 1px solid lightgrey;margin-left:15px;">
                <input type="text" id="ongkir" name="ongkir" class="form-control money" value="<?=gantitides(round($ongkir,0))?>" onkeyup="hitongkir(this.value);" style="border:none;background-color: transparent;font-size: 12pt;text-align: right;">
              </div>  

              <label for="tot_belanja" class="w3-col l4 s3 col-form-label" style="font-size: 12pt;border-bottom: 1px solid lightgrey;margin-left: 5px"><i class="fa fa-briefcase" style="color:darkblue"></i>&nbsp;&nbsp;<b>Total </b></label >
              <div class="w3-col l7 s8" style="border-bottom: 1px solid lightgrey;margin-left:15px">
                <input id="tot_belanja" type="text" class="form-control money" name="tot_belanja"  required="" value="<?=gantitides(round($gtotnotas-$totret,0))?>" readonly style="border:none;background-color: transparent;font-size: 14pt;text-align: right;">
              </div>

              <label for="bayar" class="w3-col l4 s3 col-form-label" style="font-size: 12pt;border-bottom: 1px solid lightgrey;margin-left: 5px"><i class="fa fa-money" style="color:darkblue"></i>&nbsp;&nbsp;<b>Bayar</b></label >
              <div class="w3-col l7 s8" style="border-bottom: 1px solid lightgrey;margin-left:15px">
                <input id="bayar" type="text" class="form-control hrf_arial money" name="bayar"  required style="border:none;background-color: transparent;font-size: 14pt;text-align: right;" onkeyup="hitbayar(document.getElementById('kd_bayar2').value);" 
                onkeypress="
                if (event.keyCode==13){
                  if ( '<?=$gtot?>' != '0'){
                    document.getElementById('tmb-simpan').click();
                  }          
                }"
                onblur="hitbayar(document.getElementById('kd_bayar2').value)" autofocus>
              </div>     
              <label for="kembali" class="w3-col l4 s5 col-form-label" style="font-size: 12pt;border-bottom: 1px solid lightgrey;margin-left: 5px"><i class="fa fa-mail-reply-all" style="color:darkblue"></i>&nbsp;&nbsp;<b>Kembali</b></label >
              <div class="w3-col l7 s6" style="border-bottom: 1px solid lightgrey;margin-left:15px">
                <input id="kembali1" type="text" class="form-control hrf_arial money" required style="border:none;background-color: transparent;font-size: 14pt;text-align: right;color:blue" disabled="">
                <input id="kembali" type="hidden" class="form-control hrf_arial money" name="kembali"  required style="border:none;background-color: transparent;font-size: 14pt;text-align: right;">
              </div>
              <div class="col-sm-6" style="font-size: 14pt">
                <input type="checkbox" id="pil_cetak" name="pil_cetak" checked="" value="CETAK">
                <label for="pil_cetak" style="cursor: pointer" >Cetak Nota &nbsp;&nbsp;<i class="fa fa-print" style="color:darkblue"></i></label>
              </div>  
                <?php
                if($kode==1){ ?>  
                  <script>document.getElementById("pil_cetak").checked</script>
                <?php }else{ ?>
                  <script>document.getElementById("pil_cetak").checked=false</script>
                <?php }?>
              
            </div>      
            <div class="row w3-container">
              
              <input type="hidden" id="inocetak" name="inocetak">
              <div class="col-sm-8 offset-sm-2 text-center"> 
                <?php if ( $gtot != '0') { ?>
                <button id='tmb-simpan' class="btn btn-primary" style="box-shadow: 1px 1px 2px black;font-size: 12px;width: 70px;height: 30px"
                  onclick="simpanbyr(document.getElementById('tgl_jual').value,document.getElementById('no_fakjuals').value,
                           document.getElementById('kd_pel_byr').value,document.getElementById('kd_bayar2').value,
                           document.getElementById('byr_awal').value,document.getElementById('tot_belanja').value,
                           document.getElementById('bayar').value,document.getElementById('kembali').value,
                           document.getElementById('disctot').value,document.getElementById('tdiscitem1').value,
                           document.getElementById('voucher').value,document.getElementById('ongkir').value,
                           document.getElementById('pil_tf').value,document.getElementById('tgl_jtnotas').value,
                           document.getElementById('inocetak').value,document.getElementById('pil_cetak').value);kosongkan2();aktif();"
                ><i class="fa fa-save"></i> Simpan</button>   
                <?php } else {?>     
                <button id='tmb-simpan' class="btn btn-primary" style="box-shadow: 1px 1px 2px black;font-size: 12px;width: 70px;height: 30px" disabled><i class="fa fa-save"></i> Simpan</button>     
                <?php } ?>
                <button onclick="document.getElementById('form-bayar').style.display='none'" type="button" class="btn btn-warning" style="box-shadow: 1px 1px 2px black;font-size: 12px;width: 70px;height: 30px"><i class="fa fa-undo"></i> Batal</button>   

                <!-- simpan tampa cetak -->
                <button id="btn-nocetak" type="button" onclick="
                  if (document.getElementById('tmb-simpan').disabled==false){
                    document.getElementById('inocetak').value='nocetak';
                    document.getElementById('pil_cetak').checked=false;
                    document.getElementById('pil_cetak').value='';
                    document.getElementById('tmb-simpan').click();kosongkan2();aktif();  
                  } 
                  " style="display:none">
                </button>

              </div>                
            </div>
          <!-- </form> -->

        </div>  
      </div>  
    </div>
  <!-- End form bayar    -->
  <div id="simpanbayar"></div>
<script>
  function backangka(b)
  {
    b = b.toString();
    panjang = b.length;
    for (i = 0; i < panjang; i++){
      b = b.replace(".","");
    }
    
    return b;
  }

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

  function hitdisc()
  {
    var tag     = document.getElementById('byr_awal').value;
       disc     = document.getElementById('disctot').value;
       discitem = Number(backangkades(document.getElementById('tdiscitem1').value));
       congkir  = Number(backangkades(document.getElementById('ongkir').value));
       voucher  = Number(backangkades(document.getElementById('voucher').value));

    jumlah=0;   
    tag    = Number(backangkades(tag));
    disc   = Number(backangkades(disc));  
    jumlah = (tag-(disc+discitem+voucher))+congkir;
    document.getElementById('byr_jual').value=angkatitikdes(jumlah); 
    document.getElementById('tot_belanja').value=angkatitikdes(jumlah); 
  }

  function hitbayar(kd_bayar)
  {
    var tag      = document.getElementById('byr_awal').value;
        disc     = Number(backangkades(document.getElementById('disctot').value));
        discitem = Number(backangkades(document.getElementById('tdiscitem1').value));
        voucher  = Number(backangkades(document.getElementById('voucher').value));
        congkir  = document.getElementById('ongkir').value;
        bayar    = document.getElementById('bayar').value;
        jumlah   = 0;   
        tag      = Number(backangkades(tag));
        congkir  = Number(backangkades(congkir));  
        bayar    = Number(backangkades(bayar));  

    if (disc>0 || discitem>0 || voucher>0){
      jumdisc = tag-(disc+discitem+voucher);  
    }else{
      jumdisc = tag;
    }
    
    jumlah=bayar-(jumdisc+congkir);

    if (kd_bayar=='TUNAI' && jumlah<0){
      document.getElementById('tmb-simpan').setAttribute('disabled',true); 
      document.getElementById('kembali').value=angkatitikdes(jumlah); 
      document.getElementById('kembali1').value=angkatitikdes(jumlah);   

    }else if(kd_bayar=='TUNAI' && jumlah>=0)  {
      document.getElementById('tmb-simpan').removeAttribute('disabled',true); 
      document.getElementById('kembali').value=angkatitikdes(jumlah); 
      document.getElementById('kembali1').value=angkatitikdes(jumlah);   
    }else if(kd_bayar=='TUNAI' && jumlah=='')  {
      document.getElementById('tmb-simpan').setAttribute('disabled',true); 
      document.getElementById('kembali').value=angkatitikdes(jumlah); 
      document.getElementById('kembali1').value=angkatitikdes(jumlah);   
    }else if(kd_bayar=='' && jumlah=='')  {
      document.getElementById('tmb-simpan').setAttribute('disabled',true); 
    }else{
      document.getElementById('tmb-simpan').removeAttribute('disabled',true); 
      document.getElementById('kembali').value=angkatitikdes(jumlah); 
      document.getElementById('kembali1').value=angkatitikdes(jumlah);   
    }    
    if($kd_bayar=""){
      document.getElementById('tmb-simpan').setAttribute('disabled',true);  
    }
  }

  function hitongkir(congkir){
    var tag      = document.getElementById('byr_awal').value;
        disc     = Number(backangkades(document.getElementById('disctot').value));
        discitem = Number(backangkades(document.getElementById('tdiscitem1').value));
        voucher  = Number(backangkades(document.getElementById('voucher').value));
    jumlah=0;

    tag    = Number(backangkades(tag));
    congkir  = Number(backangkades(congkir));  
    if (disc>0 || discitem>0 || voucher>0){
      jumlah = (tag-(disc+discitem+voucher))+congkir;  
    }else{
      jumlah = congkir+tag;
    }
    
    document.getElementById('byr_jual').value=angkatitikdes(jumlah);
    document.getElementById('tot_belanja').value=angkatitikdes(jumlah); 
  }

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

  function cektf() {
    var checkBox = document.getElementById("cek_tf");
    var text = document.getElementById("pil_tf");
    if (checkBox.checked == true){
      text.value = "TRANSFER";
    } else {
      text.value = "";
    }
  }
</script>
<?php
  // mysqli_close($connect);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>