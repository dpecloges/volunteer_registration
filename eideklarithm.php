<?php

header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require("lib/lib.php");

/*
if($_SERVER['HTTP_REFERER']!='http://dpekloges.gr/apps/vreg/p1.php'){
	$data['Error'] = 100;
	$data['ErrorDescr'] = '<h2>System Error!</h2>';
	die(json_encode($data));
}
*/
$errcode = 0;
$errdescr = '';


$FName = trim($_POST['FName']);
$LName = trim($_POST['LName']);
$PName = trim($_POST['PName']);
$MName = trim($_POST['MName']);
$BirthYear = $_POST['BirthYear'];



if(mb_strlen($FName, 'utf-8') < 2){
	$errcode = 101;
	$errdescr = 'Δεν έχετε συμπληρώσει το όνομα σας!';
}elseif(mb_strlen($LName, 'utf-8') < 4){
	$errcode = 102;
	$errdescr = 'Δεν έχετε συμπληρώσει το επώνυμο σας!';
}elseif(mb_strlen($PName, 'utf-8') < 2){
	$errcode = 103;
	$errdescr = 'Δεν έχετε συμπληρώσει το πατρώνυμο σας!';
}elseif(mb_strlen($MName, 'utf-8') < 2){
	$errcode = 104;
	$errdescr = 'Δεν έχετε συμπληρώσει το μητρώνυμο σας!';
}elseif(findInvalidChars($FName)){
	$errcode = 105;
	$errdescr = 'Το όνομα σας περιέχει λατινικούς ή ειδικούς χαρακτήρες!';
}elseif(findInvalidChars($LName)){
	$errcode = 106;
	$errdescr = 'Το επώνυμο σας περιέχει λατινικούς ή ειδικούς χαρακτήρες!';
}elseif(findInvalidChars($PName)){
	$errcode = 107;
	$errdescr = 'Το πατρώνυμο σας περιέχει λατινικούς ή ειδικούς χαρακτήρες!';
}elseif(empty($BirthYear)){
	$errcode = 108;
	$errdescr = 'Δεν έχετε συμπληρώσει το έτος γέννησης σας!';
}

if($errcode!=0){
	$data['Error'] = $errcode;
	$data['ErrorDescr'] = $errdescr;
	die(json_encode($data));
}
	

$YPESdata = getEidEklArDataFromDB($FName, $LName, $PName, $MName, $BirthYear);





if($YPESdata['NumRows']==0){
	$data['Error'] = 110;
	$data['ErrorDescr'] = '<span style="color:red">Ο Ειδικός Εκλογικός Αριθμός δεν βρέθηκε!<br/>Παρακαλούμε ελέγξτε τα στοιχεία που έχετε εισάγει.</span>';
	die(json_encode($data));	
}

if(EidEklArExist($YPESdata['Eid_ekl_ar'])){
	$data['Error'] = 111;
	$data['ErrorDescr'] = '<span style="color:red">Είστε ήδη καταχωρημένος!</span>';
	die(json_encode($data));	
	
}

$EidEklAr = $YPESdata['Eid_ekl_ar'];
$FName = $YPESdata['Onoma'];
$LName = $YPESdata['Eponymo']; 
$PName = $YPESdata['on_pat'];
$MName = $YPESdata['on_mht'];

$dhmot = $YPESdata['dhmot']; 
$Dimos = $YPESdata['Dimos']; 
$DimEn = $YPESdata['Dimotiki_Enotita']; 
$Nomos = $YPESdata['NOMOS']; 
$Perif = $YPESdata['PERIFER']; 
$Eklog = $YPESdata['EKL_PERIF']; 
$PerEn = $YPESdata['per_enotita']; 


session_start();
$_SESSION['FName'] = $FName;
$_SESSION['LName'] = $LName;
$_SESSION['PName'] = $PName;
$_SESSION['MName'] = $MName;
$_SESSION['BirthYear'] = $BirthYear;
$_SESSION['EidEklAr'] = $EidEklAr;


$html = "

	<div class='row'>
		<div class='col-sm-4'>Ειδικός Εκλογικός Αριθμός : </div>
		<div class='col-sm-8'><b>$EidEklAr</b></div>
	</div>
	<div class='row'>&nbsp;</div>


	<div class='row'>
		<div class='col-sm-4'>Αριθμός Δημοτολογίου : </div>
		<div class='col-sm-8'><b>$dhmot</b></div>
	</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='col-sm-4'>Όνομα Πατέρα  : </div>
		<div class='col-sm-8'><b>$PName</b></div>
	</div>
	<div class='row'>
		<div class='col-sm-4'>Όνομα Μητέρας  : </div>
		<div class='col-sm-8'><b>$MName</b></div>
	</div>
	<div class='row'>&nbsp;</div>



	<div class='row'>
		<div class='col-sm-4'>Δήμος : </div>
		<div class='col-sm-8'><b>$Dimos</b></div>
	</div>
	<div class='row'>
		<div class='col-sm-4'>Δημοτική Ενότητα : </div>
		<div class='col-sm-8'><b>$DimEn</b></div>
	</div>
	<div class='row'>&nbsp;</div>


	<div class='row'>
		<div class='col-sm-4'>Εκλογική περιφέρεια : </div>
		<div class='col-sm-8'><b>$Eklog</b></div>
	</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='col-sm-4'>Περιφερειακή Ενότητα : </div>
		<div class='col-sm-8'><b>$PerEn</b></div>
	</div>
	<div class='row'>
		<div class='col-sm-4'>Περιφέρεια : </div>
		<div class='col-sm-8'><b>$Perif</b></div>
	</div>
	<div class='row'>
		<div class='col-sm-4'>Νομός : </div>
		<div class='col-sm-8'><b>$Nomos</b></div>
	</div>
	<div class='row'>&nbsp;</div>	

";



$data['html'] = $html;
$data['EidEklAr'] = $EidEklAr;
$data['FName'] = $FName;
$data['PName'] = $PName;
$data['MName'] = $MName;
$data['Error'] = $errcode;
$data['ErrorDescr'] = $errdescr;
echo json_encode($data);


function findInvalidChars($str){
	$gr1 = array ('Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ',
				  'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω');
	$gr2 = array ( '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',
				   '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '');
	$str  = str_replace($gr1, $gr2, $str);
	$r  = (strlen($str) > 0);
	return $r;
}

function EidEklArExist($EidEklArithm){		
	$con = openDB();
	$sql = "SELECT ID FROM vreg_volunteers WHERE EidEklAr = '$EidEklArithm'";
	$result = mysqli_query($con, $sql);
	$num_rows = mysqli_num_rows($result);
	$r = ($num_rows > 0);
	mysqli_close($con);		
	return $r;
}
?>