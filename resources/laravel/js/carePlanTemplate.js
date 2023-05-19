/**
 * 
 */
const URL_POPULATE = "/org/ajax/populateCarePlanTemaplateForm";


/**
 * Populate the form of the given patient
 *
 * 
 */
var symtomscount = 0;
var goalcount = 0;
var taskscount = 0;
var allergycount = 0;
var medicationcount = 0;
var healthcount = 0;
var labcount = 0;
var populateForm = function (data, url) {

	$.get(
		url,
		data,
		function (result) {
			for (var key in result) {

				form.dynamicFormPopulate(key, result[key]);
				//console.log("symptoms"+result[keystatic].['symptoms']);
				var symptoms = JSON.parse(result[key].static['symptoms']);
				var symptomsCount = symptoms.length;

				for (var symptom in symptoms) {
					// console.log("symptom" + symptom + "=====  " + symptoms[symptom]);
					inc_symptoms = symtomscount;
					if (symtomscount == 0) {
						$('#symptoms_0').val(symptoms[symptom]);
					} else {
						$('#append_symptoms').append('<div class=" row btn_remove" id="btn_removesymptoms_' + inc_symptoms + '"><input type="text" class="form-control col-md-10" name ="symptoms[]" id ="symptoms_' + inc_symptoms + '" value="' + symptoms[symptom] + '" placeholder ="Enter Symptom"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_symptoms_' + inc_symptoms + '" title="Remove Symptom"></i><div class="error_msg" style="display:none;color:red">Please Enter Symptons</div></div>');
						$("form[name='" + key + "'] #symptoms_"+ inc_symptoms).val(symptoms[symptom]);
					}
					symtomscount++;
				}

				var goals = JSON.parse(result[key].static['goals']);

				for (var goal in goals) {
					// console.log("goal" + goal + "=====  " + goals[goal]);
					inc_goals = goalcount;
					if (goalcount == 0) {
						$('#goals_0').val(goals[goal]);
					} else {
						$('#append_goals').append('<div class="row btn_remove" id="btn_removegoals_' + inc_goals + '"><input type="text" class="form-control col-md-10" name ="goals[]" id ="goals_' + inc_goals + '" value="' + goals[goal] + '" placeholder ="Enter Goal"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_goals_' + inc_goals + '" title="Remove Goal"></i><div class="error_msg" style="display:none;color:red">Please Enter Goals</div></div>');
						$("form[name='" + key + "'] #goals_"+ inc_goals).val(goals[goal]);
					}
					goalcount++;
				}

				var tasks = JSON.parse(result[key].static['tasks']);

				for (var task in tasks) {
					// console.log("task" + task + "=====  " + tasks[task]);
					inc_tasks = taskscount;
					if (taskscount == 0) {
						$('#tasks_0').val(tasks[task]);
					} else {
						$('#append_tasks').append('<div class="row btn_remove" id="btn_removetasks_' + inc_tasks + '"><textarea class="mb-1 col-md-10 form-control" name ="tasks[]" id ="tasks_' + inc_tasks + '" placeholder ="Enter task">' + tasks[task] + '</textarea><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_tasks_' + inc_tasks + '" title="Remove Task"></i><div class="error_msg" style="display:none;color:red">Please Enter Tasks</div></div>');
						$("form[name='" + key + "'] #tasks_"+ inc_tasks).val(tasks[task]);
					}
					taskscount++;
				}

				// var allergies = JSON.parse(result[key].static['allergies']);

				// for (var allergy in allergies) {
				// 	// console.log("allergy" + allergy + "=====  " + allergies[allergy]);
				// 	inc_allergies = allergycount;
				// 	if (allergycount == 0) {
				// 		$('#allergies_0').val(allergies[allergy]);
				// 	} else {
				// 		$('#append_allergies').append('<div class="row btn_remove" id="btn_removetasks_' + inc_allergies + '"><textarea class="mb-1 col-md-10 form-control" name ="allergies[]" id ="allergies_' + inc_allergies + '" placeholder ="Enter Allergy">' + allergies[allergy] + '</textarea><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_tasks_' + inc_allergies + '" title="Remove Allergy"></i><div class="error_msg" style="display:none;color:red">Please Enter allergies</div></div>');
				// 	}
				// 	allergycount++;
				// }

				// var health_data = JSON.parse(result[key].static['health_data']);

				// for (var health in health_data) {
				// 	// console.log("allergy" + health + "=====  " + health_data[health]);
				// 	inc_health = healthcount;
				// 	if (healthcount == 0) {
				// 		$('#health_0').val(health_data[health]);
				// 	} else {
				// 		$('#append_health').append('<div class="row btn_remove" id="btn_removehealth_' + inc_health + '"><textarea class="mb-1 col-md-10 form-control" name ="health_data[]" id ="health_' + inc_health + '" placeholder ="Enter Health Data">' + health_data[health] + '</textarea><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_health_' + inc_health + '" title="Remove Health Data"></i><div class="error_msg" style="display:none;color:red">Please Enter health data</div></div>');
				// 	}
				// 	healthcount++;
				// }

				// var medications = JSON.parse(result[key].static['medications']);

				// for (var medication in medications) {
				// 	// console.log("medication" + medication + "=====  " + medications[medication]);
				// 	inc_medications = medicationcount;
				// 	if (medicationcount == 0) {
				// 		$('#medication_med_id_0').val(medications[medication]);
				// 	} else {
				// 		inc_medications++;
				// 		var $medicationElement = $('#medication_med_id_0').clone().prop('id', 'medication_med_id_' + inc_medications).removeClass('col-md-3');
				// 		$('#append_medications').append('<div class="btn_remove row" id="btn_removemedication_' + inc_medications + '"><div class="col-md-3 med "></div><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_medications_' + inc_medications + '" title="Remove Medication"></i><div class="error_msg" style="display:none;color:red">Please Enter Medication</div></div>');
				// 		$('#btn_removemedication_' + inc_medications + ' .med').html($medicationElement);
				// 		$('#medication_med_id_' + inc_medications).val(medications[medication]);


				// 	}
				// 	medicationcount++;
				// }

				// var labs = JSON.parse(result[key].static['labs']);

				// for (var lab in labs) {
				// 	// console.log("lab" + lab + "=====  " + labs[lab]);
				// 	inc_lab = labcount;
				// 	if (labcount == 0) {
				// 		$('#lab_0').val(labs[lab]);
				// 	} else {
				// 		inc_lab++;
				// 		var labElement = $('#lab_0').clone().prop('id', 'lab_' + inc_lab);
				// 		$('#append_labs').append('<div class="col-md-4 form-group mb-3 btn_remove" id="btn_removelabs_' + inc_lab + '"><div id="l' + inc_lab + '" class="col-md-4"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_lebs_' + inc_lab + '" title="Remove Labs"></i></div><div class="row mb-3" id="append_labs_params_lab' + inc_lab + '"></div><hr/></div> ');
				// 		$('#l' + inc_lab).html(labElement);
				// 		//$('#l' + inc_lab).attr(("onChange", "addLabparam(this);"));
				// 		$('#lab_' + inc_lab).val(labs[lab]);
				// 	}
				// 	labcount++;
				// }

				if (key == 'edit_diagnosis_careplan_form') {
					//console.log(result[key].static['condition']);
					$('#conditionname').val(result[key].static['condition']);
				}
			}
		}
	).fail(function (result) {
		console.error("Population Error:", result);
	});

};


