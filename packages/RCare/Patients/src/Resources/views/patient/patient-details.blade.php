@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <style> 
        .disabled { 
            pointer-events: none; 
            cursor: default; 
        } 
    </style>
@endsection
<?php 
        if(isset($patient) && count($patient)>0){ 
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

            @include('Patients::components.patient-basic-info')		
            @include('Patients::components.patient-monthly-monitoring-details')	
    </div>
</div>
<div id="app"></div>
@endsection
@section('page-js')
    <!-- <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>    cmnt on 15thNov21 -->
     
    <!-- added on 15thnov21 -->
    <script src="{{asset(mix('assets/js/laravel/patientEnrollment.js'))}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){  
            // util.stepWizard('tsf-wizard-2');
            patientEnrollment.init();
        });

    </script>
    <script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection

<?php }else{ ?>
<!-- include("Ccm::monthly-monitoring.sub-steps.no-patient") -->
<?php } ?>