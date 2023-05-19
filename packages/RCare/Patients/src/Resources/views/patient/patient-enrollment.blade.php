@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <style> 
        .disabled { 
            pointer-events: none; 
            cursor: default; 
        } 
    </style>
@endsection
@section('main-content')
    @foreach($patient as $checklist)		
        <div class="row  mb-4 text-align-center">
            @include('Theme::layouts.flash-message')    
            <div class="col-md-12  mb-4 patient_details">
                {{ csrf_field() }}
                <!--Add view Patient Overview -->
                @include('Patients::patientEnrollment.patient-overview')
                <div class="separator-breadcrumb border-top"></div>          
                <!--Email And Text Section  -->
                @include('Patients::patientEnrollment.textAndEmail')
                <!-- end -->
                <!--Calling Section  -->         
                @include('Patients::patientEnrollment.calling')             
                <!-- end --> 
            </div>
        </div>
    @endforeach
    <div id="app"></div>
@endsection
@section('page-js')
<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>

  <script type="text/javascript">
    //Editor Script
        CKEDITOR.replace('editor');
        var time = "<?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>";
        var splitTime = time.split(":");
        const H = splitTime[0];
        const M = splitTime[1];
        const S = splitTime[2];
        $("#timer_start").val(time);

    // Timer Script
  
    // var duration = moment.duration({
    //     'seconds': 30,
    //     'hour': 1,
    //     'minutes': 10
    // });

    // var timestamp = new Date(0,0,0,2,10,30);
    // var interval = 1;
    // setInterval(function () {
    //     timestamp = new Date(timestamp.getTime() + interval*1000);
        
    //     duration = moment.duration(duration.asSeconds() + interval, 'seconds');
    //     //.asSeconds() 
    //     // $('.countdown').text(Math.round(duration.asHours()) + 'h:' + Math.round(duration.asMinutes()) + 'm:' + Math.round(duration.asSeconds()) + 's'); //.seconds() 

    //    // $('.countdown1').text(duration.days() + 'd:' + duration.hours() + 'h:' + duration.minutes() + 'm:' + duration.seconds() + 's');
    //    // $('.countdown2').text(timestamp.getDay()+'d:'+timestamp.getHours()+'h:'+timestamp.getMinutes()+'m:'+timestamp.getSeconds()+'s');
        
    // }, 1000);

    // var AppStopwatch = (function () {
    //     var counter = 0,
    //         $stopwatch = {
    //             el: document.getElementById('stopwatch'),
    //             container: document.getElementById('time-container'),
    //             startControl: document.getElementById('start'),
    //             pauseControl: document.getElementById('pause'),
    //             stopControl: document.getElementById('stop')
    //         };

    //     var runClock;

    //     function displayTime() {
    //         var timestamp = new Date(0,0,0,0,0,0);
    //         var interval = 1;
    //         timestamp = new Date(timestamp.getTime() + interval*1000);
    //         $stopwatch.container.innerHTML = moment().hour(01).minute(02).second(counter++).format('HH : mm : ss');
    //     }

    //     function startWatch() {
    //         $("#start").hide();
    //         $("#pause").show();
    //         runClock = setInterval(displayTime, 1000);
    //     }

    //     function pauseWatch() {
    //         $("#start").show();
    //         $("#pause").hide();
    //         clearInterval(runClock);
    //     }

    //     function stopWatch() {
            
    //         alert(counter);
    //         $("#display-val").html($stopwatch.container.innerHTML);
    //         clearInterval(runClock);
    //         counter=0;
    //         // $("#start").show();
    //     }

    //     return {
    //         startClock: startWatch,
    //         pauseClock: pauseWatch,
    //         stopClock: stopWatch,
    //         $start: $stopwatch.startControl,
    //         $pause: $stopwatch.pauseControl,
    //         $stop: $stopwatch.stopControl
    //     };
    // })();

    // AppStopwatch.$start.addEventListener('click', AppStopwatch.startClock, false);
    // AppStopwatch.$pause.addEventListener('click', AppStopwatch.pauseClock, false);
    // AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
    $( document ).ready(function() {
        util.stepWizard('tsf-wizard-1');
        $("#start").hide();
        $("#pause").show();
        $("#time-container").val(AppStopwatch.startClock);
    });

    function stopRunningClock() {
        console.log("in stop running clock");
        // AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
        $("#time-container").val(AppStopwatch.stopClock);
        $("#start").show();
        $("#pause").hide();
    }

    // Button Script of TEXT MAIL and CALL
	 $("#text").click(function(){
            $("#header_text").html('Text');
            var hidden_id = $('#hidden_id').val()
            var title = $('#header_text').html();
        	$("#content-template").show();
            $("#template_type_id").val(2);
            var template_type_id = 2;
            $("#textarea").show();
            $("#contactlist").show();
            $("#emaillist").hide();
            $("#email-div").hide();
            $("#Calling_page").hide(); 
           $.ajax({
            type: 'post',
            url: '/patients/fetch-email-contenet',
            data: { _token: '{!! csrf_token() !!}', template_type_id:template_type_id },
            success: function (response) {
                // alert(response);
                 document.getElementById("content_title").innerHTML=response;
                 $("#content_title option:last").attr("selected", "selected").change(); 
                
            }
        });       	
    });

	$("#email").click(function(){
             $("#header_text").html('Email');
            var hidden_id = $('#hidden_id').val()
            var title = $('#header_text').html();
        	$("#content-template").show();
            $("#template_type_id").val(1);
            $("#textarea").hide();
            $("#contactlist").hide();
            $("#emaillist").show();
        	$("#email-div").show();
            $("#Calling_page").hide();

        	var template_type_id = 1;
        $.ajax({
            type: 'post',
            url: '/patients/fetch-email-contenet',
            data: { _token: '{!! csrf_token() !!}', template_type_id:template_type_id },
            success: function (response) {
                 document.getElementById("content_title").innerHTML=response; 
                 $("#content_title option:last").attr("selected", "selected").change();
            }
        });   
        	
    });
	  $("#Calling").click(function(){
        	$("#Calling_page").show();
            $("#content-template").hide();
       	
    });

    //Content title
        $("#content_title").change(function() {
           var content_title =$("#content_title").val();
            $.ajax({
            type: 'post',
            url: '/patients/fetch-content',
            data: {
                _token: '{!! csrf_token() !!}', content_title:content_title
            },
            success: function (response) { 
                // alert(JSON.stringify(response));
              //alert(jQuery.parseJSON(response[0].content).message);
                 $('#module_id').val(JSON.stringify(response[0].module_id));
                 $('#component_id').val(JSON.stringify(response[0].component_id)); 
                 $('#stage_id').val(JSON.stringify(response[0].stage_id));  
                 $('#template_id').val(JSON.stringify(response[0].id)); 
              $("#subject").val(jQuery.parseJSON(response[0].content).subject);
              $("#sender_from").val(jQuery.parseJSON(response[0].content).from); 
              var text = jQuery.parseJSON(response[0].content).message; 
              $('#content_area_msg').html($(text).text());
            CKEDITOR.instances['editor'].setData(jQuery.parseJSON(response[0].content).message);
             
            }
            });
        });

    function get_subservice(val){
        $.ajax({
            type: 'post',
            url: '/rpm/SaveTemplate',
            data: {
                _token: '{!! csrf_token() !!}',
                service:val
            },
            success: function (response) {
                document.getElementById("sub_module").innerHTML=response; 
            }
        });
    }
