<?php
function actSHOW(){ 
	require("./lib/lib_pio.php");
	//print_r($PIO->fetchThreadList());
	$SelectArgv = 'no,resto,sub,name,com,now,category,tim,ext,imgsize,tw,th,imgw,imgh';
	//$SelectArgv = "no,sub";
   	echo json_encode($PIO->fetchPosts($PIO->fetchThreadList(),$SelectArgv));
		
//	require("")




}
?>
