<script>


function registrationForm(){
	this.ID 				= '<?php JQget($J, 'RegistrationForm') 	?>';
	this.FirstName 			= '<?php JQget($J, 'FirstName') 		?>';
	this.LastName 			= '<?php JQget($J, 'LastName') 			?>';
	this.FathersName 		= '<?php JQget($J, 'FathersName')		?>';
	this.MothersName 		= '<?php JQget($J, 'MothersName') 		?>';
	this.BirthYear 			= '<?php JQget($J, 'BirthYear') 		?>';
	this.EidEklAr 			= '<?php JQget($J, 'EidEklAr') 			?>';	
	this.EidEklArithmHtml 	= '<?php JQget($J, 'EidEklArithmHtml')	?>';
// ------------------------------	
	this.Email 					= '<?php JQget($J, 'Email') 				?>';
	this.Mobile 				= '<?php JQget($J, 'Mobile')				?>';
	this.CountryCodeMobile 		= '<?php JQget($J, 'CountryCodeMobile') 	?>';
	this.FixedPhone 			= '<?php JQget($J, 'FixedPhone') 			?>';
	this.CountryCodeFixedPhone 	= '<?php JQget($J, 'CountryCodeFixedPhone')	?>';
// ------------------------------
	this.BtnSubmit 			= '<?php JQget($J, 'BtnSubmit') 		?>';
	this.BtnFind 			= '<?php JQget($J, 'BtnFind') 			?>';
	this.Error_Modal 		= '<?php JQget($J, 'Error_Modal') 		?>';
	this.Error_Modal_Msg	= '<?php JQget($J, 'Error_Modal_Msg') 	?>';
	this.ErrorMsgAddress	= '<?php JQget($J, 'ErrorMsgAddress') 	?>';
// ------------------------------	
	this.Country		= '<?php JQget($J, 'Country') 		?>';	
	this.ForeignDiv		= '<?php JQget($J, 'ForeignDiv') 	?>';
	this.ForeignRegion	= '<?php JQget($J, 'ForeignRegion') 	?>';	
	this.GreekDiv		= '<?php JQget($J, 'GreekDiv') 		?>';	
	this.Municipality	= '<?php JQget($J, 'Municipality') 	?>';	
	this.ForeignArea	= '<?php JQget($J, 'ForeignArea') 	?>';	
	this.Area			= '<?php JQget($J, 'Area') 			?>';
// ------------------------------		
	this.ForeignRegionSelect	= '<?php JQget($J, 'ForeignRegionSelect') 	?>';
// ------------------------------		
	this.Operator		= '<?php JQget($J, 'Operator') 		?>';	
	this.Eforeutiki		= '<?php JQget($J, 'Eforeutiki') 	?>';
	this.Av_12_11		= '<?php JQget($J, 'Av_12_11') 		?>';	
	this.Av_19_11		= '<?php JQget($J, 'Av_19_11') 		?>';
}


var frmRegistration = new registrationForm();

$(document).ready(function() {
	ClearData();
	InitializeTextInputEvents();
	InitializeComboChangeEvents();
	InitializeFormValidator();
});

function InitializeTextInputEvents(){
	$('<?php JQget($J, 'FirstName') ?>')[0].oninput = function(){
		FixTextInputCharacters(	$(frmRegistration.FirstName));
 	};
	
	$('<?php JQget($J, 'LastName') ?>')[0].oninput = function(){
		FixTextInputCharacters(	$(frmRegistration.LastName));
	};

	$('<?php JQget($J, 'FathersName') ?>')[0].oninput = function(){
		FixTextInputCharacters(	$(frmRegistration.FathersName));
	};
	
	$('<?php JQget($J, 'MothersName') ?>')[0].oninput = function(){
		FixTextInputCharacters(	$(frmRegistration.MothersName));
	};
	
	$('<?php JQget($J, 'BirthYear') ?>')[0].oninput = function(){
		RemoveCharactersThatAreNotNumbers($(frmRegistration.BirthYear));
	};
	
	$('<?php JQget($J, 'Mobile') ?>')[0].oninput = function(){
		RemoveCharactersThatAreNotNumbers($(frmRegistration.Mobile));
	};

	$('<?php JQget($J, 'FixedPhone') ?>')[0].oninput = function(){
		RemoveCharactersThatAreNotNumbers($(frmRegistration.FixedPhone));
	};	
}




