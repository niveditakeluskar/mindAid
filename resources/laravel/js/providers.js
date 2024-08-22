/**
 * 
 */
const URL_POPULATE = "/org/ajax/provider_populate";
const URL_POPULATE_PROVIDER_TYPE = "/org/ajax/provider_type_populate";
const URL_POPULATE_PROVIDER_SUBTYPE = "/org/ajax/provider_subtype_populate";
const URL_POPULATE_PROVIDER_SPECIALITY = "/org/ajax/provider_speciality_populate";


/**
 * Populate the form of the given patient
 *
 * 
 */
var populateForm = function (id, url) {
	//alert("url" + url);
	$.get(
		url,
		id,
		function (result) {
			console.log(result);
			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);
			}
		}
	).fail(function (result) {
		console.error("Population Error:", result);
	});

};


/**
 * Add a Allergies via Ajax request
 */

//Provider Data

var onAddproviderData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#AddProviderForm")[0].reset();
		$("#provider_success").show();
		// $("form[name='AddProviderForm'] .alert").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Provider Data Saved Successfully!</strong></div>';
		$("#provider_success").html(txt);
		$('#add_provider_modal').modal('hide');
		renderProvidersTable();
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#provider_success").fadeOut();
		}, 3000);

	}
};

var onEditproviderData = function (formObj, fields, response) {
	if (response.status == 200) {
		// $("form[name='EditProviderForm'] .alert").show();
		$("#provider_success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Provider Data Updated Successfully!</strong></div>';
		$("#provider_success").html(txt);
		$('#edit_provider_modal').modal('hide');
		$("#EditProviderForm")[0].reset();
		renderProvidersTable();
		setTimeout(function () {
			$("#provider_success").hide();
		}, 3000);

	}
};
//Provider Type
var onAddprovidertypeData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#AddProviderTypeForm")[0].reset();
		//$("form[name='AddProviderTypeForm'] .alert").show();
		$('#add_provider_type_modal').modal('hide');
		$("#provider_type_success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Provider Type Data Saved Successfully!</strong></div>';
		$("#provider_type_success").html(txt);
		renderProvidersTypeTable();
		setTimeout(function () { //goToNextStep("drug-icon-pill");
			$("#provider_type_success").hide();
		}, 3000);

	}
};

var onEditprovidertypeData = function (formObj, fields, response) {
	if (response.status == 200) {
		// $("form[name='EditProviderTypeForm'] .alert").show();
		$('#edit_provider_type_modal').modal('hide');
		$("#EditProviderTypeForm")[0].reset();
		$("#provider_type_success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Provider Type Data Updated Successfully!</strong></div>';
		$("#provider_type_success").html(txt);
		renderProvidersTypeTable();
		setTimeout(function () {
			$("#provider_type_success").hide();
		}, 3000);

	}
};

//Provider Speciality
var onAddSpecialityData = function (formObj, fields, response) {
	console.log(response.data + "addspeciality");
	if ($.trim(response.data) == 'yes') {
		$('input[name="speciality"]').addClass('is-invalid');
		$('input[name="speciality"]').next(".invalid-feedback").html('This Speciality already exists!');
	}
	else {
		if (response.status == 200) {
			$('input[name="speciality"]').removeClass('is-invalid');
			$("#AddProviderSpecialityForm")[0].reset();
			$("#provider_speciality_success").show();
			//$("form[name='AddProviderTypeForm'] .alert").show();
			$('#add_provider_speciality_modal').modal('hide');
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Specialist Saved Successfully!</strong></div>';
			$("#provider_speciality_success").html(txt);
			renderSpecialityTable();
			setTimeout(function () { //goToNextStep("drug-icon-pill");
				$("#provider_speciality_success").hide();
			}, 3000);

		}
	}

};

var onEditSpecialityData = function (formObj, fields, response) {
	console.log(response.data + "editspeciality");
	if ($.trim(response.data) == 'yes') {
		$('input[name="speciality"]').addClass('is-invalid');
		$('input[name="speciality"]').next(".invalid-feedback").html('This Speciality already exists!');
	}
	else {
		if (response.status == 200) {
			$('input[name="speciality"]').removeClass('is-invalid');
			// $("form[name='EditProviderTypeForm'] .alert").show();
			$("#provider_speciality_success").show();
			$('#edit_provider_speciality_modal').modal('hide');
			$("#EditProviderSpecialityForm")[0].reset();
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Specialist Updated Successfully!</strong></div>';
			$("#provider_speciality_success").html(txt);
			renderSpecialityTable();
			setTimeout(function () {
				$("#provider_speciality_success").hide();
			}, 3000);

		}
	}
};


//provider Subtype
var onAddproviderSubTypeData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#AddProviderSubtypeForm")[0].reset();
		// $("form[name='AddProviderSubtypeForm'] .alert").show();
		$('#add_provider_subtype_modal').modal('hide');
		$("#provider_subtype_success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Credential Data Saved Successfully!</strong></div>';
		$("#provider_subtype_success").html(txt);
		renderProvidersSubtypeTable();
		setTimeout(function () {
			$("#provider_subtype_success").hide();
		}, 3000);

	}
	else {
		if (response.data.errors.provider_type_id) {
			$('[name="provider_type_id"]').addClass("is-invalid");
			$('[name="provider_type_id"]').next(".invalid-feedback").html(response.data.errors.provider_type_id);
		} else {
			$('[name="provider_type_id"]').removeClass("is-invalid");
			$('[name="provider_type_id"]').next(".invalid-feedback").html('');
		}

		if (response.data.errors.sub_provider_type) {
			$('[name="sub_provider_type"]').addClass("is-invalid");
			$('[name="sub_provider_type"]').next(".invalid-feedback").html(response.data.errors.sub_provider_type);
		} else {
			$('[name="sub_provider_type"]').removeClass("is-invalid");
			$('[name="sub_provider_type"]').next(".invalid-feedback").html('');
		}
	}
};

