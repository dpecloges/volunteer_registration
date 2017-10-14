<?php
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require("../lib/lib.php");



$con = openDB();
$errcode = 0;
$errdescr = '';
$Country = $_POST['Country'];

$sql = "SELECT 
			geoname_id, city_name
		FROM
			`GeoLite2-City-Locations`
		WHERE 
			country_iso_code = '$Country' 
			AND 
			city_name IS NOT NULL
		ORDER BY 
			city_name";

$RegionElement = uniqid();
$Regions = '';

$result = mysqli_query($con, $sql);	








$num_rows = mysqli_num_rows($result);

if($num_rows==0){
	$disabled = "disabled";
	$City = "";
	$Value = "value='-1'";
}else{
	$Value = "";
	$City = "Πόλη";
	$disabled = "";
	while($row = mysqli_fetch_array($result)){
		$RegionID = $row['geoname_id'];	
		$Region   = $row['city_name'];
		$Regions .= "<option value='$RegionID'>$Region</option>";
	}
}



$ForeigRegionSelect =
<<<EOT
	<select class="form-control" id="$RegionElement" style="color: grey" $disabled >
		<option $Value >$City</option>
		$Regions
	</select>
EOT;

$data['html'] = $ForeigRegionSelect;
$data['RegionElement'] = "#$RegionElement";
$data['Error'] = $errcode;
$data['ErrorDescr'] = $errdescr;
echo json_encode($data);









/*

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
*/
?>
