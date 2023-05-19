var populateForm = function (id, url) {
    $.get(url, id,
        //data,
        function (result) {
            // var address = $("form[name='family_patient_data_form'] #patient_address").val();
            for (var key in result) {
                form.dynamicFormPopulate(key, result[key]);
            }
        } 
    ).fail(function (result) {
        console.error("Population Error:", result);
    });
};

	


var onMonthlyService = function (formObj, fields, response) {
    if (response.status == 200) {
        $("form[name='monthlyservice'] .alert").show();
        $("form[name='monthlyservice']")[0].reset();
        $("#success1").show();
        $("#danger1").hide();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';
        $("#success1").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success1").hide(); nextStep("monthly_step_3"); }, 3000);
		var timer_paused = $("form[name='part_of_research_study_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		$(".last_time_spend").html(timer_paused);

    }
    else {
        $("form[name='monthlyservice'] .alert").show();
        $("#danger1").show();
        $("#success1").hide();
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';
        $("#danger1").html(txt);
        setTimeout(function () { $("#danger1").hide(); }, 3000);
    }
};



var onMonthlyServiceCall = function (formObj, fields, response) {
    // console.log("helo" + response.status);
    if (response.status == 200) {
        $("form[name='monthly_service_form'] .alert").show();
        $("form[name='monthly_service_form']")[0].reset();
        $("#success2").show();
        $("#danger2").hide();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';
        $("#success2").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success2").hide(); nextStep("monthly_step_3"); }, 3000);
		var timer_paused = $("form[name='part_of_research_study_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		$(".last_time_spend").html(timer_paused);
    }
    else {
        // alert("else");  
        $("form[name='monthly_service_form'] .alert").show();
        $("#danger2").show();
        $("#success2").hide();
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';
        $("#danger2").html(txt);
        setTimeout(function () { $("#danger2").hide(); }, 3000);
    }
};


var onMonthlyServiceWithin = function (formObj, fields, response) {
    if (response.status == 200) {
        $("form[name='monthly_within_guideline_form'] .alert").show();
        $("form[name='monthly_within_guideline_form']")[0].reset();
        $("#success3").show();
        $("#danger3").hide();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';
        $("#success3").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success3").hide(); nextStep("monthly_step_3"); }, 3000);
		var timer_paused = $("form[name='part_of_research_study_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		$(".last_time_spend").html(timer_paused);
    }
    else {
        $("form[name='monthly_within_guideline_form'] .alert").show();
        $("#success3").hide();
        $("#danger3").show();
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';
        $("#danger3").html(txt);
        setTimeout(function () { $("#danger3").hide(); }, 3000);
    }
};


var onMonthlyServiceOutOf = function (formObj, fields, response) {
    if (response.status == 200) {
        $("form[name='monthly_out_of_guidelines'] .alert").show();   
        $("form[name='monthly_out_of_guidelines']")[0].reset();
        $("#success4").show(); 
        $("#danger4").hide();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';
        $("#success4").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success4").hide(); nextStep("monthly_step_3");  }, 3000);
		var timer_paused = $("form[name='part_of_research_study_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		$(".last_time_spend").html(timer_paused);
    }
    else {
        $("form[name='monthly_out_of_guidelines'] .alert").show();
        $("#danger4").show();
        $("#success4").hide();
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';
        $("#danger4").html(txt);
        setTimeout(function () { $("#danger4").hide(); }, 3000);

    }
};


var nextStep = function (step) {
    setTimeout($('#' + step).click(), 30000); 
}

var getFormValues = function () {
    
    var review_data = $("#review_data").find('option:selected').val() || '';
    var review_data_value = $("#review_data").find('option:selected').text() || '';
    $("#summary_review").html(review_data_value);
    if (review_data == 1) {
        var contact_via = $("#contact_via").val();
        if (contact_via == 'text') {
            var text_contact_number = $("#text_contact_number").find('option:selected').text() || '';
            var content_title = $("#content_title").find('option:selected').text() || '';
            var content_area = $("#content_area").text() || '';
            $("#summary_text_contact").html(text_contact_number);
            $("#summary_text_template").html(content_title);
            $("#summary_text_template_content").html(content_area);

            $("#summary_not_recorded_text").show();
            $("#summary_not_recorded_call").hide();
            $("#summary_within_guidelines").hide();
            $("#summary_out_of_guidelines").hide();
        }

        if (contact_via == 'call') {
            var call_status = $("input[name='call_status']:checked").attr('radioLable') || '';
            $("#summary_call_status").html(call_status);
            if (call_status == 'Call Answered') {
                var content_name = $("#call_content_title").find('option:selected').text() || '';
                var call_script = $("#script").html() || '';
                $("#summary_content_name").html(content_name);
                $("#summary_call_template_content").html('Message: ' + call_script);
            } else {
                var answer = $("#answer").find('option:selected').text() || '';
                $("#summary_content_name").html(answer);
                $("#summary_call_template_content").html('');
            }

            $("#summary_not_recorded_text").hide();
            $("#summary_not_recorded_call").show();
            $("#summary_within_guidelines").hide();
            $("#summary_out_of_guidelines").hide();
        }

    }

    if (review_data == 2) {
        // var leave_message_in_emr = ("#leave_message_in_emr").val() || '';
        var within_guidelines_contact_number = $("#within_guidelines_contact_number").find('option:selected').text() || '';
        var within_guideline_content_title = $("#within_guideline_content_title").find('option:selected').text() || '';
        var within_guideline_content_area_msg = $("#content_area_msg").text() || '';
        alert(within_guideline_content_area_msg); 
        if ($('#summary_leave_message_in_emr').prop('checked')) {
            $("#summary_leave_message_in_emr").html("leaved message in emr");
        } else {
            $("#summary_leave_message_in_emr").html("not leaved message in emr");
        }

        $("#summary_within_guidelines_contact_number").html(within_guidelines_contact_number);
        $("#summary_within_guideline_content_title").html(within_guideline_content_title);
        $("#summary_within_guideline_content_area_msg").html(within_guideline_content_area_msg);

        $("#summary_not_recorded_text").hide();
        $("#summary_not_recorded_call").hide();
        $("#summary_within_guidelines").show();
        $("#summary_out_of_guidelines").hide(); 
    }

    	if (review_data == 3) {
        var patient_condition = $("input[name='patient_condition']:checked").val() || '';
       
        $("#summary_questionnaire").css("pointer-events", "none");
      
       if (patient_condition == 1) { 
 
         
             $('#emergency_range_div').html('');
            $("#summary_patient_condition").html('');
            $("#summary_patient_condition").text('In Call PCP Office Range');
            $('#summary_questionnaire').html('');
            $('#office_range_div').clone().appendTo('#summary_questionnaire');
            
        }
        else{
         
            $('#office_range_div').html(''); 
            $("#summary_patient_condition").html('');
            $("#summary_patient_condition").text('In Emergency Range');
            $('#summary_questionnaire').html('');
            $('#emergency_range_div').clone().appendTo('#summary_questionnaire');
        }
        $("#summary_not_recorded_text").hide();
        $("#summary_not_recorded_call").hide(); 
        $("#summary_within_guidelines").hide();
        $("#summary_out_of_guidelines").show();  
    } 
};

var onResult = function (form, fields, response, error) {

    if (error) {
    }
    else {
        window.location.href = response.data.redirect;
    }
};

var init = function () {
    // alert('hehehehehe');
  //  console.log("RPM Monthly Monitoring inside init");
    var patient_id = $("#hidden_id").val();
    var module_id = $("input[name='module_id']").val();
    var year = (new Date).getFullYear();
    var month = (new Date).getMonth() + 1; //add +1 for current mnth
    util.getPatientStatus(patient_id, module_id);  
    util.getPatientCareplanNotes(patient_id, module_id);
    util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
    $("form[name='monthly_within_guideline_form'] #within_guideline_content_title").on('change', function() {
       
        util.getCallScriptsById($("form[name='monthly_within_guideline_form'] #within_guideline_content_title").val(), "#trial", "form[name='monthly_within_guideline_form'] input[name='template_type_id']", "form[name='within_guideline_content_title'] input[name='content_title']");
          
    });

    $('form[name="personal_notes_form"] .submit-personal-notes').on('click', function (e) {
        $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
        $("form[name='personal_notes_form'] input[name='start_time']").val(timer_start);
        $("form[name='personal_notes_form'] input[name='end_time']").val(timer_paused);
        // $("#timer_start").val(timer_paused);
        $("#timer_end").val(timer_paused);
        $("#time-container").val(AppStopwatch.startClock);
        form.ajaxSubmit('personal_notes_form', patientEnrollment.onPersonalNotes);
    });

    $('form[name="part_of_research_study_form"] .submit-part-of-research-study').on('click', function () {
       // alert('kasur');
        $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
        $("form[name='part_of_research_study_form'] input[name='start_time']").val(timer_start);
        $("form[name='part_of_research_study_form'] [name='end_time']").val(timer_paused);
        // $("#timer_start").val(timer_paused);
        $("#timer_end").val(timer_paused);
        $("#time-container").val(AppStopwatch.startClock);
        form.ajaxSubmit('part_of_research_study_form', patientEnrollment.onPartOfResearchStudy);
    });

    $('form[name="patient_threshold_form"] .submit-patient-threshold').on('click', function () {
       // alert('bekasur');
        $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val(); 
        var timer_paused = $("#time-container").text();
        $("form[name='patient_threshold_form'] input[name='start_time']").val(timer_start);
        $("form[name='patient_threshold_form'] [name='end_time']").val(timer_paused);
        // $("#timer_start").val(timer_paused);
        $("#timer_end").val(timer_paused);
        $("#time-container").val(AppStopwatch.startClock);
        form.ajaxSubmit('patient_threshold_form', patientEnrollment.onPatientThreshold);
    });

    form.ajaxForm("monthlyservice", onMonthlyService, function () {
        $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='monthlyservice'] input[name='start_time']").val(timer_start);
		$("form[name='monthlyservice'] input[name='end_time']").val(timer_paused);
        // $("input[name='start_time']").val(timer_start);
        $("input[name='end_time']").val(timer_paused);
        $("#time-container").val(AppStopwatch.startClock);
        return true;
    });

    form.ajaxForm("monthly_service_form", onMonthlyServiceCall, function () {
        $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='monthly_service_form'] input[name='start_time']").val(timer_start);
		$("form[name='monthly_service_form'] input[name='end_time']").val(timer_paused);
        // $("input[name='start_time']").val(timer_start);
        $("input[name='end_time']").val(timer_paused);
        $("#time-container").val(AppStopwatch.startClock);
        return true;
    });

    form.ajaxForm("monthly_within_guideline_form", onMonthlyServiceWithin, function () {
        $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='monthly_within_guideline_form'] input[name='start_time']").val(timer_start);
		$("form[name='monthly_within_guideline_form'] input[name='end_time']").val(timer_paused);
        // $("input[name='start_time']").val(timer_start);
        $("input[name='end_time']").val(timer_paused);
        $("#time-container").val(AppStopwatch.startClock);
        return true;
    });

    form.ajaxForm("monthly_out_of_guidelines", onMonthlyServiceOutOf, function () {
        $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='monthly_out_of_guidelines'] input[name='start_time']").val(timer_start);
		$("form[name='monthly_out_of_guidelines'] input[name='end_time']").val(timer_paused);
        // $("input[name='start_time']").val(timer_start);
        $("input[name='end_time']").val(timer_paused);
        $("#time-container").val(AppStopwatch.startClock);
        return true;
    });

    $("#content-template").hide();
    $("#monthly_services_nxt_txt_date_div").hide();
    // $("#office_range_div").hide();
    // $("#emergency_range_div").hide();
    $("#summary").hide();
    $("#contact_via").val("");
    $("#summary_not_recorded_text").hide();
    $("#summary_not_recorded_call").hide();
    $("#summary_within_guidelines").hide();
    $("#summary_out_of_guidelines").hide();

    $("#start").hide();
    $("#pause").show();
    $("#time-container").val(AppStopwatch.startClock);
    $("#monthly_services_nxt_txt_date_btn").click(function () {
        $("#monthly_services_nxt_txt_date_div").show();
    });


    $("#review_data").change(function () {
        if ($("#review_data").val() == 1) {
            
            $("#buttons").show();
            $("#within_guidelines").hide();
            $("#out_of_guidelines").hide();
            $("#question").hide();
            $("#questionnaireButtons").hide();
            $("#record_details").hide();
            $("#summary").hide();
            // var c1 = $('#trialtext').html();    
            // alert($(c1).text()); 
            // $('#content_area').val($(c1).text());  
        }
        if ($("#review_data").val() == 2) {
            var step = 'monthly_step_2';
            nextStep(step);
            $(".step2").trigger('click');
            $("#within_guidelines").show();
            $("#out_of_guidelines").hide();
            $("#buttons").hide();
            $("#content-template").hide();
            $("#call_section").hide();
            $("#question").hide();
            $("#questionnaireButtons").hide();
            $("#record_details").hide();
            $("#summary").hide();
            var c=$('#trial').html();
            // alert($(c).text());
            $("#content_area_msg").val($(c).text());


        }
        if ($("#review_data").val() == 3) {
            // $.ajax({
            //     type: 'get',
            //     url: '/rpm/getQuestionnaire',
            //     success: function (response) {
            //     }
            // });
            // $(".step2").trigger('click');
            $("#questionnaireButtons").show();
            $("#question").show();
            $("#buttons").hide();
            $("#content-template").hide();
            $("#call_section").hide();
            $("#within_guidelines").hide();
            $("#record_details").hide();
            $("#summary").hide();
        }
    });

    if ($("#answered").prop('checked')) {
        var call_status = '1'
        var enrollment_response = $('input[name=call_radio]:checked', '#action').val();
        if (enrollment_response == '2') {
            var date = $("#date").val();
            var time = $('#time').val();
        }
    }

    $("#text").click(function () {
        var step = 'monthly_step_2';
        nextStep(step);
        $(".step2").trigger('click');
        $("#content-template").show();
        $("#template_type_id").val(2);
        $("#call_section").hide();
        $("#summary_not_recorded_text").show();
        $("#summary_not_recorded_call").hide();
        $("#contact_via").val("text");
        // getTextScripts('content_title');
    });

    $("#calling").click(function () {
        var step = 'monthly_step_2';
        nextStep(step);
        $(".step2").trigger('click');
        $("#call_section").show();
        $("#content-template").hide();
        $("#summary_not_recorded_text").hide();
        $("#summary_not_recorded_call").show();
        $("#contact_via").val("call");
    });

    $("#not_answered").click(function () {
        $("#content_area").text('');
        $("#content_area_msg").text('');
        $("#answer").show();
        $("#call_not_answered_save").show();
        $("#call_scripts").hide();
    });

    $("#answered").click(function () {
        $("#answer").hide();
        $("#call_scripts").show();
        $("#call_not_answered_save").hide();
    });

    $("#call_content_title").change(function () {
        var content_title = $(this).val();
        //$("#temp_id").val(content_title);
        $.ajax({
            type: 'get',
            url: '/rpm/getContente',
            data: {
                _token: '{!! csrf_token() !!}', content_title: content_title
            },
            success: function (response) {
                var text = jQuery.parseJSON(response[0].content).message;
                $('#script').html(text);
                $('#call_content_area').val(text);
            }
        });
    });

    $(".contact_number").change(function () {
        var contact_number = $(this).val();
        $('#phone').val(contact_number);
    });

    $(".content_title").change(function () {
        var content_title = $(this).val();
        //$("#temp_id").val(content_title);
        $.ajax({
            type: 'get',
            url: '/rpm/getContente',
            data: {
                _token: '{!! csrf_token() !!}', content_title: content_title
            },
            success: function (response) {
                var text = jQuery.parseJSON(response[0].content).message;
                $('.content_area').html($(text).text());
            }
        });
    });



    $("#office_range").click(function () {
        var step = 'monthly_step_2';
        nextStep(step);
        $(".step2").trigger('click');
        $("#out_of_guidelines").show();
        $("#questionnaireButtons").show();
        $("#office_range_div").show();
        $("#emergency_range_div").hide();
        //  $('#emergency_range_div').html('');
        $("#patient-condition").val(1);
    });

    $("#emergency_range").click(function () {
        var step = 'monthly_step_2';
        nextStep(step);
        $(".step2").trigger('click');
        $("#out_of_guidelines").show();
        $("#questionnaireButtons").show();
        $("#office_range_div").hide();
        // $('#office_range_div').html('');
        $("#emergency_range_div").show(); 
        $("#patient-condition").val(2);
    });
    $("form[name='monthlyservice'] .content_title option:last").attr("selected", "selected").change();
    $("form[name='monthly_service_form'] #call_content_title option:last").attr("selected", "selected").change();
    $("form[name='monthly_within_guideline_form'] #within_guideline_content_title option:last").attr("selected", "selected").change();
    // $("form[name='monthly_out_of_guidelines'] #within_guideline_content_title option:last").attr("selected", "selected").change();


    $("#save_office_pcp").click(function () {
        $('#record_details').show();
    });

    $("#save_emergency_pcp").click(function () {
        $('#record_details').show();
    });

    $("#end_call, #call_not_answered_save, #save_within_guideline, #send").click(function () {
        var step = 'monthly_step_2';
        $("#monthly_services_nxt_txt_date_div").hide();
    });

    $(".summarize-details").click(function () {   
        alert("summerize changes");     
	
        //  $(".step3").trigger('click');
        getFormValues();
        $("#summary").show();
    });

    $("#completebutton").click(function(){
        $("#msgdiv").show();
        
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step3 Added Successfully!</strong></div>';
        $("#msgdiv").html(txt);
        setTimeout(function () { $("#msgdiv").hide(); }, 3000);
    });

    

    $("form[name='rpm_medications_form'] #medication_med_id").on('change', function () {
        var med_id = $("#medication_med_id").val();
        if (med_id == 'other') {
            // alert('yes');
            $('.med_id').removeClass("col-md-6").addClass("col-md-4");
            $('.description').removeClass("col-md-6").addClass("col-md-4");
            $("#med_name").show();
            $("#medication_description").val("");
            $("#medication_purpose").val("");
            $("#medication_strength").val("");
            $("#medication_dosage").val("");
            $("#medication_route").val("");
            $("#medication_frequency").val("");
            $("#duration").val("");
            $("#medication_drug_reaction").val("");
            $("#medication_pharmacogenetic_test").val("");

        } else {
            $('.med_id').removeClass("col-md-4").addClass("col-md-6");
            $('.description').removeClass("col-md-4").addClass("col-md-6");
            $("#med_name").hide();
            $("#medication_description").val("");
            $("#medication_purpose").val("");
            $("#medication_strength").val("");
            $("#medication_dosage").val("");
            $("#medication_route").val("");
            $("#medication_frequency").val("");
            $("#duration").val("");
            $("#medication_drug_reaction").val("");
            $("#medication_pharmacogenetic_test").val("");

            var sPageURL = window.location.pathname;
            parts = sPageURL.split("/"),
            patientId = parts[parts.length - 1];
            id = patientId;
            url = '/rpm/get-selected-medications_patient-by-id/' + patientId + '/' + med_id + '/selectedmedicationspatient';
            populateForm(id, url);
            }
    });
    
    $('body').on('click', '.editMedicationsRpmPatient', function () {
        var id = $(this).data('id');
        url = '/rpm/get-all-medications_patient-by-id/' + id + '/medicationspatient';
    populateForm(id, url);
    });

    $('body').on('click', '.deleteMedicationsRpmPatient', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to delete this?")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/rpm/delete-medications_patient-by-id/' + id,
                // data: {"_token": "{{ csrf_token() }}",
                data: { "id": id },
                success: function (response) {
                    renderMedicationsTable();

                },
            });
        } else {
            return false;
        }
    });

};

window.rpmMonthlyServices = {
    init: init,
    nextStep: nextStep,
    getFormValues: getFormValues 

};