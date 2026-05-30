<?php
  // File untuk menampilkan list discount promo yang sudah disimpan
  // File ini dapat diakses oleh semua user termasuk operator (otoritas 1) dan administrator (otoritas 2)
  // Data promo ditampilkan berdasarkan kd_toko, tidak dibatasi berdasarkan otoritas
  ob_start();
  include 'config.php';
  // Session sudah dimulai di config.php atau starting.php, tidak perlu session_start() lagi
  if (!isset($_SESSION)) {
    session_start();
  }
  $connect = opendtcek();
  $kd_toko = isset($_SESSION['id_toko']) ? $_SESSION['id_toko'] : '';
  
  // Pastikan kd_toko ada - untuk semua user termasuk operator (otoritas 1) dan administrator (otoritas 2)
  if (empty($kd_toko)) {
    // Debug: Log untuk membantu troubleshooting
    $debug_info = 'Session id_toko kosong. ';
    if (isset($_SESSION['kodepemakai'])) {
      $debug_info .= 'Otoritas: ' . $_SESSION['kodepemakai'];
    }
    echo json_encode(array('hasil' => '<div class="empty-state"><i class="fa fa-exclamation-triangle"></i><div class="empty-state-text">Session tidak valid. Silakan login kembali.</div><div style="font-size: 11px; color: #999; margin-top: 10px;">' . htmlspecialchars($debug_info) . '</div></div>'));
    exit;
  }
  
  // Hapus promo yang periode sudah berakhir secara otomatis sebelum menampilkan list
  hapusPromoBerakhir($connect, $kd_toko);
  
  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $limit = 20;
  $limit_start = ($page - 1) * $limit;
  
  // Query untuk mengambil data discount promo - Tampilkan semua promo berdasarkan no_promo
  // Data ditampilkan untuk semua user (operator dan administrator) berdasarkan kd_toko
  // Pastikan tabel ada sebelum query
  $table_check = mysqli_query($connect, "SHOW TABLES LIKE 'disc_promo'");
  if (mysqli_num_rows($table_check) == 0) {
    // Tabel belum ada, tampilkan pesan
    echo json_encode(array('hasil' => '<div class="empty-state"><i class="fa fa-inbox"></i><div class="empty-state-text">Belum ada data discount promo</div></div>'));
    exit;
  }
  
  // Query untuk mengambil semua promo secara individual (berdasarkan no_promo)
  // Setiap promo ditampilkan terpisah, tidak dikelompokkan
  // ORDER BY created_at DESC untuk menampilkan promo terbaru di atas
  // Query ini tidak membatasi berdasarkan otoritas, sehingga operator dan administrator dapat melihat semua data promo
  $query_promo = "SELECT 
                    dp.no_promo,
                    dp.nama_promo,
                    dp.tgl_awal,
                    dp.tgl_akhir,
                    dp.disc_rupiah,
                    dp.disc_persen,
                    dp.by_nama,
                    dp.created_at
                  FROM disc_promo dp
                  WHERE dp.kd_toko = '$kd_toko'
                  ORDER BY dp.created_at DESC, dp.no_urut DESC, dp.no_promo DESC
                  LIMIT $limit_start, $limit";
  
  $result_promo = mysqli_query($connect, $query_promo);
  
  // Error handling untuk query
  if (!$result_promo) {
    $error_msg = mysqli_error($connect);
    echo json_encode(array('hasil' => '<div class="empty-state"><i class="fa fa-exclamation-triangle"></i><div class="empty-state-text">Error: ' . htmlspecialchars($error_msg) . '</div></div>'));
    exit;
  }
  
  // Query untuk count total promo (untuk pagination) - berdasarkan no_promo
  $count_query = "SELECT COUNT(*) AS total FROM disc_promo dp WHERE dp.kd_toko = '$kd_toko'";
  $count_result = mysqli_query($connect, $count_query);
  $count_data = mysqli_fetch_assoc($count_result);
  $total_promo = $count_data['total'];
  $total_pages = ceil($total_promo / $limit);
  
  // Debug: Log jumlah promo yang ditemukan
  // error_log("Total promo found: " . $total_promo);
?>

