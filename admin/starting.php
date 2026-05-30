<!DOCTYPE html>
<html lang="id">
<?php 
 include 'cekmasuk.php';
 include 'config.php';
 $connect=opendtcek();
 setlocale(LC_MONETARY , "ID");
 ?>	
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="img/keranjang.png">
	<title><?=$_SESSION['nm_toko']?></title>
	<link rel="stylesheet" type="text/css" href="../assets/css/w3.css">  
	<link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="../assets/css/blue-themes.css">
	<link rel="stylesheet" href="../assets/css/alertyaz.min.css">
	<!-- /<link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Text&display=swap" rel="stylesheet"> -->
	<script src="../assets/js/alertyaz.js"></script>
	<script type="text/javascript" src="../assets/js/jquery-3.3.1.min.js"></script> 
    <script type="text/javascript" src="../assets/js/jquery.mask.min.js"></script> 
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
</head>
<style>
	body,h2,h3,h4,h5,h6 {font-family: Helvetica, Arial}
  .hrf_times {font-family: "Times New Roman", Times , serif;}
	body, html {
     /*height: 100%;*/
     line-height: 1.8;
     /*font-size: 12px;*/
     margin-top: 25px; 
	}
  .a {padding: 4px;padding-left:20px;}

	.hrf_arial {font-family: Helvetica, Arial;}

  @font-face {
    font-family: "3 of 9 barcode";
    src: url('3of9_new.TTF');
  }
 
  .hrf_barcode {
    font-family: 'Libre Barcode 39 Text', cursive;
  }
  @media only screen and (max-width: 992px) {
    .hrf_arial {font-size:12pt;}
    .hrf_res {font-size:12pt;}
    .hrf_res2 {font-size:12pt;}
    .hrf_res3 {font-size:12pt;}
  }
  @media only screen and (min-width: 992px) {
    .hrf_arial {font-size:10pt;}
    .hrf_res {font-size:8pt;}
    .hrf_res2 {font-size:12px;}
    .hrf_res3 {font-size:14px;}
  }
