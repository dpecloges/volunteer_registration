<?php

header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require("../lib/lib.php");

/*
if($_SERVER['HTTP_REFERER']!='https://vregistration2.dpekloges.gr/'){
	$data['Error'] = 100;
	$data['ErrorDescr'] = '<h2>System Error!</h2>';
	die(json_encode($data));
}
*/

$con = openDB();
$errcode = 0;
$errdescr = '';
$BirthYear = $_POST['BirthYear'];
$EidEklArithm = $_POST['EidEklArithm'];
$Mobile = $_POST['Mobile'];
$Email = strtolower(trim($_POST['Email']));


if(empty($EidEklArithm)){
	$errcode = 101;
	$errdescr = "Παρακαλούμε αναζητήστε πρώτα τον Ειδικό Εκλογικό Αριθμό σας!";
}elseif(EidEklArExist($EidEklArithm, $con)){
	$errcode = 102;
	$errdescr = "Είστε ήδη καταχωρημένος!";
}elseif(MobileExist($Mobile, $CCMobile, $con)){
	$errcode = 103;
	$errdescr = 'Αυτό το κινητό τηλέφωνο είναι ήδη καταχωρημένο!';
}elseif((!filter_var($Email, FILTER_VALIDATE_EMAIL))){
	$errcode = 104;
	$errdescr = 'Το email δεν είναι έγκυρο!';
}elseif(EmailExist($Email, $con)){
	$errcode = 105;
	$errdescr = 'Αυτό το email είναι ήδη καταχωρημένο!';
}


if($errcode!=0){
	$data['Error'] = $errcode;
	$data['ErrorDescr'] = $errdescr;
	die(json_encode($data));	
}

$UID = uniqid();
$sql = "INSERT INTO vreg2_volunteers (UID, RegDateTime) VALUES ('$UID', NOW())";
mysqli_query($con, $sql);

$sql = "SELECT ID FROM vreg2_volunteers WHERE UID = '$UID'";
$result = mysqli_query($con, $sql);	
$row = mysqli_fetch_array($result);
$ID = $row['ID'];
$ID_Number = $ID + 10000;
$ID_Number = $ID_Number . luhn_generate($ID_Number);

$FirstName = mysqli_real_escape_string($con, $_POST['FirstName']);
$LastName = mysqli_real_escape_string($con, $_POST['LastName']);
$FathersName = mysqli_real_escape_string($con, $_POST['FathersName']);
$MothersName = mysqli_real_escape_string($con, $_POST['MothersName']);
$Comments = mysqli_real_escape_string($con, $_POST['Comments']);
$Email = mysqli_real_escape_string($con, $Email);

$Country  = 	$_POST['Country'];
$BirthYear  = 	$_POST['BirthYear'];
$Operator   = 	$_POST['Operator'];
$Eforeutiki = 	$_POST['Eforeutiki'];
$Av_12_11   = 	$_POST['Av_12_11'];
$Av_19_11   = 	$_POST['Av_19_11'];


$CountryCodeMobile = $_POST['CountryCodeMobile'];
$sql = "SELECT ccode FROM GeoPC_CallingCodes WHERE iso = '$CountryCodeMobile'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$CountryCodeMobilePrefix = $row['ccode'];


$FixedPhone = $_POST['FixedPhone'];
$CountryCodeFixedPhone = $_POST['CountryCodeFixedPhone'];
$sql = "SELECT ccode FROM GeoPC_CallingCodes WHERE iso = '$CountryCodeFixedPhone'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$CountryCodeFixedPhonePrefix = $row['ccode'];

$sql = "SELECT country FROM GeoPC_Countries WHERE iso = '$Country'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$CountryName = mysqli_real_escape_string($con, $row['country']);

if($Country=='GR'){
	$Municipality = $_POST['Municipality'];
	$sql = "SELECT ONOMA, per_enotita FROM YPES_DHMOI WHERE KOD_DHM = '$Municipality'";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$MunicipalityName = mysqli_real_escape_string($con, $row['ONOMA']);
	$MunicipalityName = "'$MunicipalityName'";
	$Division = mysqli_real_escape_string($con, $row['per_enotita']); 
	$Area = mysqli_real_escape_string($con, $_POST['Area']);
	$RegionCode = $Municipality;
	
	
	
	
	//$HomePlace = $MunicipalityName
	
	
	
	
	
}else{
	$ForeigRegion = $_POST['ForeigRegion'];
	$sql = "SELECT `GeoPC_ISO3166-2`.`name` AS DivisionName FROM `GeoPC_ISO3166-2` WHERE `GeoPC_ISO3166-2`.`code` = '$ForeigRegion'";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$MunicipalityName = 'NULL';
	$Division = $row['DivisionName'];	
	$RegionCode = $_POST['ForeigRegion'];
	$Area = mysqli_real_escape_string($con, $_POST['ForeignArea']);
}