<style>
  .history-container {
    padding: 10px;
  }
  .history-item {
    position: relative;
    padding: 15px 15px 15px 50px;
    margin-bottom: 12px;
    background: white;
    border-left: 4px solid #667eea;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .history-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    transform: translateX(5px);
    border-left-color: #764ba2;
  }
  .history-item.expanded {
    border-left-color: #4CAF50;
    background: #f8f9fa;
  }
  .history-item::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 20px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #667eea;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #667eea;
  }
  .history-item.expanded::before {
    background: #4CAF50;
    box-shadow: 0 0 0 2px #4CAF50;
  }
  .history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
  }
  .history-title {
    font-size: 15px;
    font-weight: bold;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .history-title i {
    color: #667eea;
    font-size: 18px;
  }
  .history-item.expanded .history-title i {
    color: #4CAF50;
  }
  .history-meta {
    display: flex;
    gap: 15px;
    font-size: 12px;
    color: #666;
    margin-top: 5px;
    flex-wrap: wrap;
  }
  .history-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .history-meta-item i {
    color: #999;
    font-size: 11px;
  }
  .badge-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    margin-left: 10px;
  }
  .history-item.expanded .badge-count {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
  }
  .history-content {
    display: none;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
  }
  .history-content.show {
    display: block;
  }
  .promo-detail-card {
    background: white;
    padding: 15px;
    margin-bottom: 12px;
    border-radius: 5px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  }
  .promo-detail-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
  }
  .promo-detail-title {
    font-size: 13px;
    font-weight: bold;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .promo-detail-title i {
    color: #28a745;
  }
  .promo-info {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    font-size: 12px;
    margin-bottom: 12px;
  }
  .promo-info-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #666;
  }
  .promo-info-item i {
    color: #ffc107;
    font-size: 13px;
  }
  .promo-info-item strong {
    color: #333;
    margin-right: 5px;
  }
  .detail-table {
    width: 100%;
    font-size: 11px;
    margin-top: 10px;
    border-collapse: collapse;
  }
  .detail-table thead {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
  }
  .detail-table th {
    padding: 8px 10px;
    text-align: left;
    font-weight: bold;
    font-size: 11px;
  }
  .detail-table td {
    padding: 8px 10px;
    border-bottom: 1px solid #f0f0f0;
  }
  .detail-table tbody tr:hover {
    background-color: #f8f9fa;
  }
  .btn-delete {
    padding: 5px 12px;
    font-size: 11px;
    border-radius: 3px;
  }
  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
  }
  .empty-state i {
    font-size: 64px;
    margin-bottom: 15px;
    opacity: 0.3;
  }
  .empty-state-text {
    font-size: 16px;
  }
</style>

