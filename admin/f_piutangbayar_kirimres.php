<?php
ob_start();
include 'config.php';
session_start();
  
$url = "https://dania.yazgv.net/admin/kirimbrg.php";
$cin        = opendtcek();
$no_fakjual = mysqli_escape_string($cin,$_POST['keyword']);
$cekq=mysqli_query($cin,"SELECT * FROM file_kirim WHERE no_fakjual='$no_fakjual'");
if(mysqli_num_rows($cekq)>=1){
  $dtk=mysqli_fetch_assoc($cekq);
  $tglk=gantitgl($dtk['tgl_kirim']);  
  unset($dtk);
  ?><script>popnew_error("Data sudah dikirim tanggal "+'<?=$tglk?>')</script><?php
}else{
  $sq         = mysqli_query($cin,"SELECT *,mas_brg.nm_brg,mas_brg.kd_bar,kemas.nm_sat2 
                FROM dum_jual 
                LEFT JOIN mas_brg ON dum_jual.kd_brg=mas_brg.kd_brg
                LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut
                WHERE no_fakjual='$no_fakjual'");

  while($dtc=mysqli_fetch_assoc($sq)){
    $disc1  = $dtc['discitem']/100;
    $discvo = $dtc['discvo']/100;

    if ($dtc['discitem']=='0' && $dtc['discrp']=='0' ) {
        $jmlsub=round($dtc['hrg_jual']*$dtc['qty_brg'],0);
    } 
    if ($dtc['discitem'] >0 && $dtc['discrp']==0 ) {
        $jmlsub=($dtc['hrg_jual']-($dtc['hrg_jual']*$disc1))*$dtc['qty_brg'];
    }
    if ($dtc['discitem'] == 0 && $dtc['discrp'] > 0 ) {
        $jmlsub=($dtc['hrg_jual']-$dtc['discrp'])*$dtc['qty_brg']; 
    }  
    if ($dtc['discitem'] > 0 && $dtc['discrp'] > 0 ) {
        $jmlsub=($dtc['hrg_jual']-(($dtc['hrg_jual']*$disc1)+$dtc['discrp']))*$dtc['qty_brg']; 
    }  
    if($dtc['discvo']>0){
        $jmlsub=($dtc['hrg_jual']-(($dtc['hrg_jual']*$disc1)+($dtc['hrg_jual']*$discvo)+$dtc['discrp']))*$dtc['qty_brg']; 
    }
    $tgl_fak      = $dtc['tgl_jual'];
    $no_fak       = $dtc['no_fakjual'];
    $kd_brg       = $dtc['kd_brg'];
    $kd_bar       = $dtc['kd_bar'];
    $kd_toko      = 'IDTOKO-1';
    $kd_sup       = '';
    $nm_sup       = $_SESSION['nm_toko'];
    $nm_sat       = $dtc['nm_sat2'];
    $hrg_beli     = $jmlsub;
    $disc1        = 0;
    $disc2        = 0;
    $jml          = $dtc['qty_brg'];
    $stok_jual    = $dtc['qty_brg'];
    $ket          = 'PEMBELIAN BARANG';
    $no_item      = '';
    $no_item_del  = '';
    $ket_mut      = '';
    $ppn          = 0;
    $kd_kat       = '14';
    $kd_gol       = '5';
    $kons         = 'K';
    $exp_d        = '0000-00-00';
    $nm_brg       = $dtc['nm_brg'];
    $hrg_jual     = $dtc['hrg_jual'];

    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, "data1=".$tgl_fak."&data2=".$no_fak."&data3=".$kd_brg."&data4=".$kd_bar."&data5=".$kd_toko."&data6=".$nm_sup."&data7=".$nm_sat."&data8=".$hrg_beli."&data9=".$disc1."&data10=".$disc2."&data11=".$jml."&data12=".$stok_jual."&data13=".$ket."&data14=".$no_item."&data15=".$no_item_del."&data16=".$ket_mut."&data17=".$ppn."&data18=".$kd_kat."&data19=".$kd_gol."&data20=".$kons."&data21=".$exp_d."&data22=".$nm_brg."&data23=".$hrg_jual);
    curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
    curl_setopt($curlHandle, CURLOPT_POST, 1);
    curl_exec($curlHandle);
    curl_close($curlHandle);
  }
  $stb=mysqli_query($cin,"SELECT * FROM file_kirim WHERE no_fakjual='$no_fak' ORDER BY no_urut");
  while($dtb=mysqli_fetch_assoc($stb)){
    if($dtb['kirim']=='0'){
        // $status='Data gagal dikirim';
        ?><script>popnew_warning("Data gagal dikirim")</script><?php
    }
    else{
        // $status='Data berhasil dikirim';
        ?><script>popnew_warning("Data berhasil dikirim")</script><?php
    }
  }
}
mysqli_free_result($cekq);   
mysqli_close($cin);
$html = ob_get_contents(); 
ob_end_clean();
echo json_encode(array('hasil'=>$html));
?>
