<form id="allergy_pet_related_form" name="allergy_pet_related_form" action="{{route("save.allergy.data") }}" method="post">
	<div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Allergy data saved successfully! </strong><span id="text"></span>  
    </div> 

	<div class="form-row col-md-12">
		@include('Theme::layouts.flash-message')
		@csrf 
		<?php
			$module_id    = getPageModuleName();
			$submodule_id = getPageSubModuleName();
			$stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Data');
			$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Allergy-Pet-Related');
		?>
		<input type="hidden" name="uid" value="{{$patient_id}}" />
		<input type="hidden" name="patient_id" value="{{$patient_id}}" />
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" />
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" />
		<input type="hidden" name="stage_id" value="{{$stage_id}}" />
		<input type="hidden" name="step_id" value="{{$step_id}}">
        <input type="hidden" name="form_name" value="allergy_pet_related_form">
		<input type="hidden" name="allergy_type" value="petrelated">
 <input type="hidden" name="id" id="id">
 <input type="hidden" name="billable" value ="<?php if(isset($patient_enroll_date[0]->finalize_cpd) && $patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
  <br>
     <div class="col-md-12 form-group mt-3">  
        <input type="hidden" name="noallergymsg" id="noallergymsg" value="No Known Pet-Related Allergies"> 
        <label class="checkbox" onclick="ccmcpdcommonJS.hideShowNKDAMsg('petrelatedcount','petrelatedmsg');" class="noAllergiesLbl" style="z-index: 1;">No Known Pet-Related Allergies
            <input type="checkbox" name="allergy_status" class="noallergiescheckbox" onclick="ccmcpdcommonJS.noallergiescheck(this)"  value="1" style="position: absolute; z-index: -1;" />    
            <div id="petrelatedmsg" style="color:red; display:none">Please delete all data from below table to enable checkbox!</div>  
            <span class="checkmark"></span>  
        </label>
      <input type = "hidden" id ="petrelatedcount" value ="">  
      </div>
		@include('Patients::components.allergy')
	</div>
	<div class="card-footer">
		<div class="mc-footer">
			<div class="row">  
				<div class="col-lg-12 text-right">
				<!-- onclick="window.location.assign('#step-4')" -->
					<button type="submit" class="btn  btn-primary m-1" id="save_allergy_pet_related_form">Save</button>
				</div>
			</div>
		</div>
	</div>
	<div class="alert alert-danger" id="danger-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Please Fill All mandatory Fields! </strong><span id="text"></span>
    </div>
	<!-- <div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong> Allergy data saved successfully! </strong><span id="text"></span>
    </div> -->
</form>     


<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="pet-related-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                <thead>
                    <tr> 
                        <th>Sr</th>
                        <th>Specify</th>
                        <th>Type of Reactions</th>
                        <th>Severity</th>
                        <th>Course of Treatment</th>
                        <th>Allergy Status</th>
                        <th>Last Modified By</th>
                        <th>Last Modified On</th>
                        <th>Review</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody> 
            </table>
            
        </div>
    </div>
</div>
