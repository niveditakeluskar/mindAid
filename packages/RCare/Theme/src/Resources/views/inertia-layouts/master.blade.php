<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <?php
    $component_name = \Request::segment(2);
    $module_name = \Request::segment(1);
    $patient_list = \Request::segment(3);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link defer rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}">
    <link defer rel="stylesheet" href="{{ asset('assets/styles/external-css/select2.min.css') }}">
    <link defer rel="stylesheet" href="{{asset('assets/styles/vendor/calendar/fullcalendar.min.css')}}">
    {{-- form wizard --}}
    <?php
    if ($module_name != '' && $module_name != 'patients' && $patient_list != 'patients' && (($module_name == 'ccm' && $component_name == 'monthly-monitoring') || ($module_name == 'ccm' && $component_name == 'care-plan-development') || ($module_name == 'rpm' && $patient_list != ''))) {
    ?>
    <?php } ?>
    {{-- pickupdate --}}

    <link defer rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.css') }}">
    <link defer rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.date.css') }}">
    {{-- theme css --}}
    <link id="gull-theme" rel="stylesheet" href="{{ asset('assets/styles/css/themes/lite-purple.min.css') }}">
    <link defer rel="stylesheet" href="{{ asset('assets/styles/vendor/perfect-scrollbar.css') }}">
    <link defer rel="stylesheet" href="{{ asset('assets/styles/vendor/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css') }}">

    <style>
        .select2-container .select2-selection--single {
            height: 34px !important;
        }

        .select2-container--default .select2-selection--single {
            /* border: 1px solid #ccc !important;*/
            border-radius: 0px !important;
        }
    </style>
    <?php
    $themeMode = "";
    $activemode = activeThemeMode(session()->get('userid'));
    if (session()->get('darkmode') == '1' || $activemode == '1') {
        $themeMode = "dark-theme";
    }
    ?>

    @inertiaHead
</head>

<body class="layout_2 text-left {{$themeMode}}">
    @vite('resources/js/appInertia.js')
    @php
    $layout = session('layout');
    @endphp
    <!-- Pre Loader Strat  -->
    <div class='loadscreen' id="preloader">
        <div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
            <img src="{{'/images/loading.gif'}}" width="150" height="150">
        </div>
    </div> <!-- Pre Loader end  -->
    <div class="app-admin-wrap layout-horizontal-bar clearfix">
        @include('Theme::layouts_2.header-menu') <!-- ============ end of header menu ============= -->

        <!-- ============ Body content start ============= -->
        <div class="main-content-wrap  d-flex flex-column">
            <div class="main-content">

                @inertia
            </div>
            @include('Theme::layouts_2.footer')
        </div> <!-- ============ Body content End ============= -->
    </div> <!--=============== End app-admin-wrap ================-->

    <?php
    if ($module_name != '' && $module_name != 'patients' && $patient_list != 'patients' && (($module_name == 'ccm' && $component_name == 'monthly-monitoring') || ($module_name == 'ccm' && $component_name == 'care-plan-development') || ($module_name == 'rpm' && $patient_list != ''))) {
    ?>
        @include('Theme::layouts_2.patient_caretool_data')
        @include('Theme::layouts_2.patient_status')
        @include('Theme::layouts_2.patient_careplan')
        @include('Theme::layouts_2.previous-month-notes')
    <?php
    }
    ?>
    @include('Theme::layouts_2.to-list-customizer')
    <!-- ============ Horizontal Layout End ============= -->

    <!-- Model Started here -->
    <div class="modal fade" id="logout_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Logout Alert</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>You are inactive on screen since few minutes. Do you really want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary float-right" id="logout_yes" data-dismiss="modal">Yes</button>
                    <button type="button" class="btn btn-default float-left" id="logout_no" data-dismiss="modal">No</button>
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
                    <form method="post" name="active_deactive_form" id="active_deactive_form">
                        @csrf
                        <?php
                        $module_id    = getPageModuleName();
                        $submodule_id = getPageSubModuleName();
                        ?>
                        <input type="hidden" name="patient_id" value="<?php if (isset($patient[0]->id)) {
                                                                            echo $patient[0]->id;
                                                                        } ?>" />
                        <input type="hidden" name="uid" value="<?php if (isset($patient[0]->id)) {
                                                                    echo $patient[0]->id;
                                                                } ?>">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" value="{{ $module_id }}" />
                        <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                        <input type="hidden" name="form_name" value="active_deactive_form" />
                        <input type="hidden" name="id">
                        <input type="hidden" name="worklist" id="worklist">
                        <input type="hidden" name="patientid" id="patientid">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="module">Module</label>
                                    <select name="modules" id="enrolledservice_modules" class="custom-select show-tick enrolledservice_modules"></select>
                                </div>
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
                                <button type="button" class="btn btn-primary float-right submit-active-deactive">Submit</button>
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal -->
    <!-- Fin number -->
    <div id="patient-finnumber" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="fin_number_title">Fin Number</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form name="fin_number_form" id="fin_number_form">
                        @csrf
                        <?php
                        $module_id    = getPageModuleName();
                        $submodule_id = getPageSubModuleName();
                        $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'devices form ');
                        // $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Personal Notes');
                        ?>
                        <input type="hidden" name="patient_id" value="<?php if (isset($patient[0]->id)) {
                                                                            echo $patient[0]->id;
                                                                        } ?>" />
                        <input type="hidden" name="uid" value="<?php if (isset($patient[0]->id)) {
                                                                    echo $patient[0]->id;
                                                                } ?>">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" value="{{ $module_id }}" />
                        <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                        <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
                        <input type="hidden" name="form_name" value="devices_form">
                        <input type="hidden" name="idd" id="idd">

                        <div class="row">
                            <div id="devices_success"></div>
                            <div class="col-md-12 form-group">
                                <label>FIN Number<span class='error'>*</span></label>
                                <!-- <input type="text" class="form-control patient_fin_number" name="fin_number" id = "fin_number"> -->
                                <input id="fin_number_new" name="fin_number" type="text" class="form-control">
                                <div class="invalid-feedback"></div>

                                <!-- <span type="hidden id ="fin_number" class="patient_fin_number" ></span> -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary float-right submit-add-patient-fin-number">Submit</button>
                            <button type="button" class="btn btn-default float-left" onclick="devicesclear()" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Number -->
    <!-- Vatran Service -->
    <div id="vateran-service" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="vateran_service_title">Veteran Services</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" name="vateran_service_form" id="vateran_service_form">
                        @csrf
                        <?php
                        if (isset($patient[0]->id)) {
                            echo $patient[0]->id;
                            getVTreeData($patient[0]->id);
                        } else {
                            echo "No Veteran Service.";
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

    <!-- patient devices -->
    <div id="add-patient-devices" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading1">Devices</h4>
                    <button type="button" class="close" data-dismiss="modal" onclick="devicesclear()">&times;</button>
                </div>
                <div class="modal-body">
                    <form name="devices_form" id="devices_form">
                        @csrf
                        <?php
                        $module_id    = getPageModuleName();
                        $submodule_id = getPageSubModuleName();
                        $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'devices form ');
                        // $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Personal Notes');
                        ?>
                        <input type="hidden" name="patient_id" value="<?php if (isset($patient[0]->id)) {
                                                                            echo $patient[0]->id;
                                                                        } ?>" />
                        <input type="hidden" name="uid" value="<?php if (isset($patient[0]->id)) {
                                                                    echo $patient[0]->id;
                                                                } ?>">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" value="{{ $module_id }}" />
                        <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                        <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
                        <input type="hidden" name="form_name" value="devices_form">
                        <input type="hidden" name="idd" id="idd">
                        <div class="row">
                            <div id="devices_success"></div>
                            <div class="col-md-12 form-group">
                                <label>Devices ID<span class='error'>*</span></label>
                                <input type="text" class="form-control" name="device_id" id="device_id">
                                <div class="invalid-feedback"></div>
                            </div>
                            <!-- <div class="col-md-6 form-group">
                                <label>Devices<span class='error'>*</span></label>
                                @selectdevices("devices", ["id"=> "editdevice"]) 
                            </div>  -->
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Partners<span class='error'>*</span></label>
                                @selectpartner("partner_id",["id" => "partner_id"])
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Partner Devices<span class='error'>*</span></label>
                                @selectPartnerDevice("partner_devices_id",["id"=>"partner_devices_id"])
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary float-right submit-add-patient-devices">Submit</button>
                            <button type="button" class="btn btn-default float-left" onclick="devicesclear()" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                    <div class="separator-breadcrumb border-top"></div>
                    <div class="row mb-4" id="device_list_table">
                        <div class="col-md-12 mb-4">
                            <div class="card text-left">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="devices_data_list" class="display table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Sr No.</th>
                                                    <th>Device Code</th>
                                                    <th>Partner</th>
                                                    <th>Partner Device</th>
                                                    <!-- <th>Partner Devices</th>  -->
                                                    <th>Last Modifed By</th>
                                                    <th>Last Modifed On</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Additional Device -->
    <div id="additional-device" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Additional Device Email</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{route("additional.device.email")}}" method="post" name="patient_add_device_form" id="patient_add_device_form">
                        @csrf
                        <input type="hidden" name="patient_id" value="<?php if (isset($patient[0]->id)) {
                                                                            echo $patient[0]->id;
                                                                        } ?>" />
                        <input type="hidden" name="uid" value="<?php if (isset($patient[0]->id)) {
                                                                    echo $patient[0]->id;
                                                                } ?>">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
                        <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
                        <input type="hidden" name="id">
                        <input type="hidden" name="mail_content" id="mail_content">
                        <?php
                        $module_id = 2; //getPageModuleName();
                        $submodule_id = 53; //getPageSubModuleName();
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
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="sel1">Email Template:</label>
                                @selectcontentscript("call_not_answer_template_id",$module_id,$submodule_id,$stage_id,$step_id,["id"=>"email_title","class"=>"custom-select", "value" =>$template_id])
                            </div>
                            <div class="col-md-6 form-group mb-3 " style="display:none">
                                <input type="text" class="form-control" name="email_from" id="email_from">
                            </div>
                            <div class="col-md-6 form-group mb-3 " style="display:none">
                                <input type="text" class="form-control" name="email_sub" id="email_sub">
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
    <!-- End Additional Device -->
    <!-- Model for patient threshold -->
    <div id="patient-threshold" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h4 class="modal-title">Patient Threshold</h4> -->
                    <ul class="nav nav-tabs" id="ThresholdTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active tabclass" id="P-tab_1" data-toggle="tab" href="#Patient-Threshold_1" role="tab" aria-controls="ccm-call" aria-selected="false"><i class="nav-icon color-icon i-Control-2 mr-1"></i>Custom Threshold</a>
                        </li>
                        <li class="nav-item">
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
                            <form name="patient_threshold_form" id="patient_threshold_form">
                                @csrf
                                <input type="hidden" name="patient_id" value="<?php if (isset($patient[0]->id)) {
                                                                                    echo $patient[0]->id;
                                                                                } ?>" />
                                <input type="hidden" name="uid" value="<?php if (isset($patient[0]->id)) {
                                                                            echo $patient[0]->id;
                                                                        } ?>">
                                <input type="hidden" name="form_name" value="patient_threshold_form">
                                <input type="hidden" name="start_time" value="00:00:00">
                                <input type="hidden" name="end_time" value="00:00:00">
                                <input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
                                <input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
                                <input type="hidden" name="id">
                                <input type="hidden" name="stage_id" value="{{$stage_id}}">
                                <input type="hidden" name="device_code" id="device_code" value="<?php if (isset($PatientDevices[0]->device_code)) {
                                                                                                    echo $PatientDevices[0]->device_code;
                                                                                                } ?>">
                                <!-- <input type="hidden" name="deviceid" id="deviceid" value=""> -->
                                <!-- count($PatientDevices)> 0 ? $PatientDevices[0]->device_code : '' -->
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Systolic High <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['systolichigh'])) {
                                            $systolichigh = $patient_threshold['static']['systolichigh'];
                                        } else {
                                            $systolichigh = '';
                                        } ?>
                                        <input type="text" class="form-control" name="systolichigh" id="systolichigh" value="{{ $systolichigh }}">
                                        <!-- @text("systolichigh",["value" => $systolichigh ])  -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Systolic Low <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['systoliclow'])) {
                                            $systoliclow = $patient_threshold['static']['systoliclow'];
                                        } else {
                                            $systoliclow = '';
                                        } ?>
                                        <!-- @text("systoliclow",["value" => $systoliclow]) -->
                                        <input type="text" class="form-control" name="systoliclow" id="systoliclow" value="{{ $systoliclow }}">
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Diastolic High <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['diastolichigh'])) {
                                            $diastolichigh = $patient_threshold['static']['diastolichigh'];
                                        } else {
                                            $diastolichigh = '';
                                        } ?>
                                        <input type="text" class="form-control" name="diastolichigh" id="diastolichigh" value={{$diastolichigh}}>
                                        <!-- @text("systoliclow",["value" => $systoliclow]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Diastolic Low <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['diastoliclow'])) {
                                            $diastoliclow = $patient_threshold['static']['diastoliclow'];
                                        } else {
                                            $diastoliclow = '';
                                        } ?>
                                        <input type="text" class="form-control" name="diastoliclow" id="diastoliclow" value="{{$diastoliclow}}">
                                        <!-- @text("diastoliclow",["value" => $diastoliclow]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Heart Rate High <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['bpmhigh'])) {
                                            $bpmhigh = $patient_threshold['static']['bpmhigh'];
                                        } else {
                                            $bpmhigh = '';
                                        } ?>
                                        <input type="text" class="form-control" name="bpmhigh" id="bpmhigh" value="{{$bpmhigh}}">
                                        <!-- @text("bpmhigh",["value"=> $bpmhigh]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Heart Rate Low <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['bpmlow'])) {
                                            $bpmlow = $patient_threshold['static']['bpmlow'];
                                        } else {
                                            $bpmlow = '';
                                        } ?>
                                        <input type="text" class="form-control" name="bpmlow" id="bpmlow" value="{{$bpmlow}}">
                                        <!-- @text("bpmlow",["value" => $bpmlow]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Oxygen Saturation High <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['oxsathigh'])) {
                                            $oxsathigh = $patient_threshold['static']['oxsathigh'];
                                        } else {
                                            $oxsathigh = '';
                                        } ?>
                                        <input type="text" class="form-control" id="oxsathigh" name="oxsathigh" value="{{$oxsathigh}}">
                                        <!-- @text("oxsathigh",["value" => $oxsathigh]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Oxygen Saturation Low <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['oxsatlow'])) {
                                            $oxsatlow = $patient_threshold['static']['oxsatlow'];
                                        } else {
                                            $oxsatlow = '';
                                        } ?>
                                        <input type="text" class="form-control" name="oxsatlow" id="oxsatlow" value="{{$oxsatlow}}">
                                        <!-- @text("oxsatlow",["value" => $oxsatlow]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Glucose High <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['glucosehigh'])) {
                                            $glucosehigh = $patient_threshold['static']['glucosehigh'];
                                        } else {
                                            $glucosehigh = '';
                                        } ?>
                                        <input type="text" class="form-control" name="glucosehigh" id="glucosehigh" value="{{$glucosehigh}}">
                                        <!-- @text("glucosehigh",["value" => $glucosehigh]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Glucose Low <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['glucoselow'])) {
                                            $glucoselow = $patient_threshold['static']['glucoselow'];
                                        } else {
                                            $glucoselow = '';
                                        } ?>
                                        <input type="text" class="form-control" name="glucoselow" id="glucoselow" value="{{$glucoselow}}">
                                        <!-- @text("glucoselow",["value" => $glucoselow]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Temperature High <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['temperaturehigh'])) {
                                            $temperaturehigh = $patient_threshold['static']['temperaturehigh'];
                                        } else {
                                            $temperaturehigh = '';
                                        } ?>
                                        <input type="text" class="form-control" name="temperaturehigh" id="temperaturehigh" value="{{$temperaturehigh}}">
                                        <!-- @text("temperaturehigh",["value" => $temperaturehigh]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Temperature Low <!-- <span style="color:red">*</span> --></label>
                                        <?php if (isset($patient_threshold['static']['temperaturelow'])) {
                                            $temperaturelow = $patient_threshold['static']['temperaturelow'];
                                        } else {
                                            $temperaturelow = '';
                                        } ?>
                                        <input type="text" class="form-control" name="temperaturelow" id="temperaturelow" value="{{$temperaturelow}}">
                                        <!-- @text("temperaturelow",["value" => $temperaturelow]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Weight High </label>
                                        <?php if (isset($patient_threshold['static']['weighthigh'])) {
                                            $weighthigh = $patient_threshold['static']['weighthigh'];
                                        } else {
                                            $weighthigh = '';
                                        } ?>
                                        <input type="text" class="form-control" name="weighthigh" id="weighthigh" value="{{$weighthigh}}">
                                        <!-- @text("weighthigh",["id" =>"weighthigh", "value" => $weighthigh]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Weight Low </label>
                                        <?php if (isset($patient_threshold['static']['weigweightlowhtlow'])) {
                                            $weightlow = $patient_threshold['static']['weightlow'];
                                        } else {
                                            $weightlow = '';
                                        } ?>
                                        <!-- @text("weightlow",["id" => "weightlow" , "value" => $weightlow]) -->
                                        <input type="text" class="form-control" name="weightlow" id="weightlow" value="{{$weightlow}}">
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Spirometer-FEV High </label>
                                        <?php if (isset($patient_threshold['static']['spirometerfevhigh'])) {
                                            $spirometerfevhigh = $patient_threshold['static']['spirometerfevhigh'];
                                        } else {
                                            $spirometerfevhigh = '';
                                        } ?>
                                        <input type="text" class="form-control" name="spirometerfevhigh" id="spirometerfevhigh" value="{{$spirometerfevhigh}}">
                                        <!-- @text("spirometerfevhigh",["id" =>"spirometerfevhigh", "value" => $spirometerfevhigh]) -->
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Spirometer-FEV low </label>
                                        <?php if (isset($patient_threshold['static']['spirometerfevlow'])) {
                                            $spirometerfevlow = $patient_threshold['static']['spirometerfevlow'];
                                        } else {
                                            $spirometerfevlow = '';
                                        } ?>
                                        <!-- @text("spirometerfevlow",["id" => "spirometerfevlow", "value" => $spirometerfevlow]) -->
                                        <input type="text" class="form-control" name="spirometerfevlow" id="spirometerfevlow" value="{{$spirometerfevlow}}">
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Spirometer-PEF High </label>
                                        <?php if (isset($patient_threshold['static']['spirometerpefhigh'])) {
                                            $spirometerpefhigh = $patient_threshold['static']['spirometerpefhigh'];
                                        } else {
                                            $spirometerpefhigh = '';
                                        } ?>
                                        <!-- @text("spirometerpefhigh",["id" =>"spirometerpefhigh" , "value" => $spirometerpefhigh]) -->
                                        <input type="text" class="form-control" name="spirometerpefhigh" id="spirometerpefhigh" value="{{$spirometerpefhigh}}">
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label for="practicename">Spirometer-PEF low </label>
                                        <?php if (isset($patient_threshold['static']['spirometerpeflow'])) {
                                            $spirometerpeflow = $patient_threshold['static']['spirometerpeflow'];
                                        } else {
                                            $spirometerpeflow = '';
                                        } ?>
                                        <!-- @text("spirometerpeflow",["id" => "spirometerpeflow","value" => $spirometerpeflow]) -->
                                        <input type="text" class="form-control" name="spirometerpeflow" id="spirometerpeflow" value="{{$spirometerpeflow}}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <?php
                                    $uid  = session()->get('userid');
                                    $role = session()->get('role');
                                    //echo $role;
                                    if ($role == 3 || $role == 2 || $role == 5) {
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
                                        <label>Systolic Low : <p style="display:inline" id="p_systoliclow"></p> </label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Diastolic High : <p style="display:inline" id="p_diastolichigh"></p> </label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Diastolic Low : <p style="display:inline" id="p_diastoliclow"></p></label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Heart Rate High : <p style="display:inline" id="p_heartratehigh"></p></label>
                                    </div>
                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Heart Rate Low : <p style="display:inline" id="p_heartratelow"></p> </label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Oxygen Saturation High : <p style="display:inline" id="p_oxygensaturationhigh"></p> </label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Oxygen Saturation Low : <p style="display:inline" id="p_Oxygensaturationlow"></p> </label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Glucose High : <p style="display:inline" id="p_glucosehigh"></p></label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Glucose Low : <p style="display:inline" id="p_glucoselow"></p></label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Temperature High : <p style="display:inline" id="p_temperaturehigh"></p></label>
                                    </div>

                                    <div class="col-md-6 form-group mb-3 ">
                                        <label>Temperature Low : <p style="display:inline" id="p_temperaturelow"></p></label>
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
    <!-- Personal Notes Modal -->
    <div id="personal-notes" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Personal Notes</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form name="personal_notes_form" id="personal_notes_form">
                        @csrf
                        <?php
                        $module_id    = getPageModuleName();
                        $submodule_id = getPageSubModuleName();
                        $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Personal Notes');
                        // $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Personal Notes');
                        ?>
                        <input type="hidden" name="patient_id" value="<?php if (isset($patient[0]->id)) {
                                                                            echo $patient[0]->id;
                                                                        } ?>" />
                        <input type="hidden" name="uid" value="<?php if (isset($patient[0]->id)) {
                                                                    echo $patient[0]->id;
                                                                } ?>">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" value="{{ $module_id }}" />
                        <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                        <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
                        <input type="hidden" name="id">
                        <input type="hidden" name="form_name" value="personal_notes_form">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Personal Notes<span class='error'>*</span></label>
                                    <textarea name="personal_notes" class="form-control forms-element personal_notes_class"><?php if (isset($personal_notes['static']['personal_notes'])) {
                                                                                                                                echo $personal_notes['static']['personal_notes'];
                                                                                                                            } ?></textarea>
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
    <!-- Personal Notes Modal end-->
    <!-- Reasearch Study Modal -->
    <div id="part-of-research-study" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Part of Research Study</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form name="part_of_research_study_form" id="part_of_research_study_form">
                        @csrf
                        <?php
                        $module_id    = getPageModuleName();
                        $submodule_id = getPageSubModuleName();
                        $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Research Study');
                        // $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Personal Notes');
                        ?>
                        <input type="hidden" name="patient_id" value="<?php if (isset($patient[0]->id)) {
                                                                            echo $patient[0]->id;
                                                                        } ?>" />
                        <input type="hidden" name="uid" value="<?php if (isset($patient[0]->id)) {
                                                                    echo $patient[0]->id;
                                                                } ?>">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="00:00:00">
                        <input type="hidden" name="module_id" value="{{ $module_id }}" />
                        <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                        <input type="hidden" name="form_name" value="part_of_research_study_form">
                        <input type="hidden" name="stage_id" value="{{$stage_id}}">
                        <input type="hidden" name="id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Part of Research Study<span class='error'>*</span></label>
                                    <textarea name="part_of_research_study" class="form-control forms-element"><?php if (isset($research_study['static']['part_of_research_study'])) {
                                                                                                                    echo $research_study['static']['part_of_research_study'];
                                                                                                                } ?><?php //if(isset($research_study[0]->part_of_research_study)){echo $research_study[0]->part_of_research_study;}
                                                                                                                    ?></textarea>
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
    <!-- End Reasearch Study Modal -->
    
    {{-- common js --}}
    <script defer src="{{  asset('assets/js/common-bundle-script.js')}}"></script>
    {{-- page specific javascript --}}
    {{-- form.basic --}}
    <!-- <script src="{{asset('assets/js/form.basic.script.js')}}"></script>-->
    {{-- theme javascript --}}
    <script defer src="{{asset('assets/js/script.js')}}"></script>
    <script defer src="{{asset('assets/js/customizer.script.js')}}"></script>
    <!--<script src="{{asset('assets/js/sidebar-horizontal.script.js')}}"></script>-->
    {{-- laravel js --}}
