<!-- <form name="monthlyservice"> -->
<div class="form-group">
    <div class="row mt-2 mb-4">
        <div class="col-md-12">    
            <div class="card">
                <div class="card-body card text-left">
                    <div class="row">
                        <div class="col-md-4 float-left">
                            <label for="Status">Review Data</label></b>
                            @selectreviewdata("review_data",["id" => "review_data", "class" => ""])
                        </div>

                    <!-- ss -->

                        <div class="col-md-4 text-center" id="buttons" style="display:none">
                            <button type="button" class="btn btn-primary btn-icon btn-lg m-1 mt-3" id="text">
                                <span class="ul-btn__icon"><i class="i-Letter-Open"></i></span>
                                <span class="ul-btn__text">Text</span> 
                            </button>
                           <!--  <input type="hidden" id="template_id" name="template_id"> -->

                            <button type="button" class="btn btn-primary btn-icon btn-lg m-1 mt-3" id="calling">
                                <span class="ul-btn__icon"><i class="i-Old-Telephone"></i></span>
                                <span class="ul-btn__text">Call</span>
                            </button>
                        </div>  
                        <div class="col-md-8 text-center" id="questionnaireButtons" style="display:none; margin-top: 22px;" [formGroup]="radioGroup">
                            <div class="row">
                                <label class="radio radio-primary col-md-4 offset-md-1">
                                    <input type="radio" name="patient_condition" value="1" formControlName="radio" id="office_range" data-toggle="modal">
                                    <span>Patient Condition - In Call PCP Office Range</span>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="radio radio-primary col-md-4 offset-md-1">
                                    <input type="radio" name="patient_condition" value="2" formControlName="radio" id="emergency_range" data-toggle="modal">
                                    <span>Patient Condition - In Emergency Range</span>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                 
    </div>                            
</div>
<!-- </form> -->
<!-- In Call PCP Office Range Modal-->
{{-- @include('Rpm::monthlyService.in-call-pcp-office-range') --}}
<!-- In Call PCP Office Range Modal-->

<!-- In Call PCP Emergency Range Modal-->
{{-- @include('Rpm::monthlyService.in-call-pcp-emergency-range') --}}
<!-- In Call PCP Emergency Range Modal-->

@section('page-js')
    <script>
		$(document).ready(function() {
            util.stepWizard('tsf-wizard-monthly-service');
			rpmMonthlyServices.init();
		});
        // var time = '{{$time}}';
        var time = "<?php echo ($time!='0') ? $time : '00:00:00'; ?>";
        var splitTime = time.split(":");
        const H = splitTime[0];
        const M = splitTime[1];
        const S = splitTime[2];
        console.log('time=='+time);
        console.log("H="+H+" M="+M+" S="+S);
        // $("#review_data").change(function() {
        //     var review_data = $(this).val();
        //     $('form[name="monthlyservice"]').trigger("reset");
        //     $(this).val(review_data); // cause form reseted; 
        //     if(review_data == '1') {
        //         $("#buttons").show();
        //         $("#within_guidelines").hide();
        //         $("#question").hide();
        //         $("#questionnaireButtons").hide();
        //         $("#record_details").hide();
        //     } else if(review_data == '2') {
        //         var step ='step-1';
        //         nextStep2(step);
        //         $("#within_guidelines").show();
        //         $("#buttons").hide();
        //         $("#content-template").hide();
        //         $("#call_section").hide(); 
        //         $("#question").hide();
        //         $("#questionnaireButtons").hide();
        //         $("#record_details").hide();
        //         // getTextScripts('within_guideline_content_title');
        //     }  else if(review_data == '3') {
        //         $("#questionnaireButtons").show();
        //         $("#question").show();
        //         $("#buttons").hide();
        //         $("#content-template").hide();
        //         $("#call_section").hide(); 
        //         $("#within_guidelines").hide();
        //         $("#record_details").hide();
        //     } else {
                
        //     }
        // });

    //     if ($("#answered").prop('checked')){
    //     var call_status ='1'
    //     var enrollment_response = $('input[name=call_radio]:checked', '#action').val();
    //     if(enrollment_response =='2'){
    //        var date = $("#date").val();
    //        var time = $('#time').val();
    //     }
    // }

    // $("#text").click(function(){
    //         var step ='step-1';
    //         nextStep2(step);
    //         $("#content-template").show();
    //         $("#template_type_id").val(2);
    //         $("#call_section").hide();
    //         $("#summary_not_recorded_text").show();
    //         $("#summary_not_recorded_call").hide();
    //         $("#contact_via").val("text");
    //         // getTextScripts('content_title');
    // });
    
    // // function getTextScripts(id) {
    // //     var template_type_id = 2;
    // //     $.ajax({
    // //         type: 'post',
    // //         url: '/rpm/fetch-email-contenet',
    // //         data: {template_type_id:template_type_id },
    // //         success: function (response) {
    // //                 document.getElementById(id).innerHTML=response; 
    // //         }
    // //     }); 
    // // }
    // $("#calling").click(function(){
    //         var step ='step-1';
    //         nextStep2(step);
    //         $("#call_section").show();
    //         $("#content-template").hide();
    //         $("#summary_not_recorded_text").hide();
    //         $("#summary_not_recorded_call").show();
    //         $("#contact_via").val("call");
    //     });

        // $("#not_answered").click(function(){
        //     $("#content_area").text('');
        //     $("#content_area_msg").text('');
        //     $("#answer").show();
        //     $("#call_not_answered_save").show();
        //     $("#call_scripts").hide();
        // }); 

        // $("#answered").click(function(){
        //     $("#answer").hide();
        //     $("#call_scripts").show();
        //     $("#call_not_answered_save").hide();
        // });  

        // $("#call_content_title").change(function() {
        //     var content_title = $(this).val();
        //     $("#temp_id").val(content_title);
        //     $.ajax({
        //         type: 'get',
        //         url: '/rpm/getContente',
        //         data: {
        //             _token: '{!! csrf_token() !!}', content_title:content_title
        //         },
        //         success: function (response) {
        //             var text = jQuery.parseJSON(response[0].content).message;
        //             $('#script').html(text);
        //             $modal = $('.script_modal');
        //             $modal.modal('show');
        //         }
        //     });
        // });
    
        // $(".contact_number").change(function() {
        //     var contact_number = $(this).val();
        //     $('#phone').val(contact_number);
        // });

        // $(".content_title").change(function() {
        //     var content_title =$(this).val();
        //     // alert(content_title);
        //     $("#temp_id").val(content_title);
        //     $.ajax({
        //         type: 'get',
        //         url: '/rpm/getContente',
        //         data: {
        //             _token: '{!! csrf_token() !!}', content_title:content_title
        //         },
        //         success: function (response) { 
        //             var text = jQuery.parseJSON(response[0].content).message;
        //             $('.content_area').html($(text).text());
        //         }
        //     });
        // });     
    
        // $(document).ready(function(){ 
        //     $("#content-template").hide();
        //     $("#monthly_services_nxt_txt_date_div").hide();
        //     $("#office_range_questionnaire_div").hide();
        //     $("#emergency_range_div").hide();
        //     $("#summary").hide();
        //     $("#contact_via").val("");
            
        //     $("#start").hide();
        //     $("#pause").show();
        //     $("#time-container").val(AppStopwatch.startClock);
        //     $("#monthly_services_nxt_txt_date_btn").click(function(){
        //         $("#monthly_services_nxt_txt_date_div").show();
        //     });
        // });
    
        // $("#office_range").click(function(){
        //     $("#office_range_div").show();
        //     $("#emergency_range_div").hide();
        // });  
    
        // $("#emergency_range").click(function(){
        //     $("#office_range_div").hide();
        //     $("#emergency_range_div").show();
        // });  

        // $("#save_office_pcp").click(function(){
        //     $('#record_details').show();
        // });  
    
        // $("#save_emergency_pcp").click(function(){
        //     $('#record_details').show();
        // });  

        // $("#end_call, #call_not_answered_save, #save_within_guideline, #send").click(function(){
        //     var step ='step-2';
        //     $("#monthly_services_nxt_txt_date_div").hide();
        // });

        // function nextStep2(step){
        //     getFormValues();
        //     var pageURL = $(location).attr("href");
        //     var str = pageURL.substr(0,pageURL.indexOf('#'));
        //     window.location=str+""+'#step-2';
        // }

        // function nextStep3(step){
        //     getFormValues();
        //     var pageURL = $(location).attr("href");
        //     var str = pageURL.substr(0,pageURL.indexOf('#'));
        //     window.location=str+""+'#step-3';
        // }


        // var getFormValues = function () {
        //     var review_data = $("#review_data").val() || '';
        //     var review_data_value = $("#review_data").find('option:selected').text() || '';
        //     var contact_number = $("#contact_number").val() || '';
        //     var content_title_id = $("#content_title").val() || '';
        //     var content_title = $("#content_title").find('option:selected').text() || '';
        //     var content_area = $("#content_area").text() || '';
        //     var answer = $("#answer").find('option:selected').text() || '';
        //     var monthly_services_nxt_txt_date = $("#monthly_services_nxt_txt_date").val() || '';
            
        //     //call selected
        //     var call_radio = $("input[name='call_radio']:checked").attr('radioLable') || '';
        //     var content_name = $("#content_name").find('option:selected').text() || '';
        //     var call_script = $("#script").html() || '';


        //     var values =  'review_data='+review_data+'  contact_number='+contact_number+ '  content_title_id='+content_title_id+'   content_title='+content_title+
        //     '  content_area='+content_area+'  answer=='+answer;
        //     // alert(values);

        //     //if Review Data: Not Recorder-text selected 
        //     $("#summary_review").html(review_data_value);
        //     $("#summary_text_contact").html(contact_number);
        //     $("#summary_text_template").html(content_title);
        //     $("#summary_text_template_content").html(content_area);
        //     if(monthly_services_nxt_txt_date != ''){
        //         $("#summary_monthly_services_nxt_txt_date").html("Next Text Scheduled date: "+monthly_services_nxt_txt_date);
        //     }

        //     //if Review Data: Not Recorder-call selected 
        //     $("#summary_call_status").html(call_radio);
        //     if(call_radio == 'Call Answered') {
        //         $("#summary_content_name").html(content_name);
        //         $("#summary_call_template_content").html('Message: '+call_script);
        //     } else {
        //         $("#summary_content_name").html(answer);
        //         $("#summary_call_template_content").html('');
        //     }
        // };

        // function save(){
        //     stop_timer();
        //     $( 'form[name="monthlyservice"]' ).submit();
        //     form.ajaxForm("monthlyservice", onMonthlyService);
        // //     formData = $('form[name="monthlyservice"]').serialize();
        // // //    alert(formData);
        // // // alert($('#phone_primary').val());
        // //     $.ajax({
        // //         type: 'post',
        // //         url: '/rpm/saveMonthlyService',
        // //         data: formData,
        // //         /*dataType : 'json',*/
        // //         success: function (response) {
        // //             // response.trim();
        // //             // alert(response);
        // //             /*if(response == '        Device Training Completed'){
        // //                 toastr.success(response);
        // //             }  */         
        // //         }
        // //     });
        // }

        // function stop_timer() {
        //     $("#time-container").val(AppStopwatch.stopClock);
        //     var end_time = $('#time-container').text();
        //     end_time = end_time.replace(/\s/g, '');
        //     $("#end_time").val(end_time);
        // }
        // var onMonthlyService = function(formObj, fields, response) {
        //     alert('called');
        //     if (response.status == 200) {
        //         // notify.success("Monthly Services added successfully!");
        //         alert("Monthly Services added successfully!");
        //     } else {
        //         console.error(response);
        //         // notify.danger("Unable to add Monthly Services!");
        //         alert("Unable to add Monthly Services!");
        //     }
        // };
    </script>
    <script src="{{ asset('assets/js/timer.js') }}"></script>
@endsection