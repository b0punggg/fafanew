<?php
/**
 * Bangun payload JSON nota thermal — dipakai get_nota.php dan f_jual_cetnota.php.
 */
function build_nota_data($connect, $params) {
  $no_fakjual = isset($params['no_fakjual']) ? $params['no_fakjual'] : '';
  $tgl_jual   = isset($params['tgl_jual']) ? $params['tgl_jual'] : '';
  $kd_toko    = isset($params['kd_toko']) ? $params['kd_toko'] : '';
  $nm_pel     = isset($params['nm_pel']) ? $params['nm_pel'] : '';
  $alamat     = isset($params['alamat']) ? $params['alamat'] : '';
  $tgltime    = isset($params['tgltime']) ? $params['tgltime'] : '';
  $disctot    = isset($params['disctot']) ? floatval($params['disctot']) : 0;
  $voucher    = isset($params['voucher']) ? floatval($params['voucher']) : 0;
  $ongkir     = isset($params['ongkir']) ? floatval($params['ongkir']) : 0;
  $kd_bayar   = isset($params['kd_bayar']) ? $params['kd_bayar'] : 'TUNAI';
  $bayar      = isset($params['bayar']) ? floatval($params['bayar']) : 0;
  $susuk      = isset($params['susuk']) ? floatval($params['susuk']) : 0;
  $saldohut   = isset($params['saldohut']) ? floatval($params['saldohut']) : 0;
  $jtempo     = isset($params['jtempo']) ? $params['jtempo'] : '';

  $nm_toko     = isset($params['nm_toko']) ? $params['nm_toko'] : '';
  $al_toko     = isset($params['al_toko']) ? $params['al_toko'] : '';
  $nm_member   = isset($params['nm_member']) ? $params['nm_member'] : '';
  $poin_earned = isset($params['poin_earned']) ? floatval($params['poin_earned']) : 0;
  $poin_saldo  = isset($params['poin_saldo']) ? floatval($params['poin_saldo']) : 0;

  if ($nm_toko === '' && $kd_toko !== '') {
    $sqltoko = mysqli_query($connect, "SELECT nm_toko, al_toko FROM toko WHERE kd_toko='$kd_toko' LIMIT 1");
    if ($sqltoko && mysqli_num_rows($sqltoko) > 0) {
      $dttoko = mysqli_fetch_assoc($sqltoko);
      $nm_toko = $dttoko['nm_toko'];
      $al_toko = $dttoko['al_toko'];
      mysqli_free_result($sqltoko);
    }
  }

  if ($nm_member === '' && $no_fakjual !== '') {
    $cek_member = mysqli_query(
      $connect,
      "SELECT kd_member, poin_earned FROM mas_jual WHERE no_fakjual='$no_fakjual' AND tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' LIMIT 1"
    );
    if ($cek_member && mysqli_num_rows($cek_member) > 0) {
      $dt_member = mysqli_fetch_assoc($cek_member);
      $kd_member = isset($dt_member['kd_member']) ? $dt_member['kd_member'] : '';
      $poin_earned = isset($dt_member['poin_earned']) ? floatval($dt_member['poin_earned']) : 0;
      mysqli_free_result($cek_member);

      if (!empty($kd_member)) {
        $sqlmember = mysqli_query(
          $connect,
          "SELECT nm_member, poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko' LIMIT 1"
        );
        if ($sqlmember && mysqli_num_rows($sqlmember) > 0) {
          $datamember = mysqli_fetch_assoc($sqlmember);
          $nm_member = $datamember['nm_member'];
          $poin_saldo = isset($datamember['poin']) ? floatval($datamember['poin']) : 0;
          mysqli_free_result($sqlmember);
        }
      }
    } elseif ($cek_member) {
      mysqli_free_result($cek_member);
    }
  }

  $items = array();
  $total = 0;
  $sql = mysqli_query(
    $connect,
    "SELECT *,sum(dum_jual.qty_brg) AS qty_brg FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_jual' AND dum_jual.kd_toko='$kd_toko' GROUP BY dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.discitem,dum_jual.discrp,dum_jual.hrg_jual ORDER BY dum_jual.no_urut ASC"
  );
  if ($sql && mysqli_num_rows($sql) > 0) {
    while ($data = mysqli_fetch_assoc($sql)) {
      $nm_sat = ' ' . ucwords(strtolower($data['nm_sat1']));
      $hrg_jual = round($data['hrg_jual'], 0);
      $discitem = isset($data['discitem']) ? round($data['discitem'], 0) : 0;

      if ($data['discrp'] > 0) {
        $subtot = ($data['hrg_jual'] - $data['discrp']) * $data['qty_brg'];
      } else {
        $subtot = $data['hrg_jual'] * $data['qty_brg'];
      }

      $qty_brg = round($data['qty_brg'], 0);
      $discrp = round($data['discrp'], 0);
      $hrg_jual = round($data['hrg_jual'], 0);
      $total = $total + round($subtot, 0);
      $total = round($total, 0);
      $subtot = round($subtot, 0);

      $items[] = array(
        'nmbrg'   => trim(ucwords(strtolower($data['nm_brg']))),
        'qty'     => $qty_brg,
        'sat'     => $nm_sat,
        'hrg'     => $hrg_jual,
        'disc'    => $discrp,
        'discpct' => $discitem,
        'subtot'  => $subtot,
      );
    }
    mysqli_free_result($sql);
  }

  $totbelanja = ($total - ($disctot + $voucher)) + $ongkir;

  return array(
    'no_fakjual'  => $no_fakjual,
    'tgl_jual'    => $tgl_jual,
    'nm_toko'     => trim($nm_toko),
    'al_toko'     => trim($al_toko),
    'nm_pel'      => trim($nm_pel),
    'alamat'      => $alamat,
    'tgltime'     => $tgltime,
    'nm_member'   => trim($nm_member),
    'poin_earned' => $poin_earned,
    'poin_saldo'  => $poin_saldo,
    'belanja'     => $total,
    'total'       => gantiti($totbelanja),
    'total_bayar' => $totbelanja,
    'disctot'     => gantiti($disctot),
    'disctot_raw' => $disctot,
    'voucher'     => gantiti($voucher),
    'voucher_raw' => $voucher,
    'ongkir'      => gantiti($ongkir),
    'ongkir_raw'  => $ongkir,
    'kd_bayar'    => $kd_bayar,
    'bayar'       => $bayar,
    'susuk'       => $susuk,
    'saldohut'    => $saldohut,
    'jtempo'      => $jtempo,
    'items'       => $items,
  );
}
