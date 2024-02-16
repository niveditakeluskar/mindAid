<div id='success'></div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card grey-bg">
            <div class="card-body">
                <div id="patient-Ajaxdetails-model">             
                    <div class="form-row"> 
                        <input type="hidden" name="hidden_id" id="hidden_id" value="{{$patient_id}}">
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
                                <div class="col-md-3 right-divider"> 
                                    <label data-toggle="tooltip" data-toggle="tooltip" id="model-F_L_name" class="model-F_L_name"  data-placement="top" title="Name"></label><br/>
                                    <label data-toggle="tooltip" title="Gender(DOB)" id="model-gender" class="model-gender" data-original-title=" Gender" for="dob"></label>
                                    <label data-toggle="tooltip" title="DOB" data-original-title="Patient DOB" for="dob" id="model-dob" class="model-dob"></label><br>
									<label data-toggle="tooltip" id="basix-info-fin_number" title="FIN Number" data-original-title="Patient FIN Number" for="FIN Number" style="padding-right:2px;">
                                    <i class="text-muted i-ID-Card"></i> : 
                                    <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm patient_finnumber" data-toggle="modal" data-target="#patient-finnumber" style="background-color:#27a7de;border:none;" id="patient_finnumber">
                                        <span id ="fin_number" class="patient_fin_number" ></span>
                                    </a>
									</label><br/>
								
                                    <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm model-vateran_service" data-toggle="modal" data-target="#vateran-service" style="background-color:#27a7de;border:none;" id="model-vateran_service" ></a>                                                   
                                
                                </div> 
                                <div class="col-md-3 right-divider"> 
                                    <label data-toggle="tooltip" data-placement="right" title="Contact Number" data-original-title="Patient Phone No." for="Phone No"><i class="text-muted i-Old-Telephone"></i> : <b id="model-contact_num" class="model-contact_num"></b></label><br>
                                    <label data-toggle="tooltip" data-placement="right" id="model-basix-info-address" class="model-basix-info-address" title="Address" data-original-title="Patient Address" for="Address" style="padding-right:2px;"><i class="text-muted i-Post-Sign"></i> : 
                                    <span id ="model-address" class ="model-address"></span>      
                                    </label><br> 
                                    <label data-toggle="tooltip" data-placement="right" id="basix-info-fin_number" class="basix-info-fin_number" title="Fin Number" data-original-title="Patient Fin Number" for="Fin Number" style="padding-right:2px;"><i class="text-muted i-ID-Card"></i> : 
                                <span id ="model-fin_number" class="model-fin_number"></span></label><br> 
                                    <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#patient-threshold" style="background-color:#27a7de;border:none;" id="model-patient_threshold" class="model-patient_threshold">Alert Thresholds</a> 
                                </div>
                                <div class="col-md-3 right-divider">  
                                    <label for="Practice" data-toggle="tooltip" data-placement="top" title="Practice" data-original-title="Patient Practice">
                                        <i class="text-muted i-Hospital"></i> : <span id="model-practice" class="model-practice"></span>
                                    </label><br>
                                    <label for="Provider" data-toggle="tooltip" data-placement="top" title="Provider" data-original-title="Patient Provider">
                                        <i class="text-muted i-Doctor"></i> :
                                         <span id="model-provider" class="model-provider"></span>
                                    </label><br>
                                    <label for="EMR" data-toggle="tooltip" data-placement="top" title="EMR" data-original-title="Patient EMRrrr">
                                        <i class="text-muted i-ID-Card"></i>: <span id="model-practice_emr" class="model-practice_emr"></span>
                                    </label>
                                    <br><label for="CM" data-toggle="tooltip" data-placement="top" title="Assign CM" data-original-title="Assign CM">
                                        <i class="text-muted i-Talk-Man"></i> :
                                        <span id="model-assignCM" class="model-assignCM"></span>
                                    </label>
                                </div>
                                <div class="col-md-3 right-divider">
                                    <i class="text-muted i-Search-People" ></i>
                                    <label for="Enrollment Status" data-toggle="tooltip" data-placement="right" title="Enrollment Status" data-original-title="Patient Enrollment Status" id="model-pservice_status" class="model-pservice_status"> 
                                    </label>                                
                                    <span id ="serviceActiveId" class ="serviceActiveId"></span>
                                    <br/>
                                    <label id="model-statusDate" class="model-statusDate"></label>
                                    <br/> <!--data-target="#Extend-deactive"  id="model-active_extend_deactive" -->
                                    <label for="programs"  data-toggle="tooltip" data-placement="right" title="Enrolled Services" data-original-title="Patient Enrolled Services">
                                        <i class="text-muted i-Library"></i>: <span id="model-enroll_services" class="model-enroll_services"></span> 
                                    </label>
                                    <a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px; " class="adddeviceClass"
                                    data-target="#add-device"  id="model-deviceadd" class="model-deviceadd"> 
                                    <i class="plus-icons i-Add" id="model-adddevice" style="font-size: 15px;" data-toggle="tooltip" data-placement="top" data-original-title="Additional Device"></i></a>
                                    <br/>
                                    <div id="model-newenrolldate" class="model-newenrolldate">      
                                        <label for="Enrolled Date" data-toggle="tooltip" data-placement="right" title="Enrolled Date" data-original-title="Enrolled Date">
                                            <i class="text-muted i-Over-Time"></i> : <span id="model-enroll_date" class="model-enroll_date"></span>  
                                        </label>
                                    </div>
                                    <label data-toggle="tooltip" data-placement="right" title="Device Code" data-original-title="Patient Device Code." for="Device Code">
                                        <i class="text-muted i-Hospital"></i> :
                                        <span id="model-device_code" class="model-device_code"></span>
                                    </label>   
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>


        