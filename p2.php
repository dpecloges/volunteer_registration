<?php
	header('Cache-Control: no-cache, must-revalidate');
	require("lib/lib.php");

	if($_SERVER['HTTP_REFERER']!='http://dpekloges.gr/apps/vreg/p1.php'){
		//die('<h2>System Error!</h2>');
	}

	session_start();
	if($_SESSION['RegistrationSID'] != $_GET['SID']){
		 //die('<h2>System Error!</h2>');	
	}

	$_SESSION['Email_PIN_Validated'] = FALSE;
	$_SESSION['Mobile_PIN_Validated'] = FALSE;

	$con = openDB();

	$CCodes = '';
	$sql = "SELECT *, IF(iso='GR',0,1) GR FROM GeoPC_CallingCodes ORDER BY GR, country";

	$result = mysqli_query($con, $sql);	
	while($row = mysqli_fetch_array($result)){
		$ISO = $row['iso'];	
		$Country = $row['country'];	
		$CCode = $row['ccode'];		
		$S = $ISO=='GR' ? 'selected="selected"': '';
		$CCodes .= "<option value='$ISO'  $S >$ISO $CCode ($Country)</option>";
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
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Εγγραφή</title> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="assets/css/Form-Select---Full-Date---Month-Day-Year.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/header1.css">
    <link rel="stylesheet" href="assets/css/header2.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/dist/css/formValidation.min.css">    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="assets/dist/js/formValidation.min.js"></script>
    <script src="assets/dist/js/framework/bootstrap.min.js"></script>
    <script src="assets/dist/js/language/el_GR.js"></script>    
    <style type="text/css">
    	.register-photo {
			padding: 0px!important;
		}
        #RegistrationForm .form-control-feedback {
            pointer-events: auto;
        }
        #RegistrationForm .form-control-feedback:hover {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="register-photo" style="background-color:white!important;">
        <div class="form-container">  
        
        
              
            <form onsubmit="return false;" id="RegistrationForm">
            	<div class="row">
            		<div class="col-sm-6">
            			<br/><br/>   
		                <div class="form-group">
		                    <input type="email" class="form-control" placeholder="* Email" name="Email" id="Email" maxlength="50" />
		                </div>
		                <button type="button" class="btn btn-default" id="BtnEmailCheck" onclick="OpenEmailValidation()" >Επαλήθευση Email</button>
		                <br/><br/><br/>
		                <div class="form-group">
			            	<div class="row">
			    				<div class="col-sm-6">
									<select class="form-control" id="CCMobile" disabled="">
										<?php echo $CCodes ?>
									</select>
			    				</div>
			    				<div class="col-sm-6">
			                    	<input type="tel" class="form-control" placeholder="* Τηλέφωνο κινητό" name="Mobile" id="Mobile" maxlength="10" />
			    				</div>
					        </div>		   		                    
		                </div>
						<button type="button" class="btn btn-default" id="BtnMobileCheck" onclick="OpenMobileValidation()">Επαλήθευση κινητού τηλεφώνου</button>
						 <br/><br/><br/>
		                <div class="form-group">
		                    <input type="tel" class="form-control" placeholder="Τηλέφωνο σταθερό (μόνο ελληνικά σταθερά)" name="FixedPhone" id="FixedPhone" maxlength="10" />
		                </div>            			
            		</div>
            		<div class="col-sm-6">
            			<div id="map" style="width:100%;height:400px;"></div>
            			<div id="infowindow-content">
						  <span id="place-address"></span>
						</div>
            			
            		</div>
            	</div>         	
            	<br/><br/>
                  	Για την αποστολή κωδικού επιβεβαίωσης e-mail ή sms, παρακαλούμε περιμένετε έως 3 λεπτά διότι ορισμένα δίκτυα έχουν πρόβλημα.<br/>
					Σε περίπτωση που εντός τριλέπτου δεν έχετε πάρει κωδικό επικοινωνείστε με τη διεύθυνση ethelontesdp@gmail.com<br/><br/>	        
            	<div class="row">
            		<div id="AddressDiv">
						<div class="form-group">
							<div class="col-sm-5"><input type="text" class="form-control" id="StreetName" name="StreetName" placeholder="Οδός" /></div>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="StreetNumber" placeholder="Αριθμός" />
							</div>	
							<div class="col-sm-3"><input type="text" class="form-control" id="Area" name="Area" placeholder="Περιοχή" /></div>
							<div class="col-sm-2"><input type="text" class="form-control" id="zipCode" maxlength="6" placeholder="Τ.Κ." /></div>
						</div>
            		</div>
            	</div> 
             	<div class="row">
            		<div class="col-sm-5"></div>	
            		<div class="col-sm-7">
						<div class="checkbox">
							<label><input type="checkbox" id="NoNumbersAddress" onclick="NoNumbersAddressClick()" value="">Δεν υπάρχει αρίθμηση στην διεύθυνση μου</label>
						</div>                			
            			
            		</div>
		        </div>   
            	<br/>
            	<div class="row">
            		<div class="col-sm-6" id="MunicipalityDiv">
						<select class="form-control" id="Municipality" style="color: grey">
							<option value="" style="color: grey" >Επιλέξτε δήμο</option>
							<?php echo $municipalities ?>
						</select>
					</div>	
            		<div class="col-sm-6"><input type="text" disabled="" class="form-control" id="Division" placeholder="Νομός / Τομέας" style="background-color:#fffdf3" /></div>
		        </div>
		        
		        <div id="ErrorMsgAddress" class="alert alert-danger"></div>	
                <br/><br/><br/><br/>
			    <div class="row">
			    	<div class="col-sm-6"><button class="btn btn-danger btn-block" onclick="ResetData();return false">Επιστροφή στην αρχική</button></div>
			    	<div class="col-sm-6"><button class="btn btn-success btn-block" onclick="submitData();return false">Επόμενο βήμα</button></div>
			  	</div> 
            </form>
        </div>
    </div>
 