var onEditproviderSubTypeData = function (formObj, fields, response) {
	if (response.status == 200) {
		// $("form[name='EditProviderSubtypeForm'] .alert").show();
		$("#provider_subtype_success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Credential Data Updated Saved Successfully!</strong></div>';
		$("#provider_subtype_success").html(txt);
		$('#edit_provider_subtype_modal').modal('hide');
		$("#EditProviderSubtypeForm")[0].reset();
		renderProvidersSubtypeTable();
		setTimeout(function () {
			$("#provider_subtype_success").hide();
		}, 3000);
	}
	else {
		if (response.data.errors.provider_type_id) {
			$('[name="provider_type_id"]').addClass("is-invalid");
			$('[name="provider_type_id"]').next(".invalid-feedback").html(response.data.errors.provider_type_id);
		} else {
			$('[name="provider_type_id"]').removeClass("is-invalid");
			$('[name="provider_type_id"]').next(".invalid-feedback").html('');
		}

		if (response.data.errors.sub_provider_type) {
			$('[name="sub_provider_type"]').addClass("is-invalid");
			$('[name="sub_provider_type"]').next(".invalid-feedback").html(response.data.errors.sub_provider_type);
		} else {
			$('[name="sub_provider_type"]').removeClass("is-invalid");
			$('[name="sub_provider_type"]').next(".invalid-feedback").html('');
		}
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
	alert('hy!!!!');
	// renderProvidersTable();
	// renderProvidersTypeTable(); 
	// renderProvidersSubtypeTable();
//Provider
$('#addProvider').click(function () {
	$('#add_provider_modal').modal('show');
	$("form[name='AddProviderForm']")[0].reset();
 
	$("form[name='AddProviderForm'] input[name='practice_id']").removeClass('is-invalid');
	$('[name="practice_id"]').next(".invalid-feedback").html('');
 
	$("form[name='AddProviderForm'] input[name='provider_type_id']").removeClass('is-invalid');
	$('[name="provider_type_id"]').next(".invalid-feedback").html('');
 
	$('input[name="name"]').removeClass('is-invalid');
	$('[name="name"]').next(".invalid-feedback").html('');

	$('input[name="email"]').removeClass('is-invalid');
	$('[name="email"]').next(".invalid-feedback").html('');

	$('input[name="address"]').removeClass('is-invalid');
	$('[name="address"]').next(".invalid-feedback").html('');

	$('input[name="phone"]').removeClass('is-invalid');
	$('[name="phone"]').next(".invalid-feedback").html('');


});

$('body').on('click', '.editProvider', function () {
	var id = $(this).data('id');
	var url = URL_POPULATE + "/" + id + "/providerpopulate";
	populateForm(id, url);
	$('#edit_provider_modal').modal('show');
});

//addProvidertype
$('#addProvidertype').click(function () {
	$('#add_provider_type_modal').modal('show');
	
	$('input[name="provider_type"]').removeClass('is-invalid');
	$('[name="provider_type"]').next(".invalid-feedback").html('');

});

//addProviderSpeciality
$('#addspeciality').click(function () {
	$('#add_provider_speciality_modal').modal('show');
	$('input[name="speciality"]').removeClass('is-invalid');
	$('[name="speciality"]').next(".invalid-feedback").html('');

});

$('body').on('click', '.editProviderType', function () {
	var id = $(this).data('id');
	var url = URL_POPULATE_PROVIDER_TYPE + "/" + id + "/providertypepopulate";
	populateForm(id, url);
	$('#edit_provider_type_modal').modal('show');
});

$('body').on('click', '.editSpeciality', function () {
	var id = $(this).data('id');
	var url = URL_POPULATE_PROVIDER_SPECIALITY + "/" + id + "/specialitypopulate";
	populateForm(id, url);
	$('#edit_provider_speciality_modal').modal('show');
});



//provider subtype
$('#addProvidersubtype').click(function () {
	$('#add_provider_subtype_modal').modal('show');
	$('form[name="AddProviderSubtypeForm"] #provider_type_id').removeClass("is-invalid");
	$('form[name="AddProviderSubtypeForm"] #provider_type_id').next(".invalid-feedback").html(' ');
	$('[name="sub_provider_type"]').removeClass("is-invalid");
	$('[name="sub_provider_type"]').next(".invalid-feedback").html('');

});

$('body').on('click', '.editProviderSubtype', function () {
	var id = $(this).data('id');
	var url = URL_POPULATE_PROVIDER_SUBTYPE + "/" + id + "/providersubtypepopulate";
	populateForm(id, url);
	$('#edit_provider_subtype_modal').modal('show');
	$('form[name="EditProviderSubtypeForm"] #provider_type_id').removeClass("is-invalid");
	$('form[name="EditProviderSubtypeForm"] #provider_type_id').next(".invalid-feedback").html('');
	$('[name="sub_provider_type"]').removeClass("is-invalid");
	$('[name="sub_provider_type"]').next(".invalid-feedback").html('');

});
//change status provider
$('body').on('click', '.changeProviderstatus_active', function () {
	var id = $(this).data('id');
	if (confirm("Are you sure you want to Deactivate this Provider")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/changeProviderstatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderProvidersTable();
				$("#provider_success").show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">×</button><strong>Provider Deactivated Successfully!</strong></div>';
				$("#provider_success").html(txt);

				setTimeout(function () {
					$("#provider_success").hide();
				}, 3000);
			}
		});
	} else { return false; }
});
$('body').on('click', '.changeProviderstatus_deactive', function () {
	var id = $(this).data('id');

	if (confirm("Are you sure you want to Activate this Provider")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/changeProviderstatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderProvidersTable();
				$("#provider_success").show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">×</button><strong>Provider Activated Successfully!</strong></div>';
				$("#provider_success").html(txt);

				setTimeout(function () {
					$("#provider_success").fadeOut();
				}, 3000);
			}
		});
	} else { return false; }
});
//change status providertype
$('body').on('click', '.changeProvidertypestatus_active', function () {
	var id = $(this).data('id');
	if (confirm("Are you sure you want to Deactivate this Provider Type")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/changeProvidertypestatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderProvidersTypeTable();
				$("#provider_type_success").show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">×</button><strong>Provider Type Deactivated Successfully!</strong></div>';
				$("#provider_type_success").html(txt);

				setTimeout(function () {
					$("#provider_type_success").hide();
				}, 3000);
			}
		});
	} else { return false; }
});
$('body').on('click', '.changeProvidertypestatus_deactive', function () {
	var id = $(this).data('id');

	if (confirm("Are you sure you want to Activate this Provider Type")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/changeProvidertypestatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderProvidersTypeTable();
				$("#provider_type_success").show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">×</button><strong>Provider Type Activated Successfully!</strong></div>';
				$("#provider_type_success").html(txt);

				setTimeout(function () {
					$("#provider_type_success").hide();
				}, 3000);
			}
		});
	} else { return false; }
});


