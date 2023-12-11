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
    <link rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/styles/external-css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/calendar/fullcalendar.min.css')}}">
    {{-- form wizard --}}
    <?php
    if ($module_name != '' && $module_name != 'patients' && $patient_list != 'patients' && (($module_name == 'ccm' && $component_name == 'monthly-monitoring') || ($module_name == 'ccm' && $component_name == 'care-plan-development') || ($module_name == 'rpm' && $patient_list != ''))) {
    ?>
    <?php } ?>
    {{-- pickupdate --}}

    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.date.css') }}">
    {{-- theme css --}}
    <link id="gull-theme" rel="stylesheet" href="{{ asset('assets/styles/css/themes/lite-purple.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css') }}">

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
    @vite('resources/js/appInertia.js')
    @inertiaHead
</head>

<body class="layout_2 text-left {{$themeMode}}">
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

    <!-- LAModel Started here -->
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
    {{-- common js --}}
    <script src="{{  asset('assets/js/common-bundle-script.js')}}"></script>
    {{-- page specific javascript --}}
    {{-- form.basic --}}
    <script src="{{asset('assets/js/form.basic.script.js')}}"></script>
    {{-- theme javascript --}}
    <script src="{{asset('assets/js/script.js')}}"></script>
    <script src="{{asset('assets/js/sidebar-horizontal.script.js')}}"></script>
    {{-- laravel js --}}
    <script src="{{asset('assets/js/laravel/app.js')}}"></script>
    {{-- page specific javascript --}}
    <script src="{{asset('assets/js/customizer.script.js')}}"></script>
    <script src="{{asset('assets/js/laravel/ccmcpdcommonJS.js')}}"></script>
   <script>
function setIntervalMCFunctionAgain() {
            var id = $("input[name='patient_id']").val();
            $.ajax({
                url: "/messaging/get-message-count",
                type: 'GET',
                // dataType: 'json', // added data type
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
                // idleTime = 0;
                localStorage.setItem("idleTime", 0);
            });
            $(this).keypress(function(e) {
                // idleTime = 0;
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
            setIntervalMCFunction();
            setTimeout(function() {
                document.getElementById("customizer_id").style.display = "block";
            }, 3000);
            util.totalTimeSpentByCM();
        });

    var checkTimeInterval = function timerIncrement() {
            // idleTime = idleTime + 1; //Calls every 1 seconds
            sessionIdleTime = localStorage.getItem("idleTime");

            // var showPopupTime = sessionStorage.getItem("showPopupTime");
            // var sessionTimeoutInSeconds = sessionStorage.getItem("sessionTimeoutInSeconds");


            var showPopupTime = localStorage.getItem("showPopupTime"); //changes by ashvini
            var sessionTimeoutInSeconds = localStorage.getItem("sessionTimeoutInSeconds"); //changes by ashvini

            var systemDate = localStorage.getItem("systemDate");
            var currentDate = new Date();
            var res = Math.abs(Date.parse(currentDate) - Date.parse(systemDate)) / 1000;
            var idleTime = parseInt(sessionIdleTime) + (res % 60);


            console.log("idleTime-" + idleTime);
            // console.log("showPopupTime-"+showPopupTime);
            // console.log("sessionTimeoutInSeconds-"+sessionTimeoutInSeconds);


            if (idleTime >= showPopupTime) {

                console.log('idleTime in if loop idleTime >= showPopupTime');

                // $('#logout_modal').modal('show');   
                var visiblemodal = $('#logout_modal').is(':visible');
                if (visiblemodal) {
                    console.log('visiblemodal');
                } else {
                    $('#logout_modal').modal('show');
                }

                if (idleTime >= sessionTimeoutInSeconds) {
                    console.log('idleTime in if loop idleTime >= sessionTimeoutInSeconds');
                    var visiblemodal = $('#logout_modal').is(':visible');
                    if (visiblemodal) {
                        console.log('visiblemodal in sessiontimeout');
                        // $('#logout_modal').modal('hide');   
                        $("#sign-out-btn")[0].click();
                        var base_url = window.location.origin;
                        // alert(base_url);  
                        window.location.href = base_url + '/rcare-login';
                        window.location.reload();
                    }
                }
            }
            localStorage.setItem("idleTime", idleTime);
            // localStorage.setItem("idleTime", 0);
            localStorage.setItem("systemDate", currentDate);
        }
        $("#logout_yes").click(function(e) {
            $("#sign-out-btn")[0].click();
        });

        $("#logout_no").click(function(e) {
            $('#logout_modal').modal('hide');
        });
   </script>
</body>
</html>