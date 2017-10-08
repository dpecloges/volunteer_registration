<?php
	header('Cache-Control: no-cache, must-revalidate');
	require("lib/lib.php");
	
	
	if(SERVER['HTTP_REFERER']!='http://dpekloges.gr/site/ethelontes/'){
		//die('<h2>System Error!</h2>');
	}
		
	session_start();
	session_destroy();
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
    <script src="assets/dist/js/mandatoryIcon.min.js"></script>    
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
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Επώνυμο (ολογράφως)" name="LName" id="LName" maxlength="30" />
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Όνομα" name="FName" id="FName" maxlength="30" />
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Πατρώνυμο" name="PName" id="PName" maxlength="30" />
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Mητρώνυμο " name="MName" id="MName" maxlength="30" />
                </div>                
                <div class="well" style="height:600px!important">
                    <span>* Ειδικός Εκλογικός Αριθμός <b>(Μάθε που ψηφίζεις)</b></span>
                    <br />
                    <br />
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" id="EidEklArithm" />
							<input class="form-control display-inline-block" placeholder="Έτος γέννησης" type="text" name="BirthYear" id="BirthYear" maxlength="4" style="width:150px" />
							&nbsp;&nbsp;
                            <button class="btn btn-primary-small" type="button" id="BtnFind" onclick="FindEidEklArithm();return false">Αναζήτηση</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
					<div class="row" style="height:350px" >
						<div class="col-sm-12">
							<span id="EidEklArithmHtml">
								<h4>Εάν είστε κάτοικος εξωτερικού επικοινωνείστε σας παρακαλούμε με τη διεύθυνση <span style="color:blue">ethelontesdp@gmail.com</span></h4>
							</span>
						</div>
					</div>
					<h2>Σας παρακαλούμε ότι πρόβλημα έχετε στείλτε μήνυμα στην διεύθυνση <span style="color:blue">ethelontesdp@gmail.com</span></h2> 
					
					
                </div>
				   <div class="row">
				    <div class="col-sm-6"><button class="btn btn-danger btn-block" onclick="ClearData();return false">Καθαρισμός φόρμας</button></div>
				    <div class="col-sm-6"><button class="btn btn-success btn-block" id="BtnNext" onclick="submitData();return false">Επόμενο βήμα</button></div>
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
                        <div class="row" id="ErrorMsg">&nbsp;</div>
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


<script>
	
	function submitData(){		
		$('#RegistrationForm').data('formValidation').validate();
		var isValid = $('#RegistrationForm').data('formValidation').isValid();	
		if(!isValid) return;				
		$.post('u1.php', {EidEklArithm: $("#EidEklArithm").val()
						 }, function(data){
			if(data.Error == 0){
				location.replace("p2.php?SID=" + data.SID);		
			}else{
				$("#ErrorMsg").html(data.ErrorDescr);
				$('#ErrModal').modal('show');
			}
		}, "json");
	}

	function ClearData(){		
		$("#FName").val('');
		$("#LName").val('');
		$("#PName").val('');
		$("#MName").val('');
		$("#BirthYear").val('');
		$("#EidEklArithm").val('');
		$('#EidEklArithmHtml').html('');
		$("#FName").attr("disabled", false); 
		$("#LName").attr("disabled", false); 
		$("#PName").attr("disabled", false); 
		$("#BirthYear").attr("disabled", false); 
		$("#BtnFind").attr("disabled", false); 		
		$("#BtnNext").attr("disabled", true);
		$("#FName").focus();		
	}

	function FindEidEklArithm(){
		$('#RegistrationForm').data('formValidation').validate();
		var isValid = $('#RegistrationForm').data('formValidation').isValid();	
		if(!isValid) return;
		$("body").css({"cursor":"progress"});
		$("#FName").attr("disabled", true); 
		$("#LName").attr("disabled", true); 
		$("#PName").attr("disabled", true); 
		$("#MName").attr("disabled", true); 
		$("#BirthYear").attr("disabled", true);
		$("#BtnFind").attr("disabled", true);
		$('#EidEklArithmHtml').html('Παρακαλώ περιμένετε...');
		$.post('eideklarithm.php', {FName: $("#FName").val(), LName: $("#LName").val(), 
								    PName: $("#PName").val(), MName: $("#MName").val(), 
								    BirthYear: $("#BirthYear").val()}, function(data){
			$("body").css({"cursor":"auto"});
			if(data.Error == 0){
				$('#EidEklArithmHtml').html(data.html);
				$('#EidEklArithm').val(data.EidEklAr);
				$("#BtnNext").attr("disabled", false); 					
				$('#FName').val(data.FName);						
				$('#PName').val(data.PName);
				$('#MName').val(data.MName);
			}else{
				$("#FName").attr("disabled", false); 
				$("#LName").attr("disabled", false); 
				$("#PName").attr("disabled", false); 
				$("#MName").attr("disabled", false);
				$("#BirthYear").attr("disabled", false);
				$("#BtnFind").attr("disabled", false);				
				if(data.Error==110){
					$('#EidEklArithmHtml').html(data.ErrorDescr);
					$('#EidEklArithm').val('');
				}else{
					$("#ErrorMsg").html(data.ErrorDescr);
					$('#EidEklArithmHtml').html('');
					$('#ErrModal').modal('show');
				}
			}
		}, "json");
	}

	$(document).ready(function() {			
		$("#BtnNext").attr("disabled", true);
		InitializeTextInputEvents();	
		$("#LName").focus();
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
			    addOns: {
			        mandatoryIcon: {
			            icon: 'glyphicon glyphicon-asterisk'
			        }
			    },	        
		        icon: {
		            valid: 'glyphicon glyphicon-ok',
		            invalid: 'glyphicon glyphicon-remove',
		            validating: 'glyphicon glyphicon-refresh'
		        },
		        fields: {		            
		            BirthYear: {
		                validators: {
		                    notEmpty: {
		                        message: '<span style="color:#a94442">Το πεδίο Έτος γέννησης είναι υποχρεωτικό!</span>'
		                    },
		                    between: {
	                            min: 1900,
	                            max: 2005,
	                            message: '<span style="color:#a94442">Παρακαλούμε εισάγετε έγκυρο έτος γέννησης!</span>'
	                        }
		                }
		            },            
		            FName: {
		                validators: {
		                	callback: {
		                        message: 'Error!',
		                        callback: function(value, validator, $field) {
		                        	return NameValidator(value, $field);
		                        }
		                    },
		                    notEmpty: {
		                        message: 'Το πεδίο Όνομα είναι υποχρεωτικό!'
		                    },
		                    stringLength: {
		                        min: 2,
		                        message: 'Παρακαλούμε εισάγετε το όνομα σας'
		                    }
		                }
		            },
		            LName: {
		                validators: {
		                	callback: {
		                        message: 'Error!',
		                        callback: function(value, validator, $field) {
		                        	return NameValidator(value, $field);
		                        }
		                    },
		                    notEmpty: {
		                        message: 'Το πεδίο Επώνυμο είναι υποχρεωτικό!'
		                    },
		                    stringLength: {
		                        min: 2,
		                        message: 'Παρακαλούμε εισάγετε ολογράφως το επώνυμο σας'
		                    }
		                }
		            },
		            PName: { 
		                validators: {
		                	callback: {
		                        message: 'Error!',
		                        callback: function(value, validator, $field) {
		                        	return NameValidator(value, $field);
		                        }
		                    },
		                    notEmpty: {
		                        message: 'Το πεδίο Πατρώνυμο είναι υποχρεωτικό!'
		                    },
		                    stringLength: {
		                        min: 2,
		                        message: 'Παρακαλούμε εισάγετε έγκυρο Πατρώνυμο! (τουλάχιστον 2 χαρακτήρες)'
		                    }
		                }
		            },
		            MName: { 
		                validators: {
		                	callback: {
		                        message: 'Error!',
		                        callback: function(value, validator, $field) {
		                        	return NameValidator(value, $field);
		                        }
		                    },
		                    notEmpty: {
		                        message: 'Το πεδίο Mητρώνυμο είναι υποχρεωτικό!'
		                    },
		                    stringLength: {
		                        min: 2,
		                        message: 'Παρακαλούμε εισάγετε έγκυρο Mητρώνυμο! (τουλάχιστον 2 χαρακτήρες)'
		                    }
		                }
		            }
		        }
		    });		
	});


 	function NameValidator(value, field){
 		value = UppercaseGreek(value);
		field.val(value);
		if(CheckTextInput(value, false)){
			return true;
		}else{
			return {
				valid: false,
				message: 'Μόνο ελληνικοί χαρακτήρες!'
			};
		}
		return true;		
	}
 		

	function CheckTextInput(value, ng){
		var b = true;
		var s = value;
		for( i=0; i< s.length; i++){
			k=s.substr(i, 1);
			r=s.substr(0, i + 1);
			b = (b && maskdata(k, r, ng)); 
		}
		return b;
	}
	
	function maskdata(k, r, ng){
		var sparray=[" "];
		var uparray;
		uparray=['Α','Β','Γ','Δ','Ε','Ζ','Η','Θ','Ι','Κ','Λ','Μ','Ν','Ξ','Ο','Π','Ρ','Σ','Τ','Υ','Φ','Χ','Ψ','Ω'];
		if(switchArrayCase(k,uparray)){
			return true;
		}else{
			return false;	
		}
	}
	
	function switchArrayCase(item,array){
		var alen=array.length;
		while(alen--){if(array[alen]==item){return true}}
		return false;
	}



	function UppercaseGreek(str){
		var lower = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'α', 'ά', 'Ά', 'β', 'γ', 'δ', 'ε', 'έ', 'Έ', 'ζ', 'η', 'ή', 'Ή', 'θ', 'ι', 'ί', 'ϊ', 'ΐ', 'Ί', 'Ϊ', 'κ', 'λ', 'μ', 'ν', 'ξ', 'ο', 'ό', 'Ό', 'π', 'ρ', 'σ', 'ς', 'τ', 'υ', 'ύ', 'ϋ', 'ΰ', 'Ύ', 'Ϋ', 'φ', 'χ', 'ψ', 'ω', 'ώ', 'Ώ'];
		var upper = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Α', 'Α', 'Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ε', 'Ε', 'Ζ', 'Η', 'Η', 'Η', 'Θ', 'Ι', 'Ι', 'Ι', 'Ι', 'Ι', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Ο', 'Ο', 'Π', 'Ρ', 'Σ', 'Σ', 'Τ', 'Υ', 'Υ', 'Υ', 'Υ', 'Υ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω', 'Ω', 'Ω'];
		var str2  = str_replace(lower, upper, str);
		return str2;
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


	
	function InitializeTextInputEvents()
	{
		$('#FName')[0].oninput = function(){
			FixTextInputCharacters(	$('#FName'));
     	};
		
		$('#LName')[0].oninput = function(){
			FixTextInputCharacters(	$('#LName'));
		};
	
		$('#PName')[0].oninput = function(){
			FixTextInputCharacters(	$('#PName'));
		};
		
		$('#MName')[0].oninput = function(){
			FixTextInputCharacters(	$('#MName'));
		};

		
		$('#BirthYear')[0].oninput = function(){
			RemoveCharactersThatAreNotNumbers($('#BirthYear'));
		};

	}
	
	
	// Removes space characters and turns letters to greek capital
	function FixTextInputCharacters(textInput)
	{
		var s = textInput.val();
		s = s.includes(" ") ? s.replace(" ","") : s;
		s = s.toUpperCase();
		s = ConvertStringToUppercaseGreek(s);
		s = RemoveCharactersThatAreNotCapitalGreek(s);
		textInput.val(s);	
	}
	
	function ConvertStringToUppercaseGreek(str)
	{
		str = str.toUpperCase();
		var upperEnglish = ['A', 'B', 'G', 'D', 'E', 'Z', 'H', 'U', 'I', 'K', 'L', 'M', 'N', 'J', 'O', 'P', 'R', 'S', 'T', 'Y', 'F', 'X', 'C', 'V', 'Y', 'Z', 'W'];
		var upperGreek =   ['Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω', 'Υ', 'Ζ', 'Σ'];
		var str = ArrayCharactersReplace (upperEnglish , upperGreek , str);
		return str;
	}
	
	function RemoveCharactersThatAreNotNumbers(textInput)
	{
		var str = textInput.val();
		str = str.replace(/[^0-9]/gi,'');	
		textInput.val(str);	
	}
	
	function RemoveCharactersThatAreNotCapitalGreek(str)
	{
		str = str.replace(/[^Α-Ω]/gi,'');	
		return str;		
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
	
    
</body>

</html>


