<div class="card">
    <div class="card-body text-left">
        <div class="row">
        <?php 
        if(isset($call_status)){$cstatus = $call_status->call_status;}
        else{$cstatus = '0';}  ?>
            <label class="col-md-2 mb-4 float-left">Call Answered</label>
            <label class="radio radio-primary ">
                <input type="radio" name="call_radio" value="1" formControlName="radio" id="answered" <?php if($cstatus == 1){echo "checked"; } ?> >
                <span></span>
                <span class="checkmark"></span>
            </label>

            <div class="col-md-6 mb-4 float-left" id="answer"  <?php if($cstatus != 1){ ?> style= "display:none" <?php }?> >
				<?php
                                    $module_id = getPageModuleName();
                                    $submodule_id = getPageSubModuleName();
                                    $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Call');
                                
                                    $template_id=0;
                                    $call_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Answered');                                    
                                    ?>
                                    @hidden("SteponeCallScript_template_type_id",["id"=>"SteponeCallScript_template_type_id"])
                                    @selectcontentscript("content_title",$module_id,$submodule_id,$stage_id,$call_answered_step_id,["id"=>"SteponeCallScript","class"=>"custom-select", "value" =>$template_id])

									
                
                <span id="callErrors" style="display:none;color:red">Choose The Call Script<br></span>
                <label for="intro-script">
                    <h5>Introduction Script </h5>
                    <div class="call-answered-template-intro-script"></div>
                    <textarea name="call_answered_template" id="script_content" class="call-answered-template-intro-script"hidden="hidden"></textarea>
                </label>
                <div class="row">
                <div class="col-md-12 mb-4 float-left ml-1"><h5>Is this a good time to talk?</h5></div>
                           <label class="radio radio-primary col-md-2 float-left ml-2">
                              <input type="radio" id="stepyes" class="" name="available_to_talk" value="1" formControlName="radio" onchange="stepSecond(2);">
                              <span>Yes</span>
                              <span class="checkmark"></span>
                           </label>
                           <label class="radio radio-primary col-md-2 float-left">
                              <input type="radio" id="" class="" name="available_to_talk" value="0" formControlName="radio"  onchange="stepSecond(7);">
                              <span>No</span>
                              <span class="checkmark"></span>
                           </label>
                        </div>
            </div>


        </div>
        <div class="row">
            <label class="col-md-2 mb-4 float-left">Call Not Answered</label>
            <label class="radio radio-primary col-md-1">
                <input type="radio" name="call_radio" value="2" formControlName="radio" id="not_answered" <?php if($cstatus == 2){echo "checked"; } ?>>
                <span></span>
                <span class="checkmark"></span>
            </label>

<div class="col-md-6 mb-4 float-left" id="not_answer" <?php if($cstatus == 2){ ?> <?php }else{?>  style= "display:none" <?php }?>>
                <div class="mb-3">
                
                    @selectanswer("voice_mail",["id" => "voice_mail", "class" => "custom-select show-tick custom-select"]) 
                    <span class="voicError" style="display:none">Select Voice Action</span>
                </div>    
                <div class="mb-3">
                     <!--form id="template_fetch" method="post"> 
                        {{ csrf_field() }}-->
                        
                        <div class="form-group" id="template_content">
                              <div class="row">
                                 <div class="col-md-6 form-group mb-3"><label>Contact No.</label>
                         
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
           
                                 </div>
                                 <div class="col-md-8 mb-4 float-left" id="Notanswer" style=display:none></div>
                                 <div class="col-md-6 form-group mb-3"><label>Template Name</label>
                                 <?php
                                    $module_id = getPageModuleName();
                                    $submodule_id = getPageSubModuleName();
                                    $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Call');
                                
                                    $template_id=0;
                                    $call_not_answered_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Call Not Answered');
                                    
                                    ?>
                                    @hidden("call_not_answer_template_type_id",["id"=>"call_not_answer_template_type_id"])
                                    @selectcontentscript("call_not_answer_template_id",$module_id,$submodule_id,$stage_id,$call_not_answered_step_id,["id"=>"call_not_answer_template_id","class"=>"custom-select", "value" =>$template_id])

                                 </div>
                                 
                              </div>

                              <div class="row" id = "textarea">
                                 <div class="col-md-12 form-group mb-3" >
                                    <label><h3>Content</h3></label>
                                    <textarea name="text_msg" class="form-control" id="content_area"></textarea>
                                 </div>    
                              </div>
                              <div class="row">
                                 <div class="col-lg-12 text-right">
                                    <button type="button" class="btn btn-primary m-1 callNotAns">Send Text Message</button>
                                 </div>    
                              </div>
                        </div>
                     <!--/form-->
                  </div>
                  <!--div class="col-md-12 form-group mb-3">
                     <label for="chedule_next_call">Select Call Follow-up date:</label>
                    
                     @date("call_followup_date",["id" => "call_followup_date", "class" => ""])
                     <button type="#submit" class="btn btn-primary m-1" >Schedule Call</button>
                  </div-->
            </div>
        </div>
    </div>
