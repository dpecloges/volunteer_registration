<?php
	header('Cache-Control: no-cache, must-revalidate');
	require_once("lib/lib.php");
	require_once("php_code.php") 
	
	
	/*
	if(SERVER['HTTP_REFERER']!='https://dpekloges.gr/site/ethelontes/'){
		//die('<h2>System Error!</h2>');
	}
	*/
?>

<!DOCTYPE html>
<html>
	<head>		
		<?php require("header.php") ?>
	</head>
	<body>
		<?php require_once("modals/error_modal.php") ?>
		<div class="register-photo" style="background-color:white!important;"> 
			<div class="form-container">
				<form onsubmit="return false;" id="<?php JQadd($J, "RegistrationForm") ?>" >
					<?php require_once("body.php") ?>
					<div class="row">
						<div class="col-sm-6">
							<button class="btn btn-danger btn-block" type="reset" onclick="ClearData();return false">Καθαρισμός φόρμας</button>
						</div>
						<div class="col-sm-6">
							<button class="btn btn-success btn-block" id="<?php JQadd($J, "BtnSubmit") ?>" onclick="submitData();return false">Καταχώριση</button>
						</div>
					</div>
				</form>
			</div>
		</div>					
	</body>
	<?php require_once("lib/scripts.php") ?>
</html>

