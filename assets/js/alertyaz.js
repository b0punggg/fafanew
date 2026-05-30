	function popnew_ok($pesan){
       var x = document.getElementById("snackbar");
       x.style.background= "rgba(0,0,0,0.6)";
       x.style.color= "white";
       $pesan="<div class='row' style='display:table-row'><div class='col-sm-1' style:'display:table-cell'><i class='fa fa-check-circle fa-3x fa-pull-left text-success' aria-hidden='true' style='text-shadow: 1px 1px 4px #000000;'></i></div><div class='col-sm-11' style='display:table-cell;width:auto;height:auto;vertical-align:middle;text-align:justify'>"+$pesan+"</div></div>";  
       popnew($pesan);
	}
    function popnew_warning($pesan){
       var x = document.getElementById("snackbar");
       x.style.background= "rgba(0,0,0,0.6)";
       x.style.color= "white";
       $pesan="<div class='row' style='display:table-row'><div class='col-sm-1' style:'display:table-cell'><i class='fa fa-exclamation fa-2x fa-pull-left fa-border text-warning' aria-hidden='true' style='text-shadow: 1px 1px 4px #000000;'></i></div><div class='col-sm-11' style='display:table-cell;width:auto;height:auto;vertical-align:middle;text-align:justify'>"+$pesan+"</div></div>";  
       popnew($pesan);
	}
	function popnew_error($pesan){
       var x = document.getElementById("snackbar");
       x.style.background= "rgba(0,0,0,0.6)";
       x.style.color= "white";
       $pesan="<div class='row' style='display:table-row'><div class='col-sm-1' style:'display:table-cell'><i class='fa fa-times-circle fa-3x fa-pull-left text-danger' aria-hidden='true' style='text-shadow: 1px 1px 4px #000000;'></i></div><div class='col-sm-11' style='display:table-cell;width:auto;height:auto;vertical-align:middle;text-align:justify'>"+$pesan+"</div></div>";  
       popnew($pesan);
	}
	
	function popnew($pesan) {

	  document.getElementById("snackbar").innerHTML=$pesan;
	  var x = document.getElementById("snackbar");
        x.className = "show";
        x.style.right="5px";
	  setTimeout(function(){ x.className = x.className.replace("show", "off");x.style.right='-500px'; }, 10000);
	  document.getElementById("snackbar").addEventListener("click", function()
	  {
	    x.className="off";x.style.right="-500px";
	  });
	}
  
  function angka(b)
  {
    b = b.toString();
    panjang = b.length;
    for (i = 0; i < panjang; i++){
      b = b.replace(".","");
      b = b.replace(",",".");
    }
    return b;
  }

 function roundToTwo(num) {
  return +(Math.round(num + "e+2")  + "e-2");
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

  function angkatitik(b)
  {
    var _minus = false;
    if (b<0) _minus = true;
      b = b.toString();
      b=b.replace(".","");
      b=b.replace("-","");
      c = "";
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
      return c;
  } 

  