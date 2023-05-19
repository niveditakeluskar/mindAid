<div class="row">
	<div class="col-lg-12 mb-3">
		<!-- <div class="mb-3" ><b>Verify Home Services</b></div> -->
		<!-- callHomeServiceVerification -->
		<div class="card">
			<form  id="homeservice_form" name="homeservice_form" action="{{ route("monthly.monitoring.call.homeservice") }}" method="post"> 
				<div class=" card-body">
					@csrf
					<?php
						$module_id = getPageModuleName();
						$submodule_id = getPageSubModuleName();
						$home_services_stage_id = getFormStageId($module_id, $submodule_id, 'Home Services');
					?>
					<input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
					<input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
					<input type="hidden" name="start_time" value="00:00:00">
					<input type="hidden" name="end_time" value="00:00:00">
					<input type="hidden" name="moduleId" value="{{ $module_id }}" />
					<input type="hidden" name="componentId" value="{{ $submodule_id }}" />
					<input type="hidden" name="stage_id" value="{{ $stage_id }}" />
					<input type="hidden" name="step_id" value="0">
					<div class="row">
						<div class="col-md-8 text">
							<div class="alert alert-success" id="success-alert" style="display: none;">
								<!-- <button type="button" class="close" data-dismiss="alert">x</button> -->
								<strong>Home services data saved successfully! </strong>
							</div>
							<div class="alert alert-warning" id="schedule-alert" style="display: none;">
								<!-- <button type="button" class="close" data-dismiss="alert">x</button> -->
								<strong>Home services Date Schedule successfully! </strong>
							</div>
							<div class="alert alert-danger" id="eligibility-alert" style="display: none;">
								<!-- <button type="button" class="close" data-dismiss="alert">x</button> -->
								<!-- Non Eligible Script!  -->
								<strong>Home Services data saved successfully</strong>
							</div>

							<!-- non eligible script -->
							<div id="home_services_no_div" class="row" style="display:none;">
							<div class="form-row col-md-8">
								<?php
									$home_services_step_id = getFormStageId($module_id, $submodule_id, $home_services_stage_id,'Non Eligible');
								?>
								@selectcontentscript("non-eligible-script",$module_id,$submodule_id,$home_services_stage_id,$home_services_step_id,["id"=>"non-eligible-script","class"=>"custom-select", "style"=>"display:none;"])
								<label class="ml-3 mb-4"><br/>
									<b style="text-align:center; color:red;">Non Eligible Script</b>
	            					<input type="hidden" name="template_type_id">
	            					<input type="hidden" name="content_title">
									<textarea hidden="hidden" name="non-eligible-script-textarea" class="form-control non-eligible-script-container" id="non-eligible-script-textarea"></textarea>
									<div id="non-eligible-script-div" class="non-eligible-script-container"></div>
								</label>
							</div>
							</div>
							<!-- end non-eligible Script -->

							<div class="mb-3" ><b style="text-align:center;">Verify Home Services</b></div>
							<p>Does a nurse or therapist come to your home to take care of you?<span class="error">*</span></p>
							<input type="hidden" name="query1" value="Does a nurse or therapist come to your home to take care of you?" > 
							<div class="forms-element form-group d-inline-flex mb-2">
								<label class="radio radio-primary mr-4">
									<input type="radio" name="therapist_come_home_care" id="services" value="1">
									<span>Yes</span>
									<span class="checkmark"></span>
								</label>
								<label class="radio radio-primary mr-3">
									<input type="radio" name="therapist_come_home_care" id="servicese" value="0">
									<span>No</span>
									<span class="checkmark"></span>
								</label> 
							</div>
							<div class="invalid-feedback"></div>
						</div>
					</div>
					<div id="home_service_yes_div" class="row">
						<div class="form-group col-md-12 text1">
							<p>Please check applicable boxes for the reasons that the nurse or therapist comes for home-visit<span class="error">*</span></p>
							<div class="forms-element">
								<label class="checkbox checkbox-outline-primary mb-4">
									<input type="checkbox"  name="wound_care" value="1"> <span>Wound Care</span><span class="checkmark"></span>
								</label>
								
								<label class="checkbox checkbox-outline-primary mb-4">
									<input type="checkbox" name="Injections_IV" value="2"><span>Injections and IV product</span><span class="checkmark"></span>
								</label>
								
								<label class="checkbox checkbox-outline-primary mb-4">
									<input type="checkbox" name="catheter" value="3"><span>catheter Changes</span><span class="checkmark"></span>
								</label>
								
								<label class="checkbox checkbox-outline-primary mb-4">
									<input type="checkbox" name="tubefeeding" value="4"><span>Tube Feedings</span><span class="checkmark"></span>
								</label>
								
								<label class="checkbox checkbox-outline-primary mb-4">
									<input type="checkbox" name="physio" value="5"><span>Physical Therapy (to regain movement or strength)</span><span class="checkmark"></span>
								</label>
								
								<label class="checkbox checkbox-outline-primary mb-4">
									<input type="checkbox"name="oc_therapy" value="6"><span>Occupational Therapy</span><span class="checkmark"></span>
								</label>
								
								<label class="checkbox checkbox-outline-primary mb-4">
									<input type="checkbox" name="speech_therapy" value="7"><span>Speech Therapy</span><span class="checkmark"></span>
								</label>
							</div>
							<div class="invalid-feedback"></div>
							<span id ="resons_error" style="color:red;"></span>
							<div class="mb-3" ><b style="text-align:center;">Reason for Home Visit <span class="error">*</span></b></div>
							<textarea class="form-control capital-first forms-element" aria-label="With textarea" name="reason_for_visit" placeholder=""></textarea>
							<div class="invalid-feedback"></div>
						</div>
						<div class="form-group col-md-12">
							Do you know when the Home Service ends?<span class="error">*</span>
							<input type="hidden" name="query2" value="Do you know when the Home Service ends?"> 
							<div class="forms-element d-inline-flex mb-2">
								<label class="radio radio-primary mr-3">
									<input type="radio" name="home_service_ends" id="dateend" value="1">
									<span>Yes</span>
									<span class="checkmark"></span>
								</label>
								<label class="radio radio-primary mr-3">
									<input type="radio" name="home_service_ends" id="datef" value="0">
									<span>No</span>
									<span class="checkmark"></span> 
								</label>
							</div>
							<div class="invalid-feedback"></div>
						</div>
						<div class="home-service-end-date col-md-12" id="1_box" style="display: none"> 
							<div class=" col-md-12">
								<label for="home-service-end-date">Select Home Service End Date: <span class="error">*</span></label>
								@date("service_end_date",["id"=>"home-service-end-date", "class"=>"col-md-4"])
							</div>
						</div>
						<div class="home-service-follow-up-call col-md-12" id="2_box" style="display: none">
							<div class=" col-md-12">
								<label for="home-service-follow-up-call">Select Call Follow-up Date: <span class="error">*</span></label>
								@date("follow_up_date",["id"=>"home-service-followup-call", "class"=>"col-md-4"])
							</div>
						</div>
					</div>
				</div>
				
				<div class="card-footer">
					<div class="mc-footer">
						<div class="row">
							<div class="col-lg-12 text-right">
								<!-- onclick="window.location.assign('#step-5')" -->
								<button type="submit" class="btn  btn-primary m-1" id="save-homeservice">Next</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>