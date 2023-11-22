@extends('Theme::layouts_2.to-do-master')
@section('main-content')
    @foreach($patient as $checklist)		

    <div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        <div class="col-md-12">
            {{ csrf_field() }}
            <!--Add view Patient Overview -->
           @include('Rpm::monthlyService.patient-overview')
        </div>
    </div>
    @endforeach
	
@endsection
@section('page-js')
	
	<!-- <script>
        $(document).ready(function() {
            orgmenus.init();
            form.ajaxForm(
                "menuForm",
                orgmenus.onResult,
                orgmenus.onSubmit,
                orgmenus.onErrors
            );
            form.evaluateRules("MenuAddRequest");
        });
    </script> -->
@endsection
