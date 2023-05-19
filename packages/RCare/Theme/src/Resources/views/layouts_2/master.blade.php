<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('page-title') Renova Healthcare</title><!-- //Updated by -pranali on 21Oct2020 -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,600,700,800,900" rel="stylesheet"> -->
        <!--  pri19thnov21 -->
        <link rel="stylesheet" href="{{ asset('assets/styles/external-css/fonts-googleapis.css')}}">
        @yield('before-css') 
        {{-- form wizard --}}
            <!-- <link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard.min.css')}}"> -->
          <!--   <link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard_theme_arrows.min.css')}}">
            <link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard_theme_circles.min.css')}}">
            <link rel="stylesheet" href="{{asset('assets/styles/vendor/smart.wizard/smart_wizard_theme_dots.min.css')}}"> -->
             {{-- pickupdate --}} 
              <!-- aanad -->
             <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}"> 
             <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
        {{-- theme css --}}
        <link id="gull-theme" rel="stylesheet" href="{{  asset('assets/styles/css/themes/lite-purple.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/styles/vendor/perfect-scrollbar.css')}}">
        {{-- page specific css --}}
        @yield('page-css')
    </head>

 <?php $themeMode = "";
    if(session()->get('darkmode') == '1'){
        $themeMode = "dark-theme";
    } ?>
    <body class="layout_2 text-left {{$themeMode}}">       
            @php
                $layout = session('layout');
            @endphp
            <!-- Pre Loader Strat  -->
                <div class='loadscreen' id="preloader">
                    <div class="loader spinner-bubble spinner-bubble-primary">
                    </div>
                </div>
                <div style="display: none;margin-left:50%; margin-top:20%" id="load-logout">
                    <div>Logging Out</div>
                <span class="loader-bubble loader-bubble-info m-2"></span>
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
        {{-- @include('Theme::layouts.search') --}}
        <!-- ============ Search UI End ============= -->
        <!-- ============ Horizontal Layout End ============= -->
        <!-- Modal -->
        <div id="personal-notes" class="modal fade" role="dialog">
            <div class="modal-dialog">

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
                                $module_id      = getPageModuleName();
                                $submodule_id   = getPageSubModuleName();
                            ?>
                            <input type="hidden" name="patient_id" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>" />
                            <input type="hidden" name="uid" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>">
                            <input type="hidden" name="start_time" value="00:00:00">
                            <input type="hidden" name="end_time" value="00:00:00"> 
                            <input type="hidden" name="module_id" value="{{ $module_id }}" />
                            <input type="hidden" name="component_id" value="{{ $submodule_id }}" /> 
                            <input type="hidden" name="id">
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
                            ?>
                            <input type="hidden" name="patient_id" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>" />
                            <input type="hidden" name="uid" value="<?php if(isset($patient[0]->id)){ echo $patient[0]->id; } ?>">
                            <input type="hidden" name="start_time" value="00:00:00">
                            <input type="hidden" name="end_time" value="00:00:00"> 
                            <input type="hidden" name="module_id" value="{{ $module_id }}" />
                            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                            <input type="hidden" name="id">
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

        {{-- jquery js --}} 
<!--         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
        <script src="{{asset('assets/js/vendor/jquery-3.3.1.min.js')}}"></script>
        {{-- common js --}}
        <script src="{{asset('assets/js/common-bundle-script.js')}}"></script>
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

             <script src="{{asset('assets/js/vendor/parsley.min.js')}}"></script>
             <script src="{{asset('assets/js/vendor/jquery.smartWizard.min.js')}}"></script>
      
             <!-- <script src="{{asset('assets/js/smart.wizard.script.js')}}"></script> -->
            <script src="{{asset('assets/js/customizer.script.js')}}"></script>
            <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
        @yield('bottom-js')


        <script type="text/javascript">
                // var idleTime = 0;
                // var activityflag = true;
                //     $(document).ready(function () {
                //         // Increment the idle time counter every minute.
                //         var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

                //         // Zero the idle timer on mouse movement.
                //         $(this).mousemove(function (e) {
                        
                //             idleTime = 0;
                //         });
                //         $(this).keypress(function (e) {
                //             idleTime = 0;
                //         });
                //     });


                //     $(this).mousemove(function (e) {
                        
                //         // console.log("click");                     
                //                 idleTime = 0;
                //                 activityflag = false;
                //                 // console.log("body",activityflag);
                //                 $('#load-logout').hide();
                //                 $('.main-header').show();
                //                 $('.main-content').show(); 
                //                 $('#footer').show();
                //     });

                //     $(this).keypress(function (e) {
                //         // console.log("click");                     
                //                 idleTime = 0;
                //                 activityflag = false;
                //                 // console.log("body",activityflag);
                //                 $('#load-logout').hide();
                //                 $('.main-header').show();
                //                 $('.main-content').show(); 
                //                 $('#footer').show();
                //         });

                  



                //     function timerIncrement() { 
                //         idleTime = idleTime + 1;
                //         if (idleTime >= 110) { // 9 mints //110mintes // 2 minutes
                           
                //             // console.log("if",idleTime);
                //             $('#load-logout').show();
                //             $('.main-header').hide();
                //             $('.main-content').hide();
                //             $('#footer').hide();
                //             activityflag = true;
                //             // alert(activityflag);
                //             setTimeout(function(){  
                                
                           

                            
                //             if(activityflag)
                //             {
                              
                //                 var base_url = "http://rcareproto2.d-insights.global";
                //                 var url = "/rcare-login";
                //                 window.location.href=base_url+''+url;  
                //             }
                //             else{
                                
                //                 $('#load-logout').hide();
                //                 $('.main-header').show();
                //                 $('.main-content').show(); 
                           
                //             }

                //                 }, 20000);
                //         }
                //         else{
                          
                //             activityflag=false;
                //             $('#load-logout').hide();
                //             $('.main-header').show();
                //             $('.main-content').show(); 
                //             $('#footer').show();
                //         }
                //     }
            </script>   


    </body>
</html>