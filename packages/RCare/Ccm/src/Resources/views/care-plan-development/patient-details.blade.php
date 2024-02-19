@extends('Theme::layouts_2.to-do-master')
@section('page-title')Care Plan Development - @endsection
@section('page-css')
<style>
    #symptoms_0,
    input.symptoms,
    #goals_0,
    input.goals,
    #tasks_0,
    input.tasks {
        margin-bottom: 5px;
    }
</style>
@endsection
<?php
if (isset($patient_enroll_date) && $patient_enroll_date != null && count($patient_enroll_date) > 0) {
    $patient_id = $patient_enroll_date[0]->patient_id;
?>
    @section('main-content')
    <div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')
        @csrf
        <div class="col-md-12">
            @include('Patients::components.patient-Ajaxbasic-info')
            @include('Ccm::care-plan-development.care-plan-development')
        </div>
    </div>
    <div id="app"> </div>
    @endsection
    @section('page-js')
    <script src="{{asset(mix('assets/js/laravel/ccmcpdcommonJS.js'))}}"></script>
    <script src="{{asset(mix('assets/js/laravel/carePlanDevelopment.js'))}}"></script>
    <script src="{{asset(mix('assets/js/laravel/patientEnrollment.js'))}}"></script>
    <script type="text/javascript">
        var time = "<?php echo (isset($last_time_spend) && ($last_time_spend != '0')) ? $last_time_spend : '00:00:00'; ?>";
        var splitTime = time.split(":");
        const H = splitTime[0];
        const M = splitTime[1];
        const S = splitTime[2];
        $("#timer_start").val(time);
        $("#patient_time").val(time);
        $(document).ready(function() {
            ccmcpdcommonJS.init();
            carePlanDevelopment.init();
            util.stepWizard('tsf-wizard-2');
            $("#start").hide();
            $("#pause").show();
            // $("#time-container").val(AppStopwatch.startClock);
            if (($("input[name='query2']:checked").val() != null) || ($("input[name='query2']:checked").val() != "")) {
                if ($("input[name='query2']:checked").val() != undefined) {
                    $("#ignore").show();
                } else {
                    $("#ignore").hide();
                }
            } else {
                $("#ignore").hide();
            }
        });

        $("input[name='finalize_cpd']").change(function() {
            var id = $("input[name='patient_id']").val();
            var billabel = $("input[name='bill_practice']").val();
            if ($("input[name='finalize_cpd']").is(':checked')) {
                var finalize = 1;
            } else {
                var finalize = 0;
            }
            $.ajax({
                type: 'post',
                url: '/ccm/finalize-cpd',
                data: {
                    "id": id,
                    "finalize": finalize,
                    "billabel": billabel
                },
                success: function(response) {
                    $("input[name='billable']").val(response.trim());
                },
            });
        });

        jQuery(function() {
            jQuery('.click_id').click(function() {
                // var patientId = $("#hidden_id").val(); 
                // var moduleId = $("input[name='module_id']").val(); 
                // util.getPatientDetails(patientId, moduleId);
                jQuery('.pcpPatientData').hide();
                jQuery('#' + $(this).attr('target')).show();
            });
            jQuery('.review-click-id').click(function() {
                // var patientId = $("#hidden_id").val();
                // var moduleId = $("input[name='module_id']").val(); 
                // util.getPatientDetails(patientId, moduleId); 
                jQuery('.cpdReviewData').hide();
                jQuery('#' + $(this).attr('target')).show();
            });
        });
    </script>
    <!-- <script src="{{-- asset('assets/js/timer.js') --}}"></script> -->
    @endsection
<?php } else { ?>
    @section('main-content')
    <div>@include("Theme::patient-doesnt-exist")</div>
    @endsection
<?php } ?>