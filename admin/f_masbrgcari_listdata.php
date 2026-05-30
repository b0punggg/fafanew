<?php
  $keyword = $_POST['keyword'];
  ob_start();
?>

<div class="table-responsive hrf_res2" style="overflow-y:auto;overflow-x: auto;border-style: ridge;max-height: 500px">
    <table class="table-bordered table-hover" style=" width: 100%;border-collapse: collapse;white-space: nowrap;">
      <tr align="middle" class="yz-theme-l1">
        <th style="padding: 5px;width:3%">NO.</th>
        <th class="w3-hide-small" style="width:15%">KD.BARANG</th>
        <!-- <th>BARCODE</th> -->
        <th>NAMA BARANG</th>
        <th colspan="3" width="5%">OPSI</th>
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
            $sql1 = mysqli_query($connect, "SELECT * FROM mas_brg
                  ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg ORDER BY mas_brg.nm_brg");
          }
          else {
            $sql1 =mysqli_query($connect, "SELECT * from mas_brg 
                WHERE mas_brg.nm_brg LIKE '$param' ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg WHERE nm_brg LIKE '$param'"); 
          } 
        $get_jumlah = mysqli_fetch_array($sql2);
      }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
        // $id_apt=$_SESSION['id_apt'];
        $sql1 =mysqli_query($connect, "SELECT * from mas_brg 
                ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
        $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM mas_brg "); 
              
        $get_jumlah = mysqli_fetch_array($sql2);
        $subtot=$subtot+$get_jumlah['jumlah']; 
      }

      $tot=0;$sub=0;   
      $no=$limit_start;
      $xno=0;
      while($data = mysqli_fetch_array($sql1)){ // Ambil semua data dari hasil eksekusi $sql
        $no++;$xno++;
        // $nm_sat2=carinmsat($data['kd_sat']);
        $tot=mysqli_escape_string($connect,$get_jumlah['jumlah']);
        $nm_kem1=ceknmkem(mysqli_escape_string($connect,$data['kd_kem1']),$connect);
        $nm_kem2=ceknmkem(mysqli_escape_string($connect,$data['kd_kem2']),$connect);
        $nm_kem3=ceknmkem(mysqli_escape_string($connect,$data['kd_kem3']),$connect);
        $kd_brg=mysqli_escape_string($connect,$data['kd_brg']);
        $limit1=cekdisc($kd_brg,mysqli_escape_string($connect,$data['kd_kem1']),$connect);
            //echo '$lim1'.$lim1;
          
        if(empty($limit1)){
          $kd_sat4="1";$hrg_jum4=0;$lim1=0;$nm_kem4="-NONE-"; 
          $percen1=0;
        }else{
          $x1=explode(';', $limit1);
          $kd_sat4=$x1[0];
          $hrg_jum4=$x1[1];
          $lim1=$x1[2];
          $nm_kem4=ceknmkem($kd_sat4,$connect);
          if ($hrg_jum4 <=0){
            $persen1=0;
          }else{
            //$percen1=str_replace('.',',',round(($data['hrg_jum1']-$hrg_jum4)/$hrg_jum4*100,2));
            $percen1=round(($data['hrg_jum1']-$hrg_jum4)/$hrg_jum4*100,2);
          }  
          
        }  
        //
        $limit1=cekdisc($kd_brg,$data['kd_kem2'],$connect);
        if(empty($limit1)){
          $kd_sat5="1";$hrg_jum5=0;$lim2=0;$nm_kem5="-NONE-"; 
          $percen2=0;
        }else{
          $x1=explode(';', $limit1);
          $kd_sat5=$x1[0];
          $hrg_jum5=$x1[1];
          $lim2=$x1[2];
          $nm_kem5=ceknmkem($kd_sat5,$connect);
          if ($hrg_jum5 <=0){
            $persen2=0;
          }else{
            //$percen2=str_replace('.',',',round(($data['hrg_jum2']-$hrg_jum5)/$hrg_jum5*100,2));
            $percen2=round(($data['hrg_jum2']-$hrg_jum5)/$hrg_jum5*100,2);
          }  
          
        }  
        $limit1=cekdisc($kd_brg,$data['kd_kem3'],$connect);
        if(empty($limit1)){
          $kd_sat6="1";$hrg_jum6=0;$lim3=0;$nm_kem6="-NONE-"; 
          $percen3=0;
        }else{
          $x1=explode(';', $limit1);
          $kd_sat6=$x1[0];
          $hrg_jum6=$x1[1];
          $lim3=$x1[2];
          $nm_kem6=ceknmkem($kd_sat6,$connect);
          if ($hrg_jum6 <=0){
            $persen3=0;
          }else{
            //$percen3=str_replace('.',',',round(($data['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2));
            $percen3=round(($data['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2);
          }  
          
        }
        // 
      ?>
        <tr style="cursor: pointer;">
          <td align="right" onclick="document.getElementById('<?='btn'.$no?>').click();"><?php echo $no ?></td>
          <td class="w3-hide-small" align="left" onclick="document.getElementById('<?='btn'.$no?>').click();"><?php echo $data['kd_brg']; ?></td>
          <!-- <td align="left" onclick="document.getElementById('<?='btn'.$no?>').click();"><?php echo $data['kd_bar']; ?></td> -->
          <td align="left" onclick="document.getElementById('<?='btn'.$no?>').click();"><?php echo $data['nm_brg']; ?></td>
          
          <td>
              <button id="<?='btn'.$no?>" onclick="
                 document.getElementById('kd_barsay').value='<?=mysqli_escape_string($connect,$data['kd_bar']) ?>';
                 document.getElementById('kd_barsay_view').innerHTML='<?=mysqli_escape_string($connect,$data['kd_bar']) ?>';
                 document.getElementById('kd_barsay_view_kd').innerHTML='<?=mysqli_escape_string($connect,$data['kd_bar']) ?>';
                 document.getElementById('no_urutbrg').value='<?=mysqli_escape_string($connect,$data['no_urut']) ?>';
                 document.getElementById('kd_brg').value='<?=mysqli_escape_string($connect,$data['kd_brg']) ?>';
                 document.getElementById('nm_brg').value='<?=mysqli_escape_string($connect,$data['nm_brg']) ?>';
                 document.getElementById('kd_bar').value='<?=mysqli_escape_string($connect,$data['kd_bar']) ?>';
                 
                 document.getElementById('kd_sat1').value='<?=mysqli_escape_string($connect,$data['kd_kem1']) ?>';document.getElementById('nm_sat1').value='<?=$nm_kem1?>';document.getElementById('jum_sat1').value='<?=mysqli_escape_string($connect,$data['jum_kem1']) ?>';document.getElementById('hrg_jum1').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum1'])) ?>';document.getElementById('hrg_def1').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum1'])) ?>';
                 document.getElementById('kd_sat2').value='<?=mysqli_escape_string($connect,$data['kd_kem2']) ?>';document.getElementById('nm_sat2').value='<?=$nm_kem2?>';document.getElementById('jum_sat2').value='<?=mysqli_escape_string($connect,$data['jum_kem2']) ?>';document.getElementById('hrg_jum2').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum2'])) ?>';document.getElementById('hrg_def2').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum2'])) ?>';
                 document.getElementById('kd_sat3').value='<?=mysqli_escape_string($connect,$data['kd_kem3']) ?>';document.getElementById('nm_sat3').value='<?=$nm_kem3?>';document.getElementById('jum_sat3').value='<?=mysqli_escape_string($connect,$data['jum_kem3']) ?>';document.getElementById('hrg_jum3').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum3'])) ?>';document.getElementById('hrg_def3').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum3'])) ?>';
                 document.getElementById('discttp1').value='<?=$lim1 ?>';document.getElementById('nm_sat4').value='<?=$nm_kem4?>';document.getElementById('kd_sat4').value='<?=$kd_sat4 ?>';document.getElementById('hrg_jum4').value='<?=gantitides($hrg_jum4) ?>';
                     // document.getElementById('discttp1%').value='<?=$percen1 ?>';
                 document.getElementById('discttp2').value='<?=$lim2 ?>';document.getElementById('nm_sat5').value='<?=$nm_kem5?>';document.getElementById('kd_sat5').value='<?=$kd_sat5 ?>';document.getElementById('hrg_jum5').value='<?=gantitides($hrg_jum5) ?>';
                     // document.getElementById('discttp2%').value='<?=$percen2 ?>';
                 document.getElementById('discttp3').value='<?=$lim3 ?>';document.getElementById('nm_sat6').value='<?=$nm_kem6?>';document.getElementById('kd_sat6').value='<?=$kd_sat6 ?>';document.getElementById('hrg_jum6').value='<?=gantitides($hrg_jum6) ?>';
                     // document.getElementById('discttp3%').value='<?=$percen3?>';              
                 document.getElementById('saycode').innerHTML='<?=substr($data['kd_bar'],0,10)?>';document.getElementById('formlist').style.display='none'; carihrgbeli(1,true);
                 " class="btn btn-sm btn-primary fa fa-edit" style="cursor: pointer; font-size: 12pt" title="Edit Data">
              </button>     
  
             <?php $param=mysqli_escape_string($connect,$data['kd_brg']); ?>
          </td>    
          <td>&nbsp;</td>
          <td>
          <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){hapus('<?=$param?>','<?=$kd_toko?>');document.getElementById('formlist').style.display='none';}" class="btn btn-sm btn-danger fa fa-trash" style="cursor: pointer;font-size: 12pt" title="Hapus Data"></button>
          </td>
        </tr>   
      <?php  
      //$subtot=$no; 
      } 
      ?>
      <tr align="middle" class="yz-theme-l1">
        <th colspan="6" style="padding: 5px">TOTAL <?=gantiti($tot) ?> ITEM</th>
      </tr>
    </table> 
  </div>
  
    <nav  aria-label="Page navigation example hrf_arial">
      <ul class="pagination justify-content-center">
        <!-- LINK FIRST AND PREV -->
        <?php
        if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
        ?>
          <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
          <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&laquo;</a></li>
        <?php
        }else{ // Jika page bukan page ke 1
          $link_prev = ($page > 1)? $page - 1 : 1;
        ?>
          <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carilistdata(1, false)">First</a></li>
          <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carilistdata(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
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
          <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carilistdata(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>

        <?php
        }
        ?>
        
        <!-- LINK NEXT AND LAST -->
        <?php
        if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir

        ?>
          <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&raquo;</a></li>
          <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
        <?php
        }else{ // Jika Bukan page terakhir
          $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
        ?>
          <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carilistdata(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
          <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carilistdata(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
        <?php
        }
        ?>
      </ul>
    </nav> 
    
  </div>  
  <?php 
  function carinmsat($kd_sat){
  	$connect1 = opendtcek();
    $cek=mysqli_query($connect1,"select * from kemas where no_urut='$kd_sat'");
    $data=mysqli_fetch_assoc($cek);
    $nm_sat2=mysqli_escape_string($connect1,$data['nm_sat2']);
    unset($data,$cek);
    mysqli_close($connect1);
    return $nm_sat2;
  }
   ?>  
  
<?php
  mysqli_close($connect);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>