// text and Email send Ajax
    $("#send").click(function(){
        time_stop = $('.values').html();
        // alert(time_stop);
        var hidden_id         = $('#hidden_id').val();
        var title             = $('#header_text').html();
        var practice_id       =  $('#practice_id').val();
        var contact_number    =  $('#contact_number').val();
        var content_title     =  $('#content_title').val();
        var content_area_msg  =  $('#content_area_msg').val();
        var contact_email     =  $('#contact_email').val();
        var subject           =  $('#subject').val();
        var module_id         =  $('#module_id').val();
        var component_id      =  $('#component_id').val();
        var template_id       =  $('#template_id').val();
        var content           =  CKEDITOR.instances.editor.getData();
        var stage_id          =  $('#stage_id').val();
        var created_by        =  $('#created_by').val();
        var practice_id       =  $('#practice_id').val();
      
       //alert(title);
        if (title=='Text'){
            var template_type_id ='2'
                 // alert(template_type_id);
            if (contact_number=='0') {
               $('#contnumber').show();
               return false;
            }else{ $('#contnumber').hide();}
        }
        else if(title =='Email'){
                  var template_type_id ='1'
                  if (contact_email=='0') {
               $('#contemail').show();
               return false;
            }else{ $('#contemail').hide();}
            
        }
        if (content_title=='') {
               $('#contitle').show();
               return false;
            }else{ $('#contitle').hide();}
       
        $.ajax({
         type:'post', 
         url:"/patients/patient-enrollment-insert",
         data:{
            _token: '{!! csrf_token() !!}', 
            hidden_id:hidden_id,
              practice_id:practice_id,
              title:title,
              template_type:'content',
              contact_number:contact_number,
              content_title:content_title,
              title:title,
              contact_email:contact_email,
              subject:subject,
              content:content,
              content_area_msg:content_area_msg,
              template_type_id:template_type_id,
              module_id:module_id,
              component_id:component_id,
              template_id:template_id,
              created_by:created_by,
              time_stop    : time_stop,
              stage_id:stage_id},  // multiple data sent using ajax
        success: function (response) {
            if(response!=''){
         // $('#script_modal').modal('hide');
           //next_step(3);
            $('#success1').html('<div class="alert alert-success col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Successfully Inserted  </div>');
        }else{
            $('#success1').html('<div class="alert alert-danger col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Not Inserted  </div>');
        }
        }
      });
      return false;
    });
