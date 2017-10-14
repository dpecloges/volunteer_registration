


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
			<input type="tel" class="form-control" placeholder="* Τηλέφωνο κινητό" 
					name="Mobile" id="<?php JQadd($J, "Mobile") ?>" maxlength="15" />
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
				name="FixedPhone" id="<?php JQadd($J, "FixedPhone") ?>" maxlength="15" />
		</div>
	</div>
</div>	


<div class="row">
	<div class="col-sm-6" id="MunicipalityDiv">
			<select class="form-control" id="Municipality" style="color: grey">
				<option value="" style="color: grey" >Επιλέξτε δήμο</option>
				<?php echo $municipalities ?>
			</select>
		</div>	
	<div class="col-sm-6"><input type="text" disabled="" class="form-control" id="Division" placeholder="Νομός / Τομέας" style="background-color:#fffdf3" /></div>
    </div>



