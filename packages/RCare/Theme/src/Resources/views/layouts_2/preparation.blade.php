<form id="{{$section}}_preparation_followup_form" name="{{$section}}_preparation_followup_form" action="{{ route("monthly.monitoring.call.preparation") }}" method="post" > 
   @csrf
   <input type="hidden" name="uid" value="{{$patient[0]->UID}}">	
   <input type="hidden" name="patient_id" value="{{$patient[0]->id}}">	
	<input type="hidden" name="start_time" value="00:00:00">
	<input type="hidden" name="end_time" value="00:00:00">
	<input type="hidden" name="module_id" value="{{ getPageModuleName() }}">
	<input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}">
	<input type="hidden" name="stage_id" value="0">
	<input type="hidden" name="step_id" value="0">
   <div class="row call mb-4 ">
      <!-- start Solid Bar -->
      <div class="col-lg-12 mb-4 ">
         <div class="card" >
            <div class="card-body"> 
               <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call Preparation data saved successfully! </strong><span id="text"></span>
               </div>
               <div class="card-title">Call Preparation</div>
               <!-- <div class="">
                  <label>Data Present In EMR?</label><br />
                  <div class="form-row forms-element">
                     <label class="radio radio-primary col-md-4 float-left">
                        <input type="radio" class="" name="data_present_in_emr" value="1" formControlName="radio">
                        <span>Yes</span>
                        <span class="checkmark"></span>
                     </label>
                     <label class="radio radio-primary col-md-4 float-left">
                        <input type="radio" class="" name="data_present_in_emr" value="0" formControlName="radio">
                        <span>No</span>
                        <span class="checkmark"></span>
                     </label>
                  </div>
                  <div class="form-row invalid-feedback"></div>
               </div> -->
               <!-- <div id="data_present_in_emr_show"> -->
                  @include('Ccm::monthly-monitoring.components.follow-up')
               <!-- </div> -->
            </div>
            <div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
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