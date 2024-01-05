
<div class="col-md-6" id="contactlist">
	<label>Condition<span class="error">*</span>:</label>
	<input type="hidden" name="condition" value="">
	@selectCarePlancondition("diagnosis_id",["id" => "condition"])	
</div>
<div class="col-md-6 emaillist" id="emaillist">
	<label>Code <span class="error">*</span>:</label> 
	<input type="hidden" id="codeid">
	{{--@hidden("prev_code",["id" => "prev_code"])--}}
	{{--@text("code",["id" => "condition"])--}}
	@selectconditioncode("code",["id" => "code"])
	<br/>
	<!-- 	<i class="plus-icons i-Add"  class="btn btn-sprimary float-right"  id="addcode" title="Add New Code"></i> -->
</div>

<div class="col-md-3 otherlist" id="otherlist"style="display:none" >
	<label>New Code <span class="error">*</span>:</label>
	@text("new_code",["id" => "new_code"])
</div>


<div class="col-md-12 border-bottom pb-2">
	<label>Symptoms<span class="error">*</span> :</label>
	@text("symptoms[]", ["placeholder" => "Enter Symptoms", "id" => "symptoms_0", "class"=>"col-md-10"])
	<div class="col-md-10 pt-1" id="append_symptoms"></div>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalsymptoms" title="Add symptons"></i>
</div>
<div class="col-md-12 border-bottom pb-2">
	<label>Goals<span class="error">*</span> :</label>
	@text("goals[]",["placeholder" => "Enter Goals", "id" => "goals_0", "class"=>"col-md-10" ])
	<div class="col-md-10 pt-1" id="append_goals"></div>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalgoals" title="Add goals"></i>
</div>
<div class="col-md-12 border-bottom pb-2">
	<label>Tasks<span class="error">*</span> :</label>
	<textarea name="tasks[]" id="tasks_0" class="forms-element form-control col-md-10"  placeholder="" ></textarea>
	<div class="invalid-feedback"></div>
	<div class="col-md-10 pt-1" id="append_tasks"></div>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionaltasks" title="Add task"></i>
</div>
<!--div class="col-md-12 border-bottom-primary pb-4">
	<label>Care Team :</label>
	<textarea class="form-control forms-element" name="support" id="support"></textarea>
	<div class="invalid-feedback"></div>
</div-->  

<!-- priya cmnt on 13th Nov 2020  -->
<!-- <div class="col-md-12 border-bottom-primary pb-4 pt-2">		
	<label>Medications:</label> 
	<div class="ml-1">
		@selectmedications("medication_id[]",["id"=>"medication_med_id_0", "class"=>"medications col-md-3 mb-1","onchange"=>"addnewmedication(this)"])	
	</div>
	<div class="form-row mb-3 col-md-12" id="append_medication_details"></div><hr/>
	<div class="col-md-12" id="append_medications"></div>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalmedications" title="Add Medication"></i>
</div>

<div class="col-md-12 border-bottom-primary pb-4 pt-2">
	<label>Allergies:</label>
	@text("allergies[]", ["placeholder" => "Enter Allergies", "id" => "allergies_0"])
	<div class="col-md-12 pt-1" id="append_allergies"></div>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalallergies" title="Add symptons"></i>
</div>

<div class="col-md-12 border-bottom-primary pb-4 pt-2" id="vitels">
	<label>Vitals Data:</label>
	<div class="row">
		<div class="col-md-3  mb-3  form-group"  id="dropDownMenu">
			<label for="height">Height (in):</label>
			@text("height", ["id" => "height"])
		</div>
		<div class="col-md-3 form-group mb-3">
			<label for="weight">Weight (lbs):</label> 
			@text("weight", ["id" => "weight"])
		</div>
		<div class="col-md-4 form-group mb-3">
			<label for="bmi">BMI:</label>
			@text("bmi", ["id" => "bmi"])
		</div>
		<div class="col-md-3 form-group mb-3">
			<label for="bp">Blood Pressure:</label>
			<div class="row">
				<div class="col-md-5"  style="margin-left: 12px;">
					@text("bp", ["id" => "systolic","placeholder" => "Systolic"])
				</div>
				<div class="offset-md-1 col-md-5">
					@text("diastolic", ["id" => "diastolic", "placeholder" => "Diastolic"])
				</div>
			</div>
		</div>
		<div class="col-md-3 form-group mb-3">
			<label for="o2">O2 Saturation:</label>
			@text("o2", ["id" => "o2"])
		</div>
		<div class="col-md-4 form-group mb-3">
			<label for="pulse_rate">Pulse Rate:</label>
			@text("pulse_rate", ["id" => "pulse_rate"])
		</div>
	</div>
	<div class="form-row">
		<label>Health Data :</label>
		<div class="col-md-12  mb-3">
			<textarea class="form-control forms-element" name="other_vitals"></textarea>
			<div class="invalid-feedback"></div>
		</div>
	</div>
<div class="col-md-10 pb-4 pt-2">
	<label>Health Data :</label>
	@text("health_data[]", ["placeholder" => "Enter Health Data", "id" => "healthdata_0"])
	
	<div class="col-md-12 pt-1" id="append_healthdata"></div>
	 <hr/>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalhealthdata" title="Add Health Data"></i>
   </div>

</div>

<div class="col-md-12 pb-4 pt-2">
	<label>Labs:</label>
	<div class="row col-md-4">
		@selectlab("lab[]",["id" => "lab", "onchange"=>"addLabparam(this)", "class" => "col-md-12"])
	</div>
	<div class="row mb-3" id="append_labs_params_lab"></div><hr/>
	<div class="mb-3" id="append_labs"></div>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionallab" title="Add Lab"></i>
</div>

<div class="col-md-12 border-bottom-primary pb-4 pt-2">
	<label>Imaging :</label>
	@text("imaging[]", ["placeholder" => "Enter Imaging", "id" => "imaging_0"])
	<div class="col-md-12 pt-1" id="append_imaging"></div>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalimaging" title="Add imaging"></i>
</div>
 -->
<div class="col-md-12 border-bottom-primary pb-4 pt-2">
	<label>Comments:</label>
	<div class="col-md-10  mb-3">
		<textarea class="form-control forms-element" name="comments"></textarea>
		<div class="invalid-feedback"></div>
	</div>
</div>


