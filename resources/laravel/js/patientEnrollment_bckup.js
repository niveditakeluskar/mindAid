/**
 * 
 */
const URL_POPULATE = "/patients/ajax/populate";

/**
 * Populate the form of the given patient
 *
 * 
 */
var populateForm = function(data, url) {    
		
	$.get(
        url,
        data,
        function(result) {
			console.log(result);
			 for (var key in result) {					
				form.dynamicFormPopulate(key, result[key]);
			}
		}
		).fail(function(result) {
        console.error("Population Error:", result);
    });
};

var showSubmitMsg = function(formName, alertMsg, alertType) {
    if(alertType == "success"){
        $("form[name='text_form'] .alert").addClass("alert-success");
        $("form[name='text_form'] .alert").removeClass("alert-danger");
    } else if(alertType == "danger") {
        $("form[name='text_form'] .alert").addClass("alert-danger");
        $("form[name='text_form'] .alert").removeClass("alert-success");
    }

    $("form[name='"+formName+"'] .alert").html(alertMsg);
    $("form[name='"+formName+"'] .alert").show();
    var scrollPos =  $(".main-content").offset().top;
    $(window).scrollTop(scrollPos);
}

var onTextSave =  function(formObj, fields, response) {
    if (response.status == 200) {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Text data saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('text_form', text, 'success');
        // goToNextStep("ccm-hippa-icon-tab");
    } else {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Text data not saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('text_form', text, 'danger');
    }
};

var onEmailSave =  function(formObj, fields, response) {
    if (response.status == 200) {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Email data saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('email_form', text, 'success');
    } else {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Email data not saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('email_form', text, 'danger');
    }
};

var onSaveCallSatus =  function(formObj, fields, response) {
    if (response.status == 200) {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Call data saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('call_status_form', text, 'success');

        var call_status = $("form[name='call_status_form'] input[name='call_status']:checked").val();
        var call_continue_status = $("form[name='call_status_form'] input[name='call_continue_status']:checked").val();
        if( (call_status == '1') && (call_continue_status== '1') ) {
            setTimeout(function () { goToNextStep("s2"); }, 3000);
        } else if((call_status == '1') && (call_continue_status== '0')) {
            $("#s7").removeClass("disabled");
            $("#step-7 #enrollment_messages").text("Call Ended..!!");
            setTimeout(function () { goToNextStep("s7"); }, 3000);
        } else if( call_status == '2' ) {
            $("#s7").removeClass("disabled");
            $("#step-7 #enrollment_messages").text("Call Not Answered..!!");
            setTimeout(function () { goToNextStep("s7"); }, 3000);
        } 
    } else {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Call data not saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('call_status_form', text, 'danger');
    }
};

var onSaveEnrollmentSatus =  function(formObj, fields, response) {
    if (response.status == 200) {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment status saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('enrollment_status_form', text, 'success');

        var enrol_status = $("form[name='enrollment_status_form'] input[name='enrol_status']:checked").val();
        if( enrol_status == '1' ) {
            setTimeout(function () { goToNextStep("s3"); }, 3000);
        } else if( enrol_status == '2' ) {
            var call_back_date = $("form[name='enrollment_status_form'] input[name='call_back_date']").val(); 
            var call_back_time = $("form[name='enrollment_status_form'] input[name='call_back_time']").val(); 
            $("#s7").removeClass("disabled");
            $("#step-7 #enrollment_messages").text("Call Back Date-"+call_back_date+" Time-"+call_back_time);
            setTimeout(function () { goToNextStep("s7"); }, 3000);
        } else if( enrol_status == '3' ) {
            $("#s7").removeClass("disabled");
            $("#step-7 #enrollment_messages").text("Refused to enroll..!!");
            setTimeout(function () { goToNextStep("s7"); }, 3000);
        } 
    } else {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment status not saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('enrollment_status_form', text, 'danger');
    }
};

var onSaveEnrollServicesSatus =  function(formObj, fields, response) {
    if (response.status == 200) {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enroll services saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('enroll_services_form', text, 'success');
        setTimeout(function () { goToNextStep("s4"); }, 3000);
    } else {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enroll services not saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('enroll_services_form', text, 'danger');
    }
};

var onSaveChecklist =  function(formObj, fields, response) {
    if (response.status == 200) {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment checklist saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('checklist_form', text, 'success');
        setTimeout(function () { goToNextStep("s5"); }, 3000);
    } else {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment checklist not saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('checklist_form', text, 'danger');
    }
};

var onSaveFinalizationChecklistStatus =  function(formObj, fields, response) {
    if (response.status == 200) {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment finalization checklist saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('finalization_checklist_form', text, 'success');
        setTimeout(function () { goToNextStep("s6"); }, 3000);
    } else {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment finalization checklist not saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('finalization_checklist_form', text, 'danger');
    }
};

