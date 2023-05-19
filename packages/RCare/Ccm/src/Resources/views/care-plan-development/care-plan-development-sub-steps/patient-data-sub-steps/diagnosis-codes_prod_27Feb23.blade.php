<style>
    table th #lastmodified:hover{
    background: #00f;
}
</style>


<div class="row mb-4" id="diagnosis">
   <div class="col-md-12 mb-4">
      <div class="success" id="success"></div>
      <div class="card-body">
         <div class="row mb-4">
            <div class="col-md-12  mb-4">
               <div class="tab-content" id="myPillTabContent">
                  <div class="tab-pane fade show active" id="diagnosis" role="tabpanel" aria-labelledby="diagnosis-icon-pill">
                    <div class="card mb-4">
                        <div class="card-header mb-3">Diagnosis</div>
                        <form id="diagnosis_code_form" name="diagnosis_code_form" action="{{route("care.plan.development.diagnosis.save")}}" method="post">
                            <div class="card-body">
                                <div class="alert alert-success mt-4 col-md-11" id="success-alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong> Diagnosis data saved successfully! </strong><span id="text"></span>
                                </div> 
                                <div class="alert alert-danger mt-4 col-md-11" id="danger-alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong> Please fill all mandatory fields! </strong><span id="text"></span>
                                </div>
                                <div class="form-row col-md-12">
                                    @include('Theme::layouts.flash-message')
                                    @csrf 
                                    <?php
                                        $module_id    = getPageModuleName();
                                        $submodule_id = getPageSubModuleName();
                                        $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Data');
                                        $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Diagnosis Codes');
                                    ?>
                                    <input type="hidden" name="patient_id" value="{{$patient_id}}" />
                                    <input type="hidden" name="uid" value="{{$patient_id}}">
                                    <input type="hidden" name="start_time" value="00:00:00">
                                    <input type="hidden" name="end_time" value="00:00:00">
                                    <input type="hidden" name="module_id" value="{{ $module_id }}" />
                                    <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                                    <input type="hidden" name="stage_id" value="{{$stage_id}}" />
                                    <input type="hidden" name="step_id" value="{{$step_id}}">
                                    <input type="hidden" name="form_name" value="diagnosis_code_form">
                                    <input type="hidden" name="diagnosis_hiden_id" id="diagnosis_hiden_id">
                                    <input type="hidden"   name="diagnosis_id" id="diagnosis_id">
                                    <input type="hidden"   name="hiddenenablebutton" id="hiddenenablebutton" />
                                    <input type="hidden" name="editdiagnoid" id="editdiagnoid" >  
                                    <input type="hidden" name="billable" value ="<?php if($patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
                                    @include('Patients::components.diagnosis-code') 
                                </div> 
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary float-right patient_data-save mb-4" id="save_diagnosis_form">Review/Save</button>
                                </div> 
                            </div>   
                            <div class="alert alert-success mt-4 col-md-11 ml-3" id="success-alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong> Diagnosis data saved successfully! </strong><span id="text"></span>
                            </div> 
                            <div class="alert alert-danger mt-4 col-md-11 ml-3" id="danger-alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong> Please fill all mandatory fields! </strong><span id="text"></span>
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
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="diagnosis-list" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th width="30px">Sr No.</th>
                                <th width="50px">Code</th>
                                <th width="80px">Condition</th>
                                <th width="80px" >Last Modified By</th>
                                <th width="80px"  data-toggle="tooltip" data-placement="top" data-original-title="when Comments were saved or ICD10 code was changed">Last Modified On</th>
                                <!-- <th width="40px">Review</th> -->
                                <!-- <th width="40px">Date Reviewed</th> -->
                                <th width="40px"  data-toggle="tooltip" data-placement="top" data-original-title="when Symptoms, Goals, or Tasks were changed">Last Updated On</th>
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