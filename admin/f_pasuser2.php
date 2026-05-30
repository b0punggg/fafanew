<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="img/keranjang.png">

<div id="main">
	<?php 
	  //pastikan operator user
	  include "starting.php";
    
    $connect=opendtcek();
	  $id_user=$_SESSION['id_user'];
	  $oto=$_SESSION['kodepemakai'];
	  $fuser=mysqli_query($connect,"select * from pemakai where id_user='$id_user'");
    $dataus=mysqli_fetch_array($fuser);
    if ($dataus['otoritas']=='1') {
      $otoritas='OPERATOR';
    } else {
      $otoritas='ADMINISTRATOR';
    }                              			  
	 ?>
   
	<style>
   
      .col {
      	/*background-color: #ffffbb;*/
      	border-radius: 5px;
      }

     
   .bg {
     /*width: 3%;*/
     height: 97%;
     /*position: fixed;*/
     z-index: 0;
     /*float: left;*/
     left: 0;
     margin-top: 10px;
     }


   </style>   

	 <script>
	 
   	  function caridtpass(page_number, search){
		  $(this).html("ketik nama user").attr("disabled", "disabled");
		  $.ajax({
		    url: 'f_passcari.php', // File tujuan
		    type: 'POST', // Tentukan type nya POST atau GET
		    data: {keyword: $("#keyktpass").val(), page: page_number, search: search}, 
		    dataType: "json",
		    beforeSend: function(e) {
		      if(e && e.overrideMimeType) {
		        e.overrideMimeType("application/json;charset=UTF-8");
		      }
		    },
		    success: function(response){ 
		      $("#btn-ktpass").html("SEARCH").removeAttr("disabled");
		      
		      $("#viewdtpass").html(response.hasil);
		    },
		    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
		      alert(xhr.responseText); // munculkan alert
		    }
		  });
	  }

    function carikdtoko(iduser){
      $.ajax({
        url: 'f_passcarikdtoko.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword:iduser}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          $("#viewkdtoko").html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }
    function kosongkan(){
      document.getElementById('id_user').value='';
      document.getElementById('nm_user').value='';
      document.getElementById('alamat').value='';
      document.getElementById('no_hp').value='';
      document.getElementById('pilotor').value='0';
      document.getElementById('kd_tokoi').value='0'; 
      //carikdtoko(<?=$id_user?>);
    }
	 </script>   

   <div id="snackbar"></div>
   <script>
     function resetpass($id){
      $.ajax({
        url: 'f_resetpas.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {id:$id}, 
        dataType: "json",
        success: function(){ 
          // $("#resetpass").html(response.hasil);
          popnew("RESET PASSWORD BERHASIL");
        }
      });
    }
   </script>
   <?php 
      if(isset($_GET['pesan']))
      {
        $pesan=mysqli_real_escape_string($connect,$_GET['pesan']);
        if($pesan=="gagal1")
        {
          ?>
            <script>popnew_warning("Verifikasi Password Lama tidak valid");</script>
          <?php
        }else if($pesan=="gagal2")
        {
          ?>
            <script>popnew_error("Konfirmasi Password Baru tidak valid");</script>
          <?php
        }else if($pesan=="gagal")
        {
          ?>
            <script>popnew_error("Data Tidak Ditemukan");</script>
          <?php
        }else if($pesan=="ok")
        {
          ?>
            <script>popnew_ok("UpDate Data Berhasil ");carikdtoko(<?=$id_user?>);</script>
          <?php
        }
      }
    ?>
    
    <div class="container-fluid" style="border-style:ridge;font-size: 12px">
 	   <div class="row" >
	       <div class="col-sm-12" style="overflow-y:auto;border-style: solid;border-width: 1px;padding-bottom: 5px">
  			  <div class="col" style="height: 2px"></div><!--Spasi border-->
  			  <div class="col-sm-12 " style="overflow-y:auto;border-style:ridge;border-width: 3px;border-radius: 5px">

        	 <form id="form1" action="f_pasuseredit2_act.php" method="post" enctype="multipart/form-data">

        		  <div class="row">
        		  	<div class="col-sm-2"></div>	 
        			  <div class="col-sm-4">
        			  	<div class="form-group row" style="margin-top: 10px"> 
        			  	  <img id="fotouser" class="rounded-circle" style="border:2px solid black;box-shadow: 1px 1px 10px black;max-width: 300px" src="img/<?php echo $dataus['foto']; ?>" alt="">
        			    </div>
        			  </div><!--col-sm-4-->

                <div class="col-sm-6">
                  <div class="form-group row" style="margin-top: 10px">
                      <div class="col-sm-12"><center><strong>DATA USER</strong></center>	</div>
                  </div>
                  <!--key kt  -->
                  <input id='id_user' type="hidden" name="id_user" value="<?=$id_user?>">
                 	<div class="form-group row" style="margin-top: -5px">
      						  <label for="nm_user" class="col-sm-3 col-form-label"><b>Nama User</b></label>
                    <div class="col-sm-9">
                      <div class="input-group" style="border-radius: 5px;"> 
      			            <input id="nm_user" onfocus="" style="font-size:12px" type="text" class="form-control hrf_arial" name="nm_user"  required >
      			          </div>    
      						  </div>
      						</div>
                  <div class="form-group row" style="margin-top: -5px">
						        <label for="alamat" class="col-sm-3 col-form-label"><b>Alamat</b></label>
                    <div class="col-sm-9">
                      <div class="input-group" style="border-radius: 5px;"> 
			                  <input id="alamat" onfocus="" style="font-size:12px" type="text" class="form-control hrf_arial" name="alamat"  required >
			                </div>    
						        </div>
						      </div>
      						<div class="form-group row" style="margin-top: -5px">
      						  <label for="no_hp" class="col-sm-3 col-form-label"><b>Telp/No.HP</b></label>
                      <div class="col-sm-9">
                        <div class="input-group" style="border-radius: 5px;"> 
      			              <input id="no_hp" onfocus="" style="font-size:12px" type="text" class="form-control hrf_arial" name="no_hp" required >
      			            </div>    
      						    </div>
      						</div>

                  <?php if ($oto=='2') { ?>
						      <div class="form-group row" style="margin-top: -5px">
						        <label for="oto" class="col-sm-3 col-form-label"><b>Otoritas Aplikasi</b></label>
                      <div class="col-sm-9">
                        <div class="input-group" style="border-radius: 5px;"> 
                         	<!-- <input id="otor" type="hidden" name="otoritas" > -->
                          
                          <select name="otoritas" id="pilotor" class="form-control" style="font-size:12px;border-radius: 5px;"> 
                            <option value="1">OPERATOR</option>
                            <option value="2">ADMINISTRATOR</option>
                          </select>        
                           
                        </div>    
						          </div>
						      </div>
                  <?php } ?>

                  <div class="form-group row" style="margin-top: -5px">
                    <label for="kd_toko" class="col-sm-3 col-form-label"><b>Kode Toko</b></label>
                      <div class="col-sm-9">
                        <select class="form-control hrf_arial" name="kd_tokoi" id="kd_tokoi" style="font-size:12px ;height: 30px;" required >
                          <?php 
                            $cek=mysqli_query($connect,"select kd_toko from toko");
                            while($data=$data=mysqli_fetch_array($cek)){  
                          ?>
                          <option value="<?=$data['kd_toko']?>"><?=$data['kd_toko']?></option>
                          <?php }  ?>
                        </select>
                      </div>
                  </div>

                  <div class="form-group">
                      <label>Untuk Ganti Foto [besar file maksimal 1m]</label>
                      <input name="foto" type="file" class="form-control" >
                  </div>    
                </div><!--col-sm-6--> 
        			</div> <!--Row input-->
              <br>

        			<!--Tombol reset/simpan  -->
			        <div class="row">
			          <div class="col-sm-6" style="padding-bottom: 2px">
                      <button onclick="document.getElementById('fganpas').style.display='block';document.getElementById('paslama').focus();" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;background-color: orange">GANTI PASSWORD</button>
                </div>       
                <div class="col-sm-6">
                      <button type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;background-color: orange">SIMPAN PERUBAHAN</button>
                </div>  
		            </div>  
		            <!-- End tombol -->
        	 </form>
           <div id="viewkdtoko"><script>carikdtoko('<?=$id_user?>')</script></div>
           <div class="row">
             <div class="col-sm-12">
                <button id='btn-tbh' onclick="kosongkan();document.getElementById('nm_user').focus();" type="button" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;background-color: darkorange">TAMBAH DATA USER</button>
             </div>
           </div>
           <br>
          </div><!--col-sm-12 border input data-->

          <!-- Tampilkan data -->
          <div class="col" style="height: 3px"></div> 
          <div id="datlist" class="col" style="overflow-y: auto;border-style: ridge;">
            <div class="input-group" style="margin-top: 15px">
              <input onkeyup="if(event.keyCode==13){caridtpass(1, true);}" style="box-shadow: 1px 1px 5px" type="text" class="form-control" placeholder="Ketik nama user" id="keyktpass">
                <span class="input-group-btn">
                  <button onclick="caridtpass(1, true);" class="btn btn-primary" type="button" id="btn-ktpass" style="box-shadow: 1px 1px 5px black">SEARCH</button>
                  <a style="box-shadow: 1px 1px 5px" onclick="document.getElementById('keyktpass').value='';document.getElementById('btn-ktpass').click();" href="#" class="btn btn-warning">RESET</a>
                </span>
            </div>
            <div id="viewdtpass" style="margin-top: 10px;">
              <script>caridtpass(1,true);</script>
            </div>
          </div>      

          <?php if ($dataus['otoritas']=='1'){ ?>
            <script>document.getElementById("datlist").style.display="none";</script>
            <script>document.getElementById("btn-tbh").style.display="none";</script>
          <?php }elseif ($dataus['otoritas']=='2') {?>
            <script>document.getElementById("datlist").style.display="block";</script>
            <script>document.getElementById("btn-tbh").style.display="block";</script>
          <?php } ?>
          <!-- End Tampilkan data alat -->	

        </div> <!--col-sm-12 border solid-->			
      </div> <!--row ke 1-->

       <!-- Form fganpas-->
        <div id="fganpas" class="w3-modal" style="margin-left:0px;background-color:rgba(1, 1, 1, 0) ">
          <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-radius:5px;background-color:#ffffbb ;box-shadow: 0px 2px 60px;border-style: ridge;width: 50%">
            <div class="w3-center">
              <div style="background-color: orange;border-style: ridge;">
                 <center><strong>GANTI PASSWORD</strong></center>
              </div>
              <span onclick="document.getElementById('fganpas').style.display='none'" class="w3-display-topright" title="Tutup" style="margin-top: -20px;margin-right: -17px"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
            </div>
            <div class="modal-body">
              <form action="f_pasusereditpas2_act.php" method="POST">
                <div class="col-sm-12">
                  <div class="form-group row" style="margin-top: 5px">
                    <label for="paslama" class="col-sm-4 col-form-label"><b>Ketik Password Lama</b></label>
                    <div class="col-sm-8">
                      <div class="input-group" style="border-radius: 5px;"> 
                        <input id="id_userpas" type="hidden" name="id_userpas" value="<?=$id_user?>">
                        <input id="paslama" onkeyup="if(event.keyCode==120){resetpass(document.getElementById('id_userpas').value);document.getElementById('fganpas').style.display='none'}" style="background-color: #eeeeaa;font-size:12px" type="Password" class="form-control " name="paslama" autofocus required>
                        
                      </div>    
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group row" style="margin-top: 25px">
                    <label for="pasbaru" class="col-sm-4 col-form-label"><b>Ketik Password Baru</b></label>
                    <div class="col-sm-8">
                      <div class="input-group" style="border-radius: 5px;"> 
                        <input id="pasbaru" onfocus="" style="background-color: #eeeeaa;font-size:12px" type="Password" class="form-control " name="pasbaru" required>
                      </div>    
                    </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="form-group row" style="margin-top: -5px">
                    <label for="pasbaru1" class="col-sm-4 col-form-label"><b>Ketik Ulang Password Baru</b></label>
                    <div class="col-sm-8">
                      <div class="input-group" style="border-radius: 5px;"> 
                        <input id="pasbaru1" onfocus="" style="background-color: #eeeeaa;font-size:12px" type="Password" class="form-control " name="pasbaru1" required >
                      </div>    
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                      <button onclick="document.getElementById('fganpas').style.display='none'" type="submit" style="width: 100%;height:30px;margin-top: 0px;margin-bottom: 0px;border-radius: 4px;background-color: orange">PROSES</button>
                </div>  
              </form>

            </div>  <!-- modal body -->
          </div>
        </div>
      <!-- End Form nmalat-->  
    </div> <!--container fluid-->  
</div> <!-- main-->

