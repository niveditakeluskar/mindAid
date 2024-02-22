<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title') Renova Healthcare</title>
    <!--  <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,600,700,800,900" rel="stylesheet"> -->


    <link rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/styles/external-css/select2.min.css') }}">
    <!--     <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/calendar/fullcalendar.min.css')}}">
    @yield('before-css')
    {{-- form wizard --}}
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/tsf-wizard.bundle.min.css') }}">
    {{-- pickupdate --}}

    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.date.css') }}">
    {{-- theme css --}}
    <link id="gull-theme" rel="stylesheet" href="{{ asset('assets/styles/css/themes/lite-purple.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/perfect-scrollbar.css') }}">
    <!-- dropdown css -->

    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css') }}">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  -->
    <!-- <script src="{{asset('assets/js/vendor/jquery-3.3.1.min.js')}}"></script> -->

    {{-- page specific css --}}
    <!-- <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet" /> -->
    <link rel="Stylesheet" href="{{ asset('assets/styles/external-css/themes-smoothness-jquery-ui.css')}}">
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

</head>

<?php $themeMode = "";
$activemode = activeThemeMode(session()->get('userid'));
if (session()->get('darkmode') == '1' || $activemode == '1') {
    $themeMode = "dark-theme";
} ?>

<body class="layout_2 text-left {{$themeMode}}">
    {{-- <!-- @vite(['resources/js/appInertia.js','resources/laravel/js/bootstrap.js','resources/laravel/js/form.js', 'resources/laravel/js/utility.js','resources/laravel/js/carePlanDevelopment.js', 'resources/laravel/js/ccmcpdcommonJS.js']) --> --}}

    <!-- <div id="app">        -->
    @php
    $layout = session('layout');
    @endphp
    <!-- Pre Loader Strat  -->
    <div class='loadscreen' id="preloader">
        <div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
            <img src="{{'/images/loading.gif'}}" width="150" height="150">
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
    if ($module_name != '' && $module_name != 'patients' && $patient_list != 'patients' && (($module_name == 'ccm' && $component_name == 'monthly-monitoring') || ($module_name == 'ccm' && $component_name == 'care-plan-development') || ($module_name == 'rpm' && $patient_list != ''))) {
    ?>
        @include('Theme::layouts_2.patient_caretool_data')
        @include('Theme::layouts_2.patient_status')

    <?php
    }
    if ($component_name != '' && $patient_list != 'patients' && ($component_name == 'monthly-monitoring' || $component_name == 'care-plan-development' || ($component_name == 'daily-review' && $patient_list != '') || $component_name == 'patient-alert-data-list')) { ?>
        @include('Theme::layouts_2.patient_careplan')
        @include('Theme::layouts_2.previous-month-notes')
    <?php } ?>
    @include('Theme::layouts_2.to-list-customizer')
    @include('Theme::layouts_2.cm-patient-assign')


    <!-- </div> -->
    <!-- ============ Horizontal Layout End ============= -->

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
                    <button type="button" class="btn btn-default float-left" id="logout_no" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- LAModel Ended here -->

    <div id="patient-finnumber" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="fin_number_title">Fin Number</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{route('patient.savefinnumber')}}" method="post" name="fin_number_form" id="fin_number_form">
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
                    <h4 class="modal-title" id="vateran_service_title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" name="vateran_service_form" id="vateran_service_form">
                        @csrf
                        <?php

                        if (isset($patient[0]->id)) {
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
                    <form action="{{route("master.devices")}}" method="post" name="devices_form" id="devices_form">
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
    <!-- patient devices -->



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
                            <form action="{{route("patient.threshold")}}" method="post" name="patient_threshold_form" id="patient_threshold_form">
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
                                        <input type="text" class="form-control" name="oxsatlow" id="oxsatlow" value="{{$oxsatlow}"}>                           
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
    <!-- Add Device -->
    <div id="add-device" class="modal fade" role="dialog">
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
                    <form action="{{route("patient.personalnotes")}}" method="post" name="personal_notes_form" id="personal_notes_form">
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
                    <form action="{{route("patient.researchstudy")}}" method="post" name="part_of_research_study_form" id="part_of_research_study_form">
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
                    <form action="{{route("patient.active.deactive")}}" method="post" name="active_deactive_form" id="active_deactive_form">
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
                        <input type="hidden" name="fromstarttime" id="fromstarttime">
                        <input type="hidden" name="module_id" value="{{ $module_id }}" />
                        <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                        <input type="hidden" name="form_name" value="active_deactive_form" />
                        <input type="hidden" name="id">
                        <input type="hidden" name="worklistclick" id="worklistclick">
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
    <div id="enrolleddateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5>Enrolled Date</h5>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <form action="{{route('save.enrolleddate')}}" method="post" name="enrolleddateform" id="enrolleddateform">
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
                            <input type="hidden" name="patient_id" value="<?php if (isset($patient[0]->id)) {
                                                                                echo $patient[0]->id;
                                                                            } ?>" />
                            <input type="hidden" name="uid" value="<?php if (isset($patient[0]->id)) {
                                                                        echo $patient[0]->id;
                                                                    } ?>">
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
    <!-- <input type="hidden" id="patientScreenTimeout" name="patientScreenTimeout" value="<?php //echo idleTimeRedirect(); 
                                                                                            ?>" /> -->

    {{-- common js --}}

    <script src="{{ asset('assets/js/common-bundle-script.js')}}"></script>

    <!--script src="{{  asset('assets/js/ckeditor.js')}}"></script--->
    <script src="https://cdn.ckeditor.com/4.14.0/basic/ckeditor.js"></script>
    {{-- page specific javascript --}}
    {{-- form.basic --}}
    <script src="{{asset('assets/js/form.basic.script.js')}}"></script>
    {{-- theme javascript --}}

    {{-- <script src="{{'assets/js/es5/script.js'}}"></script> --}}

    <script src="{{asset('assets/js/script.js')}}"></script>

    <script src="{{asset('assets/js/sidebar-horizontal.script.js')}}"></script>


    {{-- laravel js --}}

    <!-- <script src="{{asset('assets/js/laravel/additionalMethods.js')}}"></script> -->
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>

    <!--<script src="{{asset('assets/js/datatables.script.js')}}"></script>-->
    <script src="{{asset('assets/js/vendor/dataTables.select.min.js')}}"></script>

    <script src="{{asset('assets/js/vendor/datatables/dataTables.editor.min.js')}}"></script>

    <!--  <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>  -->
    <script src="{{asset('assets/js/vendor/jquery-migrate-3.0.0.min.js')}}"></script>
    <!-- <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script> -->

    <script src="{{asset('assets/js/laravel/app.js')}}"></script>

    {{-- page specific javascript --}}

    <!--<script src="{{asset('assets/js/vendor/jquery.smartWizard.min.js')}}"></script>
            <script src="{{asset('assets/js/smart.wizard.script.js')}}"></script> -->
    <script src="{{asset('assets/js/vendor/parsley.min.js')}}"></script>
    <script src="{{asset('assets/js/vendor/tsf-wizard.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/customizer.script.js')}}"></script>



    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.js" ></script> -->
    <!-- dropdown js -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>-->
    <!-- Latest compiled and minified JavaScript -->
    <!--  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>   -->
    <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> 
              -->
    <script src="{{asset('assets/js/external-js/bootstrap-1.13.14-select.min.js')}}"></script>
    <script src="{{asset('assets/js/laravel/select2.min.js')}}"></script>
    <!-- (Optional) Latest compiled and minified JavaScript translation files
            <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->

    <script>
        function devicesclear() {
            $("#devices_form input[name='device_id']").val('');
            $('#partner_id').val('');
            $('#partner_devices_id').val('');
            $(`form[name="devices_form"]`).find(".is-invalid").removeClass("is-invalid");
            $(`form[name="devices_form"]`).find(".invalid-feedback").html("");
        }

        function getDevice(id) {
            if (id.checked) {
                var y = id.id;
                var editor = CKEDITOR.instances['email_title_area'].getData();
                var data = editor + '<li>' + y + '</li>';
                CKEDITOR.instances['email_title_area'].setData(data);
            } else {
                var myTextArea = CKEDITOR.instances['email_title_area'].getData();
                var text = $.trim(myTextArea.replace('<li>' + id.id + '</li>', ""));
                CKEDITOR.instances['email_title_area'].setData(text);
            }
        }

        $("[name='partner_id']").on("change", function() {
            if ($(this).val() == '') {
                var partner_id = null;
                util.updatePartnerDevice(parseInt(partner_id), $("#partner_devices_id"));
            } else {
                util.updatePartnerDevice(parseInt($(this).val()), $("#partner_devices_id"));
            }
        });


        function setIntervalMCFunctionAgain() {
            var id = $("input[name='patient_id']").val();
            $.ajax({
                url: "/messaging/get-message-count",
                type: 'GET',
                success: function(res) {
                    $(".message-notification").html('');
                    $(".message-notification").append(res.trim());
                    setTimeout(function() {
                        setIntervalMCFunction();
                    }, 10000);
                }
            });
        }

        function setIntervalMCFunction() {
            var id = $("input[name='patient_id']").val();
            $.ajax({
                url: "/messaging/get-message-count",
                type: 'GET',
                success: function(res) {
                    $(".message-notification").html('');
                    $(".message-notification").append(res.trim());
                    setTimeout(function() {
                        setIntervalMCFunctionAgain();
                    }, 10000);
                }
            });
        }

        $(document).ready(function() {
            localStorage.setItem("idleTime", 0);
            util.getSessionLogoutTimeWithPopupTime();
            var idleInterval = setInterval(checkTimeInterval, 1000); // 1 Seconds
            $(this).mousemove(function(e) {
                localStorage.setItem("idleTime", 0);
            });

            $(this).keypress(function(e) {
                localStorage.setItem("idleTime", 0);
            });

            var $body = $("body");
            // //Dark version
            $('#dark-checkbox').change(function() {
                if ($(this).prop('checked')) {
                    $body.addClass("dark-theme");
                    var ch = 1;
                } else {
                    $body.removeClass("dark-theme");
                    var ch = 0;
                }
                $.ajax({
                    method: "get",
                    url: "/org/ajax/theme-dark",
                    data: {
                        darkmode: ch
                    }
                });
            });

            $("[name='partner_id']").on("change", function() {
                if ($(this).val() == '') {
                    var partner_id = null;
                    util.updatePartnerDevice(parseInt(partner_id), $("#partner_devices_id"));
                } else {
                    util.updatePartnerDevice(parseInt($(this).val()), $("#partner_devices_id"));
                }
            });
            setTimeout(function() {
                document.getElementById("customizer_id").style.display = "block";
            }, 3000);

            CKEDITOR.replace('email_title_area');
            $('.select2').select2();
            $('.select2-container--default .select2-selection--single').attr('style', 'border-radius: 0.25rem !important');

            $("#ThresholdTab #P-tab_2").on("click", function() {
                var patient_id = $("#patient_id").val();
                var module_id = $("#module_id").val();
                $.ajax({
                    url: '/patients/systemThresholdTab/' + patient_id + '/' + module_id,
                    type: 'get',
                    success: function(data) {
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
        });

        /*********************************************************************************************************************************************** */
        var checkTimeInterval = function timerIncrement() {
            sessionIdleTime = localStorage.getItem("idleTime");
            var showPopupTime = localStorage.getItem("showPopupTime"); //changes by ashvini
            var sessionTimeoutInSeconds = localStorage.getItem("sessionTimeoutInSeconds"); //changes by ashvini
            var systemDate = localStorage.getItem("systemDate");
            var currentDate = new Date();
            var res = Math.abs(Date.parse(currentDate) - Date.parse(systemDate)) / 1000;
            var idleTime = parseInt(sessionIdleTime) + (res % 60);
            if (idleTime >= showPopupTime) {
                var visiblemodal = $('#logout_modal').is(':visible');
                if (visiblemodal) {
                    console.log('visiblemodal');
                } else {
                    $('#logout_modal').modal('show');
                }

                if (idleTime >= sessionTimeoutInSeconds) {
                    var visiblemodal = $('#logout_modal').is(':visible');
                    if (visiblemodal) {
                        $("#sign-out-btn")[0].click();
                        var base_url = window.location.origin;
                        window.location.href = base_url + '/rcare-login';
                        window.location.reload();
                    }
                }
            }
            localStorage.setItem("idleTime", idleTime);
            localStorage.setItem("systemDate", currentDate);
        }

        $("#logout_yes").click(function(e) {
            $("#sign-out-btn")[0].click();
        });

        $("#logout_no").click(function(e) {
            $('#logout_modal').modal('hide');
        });
        var patient_id = $("#patient_id").val();
        var module_id = $("input[name='module_id']").val();
        util.getPatientPreviousMonthCalender(patient_id, module_id);
        var regis = $("#regi_mnth").val();
        var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        var d = new Date();
        var currentMonth = moment().format("MM");
        var currentYear = moment().format("YYYY");
        $("#display_month_year").html(moment().format("MMMM YYYY"));

        $("#prev-sidebar-month").click(function() {
            var patient_id = $("#patient_id").val();
            var module_id = $("input[name='module_id']").val();
            util.getPatientPreviousMonthCalender(patient_id, module_id);
            var registeredcalender = $("#regi_mnth").val();
            var dateObj1 = new Date(registeredcalender);
            var registeredmonth = dateObj1.getMonth() + 1; //months from 1-12 =>Because getmonth() start from 0.
            var registeredday = dateObj1.getDate();
            var registeredyear = dateObj1.getFullYear();
            // ==================================================== //
            var patient_id = $("#hidden_id").val();
            var module_id = $("input[name='module_id']").val();
            var display_month_year_data = $("#display_month_year").text();
            var display_month_year_dataarray = display_month_year_data.split(' '); //21spt
            var display_month_year_datamonth = display_month_year_dataarray[0];
            var display_month_year_datayear = display_month_year_dataarray[1];
            var prev_year = display_month_year_datayear;

            for (var i = 0; i < months.length; i++) {
                if (months[i] == display_month_year_datamonth) {
                    var prev_month = i - 1;
                }
            }

            if (display_month_year_datamonth == 'January') {
                var prev_month = 11;
                var prev_year = prev_year - 1;
            }

            if (registeredcalender.length > 0) {
                $("#display_month_year").html(months[prev_month] + ' ' + prev_year);
                year = prev_year;
                month = prev_month + 1;
                if (new Date(year, month) < new Date(currentYear, currentMonth)) {
                    $("#next-sidebar-month").show();
                } else {
                    $("#next-sidebar-month").hide();
                }

                if (new Date(year, month) >= new Date(registeredyear, registeredmonth)) {
                    $("#prev-sidebar-month").show();
                } else {
                    var displaydata = "Patient CareTools"
                    $("#display_month_year").html(displaydata);
                    $("#prev-sidebar-month").hide();
                    $(".previous_month-body").hide();
                    $("#temp").show();
                    $("#temp").html('');
                    $('#temp').append($(".patientCaretool-body").html());
                }
            } else {
                var displaymonth = months[currentMonth - 2];
                var displaydata = "Patient CareTools"
                $("#display_month_year").html(displaydata);
                $("#next-sidebar-month").show();
                $("#prev-sidebar-month").hide();
                $(".previous_month-body").hide();
                $("#temp").show();
                $('#temp').html('');
                $('#temp').append($(".patientCaretool-body").html());
            }
            util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
        });
        // function curr_month_Fun(){
        $("#next-sidebar-month").click(function() {
            var registeredcalender = $("#regi_mnth").val();
            var dateObj1 = new Date(registeredcalender);
            var month_name = dateObj1.getMonth();
            var registeredmonth = dateObj1.getMonth() + 1; //months from 1-12 =>Because getmonth() start from 0.
            var registeredday = dateObj1.getDate();
            var registeredyear = dateObj1.getFullYear();
            var display_month_year_data = $("#display_month_year").text();
            var display_month_year_dataarray = display_month_year_data.split(' '); //21spt
            var display_month_year_datamonth = display_month_year_dataarray[0];
            var display_month_year_datayear = display_month_year_dataarray[1];
            var nxt_year = display_month_year_datayear;
            for (var i = 0; i < months.length; i++) {
                if (months[i] == display_month_year_datamonth) {
                    var nxt_month = i + 1;
                }
            }
            if (display_month_year_datamonth == 'December') {
                var nxt_month = 0;
                nxt_year = parseInt(nxt_year) + 1;
            } else if (display_month_year_datamonth == 'Patient' ||
                display_month_year_datamonth == 'Patient CareTools' || display_month_year_datamonth == 'undefined') {
                var nxt_month = parseInt(registeredmonth) - 1;
                nxt_year = registeredyear;
            }
            year = nxt_year;
            month = nxt_month;
            $("#temp").hide();
            $(".previous_month-body").show();
            if (registeredcalender.length > 0) {
                $("#display_month_year").html(months[month] + ' ' + year);
                month = month + 1; //bcz array starts from 0 so miunused 1 from above

                if (new Date(year, month) < new Date(currentYear, currentMonth)) {
                    $("#next-sidebar-month").show();
                } else {
                    $("#next-sidebar-month").hide();
                }
                if (new Date(year, month) >= new Date(registeredyear, registeredmonth)) {
                    $("#prev-sidebar-month").show();
                } else {
                    $("#prev-sidebar-month").hide();
                    $(".previous_month-body").hide();
                    $("#temp").show();
                    $('#temp').html('');
                    $('#temp').append($(".patientCaretool-body").html());
                }
            } else {
                var displaymonth = months[currentMonth - 1];
                $("#display_month_year").html(displaymonth + ' ' + year);
                $("#next-sidebar-month").hide();
                $("#prev-sidebar-month").show();
            }
            util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
        });
        //******************************************************************************************************************************************************** */                
    </script>

    @yield('page-js')
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    @yield('bottom-js')
</body>

</html>