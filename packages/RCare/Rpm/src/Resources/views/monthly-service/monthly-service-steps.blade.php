@extends('Theme::layouts_2.to-do-master')
@section('main-content')
    @foreach($patient as $checklist)		
    <div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        <div class="col-md-12">
            {{ csrf_field() }}
          @include('Patients::components.patient-basic-info')
          @include('Rpm::monthly-service.patient-status')
        </div>
    </div>
    @endforeach	
    <div id="app"> </div>
@endsection
@section('page-js')
<script src="{{asset(mix('assets/js/laravel/rpmMonthlyServices.js'))}}"></script>
<script type="text/javascript">
        $(document).ready(function() {
            rpmMonthlyServices.init();
            renderMedicationsTable();
        });
    
    var time = "<?php echo (isset($time) && ($time!='0')) ? $time : '00:00:00'; ?>";
        var splitTime = time.split(":");
        const H = splitTime[0];
        const M = splitTime[1];
        const S = splitTime[2];
        $("#timer_start").val(time);

		$(document).ready(function() {
            util.stepWizard('tsf-wizard-monthly-service');
            $("#start").hide();
            $("#pause").show();
            $("#time-container").val(AppStopwatch.startClock);
            util.getToDoListData(0, {{getPageModuleName()}});
		});
       
	   
	   
	$('#mdeatils').click(function () {
		$('#medication_details').modal('show');      
	});
     
   $('#editmedication').click(function () {
        $('#medication_model').modal('show');      
    });
    </script>
    <script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection
