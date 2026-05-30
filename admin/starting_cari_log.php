<?php
  ob_start();
  include "config.php";
  session_start();
  $conlog     = opendtcek();
  $xc         = explode(';',$_POST['keyword']);
  $no_fakjual = trim($xc[0]);
  $ket        = trim($xc[1]);
  $kd_toko    = trim($xc[2]);
  $id_hapus   = mysqli_escape_string($conlog,$_POST['keyword']);
  ?>
  <center><h6><?=strtoupper($ket)?></h6></center>
  <div class="table-responsive w3-border" style="overflow:auto;">
    <table id="table" class="table-hover hrf_res2" style="width: 100%;border:none;"> 
     <?php
      $sqc=mysqli_query($conlog,"SELECT file_log.*,file_log_cari.id AS idlogcari,file_log_cari.ket,file_log_cari.kd_brg,file_log_cari.no_fakjual,file_log_cari.kd_brg,file_log_cari.no_item,file_log_cari.nm_brg,file_log_cari.qty,file_log_cari.satuan,file_log_cari.hrg_jual,file_log_cari.discount,file_log_cari.konfir,toko.nm_toko FROM file_log LEFT JOIN file_log_cari ON file_log.no_fak=file_log_cari.no_fakjual AND file_log.ket=file_log_cari.ket
      LEFT JOIN toko ON file_log.kd_toko=toko.kd_toko AND file_log_cari.pilih='0'
      WHERE file_log.no_fak='$no_fakjual' AND file_log.ket='$ket' AND file_log_cari.konfir='T' ");
      $no=0;$dari="";
      while($dt=mysqli_fetch_assoc($sqc)){
        $no++;
        $jam=$dt['jam'];
        $nmuser=$dt['nm_user'];
        $nourut=$dt['idlogcari'];
        $tg=explode(" ",$dt['jam']);
        $tgl=gantitgl($tg[0]);$jam=$tg[1];
        $dari="Toko ".$dt['nm_toko'].", User : ".$dt['nm_user'].", Tgl : ".$tgl.", Jam : ".$jam;
        $ketnews=gantitides($dt['qty']).$dt['satuan'].", Harga: ".gantitides($dt['hrg_jual'])." Disc: ".gantitides($dt['discount']);
          ?>  
        <tr>  
          <td style="padding:5px">
            <span class="fa-stack fa-lg text-danger" style="font-size:9pt">
              <i class="fa fa-square-o fa-stack-2x"></i>
              <i class="fa fa-exclamation fa-stack-1x"></i>
            </span>
            <i style="color:blue;font-weight:bold"><?=$no,'. '.$dt['no_fakjual']?></i><b><?=", ".$dt['nm_brg'].", "?></b><?=$ketnews?>
          </td>    
          <?php if($ket=='Hapus Item Jual'){?>
          <td style="padding:5px"><input type="checkbox" style="width: 20px;height:20px;margin-top:7px" onclick="if(this.checked==true){cekpil('<?='1;'.$nourut?>');}else{cekpil('<?='2;'.$nourut?>');}">&nbsp;</td>
          <?php }?>
        </tr>  
          <?php
      }?>  
    </table>
    
  </div>  
  <div class="w3-margin-bottom;w3-margin-top w3-center yz-theme-l3 hrf_res2" style="font-weight:bold">
    Dari <?=$dari ?>
  </div>
  <div class="row w3-margin-top">
    <div class="col-sm-8 offset-sm-2">
      <div class="row">
        <div class="col-sm">
          <button class="hrf_res3 btn-md btn-primary form-control w3-margin-bottom" onclick="if(confirm('Yakin, hapus penjualan barang ?')){konfir_del_n('<?=$id_hapus.';D'?>')}">Konfirmasi</button>  
        </div>
        <div class="col-sm">
          <button class="hrf_res3 btn-md btn-warning form-control" onclick="if(confirm('Abaikan konfirmasi ?')){konfir_del_n('<?=$id_hapus.';A'?>');}">Abaikan</button> 
        </div>
      </div>
    </div>
    
  </div>
<script>
  document.getElementById('info').style.display='none';carinews(1);
  document.getElementById('formcarilog').style.display='block';
</script>
<?php
  mysqli_close($conlog);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>