/**
 * Add a Allergies via Ajax request
 */

//Family Data
var onEditCarePlanTemplateMainForm = function (formObj, fields, response) {
	console.log(response + "testedit respose");
	if (response.status == 200) {
		$("#main_edit_care_plan_template_form")[0].reset();
		$("#success").show();
		//window.location.href ="/org/care-plan-template";
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>CarePlanTemplate Updated Successfully!</strong></div>';
		$("#success").html(txt);
		//$("form[name='main_edit_diagnosis_form'] .alert").show();
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#success").hide();
		}, 3000);

		window.location.href = "/org/care-plan-template";

	}
	else {
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);

		var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all mandatory fields!</strong></div>';
		$("#error_msg").html(txt);
	}
};

var onCarePlanTemplateMainForm = function (formObj, fields, response) {
	console.log(response.status + "test add respose");
	if (response.status == 200) {
		$("#error_msg").html('');
		$("#main_care_plan_template_form")[0].reset();
		//window.location.href ="/org/care-plan-template";
		//$("form[name='main_diagnosis_form'] .alert").show();
		$("#success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Care Plan Template Added Successfully!</strong></div>';
		$("#success").html(txt);
		$("#msg").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#success").hide();
		}, 3000);

		window.location.href = "/org/care-plan-template";
	} else {
		if (response.data.errors.drop_condition) {
			$("select[name='drop_condition']").addClass("is-invalid");
			$("select[name='drop_condition']").next(".invalid-feedback").html(response.data.errors.drop_condition);
		} else {
			$("select[name='drop_condition']").removeClass("is-invalid");
			$("select[name='drop_condition']").next(".invalid-feedback").html('');
		} if (response.data.errors.drop_code) {
			$("select[name='drop_code']").addClass("is-invalid");
			$("select[name='drop_code']").next(".invalid-feedback").html(response.data.errors.drop_code);
		} else {
			$("select[name='drop_code']").removeClass("is-invalid");
			$("select[name='drop_code']").next(".invalid-feedback").html('');
		}
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);

		var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill all mandatory fields!</strong></div>';
		$("#error_msg").html(txt);
	}
};

