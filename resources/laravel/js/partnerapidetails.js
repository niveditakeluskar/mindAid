const URL_POPULATE = "/org/ajax/partner_api_populate";

var populateForm = function (id, url) {
	$.get(url, id,
		//data,
		function (result) {
			// var address = $("form[name='family_patient_data_form'] #patient_address").val();
			for (var key in result) {
				

				form.dynamicFormPopulate(key, result[key]); 
				console.log(result[key]);
				api_response = JSON.parse(result[key].static['api_response']);
				api_request = JSON.parse(result[key].static['api_request']);
				console.log(api_response);
				console.log(api_request);
				// var partnerdeviceparameters = result[key].static['paramdata'];
				// var partnerapiparamdata = result[key].static['partnerapiparamdata'];
				// console.log(partnerdeviceparameters);
				// console.log(partnerapiparamdata); 

			}
		}
	).fail(function (result) {
		console.error("Population Error:", result);
	});
};


$('#addPartnerapidetails').click(function () {
	$("#AddPartnerApiDetailsForm")[0].reset();
	$(".invalid-feedback").text("");
	$(".form-control").removeClass("is-invalid");	
	$('#add_partnerapidetails_modal').modal('show');
});

var onEditPartnerApiDetailsData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#success").show();
		renderPartnerApiDetailsTable();		
		$('#edit_partnerapidetails_modal').modal('hide');
		$("#EditPartnerApiDetailsForm")[0].reset();
		var txt = '<div class="alert alert-success alert-block "style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner Updated Successfully!</strong></div>';
		$("#success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#success").hide();
		}, 3000);
	} 
	
};

var onAddPartnerApiDetailsData = function (formObj, fields, response) {  

	console.log(response+"testpartnerapidetailsresponse");
	if (response.status == 200) {
		$("#success").show();
		$("#AddPartnerApiDetailsForm")[0].reset();
		renderPartnerApiDetailsTable();
		//$("form[name='AddPartnerForm'] .alert").show();
		$('#add_partnerapidetails_modal').modal('hide');
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner Api Details Added Successfully!</strong></div>';
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








var init = function () {
	form.ajaxForm("AddPartnerApiDetailsForm", onAddPartnerApiDetailsData, function () {
		return true;
	});

	$('body').on('click', '.edit', function () {
		var id = $(this).data('id');
		$(".invalid-feedback").text("");
		$(".form-control").removeClass("is-invalid");		
		$('#edit_partnerapidetails_modal').modal('show');
		var url = URL_POPULATE + "/" + id + "/populate";
		populateForm(id, url);
	});

	$('body').on('click', '.change_partnerapistatus_active', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Deactivate this Partner")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/PartnerStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderPartnerApiDetailsTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner Deactivated Successfully!</strong></div>';
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





	$('body').on('click', '.change_partnerapistatus_deactive', function () {
		var id = $(this).data('id');

		if (confirm("Are you sure you want to Activate this Partner")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'post',
				url: '/org/PartnerStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderPartnerApiDetailsTable();
					$("#msg").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Partner  Activated Successfully!</strong></div>';
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



    form.ajaxForm("EditPartnerApiDetailsForm", onEditPartnerApiDetailsData, function () {
		return true;
	});









};




window.partnerapidetails = {
	init: init
	//onResult: onResult
};