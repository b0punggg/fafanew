<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Backup Data</title>
    <?php 
      include 'starting.php';
      $nm_toko=$_SESSION['nm_toko'];
    ?>
</head>
<body>
  <style>
   button.datauser:hover {
    /*box-shadow: 0px 0px 7px;        
    background-color: grey;*/
    cursor:pointer;
  }

  </style>

  <script>
   function prosesback(cfile){
      $.ajax({
        url: 'f_backup_proses.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {c_file: cfile}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){
          $("#listhasil").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }
    
  </script>  

  <div id="main">
    <div class="container" style="text-align: center">
      <br>
      <div class="row">
        <div class="col-sm-8 offset-sm-2 w3-card-2"> 
          <div class="row yz-theme-d3" style=" border:2.5px solid;border-style: ridge;border-color: white;color:darkblue;font-size: 16px;">
            <h5 class="w3-text-white w3-padding-small"><i class="fa fa-download"></i>&nbsp;&nbsp;BACKUP DATA</h5>    
          </div>

          <div class="row" style="background: linear-gradient(565deg, #E6E6FA 0%, white 80%); ">
            <div class="col-sm-4">
              <img src="img/cloud-arrows.png" class="w3-image w3-padding" alt="">
            </div>  
            <div class="col-sm-8 w3- w3-padding">
              <?php 
                error_reporting(0);
                //$file=date("dmY_hisA").'_UNI51'.'.sql';
                $file=date("d-m-Y").'_FAFA'.'.sql';
                //backup_tables();
               ?>  
               <p>Modul ini digunakan untuk backup data, lakukan jika sudah tidak ada user lainnya aktif</p>
               <p>Jika anda ingin menyimpan backup data ke direktori lain, pastikan anda lakukan PROSES BACKUP dahulu, kemudian DOWNLOAD DATA.</p>
                <button  type="button" class="datauser btn-warning w3-hover-shadow" onclick="prosesback('<?=$file?>')" style="border-radius: 5px;height: 38px"><span class="fa fa-save"></span> Proses Backup</button>
                <button  type="button" class="datauser btn-primary w3-hover-shadow" onclick="location.href='downloadfile.php?nama_file=<?=$file?>'" style="border-radius: 5px;height: 38px"><span class="fa fa-download"></span> Download Data</button>

               <br><br>
               <div id="listhasil"></div>

            </div>
          </div>
        </div>  
      </div> 
      <br> 
      <div class="row">
        <div class="col-sm-8 offset-sm-2 w3-card-2"> 
          <div class="row yz-theme-d4" style=" border:2.5px solid;border-style: ridge;border-color: white; color:darkblue;font-size: 16px;">
            <h5 id="cekrekord" class="w3-text-white w3-padding-small"><i class="fa fa-upload"></i>&nbsp;&nbsp;RESTORE DATA</h5>    
          </div>

          <div class="row" style="background: linear-gradient(565deg, #E6E6FA 0%, white 80%); ">
            <div class="col-sm-4">
              <img src="img/restore.png" class="w3-image w3-padding" alt="">
            </div>  
            <div class="col-sm-8 w3- w3-padding">
              <p>Restore data ini digunakan untuk mengembalikan data yg sudah dibackup sebelumnya</p>

              <form enctype="multipart/form-data" method="post" class="form-control" style="background-color: transparent;">
                <div class="form-group row"> 
                  <label for="no_nota" class="col-sm-3 col-form-label" style="font-size: 12pt">File (*.sql)</label>
                  <div class="col-sm-8" >
                    <input type="file" name="datafile" size="30" class="form-control" style="font-size: 10pt;background-color: transparent;">
                  </div>
                </div>    
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" name="restore" class="datauser btn-success w3-hover-shadow fa fa-files-o" style="border-radius: 5px;height: 38px">&nbsp;Proses Restore </button>
                    </div>
                </div>
              </form> 
              <?php
                if(isset($_POST['restore'])){
                    // $koneksi=mysql_connect("localhost","root","");
                    // mysql_select_db("toko_retail",$koneksi);
                    $koneksi=opendtcek();
                    $nama_file=$_FILES['datafile']['name'];
                    $ukuran=$_FILES['datafile']['size'];
                    if ($nama_file==""){
                        echo "Fatal Error";
                    }
                    else{
                    //definisikan variabel file dan alamat file
                        $uploaddir='../backup/';
                        $alamatfile=$uploaddir.$nama_file;
                        $xend=0;
                        if (move_uploaded_file($_FILES['datafile']['tmp_name'],$alamatfile)){
                            $filename = '../backup/'.$nama_file.'';                                    
                            $templine = '';
                            $lines = file($filename);
                            mysqli_query($koneksi,"DROP TABLE bay_beli");
                            foreach ($lines as $line){
                              // Lewati komentar dan baris kosong
                              if (substr($line, 0, 2) == '--' || trim($line) == ''){
                                continue;
                              }
                              
                              $templine .= $line;
                              // Jika sudah mencapai akhir query (tanda ';'), eksekusi
                              if (substr(trim($line), -1, 1) == ';'){
                                mysqli_query($koneksi,$templine) or print('Error performing query "' . $templine . '": ' . mysqli_error($koneksi));
                                $templine = '';
                              }
                              $xend++;
                              ?><script>document.getElementById('cekrekord').innerHTML='<?='PROSES RESTORE RECORD DATA '.$xend?>'+".." </script><?php
                            }    
                            ?><script>document.getElementById('cekrekord').innerHTML='RESTORE DATA'</script><?php
                            echo "Restore Database Telah Berhasil, Silahkan dicek ! "." Total record ".gantiti($xend);
                        }
                        else{
                            echo "Restore Database Gagal, kode error = " . $_FILES['location']['error'];
                        }    
                    }
                }
                else{
                    unset($_POST['restore']);
                }
              ?>
            </div>
          </div>
        </div>  
      </div> 
    </div>
  </div>   
  <div id="fload2" class="w3-modal" style="background-color:rgba(1, 1, 1, 0); ">
    <!-- <div id="loader"><center><strong><h4>Proses..</h4></strong></center></div> -->
    <div style="position: absolute;margin-left: 40%;margin-top: 10%"><img src="img/loading.gif" class="" alt=""><h5 class="w3-text-black w3-center" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);"><strong>Loading data..</strong></h5></div>
  </div>
</body>
</html>

<!-- untuk download file jika pada server -->
<!-- <p align="center">Backup database telah berhasil !</p><br /> -->
<!-- <p align="center"><a style="cursor:pointer" onclick="location.href='downloadfile.php?nama_file=<?php echo $file;?>'" title="Download">Copy pada drive lain ?</a></p> -->


