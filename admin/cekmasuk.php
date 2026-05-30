<?php
// Start session only if not already started
if(!session_id()){
  session_start();
}

if(!isset($_SESSION['masuk'])){
	header("location:../index.php");
}else{
	if ($_SESSION['kodepemakai']=='1') {
	  $bag="Operator";
	}elseif ($_SESSION['kodepemakai']=='2') {
	  $bag="Administrator";
	}	
}	
?>