<?php
$keyword = $_POST['keyword']; // Ambil data keyword yang dikirim dengan AJAX	
ob_start();

?>
<style>
  th {
  position: sticky;
  top: 0px; 
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  border: 1px solid grey ;
  padding: 3px;
  }
  td  {
    padding: 1px;
  }
  
</style>
<!-- table table-bordered table-sm table-striped -->
<div class="table-responsive " style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
  <table class="table-stripe table-hover hrf_arial" style="width: 100%;border-collapse: collapse;white-space: nowrap;">
    <tr align="middle" class="yz-theme-l4">
      <th width="3%">No.</th>
      <!-- <th width="20%">KODE BARANG</th> -->
      <th>NAMA BARANG</th>
      <th width="8%">EXP DATE</th>
      <th width="5%">QTY</th>
      <th width="8%">SATUAN</th>
      <th width="8%">HARGA</th>
      <th width="6%">DISC</th>
      <th width="10%">JUMLAH</th>
      <th colspan="2" width="6%">OPSI</th>
    </tr>
    <?php
    include "config.php";
    session_start();
    $connect=opendtcek();
    $kd_toko=$_SESSION['id_toko'];
    $tgl_fak='0000-00-00';$no_fak='';
    $gtot=0;
    $page = (isset($_POST['page']))? $_POST['page'] : 1;

    $limit = 15; // Jumlah data per halamannya

    $limit_start = ($page - 1) * $limit;
    // echo '$limit_start='.$limit_start;
     
    if(isset($_POST['search']) && $_POST['search'] == true){ // Jika ada data search yg 
    	$params = mysqli_real_escape_string($connect, $keyword);
    	$pecah=explode(';', $params);
    	$no_fak=strtoupper($pecah[0]);
    	if(empty($pecah[1])){
          $tgl_fak='0000-00-00';
    	}else{
    	  $tgl_fak=$pecah[1];	
    	}
      $kd_brg=strtoupper($pecah[2]);    	
      //echo '$params='.$params;   
      if ($params=="") {	 
      	  $sql = mysqli_query($connect, "SELECT * FROM beli_brg WHERE kd_toko='' AND no_fak='' AND tgl_fak='' ORDER BY no_urut ASC LIMIT $limit_start, $limit");

          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE kd_toko='' AND no_fak='' AND tgl_fak='' ORDER BY no_urut");
      }
      else {
        if(!empty($kd_brg)){
          $kd_brg='%'.$kd_brg.'%';
          $sql =mysqli_query($connect, "SELECT beli_brg.ket, beli_brg.tgl_fak,beli_brg.no_urut,beli_brg.kd_brg,beli_brg.jml_brg,beli_brg.kd_bar,beli_brg.kd_toko,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,beli_brg.kd_sat,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.ppn,beli_brg.expdate,kemas.nm_sat1,kemas.nm_sat2,supplier.nm_sup,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,beli_brg.id_bag,bag_brg.nm_bag
              FROM beli_brg 
              LEFT JOIN kemas ON beli_brg.kd_sat=kemas.no_urut 
              LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup 
              LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
              LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut
              WHERE beli_brg.kd_toko='$kd_toko' AND beli_brg.no_fak='$no_fak' AND beli_brg.tgl_fak='$tgl_fak' AND mas_brg.nm_brg like '$kd_brg' AND INSTR(beli_brg.ket,'MUTASI')=0 ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
            $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg 
              LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
              WHERE beli_brg.kd_toko='$kd_toko' AND beli_brg.no_fak='$no_fak' AND beli_brg.tgl_fak='$tgl_fak' AND mas_brg.nm_brg like '$kd_brg' AND INSTR(beli_brg.ket,'MUTASI')=0");  
        }else{  
          $sql =mysqli_query($connect, "SELECT beli_brg.ket,beli_brg.tgl_fak,beli_brg.no_urut,beli_brg.kd_brg,beli_brg.jml_brg,beli_brg.kd_bar,beli_brg.kd_toko,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_sup,beli_brg.kd_sat,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.expdate,kemas.nm_sat1,kemas.nm_sat2,supplier.nm_sup,mas_brg.kd_kem1,mas_brg.jum_kem1,mas_brg.nm_brg,mas_brg.hrg_jum1,mas_brg.kd_kem2,mas_brg.jum_kem2,mas_brg.hrg_jum2,mas_brg.kd_kem3,mas_brg.jum_kem3,mas_brg.hrg_jum3,beli_brg.id_bag,bag_brg.nm_bag
              FROM beli_brg 
              LEFT JOIN kemas ON beli_brg.kd_sat=kemas.no_urut 
              LEFT JOIN supplier ON beli_brg.kd_sup=supplier.kd_sup 
              LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
              LEFT JOIN bag_brg ON beli_brg.id_bag=bag_brg.no_urut 
              WHERE beli_brg.kd_toko='$kd_toko' AND beli_brg.no_fak='$no_fak' AND beli_brg.tgl_fak='$tgl_fak' AND INSTR(beli_brg.ket,'MUTASI')=0
               ORDER BY mas_brg.nm_brg ASC LIMIT $limit_start, $limit");
          $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND INSTR(beli_brg.ket,'MUTASI')=0");   
        }
	    }	
      $get_jumlah = mysqli_fetch_array($sql2);

      //untuk total pebelian semua gtot
      $gtot=0;$disc1=0;$disc2=0;$jmlsub=0;$ketbeli='';
      $cekbeli=mysqli_query($connect,"SELECT * FROM beli_brg WHERE kd_toko='$kd_toko' AND beli_brg.no_fak='$no_fak' AND beli_brg.tgl_fak='$tgl_fak' AND INSTR(beli_brg.ket,'MUTASI')=0 AND INSTR(beli_brg.ket,'RETUR')=0");
      while ($datcekbeli=mysqli_fetch_assoc($cekbeli)){
        $disc1=mysqli_escape_string($connect,$datcekbeli['disc1'])/100;
        $disc2=mysqli_escape_string($connect,$datcekbeli['disc2']);
        if ($datcekbeli['disc1']=='0.00'){
          // echo gantiti($data['disc2']);
          $jmlsub=(mysqli_escape_string($connect,$datcekbeli['hrg_beli'])-$disc2)*mysqli_escape_string($connect,$datcekbeli['jml_brg']);
        }else{
          $jmlsub=(mysqli_escape_string($connect,$datcekbeli['hrg_beli'])-(mysqli_escape_string($connect,$datcekbeli['hrg_beli'])*$disc1))*mysqli_escape_string($connect,$datcekbeli['jml_brg']);
        }
        if ($datcekbeli['disc1']=='0.00' && $datcekbeli['disc2']=='0'){
          $jmlsub=mysqli_escape_string($connect,$datcekbeli['jml_brg'])*mysqli_escape_string($connect,$datcekbeli['hrg_beli']);
        }     
        $gtot=$gtot+$jmlsub;
        $ketbeli=$datcekbeli['ket'];
      } 
      unset($cekbeli,$datcekbeli);
      //

    }else{ // Jika user belum mengklik tombol search (PROSES TANPA AJAX)
      $sql = mysqli_query($connect, "SELECT * FROM beli_brg WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' ORDER BY no_urut ASC LIMIT $limit_start, $limit");
      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM beli_brg WHERE kd_toko='$kd_toko' AND no_fak='$no_fak' AND tgl_fak='$tgl_fak' ORDER BY no_urut");         	
      $get_jumlah = mysqli_fetch_array($sql2);
    }
    
    $no=$limit_start;$tot=0;$no_fak='';$tgl_fak='';$disc1=0;$disc2=0;$jmlsub=0;
    $kd_sup='';$nm_sup='';$no_fakcari='*';$tgl_fakcari='0000-00-00';
    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
      $no++;
      //$disc1=$data['disc1']/100;
      $disc2=$data['disc2'];
      if ($data['disc1']=='0.00'){
      	$jmlsub=(mysqli_escape_string($connect,$data['hrg_beli'])-$disc2)*mysqli_escape_string($connect,$data['jml_brg']);
      }else{
      	$jmlsub=(mysqli_escape_string($connect,$data['hrg_beli'])-(mysqli_escape_string($connect,$data['hrg_beli'])*$disc1))*mysqli_escape_string($connect,$data['jml_brg']);
      }
      if ($data['disc1']=='0.00' && $data['disc2']=='0'){
      	$jmlsub=mysqli_escape_string($connect,$data['jml_brg'])*mysqli_escape_string($connect,$data['hrg_beli']);
      }   
      $tot=$tot+$jmlsub;;
      $no_fak=mysqli_escape_string($connect,$data['no_fak']);
      $tgl_fak=mysqli_escape_string($connect,$data['tgl_fak']);
      $no_fakcari=mysqli_escape_string($connect,$data['no_fak']);
      $tgl_fakcari=mysqli_escape_string($connect,$data['tgl_fak']);
      $nm_sup=mysqli_escape_string($connect,$data['nm_sup']);
      $kd_sup=mysqli_escape_string($connect,$data['kd_sup']);
      $kd_kem1=ceknmkem(mysqli_escape_string($connect,$data['kd_kem1']),$connect);
      $kd_kem2=ceknmkem(mysqli_escape_string($connect,$data['kd_kem2']),$connect);
      $kd_kem3=ceknmkem(mysqli_escape_string($connect,$data['kd_kem3']),$connect);
      $nmsat=ceknmkem(mysqli_escape_string($connect,$data['kd_sat']),$connect);
      $kd_brg=mysqli_escape_string($connect,$data['kd_brg']);
      $limit1=cekdisc($kd_brg,mysqli_escape_string($connect,$data['kd_kem1']),$connect);
          
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
            $percen1=round(($data['hrg_jum1']-$hrg_jum4)/$hrg_jum4*100,2);
          }  
          
        }  
      
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
            // $percen2=str_replace('.',',',round(($data['hrg_jum2']-$hrg_jum5)/$hrg_jum5*100,2));
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
            // $percen3=str_replace('.',',',round(($data['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2));
            $percen3=round(($data['hrg_jum3']-$hrg_jum6)/$hrg_jum6*100,2);
          }  
          
        }
       
    ?>
      <tr >
        <td align="right" style="border: none;"><?php echo $no.'.' ?>&nbsp;</td>
        <!-- <td align="left" style="border: none;">&nbsp;<?php echo $data['kd_brg']; ?></td> -->
        <td align="left" style="border: none;">&nbsp;<?php echo $data['nm_brg']; ?></td>
        <td align="middle" style="border: none;"><?php echo gantitgl($data['expdate']); ?></td>
        <td align="middle" style="border: none;"><?php echo $data['jml_brg']; ?></td>
        <td align="middle" style="border: none;"><?php echo $nmsat; ?></td>
        <td align="right" style="border: none;"><?php echo gantitides($data['hrg_beli']); ?>&nbsp;</td>
        <td align="right" style="border: none;"><?php if ($data['disc1']=='0.00'){echo gantiti($data['disc2']);}else{echo  gantitides(round($data['disc1'],2)).'%';} ?>&nbsp;</td>
        <td align="right" style="border: none;"><?php echo gantitides($jmlsub); ?>&nbsp;</td>
        <td align="center" style="border: none;" >
        	<button onclick="
      	   document.getElementById('kd_brg').value='<?=mysqli_escape_string($connect,$data['kd_brg']) ?>';
      	   document.getElementById('nm_brg').value='<?=mysqli_escape_string($connect,$data['nm_brg']) ?>';
           document.getElementById('ketbel').value='<?=mysqli_escape_string($connect,$data['ket']) ?>';
      	   document.getElementById('jml_brg').value='<?=mysqli_escape_string($connect,$data['jml_brg']) ?>';
      	   document.getElementById('kd_bar').value='<?=mysqli_escape_string($connect,$data['kd_bar']) ?>';
      	   document.getElementById('kd_sup').value='<?=mysqli_escape_string($connect,$data['kd_sup']) ?>';
      	   document.getElementById('nm_sup').value='<?=mysqli_escape_string($connect,$data['nm_sup'])?>';
      	   document.getElementById('kd_sat').value='<?=mysqli_escape_string($connect,$data['kd_sat']) ?>';
      	   document.getElementById('nm_sat').value='<?=mysqli_escape_string($connect,$data['nm_sat2']) ?>';
      	   document.getElementById('hrg_beli').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_beli'])) ?>';
      	   document.getElementById('kd_sat1').value='<?=mysqli_escape_string($connect,$data['kd_kem1']) ?>';
           document.getElementById('jum_sat1').value='<?=mysqli_escape_string($connect,$data['jum_kem1']) ?>';
           document.getElementById('hrg_jum1').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum1'])) ?>';document.getElementById('nm_sat1').value='<?=$kd_kem1?>';
      	   document.getElementById('kd_sat2').value='<?=mysqli_escape_string($connect,$data['kd_kem2']) ?>';
           document.getElementById('jum_sat2').value='<?=mysqli_escape_string($connect,$data['jum_kem2']) ?>';
           document.getElementById('hrg_jum2').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum2'])) ?>';document.getElementById('nm_sat2').value='<?=$kd_kem2?>';
      	   document.getElementById('kd_sat3').value='<?=mysqli_escape_string($connect,$data['kd_kem3']) ?>';
           document.getElementById('jum_sat3').value='<?=mysqli_escape_string($connect,$data['jum_kem3']) ?>';
           document.getElementById('hrg_jum3').value='<?=gantitides(mysqli_escape_string($connect,$data['hrg_jum3'])) ?>';document.getElementById('nm_sat3').value='<?=$kd_kem3?>';
      	   document.getElementById('no_urutnota').value='<?=mysqli_escape_string($connect,$data['no_urut']) ?>';
      	   document.getElementById('discitem1').value='<?=round($data['disc1'],2) ?>';
           document.getElementById('discitem2').value='<?=mysqli_escape_string($connect,$data['disc2']) ?>';
           document.getElementById('discttp1').value='<?=$lim1 ?>';document.getElementById('nm_sat4').value='<?=$nm_kem4?>';document.getElementById('kd_sat4').value='<?=$kd_sat4 ?>';document.getElementById('hrg_jum4').value='<?=gantitides($hrg_jum4) ?>';
           document.getElementById('discttp1%').value='<?=$percen1 ?>';
           document.getElementById('discttp2').value='<?=$lim2 ?>';document.getElementById('nm_sat5').value='<?=$nm_kem5?>';document.getElementById('kd_sat5').value='<?=$kd_sat5 ?>';document.getElementById('hrg_jum5').value='<?=gantitides($hrg_jum5) ?>';
           document.getElementById('discttp2%').value='<?=$percen2 ?>';
           document.getElementById('discttp3').value='<?=$lim3 ?>';document.getElementById('nm_sat6').value='<?=$nm_kem6?>';document.getElementById('kd_sat6').value='<?=$kd_sat6 ?>';document.getElementById('hrg_jum6').value='<?=gantitides($hrg_jum6) ?>';
           document.getElementById('discttp3%').value='<?=$percen3?>';
           document.getElementById('id_bag').value='<?=$data['id_bag'] ?>';
           document.getElementById('nm_bag').value='<?=$data['nm_bag'] ?>';
           document.getElementById('expdate').value='<?=$data['expdate'] ?>';
      	   document.getElementById('btn-geser').click();" class="w3-center btn btn-sm btn-primary fa fa-edit" style="cursor: pointer; font-size: 12pt" title="Edit Data">
          </button>	    
        </td> 
        <td align="center" style="border:none">
         <?php $param=mysqli_escape_string($connect,$data['no_urut']); ?>
         <button onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){delrec('<?=$param?>')}" class="btn btn-sm btn-danger fa fa-trash" style="cursor: pointer;font-size: 12pt" title="Hapus Data"></button>
        </td>    
      </tr> <?php
    }
    $ppn=0;
    $sqlbay=mysqli_query($connect,"SELECT * FROM beli_bay WHERE no_fak='$no_fak' AND tgl_fak='$tgl_fak'");
    if (mysqli_num_rows($sqlbay)>=1){
      $dabay=mysqli_fetch_assoc($sqlbay);
      $ppn=$dabay['ppn'];
    } ?>
    <tr align="right" class="yz-theme-l4">
      <th colspan="6" >Sub Total</th>
      <th ><?=gantitides($tot) ?></th>
      <th colspan="2"></th>
    </tr>
    <tr align="right" class="yz-theme-l3"> <?php 
      if ($ppn > 0){ ?>
        <th colspan="6" >Total Tagihan + PPN <?=$ppn?> % </th>
        <th><?=gantitides($gtot+($gtot*$ppn/100)) ?></th>
        <th colspan="2"></th> <?php 
      } else { ?>  
        <th colspan="6" >Total Tagihan </th>
        <th ><?=gantitides($gtot) ?></th>
        <th colspan="2"></th> <?php 
      } ?>  
    </tr>
  </table> 
