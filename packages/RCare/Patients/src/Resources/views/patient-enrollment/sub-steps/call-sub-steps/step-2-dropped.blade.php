<!-- <div class="tsf-step-content"> -->
<div class="row">
      <div class="col-lg-12 mb-3">
         <form id="enrollment_status_form" name="enrollment_status_form" action="{{ route("patient.enrollment.call.enrollmentstatus") }}" method="post"> 
            @csrf 
            <?php
               $module_id = getPageModuleName();
               $submodule_id = getPageSubModuleName();
               $stage_id = getFormStageId($module_id, $submodule_id, 'Call');
               $enrollment_status_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Enrollment Script');
            ?>
            <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
            <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
            <input type="hidden" name="start_time" value="00:00:00">
            <input type="hidden" name="end_time" value="00:00:00">
            <input type="hidden" name="module_id" value="{{ $module_id }}" />
            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
            <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
            <input type="hidden" name="step_id" value="{{ $enrollment_status_step_id }}">
            <input type="hidden" name="template_type_id">
            <input type="hidden" name="content_title">
            <input type="hidden" name="enrollment_id" class="enrollment_id" />
            <div class="card">
            	<div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call data saved successfully! </strong><span id="text"></span>
               </div>
               <div class=" card-body">
                  <div class="form-row">
                     @selectcontentscript("enrollment_status_template_id",$module_id,$submodule_id,$stage_id,$enrollment_status_step_id,["id"=>"enrollment_status_template_id","class"=>"custom-select"])
                  </div>
                  <div class="form-row mt-2 ">
                     <p class="enrollment_status_script"></p>
                     <textarea class="enrollment_status_script" hidden="hidden" name="enrollment_status_script"></textarea>
                  </div>
                     <div class="border-top pt-3 d-flex flex-column flex-sm-row align-items-center">
                        <label for="enrol_status_agreed" class="radio radio-primary">
                           <input type="radio" name="enrol_status" id="enrol_status_agreed" value="1" formcontrolname="radio" data-feedback="enrol-status-feedback">
                           <span>Agreed to Enroll</span>
                           <span class="checkmark"></span>
                        </label>
                        <label for="enrol_status_asked_to_callback" class="radio radio-primary ml-4">
                           <input type="radio" name="enrol_status" id="enrol_status_asked_to_callback" value="2" formcontrolname="radio" data-feedback="enrol-status-feedback">
                           <span>Asked to Be Called Back to Delibrate & Decide</span>
                           <span class="checkmark"></span>
                        </label>
                        <label for="enrol_status_refused" class="radio radio-primary ml-4">
                           <input type="radio" name="enrol_status" id="enrol_status_refused" value="3" formcontrolname="radio" data-feedback="enrol-status-feedback">
                           <span>Refused</span> 
                           <span class="checkmark"></span> 
                           <span class="error">*</span>
                        </label>
                     </div>
                     <div class="invalid-feedback visible" data-feedback-area="enrol-status-feedback" style="display:block;"></div>
                     <div class="row" id="date-time"  <?php if(isset($call_status->enrollment_response) && $call_status->enrollment_response == 2){}else{ ?> style="display: none" <?php } ?>>
                        <div class="col-md-6"> 
                           <label for="call_back_date">Select Date <span class="error">*</span></label>
                           @date("call_back_date",['id'=>'call_back_date'])
                        </div>
                        <div class="col-md-6 float-left">     
                           <label for="call_back_time">Enter Time <span class="error">*</span></label>  
                           @time("call_back_time",['id'=>'call_back_time'])
                        </div>
                     </div>
               </div>
               <div class="card-footer">
                  <div class="mc-footer text-right">
                     <button type="button" class="btn btn-secondary" onclick="backStep(1)"> Back </button>
                     <button type="submit" class="btn btn-primary m-1" id="save-enrollmentstatus">Next</button>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
<!-- </div> -->