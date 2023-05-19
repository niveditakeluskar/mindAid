<div class="tsf-step-content">
   <div class="row">
      <div class="col-lg-12 mb-3">
         <form id="callstatus_form" name="callstatus_form" action="{{ route("monthly.monitoring.call.callstatus") }}" method="post"> 
            @csrf 
            <?php
               $module_id = getPageModuleName();
               $submodule_id = getPageSubModuleName();
               $stage_id = getFormStageId($module_id, $submodule_id, 'Call');
			   //print_r('sid'.$stage_id);
			   
               $call_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Answered');
               $call_notanswered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Not Answered');
               $conf = getSMSConfigue();
               if(isset($patient_providers->practice['practice_group'])){ 
                  $org = getOrganization($patient_providers->practice['practice_group']);   
               }
               
               //print_r($org[0]->assign_message);
               //echo 'hi'.$enroll_in_rpm; 
            ?>
            <input type="hidden" name="uid" value="{{$patient_id}}" />
            <input type="hidden" name="patient_id" value="{{$patient_id}}" />
            <input type="hidden" name="start_time" value="00:00:00"> 
            <input type="hidden" name="end_time" value="00:00:00">
            <input type="hidden" name="module_id" value="{{ getPageModuleName() }}"  />
            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
            <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
            <input type="hidden" name="template_type_id" value="2">
            <input type="hidden" name="form_name" value="callstatus_form">
            <input type="hidden" name="call_answered_step_id" value="{{$call_answered_step_id}}">
            <input type="hidden" name="call_notanswered_step_id" value="{{$call_notanswered_step_id}}">
            <input type="hidden" name="content_title">
            <input type="hidden" name="call_not_text_message">
            <input type="hidden" name="billable" value ="<?php if($patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
            <input type="hidden" name="hourtime" id="hourtime">
            <div class="card">
            	<div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call data saved successfully! </strong><span id="text"></span>
               </div>
               <div class="twilo-error"></div>
               <div class=" card-body">
                  <div class="row">
                     <div class="col-md-3">
                        <span class=" forms-element">
                           <div class="form-row">
                              <label class="col-md-8 float-left">Call Answered</label>
                              <label class="radio radio-primary col-md-4">
                                 <input type="radio" name="call_status" value="1" formControlName="radio" id="answered">
                                 <span></span> 
                                 <span class="checkmark"></span>
                              </label>
                           </div>
                           <div class="form-row">
                              <label class="col-md-8 float-left">Call Not Answered</label>
                              <label class="radio radio-primary col-md-4">
                                 <input type="radio" name="call_status" value="2" formControlName="radio" id="not_answered">
                                 <span></span> 
                                 <span class="checkmark"></span>
                              </label>
                           </div>
                        </span>
                        <div class="form-row invalid-feedback"></div>
                     </div>
                        <div class="col-md-8" id="callAnswer" style="display:block">
                           <?php

                              
                              if (isset($callstatus->call_action_template) && ($callstatus->call_action_template != '')) {
                                 $content_scripts_select = json_decode($callstatus->call_action_template);
                                 $template_ids = $content_scripts_select->template_id;
                              } else {
                                 $template_ids = 0;
                              }
     

                              $call_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Answered');
							  
                           ?>
                           @selectcontentscript("call_answer_template_id",$module_id,$submodule_id,$stage_id,$call_answered_step_id,["id"=>"call_scripts_select","class"=>"custom-select", "value" =>$template_ids])
                           <label for="intro-script"><br/>
                              <h6>Introduction Script</h6>
                              <div class="call_answer_template"></div>
                              <textarea hidden="hidden" name="call_answer_template" class="form-control call_answer_template" id="call_answer_template"></textarea>
                           </label>
                           <div class="">
                              <label for="Continue this call?">Is this good time to talk? <span class="error">*</span></label><br />
                              <div class="form-row forms-element">
                                 <label class="radio radio-primary col-md-4 float-left">
                                    <input type="radio" id="role1" class="" name="call_continue_status" value="1" formControlName="radio">
                                    <span>Yes</span>
                                    <span class="checkmark"></span>
                                 </label>
                                 <label class="radio radio-primary col-md-4 float-left">
                                    <input type="radio" id="role2" class="" name="call_continue_status" value="0" formControlName="radio">
                                    <span>No</span>
                                    <span class="checkmark"></span>
                                 </label>
                              </div>
                              <div class="form-row invalid-feedback"></div>
                           </div>
                           <div class="row mb-3" id="schedule_call_ans_next_call" style="display:none">
                              <div class="col-md-6">
                              <label for="shedule_next_call">Select Call Follow-up date: </label> 
                              <!-- <span class="error">*</span> -->
                              <?php (isset($callstatus->answer_followup_date) && ($callstatus->answer_followup_date != '')) ? $answer_followup_date = $callstatus->answer_followup_date : $answer_followup_date = ''; ?>                              
                              @date("answer_followup_date",["id" => "answer_followup_date", "class" => "","value"=>$answer_followup_date])
                              <div id="call_continue_followup_date_error" class="invalid-feedback"></div>
                              </div>
                             <div class="col-md-6">
                               <label for="shedule_next_call">Select Call Follow-up Time:</label>
                               <!--   <span class="error">*</span> -->
                                @time("answer_followup_time", ["id"=>"answer_followup_time"])
                             </div>
                        </div>
                        </div>

                        <div class="col-md-6" id="CcmNotAnswer" style="display:none">
                           <div class="mb-3">
                              <select class="forms-element custom-select" id="answer" name="voice_mail">
                                 <option value="">Select Voice Mail Action</option>
                                 <option value="1" <?php if (isset($callstatus->voice_mail) && ($callstatus->voice_mail == '1')) echo "selected"; ?>>Left Voice Mail</option>
                                 <option value="2" <?php if (isset($callstatus->voice_mail) && ($callstatus->voice_mail == '2')) echo "selected"; ?>>No Voice Mail</option>
                                 <option value="3">Send Text Message</option>
                              </select>
                              <div class="invalid-feedback"></div>
                           </div>
                           
                           <div class="row" id="voicetextarea" style="display:none">
                           <div class="col-md-12 form-group mb-3">
                           <?php $call_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Left Voice Mail');   ?>
                           @selectcontentscript("voice_scripts_id",$module_id,$submodule_id,$stage_id,$call_answered_step_id,["id"=>"voice_scripts_select","class"=>"custom-select", "value" =>$template_ids])
                           <label for="intro-script"><br/>
                              <h6>Voice Mail Script</h6>
                              <div class="voice_mail_template"></div>
                              <textarea hidden = "hidden" name="voice_template" class="form-control voice_mail_template" style="padding: 5px;width: 47em;min-height: 5em;overflow: auto;height: 87px;" id="voice_mail_template"></textarea>
                           </label>
                           </div>
                           </div>
                           @include('Messaging::text-message')
                           
                           <div class="mb-3">
                              <label for="shedule_next_call">Select Call Follow-up date: </label>
                              <!-- <span class="error">*</span> -->
                              <?php (isset($callstatus->call_followup_date) && ($callstatus->call_followup_date != '')) ? $call_followup_date = $callstatus->call_followup_date : $call_followup_date = ''; ?>
                              @date("call_followup_date",["id" => "call_followup_date", "class" => "","value"=>$call_followup_date])
                              <div id="call_followup_date_error" class="invalid-feedback"></div>
                           </div>
                        </div>
                  </div>
            <div id="tempdiv"> 
                    
            </div>                          
                      
               </div>

                
               
               
               <div class="card-footer">
                  <div class="mc-footer">
                     <div class="row">
                        <div class="col-lg-12 text-right" id="call-save-button" >
                           
                        </div>
                     </div>
                  </div>
               </div>

            </div>
         </form>
         @include('Messaging::call-history')
      </div>
   </div>
</div>