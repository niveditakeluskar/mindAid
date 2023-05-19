<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('page-title') Renova Healthcare</title>
        <!-- <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,600,700,800,900" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
         --> 
        <!--  pri19thnov21 -->
        <link rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/styles/external-css/select2.min.css') }}">
        @yield('before-css')
        {{-- form wizard --}}
            <link rel="stylesheet" href="{{ asset('assets/styles/vendor/tsf-wizard.bundle.min.css') }}">
        {{-- pickupdate --}}
        <link rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.date.css') }}">
        {{-- theme css --}}
        <link id="gull-theme" rel="stylesheet" href="{{ asset(mix('assets/styles/css/themes/lite-purple.min.css')) }}">
        <link rel="stylesheet" href="{{ asset('assets/styles/vendor/perfect-scrollbar.css') }}">
        <!-- dropdown css -->
        <link rel="stylesheet" href="{{ asset('assets/styles/vendor/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/styles/vendor/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css') }}"> 
        <!--  pri29th oct 21 -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
        <script src="{{asset('assets/js/vendor/jquery-3.3.1.min.js')}}"></script> 
        {{-- page specific css --}}
        <!-- <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet" /> -->
        <!-- cmnt by priya on 15th nov -->
        <link  rel="Stylesheet" href="{{ asset('assets/styles/external-css/themes-smoothness-jquery-ui.css')}}">
        
        <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
        
        @yield('page-css')
        <style>
            .select2-container .select2-selection--single {
                height: 34px !important;
            }

            .select2-container--default .select2-selection--single {
            /* border: 1px solid #ccc !important;*/
                border-radius: 0px !important;
            }
        </style>
        <script src="https://cdn.ckeditor.com/4.14.0/basic/ckeditor.js"></script> 
    </head>


    <body class="layout_2 text-left"> 
    
        <!-- <div id="app">        -->
            @php
                $layout = session('layout');
            @endphp
            <!-- Pre Loader Strat  -->
                <div class='loadscreen' id="preloader">
                    <div class="loader spinner-bubble spinner-bubble-primary">
                    </div>
                </div>
            <!-- Pre Loader end  -->
            <div class="app-admin-wrap layout-horizontal-bar clearfix">
                @include('Theme::layouts_2.header-menu')
                <!-- ============ end of header menu ============= -->
                
                <!-- ============ end of left sidebar ============= -->
                <!-- ============ Body content start ============= -->
                <div class="main-content-wrap  d-flex flex-column">
                    <div class="main-content">
                        @yield('main-content')
                    </div>
                    @include('Theme::layouts_2.footer')
                </div>
                <!-- ============ Body content End ============= -->
            </div>
            <!--=============== End app-admin-wrap ================-->

            <!-- ============ Search UI Start ============= -->
            {{-- @include('Theme::layouts_2.search') --}}
            <!-- ============ Search UI End ============= -->
            
            <?php
                $component_name = \Request::segment(2);
                $module_name = \Request::segment(1);
                $patient_list = \Request::segment(3);
                //echo "sdfdg gfdgdfgf grgtr gtrgrtgtrgggggggggggggggggggggg";
                //echo $module_name;
                // $current_url = url()->current();
                // $previous_url = url()->previous();
                // Session::set('_current.url', $current_url);
                // Session::set('_previous.url', $previous_url);
                // $data = \Request::Session()->all();
                // dd($data);
               // print_r($component_name);
                // print_r($patient_list);
                // if($component_name != "monthly-monitoring-patients" && $component_name != "monthly-monitoring-patient-list")
                // if($module_name!= '' && $module_name!='patients' && $patient_list!='patients' && ($module_name=='ccm' || $module_name=='rpm'))
              //  dd("hiiii".$module_name);

                if($module_name!= '' && $module_name!='patients' && $patient_list!='patients' && (($module_name=='ccm' && $component_name =='monthly-monitoring')|| ($module_name=='ccm' && $component_name == 'care-plan-development') || ($module_name=='rpm' && $patient_list!='' ) || $module_name=='messaging'))
                {
                    // echo $component_name . $module_name; 
                      
                ?>
                    @include('Theme::layouts_2.patient_caretool_data')
                    @include('Theme::layouts_2.patient_status')
                   
                <?php
                }
                if($component_name!='' && $patient_list!='patients' && ($component_name == 'monthly-monitoring' || $component_name == 'care-plan-development' ||( $component_name == 'daily-review' && $patient_list!='') || $component_name =='patient-alert-data-list' || $module_name=='messaging')){?>
                    @include('Theme::layouts_2.patient_careplan')
                    @include('Theme::layouts_2.previous-month-notes')
                <?php }?>
            @include('Theme::layouts_2.to-list-customizer')  
             
        <!-- </div> -->
        <!-- ============ Horizontal Layout End ============= -->

        

        <!-- Vatran Service -->
