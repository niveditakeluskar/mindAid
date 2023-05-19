<!-- <div class="tsf-step-content"> -->
<div class="row">
      <div class="col-lg-12 mb-3">
         <form id="call_status_form" name="call_status_form" action="{{ route('patient.enrollment.call.callstatus') }}" method="post"> 
            @csrf 
            <?php
               $module_id = getPageModuleName();             
               $submodule_id = getPageSubModuleName(); 
               // $stage_id = getFormStageId($module_id, $submodule_id, 'Call');
               $enrollServiceName;
               $stage_id = getFormStageId($module_id, $submodule_id, $enrollServiceName);
               $step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Introduction');
               // dd($patient_providers);
               $provider_data = $patient_providers['provider']; 
               $practice_data = $patient_providers['practice']; 
               $provider_name = empty($provider_data['name']) ? '[provider]' : $provider_data['name'];
               $practice_name = empty($practice_data["name"]) ? '[practice]' : $practice_data["name"];
            ?>
            <input type="hidden" name="time_rec_module" value="{{$module_id}}">
            <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
            <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
            <input type="hidden" name="start_time" value="00:00:00" />
            <input type="hidden" name="end_time" value="00:00:00" />
            <input type="hidden" name="module_id" value="{{ $enroll_module_id }}" />
            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />

            <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
            <input type="hidden" name="template_type_id" value="2" />
            <input type="hidden" name="step_id" value="{{$step_id}}" />
		    <input type="hidden" name="form_name" value="enrollment_call_status_form">
            <input type="hidden" name="content_title" />
            <input type="hidden" name="enrollment_id" class="enrollment_id" />
            <input type="hidden" name="enrolled_service_id" class="enrolled_service_id" value="{{ collect(request()->segments())->last() }}" />
            <div class="card">
            	<div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Data saved successfully! </strong><span id="text"></span>
               </div>
               <div id="error_msg"></div>
               <div class=" card-body">
                  <div class="row">
                     <div class="col-md-3" style="display:none">
                        <span class=" forms-element">
                           <div class="form-row">
                              <label class="col-md-8 float-left">Call Answered</label>
                              <label class="radio radio-primary col-md-4">
                                 <input type="radio" name="call_status" value="1" formControlName="radio" id="answered" checked>
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
                     <div class="col-md-12" id="callAnswer" >
                       
                        
                        <label for="intro-script"><br/>
                           <h5>Enrollment</h5>
                           <div class="call_answer_template">

                          

                           <div class="col-md-6">
                           <span class=" forms-element">
                           <div class="form-row">
                             
                              <label class="col-md-2 float-left" i="fin2">FIN NUMBER</label>
                              <div class="col-md-4">
                                       <?php
                                          if(isset($fin_number->fin_number) && ($fin_number->fin_number != "") && ($fin_number->fin_number != null)){
                                       ?>
                                             <!-- <input type="text" id="fin_number" value="{{$fin_number->fin_number}}"> -->
                                             @text("fin_number", ["id" => "fin_number","value" => $fin_number->fin_number])  
                                       <?php
                                          }else{?>

                                             <!-- <input type="text" id="fin_number" value=""> -->
                                             @text("fin_number", ["id" => "fin_number"])   

                                         <?php }
                                       ?>
                              </div>
                           </div>
                        </div>


                          			            
                           <input type='hidden' name='call_answer_template_id' value='<?php echo  $introductionScriptId; ?>'><br>
                          
						   <?php
								// if($introductionScript){
								// 	$intro = get_object_vars(json_decode($introductionScript));
								// 	echo $intro['message'];
                        // }
                       
                        if($introductionScript){
                           $intro = get_object_vars(json_decode($introductionScript));
                           $replace_provider = str_replace("[provider]", $provider_name, $intro['message']);
                           $replace_practice_name = str_replace("[practice_name]", $practice_name, $replace_provider);
                           $replace_user = str_replace("[users_name]", Session::get('f_name')." ".Session::get('l_name'), $replace_practice_name);
                           echo $replace_user;
								}
						   ?>
						   </div>
                           <textarea hidden="hidden" name="call_answer_template" class="form-control call_answer_template" id="call_answer_template">
								 <?php
								// if($introductionScript){
								// 	$intro = get_object_vars(json_decode($introductionScript));
								// 	echo $intro['message'];							
                        // }
                        if($introductionScript){
                           $intro = get_object_vars(json_decode($introductionScript));
                           // $provider_data = $patient_providers['provider']; 
                           // $practice_data = $patient_providers['practice']; 
                           // $provider_name = empty($provider_data['name']) ? '[provider]' : $provider_data['name'];
                           // $practice_name = empty($practice_data["name"]) ? '' : $practice_data["name"];
                           $replace_provider = str_replace("[provider]", $provider_name, $intro['message']);
                           $replace_practice_name = str_replace("[practice_name]", $practice_name, $replace_provider);
                           $replace_user = str_replace("[users_name]", Session::get('f_name')." ".Session::get('l_name'), $replace_practice_name);
                           echo $replace_user;
								}
								?>						   
						   </textarea>
                        </label>
                        <div class="">
                           <label for="Continue this call?">Okay, can I get you enrolled in this program today?<span class="error">*</span></label><br />
                           <div class="form-row forms-element">
                              <label class="radio radio-primary col-md-4 float-left">
                                 <input type="radio" class="" name="call_continue_status" value="1" formControlName="radio">
                                 <span>Yes</span>
                                 <span class="checkmark"></span>
                              </label>
                              <label class="radio radio-primary col-md-4 float-left">
                                 <input type="radio" class="" name="call_continue_status" value="0" formControlName="radio">
                                 <span>No</span>
                                 <span class="checkmark"></span>
                              </label>
                           </div>
                           <div class="form-row invalid-feedback"></div>
                        </div>
                     </div>
                     <div class="col-md-9" id="callNotAnswer" style="display:none">
                        <div class="mb-3">
                           <select class="forms-element custom-select" id="answer" name="voice_mail">
                              <option value="">Select Voice Mail Action</option>
                              <option value="1" <?php if (isset($callstatus->voice_mail) && ($callstatus->voice_mail == '1')) echo "selected"; ?>>Left Voice Mail</option>
                              <option value="2" <?php if (isset($callstatus->voice_mail) && ($callstatus->voice_mail == '2')) echo "selected"; ?>>No Voice Mail</option>
                           </select>
                           <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                           <!-- <div class="form-group" id="template_content"> -->
                              <div class="row">
                                 <div class="col-md-6"><label>Contact No. <span class="error">*</span></label>
                                    <select class="forms-element custom-select" name="phone_no" id="contact_number">
                                       <option value="">Choose Contact Number</option>
                                       <?php
                                          if(isset($patient[0]->mob) && ($patient[0]->mob != "") && ($patient[0]->mob != null)){
                                       ?>
                                             <option value="{{$patient[0]->mob}}">{{$patient[0]->mob}}</option>
                                       <?php
                                          }
                                       ?>
                                       <?php
                                          if(isset($patient[0]->home_number) && ($patient[0]->home_number != "") && ($patient[0]->home_number != null)){
                                       ?>
                                             <option value="{{$patient[0]->home_number}}">{{$patient[0]->home_number}}</option>
                                       <?php
                                          }
                                       ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                 </div>
                                 <div class="col-md-6"><label>Template Name <span class="error">*</span></label>
                                    <?php
                                       $call_not_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Not Answered');
                                    ?>
                                    @selectcontentscript("call_not_answer_template_id",$module_id,$submodule_id,$stage_id,$call_not_answered_step_id,["id"=>"call_not_answer_template_id","class"=>"custom-select"])
                                 </div>
                              </div>
                              <div class="row" id="textarea">
                                 <div class="col-md-12 form-group mb-3">
                                    <label><h3>Content</h3> <span class="error">*</span></label>
                                    <textarea name="text_msg" class="form-control" id="call_not_answer_content_area" rows="8" data-feedback="text-msg-feedback"></textarea>
                                    <div class="invalid-feedback visible" data-feedback-area="text-msg-feedback"></div>
                                 </div>
                              </div>
                           <!-- </div> -->
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-footer">
                  <div class="mc-footer text-right" id="call-save-button" >
                     <!-- <div class="row">
                        <div class="col-lg-12 text-right" id="call-save-button" > -->
                           <button type="submit" class="btn btn-primary m-1" id="save-callstatus">Next</button>
                        <!-- </div>
                     </div> -->
                  </div>
               </div>

            </div>
         </form>
      </div>
   </div>
<!-- </div> -->