function InitializeComboChangeEvents(){	
	$('<?php JQget($J, 'Country') ?>').change(function(){
		$(frmRegistration.ErrorMsgAddress).html('');
		$(frmRegistration.ErrorMsgAddress).hide();
		var SelectedCountry = $(frmRegistration.Country).val();
		if(SelectedCountry=='GR'){
			$(frmRegistration.ForeignDiv).hide();
			$(frmRegistration.GreekDiv).show();
		}else{
			$.post('ajax/country_change.php', {Country: $(frmRegistration.Country).val()}, function(data){
				if(data.Error == 0){
					$(frmRegistration.ForeignRegionSelect).html(data.html);
					frmRegistration.ForeignRegion = data.RegionElement;
				}			
			}, "json");	
			$(frmRegistration.ForeignDiv).show();
			$(frmRegistration.GreekDiv).hide();
		}
	});
	
	$('<?php JQget($J, 'Municipality') ?>').change(function(){
		$(frmRegistration.ErrorMsgAddress).html('');
		$(frmRegistration.ErrorMsgAddress).hide();
	});	
	
	
	$('<?php JQget($J, 'CountryCodeMobile') ?>').change(function(){
		$(frmRegistration.ID).data('formValidation').revalidateField('Mobile');
	});	
	
	
	$('<?php JQget($J, 'CountryCodeFixedPhone') ?>').change(function(){
		$(frmRegistration.ID).data('formValidation').validateField('FixedPhone');
	});
	
}

function submitData(){
	var isValid2 = true;
	$(frmRegistration.ID).data('formValidation').validate();
	var isValid = $(frmRegistration.ID).data('formValidation').isValid();
	
	if($(frmRegistration.Municipality).val()=='' && $(frmRegistration.Country).val()=='GR'){
		$(frmRegistration.ErrorMsgAddress).html('Παρακαλούμε επιλέξτε τον (Καλλικρατικό) Δήμο στον οποίο διαμένετε!');
		$(frmRegistration.ErrorMsgAddress).show();
		isValid2 = false;
	}
	if(!isValid || !isValid2){
		if(!isValid){
			$(frmRegistration.Email).focus();
		}else{
			$(frmRegistration.Country).focus();
		}
		return;
	}
	
	var Operator = $(frmRegistration.Operator).prop('checked') ? 1: 0;
	var Eforeutiki = $(frmRegistration.Eforeutiki).prop('checked') ? 1: 0;
	var Av_12_11 = $(frmRegistration.Av_12_11).prop('checked') ? 1: 0;
	var Av_19_11 = $(frmRegistration.Av_19_11).prop('checked') ? 1: 0;
		
	$.post('ajax/save.php', {EidEklArithm: $(frmRegistration.EidEklAr).val(), 
							 FirstName: $(frmRegistration.FirstName).val(), 
							 LastName: $(frmRegistration.LastName).val(), 
							 FathersName: $(frmRegistration.FathersName).val(), 
							 MothersName: $(frmRegistration.MothersName).val(), 
							 
							 BirthYear: $(frmRegistration.BirthYear).val(),
							 Email: $(frmRegistration.Email).val(), 
							 Country: $(frmRegistration.Country).val(), 
							 
							 CountryCodeMobile: $(frmRegistration.CountryCodeMobile).val(), 
							 Mobile: $(frmRegistration.Mobile).val(), 
							 
							 CountryCodeFixedPhone: $(frmRegistration.CountryCodeFixedPhone).val(), 
							 FixedPhone: $(frmRegistration.FixedPhone).val(),
							 
							 Municipality: $(frmRegistration.Municipality).val(), 
							 Area: $(frmRegistration.Area).val(),
							 ForeignRegion: $(frmRegistration.ForeignRegion).val(), 
							 ForeignArea: $(frmRegistration.ForeignArea).val(), 
							 
							 Operator: Operator, Eforeutiki: Eforeutiki, 
							 Av_12_11: Av_12_11, Av_19_11: Av_19_11
					 }, function(data){
		if(data.Error == 0){
			//location.replace("step_2.php?SID=" + data.SID);		
		}else{
			$("#focus").show();
			$("#focus").focus();
			$("#focus").hide();
			$(frmRegistration.Error_Modal_Msg).html(data.ErrorDescr);
			$(frmRegistration.Error_Modal).modal('show');
		}
	}, "json");
}


function FindEidEklArithm(){
	$(frmRegistration.ID).data('formValidation').revalidateField('FirstName');	
	$(frmRegistration.ID).data('formValidation').revalidateField('LastName');	
	$(frmRegistration.ID).data('formValidation').revalidateField('FathersName');	
	$(frmRegistration.ID).data('formValidation').revalidateField('MothersName');	
	$(frmRegistration.ID).data('formValidation').revalidateField('BirthYear');		
	var isValid1 = $(frmRegistration.ID).data('formValidation').isValidField('FirstName')===true;
	var isValid2 = $(frmRegistration.ID).data('formValidation').isValidField('LastName')===true;
	var isValid3 = $(frmRegistration.ID).data('formValidation').isValidField('FathersName')===true;
	var isValid4 = $(frmRegistration.ID).data('formValidation').isValidField('MothersName')===true;
	var isValid5 = $(frmRegistration.ID).data('formValidation').isValidField('BirthYear')===true;
	if(!(isValid1 && isValid2 && isValid3 && isValid4 && isValid5)){
		var ErrorDescr = 
			'<span style="color:red">Για τη αναζήτηση Ειδικού Εκλογικού Αριθμού θα πρέπει πρώτα να εισάγεται <br/>τα πεδία επώνυμο, όνομα, πατρώνυμο, μητρώνυμο και έτος γέννησης!</span>';
		$(frmRegistration.EidEklArithmHtml).html(ErrorDescr);
		return;
	}
	
	$.post('ajax/eid_ekl_arithm.php', {FirstName: $(frmRegistration.FirstName).val(), LastName: $(frmRegistration.LastName).val(), 
							    	   FathersName: $(frmRegistration.FathersName).val(), MothersName: $(frmRegistration.MothersName).val(), 
							    	   BirthYear: $(frmRegistration.BirthYear).val()}, function(data){
		if(data.Error == 0){
			$(frmRegistration.EidEklArithmHtml).html(data.html);
			$(frmRegistration.EidEklAr).val(data.EidEklAr);			
			$(frmRegistration.FirstName).attr("disabled", true); 
			$(frmRegistration.LastName).attr("disabled", true); 
			$(frmRegistration.FathersName).attr("disabled", true); 
			$(frmRegistration.MothersName).attr("disabled", true); 
			$(frmRegistration.BirthYear).attr("disabled", true);
			$(frmRegistration.BtnFind).attr("disabled", true);
			$(frmRegistration.BtnSubmit).attr("disabled", false);
			$(frmRegistration.BtnSubmit).show();
			$(frmRegistration.FirstName).val(data.FirstName);	
			$(frmRegistration.LastName).val(data.LastName);
			$(frmRegistration.FathersName).val(data.FathersName);	
			$(frmRegistration.MothersName).val(data.MothersName);		
		}else if(data.Error==110){	
			$(frmRegistration.ID).data('formValidation').validate();
			$(frmRegistration.EidEklArithmHtml).html(data.ErrorDescr);
			$(frmRegistration.EidEklAr).val('');
		}else{			
			$(frmRegistration.ID).data('formValidation').validate();
			$(frmRegistration.Error_Modal_Msg).html(data.ErrorDescr);
			$(frmRegistration.Error_Modal).modal('show');
		}
	}, "json");
}

function ClearData(){
	$("#focus").hide();	
	$(frmRegistration.FirstName).val('');
	$(frmRegistration.LastName).val('');
	$(frmRegistration.FathersName).val('');
	$(frmRegistration.MothersName).val('');
	$(frmRegistration.BirthYear).val('');
	$(frmRegistration.EidEklAr).val('');
	$(frmRegistration.EidEklArithmHtml).html('');
	$(frmRegistration.ForeignRegionSelect).val('');
	$(frmRegistration.Email).val('');
	$(frmRegistration.Mobile).val('');
	$(frmRegistration.FixedPhone).val('');
	$(frmRegistration.Municipality).val('');
	$(frmRegistration.ErrorMsgAddress).html('');
	$(frmRegistration.ErrorMsgAddress).hide();
	$(frmRegistration.CountryCodeMobile).val('GR');
	$(frmRegistration.CountryCodeFixedPhone).val('GR');	
	$(frmRegistration.Country).val('GR');
	$(frmRegistration.FirstName).attr("disabled", false); 
	$(frmRegistration.LastName).attr("disabled", false); 
	$(frmRegistration.FathersName).attr("disabled", false); 
	$(frmRegistration.MothersName).attr("disabled", false); 
	$(frmRegistration.BirthYear).attr("disabled", false); 
	$(frmRegistration.BtnFind).attr("disabled", false); 
	$(frmRegistration.Operator).prop('checked', false);
	$(frmRegistration.Eforeutiki).prop('checked', false);
	$(frmRegistration.Av_12_11).prop('checked', false);
	$(frmRegistration.Av_19_11).prop('checked', false);	
	$(frmRegistration.ForeignDiv).hide();
	$(frmRegistration.BtnSubmit).attr("disabled", true);
	$(frmRegistration.LastName).focus();	
}



