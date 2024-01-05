<!-- <div class="tsf-step-content"> -->
<div class="row">
   <div class="col-lg-12 mb-3">
      <form id="call_status_form_final" name="call_status_form_final" action="{{ route('patient.enrollment.call.callstatus.final') }}" method="post"> 
         @csrf 
         <?php
            $module_id = getPageModuleName();
            $submodule_id = getPageSubModuleName();
            $stage_id = getFormStageId($module_id, $submodule_id, $enrollServiceName);
            $refused_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Refused Enrollment');
            $ask_callback_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Enrollment Callback');
         ?>
         <input type="hidden" name="time_rec_module" value="{{$module_id}}">
         <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
         <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
         <input type="hidden" name="start_time" value="00:00:00" />
         <input type="hidden" name="end_time" value="00:00:00" />
         <input type="hidden" name="module_id" value="{{ $enroll_module_id }}" />
         <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
         <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
         <input type="hidden" name="template_type_id" value="0" />
         <input type="hidden" name="refused_step_id" value="{{$refused_step_id}}" />
         <input type="hidden" name="ask_callback_step_id" value="{{$ask_callback_step_id}}" />
		   <input type="hidden" name="form_name" value="enrollment_call_status_form_final">
         <input type="hidden" name="content_title" />
         <input type="hidden" name="enrollment_id" class="enrollment_id" />
         <input type="hidden" name="enrolled_service_id" class="enrolled_service_id" value="{{ collect(request()->segments())->last() }}" />
         <div class="card">
            <div class="alert alert-success" id="success-alert" style="display: none;">
               <button type="button" class="close" data-dismiss="alert">x</button>
               <strong>Data saved successfully! </strong><span id="text"></span>
            </div>
            <div id="error_msg3"></div>
            <div class=" card-body">
               <div class="row">
                  <div class="row pt-3 d-flex flex-column flex-sm-row align-items-center forms-element">
                        <label for="enrol_status_asked_to_callback" class="radio radio-primary ml-4">
                           <input type="radio" name="enroll_status" id="enrol_status_asked_to_callback" value="2" formcontrolname="radio">  <!-- data-feedback="enrol-status-feedback" -->
                           <span>Asked to Be Called Back to Delibrate & Decide</span>
                           <span class="checkmark"></span>
                        </label>
                        <label for="enrol_status_refused" class="radio radio-primary ml-4">
                           <input type="radio" name="enroll_status" id="enrol_status_refused" value="3" formcontrolname="radio">  <!-- data-feedback="enrol-status-feedback" -->
                           <span>Refused</span> 
                           <span class="checkmark"></span> 
                           <span class="error">*</span>
                        </label>
                  </div>
                  <div class="invalid-feedback ml-3"></div>
                  <!-- <div class="invalid-feedback visible" data-feedback-area="enrol-status-feedback"></div> -->
                  <div class="form-row col-md-12" id="enrollment-call-back-date-time" style="display: none">
                     <div class='form-row col-md-12 mb-3'>
                        <div class="col-md-3"> 
                           <label for="call_back_date">Select Date <span class="error">*</span></label>
                           @date("call_back_date",['id'=>'call_back_date','type'=>'datetime-local'])
                        </div>
                        <div class="col-md-3 float-left" style="display: none;">     
                           <label for="call_back_time">Enter Time <span class="error">*</span></label>  
                           @time("call_back_time",['id'=>'call_back_time'])
                        </div>
                     </div>
                     <div class='col-md-12'>
                        <p>Thank you for stopping by and discussing our new wellness program with me.</p>
                        <p>Have a great day.</p>
                     </div>
                  </div>
                  <div class="col-md-12 mb-3" id="enrollment-refused-reason" style="display:none;">
                     <div class="form-row forms-element">
                        <label class="col-md-12 float-left">May I ask you why you are not interested in enrolling in this program?<span class="error">*</span></label>
                        <textarea class='form-control col-md-12' name="enrl_refuse_reason"></textarea>
                     </div>
                     <div class="form-row invalid-feedback"></div>
                     <div class='col-md-12 mt-3'>
                        <p>Thank you for stopping by and discussing our new wellness program with me.</p>
                        <p>Have a great day.</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="card-footer">
               <div class="mc-footer text-right" id="call-save-button">
                  <!-- <div class="row">
                  <div class="col-lg-12 text-right" id="call-save-button" > -->
                  <button type="submit" class="btn btn-primary m-1" id="save_callstatus_final" name="save">Save</button>
                  <!-- </div>
                  </div> -->
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
<!-- </div> -->   