<!--     <div class="card-footer">
        <div class="row">
            <div class="col-lg-12 text-right">
                <button type="submit" class="btn  btn-primary m-1" >Save</button>
            </div>
        </div>
    </div> -->
</div>

<!-- Modal -->
<div class="modal fade script_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" id="script_modal" width="95%">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">script</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div class="row mb-4">
    <div class="col-md-12">
        <div class="card grey-bg">
            <div class="card-body">             
                <div class="form-row">
                <input type="hidden" name="hidden_id" id="hidden_id" value="{{$checklist->id}}">
                    <input type="hidden" name="practice_id" id="practice_id" value="{{$checklist->practice_id}}">
                    <input type="hidden" name="script_module_id" id="script_module_id" value="{{$module_id}}" >
                    <input type="hidden" name="script_template_id" id="script_template_id" >
                    <input type="hidden" name="script_component_id" id="script_component_id" value="{{$submodule_id}}">
                    <input type="hidden" name="script_stage_id" id="script_stage_id" value="{{$stage_id}}" >
                    <input type="hidden" name="script_template_type_id" id="script_template_type_id">
                    <div class="col-md-1">
                        @if($checklist->gender =='1')
                        <img src="{{asset("assets/images/faces/oldimages.jpg")}}" class='user-image' style=" width: 60px;" />
                        @else
                        <img src="{{asset("assets/images/faces/oldmen.jpg")}}" class='user-image' style=" width: 60px;" />
                        @endif
                    </div>
                    <div class="col-md-11">
                        <div class="form-row">
                            <div class="col-md-3">
								<label class="patientname">{{$checklist->fname}} {{$checklist->lname}}</label><br/>
                                <label for="name">
                                    @if($checklist->gender =='1')
                                        Female
                                    @else
                                        Male
                                    @endif
                                    ({{ calAge($checklist->dob)}})
                                </label>
                                
                                
                            </div>
                            <div class="col-md-3">
                                <label for="name"><i class="text-muted i-Old-Telephone"></i> : <b>{{$checklist->phone_primary}}</b> </label>
                                <br>
                                <label for="name"><i class="text-muted i-Email"></i> : {{$checklist->email}} </label>
                            </div>
                            <div class="col-md-3">
                                <label for="name"><i class="text-muted i-Old-Telephone"></i> : {{$checklist->emr}} </label>
                                <br>
								<label for="name"><i class="text-muted i-Over-Time"></i> : {{date('m/d/Y', strtotime($checklist->dob))}}  </label>
                                
                                <!-- <label for="name"><i class="text-muted i-VPN"></i> : {{date('m/d/Y', strtotime($checklist->created_at))}}  </label> -->
                            </div> 
                            <div class="col-md-3">
								<label for="name">Programs: </label> RPM + CCM                                 
                            </div>
                        </div>
                        <!-- <div class="form-row col-md-12">
                            <div id="chronoExample">
                                <div class="">Time Elapsed: <span class="values">00:00:00</span> Hours
                                </div>
                                <div class="float_left">
                                    <!- - <button class="startButton"><i class="text-muted i-Play"></i> </button> - ->
                                    <button class="pauseButton" ><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 34px;" /></button>
                                    <button class="stopButton"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 34px;" /></button>
                                    <!- - <button class="resetButton">Reset</button> - ->
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="form-row ">
                    <span class="col-md-8">
                        <h4>Patient Status</h4>
                    </span>
                   <div id="chronoExample">
					<div class="float_left">Time Elapsed: <span class="values">00:00:00</span> Hours
					</div>
					<div class="float_left" style="margin-top:-3px">
						<!-- <button class="startButton"><i class="text-muted i-Play"></i> </button> -->
						<button class="pauseButton" ><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 29px;" /></button>
						<button class="stopButton"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 30px;" /></button>
						<!-- <button class="resetButton">Reset</button> -->
						</div>
					</div>
                </div>
				
            </div>
            <div class="card-body "> 
				<div class="row">
				<div class="col-md-7 status_section">
				<label for="name">Enrollment Status: </label> New <br/>
				
                <label for="name">Total Time Elapsed: </label> 00:14:00 Hours<br/>
                <label for="name">Communication Vehicle: </label> @if($checklist->contact_preference_email =='1')
                                        <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="email">
                                            <span class="ul-btn__icon"><i class="i-Email"></i></span>
                                            <span class="ul-btn__text"></span>
                                        </button>
                                    <!-- <img src="{{asset("assets/images/checkmark.svg")}}" class=" " style="width: 2em; border-radius: 6em; margin-left: 7px;" /> -->
                                    @endif
									
									@if($checklist->contact_preference_calling =='1')
                                        <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="Calling">
                                            <span class="ul-btn__icon"><i class="i-Headset"></i></span>
                                            <span class="ul-btn__text"></span>
                                        </button>
                                    <!-- <img src="{{asset("assets/images/checkmark.svg")}}" class="" style="width: 2em; border-radius: 6em; margin-left: 7px;" /> -->
                                    @endif
									
									@if($checklist->contact_preference_sms =='1')
                                        <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="text">
                                            <span class="ul-btn__icon"><i class="i-Letter-Open"></i></span>
                                            <span class="ul-btn__text"></span>
                                        </button>
                                    <!-- <img src="{{asset("assets/images/checkmark.svg")}}" class="" style="width: 2em; border-radius: 6em; margin-left: 7px;" /> -->
                                    @endif
						
                    <div class="mb-1 mt-1">
                        <label class=" mb-1"><strong>Chronic Condition(s):</strong></label>
                        <span>Hypertension (I10), COPD (J44.9), Diabetes (E11.9), Depression (F32.9)</span>
                    </div>
                    <div class="mb-1">
                        <label class=" mb-1"><strong>RPM Device(s):</strong></label>
                        <span>Blood Pressure Cuff</span>
                    </div>
                    <div class="mb-1">
                        <label class=" mb-1"><strong>Medications / Vaccines:</strong></label>
                        <span>Enduron (5 mg 1x daily), Lopressor (100 mg 2x daily), Zoloft (25 mg 2x daily)</span>
                    </div>
                    <div class="mb-1">
                        <label class=" "><strong>Date of Last Contact:</strong></label>
                        <span>November 23, 2019</span>
                    </div>
                    <div class="mb-1">
                        <label class=" "><strong>Personal Notes:</strong></label>
                        <span>Wife is deceased (2015).  Daughter (Julie) is his primary care giver.  Dog lover; has 3 golden retrievers (Charlie, Thor, Rex); Rex is old and sick.  Grandson goes to UT, in his third year and is studying to be an architect.  Likes to play bingo at the Catholic Church on Wednesdays.  Plays golf on Saturday morning with regular group.</span>
                    </div>
                    <div class="mb-1">
                        <label class=" "><strong>Part of Research Study:</strong></label>
                        <span>Self-Management Grant (G1C)</span>
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
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        //call answerd content script 
        $("#SteponeCallScript option:last").attr("selected", "selected").change();
        util.getCallScriptsById($("#SteponeCallScript").val(), '.call-answered-template-intro-script','#SteponeCallScript_template_type_id');
        $("#SteponeCallScript").change(function(){
            util.getCallScriptsById($(this).val(), '.call-answered-template-intro-script','#SteponeCallScript_template_type_id');
        });

        //call answered - enrollment content scripts
        $("#call_scripts option:last").attr("selected", "selected").change();
        util.getCallScriptsById($("#call_scripts").val(), '.enrollment_script', '#call_scripts_template_type_id');
        $("#call_scripts").change(function(){
            util.getCallScriptsById($(this).val(), '.enrollment_script', '#call_scripts_template_type_id');
        });

        //call not answered scripts
        $("#call_not_answer_template_id option:last").attr("selected", "selected").change();
        util.getCallScriptsById($("#call_not_answer_template_id").val(), '#content_area', '#call_not_answer_template_type_id');
        $("#call_not_answer_template_id").change(function(){
            util.getCallScriptsById($(this).val(), '#content_area', '#call_not_answer_template_type_id');
        });

        //call not answered scripts
        $("#email_content_title option:last").attr("selected", "selected").change();
        util.getCallScriptsById($("#email_content_title").val(), '#email_content_area_msg', '#email_template_type_id');
        $("#email_content_title").change(function(){
            util.getCallScriptsById($(this).val(), '#email_content_area_msg', '#email_template_type_id');
        });
    });
</script>