<!--------------------------------------------------------------- ERROR MODAL --------------------------------------------------------------->
  <div class="modal fade" id="ErrModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" align="center">Προσοχή!</h4>
        </div>
        <div class="modal-body">

          <table style="height:100%!important;width:100%!important;border:0">
            <tbody>
              <tr style="height:20px"></tr>
              <tr>
                <td align="center">
                  <div class="alert alert-danger">
                    <span>
                        <div class="row" id="ErrorMsg"></div>
                    </span>
                  </div>
                </td>
              </tr>
              <tr style="height:10px"></tr>
            </tbody>
          </table>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Κλείσιμο</button>
        </div>
      </div>
    </div>
  </div>
<!-------------------------------------------------------------------------------------------------------------------------------------------->

<!--------------------------------------------------------------- EMAIL VALIDATION MODAL --------------------------------------------------------------->
  <div class="modal fade" id="EmailModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" align="center">Επαλήθευση Email</h4>
        </div>
        <div class="modal-body">

          <table style="height:100%!important;width:100%!important;border:0">
            <tbody>
              <tr style="height:20px"></tr>
              <tr>
                <td align="center">
                  <p id="SendEmailMsg">Θα σας αποσταλεί κωδικός επαλήθευσης στο email σας.<br /><br />
                  Παρακαλούμε πατήστε «<strong>Αποστολή Κωδικού στο email μου</strong>»<br/>
                  και στην συνέχεια εισάγετε τον κωδικό που θα παραλάβατε.</p>
                  <div id="SendEmailDiv">
                  	Εισάγετε τον κωδικό επαλήθευσης που λάβατε στο<br/>
                  	email σας και πατήστε το κουμπί «<strong>Επαλήθευση</strong>».
                  	<br/><br/>
                  	<input type="text" maxlength="4" id="EmailPIN" style="text-align:center;font-size:18px" />
                  	<br/><br/>
                  </div>
                  <div id="ErrorMsgEmail" class="alert alert-danger"></div>
                </td>
              </tr>
              <tr style="height:10px"></tr>
            </tbody>
          </table>

        </div>
        <div class="modal-footer">

        
          <button type="button" class="btn btn-info" onclick="SendEmail()" id="BtnSendEmail">Αποστολή κωδικού στο email μου</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" id="BtnSendEmailCancel">Κλείσιμο</button>
          <button type="button" class="btn btn-success" onclick="CheckEmailCode()" id="BtnCheckEmailCode">Επαλήθευση</button>
        </div>
      </div>
    </div>
  </div>