// CALL Action JS{ agree not agree or refused }
        $('#action input').on('change', function() {
            var action = $('input[name=role]:checked', '#action').val(); 
            // alert(action);
            if (action=='1'){
                $("#next_step").show();
                 $("#step").hide();
                $("#date-time").hide();
            }else if(action=='2'){
                 $("#date-time").show();
                 $("#next_step").hide();
                 $('#step').show();
            }else if(action=='3'){
                $("#next_step").hide();
                $("#date-time").hide();
                $('#step').show();
            }
        });
//calling blade js
//call answer or Not
 $("#answered").click(function(){
            $("#answer").show();
            $("#not_answer").hide();
        }); 

    $("#not_answered").click(function(){
        $("#not_answer").show();
        $("#answer").hide();

    });
//Move next step
function next_step(id){
    $("#s"+id).removeClass("disabled");
    $("#s"+id).click();
    
       /*var pageURL = $(location).attr("href");  
           var str = pageURL.substr(0,pageURL.indexOf('#')); 
           // alert(str);
           var n = pageURL.search("#");
           var m = pageURL.slice(n+6, n+7)
           var i = parseInt(m)+1;
          //var str2 = parseInt(str)+1;
          window.location=str+""+'#step-'+i;*/

  }
//move back
$("#back").click(function(){
      backStep();
});
 
function backStep(val){
    $("#s"+val).click();
   /* // alert(step);
    $('#script_modal').modal('hide');
     var pageURL = $(location).attr("href");  
     var str = pageURL.substr(0,pageURL.indexOf('#')); 
     // alert(str);
     var n = pageURL.search("#");
     var m = pageURL.slice(n+6, n+7)
     var i = parseInt(m)-1;
    //var str2 = parseInt(str)+1;
    window.location=str+""+'#step-'+i;*/
}

