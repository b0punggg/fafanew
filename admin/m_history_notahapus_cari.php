<?php
  include 'cekmasuk.php';
  if($bag!="Administrator"){
    echo json_encode(array('hasil' => '<div class="w3-panel w3-pale-red w3-border w3-round" style="margin:8px"><b>Akses ditolak.</b> Menu ini hanya untuk Administrator.</div>'));
    exit;
  }
  $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
  $status  = isset($_POST['status']) ? $_POST['status'] : 'ALL';
  ob_start();
?>
<style>
  .history-table-wrap {
    overflow-y: auto;
    overflow-x: auto;
    border: 1px solid #d9dee7;
    border-radius: 6px;
    background: #fff;
    height: 430px;
  }
  .history-table {
    width: 100%;
    font-size: 9pt;
    border-collapse: collapse;
  }
  .history-table th {
    position: sticky;
    top: 0;
    z-index: 2;
    padding: 7px 6px;
    border: 1px solid #d9dee7;
    background: linear-gradient(180deg, #e9f2ff 0%, #dbe9ff 100%);
    color: #1f2d3d;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.2px;
  }
  .history-table td {
    padding: 7px 6px;
    border: 1px solid #e3e7ee;
    vertical-align: middle;
  }
  .history-table tbody tr:nth-child(even) {
    background-color: #fafcff;
  }
  .history-table tbody tr:hover {
    background-color: #f2f7ff;
  }
  .history-status {
    display: inline-block;
    min-width: 75px;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 8.5pt;
    font-weight: 700;
    text-align: center;
  }
  .status-pending {
    color: #8a5a00;
    background: #fff4d6;
    border: 1px solid #ffd885;
  }
  .status-dihapus {
    color: #b71c1c;
    background: #ffe3e3;
    border: 1px solid #ffc7c7;
  }
  .status-diabaikan {
    color: #37474f;
    background: #eceff1;
    border: 1px solid #cfd8dc;
  }
  .history-empty {
    text-align: center;
    color: #777;
    padding: 20px !important;
    font-style: italic;
  }
</style>
<div class="history-table-wrap">
  <table class="history-table table-hover">
    <thead>
    <tr align="middle">
      <th width="6%">No.</th>
      <th width="18%">No. Nota</th>
      <th width="20%">Waktu Request</th>
      <th width="12%">User</th>
      <th width="17%">Toko</th>
      <th width="12%">Status</th>
      <th width="15%">Waktu Proses</th>
    </tr>
    </thead>
    <tbody>
    <?php
      include "config.php";
      if(!session_id()) session_start();
      $connect = opendtcek();
      $kd_toko = isset($_SESSION['id_toko']) ? mysqli_real_escape_string($connect, $_SESSION['id_toko']) : '';
      $page = (isset($_POST['page']))? $_POST['page'] : 1;
      $limit = 10;
      $limit_start = ($page - 1) * $limit;

      $params = mysqli_real_escape_string($connect, $keyword);
      $param = '%'.$params.'%';
      $status_esc = mysqli_real_escape_string($connect, $status);

      $where = "WHERE fl.ket='Hapus Nota Jual'";
      if ($kd_toko !== '') {
        $where .= " AND fl.kd_toko='$kd_toko'";
      }
      if ($status_esc !== 'ALL') {
        $where .= " AND fl.konfir='$status_esc'";
      } else {
        $where .= " AND fl.konfir IN ('T','A','D')";
      }
      if ($params !== '') {
        $where .= " AND (fl.no_fak LIKE '$param' OR fl.nm_user LIKE '$param')";
      }

      $sql = mysqli_query($connect, "SELECT fl.*, tk.nm_toko
        FROM file_log fl
        LEFT JOIN toko tk ON fl.kd_toko=tk.kd_toko
        $where
        ORDER BY fl.jam DESC
        LIMIT $limit_start, $limit");

      $sql2 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah
        FROM file_log fl
        $where");
      $get_jumlah = mysqli_fetch_array($sql2);
      $no = $limit_start;

      while($data = mysqli_fetch_array($sql)){
        $no++;
        $status_text = 'Pending';
        $status_class = 'status-pending';
        if ($data['konfir'] == 'D'){
          $status_text = 'Dihapus';
          $status_class = 'status-dihapus';
        } else if ($data['konfir'] == 'A'){
          $status_text = 'Diabaikan';
          $status_class = 'status-diabaikan';
        }
    ?>
      <tr>
        <td align="right"><?php echo $no.'.'; ?></td>
        <td><?php echo htmlspecialchars($data['no_fak']); ?></td>
        <td><?php
          $jam_request = isset($data['jam']) ? $data['jam'] : '';
          if ($jam_request != '' && $jam_request != '0000-00-00 00:00:00'){
            $pecah_req = explode(' ', $jam_request);
            echo htmlspecialchars(gantitgl($pecah_req[0]).' '.(isset($pecah_req[1]) ? $pecah_req[1] : ''));
          } else {
            echo '-';
          }
        ?></td>
        <td><?php echo htmlspecialchars($data['nm_user']); ?></td>
        <td><?php echo htmlspecialchars(isset($data['nm_toko']) ? $data['nm_toko'] : $data['kd_toko']); ?></td>
        <td align="center"><span class="history-status <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
        <td><?php
          if ($data['w_konfir'] != '' && $data['w_konfir'] != null && $data['w_konfir'] != '0000-00-00 00:00:00'){
            $pecah_konf = explode(' ', $data['w_konfir']);
            echo htmlspecialchars(gantitgl($pecah_konf[0]).' '.(isset($pecah_konf[1]) ? $pecah_konf[1] : ''));
          } else {
            echo '-';
          }
        ?></td>
      </tr>
    <?php } ?>
    <?php if ($no == $limit_start) { ?>
      <tr>
        <td colspan="7" class="history-empty">Tidak ada data history sesuai filter.</td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>

<div class="w3-border">
  <nav aria-label="Page navigation example" style="margin-top:5px;font-size: 9pt">
    <ul class="pagination justify-content-start">
      <?php
      if($page == 1){
      ?>
      <li class="page-item disabled"><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">First</a></li>
      <li class="page-item disabled"><a class="page-link yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&laquo;</a></li>
      <?php
      }else{
        $link_prev = ($page > 1)? $page - 1 : 1;
      ?>
      <li><a class="page-link yz-theme-d1" style="cursor: pointer" href="javascript:void(0);" onclick="carihistory(1, false)">First</a></li>
      <li><a class="page-link yz-theme-l1" style="cursor: pointer" href="javascript:void(0);" onclick="carihistory(<?php echo $link_prev; ?>, false)">&laquo;</a></li>
      <?php } ?>

      <?php
      $jumlah_page = ceil($get_jumlah['jumlah'] / $limit);
      $jumlah_number = 1;
      $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1;
      $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page;
      for($i = $start_number; $i <= $end_number; $i++){
        $link_active = ($page == $i)? ' class="active"' : '';
      ?>
      <li class="page-item" <?php echo $link_active; ?>><a class="page-link yz-theme-l3" href="javascript:void(0);" style="cursor: pointer" onclick="carihistory(<?php echo $i; ?>, false)"><?php echo $i; ?></a></li>
      <?php } ?>

      <?php
      if($page == $jumlah_page || $get_jumlah['jumlah']==0){
      ?>
      <li class="page-item disabled"><a class="page-link yz-theme-l1" href="javascript:void(0)" style="cursor: no-drop">&raquo;</a></li>
      <li class="page-item disabled"><a class="page-link yz-theme-d1" href="javascript:void(0)" style="cursor: no-drop">Last</a></li>
      <?php
      }else{
        $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
      ?>
      <li class="page-item"><a class="page-link yz-theme-l1" href="javascript:void(0)" onclick="carihistory(<?php echo $link_next; ?>, false)" style="cursor: pointer">&raquo;</a></li>
      <li class="page-item"><a class="page-link yz-theme-d1" href="javascript:void(0)" onclick="carihistory(<?php echo $jumlah_page; ?>, false)" style="cursor: pointer">Last</a></li>
      <?php } ?>
    </ul>
  </nav>
</div>

<?php
  mysqli_close($connect);
  $html = ob_get_contents();
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>
