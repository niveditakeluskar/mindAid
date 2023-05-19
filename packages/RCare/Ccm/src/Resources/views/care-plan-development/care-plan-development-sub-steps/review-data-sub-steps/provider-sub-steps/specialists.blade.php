<form id="review_provider_specialists_form" name="review_provider_specialists_form" action="{{route('care.plan.development.provider')}}" method="post">
	<div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button> 
        <strong> Specialists Provider data saved successfully! </strong><span id="text"></span>
    </div>  
	<div class="form-row col-md-12">
		@include('Theme::layouts.flash-message')
		@csrf 
		<?php
			// $module_id    = Route::input('module_id');
			// $submodule_id = Route::input('submodule_id'); 
			// $stage_id     = getFormStageId($module_id, $submodule_id, 'Review Data');
			$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Review-Provider-Specialists');
			// $patient_id = Route::input('patient_id');
			// $billable = Route::input('billable');
		?>
    <input type="hidden" name="uid" value="{{$patient_id}}" />
		<input type="hidden" name="patient_id" value="{{$patient_id}}" />
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" />
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" />
		<input type="hidden" name="tab" value="review-provider" />
		<input type="hidden" name="stage_id" value="{{ $stage_id}}" />
		<input type="hidden" name="step_id" value="{{$step_id}}">
		<input type="hidden" name="form_name" value="review_provider_specialists_form">
		<input type="hidden" name="practice_type" value="specialist">
		<input type="hidden" name="provider_type_id" value="2">
    <input type='hidden' name='id'>
    <input type="hidden" name="billable" value ="{{$billable}}">
		@include('Patients::components.specialist-provider')
	</div>
	<div class="card-footer">
		<div class="mc-footer">
			<div class="row">
				<div class="col-lg-12 text-right">
				<!-- onclick="window.location.assign('#step-4')" -->
					<button type="submit" class="btn  btn-primary m-1" id="save_review_provider_specialists_form">Save</button>
				</div>
			</div>
		</div>
	</div>
    <div class="alert alert-danger" id="danger-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Please Fill All Mandatory Fields! </strong><span id="text"></span>
    </div>
</form> 

<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
    <div class="col-md-12">
  <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
      @include('Theme::layouts.flash-message')
      <div class="table-responsive">
          <table id="specialists-review-list" class="display datatable table-striped table-bordered" style="width:100%">
       
          <thead>
          <tr>
              <th width="30px">Sr No.</th>
              <th width="80px">Practice</th>
              <th width="80px">Provider</th>
              <th width="80px">Speciality</th>
              <th width="50px">Credential</th>
              <th width="30px">Phone Number</th>
              <th width="30px">Last Visit Date</th>
              <th width="30px">Last Modified By</th>
              <th width="30px">Last Modified On</th>
              <th width="40px">Action</th>
          </tr>
      </thead>
   
         </table>
      </div>
    </div>
</div>
<div id ="testdata"></div>