function stepSecond(is){
    if(is==2){
        if($("#SteponeCallScript").val()==0){
            $("#callErrors").show();
            $("#stepyes").prop('checked', false);
            return false;
        }else{
            $("#callErrors").hide();
        }
    }
    $("#s"+is).removeClass("disabled");
   $("#s"+is).click();
   if(is==7){
    $('#call_refused_messages').html('<div><h2>Call Ended</h2></div>');
   }
  
}

//Call Script
jQuery(document).ready(function($){
  $('#call_scripts').find('option[value=347]').attr('selected','selected').change();
  util.getToDoListData(0, {{getPageModuleName()}});
});
/*
$('#SteponeCallScript').on('change', function() {
   //alert( this.value );
    var id = $("#call_scripts").val(); 
     //alert(id);
        $.ajax({
            type: 'get',
            url: '/patients/fetch-getCallScriptsById',
            data : {
                'id': id,
            },
            success: function (response) {
               //alert(jQuery.parseJSON(response[0].content).message);
               $("#script").html(jQuery.parseJSON(response[0].content).message);
                // alert(response[0].id);
                $('#script_module_id').val(response[0].module_id);
                $('#script_template_id').val(response[0].id);
                $('#script_component_id').val(response[0].component_id);
                $('#script_stage_id').val(response[0].stage_id);
                $('#script_template_type_id').val(response[0].template_type_id);
                $('#script_template_id').val(response[0].id);  
                $('#script_content').html(jQuery.parseJSON(response[0].content).message);
                //$modal = $('.script_modal');
                //$modal.modal('show');
            },
            dataType: 'JSON',
        });
});*/
// call Answer

$("#module_next").click(function(){
    var enroll_module = [];
    var hidden_id =  $('#hidden_id').val();
    if($('#enroll_rpm').is(":checked")){
        var rpm = $('#enroll_rpm').val();//document.getElementsByName('enroll_module[]');
        enroll_module.push(rpm);
    }
    if($('#enroll_ccm').is(":checked")){
        var ccm = $('#enroll_ccm').val();//document.getElementsByName('enroll_module[]');
        enroll_module.push(ccm);
    }
    if($('#enroll_awv').is(":checked")){
        var awv = $('#enroll_awv').val();//document.getElementsByName('enroll_module[]');
        enroll_module.push(awv);
    }
    if($('#enroll_tcm').is(":checked")){
        var tcm = $('#enroll_tcm').val();//document.getElementsByName('enroll_module[]');
        enroll_module.push(tcm);
    }
   
    $.ajax({
        type:'post', 
        url:"/patients/enrollment-service",
        data:{
            "_token": "{{ csrf_token() }}", 
        hidden_id:hidden_id,
        enroll_module:enroll_module,
        },
        success: function (response){ 
     // alert(response);
       //$('#call_hidden_id').val(response);
       // $('#checklist_stop_hidden_time').val(response);
        if(response!=''){
         // $('#script_modal').modal('hide');
           next_step(4);
            $('#success').html('<div class="alert alert-success col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Successfully Inserted  </div>');
        }else{
            $('#success').html('<div class="alert alert-danger col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Not Inserted  </div>');
        }
        
      }
        

    });

});

 $("#next_step, .callNotAns").click(function(){
     if($('#call_scripts').val()=='0'){
         $('#CallError').show();
         return false;
     }else{ $('#CallError').hide();}

     var action = $('input[name=role]:checked', '#action').val(); 
     if(action == 1 || action == 2 || action == 3){
        $('#CheckError').hide();
     }else{
        $('#CheckError').show();
        return false;
     }
     //if(action == 1 || action==)
    
    var time_stop = $('.values').html();
    var practice_id       =  $('#practice_id').val();
    var hidden_id         =  $('#hidden_id').val();
    var title             =  'Call Attempt';//$('#card-header').text();
    var stage_id          =  $('#script_stage_id').val();
    var module_id         =  $('#script_module_id').val();
    var component_id      =  $('#script_component_id').val();
    var template_id       =  $('#SteponeCallScript').val();
    var template_type_id  =  $('#SteponeCallScript_template_type_id').val();
    var script_content    =  $('#script_content').val();
    var script_component_id = $('#script_component_id').val();
    var voice_mail = $('#voice_mail').val();



    if ($("#answered").prop('checked')){
        var call_status ='1';
       // alert('here');
        var enrollment_response = $('input[name=role]:checked', '#action').val();
        if(enrollment_response == 2){
           var date = $("#date").val();
           var time = $('#time').val();
           if(date =='' || time == ''){
            $('#datetimeCheckError').show();
            return false;
           }else{
            $('#datetimeCheckError').hide();
           }
        }
    }else{
       // alert('here1');
        var call_status ='2';
        var  enrollment_response =0;
        
    }
    //alert(call_status);

    $.ajax({
     type:'post', 
     url:"/patients/patient-enrollment-insert",
     data:{
        "_token": "{{ csrf_token() }}", 
        hidden_id:hidden_id,
        title:title,
        practice_id:practice_id,
        script_template_id: template_id,
        script_module_id:module_id,
        call_status:call_status,
        script_template_id:template_id,
        script_stage_id:stage_id,
        script_template_type_id:template_type_id,
        enrollment_response:enrollment_response,
        script_content:script_content,
        script_component_id:component_id,
        date:date,
        time:time,
        time_stop: time_stop,
        voice_mail:voice_mail
        },  // multiple data sent using ajax
    success: function (response){ 
     
       $('#call_hidden_id').val(response.trim());
       // $('#checklist_stop_hidden_time').val(response);
        if(response!=''){
         // $('#script_modal').modal('hide');
         if(call_status == 1){
            next_step(3);
         }else{
            next_step(7);
            $('#call_refused_messages').html('<div><h2>Call Not Answered</h2></div>');

         }
           
            $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-success col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Successfully Inserted  </div>');
        }else{
            $('#success').fadeOut('slow').html('<div class="alert alert-danger col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Not Inserted  </div>');
        }
        
      }
    }); 
  });
