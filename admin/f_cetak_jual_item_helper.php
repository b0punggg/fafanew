<?php
  if (!defined('F_CETAK_JUAL_ITEM_HELPER')) {
    define('F_CETAK_JUAL_ITEM_HELPER', true);
    include_once 'config.php';
  }

  /**
   * Hitung total penjualan dan total retur per bagian (id_bag) dalam rentang tanggal.
   * Return string "total_jual;total_retur"
   */
  if (!function_exists('caritotbag')) {
  function caritotbag($id_bag,$kd_toko,$tgl1,$tgl2,$cr_bay,$connect){
    $total_jual = 0;
    $total_retur = 0;

    try {
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
        error_log("Error in caritotbag() - SQL query failed: " . mysqli_error($connect));
      }

      // Total retur per bagian (kolom qty di retur_jual = qty_retur)
      $sqlr = mysqli_query($connect,"
        SELECT retur_jual.qty_retur AS qty,dum_jual.hrg_jual,dum_jual.discitem,dum_jual.discrp,dum_jual.discvo
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
        error_log("Error in caritotbag() - SQL retur query failed: " . mysqli_error($connect));
      }
    } catch (Throwable $e) {
      error_log("Error in caritotbag(): " . $e->getMessage());
    }

    return $total_jual.';'.$total_retur;
  }
  }
  if (!function_exists('brandReportDefaults')) {
    function brandReportDefaults() {
      return array(
        'BC OMG','OMG','EMINA','WARDAH','MAKE OVER','KAHF','SKINTIFIC MARINA','G2G','GLAD2GLOW',
        'HANASUI','SLAVINA','SCARLET','HADALABO','IMPLORA','VIVA','GLOW & LOVELY','PONDS','GARNIER',
        'CUSSONS','NIVEA','PIXY','MAKARIZO','YOU','NYU','MIRANDA','DAZZLE ME','ANIMATE','MY BABY',
        'MOELL','NPURE','SCORA','FACETOLOGY','JHONSONS','EUREKA','EVANGELINE','VITALIS','GATSBY',
        'PUCELLE','ANDO','PRO ATT','CARVIL','BENING','LOGO','NEW ERA','SPEED','ARMOD','LUBRENA','VAUSTIN'
      );
    }
  }

  if (!function_exists('ensureBrandReportTable')) {
    function ensureBrandReportTable($connect) {
      mysqli_query($connect, "CREATE TABLE IF NOT EXISTS brand_report (
        no_urut INT NOT NULL AUTO_INCREMENT,
        nm_brand VARCHAR(100) NOT NULL,
        PRIMARY KEY (no_urut),
        UNIQUE KEY uq_nm_brand (nm_brand)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

      $cek = mysqli_query($connect, "SELECT COUNT(*) AS jml FROM brand_report");
      if ($cek) {
        $row = mysqli_fetch_assoc($cek);
        mysqli_free_result($cek);
        if ((int)$row['jml'] === 0) {
          foreach (brandReportDefaults() as $nm) {
            $nm_esc = mysqli_real_escape_string($connect, $nm);
            mysqli_query($connect, "INSERT IGNORE INTO brand_report (nm_brand) VALUES ('$nm_esc')");
          }
        }
      }
    }
  }

  if (!function_exists('getReportBrandOptions')) {
    function getReportBrandOptions($connect) {
      ensureBrandReportTable($connect);
      $brands = array();
      $q = mysqli_query($connect, "SELECT nm_brand FROM brand_report ORDER BY nm_brand ASC");
      if ($q) {
        while ($r = mysqli_fetch_assoc($q)) {
          $brands[] = $r['nm_brand'];
        }
        mysqli_free_result($q);
      }
      return $brands;
    }
  }

  if (!function_exists('sanitizeReportBrandFilter')) {
    function sanitizeReportBrandFilter($connect, $brand_filter) {
      if ($brand_filter === '' || $brand_filter === null) {
        return '';
      }
      ensureBrandReportTable($connect);
      $brand_filter = mysqli_real_escape_string($connect, trim($brand_filter));
      $q = mysqli_query($connect, "SELECT nm_brand FROM brand_report WHERE nm_brand='$brand_filter' LIMIT 1");
      if ($q && mysqli_num_rows($q) >= 1) {
        mysqli_free_result($q);
        return $brand_filter;
      }
      if ($q) {
        mysqli_free_result($q);
      }
      return '';
    }
  }
?>
