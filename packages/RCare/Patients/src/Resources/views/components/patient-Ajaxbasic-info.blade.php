<div id='success'></div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card grey-bg">
            <div class="card-body">             
                <div class="form-row" id="patient-Ajaxdetails">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$patient_id}}">
                    <input type="hidden" name="page_module_id" id="page_module_id" value="{{ getPageModuleName() }}">
                    <input type="hidden" name="page_component_id" id="page_component_id" value="{{ getPageSubModuleName() }}">         
                    <input type="hidden" name="service_status" id="service_status" value="{{isset($patient_enroll_date[0]->status)?$patient_enroll_date[0]->status:''}}">                    
                    <div class="col-md-1 patient_img" id="patient_img">  
                        <?php
                        $m_id           = getPageModuleName();
                        $c_id           = getPageSubModuleName();
                        $enroll_service = (Request::segment(4)) ? Request::segment(4) : 0;
                        $default_img_path = asset('assets/images/faces/avatar.png');
                        $path             = $patient[0]->profile_img;
						$role_type = session()->get('role_type');
						$showstopbtn = "inline-block";
						if(isset($role_type) && $role_type =="Care Managers" ) {  
						$showstopbtn = "none";
						}
                        ?>
                        {{--@if(isset($patient[0]->profile_img) && ($patient[0]->profile_img != ""))
                            <img src="{{ $path }}" class='user-image' style="width: 60px;" />
                        @else
                            <img src="{{ $default_img_path }}" class='user-image' style="width: 60px;" />
                        @endif--}}

                    </div>
                    <div class="col-md-11">
                        <div class="form-row">
                            <div class="col-md-2 right-divider">
                                <label data-toggle="tooltip" data-toggle="tooltip" id="F_L_name" data-placement="top" title="Name" class="patient_name"></label><br/>
                                <label data-toggle="tooltip" title="Gender(DOB)" id="gender" data-original-title=" Gender" for="dob" class="patient_gender"></label>
                                <label data-toggle="tooltip" title="DOB" data-original-title="Patient DOB" for="dob" id="dob" class="patient_dob"></label><br/>
                                <label data-toggle="tooltip" id="basix-info-fin_number" title="FIN Number" data-original-title="Patient FIN Number" for="FIN Number" style="padding-right:2px;">
                                    <i class="text-muted i-ID-Card"></i> : 
                                    <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm patient_finnumber" data-toggle="modal" data-target="#patient-finnumber" style="background-color:#27a7de;border:none;" id="patient_finnumber">
                                        <span id ="fin_number" class="patient_fin_number" ></span>
                                    </a>
                                </label><br/>
                                <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm patient_vateran_service" data-toggle="modal" data-target="#vateran-service" style="background-color:#27a7de;border:none;" id="vateran_service" onclick="getElementById('vateran_service_title').innerHTML=this.innerHTML;"></a>                                                   
                            </div>
                            <div class="col-md-3 right-divider">
                                <label data-toggle="tooltip" title="Contact Number" data-original-title="Patient Phone No." for="Phone No"><i class="text-muted i-Old-Telephone"></i> : <b id="contact_num" class="patient_contact_num"></b></label><br>
                                <label data-toggle="tooltip" id="basix-info-concent-text" title="Consent Text" data-original-title="Consent Text" for="Concent Text" style="padding-right:2px;"><i class="text-muted i-Speach-Bubble-Dialog"></i> : <span id ="concent_to_text" class="patient_concent_to_text"></span></label><br/>
                                <label data-toggle="tooltip" id="basix-info-address" title="Address" data-original-title="Patient Address" for="Address" style="padding-right:2px;"><i class="text-muted i-Post-Sign"></i> : <span id ="address" class="patient_address"></span></label><br> 
                                <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#patient-threshold" style="background-color:#27a7de;border:none;" id="patient_threshold">Alert Thresholds</a> 
                            </div>
                            <div class="col-md-2 right-divider">  
                                <label for="Practice" data-toggle="tooltip" data-placement="top" title="Practice" data-original-title="Patient Practice">
                                    <i class="text-muted i-Hospital"></i> : <span id="practice" class="patient_practice"></span>
                                </label><br>
                                <label for="Provider" data-toggle="tooltip" data-placement="top" title="Provider" data-original-title="Patient Provider">
                                    <i class="text-muted i-Doctor"></i> : <span id="provider" class="patient_provider"></span>
                                </label><br>
                                <label for="EMR" data-toggle="tooltip" data-placement="top" title="EMR" data-original-title="Patient EMRrrr">
                                    <i class="text-muted i-ID-Card"></i>: <span id="practice_emr" class="patient_practice_emr"></span>
                                </label>
                                <br><label for="CM" data-toggle="tooltip" data-placement="top" title="Assign CM" data-original-title="Assign CM">
                                    <i class="text-muted i-Talk-Man"></i> : <span id="assignCM" class="patient_assign_cm"></span>
                                </label>
                               <!--  <br><a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add-patient-devices" style="background-color:#27a7de;border:none;" id="add_patient_devices">Devices</a>  -->
                            </div>
                            <div class="col-md-2 right-divider">
                                <i class="text-muted i-Search-People" ></i>
                                <label for="Enrollment Status" data-toggle="tooltip" title="Enrollment Status" data-original-title="Patient Enrollment Status" id="pservice_status" class="patient_service_status"> </label>                                
                                <span id ="serviceActiveId" class="patient_service_active_id"></span>
                                <label id="statusDate" class="patient_status_date"></label>
                                <br/> <!--data-target="#Extend-deactive"  id="active_extend_deactive" -->
                                <label for="programs"  data-toggle="tooltip" title="Enrolled Services" data-original-title="Patient Enrolled Services">
                                    <i class="text-muted i-Library"></i>: <span id="enroll_services" class="patient_enroll_services"></span> 
                                </label>
                                <a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px; display:none" class="adddeviceClass patient_add_device" data-target="#add-device"  id="deviceadd" > 
                                    <i class="plus-icons i-Add" id="adddevice" style="font-size: 15px;" data-toggle="tooltip" data-placement="top" data-original-title="Additional Device"></i>
                                </a>
                               <!-- ash -->
                                <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm add_patient_devices" data-toggle="modal" data-target="#add-patient-devices" style="background-color:#27a7de;border:none;display: none;" id="add_patient_devices">Devices</a>  
                                <!-- !ash -->
                                <br/>
                                <div id="newenrolldate">      
                                    <label for="Enrolled Date" data-toggle="tooltip" title="Enrolled Date" data-original-title="Enrolled Date">
                                        <i class="text-muted i-Over-Time"></i> : <span id="enroll_date" class="patient_enroll_date"></span>  
                                    </label>
                                </div>
                                <label data-toggle="tooltip" title="Device Code" data-original-title="Patient Device Code." for="Device Code">
                                    <i class="text-muted i-Hospital"></i> : <span id="device_code" class="patient_device_code"></span>
                                </label>   
                            </div>   
                            <div class="row col-md-3">
                                <div class="col-md-11 careplan">
                                    <label for="total time" data-toggle="tooltip" title="Billable Time" data-original-title="Billable Time"><i class="text-muted i-Clock-4"></i> {{-- Total Time Elapsed --}}:
                                    <span class="last_time_spend" id="btime"></span></label>
                                    <label for="total time" data-toggle="tooltip" title="Non Billable Time" data-original-title="Non Billable Time">
                                     / <span class="non_billabel_last_time_spend" id="nbtime"></span></label>
                                    <button class="button" style="border: 0px none;background: #f7f7f7;outline: none;"><a href="/patients/registerd-patient-edit/{{$patient_id}}/{{$m_id}}/{{$c_id}}/{{$enroll_service}}" title="Edit Patient Info" data-toggle="tooltip" data-placement="top"  data-original-title="Edit Patient Info" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a></button>
                                    <div class="demo-div" style="margin-bottom:5px;">
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
                                            <div id="time-container" class="container" data-toggle="tooltip" title="Current Running Time" data-original-title="Current Running Time" style="display:none!important"></div>
                                            <label for="Current Running Time" data-toggle="tooltip" title="Current Running Time" data-original-title="Current Running Time">
                                            <span id="time-containers"></span></label>
                                            <button class="button" id="start" data-toggle="tooltip" data-placement="top" title="Start Timer" data-original-title="Start Timer" onclick="util.logPauseTime($('.form_start_time').val(), {{$patient_id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient_id}}, 0, 'log_time_<?php $uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');" ><img src="{{asset('assets/images/play.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="pause" data-toggle="tooltip" data-placement="top" title="Pause Timer" data-original-title="Pause Timer" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient_id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0,  $('input[name=billable]').val(), {{$patient_id}}, 0, 'log_time_<?php $uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');" ><img src="{{asset('assets/images/pause.png')}}" style=" width: 28px;" /></button>
											
                                            <button class="button" id="stop" data-toggle="tooltip" data-placement="top" title="Stop Timer" data-original-title="Stop Timer"  onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient_id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient_id}}, 0, 'log_time_<?php $uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');" style=" display: <?php echo $showstopbtn; ?>"><img src="{{asset('assets/images/stop.png')}}" style=" width: 28px; " /></button>
											
                                            <button class="button" id="reset" data-toggle="tooltip" data-placement="top" title="Reset Timer" data-original-title="Reset Timer" style="display:none;">Reset</button>
                                             <button class="button" id="resetTickingTime" data-toggle="tooltip" data-placement="top" title="resetTickingTime Timer" data-original-title="resetTickingTime Timer" style="display:none;">resetTickingTime</button>
                                            <!--<a href="javascript:void(0)" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient_id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient_id}}, 0, 'log_time_<?php //$uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');" id="log_time" data-toggle="tooltip" title="Log Time" data-original-title="Log Time" style="padding: 0px 8px;"><img src="{{asset('assets/images/log_time.png')}}" style=" width: 27px;" /></a>
                                            <button class="button" id="log_time" data-toggle="tooltip" title="Log Time" data-original-title="Log Time" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient_id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient_id}});"><i class="add-icons i-Time-Backup"></i></button> -->
                                        </div>
                                    </div>       
                                        <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#personal-notes" style="background-color:#27a7de;border:none;" id="personal_notes">Personal Notes</a> | 
                                        <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#part-of-research-study" style="background-color:#27a7de;border:none;" id="part_of_research_study">Research Study</a>  
                                </div>
                            </div>
                            <div style="padding-left: 823px;">  
                            </div> 
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>


        