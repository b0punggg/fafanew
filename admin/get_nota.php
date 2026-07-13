<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
include 'config.php';
include_once 'nota_data.php';

$cones = opendtcek();
$output = array();

if (isset($_GET['dts'])) {
  $xd = explode(';', $_GET['dts']);
  $output = build_nota_data($cones, array(
    'no_fakjual' => isset($xd[0]) ? $xd[0] : '',
    'tgl_jual'   => isset($xd[1]) ? $xd[1] : '',
    'kd_toko'    => isset($xd[2]) ? $xd[2] : '',
    'nm_pel'     => isset($xd[3]) ? $xd[3] : '',
    'alamat'     => isset($xd[4]) ? $xd[4] : '',
    'tgltime'    => isset($xd[5]) ? $xd[5] : '',
    'disctot'    => isset($xd[6]) ? floatval($xd[6]) : 0,
    'voucher'    => isset($xd[7]) ? floatval($xd[7]) : 0,
    'ongkir'     => isset($xd[8]) ? floatval($xd[8]) : 0,
    'kd_bayar'   => isset($xd[9]) ? $xd[9] : 'TUNAI',
    'bayar'      => isset($xd[10]) ? floatval($xd[10]) : 0,
    'susuk'      => isset($xd[11]) ? floatval($xd[11]) : 0,
    'saldohut'   => isset($xd[12]) ? floatval($xd[12]) : 0,
    'jtempo'     => isset($xd[13]) ? $xd[13] : '',
  ));
}

mysqli_close($cones);

echo json_encode(array(
  'success' => true,
  'data'    => $output,
), JSON_UNESCAPED_UNICODE);
