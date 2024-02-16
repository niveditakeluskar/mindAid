<div class="form-group" id ="call_section" style="display:none">
            <div class="row  mb-4">
                <div class="col-md-12 mb-4 ">    
                    <div class="card">
                    <form  name="monthly_service_form" action="{{ route("ajax.save.monthly.service") }}" method="post"> 
                @csrf
                <?php 
                // print_r($checklist); 
                $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Not Recorded'); ?>
                        <input type="hidden"  name="module_id" value="{{ getPageModuleName() }}">
                        <input type="hidden"  name="component_id" value="{{ getPageSubModuleName() }}">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="patient_id" value="{{$checklist->id}}" >
                        <input type="hidden" name="contact_via" id="call_contact_via"  value="call">
                        <input type="hidden" name="temp_id" id="call_temp_id" value="">
                        <input type="hidden" name="text_content_area" id="call_content_area" value="">
                        <input type="hidden" name="stage_id" value="{{$stage_id}}">
                        <input type="hidden" name="review_data" value="1">
                        <!-- <input type="hidden" name="text_contact_number" value="{{$checklist->home_number}}" > -->
                        <input type="hidden" name="text_contact_number" value="{{$checklist->mob}}">

                        
                        <div class="card-body text-left"> 
                        <span class=" forms-element">
                        <div id="success2"></div>  
                        <div id="danger2"></div> 
                            <div class="row"> 
                                <label class="col-md-2 mb-4 float-left">Call Answered</label>
                                <label class="radio radio-primary col-md-1">
                                    <input type="radio" name="call_status" value="1" formControlName="radio" id="answered" radioLable="Call Answered">
                                    <span></span>
                                    <span class="checkmark"></span>
                                </label>
                                <div class="col-md-9 mb-4 float-left" id="call_scripts" style=display:none>
                                    <?php
                                    $module_id = getPageModuleName();
                                    $submodule_id = getPageSubModuleName();
                                    $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'RPMCall'); 
                                    $template_id=0;
                                    $call_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Answered');                                    
                                    ?>
                                    @hidden("SteponeCallScript_template_type_id",["id"=>"SteponeCallScript_template_type_id"])
                                    @selectcontentscript("text_content_title",$module_id,$submodule_id,$stage_id,$call_answered_step_id,["id"=>"call_content_title","class"=>"custom-select", "value" =>$template_id])
                                    <p id="script"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 mb-4 float-left">Call Not Answered</label>
                                <label class="radio radio-primary col-md-1">
                                    <input type="radio" name="call_status" value="2" formControlName="radio" id="not_answered" radioLable="Call Not Answered">
                                    <span></span>
                                    <span class="checkmark"></span>
                                </label>
                                <div class="col-md-4 mb-4 float-left" id="answer" style=display:none>
                                    @selectanswer("answer",["id" => "answer", "class" => "form-control form-control"])
                                </div>
                            </div>
                            </span>
                            <div class="form-row invalid-feedback"></div>
                        </div>
                        @include('Rpm::monthly-service.monthly-service-steps.call-script-modal')
                        <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right" style="">
                                    <button type="submit" class="btn btn-primary btn-icon btn-lg m-1 summarize-details" >Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>    
                    </div>
                </div>                                 
            </div>        
</div>  