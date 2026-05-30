<!DOCTYPE html>

<html>
<title>Login Shop</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="admin/img/login.png">
<link rel="stylesheet" type="text/css" href="assets/css/w3.css">  
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
<!-- <link rel="stylesheet" href="assets/css/darkgrey-theme.css"> -->
<link rel="stylesheet" href="assets/css/alertyaz.min.css">
<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway|Cinzel Decorative&effect=outline|emboss">   -->
<script src="assets/js/alertyaz.min.js"></script>
<script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script> 
<script type="text/javascript" src="assets/js/jquery.mask.min.js"></script> 
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script>
  $(document).ready(function(){
    $(".loader1").fadeOut();
  })
</script>     
<style>
  body,h2,h3,h4,h5,h6 {font-family: "Helvetica", sans-serif}
  .hrf_arial {font-family: Arial, Helvetica;}

  body, html {
    height: 100%;
    line-height: 1.8;
  }
  .bgimg-1 {
  background-position: center;
  background-size: cover;
  background-image: url("admin/img/logo_login.jpg");
  min-height: 100%;
  }
</style>
<body>
  

  <div id="snackbar" style="z-index: 10001"></div>

  <?php 
    include 'admin/config.php';
    session_start();
    session_destroy();
    session_start();
    
    $_SESSION['nm_user']="";
    $connect = opendtcek();
    $cek=mysqli_query($connect,"SELECT * FROM toko");
    if(mysqli_num_rows($cek)>=1){
      $data=mysqli_fetch_array($cek);  
      $nm_toko=$data['nm_toko'];
      $al_toko=$data['al_toko'];
    }else{$nm_toko="";$al_toko='';}
    unset($data,$cek);

    if(isset($_GET['pesan'])){
      $pesan=$_GET['pesan'];
      if($pesan=="gagal"){
        ?>
          <script>popnew_error("Data Tidak Valid ! <br> Isi Nama User & Password dng benar !");</script>
        <?php
      }
    }
  ?>          
  
  <div class="bgimg-1 w3-container" >
    
    <div class="w3-row " >

      <div class="w3-col w3-padding w3-margin-top w3-center"  >
          <h1 class=" font-effect-outline w3-text-white w3-hide-medium w3-hide-small w3-wide">FAFA COLLECTION & GALERY</h1>
          <h2 class=" font-effect-outline w3-text-white w3-hide-medium w3-hide-small w3-wide"><?=$al_toko?></h2>
          <h3 class=" font-effect-outline w3-text-white w3-hide-large">FAFA COLLECTION & GALERY</h3>
          <h5 class=" font-effect-outline w3-text-white w3-hide-large"><?=$al_toko?></h5>
      </div>
    </div>  

    <div class="w3-container" >
      
      <div class="row" style="margin-top: 50px">
        <div class="col-sm-8 offset-sm-2 text-center"> 
          <div class="row">

            <div class="col-sm-6 offset-sm-3 "  style="box-shadow: 0px 0px 20px;background: linear-gradient(147deg, magenta 0%, yellow 35%, white 70%);border-style: ridge;border-color: white "> 
              <form action="login_act.php" method="post">
                <fieldset>
                  <div class="row">
                    <div class="col-sm-12" style="margin-top: 20px">
                        <input type="text" class="form-control w3-margin-bottom" placeholder="Ketik Nama User" type="text" name="nm_user" style="background-image: url('admin/img/user3.png');background-repeat: no-repeat;background-size: 10% ; background-position: 325px 1px;padding: 10px 0px 12px 10px;background-color: rgba(255,255,255,0.6);">

                        <input type="password" class="form-control w3-margin-bottom" placeholder="Ketik Password" name="pass" style="background-image: url('admin/img/login.png');background-repeat: no-repeat;background-size: 10% ; background-position: 325px 1px;padding: 10px 0px 12px 10px;background-color: rgba(255,255,255,0.6);">
                        <br>

                        <button type="submit" class="w3-button w3-block w3-card-4 w3-border" style="background-color: rgba(255,70,0,0.2);border-radius: 5px"><b>M A S U K</b></button>
                          
                    </div> <!--col-sm-12 -->
                   </div>              
                </fieldset>
                <br/>
              </form>
            </div> <!--col-sm-8 offset-sm-2  -->
          </div> <!-- row  -->

        </div> <!-- col-sm-10 offset-sm-1 text-center -->

            

      </div> <!--Row justify --> 
    </div>  <!--container  -->     
     <div class="loader1"><div class="loader2"><div class="loader3"></div></div></div>
     
    <!-- Large screen -->
    <div class="w3-display-bottomleft w3-hide-small w3-hide-medium w3-padding w3-margin-top">
      <canvas id="myCanvas" width="760" height="140" >
        Your browser does not support the HTML5 canvas tag.
      </canvas>
      <script>
        var c = document.getElementById("myCanvas");
        var ctx = c.getContext("2d");

        ctx.font = "14px Verdana";
        ctx.fillStyle = "white";
        ctx.fillText("A L L  R I G H T  R E S E R V E D  B Y  Y A Z P R O  2 0 2 3", 330, 120);

        ctx.font = "80px Verdana";
        // Create gradient
        var gradient = ctx.createLinearGradient(0, 0, c.width, 0);
        gradient.addColorStop("0", "magenta");
        gradient.addColorStop("0.5", "yellow");
        gradient.addColorStop("0.9", "white");
        // Fill with gradient
        ctx.fillStyle = gradient;
        ctx.fillText("L O G I N  U S E R", 10, 75);
        
        var gradient = ctx.createLinearGradient(0, 0, c.width, 0);
        gradient.addColorStop("0", "white");
        gradient.addColorStop("0.7", "yellow");
        gradient.addColorStop("0.9", "magenta");   
        ctx.beginPath();
        ctx.strokeStyle = gradient;
        ctx.lineWidth = 4;
        ctx.lineCap = "round";
        ctx.moveTo(18, 93);
        ctx.lineTo(750, 93);
        ctx.stroke();
        ctx.closePath();
      </script>
    </div>

    <!-- Small screen -->
    <div class="w3-display-bottomleft w3-hide-large w3-margin-left">
      <canvas id="myCanvas3" width="300" height="70" style="border:0px solid #000000;">
        Your browser does not support the HTML5 canvas tag.
      </canvas>
      <script>
        var c = document.getElementById("myCanvas3");
        var ctx = c.getContext("2d");
        ctx.font = "32px Verdana";
        // Create gradient
        var gradient = ctx.createLinearGradient(0, 0, c.width, 0);
        gradient.addColorStop("0", "magenta");
        gradient.addColorStop("0.5", "yellow");
        gradient.addColorStop("0.9", "white");
        // Fill with gradient
        ctx.fillStyle = gradient;
        ctx.fillText("L O G I N  U S E R", 5, 30);

        ctx.font = "9px Verdana";
        ctx.fillStyle = "white";
        ctx.fillText("ALL RIGHT RESERVED BY YAZPRO 2021", 118, 57);
        var gradient = ctx.createLinearGradient(0, 0, c.width, 0);
        gradient.addColorStop("0", "white");
        gradient.addColorStop("0.7", "yellow");
        gradient.addColorStop("0.9", "magenta");   
        ctx.beginPath();
        ctx.strokeStyle = gradient;
        ctx.lineWidth = 4;
        ctx.lineCap = "round";
        ctx.moveTo(10, 40);
        ctx.lineTo(330, 40);
        ctx.stroke();
        ctx.closePath();
      </script>
    </div>
  </div>

</body>
</html>
