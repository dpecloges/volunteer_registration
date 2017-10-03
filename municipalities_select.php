<?php
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require("lib/lib.php");


$con = openDB();


$sql = "SELECT
			IF(YPES_DHMOI.KOD_PER = '09', 0, 1) AS Attica,
			YPES_DHMOI.KOD_DHM MunicipalityID,
			YPES_DHMOI.per_enotita Division,
			YPES_DHMOI.kod_per_enotita DivisionID,
			YPES_DHMOI.KOD_PER RegionID,
			GeoPC_GR_Regions.`name` Municipality
		FROM
			YPES_DHMOI
			INNER JOIN Geo_YPES_municipalities_relation ON YPES_DHMOI.KOD_DHM = Geo_YPES_municipalities_relation.KOD_DHM
			INNER JOIN GeoPC_GR_Regions ON Geo_YPES_municipalities_relation.GID = GeoPC_GR_Regions.ID
		ORDER BY
			Attica ASC,
			YPES_DHMOI.kod_per_enotita,
			YPES_DHMOI.ONOMA";

$result = mysqli_query($con, $sql);	
while($row = mysqli_fetch_array($result)){
	$MunicipalityID = $row['MunicipalityID'];
	$Municipality = $row['Municipality'];	
	$DivisionID = $row['DivisionID'];	
	$Division = $row['Division'];

	if($OldDivisionID != $DivisionID){
		$GroupLabel = $row['RegionID']=='09' ? "ΔΗΜΟΙ $Division": "ΝΟΜΟΣ $Division";		
		$FirstGroup = $OldDivisionID == 1 ? "":"</optgroup>";
		$municipalities .= "$FirstGroup<optgroup label='$GroupLabel'>";
	}
	$municipalities .= "<option value='$MunicipalityID'>$Municipality</option>";
	$OldDivisionID = $DivisionID;
}
$municipalities .= "</optgroup>";



$MunicipalityDiv =

<<<EOT

<select class="form-control" id="Municipality">
<option></option>
$municipalities
</select>

<script>
	$("#Municipality").change(function(){
		$.post('municipality_change.php', {MunicipalityID: $("#Municipality").val()}, function(data){
			if(data.Error == 0){
				$("#Division").val(data.Division);
				DivisionID = data.DivisionID;
			}			
		}, "json");
	});	
</script>

EOT;

$AddressDiv =

<<<EOT

<div class="form-group">
	<div class="col-sm-5"><input type="text" class="form-control" id="StreetName" name="StreetName" placeholder="Οδός" /></div>
	<div class="col-sm-2"><input type="text" class="form-control" id="StreetNumber" placeholder="Αριθμός" /></div>	
	<div class="col-sm-3"><input type="text" class="form-control" id="Area" name="Area" placeholder="Περιοχή" /></div>
	<div class="col-sm-2"><input type="text" class="form-control" id="zipCode" maxlength="6" placeholder="Τ.Κ." /></div>
</div>

<script>
	$('#zipCode')[0].oninput = function(){
		$('#ErrorMsgAddress').hide();
		var ZipCodeValue = str_replace (' ', '', $("#zipCode").val());	
		var b = isNormalIntegerDigits(ZipCodeValue);		
		if(b){
			vZipCode = ZipCodeValue;
			myAddress.Zip = ZipCodeValue;
			if(ZipCodeValue.length>4) zipCodefocusout();
		}else{
			$("#zipCode").val(vZipCode);
		}
	}
	
	$( "#zipCode" ).focusout(function(){
		zipCodefocusout();
	});		
</script>


EOT;

$oAddressDiv =
<<<EOT
	<div class="form-group">
		<div class="col-sm-8"><input type="text" disabled="" class="form-control" id="StreetName" name="StreetName" placeholder="Οδός" style="background-color:#fffdf3" /></div>
		<div class="col-sm-2"><input type="text" disabled="" class="form-control" id="StreetNumber" placeholder="Αριθμός" style="background-color:#fffdf3" /></div>
		<div class="col-sm-2"><input type="text" disabled="" class="form-control" id="zipCode" placeholder="Τ.Κ." style="background-color:#fffdf3" /></div>
	</div>
EOT;


$data['MunicipalityDiv'] = $MunicipalityDiv;
$data['AddressDiv'] = $AddressDiv;
$data['oAddressDiv'] = $oAddressDiv;
$data['Error'] = $errcode;
$data['ErrorDescr'] = $errdescr;
echo json_encode($data);
?>
