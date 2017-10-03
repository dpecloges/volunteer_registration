<?php

header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require("lib/lib.php");

$con = openDB();
$zipCode = $_POST['zipCode'];
$sql = "SELECT
			latitude, longitude, region3 Division
		FROM
			GeoPC_GR_Places
		WHERE
			GeoPC_GR_Places.`language` = 'EL'
		AND
			postcode = '$zipCode'";
			
$result = mysqli_query($con , $sql);	
$rowcount = mysqli_num_rows($result);

if($rowcount==0){
	$errcode = 100;
}else{
	$row = mysqli_fetch_array($result);
	$data['lat'] = $row['latitude'];
	$data['lng'] = $row['longitude'];
	$errcode = 0;
}

mysqli_close($con);
$data['Error'] = $errcode;
$data['ErrorDescr'] = '';
echo json_encode($data);

?>