var onEditDiagnosisForm = function (formObj, fields, response) {
	console.log(response.data + "testedit respose");


	if (response.status == 200) {
		if ($.trim(response.data) == 'yes') {
			console.log("testing");
			$('form[name="edit_diagnosis_careplan_form"] input[name="condition"]').addClass('is-invalid');
			$('form[name="edit_diagnosis_careplan_form"] input[name="condition"]').next(".invalid-feedback").html('Condition already exists!');
		}
		else {
			$('form[name="edit_diagnosis_careplan_form"] #codition').removeClass('is-invalid');
			$('form[name="edit_diagnosis_careplan_form"] #codition').next(".invalid-feedback").html('');
			$('#edit_diagnosis_modal').modal('hide');
			$("#success").hide();
			renderCarePlanTable();
			$("#edit_diagnosis_careplan_form")[0].reset();
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Diagnosis Added Successfully!</strong></div>';
			$("#success").html(txt);

			setTimeout(function () {
				$("#success").hide();
			}, 3000);

			//window.location.href ="/org/diagnosis-code";

		}
	}
};


var onCarePlanDiagnosisMainForm = function (formObj, fields, response) {
	console.log(response.status + "test add respose");
	if (response.status == 200) {

		if ($.trim(response.data) != 'exist') {
			$("#msgsccess").show();
			$('#add_diagnosis_modal').modal('hide');

			$("#main_careplan_diagnosis_form")[0].reset();


			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Diagnosis Added Successfully!</strong></div>';

			$("#msgsccess").html(txt);
			var scrollPos = $(".main-content").offset().top;
			$(window).scrollTop(scrollPos);
			//goToNextStep("call_step_1_id");
			setTimeout(function () {
				$("#msgsccess").hide();
			}, 3000);
		}
		else {
			$("form[name='main_careplan_diagnosis_form'] #condition").addClass('is-invalid');
			$("form[name='main_careplan_diagnosis_form'] #condition").next('.invalid-feedback').html('This condition already exist!');
		}

		//window.location.href ="/org/diagnosis-code";
	}
};


var onResult = function (form, fields, response, error) {

	if (error) {
	}
	else {
		//console.log(response.data.redirect+"*&*&*&");
		window.location.href = response.data.redirect;
	}
};