function InitializeFormValidator() {
    $(frmRegistration.ID)
        .on('init.field.fv', function(e, data) {
            var $parent = data.element.parents('.form-group'),
                $icon = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
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
                FirstName: {
                    validators: {
                        notEmpty: {
                            message: 'Το πεδίο Όνομα είναι υποχρεωτικό!'
                        },
                        stringLength: {
                            min: 2,
                            message: 'Παρακαλούμε εισάγετε το όνομα σας'
                        }
                    }
                },
                LastName: {
                    validators: {
                        notEmpty: {
                            message: 'Το πεδίο Επώνυμο είναι υποχρεωτικό!'
                        },
                        stringLength: {
                            min: 2,
                            message: 'Παρακαλούμε εισάγετε ολογράφως το επώνυμο σας'
                        }
                    }
                },
                FathersName: {
                    validators: {
                        notEmpty: {
                            message: 'Το πεδίο Πατρώνυμο είναι υποχρεωτικό!'
                        },
                        stringLength: {
                            min: 2,
                            message: 'Παρακαλούμε εισάγετε έγκυρο Πατρώνυμο! (τουλάχιστον 2 χαρακτήρες)'
                        }
                    }
                },
                MothersName: {
                    validators: {
                        notEmpty: {
                            message: 'Το πεδίο Mητρώνυμο είναι υποχρεωτικό!'
                        },
                        stringLength: {
                            min: 2,
                            message: 'Παρακαλούμε εισάγετε έγκυρο Mητρώνυμο! (τουλάχιστον 2 χαρακτήρες)'
                        }
                    }
                },
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
}



function FixedPhoneValidator(FixedPhone){
	if(FixedPhone=='') return true;
	var s = FixedPhone.length;
	if(FixedPhone=='') return true;
	if($(frmRegistration.CountryCodeFixedPhone).val()=='GR'){
		var b = FixedPhone.substr(0, 1);	
		return ((s==10) && (b=='2'));
	}else{
		return (s>4);
	}


}

function MobileValidator(Mobile){
	if(Mobile=='') return true;
	if($(frmRegistration.CountryCodeMobile).val()=='GR'){
		var s = Mobile.length;
		var b = Mobile.substr(0, 2);
		return ((s==10) && (b=='69'));
	}else{
		var s = Mobile.length;
		return (s>4);
	}
}

// Removes space characters and turns letters to greek capital
function FixTextInputCharacters(textInput){
	var s = textInput.val();
	s = s.includes(" ") ? s.replace(" ","") : s;
	s = s.toUpperCase();
	s = ConvertStringToUppercaseGreek(s);
	s = RemoveCharactersThatAreNotCapitalGreek(s);
	textInput.val(s);	
}

function ConvertStringToUppercaseGreek(str){
	str = str.toUpperCase();
	var upperEnglish = ['A', 'B', 'G', 'D', 'E', 'Z', 'H', 'U', 'I', 'K', 'L', 'M', 'N', 'J', 'O', 'P', 'R', 'S', 'T', 'Y', 'F', 'X', 'C', 'V', 'Y', 'Z', 'W'];
	var upperGreek =   ['Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω', 'Υ', 'Ζ', 'Σ'];
	var str = ArrayCharactersReplace (upperEnglish , upperGreek , str);
	return str;
}

function RemoveCharactersThatAreNotNumbers(textInput){
	var str = textInput.val();
	str = str.replace(/[^0-9]/gi,'');	
	textInput.val(str);	
}

function RemoveCharactersThatAreNotCapitalGreek(str){
	str = str.replace(/[^Α-Ω]/gi,'');	
	return str;		
}

function ArrayCharactersReplace(search, replace, subject, countObj) {
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
    if (typeof(search) === 'object' && typeof(replace) === 'string') {
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


