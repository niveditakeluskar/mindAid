const URL_POPULATE = "/org/ajax/medication_populate";


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

var onAddmedicationData = function (formObj, fields, response) {
	//console.log(response+"testresponse");
	if (response.status == 200) {
		$("#success").show();
		$("#AddMedicationForm")[0].reset();
		renderMedicationTable();
		//$("form[name='AddMedicationForm'] .alert").show();
		$('#add_medication_modal').modal('hide');
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Medication Added Successfully!</strong></div>';
		$("#success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#success").hide();
		}, 3000);

	 } 
	//else {
    //     var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    //     $("#success").html(txt);
    //     $("#success").show();
    //     setTimeout(function () {
    //         $("#success").hide();
    //     }, 3000);
    //}
};

var onEditMedicationData = function (formObj, fields, response) {
	//console.log(response+"testresponse");
	if (response.status == 200) {
		$("#success").show();
		renderMedicationTable();
		//	$("form[name='EditpracticeForm'] .alert").show();
		$('#edit_medication_modal').modal('hide');
		$("#editMedicationForm")[0].reset();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Medication Updated Successfully!</strong></div>';
		$("#success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#success").hide();
		}, 3000);
	} 
	// else {
 //        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
 //        $("#success").html(txt);
 //        $("#success").show();
 //        setTimeout(function () {
 //            $("#success").hide();
 //        }, 3000);
 //    }
};

$('#addMedication').click(function () {
	$("#AddMedicationForm")[0].reset();
	$(".invalid-feedback").text("");
	$(".form-control").removeClass("is-invalid");
	// $('#modelHeading').html("Add Role");
	$('#add_medication_modal').modal('show');
});

var init = function () {
	form.ajaxForm("AddMedicationForm", onAddmedicationData, function () {
		return true;
	});

	$('body').on('click', '.edit', function () {
		var id = $(this).data('id');
		$(".invalid-feedback").text("");
		$(".form-control").removeClass("is-invalid");
		//	alert(id);	
		$('#edit_medication_modal').modal('show');
		var url = URL_POPULATE + "/" + id + "/populate";
		populateForm(id, url);
	});

	$('body').on('click', '.change_medicationstatus_active', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Deactivate this Medication")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/medicationStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderMedicationTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Medication Deactivated Successfully!</strong></div>';
					$("#success").html(txt);
					var scrollPos = $(".main-content").offset().top;
					$(window).scrollTop(scrollPos);
					//goToNextStep("call_step_1_id");
					setTimeout(function () {
						$("#success").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});

	$('body').on('click', '.change_medicationstatus_deactive', function () {
		var id = $(this).data('id');

		if (confirm("Are you sure you want to Activate this Medication")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/medicationStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderMedicationTable();
					$("#msg").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Medication  Activated Successfully!</strong></div>';
					$("#msg").html(txt);
					var scrollPos = $(".main-content").offset().top;
					$(window).scrollTop(scrollPos);
					//goToNextStep("call_step_1_id");
					setTimeout(function () {
						$("#msg").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});


	// $('body').on('click', '.medstatus', function () {
	//    //  alert("test"+);
	// 	var id = $(this).data('id');	
	// 	var url = "medicationStatus/" + id;		
	// 	// alert("test"+id);		

	// 	  $.ajax({
	//               type:'get',
	//               url:url, 
	//               id:"id="+id,             
	//               success:function(data) {
	//                // var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Medication Updated Successfully!</strong></div>';
	//                // $("#success").html(txt); 
	//               // console.log("test"+trueOrFalse(data));
	//                renderMedicationTable();
	//               }
	//            });
	// });

	form.ajaxForm("editMedicationForm", onEditMedicationData, function () {
		return true;
	});
};




window.medication = {
	init: init
	//onResult: onResult
};