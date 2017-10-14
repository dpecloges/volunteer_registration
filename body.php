
<input type="text" id="focus"/>
<div class="form-group">
	<input class="form-control" type="text" placeholder="Επώνυμο (ολογράφως)" id="<?php JQadd($J, "LastName") ?>" name="LastName" maxlength="30" />
</div>
<div class="form-group">
	<input class="form-control" type="text" placeholder="Όνομα" id="<?php JQadd($J, "FirstName") ?>" name="FirstName" maxlength="30" />
</div>
<div class="form-group">
	<input class="form-control" type="text" placeholder="Πατρώνυμο" id="<?php JQadd($J, "FathersName") ?>" name="FathersName" maxlength="30" />
</div>
<div class="form-group">
	<input class="form-control" type="text" placeholder="Mητρώνυμο" id="<?php JQadd($J, "MothersName") ?>" name="MothersName" maxlength="30" />
</div>
<div class="well" style="height:500px!important">
	<span>* Ειδικός Εκλογικός Αριθμός <b>(Μάθε που ψηφίζεις)</b></span>
	<br />
	<br />
	<div class="row">
		<div class="col-sm-12">
			<input type="hidden" id="<?php JQadd($J, "EidEklAr") ?>" />
			<input class="form-control display-inline-block" placeholder="Έτος γέννησης" type="text" 
				id="<?php JQadd($J, "BirthYear") ?>" name="BirthYear" maxlength="4" style="width:150px" />
			&nbsp;&nbsp;                            
			<button class="btn btn-primary-small" type="button" id="<?php JQadd($J, "BtnFind") ?>" 
				onclick="FindEidEklArithm();return false">Αναζήτηση Ειδικού Εκλογικού Αριθμού</button>
		</div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-12">
			<span id="<?php JQadd($J, "EidEklArithmHtml") ?>"></span>
		</div>
	</div>
</div>
<br />
<br />			
<div class="form-group">
	<input type="email" class="form-control" placeholder="* Email" name="Email" id="<?php JQadd($J, "Email") ?>" maxlength="50" />
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<select class="form-control" id="<?php JQadd($J, "CountryCodeMobile") ?>">
				<?php echo $CountryCodes ?>
			</select>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<input type="tel" class="form-control" placeholder="Τηλέφωνο κινητό" 
					name="Mobile" id="<?php JQadd($J, "Mobile") ?>" maxlength="10" />
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<select class="form-control" id="<?php JQadd($J, "CountryCodeFixedPhone") ?>">
				<?php echo $CountryCodes ?>
			</select>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<input type="tel" class="form-control" placeholder="Τηλέφωνο σταθερό" 
				name="FixedPhone" id="<?php JQadd($J, "FixedPhone") ?>" maxlength="10" />
		</div>
	</div>
</div>	
<br/><br/>
<div class="well">
	<div class="row">
		<div class="col-sm-6"><b> * Τόπος στον οποίο διαμένετε</b></div>
	</div>
	<br/><br/>
	<div class="row">
		
		<div class="col-sm-6">
			<select class="form-control" id="<?php JQadd($J, "Country") ?>" style="color: grey">
				<?php echo $Countries ?>
			</select>
			
			
		</div>
	</div>	
	
	<br/>
	
	<br/>
	<div class="row" id="<?php JQadd($J, "GreekDiv") ?>">
		<div class="col-sm-6" id="MunicipalityDiv">
			<select class="form-control" id="<?php JQadd($J, "Municipality") ?>" style="color: grey">
				<option value="" style="color: grey" >* Επιλέξτε δήμο</option>
				<?php echo $municipalities ?>
			</select>
		</div>
			
		<div class="col-sm-6">
			<input type="text" class="form-control" id="<?php JQadd($J, "Area") ?>"  placeholder="Περιοχή"/>
		</div>
	</div>
	<div class="row" id="<?php JQadd($J, "ForeignDiv") ?>">
		<div class="col-sm-6" id="<?php JQadd($J, "ForeignRegionSelect") ?>">
			<select class="form-control" id="<?php JQadd($J, "ForeignRegion") ?>" style="color: grey">
				<option value="" style="color: grey" >City</option>
			</select>			
		</div>	
		<div class="col-sm-6">
			<input type="text" class="form-control" id="<?php JQadd($J, "ForeignArea") ?>" placeholder="Περιοχή"/>
		</div>
	</div>
	<br/>
	<div id="<?php JQadd($J, "ErrorMsgAddress") ?>" class="alert alert-danger"></div>		
	<br/>	
</div>
    
<br/><br/>


<div class="well">
	<br/>
	<div class="well">
		<div class="checkbox">
			<label><input type="checkbox" id="<?php JQadd($J, "Operator") ?>" value="">Θέλετε να απασχοληθείτε ως χειριστής;</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" id="<?php JQadd($J, "Eforeutiki") ?>" value="">Θέλετε να είστε μέλος της Εφορευτικής Επιτροπής;</label>
		</div>
	</div>
	<br/>
	<div class="well">
		Διαθεσιμότητα
		<div class="checkbox">
			<label><input type="checkbox" id="<?php JQadd($J, "Av_12_11") ?>" value="">1η Κυριακή - 12/11/2017</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" id="<?php JQadd($J, "Av_19_11") ?>" value="">2η Κυριακή - 19/11/2017</label>
		</div>
	</div>
</div>
<br/><br/>  

<div class="form-group">
	<textarea class="form-control" style="height:200px" placeholder="Σχόλια / προτάσεις" id="Comments"></textarea>
</div>
    
    

    
<br/><br/>  
    
    
    
    
    
    
    
    
    
    