// call answer with refused and call back
 $("#step").click(function(){

    var hidden_id         = $('#hidden_id').val();
    var practice_id       = $('#practice_id').val();
    var title             = 'Call Attempt';//$('#card-header').text(); 
    var stage_id          =  $('#script_stage_id').val();
    var script_content    =  $('#script_content').text();
    var module_id         =  $('#script_module_id').val();
    var component_id      =  $('#script_component_id').val();
    var template_id       =  $('#SteponeCallScript').val();
    var template_type_id  =  $('#SteponeCallScript_template_type_id').val();
    var script_component_id = $('#script_component_id').val();
    

    if ($("#answered").prop('checked')){
        var call_status ='1'
        var enrollment_response = $('input[name=role]:checked', '#action').val();
        if(enrollment_response =='2'){
           var date = $("#date").val();
           var time = $('#time').val();
           if(date =='' || time == ''){
            $('#datetimeCheckError').show();
            return false;
           }else{
            $('#datetimeCheckError').hide();
            var utc = new Date().toJSON().slice(0,10).replace(/-/g,'-');
            if(utc > date){
                $('#dateCheckError').show();
                return false;   
            }else{
                $('#dateCheckError').hide();
            }
           }
        }
    }

    $.ajax({
     type:'post', 
     url:"/patients/patient-enrollment-insert",
     data:{
        "_token": "{{ csrf_token() }}", 
         hidden_id:hidden_id,
        title:title,
        practice_id:practice_id,
        script_content:script_content,
        script_module_id:module_id,
        call_status:call_status,
        script_template_id:template_id,
        script_stage_id:stage_id,
        script_template_type_id:template_type_id,
        enrollment_response:enrollment_response,
        script_component_id:component_id,
        date:date,
        time:time,
        //voice_mail:voice_mail
        },  // multiple data sent using ajax
    success: function (response) {
        if(response!=''){
         // $('#script_modal').modal('hide');
           next_step(7);
           if(enrollment_response =='2'){
               
                $('#call_refused_messages').html('<h2>Call Back Date:"'+date+'" And Time: "'+time+'" </h2>');
           }else{
            $('#call_refused_messages').html('<div><h2>Call Refused</h2></div>');
           }
            $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-success col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Successfully Inserted  </div>');
        }else{
            $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-danger col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Not Inserted  </div>');
        }
        
      }
    }); 
  }); 
   // CAll Not Answered
       $("#finish").click(function(){
         var form_id  = $('#call_hidden_id').val();
         var practice_id       =  $('#practice_id').val();
         var hidden_id         = $('#hidden_id').val();
         var title             = $('#card-header').text();
      if ($("#not_answered").prop('checked')){
            var call_status ='0'
            var voice_mail = $('#not_answer :selected').val();
              if (voice_mail=='') {
                alert('choose Voice Mail Action')
              }
          }
          $.ajax({
           type:'post', 
           url:"/patients/patient-enrollment-insert",
           data:{hidden_id:hidden_id,
              title:title,
              practice_id:practice_id,
              call_status:call_status,
              voice_mail:voice_mail
              },  // multiple data sent using ajax
          success: function (response) {
          }
        }); 
      });
