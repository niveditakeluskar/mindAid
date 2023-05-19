<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Renova Healthcare</title>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,600,700,800,900" rel="stylesheet">
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
        {{-- page specific css --}}
        @yield('page-css')
    </head>


    <body class="text-left">        
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
            @include('Theme::layouts.header-menu')
            <!-- ============ end of header menu ============= -->
            @include('Theme::layouts.horizontal-bar')
            <!-- ============ end of left sidebar ============= -->
            <!-- ============ Body content start ============= -->
            <div class="main-content-wrap  d-flex flex-column">
                <div class="main-content">
                    @yield('main-content')
                </div>
                @include('Theme::layouts.footer')
            </div>
            <!-- ============ Body content End ============= -->
        </div>
        <!--=============== End app-admin-wrap ================-->

        <!-- ============ Search UI Start ============= -->
        @include('Theme::layouts.search')
        <!-- ============ Search UI End ============= -->
        <!-- ============ Horizontal Layout End ============= -->
        {{-- jquery js --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
         <script src="{{asset('assets/js/laravel/app.js')}}"></script>
        {{-- page specific javascript --}}
        @yield('page-js')
            <!-- form WIZARD -->
             <script src="{{asset('assets/js/vendor/jquery.smartWizard.min.js')}}"></script>
             <script src="{{asset('assets/js/smart.wizard.script.js')}}"></script>
            <script src="{{asset('assets/js/customizer.script.js')}}"></script>
        <!-- <script src="{{asset('assets/js/tooltip.script.js')}}"></script> -->
        @yield('bottom-js')
    </body>
</html>