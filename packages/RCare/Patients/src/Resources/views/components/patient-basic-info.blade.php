<div id='success'></div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card grey-bg">
            <div class="card-body">             
                <div class="form-row">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$patient[0]->id}}">
                    <input type="hidden" name="page_module_id" id="page_module_id" value="{{ getPageModuleName() }}">
                    <input type="hidden" name="page_component_id" id="page_component_id" value="{{ getPageSubModuleName() }}">         
                    <input type="hidden" name="service_status" id="service_status" value="{{isset($patient_enroll_date[0]->status)?$patient_enroll_date[0]->status:''}}">                    
                    <div class="col-md-1"> 
                        <?php
                        $m_id           = getPageModuleName();
                        $c_id           = getPageSubModuleName();
                        $enroll_service = (Request::segment(4)) ? Request::segment(4) : 0;
                        $default_img_path = asset('assets/images/faces/avatar.png');
                        $path             = $patient[0]->profile_img;
                        ?>
                        @if(isset($patient[0]->profile_img) && ($patient[0]->profile_img != ""))
                            <img src="{{ $path }}" class='user-image' style="width: 60px;" />
                        @else
                            <img src="{{ $default_img_path }}" class='user-image' style="width: 60px;" />
                        @endif
                    </div>
                    <div class="col-md-11">
                        <div class="form-row">
                            <div class="col-md-2 right-divider">
                                <label data-toggle="tooltip" data-toggle="tooltip" data-placement="top" title="Name">{{$patient[0]->fname}} {{$patient[0]->lname}}</label><br/>
                                <label data-toggle="tooltip" title="Gender (DOB)" data-original-title="Patient DOB" for="dob">
                                    <?php
                                        if(isset($patient_demographics)){
                                            if($patient_demographics->gender == '1') {
                                                echo 'Female / ';
                                            } else if($patient_demographics->gender == '0') {
                                                echo 'Male / ';
                                            }else {
                                                echo '';
                                            }
                                        }
                                    ?>
                                   ({{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}})
                                </label>
                                <label data-toggle="tooltip" title="DOB" data-original-title="Patient DOB" for="dob">{{ empty($patient[0]->dob) ? '' : date("m-d-Y", strtotime($patient[0]->dob)) }}</label>    
                                <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#vateran-service" style="background-color:#27a7de;border:none;" id="vateran_service">Veteran Service - <?php if(isset($patient_demographics->military_status) && $patient_demographics->military_status == '0'){echo " Yes";}else if(isset($patient_demographics->military_status) && $patient_demographics->military_status == '1'){echo " No"; }else{echo ' Unknown'; } ?></a>                                                   
                            </div>
                            <div class="col-md-3 right-divider">
                                <label data-toggle="tooltip" data-placement="right" title="Contact Number" data-original-title="Patient Phone No." for="Phone No"><i class="text-muted i-Old-Telephone"></i> : <b>{{$patient[0]->mob}}<?php if(isset($patient[0]->home_number) && $patient[0]->home_number!=''){echo ' | '.$patient[0]->home_number; }?></b></label><br>
                                <label data-toggle="tooltip" data-placement="right" id="basix-info-address" title="Address" data-original-title="Patient Address" for="Address" style="padding-right:2px;"><i class="text-muted i-Post-Sign"></i> : {{ empty($PatientAddress->add_1) ? '' : $PatientAddress->add_1}}
                                    {{ empty($PatientAddress->add_2) ? '' : ', '.$PatientAddress->add_2}}{{ empty($PatientAddress->city) ? '' : ', '.ucwords($PatientAddress->city)}}
                                    @if(!empty($PatientAddress->state))
                                        @foreach(config("form.states") as $name => $label)
                                            @if($name == $PatientAddress->state)
                                                {{", ". $label}}
                                            @endif
                                        @endforeach 
                                    @endif
                                </label><br>
                                <?php
                                $m = getPageModuleName();
                                // echo $m;
                                ?>
                                @if($m==2) 
                                <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#patient-threshold" style="background-color:#27a7de;border:none;" id="patient_threshold">Alert Thresholds</a> 
                               @endif         
                            </div>
                            <div class="col-md-2 right-divider">  
                                <label for="Practice" data-toggle="tooltip" data-placement="top" title="Practice" data-original-title="Patient Practice">
                                    <i class="text-muted i-Hospital"></i> : 
                                    {{empty($patient_providers) ? '' : $patient_providers->practice['name']}}
                                </label><br>
                                <label for="Provider" data-toggle="tooltip" data-placement="top" title="Provider" data-original-title="Patient Provider">
                                    <i class="text-muted i-Doctor"></i> :
                                     {{empty($patient_providers) ? '' : $patient_providers->provider['name']}}
                                </label><br>
                                <label for="EMR" data-toggle="tooltip" data-placement="top" title="EMR" data-original-title="Patient EMRrrr">
                                    <i class="text-muted i-ID-Card"></i>
                                     : {{ empty($patient_providers->practice_emr) ? '' : $patient_providers->practice_emr }}
                                </label>
                                <br><label for="CM" data-toggle="tooltip" data-placement="top" title="Assign CM" data-original-title="Assign CM">
                                    <i class="text-muted i-Talk-Man"></i>
                                     : {{ assingCareManager($patient[0]->id) }}
                                </label>
                            </div>
                            <div class="col-md-2 right-divider">
                                <i class="text-muted i-Search-People" ></i>
                                <label for="Enrollment Status" data-toggle="tooltip" data-placement="right" title="Enrollment Status" data-original-title="Patient Enrollment Status" id="PatientStatus" value="<?php $status = isset($patient_enroll_date[0]->status)?$patient_enroll_date[0]->status:''; echo $status?>"> 
                                </label>    
                                    {{-- Enrollment Status --}}: 
                                    <?php 
                                      if(isset($patient_enroll_date[0]->date_enrolled) && $patient_enroll_date[0]->date_enrolled!=''){
                                            $todate='';
                                            if(isset($patient_enroll_date[0]->suspended_to) && $patient_enroll_date[0]->suspended_to!='')
                                            { 
                                                $vartodate=explode(" ", $patient_enroll_date[0]->suspended_to);
                                                $finaltodate=date("m-d-Y", strtotime($vartodate[0]));
                                                $todate="(".$finaltodate.")";
                                            }
                                            $fromdate =''; 
                                            if(isset($patient_enroll_date[0]->suspended_from) && $patient_enroll_date[0]->suspended_from!='')
                                            { 
                                                $varfromdate=explode(" ", $patient_enroll_date[0]->suspended_from);
                                                $finalfromdate=date("m-d-Y", strtotime($varfromdate[0]));
                                                $fromdate="(".$finalfromdate.")";
                                            }
                                        if($patient_enroll_date[0]->status=='0')
                                        { 
                                             echo "Suspended From ".'<br>'.$fromdate.'To'.$todate;
                                        }
                                        else if($patient_enroll_date[0]->status=='2')
                                        {
                                             echo "Deactivated From ".'<br>'.$fromdate; 
                                        } 
                                        else if($patient_enroll_date[0]->status=='3')
                                        {
                                            echo "Deceased";
                                        }else{  
                                            echo "Active"; 
                                        }
                                    }?>
                                
                                <?php if(isset($patient_enroll_date[0]->status)){ 
                                    if($patient_enroll_date[0]->status=='1'){ ?>
                                    <a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass"
                                    data-target="#active-deactive"  id="active" > <i class="i-Yess i-Yes" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i></a>
                                   <?php }?>
                                   <?php if($patient_enroll_date[0]->status=='0'){ ?>
                                   <a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass"
                                    data-target="#active-deactive"  id="suspend"> <i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i></a>
                                    <?php }?> 
                                    <?php if($patient_enroll_date[0]->status=='2'){ ?>
                                   <a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass"
                                    data-target="#active-deactive"  id="deactive"> <i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i></a>
                                   <?php }?>
                                   <?php if($patient_enroll_date[0]->status=='3'){?>
                                    <a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" 
                                    data-target="#active-deactive"  id="deceased" ><i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i></a>
                                    <?php } 
                                }?>
                                <br/> <!--data-target="#Extend-deactive"  id="active_extend_deactive" -->
                                <label for="programs"  data-toggle="tooltip" data-placement="right" title="Enrolled Services" data-original-title="Patient Enrolled Services"><i class="text-muted i-Library"></i> {{-- Programs --}}: 
                                    <?php 
                                        if(isset($patient[0]->PatientServices)){
                                            $enrollin = "";
                                            foreach($patient[0]->PatientServices as $patient_service){
                                                if(isset($services)){ 
                                                    foreach($services as $service){
                                                        if($service['id'] == $patient_service->module_id){
                                                            $enrollin = $enrollin . $service['module'].", ";                                                    
                                                        }
                                                    }
                                                } 
                                            } 
                                           // $enrollin=rtrim($enrollin, ', ');
                                           // echo $enrollin;
                                           echo $enrollin;       
                                        }
                                        if(isset($patient_assign_device)){
                                            echo  $patient_assign_device;
                                        }
                                      
                                      ?>
                                </label>
                                <a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px; font-size: 15px;" class="adddeviceClass"
                                data-target="#add-device"  id="deviceadd" > 
                                <i class="plus-icons i-Add" id="adddevice" data-toggle="tooltip" data-placement="top" data-original-title="Additional Device"></i></a>
                                <br/>
                                <div id="newenrolldate">      
                                    <label for="Enrolled Date" data-toggle="tooltip" data-placement="right" title="Enrolled Date" data-original-title="Enrolled Date"><i class="text-muted i-Over-Time"></i> : {{ empty($patient[0]->created_date) ? '' : $patient[0]->created_date }} 
                                        <?php
                                        //    dd($roleid);
                                            $enroll_date="";
                                            $module_id = getPageModuleName();                                    
                                            if(isset($patient_enroll_date[0]->date_enrolled)){
                                               $str = $patient_enroll_date[0]->date_enrolled; 
                                                // $enroll_date = date("m-d-Y", strtotime($str)); 
                                                $enroll_date = explode(" ",$str); 
                                                
                                                echo $enroll_date[0];        
                                            }
                                        ?>  
                                    </label>
                                </div>
                                <label data-toggle="tooltip" data-placement="right" title="Device Code" data-original-title="Patient Device Code." for="Device Code">
                                    <i class="text-muted i-Hospital"></i> :
                                    {{count($PatientDevices)> 0 ? $PatientDevices[0]->device_code : ''}}
                                </label>   
                                <input type="hidden" name="device_code" value="{{count($PatientDevices)> 0 && count($PatientDevices)== 7 ? $PatientDevices[0]->device_code : ''}}">
                            </div>   
                            <div class="row col-md-3">
                                <div class="col-md-11 careplan">
                                    <label for="total time" data-toggle="tooltip" data-placement="right" title="Billable Time" data-original-title="Billable Time"><i class="text-muted i-Clock-4"></i> {{-- Total Time Elapsed --}}: <span class="last_time_spend"><?php if(isset($billable_time)){echo $billable_time; }else{ echo '00:00:00';} ?> </span></label>
                                    <label for="total time" data-toggle="tooltip" data-placement="right" title="Non Billable Time" data-original-title="Non Billable Time"> / <span class="non_billabel_last_time_spend"> <?php if(isset($non_billabel_time)){ echo " ". $non_billabel_time;}else{ echo '00:00:00'; } ?></span></label>
                                    <button class="button" style="border: 0px none;background: #f7f7f7;outline: none;"><a href="/patients/registerd-patient-edit/{{$patient[0]->id}}/{{$m_id}}/{{$c_id}}/{{$enroll_service}}" title="Edit Patient Info" data-toggle="tooltip" data-placement="top"  data-original-title="Edit Patient Info" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a></button>
                                    <div class="demo-div">
                                        @hidden("timer_start",["id"=>"timer_start"])
                                        @hidden("timer_end",["id"=>"timer_end"])
                                        @hidden("page_landing_time",["id"=>"page_landing_time"])
                                        @hidden("patient_time",["id"=>"patient_time"])
                                        @hidden("pause_time",["id"=>"pause_time", "value"=>"0"])
                                        @hidden("play_time",["id"=>"play_time", "value"=>"0"])
                                        @hidden("pauseplaydiff",["id"=>"pauseplaydiff", "value"=>"0"])
                                        <div class="stopwatch" id="stopwatch">
                                            <i class="text-muted i-Timer1"></i> :
                                            <div id="time-container" class="container" data-toggle="tooltip" data-placement="right" title="Current Running Time" data-original-title="Current Running Time"></div>
                                            <button class="button" id="start" data-toggle="tooltip" data-placement="top" title="Start Timer" data-original-title="Start Timer"><img src="{{asset('assets/images/play.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="pause" data-toggle="tooltip" data-placement="top" title="Pause Timer" data-original-title="Pause Timer"><img src="{{asset('assets/images/pause.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="stop" data-toggle="tooltip" data-placement="top" title="Stop Timer" data-original-title="Stop Timer"><img src="{{asset('assets/images/stop.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="reset" data-toggle="tooltip" data-placement="top" title="Reset Timer" data-original-title="Reset Timer" style="display:none;">Reset</button>
                                            <button class="button" id="resetTickingTime" data-toggle="tooltip" data-placement="top" title="resetTickingTime Timer" data-original-title="resetTickingTime Timer" style="display:none;">resetTickingTime</button>
                                            <a href="javascript:void(0)" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient[0]->id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient[0]->id}}, 0, 'log_time_<?php $uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));echo $uriSegments[1].'_'.$uriSegments[2];?>');" id="log_time" data-toggle="tooltip" data-placement="right" title="Log Time" data-original-title="Log Time" style="padding: 0px 8px;"><img src="{{asset('assets/images/log_time.png')}}" style=" width: 27px;" /></a>
                                            <!-- <button class="button" id="log_time" data-toggle="tooltip" data-placement="right" title="Log Time" data-original-title="Log Time" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient[0]->id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient[0]->UID}});"><i class="add-icons i-Time-Backup"></i></button> -->
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


        