</div>

<script>
  function delrec(nourut){
    $.ajax({
      url: 'f_belihapus_act.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keydel:nourut}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        $("#viewcek").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
</script>
<script>document.getElementById("link").setAttribute("href","f_belinota_cetak.php?pesan=<?=$no_fakcari.';'.$tgl_fakcari?>")</script>

	<div class="w3-border">
		<nav class="hrf_arial" aria-label="Page navigation example" style="margin-top:15px;">
		  <ul class="pagination justify-content-center hrf_arial">
		    <!-- LINK FIRST AND PREV -->
		    <?php
		    if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
		    ?>
		      <li class="page-item disabled "><a class="page-link  yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
		      <li class="page-item disabled "><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:18px;padding-right:18px;"><i class="fa fa-caret-left"></i></a></li>
		    <?php
		    }else{ // Jika page bukan page ke 1
		      $link_prev = ($page > 1)? $page - 1 : 1;
		    ?>
		      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carinota(1, false)">First</a></li>
		      <li><a class="page-link yz-theme-l1" style="cursor: pointer;padding-left:18px;padding-right:18px;" href="javascript:void(0);" onclick="carinota(<?php echo $link_prev; ?>, false)"><i class="fa fa-caret-left"></i></a></li>
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
		      <li class="page-item " <?php echo $link_active; ?>><a class="page-link  yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carinota(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
		    <?php
		    }
		    ?>
		    
		    <!-- LINK NEXT AND LAST -->
		    <?php
		    if($page == $jumlah_page || $get_jumlah['jumlah']==0){ // Jika page terakhir
		    ?>
		      <li class="page-item disabled " ><a class="page-link  yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:18px;padding-right:18px;"><i class="fa fa-caret-right"></i></a></li>
		      <li class="page-item disabled "><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
		    <?php
		    }else{ // Jika Bukan page terakhir
		      $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
		    ?>
		      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carinota(<?php echo $link_next; ?>, false)" style="cursor: pointer;padding-left:18px;padding-right:18px;"><i class="fa fa-caret-right"></i></a></li>
		      <li class="page-item "><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carinota(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
		    <?php
		    }
		    ?>
		  </ul>
		</nav>
	</div>

	<!-- form bayar  -->
	  <?php 
	   $sld_hut=0;$tgl_tempo='';
	    $cek=mysqli_query($connect,"SELECT * FROM beli_bay WHERE no_fak='$no_fak' AND tgl_fak='$tgl_fak' AND kd_toko='$kd_toko'");
	    if (mysqli_num_rows($cek)>=1){
          $hutang=mysqli_fetch_array($cek);
          $sld_hut=mysqli_escape_string($connect,$hutang['saldo_hutang']);
          $tgl_tempo=mysqli_escape_string($connect,$hutang['tgl_jt']);
          $ket2="";
          if($hutang['pay']=='0'){
            $ket2=" [ Belum Bayar ]";
          }
          if ($sld_hut>0) {
            $cr_bays="TEMPO";$ket=", # Pembayaran tempo, sisa hutang : Rp. ".gantitides($sld_hut)." # Jatuh tempo tgl. ".gantitgl($tgl_tempo)." ".$ket2;	
          }else{$cr_bays="TUNAI";$ket="dibayar lunas ".$ket2;}
          $disctot=$hutang['disc'];
          $ppn=$hutang['ppn'];
	    }else{
	      $sld_hut=gantiti($gtot);	
	      $tgl_tempo='';
	      $cr_bays='';
	      $ket='Belum dibayar';
        $disctot='00,00';
        $ppn='00,00';
	    }   	
	    unset($cek,$hutang);
	   ?>
      
	<?php 
	  if ($get_jumlah['jumlah']>=1){
	  	?>
	  	<script>document.getElementById("btn-geser").innerHTML=" Total barang "+<?php echo $get_jumlah['jumlah'] ?>+" Item"+" <?=$ket;?>" </script>	
	  	<?php
	  }else {
	  	?>
	  	<script>document.getElementById("btn-geser").innerHTML=" Belum Ada Data"</script>	
	  	<?php
	  } ?>
	  <div id="form-bayar" class="w3-modal" style="margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
	    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-radius:5px;background: linear-gradient(565deg, #E6E6FA 0%, white 80%);box-shadow: 0px 2px 60px;border-style: ridge;border-color:white;max-width: 450px">
	      <div class="w3-center w3-padding-small yz-theme-d1 w3-wide">
	        <center><i class="fa fa-server"></i>BAYAR NOTA PEMBELIAN BARANG</center>
	      </div>
	        <span onclick="document.getElementById('form-bayar').style.display='none'" class="close  w3-display-topright" title="Close Modal" style="margin-top: -20px;margin-right: -17px;z-index: 1"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
	      <div class="modal-body">
	      	<form id="form2" action="f_belibayar_act.php" method="post">
		        <div class="row w3-container">
		          <div class="col">
		            <div class="form-group row" >
                  
                  <!--HIDDEN FILE  -->
                    <input id="byr_tgl_hi" type="hidden"  name="byr_tgl_hi" value="<?php echo date('Y-m-d'); ?>" required="" >
                    <input type="hidden" id="byr_kd_sup" name="byr_kd_sup" value="">
                    <input id="byr_tag_fak" type="hidden" style=" font-size: 10pt;text-align: right" class="form-control hrf_arial" name="byr_tag_fak" value='<?=gantitides($gtot)?>' required="" >
                    <input type="hidden" name="ketbeli" value='<?=$ketbeli?>' >
                  <!--  -->
   
		              <label for="byr_tgl_fak" class="col-sm-4 col-form-label"><b>Tgl.Faktur</b></label>
		              <div class="col-sm-8">
		                <input id="byr_tgl_fak" type="date" style=" font-size: 10pt;" class="form-control hrf_arial" name="byr_tgl_fak" value="<?=$tgl_fak?>" required="" >
		              </div>      

		              <label for="byr_no_fak" class="col-sm-4 col-form-label"><b>Nomer Faktur</b></label>
		              <div class="col-sm-8">
		                <input id="byr_no_fak" type="text" style="font-size: 10pt;" class="form-control hrf_arial" name="byr_no_fak" value="<?=$no_fak?>" required="" >
		              </div>    

		              <label for="byr_nm_sup" class="col-sm-4 col-form-label"><b>Nama Supplier</b></label>
		              <div class="col-sm-8">
		                <input id="byr_nm_sup" type="text" style="font-size: 10pt;" class="form-control hrf_arial" name="byr_nm_fak" value='' required="" >
		              </div>    
                  <div id='viewdftsup2'></div>
                  <label for="disctot" class="col-sm-4 col-form-label w3-margin-top"><b>Discount</b></label>
                  <div class="col-sm-8 w3-margin-top">
                    <div class="form-group row" >
                      <div class="col-sm-5">
                        <input id="disctot" type="number" step="0.01" style=" font-size: 10pt;width: 70px" class="form-control " name="disctot" value='' 
                        onfocus="hitdiscbayar(this.value,<?=$gtot?>,document.getElementById('tax').value,'byr_tag_fak','byr_tag_sup1','viewbaydisc1')" 
                        onkeyup="hitdiscbayar(this.value,<?=$gtot?>,document.getElementById('tax').value,'byr_tag_fak','byr_tag_sup1','viewbaydisc')" autofocus> 
                      </div>
                      <label for="tax" class="col-sm-2 col-form-label"><b>PPn</b></label>
                      <div class="col-sm-5">
                        <input id="tax" type="number" step="0.01" style=" font-size: 10pt;" class=" form-control" name="tax" value='<?=$ppn?>' 
                        onkeyup="hitppnbayar(this.value,<?=$gtot?>,document.getElementById('disctot').value,'byr_tag_fak','byr_tag_sup1')" 
                        onfocus="hitppnbayar(this.value,<?=$gtot?>,document.getElementById('disctot').value,'byr_tag_fak','byr_tag_sup1')" > 
                      </div>
                    </div>  
                  </div>     
		              
		              <label for="byr_tag_sup1" class="col-sm-4 col-form-label " style="margin-top: -15px"><b>Tagihan</b></label>
		              <div class="col-sm-8 " style="margin-top: -15px">
		                <input id="byr_tag_sup1" type="text" style=" font-size: 12pt;text-align: right" class="form-control hrf_arial" value='<?=gantitides($gtot)?>' readonly="">
		              </div>     

                  <!--view hitdiscbayar  -->
                  <div id='viewbaydisc'></div>  
                  <div id='viewbayppn'></div>  
                 

		              <label for="byr_cr_bay" class="col-sm-4 col-form-label w3-margin-top"><b>Cara Bayar</b></label>
		                <div class="col-sm-8 w3-margin-top">
		                  <div class="input-group"> 
		                    <input id="byr_cr_bay" style="font-size: 10pt" type="text" class="form-control" name="byr_cr_bay" value="<?=$cr_bays?>" required>
		                    <span>
		                      <select id="caras" onchange="
                            document.getElementById('byr_cr_bay').value=this.value;
                            document.getElementById('byr_cr_bay').focus();
                            if (this.value=='TUNAI') { 
                              document.getElementById('byr_tgl_jt').setAttribute('disabled',true);
                              document.getElementById('byr_tag_dp').setAttribute('disabled',true)
                            } else { 
                              document.getElementById('byr_tgl_jt').removeAttribute('disabled');
                              document.getElementById('byr_tag_dp').removeAttribute('disabled')
                            }" 
                            style="width: 37px;font-size: 10pt" name="cara" class="form-control w3-hover-shadow btn btn-primary">
                            <option value="Pilih cara bayar"></option>
                            <option value="TUNAI">TUNAI</option>
                            <option value="TEMPO">TEMPO</option>
		                      </select>
		                    </span>
		                  </div>   
		                </div>

                  <label for="byr_tgl_jt" class="col-sm-4 col-form-label"><b>Tempo Tanggal</b></label>
		              <div class="col-sm-8 ">
		                <input id="byr_tgl_jt" type="date" style=" font-size: 10pt;" class="form-control hrf_arial" name="byr_tgl_jt" value="<?=$tgl_tempo?>" required="" >
		              </div>                             

		              <label for="byr_tag_dp" class="col-sm-4 col-form-label"><b>Down Payment</b></label>
		              <div class="col-sm-8">
		                <input id="byr_tag_dp" type="text" style=" font-size: 12pt;text-align: right" class="form-control hrf_arial money" name="byr_tag_dp" required="" >
		              </div>                             
		            </div>      
		          </div>
		        </div>
		        <div class="row w3-container">
		          <div class="col-sm-8 offset-sm-2 text-center"> 
	                <?php if ( !empty($no_fak) > 0) {?>
	                <button type="submit" class="btn btn-primary" style="box-shadow: 1px 1px 2px black;font-size: 12px;width: 70px;height: 30px">Simpan</button>   
	                <?php } else {?>     
	                <button type="submit" class="btn btn-primary" style="box-shadow: 1px 1px 2px black;font-size: 12px;width: 70px;height: 30px" disabled>Simpan</button>     
	                <?php } ?>
                  <button onclick="document.getElementById('form-bayar').style.display='none'" type="button" class="btn btn-warning" style="box-shadow: 1px 1px 2px black;font-size: 12px;width: 70px;height: 30px">Batal</button>   
	            </div>	              
		        </div>
	        </form>
	      </div>
	    </div>  
	  </div>
	<!-- End form bayar -->		
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
  mysqli_close($connect);
	$html = ob_get_contents(); 
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>