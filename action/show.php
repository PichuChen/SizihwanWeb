<?php
function actSHOW(){ 
	require("./lib/lib_pio.php");
	print_r($PIO->fetchThreadList());
	$SelectArgv = 'no,sub,name,now,category,tim';
	//$SelectArgv = "no,sub";
   	print_r($PIO->fetchPosts($PIO->fetchThreadList(),$SelectArgv));
		
//	require("")




}
?>