var init = function () {
	//renderCarePlanTable();	
	$(document).on("click", ".remove-icons", function () {
		var button_id = $(this).closest('div').attr('id');
		console.log("removebuttonid: " + button_id);
		$('#' + button_id).remove();
	});

	//	var inc_goals = 1;
	$('#additionalgoals').click(function () {
		goalcount++;
		console.log("testgoal" + goalcount);
		$('#append_goals').append('<div class="btn_remove row" id="btn_removegoals_' + goalcount + '"><input type="text" class="col-md-10 form-control" name ="goals[]" id ="goals_' + goalcount + '" placeholder ="Enter Goal"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_goals_' + goalcount + '" title="Remove Goal"></i><div class="error_msg" style="display:none;color:red">Please Enter Goal</div></div>');
	});

 
	$('#additionalsymptoms').click(function () {
		symtomscount++;

		$('#append_symptoms').append('<div class="btn_remove row" id="btn_removesymptoms_' + symtomscount + '"><input type="text" class="col-md-10 form-control" name ="symptoms[]" id ="symptoms_' + symtomscount + '" placeholder ="Enter Symptom"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_symptoms_' + symtomscount + '" title="Remove Symptom"></i><div class="error_msg" style="display:none;color:red">Please Enter Symptom</div></div>');
	});


	$('#additionaltasks').click(function () {
		taskscount++;
		$('#append_tasks').append('<div class="btn_remove row" id="btn_removetasks_' + taskscount + '"><textarea class="col-md-10 form-control" name ="tasks[]" id ="tasks_' + taskscount + '" placeholder ="Enter task"></textarea><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_tasks_' + taskscount + '" title="Remove Task"></i><div class="error_msg" style="display:none;color:red">Please Enter Task</div></div>');
	});


	$('#additionalallergies').click(function () {
		allergycount++;
		$('#append_allergies').append('<div class="btn_remove row" id="btn_removeallergies_' + allergycount + '"><input type="text" class="col-md-10 form-control" name ="allergies[]" id ="allergies_' + allergycount + '" placeholder ="Enter Allergy"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_allergies_' + allergycount + '" title="Remove Allergy"></i><div class="error_msg" style="display:none;color:red">Please Enter Allergy</div></div>');
	});

	$('#additionalhealth').click(function () {
		healthcount++;
		$('#append_health').append('<div class="btn_remove row" id="btn_removehealth_' + healthcount + '"><input type="text" class="col-md-10 form-control" name ="health_data[]" id ="health_' + healthcount + '" placeholder ="Enter Health Data"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_health_' + healthcount + '" title="Remove Health Data"></i><div class="error_msg" style="display:none;color:red">Please Enter Health Data</div></div>');
	});


	$('#additionalmedications').click(function () {
		medicationcount++;
		var $medicationElement = $('#medication_med_id_0').clone().prop('id', 'medication_med_id_' + medicationcount).removeClass('col-md-3');
		$('#append_medications').append('<div class="btn_remove row" id="btn_removemedication_' + medicationcount + '"><div class="col-md-3 med"></div><div class="col-md-3 forms-element is-invalid" " id="newmed' + medicationcount + '" style="display:none"><div class="invalid-feedback"></div></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_medications_' + medicationcount + '" title="Remove Medication"></i><div class="error_msg" style="display:none;color:red">Please Enter Medication</div></div>');
		$('#btn_removemedication_' + medicationcount + ' .med').html($medicationElement);
		$('#medication_med_id_' + medicationcount).attr(("onChange", "addnewmedication(this);"));
	});
	$('#additionallab').click(function () {
		labcount++;
		var labElement = $('#lab_0').clone().prop('id', 'lab_' + labcount);
		$('#append_labs').append('<div class="row btn_remove" id="btn_removelabs_' + labcount + '"><div id="l' + labcount + '" class="col-md-4"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_labs_' + labcount + '" title="Remove Labs"></i></div></div> ');
		$('#l' + labcount).html(labElement);
		//$('#l' + labcount).attr(("onChange", "addLabparam(this);"));
	});

	form.ajaxForm("main_care_plan_template_form", onCarePlanTemplateMainForm, function () {
		//var condition=$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val(); 
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start); 
		$("input[name='end_time']").val(timer_paused);
		//$("#time-container").val(AppStopwatch.startClock).die();		
		return true;
	});


	form.ajaxForm("main_edit_care_plan_template_form", onEditCarePlanTemplateMainForm, function () {
		//$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		//$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("edit_diagnosis_careplan_form", onEditDiagnosisForm, function () {
		//$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		//$("#time-container").val(AppStopwatch.startClock);
		return true;
	});


	form.ajaxForm("main_careplan_diagnosis_form", onCarePlanDiagnosisMainForm, function () {
		//$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		//$("#time-container").val(AppStopwatch.startClock).die();		
		return true;
	});

	var sPageURL = window.location.pathname;
	parts = sPageURL.split("/"),
		id = parts[parts.length - 1];
	var patientId = id;
	if ($.isNumeric(patientId)) {
		var data = "";
		var formpopulateurl = URL_POPULATE + "/" + patientId;
		populateForm(data, formpopulateurl);
	}


	$('#addCarePlanDiagnosis').click(function () {
		$('#appendcode').html('');
		$("form[name='main_careplan_diagnosis_form'] #code").removeClass('is-invalid');
		$("form[name='main_careplan_diagnosis_form'] #code").next('.invalid-feedback').html('');
		$("form[name='main_careplan_diagnosis_form'] #condition").removeClass('is-invalid');
		$("form[name='main_careplan_diagnosis_form'] #condition").next('.invalid-feedback').html('');
		codecount = 0;
		$("#main_careplan_diagnosis_form")[0].reset();
		$('#add_diagnosis_modal').modal('show');
		$('#id').val("");
		//  $('h4#Add Diagnosis').text('Add Diagnosis');
	});



	$('#addcode').click(function () {
		//$("#addNewCodeform")[0].reset();
		var conditionid = $('#condition option:selected').val();
		var conditionname = $('#condition option:selected').text();
		if (conditionid != 0) {
			$('#add_code_modal').modal('show');
			$('#conditionval').val(conditionname);
			$('#conditionid').val(conditionid);
		}
		else {
			alert("Please select condition!!");
		}
	});


};

