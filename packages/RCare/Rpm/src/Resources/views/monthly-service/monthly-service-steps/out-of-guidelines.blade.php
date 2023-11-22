<!-- <form name="monthlyservice"> -->
<div class="form-group" id="out_of_guidelines" style="display:none">
    <div class="row  mb-4">
        <div class="col-md-12 mb-4 ">    
            <div class="card">
              
            <form  name="monthly_out_of_guidelines" action="{{ route("ajax.save.monthly.service") }}" method="post">  
                @csrf
                <?php 
                    $module_id    = getPageModuleName(); //2 
                    $submodule_id = getPageSubModuleName();//18
                    // $stage_id     = getFormStageId($module_id, $submodule_id, 'Out Of Guidelines'); 
                    $stage_id     = getFormStageId($module_id, $submodule_id, 'Monthy Service');  //46 
                    $pcp_range_step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'In Call PCP Office Range'); //109 for callpcp and 112
                    $emergency_range_step_id =    getFormStepId($module_id, $submodule_id, $stage_id, 'In Emergency Range');  //110 for emergency and 115
                   
                    ?>
                       {{ $stage_id}} {{ $pcp_range_step_id }} {{ $emergency_range_step_id}}
                        <input type="hidden"  name="module_id" value="{{ $module_id }}">
                        <input type="hidden"  name="component_id" value="{{ $submodule_id }}">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="patient_id" value="{{$checklist->id}}" >
                        <input type="hidden" name="contact_via"  value="Out Of Guidelines">
                        <input type="hidden" name="text_content_area"  value="Out Of Guidelines">
                        <input type="hidden" name="stage_id" value="{{$stage_id}}">
                        <input type="hidden" name="review_data" value="3"> 
                        <input type="hidden" name="out_guidelines_contact_number" value="{{$checklist->mob}}" > 
                        <input type="hidden" name="patient_condition" id="patient-condition" value="">
                        

                        <div id="success4"></div>   
                        <div id="danger4"></div> 
                           

                <div class="card-body card text-left">  
                
                            
                          
                            <div class="row"  id="office_range_div">  
                                {{ getQuestionnaireTemplate($module_id, $submodule_id, $stage_id, $pcp_range_step_id) }}
                            </div>
                            <div class="col-md-12 row" id="emergency_range_div">
                                 {{ getQuestionnaireTemplate($module_id, $submodule_id, $stage_id, $emergency_range_step_id) }} 
                            </div>

                           
                           
                            

                            <div id="record_details" style="margin-top: 33px; margin-left: 50px; display:none;">
                                <div class="row" >
                                        <label class="col-md-2 offset-md-4">Record Episode Details :</label>
                                        <textarea class="col-md-6 form-control"></textarea>
                                </div> 
                                <div class="row">
                                    <div class="col-lg-12 text-right mt-3">
                                        <button type="button" class="btn  btn-primary m-1" >dgstryse</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                <!-- </div> -->
                <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <!-- <button id="" type="button" class="btn  btn-primary m-1 saveMonthlyService" >Save</button> -->
                            <button type="submit" class="btn btn-primary m-1 summarize-details" id ="outofguidelinesave">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            </div>
        </div>                                 
    </div>
</div>


