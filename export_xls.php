<?php
header('Cache-Control: no-cache, must-revalidate');
require("lib/lib.php");

if($_GET['key']!=My_XLS_Export_Key) die('Permission Denied');
$con = opendb();
$sql = "SELECT * FROM vreg_volunteers ORDER BY ID";
$result = mysqli_query($con, $sql);

echo 
<<<EOT

<table><tbody>
<td>ID</td>
<td>Εγγραφή ημερ.</td>
<td>Εγγραφή ώρα</td>
<td>#&nbsp;&nbsp;Ειδ.εκλ.αρ.</td>
<td>Όνομα</td>
<td>Επώνυμο</td>
<td>Ετ. Γεν.</td>
<td>Όνομα πατρός</td>
<td>Όνομα μητρός</td>
<td>Email</td>
<td>Κινητό</td>
<td>Σταθερό</td>
<td>Οδός</td>
<td>Αριθμός</td>
<td>Περιοχή</td>
<td>Δήμος</td>
<td>Τ.Κ.</td>
<td>Διεύθυνση</td>
<td>Νομός/Τομέας</td>
<td>Επάγγελμα</td>
<td>Ενδιαφέροντα</td>
<td>Η/Υ</td>
<td>Οθόνες</td>
<td>Πληκτρολόγιο</td>
<td>Εφαρμογές</td>
<td>Οργανωτικός</td>
<td>Σχέση με ανθρώπους</td>
<td>Διεξαγωγή</td>
<td>5/11 7-2</td>
<td>5/11 2-9</td>
<td>12/11 7-2</td>
<td>12/11 2-9</td>
<td>Εθνικό επίπεδο</td>
<td>Τοπική αυτοδιοίκηση</td>
<td>Πόλη/εργασία</td>

EOT;

while($row = mysqli_fetch_array($result)){
	$h1 = $row['H1']==1 ? 'ΝΑΙ</b>': 'ΟΧΙ</b>';
	$h1a = $row['H1a']==1 ? 'ΝΑΙ</b>': 'ΟΧΙ</b>';
	$h1b = $row['H1b']==1 ? 'ΝΑΙ</b>': 'ΟΧΙ</b>';
	$h1c = $row['H1c']==1 ? 'ΝΑΙ</b>': 'ΟΧΙ</b>';
	$h1d = $row['H1d']==1 ? 'ΝΑΙ</b>': 'ΟΧΙ</b>';
	$h2 = $row['H2']==1 ? 'ΝΑΙ</b>': 'ΟΧΙ</b>';
	$h3 = $row['H3']==1 ? 'ΝΑΙ</b>': 'ΟΧΙ</b>';
	$h4 = $row['H4']==1 ? 'ΝΑΙ</b>': 'ΟΧΙ</b>';
	$Area = trim($row['Area'])=='' ? '': trim($row['Area']) . ", ";
	

	echo "<tr>";
	echo "<td>" . $row['ID_Number']  . "</td>";
	echo "<td>" . Date('j/n/Y', strtotime($row['RegDateTime']))  . "</td>";
	echo "<td>" . Date('G:i', strtotime($row['RegDateTime']))  . "</td>";
	echo "<td>#&nbsp;&nbsp;" . $row['EidEklAr']  . "</td>";
	echo "<td>" . $row['FName']  . "</td>";
	echo "<td>" . $row['LName']  . "</td>";
	echo "<td>" . $row['BirthYear']  . "</td>";
	echo "<td>" . $row['PName']  . "</td>";
	echo "<td>" . $row['MName']  . "</td>";
	echo "<td>" . $row['Email'] . "</td>";
	echo "<td>" . $row['Mobile']  . "</td>";
	echo "<td>" . $row['FixedPhone']  . "</td>";	
	echo "<td>" . $row['StreetName'] . "</td>";	
	echo "<td>" . $row['StreetNumber']  . "</td>";	
	echo "<td>" . $row['Area'] . "</td>";
	echo "<td>" . $row['Municipality'] . "</td>";	
	echo "<td>" . $row['Zip'] . "</td>";	
	echo "<td>" . $row['StreetName']  . " " . $row['StreetNumber']  . ", " . $Area . $row['Municipality']  . " " . $row['Zip']  . "</td>";
	echo "<td>" . $row['Division']  . "</td>";
	echo "<td>" . $row['Job']  . "</td>";
	echo "<td>" . $row['MiscSkills']  . "</td>";
	echo "<td>" . $row['Q1']  . " / 10</td>";
	echo "<td>" . $row['Q2']  . " / 10</td>";
	echo "<td>" . $row['Q3']  . " / 10</td>";
	echo "<td>" . $row['Q4']  . " / 10</td>";
	echo "<td>" . $row['Q5']  . " / 10</td>";
	echo "<td>" . $row['Q6']  . " / 10</td>";
	echo "<td>$h1</td>";
	echo "<td>$h1a</td>";
	echo "<td>$h1b</td>";
	echo "<td>$h1c</td>";
	echo "<td>$h1d</td>";
	echo "<td>$h2</td>";
	echo "<td>$h3</td>";
	echo "<td>$h4</td>";
	echo "</tr>";
}
echo "</tbody></table>";


?>