<div class="history-container">
  <?php
  if ($result_promo && mysqli_num_rows($result_promo) > 0) {
    $no = $limit_start + 1;
    while ($row_promo = mysqli_fetch_array($result_promo)) {
      $no_promo_current = $row_promo['no_promo'];
      $nama_promo = $row_promo['nama_promo'];
      $by_nama = $row_promo['by_nama'];
      $promo_id = 'promo_' . md5($no_promo_current); // Gunakan no_promo untuk ID unik
      
      // Format tanggal
      $tgl_awal_formatted = date('d/m/Y', strtotime($row_promo['tgl_awal']));
      $tgl_akhir_formatted = date('d/m/Y', strtotime($row_promo['tgl_akhir']));
      $created_formatted = date('d/m/Y H:i', strtotime($row_promo['created_at']));
      
      // Ambil detail barang untuk promo ini saja (tidak digabung dengan promo lain)
      // PENTING: Query ini hanya mengambil barang yang disimpan dengan no_promo ini
      // Setiap promo memiliki detail barang yang terpisah, tidak tercampur dengan promo lain
      // Detail barang hanya sesuai dengan no_promo ini, sesuai dengan barang yang di-load saat membuat promo ini
      $no_promo_escaped = mysqli_real_escape_string($connect, $no_promo_current);
      $query_detail = "SELECT 
                        dpd.kd_brg,
                        dpd.disc_rupiah AS disc_rupiah_item,
                        dpd.disc_persen AS disc_persen_item,
                        mas_brg.nm_brg,
                        mas_brg.kd_bar
                      FROM disc_promo_detail dpd
                      LEFT JOIN mas_brg ON dpd.kd_brg = mas_brg.kd_brg AND dpd.kd_toko = mas_brg.kd_toko
                      WHERE dpd.no_promo = '$no_promo_escaped' AND dpd.kd_toko = '$kd_toko'
                      ORDER BY dpd.no_urut ASC";
      
      $result_detail = mysqli_query($connect, $query_detail);
      
      // Error handling untuk query detail barang
      if (!$result_detail) {
        $error_msg = mysqli_error($connect);
        echo '<div style="padding: 10px; color: red;">Error query detail barang: ' . htmlspecialchars($error_msg) . '</div>';
        continue;
      }
      
      $jml_barang = mysqli_num_rows($result_detail);
      ?>
      
      <div class="history-item" id="item_<?php echo $promo_id; ?>" onclick="togglePromo('<?php echo $promo_id; ?>')">
        <div class="history-header">
          <div class="history-title">
            <i class="fa fa-tag"></i>
            <span><?php echo htmlspecialchars($nama_promo); ?></span>
            <span class="badge-count" style="margin-left: 10px; background: #2196F3; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;"><?php echo $no_promo_current; ?></span>
          </div>
          <div style="color: #999; font-size: 12px;">
            <i class="fa fa-chevron-right" id="chevron_<?php echo $promo_id; ?>" style="transition: transform 0.3s ease;"></i>
          </div>
        </div>
        <div class="history-meta">
          <div class="history-meta-item">
            <i class="fa fa-calendar"></i>
            <span><?php echo $tgl_awal_formatted; ?> - <?php echo $tgl_akhir_formatted; ?></span>
          </div>
          <div class="history-meta-item">
            <i class="fa fa-clock-o"></i>
            <span>Dibuat: <?php echo $created_formatted; ?></span>
          </div>
          <div class="history-meta-item">
            <i class="fa fa-cube"></i>
            <span><?php echo $jml_barang; ?> Barang</span>
          </div>
        </div>
        
        <div class="history-content" id="<?php echo $promo_id; ?>">
          <div class="promo-detail-card">
            <div class="promo-detail-header">
              <div class="promo-detail-title">
                <i class="fa fa-barcode"></i>
                <span><?php echo $no_promo_current; ?></span>
                <span style="font-size: 11px; color: #666; margin-left: 10px; font-weight: normal;">
                  (Barang yang di-load untuk promo ini)
                </span>
              </div>
              <div>
                <button type="button" class="btn btn-sm btn-danger btn-delete" onclick="event.stopPropagation(); hapusPromo('<?php echo $no_promo_current; ?>')" title="Hapus Promo">
                  <i class="fa fa-trash"></i> Hapus
                </button>
              </div>
            </div>
            
            <div class="promo-info">
              <div class="promo-info-item">
                <i class="fa fa-calendar"></i>
                <strong>Periode:</strong> <?php echo $tgl_awal_formatted; ?> - <?php echo $tgl_akhir_formatted; ?>
              </div>
              <div class="promo-info-item">
                <i class="fa fa-money"></i>
                <strong>Disc Rupiah:</strong> <?php echo gantitides($row_promo['disc_rupiah']); ?>
              </div>
              <div class="promo-info-item">
                <i class="fa fa-percent"></i>
                <strong>Disc %:</strong> <?php echo $row_promo['disc_persen'] > 0 ? gantitides($row_promo['disc_persen']) . '%' : '-'; ?>
              </div>
              <?php if ($by_nama) { ?>
              <div class="promo-info-item">
                <i class="fa fa-tags"></i>
                <strong>By Nama:</strong> <?php echo htmlspecialchars($by_nama); ?>
              </div>
              <?php } ?>
              <div class="promo-info-item">
                <i class="fa fa-cube"></i>
                <strong>Jumlah Barang:</strong> <?php echo $jml_barang; ?> item
                <span style="font-size: 10px; color: #999; margin-left: 5px;">(hanya untuk promo ini)</span>
              </div>
              <div class="promo-info-item">
                <i class="fa fa-clock-o"></i>
                <strong>Dibuat:</strong> <?php echo $created_formatted; ?>
              </div>
            </div>
            
            <?php if ($jml_barang > 0) { ?>
            <div style="margin-top: 15px; margin-bottom: 10px; padding: 10px; background: #f0f7ff; border-left: 3px solid #2196F3; border-radius: 3px;">
              <i class="fa fa-info-circle" style="color: #2196F3;"></i>
              <span style="font-size: 12px; color: #333;">
                <strong>Daftar Barang untuk Promo "<?php echo htmlspecialchars($nama_promo); ?>"</strong> - Barang yang di-load saat membuat promo ini
              </span>
            </div>
            <table class="detail-table">
              <thead>
                <tr>
                  <th style="width: 5%; text-align: center;">No</th>
                  <th style="width: 12%;">SKU</th>
                  <th style="width: 40%;">Nama Barang</th>
                  <th style="width: 15%; text-align: right;">Disc Item Rp</th>
                  <th style="width: 10%; text-align: center;">Disc Item %</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no_item = 1;
                while ($row_detail = mysqli_fetch_array($result_detail)) {
                  ?>
                  <tr>
                    <td style="text-align: center;"><?php echo $no_item++; ?></td>
                    <td><?php echo isset($row_detail['kd_bar']) && !empty($row_detail['kd_bar']) ? htmlspecialchars($row_detail['kd_bar']) : htmlspecialchars($row_detail['kd_brg']); ?></td>
                    <td><?php echo $row_detail['nm_brg'] ? htmlspecialchars($row_detail['nm_brg']) : '-'; ?></td>
                    <td style="text-align: right;"><?php echo gantitides($row_detail['disc_rupiah_item']); ?></td>
                    <td style="text-align: center;"><?php echo $row_detail['disc_persen_item'] > 0 ? gantitides($row_detail['disc_persen_item']) . '%' : '-'; ?></td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
            <?php } else { ?>
            <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">
              <i class="fa fa-info-circle"></i> Tidak ada barang pada promo ini
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php
      $no++;
    }
  } else {
    ?>
    <div class="empty-state">
      <i class="fa fa-inbox"></i>
      <div class="empty-state-text">Belum ada data discount promo</div>
    </div>
    <?php
  }
  ?>
</div>

<?php if ($total_promo > 0) { ?>
<div class="w3-border yz-theme-l5" style="margin-top: 20px;">
  <nav aria-label="Page navigation example" style="margin-top:15px;font-size: 9pt">
    <ul class="pagination justify-content-center">
      <!-- LINK FIRST AND PREV -->
      <?php
      if($page == 1){
      ?>
        <li class="page-item disabled"><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
        <li class="page-item disabled"><a class="page-link yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:15px;padding-right:15px"><i class="fa fa-chevron-left"></i></a></li>
      <?php
      }else{
        $link_prev = ($page > 1) ? $page - 1 : 1;
      ?>
        <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="loadListPromo(1)">First</a></li>
        <li><a class="page-link yz-theme-l1" style="cursor: pointer;padding-left:15px;padding-right:15px" href="javascript:void(0);" onclick="loadListPromo(<?php echo $link_prev; ?>)"><i class="fa fa-chevron-left"></i></a></li>
      <?php
      }
      ?>
      
      <!-- LINK NUMBER -->
      <?php
      $jumlah_number = 2;
      $start_number = ($page > $jumlah_number) ? $page - $jumlah_number : 1;
      $end_number = ($page < ($total_pages - $jumlah_number)) ? $page + $jumlah_number : $total_pages;
      
      for($i = $start_number; $i <= $end_number; $i++){
        $link_active = ($page == $i) ? ' class="active"' : '';
      ?>
        <li class="page-item" <?php echo $link_active; ?>><a class="page-link yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="loadListPromo(<?php echo $i; ?>)"><?php echo $i; ?></a></li>
      <?php
      }
      ?>
      
      <!-- LINK NEXT AND LAST -->
      <?php
      if($page == $total_pages || $total_promo == 0){
      ?>
        <li class="page-item disabled"><a class="page-link yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop;padding-left:15px;padding-right:15px"><i class="fa fa-chevron-right"></i></a></li>
        <li class="page-item disabled"><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
      <?php
      }else{
        $link_next = ($page < $total_pages) ? $page + 1 : $total_pages;
      ?>
        <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="loadListPromo(<?php echo $link_next; ?>)" style="cursor: pointer;padding-left:15px;padding-right:15px"><i class="fa fa-chevron-right"></i></a></li>
        <li class="page-item"><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="loadListPromo(<?php echo $total_pages; ?>)" style="cursor: pointer">Last</a></li>
      <?php
      }
      ?>
    </ul>
  </nav>
</div>
<?php } ?>

<script>
  function togglePromo(promoId) {
    var content = document.getElementById(promoId);
    if (!content) return;
    
    var item = document.getElementById('item_' + promoId);
    var chevron = document.getElementById('chevron_' + promoId);
    
    if (content.classList.contains('show')) {
      content.classList.remove('show');
      if (item) item.classList.remove('expanded');
      if (chevron) chevron.style.transform = 'rotate(0deg)';
    } else {
      content.classList.add('show');
      if (item) item.classList.add('expanded');
      if (chevron) chevron.style.transform = 'rotate(90deg)';
    }
  }
</script>

<?php
  mysqli_close($connect);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil' => $html));
?>
