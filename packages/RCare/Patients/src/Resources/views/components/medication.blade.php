<div class="col-md-6 form-group mb-3 med_id">
	<label for="tags">Select Medication<span class='error'>*</span></label> 
	@selectmedications("med_id",["id"=>"medication_med_id","onchange"=>"carePlanDevelopment.selectMedicationOther(this.value)"])
</div>
<div class="col-md-4 form-group mb-3" style="display:none" id="med_name"> 
	<label for="tags">Medication Name<span class='error'>*</span></label>
	@text("med_description",["id"=>"description", "placeholder" => "Enter Medication Description Name"])
</div>
<div class="col-md-6 form-group mb-3 description">
	<label for="description">Description</label>
	@text("description",["id"=>"medication_description"])
</div>
<div class="col-md-4 form-group mb-3"> 
	<label for="purpose">Purpose<span class='error'>*</span></label>
	@text("purpose",["id"=>"medication_purpose"])
</div>
<div class="col-md-4 form-group mb-3">
	<label for="strength">Strength<span class='error'>*</span></label>
	@text("strength",["id"=>"medication_strength"])
</div>
<div class="col-md-4 form-group mb-3">
	<label for="dosage">Dosage<span class='error'>*</span></label>
	@text("dosage",["id"=>"medication_dosage"])
</div>
<div class="col-md-4 form-group mb-3">
	<label for="route">Route<span class='error'>*</span></label>
	@text("route",["id"=>"medication_route"])
</div>
<div class="col-md-4 form-group mb-3">
	<label for="frequency">Frequency<span class='error'>*</span></label>
	@text("frequency",["id"=>"medication_frequency"])
</div>
<div class="col-md-4 form-group mb-3">
	<label for="time">Duration<span class='error'>*</span></label>
	@text("duration",["id"=>"duration"])
</div>
<div class="col-md-6 form-group mb-3">
	<label for="pharmacy_name">Pharmacy Name</label>
	@text("pharmacy_name",["id"=>"pharmacy_name"])
</div>
<div class="col-md-6 form-group mb-3">
	<label for="pharmacy_phone_no">Pharmacy Phone Number</label>
	@phone("pharmacy_phone_no",["id"=>"pharmacy_phone_no"])
</div>
<div class="col-md-6 form-group mb-3">
	<label for="drug_reaction">Adverse Drug Reactions</label>
	@text("drug_reaction",["id"=>"medication_drug_reaction"])
</div>
<div class="col-md-6 form-group mb-3">
	<label for="pharmacogenetic_test">Pharmacogenetics Test</label>
	@text("pharmacogenetic_test",["id"=>"medication_pharmacogenetic_test"])
</div>