$('#careplan-list').on('click', '.editdiagnosis', function () {
	$('#code').removeClass('is-invalid');
	$('#codition').removeClass('is-invalid');
	var careplanid = $(this).data('id');
	$('#edit_diagnosis_modal').modal('show');
	var data = "";
	var formpopulateurl = "editdiagnosis/" + careplanid;
	populateForm(data, formpopulateurl);
});

var populateCode = function (id) {

	$.ajax({
		type: 'get',
		url: '/org/getcode/' + id,
		success: function (data) {

			//$('#code').editableSelect('clear');
			$('#code').empty().append(`<option value="0"> select code </option>`);
			var datanew = jQuery.parseJSON(data);
			$.each(datanew, function (index, value) {
				$('form[name="main_care_plan_template_form"] #code').append(`<option value="${value.code}"> ${value.code}
                                  </option>`);
			});
			$('form[name="main_care_plan_template_form"] #code').editableSelect();

		}
	});
}

$('form[name="main_care_plan_template_form"] #condition').on('change', function () {
	$('form[name="main_care_plan_template_form"] #code').editableSelect('destroy');
	var conditionid = $('#condition option:selected').val();
	console.log(conditionid + "conditionid");
	populateCode(conditionid);

});

$('#submitcode').on('click', function () {
	var data = {
		condition: $('#conditionid').val(),
		code: $('#newcode').val(),

	}
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: '/org/addnewcode',
		data: data,
		success: function (data) {
			$("#add_code_modal").modal('hide');

			$("#success").show();
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Code Added Successfully!</strong></div>';
			$("#success").html(txt);
			setTimeout(function () {
				$("#success").hide();
			}, 3000);
			var conditionid = $('#condition option:selected').val();
			populateCode(conditionid);
			// window.location.href ="/org/care-plan-template-add"
		}
	});
});


$('body').on('click', '.change_status_care_active', function () {
	var id = $(this).data('id');
	if (confirm("Are you sure you want to Deactivate this CarePlanTemplate")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/delete-careplan/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderCarePlanTable();
				$("#msgsccess").show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>CarePlanTemplate Deactivated Successfully!</strong></div>';
				$("#msgsccess").html(txt);
				//goToNextStep("call_step_1_id");
				setTimeout(function () {
					$("#msgsccess").hide();
				}, 3000);
			}
		});
	} else { return false; }
});
$('body').on('click', '.change_status_care_deactive', function () {
	var id = $(this).data('id');

	if (confirm("Are you sure you want to Activate this CarePlanTemplate")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/delete-careplan/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderCarePlanTable();
				$("#msgsccess").show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>CarePlanTemplate Activated Successfully!</strong></div>';
				$("#msgsccess").html(txt);
				//goToNextStep("call_step_1_id");
				setTimeout(function () {
					$("#msgsccess").hide();
				}, 3000);
			}
		});
	} else { return false; }
});


window.carePlanTemplate = {
	init: init,
	onResult: onResult
};