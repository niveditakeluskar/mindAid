@extends('Theme::layouts_2.to-do-master')
@section('page-title')details - @endsection
@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/styles/vendor/multiselect/bootstrap-multiselect.css')}}">
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables/editor.dataTables.min.css')}}">
    <style> 
        #symptoms_0, input.symptoms, #goals_0, input.goals, #tasks_0, input.tasks {
            margin-bottom: 5px;
        }
    </style>
@endsection
<?php 
// dd($patient[0]->id);
    // if(isset($patient) && isset($patient_enroll_date) && $patient_enroll_date != null && count($patient_enroll_date)>0) {
    if(isset($patient) && $patient != null && count($patient)>0) {
        $patient_id = $patient[0]->id; 
?>
    @section('main-content')
    <div class="separator-breadcrumb "></div>
    <input type="hidden" id="patient_id" name="patient_id" value="{{ $patient_id }}">
    
      <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        @csrf
        <div class="col-md-12">
            {{ csrf_field() }}
            <?php
                $module_id    = getPageModuleName();
                $submodule_id = getPageSubModuleName();
                $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Data');
                $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'Family-Emergency-Contact');
            ?>
            @include('Patients::components.patient-Ajaxbasic-info')
            @include('Patients::components.patient-monthly-monitoring-details')
        </div>
    </div>

    
    
    <div id="app"> </div>
    @endsection
@section('page-js')
<script src="{{asset(mix('assets/js/laravel/ccmMonthlyMonitoring.js'))}}"></script>

<script type="text/javascript">
    var newDate = new Date,
    date = newDate.getDate(),
    month = newDate.getMonth(),
    year = newDate.getFullYear();
    var patient_id = $('#patient_id').val();
</script>
   
<script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection
<?php } else { ?>
    @section('main-content')
    <div>@include("Theme::patient-doesnt-exist")</div>
    @endsection
<?php } ?>
