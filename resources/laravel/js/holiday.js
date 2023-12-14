/**
 * 
 */
const URL_POPULATE = "/org/ajax/holiday/populateForm";


/**
 * Populate the form of the given patient
 *
 * 
 */
var populateForm = function (data, url) { //console.log("ash m");
	$.get(
		url,
		data,
		function (result) {
			console.log(result);
			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);

				console.log(result[key]);
				$('#id').val(result[key][0].id);
				$('#event').val(result[key][0].event);
                $('#date').val(result[key][0].date1);
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
var onEditHolidayMainForm = function (formObj, fields, response) {
	//console.log(response+"testedit respose");
	if (response.status == 200) {
		$('#add_holiday_modal').modal('hide');
		$("#success").show();
		renderHolidayTable();
		$("#edit_holiday_form")[0].reset();
		if ($.trim(response.data) == "edit") {
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Holidays Updated Successfully!</strong></div>';
		}
		else {
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Holidays Added Successfully!</strong></div>';

		} $("#success").html(txt);

		setTimeout(function () {
			$("#success").hide();
		}, 3000);

		//window.location.href ="/org/diagnosis-code";

	}
};

var onHolidayForm = function (formObj, fields, response) {
	// console.log(response.status + "test add respose");
	if (response.status == 200) {

		// if ($.trim(response.data) != 'exist') {
			$("#success").show();
			$('#add_holiday_modal').modal('hide');
			$("#msgsccess").show();
			renderHolidayTable();
			//$("#add_holiday_modal")[0].reset();
			if ($.trim(response.data) == "edit") {
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Holiday Updated Successfully!</strong></div>';
			}
			else {
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Holiday Added Successfully!</strong></div>';

			}
			$("#success").html(txt);
			$("#msgsccess").html(txt);
			var scrollPos = $(".main-content").offset().top;
			$(window).scrollTop(scrollPos);
			//goToNextStep("call_step_1_id");
			setTimeout(function () {
				$("#success").hide(); $("#msgsccess").hide();
			}, 3000);
		//}
		// else {
		// 	$("form[name='holiday_form'] #event").addClass('is-invalid');
		// 	$("form[name='holiday_form'] #evemt").next('.invalid-feedback').html('This condition already exist!');
		// }
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
	form.ajaxForm("holiday_form", onHolidayForm, function () {
		//$("#time-container").val(AppStopwatch.pauseClock);		
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		//$("#time-container").val(AppStopwatch.startClock).die();		
		return true;
	});




	form.ajaxForm("edit_holiday_form", onEditHolidayMainForm, function () {
		//$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		//$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	$('#addHoliday').click(function () {
		$('#appendcode').html('');
		$("form[name='holiday_form'] #event").removeClass('is-invalid');
		$("form[name='holiday_form'] #event").next('.invalid-feedback').html('');
		$("form[name='holiday_form'] #date").removeClass('is-invalid');
		$("form[name='holiday_form'] #date").next('.invalid-feedback').html('');
		$("#modelHeading1").text('Add Holidays');
		codecount = 0;
		$("#holiday_form")[0].reset();
		$('#add_holiday_modal').modal('show');
		$('#id').val("");
		//  $('h4#Add Diagnosis').text('Add Diagnosis');
	});


	$('body').on('click', '.editholiday', function () { //alert("working");
		$("form[name='holiday_form'] #event").removeClass('is-invalid');
		$("form[name='holiday_form'] #event").next('.invalid-feedback').html('');
		$("form[name='holiday_form'] #date").removeClass('is-invalid');
		$("form[name='holiday_form'] #date").next('.invalid-feedback').html('');
		$("#modelHeading1").text('Edit Holidays');
		$('#add_holiday_modal').modal('show');
		var sPageURL = window.location.pathname;
		parts = sPageURL.split("/"),
			id = parts[parts.length - 1];
		var id = $(this).data('id');
		var data = "";
		var formpopulateurl = URL_POPULATE + "/" + id;
		console.log("formpopulateurl" + formpopulateurl);
        console.log("data" + data);
		populateForm(data, formpopulateurl);
	});


    $('body').on('click', '.change_holiday_status_active', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Deactivate this Holiday")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/delete-holiday/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderHolidayTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Holiday Deactivated Successfully!</strong></div>';
					$("#success").html(txt);
					setTimeout(function () {
						$("#success").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});
	$('body').on('click', '.change_holiday_status_deactive', function () {
		var id = $(this).data('id');

		if (confirm("Are you sure you want to Activate this Holiday")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/delete-holiday/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderHolidayTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Holiday  Activated Successfully!</strong></div>';
					$("#success").html(txt);
					setTimeout(function () {
						$("#success").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});

};

window.holiday = {
	init: init,
	onResult: onResult
};