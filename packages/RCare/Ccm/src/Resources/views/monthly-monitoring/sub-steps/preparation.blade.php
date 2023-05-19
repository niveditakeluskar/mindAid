<form id="{{$section}}_preparation_followup_form" name="{{$section}}_preparation_followup_form" action="{{ route("monthly.monitoring.call.preparation") }}" method="post" > 
   @csrf
   <?php
       $module_id    = getPageModuleName();
       $submodule_id = getPageSubModuleName();
       $stage_id =  getFormStageId($module_id , $submodule_id, 'Preparation');
   ?> 
  <input type="hidden" name="uid" value="{{$patient_id}}">	
  <input type="hidden" name="patient_id" value="{{$patient_id}}">	
	<input type="hidden" name="start_time" value="00:00:00"> 
	<input type="hidden" name="end_time" value="00:00:00">
	<input type="hidden" name="module_id" value="{{ getPageModuleName() }}">
	<input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}">
  <input type="hidden" name="{{$section}}" value="{{$section}}">
  <input type="hidden" name="form_name" value="call_preparation_followup_form">
	<input type="hidden" name="stage_id" value="{{$stage_id}}"> 
	<input type="hidden" name="step_id"  value="0">
   <div class="row call mb-4 ">
      <!-- start Solid Bar -->
      <div class="col-lg-12 mb-4 ">
         <div class="card" >
            <div class="card-body"> 
               <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call Preparation Completed! </strong><span id="text"></span>
               </div>
               <div class="card-title">Call Preparation</div>
               @include('Ccm::monthly-monitoring.components.follow-up')
            </div>
            <div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
                        <button type="button" class="btn btn-primary m-1 draft_preparation" sid="draft_{{$section}}" id="{{$section}}_draft">Draft Save</button>
                        <button type="submit" class="btn btn-primary m-1 save_preparation" sid="{{$section}}" id="{{$section}}_save">Save</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end::form -->
   </div>
</form>
