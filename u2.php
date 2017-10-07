<?php
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require("lib/lib.php");


session_start();
$referer = "http://dpekloges.gr/apps/vreg/p2.php?SID=" . $_SESSION['RegistrationSID'];

if($_SERVER['HTTP_REFERER']!=$referer){
	$data['Error'] = 100;
	$data['ErrorDescr'] = '<h2>System Error!</h2>';
	//die(json_encode($data));
}

$AddressStreetName = trim($_POST['StreetName']);
$AddressStreetNumber = trim($_POST['StreetNumber']);
$Locality = trim($_POST['Area']);
$Municipality = $_POST['Municipality'];
$AddressZip = trim($_POST['Zip']);
$Division = $_POST['Division'];
$AddressCountry = $_POST['Country'];
$NoNumbersAddress = $_POST['NoNumbersAddress']==1;
$CustomAddresss = $_POST['CustomAddress']==1;
$FixedPhone = $_POST['FixedPhone'];



$AddressIsValid = !empty($AddressStreetName) && !empty($Municipality) && (!empty($AddressStreetNumber) || $NoNumbersAddress);

$errcode = 0;
if($_SESSION['Email_PIN_Validated']!==TRUE){
	$errcode = 101;
	$errdescr = "Παρακαλούμε επαληθεύεστε το email σας!";
}elseif($_SESSION['Mobile_PIN_Validated']!==TRUE){
	$errcode = 102;
	$errdescr = "Παρακαλούμε επαληθεύεστε το κινητό σας τηλέφωνο!";
}elseif(!$AddressIsValid){
	$errcode = 103;
	$errdescr = "Η διεύθυνση δεν είναι έγκυρη!";
}else{
	
	
	if($CustomAddresss){
		$_SESSION['Area'] = $Locality;
		$con = openDB();
		$sql = "SELECT
					GeoPC_GR_Regions2.`name` AS Municipality, GeoPC_GR_Regions.`name` AS Division
				FROM
					YPES_DHMOI
					INNER JOIN Geo_YPES_divisions_relation ON YPES_DHMOI.kod_per_enotita = Geo_YPES_divisions_relation.kod_per_enotita
					INNER JOIN GeoPC_GR_Regions ON Geo_YPES_divisions_relation.GID = GeoPC_GR_Regions.ID
					INNER JOIN Geo_YPES_municipalities_relation ON YPES_DHMOI.KOD_DHM = Geo_YPES_municipalities_relation.KOD_DHM
					INNER JOIN GeoPC_GR_Regions GeoPC_GR_Regions2 ON Geo_YPES_municipalities_relation.GID = GeoPC_GR_Regions2.ID
				WHERE
					YPES_DHMOI.KOD_DHM = '$Municipality'";
		$result = mysqli_query($con, $sql);	
		$row = mysqli_fetch_array($result);
		$Municipality = $row['Municipality'];
		$Division = $row['Division'];
	}
	
	$_SESSION['RegistrationSID'] = uniqid();
	$_SESSION['StreetName'] = $AddressStreetName;
	$_SESSION['StreetNumber'] = $AddressStreetNumber;
	$_SESSION['ZipCode'] = $AddressZip;
	$_SESSION['Municipality'] = $Municipality;
	$_SESSION['Division'] = $Division;
	$_SESSION['FixedPhone'] = $FixedPhone;
	$_SESSION['NoNumbersAddress'] = $NoNumbersAddress;	
	$errcode = 0;
	$errdescr = '';	
}

$data['SID'] = $_SESSION['RegistrationSID'];
$data['Error'] = $errcode;
$data['ErrorDescr'] = $errdescr;
echo json_encode($data);


?>