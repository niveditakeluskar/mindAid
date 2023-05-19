<form id="{{$section}}_preparation_followup_form" name="{{$section}}_preparation_followup_form" action="{{ route("monthly.monitoring.call.preparation") }}" method="post" > 
   @csrf
   <?php
       $module_id    = getPageModuleName();
       $submodule_id = getPageSubModuleName();
       $stage_id =  getFormStageId($module_id , $submodule_id, 'Condition Review');//Call
       // $step_id =  getFormStepId($module_id , $submodule_id, $stage_id, 'Condition Review');
   ?> 
   <input type="hidden" name="uid" value="{{$patient_id}}">
   <input type="hidden" name="patient_id" value="{{$patient_id}}">	
	<input type="hidden" name="start_time" value="00:00:00">
	<input type="hidden" name="end_time" value="00:00:00">
	<input type="hidden" name="module_id" value="{{ getPageModuleName() }}">
	<input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}">
   <input type="hidden" name="{{$section}}" value="{{$section}}">
   <input type="hidden" name="form_name" id="form_name"value="{{$section}}_preparation_followup_form">
	<input type="hidden" name="stage_id" value="{{$stage_id}}">
	<!-- <input type="hidden" name="step_id" value="{{$step_id}}"> -->
   <div class="row call mb-4 ">
      <!-- start Solid Bar -->
      <div class="col-lg-12 mb-4 ">
         <div class="card" >
            <div class="card-body"> 
               <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Research Follow up Completed!</strong><span id="text"></span>
               </div>
               <div class="card-title">Condition Review</div>
               <div class="form-row mb-4">
                  <div class="col-md-12 forms-element">
                     <label for="step-1_office_visit" class="mr-3 mb-4"><b>Call preparation completed?</b></label>
                     <div class="mr-3 d-inline-flex align-self-center"> 
                        <label for ="{{$section}}_data_present_in_emr_yes" class="radio radio-primary mr-3">
                           <input type="radio" formControlName="radio" name="data_present_in_emr" id="{{$section}}_data_present_in_emr_yes" value="1">
                           <span>Yes</span>
                           <span class="checkmark"></span>
                        </label>
                        <label for="{{$section}}_data_present_in_emr_no" class="radio radio-primary mr-3">
                           <input type="radio" formControlName="radio" name="data_present_in_emr" id="{{$section}}_data_present_in_emr_no" value ="0">
                           <span>No</span>
                           <span class="checkmark"></span>
                        </label>
                     </div>
                  </div>
                  <div class="invalid-feedback">office visit</div>
               </div>
               <div id="data_present_in_emr_show">
                  @include('Ccm::monthly-monitoring.components.follow-up')
                  
               </div>
            </div>
            <div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
                        <button type="submit" class="btn btn-primary m-1 save_preparation" sid="{{$section}}" id="{{$section}}_save">Next</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end::form -->
   </div>
</form>