<!-------------------------------------------------------------------------------------------------------------------------------------------->


<!--------------------------------------------------------------- MOBILE VALIDATION MODAL --------------------------------------------------------------->
  <div class="modal fade" id="MobileModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" align="center">Επαλήθευση κινητού τηλεφώνου</h4>
        </div>
        <div class="modal-body">
          <table style="height:100%!important;width:100%!important;border:0">
            <tbody>
              <tr style="height:20px"></tr>
              <tr>
                <td align="center">
                  <p id="SendMobileMsg">Θα λάβετε κωδικό επαλήθευσης στο κινητό σας.<br /><br />
                  Παρακαλούμε πατήστε «<strong>Επαλήθευση μέσω SMS</strong>»<br/>
                  ή «<strong>Επαλήθευση μέσω φωνητικής κλήσης</strong>»<br/>
                  και στην συνέχεια εισάγετε τον κωδικό που θα παραλάβατε.</p>
                  <div id="SendMobileDiv">
                  	Εισάγετε τον κωδικό επαλήθευσης που λάβατε στο<br/>
                  	κινητό σας και πατήστε το κουμπί «<strong>Επαλήθευση</strong>».
                  	<br/><br/>
                  	<input type="text" maxlength="4" id="MobilePIN" style="text-align:center;font-size:18px" />
                  	<br/><br/>
                  </div>
                  <div id="ErrorMsgMobile" class="alert alert-danger"></div>
                </td>
              </tr>
              <tr style="height:10px"></tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" id="BtnSendMobileSMS" onclick="SendMobileCode(1)" >Επαλήθευση μέσω SMS</button>
          <button type="button" class="btn btn-warning" id="BtnSendMobileVoice" onclick="SendMobileCode(2)">Επαλήθευση μέσω φωνητικής κλήσης</button>
          <button type="button" class="btn btn-default" id="BtnSendMobileCancel" data-dismiss="modal">Κλείσιμο</button>
          <button type="button" class="btn btn-success" onclick="CheckMobileCode()" id="BtnCheckMobileCode">Επαλήθευση</button>
        </div>
      </div>
    </div>
  </div>
<!-------------------------------------------------------------------------------------------------------------------------------------------->



