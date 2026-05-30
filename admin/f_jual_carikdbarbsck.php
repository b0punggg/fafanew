<?php
  ob_start();
  session_start();
  include 'config.php';
  date_default_timezone_set('Asia/Jakarta');
  $tghi        = date("Y-m-d H:i:s");   
  $conkdbar    = opendtcek();
  $kd_bar      = mysqli_escape_string($conkdbar,trim($_POST['kd_bar'])); 
  $tgl_jual    = $_POST['tgl_jual'];
  $tgl_jt      = $_POST['tgl_jt'];
  $no_fakjual  = strtoupper($_POST['no_fakjual']);
  $kd_bayar    = strtoupper($_POST['kd_bayar']);
  $kd_pel      = $_POST['kd_pel'];
  $no_urutjual = $_POST['no_urutjual'];
  
  $kd_toko    = $_SESSION['id_toko'];
  $id_user    = $_SESSION['id_user'];
  $nm_user    = $_SESSION['nm_user'];
  $qty_brg    = 1;
  $discitem   = 00.00;
  $disckov    = 00.00;
  $ketjual    ="";
  $kd_brg='';$kd_sat=0;$nm_sat='';$jml=0;$warning=0;
  $d=false;   
  
  
  //cek barcode ganda apa tidak;
    $cek=mysqli_query($conkdbar,"SELECT count(*) as jmlbarkode from mas_brg WHERE mas_brg.kd_bar='$kd_bar'  group by mas_brg.kd_bar");
    if (mysqli_num_rows($cek)>0){
       $data=mysqli_fetch_assoc($cek);
       $jml= $data['jmlbarkode'];  
    }
    unset($data,$cek);
  //------------------------------------------
  
  if ($jml>1){
    ?>
    <script>document.getElementById('modalkdbar2').style.display='block';</script>
    <div id="modalkdbar2"  class="modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge; ">
      <div class="w3-modal-content w3-card-4 w3-animate-top" style="max-width:700px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

          <div  style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, darkblue 0%,cyan 50%,white 80%);color:white;">&nbsp;<i class="fa fa-search"></i>
            CEK BARCODE 
          </div>
             
          <div class="w3-center">
            <span onclick="document.getElementById('modalkdbar2').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
          </div>
          
          <div class=""> 
            <div  class="table-responsive hrf_arial" style="overflow-y:auto;overflow-x: auto;border-style: ridge;">
              <table  class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                <tr align="middle" class="yz-theme-l1">
                  <th>No.</th>
                  <th>BARCODE</th>
                  <th>KODE BARANG</th>
                  <th>NAMA BARANG</th>
                  <th>STOK</th>
                  <th>HRG. JUAL</th>
                  <th>OPSI</th>
                </tr>           
                <?php  
                  $no=0;$hrg_beli=0;$kd_satkecil='';$qty=0;
                  $cek=mysqli_query($conkdbar,"SELECT mas_brg.jml_brg,mas_brg.kd_brg,mas_brg.nm_brg,mas_brg.kd_bar,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,mas_brg.hrg_jum1,mas_brg.hrg_jum2,mas_brg.hrg_jum3,beli_brg.id_bag FROM mas_brg 
                  LEFT JOIN beli_brg ON mas_brg.kd_bar=beli_kd_bar
                  WHERE mas_brg.kd_bar='$kd_bar' ");
                  while($data=mysqli_fetch_assoc($cek)){
                    $no++;  
                    $nm_kem1=ceknmkem($data['kd_kem1'],$conkdbar);
                    $nm_kem2=ceknmkem($data['kd_kem2'],$conkdbar);
                    $nm_kem3=ceknmkem($data['kd_kem3'],$conkdbar);
                    
                    $x=explode(';',carisatkecil2($data['kd_brg'],$conkdbar));
                    $kd_satkecil=ceknmkem2($x[0],$conkdbar);
                    if ($data['kd_kem1']==$x[0]){
                      $hrg_beli=$data['hrg_jum1'];
                    }
                    if ($data['kd_kem2']==$x[0]){
                      $hrg_beli=$data['hrg_jum2'];
                    }
                    if ($data['kd_kem3']==$x[0]){
                      $hrg_beli=$data['hrg_jum3'];
                    }
                    if ($data['jml_brg']>0){$qty=1;}
                    ?>
                    <tr>
                         <td align="right"><?php echo $no ?></td>
                         <td align="left"><?php echo $data['kd_bar'] ?></td>
                         <td align="left"><?php echo $data['kd_brg'] ?></td>
                         <td align="left"><?php echo $data['nm_brg'] ?></td>
                         <td align="left"><?php echo $data['jml_brg'].' '.$kd_satkecil ?></td>
                         <td align="left"><?php echo gantitides($hrg_beli) ?></td>
                         <td>
                      <button id="<?='tmbpil'.$no?>" onclick="
                              document.getElementById('kd_bar').value='<?=$kd_bar?>';
                              document.getElementById('kd_brg').value='<?=$data['kd_brg']?>';carisatbrg();
                              // document.getElementById('kd_sat').value='<?=$kd_bar?>';
                              document.getElementById('modalkdbar2').style.display='none';
                              document.getElementById('qty_brg').value='<?=$qty?>';
                              document.getElementById('id_bag').value='<?=$data['id_bag']?>';
                              aktif();document.getElementById('qty_brg').focus();document.getElementById('qty_brg').select();" 
                           class="btn-primary fa fa-edit" style="cursor: pointer; font-size: 12pt" title="Edit Data">
                      </button>     
                   </td>  
                </tr>   
                    <?php
                    }  
                ?>
              </table>
            </div>    
          </div> <!--Modal-body-->
      </div><!--Modal content-->
    </div>
  <?php  
  } else{
    if (!empty($kd_bar)) {  
      $cekot=mysqli_query($conkdbar,"SELECT * FROM mas_brg WHERE kd_bar='$kd_bar' ");
      if(mysqli_num_rows($cekot)>=1){
        // jika ada pada mas_brg
        $datax=mysqli_fetch_array($cekot);
        $kd_brg=mysqli_escape_string($conkdbar,$datax['kd_brg']); 
        $nm_brgx=$datax['nm_brg']; 
        // Proses simpan 
        $hrg_jual=0;$jum_kem=0;$subtot=0;$diskon=0;$brg_klr=0;$d=false;
        //**--- bagian save data ----
        $x        =explode(';', carisatkecil2($kd_brg,$conkdbar));
        $sat_kecil=$x[0]; 
        $jum_kecil=$x[1]; 

        // cari satuan besar
        $x     =explode(";",carisatbesar2($kd_brg,$conkdbar));
        $bigsat=$x[0];
        $bigjum=$x[1];
       //-------------------------
        if (ceknmkem($bigsat,$conkdbar)=="RENTENG"){
          $kd_sat=$bigsat;   
        } else {
          $kd_sat=$sat_kecil;
        }  
        if($kd_sat==$datax['kd_kem3']){
          $hrg_jual= $datax['hrg_jum3'];
          $jum_kem = $datax['jum_kem3'];
        }
        if($kd_sat==$datax['kd_kem2']){
          $hrg_jual=$datax['hrg_jum2'];
          $jum_kem=$datax['jum_kem2'];
        }
        if($kd_sat==$datax['kd_kem1']){
          $hrg_jual=$datax['hrg_jum1'];
          $jum_kem=$datax['jum_kem1'];
        }        
        // jika ada disckon tetap     
        $cekdisc=mysqli_query($conkdbar,"SELECT * from disctetap where kd_brg='$kd_brg' order by no_urut");
        if (mysqli_num_rows($cekdisc)>=1){    
          while($datadisc=mysqli_fetch_assoc($cekdisc)){
            if ($kd_sat==$datadisc['kd_sat'] && $datadisc['hrg_jual']>0){
              if ($qty_brg>=$datadisc['lim_jual']){
                $hrg_jual=$datadisc['hrg_jual'];
              } 
            }
          }
        }
        unset($datadisc);mysqli_free_result($cekdisc);

        //**cek jika potong stok atau tidak
          $q=mysqli_query($conkdbar,"SELECT * FROM seting ORDER BY no_urut");
          while ($d=mysqli_fetch_assoc($q)){
            if ($d['nm_per']=="POTONG"){
              $potong=$d['kode'];  
            }
            if ($d['nm_per']=="PROSES"){
              $c_proses=$d['kode'];  
            }
          }
          mysqli_free_result($q);unset($d);

       //------
        $jumlahstok = 0;
        $cekstok    = mysqli_query($conkdbar,"SELECT SUM(stok_jual) AS jums FROM beli_brg WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko' GROUP BY kd_brg");
        $datax1     = mysqli_fetch_assoc($cekstok);
        $jumlahstok = $datax1['jums'];    
        unset($datax1,$cekstok);

        if ($jumlahstok>=1){ 
         
          if ($potong==0){
            $xr=0;$rhrg_beli=0;$hrg_beli=0;$ket='';
            //**cari hrg beli rata-rata
            $q=mysqli_query($conkdbar,"SELECT * FROM beli_brg 
              LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
              WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' ORDER BY beli_brg.no_urut DESC LIMIT 1");
              $d=mysqli_fetch_assoc($q);
              $xr++;
              $disc1=mysqli_escape_string($conkdbar,$d['disc1'])/100;
              $disc2=mysqli_escape_string($conkdbar,$d['disc2']);
              $ppn=mysqli_escape_string($conkdbar,$d['ppn']/100);
              if ($d['disc1']=='0.00'){
                // echo gantiti($data['disc2']);
                $hrg_beli=($d['hrg_beli']-$disc2);
              }else{
                $hrg_beli=($d['hrg_beli'])-(($d['hrg_beli'])*$disc1);
                $hrg_beli=round($hrg_beli,0);
              }
              if ($d['disc1']=='0.00' && $d['disc2']=='0'){
                $hrg_beli=$d['hrg_beli'];
              }
              $hrg_beli  = ($hrg_beli/konjumbrg2($d['kd_sat'],$kd_brg,$conkdbar))*$jum_kem;
              $rhrg_beli = $rhrg_beli+($hrg_beli+($hrg_beli*$ppn));
              $ket       = $d['ket'];
              $nm_brg    = mysqli_real_escape_string($conkdbar,$d['nm_brg']);
            
              $rhrg_beli = round($rhrg_beli/$xr,0);    
            //echo '$rhrg_beli='.$rhrg_beli;    
            unset($d,$q);

            //proses penjualan
            $jml_brg = ($qty_brg*$jum_kem)/$jum_kem;
            $laba    = ($hrg_jual-$rhrg_beli)*$jml_brg;
            $nm_brg  = $nm_brg." ".$ketjual;
            $consave = $conkdbar;
            $d=mysqli_query($consave,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$rhrg_beli','$qty_brg','$disckov','$discitem','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','','$tgl_jt','$id_user','$nm_user',false,'$ket','0','','$id_bag','$tghi')");
             
            ?><script>document.getElementById("edit-warning").value=0</script><?php
          } else {

            // Insert dum_jual
            if ($c_proses==0){
             $cek_it=mysqli_query($conkdbar,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag FROM beli_brg 
                  LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
                  WHERE beli_brg.kd_brg='$kd_brg' AND beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
                  ORDER BY beli_brg.no_urut ASC");    
            }
            if ($c_proses==1){
              $cek_it=mysqli_query($conkdbar,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag FROM beli_brg 
                  LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
                  WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
                  ORDER BY beli_brg.no_urut DESC");    
            }
            if ($c_proses==2){
              //***cek hrg beli utk dijadikan rata
              $qr=mysqli_query($conkdbar,"SELECT hrg_beli,kd_brg,disc1,disc2,ppn,kd_sat FROM beli_brg WHERE kd_brg='$kd_brg' AND kd_toko='$kd_toko' AND stok_jual>0");
              $xc=0;$jum_hrg=0;$hrgx=0;$hrg_rata=0;
              while ($dr=mysqli_fetch_assoc($qr)){
                $xc++;
                $disc1 = $dr['disc1']/100;
                $disc2 = $dr['disc2'];
                $ppn   = $dr['ppn']/100;
                if ($dr['disc1']=='0.00'){
                    // echo gantiti($data['disc2']);
                  $hrgx=($dr['hrg_beli']-$disc2);
                }else{
                  $hrgx=($dr['hrg_beli'])-(($dr['hrg_beli'])*$disc1);
                  $hrgx=round($hrgx,0);
                }
                if ($dr['disc1']=='0.00' && $dr['disc2']=='0'){
                  $hrgx=$dr['hrg_beli'];
                } 
                $hrg_rata = ($hrgx/konjumbrg2($dr['kd_sat'],$kd_brg,$conkdbar))*$jum_kem;
                $jum_hrg  = $jum_hrg+($hrg_rata+($hrg_rata*$ppn));        
              }
              $hrg_rata = round($jum_hrg/$xc,0);
              mysqli_free_result($qr);unset($dr,$xc,$jum_hrg,$hrgx);
             
              $cek_it=mysqli_query($conkdbar,"SELECT beli_brg.kd_brg,beli_brg.no_urut,beli_brg.no_item,beli_brg.no_fak,beli_brg.tgl_fak,beli_brg.kd_bar,beli_brg.kd_sup,beli_brg.stok_jual,beli_brg.hrg_beli,beli_brg.disc1,beli_brg.disc2,beli_brg.kd_sat,beli_brg.ket,beli_brg.ppn,mas_brg.nm_brg,mas_brg.kd_kem1,mas_brg.kd_kem2,mas_brg.kd_kem3,mas_brg.jum_kem1,mas_brg.jum_kem2,mas_brg.jum_kem3,beli_brg.id_bag FROM beli_brg 
                  LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg 
                  WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' AND beli_brg.stok_jual>0
                  ORDER BY beli_brg.no_urut ASC");    
            }    

            //replace jml_brg,brg_klr pd mas_brg
            $conk=opendtcek();
            $jml_brg_mas  = $brg_klr_mas=$no_urut_mas=0;
            $cekbrg       = mysqli_query($conk,"SELECT jml_brg,brg_klr,no_urut FROM mas_brg WHERE kd_brg='$kd_brg'");
            $dtbrg        = mysqli_fetch_assoc($cekbrg);
            $jml_brg_mas  = $dtbrg['jml_brg']-($qty_brg*$jum_kem);
            $brg_klr_mas  = $dtbrg['brg_klr']+($qty_brg*$jum_kem);
            $no_urut_mas  = $dtbrg['no_urut'];
            $d            = mysqli_query($conk,"UPDATE mas_brg SET jml_brg='$jml_brg_mas',brg_klr='$brg_klr_mas' WHERE no_urut='$no_urut_mas'");   
            mysqli_free_result($cekbrg);unset($dtbrg);  
            mysqli_close($conk);

            $stok = $jumlahstok;          
            $qty  = $n_stok_jual=$jml=$hrg_beliawal=$hrg_asal=$xhrg_beli=0;
            $qty  = $qty_brg*$jum_kem; 
            while ($cari=mysqli_fetch_array($cek_it)) {
              if ($qty>0){
                if ($qty>=$cari['stok_jual']){
                  if ($cari['stok_jual']-$qty<=0){
                    $stok_jual = 0; // utk replace pada beli_brg
                    $jml_brg   = $cari['stok_jual']/$jum_kem;
                      
                  } else{
                    $stok_jual = $cari['stok_jual']-$qty; // utk replace pada beli_brg
                    $jml_brg   = $qty/$jum_kem;
                    
                  } 
                } else{
                    $stok_jual = $cari['stok_jual']-$qty;  
                    $jml_brg   = $qty/$jum_kem;
                    
                }
                //divinisi variable untuk dum_jual
                $disc1 = $cari['disc1']/100;
                $disc2 = $cari['disc2'];
                $ppn   = $cari['ppn']/100;
                if ($c_proses==2){
                  $xhrg_beli=$hrg_rata;
                } else {
                  $xhrg_beli=$cari['hrg_beli'];
                }
                //konversi satuan terkecil
                if($cari['kd_sat']==$cari['kd_kem3']){
                  $hrg_asal=$xhrg_beli/$cari['jum_kem3'];
                }
                if($cari['kd_sat']==$cari['kd_kem2']){
                  $hrg_asal=$xhrg_beli/$cari['jum_kem2'];
                }
                if($cari['kd_sat']==$cari['kd_kem1']){
                  $hrg_asal=$xhrg_beli/$cari['jum_kem1'];
                }
                //------------------------  
                
                $hrg_beli=$hrg_asal*$jum_kem;
                if ($cari['disc1']=='0.00'){
                  $hrg_beliawal=($hrg_beli-$disc2);
                }else{
                  $hrg_beliawal=($hrg_beli)-(($hrg_beli)*$disc1);
                  $hrg_beliawal=round($hrg_beliawal,0);
                }
                if ($cari['disc1']=='0.00' && $cari['disc2']=='0'){
                  $hrg_beliawal=$hrg_beli;
                } 
                $hrg_beliawal=$hrg_beliawal+($hrg_beliawal*$ppn);  
                if($discitem=='0'){
                  $laba=($hrg_jual-$hrg_beliawal)*$jml_brg;  
                  $disckov='00.00';
                }else{
                  $disc=0;
                  //konversi satuan terbesar
                  $awaldisc=$discitem/$hrg_jual;
                  $laba=(($hrg_jual-$discitem)-$hrg_beliawal)*$jml_brg;  
                  $disckov='00.00';
                }
                //------------------------------------
                
                $qty     = $qty-$cari['stok_jual'];  
                $no_urut = $cari['no_urut'];
                $nm_brg  = $cari['nm_brg']." ".$ketjual;
                $ket     = $cari['ket'];
                $id_bag  = $cari['id_bag']; 
                $stok    = $stok-($jml_brg*$jum_kem);
                $d       = mysqli_query($conkdbar,"UPDATE beli_brg SET stok_jual='$stok_jual' WHERE no_urut='$no_urut'");
                $d       = mysqli_query($conkdbar,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$hrg_beli','$jml_brg','$disckov','$discitem','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','$no_urut','$tgl_jt','$id_user','$nm_user',false,'$ket','0','','$id_bag','$tghi')");
                
              }//qty>0
            }//while   
        
            // if($stok!=$jml_brg_mas){
            //   date_default_timezone_set('Asia/Jakarta');
            //   $nm_user=$_SESSION['nm_user'];
            //   $tghi=date("Y-m-d h:i:sa");
            //   mysqli_query($conkdbar,"INSERT INTO file_log VALUES('','Jual cari kdbar','$kd_brg','$nm_brgx','$tghi','$kd_toko','$nm_user')");
            // }
            mysqli_free_result($cek_it);    
            ?><script>document.getElementById("edit-warning").value=0</script><?php   
          } // if potong==0

        } else { // jika stok 0 tp tetap jalan
          if ($potong==0){
            $xr=0;$rhrg_beli=0;$hrg_beli=0;$ket='';
            
            $q=mysqli_query($conkdbar,"SELECT * FROM beli_brg 
              LEFT JOIN mas_brg ON beli_brg.kd_brg=mas_brg.kd_brg
              WHERE beli_brg.kd_brg='$kd_brg' and beli_brg.kd_toko='$kd_toko' ORDER BY beli_brg.no_urut DESC LIMIT 1");
              $d=mysqli_fetch_assoc($q);
              $xr++;
              $id_bag=$d['id_bag'];
              $disc1=mysqli_escape_string($conkdbar,$d['disc1'])/100;
              $disc2=mysqli_escape_string($conkdbar,$d['disc2']);
              $ppn=mysqli_escape_string($conkdbar,$d['ppn']/100);
              if ($d['disc1']=='0.00'){
                // echo gantiti($data['disc2']);
                $hrg_beli=($d['hrg_beli']-$disc2);
              }else{
                $hrg_beli=($d['hrg_beli'])-(($d['hrg_beli'])*$disc1);
                $hrg_beli=round($hrg_beli,0);
              }
              if ($d['disc1']=='0.00' && $d['disc2']=='0'){
                $hrg_beli=$d['hrg_beli'];
              }
              $hrg_beli  = ($hrg_beli/konjumbrg2($d['kd_sat'],$kd_brg,$conkdbar))*$jum_kem;
              $rhrg_beli = $rhrg_beli+($hrg_beli+($hrg_beli*$ppn));
              $ket       = $d['ket'];
              $nm_brg    = mysqli_real_escape_string($conkdbar,$d['nm_brg']);
            
              $rhrg_beli = round($rhrg_beli/$xr,0);    
            //echo '$rhrg_beli='.$rhrg_beli;    
            unset($d,$q);

            //proses penjualan
            $jml_brg=($qty_brg*$jum_kem)/$jum_kem;
            $laba=($hrg_jual-$rhrg_beli)*$jml_brg;
            $nm_brg=$nm_brg." ".$ketjual;
            
            //$consave=opendtcek();
            $d=mysqli_query($conkdbar,"INSERT INTO dum_jual VALUES('','$tgl_jual','$no_fakjual','$kd_toko','$hrg_jual','$rhrg_beli','$qty_brg','$disckov','$discitem','$laba','$kd_bayar','$kd_pel','$kd_brg','$kd_sat','$nm_brg','BELUM','','$tgl_jt','$id_user','$nm_user',false,'$ket','0','','$id_bag','$tghi')");
            //mysqli_close($consave);    
            ?><script>document.getElementById("edit-warning").value=0</script><?php
          } else {
            ?><script>
              document.getElementById("form-warning").style.display='block';
              document.getElementById("kd_bar").value='';
              document.getElementById("edit-warning").value=1;
              document.getElementById("igt-txt1").value='<?=$nm_brgx?>';
              document.getElementById("igt-txt2").value='Stok barang tidak cukup !!';
              document.getElementById("igt-txt2").focus();
              </script><?php 
          } // $potong==0 
          
        } 

      } else {
        ?><script>
          document.getElementById("form-warning").style.display='block';
          document.getElementById("kd_bar").value='';
          document.getElementById("edit-warning").value=1;
          document.getElementById("igt-txt1").value='<?=$kd_brg?>';
          document.getElementById("igt-txt2").value='Uppss.. barang tidak ditemukan';
          document.getElementById("igt-txt2").focus();
        </script><?php
      }
      unset($datax);mysqli_free_result($cekot);
      $no_urutjual="";

    } // empty kode_bar
  } // jika ada 2 code bar 

  // mysqli_close($conkdbar);    
?>

<script>
  document.getElementById('kd_bar').value='';  
  caribrgjual(1,true);
  //kosongkan();
</script>

<?php  
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  echo json_encode(array('hasil'=>$html));
?>