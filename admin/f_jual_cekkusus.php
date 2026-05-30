<?php 
  $kd_brg=$_POST['keyword'];
  ob_start();
  session_start();
  include 'config.php';
  $connect=opendtcek();
  $kd_toko=$_SESSION['id_toko'];
  echo "cek=".substr($kd_brg, -3);
   $kd_brg=rtrim($kd_brg);
   if(substr($kd_brg, -3)=="CEK"){
    
       ?>
       <script>
        // document.getElementById('tmb-add').type='button';
        // document.getElementById('tmb-add2').type='button';
        // document.getElementById('tmb-add3').type='button';
        //document.getElementById('form-cekkd').style.display='block';
        //document.getElementById('potong').focus();
       </script>
       <?php
   }else{
   	?><script>
      // document.getElementById("cekkd").value='';
      // document.getElementById('form-cekkd').style.display='none'
      // document.getElementById('tmb-add').type='submit';
      // document.getElementById('tmb-add2').type='submit';
      // document.getElementById('tmb-add3').type='submit';
      </script>
    <?php
   }
?>

<!-- Form  cekkd-->
      <div id="form-cekkd" class="w3-modal" style="padding-top:50px;background-color:rgba(1, 1, 1, 0.3);border-style: ridge; ">
        <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px;border-radius:5px;box-shadow: 0px 2px 60px;border-style: ridge;border-color:white">

          <div style="background-color: orange;border-style: ridge;border-color: white;background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;">&nbsp;<i class="fa fa-search"></i>
            Daftar panding nota penjualan
          </div>
   
          <div class="w3-center">
            <span onclick="document.getElementById('form-cekkd').style.display='none'" class="w3-display-topright" title="Close Modal" style="margin-top: -3px;margin-right: 0px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
          </div>
          <input id="potong" type="text" >

          
        </div><!--Modal content-->
      </div>
      <!-- End Form Nota --> 
<?php
  mysqli_close($connect);
  $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
  ob_end_clean();
  // Buat array dengan index hasil dan value nya $html
  // Lalu konversi menjadi JSON
  echo json_encode(array('hasil'=>$html));
?>