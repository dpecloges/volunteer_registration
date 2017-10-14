<?php
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require("lib/lib.php");

$errcode = 0;
$errdescr = '';
$con = openDB();
$MunicipalityID = $_POST['MunicipalityID'];

$sql = "SELECT
			GeoPC_GR_Regions.`name` Division,
			Geo_YPES_divisions_relation.kod_per_enotita DivisionID
		FROM
			YPES_DHMOI
			INNER JOIN Geo_YPES_divisions_relation ON YPES_DHMOI.kod_per_enotita = Geo_YPES_divisions_relation.kod_per_enotita
			INNER JOIN GeoPC_GR_Regions ON Geo_YPES_divisions_relation.GID = GeoPC_GR_Regions.ID
		WHERE
			YPES_DHMOI.KOD_DHM = '$MunicipalityID'";

$result = mysqli_query($con, $sql);	
$row = mysqli_fetch_array($result);


$data['Division'] = $row['Division'];;
$data['DivisionID'] = $row['DivisionID'];;
$data['Error'] = $errcode;
$data['ErrorDescr'] = $errdescr;
echo json_encode($data);
	
?>