</style>
<script>
  function carinews(kd_toko){      
    $.ajax({
      url: 'starting_cari.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {kd_toko:kd_toko}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        $("#viewnews").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  function cinfopiut(page_number, search){      
    $.ajax({
      url: 'starting_cari_piut.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword: $("#keypiut").val(), page: page_number, search: search}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        $("#viewinfopiut").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  function cinfohut(page_number, search){      
    $.ajax({
      url: 'starting_cari_hut.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword: $("#keyhut").val(), page: page_number, search: search}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        $("#viewinfohut").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  function cinfodel(page_number, search){      
    $.ajax({
      url: 'starting_cari_del.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword: $("#keydel_l").val(), page: page_number, search: search}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        $("#viewinfodel").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  function cari_log(id){      
    $.ajax({
      url: 'starting_cari_log.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword: id}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        $("#viewcaridel").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  function settgl(dval){      
    $.ajax({
      url: 'starting_settgl.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword: dval}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
        $("#viewsettgl").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  function upproses(){      
    $.ajax({
      url: 'starting_upproses.php', 
      type: 'POST', 
      data: {}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
     
        $("#viewsettgl").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { 
        alert(xhr.responseText); 
      }
    });
  }
  function cektokos(){      
    $.ajax({
      url: 'cektokos.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword: $("#cekuser").val()}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        $("#viewsettgl").html(response.hasil);
       
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  function konfir_del_n(id){      
    $.ajax({
      url: 'starting_del_log.php', // File tujuan
      type: 'POST', // Tentukan type nya POST atau GET
      data: {keyword: id}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        $("#viewsettgl").html(response.hasil);
       
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
        alert(xhr.responseText); // munculkan alert
      }
    });
  }
  function cekpil(id){      
    $.ajax({
      url: 'starting_log_pilih.php', 
      type: 'POST', 
      data: {keyword:id}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        $("#viewsettgl").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { 
        alert(xhr.responseText); 
      }
    });
  }
  function fak_jual(id){      
    $.ajax({
      url: 'starting_fakjual.php', 
      type: 'POST', 
      data: {keyword:id}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response){ 
        $("#viewfaktur").html(response.hasil);
      },
      error: function (xhr, ajaxOptions, thrownError) { 
        alert(xhr.responseText); 
      }
    });
  }
  carinews();
</script>

<!-- <body oncontextmenu="return false;"> -->
<body>  

  <div id="loader" style="display:none;"></div>
  <!--Menu top  -->
  <input type="hidden" id="cekuser" value="<?=$_SESSION['nm_user']?>">
  <div class="w3-top" id="menutop1"> 
    <div class=" w3-white w3-card-4 yz-theme-d1" id="myNavbar">
      <a href="javascript:void(0)" id="bukamenu" onclick="openmenu()" class="w3-bar-item w3-button w3-wide" style="font-size: 14px;color:yellow;text-shadow: 2px 3px 5px black;"><img src="img/menu.png" class="w3-image" alt="Toko Retail" style="height: 25px">&nbsp;<b>MENU</b></a>
            
      <!-- on large screen -->
      <a href="#" id="tmb-info" class="w3-text-white w3-hover-shadow w3-bar-item w3-hide-small w3-hide-medium" style="margin-left:65% ">
        <i class="fa fa-bell-o w3-text-white"></i>
        <span id="newsbadgel" class="w3-badge w3-tiny" style="position: absolute;top: 0px;background-color: red;  color: white;"></span>
      </a>  

      <!-- on small screen -->  
      <a href="#" id="tmb-info1" class="w3-text-white w3-hover-shadow w3-bar-item w3-hide-large " style="margin-left:15%; ">
        <i class="fa fa-bell-o"></i>&nbsp;
        <span id="newsbadges" class="w3-badge w3-tiny" style="position: absolute;top: 0px;background-color: red;  color: white;"></span>
      </a>  

      <div class="row w3-container w3-card-4" id="info"  style="z-index: 1000;display: none;position: absolute;top:45px;right:0;overflow: auto;max-height: 400px;max-width:500px;
        background-color: rgba(0, 0, 0, 0.9);border-radius: 10px;border-style: ridge;border-color:white;border-width: 1px">
        <div class="col-sm">
          <div class="hrf_res2" id="viewnews" style="overflow: auto;"></div>  
        </div>
      </div>

      <script>
        $(document).ready(function(){
          $("#tmb-info").click(function(){
            $("#info").slideToggle("fast");
            $("#listuserl").slideUp("fast");
            $("#listuser").slideUp("fast");
            carinews();
          });  
          $("#tmb-info1").click(function(){
            $("#info").slideToggle("fast");
            $("#listuserl").slideUp("fast");
            $("#listuser").slideUp("fast");
            carinews();
          });  
        });
      </script>

      <a id="user_on" href="javascript:void(0);" class="w3-bar-item w3-button w3-right w3-text-white w3-hide-small yz-theme-l2" style="font-size: 14px">
        <?php echo $bag.' '.$_SESSION['nm_user']?>&nbsp;&nbsp;
        <img class="rounded-circle" style="max-width:26px;border:2px solid white" src="img/<?=$_SESSION['foto']; ?>" alt="">
      </a>  
      <div id="listuserl" class="w3-container w3-card-4 w3-text-white" style="display:none;border-radius: 6px;background-color: rgba(0, 0, 0,0.8);position:absolute;right:0;z-index: 1000;border-radius: 7px;border-style: ridge;border-color:white;border-width: 1px">
        <p><i class="fa fa-user w3-margin-top"></i>&nbsp;<?=$_SESSION['nm_user']?></p>
        <p style="margin-top: -10px"><i class="fa fa-legal"></i> <?=$bag?>&nbsp;</p>
        <p style="margin-top: -10px"><i class="fa fa-desktop"></i> <?=$_SESSION['id_toko']
        ?>&nbsp;</p>
        <hr class="w3-yellow">
        <div style="display:table-cell;">
           <img class="rounded-circle" style="margin-left:5%;margin-right: 15%; max-width:100px;border:2px solid white;" src="img/<?=$_SESSION['foto']; ?>" alt="">
        </div>
        <hr class="w3-yellow">
        <a href="f_pasuser2.php" style="color: white"><i class="fa fa-database w3-text-yellow"></i> &nbsp;User Admin</a>
        <br>
        <a href="../index.php" style="color: white"><i class="fa fa-undo w3-text-green"></i> &nbsp;Log Out</a>
      </div>
      <script>
        $(document).ready(function(){
          $("#user_on").click(function(){
            $("#listuserl").slideToggle("fast");
            $("#info").slideUp("fast");
          });
          // $("#user_on").blur(function(){
          //   $("#listuserl").slideUp("fast");
          // });  
          // $("#listuserl").mouseleave(function(){
          //   $("#listuserl").slideUp("fast");
          // });
          
        });
      </script>

      <!-- on small sreen -->
      <a href="javascript:void(0);" class="w3-bar-item w3-button w3-right w3-text-white w3-orange w3-hide-medium w3-hide-large" id="user_on_sm" style="font-size: 14px"><img class="rounded-circle" style="width:30px;border:2px solid white" src="img/<?=$_SESSION['foto']; ?>" alt=""></a>  

      <div id="listuser" class="w3-hide-medium w3-hide-large w3-padding w3-card-4 w3-right w3-text-black" style="display:none;border-radius: 6px;background-color: rgba(0, 0, 0,0.8);position:absolute;right:0;z-index: 1000;border-radius: 7px;border-style: ridge;border-color:white;border-width: 1px">
        <p class="w3-text-white"><i class="fa fa-user "></i>&nbsp; <?=$_SESSION['nm_user']?></p>
        <p class=" w3-text-white" style="margin-top: -10px"><i class="fa fa-legal"></i> <?=$bag?>&nbsp;</p>
        <p class=" w3-text-white" style="margin-top: -10px"><i class="fa fa-desktop"></i> <?=$_SESSION['id_toko']?></p>
        <hr class="w3-yellow">
        <div style="display:table-cell;">
           <img class="rounded-circle" style="margin-left:5%; max-width:100px;border:2px solid white;" src="img/<?=$_SESSION['foto']; ?>" alt="">
        </div>
        <hr class="w3-yellow">
        <a href="f_pasuser2.php" style="color: white"><i class="fa fa-database w3-text-yellow"></i> &nbsp;User Admin</a>
        <br>
        <a href="../index.php" style="color: white"><i class="fa fa-undo w3-text-green"></i> &nbsp;Log Out</a>
      </div>
      <script>
        $(document).ready(function(){
          $("#user_on_sm").click(function(){
            $("#listuser").slideToggle("fast");
            $("#info").slideUp("fast");
          });
          $("#user_on_sm").blur(function(){
            $("#listuser").slideUp("fast");
          });
          $("#listuser").mouseleave(function(){
            $("#listuser").slideUp("fast");
          });
          
        });
      </script>
    </div>
  </div> 

  <!-- Side bar -->
  <?php if($bag=="Administrator"){ ?>
    <nav class="hrf_arial w3-sidebar w3-bar-block w3-wide2 w3-card-4 w3-border" style="width:0;background-color: rgba(0, 0, 0,0.8);margin-top: 0px;z-index: 1034" id="mySidebar" >
      <a  href="dasbor.php" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn1" style="text-shadow: 2px 3px 5px black;margin-top: 10px"><img src="img/home.png" class="w3-image" alt="Retail home" style="box-shadow:0px 0px 5px white; "> &nbsp; Dasboard
      </a>
      
      <a  href="javascript:void(0)" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn2" style="text-shadow: 2px 3px 5px black;margin-top: 10px"><img src="img/master_data.png" class="w3-image" alt="Retail data" style="box-shadow:0px 0px 5px white "> &nbsp; Master Data<i class=" w3-right fa fa-caret-down" style="margin-top: 10px"></i>
      </a>
        <div id="listmaster" class="w3-padding-large w3-text-white" style="display: none;text-shadow: 2px 3px 5px black">
          <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border" onclick="document.getElementById('form-tgl').style.display='block'" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Set Tanggal</a>
          <a href="m_kemas.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Kemasan</a>
          <a href="m_sup.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Supplier</a>
          <a href="m_pel.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Pelanggan</a>
          <a href="m_member.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Member</a>
          <a href="m_toko.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Toko</a>
          <a href="f_masbrg.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Barang</a>
          <a href="m_paket.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Inisial Perpaket</a>
          <a href="m_bagian.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Bagian Penjualan</a>
          <a href="f_discount_promo.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Discount Promo</a>
          <a href="m_persediaan_bulan.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Persediaan Barang/Bulan</a>
          
          <a href="f_pasuser2.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>User</a>
        </div>  
        <script>
          $(document).ready(function(){
            $("#myBtn2").click(function(){
              $("#listmaster").slideToggle();
              $("#listtran").slideUp();
              $("#listcetak").slideUp();
              $("#maintenance").slideUp();
            });
          });
        </script>

      <a  href="javascript:void(0)" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn3" style="text-shadow: 2px 3px 5px black"><img src="img/master_brg.png" class="w3-image" alt="Retail Barang" style="box-shadow: 0px 0px 5px white"> &nbsp; Transaksi<i class=" w3-right fa fa-caret-down" style="margin-top: 10px"></i>
      </a>  
        <div id="listtran" class="w3-padding-large w3-text-white" style="display: none;text-shadow: 2px 3px 5px black">
          <a href="f_kas.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px "><i class="fa fa-bullseye">&nbsp;</i>Kas Kasir</a>
          <a href="f_beli.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Pembelian</a>
          <a href="f_jual.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Penjualan</a>
          <a href="f_hutangbayar.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Hutang</a>
          <a href="f_piutangbayar.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Piutang</a>
          <a href="f_stokbrg.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Barang</a>
          <a href="f_mutgudang.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Mutasi Barang</a>
          <a href="f_returbeli.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Retur Pembelian</a>
          <a href="f_returjual.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Retur Penjualan</a>
          <a href="f_biaya.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Biaya Operasional</a>
          <a href="f_expdate.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Cek Expired Date</a>
        </div>  
        <script>
          $(document).ready(function(){
            $("#myBtn3").click(function(){
              $("#listtran").slideToggle();
              $("#listmaster").slideUp();
              $("#listcetak").slideUp();
              $("#maintenance").slideUp();
            });
          });
        </script>

      <a href="javascript:void(0)" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn8" style="text-shadow: 2px 3px 5px black" ><img src="img/cetak_brg.png" class="w3-image" alt="Retail cetak" style="box-shadow: 0px 0px 5px white"> &nbsp; Report &nbsp;<i class=" w3-right fa fa-caret-down" style="margin-top: 10px"></i>
      </a>  
      <div id="listcetak" class="w3-padding-large w3-text-white" style="display: none;text-shadow: 2px 3px 5px black">
          <a href="#" onclick="closemenu();document.getElementById('formcetbeli').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Pembelian</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetjual').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Penjualan</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetjual_bag').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Bagian Penjualan</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetlaba').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Laba Penjualan</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetvoucher').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Discount Voucher</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetstok').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Barang</a>
          <a href="#" onclick="closemenu();document.getElementById('formcethutang').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Hutang Supplier</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetpiutang').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Piutang Pelanggan</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetmutasi').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Mutasi Barang</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetmutasio').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Mutasi Online</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetstokop').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Opname</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetreturbeli').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Retur Pembelian</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetretur').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Retur Penjualan</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetrekap').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Rekapitulasi</a>
          <a href="#" onclick="closemenu();document.getElementById('formcetjualmember').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Transaksi Member</a>
          <a href="#" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px" onclick="closemenu();document.getElementById('form-piut').style.display='block'">
            <i class="fa fa-bullseye">&nbsp;</i>Bayar Piutang</a>
          <a href="#" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px" onclick="closemenu();document.getElementById('form-hut').style.display='block'">
            <i class="fa fa-bullseye">&nbsp;</i>Bayar Hutang</a>
          <a href="#" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px" onclick="closemenu();document.getElementById('form-labamsk').style.display='block'">
            <i class="fa fa-bullseye">&nbsp;</i>Laba Piutang</a>
      </div>  
      <script>
        $(document).ready(function(){
          $("#myBtn8").click(function(){
            $("#listcetak").slideToggle();
            $("#listmaster").slideUp();
            $("#listtran").slideUp();
            $("#maintenance").slideUp();
          });
        });
      </script>

      <a id="myBtn9" href="javascript:void(0)" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn7" style="text-shadow: 2px 3px 5px black" ><img src="img/backup.png" class="w3-image" alt="Retail logout" style="box-shadow: 0px 0px 5px white"> &nbsp; Maintenance<i class=" w3-right fa fa-caret-down" style="margin-top: 10px"></i>
      </a>    
      <div id="maintenance" class="w3-padding-large w3-text-white" style="display: none;text-shadow: 2px 3px 5px black">
        <a href="f_backup.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Bakcup Data</a>
        <a href="f_stokbrg2.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Compare Harga</a>
        <a href="f_stokopname.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Opname</a>
        <a href="f_stokaset.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Cek Asset Barang</a>
        <a href="f_cekdatafile.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa  fa-bullseye">&nbsp;</i>Cek Data</a>
        <a href="f_setbag.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Set Bagian</a>
        <a href="#" onclick="document.getElementById('formsetprinter').style.display='block';upproses();closemenu();fak_jual();" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Setting Proses Data</a>
        <a href="#" onclick=";document.getElementById('formhapusjual').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye" >&nbsp;</i>Hapus Penjualan</a>
        <a href="m_history_notahapus.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>History Hapus Nota</a>
        <a href="f_cetbarcode.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Cetak Barcode</a>
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px" onclick="document.getElementById('fpilprint2').style.display='block';closemenu()"><i class="fa fa-bullseye">&nbsp;</i>Pilih Printer</a>
      </div>  
        <script>
          $(document).ready(function(){
            $("#myBtn9").click(function(){
              $("#maintenance").slideToggle();
              $("#listmaster").slideUp();
              $("#listtran").slideUp();
              $("#listcetak").slideUp();
            });
            // $("#listtran").mouseleave(function(){
            //   $("#listtran").slideToggle("fast");
            // });
            //   $("#myBtn2").click(function(){
            //   $("#listmaster").slideToggle();
            // });
          });
        </script> 
      <a href="../index.php" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn7" style="text-shadow: 2px 3px 5px black" ><img src="img/logout.png" class="w3-image" alt="Retail logout" style="box-shadow: 0px 0px 5px white"> &nbsp; Logout
      </a>  
      <script>
        $(document).ready(function(){
          $("#btnclose").click(function(){
            $("#listtran").slideUp();
            $("#listmaster").slideUp();
            $("#listcetak").slideUp();
            $("#maintenance").slideUp();
          });
          $("#bukamenu").click(function(){
            $("#listtran").slideUp();
            $("#listmaster").slideUp();
            $("#listcetak").slideUp();
          });
        });
      </script>
      <br><br>
    </nav>      
    <!--Menu utk operator  -->
  <?php }else{ ?>
    <nav class="hrf_arial w3-sidebar w3-bar-block w3-wide2 w3-card-4 w3-border" style="width:0;background-color: rgba(0, 0, 0,0.8);margin-top: 0px;z-index: 1034" id="mySidebar" >
        
        <a  href="dasbor.php" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn1" style="text-shadow: 2px 3px 5px black;margin-top: 50px"><img src="img/home.png" class="w3-image" alt="Retail home" style="box-shadow:0px 0px 5px white; "> &nbsp; Beranda
        </a>
        <br>
        <a  href="javascript:void(0)" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn2" style="text-shadow: 2px 3px 5px black"><img src="img/master_data.png" class="w3-image" alt="Retail data" style="box-shadow:0px 0px 5px white "> &nbsp; Master Data<i class=" w3-right fa fa-caret-down" style="margin-top: 10px"></i>
        </a>
          <div id="listmaster" class="w3-padding-large w3-text-white" style="display: none;text-shadow: 2px 3px 5px black">
            <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border" onclick="closemenu();document.getElementById('form-tgl').style.display='block'" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Set Tanggal</a>
            <a href="m_kemas.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Kemasan</a>
            <a href="m_sup.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Supplier</a>
            <a href="m_pel.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Pelanggan</a>
            <a href="m_member.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Member</a>
            <!-- <a href="m_toko.php" class="w3-bar-item w3-button w3-border"><i class="fa fa-bullseye">&nbsp;</i>Toko</a> -->
            <a href="f_masbrg.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Barang</a>
            <a href="m_paket.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Inisial Perpaket</a>
            <a href="m_bagian.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Bagian Penjualan</a>
            <a href="f_discount_promo.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Discount Promo</a>
            <a href="m_persediaan_bulan.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Persediaan Barang/Bulan</a>
            
            <a href="f_pasuser2.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>User</a>
            <!-- <a href="f_stokopname.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Opname</a> -->
          </div>  
          <script>
            $(document).ready(function(){
              $("#myBtn2").click(function(){
                $("#listmaster").slideToggle();
                $("#listtran").slideUp();
                $("#listcetak").slideUp();
                $("#maintenance2").slideUp();
              });
            });
          </script>

        <a  href="javascript:void(0)" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn3" style="text-shadow: 2px 3px 5px black"><img src="img/master_brg.png" class="w3-image" alt="Retail Barang" style="box-shadow: 0px 0px 5px white"> &nbsp; Transaksi<i class=" w3-right fa fa-caret-down" style="margin-top: 10px"></i>
        </a>  

        <div id="listtran" class="w3-padding-large w3-text-white" style="display: none;text-shadow: 2px 3px 5px black">
            <a href="f_kas.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Kas Kasir</a>
            <a href="f_beli.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Pembelian</a>
            <a href="f_jual.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Penjualan</a>
            <a href="f_hutangbayar.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Hutang</a>
            <a href="f_piutangbayar.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Piutang</a>
            <a href="f_stokbrg.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Barang</a>
            <a href="f_mutgudang.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Mutasi Barang</a>
            <a href="f_returjual.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Retur Penjualan</a>
            <a href="f_returbeli.php" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Retur Pembelian</a>
            <a href="f_biaya.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Biaya Operasional</a>
            <a href="f_expdate.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Cek Expired Date</a>
        </div>  
        <script>
          $(document).ready(function(){
            $("#myBtn3").click(function(){
              $("#listtran").slideToggle();
              $("#listmaster").slideUp();
              $("#listcetak").slideUp();
              $("#maintenance2").slideUp();
            });
          });
        </script>

        <a href="javascript:void(0)" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn8" style="text-shadow: 2px 3px 5px black" ><img src="img/cetak_brg.png" class="w3-image" alt="Retail cetak" style="box-shadow: 0px 0px 5px white"> &nbsp; Report &nbsp;<i class=" w3-right fa fa-caret-down" style="margin-top: 10px"></i>
        </a>  

        <div id="listcetak" class="w3-padding-large w3-text-white" style="display: none;text-shadow: 2px 3px 5px black">
            <a href="#" onclick="closemenu();document.getElementById('formcetbeli').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Pembelian</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetjual').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Penjualan</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetjual_bag').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Bagian Penjualan</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetvoucher').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Discount Voucher</a>     
            <a href="#" onclick="closemenu();document.getElementById('formcetstok').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Barang</a>
            <a href="#" onclick="closemenu();document.getElementById('formcethutang').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Hutang Supplier</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetpiutang').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Piutang Pelanggan</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetmutasi').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Mutasi Barang</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetmutasio').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Mutasi Online</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetstokop').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Opname</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetretur').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Retur Penjualan</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetreturbeli').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Retur Pembelian</a>
            <a href="#" onclick="closemenu();document.getElementById('formcetjualmember').style.display='block'" class="w3-bar-item w3-button w3-border" style="padding:4px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Transaksi Member</a>
            <a href="#" class="w3-bar-item w3-button w3-border" onclick="closemenu();document.getElementById('form-piut').style.display='block'" style="padding:4px;padding-left:20px">
              <i class="fa fa-bullseye">&nbsp;</i>Bayar Piutang</a>
            <a href="#" class="w3-bar-item w3-button w3-border" onclick="closemenu();document.getElementById('form-hut').style.display='block'" style="padding:4px;padding-left:20px">
              <i class="fa fa-bullseye">&nbsp;</i>Bayar Hutang</a>
          </div>  
          <script>
            $(document).ready(function(){
              $("#myBtn8").click(function(){
                $("#listcetak").slideToggle();
                $("#listmaster").slideUp();
                $("#listtran").slideUp();
                $("#maintenance2").slideUp();
              });
              
            });
          </script>
        
        <a id="myBtn10" href="javascript:void(0)" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn7" style="text-shadow: 2px 3px 5px black" ><img src="img/backup.png" class="w3-image" alt="Retail logout" style="box-shadow: 0px 0px 5px white"> &nbsp; Maintenance<i class=" w3-right fa fa-caret-down" style="margin-top: 10px"></i>
        </a>    

        <div id="maintenance2" class="w3-padding-large w3-text-white" style="display: none;text-shadow: 2px 3px 5px black">
          <a href="f_backup.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Bakcup Data</a>
          <a href="f_stokopname.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Stok Opname</a>
          <a href="f_cekdatafile.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa  fa-bullseye">&nbsp;</i>Cek Data</a>
          <a href="f_cetbarcode.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Cetak Barcode</a>
          <a href="f_setbag.php" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px"><i class="fa fa-bullseye">&nbsp;</i>Set Bagian</a>
          <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border" style="padding:2px;padding-left:20px" onclick="document.getElementById('fpilprint2').style.display='block';closemenu()"><i class="fa fa-bullseye">&nbsp;</i>Pilih Printer</a>
        </div>  
        <script>
          $(document).ready(function(){
            $("#myBtn10").click(function(){
              $("#maintenance2").slideToggle();
              $("#listmaster").slideUp();
              $("#listtran").slideUp();
              $("#listcetak").slideUp();
            });
          });
        </script> 
        
        <a href="../index.php" class=" w3-bar-item w3-left-align w3-text-white w3-hover-shadow" id="myBtn7" style="text-shadow: 2px 3px 5px black" ><img src="img/logout.png" class="w3-image" alt="Retail logout" style="box-shadow: 0px 0px 5px white"> &nbsp; Logout
        </a>  
        <script>
          $(document).ready(function(){
            $("#btnclose").click(function(){
              $("#listtran").slideUp();
              $("#listmaster").slideUp();
              $("#listcetak").slideUp();
            });
            $("#bukamenu").click(function(){
              $("#listtran").slideUp();
              $("#listmaster").slideUp();
              $("#listcetak").slideUp();
            });
          });
        </script>
        <br><br>
    </nav>                                
  <?php } ?>                              
<!-- end side bar -->

  <!-- FORM UNTUK CETAK LAPORAN -->
    <!-- Form cetak Mutasi online-->
    <div id="formcetmutasio" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Mutasi Online
          <span onclick="document.getElementById('formcetmutasio').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_mutasi_ol.php" method="POST" target="_blank">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="tglol1" class="col-sm-5 col-form-label">Tanggal</label>
                  <div class="col-sm-4">
                    <input type="date" id="tglol1" name="tglol1" placeholder="Tanggal awal" required="">
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="tglol2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                  <div class="col-sm-4">
                    <input type="date" id="tglol2" name="tglol2" placeholder="Tanggal akhir" required="">
                  </div>   
                </div>

                <div class="form-group row">
                  <div class="col-sm-12">
                    <p>Pilih list cetak mutasi:</p>
                      <!-- pilih semua -->
                      <input type="radio" id="pil_allmutol" name="pilihmutol" value="alldata" checked="">
                      <label for="pil_allmutol" style="cursor: pointer">Semua Data</label><br>
                      
                      <!-- pilih toko -->
                      <input type="radio" id="pil_tokool" name="pilihmutol" value="toko" onclick="document.getElementById('kd_tokomutol').value='';document.getElementById('kd_tokomutol').value=''">
                      <label for="pil_tokool" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                      <!-- box pil kategori -->
                      <div id="listmutasiol" style="display: none;position: relative;z-index: 1;">
                        <input type="text" id="nm_tokomutol" onkeyup=" carmutasiol()" name="nm_tokomutol" placeholder="ketik id toko" style="font-size: 9pt" class="form-control">
                        <input type="hidden" id="kd_tokomutol" name="kd_tokomutol" >
                        <div id="tabmutasiol" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                          <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                            <tr align="middle" class="yz-theme-l3">
                              <th>NAMA TOKO</th>
                              <!-- <th>OPSI</th> -->
                            </tr>
                            <?php 
                            $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                            while ($datakat = mysqli_fetch_array($sql2)){
                            ?>
                            <tr>
                              <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokomutol').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokomutol').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                            </tr>  
                            <?php   
                            }
                            unset($datakat,$sql2);
                            ?>
                          </table>
                        </div>  <!-- tabbrand -->
                      </div> <!--listbrand-->
                      <script>
                        $(document).ready(function(){
                          $("#pil_tokool").click(function(){
                            $("#listmutasiol").slideToggle("fast,swing");
                          });
                          $("#pil_allmutol").click(function(){
                            $("#listmutasiol").slideUp("fast,swing");
                          });
                          
                        });
                        function carmutasiol() {
                        var input, filter, table, tr, td, i, txtValue;
                        input = document.getElementById("nm_tokomutol");
                        filter = input.value.toUpperCase();
                        table = document.getElementById("tabmutasiol");
                        tr = table.getElementsByTagName("tr");
                        for (i = 0; i < tr.length; i++) {
                          td = tr[i].getElementsByTagName("td")[0];
                          if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                              tr[i].style.display = "";
                            } else {
                              tr[i].style.display = "none";
                            }
                          }       
                        }
                      }
                      </script>    
                      <br>
                      <!-- end pilih supplier -->
                  </div>                   
                </div>

              </div>
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>         
        </div>
      </div>
    </div>
    <!-- End Form cetak mutasi online-->
    
    <!-- Form cetak stok opname-->
    <div id="formcetstokop" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Stok Opname
          <span onclick="document.getElementById('formcetstokop').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_stok_op.php" method="POST" target="_blank">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="tglop1" class="col-sm-5 col-form-label">Tanggal</label>
                  <div class="col-sm-4">
                    <input type="date" id="tglop1" name="tglop1" placeholder="Tanggal awal" required="">
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="tglop2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                  <div class="col-sm-4">
                    <input type="date" id="tglop2" name="tglop2" placeholder="Tanggal akhir" required="">
                  </div>   
                </div>

                <div class="form-group row" style="margin-top: -10px">
                  <label for="cuser" class="col-sm-5 col-form-label">User</label>
                  <div class="col-sm">
                    <select id="cuser" name="cuser" class="form-control">
                      <option selected>SEMUA</option>
                      <?php 
                      $idtoko=$_SESSION['id_toko'];
                      $vc=mysqli_query($connect,"SELECT nm_user FROM pemakai WHERE kd_toko='$idtoko' and id_user<>1");
                      while($fcs=mysqli_fetch_assoc($vc)){ ?>
                        <option value="<?=$fcs['nm_user']?>"><?=$fcs['nm_user']?></option> <?php
                      } ?>
                    </select> 
                  </div>                   
                </div>

                <div class="form-group row">
                  <div class="col-sm-12">
                    <p>Pilih list cetak mutasi:</p>
                      <!-- pilih semua -->
                      <input type="radio" id="pil_allstokop" name="pilihstokop" value="alldata" checked="">
                      <label for="pil_allstokop" style="cursor: pointer">Semua Data</label><br>
                      
                      <!-- pilih toko -->
                      <input type="radio" id="pil_stokop" name="pilihstokop" value="toko" onclick="document.getElementById('kd_tokostokop').value=''">
                      <label for="pil_stokop" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                      <!-- box pil kategori -->
                      <div id="liststokop" style="display: none;position: relative;z-index: 1;">
                        <input type="text" id="nm_tokostokop" onkeyup="carstokop()" name="nm_tokostokop" placeholder="ketik id toko" style="font-size: 9pt" class="form-control">
                        <input type="hidden" id="kd_tokostokop" name="kd_tokostokop" >
                        <div id="tabstokop" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                          <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                            <tr align="middle" class="yz-theme-l3">
                              <th>NAMA TOKO</th>
                              <!-- <th>OPSI</th> -->
                            </tr>
                            <?php 
                            $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                            while ($datakat = mysqli_fetch_array($sql2)){
                            ?>
                            <tr>
                              <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokostokop').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokostokop').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                            </tr>  
                            <?php   
                            }
                            unset($datakat,$sql2);
                            ?>
                          </table>
                        </div>  <!-- tabbrand -->
                      </div> <!--listbrand-->
                      <script>
                        $(document).ready(function(){
                          $("#pil_stokop").click(function(){
                            $("#liststokop").slideToggle("fast,swing");
                          });
                          $("#pil_allstokop").click(function(){
                            $("#liststokop").slideUp("fast,swing");
                          });
                          
                        });
                        function carstokop() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_tokostokop");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabstokop");
                          tr = table.getElementsByTagName("tr");
                          for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("td")[0];
                            if (td) {
                              txtValue = td.textContent || td.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                              } else {
                                tr[i].style.display = "none";
                              }
                            }       
                          }
                        }
                      </script>    
                      <br>
                      <!-- end pilih supplier -->
                  </div>                   
                </div>

              </div>
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>         
        </div>
      </div>
    </div>
    <!-- End Form cetak stok opname-->
    
    <!-- Form cetak Rekapitulasi-->
    <div id="formcetrekap" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Laporan Rekapitulasi
          <span onclick="document.getElementById('formcetrekap').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_rekap.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="blnrek" class="col-sm-5 col-form-label">Bulan</label>
                    <div class="col-sm-7">
                      <?php
                      $x=explode("-",$_SESSION['tgl_set']);
                      $blnrekhi=$x[1];$thnrekhi=$x[0];
                      ?>
                      <select class="form-control" name="blnrek" id="blnrek" style="border: 1px solid black;font-size:12px ;height: 30px;" required="" >
                        <option value="01">JANUARI</option>
                        <option value="02">FEBRUARI</option>
                        <option value="03">MARET</option>
                        <option value="04">APRIL</option>
                        <option value="05">MEI</option>
                        <option value="06">JUNI</option>
                        <option value="07">JULI</option>
                        <option value="08">AGUSTUS</option>
                        <option value="09">SEPTEMBER</option>
                        <option value="10">OKTOBER</option>
                        <option value="11">NOPEMBER</option>
                        <option value="12">DESEMBER</option>
                      </select>
                    </div>
                    
                  </div>

                  <div class="form-group row" style="margin-top: -10px">
                    <label for="thnrek" class="col-sm-5 col-form-label">Tahun</label>
                    <div class="col-sm-7">
                      <input class="form_control" type="number" id="thnrek" name="thnrek" min="2023" max='2050' style="width:100%" value="<?=$thnrekhi?>">
                    </div>   
                  </div>
                  <script>
                      document.getElementById("blnrek").value="<?=$blnrekhi?>";
                      document.getElementById("thnrek").value="<?=$thnrekhi?>";
                  </script>
                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt="" type="submit"></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form cetak REKAPITULASI-->

    <!-- Form hapus penjualan-->
    <div id="formhapusjual" class="modal" style="padding-top:180px;margin-left:0px;background-color:rgba(1, 1, 1, 0.4) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px;max-height: 300px;overflow-x: auto;">
        <div style="background: linear-gradient(165deg, darkblue 0%,cyan 30%,white 100%);color:white;font-size: 15px;padding:4px;text-shadow: 1px 1px 1px black">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Hapus Data Penjualan
          <span onclick="document.getElementById('formhapusjual').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container">
          <form id="formhps" action="hapus_jual.php" method="POST">
            <div class="row mt-3">
              <div class="col-sm-7">
                <div class="form-group row">
                  <label for="tglhap1" class="col-sm-4 col-form-label">Tanggal</label>
                  <div class="col">
                    <input class="form-control" type="date" id="tglhap1" name="tglhap1" placeholder="Tanggal awal" required="">
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="tglhap2" class="col-sm-4 col-form-label">s/d Tanggal</label>
                  <div class="col">
                    <input class="form-control" type="date" id="tglhap2" name="tglhap2" placeholder="Tanggal akhir" required="">
                  </div>   
                </div>
              </div>    
                
              <div class="col-sm-5 ">
                <button class="mb-2 btn btn-success w3-card form-control" type="submit" onclick="document.getElementById('viewhapusjual').innerHTML='Tunggu sebentar..'+'<br>'"><i class="fa fa-trash-o"></i> Proses </button>
                <button class="btn btn-warning w3-card form-control" type="button" onclick="document.getElementById('formhapusjual').style.display='none'"> <i class="fa fa-undo"></i> Batal </button>
              </div>
            </div>
          </form>
          <script type="text/javascript">
            $(document).ready(function() {
              $('#formhps').submit(function() {
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#viewhapusjual').html(data);
                    }
                })
                return false;
              });
            })
          </script>      
          <!-- <script type="text/javascript">
            $(document).ready(function() {
              $('#formhps').submit(function() {
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function(e) {
                      if(e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                      }
                    },
                    success: function(response) {
                        $('#viewhapusjual').html(response.hasil);
                    },
                    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
                      alert(xhr.responseText); // munculkan alert
                    }
                    
                })
                return false;
              });
            })
          </script>  -->
          <div id="viewhapusjual"></div>
        </div>
      </div>
    </div>
    <!-- End Form hapus penjualan-->

    <!-- Form cetak nota pembelian-->
    <div id="formcetbeli" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div  class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Pembelian Barang
          <span onclick="document.getElementById('formcetbeli').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_beli.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="tgl11" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tgl11" name="tgl1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tgl21" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tgl21" name="tgl2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div>
                  <div class="form-group row">
                    <label for="pilih1" class="col-sm-5 col-form-label">Data List</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="pilih" id="pilih1" style="border: 1px solid black;font-size:12px ;height: 30px;width: 165px" required="" tabindex="4">
                            <option value="NOTA">PER NOTA</option>
                            <option value="ITEM">PER ITEM</option>
                          </select>
                    </div>   
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="pilih2" class="col-sm-5 col-form-label">Pembayaran</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="pilih2" id="pilih2" style="border: 1px solid black;font-size:12px ;height: 30px;width: 165px" required="" tabindex="4">
                            <option value="TUNAI">TUNAI</option>
                            <option value="TEMPO">TEMPO</option>
                            <option value="SEMUA">SEMUA</option>
                          </select>
                    </div>   
                  </div>

                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form cetak nota pembelian-->

    <!-- Form cetak nota penjualan-->
    <div id="formcetjual" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Penjualan Barang
          <span onclick="document.getElementById('formcetjual').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_jual.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="tgl12" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tgl12" name="tgl1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tgl22" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tgl22" name="tgl2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div>
                  <div class="form-group row">
                    <label for="pilih21" class="col-sm-5 col-form-label">Data List</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="pilih" id="pilih21" style="border: 1px solid black;font-size:12px ;height: 30px;width: 165px" required="" tabindex="4">
                            <option value="NOTA">PER NOTA</option>
                            <option value="ITEM">PER ITEM</option>
                          </select>
                    </div>   
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="pilih3" class="col-sm-5 col-form-label">Pembayaran</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="pilih3" id="pilih3" style="border: 1px solid black;font-size:12px ;height: 30px;width: 165px" required="" tabindex="4">
                            <option value="TUNAI">TUNAI</option>
                            <option value="TEMPO">TEMPO</option>
                            <option value="SEMUA">SEMUA</option>
                          </select>
                    </div>   
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="brand_jual_report" class="col-sm-5 col-form-label">Brand</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="kd_brand" id="brand_jual_report" style="border: 1px solid black;font-size:12px ;height: 30px;width: 165px" tabindex="4">
                        <option value="">SEMUA</option>
                        <option value="OMG">OMG</option>
                        <option value="EMINA">EMINA</option>
                        <option value="WARDAH">WARDAH</option>
                        <option value="MAKE OVER">MAKE OVER</option>
                        <option value="KAHF">KAHF</option>
                        <option value="SKINTIFIC MARINA">SKINTIFIC MARINA</option>
                        <option value="G2G">G2G</option>
                        <option value="HANASUI">HANASUI</option>
                        <option value="SLAVINA">SLAVINA</option>
                        <option value="SCARLET">SCARLET</option>
                        <option value="HADALABO">HADALABO</option>
                        <option value="IMPLORA">IMPLORA</option>
                        <option value="VIVA">VIVA</option>
                        <option value="GLOW & LOVELY">GLOW & LOVELY</option>
                        <option value="PONDS">PONDS</option>
                        <option value="GARNIER">GARNIER</option>
                        <option value="CUSSONS">CUSSONS</option>
                        <option value="NIVEA">NIVEA</option>
                        <option value="PIXY">PIXY</option>
                        <option value="MAKARIZO">MAKARIZO</option>
                        <option value="YOU">YOU</option>
                        <option value="NYU">NYU</option>
                        <option value="MIRANDA">MIRANDA</option>
                        <option value="DAZZLE ME">DAZZLE ME</option>
                        <option value="ANIMATE">ANIMATE</option>
                        <option value="MY BABY">MY BABY</option>
                        <option value="MOELL">MOELL</option>
                        <option value="NPURE">NPURE</option>
                        <option value="SCORA">SCORA</option>
                        <option value="FACETOLOGY">FACETOLOGY</option>
                        <option value="JHONSONS">JHONSONS</option>
                        <option value="EUREKA">EUREKA</option>
                        <option value="EVANGELINE">EVANGELINE</option>
                        <option value="VITALIS">VITALIS</option>
                        <option value="GATSBY">GATSBY</option>
                        <option value="PUCELLE">PUCELLE</option>
                        <option value="ANDO">ANDO</option>
                        <option value="PRO ATT">PRO ATT</option>
                        <option value="CARVIL">CARVIL</option>
                        <option value="BENING">BENING</option>
                        <option value="LOGO">LOGO</option>
                        <option value="NEW ERA">NEW ERA</option>
                        <option value="SPEED">SPEED</option>
                        <option value="ARMOD">ARMOD</option>
                        <option value="LUBRENA">LUBRENA</option>
                        <option value="VAUSTIN">VAUSTIN</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form cetak nota penjualan-->

    <!-- Form cetak transaksi member-->
    <div id="formcetjualmember" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Transaksi Member
          <span onclick="document.getElementById('formcetjualmember').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_jual_member.php" method="POST" target="_blank">
            <div class="row">
              <div class="col-sm-7">
                <div class="form-group row">
                  <label for="tglmember1" class="col-sm-5 col-form-label">Tanggal</label>
                  <div class="col-sm-7">
                    <input type="date" id="tglmember1" name="tgl1" placeholder="Tanggal awal" required="">
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="tglmember2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                  <div class="col-sm-7">
                    <input type="date" id="tglmember2" name="tgl2" placeholder="Tanggal akhir" required="">
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="memberreport" class="col-sm-5 col-form-label">Member</label>
                  <div class="col-sm-7">
                    <select class="form-control" name="kd_member" id="memberreport" style="border: 1px solid black;font-size:12px ;height: 30px;" tabindex="4">
                      <option value="">SEMUA MEMBER</option>
                      <?php
                      $qmember = mysqli_query($connect, "SELECT kd_member,nm_member FROM member ORDER BY nm_member ASC");
                      while($dmember = mysqli_fetch_assoc($qmember)){
                      ?>
                        <option value="<?=$dmember['kd_member']?>"><?=$dmember['nm_member']?></option>
                      <?php }
                      mysqli_free_result($qmember);unset($dmember);
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="include_poin_member" class="col-sm-5 col-form-label">Opsi Poin</label>
                  <div class="col-sm-7" style="padding-top: 6px">
                    <input type="checkbox" id="include_poin_member" name="include_poin" value="1" checked>
                    <label for="include_poin_member" style="cursor: pointer">Include poin pada cetak</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-5 ">
                <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End Form cetak transaksi member-->

    <!-- Form cetak nota bagian penjualan-->
    <div id="formcetjual_bag" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Bagian Penjualan Barang
          <span onclick="document.getElementById('formcetjual_bag').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_jual_bag.php" method="POST" target="_blank">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="tglbag1" class="col-sm-5 col-form-label">Tanggal</label>
                  <div class="col-sm-7">
                    <input type="date" id="tglbag1" name="tglbag1" placeholder="Tanggal awal" required="">
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="tglbag2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                  <div class="col-sm-7">
                    <input type="date" id="tglbag2" name="tglbag2" placeholder="Tanggal akhir" required="">
                  </div>   
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="pilihbag" class="col-sm-5 col-form-label">List Bagian</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pilihbag" id="pilihbag" style="border: 1px solid black;font-size:12px ;height: 30px;width: 165px" required="" tabindex="4">
                      <?php $qs=mysqli_query($connect,"SELECT * FROM bag_brg ORDER BY nm_bag");
                      while($da=mysqli_fetch_assoc($qs)){
                      ?>
                        <option value="<?=$da['no_urut']?>"><?=$da['nm_bag']?></option>
                      <?php }
                      mysqli_free_result($qs);unset($da); 
                      ?>    
                    </select>
                  </div>   
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="pilihtoko" class="col-sm-5 col-form-label">List Toko</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pilihtoko" id="pilihtoko" style="border: 1px solid black;font-size:12px ;height: 30px;width: 165px" required="" tabindex="4">
                      <?php $qs=mysqli_query($connect,"SELECT * FROM toko ORDER BY nm_toko");
                      while($da=mysqli_fetch_assoc($qs)) {
                      ?>
                        <option value="<?=$da['kd_toko']?>"><?=$da['nm_toko']?></option>
                      <?php }
                      mysqli_free_result($qs);unset($da); 
                      ?>
                      <option value="NONE">SEMUA</option>    
                    </select>
                  </div>   
                </div>
              </div>
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>       
        </div>
      </div>
    </div>
    <!-- End Form cetak nota bagian penjualan-->
    
    <!-- Form cetak nota laba-->
    <div id="formcetlaba" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Laporan Laba Penjualan
          <span onclick="document.getElementById('formcetlaba').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_laba.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="tgl13" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tgl13" name="tgl1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tgl23" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tgl23" name="tgl2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div>
                  <div class="form-group row">
                    <label for="pilih31" class="col-sm-5 col-form-label">Data List</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="pilih" id="pilih31" style="border: 1px solid black;font-size:12px ;height: 30px;width: 165px" required="" tabindex="4">
                            <option value="NOTA">PER NOTA</option>
                            <option value="ITEM">PER ITEM</option>
                          </select>
                    </div>   
                  </div>
                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form cetak laba-->
    
    <!-- Form cetak stok barang-->
    <div id="formcetstok" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Stok Barang
          <span id="brandclose" onclick="document.getElementById('formcetstok').style.display='none';document.getElementById('nm_brand1').value='';document.getElementById('kd_brand1').value='';document.getElementById('nm_kat1').value='';document.getElementById('kd_kat1').value=''" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_stok.php" method="POST" target="_blank">
            <div class="row">

              <div class="col-sm-6">
                <p>Pilih list cetak stok:</p>
                 <!-- pilih semua -->
                  <input type="radio" id="pil_alldata" name="pilih" value="alldata" checked>
                  <label for="pil_alldata" style="cursor: pointer">Semua Data</label><br>
                  
                  <!-- pilih Supplier -->
                  <input type="radio" id="pil_supplier" name="pilih" value="supplier" onclick="document.getElementById('kd_sup1').value='';document.getElementById('nm_sup1').value=''">
                  <label for="pil_supplier" style="cursor: pointer">Berdasar Supplier &nbsp;<i class="fa fa-caret-down"></i></label>
                  <!-- box pil kategori -->
                  <div id="listsupplier1" style="display: none;position: relative;z-index: 1;">
                    <input type="text" id="nm_sup1" onkeyup="carsup1()" name="nm_sup1" placeholder="ketik supplier">
                    <input type="hidden" id="kd_sup1" name="kd_sup1">
                    <div id="tabsup1" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                      <table class="table table-bordered table-sm table-hover" style="font-size:8pt;">
                        <tr align="middle" class="yz-theme-l3">
                          <th>SUPPLIER</th>
                          <!-- <th>OPSI</th> -->
                        </tr>
                        <?php 
                        $sql2 = mysqli_query($connect, "SELECT * from supplier ORDER BY nm_sup ASC ");
                        while ($datakat = mysqli_fetch_array($sql2)){
                        ?>
                        <tr>
                          <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_sup1').value='<?=mysqli_escape_string($connect,$datakat['kd_sup']) ?>';document.getElementById('nm_sup1').value='<?=mysqli_escape_string($connect,$datakat['nm_sup']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_sup']; ?></td>
                        </tr>  
                        <?php   
                        }
                        unset($datakat,$sql2);
                        ?>
                      </table>
                    </div>  <!-- tabbrand -->
                  </div> <!--listbrand-->
                  <script>
                    $(document).ready(function(){
                      $("#pil_supplier").click(function(){
                        $("#listsupplier1").slideToggle("fast");
                      });
                      $("#pil_alldata").click(function(){
                        $("#listsupplier1").slideUp("fast");
                      });
                      $("#pil_brand").click(function(){
                        $("#listsupplier1").slideUp("fast");
                      });
                      // $("#tabbrand").click(function(){
                      //   $("#listbrand").slideUp("fast");
                      // });
                    });
                    function carsup1() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("nm_sup1");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("tabsup1");
                    tr = table.getElementsByTagName("tr");
                    for (i = 0; i < tr.length; i++) {
                      td = tr[i].getElementsByTagName("td")[0];
                      if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                          tr[i].style.display = "";
                        } else {
                          tr[i].style.display = "none";
                        }
                      }       
                    }
                  }
                  </script>    
                  <br>
                  <!-- end pilih supplier -->

                  <!-- <label for="jmlstok">Range Jml Stok</label> -->
                  <input type="number" class='w3-margin-bottom' min="0" name="jml_stok" id="jml_stok" placeholder="Range jml stok" style="text-align: center" onchange="
                  if (this.value>0) {
                    document.getElementById('cek1').style.display='none';
                  } else {document.getElementById('cek1').style.display='block';}">
              </div>   
                
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-center"><img src="img/printer.png" alt=""></button>

                <div id="cek1" class="form-check w3-margin-top" style="font-size: 10pt">
                  <input class="form-check-input" type="checkbox" id="cektampil1" name="cektampil1" value="1" style="width: 18px;height: 18px">
                  <label class="form-check-label" for="cektampil1"> &nbsp; Tampilkan stok kosong</label>
                </div>

              </div>
            </div>
          </form>
                
        </div>
      </div>
      <script>
        $(document).ready(function(){
          $("#brandclose").click(function(){
            $("#listbrand").slideUp("fast");
            $("#listkategori1").slideUp("fast");
          });
        });
      </script>
    </div>
    <!-- End Form cetak stok barang-->

  <!-- Form cetak hutang-->
    <div id="formcethutang" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Hutang Supplier
          <span id="brandclose" onclick="document.getElementById('formcethutang').style.display='none';" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_hutang.php" method="POST" target="_blank">
            <div class="row">

              <div class="col-sm-6">
                <p>Pilih list cetak hutang:</p>
                 <!-- pilih semua -->
                  <input type="radio" id="pil_alldata2" name="pilih2" value="alldata" checked="">
                  <label for="pil_alldata2" style="cursor: pointer">Semua Data</label><br>
                  
                  <!-- pilih Supplier -->
                  <input type="radio" id="pil_supplier2" name="pilih2" value="supplier" onclick="document.getElementById('kd_sup2').value='';document.getElementById('nm_sup2').value=''">
                  <label for="pil_supplier2" style="cursor: pointer" id="pilsup">Berdasar Supplier &nbsp;<i class="fa fa-caret-down"></i></label>
                  <!-- box pil kategori -->
                  <div id="listsupplier2" style="display: none;position: relative;z-index: 1;">
                    <input type="text" id="nm_sup2" onkeyup="carsup2()" name="nm_sup2" placeholder="ketik supplier">
                    <input type="hidden" id="kd_sup2" name="kd_sup2">
                    <div id="tabsup2" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                      <table class="table table-bordered table-sm table-hover" style="font-size:8pt;">
                        <tr align="middle" class="yz-theme-l3">
                          <th>SUPPLIER</th>
                          <!-- <th>OPSI</th> -->
                        </tr>
                        <?php 
                        $sql2 = mysqli_query($connect, "SELECT * from supplier ORDER BY nm_sup ASC ");
                        while ($datakat = mysqli_fetch_array($sql2)){
                        ?>
                        <tr>
                          <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_sup2').value='<?=mysqli_escape_string($connect,$datakat['kd_sup']) ?>';document.getElementById('nm_sup2').value='<?=mysqli_escape_string($connect,$datakat['nm_sup']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_sup']; ?></td>
                        </tr>  
                        <?php   
                        }
                        unset($datakat,$sql2);
                        ?>
                      </table>
                    </div>  <!-- tabbrand -->
                  </div> <!--listbrand-->
                  <script>
                    $(document).ready(function(){
                      $("#pil_supplier2").click(function(){
                        $("#listsupplier2").slideToggle("slow");
                      });
                      // $("#pilsup").click(function(){
                      //   $("#listsupplier2").slideToggle("slow");
                      // });
                      
                    });
                    function carsup2() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("nm_sup2");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("tabsup2");
                    tr = table.getElementsByTagName("tr");
                    for (i = 0; i < tr.length; i++) {
                      td = tr[i].getElementsByTagName("td")[0];
                      if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                          tr[i].style.display = "";
                        } else {
                          tr[i].style.display = "none";
                        }
                      }       
                    }
                  }
                  </script>    
                  <br>
                  <!-- end pilih supplier -->
                  <label for="kethut" style="font-size: 11pt">Pilih Kondisi:</label>

                  <select name="kethut" id="kethut" class="form-control" style="font-size: 11pt">
                    <option value="belum">Belum Lunas</option>
                    <option value="lunas">Lunas</option>
                    <option value="semua">Semua</option>
                  </select>

              </div>                   
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-center"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>
                
        </div>
      </div>
      
    </div>
    <!-- End Form cetak hutang supplier-->

    <!-- Form cetak piutang pelanggan-->
    <div id="formcetpiutang" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Piutang Pelanggan
          <span id="brandclose" onclick="document.getElementById('formcetpiutang').style.display='none';" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_piutang.php" method="POST" target="_blank">
            <div class="row">

              <div class="col-sm-6">
                <p>Pilih list cetak piutang:</p>
                 <!-- pilih semua -->
                  <input type="radio" id="pil_alldata3" name="pilih3" value="alldata" checked="">
                  <label for="pil_alldata3" style="cursor: pointer">Semua Data</label><br>
                  
                  <!-- pilih pelanggan -->
                  <input type="radio" id="pil_pelanggan3" name="pilih3" value="pelanggan" onclick="document.getElementById('kd_pel3').value='';document.getElementById('nm_pel3').value=''">
                  <label for="pil_pelanggan3" style="cursor: pointer" id="pilpel">Berdasar Pelanggan &nbsp;<i class="fa fa-caret-down"></i></label>

                  <!-- box pil kategori -->
                  <div id="listpelanggan3" style="display: none;position: relative;z-index: 1;">
                    <input type="text" id="nm_pel3" onkeyup="carpel3()" name="nm_pel3" placeholder="ketik pelanggan" style="font-size: 10pt">
                    <input type="hidden" id="kd_pel3" name="kd_pel3">
                    <div id="tabpel3" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                      <table class="table table-bordered table-sm table-hover" style="font-size:8pt;">
                        <tr align="middle" class="yz-theme-l3">
                          <th>PELANGGAN</th>
                          <!-- <th>OPSI</th> -->
                        </tr>
                        <?php 
                        $sql2 = mysqli_query($connect, "SELECT * from pelanggan ORDER BY nm_pel ASC ");
                        while ($datakat = mysqli_fetch_array($sql2)){
                        ?>
                        <tr>
                          <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_pel3').value='<?=mysqli_escape_string($connect,$datakat['kd_pel']) ?>';document.getElementById('nm_pel3').value='<?=mysqli_escape_string($connect,$datakat['nm_pel']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_pel']; ?></td>
                        </tr>  
                        <?php   
                        }
                        unset($datakat,$sql2);
                        ?>
                      </table>
                    </div>  <!-- tabbrand -->
                  </div> <!--listbrand-->
                  <script>
                    $(document).ready(function(){
                      $("#pil_pelanggan3").click(function(){
                        $("#listpelanggan3").slideToggle("slow");
                      });
                      
                      
                    });
                    function carsup2() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("nm_pel3");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("tabpel3");
                    tr = table.getElementsByTagName("tr");
                    for (i = 0; i < tr.length; i++) {
                      td = tr[i].getElementsByTagName("td")[0];
                      if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                          tr[i].style.display = "";
                        } else {
                          tr[i].style.display = "none";
                        }
                      }       
                    }
                  }
                  </script>    
                  <br>
                  <!-- end pilih supplier -->
                  <label for="ketput" style="font-size: 11pt">Pilih Kondisi:</label>

                  <select name="ketput" id="ketput" class="form-control" style="font-size: 11pt">
                    <option value="belum">Belum Lunas</option>
                    <option value="lunas">Lunas</option>
                    <option value="semua">Semua</option>
                  </select>

              </div>                   
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-center"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>
                
        </div>
      </div>
      
    </div>
    <!-- End Form cetak piutang-->
    
    <!-- Form cetak mutasi barang-->
    <div id="formcetmutasi" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Mutasi Barang
          <span onclick="document.getElementById('formcetmutasi').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_mutasi.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="tglmut1" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglmut1" name="tglmut1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tglmut2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglmut2" name="tglmut2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12">
                      <p>Pilih list cetak mutasi:</p>
                       <!-- pilih semua -->
                        <input type="radio" id="pil_allmut" name="pilihmut" value="alldata" checked="">
                        <label for="pil_allmut" style="cursor: pointer">Semua Data</label><br>
                        
                        <!-- pilih pelanggan -->
                        <input type="radio" id="pil_toko" name="pilihmut" value="toko" onclick="document.getElementById('kd_tokomut').value='';document.getElementById('kd_tokomut').value=''">
                        <label for="pil_toko" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                        <!-- box pil kategori -->
                        <div id="listmutasi" style="display: none;position: relative;z-index: 1;">
                          <input type="text" id="nm_tokomut" onkeyup="carmutasi()" name="nm_tokomut" placeholder="ketik id toko" style="font-size: 9pt" class="form-control">
                          <input type="hidden" id="kd_tokomut" name="kd_tokomut" >
                          <div id="tabmutasi" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                            <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                              <tr align="middle" class="yz-theme-l3">
                                <th>NAMA TOKO</th>
                                <!-- <th>OPSI</th> -->
                              </tr>
                              <?php 
                              $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                              while ($datakat = mysqli_fetch_array($sql2)){
                              ?>
                              <tr>
                                <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokomut').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokomut').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                              </tr>  
                              <?php   
                              }
                              unset($datakat,$sql2);
                              ?>
                            </table>
                          </div>  <!-- tabbrand -->
                        </div> <!--listbrand-->
                        <script>
                          $(document).ready(function(){
                            $("#pil_toko").click(function(){
                              $("#listmutasi").slideToggle("fast,swing");
                            });
                            $("#pil_allmut").click(function(){
                              $("#listmutasi").slideUp("fast,swing");
                            });
                            
                          });
                          function carmutasi() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_tokomut");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabmutasi");
                          tr = table.getElementsByTagName("tr");
                          for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("td")[0];
                            if (td) {
                              txtValue = td.textContent || td.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                              } else {
                                tr[i].style.display = "none";
                              }
                            }       
                          }
                        }
                        </script>    
                        <br>
                        <!-- end pilih supplier -->
                    </div>                   
                  </div>

                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End Form cetak nota mutasi barang-->

    <!-- Form cetak retur beli barang-->
    <div id="formcetreturbeli" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Retur Pembelian Barang
          <span onclick="document.getElementById('formcetreturbeli').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_returbeli.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="tglretbel1" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglretbel1" name="tglretbel1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tglretbel2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglretbel2" name="tglretbel2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12">
                      <p>Pilih list cetak mutasi:</p>
                       <!-- pilih semua -->
                        <input type="radio" id="pil_allretbel" name="pilihretbel" value="alldata" checked="">
                        <label for="pil_allretbel" style="cursor: pointer">Semua Data</label><br>
                        
                        <!-- pilih pelanggan -->
                        <input type="radio" id="pil_tokoretbel" name="pilihretbel" value="toko" onclick="document.getElementById('kd_tokoretbel').value='';document.getElementById('nm_tokoretbel').value=''">
                        <label for="pil_tokoretbel" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                        <!-- box pil kategori -->
                        <div id="listretbel" style="display: none;position: relative;z-index: 1;">
                          <input type="text" id="nm_tokoretbel" onkeyup="carretbel()" name="nm_tokoretbel" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                          <input type="hidden" id="kd_tokoretbel" name="kd_tokoretbel" >
                          <div id="tabretbel" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                            <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                              <tr align="middle" class="yz-theme-l3">
                                <th>NAMA TOKO</th>
                                <!-- <th>OPSI</th> -->
                              </tr>
                              <?php 
                              $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                              while ($datakat = mysqli_fetch_array($sql2)){
                              ?>
                              <tr>
                                <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokoretbel').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokoretbel').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                              </tr>  
                              <?php   
                              }
                              unset($datakat,$sql2);
                              ?>
                            </table>
                          </div>  <!-- tabbrand -->
                        </div> <!--listbrand-->
                        <script>
                          $(document).ready(function(){
                            $("#pil_tokoretbel").click(function(){
                              $("#listretbel").slideToggle("fast,swing");
                            });
                            $("#pil_allretbel").click(function(){
                              $("#listretbel").slideUp("fast,swing");
                            });
                            
                          });
                          function carretbel() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_tokoretbel");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabretbel");
                          tr = table.getElementsByTagName("tr");
                          for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("td")[0];
                            if (td) {
                              txtValue = td.textContent || td.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                              } else {
                                tr[i].style.display = "none";
                              }
                            }       
                          }
                        }
                        </script>    
                        <br>
                        <!-- end pilih supplier -->
                    </div>                   
                  </div>

                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form retur barang-->

    <!-- Form cetak bonus-->
    <div id="formcetbonus" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Bonus Penjualan
          <span onclick="document.getElementById('formcetbonus').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_bonus.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="tglbonus1" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglbonus1" name="tglbonus1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tglbonus2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglbonus2" name="tglbonus2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12">
                      <p>Pilih list cetak :</p>
                       <!-- pilih semua -->
                        <input type="radio" id="pil_allbonus" name="pilihbonus" value="alldata" checked="">
                        <label for="pil_allbonus" style="cursor: pointer">Semua Data</label><br>
                        
                        <!-- pilih pelanggan -->
                        <input type="radio" id="pil_tokobonus" name="pilihbonus" value="toko" onclick="document.getElementById('kd_tokobonus').value='';document.getElementById('nm_tokobonus').value=''">
                        <label for="pil_tokobonus" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                        <!-- box pil -->
                        <div id="listbonus" style="display: none;position: relative;z-index: 1;">
                          <input type="text" id="nm_tokobonus" onkeyup="carbonus()" name="nm_tokobonus" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                          <input type="hidden" id="kd_tokobonus" name="kd_tokobonus" >
                          <div id="tabbonus" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                            <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                              <tr align="middle" class="yz-theme-l3">
                                <th>NAMA TOKO</th>
                                <!-- <th>OPSI</th> -->
                              </tr>
                              <?php 
                              $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                              while ($datakat = mysqli_fetch_array($sql2)){
                              ?>
                              <tr>
                                <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokobonus').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokobonus').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                              </tr>  
                              <?php   
                              }
                              unset($datakat,$sql2);
                              ?>
                            </table>
                          </div>  <!-- tabbonus -->
                        </div> <!--listbonus-->
                        <script>
                          $(document).ready(function(){
                            $("#pil_tokobonus").click(function(){
                              $("#listbonus").slideToggle("fast,swing");
                            });
                            $("#pil_allbonus").click(function(){
                              $("#listbonus").slideUp("fast,swing");
                            });
                            
                          });
                          function carbonus() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_tokobonus");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabbonus");
                          tr = table.getElementsByTagName("tr");
                          for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("td")[0];
                            if (td) {
                              txtValue = td.textContent || td.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                              } else {
                                tr[i].style.display = "none";
                              }
                            }       
                          }
                        }
                        </script>    
                        <br>
                        <!-- end pilih supplier -->
                    </div>                   
                  </div>

                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form bonus-->

    <!-- Form cetak VOUCHER-->
    <div id="formcetvoucher" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak voucher Penjualan
          <span onclick="document.getElementById('formcetvoucher').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_pilih_voucher.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="tglvoucher1" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglvoucher1" name="tglvoucher1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tglvoucher2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglvoucher2" name="tglvoucher2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12">
                      <p>Pilih list cetak :</p>
                       <!-- pilih semua -->
                        <input type="radio" id="pil_allvoucher" name="pilihvoucher" value="alldata" checked="">
                        <label for="pil_allvoucher" style="cursor: pointer">Semua Data</label><br>
                        
                        <!-- pilih pelanggan -->
                        <input type="radio" id="pil_tokovoucher" name="pilihvoucher" value="tokovoucher" onclick="document.getElementById('kd_tokovoucher').value='';document.getElementById('nm_tokovoucher').value=''">
                        <label for="pil_tokovoucher" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                        <!-- box pil -->
                        <div id="listvoucher" style="display: none;position: relative;z-index: 1;">
                          <input type="text" id="nm_tokovoucher" onkeyup="carvoucher()" name="nm_tokovoucher" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                          <input type="hidden" id="kd_tokovoucher" name="kd_tokovoucher" >
                          <div id="tabvoucher" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                            <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                              <tr align="middle" class="yz-theme-l3">
                                <th>NAMA TOKO</th>
                                <!-- <th>OPSI</th> -->
                              </tr>
                              <?php 
                              $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                              while ($datakat = mysqli_fetch_array($sql2)){
                              ?>
                              <tr>
                                <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokovoucher').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokovoucher').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                              </tr>  
                              <?php   
                              }
                              unset($datakat,$sql2);
                              ?>
                            </table>
                          </div>  <!-- tabbonus -->
                        </div> <!--listbonus-->
                        <script>
                          $(document).ready(function(){
                            $("#pil_tokovoucher").click(function(){
                              $("#listvoucher").slideToggle("fast,swing");
                            });
                            $("#pil_allvoucher").click(function(){
                              $("#listvoucher").slideUp("fast,swing");
                            });
                            
                          });
                          function carvoucher() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_tokovoucher");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabvoucher");
                          tr = table.getElementsByTagName("tr");
                          for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("td")[0];
                            if (td) {
                              txtValue = td.textContent || td.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                              } else {
                                tr[i].style.display = "none";
                              }
                            }       
                          }
                        }
                        </script>    
                        <br>
                        <!-- end pilih supplier -->
                    </div>                   
                  </div>

                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form VOUCER-->

    <!-- Form cetak retur-->
    <div id="formcetretur" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak voucher Penjualan
          <span onclick="document.getElementById('formcetretur').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_retur.php" method="POST" target="_blank">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="tglretur1" class="col-sm-5 col-form-label">Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglretur1" name="tglretur1" placeholder="Tanggal awal" required="">
                    </div>
                  </div>
                  <div class="form-group row" style="margin-top: -10px">
                    <label for="tglretur2" class="col-sm-5 col-form-label">s/d Tanggal</label>
                    <div class="col-sm-4">
                      <input type="date" id="tglretur2" name="tglretur2" placeholder="Tanggal akhir" required="">
                    </div>   
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12">
                      <p>Pilih list cetak :</p>
                       <!-- pilih semua -->
                        <input type="radio" id="pil_allretur" name="pilihretur" value="alldata" checked="">
                        <label for="pil_allretur" style="cursor: pointer">Semua Data</label><br>
                        
                        <!-- pilih toko -->
                        <input type="radio" id="pil_tokoretur" name="pilihretur" value="tokoretur" onclick="document.getElementById('kd_tokoretur').value='';document.getElementById('nm_tokoretur').value=''">
                        <label for="pil_tokoretur" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                        <!-- box pil -->
                        <div id="listretur" style="display: none;position: relative;z-index: 1;">
                          <input type="text" id="nm_tokoretur" onkeyup="carretur()" name="nm_tokoretur" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                          <input type="hidden" id="kd_tokoretur" name="kd_tokoretur" >
                          <div id="tabretur" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                            <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                              <tr align="middle" class="yz-theme-l3">
                                <th>NAMA TOKO</th>
                                <!-- <th>OPSI</th> -->
                              </tr>
                              <?php 
                              $sql3 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                              while ($datakat = mysqli_fetch_array($sql3)){
                              ?>
                              <tr>
                                <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokoretur').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokoretur').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                              </tr>  
                              <?php   
                              }
                              unset($datakat,$sql3);
                              ?>
                            </table>
                          </div>  <!-- tabbonus -->
                        </div> <!--listbonus-->
                        <script>
                          $(document).ready(function(){
                            $("#pil_tokoretur").click(function(){
                              $("#listretur").slideToggle("fast,swing");
                            });
                            $("#pil_allretur").click(function(){
                              $("#listretur").slideUp("fast,swing");
                            });
                            
                          });
                          function carretur() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("nm_tokoretur");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("tabretur");
                          tr = table.getElementsByTagName("tr");
                          for (i = 0; i < tr.length; i++) {
                            td = tr[i].getElementsByTagName("td")[0];
                            if (td) {
                              txtValue = td.textContent || td.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                              } else {
                                tr[i].style.display = "none";
                              }
                            }       
                          }
                        }
                        </script>    
                        <br>
                        <!-- end pilih supplier -->
                    </div>                   
                  </div>

                </div>
                <div class="col-sm-6 ">
                  <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
                </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form retur-->

    <!-- Form-tgl -->
    <div id="form-tgl" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3);">
      <div class="w3-modal-content w3-card-4 w3-animate-top" style="border-style: ridge;border-color: white;width: 400px;border-radius: 5px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-desktop"></i>&nbsp;Setting
          <span onclick="document.getElementById('form-tgl').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-center w3-section">
          <p><i class="fa fa-calendar"></i><b>&nbsp;Tanggal Sistim : <?=date('d-m-Y')?></b></p>
          <hr>
          <br>
          <div class="form-group row">
            <label for="tgl_sis" class="col-sm-4 col-form-label"><b>Ganti Tanggal</b></label>
            <div class="col-sm-8">
              <input class="form-control" id="tgl_sis" type="date" name="tgl_sis" autofocus required style="border: 1px solid black;font-size: 10pt;" value="<?=$_SESSION['tgl_set']?>">
            </div>
          </div> 
          <p>Jika anda mengganti tanggal default hari ini, semua tanggal transaksi yang dilakukan mengikuti tanggal tersebut</p>
          <div class="row">
            <div class="col">
              <button class="form-control btn-primary w3-card-2 w3-hover-shadow" style="cursor:pointer" onclick="document.getElementById('form-tgl').style.display='none'"><i class="fa fa-undo">&nbsp;</i> Batal</button>
            </div>
            <div class="col">
              <button class="form-control btn-success w3-card-2 w3-hover-shadow" style="cursor:pointer" onclick="settgl(document.getElementById('tgl_sis').value);document.getElementById('form-tgl').style.display='none'"><i class="fa fa-check"></i>&nbsp;
                Lanjutkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>    
    <!-- end form tgl -->

     <!-- Form cetak laba ditahan-->
    <div id="formcetlabatahan" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Asset / Laba ditahan
          <span onclick="document.getElementById('formcetlabatahan').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_laba_tahan.php" method="POST" target="_blank">
            <div class="row">
              <div class="form-group row">
                <div class="col-sm-12">
                  <p>Pilih list cetak :</p>
                   <!-- pilih semua -->
                    <input type="radio" id="pilihmutth1" name="pilihmutth" value="alldata" checked="">
                    <label for="pilihmutth1" style="cursor: pointer">Semua Data</label><br>
                    
                    <!-- pilih pelanggan -->
                    <input type="radio" id="pilihmutth2" name="pilihmutth" value="toko" onclick="document.getElementById('kd_tokotahan').value='';document.getElementById('nm_tokotahan').value=''">
                    <label for="pilihmutth2" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                    <!-- box pil -->
                    <div id="listtahan" style="display: none;position: relative;z-index: 1;">
                      <input type="text" id="nm_tokotahan" onkeyup="cartahan()" name="nm_tokotahan" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                      <input type="hidden" id="kd_tokotahan" name="kd_tokotahan" >
                      <div id="tabtahan" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                        <table class="table table-bordered table-sm table-hover" style="font-size:9pt;">
                          <tr align="middle" class="yz-theme-l3">
                            <th>NAMA TOKO</th>
                            <!-- <th>OPSI</th> -->
                          </tr>
                          <?php 
                          $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                          while ($datakat = mysqli_fetch_array($sql2)){
                          ?>
                          <tr>
                            <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokotahan').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokotahan').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                          </tr>  
                          <?php   
                          }
                          unset($datakat,$sql2);
                          ?>
                        </table>
                      </div>  <!-- tabbonus -->
                    </div> <!--listbonus-->
                    <script>
                      $(document).ready(function(){
                        $("#pilihmutth2").click(function(){
                          $("#listtahan").slideToggle("fast,swing");
                        });
                        $("#pilihmutth1").click(function(){
                          $("#listtahan").slideUp("fast,swing");
                        });
                        
                      });
                      function cartahan() {
                      var input, filter, table, tr, td, i, txtValue;
                      input = document.getElementById("nm_tokotahan");
                      filter = input.value.toUpperCase();
                      table = document.getElementById("tabtahan");
                      tr = table.getElementsByTagName("tr");
                      for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[0];
                        if (td) {
                          txtValue = td.textContent || td.innerText;
                          if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                          } else {
                            tr[i].style.display = "none";
                          }
                        }       
                      }
                    }
                    </script>    
                    <br>
                    <!-- end pilih supplier -->
                </div>                   
              </div> 
              
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form laba ditahan-->

    <div id="form-piut" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Pembayaran Piutang
          <span onclick="document.getElementById('form-piut').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_baypiut.php" method="POST" target="_blank">
            <div class="row">
              <div class="form-group row">
                <div class="col-sm-12">
                  <p>Pilih list cetak :</p>
                   <!-- pilih semua -->
                    <input type="radio" id="pilihmutpiut1" name="pilihmutpiut" value="alldata" checked="">
                    <label for="pilihmutpiut1" style="cursor: pointer">Semua Data</label><br>
                    
                    <!-- pilih pelanggan -->
                    <input type="radio" id="pilihmutpiut2" name="pilihmutpiut" value="toko" onclick="document.getElementById('kd_tokomutpiut').value='';document.getElementById('nm_tokomutpiut').value=''">
                    <label for="pilihmutpiut2" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                    <!-- box pil -->
                    <div id="listmutpiut" style="display: none;position: relative;z-index: 1;">
                      <input type="text" id="nm_tokomutpiut" onkeyup="carmuthut()" name="nm_tokomutpiut" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                      <input type="hidden" id="kd_tokomutpiut" name="kd_tokomutpiut" >
                      <div id="tabmutpiut" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                        <table class="table table-bordered table-sm table-hover" style="font-size:9pt; ">
                          <tr align="middle" class="yz-theme-l3">
                            <th>NAMA TOKO</th>
                            <!-- <th>OPSI</th> -->
                          </tr>
                          <?php 
                          $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                          while ($datakat = mysqli_fetch_array($sql2)){
                          ?>
                          <tr>
                            <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokomutpiut').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokomutpiut').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                          </tr>  
                          <?php   
                          }
                          unset($datakat,$sql2);
                          ?>
                        </table>
                      </div>  <!-- tabbonus -->
                    </div> <!--listbonus-->
                    <script>
                      $(document).ready(function(){
                        $("#pilihmutpiut2").click(function(){
                          $("#listmutpiut").slideToggle("fast,swing");
                        });
                        $("#pilihmutpiut1").click(function(){
                          $("#listmutpiut").slideUp("fast,swing");
                        });
                        
                      });
                      function carmuthut() {
                      var input, filter, table, tr, td, i, txtValue;
                      input = document.getElementById("nm_tokomutpiut");
                      filter = input.value.toUpperCase();
                      table = document.getElementById("tabmutpiut");
                      tr = table.getElementsByTagName("tr");
                      for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[0];
                        if (td) {
                          txtValue = td.textContent || td.innerText;
                          if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                          } else {
                            tr[i].style.display = "none";
                          }
                        }       
                      }
                    }
                    </script>    
                    <br>
                    <!--  -->
                </div>                   
              </div> 
              
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form bayar piutang -->

    <!-- Form cetak bayar hutang-->
    <div id="form-hut" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Pembayaran Hutang
          <span onclick="document.getElementById('form-hut').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_bayhut.php" method="POST" target="_blank">
            <div class="row">
              <div class="form-group row">
                <div class="col-sm-12">
                  <p>Pilih list cetak :</p>
                   <!-- pilih semua -->
                    <input type="radio" id="pilihmuthut1" name="pilihmuthut" value="alldata" checked="">
                    <label for="pilihmuthut1" style="cursor: pointer">Semua Data</label><br>
                    
                    <!-- pilih pelanggan -->
                    <input type="radio" id="pilihmuthut2" name="pilihmuthut" value="toko" onclick="document.getElementById('kd_tokomuthut').value='';document.getElementById('nm_tokomuthut').value=''">
                    <label for="pilihmuthut2" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                    <!-- box pil -->
                    <div id="listmuthut" style="display: none;position: relative;z-index: 1;">
                      <input type="text" id="nm_tokomuthut" onkeyup="carmuthut()" name="nm_tokomuthut" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                      <input type="hidden" id="kd_tokomuthut" name="kd_tokomuthut" >
                      <div id="tabmuthut" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                        <table class="table table-bordered table-sm table-hover" style="font-size:9pt; ">
                          <tr align="middle" class="yz-theme-l3">
                            <th>NAMA TOKO</th>
                            <!-- <th>OPSI</th> -->
                          </tr>
                          <?php 
                          $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                          while ($datakat = mysqli_fetch_array($sql2)){
                          ?>
                          <tr>
                            <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokomuthut').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokomuthut').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                          </tr>  
                          <?php   
                          }
                          unset($datakat,$sql2);
                          ?>
                        </table>
                      </div>  <!-- tabbonus -->
                    </div> <!--listbonus-->
                    <script>
                      $(document).ready(function(){
                        $("#pilihmuthut2").click(function(){
                          $("#listmuthut").slideToggle("fast,swing");
                        });
                        $("#pilihmuthut1").click(function(){
                          $("#listmuthut").slideUp("fast,swing");
                        });
                        
                      });
                      function carmuthut() {
                      var input, filter, table, tr, td, i, txtValue;
                      input = document.getElementById("nm_tokomuthut");
                      filter = input.value.toUpperCase();
                      table = document.getElementById("tabmuthut");
                      tr = table.getElementsByTagName("tr");
                      for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[0];
                        if (td) {
                          txtValue = td.textContent || td.innerText;
                          if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                          } else {
                            tr[i].style.display = "none";
                          }
                        }       
                      }
                    }
                    </script>    
                    <br>
                    <!--  -->
                </div>                   
              </div> 
              
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form hutang ditahan-->

    <!-- Form cetak laba piutang-->
    <div id="form-labamsk" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:600px ">
        <div style="background: linear-gradient(165deg, darkblue 20%, cyan 60%, white 80%);color:white;font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Cetak Laba Piutang Berjalan 
          <span onclick="document.getElementById('form-labamsk').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form action="f_cetak_labamsk.php" method="POST" target="_blank">
            <div class="row">
              <div class="form-group row">
                <div class="col-sm-12">

                <p>Pilih list cetak :</p>
                  <div class="row">
                    <label for="bulanpi" class="col-sm-4 col-form-label">Penjualan</label>
                    <div class="col-sm-8">
                      <select class="custom-select" id="bulanpi" name="bulanpi">
                        <option value="1">Bulan ini</option>
                        <option value="2">Bulan lalu</option>
                        <option value="3">Semua</option>
                      </select>
                    </div>
                  </div>
                  
                   <!-- pilih semua -->
                    <input type="radio" id="pilihlabamsk1" name="pilihlabamsk" value="alldata" checked="">
                    <label for="pilihlabamsk1" style="cursor: pointer">Semua Data</label><br>
                    
                    <!-- pilih toko -->
                    <input type="radio" id="pilihlabamsk2" name="pilihlabamsk" value="toko" onclick="document.getElementById('kd_tokolabamsk').value='';document.getElementById('nm_tokolabamsk').value=''">
                    <label for="pilihlabamsk2" style="cursor: pointer">Berdasar ID TOKO &nbsp;<i class="fa fa-caret-down"></i></label>

                    <!-- box pil -->
                    <div id="listlabamsk" style="display: none;position: relative;z-index: 1;">
                      <input type="text" id="nm_tokolabamsk" onkeyup="carlabamsk()" name="nm_tokolabamsk" placeholder="ketik nama toko" style="font-size: 9pt" class="form-control">
                      <input type="hidden" id="kd_tokolabamsk" name="kd_tokolabamsk" >
                      <div id="tablabamsk" class="table-responsive w3-white w3-card" style="overflow-y:auto;overflow-x: auto;border-style: ridge; border-color: white;max-height: 288px">
                        <table class="table table-bordered table-sm table-hover" style="font-size:9pt; ">
                          <tr align="middle" class="yz-theme-l3">
                            <th>NAMA TOKO</th>
                            <!-- <th>OPSI</th> -->
                          </tr>
                          <?php 
                          $sql2 = mysqli_query($connect, "SELECT * from toko ORDER BY no_urut ASC ");
                          while ($datakat = mysqli_fetch_array($sql2)){
                          ?>
                          <tr>
                            <td align="left" class="button w3-hover-shadow" class="button" onclick="document.getElementById('kd_tokolabamsk').value='<?=mysqli_escape_string($connect,$datakat['kd_toko']) ?>';document.getElementById('nm_tokolabamsk').value='<?=mysqli_escape_string($connect,$datakat['nm_toko']) ?>'" style="cursor: pointer"><?php echo $datakat['nm_toko']; ?></td>
                          </tr>  
                          <?php   
                          }
                          unset($datakat,$sql2);
                          ?>
                        </table>
                      </div>  <!-- tabbonus -->
                    </div> <!--listbonus-->
                    <script>
                      $(document).ready(function(){
                        $("#pilihlabamsk2").click(function(){
                          $("#listlabamsk").slideToggle("fast,swing");
                        });
                        $("#pilihlabamsk1").click(function(){
                          $("#listlabamsk").slideUp("fast,swing");
                        });
                        
                      });
                      function carlabamsk() {
                      var input, filter, table, tr, td, i, txtValue;
                      input = document.getElementById("nm_tokolabamsk");
                      filter = input.value.toUpperCase();
                      table = document.getElementById("tablabamsk");
                      tr = table.getElementsByTagName("tr");
                      for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[0];
                        if (td) {
                          txtValue = td.textContent || td.innerText;
                          if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                          } else {
                            tr[i].style.display = "none";
                          }
                        }       
                      }
                    }
                    </script>    
                    <br>
                    <!--  -->
                </div>                   
              </div> 
              
              <div class="col-sm-6 ">
                <button class="btn w3-card w3-right"><img src="img/printer.png" alt=""></button>
              </div>
            </div>
          </form>
                
        </div>
      </div>
    </div>
    <!-- End Form laba piutang-->

    <!-- Form-printer -->
    <div id="formsetprinter" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3);">
      <div class="w3-modal-content w3-card-4 w3-animate-top" style="border-style: ridge;border-color: white;width: 500px;border-radius: 5px; ">
        <div style="color:white;font-size: 15px;padding:4px;text-shadow: 1px 1px 1px black" class="yz-theme-dark">
          &nbsp; <i class="fa fa-desktop w3-text-orange"></i>&nbsp;Setting Proses
          <span onclick="document.getElementById('formsetprinter').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-center w3-section">
          <form id="formp" action="f_setprinter_act.php" method="post">
            <div class="form-group row">
              <label for="pilih_cetak" class="col-sm-4 col-form-label"><b>Cetak Nota</b></label>
              <div class="col-sm-8">
                <select class="form-control" name="cet_pilih" id="pilih_cetak" style="border: 1px solid black;font-size: 10pt;height: 33px;">
                      <option value="0">TIDAK</option>
                      <option value="1">YA</option>
                </select>
              </div>   
            </div>  
            <div class="form-group row">
              <label for="cet_copy" class="col-sm-4 col-form-label"><b>Copy Nota</b></label>
              <div class="col-sm-8">
                <input class="form-control" id="cet_copy" type="number" name="cet_copy" min="0" required style="border: 1px solid black;font-size: 10pt;">
              </div>
            </div> 
            <div class="form-group row">
              <label for="pilih_potong" class="col-sm-4 col-form-label"><b>Update Stok</b></label>
              <div class="col-sm-8">
                <select class="form-control" name="pilih_potong" id="pilih_potong" style="border: 1px solid black;font-size: 10pt;height: 33px;">
                      <option value="0">TIDAK</option>
                      <option value="1">YA</option>
                </select>
              </div>   
            </div>
            <div class="form-group row">
              <label for="p_proses" class="col-sm-4 col-form-label"><b>Proses</b></label>
              <div class="col-sm-8">
                <select class="form-control" name="p_proses" id="p_proses" style="border: 1px solid black;font-size: 10pt;height: 33px;">
                      <option value="0">FIFO</option>
                      <option value="1">LIFO</option>
                      <option value="2">AVERAGE</option>
                </select>
              </div>   
            </div>  
            <div id="viewfaktur"></div>
            <!-- <div class="form-group row">
              <label for="nofakturs" class="col-sm-4 col-form-label"><b>Faktur jual</b></label>
              <div class="col-sm-8">
                <input class="form-control" type="number" max="1000000000" min="1" id="nofakturs" name="nofakturs" style="border: 1px solid black;font-size: 10pt;height: 33px;">
              </div>   
            </div>   -->
            <div class="row">
              <div class="col">
                <button type="button" class="form-control yz-theme-l1 w3-card-2 w3-hover-shadow" style="cursor:pointer" onclick="document.getElementById('formsetprinter').style.display='none'"><i class="fa fa-undo">&nbsp;</i> Batal</button>
              </div>
              <div class="col">
                <button id="klik" class="form-control btn-success w3-card-2 w3-hover-shadow" type="button" style="cursor:pointer" onclick="document.getElementById('formsetprinter').style.display='none'"><i class="fa fa-check"></i>&nbsp;
                  Lanjutkan
                </button>
              </div>
            </div>
          </form>
            
          <script type="text/javascript">
            $(document).ready(function() {
              $('#klik').click(function() {
                $.ajax({
                    type: 'POST',
                    url: $('#formp').attr('action'),
                    data: $('#formp').serialize(),
                    success: function(data) {
                        $('#viewsettgl').html(data);
                    }
                })
                return false;
              });
            })
          </script>   
          
        </div>
      </div>
    </div>    
    <!-- end form set printer -->

    <!-- Form-log -->
    <div id="formcarilog" class="w3-modal" style="background-color:rgba(1, 1, 1, 0.3);">
      <div class="w3-modal-content w3-card-4 w3-animate-top" style="border-style: ridge;border-color: white;width: 700px;border-radius: 5px; ">
        <div style="color:white;font-size: 15px;padding:4px;text-shadow: 1px 1px 1px black" class="yz-theme-dark">
          &nbsp; <i class="fa fa-desktop w3-text-orange"></i>&nbsp;Permintaan Akses Data
          <span onclick="document.getElementById('formcarilog').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-center w3-section">
          <div id="viewcaridel"></div>
        </div>
      </div>
    </div>    
    <!-- end form log -->
    
    <!-- pilih printer -->
    <div id="fpilprint2" class="w3-modal" style="padding-top:60px;margin-left:0px;background-color:rgba(1, 1, 1, 0.3) ">
      <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="border-style: ridge;border-color: white;width:300px ">
        <div  class="yz-theme-d1" style="font-size: 14px;padding:4px">
          &nbsp; <i class="fa fa-print"></i>&nbsp;Silahkan Pilih Printer
          <span onclick="document.getElementById('fpilprint2').style.display='none'" class="w3-display-topright" title="Close Form" style="margin-top: -2px;margin-right: 0px;cursor: pointer"><img style="width: 108%" src="img/tomexit2.png" alt=""></span>    
        </div>
        <div class="w3-container w3-padding-large">
          <form id="form-cets2" action="f_jual_set_print2.php" method="POST">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="pilprints" id="pilprint1" value='CETAK' checked>
              <label class="form-check-label" for="pilprint1">
                Printer thermal ukuran 80 inc
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="pilprints" id="pilprint2" value='CETAK-CK' >
              <label class="form-check-label" for="pilprint2">
                Printer thermal ukuran 58 inc
              </label>
            </div>
            <div class="row gx-5 mt-3">
              <div class="col">
                <button type="submit" class="btn btn-sm btn-success form-control"> OK </button>
              </div>
              <div class="col">
                <button type="button" class="btn btn-sm btn-warning form-control" onclick="document.getElementById('fpilprint2').style.display='none'"> Cancel </button>
              </div>
            </div>
            
          </form>  
          <script>
              $('#form-cets2').submit(function() {
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function(e) {
                      if(e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                      }
                    },
                    success: function(response) {
                        $('#viewpil').html(response.hasil);
                    },
                    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
                      alert(xhr.responseText); // munculkan alert
                    }
                    
                })
                return false;
              });
          </script>
          <div id="viewpil"></div>
        </div>
      </div>
    </div>        
    <!--  -->
   <div id="snackbar"></div> 
   <div id="viewsettgl"></div>
   <div id="viewinfodel"></div>
  <div id="fload" class="w3-modal" style="background-color:rgba(1, 1, 1, 0); ">
    <div style="position: absolute;margin-left: 40%;margin-top: 10%"><img src="img/loading.gif" class="" alt=""><h5 class="w3-text-black w3-center" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);"><strong>Loading data..</strong></h5></div>
  </div>
  <?php 
  if(isset($_COOKIE["Kts"])) {
    $_SESSION['pilprint']=$_COOKIE["Kts"];
  }else{ ?>
    <script>
      if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
      }else{
        document.getElementById('fpilprint2').style.display="block";
      }  
    </script>
    <?php
  } ?>
  
  <script>
    var mySidebar = document.getElementById("mySidebar");
    function openmenu() {
      <?php 
        $_SESSION['no_fak']="";$_SESSION['tgl_fak']="";
        ?>
      if (mySidebar.style.width === "260px") {
        mySidebar.style.width = "0px";
      } else {
        mySidebar.style.width = "260px";
      }
    }

    function closemenu() {
      document.getElementById("mySidebar").style.width = "0";
    }
      
    function myAccFunc(listname) {
      var x = document.getElementById(listname);
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.style=slideUp();
      } else {
        // x.className = x.className.replace(" w3-show", "");
        $("#listname").slideUp();
      }
    }  

    $(document).ready(function(){
      $('.idsup').mask('IDPEM-00000000');
      $('.telp').mask('0000 00000000000');
      $('.hp').mask('000 00000000000');
      $('.uang').mask('000.000.000.000.000', {reverse: true});
      $('.money').mask('000.000.000.000.000,00', {reverse: true});
      $('.money2').mask("#.##0,00", {reverse: true});
      $('.desimal').mask('000,00', {reverse: true});
      $('.desimal2').mask('00,00', {reverse: true});
      $('.angka').mask('000000', {reverse: true});
    });
    
    function carisat(keywords,kdfield1,kdfield2,kdfield3,kdfield4,kdfield5,viewer,tindex){
      // $(this).html("ketik pencarian").attr("disabled", "disabled");
      var sayview;
      sayview="#"+viewer;

      $.ajax({
        url: 'f_carisat.php', // File tujuan
        type: 'POST', // Tentukan type nya POST atau GET
        data: {keyword: keywords, kdfield1: kdfield1 , kdfield2: kdfield2,kdfield3:kdfield3,kdfield4:kdfield4,kdfield5:kdfield5,t_index:tindex}, 
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ 
          // $("#btn-ktcari").html("fa fa-search").removeAttr("disabled");
          
          $(sayview).html(response.hasil);
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
          alert(xhr.responseText); // munculkan alert
        }
      });
    }
    
    function angkatitikdes(b)
    {
      var _minus = false;
      if (b<0) _minus = true;
        b = b.toString();
        b=b.replace(".",",");
        b=b.replace(".","");
        b=b.replace("-","");
        c = "";
        //cek ada koma tdk
        koma=b.search(",");
        if (koma>0) {
          cc=b;
          b=b.substr(0,koma);
          xkoma=cc.substr(koma,3);
        } else {
          xkoma=",00";
        }
        panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--){
          j = j + 1;
          if (((j % 3) == 1) && (j != 1)){
            c = b.substr(i-1,1) + "." + c;
          } else {
            c = b.substr(i-1,1) + c;
          }
        }
      if (_minus) c = "-" + c ;
        return c + xkoma;
    }

    function angkades(b,dec)
    {
      var _minus = false;
      if (b<0) _minus = true;
        b = b.toString();
        b=b.replace(",","");
        b=b.replace("-","");
        c = "";
        panjang = b.length;
        j = 0;
        for (i = panjang; i > 0; i--){
          j = j + 1;
          if (((j % dec) == 1) && (j != 1)){
            c = b.substr(i-1,1) + "," + c;
          } else {
            c = b.substr(i-1,1) + c;
          }
        }
        if (b=="") c = "0";
      if (_minus) c = "0";
        return c;
    }

    function hit1(xnums){
        var hrg_jual1;
        sat=document.getElementById("kd_sat4").value;
        sat1=document.getElementById("kd_sat1").value;
        hrg_jum1=document.getElementById("hrg_jum1").value;
        hrg_jum1=hrg_jum1.replace(".","");
        hrg_jum1=hrg_jum1.replace(",",".");
        if (sat != "-NONE-" && sat==sat1){
          hrg_jual1=Number(hrg_jum1)-(Number(hrg_jum1)*(Number(xnums)/100));
        }    
        document.getElementById('hrg_jum4').value=angkatitikdes(hrg_jual1);
    }

    function hit2(xnums){
        var hrg_jual1;
        sat=document.getElementById("kd_sat5").value;
        sat2=document.getElementById("kd_sat2").value;
        hrg_jum1=document.getElementById("hrg_jum2").value;
        hrg_jum1=hrg_jum1.replace(".","");
        hrg_jum1=hrg_jum1.replace(",",".");

        if (sat != "-NONE-" && sat==sat2){
          hrg_jual1=Number(hrg_jum1)-(Number(hrg_jum1)*(Number(xnums)/100));
        }  
        
        document.getElementById('hrg_jum5').value=angkatitikdes(hrg_jual1);
    }

    function hit3(xnums){
        var hrg_jual1;
        sat=document.getElementById("kd_sat6").value;
        sat3=document.getElementById("kd_sat3").value;
        hrg_jum1=document.getElementById("hrg_jum3").value;
        hrg_jum1=hrg_jum1.replace(".","");
        hrg_jum1=hrg_jum1.replace(",",".");

        if (sat != "-NONE-" && sat==sat3){
          hrg_jual1=Number(hrg_jum1)-(Number(hrg_jum1)*(Number(xnums)/100));
        }
        document.getElementById('hrg_jum6').value=angkatitikdes(hrg_jual1);
    }

    $(window).focus(function(){
      cektokos();
      carinews(1);
    });
  </script>	
</body>
</html>