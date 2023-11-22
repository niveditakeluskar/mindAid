 var onPatientTraningInfo = function (formObj, fields, response) {
	if (response.status == 200) {
		$("form[name='patient_traning_info_and_checklist_form'] .alert").show();
		$("#newsuccess1").show();
        $("#newdanger1").hide();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step1 Added Successfully!</strong></div>';
        $("#newsuccess1").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () { goToNextStep("step_softwear"); }, 3000);
		var timer_paused = $("form[name='patient_traning_info_and_checklist_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		$(".last_time_spend").html(timer_paused);
	}
	else{
		$("form[name='patient_traning_info_and_checklist_form'] .alert").show();
        $("#newsuccess1").hide();
        $("#newdanger1").show();
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';
        $("#newdanger1").html(txt);
        setTimeout(function () {    $("#newdanger1").hide();  }, 3000); 
	}
};
var onSoftwearUsage = function (formObj, fields, response) {
	if (response.status == 200) {
		$("form[name='software_usage_instruction_form'] .alert").show();
		$("#newsuccess2").show();
        $("#newdanger2").hide();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step2 Added Successfully!</strong></div>';
        $("#newsuccess2").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () { goToNextStep("step_device_traning"); }, 3000); 
		var timer_paused = $("form[name='software_usage_instruction_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		$(".last_time_spend").html(timer_paused);
	}
	else{
		$("form[name='software_usage_instruction_form'] .alert").show();
        $("#newsuccess2").hide();
        $("#newdanger2").show();
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';
        $("#newdanger2").html(txt); 
        setTimeout(function () {    $("#newdanger2").hide();  }, 3000);
	}
};
var onDeviceTraning = function (formObj, fields, response) {
	if (response.status == 200) {
		$("form[name='device_traning_form'] .alert").show();
        $("#newsuccess3").show();
        $("#newdanger3").hide();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Step3 Added Successfully!</strong></div>';
        $("#newsuccess3").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
        setTimeout(function () { $("#newsuccess3").hide();  }, 3000); 
		var timer_paused = $("form[name='device_traning_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		$(".last_time_spend").html(timer_paused);

		//setTimeout(function () { goToNextStep("review-patient-tab"); }, 3000);
	}
	else{
        $("form[name='device_traning_form'] .alert").show();
        $("#newsuccess3").hide();
        $("#newdanger3").show();
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all required fields!</strong></div>';
        $("#newdanger3").html(txt);
        setTimeout(function () {    $("#newdanger3").hide();  }, 3000);
    }
};

var onResult = function (form, fields, response, error) {

	if (error) {
	}
	else {
		window.location.href = response.data.redirect;
	}
}; 	

function goToNextStep(id) {
	setTimeout($('#' + id).click(), 30000);
}

var init = function () {
    util.getPatientStatus($("#hidden_id").val(), $("#page_module_id").val());
	util.getToDoListData($("#hidden_id").val(), $("#page_module_id").val());
	
	form.ajaxForm("patient_traning_info_and_checklist_form", onPatientTraningInfo, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("form[name='patient_traning_info_and_checklist_form'] input[name='start_time']").val(timer_start);
		$("form[name='patient_traning_info_and_checklist_form'] input[name='end_time']").val(timer_paused);
		// $("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("software_usage_instruction_form", onSoftwearUsage, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("form[name='software_usage_instruction_form'] input[name='start_time']").val(timer_start);
		$("form[name='software_usage_instruction_form'] input[name='end_time']").val(timer_paused);
		// $("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("device_traning_form", onDeviceTraning, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("form[name='device_traning_form'] input[name='start_time']").val(timer_start);
		$("form[name='device_traning_form'] input[name='end_time']").val(timer_paused);
		// $("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
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

};

window.deviceTraning = { 
	init: init,
	onResult: onResult
};