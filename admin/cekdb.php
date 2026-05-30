<?php  
  $dtbase=$_POST['dtbase'];
  ob_start();
  include 'config.php';
  $host = "localhost"; // Nama hostnya
  $username = "root"; // Username
  $password = ""; // Password (Isi jika menggunakan password)

  if (!mysqli_connect($host,$username,'')){
    ?><script>
    	document.getElementById('form-warning').style.display='block';
    	document.getElementById('ketwarning').innerHTML='Tidak bisa terhubung ke database server'+'<br>'+'Coba periksa pakah XAMPP sudah posisi ON atau belum..';
    </script><?php
  } else {
  	// cek data base sudah ada apa belum
  	$conn=mysqli_connect($host,$username,'');
  	if (!mysqli_connect($host,$username,'',$dtbase))
  	{
      mysqli_query($conn,"CREATE DATABASE $dtbase DEFAULT CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'");

      $koneksi=mysqli_connect($host,$username,'',$dtbase);
      //mysqli_set_charset($koneksi,"utf8");

      // proses masukkan data file
      $filename = '../backup/toko_besi.sql';                                    
	    $templine = '';
	    $lines = file($filename);
        foreach ($lines as $line){
            // Lewati komentar dan baris kosong
            if (substr($line, 0, 2) == '--' || trim($line) == '') {
              continue;
            }
            $templine .= $line;
            // Jika akhir query ditemukan (tanda ';'), eksekusi
            if (substr(trim($line), -1, 1) == ';'){
              mysqli_query($koneksi,$templine) or print('Error performing query "' . $templine . '": ' . mysqli_error($koneksi));
              $templine = '';
            }
            ?><div class="loader1"><div class="loader2"><div class="loader3"></div></div></div><?php
        } 
        ?>
        <script>
          $(document).ready(function(){
		    $(".loader1").fadeOut();
		  })
        </script>
        <?php
        // Tutup koneksi yang benar (tanpa tanda kutip)
        mysqli_close($koneksi);
  	} 	
  }
?>

<!--Box Warning -->
<div id="form-warning" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0) ">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
    <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-excalmation"></i>&nbsp;Warning
      <span onclick="document.getElementById('form-warning').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
    </div>
    <div class="row w3-container">
    	<div class="col-sm-2">
    	   <i class="w3-large fa fa-warning"></i>	
    	</div>
    	<div class="col">
          <div id="ketwarning" class="w3-center">			
    	</div>
    </div>
    
    </div>
  </div>  
</div>        

<?php 
	$html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
	ob_end_clean();
	echo json_encode(array('hasil'=>$html));
?>