@extends('Theme::layouts_2.to-do-master')
@section('main-content')


<meta name="csrf-token" content="{{ csrf_token() }}" />
<div id="app">
	<div id="success">
	</div>	
	 

	<div class="card-body">
		<div class="row mb-4">
			<div class="col-md-12  mb-4">
				<div class="card mb-4">
					<div class="card-header mb-3">
						<div class="row">
							<div class="col-9">EDIT PATIENT REGISTRATION</div>
							<div class="row col-3">
								<label for="total time" data-toggle="tooltip" data-placement="right" title="Total Time Elapsed" data-original-title="Total Time Elapsed" style="margin-top: 2px; margin-right: 15px;"><i class="text-muted i-Clock-4"></i> : <span class="last_time_spend"><?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?></span></label><br>     
								<div class="demo-div">
								<?php
									$role_type = session()->get('role_type');						
									$showstopbtn = "inline-block";
									if(isset($role_type) && $role_type =="Care Managers" ) {  
										$showstopbtn = "none";
									}
								?>
										
									@hidden("timer_start",["id"=>"timer_start"])
									@hidden("timer_end",["id"=>"timer_end"])
									@hidden("page_landing_time",["id"=>"page_landing_time"])
                                        @hidden("patient_time",["id"=>"patient_time"])
                                        @hidden("pause_time",["id"=>"pause_time", "value"=>"0"])
                                        @hidden("play_time",["id"=>"play_time", "value"=>"0"])
                                        @hidden("pauseplaydiff",["id"=>"pauseplaydiff", "value"=>"0"])
										<input type="hidden" id="page_landing_times" name="page_landing_times" value=''>
										
									<div class="stopwatch" id="stopwatch">
										<i class="text-muted i-Timer1"></i> :
										<div id="time-container" class="container" data-toggle="tooltip" data-placement="right" title="Current Running Time" data-original-title="Current Running Time" style="display:none!important"></div>
										<label for="Current Running Time" data-toggle="tooltip" title="Current Running Time" data-original-title="Current Running Time">
                                        <span id="time-containers"></span></label>
										<a class="button" id="start" data-toggle="tooltip" data-placement="right" title="Start Timer" data-original-title="Start Timer" onclick="util.logPauseTime($('.form_start_time').val(), {{$patient[0]->id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient[0]->id}}, 0, 'log_time_<?php $uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');" ><img src="{{asset('assets/images/play.png')}}" style=" width: 28px;" /></a>
										<a class="button" id="pause" data-toggle="tooltip" data-placement="right" title="Pause Timer" data-original-title="Pause Timer" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient[0]->id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient[0]->id}}, 0, 'log_time_<?php $uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');"  ><img src="{{asset('assets/images/pause.png')}}" style=" width: 28px;"   /></a>
										<a class="button" id="stop" data-toggle="tooltip" data-placement="right" title="Stop Timer" data-original-title="Stop Timer" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient[0]->id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient[0]->id}}, 0, 'log_time_<?php $uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');" style="display:<?php echo $showstopbtn; ?>"><img src="{{asset('assets/images/stop.png')}}" style=" width: 28px;" /></a>
										<button class="button" id="reset" data-toggle="tooltip" data-placement="top" title="Reset Timer" data-original-title="Reset Timer" style="display:none;">Reset</button>
                                            <button class="button" id="resetTickingTime" data-toggle="tooltip" data-placement="top" title="resetTickingTime Timer" data-original-title="resetTickingTime Timer" style="display:none;">resetTickingTime</button>
									</div>
								</div>
							</div>
						</div>
							<!-- <div class='row'><div class="col-md-8">PATIENT REGISTRATION</div>
							<div class="col-md-1">
								<label for="total time" data-toggle="tooltip" data-placement="right" title="Total Time Elapsed" data-original-title="Total Time Elapsed"><i class="text-muted i-Clock-4"></i> {{-- Total Time Elapsed --}}: <?php //echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?></label><br>     
							</div>            
										<div class="col-md-2 demo-div">
											{{-- @hidden("timer_start",["id"=>"timer_start"]) --}}
											{{-- @hidden("timer_end",["id"=>"timer_end"]) --}}
											<div class="stopwatch d-inline-flex" id="stopwatch" data-toggle="tooltip" data-placement="right" title="Current Running Time" data-original-title="Current Running Time">
												<i class="text-muted i-Timer1"></i>:
												<div id="time-container" class="container"></div>
												<button class="button" id="start"><img src="{{-- asset('assets/images/play.png') --}}" class='user-image' style=" width: 28px;" /></button>
												<button class="button" id="pause"><img src="{{-- asset('assets/images/pause.png') --}}" class='user-image' style=" width: 28px;" /></button>
												<button class="button" id="stop"><img src="{{-- asset('assets/images/stop.png') --}}" class='user-image' style=" width: 28px;" /></button>
											</div>
										</div>
							</div> -->
					</div>
					<div class="card-body" id="hobby">
						<!--  -->
						<form action="{{ route('ajax.update.patient.registration')}}" method="post" name ="patient_registration_form"  id="patient_registration_form" enctype="multipart/form-data">
						<!-- <form action="" method="post" autocomplete="off" name="patient_registration_form" id="patient_registration_form"> -->
							<?php
				                $module_id = getPageModuleName();             
				                $submodule_id = getPageSubModuleName();
								$stage_id = getFormStageId($module_id, $submodule_id, 'Registration'); 
							?>
							@csrf
						    <input type="hidden" name="form_name" value="edit_patient_registration_form">
							<input id="patient_id" name="patient_id" type="hidden"  value="{{$patient[0]->id}}">
							<input id="edit" name="edit" type="hidden"  value="1">
							<input type="hidden" name="module_id" id="module_id" value="{{$module_id}}" >
							<input type="hidden" name="submodule_id" id="submodule_id" value="{{$submodule_id}}">
							<input type="hidden" name="component_id" value="{{ $submodule_id }}" />
							<input type="hidden" name="stage_id" value="{{$stage_id}}">
		    				<input type="hidden" name="step_id" value="0">
							<input type="hidden" name="enroll_service" id="enroll_service" value="">
							<input type="hidden" name="start_time" id="start_time">
							<input type="hidden" name="end_time" id="end_time">
							<input type="hidden" name="organization" value="" id="organization"/>
							<input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" >
							<div class="alert alert-success" id="success-alert" style="display: none;">
								<button type="button" class="close" data-dismiss="alert">x</button>
								<strong> Patient data updated successfully! </strong><span id="text"></span>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>Select Practice<span class="error">*</span></label> 
										<?php if(isset($patientProvider[0]->practice_id) && $patientProvider[0]->practice_id != ''){ 
											$practices = $patientProvider[0]->practice_id; 
										}else{  
											$practices ='';
										}?> 
										@selectpracticespcp("practice_id",["id"=>"practices", "value" => $practices])
										<!-- selectpractices("practice_id", ["id" => "practices", "value" => $practices])  -->
									</div>
								</div>
								<div class="col-6" id="choose_provider">
									<div class="form-group">
										<label>Select Primary Care Provider (PCP)<span class="error">*</span></label>
										<?php if(isset($patientProvider[0]->provider_id) && $patientProvider[0]->provider_id != ''){ 
											$physician = $patientProvider[0]->provider_id; 
										}else{  
											$physician ='';
										}?>
										@select("Primary Care Provider (PCP)", "provider_id", [], ["id" => "physician", "value" => $physician])
										{{-- @selectpcpprovider("provider_id", ["id" => "physician", "value" => $physician]) --}}
									</div>
								</div>
								<div class="providers_name col-3" style="display:none"> 
									<div class="form-group">
										<label for="provider_id">Provider Name<span class="error">*</span></label>	
									 	{{-- @text("name",["id" => "pro_name", "class" => "capitalize"]) --}}
										@text("provider_name",["id"=>"pro_name", "class" => "capitalize"]) <!-- updated by pranali on 29Oct2020  -->
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-4">
									<div class="form-group">
										<label for="lname">Last Name<span class="error">*</span></label>
										<?php //isset($patient[0]->lname) && ($patient[0]->lname != '') ? $lname = $patient[0]->lname : $lname =''?>
										@text("lname", ["id" => "lname", "class" => "capitalize"]) <!--"disabled"=>"disabled" --> 
										@hidden("uid", ["id" => "uid"])
									</div>
								</div>
								<div class="col-4">
									<div class="form-group">
										<label for="fname">First Name<span class="error">*</span></label>
										<?php //isset($patient[0]->fname) && ($patient[0]->fname != '') ? $fname = $patient[0]->fname : $fname =''?>
										@text("fname", ["id" => "fname", "class" => "capitalize" ]) <!--"disabled"=>"disabled" -->
									</div>
								</div>
								<div class="col-4">
									<div class="form-group">
										<label for="mname">Middle Name</label>
										<?php //isset($patient[0]->mname) && ($patient[0]->mname != '') ? $mname = $patient[0]->mname : $mname =''?>
										@text("mname", ["id" => "mname", "class" => "capitalize"]) <!--"disabled"=>"disabled" -->
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-4 col-md-2">
									<div class="form-group">
										<label>Gender<span class="error">*</span></label>
										@select("Gender", "gender", [0 => "Male",1 => "Female"])
									</div>
								</div>
								<div class="col-md-3 form-group">
									<label>Marital Status<span class="error">*</span></label>
									@select("Marital Status", "marital_status", [
									"single" => "Single",
									"partnered" => "Partnered",
									"married" => "Married",
									"separated" => "Separated",
									"divorced" => "Divorced",
									"widowed" => "Widowed"
									])
								</div>
								<div class="col-8 col-md-3">
									<div class="form-group">
										<label>Date of Birth<span class="error">*</span></label>
										@date("dob") <!--"disabled"=>"disabled" -->
									</div>
								</div>
								<div class="col-4 col-md-2">
									<label>Age</label>
									<input type="number" class="form-control" id="age" name="age" readonly>
								</div>
								<div class="col-4 col-md-2">
									<label>Fin Number</label>
									@text("fin_number", ["id" => "fin_number"])
									
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label for="add_1">Address Line 1<span class="error">*</span></label>
										@text("add_1", ["id" => "add_1"])
									</div>
								</div>
							<!-- </div>
							<div class="row"> -->
								<div class="col-6">
									<div class="form-group">
										<label for="add_2">Address Line 2</label>
										@text("add_2", ["id" => "add_2"])
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="city">City<span class="error">*</span></label>
										@text("city", ["id" => "city", "class" => "capitalize"])
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="state">State<span class="error">*</span></label>
										@selectstate("state", request()->input("state"))
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="zip">Zip Code<span class="error">*</span></label>
										@text("zipcode", ["id" => "zip"])
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-4">
									<label>Ethnicity</label>
									@select("Ethnicity", "ethnicity", config("form.ethnicities"))
								</div>
								<div class="form-group col-md-4">
									<label>Other Ethnicity (Optional)</label>
									@select("Ethnicity", "ethnicity_2", config("form.ethnicities"))
								</div>
								<div class="col-md-4 form-group">
									<label>Education</label>
									@select("Education", "education", config("form.education"))
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-3">
									<label>Occupation Status</label>
									@select("Occupation Status", "occupation", config("form.occupation_status"))
								</div>
								<div class="form-group col-md-5">
									<label for="occupation_description">Occupation Description</label>
									@text("occupation_description")
								</div>
								<div class="col-md-4 form-group">
									<label>Are you or your spouse a Veteran?<span class="error">*</span></label>
									@select("Veteran status", "military_status", [
									0 => "Yes",
									1 => "No",
									2 => "Unknown"
									], [ "id" => "military" ])
								</div>
							</div>


							
