 <div class="col-md-6 form-group mb-3" id="practices_div">
	<label for="practice_id">Select a Practice<span class="error">*</label>
	 <!-- @selectpractices("practice_id",["id"=>"practices"])--> <!-- selectGroupedPractices -->
	 @selectpracticespcp("practice_id",["id"=>"practices" ,"onchange"=>"carePlanDevelopment.onPracticepcpChange(this)"])
</div>

<div class="col-md-6 form-group mb-3" id="providers_div">  
	<label for="provider_id">Select Provider<span class="error">*</label>	
	 @selectprovider("provider_id",["id" =>"provider_id","onchange"=>"carePlanDevelopment.onProviderChange(this)"])
</div>

<div class="col-md-4 form-group mb-3" id="providers_name" style="display:none";> 
	<label for="provider_id">Provider Name<span class="error">*</label>	
	@text("provider_name",["id"=>"pro_name"]) <!-- updated by pranali on 29Oct2020 previously it is name="name" -->
</div>
<div class="col-md-6 form-group mb-3"> 
	<label for="specialist_id">Select Speciality<span class="error">*</label>	
	 @selectspeciality("specialist_id",[])
</div>
<div class="col-md-6 form-group mb-3"> 
	<label for="provider_subtype_id">Select Credential<span class="error">*</label>
	
	 @selectpcppractices("provider_subtype_id",[])
</div>

<div class="col-md-6 form-group mb-3">
	<label for="phone_no">Phone Number<span class="error">*</label>
	@phone("phone_no")
	<!-- <input type="number" min="0" class="form-control" name="phone_no"  placeholder="">--><!-- class="phone_no" -->
</div>

<div class="col-md-6 form-group mb-3">
	<label for="last_visit_date">Date of Last Visit<span class="error">*</label>
	@date("last_visit_date")
	<!-- <input type="date" class="form-control" name="last_visit_date"  placeholder="">--><!-- class="last_visit_date" --> 
</div>

<div class="col-md-12 form-group mb-3"> 
	<label for="address">Address<span class="error">*</label>
	<!-- <textarea  class="form-control"  name="address" placeholder=""></textarea> --><!--class="address" -->
	@text("address")
</div>