<!--   <script defer src="{{asset('assets/js/laravel/iapp.js')}}"></script>  -->
<script src="https://cdn.ckeditor.com/4.14.0/basic/ckeditor.js"></script> 
<!--  <script src="{{asset('assets/js/laravel/ccmMonthlyMonitoring.js')}}"></script>
<script src="{{asset('assets/js/laravel/carePlanDevelopment.js')}}"></script> -->
    {{-- page specific javascript --}}

    <script defer src="{{asset('assets/js/laravel/ccmcpdcommonJS.js')}}"></script>
<script>
    var populateForm = function populateForm(data, url) {
  $.get(url, data, function (result) {
    for (var key in result) {
      if (key == 'hippa_form') {
        if (result[key] != null && typeof result[key] != "undefined" && result[key] != "") {
          var hippa_vf = result[key]['static']['verification'];

          if (hippa_vf = 1) {
            $('#ccm-relationship-icon-tab').removeClass('disabled');
            $('#ccm-research-follow-up-icon-tab').removeClass('disabled');
            $('#ccm-general-questions-icon-tab').removeClass('disabled');
            $('#ccm-call-close-icon-tab').removeClass('disabled'); // $('#ccm-call-wrapup-icon-tab').removeClass('disabled');
          }
        } else {
          $('#ccm-relationship-icon-tab').addClass('disabled');
          $('#ccm-research-follow-up-icon-tab').addClass('disabled');
          $('#ccm-general-questions-icon-tab').addClass('disabled');
          $('#ccm-call-close-icon-tab').addClass('disabled'); // $('#ccm-call-wrapup-icon-tab').addClass('disabled');

          $("#ccm-relationship-icon-tab").css("background-color", "#c0c0c047");
          $('#ccm-research-follow-up-icon-tab').css("background-color", "#c0c0c047");
          $('#ccm-general-questions-icon-tab').css("background-color", "#c0c0c047");
          $('#ccm-call-close-icon-tab').css("background-color", "#c0c0c047"); // $('#ccm-call-wrapup-icon-tab').css("background-color","#c0c0c047");
        }
      }

      if (key == 'callwrapup_form') {
        if (result[key] != null && typeof result[key] != "undefined" && result[key] != "") {
          var emr_monthly_summarys = result[key]["static"]['emr_monthly_summary'];
          var summarys = result[key]["static"]['summary'];

          if (summarys != null && summarys != undefined && summarys != "") {
            var summaryslength = result[key]["static"]['summary'].length;
          } else {
            var summaryslength = "";
          }

          var checklist_data = result[key]["static"]['checklist_data'];

          if (result[key]["static"]['additional_services'] != null && typeof result[key]["static"]['additional_services'] != "undefined" && result[key]["static"]['additional_services'] != "") {
            var additionalservices = result[key]["static"]['additional_services'][0]['notes'];
            var resultnew = additionalservices.split(';').map(function (e) {
              return e.split(':');
            });

            for (var i = 0; i < resultnew.length; i++) {
              var servicedata = resultnew[i];

              for (var k = 0; k < servicedata.length; k++) {
                var maindivname = servicedata[0];
                var lwercase = maindivname.toLowerCase();
                var trimcase = lwercase.trim();
                newtrimcase = trimcase.replace('/', '');
                var mymaindiv = newtrimcase.replace(/ /g, "");
                var finalservicedata = servicedata[k];

                if (finalservicedata == 'No Additional Services Provided ' || finalservicedata == "No Additional Services Provided ") {
                  // alert('if');
                  // alert(finalservicedata);
                  if (finalservicedata != '') {
                    $("form[name='callwrapup_form'] #no_additional_services_provided").prop('checked', true);
                  }
                } else {
                  if (finalservicedata.indexOf(',') > -1) {
                    var additionaldata = finalservicedata.split(',');

                    for (var n = 0; n < additionaldata.length; n++) {
                      w = additionaldata[n];
                      var y = w.trim();
                      var y_old = y.replace(/ /g, "_");
                      var mydata = y_old.replace(/\//g, "_");

                      if (mydata != '') {
                        var addname = "RRclass " + mydata;
                        var checkboxid = mymaindiv + '_' + mydata;
                        $("form[name='callwrapup_form'] #" + checkboxid).prop('checked', true); // $("form[name='callwrapup_form'] #"+mydata).prop('checked', true);
                        // $('input:checkbox[name="urgentemergentresponse[Interaction_with_Office_Staff]"][value="1"]').prop('checked',true);
                      }
                    }
                  } else {
                    var f_lowercase = finalservicedata.toLowerCase();
                    var x = f_lowercase.trim();
                    x = x.replace('/', ' ');
                    var n_lowercase_old = x.replace(/ /g, "_");
                    var n_lowercase = n_lowercase_old.replace(/\//g, "_");

                    if (n_lowercase != '') {
                      $("form[name='callwrapup_form'] #" + n_lowercase).prop('checked', true);
                    }
                  }
                }
              }
            }
          }

          callWrapUpShowHide();

          if (emr_monthly_summarys != null && emr_monthly_summarys != "") {
            $("textarea#callwrap_up_emr_monthly_summary").html(emr_monthly_summarys[0]['notes']);
          }

          var newwwchildrenlength = $("div#additional_monthly_notes").children().length;
          var inc_notes = 0;

          if (newwwchildrenlength == summaryslength) {} else {
            for (var summary in summarys) {
              var summarys1 = summarys[summary]['record_date'];

              if (summarys1 != null && summarys1 != undefined && summarys1 != "") {
                var e_date = summarys[summary]['record_date'];
                edate = e_date.split(' '); // console.log(edate);

                var enew_date = edate[0];
                var echange_date_format = enew_date.split('-');
                var e_set_date = echange_date_format[2] + '-' + echange_date_format[0] + '-' + echange_date_format[1];
                console.log(e_set_date); // alert(e_set_date);  

                $('#additional_monthly_notes').append('<div class="additionalfeilds additionalfeilds row"  style="margin-left: 0.05rem !important;  margin-bottom: 0.5rem; "><div class="col-md-4"><input type="date" class="form-control" id="emr_monthly_summary_date_' + inc_notes + '" name="emr_monthly_summary_date[]" ><div class="invalid-feedback"></div></div><div class="col-md-8"><textarea  class="form-control " cols="90" style="margin-bottom: 1.1rem !important;"  name="emr_monthly_summary[]" onfocusout="saveEMR()">' + summarys[summary]['notes'] + '</textarea><div class="invalid-feedback"></div><i type="button" class="removenotes  i-Remove" style="color: #f44336;  font-size: 22px;margin-top: -37px;margin-right: -51px;float: right;"></i></div></div>');
                $("form[name='callwrapup_form'] #emr_monthly_summary_date_" + inc_notes).val(e_set_date);
                inc_notes++;
              }
            }
          }

          if (checklist_data && checklist_data['emr_entry_completed'] != null) {
            var emr_entry_completed = checklist_data['emr_entry_completed'];

            if (emr_entry_completed == 1) {
              $('#emr_entry_completed').prop('checked', true); // Checks it
            } else {
              $('#emr_entry_completed').prop('checked', false); // Unchecks it
            }
          }

          if (checklist_data && checklist_data['called_office_patientbehalf'] != null) {
            var checklist_data = result[key]["static"]['checklist_data'];
            var called_office_patientbehalf = checklist_data['called_office_patientbehalf'];

            if (called_office_patientbehalf == 1) {
              $('#called_office_patientbehalf').prop('checked', true); // Checks it
            } else {
              $('#called_office_patientbehalf').prop('checked', false); // Unchecks it
            }
          }

          if (checklist_data && checklist_data['schedule_office_appointment'] != null) {
            var checklist_data = result[key]["static"]['checklist_data'];
            var schedule_office_appointment = checklist_data['schedule_office_appointment'];

            if (schedule_office_appointment == 1) {
              $('#schedule_office_appointment').prop('checked', true); // Checks it
            } else {
              $('#schedule_office_appointment').prop('checked', false); // Unchecks it
            }
          }

          if (checklist_data && checklist_data['resources_for_medication'] != null) {
            var checklist_data = result[key]["static"]['checklist_data'];
            var resources_for_medication = checklist_data['resources_for_medication'];

            if (resources_for_medication == 1) {
              $('#resources_for_medication').prop('checked', true); // Checks it
            } else {
              $('#resources_for_medication').prop('checked', false); // Unchecks it
            }
          }

          if (checklist_data && checklist_data['medical_renewal'] != null) {
            var checklist_data = result[key]["static"]['checklist_data'];
            var medical_renewal = checklist_data['medical_renewal'];

            if (medical_renewal == 1) {
              $('#medical_renewal').prop('checked', true); // Checks it
            } else {
              $('#medical_renewal').prop('checked', false); // Unchecks it
            }
          }

          if (checklist_data && checklist_data['referral_support'] != null) {
            var checklist_data = result[key]["static"]['checklist_data'];
            var referral_support = checklist_data['referral_support'];

            if (referral_support == 1) {
              $('#referral_support').prop('checked', true); // Checks it
            } else {
              $('#referral_support').prop('checked', false); // Unchecks it
            }
          }

          if (checklist_data && checklist_data['no_other_services'] != null) {
            var checklist_data = result[key]["static"]['checklist_data'];
            var no_other_services = checklist_data['no_other_services'];

            if (no_other_services == 1) {
              $('#no_other_services').prop('checked', true); // Checks it
            } else {
              $('#no_other_services').prop('checked', false); // Unchecks it
            }
          }
        }
      } else {

        var form = {
  dynamicFormPopulate: function(key, value) {
    // Your implementation here
    console.log(key, value);
  }
};

        form.dynamicFormPopulate(key, result[key]);

        if (key == 'call_close_form') {
          if (result[key] != '') {
            //debugger;
            var date_enrolled = result[key]["static"]['q2_datetime'];

            if (date_enrolled != undefined) {
              var a = date_enrolled.split(" ")[0];
              var day = a.split("-");
              $mydate = day[2] + '-' + day[0] + '-' + day[1];
              document.getElementById("next_month_call_date").value = $mydate;
            }
          }
        }

        if (key == 'followup_task_edit_notes') {
          if (result[key][0].notes != null) {
            var notes = result[key][0].notes;
            $('#notes').html(notes);
          }

          if (result[key][0].task_notes != null) {
            var task_notes = result[key][0].task_notes;
            $('#task_notes').html(task_notes);
            $('#topic').val(task_notes);
          }

          if (result[key][0].task_date != null) {
            var task_date = result[key][0].task_date;

            if (task_date != '') {
              date = task_date.split(' ');
              var new_date = date[0];
              var change_date_format = new_date.split('-');
              var set_date = change_date_format[2] + '-' + change_date_format[0] + '-' + change_date_format[1];
              $('#task_date').html(date[0]);
              document.getElementById("task_date_val").value = set_date;
            }
          }

          if (result[key][0].master_followuptask != null) {
            var category = result[key][0].master_followuptask['task'];
            $('#category').html(category);
          }

          if (result[key][0].status_flag == 1) {
            $('#status_flag').prop('checked', true);
          } else {
            $('#status_flag').prop('checked', false);
          }
        }

        if (key == 'call_preparation_preparation_followup_form' && result[key].hasOwnProperty("static")) {
          if (result[key]["static"]['condition_requirnment1'] != null) {
            var CR1 = result[key]["static"]['condition_requirnment1'];
          } else {
            var CR1 = "";
          }

          if (result[key]["static"]['condition_requirnment2'] != null) {
            var CR2 = result[key]["static"]['condition_requirnment2'];
          } else {
            var CR2 = "";
          }

          if (result[key]["static"]['condition_requirnment3'] != null) {
            var CR3 = result[key]["static"]['condition_requirnment3'];
          } else {
            var CR3 = "";
          }

          if (result[key]["static"]['report_requirnment1'] != null) {
            var RR1 = result[key]["static"]['report_requirnment1'];
          } else {
            var RR1 = "";
          }

          if (result[key]["static"]['report_requirnment2'] != null) {
            var RR2 = result[key]["static"]['report_requirnment2'];
          } else {
            var RR2 = "";
          }

          if (result[key]["static"]['report_requirnment4'] != null) {
            var RR4 = result[key]["static"]['report_requirnment4'];
          } else {
            var RR4 = "";
          }

          if (result[key]["static"]['report_requirnment5'] != null) {
            var RR5 = result[key]["static"]['report_requirnment5'];
          } else {
            var RR5 = "";
          }

          if (CR1 == 1 || CR2 == 1 || CR3 == 1) {
            $('form[name="call_preparation_preparation_followup_form"] #call_preparation_note').css('display', 'block');
            $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_note').css('display', 'block');
          } else {
            $('form[name="call_preparation_preparation_followup_form"] #call_preparation_note').css('display', 'none');
            $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_note').css('display', 'none');
          }

          if (RR1 == 1 || RR2 == 1 || RR4 == 1 || RR5 == 1) {
            $('form[name="call_preparation_preparation_followup_form"] #call_preparation_requirnment').css('display', 'block');
            $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_requirnment').css('display', 'block');
          } else {
            $('form[name="call_preparation_preparation_followup_form"] #call_preparation_requirnment').css('display', 'none');
            $('form[name="research_follow_up_preparation_followup_form"] #research_follow_up_requirnment').css('display', 'none');
          }

          if (result[key]["static"]['condition_requirnment4'] != null) {
            var CR4 = result[key]["static"]['condition_requirnment4'];
          } else {
            var CR4 = "";
          }

          if (CR1 == 1 || CR2 == 1 || CR3 == 1 || CR4 == 1) {
            $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_yes").prop("checked", true);
          } else {
            $("form[name='research_follow_up_preparation_followup_form'] #research_follow_up_data_present_in_emr_no").prop("checked", true);
          }
        }
      }
    }
  }).fail(function (result) {
    console.error("Population Error:", result);
  });
};



           
</script>

</body>

</html>