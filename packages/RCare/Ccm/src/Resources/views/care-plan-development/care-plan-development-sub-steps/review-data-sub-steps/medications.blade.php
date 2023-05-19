<div class="row mb-4" id="review-medications">
   <div class="col-md-12 mb-4">
      <div class="success" id="success"></div>
      <div class="card-body">
         <div class="row mb-4">
            <div class="col-md-12  mb-4">
               <ul class="nav nav-pills" id="myPillTab" role="tablist">
                  <li class="nav-item">
                     <!-- <a class="nav-link active" id="medication-icon-pill" data-toggle="pill" href="#medication" role="tab" aria-controls="medication" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>MEDICATION</a> -->
                  </li>
               </ul>
               <div class="tab-content" id="myPillTabContent">
                  <div class="tab-pane fade show active" id="review-medication" role="tabpanel" aria-labelledby="review-medication-icon-pill">
                    <div class="card mb-4">
                        <div class="card-header mb-3">MEDICATION</div>
                          <form id="review_medications_form" name="review_medications_form" action="{{route("care.plan.development.medications")}}" method="post">
                            <div class="card-body">
                            <div class="alert alert-success" id="success-alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong> Medication data saved successfully! </strong><span id="text"></span>
                            </div> 
                            <div class="form-row col-md-12">
                                @include('Theme::layouts.flash-message')
                                @csrf 
                                  <?php
                  //                   $module_id    = Route::input('module_id');
									// $submodule_id = Route::input('submodule_id'); 
									// $stage_id     = getFormStageId($module_id, $submodule_id, 'Review Data');
                                    $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Review-Medication');
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
                                    <input type="hidden" name="form_name" value="review_medications_form">
                                    <input type="hidden" name="tab" value="review-medication">
                                    <input type="hidden" name="id">
                                    <input type="hidden" name="billable" value ="{{$billable}}">
                                @include('Patients::components.medication')
                            </div> 
                            </div>
                            <div class="card-footer">
                              <div class="mc-footer">
                                <div class="row">
                                  <div class="col-lg-12 text-right">
                                      <button type="submit" class="btn btn-primary float-right patient_data-save" id="save_medications_form">Save</button>
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

<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="MedicationReview-list" class="display datatable table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                <thead>
                    <tr> 
                        <th>Sr</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Purpose</th>
                        <th>Dosage</th>
                        <th>Strength</th>
                        <th>Frequency</th>
                        <th>Route</th>
                        <th>Duration</th>
                        <th>Pharmacy Name</th>
                        <th>Pharmacy Phone No</th>
                        <th>Reviewed Data</th>
                        <th>Last Modified By</th>
                        <th>Last Modified On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody> 
            </table>
        </div>
    </div>
</div>