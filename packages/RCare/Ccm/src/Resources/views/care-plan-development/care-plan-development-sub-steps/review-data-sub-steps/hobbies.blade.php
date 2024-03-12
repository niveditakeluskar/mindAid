<div class="row mb-4" id="Calling_page">
   <div class="col-md-12 mb-4">
      <div class="card-body">
         <div class="row mb-4">
            <div class="col-md-12  mb-4">
                <ul class="nav nav-pills" id="myPillTab" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" id="hobbies-icon-pill" data-toggle="pill" href="#patient-hobbies" role="tab" aria-controls="patient-hobbies" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>Hobbies</a>
                  </li>
               </ul>
               <div class="tab-content" id="myPillTabContent">
                  <div class="tab-pane fade show active" id="patient-hobbies" role="tabpanel" aria-labelledby="hobbies-icon-pill">
                  <div class="card mb-4">
                    <div class="card-header mb-3">Hobbies</div>
                        <form id="review_hobbies_form" name="review_hobbies_form" action="{{route("care.plan.development.review.hobbies")}}" method="post">
                        <div class="card-body">
                            <div class="alert alert-success" id="success-alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong> Hobbies data saved successfully! </strong><span id="text"></span>
                            </div> 
                            <div class="form-row col-md-12">
                                @include('Theme::layouts.flash-message')
                                @csrf 
                                <?php
                                    // $module_id    = Route::input('module_id');
									// $submodule_id = Route::input('submodule_id'); 
									// $stage_id     = getFormStageId($module_id, $submodule_id, 'Review Data');
                                    $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Review-Hobbies');
									// $patient_id = Route::input('patient_id');
									// $billable = Route::input('billable');
                                ?>
                                <input type="hidden" name="patient_id" value="{{$patient_id}}" />
                                <input type="hidden" name="uid" value="{{$patient_id}}">
                                <input type="hidden" name="start_time" value="00:00:00">
                                <input type="hidden" name="end_time" value="00:00:00">
                                <input type="hidden" name="module_id" value="{{ $module_id }}" />
                                <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                                <input type="hidden" name="stage_id" value="{{$stage_id}}" />
                                <input type="hidden" name="step_id" value="{{$step_id}}">
                                <input type="hidden" name="form_name" value="review_hobbies_form">
                                <input type="hidden" name="id">
                                <input type="hidden" name="billable" value ="{{$billable}}">
                                <div class="form-row mb-4">
                                    <span class="col-md-12 forms-element">
                                     <label for="step-1_office_visit" class="mr-3 mb-4"><b>Do you have any hobbies?</b><span class="error"> *</span></label>
                                       <div class="mr-3 d-inline-flex align-self-center"> 
                                          <!-- <label for ="yes" class="radio radio-primary mr-3">Call Answered</label> -->
                                          <label class="radio radio-primary mr-3">
                                             <input type="radio" name="hobbies_status" value="1" formControlName="radio" id="hobbies_status_yes">
                                             <span>Yes</span> 
                                             <span class="checkmark"></span>
                                          </label>
              
                                          <!-- <label for ="no" class="radio radio-primary mr-3">Call Not Answered</label> -->
                                          <label class="radio radio-primary mr-3">
                                             <input type="radio" name="hobbies_status" value="0" formControlName="radio" id="hobbies_status_no">
                                             <span>No</span> 
                                             <span class="checkmark"></span>
                                          </label> 
                                       </div>
                                    </span>
                                    <div class="form-row invalid-feedback"></div>
                                </div>
                                <div class="form-row col-md-12" id="hobbies_0">
                                    @include('Patients::components.hobbies')
                                </div>
                                <hr>
                                <div class="form-row col-md-12" id="appendHobbiesDiv"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="mc-footer">
                                <div class="row"> 
                                    <div class="col-lg-12 text-right">
                                        <!-- <button type="button" class="btn btn-primary additional float-left" id="additional_hobbies">Additional</button> -->
                                        <button type="submit" class="btn  btn-primary m-1 float-right" id="save_review_hobbies_form">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="alert alert-danger" id="danger-alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong> Please Fill All Mandatory Fields! </strong><span id="text"></span>
                        </div> 
                        </form>
                    </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            @csrf
            <table id="hobbies-list" class="display datatable table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                <thead>
                    <tr> 
                        <th>Sr</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Frequency</th>
                        <th>With Whom</th>
                        <th>Last Modified By</th>
                        <th>Last Modified On</th>
                        <!-- <th>Review</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody> 
            </table>
            
        </div>
    </div>
</div>
