<!-- <div class="tsf-step-content"> -->
   <div class="row">
      <div class="col-lg-12 mb-3">
         <form id="checklist_form" name="checklist_form" action="{{ route("patient.enrollment.call.checklist") }}" method="post"> 
            @csrf 
            <?php           
               $module_id = getPageModuleName();
               $submodule_id = getPageSubModuleName(); 
               $stage_id = getFormStageId($module_id, $submodule_id, $enrollServiceName);
               $checklist_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Enrollment Checklist');             
            ?>
            <input type="hidden" name="time_rec_module" value="{{$module_id}}">
            <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
            <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
            <input type="hidden" name="start_time" value="00:00:00" />
            <input type="hidden" name="end_time" value="00:00:00" />
            <input type="hidden" name="module_id" value="{{ $enroll_module_id }}" />
            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
            <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
            <input type="hidden" name="step_id" value="{{ $checklist_step_id}}" />
		      <input type="hidden" name="form_name" value="enrollment_checklist_form">
            <input type="hidden" name="enrollment_id" class="enrollment_id" />
            <div class="card">
            	<div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Data saved successfully! </strong><span id="text"></span>
               </div>
               <div id="error_msg1"></div>
               <div class=" card-body">
                  <div id="checklist_form_questionnaire">
                     <div class="card-title">Patient Enrollment Checklist</div>
                     <div class="form-row ml-1 mb-3">Great, I need to confirm a couple things with you which is required by Medicare:

					 </div>
                  <div class="form-row ml-1">
                     {{ getQuestionnaireTemplate($module_id, $submodule_id, $stage_id, $checklist_step_id) }}
                  </div>
					   <div class="form-row ml-1 forms-element">
                     <p class="col-md-12">Also, as part of the requirements of Medicare, I need for you to review and sign this enrollment form, which basically covers the same information we just went over. </p>
                     <p class="col-md-3">PATIENT REVIEWS AND SIGNS  <span class="error">*</span></p>
                     <label class="radio radio-primary col-md-2 float-left">
                        <input type="radio" class="" name="patient_reviews_and_signs" value="1" formControlName="radio">
                        <span>Yes</span>
                        <span class="checkmark"></span>
                     </label>
                     <label class="radio radio-primary col-md-2 float-left">
                        <input type="radio" class="" name="patient_reviews_and_signs" value="0" formControlName="radio">
                        <span>No</span>
                        <span class="checkmark"></span>
                     </label>  
                     <!-- <label class="radio radio-primary col-md-2 float-left">
                        <input type="radio" class="" name="enroll_status" value="1" formControlName="radio">
                        <span>Yes</span>
                        <span class="checkmark"></span>
                     </label>
                     <label class="radio radio-primary col-md-2 float-left">
                        <input type="radio" class="" name="enroll_status" value="0" formControlName="radio">
                        <span>No</span>
                        <span class="checkmark"></span>
                     </label>     -->
					   </div>
                  <div class="invalid-feedback ml-2" style="margin-top: -9px;"></div>
                  </div>
               </div>
               <div class="card-footer">
                  <div class="mc-footer text-right">
                     <!--<button type="button" class="btn btn-secondary" onclick="backStep(3)"> Back </button>-->
                     <button type="submit" class="btn btn-primary m-1" style="display:none" id="save-checklist">Next</button>
                     <button type="button" class="btn btn-primary m-1" id="save_checklist">Next</button>

                  </div>
               </div>

            </div>
         </form>
      </div>
   </div>
<!-- </div> -->