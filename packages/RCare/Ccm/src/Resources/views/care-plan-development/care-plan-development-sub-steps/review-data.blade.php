<div class="card">
  <div class="card-body text-left">
    <!--form-->
        <div class="row">
            <div class= "col-md-12 form-group mb-3">
                <label>     
            {{-- @foreach($data as $checklist) 
                 {{ strip_tags($content->message) }}
             @endforeach--}} 
             </label>
         </div>    
     </div>
     <ol>
     <div class="row">
        <div class="col-md-10 mb-3">
          <label> <li> Great, the first thing we are going to review is your personal information.</li></label>
       </div>    
       <div class="col-md-2 form-group mb-3">
        <button  type="button" class="btn btn-primary float-right review-click-id" target="family-info" data-toggle="modal" data-target="#review_data_2" id="open_family_info">Open Family Info</button>
    </div>
</div>

<form name="patient_first_review" action="{{ route("patient.first.review") }}" method="post">
@csrf 
<?php
	$module_id    = getPageModuleName();
	$submodule_id = getPageSubModuleName();
    $stage_id     = getFormStageId($module_id, $submodule_id, 'Review Data');
    $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Review-Living-Situation');
//   $patient_id = Route::input('patient_id');
    if($patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0) {
        $billable= 0;
    } else {
        $billable = 1;
    }
?>
  <input type="hidden" name="uid" value="{{$patient_id}}" /> 
  <input type="hidden" name="start_time" value="00:00:00"> 
  <input type="hidden" name="end_time" value="00:00:00">
  <input type="hidden" name="module_id" value="{{ $module_id }}" />
  <input type="hidden" name="component_id" value="{{$submodule_id }}" />
  <input type="hidden" name="stage_id" value="{{ $stage_id}}">
  <input type="hidden" name="step_id" value="{{ $step_id }}"> 
  <input type="hidden" name="form_name" value="patient_first_review"> 
  <input type="hidden" name="billable" value ="{{$billable}}<?php // ?>">
<div class="row ">
    <div class="alert alert-success" id="success-alert" style="display: none;">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong> living Situation data saved successfully! </strong><span id="text"></span>
  </div>           
    <div class="col-md-12 mb-3">
       <label><li> How would you describe your living situation.  Do you.</li></label>
   </div>
   <div class="col-md-1"></div>
   <div class="col-md-6 mb-3">
        <label class="checkbox checkbox-outline-primary">
            <input type="checkbox" name="independent_living_at_home" value="1"><span>Live independently at home</span><span class="checkmark"></span>
         </label>
        <label class="checkbox checkbox-outline-primary">
            <input type="checkbox" name="at_home_with_assistance" value="1"><span>Live at home with some assistance</span><span class="checkmark"></span>
        </label>              
        <label class="checkbox checkbox-outline-primary">
            <input type="checkbox" name="assisted_living_group_living" value="1"><span>Live in an assisted living or group living situation</span><span class="checkmark"></span>
        </label>
        <label class="checkbox checkbox-outline-primary">
            <input type="checkbox" name="other" value="1"><span>Other</span><span class="checkmark"></span>                                   
        </label>
        <label for="description">Description</label>
        <textarea Name="description" class="form-control capital-first forms-element"></textarea>
        <button type="submit" class="btn  btn-primary m-1" id="save_patient_first_review">Save</button>
    </div>
    <div class="col-md-5"></div>
</div>
</form>
<div class="row">
    <div class="col-md-10  mb-3">
       <label><li> Do you live with someone?  If so, record their information.</li></label>
   </div>    
   <div class="col-md-2 form-group mb-3">
       <button  type="button"  class="btn btn-primary float-right review-click-id" target="live-info-for-anyone" data-toggle="modal" data-target="#review_data_2" id="open_live_info">Who They Live With</button>
   </div>
</div>
<div class="row">
    <div class="col-md-10 mb-3">
       <label><li> Now, I would like for you to tell me a little bit about your family.  Do you have any siblings?  If so, inquire about the names and ages.</li></label>
   </div>    
   <div class="col-md-2 form-group mb-3">
       <button  type="button"  class="btn btn-primary float-right review-click-id" target="sibiling-info" data-toggle="modal" data-target="#review_data_2" id="open_sibiling_info">Siblings</button>
   </div>
</div> 
<div class="row">
    <div class="col-md-10 mb-3">
       <label><li> Do you have any children?  If so, inquire about the names and ages.</li></label>
   </div>    
   <div class="col-md-2 form-group mb-3">
       <button  type="button"  class="btn btn-primary float-right review-click-id" target="children-info" data-toggle="modal" data-target="#review_data_2" id="open_children_info">Children</button>
   </div>
</div>
<div class="row">
    <div class="col-md-10 mb-3">
       <label><li> What about grandchildren?  If so, inquire about the names and ages.</li></label>
   </div>    
   <div class="col-md-2 form-group mb-3">
       <button  type="button"   class="btn btn-primary float-right review-click-id" target="grandchildren-info" data-toggle="modal" data-target="#review_data_2" id="open_grandchildren_info">Grand Children</button>
   </div>
