<div class="row mb-4" id="diagnosis">
   <div class="col-md-12 mb-4">
      <div class="success" id="success"></div>
      <div class="card-body diagnosis-Data">
         <div class="row mb-4">
            <div class="col-md-12  mb-4">
               <ul class="nav nav-pills" id="myPillTab" role="tablist">
                  <li class="nav-item">
                  </li>
               </ul>
               <div class="tab-content" id="myPillTabContent">
                  <div class="tab-pane fade show active" id="diagnosis" role="tabpanel" aria-labelledby="diagnosis-icon-pill">
                    <div class="card mb-4">
                        <!--div class="card-header mb-3">Add new Diagnosis Code</div-->
                        <div class="card-body">    <!-- care.plan.data.save -->
                          <form id="care_plan_form" name="care_plan_form" action="{{route("care.plan.development.diagnosis.save")}}" method="post">
                            <div class="alert alert-success" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong> Care Plan saved successfully! </strong><span id="text"></span>
                            </div>
                            <div class="alert alert-danger" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong> Fill All Mandatory Fields! </strong><span id="text"></span>
                            </div>
                             
                            <div class="form-row col-md-12">
                            
                                @include('Theme::layouts.flash-message')
                                @csrf 
                                <?php
                                    $module_id    = getPageModuleName();
                                    $submodule_id = getPageSubModuleName(); 
                                    $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Preparation');
                                    $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Care Plan');
                                ?>
                                <input type="hidden" name="patient_id" value="{{$patient_id}}" />
                                <input type="hidden" name="uid" value="{{$patient_id}}">
                                <input type="hidden" name="start_time" value="00:00:00">
                                <input type="hidden" name="end_time" value="00:00:00">
                                <input type="hidden" name="module_id" value="{{ $module_id }}" />
                                <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                                <input type="hidden" name="stage_id" value="{{$stage_id}}" />
                                <input type="hidden" name="step_id" value="{{$step_id}}">
                                <input type="hidden" name="form_name" value="care_plan_form">
                                <input type="hidden" name="diagnosis_id" id="diagnosis_id">
                                <input type="hidden" name="hiddenenablebutton" id="hiddenenablebutton" />
                                <input type="hidden" name="editdiagnoid" id="editdiagnoid" >   
                                <input type="hidden" id="cpd_finalize" value="<?php if(isset($patient_enroll_date[0]->finalize_cpd)){echo $patient_enroll_date[0]->finalize_cpd;} ?>">
                                <!-- ('Patients::components.care-plan') not in use --> 
                                <input type="hidden" name="billable" value="1">
                                @include('Patients::components.diagnosis-code')
                            </div> 
                            <br>
                            <div class="alert alert-success" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong> Care Plan saved successfully! </strong><span id="text"></span>
                            </div>
                            <div class="alert alert-danger" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong> Fill All Mandatory Fields! </strong><span id="text"></span>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary float-right save_care_plan_form" id="save_care_plan_form" disabled="disabled">Review/Save</button>
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
</div>

<div class="col-md-12 mb-4" style="margin-left: 40px;">      
  <div class="row mb-12">
    <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="green" title="green" onclick = "" ><i class="i-Closee  i-Data-Yes" style="color: #33ff33;"></i></a>&nbsp;<p>Care Plans reviewed for 0-6 months&nbsp; &nbsp; &nbsp;</p><a href="javascript:void(0)" data-toggle="tooltip" data-original-title="yellow"  title="yellow" onclick=""><i class="i-Closee  i-Data-Yes" style="color: yellow;"></i></a>&nbsp;<p>Care Plans not reviewed for more than 6 months and less than 12 months&nbsp; &nbsp; &nbsp;</p><a href="javascript:void(0)" data-toggle="tooltip" data-original-title="red"  title="red" onclick = "" ><i class="i-Closee  i-Data-Yes" style="color: red;"></i></a>&nbsp;<p>Care Plans not reviewed for more than or equal to 12 months&nbsp; &nbsp; &nbsp;</p>    
  </div>
</div>


<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
    <div class="col-md-12 mb-4">    
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message') 
                <div class="table-responsive">
                  <table id="diagnosis-list" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th width="30px">Sr No.</th>
                                <th width="50px">Code</th>
                                <th width="80px">Condition</th>
                                <th width="80px">Last Modified By</th>
                                <th width="80px" >Last Modified On</th>
                                 <!-- <th width="40px">Review</th> -->
                                <th width="40px" data-toggle="tooltip" data-placement="top" data-original-title="when Comments were saved or ICD10 code was changed" title="when Comments were saved or ICD10 code was changed"> Last Review Date</th>
                                <th width="40px" data-toggle="tooltip" data-placement="top" data-original-title="when Symptoms, Goals, or Tasks were changed"  title="when Symptoms, Goals, or Tasks were changed">Last Updated On</th>
                                <th width="40px">Action</th>
                            </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>