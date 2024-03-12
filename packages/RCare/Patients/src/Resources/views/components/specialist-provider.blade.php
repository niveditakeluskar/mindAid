<div class="col-md-6 form-group mb-3" id="practices_div">
    <label for="practice_id">Select a Practice<span class="error">*</span></label>
     @selectpracticeswithother("practice_id",["id"=>"practices","onchange"=>"carePlanDevelopment.onPracticeChange(this)"]) 
</div>
<div class="col-md-4 form-group mb-3" id="practice_name" style="display:none";> 
    <label for="practice_id">Practice Name<span class="error">*</span></label> 
    @text("practice_name",["id"=>"prac_name"]) <!-- updated by pranali on 29Oct2020 previously it is name="name" -->
</div>
<div class="col-md-6 form-group mb-3" id="providers_div">   
    <label for="provider_id">Select Provider<span class="error">*</span></label>   
     @selectprovider("provider_id",["id" =>"provider_id","onchange"=>"carePlanDevelopment.onProviderChange(this)"]) <!-- selectGroupedProvider -->
</div>

<div class="col-md-4 form-group mb-3" id="providers_name" style="display:none";> 
    <label for="provider_id">Provider Name<span class="error">*</span></label> 
    @text("provider_name",["id"=>"pro_name"]) <!-- updated by pranali on 29Oct2020 previously it is name="name" -->
</div>

<div class="col-md-6 form-group mb-3"> 
    <label for="specialist_id">Select Speciality<span class="error">*</span></label>   
     @selectspeciality("specialist_id",[])
</div>

<div class="col-md-6 form-group mb-3"> 
    <label for="provider_subtype_id">Select Credential<span class="error">*</span></label>
     @selectpcppractices("provider_subtype_id",[])
</div>

<div class="col-md-6 form-group mb-3">
    <label for="phone_no">Phone Number<span class="error">*</span></label>
    @phone("phone_no")
</div>

<div class="col-md-6 form-group mb-3">
    <label for="last_visit_date">Date of Last Visit<span class="error">*</span></label>
    @date("last_visit_date") 
</div>
 
<div class="col-md-12 form-group mb-3"> 
    <label for="address">Address<span class="error">*</span></label>
    <!-- <textarea  class="form-control"  name="address" placeholder=""></textarea> --><!--class="address" -->
    @text("address")
</div>

