<div class="row">
	<div class="col-lg-12 mb-3">
		<div class="card">		
			<form id="call_wrap_up_form" name="callwrapup_form" action="{{ route("care.plan.call.callwrapup") }}" method="post"> 
				<div class="card-body">
					<div class="row">
						@include('Theme::layouts.flash-message')
						@csrf 
						<?php
						$module_id = getPageModuleName();
						$submodule_id = getPageSubModuleName();
						$stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Call Wrap Up');
						?>
						<input type="hidden" name="uid" value="{{$patient_id}}" />
						<input type="hidden" name="start_time" value="00:00:00"> 
						<input type="hidden" name="end_time" value="00:00:00">
						<input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
						<input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />                    
						<input type="hidden" name="stage_id" value="{{$stage_id}}" />
                        <input type="hidden" name="step_id" value="0">
                        <input type="hidden" name="form_name" value="callwrapup_form">
						<input type="hidden" name="billable" value ="<?php if($patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
						<?php //print_r($billable); ?>
						<input type="hidden" name="bill_practice" value ="{{$billable}}">
						

						<div class="col-md-12 mb-4">
							<div class="alert alert-success" id="success-alert" style="display: none;">
								<button type="button" class="close" data-dismiss="alert">x</button>
								<strong>Call wrap-up data save successfully! </strong><span id="text"></span>
							</div>
							<!-- <button type="button" class="btn  btn-primary m-1 office-visit-save" id="view_care_plan">View Care Plan</button> -->
							<a href="/ccm/{{\Request::segment(2)}}/patient-care-plan/{{$patient_id}}" class="btn btn-primary" target="_blank">Print Care Plan</a>
						</div>	
						<div class="col-md-12 form-group mb-4">
						
							<!--label>
								<yes-no name="finalize_cpd" label-no="No" label-yes="Yes">Finalize</yes-no>
							</label-->Finalize
							<label class="switch ">
								<input type="checkbox" name="finalize_cpd"  value="" <?php if($patient_enroll_date[0]->finalize_cpd == 1){echo 'checked'; } ?>>
								<span class="slider round"></span>
							</label>
						</div>			
						<div class="col-md-12 mb-4" id="care_plan_output" style="display:none">
							@include('Ccm::sub-steps.care-plan-output')
						</div>
						<div class="col-md-12 mb-4">
							<textarea class="form-control" name="notes" placeholder="Follow-up notes regarding next month's phone call"></textarea>
						</div>
					</div>
				</div> 
				<div class="card-footer">
					<div class="mc-footer">
						<div class="row">
							<div class="col-lg-12 text-right">
								<button type="submit" class="btn  btn-primary m-1" id="save_call_wrap_up_form">Save</button>
								<a href="/ccm/care-plan-development-patients" type="button" class="btn  btn-info m-1">Back to Patient List</a>
							</div>
						</div>
					</div>
				</div>			
			</form>
		</div>
	</div>
</div>