</div>
<div class="row">
    <div class="col-md-10 mb-3">
       <label><li> Next, we are going to go over the information from your various Doctors.</li></label>
   </div>    
   <div class="col-md-2 form-group mb-3">
       <button  type="button"   class="btn btn-primary float-right review-click-id" target="doctors-information" data-toggle="modal" data-target="#review_data_2" id="open_doctors_information">Open Provider Data</button>
   </div>
</div>

<div class="row">
    <div class="col-md-10 mb-3">
       <label><li> We are now going to review your various medical conditions.</li></label>
   </div>    
   <div class="col-md-2 form-group mb-3">
       <button  type="button"   class="btn btn-primary float-right review-click-id" target="codes-info-for-medical" data-toggle="modal" data-target="#review_data_2" id="open_codes_info_for_medical">Open Codes Info</button>
   </div>
</div>


  <div class="row">
    <div class="col-md-10 mb-3">
        <label><li> Now, I would like to talk to you about some of the things you like to do?  Do you have any pets?  If so, inquire about their names, type, etc.</li></label>
    </div>    
    <div class="col-md-2 form-group mb-3">
        <button  type="button"   class="btn btn-primary float-right review-click-id" target="review_pets_data" data-toggle="modal" data-target="#review_data_2" id="open_pets">Pets</button>
    </div>
</div> 
<div class="row">
    <div class="col-md-10 mb-3">
        <label><li> What about hobbies or special activities?  How do you like to spend your time?  If so, inquire about their names, type, etc.</li></label>
    </div>    
    <div class="col-md-2 form-group mb-3">
        <button  type="button"   class="btn btn-primary float-right review-click-id" target="review_hobbies" data-toggle="modal" data-target="#review_data_2" id="open_hobbies">Hobbies</button>
    </div>
</div> 
<div  class="row">   
    <div class="col-md-10 mb-3">
        <label><li> Do you like to travel?  If YES, inquire about their favorite locations, etc.</li></label>
    </div> 
    <div class="col-md-2 form-group mb-3">
        <button  type="button"   class="btn btn-primary float-right review-click-id" target="review_travel" data-toggle="modal" data-target="#review_data_2" id="open_travel">Travel</button>
    </div>   
</div>

<div class="row">
    <div class="col-md-10 mb-3">
        <label for="exampleInputEmail1"><li> We are now going to review the various Medications you are taking and see if you have any questions.</li></label>
    </div>    
    <div class="col-md-2 form-group mb-3">
        <button  type="button"   class="btn btn-primary float-right review-click-id" target="review_Medications" data-toggle="modal" data-target="#review_data_2" id="open_medications">Medications</button>
    </div>
</div>

<div class="row">
    <div class="col-md-10 mb-3">
        <label><li> Next, we are going to go over information on your Allergies</li></label>
    </div>
    <div class="col-md-2 form-group mb-3">
        <button  type="button"   class="btn btn-primary float-right review-click-id allergiesclick" target="review_allergy_information" data-toggle="modal" data-target="#review_data_2" id="open_allergies">Allergies</button>
    </div>
</div>

<div class="row">
    <div class="col-md-10 mb-3">
        <label><li> Thank you for your patience while I verify your information.  The last thing we are going to do is go over your healthcare related services.</li></label>
    </div>    
    <div class="col-md-2 mb-3">
        <button  type="button"   class="btn btn-primary float-right review-click-id" target="review_healthcare_services" data-toggle="modal" data-target="#review_data_2" id="open_health_services">Healthcare Services</button>
    </div>
    
</div> 
</ol>
        <!--/form-->
    </div>
    <div class="card-footer">
		<div class="mc-footer">
			<div class="row">
				<div class="col-lg-12 text-right">
                     <a class="btn btn-primary float-right" style="color:white;" onclick="$('#call-close-tab').trigger('click')">Next</a>				
				</div>
			</div>
	    </div> 
  </div> 
</div>
<div class="modal fade" id="review_data_2" role="dialog">
	<div class="modal-dialog modal-lg">
		  <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
				    @include('Patients::components.patient-Ajaxbasic-info')
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body "> 
                                    <div class="row">
                                        <div class="col-md-12" id="review-content"> 
											<div class="form-group cpdReviewData" id="review_healthcare_services" name="healthcare_services" style="display:none;">	
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group cpdReviewData" id="review_allergy_information" name="allergy_information" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group cpdReviewData" id="review_Medications" name="medications" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group cpdReviewData" id="review_travel" name="travel" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group cpdReviewData" id="review_hobbies" name="hobbies" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group cpdReviewData" id="review_pets_data" name="pets" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group review_data_sections cpdReviewData" id="codes-info-for-medical" name="codes_info_for_medical" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group review_data_sections cpdReviewData" id="doctors-information" name="doctors_information" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group review_data_sections cpdReviewData" id="grandchildren-info" name="grandchildren_info" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group review_data_sections cpdReviewData" id="children-info" name="children_info" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group review_data_sections cpdReviewData" id="sibiling-info" name="sibiling_info" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
											<div class="form-group cpdReviewData" id="live-info-for-anyone" name="live_info_for_anyone" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
		
											<div class="form-group cpdReviewData" id="family-info" name="family_info" style="display:none;">
												<div class='loadscreen' id="preloader">
													<div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
														<img src="{{'/images/loading.gif'}}" width="150" height="150">
													</div>
												</div>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>					
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
	        </div>


    </div>
</div>