$sql = "UPDATE vreg2_volunteers SET 
				ID_Number = '$ID_Number',
				EidEklAr = '$EidEklArithm',
				FirstName = '$FirstName',
				LastName = '$LastName',
				FathersName = '$FathersName',
				MothersName = '$MothersName',
				Email = '$Email',
				BirthYear = $BirthYear,
				
				CCMobile 	   = '$CountryCodeMobile',
				CCMobilePrefix = '$CountryCodeMobilePrefix',
				Mobile 		   = '$Mobile',
				
				FixedPhone 			= '$CountryCodeFixedPhone',
				CCFixedPhonePrefix  = '$CountryCodeFixedPhonePrefix',
				FixedPhone 			= '$FixedPhone',
				
				Country 	= '$Country',
				CountryName = '$CountryName',
				
				RegionCode 	 = '$RegionCode',
				MunicipalityName = $MunicipalityName,
				
				
				Area = '$Area',
				Division = '$Division',
				
				Operator = $Operator,
				Eforeutiki = $Eforeutiki,
				Av_12_11 = $Av_12_11,
				Av_19_11 = $Av_19_11,
				
				
				Comments = '$Comments'
		WHERE UID = '$UID'";
		
/*
$h1 = $Help1==1 ? '<b>ΝΑΙ</b>': '<b>ΟΧΙ</b>';
$h2 = $Help2==1 ? '<b>ΝΑΙ</b>': '<b>ΟΧΙ</b>';
$h3 = $Help3==1 ? '<b>ΝΑΙ</b>': '<b>ΟΧΙ</b>';
$h4 = $Help4==1 ? '<b>ΝΑΙ</b>': '<b>ΟΧΙ</b>';
*/


$Subject = 'Εγγραφή εθελοντή';
$HomePlace = 
$Body =  
<<<EOT
<br/>
<h1>Εκλογές για τον Πρόεδρο του Νέου Φορέα</h1>
<h3>Ανεξάρτητη Επιτροπή Διαδικασιών & Δεοντολογίας (ΑΕΔΔ)</h3>
<hr/>
<br/>
<br/>
<span style="font:Verdana, Geneva, sans-serif;font-size:14px">


Σε ευχαριστούμε για την εγγραφή σου στους καταλόγους των εθελοντών.<br/><br/>
						
Ο προσωπικός σου κωδικός εθελοντή είναι <h3>$ID_Number</h3><br/>
<br/>

Τα στοιχεία που μας έχεις δώσει είναι:
<br/><br/><br/>

<table>
	<tr>
		<td><b>Ειδικός εκλογικός αριθμός : </b></td>
		<td>$EidEklAr</td>
	</tr>
	<tr>
		<td><b>Όνομα : </b></td>
		<td>$FirstName</td>
	</tr>
	<tr>
		<td><b>Επώνυμο : </b></td>
		<td>$LastName</td>
	</tr>
	<tr>
		<td><b>Όνομα πατρός : </b></td>
		<td>$FathersName</td>
	</tr>
	<tr>
		<td><b>Όνομα μητρός : </b></td>
		<td>$MothersName</td>
	</tr>
	
	<tr>
		<td><b>Email : </b></td>
		<td>$Email</td>
	</tr>
	<tr>
		<td><b>Κινητό τηλέφωνο : </b></td>
		<td>$Mobile</td>
	</tr>
	<tr>
		<td><b>Σταθερό τηλέφωνο : </b></td>
		<td>$FixedPhone</td>
	</tr>
	<tr>
		<td><b>Τόπος κατοικίας : </b></td>
		<td>$HomePlace</td>
	</tr>
</table>

<br/><br/>
<br/><br/>


</span>
<br/><br/>

EOT;



$Fullname = $FirstName . ' ' . $LastName;
sendMail($Email, $Fullname, $Subject, $Body);


mysqli_query($con, $sql);
mysqli_close($con);

$data['ID_Number'] 		= $ID_Number;
$data['Error'] 			= $errcode;
$data['ErrorDescr'] 	= $errdescr;
echo json_encode($data);


function EidEklArExist($EidEklArithm, $con){
	$sql = "SELECT ID FROM vreg2_volunteers WHERE EidEklAr = '$EidEklArithm'";
	$result = mysqli_query($con, $sql);
	$num_rows = mysqli_num_rows($result);
	$r = ($num_rows > 0);
	return $r;
}

function MobileExist($Mobile, $CCMobile, $con){
	$sql = "SELECT ID FROM vreg2_volunteers WHERE Mobile = '$Mobile'";
	$result = mysqli_query($con, $sql);
	$num_rows = mysqli_num_rows($result);
	$r = ($num_rows > 0);
	return $r;
}


function EmailExist($Email, $con){
	$sql = "SELECT ID FROM vreg2_volunteers WHERE Email = '$Email'";
	$result = mysqli_query($con, $sql);
	$num_rows = mysqli_num_rows($result);
	$r = ($num_rows > 0);
	return $r;
}


?>