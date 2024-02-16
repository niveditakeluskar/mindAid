<div class="row">
	<div class="col-lg-12 mb-3">
		<!-- 	<div class="mb-3" ><b>Call Wrap up</b></div> -->
		<div class="card">
			<form id="hippa_form" name="hippa_form" action="{{ route("monthly.monitoring.call.hippa") }}" method="post"> 
				<div class="card-body">
					<div class="row">
						@include('Theme::layouts.flash-message') 
						@csrf 
						<?php
							$module_id    = getPageModuleName();
							$submodule_id = getPageSubModuleName();
							$stage_id =  getFormStageId($module_id , $submodule_id, 'Verification');
						?>
						<input type="hidden" name="uid" value="{{$patient_id}}" />
						<input type="hidden" name="start_time" value="00:00:00">
						<input type="hidden" name="end_time" value="00:00:00">
						<input type="hidden" name="module" value="{{ $module_id }}" />
						<input type="hidden" name="component" value="{{ $submodule_id }}" />
						<input type="hidden" name="stage_id" value="{{ $stage_id }}" />
						<input type="hidden" name="form_name" value="hippa_form" />
						<input type="hidden" name="step_id" value="0">
						<input type="hidden" name="billable" value ="<?php if($patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
						<div class="col-md-12">
							<div class="alert alert-success" id="success-alert" style="display: none;">
								<button type="button" class="close" data-dismiss="alert">x</button>
								<strong> Hippa data saved successfully! </strong><span id="text"></span>
							</div>
							<p class="mb-4"><b>Verify HIPAA script</b><br>
							<?php getHippaScriptContent($module_id, $submodule_id, getFormStageId($module_id , $submodule_id, 'Hippa')); ?></p>
							<div class="forms-element form-group d-inline-flex mb-2"> 
								<label class="radio radio-primary mr-4" for="verification">
									<input type="radio" name="verification" id="verification" value="1" formControlName="radio" 
									<?php (isset($callHipaaVerification[0]->verification) && ($callHipaaVerification[0]->verification == "1") ) ?print("checked") : '';?>>
									<span>HIPAA Verified <span class="error">*</span></span>
									<span class="checkmark"></span>
								</label>
							</div>
							<div class="invalid-feedback"></div>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="mc-footer">
						<div class="row">
							<div class="col-lg-12 text-right">
							<!-- onclick="window.location.assign('#step-4')" -->
								<button type="submit" class="btn  btn-primary m-1" id="save-hippa">Next</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>