<script>
	var vZipCode = "";
	var map =  null;
	var infowindowG = null;
	var markerG = null;
	var myAddress = new fullAddress();
	
	function fullAddress(){
		this.Area = '';
		this.StreetName = '';
		this.StreetNumber = '';
		this.Zip = '';
		this.Municipality = '';
		this.Division = '';
		this.Country = '';
		this.IsValid = false;		
		this.oAddressDiv = '';
	}

	$(document).ready(function() {
		InitializeTextInputEvents();
		$('#Email').focus();
		$('#NoNumbersAddress').prop('checked', false);
		$('#ErrorMsgAddress').hide();

		
	    $('#RegistrationForm')
	     	.on('init.field.fv', function(e, data) {
	            var $parent = data.element.parents('.form-group'),
	                $icon   = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
	            $icon.on('click.clearing', function() {
	                if ($icon.hasClass('glyphicon-remove')) {
	                	var reset = data.field != "HomePlace";
	                    data.fv.resetField(data.element, reset);
	                }
	            });
	        })    
		    .formValidation({
		        framework: 'bootstrap',
		        icon: {
		            valid: 'glyphicon glyphicon-ok',
		            invalid: 'glyphicon glyphicon-remove',
		            validating: 'glyphicon glyphicon-refresh'
		        },
		        fields: {
		            Mobile: {
		                validators: {
		                	callback: {
		                        message: 'Παρακαλούμε εισάγετε έγκυρο κινητό τηλέφωνο!',
		                        callback: function(value, validator, $field) {
		                        	return MobileValidator(value);
		                        }
		                    },
		                    notEmpty: {
		                        message: 'Το πεδίο κινητό τηλέφωνο είναι υποχρεωτικό!'
		                    }
		                }
		            },
		        	FixedPhone: {
		                validators: {
		                	callback: {
		                        message: 'Παρακαλούμε εισάγετε έγκυρο σταθερό τηλέφωνο!',
		                        callback: function(value, validator, $field) {
		                        	return FixedPhoneValidator(value);
		                        }
		                    }
		                }
		            },
					Email: {
						validators: {
							emailAddress: {
								message: 'Παρακαλούμε εισάγετε έγκυρο Email!'
							},
		                    notEmpty: {
		                        message: 'Το πεδίο Email είναι υποχρεωτικό!'
		                    }
						}
					}
		        }
		    });

	});

	function NoNumbersAddressClick(){
		$('#ErrorMsgAddress').hide();
		var StreetName = $("#StreetName").val();
		var NoNumbersAddress = $("#NoNumbersAddress").prop('checked');
		bgcolor = NoNumbersAddress ? "#fffdf3": "#f7f9fc";	
		$("#StreetNumber").attr("disabled", NoNumbersAddress).css("background-color", bgcolor);
		$("#StreetNumber").val('');		
	}

	function initMap(){
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 6,
			center: {lat: 37.9752816, lng: 23.736729}
		});

		var infowindow = new google.maps.InfoWindow();
		var infowindowContent = document.getElementById('infowindow-content');
		infowindow.setContent(infowindowContent);
		var marker = new google.maps.Marker({
			map: map,
			anchorPoint: new google.maps.Point(0, -29)
		});
		infowindowG = infowindow;
		markerG = marker;
	}
	  
	function AddressValidation(){		
		var NoNumbersAddress = $('#NoNumbersAddress').prop('checked');	
		myAddress.StreetName  = $("#StreetName").val();
		myAddress.StreetNumber = NoNumbersAddress ? '': $("#StreetNumber").val();
		myAddress.Zip = $("#zipCode").val();
		myAddress.Municipality = $("#Municipality").val();
		myAddress.Area = $("#Area").val();
		myAddress.Division = $("#Division").val();
		
		myAddress.IsValid = myAddress.StreetName != '' && myAddress.Municipality != '' && 
							(myAddress.StreetNumber != '' || NoNumbersAddress);
		if(myAddress.IsValid){
			$('#ErrorMsgAddress').hide();
		}else if(NoNumbersAddress){
			$('#ErrorMsgAddress').html('Παρακαλούμε εισάγετε έγκυρη διεύθυνση! (Οδό, Δήμο)');
			$('#ErrorMsgAddress').show();
		}else{
			$('#ErrorMsgAddress').html('Παρακαλούμε εισάγετε έγκυρη διεύθυνση! (Οδός, Αριθμός, Δήμο)');
			$('#ErrorMsgAddress').show();
		}
		return myAddress.IsValid;
	}

	function zipCodefocusout(){
		var address_components = null;
		var geometry = null;
		$('#Address').val('');
		$('#ErrorMsgAddress').hide();
		var zip = $("#zipCode").val();
		
		if(zip.length==5){
			zip = zip.substr(0, 3) + ' ' + zip.substr(3, 2);
		}
		$("#zipCode").val(zip);
		$.post('get_zip_data.php', {zipCode: $("#zipCode").val()}, function(data){
			if(data.Error == 0){
			    var center = new google.maps.LatLng(data.lat, data.lng);
			    map.panTo(center);
			    map.setZoom(15);
			}			
		}, "json");			
		
	}

	function InitializeTextInputEvents(){
		$('#StreetName')[0].oninput = function(){
			$('#ErrorMsgAddress').hide();
			FixTextInputCharacters(	$('#StreetName'));
			myAddress.StreetName = $("#StreetName").val();
		}			
		
		$('#Area')[0].oninput = function(){
			$('#ErrorMsgAddress').hide();
			FixTextInputCharacters(	$('#Area'));
			myAddress.Area = $("#Area").val();
		}	
		
		$('#StreetNumber')[0].oninput = function(){
			$('#ErrorMsgAddress').hide();
			myAddress.StreetNumber = $("#StreetNumber").val();
		}		

		$('#Mobile')[0].oninput = function(){
			RemoveCharactersThatAreNotNumbers($('#Mobile'));
		};

		$('#FixedPhone')[0].oninput = function(){
			RemoveCharactersThatAreNotNumbers($('#FixedPhone'));
		};

		$('#MobilePIN')[0].oninput = function(){
			RemoveCharactersThatAreNotNumbers($('#MobilePIN'));
		};

		$('#EmailPIN')[0].oninput = function(){
			RemoveCharactersThatAreNotNumbers($('#EmailPIN'));
		};		
			

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
	}
		
	$( "#zipCode" ).focusout(function(){
		zipCodefocusout();
	});		
	
	$("#Municipality").change(function(){
		$.post('municipality_change.php', {MunicipalityID: $("#Municipality").val()}, function(data){
			if(data.Error == 0){
				$("#Division").val(data.Division);
				DivisionID = data.DivisionID;
			}			
		}, "json");
	});	

	function OpenMobileValidation(){
		$('#RegistrationForm').data('formValidation').validateField('Mobile');
		var isValid = $('#RegistrationForm').data('formValidation').isValidField('Mobile');
		if(!isValid) return;	
		$('#ErrorMsgMobile').hide();
		$('#SendMobileDiv').hide();
		$('#SendMobileMsg').show();	
		$('#BtnCheckMobileCode').hide();
		$('#BtnSendMobileSMS').show();
		$('#BtnSendMobileVoice').show();
		$('#MobileModal').modal('show');		
	}

	function CheckMobileCode(){
		$.post('mobile_vc.php', {PIN: $("#MobilePIN").val()}, function(data){
			if(data.Error == 0){
				$("#Mobile").attr("disabled", true);
				$("#BtnMobileCheck").attr("disabled", true);
				$("#BtnMobileCheck" ).removeClass( "btn-default" ).addClass( "btn-info" );
				$("#BtnMobileCheck").text('Επαληθεύτηκε');
				$('#MobileModal').modal('hide');	
			}else{			
				$("#ErrorMsgMobile").html(data.ErrorDescr);
				$('#ErrorMsgMobile').show();
				$('#MobilePIN').focus();
			}			
		}, "json");	
	}

	function SendMobileCode(CodeType){
		$('#RegistrationForm').data('formValidation').validateField('Mobile');
		var isValid = $('#RegistrationForm').data('formValidation').isValidField('Mobile');
		if(!isValid) return;	
		$("body").css({"cursor":"progress"});
		$("#BtnSendMobileSMS").attr("disabled", true);
		$("#BtnSendMobileVoice").attr("disabled", true);
		$("#BtnSendMobileCancel").attr("disabled", true);			
		$.post('mobile_sc.php', {Mobile: $("#Mobile").val(), CodeType: CodeType}, function(data){
			$("body").css({"cursor":"auto"});
			$("#BtnSendMobileSMS").attr("disabled", false);
			$("#BtnSendMobileVoice").attr("disabled", false);
			$("#BtnSendMobileCancel").attr("disabled", false);		
			if(data.Error == 0){
				$('#SendMobileMsg').hide();				
				$('#BtnSendMobileSMS').hide();
				$('#BtnSendMobileVoice').hide();
				$('#SendMobileDiv').show();
				$('#BtnCheckMobileCode').show();
				$('#MobilePIN').focus();
			}else{
				$('#MobileModal').modal('hide');	
				$("#ErrorMsg").html(data.ErrorDescr);
				$('#ErrModal').modal('show');		
			}
		}, "json");	
	}

	function OpenEmailValidation(){
		$('#RegistrationForm').data('formValidation').validateField('Email');
		var isValid = $('#RegistrationForm').data('formValidation').isValidField('Email');
		if(!isValid) return;	
		$('#ErrorMsgEmail').hide();
		$('#SendEmailDiv').hide();
		$('#SendEmailMsg').show();	
		$('#BtnCheckEmailCode').hide();
		$('#BtnSendEmail').show();	
		$('#EmailModal').modal('show');		
	}

	function CheckEmailCode(){
		$.post('email_vc.php', {PIN: $("#EmailPIN").val()}, function(data){
			if(data.Error == 0){
				$("#Email").attr("disabled", true);
				$("#BtnEmailCheck").attr("disabled", true);
				$("#BtnEmailCheck" ).removeClass( "btn-default" ).addClass( "btn-info" );
				$("#BtnEmailCheck").text('Επαληθεύτηκε');
				$('#EmailModal').modal('hide');	
			}else{			
				$("#ErrorMsgEmail").html(data.ErrorDescr);
				$('#ErrorMsgEmail').show();
				$('#EmailPIN').focus();
			}			
		}, "json");	
	}

	function SendEmail(){
		$('#RegistrationForm').data('formValidation').validateField('Email');
		var isValid = $('#RegistrationForm').data('formValidation').isValidField('Email');
		if(!isValid) return;		
		$("body").css({"cursor":"progress"});
		$("#BtnSendEmail").attr("disabled", true);
		$("#BtnSendEmailCancel").attr("disabled", true);	
		$.post('email_sc.php', {Email: $("#Email").val()}, function(data){
			$("body").css({"cursor":"auto"});
			$("#BtnSendEmail").attr("disabled", false);
			$("#BtnSendEmailCancel").attr("disabled", false);
			if(data.Error == 0){
				$('#SendEmailMsg').hide();
				$('#BtnSendEmail').hide();		
				$('#SendEmailDiv').show();
				$('#BtnCheckEmailCode').show();
				$('#EmailPIN').focus();
			}else{
				$('#EmailModal').modal('hide');	
				$("#ErrorMsg").html(data.ErrorDescr);
				$('#ErrModal').modal('show');		
			}
		}, "json");	
	}
	
	function FixTextInputCharacters(textInput){
		var s = textInput.val();
		s = s.includes("  ") ? s.replace("  "," ") : s;
		s = s.toUpperCase();		
		s = ConvertStringToUppercaseGreek(s);
		textInput.val(s);	
	}
	
	function ConvertStringToUppercaseGreek(str){
		str = str.toUpperCase();
		var upperEnglish = ['A', 'B', 'G', 'D', 'E', 'Z', 'H', 'U', 'I', 'K', 'L', 'M', 'N', 'J', 'O', 'P', 'R', 'S', 'T', 'Y', 'F', 'X', 'C', 'V', 'Y', 'Z', 'W', 'Q', 
							'1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '!', '@', '#', '$', '%', '^', '&', '*', '_', '{', '}','\\', '<', '>', '?', '=', ';', '.'];
		var upperGreek =   ['Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω', 'Υ', 'Ζ', 'Σ',  '',  
							 '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  ''];
		var str = ArrayCharactersReplace (upperEnglish , upperGreek , str);
		return str;
	}
	
	function RemoveCharactersThatAreNotNumbers(textInput){
		var str = textInput.val();
		str = str.replace(/[^0-9]/gi,'');	
		textInput.val(str);	
	}

	function isNormalIntegerDigits(str) {	
		var n = true;
		for (var i = 0, len = str.length; i < len; i++) {
			n = n && isNormalInteger(str[i]);
		}
		return n;
	}

	function isNormalInteger(str) {
		return /^\+?(0|[1-9]\d*)$/.test(str);
	}

	function submitData(){		
		$('#RegistrationForm').data('formValidation').validate();
		var isValid = $('#RegistrationForm').data('formValidation').isValid();
		if(!isValid) return;
		isValid = AddressValidation();
		if(!isValid) return;
		var NoNumbersAddress = $('#NoNumbersAddress').prop('checked') ? 1: 0;
		$.post('u2.php', {StreetName: myAddress.StreetName, StreetNumber: myAddress.StreetNumber, Municipality: myAddress.Municipality, 
						  Division: myAddress.Division, Country: myAddress.Country, Zip: myAddress.Zip, Area: myAddress.Area, 
						  NoNumbersAddress: NoNumbersAddress, FixedPhone: $('#FixedPhone').val()}, function(data){
			if(data.Error == 0){
				location.replace("p3.php?SID=" + data.SID);	
			}else{
				$("#ErrorMsg").html(data.ErrorDescr);
				$('#ErrModal').modal('show');
			}
		}, "json");
	}

	function ResetData(){
		location.replace("p1.php");
	}

	function FixedPhoneValidator(FixedPhone){
		if(FixedPhone=='') return true;
		var s = FixedPhone.length;
		var b = FixedPhone.substr(0, 1);
		return ((s==10) && (b=='2'));		
	}

	function MobileValidator(Mobile){
		if(Mobile=='') return true;
		if($('#CCMobile').val()=='GR'){
			var s = Mobile.length;
			var b = Mobile.substr(0, 2);
			return ((s==10) && (b=='69'));
		}else{
			var s = Mobile.length;
			return (s>4);
		}
	}

	function str_replace (search, replace, subject, countObj) { 
	  var i = 0
	  var j = 0
	  var temp = ''
	  var repl = ''
	  var sl = 0
	  var fl = 0
	  var f = [].concat(search)
	  var r = [].concat(replace)
	  var s = subject
	  var ra = Object.prototype.toString.call(r) === '[object Array]'
	  var sa = Object.prototype.toString.call(s) === '[object Array]'
	  s = [].concat(s)
	  var $global = (typeof window !== 'undefined' ? window : global)
	  $global.$locutus = $global.$locutus || {}
	  var $locutus = $global.$locutus
	  $locutus.php = $locutus.php || {}
	  if (typeof (search) === 'object' && typeof (replace) === 'string') {
	    temp = replace
	    replace = []
	    for (i = 0; i < search.length; i += 1) {
	      replace[i] = temp
	    }
	    temp = ''
	    r = [].concat(replace)
	    ra = Object.prototype.toString.call(r) === '[object Array]'
	  }
	  if (typeof countObj !== 'undefined') {
	    countObj.value = 0
	  }
	  for (i = 0, sl = s.length; i < sl; i++) {
	    if (s[i] === '') {
	      continue
	    }
	    for (j = 0, fl = f.length; j < fl; j++) {
	      temp = s[i] + ''
	      repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0]
	      s[i] = (temp).split(f[j]).join(repl)
	      if (typeof countObj !== 'undefined') {
	        countObj.value += ((temp.split(f[j])).length - 1)
	      }
	    }
	  }
	  return sa ? s : s[0]
	}

	function ArrayCharactersReplace (search, replace, subject, countObj) { 
	  var i = 0
	  var j = 0
	  var temp = ''
	  var repl = ''
	  var sl = 0
	  var fl = 0
	  var f = [].concat(search)
	  var r = [].concat(replace)
	  var s = subject
	  var ra = Object.prototype.toString.call(r) === '[object Array]'
	  var sa = Object.prototype.toString.call(s) === '[object Array]'
	  s = [].concat(s)
	  var $global = (typeof window !== 'undefined' ? window : global)
	  $global.$locutus = $global.$locutus || {}
	  var $locutus = $global.$locutus
	  $locutus.php = $locutus.php || {}
	  if (typeof (search) === 'object' && typeof (replace) === 'string') {
	    temp = replace
	    replace = []
	    for (i = 0; i < search.length; i += 1) {
	      replace[i] = temp
	    }
	    temp = ''
	    r = [].concat(replace)
	    ra = Object.prototype.toString.call(r) === '[object Array]'
	  }
	  if (typeof countObj !== 'undefined') {
	    countObj.value = 0
	  }
	  for (i = 0, sl = s.length; i < sl; i++) {
	    if (s[i] === '') {
	      continue
	    }
	    for (j = 0, fl = f.length; j < fl; j++) {
	      temp = s[i] + ''
	      repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0]
	      s[i] = (temp).split(f[j]).join(repl)
	      if (typeof countObj !== 'undefined') {
	        countObj.value += ((temp.split(f[j])).length - 1)
	      }
	    }
	  }
	  return sa ? s : s[0]
	}
	





</script>	

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php 
																		echo My_Google_API_Key
																	 ?>&libraries=places&language=el&callback=initMap"></script>

</body>

</html>


