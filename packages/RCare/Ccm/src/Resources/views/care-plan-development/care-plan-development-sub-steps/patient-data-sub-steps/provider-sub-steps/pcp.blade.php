<form id="provider_pcp_form" name="provider_pcp_form" action="{{route("care.plan.development.provider")}}" method="post"> 
	<div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button> 
        <strong> PCP Provider data saved successfully! </strong><span id="text"></span>
    </div> 
	<div class="form-row col-md-12">
		@include('Theme::layouts.flash-message')
		@csrf 
		<?php
			$module_id    = getPageModuleName();
			$submodule_id = getPageSubModuleName();
			$stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Data');
			$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Provider-PCP');
		?>
		<input type="hidden" name="patient_id" value="{{$patient_id}}" />
		<input type="hidden" name="practice_emr" value="{{ empty($patient_providers[0]->practice_emr) ? '' : $patient_providers[0]->practice_emr }}" />
		<input type="hidden" name="uid" value="{{$patient_id}}" />
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" />
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" />
		<input type="hidden" name="stage_id" value="{{ $stage_id}}" />
		<input type="hidden" name="step_id" value="{{$step_id}}">
		<input type="hidden" name="form_name" value="provider_pcp_form">
		<input type="hidden" name="practice_type" value="pcp">
		<input type="hidden" name="provider_type_id" value="1">
		<input type="hidden" name="billable" value ="<?php if($patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
		@include('Patients::components.provider')
	</div> 
	<div class="card-footer">
		<div class="mc-footer">
			<div class="row">
				<div class="col-lg-12 text-right">
				<!-- onclick="window.location.assign('#step-4')" -->
					<button type="submit" class="btn  btn-primary m-1" id="save_provider_pcp_form">Save</button>
				</div>
			</div>
		</div>
	</div>
	<div class="alert alert-danger" id="danger-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Please Fill All Mandatory Fields! </strong><span id="text"></span> 
    </div>
</form> 