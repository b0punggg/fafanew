<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak barcode</title>   
</head>
 <script src="../assets/js/qrcode.js"></script>
 
<style>
    table {
    width:  100%;
    /*border: solid 1px black;*/
    text-align: center;
    border-spacing: 10px;
    }
    th {
      text-align: center;
      border: solid 1px black;
      background: white;
    }

    td {
      border: solid 1px black;
      background: white;
      font-size: 9pt;
      border-left: none;
      border-right: none;
      border-top: none;
      border-bottom: none;
    }

</style> 
<body>
  <table style="margin-top:-30px">    
    <?php 
    include "config.php";
    $concet=opendtcek();
    $i=0;$x=0;
    if(isset($_GET['bcode'])){
      $cek=mysqli_query($concet,"SELECT * from mas_brg where pilih='1'");  
      if($_GET['bcode']=='1'){
        while($data=mysqli_fetch_array($cek)){
          $nm_brg=$data['nm_brg'];
          $no_urut=$data['no_urut'];
          $copies=$data['copy'];
          $kdbar=$data['kd_bar'];
          $i++;
          mysqli_query($concet,"UPDATE mas_brg SET cetak='1' WHERE no_urut='$no_urut'");
          for ($z=0; $z < $copies ; $z++) {
            if ($x == 0) {echo "<tr>";} ?>
            <td align="center">
             <p style='font-size:3pt;text-align: center'><b><?=$nm_brg?></b></p>
              <div id="<?=$i?>">
                <script>
                  new QRCode(document.getElementById("<?=$i?>"), {
                    text: "<?=$kdbar?>",
                    width: 48,
                    height: 48,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                  });
                </script>
              </div>
            </td> <?php              
            $x=$x+1; 
            if ( $x == 2 ) { echo "</tr>";$x=0;}
          } 
        }     
        if ($x<8 && $x!=0) {echo "</tr>";} 
        mysqli_free_result($cek);unset($data);
      }  
    } mysqli_close($concet) ?>
  </table>
  <script>window.print()</script>
</body>
</html>
  
