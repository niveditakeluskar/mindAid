<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token --> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title') PeriOp</title>
    <!--  <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,600,700,800,900" rel="stylesheet"> -->

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico')}}">
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
    <link id="gull-theme" rel="stylesheet" href="{{ asset(mix('assets/styles/css/themes/lite-purple.min.css')) }}">
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

        <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta http-equiv="X-UA-Compatible" content="ie=edge">< !-- CSRF Token --><meta name="csrf-token" content="{{ csrf_token() }}"><title>@yield('page-title') PeriOp</title>< !-- <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,600,700,800,900" rel="stylesheet">--><link rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}"><link rel="stylesheet" href="{{ asset('assets/styles/external-css/select2.min.css') }}">< !-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />--><link rel="stylesheet" href="{{asset('assets/styles/vendor/calendar/fullcalendar.min.css')}}">@yield('before-css') {
                {
                -- form wizard --
            }
        }

        <link rel="stylesheet" href="{{ asset('assets/styles/vendor/tsf-wizard.bundle.min.css') }}"> {
                {
                -- pickupdate --
            }
        }

        <link rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.css') }}"><link rel="stylesheet" href="{{ asset('assets/styles/vendor/pickadate/classic.date.css') }}"> {
                {
                -- theme css --
            }
        }

        <link id="gull-theme" rel="stylesheet" href="{{ asset('assets/styles/css/themes/lite-purple.min.css') }}"><link rel="stylesheet" href="{{ asset('assets/styles/vendor/perfect-scrollbar.css') }}">< !-- dropdown css --><link rel="stylesheet" href="{{ asset('assets/styles/vendor/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css') }}">< !-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->< !-- <script src="{{asset('assets/js/vendor/jquery-3.3.1.min.js')}}"></script>--> {
                {
                -- page specific css --
            }
        }

        < !-- <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet" />--><link rel="Stylesheet" href="{{ asset('assets/styles/external-css/themes-smoothness-jquery-ui.css')}}"><link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">@yield('page-css') <style>.select2-container .select2-selection--single {
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