var onSaveChecklistSatus =  function(formObj, fields, response) {
    if (response.status == 200) {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment finalization checklist saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('checklist_status_form', text, 'success');

        $("#s7").removeClass("disabled");
        $("#step-7 #enrollment_messages").text("Patient Enrollment Completed..!!");
        setTimeout(function () { goToNextStep("s7"); }, 3000);
    } else {
        var text = '<button type="button" class="close" data-dismiss="alert">x</button><strong>Enrollment finalization checklist not saved successfully! </strong><span id="text"></span>';
        showSubmitMsg('checklist_status_form', text, 'danger');
    }
};

var onSubmit = function(name) {
  //$(".tab-error").removeClass("tab-error");
  $("form[name="+name+"] input[name='timer']").val("12");
  return true;
};

function goToNextStep(id) {
    $("#"+id).removeClass("disabled");
    $('#'+id).click();
    // setTimeout($('#' + id).click(),30000);
}

var init = function() {	
    util.stepWizard('tsf-wizard-1');
    util.getToDoListData($("#hidden_id").val(), $("#page_module_id").val());
    $("#main-text-btn").click(function(){
        $("#text_step_div").show();
        $("#email_step_div").hide();
        $("#call_step_div").hide();
    });

    $("#main-email-btn").click(function(){
        $("#text_step_div").hide();
        $("#email_step_div").show();
        $("#call_step_div").hide();
    });

    $("#main-call-btn").click(function(){
        $("#text_step_div").hide();
        $("#email_step_div").hide();
        $("#call_step_div").show();
        // util.getQuestionnaireScript( $("form[name='checklist_form'] input[name='module_id']").val(), $("form[name='checklist_form'] input[name='component_id']").val(), $("form[name='checklist_form'] input[name='stage_id']").val(), $("form[name='checklist_form'] input[name='step_id']").val(), "#checklist_questionnaire_div");
    });
    //select default value for text script(last entered script)
    $("form[name='text_form'] #text_template_id option:last").attr("selected", "selected").change();
    util.getCallScriptsById($("form[name='text_form'] #text_template_id").val(), '#templatearea_sms', 'form[name="text_form"] input[name="template_type_id"]', '#text_content_title');

    $("form[name='email_form'] #email_template_id option:last").attr("selected", "selected").change();
    util.getCallScriptsById($("form[name='email_form'] #email_template_id").val(), '#email_template_area', 'form[name="email_form"] input[name="template_type_id"]', '#email_content_title', 'form[name="email_form"] #subject');

    $("form[name='enrollment_status_form'] #enrollment_status_template_id option:last").attr("selected", "selected").change();
    util.getCallScriptsById($("form[name='enrollment_status_form'] #enrollment_status_template_id").val(), '.enrollment_status_script', 'form[name="enrollment_status_form"] input[name="template_type_id"]', 'form[name="enrollment_status_form"] input[name="content_title"]');

    form.ajaxForm("text_form", onTextSave, function(){
        $("#time-container").val(AppStopwatch.pauseClock);		
        var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
        $("form[name='text_form] input[name='start_time']").val(timer_start); 
        $("form[name='text_form] input[name='end_time']").val(timer_paused);
        return true; 
    });
	
	form.ajaxForm("email_form", onEmailSave, function(){
		$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='email_form] input[name='start_time']").val(timer_start); 
		$("form[name='email_form] input[name='end_time']").val(timer_paused); 		
		$("#time-container").val(AppStopwatch.startClock);
		return true; 
	});
	
	form.ajaxForm("call_status_form", onSaveCallSatus, function(){
		$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='call_status_form] input[name='start_time']").val(timer_start); 
		$("form[name='call_status_form] input[name='end_time']").val(timer_paused); 		
		$("#time-container").val(AppStopwatch.startClock);
		return true; 
	});
	
	form.ajaxForm("enrollment_status_form", onSaveEnrollmentSatus, function(){
		$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='enrollment_status_form] input[name='start_time']").val(timer_start); 
		$("form[name='enrollment_status_form] input[name='end_time']").val(timer_paused); 		
		$("#time-container").val(AppStopwatch.startClock);
		return true; 
	});
	
	form.ajaxForm("enroll_services_form", onSaveEnrollServicesSatus, function(){
		$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='enroll_services_form] input[name='start_time']").val(timer_start); 
		$("form[name='enroll_services_form] input[name='end_time']").val(timer_paused); 		
		$("#time-container").val(AppStopwatch.startClock);
		return true; 
	});
	
	form.ajaxForm("checklist_form", onSaveChecklist, function(){
		$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='checklist_form] input[name='start_time']").val(timer_start); 
		$("form[name='checklist_form] input[name='end_time']").val(timer_paused); 		
		$("#time-container").val(AppStopwatch.startClock);
		return true; 
	});
	
	form.ajaxForm("finalization_checklist_form", onSaveFinalizationChecklistStatus, function(){
		$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='finalization_checklist_form] input[name='start_time']").val(timer_start); 
		$("form[name='finalization_checklist_form] input[name='end_time']").val(timer_paused); 		
		$("#time-container").val(AppStopwatch.startClock);
		return true; 
	});
	
	form.ajaxForm("checklist_status_form", onSaveChecklistSatus, function(){
		$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
        var timer_paused = $("#time-container").text();
		$("form[name='checklist_status_form] input[name='start_time']").val(timer_start); 
		$("form[name='checklist_status_form] input[name='end_time']").val(timer_paused); 		
		$("#time-container").val(AppStopwatch.startClock);
		return true; 
	});
	
    //text
    $("form[name='text_form'] #text_template_id").change(function(){
        util.getCallScriptsById($(this).val(), '#templatearea_sms', 'form[name="text_form"] input[name="template_type_id"]', '#text_content_title');
    });
    
    //email
    $("form[name='email_form'] #email_template_id").change(function(){
        util.getCallScriptsById($(this).val(), '#email_template_area', 'form[name="email_form"] input[name="template_type_id"]', '#email_content_title',  'form[name="email_form"] #subject');
    });

    //call.step-1
    $("form[name='call_status_form'] #call_scripts_select").change(function(){
        util.getCallScriptsById($(this).val(), '.call_answer_template', 'form[name="call_status_form"] input[name="template_type_id"]', 'form[name="call_status_form"] input[name="content_title"]');
    });

    $("form[name='call_status_form'] #call_not_answer_template_id").change(function(){
        util.getCallScriptsById($(this).val(), '#call_not_answer_content_area', 'form[name="call_status_form"] input[name="template_type_id"]', 'form[name="call_status_form"] input[name="content_title"]');
    });

    $("form[name='enrollment_status_form'] #enrollment_status_template_id").change(function(){
        util.getCallScriptsById($("form[name='enrollment_status_form'] #enrollment_status_template_id").val(), '.enrollment_status_script', 'form[name="enrollment_status_form"] input[name="template_type_id"]', 'form[name="enrollment_status_form"] input[name="content_title"]');
    });

    $("form[name='call_status_form'] input[name='call_status']").click(function(){
        var checked_call_option = $("form[name='call_status_form'] input[name$='call_status']:checked").val();
        if(checked_call_option=='1'){
            $('.invalid-feedback').html('');
            $("form[name='call_status_form'] #callNotAnswer").hide();
            $("form[name='call_status_form'] #callAnswer").show();
            $("form[name='call_status_form'] #call_scripts_select option:last").attr("selected", "selected").change();
            $("form[name='call_status_form'] #call-save-button").html('<button type="submit" class="btn  btn-primary m-1" id="save-callstatus">Next</button>');
            $("form[name='call_status_form'] #call_action_script").val($( "form[name='call_status_form'] input[name='call_action_script'] option:selected" ).text());
            util.getCallScriptsById($("form[name='call_status_form'] #call_scripts_select").val(), '.call_answer_template');
            $("form[name='call_status_form'] input[name='content_title']").val($("form[name='call_status_form'] #call_scripts_select option:selected").text());
        } else if(checked_call_option=='2'){
            $('.invalid-feedback').html('');
            $("form[name='call_status_form'] #callNotAnswer").show(); 
            $("form[name='call_status_form'] #callAnswer").hide();
            $("form[name='call_status_form'] #call_not_answer_template_id option:last").attr("selected", "selected").change();
            $("form[name='call_status_form'] #call-save-button").html('<button type="submit" class="btn btn-primary m-1 call_status_submit" id="save_schedule_call">Send Text Message</button>');
            $("form[name='call_status_form'] #call_action_script").val($( "form[name='call_status_form'] input[name='content_title'] option:selected" ).text());
            util.getCallScriptsById($("form[name='call_status_form'] #call_not_answer_template_id").val(), '#call_not_answer_content_area');
            $("form[name='call_status_form'] input[name='content_title']").val($("form[name='call_status_form'] #call_not_answer_template_id option:selected").text());
        }
    }); 

    $("form[name='enrollment_status_form'] input[name='enrol_status']").click(function(){
        var enrol_status_option = $("form[name='enrollment_status_form'] input[name$='enrol_status']:checked").val();
        if(enrol_status_option=='1' || enrol_status_option=='3'){
            $('.invalid-feedback').html('');
            $("form[name='enrollment_status_form'] #date-time").hide();
        } else if(enrol_status_option=='2'){
            $('.invalid-feedback').html('');
            $("form[name='enrollment_status_form'] #date-time").show();
        }
    }); 
	
	var patientId = $("#hidden_id").val();
    // var url = URL_POPULATE+"/"+patientId;
    populateForm(patientId, URL_POPULATE);
};

// Module Export ---------------------------------------------------------------

// Export the module functions
window.patientEnrollment = {
    init               : init,

};
