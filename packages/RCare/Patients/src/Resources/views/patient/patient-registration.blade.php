@extends('Theme::layouts_2.to-do-master')
@section('main-content')

<meta name="csrf-token" content="{{ csrf_token() }}" />
<div id="app">
	<div id="success">
	</div>	

	<div class="card-body">
		<div id="error_msg"></div>
		<div class="row mb-4">
			<div class="col-md-12  mb-4">
				<form action="{{ route('ajax.patient.registration')}}" method="post" enctype="multipart/form-data" name ="patient_registration_form"  id="patient_registration_form">
					<div class="card mb-4">
						<div class="card-header mb-3">
							<div class="row">
								<div class="col-10">PATIENT REGISTRATION</div>
								<div class="col-2">
									<div class="demo-div">
										<?php  
											 $module_id = getPageModuleName();
               								 $submodule_id = getPageSubModuleName();
               								 $stage_id = getFormStageId($module_id, $submodule_id, 'Registration'); 
											 $role_type = session()->get('role_type');
						
											$showstopbtn = "inline-block";
											if(isset($role_type) && $role_type =="Care Managers" ) {  
												$showstopbtn = "none";
											}
										?>
            							<input type="hidden" name="module_id" value="{{ $module_id }}" />
            							<input type="hidden" name="submodule_id" value="{{ $submodule_id }}" />
							            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
										<input type="hidden" name="organization" value="" id="organization"/>
		    							<input type="hidden" name="form_name" value="patient_registration_form">
		    							<input type="hidden" name="stage_id" value="{{$stage_id}}">
		    							<input type="hidden" name="step_id" value="0">
										<input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" >
										@hidden("timer_start",["id"=>"timer_start"])
										@hidden("timer_end",["id"=>"timer_end"]) 
										@hidden("page_landing_time",["id"=>"page_landing_time"])
										<input type="hidden" id="page_landing_times" name="page_landing_times" value=''>
                                        @hidden("patient_time",["id"=>"patient_time"])
                                        @hidden("pause_time",["id"=>"pause_time", "value"=>"0"])
                                        @hidden("play_time",["id"=>"play_time", "value"=>"0"])
                                        @hidden("pauseplaydiff",["id"=>"pauseplaydiff", "value"=>"0"])
										<div class="stopwatch" id="stopwatch">
											<i class="text-muted i-Timer1"></i> :
											<div id="time-container" class="container" data-toggle="tooltip" data-placement="right" title="Current Running Time" data-original-title="Current Running Time"  style="display:none!important"></div>
											<label for="Current Running Time" data-toggle="tooltip" title="Current Running Time" data-original-title="Current Running Time">
                                            <span id="time-containers"></span></label>
											<a class="button" id="start" data-toggle="tooltip" data-placement="right" title="Start Timer" data-original-title="Start Timer"><img src="{{asset('assets/images/play.png')}}" style=" width: 28px;" /></a>
											<a class="button" id="pause" data-toggle="tooltip" data-placement="right" title="Pause Timer" data-original-title="Pause Timer" ><img src="{{asset('assets/images/pause.png')}}" style=" width: 28px;" /></a>
											<a class="button" id="stop" data-toggle="tooltip" data-placement="right" title="Stop Timer" data-original-title="Stop Timer" style="display:<?php echo $showstopbtn; ?>"><img src="{{asset('assets/images/stop.png')}}" style=" width: 28px;" /></a>
                                            <button class="button" id="reset" data-toggle="tooltip" data-placement="top" title="Reset Timer" data-original-title="Reset Timer" style="display:none;">Reset</button>
                                            <button class="button" id="resetTickingTime" data-toggle="tooltip" data-placement="top" title="resetTickingTime Timer" data-original-title="resetTickingTime Timer" style="display:none;">resetTickingTime</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body" id="hobby">
							<!-- <form action="{{ route('ajax.patient.registration')}}" method="post" enctype="multipart/form-data" name ="patient_registration_form"  id="patient_registration_form"> -->
							<!-- <form action="" method="post" autocomplete="off" name="patient_registration_form" id="patient_registration_form"> -->
								{{-- @csrf --}}
								<div class="alert alert-success" id="success-alert" style="display: none;">
									<button type="button" class="close" data-dismiss="alert">x</button>
									<strong> Patient Registered successfully! </strong><span id="text"></span>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label>Select Practice<span class="error">*</span></label>
											<!-- selectpractices("practice_id", ["id" => "practices"])  -->
											 @selectpracticespcp("practice_id",["id"=>"practices"])
										</div>
									</div>
									<div class="col-6" id="choose_provider">
										<div class="form-group">
											<label>Select Primary Care Provider (PCP)<span class="error">*</span></label>
											<!-- <label>Select Physician</label> -->
										    @select("Primary Care Provider (PCP)", "provider_id", [], ["id" => "physician"]) 
											{{-- @selectpcpprovider("provider_id", ["id" => "physician"]) --}}
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
											@text("lname", ["id" => "lname", "class" => "capitalize"]) 
											@hidden("uid", ["id" => "uid"])
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label for="fname">First Name<span class="error">*</span></label>
											<?php //isset($patient[0]->fname) && ($patient[0]->fname != '') ? $fname = $patient[0]->fname : $fname =''?>
											@text("fname", ["id" => "fname", "class" => "capitalize"])
											
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label for="mname">Middle Name</label>
											<?php //isset($patient[0]->mname) && ($patient[0]->mname != '') ? $mname = $patient[0]->mname : $mname =''?>
											@text("mname", ["id" => "mname", "class" => "capitalize"])
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-4 col-md-2">
										<div class="form-group">
											<label>Gender<span class="error">*</span></label>
											@select("Gender", "gender", [
											0 => "Male",
											1 => "Female"
											])
										</div>
									</div>
									<div class="col-md-2 form-group">
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
									<div class="col-8 col-md-4">
										<div class="form-group">
											<label>Date of Birth<span class="error">*</span></label>
											@date("dob")
										</div>
									</div>
									<div class="col-4 col-md-2 form-group">
										<label>Age</label>
										<input type="number" class="form-control" id="age" name="age" readonly>
									</div>
									<div class="col-4 col-md-2 form-group">
										<label>Fin Number</label>
										@text("fin_number", ["id" => "fin_number" ])
										
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label for="add_1">Address Line 1<span class="error">*</span></label>
											@text("add_1", ["id" => "addr1"])
										</div>
									</div>
								<!-- </div>
								<div class="row"> -->
									<div class="col-6">
										<div class="form-group">
											<label for="add_2">Address Line 2</label>
											@text("add_2", ["id" => "addr2"])
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
											<label for="zipcode">Zip Code<span class="error">*</span></label>
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
										@text("occupation_description",["id"=>"occupation_description"])
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
				foreach($questionnaire as $value){ ?>
					
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
											echo '<option value="'.$labels.'" >'.$labels.'</option>';
										}
										echo '</select><div class="invalid-feedback"></div>';
										
									}
									elseif($value->answerFormat == '2') {
										echo '<input type="text" name="question[question]['.$value->questionTitle.']" class="form-control col-md-8"  ><div class="invalid-feedback"></div>';
									}
									elseif(property_exists($value, 'label') && $value->answerFormat == '3') { 
										echo '<div class="checkRadio forms-element">';
										foreach($value->label as $labels){
											echo '<label class="radio radio-primary col-md-4 float-left" for="'.$value->questionTitle.'_'.$labels.'">
																		<input type="radio" name="question[question]['.$value->questionTitle.']" value="'.$labels.'" formControlName="radio" id="'.$value->questionTitle.'_'.$labels.'"  >
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
											echo '<label class="checkbox checkbox-primary col-md-4 float-left" for="'.$value->questionTitle.'_'.$labelArray.'">
																		<input class="form-check-input" value="'.$labels.'" type="checkbox" name="question[question]['.$value->questionTitle.']['.$labelArray.']" id="'.$value->questionTitle.'_'.$labelArray.'"  >
																		<span>'.$labels.'</span>
																		<span class="checkmark"></span>
																	</label>';
										}
										echo '</div><div class="invalid-feedback"></div>';
									} elseif($value->answerFormat == '5') {
										echo '<textarea class="form-control col-md-8" name="question[question]['.$value->questionTitle.']" ></textarea><div class="invalid-feedback"></div>';
									}
									
								?>

							</div>
							<p class="message" style="color:red"></p>
						</div>
						<div id="question"></div>
						<br>
						<hr>
				<?php $number++; 
				
			} 
		}
			?>
				</div>
				
			</div>
		</div>
	</div> 


								<div class="row">
									<div class="col-md-4 form-group">
										<label for="email">Email<span class="error">*</span></label>
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
										<div class="col-md-3 form-group">
											<label>Primary No. Country Code</label>
											@selectcountrycode("country_code", ["id" => "country_code"]) 
										</div>
										<div class="col-md-3 form-group">
											<label for="mob">Primary Phone Number<span class="error">*</span></label>
											<div class="input-group form-group">
												<div class="input-group-prepend btn-group btn-group-toggle" >
													<label class="btn btn-outline-primary" for="phone-primary-preferred">
													Preferred
													@input("radio", "preferred_contact", ["id" => "phone-primary-preferred", "value" => "0", "data-feedback" => "contact-preferred-feedback"])
													</label>
												</div>
												<!-- @phone("phone_primary") -->
												@phone("mob", ["id"=> "mob"])
											</div> 
										</div>
										<div class="col-md-1 form-group iscellmargin">
											<yes-no name="primary_cell_phone" id="primary_cell_phone" label-no="No" label-yes="Yes"><label style="margin-bottom:9px">Is Cell Phone</label></yes-no>
										</div>
										<div class="col-md-2 form-group iscellmargin" id="content_text" style="display:none">
											<yes-no id="consent_to_text" name="consent_to_text" label-no="No" label-yes="Yes"><label style="margin-bottom:9px">Consent To Text</label></yes-no>
										</div>
									</div>	
									<div class="row">
										<div class="col-md-3 form-group">
											<label>Secondary No. Country Code</label>
											@selectcountrycode("secondary_country_code", ["id" => "secondary_country_code"]) 
										</div> 
										<div class="col-md-3 form-group">
											<label for="home_number">Secondary Phone Number</label>
											<div class="input-group form-group">
												<div class="input-group-prepend btn-group btn-group-toggle" >
													<label class="btn btn-outline-primary" for="phone-secondary-preferred">
													Preferred
													@input("radio", "preferred_contact", ["id" => "phone-secondary-preferred", "value" => "1", "data-feedback" => "contact-preferred-feedback"])
													</label>
												</div>
												<!-- @phone("phone_secondary") -->
												@phone("home_number", ["id"=> "home_number"])
											</div>
										</div>
										<div class="col-md-1 form-group iscellmargin" id="scphn" class="display:none">
										<yes-no name="secondary_cell_phone" id="secondary_cell_phone" label-no="No" label-yes="Yes"><label style="margin-bottom:9px">Is Cell Phone</label></yes-no>
											
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12 form-group">
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
										@text("ins_provider[1]")
										<!-- @text("insurance_primary") -->
									</div>
									<div class="col-6 div form-group">
										<label for="insurance_primary_idnum">ID#</label>
										@text("ins_id[1]")
										<!-- @text("insurance_primary_idnum") -->
										@hidden("ins_type[1]", ["value"=>"primary"])
										<input type="hidden" name="insurance_primary_idnum_check" id="insurance_primary_idnum_check" value="0">
									</div>
								</div>
								<div class="row">
									<div class="col-6 div form-group">
										<label for="insurance_secondary">Secondary Insurance</label>
										@text("ins_provider[2]")
										<!-- @text("insurance_secondary") -->
									</div>
									<div class="col-6 div form-group">
										<label for="insurance_secondary_idnum">ID#</label>
										@text("ins_id[2]")
										@hidden("ins_type[2]", ["value"=>"secondary"])
										<!-- @text("insurance_secondary_idnum") -->
										<input type="hidden" name="insurance_secondary_idnum_check" id="insurance_secondary_idnum_check" value="0">
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
									<!-- <div class="col-md-3 form-group">
										<label>Age</label>
										@text("poa_age", ["id" => "poa_age", "disabled"])
									</div> -->
									<!-- <div class="col-md-3 form-group">
										<label>Mobile Number</label>
										@phone("poa_mobile", ["id" => "poa_phone", "disabled"])
									</div> -->
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
								<div class="col-md-6 form-group mb-3">  
									<label for="entrollment_form">Enrollment From <span class="error">*</span></label> 
									@select("Select Enrollment From", "entrollment_from", [
											0 => "Clinic Care Manage",
											1 => "Remote Care Manager By Phone"
										])                          
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
								</div>
								<?php
								//if(isset($services)){
									//$i = 0;
									//foreach($services as $service){?>			
										<?php //echo $service[0]['module']; 
											// $enrollin = "Enroll in ". $service['module'];
											//$moduleid = $service['id'];
										?>							
											<div class="col-md-3 form-group"> 
											{{--@checkbox($enrollin, "enroll[".$moduleid."]", "enroll_".$i , $moduleid , ["id"=> "enroll_".$i])--}}
											</div>										
										<?php
										//$i++;
									//}									
								//}
								?>
								</div>
								<hr> -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="file"><span class="error"></span>Upload Patient's Image</label>
											@file("file", ["id" => "file", "class" => "form-control",'onchange'=>"uploadfile()"])
											<input type="hidden" name="image_path" id="image_path">
											@if ($errors->any())
												<div class="alert alert-danger">
													<ul>
														@foreach ($errors->all() as $error)
															<li>{{ $error }}</li>
														@endforeach
													</ul>
												</div>
											@endif
										</div>
									</div>
									
								</div>
								
								
								<!-- <div class="row">
									<div class="col-md-12 form-group text-right">
									{{-- @checkbox("Enroll in CCM", "enroll_ccm", "enroll_ccm", "1") --}}
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 form-group text-right"> 
									{{-- @checkbox("Enroll in AWV", "enroll_awv", "enroll_awv", "2") --}}
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 form-group text-right">
									{{-- @checkbox("Enroll in RPM", "enroll_rpm", "enroll_rpm", "3") --}}
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 form-group text-right">
									{{-- @checkbox("Enroll in TCM", "enroll_tcm", "enroll_tcm", "4") --}}
									</div> 
								</div> -->
								<div class="row">
									<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
									<div class="col-12 text-right form-group mb-4"><button type="submit" class="btn btn-primary" id="submit">Submit</button></div>
								</div>
								<div id="error_msg1"></div>
							<!-- </form> -->
						</div>	
					</div>	
				</form>
			</div>	
		</div>	
	</div>	
	<!-- confirmation Model -->
	<div class="modal fade" id="confirmdialog" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<h5 class="modal-title" id="exampleModalLabel">Do you want to enroll this patient? If yes, select the service</h5> 
	        </div>
	      <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
	      	<div class="modal-body"> 
		        <input class="confirmation" id="confirmCcm" type="button" value="CCM" />
		      	<input class="confirmation" id="confirmRpm" type="button" value="RPM" />
		      	<input class="confirmation" id="confirmNo"  type="button" value="No" />
		      	<input type="hidden" id ="patientId" value="">
		      	<input type="hidden" id ="getModuleIdFromDB" value="">
	      	</div>
	    </div>
	  </div>
	</div>
</div>
<!-- </div> -->
@endsection
@section('page-js')
<script src="{{asset(mix('assets/js/laravel/patientRegistration.js'))}}"></script>
	<script type="text/javascript">
		var time = "00:00:00";
        var splitTime = time.split(":");
        var H = splitTime[0];
        var M = splitTime[1];
        var S = splitTime[2];
        $("#timer_start").val(time);
		$("#patient_time").val(time);
		
		$(document).ready(function() {
			// $('#confirmdialog').modal('show');
			patientRegistration.init();
			$("#file").removeClass("form-control");
			util.getToDoListData(0, {{getPageModuleName()}});
			$("#start").hide();
            $("#pause").show();
			$("#time-container").val(AppStopwatch.startClock);
			$('#country_code option:contains(United States (US) +1)').attr('selected', 'selected');
			$('#secondary_country_code option:contains(United States (US) +1)').attr('selected', 'selected');

			$('#military').change(function(){
				if(this.value == '0'){
					$('#veteran-question').show();
				}else{
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
					 console.log(data + "testingggg");    
					$('#image_path').val(data);
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}


		
        
		
	</script>
	<script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection