<form id="service_other_health_form" name="service_other_health_form" action="{{ route("care.plan.development.services") }}" method="post">
	<div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Other Health Services data saved successfully! </strong><span id="text"></span>
    </div>
	<div class="form-row col-md-12">
		@include('Theme::layouts.flash-message')
		@csrf 
		<?php
			$module_id    = getPageModuleName();
			$submodule_id = getPageSubModuleName();			
			$stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Data');
			$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Service-Other-Health');
		?>
		<input type="hidden" name="patient_id" value="{{$patient_id}}" >
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" >
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" >
		<input type="hidden" name="stage_id" value="{{ $stage_id}}" >
		<input type="hidden" name="step_id" value="{{ $step_id}}">
        <input type="hidden" name="form_name" value="service_other_health_form">
		<input type="hidden" name="service_type" value="other_health">
		<input type="hidden" name="hid" class="hid" value='7'>
		<input type="hidden" name="id">
    <input type="hidden" name="billable" value ="<?php if(isset($patient_enroll_date[0]->finalize_cpd) && $patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
		@include('Patients::components.dme')
	</div>
	<div class="card-footer">
		<div class="mc-footer">
			<div class="row">
				<div class="col-lg-12 text-right">
				<!-- onclick="window.location.assign('#step-4')" -->
					<button type="submit" class="btn  btn-primary m-1" id="save_service_other_health_form">Save</button>
				</div>
			</div>
		</div>
	</div>
	<div class="alert alert-danger" id="danger-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Please Fill All mandatory Fields! </strong><span id="text"></span>
    </div>
	<!-- <div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Other Health Services data saved successfully! </strong><span id="text"></span>
    </div> -->
</form>  


<div class="separator-breadcrumb border-top"></div>
<div class="row">
	<div class="col-12">
		<div class="table-responsive">
			<table id="other-services-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
				<thead >
					<tr> 
						<th>Sr</th>
						<th>Types</th>
						<th>Purpose</th>
						<th>Company Name</th>
						<th>Prescribing Provider</th>
						<th>Last Modified By</th>
						<th>Last Modified On</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				</tbody> 
			</table>
		</div>
	</div>
</div>