<div class="row" id="veteran-question" style="display:none">
		<div class="col-lg-12 mb-3">
			<div class="card">
				<div class="card-body">		
				<?php 
				$number = 1;
				$content = "";
				if(isset($veteranQuestion['question'])){
					echo '<input type="hidden" name="question[question][template_id]" value="'.$veteranQuestion['id'].'" >';
				$queData = json_decode($veteranQuestion['question']);
				$questionnaire = $queData->question->q;
				$patient_questionnaire = array();
				if(isset($patient_demographics[0]->template)){
					$patient_questionnaire = json_decode($patient_demographics[0]->template, true);
					
				}
				
				foreach($questionnaire as $value){
					//print_r($patient_questionnaire);
					$questionTitle = trim($value->questionTitle);
                	$questionExist = 0;
						if (array_key_exists($questionTitle, $patient_questionnaire)) {
							$questionExist = 1;
						}
						//echo $questionExist;
						//print_r($questionTitle);
					?>

						<div class="mb-4 radioVal" id="general_question11">
							
						<?php $que_val = trim(preg_replace('/\s+/', ' ',$value->questionTitle)); ?>
							<label for="are-you-in-pain" class="col-md-12">
							<input type="hidden" name="" value="<?php echo $que_val; ?>">
							<?php echo $value->questionTitle; ?>
						</label>
	
							<div class=" mb-2 col-md-12">
								<?php
									 if (property_exists($value, 'label') && $value->answerFormat == '1') {
                    
										echo '<select name="question[question]['. $value->questionTitle.']" class="col-md-3 custom-select" >';
											echo '<option value="">Select Option</option>';
										foreach($value->label as $labels) {
											echo '<option value="'.$labels.'" '.($questionExist && $patient_questionnaire[$questionTitle]==$labels ? 'selected': '').'>'.$labels.'</option>';
										}
										echo '</select><div class="invalid-feedback"></div>';
										
									}
									elseif($value->answerFormat == '2') {
										echo '<input type="text" name="question[question]['.$value->questionTitle.']" class="form-control col-md-8" value="'.($questionExist ? $patient_questionnaire[$questionTitle] : '').'" ><div class="invalid-feedback"></div>';
									}
									elseif(property_exists($value, 'label') && $value->answerFormat == '3') { 
										echo '<div class="checkRadio forms-element">';
										foreach($value->label as $labels){
											echo '<label class="radio radio-primary col-md-4 float-left" for="'.$value->questionTitle.'_'.$labels.'">
																		<input type="radio" name="question[question]['.$questionTitle.']" value="'.$labels.'" formControlName="radio" id="'.$value->questionTitle.'_'.$labels.'"  '.($questionExist && $patient_questionnaire[$questionTitle]==$labels ? 'checked': '').'>
																		<span>'.$labels.'</span>
																		<span class="checkmark"></span>
																	</label>';
										}
										echo '</div><div class="invalid-feedback"></div>';
									}
									elseif(property_exists($value, 'label') && $value->answerFormat == '4') {    
										echo '<div class="checkRadio forms-element">';
										foreach($value->label as $labels) {
		
											$labelArray = str_replace(' ','_', trim($labels));
											if($questionExist && isset($patient_questionnaire[$questionTitle][str_replace(' ','_',$labels)])){		
												echo '<label class="checkbox checkbox-primary col-md-4 float-left" for="'.$value->questionTitle.'_'.$labelArray.'">
																			<input class="form-check-input" value="'.$labels.'" type="checkbox" name="question[question]['.$value->questionTitle.']['.$labelArray.']" id="'.$value->questionTitle.'_'.$labelArray.'"  '.($questionExist && $patient_questionnaire[$questionTitle][str_replace(' ','_',$labels)]==1 ? 'checked': '').'>
																			<span>'.$labels.'</span>
																			<span class="checkmark"></span>
																		</label>';
												}else{
													echo '<label class="checkbox checkbox-primary col-md-4 float-left" for="'.$value->questionTitle.'_'.$labelArray.'">
																			<input class="form-check-input" value="'.$labels.'" type="checkbox" name="question[question]['.$value->questionTitle.']['.$labelArray.']" id="'.$value->questionTitle.'_'.$labelArray.'"  >
																			<span>'.$labels.'</span>
																			<span class="checkmark"></span>
																		</label>';
												}
										}
										echo '</div><div class="invalid-feedback"></div>';
									} elseif($value->answerFormat == '5') {
										echo '<textarea class="form-control col-md-8" name="question[question]['.$value->questionTitle.']" >'.($questionExist ? $patient_questionnaire[$questionTitle] : '').'</textarea><div class="invalid-feedback"></div>';
									}
									
								?>

							</div>
							<p class="message" style="color:red"></p>
						</div>
						<div id="question"></div>
						<br>
						<hr>
				<?php $number++; 
				
			} }
			
			?>
				</div>
				
			</div>
		</div>
	</div> 


							<div class="row">
								<div class="col-md-4 form-group">
									<label for="email">Email</label>
									@checkbox("None", "no_email", "no_email")
									<!-- <div class="col-md-12">
										<input class="form-check-input" type="checkbox"  id="no_email" name="no_email" >
										<label class="form-check-label" for="no-email">No Email</label>
										</div> -->
								</div>
							</div>
							<div data-toggle="buttons">
								<div class="row">
									<div class="col-md-6">
										<div class="input-group form-group">
											<div class="input-group-prepend btn-group btn-group-toggle" >
												<label class="btn btn-outline-primary" for="email-preferred">
												Preferred
												@input("radio", "preferred_contact", ["id" => "email-preferred", "value" => "2", "data-feedback" => "contact-preferred-feedback"])
												</label>
											</div>
											@email("email", ["id" => "email"])
										</div>
									</div>
								</div>
								<div class="row">
								<div class="col-md-3">
											<label>Primary No. Country Code</label>
											@selectcountrycode("country_code", ["id" => "country_code"]) 
										</div>
										
									<div class="col-md-3">
										<label for="phone_primary">Primary Phone Number<span class="error">*</span></label>
										<div class="input-group form-group">
											<div class="input-group-prepend btn-group btn-group-toggle" >
												<label class="btn btn-outline-primary" for="mob">
												Preferred
												@input("radio", "preferred_contact", ["id" => "phone-primary-preferred", "value" => "0", "data-feedback" => "contact-preferred-feedback"])
												</label>
											</div>
											<!-- @phone("phone_primary") -->
											@phone("mob", ["id"=>"mob"])
										</div> 
									</div>
									<div class="col-md-1 form-group">
										
												<yes-no name="primary_cell_phone" id="primary_cell_phone" label-no="No" label-yes="Yes"><label style="margin-bottom:9px">Is Cell Phone</label></yes-no>
											
										</div>
										<div class="col-md-2 form-group iscellmargin" id="content_text">
											<yes-no id="consent_to_text" name="consent_to_text" label-no="No" label-yes="Yes"><label style="margin-bottom:9px">Consent To Text</label></yes-no>
										</div>
								</div>
								<div class="row">	
									<div class="col-md-3">
											<label>Secondary No. Country Code</label>
											@selectcountrycode("secondary_country_code", ["id" => "secondary_country_code"]) 
										</div>
										
									<div class="col-md-3">
										<label for="phone_secondary">Secondary Phone Number</label>
										<div class="input-group form-group">
											<div class="input-group-prepend btn-group btn-group-toggle" >
												<label class="btn btn-outline-primary" for="home_number">
												Preferred
												@input("radio", "preferred_contact", ["id" => "phone-secondary-preferred", "value" => "1", "data-feedback" => "contact-preferred-feedback"])
												</label>
											</div>
											<!-- @phone("phone_secondary") -->
											@phone("home_number", ["id"=>"home_number"])
										</div>
									</div>
									<div class="col-md-1 form-group">
											
												<yes-no name="secondary_cell_phone" id="secondary_cell_phone" label-no="No" label-yes="Yes"><label style="margin-bottom:9px">Is Cell Phone</label></yes-no>
											
										</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<label>Other Contact</label>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4 col-md-6 form-group">
										<label>Name</label>
										@text("other_contact_name")
									</div>
									<div class="col-lg-2 col-md-6 form-group">
										<label>Relationship</label>
										@text("other_contact_relationship")
									</div>
									<div class="col-lg-2 col-md-4 form-group">
										<label>Phone Number</label>
										@phone("other_contact_phone_number")
									</div>
									<div class="col-lg-4 col-md-8 form-group">
										<label>Email</label>
										@email("other_contact_email")
									</div>
								</div>
								<div class="row">
									<div class="col-12 text-right">
										<span class="invalid-feedback visible" style="display: inline;" data-feedback-area="contact-preferred-feedback"></span>
										<div class="btn-group btn-group-toggle">
											<label class="btn btn-outline-primary" for="preferred_contact">
											Preferred
											@input("radio", "preferred_contact", ["id" => "other-preferred", "value" => "3", "data-feedback" => "contact-preferred-feedback"])
											</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="invalid-feedback visible" data-feedback-area="other-preferred-feedback"></div>
									</div>
								</div>
							</div>
							
							<hr>
							@header("Best Time to Contact")
							<contact-time></contact-time>
							<!-- <yes-no name="discharge_instruct">Discharge Instruction</yes-no> -->
							<hr>
							<div class="row">
								<div class="col-6 div form-group">
									<label for="insurance_primary">Primary Insurance</label>
									@text("ins_provider[1]", ["id"=>"insurance_primary"])
									<!-- @text("insurance_primary") -->
								</div>
								<div class="col-6 div form-group">
									<label for="insurance_primary_idnum">ID#</label>
									@text("ins_id[1]" , ["id"=>"insurance_primary_idnum"])
									<!-- @text("insurance_primary_idnum") -->
									<input type="hidden" name="insurance_primary_idnum_check" id="insurance_primary_idnum_check" value="0">
									<input type="hidden" name="ins_type[1]" id="primary_insurance_type" value="primary">
								</div>
							</div>
							<div class="row">
								<div class="col-6 div form-group">
									<label for="insurance_secondary">Secondary Insurance</label>
									@text("ins_provider[2]" , ["id"=>"insurance_secondary"])
									<!-- @text("insurance_secondary") -->
								</div>
								<div class="col-6 div form-group">
									<label for="insurance_secondary_idnum">ID#</label>
									@text("ins_id[2]" , ["id"=>"insurance_secondary_idnum"])
									<!-- @text("insurance_secondary_idnum") -->
									<input type="hidden" name="insurance_secondary_idnum_check" id="insurance_secondary_idnum_check" value="0">
									<input type="hidden" name="ins_type[2]" id="secondary_insurance_type" value="secondary">
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label for="emr">EMR#<span class="error">*</span></label>
										@text("practice_emr", ["id" => "practice_emr"])
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 form-group">
									@checkbox("Has Power of Attorney", "poa", "poa")
								</div>
							</div>
							<div class="row">
								<div class="col-md-3 form-group">
									<label>First Name</label>
									@text("poa_first_name", ["id" => "poa_first_name", "disabled"])
								</div>
								<div class="col-md-3 form-group">
									<label>Last Name</label>
									@text("poa_last_name", ["id" => "poa_last_name", "disabled"])
								</div>
								<div class="col-md-3 form-group">
									<label>Relationship</label>
									@text("poa_relationship", ["id" => "poa_relationship", "disabled"])
								</div>
								<div class="col-md-3 form-group">
									<label>Phone Number</label>
									@phone("poa_phone_2", ["id" => "poa_phone", "disabled"])
								</div>
								<div class="col-md-3 form-group">
									<label>Email</label>
									@email("poa_email", ["id" => "poa_email", "disabled"])
								</div>
							</div>
							
							<hr>
							<div class="row">
									<div class="col-md-12">
										@header("Communication Vehicle")
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 form-group">
										@checkbox("Voice Call", "contact_preference_calling", "contact_preference_calling")
									</div>
							
							
									<div class="col-md-3 form-group">
										@checkbox("Text", "contact_preference_sms", "contact_preference_sms")
									</div>
						
						
									<div class="col-md-3 form-group">
										@checkbox("Email", "contact_preference_email", "contact_preference_email")
									</div>
						
							
									<div class="col-md-3 form-group">
										@checkbox("Letter", "contact_preference_letter", "contact_preference_letter")
									</div>
								</div>
							<hr>
							<!-- <div class="row">
							<div class="col-md-12">
								@header("Enrollment")
							</div> -->
							<?php
							
							//if(isset($services)){
							//$i = 0;
							//foreach($services as $service){
							//?>			
							<?php //echo $service[0]['module']; 
								// $enrollin //= "Enroll in ". $service['module'];
								//$moduleid //= $service['id'];
							?>							
							<div class="col-md-3 form-group"> 
									{{-- @checkbox($enrollin, "enroll[".$moduleid."]", "enroll_".$i , $moduleid , ["id"=> "enroll_".$i]) --}}	
							</div>										
							<?php
									//$i++;
									
								//}									
							//}
							
							//?>
							<!--</div>
							 <hr> -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">

										<label for="file"><span class="error"></span>Upload Patient's Image :</label>
										@file("file", ["id" => "file",'onchange'=>"uploadfile()"])
										<input type="hidden" name="image_path" id="image_path">
										<br/>
										<div id="viewprofileimg"> </div>
										<input type="hidden" name="profile_img" id="profile_img">

										<!--    <input type="hidden" name="img" id="img" value="test.abc"> 
										<span id="profile_img"></span> -->
									
							</div>
								</div>
								</div>
							<!--
							<div class="row">
								<div class="col-md-12 form-group text-right"> 
								@checkbox("Enroll in AWV", "enroll_awv", "enroll_awv", "2", [])
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 form-group text-right">
								@checkbox("Enroll in TCM", "enroll_tcm", "enroll_tcm", "4", [])
								</div> 
							</div>
							
							<div class="row">
								<div class="col-md-12 form-group text-right">
								@checkbox("Enroll in RPM", "enroll_rpm", "enroll_rpm", "3", [])
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 form-group text-right">
								@checkbox("Enroll in CCM", "enroll_ccm", "enroll_ccm", "1", [])
								</div>
							</div>
							
							-->
							
							

							
							<div class="row">
								<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
								<div class="col-12 text-right form-group mb-4"><button type="submit" class="btn btn-primary" id="submit">Submit</button></div>
							</div>
						</form>
					</div>	
				</div>	
			</div>	
		</div>	
	</div>	
</div>
<!-- </div> -->
@endsection
@section('page-js')
<script src="{{asset(mix('assets/js/laravel/editPatientRegistration.js'))}}"></script>
<script type="text/javascript">
	var time = "<?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>";
	var splitTime = time.split(":");
	var H = splitTime[0];
	var M = splitTime[1];
	var S = splitTime[2];
	$("#timer_start").val(time);
	$("#start_time").val(time);
	$("#patient_time").val(time);
	
	$(document).ready(function() {
		$('.firsttbox').click();

		util.stepWizard('tsf-wizard-2');
		$("#start").hide();
		$("#pause").show();
		$("#time-container").val(AppStopwatch.startClock);
		//alert($('#military').val());
		
		
		//alert('here');
		// $("#consent_to_text").change(function() {
		// 		//alert($("#consent_to_text-yes").val());
		// 		if($("#consent_to_text-yes").is(':checked')){
		// 			$("#contact_preference_sms").prop('checked', true);
		// 		}else{ 
		// 			$("#contact_preference_sms").prop('checked', false);
		// 		}
		// 	});
		// 	$("#mob, #home_number").blur(function(){
		// 		if($("#mob").val() == '' && $("#home_number").val() == ''){
		// 			$("#contact_preference_calling").prop('checked', false);
		// 		}else{
		// 			$("#contact_preference_calling").prop('checked', true);
		// 		}
				
  // 			});
		// 	  $('#email').blur(function() {
		// 		if(this.value !=''){
		// 			$("#contact_preference_email").prop('checked', true);
		// 		}else{
		// 			$("#contact_preference_email").prop('checked', false);
		// 		}
				
		// 	});
		// 	$('#email-preferred').change(function() {
		// 		$("#contact_preference_email").prop('checked', true);
		// 	});
		editPatientRegistration.init();
		util.getToDoListData(0, {{getPageModuleName()}});
		$("#file").removeClass("form-control");
		$('form[name="patient_registration_form"] input[name="fname"], form[name="patient_registration_form"] input[name="lname"], form[name="patient_registration_form"] input[name="dob"]').on("change", function () {
			var fName = $('form[name="patient_registration_form"] input[name="fname"]').val();
			var lName = $('form[name="patient_registration_form"] input[name="lname"]').val();
			var dob   = $('form[name="patient_registration_form"] input[name="dob"]').val();
			if( (fName != 'undefined' && fName != null && fName != "" ) && (lName != 'undefined' && lName != null && lName != "" ) && (dob != 'undefined' && dob != null && dob != "" ) ) {
				util.validateAndGenerateUid(fName, lName, dob);
			}
		});
		$('#military').change(function(){
			
				if(this.value == '0'){
					$('#veteran-question').show();
				}else{
					var success = confirm('Are you sure want to change this data? Previously saved data will be lost.');
					if (success == true) {
						// do something                  
					}
					else {
						$(this).val('0');
						return false; // will set the value to previous selected
					}
					$('#veteran-question').hide();
				}
			});
			
	});
		
	function uploadfile() {
		var fname=$('#fname').val();
		var lname=$('#lname').val();
		var file_data = $("#file").prop("files")[0];   
		var form_data = new FormData();
		form_data.append("fname", fname);
		form_data.append("lname", lname);
		form_data.append("file", file_data);

		$.ajax({
			url: '/patients/ajax/uploadImage',
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: form_data,
			enctype: 'multipart/form-data',
			success: function(data) {
				// console.log(data + "testingggg");    
				var imgpath=$.trim(data);
				$('#image_path').val(imgpath);
			},
			cache: false,
			contentType: false,
			processData: false
		});
	}



	

        
	

        
</script>
<script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection