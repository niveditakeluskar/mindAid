@extends('Theme::layouts_2.to-do-master')
@section('main-content')

    @foreach($patient as $checklist)		
    <div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        <div class="col-md-12">
            {{ csrf_field() }}
          @include('Patients::components.patient-basic-info')
          @include('Rpm::chart-new.newchartfile_2')
        </div>
    </div> 
    @endforeach	
    <div id="app"> </div>
@endsection
@section('page-js') 
    <script type="text/javascript">  
      var time = "<?php echo (isset($time) && ($time!='0')) ? $time : '00:00:00'; ?>";
        var splitTime = time.split(":");
        const H = splitTime[0];
        const M = splitTime[1];
        const S = splitTime[2];
        $("#timer_start").val(time);


        $(document).ready(function() {  
           // alert("test");
            $('form[name="patient_threshold_form"] .submit-patient-threshold').on('click', function () {
                alert('Yes Review Readin Inside You!!');
            });
           // alert(AppStopwatch.startClock);
            var patient_id = $("#patient_id").val();
            var module_id = $("input[name='module_id']").val();
            util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
            util.stepWizard('tsf-wizard-monthly-service');
            $("#start").hide();
            $("#pause").show();
            $("#time-container").val(AppStopwatch.startClock);
           // util.getToDoListData(0, {{getPageModuleName()}});
            rpmMonthlyServices.init();
            // util.getPatientStatus(patient_id, module_id);
        });
    </script>
    <script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection
 