// checklist

 $("#step_3").click(function(){

   var form_id  = $('#call_hidden_id').val();
   var hidden_id = $('#hidden_id').val();
   var title     = "Patient Enrollment checklist";//$('#card-header1').text();
   var template_id = $('#checklist_template_id').val(); 
   var module_id   =$('#checklist_module_id').val();
   var component_id =$('#checklist_component_id').val();
   var template_type ='questionnaire';
   var questionnaire =[];
   /*if($('#questionnaire1').is(":checked")){
        var q = $('#questionnaire1').val();//document.getElementsByName('enroll_module[]');
        questionnaire.push(q);
    }
    if($('#questionnaire2').is(":checked")){
        var q = $('#questionnaire2').val();//document.getElementsByName('enroll_module[]');
        questionnaire.push(q);
    }
    if($('#questionnaire3').is(":checked")){
        var q = $('#questionnaire3').val();//document.getElementsByName('enroll_module[]');
        questionnaire.push(q);
    }
    if($('#questionnaire4').is(":checked")){
        var q = $('#questionnaire4').val();//document.getElementsByName('enroll_module[]');
        questionnaire.push(q);
    }*/
   
   
   var _token = "{{ csrf_token() }}";
   var step3Data =   "component_id="+component_id+"&"+"template_id="+template_id+"&"+"module_id="+module_id+"&"+"_token="+_token+"&"+"form_id="+form_id+"&"+ "hidden_id="+ hidden_id+"&"+ "template_type="+ template_type+"&"+ "title="+ title+"&"+$('form').serialize();
   $.ajax({
           type:'post', 
           url:"/patients/patient-enrollment-insert",
           data:step3Data,  // multiple data sent using ajax
          success: function (response) {
              //alert(response);
              //    3(step);
          $('#call_hidden_id_finalize').val(response.trim());
            if(response!=''){
              $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-success col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Successfully Inserted  </div>');
              next_step(5);
            }else{ 
             $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-danger col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Not Inserted  </div>');
            }
            
          }
        }); 
 });
//finalize checklist

