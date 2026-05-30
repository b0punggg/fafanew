<?php
  include 'config.php';

  /**
   * Hitung total penjualan dan total retur per bagian (id_bag) dalam rentang tanggal.
   * Return string "total_jual;total_retur"
   */
  function caritotbag($id_bag,$kd_toko,$tgl1,$tgl2,$cr_bay,$connect){
    $total_jual = 0;
    $total_retur = 0;

    // Escape semua input untuk mencegah SQL injection
    $id_bag = mysqli_real_escape_string($connect, $id_bag);
    $kd_toko = mysqli_real_escape_string($connect, $kd_toko);
    $tgl1 = mysqli_real_escape_string($connect, $tgl1);
    $tgl2 = mysqli_real_escape_string($connect, $tgl2);
    $cr_bay = mysqli_real_escape_string($connect, $cr_bay);

    // Filter cara bayar
    $filter_bayar = "";
    if($cr_bay == 'TUNAI'){
      $filter_bayar = " AND dum_jual.kd_bayar='TUNAI' ";
    }elseif($cr_bay == 'TEMPO'){
      $filter_bayar = " AND dum_jual.kd_bayar='TEMPO' ";
    }

    // Total penjualan per bagian
    $sql = mysqli_query($connect,"
      SELECT dum_jual.qty_brg,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.discvo
      FROM dum_jual
      WHERE dum_jual.kd_toko='$kd_toko'
        AND dum_jual.tgl_jual>='$tgl1'
        AND dum_jual.tgl_jual<='$tgl2'
        AND dum_jual.id_bag='$id_bag'
        $filter_bayar
        AND panding=false
    ");
    if ($sql && $sql !== false) {
      while($row = mysqli_fetch_assoc($sql)){
        $qty  = floatval($row['qty_brg']);
        $hrg  = floatval($row['hrg_jual']);
        $discitem = floatval($row['discitem']);
        $discrp   = floatval($row['discrp']);
        $discvo   = floatval($row['discvo']);

        $hrg_net = $hrg - $discrp;
        if($discitem > 0){
          $hrg_net -= $hrg * ($discitem/100);
        }
        if($discvo > 0){
          $hrg_net -= $hrg * ($discvo/100);
        }
        if($hrg_net < 0){ $hrg_net = 0; }
        $total_jual += $qty * $hrg_net;
      }
      mysqli_free_result($sql);
    } else {
      // Log error jika query gagal
      error_log("Error in caritotbag() - SQL query failed: " . mysqli_error($connect));
    }

    // Total retur per bagian
    $sqlr = mysqli_query($connect,"
      SELECT retur_jual.qty_brg AS qty,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.discvo
      FROM retur_jual
      LEFT JOIN dum_jual ON retur_jual.no_urutjual=dum_jual.no_urut
      WHERE retur_jual.kd_toko='$kd_toko'
        AND retur_jual.tgl_retur>='$tgl1'
        AND retur_jual.tgl_retur<='$tgl2'
        AND dum_jual.id_bag='$id_bag'
    ");
    if ($sqlr && $sqlr !== false) {
      while($row = mysqli_fetch_assoc($sqlr)){
        $qty  = floatval($row['qty']);
        $hrg  = floatval($row['hrg_jual']);
        $discitem = floatval($row['discitem']);
        $discrp   = floatval($row['discrp']);
        $discvo   = floatval($row['discvo']);

        $hrg_net = $hrg - $discrp;
        if($discitem > 0){
          $hrg_net -= $hrg * ($discitem/100);
        }
        if($discvo > 0){
          $hrg_net -= $hrg * ($discvo/100);
        }
        if($hrg_net < 0){ $hrg_net = 0; }
        $total_retur += $qty * $hrg_net;
      }
      mysqli_free_result($sqlr);
    } else {
      // Log error jika query gagal
      error_log("Error in caritotbag() - SQL retur query failed: " . mysqli_error($connect));
    }

    return $total_jual.';'.$total_retur;
  }
?>

