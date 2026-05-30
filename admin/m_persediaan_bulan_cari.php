<?php
// Increase execution time for large datasets
set_time_limit(180); // Increase to 180 seconds (3 minutes)
ini_set('max_execution_time', 180);
ini_set('memory_limit', '256M'); // Increase memory limit

ob_start();
// Start session only if not already started
if(!session_id()){
  session_start();
}
include 'config.php';
$kd_toko = $_SESSION['id_toko'];
$connect = opendtcek();

// Pastikan tabel persediaan_bulan ada
$create_table = "CREATE TABLE IF NOT EXISTS `persediaan_bulan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_brg` varchar(50) NOT NULL,
  `bulan` varchar(2) NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `stok_juals` decimal(15,2) DEFAULT 0.00,
  `hrg_beli` decimal(15,2) DEFAULT 0.00,
  `nilai_persediaan` decimal(15,2) DEFAULT 0.00,
  `kd_sup` varchar(50) DEFAULT NULL,
  `id_bag` int(11) DEFAULT NULL,
  `kd_toko` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_persediaan` (`kd_brg`, `bulan`, `tahun`, `kd_toko`),
  KEY `idx_kd_brg` (`kd_brg`),
  KEY `idx_bulan_tahun` (`bulan`, `tahun`),
  KEY `idx_kd_toko` (`kd_toko`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

mysqli_query($connect, $create_table);

$page = (isset($_POST['page'])) ? $_POST['page'] : 1;
$limit = 20; // Increase limit sedikit untuk mengurangi jumlah request, tapi tidak terlalu banyak
$limit_start = ($page - 1) * $limit;
$keyword = isset($_POST['keyword']) ? mysqli_real_escape_string($connect, $_POST['keyword']) : '';
$bulan = isset($_POST['bulan']) ? mysqli_real_escape_string($connect, $_POST['bulan']) : date('m');
$tahun = isset($_POST['tahun']) ? mysqli_real_escape_string($connect, $_POST['tahun']) : date('Y');
$cek_stok_kosong = isset($_POST['cek_stok_kosong']) ? intval($_POST['cek_stok_kosong']) : 0;
$filter_bulan_tahun = isset($_POST['filter_bulan_tahun']) ? intval($_POST['filter_bulan_tahun']) : 0; // Flag apakah filter bulan/tahun aktif

// Pastikan bulan dalam format 2 digit (01-12)
if(strlen($bulan) == 1){
  $bulan = '0' . $bulan;
}

// Cek setting tampilan stok kosong (sama seperti f_stokbrg_cari.php)
$sqlstok = @mysqli_query($connect, "SELECT kode FROM seting WHERE nm_per='tampil_stok'");
if($sqlstok && mysqli_num_rows($sqlstok) > 0){
  $dtper = mysqli_fetch_assoc($sqlstok);
  $kodestok = $dtper['kode'];
} else {
  $kodestok = 0;
}

// Jika filter_bulan_tahun = 0, tampilkan semua data stok seperti di f_stokbrg_cari.php
// Jika filter_bulan_tahun = 1, filter berdasarkan bulan/tahun
if($filter_bulan_tahun == 0){
  // Tampilkan semua data stok tanpa filter bulan/tahun
  if($kodestok == 1 || $cek_stok_kosong == 1){
    $tampil_stok = '';
    $tampil_stok1 = '';
    $having_clause = "HAVING SUM(beli_brg.stok_jual) >= 0";
  } else {
    $tampil_stok = " AND beli_brg.stok_jual > 0 ";
    $tampil_stok1 = " AND beli_brg.stok_jual > 0 ";
    $having_clause = "HAVING SUM(beli_brg.stok_jual) > 0";
  }
  
  // Query untuk semua data stok (tanpa filter bulan/tahun)
  if($keyword != ''){
    $keyword_escaped = mysqli_real_escape_string($connect, $keyword);
    // Cek apakah keyword mengandung "like" untuk pencarian khusus
    $xada = strpos($keyword_escaped, "like");
    if($xada !== false){
      $pecah = explode('like', $keyword_escaped);
      $kunci = trim($pecah[0]);
      $kunci2 = trim($pecah[1]);
      $params = $kunci . " like '%" . $kunci2 . "%'";
    } else {
      $params = "(mas_brg.nm_brg LIKE '%$keyword_escaped%' OR beli_brg.kd_brg LIKE '%$keyword_escaped%' OR mas_brg.kd_bar LIKE '%$keyword_escaped%')";
    }
    
    $query_sql = "SELECT 
      SUM(beli_brg.stok_jual) AS stok_juals,
      beli_brg.kd_brg,
      beli_brg.kd_sup,
      MAX(beli_brg.id_bag) AS id_bag,
      AVG(beli_brg.hrg_beli) AS hrg_beli,
      mas_brg.nm_brg,
      mas_brg.kd_kem1,
      mas_brg.kd_kem2,
      mas_brg.kd_kem3,
      mas_brg.jum_kem1,
      mas_brg.jum_kem2,
      mas_brg.jum_kem3,
      mas_brg.hrg_jum1,
      mas_brg.hrg_jum2,
      mas_brg.hrg_jum3,
      mas_brg.nm_kem1,
      mas_brg.nm_kem2,
      mas_brg.nm_kem3,
      mas_brg.brg_msk,
      mas_brg.brg_klr,
      mas_brg.kd_bar,
      supplier.nm_sup,
      bag_brg.nm_bag,
      '$bulan' AS bulan,
      '$tahun' AS tahun,
      (SUM(beli_brg.stok_jual) * AVG(beli_brg.hrg_beli)) AS nilai_persediaan
      FROM beli_brg
      INNER JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg AND beli_brg.kd_toko = mas_brg.kd_toko
      LEFT JOIN supplier ON beli_brg.kd_sup = supplier.kd_sup
      LEFT JOIN bag_brg ON beli_brg.id_bag = bag_brg.no_urut
      WHERE $params AND beli_brg.kd_toko='$kd_toko' $tampil_stok1
      GROUP BY beli_brg.kd_brg
      $having_clause
      ORDER BY mas_brg.nm_brg ASC
      LIMIT $limit_start, $limit";
    
    $sql = @mysqli_query($connect, $query_sql);
    
    $query_sql2 = "SELECT count(*) AS jumlah FROM (
      SELECT beli_brg.kd_brg
      FROM beli_brg 
      INNER JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg AND beli_brg.kd_toko = mas_brg.kd_toko
      WHERE $params AND beli_brg.kd_toko='$kd_toko' $tampil_stok1
      GROUP BY beli_brg.kd_brg
      $having_clause
    ) jumlah";
  } else {
    $query_sql = "SELECT 
      SUM(beli_brg.stok_jual) AS stok_juals,
      beli_brg.kd_brg,
      beli_brg.kd_sup,
      MAX(beli_brg.id_bag) AS id_bag,
      AVG(beli_brg.hrg_beli) AS hrg_beli,
      mas_brg.nm_brg,
      mas_brg.kd_kem1,
      mas_brg.kd_kem2,
      mas_brg.kd_kem3,
      mas_brg.jum_kem1,
      mas_brg.jum_kem2,
      mas_brg.jum_kem3,
      mas_brg.hrg_jum1,
      mas_brg.hrg_jum2,
      mas_brg.hrg_jum3,
      mas_brg.nm_kem1,
      mas_brg.nm_kem2,
      mas_brg.nm_kem3,
      mas_brg.brg_msk,
      mas_brg.brg_klr,
      mas_brg.kd_bar,
      supplier.nm_sup,
      bag_brg.nm_bag,
      '$bulan' AS bulan,
      '$tahun' AS tahun,
      (SUM(beli_brg.stok_jual) * AVG(beli_brg.hrg_beli)) AS nilai_persediaan
      FROM beli_brg
      INNER JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg AND beli_brg.kd_toko = mas_brg.kd_toko
      LEFT JOIN supplier ON beli_brg.kd_sup = supplier.kd_sup
      LEFT JOIN bag_brg ON beli_brg.id_bag = bag_brg.no_urut
      WHERE beli_brg.kd_toko='$kd_toko' $tampil_stok
      GROUP BY beli_brg.kd_brg
      $having_clause
      ORDER BY mas_brg.nm_brg ASC
      LIMIT $limit_start, $limit";
    
    $sql = @mysqli_query($connect, $query_sql);
    
    $query_sql2 = "SELECT count(*) AS jumlah FROM (
      SELECT beli_brg.kd_brg
      FROM beli_brg 
      INNER JOIN mas_brg ON beli_brg.kd_brg = mas_brg.kd_brg AND beli_brg.kd_toko = mas_brg.kd_toko
      WHERE beli_brg.kd_toko='$kd_toko' $tampil_stok
      GROUP BY beli_brg.kd_brg
      $having_clause
    ) jumlah";
  }
  
  $sql2 = @mysqli_query($connect, $query_sql2);
  
} else {
  // Filter berdasarkan bulan/tahun (kode yang sudah ada sebelumnya)
  // Build WHERE clause dengan index yang tepat
  $where_base = "pb.kd_toko='$kd_toko' AND pb.bulan='$bulan' AND pb.tahun='$tahun'";
  $where_keyword = "";
  if($keyword != ''){
    $where_keyword = " AND (mb.nm_brg LIKE '%$keyword%' OR pb.kd_brg LIKE '%$keyword%' OR sup.nm_sup LIKE '%$keyword%')";
  }

  // Filter berdasarkan bulan dan tahun transaksi pembelian
  // Jika cek_stok_kosong = 1, sertakan stok kosong (stok_jual >= 0), jika tidak hanya stok > 0
  if($cek_stok_kosong == 1){
    $where_beli = "beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual >= 0 AND MONTH(beli_brg.tgl_fak)='$bulan' AND YEAR(beli_brg.tgl_fak)='$tahun'";
    $having_clause = "HAVING stok_juals >= 0";
  } else {
    $where_beli = "beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual > 0 AND MONTH(beli_brg.tgl_fak)='$bulan' AND YEAR(beli_brg.tgl_fak)='$tahun'";
    $having_clause = "HAVING stok_juals > 0";
  }

// Query langsung dari beli_brg dengan filter bulan/tahun dari tgl_fak
  
  // Optimasi: Jika ada keyword, filter di subquery dulu
  if($keyword != ''){
    $keyword_escaped = mysqli_real_escape_string($connect, $keyword);
    // Gunakan subquery untuk filter lebih efisien
    $sql = mysqli_query($connect, "SELECT 
      sub.kd_sup,
      sub.kd_brg,
      sub.stok_juals,
      sub.hrg_beli,
      sub.id_bag,
      mas_brg.nm_brg,
      mas_brg.kd_kem1,
      mas_brg.kd_kem2,
      mas_brg.kd_kem3,
      mas_brg.jum_kem1,
      mas_brg.jum_kem2,
      mas_brg.jum_kem3,
      mas_brg.nm_kem1,
      mas_brg.nm_kem2,
      mas_brg.nm_kem3,
      supplier.nm_sup,
      bag_brg.nm_bag,
      '$bulan' AS bulan,
      '$tahun' AS tahun,
      (sub.stok_juals * sub.hrg_beli) AS nilai_persediaan
      FROM (
        SELECT 
          beli_brg.kd_sup,
          beli_brg.kd_brg,
          SUM(beli_brg.stok_jual) AS stok_juals,
          AVG(beli_brg.hrg_beli) AS hrg_beli,
          MAX(beli_brg.id_bag) AS id_bag
        FROM beli_brg
        WHERE $where_beli
        GROUP BY beli_brg.kd_brg
        $having_clause
      ) AS sub
      LEFT JOIN mas_brg ON sub.kd_brg = mas_brg.kd_brg
      LEFT JOIN supplier ON sub.kd_sup = supplier.kd_sup
      LEFT JOIN bag_brg ON sub.id_bag = bag_brg.no_urut
      WHERE (mas_brg.nm_brg LIKE '%$keyword_escaped%' OR sub.kd_brg LIKE '%$keyword_escaped%' OR supplier.nm_sup LIKE '%$keyword_escaped%')
      ORDER BY COALESCE(mas_brg.nm_brg, sub.kd_brg) ASC
      LIMIT $limit_start, $limit");
  } else {
    // Tanpa keyword, query lebih sederhana
    $sql = mysqli_query($connect, "SELECT 
      sub.kd_sup,
      sub.kd_brg,
      sub.stok_juals,
      sub.hrg_beli,
      sub.id_bag,
      mas_brg.nm_brg,
      mas_brg.kd_kem1,
      mas_brg.kd_kem2,
      mas_brg.kd_kem3,
      mas_brg.jum_kem1,
      mas_brg.jum_kem2,
      mas_brg.jum_kem3,
      mas_brg.nm_kem1,
      mas_brg.nm_kem2,
      mas_brg.nm_kem3,
      supplier.nm_sup,
      bag_brg.nm_bag,
      '$bulan' AS bulan,
      '$tahun' AS tahun,
      (sub.stok_juals * sub.hrg_beli) AS nilai_persediaan
      FROM (
        SELECT 
          beli_brg.kd_sup,
          beli_brg.kd_brg,
          SUM(beli_brg.stok_jual) AS stok_juals,
          AVG(beli_brg.hrg_beli) AS hrg_beli,
          MAX(beli_brg.id_bag) AS id_bag
        FROM beli_brg
        WHERE $where_beli
        GROUP BY beli_brg.kd_brg
        $having_clause
      ) AS sub
      LEFT JOIN mas_brg ON sub.kd_brg = mas_brg.kd_brg
      LEFT JOIN supplier ON sub.kd_sup = supplier.kd_sup
      LEFT JOIN bag_brg ON sub.id_bag = bag_brg.no_urut
      ORDER BY COALESCE(mas_brg.nm_brg, sub.kd_brg) ASC
      LIMIT $limit_start, $limit");
  }
  
  // Count query untuk beli_brg - optimasi dengan menghitung langsung tanpa JOIN yang tidak perlu
  if($keyword != ''){
    $keyword_escaped = mysqli_real_escape_string($connect, $keyword);
    $count_query_beli = "SELECT COUNT(*) AS jumlah FROM (
      SELECT sub.kd_brg
      FROM (
        SELECT 
          beli_brg.kd_sup,
          beli_brg.kd_brg,
          SUM(beli_brg.stok_jual) AS stok_juals
        FROM beli_brg
        WHERE $where_beli
        GROUP BY beli_brg.kd_brg
        $having_clause
      ) AS sub
      LEFT JOIN mas_brg ON sub.kd_brg = mas_brg.kd_brg
      LEFT JOIN supplier ON sub.kd_sup = supplier.kd_sup
      WHERE (mas_brg.nm_brg LIKE '%$keyword_escaped%' OR sub.kd_brg LIKE '%$keyword_escaped%' OR supplier.nm_sup LIKE '%$keyword_escaped%')
    ) AS final";
  } else {
    $count_query_beli = "SELECT COUNT(*) AS jumlah FROM (
      SELECT 
        beli_brg.kd_brg,
        SUM(beli_brg.stok_jual) AS stok_juals
      FROM beli_brg
      WHERE $where_beli
      GROUP BY beli_brg.kd_brg
      $having_clause
    ) AS subquery";
  }
  $sql2 = mysqli_query($connect, $count_query_beli);
  
  $use_persediaan_table = false;
}

// Error handling
if(!$sql || !$sql2){
  $error_msg = mysqli_error($connect);
  ?>
  <div class="alert alert-danger">
    <strong>Error:</strong> <?=$error_msg?>
  </div>
  <?php
  mysqli_close($connect);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
  exit;
}

$get_jumlah = mysqli_fetch_array($sql2);
$jumlah_page = ($get_jumlah && isset($get_jumlah['jumlah'])) ? ceil($get_jumlah['jumlah'] / $limit) : 0;
$jumlah = ($get_jumlah && isset($get_jumlah['jumlah'])) ? $get_jumlah['jumlah'] : 0;

$no = $limit_start;
$total_nilai = 0;
?>
<table class="table table-bordered table-hover table-sm" style="font-size: 9pt">
  <thead>
    <tr align="middle" style="background-color: #4CAF50;color: white">
      <th style="width: 3%">No</th>
      <th style="width: 10%">Kode Barang</th>
      <th style="width: 20%">Nama Barang</th>
      <th style="width: 12%">Supplier</th>
      <th style="width: 10%">Bagian</th>
      <th style="width: 10%">Stok (Satuan Kecil)</th>
      <th style="width: 10%">Harga Beli</th>
      <th style="width: 12%">Nilai Persediaan</th>
      <th style="width: 8%">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if($sql && mysqli_num_rows($sql) > 0){
      while($data = mysqli_fetch_array($sql)){
        $no++;
        // Pastikan nilai tidak null
        $hrg_beli_val = isset($data['hrg_beli']) && $data['hrg_beli'] !== null ? floatval($data['hrg_beli']) : 0;
        $stok_juals_val = isset($data['stok_juals']) && $data['stok_juals'] !== null ? floatval($data['stok_juals']) : 0;
        // Jika nilai_persediaan sudah dihitung di query (dari beli_brg), gunakan itu, jika tidak hitung manual
        if(isset($data['nilai_persediaan']) && $data['nilai_persediaan'] !== null && $data['nilai_persediaan'] != ''){
          $nilai_persediaan = floatval($data['nilai_persediaan']);
        } else {
          $nilai_persediaan = $hrg_beli_val * $stok_juals_val;
        }
        $total_nilai += $nilai_persediaan;
        
        // Konversi stok ke satuan kemasan (sama seperti f_cetak_pilih_stok.php)
        $stok1 = $stok2 = $stok3 = '';
        $brg_msk_hi = $stok_juals_val;
        
        $jum_kem1 = isset($data['jum_kem1']) ? floatval($data['jum_kem1']) : 0;
        $jum_kem2 = isset($data['jum_kem2']) ? floatval($data['jum_kem2']) : 0;
        $jum_kem3 = isset($data['jum_kem3']) ? floatval($data['jum_kem3']) : 0;
        $nm_kem1 = isset($data['nm_kem1']) ? $data['nm_kem1'] : '';
        $nm_kem2 = isset($data['nm_kem2']) ? $data['nm_kem2'] : '';
        $nm_kem3 = isset($data['nm_kem3']) ? $data['nm_kem3'] : '';
        
        if ($jum_kem1 > 0) {
          $stok1 = gantitides($brg_msk_hi / $jum_kem1) . ' ' . $nm_kem1;
        } else {
          $stok1 = '0,00 ' . $nm_kem1;
        }
        if ($jum_kem2 > 0) {
          $stok2 = gantitides($brg_msk_hi / $jum_kem2) . ' ' . $nm_kem2;
        } else {
          $stok2 = '0,00 ' . $nm_kem2;
        }
        if ($jum_kem3 > 0) {
          $stok3 = gantitides($brg_msk_hi / $jum_kem3) . ' ' . $nm_kem3;
        } else {
          $stok3 = '0,00 ' . $nm_kem3;
        }
        ?>
        <tr>
          <td align="center"><?=$no?></td>
          <td><?=htmlspecialchars($data['kd_brg'])?></td>
          <td><?=htmlspecialchars($data['nm_brg'] ? $data['nm_brg'] : '-')?></td>
          <td><?=htmlspecialchars($data['nm_sup'] ? $data['nm_sup'] : '-')?></td>
          <td><?=htmlspecialchars($data['nm_bag'] ? $data['nm_bag'] : '-')?></td>
          <td align="right">
            <small>
              <?=$stok1?><br>
              <?=$stok2?><br>
              <?=$stok3?>
            </small>
          </td>
          <td align="right"><?=gantitides($hrg_beli_val)?></td>
          <td align="right"><b><?=gantitides($nilai_persediaan)?></b></td>
          <td align="center">
            <button onclick="detailData('<?=$data['kd_brg']?>', '<?=$data['bulan']?>', '<?=$data['tahun']?>')" class="btn btn-sm btn-info" title="Detail"><i class="fa fa-eye"></i></button>
            <?php if(isset($use_persediaan_table) && $use_persediaan_table): ?>
              <button onclick="editData('<?=$data['kd_brg']?>', '<?=$data['bulan']?>', '<?=$data['tahun']?>')" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></button>
              <button onclick="hapusData('<?=$data['kd_brg']?>', '<?=$data['bulan']?>', '<?=$data['tahun']?>')" class="btn btn-sm btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
            <?php endif; ?>
          </td>
        </tr>
        <?php
      }
      ?>
      <tr style="background-color: #f0f0f0;font-weight: bold">
        <td colspan="7" align="right">TOTAL NILAI PERSEDIAAN:</td>
        <td align="right"><?=gantitides($total_nilai)?></td>
        <td></td>
      </tr>
      <?php
    } else {
      $nama_bulan = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
      ?>
      <tr>
        <td colspan="9" align="center" style="padding: 20px;">
          <div style="font-size: 11pt; color: #666;">
            <i class="fa fa-info-circle" style="font-size: 24pt; color: #4CAF50; margin-bottom: 10px;"></i><br>
            <?php if($filter_bulan_tahun == 1): ?>
              <strong>Tidak ada data untuk bulan <?=$nama_bulan[intval($bulan)]?> <?=$tahun?></strong><br><br>
              <small>Data ditampilkan berdasarkan transaksi pembelian di bulan <?=$nama_bulan[intval($bulan)]?> <?=$tahun?> dari tabel beli_brg</small>
            <?php else: ?>
              <strong>Tidak ada data stok barang</strong><br><br>
              <small>Pilih bulan dan tahun kemudian klik tombol "Cari Persediaan Barang" untuk memfilter data berdasarkan periode tertentu</small>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <?php
    }
    ?>
  </tbody>
</table>

<!-- Pagination -->
<div class="row">
  <div class="col-sm-6">
    <div id="ket_rec" style="margin-top: 10px;font-size: 10pt">
      Menampilkan <?=$limit_start+1?> sampai <?=min($limit_start+$limit, $jumlah)?> dari <?=$jumlah?> data
    </div>
  </div>
  <div class="col-sm-6">
    <ul class="pagination pagination-sm" style="float: right;margin-top: 5px">
      <?php
      // Tombol Previous
      if($page > 1){
        ?>
        <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="caripersediaan(<?=$page-1?>, true)">Previous</a></li>
        <?php
      } else {
        ?>
        <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Previous</a></li>
        <?php
      }
      
      
      // Tombol Next
      if($page < $jumlah_page){
        ?>
        <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="caripersediaan(<?=$page+1?>, true)">Next</a></li>
        <?php
      } else {
        ?>
        <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Next</a></li>
        <?php
      }
      ?>
    </ul>
  </div>
</div>

<script>
function detailData(kd_brg, bulan, tahun){
  // Tampilkan detail data barang dalam modal
  var nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  var bulan_nama = nama_bulan[parseInt(bulan)] || bulan;
  
  var detailHtml = '<div style="padding: 20px;">';
  detailHtml += '<h4><i class="fa fa-info-circle"></i> Detail Persediaan Barang</h4>';
  detailHtml += '<hr>';
  detailHtml += '<table class="table table-bordered" style="font-size: 10pt;">';
  detailHtml += '<tr><td width="30%"><strong>Kode Barang:</strong></td><td>' + kd_brg + '</td></tr>';
  detailHtml += '<tr><td><strong>Bulan:</strong></td><td>' + bulan_nama + '</td></tr>';
  detailHtml += '<tr><td><strong>Tahun:</strong></td><td>' + tahun + '</td></tr>';
  //detailHtml += '<tr><td><strong>Periode:</strong></td><td>' + bulan_nama + ' ' + tahun + '</td></tr>';
  //detailHtml += '<tr><td><strong>Catatan:</strong></td><td>Data persediaan barang berdasarkan transaksi pembelian di bulan ' + bulan_nama + ' tahun ' + tahun + '</td></tr>';
  detailHtml += '</table>';
  detailHtml += '<div style="text-align: right; margin-top: 15px;">';
  detailHtml += '<button onclick="this.closest(\'.w3-modal\').style.display=\'none\'; this.closest(\'.w3-modal\').remove();" class="btn btn-primary">Tutup</button>';
  detailHtml += '</div>';
  detailHtml += '</div>';
  
  // Buat modal untuk menampilkan detail
  var modal = document.createElement('div');
  modal.className = 'w3-modal';
  modal.style.display = 'block';
  modal.style.zIndex = '9999';
  modal.innerHTML = '<div class="w3-modal-content w3-card-4" style="max-width: 600px; margin: 50px auto;">' +
    '<header class="w3-container w3-blue">' +
    '<span onclick="this.closest(\'.w3-modal\').style.display=\'none\'; this.closest(\'.w3-modal\').remove();" class="w3-button w3-display-topright" style="cursor: pointer;">&times;</span>' +
    '<h3><i class="fa fa-info-circle"></i> Detail Persediaan</h3>' +
    '</header>' +
    '<div class="w3-container">' + detailHtml + '</div>' +
    '</div>';
  
  document.body.appendChild(modal);
  
  // Tutup modal saat klik di luar
  modal.onclick = function(e) {
    if (e.target === modal) {
      modal.style.display = 'none';
      document.body.removeChild(modal);
    }
  };
}

function editData(kd_brg, bulan, tahun){
  // Load data untuk edit
  $.ajax({
    url: 'm_persediaan_bulan_getdata.php',
    type: 'POST',
    data: {kd_brg: kd_brg, bulan: bulan, tahun: tahun},
    dataType: 'json',
    success: function(response){
      if(response.success){
        // Tampilkan form edit (bisa menggunakan modal atau form terpisah)
        var stok = prompt('Edit Stok:', response.data.stok_juals);
        if(stok !== null && stok !== ''){
          var hrg_beli = prompt('Edit Harga Beli:', response.data.hrg_beli);
          if(hrg_beli !== null && hrg_beli !== ''){
            $.ajax({
              url: 'm_persediaan_bulan_update.php',
              type: 'POST',
              data: {
                kd_brg: kd_brg,
                bulan: bulan,
                tahun: tahun,
                stok_juals: stok,
                hrg_beli: hrg_beli
              },
              dataType: 'json',
              success: function(res){
                if(res.success){
                  popnew_ok('Data berhasil diupdate');
                  caripersediaan(1, true);
                } else {
                  popnew_error('Gagal update data');
                }
              }
            });
          }
        }
      }
    }
  });
}

function hapusData(kd_brg, bulan, tahun){
  if(confirm('Yakin ingin menghapus data ini?')){
    $.ajax({
      url: 'm_persediaan_bulan_hapus_act.php',
      type: 'POST',
      data: {kd_brg: kd_brg, bulan: bulan, tahun: tahun},
      dataType: 'json',
      success: function(response){
        if(response.success){
          popnew_ok('Data berhasil dihapus');
          caripersediaan(1, true);
        } else {
          popnew_error('Gagal menghapus data');
        }
      }
    });
  }
}
</script>

<?php
mysqli_close($connect);
$html = ob_get_contents();
ob_end_clean();

// Pastikan tidak ada output sebelum JSON
if (!headers_sent()) {
  header('Content-Type: application/json; charset=UTF-8');
}

// Debug: Log jika ada masalah (opsional)
if($sql && mysqli_num_rows($sql) == 0){
  error_log("Debug: No records found for bulan: $bulan, tahun: $tahun, kd_toko: $kd_toko");
}

echo json_encode(array('hasil'=>$html));
exit;
?>

