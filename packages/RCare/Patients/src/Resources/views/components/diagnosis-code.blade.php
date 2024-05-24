<div class="row col-md-12">
	<div class="col-md-6">
		<label>Condition<span class="error">*</span> :</label>	  
			<input type="hidden" name="condition" value="">
			@selectdiagnosiscondition("diagnosis",["id" => "diagnosis_condition", "onchange" =>"carePlanDevelopment.clearConditionForm(this)"])
			<!-- selectCarePlancondition("diagnosis",["id" => "diagnosis_condition","onchange"=>"carePlanDevelopment.changeCondition(this)"]) -->
	</div>
	<div class="col-md-2">
		<label><span class="error"></span>&nbsp; </label>
		<button type="button" class="col-md-12 btn btn-primary " onclick = "carePlanDevelopment.changeCondition(this)" id="render_plan_form" >Display Care Plan</button>
	</div>
	<div class="col-md-4">
		<label>Code<span class="error">*</span> :</label>
			<input type="hidden" id="codeid">
			<!-- hidden("prev_code",["id" => "prev_code"]) -->
			<!-- text("code",["id" => "diagnosis_code"]) -->
			@selectconditioncode("code",["id" => "diagnosis_code","onchange"=>"carePlanDevelopment.changeCode(this)"])

	</div>
	<div class="col-md-3 otherlist" id="otherlist"style="display:none" >
		<label>New Code <span class="error">*</span>:</label>
		@text("new_code",["id" => "new_code"])
	</div>

	
	<div class ="eneable_disable_btn" id="eneable_disable_btn" style="display:none">
        <button type="button" class="btn btn-primary mt-2 ml-3" id="enable_diagnosis_button" onclick="carePlanDevelopment.enableDiagnosisbutton(this)">Enable Editing</button>
		<button type="button" class="btn btn-primary mt-2 ml-3" id="disable_diagnosis_button" onclick="carePlanDevelopment.disableDiagnosisbutton(this)">Disable Editing</button>
	</div>    

	<div class="col-md-12">
		<label for="Template">Symptoms<span class="error">*</span> :</label>
			@text("symptoms[]", ["placeholder" => "Enter Symptoms", "id" => "symptoms_0"])
		<i class="plus-icons i-Add" id="append_symptoms_icons" class="btn btn-sprimary float-left"  onclick="carePlanDevelopment.additionalsymptoms(this)" title="Add symptons" ></i>
		<div class="col-md-10 mb-3" id="append_symptoms"></div>
	</div>

	<div class="col-md-12">
		<label for="contactNumber">Goals<span class="error">*</span> :</label>
			@text("goals[]",["placeholder" => "Enter Goal", "id" => "goals_0"])
		<i class="plus-icons i-Add" id="append_goals_icons"  class="btn btn-sprimary float-left"  onclick="carePlanDevelopment.additionalgoals(this)" title="Add goals" ></i>
		<div class="col-md-10 mb-3" id="append_goals"></div>
	</div>   

	<div class="col-md-12">
		<label for="emailTo">Tasks<span class="error">*</span> :</label>
		<textarea name="tasks[]" id="tasks_0" class="forms-element form-control"  placeholder="Enter Task" ></textarea>
		<div class="invalid-feedback"></div>
		<i class="plus-icons i-Add" id="append_tasks_icons"  class="btn btn-sprimary float-left"  onclick="carePlanDevelopment.additionaltasks(this)" title="Add task" ></i>
		<div class="col-md-10 mb-3" id="append_tasks"></div>
	</div>
	<!--div class="col-md-12">
		<label for="emailTo">Care Team:</label>
		<textarea class="forms-element form-control" name="support" id="support"></textarea>
		<div class="invalid-feedback"></div>
	</div-->

	<div class="col-md-12">
		<label for="Template">Comment:</label>
		<textarea class="forms-element form-control" id="diagnosis_comments" name="comments"></textarea>
		<div class="invalid-feedback"></div>
	</div>
</div>