<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/keranjang.png">
    <title>Penjualan Barang</title>
    <link rel="stylesheet" href="../assets/css/paper.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/w3.css">
    <link rel="stylesheet" href="../assets/css/blue-themes.css">
  </head>
</head>
<style>
  table {
  border-spacing: 0px;
}
th, td {
  padding: 0px;
}
</style>

<?php
  include_once 'config.php';
  session_start();
  $zx=explode(';',$_GET['nof']);
  $no_fakjual = $zx[0];
  $tgl_jual   = $zx[1];
  $kd_toko    = $zx[2];
  $kd_pel     = $zx[3];
  $nm_toko    = $zx[4];
  $al_toko    = $zx[5];
  $nm_pel     = $zx[6];
  $al_pel     = $zx[7];
  $kd_bayar   = $zx[8];
  $bayar      = $zx[9];
  $susuk      = $zx[10];
  $disctot    = $zx[11];
  $ongkir     = $zx[12];
  $saldohut   = $zx[13];
  $tgl_jt     = $zx[14];
  $jam        = $zx[15];
  $user       = $zx[16];
  $voucer     = $zx[17];
  // Data member (jika ada) - ambil dari URL atau database
  $kd_member  = isset($zx[18]) ? $zx[18] : '';
  $nm_member  = isset($zx[19]) ? $zx[19] : '';
  $poin_earned = isset($zx[20]) ? floatval($zx[20]) : 0;
  $poin_saldo = isset($zx[21]) ? floatval($zx[21]) : 0;
  
  $con_sm=opendtcek();
  
  // Selalu ambil data member dari database untuk memastikan data terbaru
  $cek_member = mysqli_query($con_sm, "SELECT kd_member, poin_earned FROM mas_jual WHERE no_fakjual='$no_fakjual' AND tgl_jual='$tgl_jual' AND kd_toko='$kd_toko' LIMIT 1");
  if ($cek_member && mysqli_num_rows($cek_member) > 0) {
    $dt_member = mysqli_fetch_assoc($cek_member);
    if (!empty($dt_member['kd_member'])) {
      $kd_member = $dt_member['kd_member'];
      $poin_earned = isset($dt_member['poin_earned']) ? floatval($dt_member['poin_earned']) : 0;
      
      // Ambil nama member dan poin saldo jika ada kd_member
      $sqlmember=mysqli_query($con_sm,"SELECT nm_member, poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko' LIMIT 1");
      if ($sqlmember && mysqli_num_rows($sqlmember) > 0) {
        $datamember=mysqli_fetch_assoc($sqlmember);
        $nm_member = isset($datamember['nm_member']) ? $datamember['nm_member'] : '';
        $poin_saldo = isset($datamember['poin']) ? floatval($datamember['poin']) : 0;
      }
      if ($sqlmember) {
        mysqli_free_result($sqlmember);
      }
      unset($sqlmember,$datamember);
    }
  }
  if ($cek_member) {
    mysqli_free_result($cek_member);
  }
  unset($cek_member,$dt_member);
  $sql=mysqli_query($con_sm,"SELECT *,sum(dum_jual.qty_brg) AS qty_brg FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_jual' AND dum_jual.kd_toko='$kd_toko' GROUP BY dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.discrp,dum_jual.hrg_jual ORDER BY dum_jual.no_urut ASC");
  $no=$total=0; ?>
  <body >      
  <!-- <section class="sheet padding-10mm">   -->
      <table>
        <tr>
          <td style="font-size:7px" align="center"><div style="width:60px">F A F A</div></td>
        </tr>
        <tr>  
          <td style="font-size:6px;" align="center">
            <div style="margin-top:-4px;width:60px">Fashion & Galery</div>
          </td>
        </tr>
        <tr>
          <td style="font-size:4px" align="center"><div style="margin-top:-3px;width:60px"><?=ucwords(strtolower($al_toko))?></div></td>
        </tr>
      </table>
      <table style="margin-top:4px;">
        <tr> 
          <td style="font-size:3px">Struk</td>
          <td style="font-size:3px">&nbsp;:&nbsp;</td>
          <td style="font-size:3px"><?=trim($no_fakjual)?></td>
        <tr>
        <tr> 
          <td style="font-size:3px">Tanggal</td>
          <td style="font-size:3px">&nbsp;:&nbsp;</td>
          <td style="font-size:3px"><?=$jam?></td>
        </tr>
        <tr> 
          <td style="font-size:3px">Pembeli</td>
          <td style="font-size:3px">&nbsp;:&nbsp;</td>
          <td style="font-size:3px"><?=$nm_pel?></td>
        </tr>
        <?php if($kd_bayar=='TEMPO'){ ?>
          <tr> 
            <td style="font-size:3px">Alamat</td>
            <td style="font-size:3px">&nbsp;:&nbsp;</td>
            <td style="font-size:3px"><?=strtolower(ucwords($al_pel))?></td>
          </tr>  <?php
        } ?>
        <?php 
        // Debug: tampilkan nilai variabel (akan muncul di HTML source)
        // echo "<!-- Debug: kd_member='$kd_member', nm_member='$nm_member', poin_earned=$poin_earned, poin_saldo=$poin_saldo -->";
        
        // Tampilkan data member jika ada kd_member
        if(!empty($kd_member)){ 
          // Jika nm_member masih kosong, ambil lagi dari database
          if (empty($nm_member)) {
            $sqlmember2=mysqli_query($con_sm,"SELECT nm_member, poin FROM member WHERE kd_member='$kd_member' AND kd_toko='$kd_toko' LIMIT 1");
            if ($sqlmember2 && mysqli_num_rows($sqlmember2) > 0) {
              $datamember2=mysqli_fetch_assoc($sqlmember2);
              $nm_member = isset($datamember2['nm_member']) ? $datamember2['nm_member'] : '';
              $poin_saldo = isset($datamember2['poin']) ? floatval($datamember2['poin']) : 0;
            }
            if ($sqlmember2) {
              mysqli_free_result($sqlmember2);
            }
            unset($sqlmember2,$datamember2);
          }
          // Tampilkan nama member atau kd_member jika nm_member kosong
          $display_member = !empty($nm_member) ? ucwords(strtolower($nm_member)) : $kd_member;
        ?>
          <tr> 
            <td style="font-size:3px">Member</td>
            <td style="font-size:3px">&nbsp;:&nbsp;</td>
            <td style="font-size:3px"><?=$display_member?></td>
          </tr>
          <?php if($poin_earned > 0){ ?>
          <tr> 
            <td style="font-size:3px">Poin Dapat</td>
            <td style="font-size:3px">&nbsp;:&nbsp;</td>
            <td style="font-size:3px"><?=number_format($poin_earned, 0, ',', '.')?> Poin</td>
          </tr>
          <?php } ?>
          <tr> 
            <td style="font-size:3px">Poin Saldo</td>
            <td style="font-size:3px">&nbsp;:&nbsp;</td>
            <td style="font-size:3px"><?=number_format($poin_saldo, 0, ',', '.')?> Poin</td>
          </tr>
        <?php } ?>
      </table>
      <div style="font-size: 3px;">-------------------------------------------------------------------------</div>
      <div style="font-size:3px">NO. &nbsp;&nbsp;&nbsp;&nbsp;NAMA BARANG</div>
      <div style="font-size:3px">&nbsp;&nbsp;&nbsp;QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HARGA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DISC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUBTOTAL</div>
      <div style="font-size: 3px;">-------------------------------------------------------------------------</div>
      <?php
      $sql=mysqli_query($con_sm,"SELECT *,sum(dum_jual.qty_brg) AS qty_brg FROM dum_jual LEFT JOIN kemas ON dum_jual.kd_sat=kemas.no_urut WHERE dum_jual.no_fakjual='$no_fakjual' AND dum_jual.tgl_jual='$tgl_jual' AND dum_jual.kd_toko='$kd_toko' GROUP BY dum_jual.kd_brg,dum_jual.kd_sat,dum_jual.discrp,dum_jual.hrg_jual ORDER BY dum_jual.no_urut ASC");
      $no=$total=0;
      while($data=mysqli_fetch_assoc($sql)){
        $no++;
        $nm_sat=ucwords(strtolower($data['nm_sat1']));
        $hrg_jual=round($data['hrg_jual'],0);
        
        if ($data['discrp']>0){
          $subtot=($data['hrg_jual']-$data['discrp'])*$data['qty_brg'];
        }else{
          $subtot=$data['hrg_jual']*$data['qty_brg'];
        }
    
        $qty_brg = round($data['qty_brg'],0);   
        $discrp  = gantiti(round($data['discrp'],0));
        $hrg_jual= gantiti(round($data['hrg_jual'],0));
        $total   = $total+$subtot; 
        $subtot  = gantiti(round($subtot,0));
        $tgl_jual= gantitgl($data['tgl_jual']);
        $brs2    = $qty_brg.' '.$nm_sat;
        if(strlen($data['nm_brg'])>30){
          $nm_brg = substr($data['nm_brg'],0,31).'..';
        }else{$nm_brg=$data['nm_brg'];}
        ?>
        <table>
          <tr style="font-size:3px">
            <td align="right"><div style="width:5px;margin-top:1px"><?=$no?>.</div></td>
            <td><div style="margin-top:1px"><?=spasi(2).$nm_brg?></div></td>
          </tr>
        </table>
        <table style="font-size:3px">
          <tr style="font-size:3px">
            <td align="right"><div style="width:12px;margin-top:-1px"><?=$brs2?></div></td>
            <td align="right"><div style="width:17px;margin-top:-1px"><?=$hrg_jual?></div></td>
            <td align="right"><div style="width:14px;margin-top:-1px"><?=$discrp?></div></td>
            <td align="right"><div style="width:17px;margin-top:-1px"><?=$subtot?></div></td>
          </tr>
        </table> <?php  
      } ?>      

      <div style="font-size: 3px;">-------------------------------------------------------------------------</div>
      <table style="font-size:3px">
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">TOTAL</div></td>
          <td><div style="width:5px">Rp.</div></td>
          <td><div align="right" style="width:17px"><?=gantiti($total)?></div></td>
        </tr>    

        <?php if ($disctot>0){ ?>
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">DISC NOTA</div></td>
          <td><div style="width:5px">Rp.</div></td>
          <td><div align="right" style="width:17px"><?=gantiti(round($disctot,0))?></div></td>
        </tr>
        <?php } ?> 

        <?php if ($voucer>0){ ?>
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">VOUCHER</div></td>
          <td><div style="width:5px">Rp.</div></td>
          <td><div align="right" style="width:17px"><?=gantiti(round($voucer,0))?></div></td>
        </tr>
        <?php } ?> 

        <?php if ($ongkir>0){ ?>
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">ONGKIR</div></td>
          <td><div style="width:5px">Rp.</div></td>
          <td><div align="right" style="width:17px"><?=gantiti(round($ongkir,0))?></div></td>
        </tr>
        <?php } ?>  

        <?php if($kd_bayar=="TUNAI"){ ?>
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">BAYAR</div></td>
          <td><div style="width:5px">Rp.</div></td>
          <td><div align="right" style="width:17px"><?=gantiti(round($bayar,0))?></div></td>
        </tr>
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">KEMBALIAN</div></td>
          <td><div style="width:5px">Rp.</div></td>
          <td><div align="right" style="width:17px"><?=gantiti(round($susuk,0))?></div></td>
        </tr>  
        <?php } else { ?>    
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">BAYAR</div></td>
          <td><div style="width:5px">Rp.</div></td>
          <td><div align="right" style="width:17px"><?=gantiti(round($bayar,0))?></div></td>
        </tr>
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">KEKURANGAN</div></td>
          <td><div style="width:5px">Rp.</div></td>
          <td><div align="right" style="width:17px"><?=gantiti(round($saldohut,0))?></div></td>
        </tr> 
        <tr>
          <td><div style="width:17px">&nbsp;</div></td>
          <td><div style="width:21px">JTH.TEMPO</div></td>
          <td><div style="width:5px">:</div></td>
          <td><div align="right" style="width:17px"><?=gantitgl($tgl_jt)?></div></td>
        </tr>  
        <?php } ?>      
      </table>

      <table style="font-size:3px">
        <tr>
          <td align="center"><div style="margin-top:2px;width:60px">*TERIMA KASIH ATAS KUNJUNGANNYA*</div></td>
        </tr>
      </table>

  <!-- </section> -->
  </body>
  <script>
    // Fungsi untuk print langsung ke printer thermal tanpa dialog
    // Metode ini mengirim HTML content langsung ke print server tanpa membuka window
    (function() {
      var printed = false;
      
      function attemptPrint() {
        if (printed) return;
        
        // Ambil HTML content dari halaman ini
        var htmlContent = document.documentElement.outerHTML;
        
        // Metode 1: Kirim HTML content langsung ke print server (port 3000)
        fetch("http://localhost:3000/print/html", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            html: htmlContent,
            printerName: "POS58 Printer"
          }),
          signal: AbortSignal.timeout(2000)
        })
        .then(function(response) {
          if (response.ok) {
            return response.json();
          }
          throw new Error("Print server tidak tersedia");
        })
        .then(function(data) {
          if (data.success) {
            console.log("✅ Nota dikirim langsung ke printer thermal tanpa dialog");
            printed = true;
            setTimeout(function() {
              if (window.opener) {
                window.close();
              }
            }, 500);
            return;
          }
          throw new Error("Print gagal");
        })
        .catch(function(error) {
          // Metode 2: Coba print server alternatif dengan HTML content
          return fetch("http://localhost:8080/print/html", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              html: htmlContent,
              printer: "POS58 Printer"
            }),
            signal: AbortSignal.timeout(2000)
          })
          .then(function(response) {
            if (response.ok) {
              console.log("✅ Nota dikirim ke print server alternatif");
              printed = true;
              setTimeout(function() {
                if (window.opener) {
                  window.close();
                }
              }, 500);
              return;
            }
            throw new Error("Print server alternatif tidak tersedia");
          });
        })
        .catch(function(error) {
          // Metode 3: Gunakan WebUSB/Web Serial API jika printer mendukung
          if (navigator.serial || navigator.usb) {
            console.log("🖨️ Mencoba print langsung via WebUSB/Web Serial");
            // Implementasi WebUSB/Web Serial akan ditambahkan jika printer mendukung
          }
          
          // Metode 4: Fallback - gunakan window.print() (akan muncul dialog di browser modern)
          if (!printed) {
            console.warn("⚠️ Print server tidak tersedia, menggunakan window.print() (akan muncul dialog print)");
            setTimeout(function() {
              try {
                window.print();
                printed = true;
                setTimeout(function() {
                  if (window.opener) {
                    window.close();
                  }
                }, 1000);
              } catch(e) {
                console.error("Error printing:", e);
              }
            }, 200);
          }
        });
      }
      
      // Tunggu page selesai load sebelum print
      if (document.readyState === 'complete') {
        setTimeout(attemptPrint, 300);
      } else {
        window.addEventListener('load', function() {
          setTimeout(attemptPrint, 300);
        });
      }
      
      // Fallback timeout
      setTimeout(function() {
        if (!printed) {
          attemptPrint();
        }
      }, 1000);
    })();
  </script>
  </html>