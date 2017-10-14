<?php


header('Cache-Control: no-cache, must-revalidate');
require("lib/lib.php");


	
	
	$EidEklArithm = $_GET['EED'];
	$con = openDB();
	
	$sql = "SELECT * FROM vreg2_volunteers WHERE EidEklAr = '$EidEklArithm'";
	$result = mysqli_query($con, $sql);
	$num_rows = mysqli_num_rows($result);
	
	if($num_rows==0){
		mysqli_close($con);	
		die('NOT FOUND!');
	}
	
	$data = mysqli_fetch_array($result);
	
	print "<pre>";
	print_r($data);
	print "</pre>";


	$sql = "DELETE FROM vreg2_volunteers WHERE EidEklAr = '$EidEklArithm'";
	mysqli_query($con, $sql);
	
	mysqli_close($con);	
		

?>