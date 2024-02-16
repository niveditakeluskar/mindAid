<!-- <style type="text/css">
	.wrapMulDrop ul li :hover{
    background-color: #fdfdfd!important;
	}
</style>  -->
<div class="row">	
	<div class="col-12">
		<div class="form-group">
			<label>Symptoms<span class='error'>*</span></label>
			@text("symptoms[]", ["placeholder" => "Enter Symptom", "id" => "symptoms_0", "class" => "col-md-10 form-control"])
		</div>
		<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalsymptoms" title="Add symptons"></i>
		<div class="col-md-12 form-group mb-3" id="append_symptoms"></div>
	</div>

	<div class="col-12">
		<div class="form-group">
			<label>Goals<span class='error'>*</span></label>
			@text("goals[]",["placeholder" => "Enter Goal", "id" => "goals_0", "class" => "col-md-10 form-control"])
		</div>
		<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalgoals" title="Add goals"></i>
		<div class="col-md-12 form-group mb-3" id="append_goals"></div>
	</div>

	<div class="col-12">
		<div class="form-group">
			<label>Tasks<span class='error'>*</span></label>
			<textarea name="tasks[]" class=" col-md-10 form-control forms-element"  id="tasks_0" placeholder="Enter Task"></textarea>
			<div class='invalid-feedback'></div>
		</div>
		<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionaltasks" title="Add task"></i>
		<div class="col-md-12 form-group mb-3" id="append_tasks"></div>
	</div>
	<!--div class="col-12">
		<div class="form-group">
			<label>Care Team</label>
			<textarea name="support" class="form-control col-md-10 forms-element"  id="support" placeholder="Enter Care Team"></textarea>
			<div class='invalid-feedback'></div>	
		</div>
	</div-->
	
<!-- 
	<div class="col-12">
		<div class="form-group">
				<label>Medications</label>
			<div class="row addtext" style="padding-left: 14px;">				
				@selectmedications("medications[]",["id"=>"medication_med_id_0", "class"=>"col-md-3","onchange"=>"addnewmedication(this)"])		
			</div>
		</div>
		<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalmedications" title="Add Medication"></i>
		<div class="col-md-12 form-group mb-3" id="append_medications"></div>
	</div>
	 
	<div class="col-12">
		<div class="form-group">
			<label>Allergies</label>
			@text("allergies[]", ["placeholder" => "Enter Allergy", "id" => "allergies_0", "class" => "col-md-10 form-control"])
		</div>
		<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalallergies" title="Add Allergy"></i>
		<div class="col-md-12 form-group mb-3" id="append_allergies"></div>
	</div>

	<div class="col-md-12 pb-4 pt-2">
		<label>Labs:</label>
		<div class="row col-md-4">
			@selectlab("labs[]",["id" => "lab_0", "class" => "col-md-12"])
		</div>
		<div class="mt-3" id="append_labs"></div>
		<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionallab" title="Add Lab"></i>
	</div>

	<div class="col-12">
		<div class="form-group">
			<label>Health Data</label>
			@text("health_data[]", ["placeholder" => "Enter Health", "id" => "health_0", "class" => "col-md-10 form-control"])
		</div>
		<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalhealth" title="Add Health Data"></i>
		<div class="col-md-12 form-group mb-3" id="append_health"></div>
	</div>
	<div class="col-12">
		<div class="form-group">
			<label>Vitals</label>
	            <div class="wrapMulDrop">
					<button type="button" class="multiDrop form-control col-md-11" id="multiDrop">Select <i style="float:right; border: 1px solid #ced4da; background: #f8f9fa;" class="icon ion-android-arrow-dropdown"></i></button>
					<ul id="follow_up_list">
						<li style="padding: 5px;">
							<label class="forms-element checkbox checkbox-outline-primary">
								<input class="" name="vitals['Height']" type="checkbox" value="Height"><span class="">Height</span><span class="checkmark"></span>				
							</label>
						</li>	
						<li style="padding: 5px;">
							<label class="forms-element checkbox checkbox-outline-primary">
								<input class="" name="vitals['Weight']" type="checkbox" value="Weight"><span class="">Weight</span><span class="checkmark"></span>				
							</label>
						</li>	
						<li style="padding: 5px;">
							<label class="forms-element checkbox checkbox-outline-primary">
								<input class="" name="vitals['BMI']" type="checkbox" value="BMI"><span class="">BMI</span><span class="checkmark"></span>				
							</label>
						</li>	
						<li style="padding: 5px;">
							<label class="forms-element checkbox checkbox-outline-primary">
								<input class="" name="vitals['Blood Pressure']" type="checkbox" value="Blood Pressure"><span class="">Blood Pressure</span><span class="checkmark"></span>				
							</label>
						</li>	
						<li style="padding: 5px;">
							<label class="forms-element checkbox checkbox-outline-primary">
								<input class="" name="vitals['O2 Saturation']" type="checkbox" value="O2 Saturation"><span class="">O2 Saturation</span><span class="checkmark"></span>				
							</label>
						</li>	
						<li style="padding: 5px;">
							<label class="forms-element checkbox checkbox-outline-primary">
								<input class="" name="vitals['Pulse Rate']" type="checkbox" value="Pulse Rate"><span class="">Pulse Rate</span><span class="checkmark"></span>				
							</label>
	                	</li>				
					</ul>
				</div>	   
		</div>
	</div>
	-->
</div>	

<div class="row">
	<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
	<div class="col-12 text-right form-group mb-4">
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
</div>