<div id="vateran-service" class="modal fade" role="dialog">  
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="vateran_service_title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form  method="post" name ="vateran_service_form"  id="vateran_service_form">
                        @csrf
                        <?php
                        
                            if(isset($patient[0]->id)){
                                getVTreeData($patient[0]->id);
                                
                            }
                        ?>

                        <div class="modal-footer">
                            <!--button type="button" class="btn btn-primary float-right submit-vateran-service">Submit</button-->
                            <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Vatran Service --> 

       <!-- Model for patient threshold -->
<div  id="patient-threshold" class="modal fade" role="dialog">
     <div class="modal-dialog">

            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h4 class="modal-title">Patient Threshold</h4> -->
                        <ul class="nav nav-tabs" id="ThresholdTab" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active tabclass" id="P-tab_1" data-toggle="tab" href="#Patient-Threshold_1" role="tab" aria-controls="ccm-call" aria-selected="false"><i class="nav-icon color-icon i-Control-2 mr-1"></i>Custom Threshold</a>
                            </li>
                            <li class="nav-item" >
                                <a class="nav-link  tabclass" id="P-tab_2" data-toggle="tab" href="#Patient-Threshold_2" role="tab" aria-controls="ccm-call" aria-selected="false"><i class="nav-icon color-icon i-Control-2 mr-1"></i> Standard Threshold</a>
                            </li>
                        </ul> 
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="tab-content" id="myIconTabContent">
                            <div class="tab-pane show active" id="Patient-Threshold_1" role="tabpanel" aria-labelledby="call-icon-tab">  
                            <?php
                                $module_id    = getPageModuleName();
                                $submodule_id = getPageSubModuleName();
                                $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Alert Threshold');
                                // $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Personal Notes');
                            ?>
                            <form action="{{route("patient.threshold")}}" method="post" name ="patient_threshold_form"  id="patient_threshold_form">
                            @csrf
                                <input type="hidden" name="patient_id" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>" />
                                <input type="hidden" name="uid" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>">
                                <input type="hidden" name="form_name" value="patient_threshold_form">
                                <input type="hidden" name="start_time" value="00:00:00">
                                <input type="hidden" name="end_time" value="00:00:00"> 
                                <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
                                <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" /> 
                                <input type="hidden" name="id">
                                <input type="hidden" name="stage_id" value="{{$stage_id}}">
                                  <input type="hidden" name="device_code" id="device_code" value="<?php if(isset($PatientDevices[0]->device_code)){ echo $PatientDevices[0]->device_code; } ?>">
                                <!-- <input type="hidden" name="deviceid" id="deviceid" value=""> -->
                                <!-- count($PatientDevices)> 0 ? $PatientDevices[0]->device_code : '' -->

                    
                            <div class="row">
                            
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Systolic High <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['systolichigh'])) { $systolichigh = $patient_threshold['static']['systolichigh']; }else{$systolichigh ='';}?>
                                @text("systolichigh",["value" => $systolichigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Systolic Low <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['systoliclow'])) { $systoliclow = $patient_threshold['static']['systoliclow']; }else{$systoliclow ='';}?>
                                @text("systoliclow",["value" => $systoliclow])
                            </div>    
                            
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Diastolic High <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['diastolichigh'])) { $diastolichigh = $patient_threshold['static']['diastolichigh']; }else{$diastolichigh ='';}?>
                                @text("diastolichigh",["value" => $diastolichigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 "> 
                                <label for="practicename">Diastolic Low <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['diastoliclow'])) { $diastoliclow = $patient_threshold['static']['diastoliclow']; }else{$diastoliclow ='';}?>
                                @text("diastoliclow",["value" => $diastoliclow])
                            </div>

                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Heart Rate High <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['bpmhigh'])) { $bpmhigh = $patient_threshold['static']['bpmhigh']; }else{$bpmhigh ='';}?>
                                @text("bpmhigh",["value"=> $bpmhigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Heart Rate Low <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['bpmlow'])) { $bpmlow = $patient_threshold['static']['bpmlow']; }else{$bpmlow ='';}?>
                                @text("bpmlow",["value" => $bpmlow])
                            </div>

                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Oxygen Saturation High <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['oxsathigh'])) { $oxsathigh = $patient_threshold['static']['oxsathigh']; }else{$oxsathigh ='';}?>
                                @text("oxsathigh",["value" => $oxsathigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Oxygen Saturation Low <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['oxsatlow'])) { $oxsatlow = $patient_threshold['static']['oxsatlow']; }else{$oxsatlow ='';}?>
                                @text("oxsatlow",["value" => $oxsatlow])
                            </div>

                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Glucose High <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['glucosehigh'])) { $glucosehigh = $patient_threshold['static']['glucosehigh']; }else{$glucosehigh ='';}?>
                                @text("glucosehigh",["value" => $glucosehigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Glucose Low <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['glucoselow'])) { $glucoselow = $patient_threshold['static']['glucoselow']; }else{$glucoselow ='';}?>
                                @text("glucoselow",["value" => $glucoselow])
                            </div>

                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Temperature High <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['temperaturehigh'])) { $temperaturehigh = $patient_threshold['static']['temperaturehigh']; }else{$temperaturehigh ='';}?>
                                @text("temperaturehigh",["value" => $temperaturehigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Temperature Low <!-- <span style="color:red">*</span> --></label>
                                <?php if(isset($patient_threshold['static']['temperaturelow'])) { $temperaturelow = $patient_threshold['static']['temperaturelow']; }else{$temperaturelow ='';}?>
                                @text("temperaturelow",["value" => $temperaturelow])
                            </div>

                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Weight High </label>
                                <?php if(isset($patient_threshold['static']['weighthigh'])) { $weighthigh = $patient_threshold['static']['weighthigh']; }else{$weighthigh ='';}?>
                                @text("weighthigh",["id" =>"weighthigh", "value" => $weighthigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Weight Low </label>
                                <?php if(isset($patient_threshold['static']['weightlow'])) { $weightlow = $patient_threshold['static']['weightlow']; }else{$weightlow ='';}?>
                                @text("weightlow",["id" => "weightlow" , "value" => $weightlow])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Spirometer-FEV High </label>
                                <?php if(isset($patient_threshold['static']['spirometerfevhigh'])) { $spirometerfevhigh = $patient_threshold['static']['spirometerfevhigh']; }else{$spirometerfevhigh ='';}?>
                                @text("spirometerfevhigh",["id" =>"spirometerfevhigh", "value" => $spirometerfevhigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Spirometer-FEV low </label>
                                <?php if(isset($patient_threshold['static']['spirometerfevlow'])) { $spirometerfevlow = $patient_threshold['static']['spirometerfevlow']; }else{$spirometerfevlow ='';}?>
                                @text("spirometerfevlow",["id" => "spirometerfevlow", "value" => $spirometerfevlow])
                            </div>
                             <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Spirometer-PEF High </label>
                                <?php if(isset($patient_threshold['static']['spirometerpefhigh'])) { $spirometerpefhigh = $patient_threshold['static']['spirometerpefhigh']; }else{$spirometerpefhigh ='';}?>
                                @text("spirometerpefhigh",["id" =>"spirometerpefhigh" , "value" => $spirometerpefhigh])
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="practicename">Spirometer-PEF low </label>
                                <?php if(isset($patient_threshold['static']['spirometerpeflow'])) { $spirometerpeflow = $patient_threshold['static']['spirometerpeflow']; }else{$spirometerpeflow ='';}?>
                                @text("spirometerpeflow",["id" => "spirometerpeflow","value" => $spirometerpeflow])
                            </div>
                            
                        </div>                              
                        <div class="modal-footer">
                                <?php   
                                        $uid  = session()->get('userid');
                                        $role = session()->get('role');
                                        //echo $role;
                                        if($role == 3 || $role == 2 || $role == 5) {
                                ?>
                                <button type="button" class="btn btn-primary float-right submit-patient-threshold">Submit</button>
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                            <?php } ?>
                            </div>
                        </form>
                    </div>

                         <!--===========Second tab ===================-->
                                <div class="tab-pane show " id="Patient-Threshold_2" role="tabpanel" aria-labelledby="call-icon-tab" style="margin-top: -26">  
                               
                             
                               
                                <h4 id="heading_thr">Practice Threshold</h4>
                                 
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3 ">
                              <label>Systolic High : <p style="display:inline" id="p_systolichigh"></p></label>
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label >Systolic Low : <p style="display:inline" id="p_systoliclow"></p> </label>
                            
                            </div>
                             <div class="col-md-6 form-group mb-3 ">
                                <label >Diastolic High : <p style="display:inline" id="p_diastolichigh"></p> </label>
                                
                            </div>
                            <div class="col-md-6 form-group mb-3 "> 
                                <label >Diastolic Low : <p style="display:inline" id="p_diastoliclow"></p></label>
                              
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label>Heart Rate High : <p style="display:inline" id="p_heartratehigh"></p></label>                              
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label >Heart Rate Low : <p style="display:inline" id="p_heartratelow"></p> </label>
                                
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label >Oxygen Saturation High : <p style="display:inline" id="p_oxygensaturationhigh"></p> </label>
                               
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label >Oxygen Saturation Low : <p style="display:inline" id="p_Oxygensaturationlow"></p> </label>
                                
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label >Glucose High : <p style="display:inline" id="p_glucosehigh"></p></label>
                               
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label >Glucose Low : <p style="display:inline" id="p_glucoselow"></p></label>
                                
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label >Temperature High : <p style="display:inline" id="p_temperaturehigh"></p></label>
                            </div>  
                            <div class="col-md-6 form-group mb-3 ">
                                <label >Temperature Low : <p style="display:inline" id="p_temperaturelow"></p></label>
                            </div>
                                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Patient Threshold Ends -->


  <!-- Add Device -->
  <div id="add-device" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Additional Device Email</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route("additional.device.email")}}" method="post" name ="patient_add_device_form"  id="patient_add_device_form">
                            @csrf
                            <input type="hidden" name="patient_id" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>" />
                            <input type="hidden" name="uid" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>">
                            <input type="hidden" name="start_time" value="00:00:00">
                            <input type="hidden" name="end_time" value="00:00:00"> 
                            <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
                            <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" /> 
                            <input type="hidden" name="id">
                            <input type="hidden" name="mail_content" id="mail_content">
                           <?php
                             $module_id = 2;//getPageModuleName();
                             $submodule_id = 55;//getPageSubModuleName();
                             $stage_id = getFormStageId($module_id, $submodule_id, 'Email');
                             $step_id =  getFormStepId($module_id, $submodule_id, $stage_id, 'Additional Device');
                             $template_id = 0;
                            
                        
                           ?>
                           <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
                           <div class="alert alert-success" id="device-alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong>Additional Device data saved successfully! </strong><span id="text"></span>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-3 ">
                                    <!--label for="module">Se</label-->
                                    <select name="add_replace_device" id="add_replace_device" class="custom-select show-tick select2">
                                        <option value="1">Additional device</option>
                                        <option value="2">Replace device</option>   
                                    </select>
                                </div>    
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="sel1">Select Device:</label>
                                    <div class="wrapMulDropDevice">
                                        <button type="button" id="multiDropDevice" name="multiDropDevice" class="multiDropDevice form-control col-md-12">Select Device<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                        <ul>
                                                                      
                                        </ul>
                                    </div>
                                </div> 
                                <div class="col-md-6 form-group mb-3 " >
                                    <label for="sel1">Email Template:</label>
                                    @selectcontentscript("call_not_answer_template_id",$module_id,$submodule_id,$stage_id,$step_id,["id"=>"email_title","class"=>"custom-select", "value" =>$template_id])   
                                </div>
                                <div class="col-md-6 form-group mb-3 " style="display:none">
                                    <input type="text" class="form-control" name="email_from" id = "email_from">
                                </div>
                                <div class="col-md-6 form-group mb-3 " style="display:none">
                                    <input type="text" class="form-control" name="email_sub" id = "email_sub">
                                </div>    
                                <div class="col-md-12 form-group mb-3">
                                    <label><b>Content</b><span class="error">*</span></label>
                                    <textarea name="text_msg" class="form-control" id="email_title_area" style="padding: 5px;width: 47em;min-height: 5em;overflow: auto;height: 87px;}"></textarea>
                                </div>  
                            </div>                              
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary float-right submit-patient-add_device">Submit</button>
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Add Device -->

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
                                $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Personal Notes');
                                // $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Personal Notes');
                            ?>
                            <input type="hidden" name="patient_id" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>" />
                            <input type="hidden" name="uid" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>">
                            <input type="hidden" name="start_time" value="00:00:00">
                            <input type="hidden" name="end_time" value="00:00:00"> 
                            <input type="hidden" name="module_id" value="{{ $module_id }}" />
                            <input type="hidden" name="component_id" value="{{ $submodule_id }}" /> 
                            <input type="hidden" name="id">
                            <input type="hidden" name="stage_id" value="{{ $stage_id }}" /> 
                            <input type="hidden" name="form_name" value="personal_notes_form">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Personal Notes<span class='error'>*</span></label>
                                            <textarea name ="personal_notes" class="form-control forms-element personal_notes_class"><?php if(isset($personal_notes['static']['personal_notes'])) { echo $personal_notes['static']['personal_notes']; }?></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div> 
                                </div>
                            </div>                              
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary float-right submit-personal-notes">Submit</button>
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
                                $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Research Study');
                                // $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Personal Notes');
                            ?>
                            <input type="hidden" name="patient_id" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>" />
                            <input type="hidden" name="uid" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>">
                            <input type="hidden" name="start_time" value="00:00:00">
                            <input type="hidden" name="end_time" value="00:00:00"> 
                            <input type="hidden" name="module_id" value="{{ $module_id }}" />
                            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                            <input type="hidden" name="id">
                            <input type="hidden" name="form_name" value="part_of_research_study_form">
                            <input type="hidden" name="stage_id" value="{{$stage_id}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Part of Research Study<span class='error'>*</span></label>
                                            <textarea name ="part_of_research_study" class="form-control forms-element"><?php if(isset($research_study['static']['part_of_research_study'])) { echo $research_study['static']['part_of_research_study']; }?><?php //if(isset($research_study[0]->part_of_research_study)){echo $research_study[0]->part_of_research_study;}?></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>                              
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary float-right submit-part-of-research-study">Submit</button>
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal for Deactivation-->
    <div id="active-deactive" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content--> 
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="status-title">Change Patient Status</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="patientalertdiv"></div> 
                    <form action="{{route("patient.active.deactive")}}" method="post" name ="active_deactive_form"  id="active_deactive_form">
                        @csrf
                        <?php
                                $module_id    = getPageModuleName();
                                $submodule_id = getPageSubModuleName();
                        ?>
                        <input type="hidden" name="patient_id" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>" />
                        <input type="hidden" name="uid" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00"> 
                        <input type="hidden" name="module_id" value="{{ $module_id }}" />
                        <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                        <input type="hidden" name="form_name" value="active_deactive_form" />
                        <input type="hidden" name="id">
                        <input type="hidden" name="worklistclick" id="worklistclick">
                        <input type="hidden" name="patientid" id="patientid">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status"> Select the Status <span class="error">*</span></label>
                                    <span class="forms-element">
                                        <div class="form-row">
                                            <label class="radio radio-primary col-md-3 float-left" id="role1">
                                                <input type="radio" id="role1" class="" name="status" value="1" formControlName="radio">
                                                <span>Active</span>
                                                <span class="checkmark"></span> 
                                            </label> 
                                            <label class="radio radio-primary col-md-3 float-left" id="role0">
                                                <input type="radio" id="role0" class="" name="status" value="0" formControlName="radio">
                                                <span>Suspended</span>
                                                <span class="checkmark"></span> 
                                            </label>
                                            <label class="radio radio-primary col-md-3 float-left" id="role2">
                                                <input type="radio" id="role2" class="" name="status" value="2" formControlName="radio">
                                                <span>Deactivated</span> 
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio radio-primary col-md-3 float-left" id="role3">
                                                <input type="radio" id="role3" class="" name="status" value="3" formControlName="radio">
                                                <span>Deceased</span> 
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </span>
                                    <div class="form-row invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12" id="date_value" style="display:none">
                                <div class="form-group row">
                                    <div class="col-md-6 form-group mb-3" id="fromdate">
                                       <label for="date" id="from_date">From Date <span class="error">*</span></label>
                                       @date('activedeactivefromdate',["id" => "fromdate"])
                                    </div>
                                    <div class="col-md-6 form-group mb-3" id="deceasedfromdate">
                                       <label for="date" id="from_date">Date of Deceased <span class="error">*</span></label>
                                       @date('deceasedfromdate',["id" => "deceasedfromdate"])
                                    </div>
                                    <div class="col-md-6 form-group mb-3" id="todate">
                                       <label for="date">To Date <span class="error">*</span></label>
                                       @date('activedeactivetodate',["id" => "todate"])                    
                                   </div>
                                    <div class="col-md-6 form-group mb-3" id="deactivation_drpdwn_div">
                                        <label for="deactivation_drpdwn">Reason for Deactivation</label>
                                        @selectdeactivationReasons("deactivation_drpdwn", ["id" => "deactivation_drpdwn", "class" => "select2"])
                                    </div>
                                </div>                             
                            </div> 
                            <div class="col-md-12" id="reason">
                                 <div id="comments_div" class="mb-3 form-group">
                                    <label for="comments">Reason for status change <span class="error">*</span></label>
                                  <textarea class="form-control" name="deactivation_reason" id="comments"></textarea>
                                  <div id="comments" class="invalid-feedback"></div> 
                                 </div> 
                            </div> 
                        </div>          
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-right submit-active-deactive">Submit</button>
                            <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
    </div> 
<!--End Modal -->
    <div  id="enrolleddateModal" class="modal fade" role="dialog">   
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5>Enrolled Date</h5>  
                </div>

                <!-- Body -->
                <div class="modal-body">     
                   
                    <form action="{{route('save.enrolleddate')}}" method="post" name ="enrolleddateform"  id="enrolleddateform">
                        @csrf
                        <div class="modal-body">  
                            <div id="messagingbody"></div>  
                            <?php
                                $module_id    = getPageModuleName();
                                $submodule_id = getPageSubModuleName();
                                $cid = session()->get('userid');
                                $roleid = session()->get('role');
                                // $usersdetails = Users::where('id',$cid)->get();
                                // $roleid = $usersdetails[0]->role; 
                            ?>
                            <input type="hidden" name="patient_id" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>" />
                            <input type="hidden" name="uid" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>">
                            <input type="hidden" name="start_time" value="00:00:00">
                            <input type="hidden" name="end_time" value="00:00:00"> 
                            <!-- <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
                            <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" /> -->
                            <input type="hidden" name="roleid" id="roleid" value="<?php echo $roleid; ?>">    
                            <div class="row">
                            @date('date_enrolled',["id" => "date_enrolled"])
                            </div> 

                                                          
                        </div>         
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-right" id="">Submit</button>  
                            <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
               
            </div>
        </div>
        </div>
    </div>

    <!-- LAModel Started here -->
    <div class="modal fade" id="logout_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Logout Alert</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- <label>Do you want to logout?</label> -->
                    <label>You are inactive on screen since few minutes. Do you really want to logout?</label>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary float-right" id="logout_yes" data-dismiss="modal">Yes</button>
                    <button type="button" class="btn btn-default float-left" id="logout_no" data-dismiss="modal" >No</button>
                </div>
            </div>
        </div> 
    </div> 
    <!-- LAModel Ended here -->
        {{-- common js --}}
       
        <script src="{{  asset('assets/js/common-bundle-script.js')}}"></script>
       
         {{-- page specific javascript --}}
        {{-- form.basic --}}
        <script src="{{asset('assets/js/form.basic.script.js')}}"></script>
        {{-- theme javascript --}}
        
        {{-- <script src="{{mix('assets/js/es5/script.js')}}"></script> --}}
      
        <script src="{{asset('assets/js/script.js')}}"></script>
        
        <script src="{{asset('assets/js/sidebar-horizontal.script.js')}}"></script>
       

        {{-- laravel js --}}
   
         <!-- <script src="{{asset('assets/js/laravel/additionalMethods.js')}}"></script> -->
        <!-- <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>-->
         <script src="{{asset('assets/js/vendor/jquerydatatable/jquery.dataTables.js')}}"></script>
         <script src="{{asset('assets/js/vendor/jquerydatatable/dataTables.buttons.js')}}"></script>

         <!--<script src="{{asset('assets/js/datatables.script.js')}}"></script>-->
         <script src="{{asset('assets/js/vendor/dataTables.select.min.js')}}"></script>
          
         <script src="{{asset('assets/js/vendor/datatables/dataTables.editor.min.js')}}"></script>
     
         <!-- <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>  -->
         <script src="{{asset(mix('assets/js/vendor/jquery-migrate-3.0.0.min.js'))}}"></script>
         
         
                 
        <!-- <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script> --> 
       
        <script src="{{asset(mix('assets/js/laravel/app.js'))}}"></script>
        <script src="{{asset(mix('assets/js/laravel/patientEnrollment.js'))}}"></script>
        {{-- page specific javascript --}}
            <!-- form WIZARD -->
            <!--<script src="{{asset('assets/js/vendor/jquery.smartWizard.min.js')}}"></script>
            <script src="{{asset('assets/js/smart.wizard.script.js')}}"></script> -->
            <script src="{{asset('assets/js/vendor/parsley.min.js')}}"></script>  
            <script src="{{asset('assets/js/vendor/tsf-wizard.bundle.min.js')}}"></script> 
            <script src="{{asset(mix('assets/js/customizer.script.js'))}}"></script>
            <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.js" ></script> -->
            <!-- dropdown js -->
            <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>-->
            <!-- Latest compiled and minified JavaScript -->
            <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->
            <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> -->
            <!-- pri15nov21 -->
            <script src="{{asset('assets/js/external-js/bootstrap-1.13.14-select.min.js')}}"></script>
            <script src="{{asset('assets/js/laravel/select2.min.js')}}"></script>
            <!-- (Optional) Latest compiled and minified JavaScript translation files
            <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->
            <script>
                // var idleTime = 0;
                CKEDITOR.replace( 'email_title_area');
                $('.select2').select2();
                $('.select2-container--default .select2-selection--single').attr('style', 'border-radius: 0.25rem !important');

                $("#ThresholdTab #P-tab_2").on("click", function() {
                    var patient_id=$("#patient_id").val();
                    var module_id=$("#module_id").val();
                    $.ajax({
                        url: '/patients/systemThresholdTab/'+patient_id+'/'+module_id ,
                        type: 'get',
                        success: function(data) {
                        //    alert(JSON.stringify(data));
                            $("#heading_thr").html(data[1]);
                            $("#p_systolichigh").html(data[0].systolichigh);
                            $("#p_systoliclow").html(data[0].systoliclow);
                            $("#p_diastolichigh").html(data[0].diastolichigh);
                            $("#p_diastoliclow").html(data[0].diastoliclow);
                            $("#p_heartratehigh").html(data[0].bpmhigh);
                            $("#p_heartratelow").html(data[0].bpmlow);
                            $("#p_oxygensaturationhigh").html(data[0].oxsathigh);
                            $("#p_Oxygensaturationlow").html(data[0].oxsatlow);
                            $("#p_glucosehigh").html(data[0].glucosehigh);
                            $("#p_glucoselow").html(data[0].glucoselow);
                            $("#p_temperaturehigh").html(data[0].temperaturehigh);
                            $("#p_temperaturelow").html(data[0].temperaturelow);
                        }
                    })
                });
                util.totalTimeSpentByCM();
                function getDevice(id){
                    //alert(id.value);
                    if (id.checked) {
                        var y = id.id;
                        //var myTextArea = $('#email_title_area');
                        var editor = CKEDITOR.instances['email_title_area'].getData();
                        var data = editor + '<li>'+y+'</li>';
                        CKEDITOR.instances['email_title_area'].setData(data);
                    // myTextArea.val(myTextArea.val() + '\n' + y);
                    }else{
                        var myTextArea = CKEDITOR.instances['email_title_area'].getData();
                        var text = $.trim(myTextArea.replace('<li>'+id.id+'</li>', ""));
                        CKEDITOR.instances['email_title_area'].setData(text);
                        
                    }
                }
                
                function setIntervalMCFunctionAgain() { 
                    var id = $("input[name='patient_id']").val();
                    $.ajax({
                        url: "/messaging/get-message-count",
                        type: 'GET',
                        // dataType: 'json', // added data type
                        success: function (res) {
                        $(".message-notification").html('');
                        $(".message-notification").append(res.trim());
                        setTimeout(function () { setIntervalMCFunction(); }, 10000);
                        }
                    });
                }

                function setIntervalMCFunction() {
                    var id = $("input[name='patient_id']").val();
                    $.ajax({
                        url: "/messaging/get-message-count",
                        type: 'GET',
                        success: function (res) { 
                            $(".message-notification").html('');
                            $(".message-notification").append(res.trim());
                            setTimeout(function () { setIntervalMCFunctionAgain(); }, 10000);
                        }
                    });
                }

                $(document).ready(function () {
                    setIntervalMCFunction();
                    util.getSessionLogoutTimeWithPopupTime();
                    var idleInterval = setInterval(checkTimeInterval, 1000); // 1 Seconds
                    $(this).mousemove(function(e) {
                        // idleTime = 0;
                        localStorage.setItem("idleTime", 0);
                    });

                    $(this).keypress(function(e){
                        // idleTime = 0;
                        localStorage.setItem("idleTime", 0);
                    });
                });

                var checkTimeInterval = function timerIncrement() {
                    // idleTime = idleTime + 1; //Calls every 1 seconds
                    sessionIdleTime = localStorage.getItem("idleTime");
                    // var showPopupTime = sessionStorage.getItem("showPopupTime");
                    // var sessionTimeoutInSeconds = sessionStorage.getItem("sessionTimeoutInSeconds");

                    var showPopupTime = localStorage.getItem("showPopupTime");
                    var sessionTimeoutInSeconds = localStorage.getItem("sessionTimeoutInSeconds");


                    var systemDate= localStorage.getItem("systemDate");
                    var currentDate = new Date();
                    var res = Math.abs(Date.parse(currentDate) - Date.parse(systemDate)) / 1000;
                    var idleTime = parseInt(sessionIdleTime) + (res % 60);

                    // console.log("idleTime-"+idleTime);
                    // console.log("showPopupTime-"+showPopupTime);
                    // console.log("sessionTimeoutInSeconds-"+sessionTimeoutInSeconds);


                    if(idleTime >= showPopupTime) {
                        $('#logout_modal').modal('show'); 
                    }
                    if(idleTime >= sessionTimeoutInSeconds) {
                        $('#logout_modal').modal('hide'); 
                        $( "#sign-out-btn" )[0].click();
                    }
                    localStorage.setItem("idleTime", idleTime);
                    localStorage.setItem("systemDate", currentDate);
                }

                $("#logout_yes").click(function (e) { 
                    $( "#sign-out-btn" )[0].click();
                }); 

                $("#logout_no").click(function (e) {    
                    $('#logout_modal').modal('hide'); 
                }); 
            </script>

 
        @yield('page-js')
        <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
        @yield('bottom-js')
    </body>
</html>