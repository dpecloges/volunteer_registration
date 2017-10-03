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
$BirthYear = $_POST['BirthYear'];



if(mb_strlen($FName, 'utf-8') < 3){
	$errcode = 101;
	$errdescr = 'Δεν έχετε συμπληρώσει το όνομα σας!';
}elseif(mb_strlen($LName, 'utf-8') < 3){
	$errcode = 102;
	$errdescr = 'Δεν έχετε συμπληρώσει το επώνυμο σας!';
}elseif(mb_strlen($PName, 'utf-8') < 3){
	$errcode = 103;
	$errdescr = 'Δεν έχετε συμπληρώσει το πατρώνυμο σας!';
}elseif(findInvalidChars($FName)){
	$errcode = 104;
	$errdescr = 'Το όνομα σας περιέχει λατινικούς ή ειδικούς χαρακτήρες!';
}elseif(findInvalidChars($LName)){
	$errcode = 105;
	$errdescr = 'Το επώνυμο σας περιέχει λατινικούς ή ειδικούς χαρακτήρες!';
}elseif(findInvalidChars($PName)){
	$errcode = 106;
	$errdescr = 'Το πατρώνυμο σας περιέχει λατινικούς ή ειδικούς χαρακτήρες!';
}elseif(empty($BirthYear)){
	$errcode = 107;
	$errdescr = 'Δεν έχετε συμπληρώσει το έτος γέννησης σας!';
}

if($errcode!=0){
	$data['Error'] = $errcode;
	$data['ErrorDescr'] = $errdescr;
	die(json_encode($data));
}
	

$YPESdata = getEidEklArDataFromDB($FName, $LName, $PName, $BirthYear);

if($YPESdata['NumRows']==0){
	$data['Error'] = 110;
	$data['ErrorDescr'] = '<span style="color:red">Ο Ειδικός Εκλογικός Αριθμός δεν βρέθηκε!<br/>Παρακαλούμε ελέγξτε τα στοιχεία που έχετε εισάγει.</span>';
	die(json_encode($data));	
}

/*
		
$p1 = mb_strpos($html, '<td class="t">Όνομα :</td>');		
$FName = mb_substr($html, $p1 + 46);		
$p2 = mb_strpos($FName, '</td>');			
$FName = mb_substr($FName, 0, $p2);

$p1 = mb_strpos($html, '<td class="t">Όνομα Πατέρα :</td>');		
$PName = mb_substr($html, $p1 + 53);		
$p2 = mb_strpos($PName, '</td>');			
$PName = mb_substr($PName, 0, $p2);

$p1 = mb_strpos($html, '<td class="t">Όνομα Μητέρας :</td>');		
$MName = mb_substr($html, $p1 + 54);		
$p2 = mb_strpos($MName, '</td>');			
$MName = mb_substr($MName, 0, $p2);

		YPES_voters.Eid_ekl_ar,
		YPES_voters.Fylo,
		YPES_voters.Onoma,
		YPES_voters.Onoma_b,
		YPES_voters.Eponymo,
		YPES_voters.Eponymo_b,
		YPES_voters.mer_gen,
		YPES_voters.mhn_gen,
		YPES_voters.etos_gen,
		YPES_voters.on_pat,
		YPES_voters.epon_pat,
		YPES_voters.on_mht,
		YPES_voters.Tax_kod,
		YPES_voters.dhmot,
		YPES_DHMOTIKES_ENOTITES.ONOMA Dimotiki_Enotita,
		YPES_DHMOTIKES_ENOTITES.PERIFER,
		YPES_DHMOTIKES_ENOTITES.NOMOS,
		YPES_DHMOTIKES_ENOTITES.EKL_PERIF,
		YPES_DHMOI.ONOMA Dimos



*/


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
		<div class='col-sm-3'>Ειδικός Εκλογικός Αριθμός : </div>
		<div class='col-sm-9'><b>$EidEklAr</b></div>
	</div>
	<div class='row'>&nbsp;</div>


	<div class='row'>
		<div class='col-sm-3'>Αριθμός Δημοτολογίου : </div>
		<div class='col-sm-9'><b>$dhmot</b></div>
	</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='col-sm-3'>Όνομα Πατέρα  : </div>
		<div class='col-sm-9'><b>$PName</b></div>
	</div>
	<div class='row'>
		<div class='col-sm-3'>Όνομα Μητέρας  : </div>
		<div class='col-sm-9'><b>$MName</b></div>
	</div>
	<div class='row'>&nbsp;</div>



	<div class='row'>
		<div class='col-sm-3'>Δήμος : </div>
		<div class='col-sm-9'><b>$Dimos</b></div>
	</div>
	<div class='row'>
		<div class='col-sm-3'>Δημοτική Ενότητα : </div>
		<div class='col-sm-9'><b>$DimEn</b></div>
	</div>
	<div class='row'>&nbsp;</div>


	<div class='row'>
		<div class='col-sm-3'>Εκλογική περιφέρεια : </div>
		<div class='col-sm-9'><b>$Eklog</b></div>
	</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='col-sm-3'>Περιφερειακή Ενότητα : </div>
		<div class='col-sm-9'><b>$PerEn</b></div>
	</div>
	<div class='row'>
		<div class='col-sm-3'>Περιφέρεια : </div>
		<div class='col-sm-9'><b>$Perif</b></div>
	</div>
	<div class='row'>
		<div class='col-sm-3'>Νομός : </div>
		<div class='col-sm-9'><b>$Nomos</b></div>
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

?>