<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link defer rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}">
    <link defer rel="stylesheet" href="{{ asset('assets/styles/external-css/select2.min.css') }}">
    <link defer rel="stylesheet" href="{{asset('assets/styles/vendor/calendar/fullcalendar.min.css')}}">

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
    <?php
    $component_name = \Request::segment(2);
    $module_name = \Request::segment(1);
    $patient_list = \Request::segment(3);
    ?>
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
    @include('Theme::layouts_2.cm-patient-assign')
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

    {{-- common js --}}
    <script defer src="{{  asset('assets/js/common-bundle-script.js')}}"></script>
    {{-- page specific javascript --}}

    {{-- theme javascript --}}
    <script defer src="{{asset('assets/js/script.js')}}"></script>
    <script defer src="{{asset('assets/js/customizer.script.js')}}"></script>

    {{-- laravel js --}}
    <script defer src="{{asset('assets/js/laravel/iapp.js')}}"></script>
    <script defer src="{{asset(mix('assets/js/laravel/commonHighchart.js'))}}"></script>
    <script defer src="https://cdn.ckeditor.com/4.14.0/basic/ckeditor.js"></script>

</body>

</html>