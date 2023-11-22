<div id='success'></div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card grey-bg">
            <div class="card-body">             
                <div class="form-row">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$patient[0]->id}}">
                    <input type="hidden" name="page_module_id" id="page_module_id" value="{{ getPageModuleName() }}">
                    <!-- <input type="hidden" name="practice_id" id="practice_id" value="{{-- $patient[0]->practice_id --}}"> -->
                    <div class="col-md-1">
                    <?php
                    $m_id = getPageModuleName();
                    $c_id = getPageSubModuleName();
                    //echo $m_id .''.$c_id;
                        // use Illuminate\Http\File;
                        $default_img_path = asset('assets/images/faces/avatar.png');
                        $path             = $patient[0]->profile_img;
                    ?>
                        @if(isset($patient[0]->profile_img) && ($patient[0]->profile_img != ""))
                            {{-- @if(file_exists($path))) --}}
                                <img src="{{ $path }}" class='user-image' style="width: 60px;" />
                                {{-- @else --}}
                                <!-- <img src="{{-- $default_img_path --}}" class='user-image' style="width: 60px;" /> -->
                                {{-- @endif --}}
                        @else
                            <img src="{{ $default_img_path }}" class='user-image' style="width: 60px;" />
                        @endif

                        {{-- @if($patient[0]->gender =='1') --}} 
                            <!-- <img src="{{asset('assets/images/faces/oldimages.jpg')}}" class='user-image' style="width: 60px;" /> -->
                        {{-- @else --}}
                            <!-- <img src="{{asset('assets/images/faces/oldmen.jpg')}}" class='user-image' style=" width: 60px;" /> -->
                        {{-- @endif --}}
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
                                </label> / 
                                <label data-toggle="tooltip" title="DOB" data-original-title="Patient DOB" for="dob">{{ empty($patient[0]->dob) ? '' : date("m-d-Y", strtotime($patient[0]->dob)) }}</label>                      
                            </div>
                            <div class="col-md-3 right-divider">
                                <label data-toggle="tooltip" data-placement="right" title="Contact Number" data-original-title="Patient Phone No." for="Phone No"><i class="text-muted i-Old-Telephone"></i> : <b>{{$patient[0]->mob}}<?php if(isset($patient[0]->home_number) && $patient[0]->home_number!=''){echo ' | '.$patient[0]->home_number; }?></b></label><br>
                                <label data-toggle="tooltip" data-placement="right" title="Email" data-original-title="Patient Email" for="Email"><i class="text-muted i-Email"></i> : {{$patient[0]->email}} </label><br/>
                                <label data-toggle="tooltip" data-placement="right" title="Address" data-original-title="Patient Address" for="Address"><i class="text-muted i-Post-Sign"></i> : {{ empty($PatientAddress->add_1) ? '' : $PatientAddress->add_1}}{{ empty($PatientAddress->add_2) ? '' : ', '.$PatientAddress->add_2}}</label>
                            </div>
                            <div class="col-md-2 right-divider">
                                <label for="EMR" data-toggle="tooltip" data-placement="right" title="EMR" data-original-title="Patient EMR">
                                    <i class="text-muted i-ID-Card"></i> : {{$patient[0]->emr}} 
                                    <?php
                                        if(isset($patient_providers[0])){
                                            echo $patient_providers[0]->practice_emr;
                                            // $emr = ""; 
                                            // foreach($patient_providers[0] as $provider){
                                            //     echo $provider;
                                            //     if($provider['practice_emr'] == ""){
                                            //         $emr = $emr . $provider['practice_emr'].", ";                                                    
                                            //     }
                                            // }
                                            // $emr=rtrim($emr, ', ');
                                            // echo $emr;       
                                        }
                                    ?>
                                </label><br>
                                <label for="Enrolled Date" data-toggle="tooltip" data-placement="right" title="Enrolled Date" data-original-title="Enrolled Date"><i class="text-muted i-Over-Time"></i> : {{ empty($patient[0]->created_date) ? '' : $patient[0]->created_date }} 
                                    <?php
                                //  print_r($patient_enroll_date);
                                    $enroll_date="";
                                        $module_id = getPageModuleName();                                    
                                        if(isset($patient_enroll_date[0]->date_enrolled)){
                                           $str = $patient_enroll_date[0]->date_enrolled; 
                                            $enroll_date = date("m-d-Y", strtotime($str)); 
                                            echo $enroll_date;   
                                        }

                                    ?>
                                  
                                </label><br>
                                <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#personal-notes" style="background-color:#27a7de;border:none;" id="personal_notes">Personal Notes</a>
                            </div> 
                            <div class="col-md-2 right-divider">
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
                                            $enrollin=rtrim($enrollin, ', ');
                                            echo $enrollin;       
                                        }
                                    ?>
                                </label><br>  
                                <label for="Enrollment Status" data-toggle="tooltip" data-placement="right" title="Enrollment Status" data-original-title="Patient Enrollment Status"><i class="text-muted i-Search-People"></i> 
                                    {{-- Enrollment Status --}}: 
                                    <?php 
                                        if(isset($patient_enroll_date[0]->date_enrolled)){
                                            $date = $patient_enroll_date[0]->date_enrolled;
                                            if(date("m", strtotime($date)) == date("m")) {
                                                echo "New";
                                            } else {
                                                echo "Existing";
                                            }
                                        }
                                    ?>
                                </label><br>
                                <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#part-of-research-study" style="background-color:#27a7de;border:none;" id="part_of_research_study">Part of Research Study</a>                
                            </div>
                            <div class="row col-md-3">
                                <div class="col-md-11 careplan">
                                    <label for="total time" data-toggle="tooltip" data-placement="right" title="Total Time Elapsed" data-original-title="Total Time Elapsed"><i class="text-muted i-Clock-4"></i> {{-- Total Time Elapsed --}}: <span class="last_time_spend"><?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?></span></label><br>     
                                    <div class="demo-div">
                                        @hidden("timer_start",["id"=>"timer_start"])
                                        @hidden("timer_end",["id"=>"timer_end"])
                                        <div class="stopwatch" id="stopwatch">
                                            <i class="text-muted i-Timer1"></i> :
                                            <div id="time-container" class="container" data-toggle="tooltip" data-placement="right" title="Current Running Time" data-original-title="Current Running Time"></div>
                                            <button class="button" id="start" data-toggle="tooltip" data-placement="right" title="Start Timer" data-original-title="Start Timer"><img src="{{asset('assets/images/play.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="pause" data-toggle="tooltip" data-placement="right" title="Pause Timer" data-original-title="Pause Timer"><img src="{{asset('assets/images/pause.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="stop" data-toggle="tooltip" data-placement="right" title="Stop Timer" data-original-title="Stop Timer"><img src="{{asset('assets/images/stop.png')}}" style=" width: 28px;" /></button>
                                            <button class="button" id="reset" data-toggle="tooltip" data-placement="right" title="Reset Timer" data-original-title="Reset Timer" style="display:none;">Reset</button>
                                            <a href="javascript:void(0)" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient[0]->id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient[0]->UID}});" id="log_time" data-toggle="tooltip" data-placement="right" title="Log Time" data-original-title="Log Time" style="padding: 0px 8px;"><img src="{{asset('assets/images/log_time.png')}}" style=" width: 27px;" /></a>
                                            <!-- <button class="button" id="log_time" data-toggle="tooltip" data-placement="right" title="Log Time" data-original-title="Log Time" onclick="util.logTimeManually($('#timer_start').val(), $('#time-container').text(), {{$patient[0]->id}}, {{ getPageModuleName() }}, {{ getPageSubModuleName() }}, 0, 1, {{$patient[0]->UID}});"><i class="add-icons i-Time-Backup"></i></button> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="button" id="start"><a href="/patients/registerd-patient-edit/{{$patient[0]->id}}/{{$m_id}}/{{$c_id}}" title="Edit Patient Info" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a></button>                                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="personal-notes" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Personal Notes</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route("patient.personalnotes")}}" method="post" name ="personal_notes_form"  id="personal_notes_form">
                @csrf
                    <?php
                        $module_id    = getPageModuleName();
                        $submodule_id = getPageSubModuleName();
                    ?>
                    <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
                    <input type="hidden" name="uid" value="{{$patient[0]->id}}">
                    <input type="hidden" name="start_time" value="00:00:00">
                    <input type="hidden" name="end_time" value="00:00:00"> 
                    <input type="hidden" name="module_id" value="{{ $module_id }}" />
                    <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Personal Notes<span class='error'>*</span></label>
                               <textarea name ="personal_notes" class="form-control forms-element"><?php if(isset($personal_notes[0]->personal_notes)){echo $personal_notes[0]->personal_notes;}?></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>                              
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                        <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="part-of-research-study" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Part of Research Study</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route("patient.researchstudy")}}" method="post" name ="part_of_research_study_form"  id="part_of_research_study_form">
                @csrf
                    <?php
                        $module_id    = getPageModuleName();
                        $submodule_id = getPageSubModuleName();
                    ?>
                    <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
                    <input type="hidden" name="uid" value="{{$patient[0]->id}}">
                    <input type="hidden" name="start_time" value="00:00:00">
                    <input type="hidden" name="end_time" value="00:00:00"> 
                    <input type="hidden" name="module_id" value="{{ $module_id }}" />
                    <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Part of Research Study<span class='error'>*</span></label>
                               <textarea name ="part_of_research_study" class="form-control forms-element"><?php if(isset($research_study[0]->part_of_research_study)){echo $research_study[0]->part_of_research_study;}?></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>                              
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                        <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