$('#step_5').click(function(){
   var form_id  = $('#call_hidden_id_finalize').val();
   var hidden_id = $('#hidden_id').val();
   var title     = "Patient Enrollment Finalization Checklist Update";//$('#card-header2').text();
   var template_id = $('#finilization_template_id').val(); 
   var module_id   =$('#finilization_module_id').val();
   var component_id =$('#finilization_component_id').val();
   var template_type ='questionnaire';
   

   var stage_id = $("#finilization_stage_id").val();
   var _token = "{{ csrf_token() }}";
   var step4Data =  "stage_id="+stage_id+"&"+"template_id="+template_id+"&"+"module_id="+module_id+"&"+"component_id="+component_id+"&"+"_token="+_token+"&"+"form_id="+ form_id+"&"+ "hidden_id="+ hidden_id+"&"+ "template_type="+ template_type+"&"+ "title="+ title+"&"+$('form').serialize();
    //alert($('#finilization_module_id').val());
  $.ajax({
         type:'post', 
         url:"/patients/patient-enrollment-insert",
         data:step4Data,  // multiple data sent using ajax
        success: function (response) {
          // alert(response);
            $('#call_hidden_id').val(response);
            if(response!=''){
                //$("#fap").clone().appendTo("#aapding");
              $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-success col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Successfully Inserted  </div>');
              next_step(6);
            }else{ 
              $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-danger col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Not Inserted  </div>');
            }
            
        }
  }); 
});


$('#step_4').click(function(){
   var form_id  = $('#call_hidden_id_finalize').val();
   var hidden_id = $('#hidden_id').val();
   var title     = "Patient Enrollment Finalization Checklist";//$('#card-header2').text();
   var template_id = $('#finilization_template_id').val(); 
   var module_id   =$('#finilization_module_id').val();
   var component_id =$('#finilization_component_id').val();
   var template_type ='questionnaire';
   //var values = $("#Fquestionnaire").serializeArray();
    //alert(values);
  /* var questionnaire =[];
   if($('#questionnaire1').is(":checked")){
        var q = $('#questionnaire1').val();//document.getElementsByName('enroll_module[]');
        questionnaire.push(q);
    }
    if($('#questionnaire2').is(":checked")){
        var q = $('#questionnaire2').val();//document.getElementsByName('enroll_module[]');
        questionnaire.push(q);
    }
    if($('#questionnaire3').is(":checked")){
        var q = $('#questionnaire3').val();//document.getElementsByName('enroll_module[]');
        questionnaire.push(q);
    }
    if($('#questionnaire4').is(":checked")){
        var q = $('#questionnaire4').val();//document.getElementsByName('enroll_module[]');
        questionnaire.push(q);
    }*/

   var stage_id = $("#finilization_stage_id").val();
   var _token = "{{ csrf_token() }}";
   var step4Data =  "stage_id="+stage_id+"&"+"template_id="+template_id+"&"+"module_id="+module_id+"&"+"component_id="+component_id+"&"+"_token="+_token+"&"+"form_id="+ form_id+"&"+ "hidden_id="+ hidden_id+"&"+ "template_type="+ template_type+"&"+ "title="+ title+"&"+$('form').serialize();
    //alert($('#finilization_module_id').val());
  $.ajax({
         type:'post', 
         url:"/patients/patient-enrollment-insert",
         data:step4Data,  // multiple data sent using ajax
        success: function (response) {
          // alert(response);
            $('#call_hidden_id').val(response);
            if(response!=''){
                //$("#fap").clone().appendTo("#aapding");
              $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-success col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Successfully Inserted  </div>');
              next_step(6);
            }else{ 
              $('#success').delay(6000).fadeOut('slow').html('<div class="alert alert-danger col-ssm-12" >  <button class="close" data-dismiss="alert"></button> Data Not Inserted  </div>');
            }
            
        }
  }); 
});

//
function chabgeStepSix(val,id){
    //alert(val);
   // alert(id);
    $("#s"+id+""+val).prop("checked", true);
}
/*$(document).ready(function(){
    $('#sub input[type="radio"]').on('change', function() {
        
   var val = ($('input[type="radio"]:checked', '#sub').val()); 
  var id = ($('input:checked', '#sub').attr('id')); 
  alert(val);
  $("#s"+id+""+val).prop("checked", true);
});
   
});*/
</script>
<script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection