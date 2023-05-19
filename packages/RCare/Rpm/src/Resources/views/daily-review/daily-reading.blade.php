@extends('Theme::layouts_2.to-do-master')
@section('main-content')
<?php 
    if(isset($patient) && count($patient)>0){ ?>
        @foreach($patient as $checklist)        
        <div class="separator-breadcrumb "></div>
        <div class="row text-align-center">
            @include('Theme::layouts_2.flash-message')  
            <div class="col-md-12">
                {{ csrf_field() }}

              <!-- Patients::components.patient-basic-info -->
              @include('Patients::components.patient-Ajaxbasic-info')
              @include('Rpm::daily-review.reading')
              @include('Rpm::daily-review.patient-device-sub-steps.Care-Manager-Notes')
         
            </div>
            <input type="hidden" name="activedevice" id="activedevice">
            <?php  $component_id = getPageSubModuleName(); ?>   
        </div> 
        @endforeach 
    <?php }else{ ?>
       <br/>@include("Ccm::monthly-monitoring.sub-steps.no-patient")
    <?php } ?>
    <div id="app"> </div>
@endsection
@section('page-js')
<!-- Andy25Nov21-->
   <!--   <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.js"></script> 
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> -->
      <script src="{{asset('assets/js/external-js/export-child-table/buttons.html5.js')}}"></script> 
   <script src="{{asset('assets/js/external-js/export-child-table/dataTables.buttons.min.js')}}"></script> 
    <script src="{{asset('assets/js/external-js/export-child-table/buttons.colVis.min.js')}}"></script> 
      <script src="{{asset('assets/js/external-js/export-child-table/jszip.min.js')}}"></script> 
    <script src="{{asset(mix('assets/js/laravel/rpmPatientDeviceReading.js'))}}"></script>

<script type="text/javascript">
 
        var time="<?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend:'00:00:00';?>";
        var splitTime = time.split(":");
        var H = splitTime[0];
        var M = splitTime[1];
        var S = splitTime[2];
       
        $("#timer_start").val(time);
        $("#patient_time").val(time);
        function getMonth(date) {
          var month = date.getMonth() + 1;
          return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
        }

       $(document).ready(function() {
        var patientId = $("#hidden_id").val(); 
        var moduleId = $("input[name='module_id']").val(); 
        util.getPatientDetails(patientId, moduleId);
            rpmPatientDeviceReading.init();
        });



    </script>
    <script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection
 