<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Renova Healthcare</title>
          <!--  andy22nov21 -->
           <link rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/styles/external-css/select2.min.css') }}">
        @yield('before-css')
        {{-- form wizard --}}
            <link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard.min.css')}}">
          <!--   <link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard_theme_arrows.min.css')}}">
            <link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard_theme_circles.min.css')}}">
            <link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard_theme_dots.min.css')}}"> -->
             {{-- pickupdate --}}
             <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
             <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
        {{-- theme css --}}
        <link id="gull-theme" rel="stylesheet" href="{{  asset('assets/styles/css/themes/lite-purple.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/styles/vendor/perfect-scrollbar.css')}}">
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
         <script src="{{asset('assets/js/vendor/jquery-3.3.1.min.js')}}"></script> 
        {{-- page specific css --}}
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
    if(session()->get('darkmode') == '1'){
        $themeMode = "dark-theme";
    } ?>
    <body class="layout_2 text-left {{$themeMode}}">
        <div id="">
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
    </div>
<div id="app"></div>
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
         <script src="{{asset(mix('assets/js/laravel/app.js'))}}"></script>

        {{-- page specific javascript --}}
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> -->
        <script src="{{asset('assets/js/laravel/select2.min.js')}}"></script>
        <script>
            // var idleTime = 0;
            $('.select2').select2();
            $('.select2-container--default .select2-selection--single').attr('style', 'border-radius: 0.25rem !important');
            $(document).ready(function () {
                var $body = $("body");
                // Dark version
                $("#dark-checkbox").change(function() {
                    if (this.checked) {
                        $body.addClass("dark-theme");
                        var ch = 1;
                    } else {
                        $body.removeClass("dark-theme");
                        var ch = 0;
                    }
                    $.ajax({ 
                            method: "get",
                            url: "/org/ajax/theme-dark",
                            data: {darkmode: ch}
                        });
                });

            });
            // $(document).ready(function () {
            //     util.getSessionLogoutTimeWithPopupTime();
            //     var idleInterval = setInterval(checkTimeInterval, 1000); // 1 Seconds
            //     $(this).mousemove(function(e) {
            //         // idleTime = 0;
            //         localStorage.setItem("idleTime", 0);
            //     });

            //     $(this).keypress(function(e){
            //         // idleTime = 0;
            //         localStorage.setItem("idleTime", 0);
            //     });
            // });

            // var checkTimeInterval = function timerIncrement() {
            //     // idleTime = idleTime + 1; //Calls every 1 seconds
            //     sessionIdleTime = localStorage.getItem("idleTime");

            //     // var showPopupTime = sessionStorage.getItem("showPopupTime");
            //     // var sessionTimeoutInSeconds = sessionStorage.getItem("sessionTimeoutInSeconds");

                
            //     var showPopupTime = localStorage.getItem("showPopupTime"); //changes by ashvini
            //     var sessionTimeoutInSeconds = localStorage.getItem("sessionTimeoutInSeconds"); //changes by ashvini

            //     var systemDate= localStorage.getItem("systemDate");
            //     var currentDate = new Date();
            //     var res = Math.abs(Date.parse(currentDate) - Date.parse(systemDate)) / 1000;
            //     var idleTime = parseInt(sessionIdleTime) + (res % 60);
            //     // console.log("idleTime-"+idleTime);
            //     // console.log("showPopupTime-"+showPopupTime);
            //     // console.log("sessionTimeoutInSeconds-"+sessionTimeoutInSeconds);
            //     if(idleTime >= showPopupTime) {
            //         $('#logout_modal').modal('show'); 
            //     }
            //     if(idleTime >= sessionTimeoutInSeconds) {
            //         $('#logout_modal').modal('hide'); 
            //         $( "#sign-out-btn" )[0].click();
            //     }
            //     localStorage.setItem("idleTime", idleTime);
            //     localStorage.setItem("systemDate", currentDate);
            // }

            // $("#logout_yes").click(function (e) { 
            //     $( "#sign-out-btn" )[0].click();
            // }); 

            // $("#logout_no").click(function (e) {    
            //     $('#logout_modal').modal('hide'); 
            // }); 
        </script>  
        @yield('page-js')
            <!-- form WIZARD -->
            <script src="{{asset('assets/js/vendor/jquery.smartWizard.min.js')}}"></script>
            <script src="{{asset('assets/js/smart.wizard.script.js')}}"></script>
            <script src="{{asset('assets/js/customizer.script.js')}}"></script>
            <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
            <script type="text/javascript">
                util.totalTimeSpentByCM();
            </script>
        @yield('bottom-js')
    </body>
</html>