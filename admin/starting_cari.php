<?php
// $keyword = $_POST['kd_toko'];
 ob_start();
 include 'config.php';
 session_start();
 $kd_toko=$_SESSION['id_toko'];
 $con=opendtcek();
 
 //create date for search
 $tglhi=date("Y-m-d");
 $news_jt_1=tglingat($tglhi,-1);
 $news_jt=tglingat($tglhi,2);
 $huta=0;
 $cekdata=mysqli_query($con,"SELECT COUNT(*) AS jumhut FROM beli_bay 
  WHERE beli_bay.kd_toko='$kd_toko' and beli_bay.tgl_jt <='$news_jt' AND beli_bay.saldo_hutang>0");
 $dtnews=mysqli_fetch_assoc($cekdata);
 $huta=$dtnews['jumhut']; 
 unset($cekdata,$dtnews);
 
 $news_hut_1=tglingat($tglhi,-1);
 $news_hut=tglingat($tglhi,2);
 $cekdata1=mysqli_query($con,"SELECT count(*) as jumpiut FROM mas_jual 
  WHERE mas_jual.kd_toko='$kd_toko' and mas_jual.tgl_jt <='$news_hut' AND mas_jual.saldo_hutang>0 ORDER BY mas_jual.kd_pel ASC");
 $piut=0;
 $dtnews1=mysqli_fetch_assoc($cekdata1);
 $piut=$dtnews1['jumpiut'];
 unset($cekdata1,$dtnews1);    
 $del_l=0;
 if($_SESSION['kodepemakai']=='2'){
  $cekd=mysqli_query($con,"SELECT COUNT(*) AS jumdel FROM file_log WHERE konfir='T'");
  $dtc1=mysqli_fetch_assoc($cekd);
  $del_l=$dtc1['jumdel'];
  mysqli_free_result($cekd);unset($dtc1);
 }
 //echo '$piut='.$piut;

if(($huta+$piut+$del_l)==0) {?>
  <script>
  document.getElementById('newsbadgel').innerHTML='';
  document.getElementById('newsbadges').innerHTML='';
  </script>
<?php }else{ ?>
  <script>
  document.getElementById('newsbadgel').innerHTML=<?=$huta+$piut+$del_l?>;
  document.getElementById('newsbadges').innerHTML=<?=$huta+$piut+$del_l?>;
  </script>
<?php } ?>  
<div>
  <center style="margin-top: 10px;font-size: 8pt">
    <span class="fa-stack fa-lg w3-text-orange">
      <i class="fa fa-square-o fa-stack-2x"></i>
      <i class="fa fa-bullhorn fa-stack-1x"></i>
    </span>
    <b style="color:yellow;font-size: 10pt">Notify</b>
  </center>
  <hr style="height:1px;border-width:0;background-color:white">
</div>

<?php 
 if ($del_l>0 && $_SESSION['kodepemakai']=='2'){
  ?>
   <table class="hrf_res3" style="width: 100%;border:none;overflow:auto">
     <tr >
       <td width="30%" style="color:yellow;border:none;"><center>- KONFIRMASI PROSES -</center></td>
       <td style="border:none">
         <div class="input-group">
          <input id="keydel_l" class="form-control" type="text" placeholder=" Cari No. Faktur" style="padding:1px;background: transparent;border-color:blue;color:yellow" onkeyup="if (event.keyCode==13){cinfodel(1,true);}">
         <span>
           <button class="btn-primary form-control fa fa-search" onclick="cinfodel(1,true);" title="Cari"></button>
         </span>  
         <span>
           <button class="btn-warning form-control fa fa-undo" onclick="document.getElementById('keydel_l').value='';cinfodel(1,true);" title="Reset"></button>
         </span>
         </div> 
       </td>
     </tr>
   </table>  
   <div class="w3-margin-bottom " id="viewinfodel" style="border:none"><script>cinfodel(1,true);</script></div>
  <?php
 }

 if ($huta>0){
   ?>
    <table class="table-hover hrf_res3" style="width: 100%;border:none;overflow:auto">
      <tr>
        <td width="30%" style="color:yellow;border:none;"><center>- HUTANG SUPPLIER -</center></td>
        <td style="border:none">
          <div class="input-group">
           <input id="keyhut" class="form-control" type="text" placeholder=" Cari supplier" style="padding:1px;background: transparent;border-color:blue;color:yellow" onkeyup="if (event.keyCode==13){cinfohut(1,true);}">
          <span>
            <button class="btn-primary form-control fa fa-search" onclick="cinfohut(1,true);" title="Cari"></button>
          </span>  
          <span>
            <button class="btn-warning form-control fa fa-undo" onclick="document.getElementById('keyhut').value='';cinfohut(1,true);" title="Reset"></button>
          </span>
          </div>
          
        </td>
      </tr>
    </table>  
   <div class="w3-margin-bottom " id="viewinfohut" style="border:none;"><script>cinfohut(1,true);</script></div>
   <br>
   <?php
 }

 if ($piut>0){
   ?>
    <table class="hrf_res3" style=" width: 100%;border:none;overflow:auto">
      <tr >
        <td width="30%" style="color:yellow;border:none"><center>- PIUTANG PELANGGAN -</center></td>
        <td style="border:none">
          <div class="input-group">
           <input id="keypiut" class="form-control" type="text" placeholder=" Cari pelanggan" style="padding:1px;background: transparent;border-color:blue;color:yellow" onkeyup="if (event.keyCode==13){cinfopiut(1,true);}">
          <span>
            <button class="btn-primary form-control fa fa-search" onclick="cinfopiut(1,true);" title="Cari"></button>
          </span>  
          <span>
            <button class="btn-warning form-control fa fa-undo" onclick="document.getElementById('keypiut').value='';cinfopiut(1,true);" title="Reset"></button>
          </span>
          </div>
          
        </td>
      </tr>
    </table>  
   <div class="w3-margin-bottom " class="w3-margin-bottom" id="viewinfopiut" style="border:none;overflow:auto"><script>cinfopiut(1,true);</script></div>
   <?php
 }
?>

<?php
  mysqli_close($con);
  $html = ob_get_contents(); 
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>