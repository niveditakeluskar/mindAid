<form id="review_service_dialysis_form" name="review_service_dialysis_form" action="{{ route("care.plan.development.services") }}" method="post"> 
	  <div class="form-row col-md-12">
		@include('Theme::layouts.flash-message')
		@csrf 
		<?php
			// $module_id    = Route::input('module_id');
			// $submodule_id = Route::input('submodule_id'); 
			// $stage_id     = getFormStageId($module_id, $submodule_id, 'Review Data');
			$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Review-Service-Dialysis');
			// $patient_id = Route::input('patient_id');
			// $billable = Route::input('billable');
		?>
		<input type="hidden" name="patient_id" value="{{$patient_id}}" >
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" >
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" >
		<input type="hidden" name="stage_id" value="{{ $stage_id}}" >
		<input type="hidden" name="step_id" value="{{ $step_id}}">
        <input type="hidden" name="form_name" value="review_service_dialysis_form">
		<input type="hidden" name="service_type" value="dialysis">
		<input type="hidden" name="tab" value="review-services">
		<input type="hidden" name="hid" class="hid" value='3'>
		<input type="hidden" name="id">
		<input type="hidden" name="billable" value ="{{ $billable }}">
		@include('Patients::components.health-service')
	</div>
	<div class="card-footer">
		<div class="mc-footer">
			<div class="row">
				<div class="col-lg-12 text-right">
					<button type="submit" class="btn  btn-primary m-1" id="save_review_service_dialysis_form">Save</button>
				</div>
			</div>
		</div>
	</div>
	<div class="alert alert-danger" id="danger-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Please Fill All mandatory Fields! </strong><span id="text"></span>
    </div>
	<div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Services data saved successfully! </strong><span id="text"></span>
    </div>
</form> 

<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="dialysis-review-services-list" class="display datatable table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                <thead >
                    <tr> 
                        <th>Sr</th>
                        <th>Types</th>
                        <th>Purpose</th>
                        <th>Company Name</th>
                        <th>Prescribing Provider</th> 
                        <th>Frequency</th> 
                        <th>Service Start Date</th>   
                        <th>Service End Date</th>            
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