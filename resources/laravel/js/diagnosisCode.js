/**
 * 
 */
const URL_POPULATE = "/org/ajax/populateForm";


/**
 * Populate the form of the given patient
 *
 * 
 */
var codecount = 0;
var populateForm = function (data, url) {
	$.get(
		url,
		data,
		function (result) {
			console.log(result);
			for (var key in result) { 
				form.dynamicFormPopulate(key, result[key]);

				console.log(result[key].length + "count");
				$('#id').val(result[key][0].id);
				$('#condition').val(result[key][0].condition);
				if (result[key][0].qualified == 1) {
						console.log("Was Checked");
						//do stuffs
						$("input[name=qualified").attr('checked', true);
						$("#toggle_value").text('Yes');
				}
				if (result[key][0].qualified == 0){
						console.log("Was Not Checked");
						//do stuffs
						$("input[name=qualified").attr('checked', false);
						$("#toggle_value").text('No');
				}
				for (var i = 0; i < result[key].length; i++) {
					//console.log(result[key][i].code+"test");
					if (codecount == 0) {
						$('#code_0').val(result[key][i].code);
					}
					else {
						$('#appendcode').append('<div class="btn_remove" id="btn_removecode_' + codecount + '" style="margin-bottom: 10px;"><input type="text" class="form-control col-md-11" name ="code[]" id ="code_' + codecount + '" value="' + result[key][i].code + '" placeholder ="Enter code"><div class="invalid-feedback"></div><i class="remove-icons i-Remove float-right mb-3" id="remove_code_' + codecount + '" title="Remove Code" style="margin-top: -26px;"></i><div class="error_msg" style="display:none;color:red">Please Enter Code</div></div>');

					}
					codecount++;
				}
				//console.log("symptoms"+result[key].static['symptoms']);
				// var symptoms = JSON.parse(result[key].static['symptoms']);	
				// var i = 0;
				// for (var symptom in symptoms) {
				// 	console.log("symptom"+symptom+ "=====  "+symptoms[symptom]);
				// 	inc_symptoms = i;
				// 	if(i==0){
				// 		$('#symptoms_0').val(symptoms[symptom]);
				// 	}else{
				// 		$('#append_symptoms').append('<div class="btn_remove" id="btn_removesymptoms_' +inc_symptoms+'"><input type="text" class="form-control" name ="symptoms[]" id ="symptoms_' +inc_symptoms+'" value="'+symptoms[symptom]+'" placeholder ="Enter Symptoms"><div class="invalid-feedback"></div><i class="remove-icons i-Remove float-right mb-3" id="remove_symptoms_' +inc_symptoms+'" title="Remove Symptom"></i><div class="error_msg" style="display:none;color:red">Please Enter Symptons</div></div>');
				// 	}
				// 	i++;
				// }

				// var goals = JSON.parse(result[key].static['goals']);	
				// var i = 0;
				// for (var goal in goals) {
				// 	console.log("goal"+goal+ "=====  "+goals[goal]);
				// 	inc_goals = i;
				// 	if(i==0){
				// 		$('#goals_0').val(goals[goal]);
				// 	}else{
				// 		$('#append_goals').append('<div class="btn_remove" id="btn_removegoals_' +inc_goals+'"><input type="text" class="form-control" name ="goals[]" id ="goals_' +inc_goals+'" value="'+goals[goal]+'" placeholder ="Enter Goals"><div class="invalid-feedback"></div><i class="remove-icons i-Remove float-right mb-3" id="remove_goals_' +inc_goals+'" title="Remove Goals"></i><div class="error_msg" style="display:none;color:red">Please Enter Goals</div></div>');
				// 	}
				// 	i++;
				// }

				// var tasks = JSON.parse(result[key].static['tasks']);	
				// var i = 0;
				// for (var task in tasks) {
				// 	console.log("task"+task+ "=====  "+tasks[task]);
				// 	inc_tasks = i;
				// 	if(i==0){
				// 		$('#tasks_0').val(tasks[task]);
				// 	}else{
				// 		$('#append_tasks').append('<div class="btn_remove" id="btn_removetasks_' +inc_tasks+'"><textarea class="form-control" name ="tasks[]" id ="tasks_' +inc_tasks+'" placeholder ="Enter tasks">'+tasks[task]+'</textarea><div class="invalid-feedback"></div><i class="remove-icons i-Remove float-right mb-3" id="remove_tasks_' +inc_tasks+'" title="Remove Tasks"></i><div class="error_msg" style="display:none;color:red">Please Enter Tasks</div></div>');
				// 	}
				// 	i++;
				// }

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
var onEditDiagnosisMainForm = function (formObj, fields, response) {
	//console.log(response+"testedit respose");
	if (response.status == 200) {
		$('#add_diagnosis_modal').modal('hide');
		$("#success").show();
		renderDiagnnosisTable();
		$("#main_edit_diagnosis_form")[0].reset();
		if ($.trim(response.data) == "edit") {
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Diagnosis Updated Successfully!</strong></div>';
		}
		else {
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Diagnosis Added Successfully!</strong></div>';

		} $("#success").html(txt);

		setTimeout(function () {
			$("#success").hide();
		}, 3000);

		//window.location.href ="/org/diagnosis-code";

	}
};

var onDiagnosisMainForm = function (formObj, fields, response) {
	// console.log(response.status + "test add respose");
	if (response.status == 200) {

		if ($.trim(response.data) != 'exist') {
			$("#success").show();
			$('#add_diagnosis_modal').modal('hide');
			$("#msgsccess").show();
			renderDiagnnosisTable();
			$("#main_diagnosis_form")[0].reset();
			if ($.trim(response.data) == "edit") {
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Diagnosis Updated Successfully!</strong></div>';
			}
			else {
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Diagnosis Added Successfully!</strong></div>';

			}
			$("#success").html(txt);
			$("#msgsccess").html(txt);
			var scrollPos = $(".main-content").offset().top;
			$(window).scrollTop(scrollPos);
			//goToNextStep("call_step_1_id");
			setTimeout(function () {
				$("#success").hide(); $("#msgsccess").hide();
			}, 3000);
		}
		else {
			$("form[name='main_diagnosis_form'] #condition").addClass('is-invalid');
			$("form[name='main_diagnosis_form'] #condition").next('.invalid-feedback').html('This condition already exist!');
		}

		//window.location.href ="/org/diagnosis-code";
	}
};




var onResult = function (form, fields, response, error) {

	if (error) {
	}
	else {
		//console.log(response.data.redirect+"*&*&*&");
		//	window.location.href = response.data.redirect;
	}
};




var init = function () {


	$(document).on("click", ".remove-icons", function () {
		var button_id = $(this).closest('div').attr('id');
		$('#' + button_id).remove();
	});

	$('#additionalcode').click(function () {
		codecount++;
		$('#appendcode').append('<div class="btn_remove" id="btn_removecode_' + codecount + '" style="margin-bottom: 10px;"><input type="text" class="form-control col-md-11" name ="code[]" id ="code_' + codecount + '" placeholder ="Enter code"><div class="invalid-feedback"></div><i class="remove-icons i-Remove float-right mb-3" id="remove_code_' + codecount + '" title="Remove Code" style="margin-top: -26px;"></i><div class="error_msg" style="display:none;color:red">Please Enter Code</div></div>');
	});



	form.ajaxForm("main_diagnosis_form", onDiagnosisMainForm, function () {
		//$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		//$("#time-container").val(AppStopwatch.startClock).die();		
		return true;
	});




	form.ajaxForm("main_edit_diagnosis_form", onEditDiagnosisMainForm, function () {
		//$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		//$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	$('#addDiagnosis').click(function () {
		$('#appendcode').html('');
		$("form[name='main_diagnosis_form'] #code").removeClass('is-invalid');
		$("form[name='main_diagnosis_form'] #code").next('.invalid-feedback').html('');
		$("form[name='main_diagnosis_form'] #condition").removeClass('is-invalid');
		$("form[name='main_diagnosis_form'] #condition").next('.invalid-feedback').html('');
		$("#modelHeading1").text('Add Diagnosis');
		codecount = 0;
		$("#main_diagnosis_form")[0].reset();
		$('#add_diagnosis_modal').modal('show');
		$("#qualified-yes").click();
		$('#id').val("");
		//  $('h4#Add Diagnosis').text('Add Diagnosis');
	});


	$('body').on('click', '.editDiagnosis', function () {
		codecount = 0;
		$('#appendcode').html('');
		$("form[name='main_diagnosis_form'] #code").removeClass('is-invalid');
		$("form[name='main_diagnosis_form'] #code").next('.invalid-feedback').html('');
		$("form[name='main_diagnosis_form'] #condition").removeClass('is-invalid');
		$("form[name='main_diagnosis_form'] #condition").next('.invalid-feedback').html('');
		$("#modelHeading1").text('Edit Diagnosis');
		$('#add_diagnosis_modal').modal('show');
		var sPageURL = window.location.pathname;
		console.log("sPageURL" + sPageURL);
		parts = sPageURL.split("/"),
			id = parts[parts.length - 1];
		var patientId = $(this).data('id');

		var data = "";
		var formpopulateurl = URL_POPULATE + "/" + patientId;
		populateForm(data, formpopulateurl);
	});

	// $("input[name='condition']").blur(function(){
	// 	var condition=$('#condition').val();
	// 	var id=$('#id').val();
	// 	if(id=='')
	// 	{
	// 		id=0;
	// 	}

	//   $.ajaxSetup({
	// 	            headers: {
	// 	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 	            }
	// 	        });
	//       		$.ajax({
	// 	            type   : 'post',
	// 	            url    : '/org/condition-exist',
	// 	           // data: {"_token": "{{ csrf_token() }}","id": id},
	// 	            data   :  {"condition": condition,"id":id},
	// 	            success: function(response) {
	// 	              if($.trim(response)=='exist')
	// 	              {
	//                      $("form[name='main_diagnosis_form'] #condition").addClass('is-invalid');
	//                     $("form[name='main_diagnosis_form'] #condition").next('.invalid-feedback').html('This condition already exist!');
	// 	              //  $('#diagnosissubmit').prop( "disabled", true);
	// 	              }
	// 	              else
	// 	              {
	// 	              	$("form[name='main_diagnosis_form'] #condition").removeClass('is-invalid');
	// 	              	$("form[name='main_diagnosis_form'] #condition").next('.invalid-feedback').html('');
	// 	              //	$('#diagnosissubmit').prop( "disabled", false);

	// 	              }

	// 				}
	//         	});
	// });

	$('body').on('click', '.change_diagnosis_status_active', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Deactivate this Diagnosis")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/delete-diagnosis/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderDiagnnosisTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Diagnosis Deactivated Successfully!</strong></div>';
					$("#success").html(txt);
					setTimeout(function () {
						$("#success").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});
	$('body').on('click', '.change_diagnosis_status_deactive', function () {
		var id = $(this).data('id');

		if (confirm("Are you sure you want to Activate this Diagnosis")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/delete-diagnosis/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderDiagnnosisTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Diagnosis  Activated Successfully!</strong></div>';
					$("#success").html(txt);
					setTimeout(function () {
						$("#success").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});

};

window.diagnosisCode = {
	init: init,
	onResult: onResult
};