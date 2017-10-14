<?php



$con = openDB();

$CountryCodes = '';
$sql = "SELECT *, IF(iso='GR',0,1) GR FROM GeoPC_CallingCodes ORDER BY GR, country";

$result = mysqli_query($con, $sql);	
while($row = mysqli_fetch_array($result)){
	$ISO = $row['iso'];	
	$Country = $row['country'];	
	$CCode = $row['ccode'];		
	$S = $ISO=='GR' ? 'selected="selected"': '';
	$CountryCodes .= "<option value='$ISO'  $S >$ISO $CCode ($Country)</option>";
}






$Countries = '';
$sql = "SELECT iso, IF(iso = 'gr', 0, 1) AS GRfirst, IF(iso = 'gr', 'Ελλάδα', country) AS CountryName
			FROM GeoPC_Countries ORDER BY GRfirst, country";

$result = mysqli_query($con, $sql);	
while($row = mysqli_fetch_array($result)){
	$ISO = $row['iso'];	
	$Country = $row['CountryName'];
	$S = $ISO=='GR' ? 'selected="selected"': '';
	$Countries .= "<option value='$ISO'  $S >$Country</option>";
}







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
			YPES_DHMOI.PERIFEREIA,
			YPES_DHMOI.per_enotita,
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











?>
