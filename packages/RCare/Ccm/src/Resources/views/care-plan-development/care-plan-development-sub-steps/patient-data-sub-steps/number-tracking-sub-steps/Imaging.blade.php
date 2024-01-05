<form id="number_tracking_imaging_form" name="number_tracking_imaging_form" action="{{route("care.plan.development.numbertracking.imaging")}}" method="post">
	<div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Imaging data saved successfully! </strong><span id="text"></span>
	</div>
	<div class="alert alert-danger" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Please fill all mandatory fields! </strong><span id="text"></span>
    </div> 
	<div class="form-row col-md-12">
		@include('Theme::layouts.flash-message')
		@csrf 
		<?php
			$module_id    = getPageModuleName();
			$submodule_id = getPageSubModuleName(); 
			$stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Data');
			$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'NumberTracking-Imaging');
		?>
		<input type="hidden" name="patient_id" value="{{$patient_id}}" />
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" />
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" />
		<input type="hidden" name="stage_id" value="{{$stage_id}}" />		
		<input type="hidden" name="step_id" value="{{$step_id}}">
		<input type="hidden" name="form_name" value="number_tracking_imaging_form">
		<input type="hidden" name="billable" value ="<?php if(isset($patient_enroll_date[0]->finalize_cpd) && $patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
		@include('Patients::components.imaging')
	</div>
	<div class="card-footer">
		<div class="mc-footer"> 
			<div class="row">
				<div class="col-lg-12 text-right">				
					<button type="submit" class="btn  btn-primary m-1" id="save_number_tracking_vitals_form">Save</button>
				</div>
			</div>
		</div>
	</div>
</form> 

<!-- <div class="separator-breadcrumb border-top"></div>
<div id="msgsccess"></div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="imaging-list" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr> 
                        <th style="width:auto!important">Sr No.</th>
                        <th style="width:auto!important">Imaging</th>
                        <th style="width:auto!important">Imaging Date</th>
                        <th style="width:auto!important">Comment</th>   
                        <th style="width:auto!important">Action</th>    
                    </tr>
                </thead>
                <tbody>
                </tbody> 
            </table>
            
        </div>
    </div>
</div> -->