//change status speciality
$('body').on('click', '.changeSpecialitystatus_active', function () {
	var id = $(this).data('id');
	$('#provider_speciality_success').show();
	if (confirm("Are you sure you want to Deactivate this Speciality")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/changeSpecialitystatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {

				renderSpecialityTable();
				$('#provider_speciality_success').show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">×</button><strong>Speciality Deactivated Successfully!</strong></div>';
				$("#provider_speciality_success").html(txt);

				setTimeout(function () {

					$('#provider_speciality_success').hide();

				}, 3000);
			}
		});
	} else { return false; }
});
$('body').on('click', '.changeSpecialitystatus_deactive', function () {
	var id = $(this).data('id');
	$('#provider_speciality_success').show();
	if (confirm("Are you sure you want to Activate this Speciality")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/changeSpecialitystatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {

				renderSpecialityTable();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">×</button><strong>Speciality Activated Successfully!</strong></div>';
				$("#provider_speciality_success").html(txt);

				setTimeout(function () { //$("#provider_speciality_success").html(txt); 
					$('#provider_speciality_success').hide();
				}, 3000);
			}
		});
	} else { return false; }
});

//change status providersubtype
$('body').on('click', '.changeProvidersubtypestatus_active', function () {
	var id = $(this).data('id');
	if (confirm("Are you sure you want to Deactivate this Credential")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/changeProvidersubtypestatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderProvidersSubtypeTable();
				$('#provider_subtype_success').show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">×</button><strong>Credential Deactivated Successfully!</strong></div>';
				$("#provider_subtype_success").html(txt);

				setTimeout(function () { //$("#provider_subtype_success").html(txt); 
					$('#provider_subtype_success').hide();
				}, 3000);
			}
		});
	} else { return false; }
});
$('body').on('click', '.changeProvidersubtypestatus_deactive', function () {
	var id = $(this).data('id');

	if (confirm("Are you sure you want to Activate this Credential")) {
		$.ajaxSetup({ 
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: '/org/changeProvidersubtypestatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderProvidersSubtypeTable();
				$("#provider_subtype_success").show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">×</button><strong>Credential Activated Successfully!</strong></div>';
				$("#provider_subtype_success").html(txt);

				setTimeout(function () {
					$("#provider_subtype_success").fadeOut();
				}, 3000); 
			}
		});
	} else { return false; }
});


	//Provider
	form.ajaxForm("AddProviderForm", onAddproviderData, function () {
		//renderProvidersTable();
		return true;
	});

	form.ajaxForm("EditProviderForm", onEditproviderData, function () {
		//renderProvidersTable();
		return true;
	});

	//Provider Type
	form.ajaxForm("AddProviderTypeForm", onAddprovidertypeData, function () {
		// renderProvidersTable();
		return true;
	});

	form.ajaxForm("EditProviderTypeForm", onEditprovidertypeData, function () {
		// renderProvidersTable();
		return true;
	});


	//Provider Speciality
	form.ajaxForm("AddProviderSpecialityForm", onAddSpecialityData, function () {
		// renderProvidersTable();
		return true;
	});

	form.ajaxForm("EditProviderSpecialityForm", onEditSpecialityData, function () {
		// renderProvidersTable();
		return true;
	});

	//Provider Sub Type
	// form.ajaxForm("AddProviderSubtypeForm", onAddproviderSubTypeData, function () {
	// 	// renderProvidersSubtypeTable();
	// 	return true;
	// });

	// form.ajaxForm("EditProviderSubtypeForm", onEditproviderSubTypeData, function () {
	// 	// renderProvidersSubtypeTable();
	// 	return true;
	// });

};

$('#updatesubtype').click(function () {
	// alert("testinf");
	form.ajaxSubmit("EditProviderSubtypeForm", onEditproviderSubTypeData, function () { });
});

$('#insertsubtype').click(function () {
	// alert("testinf");
	form.ajaxSubmit("AddProviderSubtypeForm", onAddproviderSubTypeData, function () { });
});

window.providers = {
	init: init,
	onResult: onResult
};
