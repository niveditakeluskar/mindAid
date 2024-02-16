<form id="review_family_patient_data_form" name="review_family_patient_data_form" action="{{route("care.plan.development.family.patient.data")}}" method="post">
	<div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button> 
        <strong> Patient data saved successfully! </strong><span id="text"></span>
    </div> 
	<div class="form-row col-md-12">
		@include('Theme::layouts.flash-message')
		@csrf 
		<?php 
			// $module_id    = Route::input('module_id');
			// $submodule_id = Route::input('submodule_id'); 
			// $stage_id     = getFormStageId($module_id, $submodule_id, 'Review Data');
			$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Review-Family-Patient');
			// $patient_id = Route::input('patient_id');
			// $billable = Route::input('billable');
		?>
		<input type="hidden" name="patient_id" value="{{$patient_id}}" />
		<input type="hidden" name="uid" value="{{$patient_id}}" />
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" />	
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" />
		<input type="hidden" name="stage_id" value="{{$stage_id}}" />
		<input type="hidden" name="step_id" value="{{$step_id}}">
		<input type="hidden" name="form_name" value="review_family_patient_data_form">
 		<input type="hidden" name="tab" value="review-patient">
 		<input type="hidden" name="id">
		 <input type="hidden" name="billable" value ="{{$billable}}">
		@include('Patients::components.patient-data')
	</div>
	<div class="card-footer">
		<div class="mc-footer">
			<div class="row">
				<div class="col-lg-12 text-right">
				<!-- onclick="window.location.assign('#step-4')" -->
					<button type="submit" class="btn  btn-primary m-1" id="save_review_family_patient_data_form">Save</button>
				</div>
			</div>
		</div>
	</div>
	<div class="alert alert-danger" id="danger-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Please Fill All Mandatory Fields! </strong><span id="text"></span>
    </div>
</form>