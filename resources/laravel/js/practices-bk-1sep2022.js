/**
 * 
 */
const URL_POPULATE = "/org/ajax/practice_populate";
const URL_PRACTICE_GRP_POPULATE = '/org/ajax/practice_group_populate';
const URL_PRACTICE_THRESHOLS_POPULATE = '/org/ajax/practice_threshold_populate';
const URL_ORG_THRESHOLS_POPULATE = '/org/ajax/org_threshold_populate';
const URL_DOCUMENT_POPULATE = '/org/ajax/org_document_populate';
Vue.config.productionTip = false;
Vue.config.devtools = false;
/**
 * Populate the form of the given patient
 *
 * 
 */
var providercount = 0;
var populateForm = function (id, url) {
	//alert("url"+url);
	$.get(
		url,
		id,
		function (result) {
			// console.log(result);

			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);
				if (key == 'AddPracticeForm') {
					if (result[key].static.logo == '' || result[key].static.logo == null) {
						$('#viewlogo').html('');
						$('#image_path').val('');
						//$('#viewlogo').prepend('<img id="img" src="/assets/images/faces/avatar.png"  width="100px" height="100px"/>')
					} else {
						$('#viewlogo').html('<img id="img" src="/PracticeLogo/' + result[key].static.logo + '"  width="100px" height="80px"/>')					} 
					if (result[key].static.practice_type == '' || result[key].static.practice_type == null) {
						$("form[name='review_provider_pcp_form'] #practices option:selected").val(result[key].static.practice_type);
						$("form[name='provider_pcp_form'] #practices option:selected").val(result[key].static.practice_type);
					}
 
				}

				// alert(key+'key'); 
				if (key == 'AddDocumentForm') {
					//debugger;
					//alert(result[key].static.doc_content);
					if (result[key].static.doc_content != '' || result[key].static.doc_content != null) {
						//alert(result[key].static.doc_content);
						$("form[name='" + key + "'] #doc_filecontent").html(result[key].static.doc_content);
						$("form[name='" + key + "'] #exist_docs").val(result[key].static.doc_content);
						$("form[name='" + key + "'] #uploadfile").val(result[key].static.doc_content); 
						$('#viewdocs').html('<a href="/practice-provider-documents/'+result[key].static.doc_content+'">View Document</a>')
					}

					practice = result[key].static.practice_id;
					if (result[key].static.hasOwnProperty('practice_id')) {
					    $("form[name='" + key + "'] #practices option:selected").val(practice);
					}

					provider = result[key].static.provider_id
					if (result[key].static.provider_id != '' || result[key].static.provider_id != null) {
						$("form[name='" + key + "'] #providers option:selected").val(provider);
					}
					// if (result[key].static.doc_content != '' || result[key].static.doc_content != null) {
					// 	//$('#uploadfile').val(result[key].static.file);
					// 	$('#doc_content').val(result[key].static.doc_content);
					// 	$('file').val(result[key].static.doc_content);
					// 	$('#viewdocs').html('<a href="/practice-provider-documents/'+result[key].static.doc_content+'">Download Document</a>')
					// } else{
					// 	$('#viewdocs').html('');
					// 	$('#doc_content').val('');
					// }
					// practice = result[key].static.practice_id;
					// if (result[key].static.hasOwnProperty('practice_id')) {
					//     $("form[name='" + key + "'] #practices option:selected").val(practice);
					// }
					// if (result[key].static.provider_id != '' || result[key].static.provider_id != null) {
					// 	$("form[name='AddDocumentForm'] #physician option:selected").val(result[key].static.provider_id);
					// }
					// //alert(result[key].static.doc_type+'doc_Type');
					// documents = result[key].static.doc_type;
					// if (result[key].static.hasOwnProperty('doc_type')) {
					//     $("form[name='" + key + "'] #doc_id option:selected").val(documents);
					// }
				}

				// var providercnt = providerdata.length;
				// for (providercount = 0; providercount < providercnt; providercount++) {

				// 	$('#appendprovider').append('<div class="btn_remove row" id="btn_provider_' + providercount + '"><div class="col-md-4"><label for="practicename">Provider </label><input type="text" class="col-md-12 form-control" name ="providers[]" id ="provider_' + providercount + '" value="' + providerdata[providercount]['name'] + '" placeholder ="Enter Provider "></div>');


				// 	var providertype = $('#providertype').clone().prop('id', 'providertype_' + providercount).removeClass('col-md-10');
				// 	$('#btn_provider_' + providercount).append('<div class="col-md-3"> <label for="practicename">Provider Type</label><div id="ptype' + providercount + '"></div></div>');
				// 	providertype.find("option[value = '" + providerdata[providercount]['provider_type_id'] + "']").attr("selected", "selected");
				// 	$('#ptype' + providercount).append(providertype);


				// 	var providersubtype = $('#provider_subtype').clone().prop('id', 'provider_subtype_' + providercount).removeClass('col-md-10');
				// 	$('#btn_provider_' + providercount).append('<div class="col-md-4"> <label for="practicename">Provider Subtype</label><div id="psubtype' + providercount + '"></div></div>');
				// 	providersubtype.find("option[value = '" + providerdata[providercount]['provider_subtype_id'] + "']").attr("selected", "selected");
				// 	$('#psubtype' + providercount).append(providersubtype);


				// 	$('#btn_provider_' + providercount).append('<div class="invalid-feedback"></div><i class="remove-icons i-Remove float-right mb-3" id="remove_provider_' + providercount + '" title="Remove Provider" style="margin-top: 25px;"></i><div class="error_msg" style="display:none;color:red">Please Enter Provider</div></div>');
				//}
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

var onAddpracticeData = function (formObj, fields, response) {
	// console.log(response.data);
	if (response.status == 200) {
		$("#AddPracticeForm")[0].reset();
		//$("form[name='AddPracticeForm'] .alert").show();
		$('#add_practice_modal').modal('hide');
		$('#viewlogo').html('');
		$('#image_path').val('');
		renderPracticeTable();
		$("#success").show();
		if ($.trim(response.data) == "edit") {
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice updated successfully!</strong></div>';
		}
		else {
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice added successfully!</strong></div>';
		}
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

// var onAddDocumentData = function (formObj, fields, response) {
// 	//console.log(response.data);
// 	if (response.status == 200) {
// 		$("#AddDocumentForm")[0].reset();
// 		$('#add_docs_modal').modal('hide');
// 		renderDocsTable();
// 		$("#success").show();
// 		if ($.trim(response.data) == "edit") {
// 			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice updated successfully!</strong></div>';
// 		}
// 		else {
// 			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice added successfully!</strong></div>';
// 		}
// 		$("#success").html(txt);
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		//goToNextStep("call_step_1_id");
// 		setTimeout(function () {
// 			$("#success").hide();
// 		}, 3000);
// 	}
// };

// var onEditpracticeData = function (formObj, fields, response) {
// 	// console.log(response);
// 	if (response.status == 200) {
// 		$("form[name='EditpracticeForm'] .alert").show();
// 		$('#edit_practice_modal').modal('hide');
// 		$('#viewlogo').html('');
// 		$('#image_path').val('');
// 		renderPracticeTable();
// 		$("#EditpracticeForm")[0].reset();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		//goToNextStep("call_step_1_id");
// 		setTimeout(function () {
// 			$("form[name='EditpracticeForm'] .alert").hide();//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 	} else {
//         var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
//         $("#success").html(txt);
//         $("#success").show();
//         setTimeout(function () {
//             $("#success").hide();
//         }, 3000);
//     }
// };

// $(function () {
//     $("#ddlPassport").change(function () {
//         if ($(this).val() == "0") {
//             $("#dvPassport").show();
//         } else {
//             $("#dvPassport").hide();
//         }
//     });
// });

var onAddpracticeGrpData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#AddPracticeGrpForm")[0].reset();
		// $("form[name='AddPracticeGrpForm'] #success-alert").show();
		$('#add_practice_grp_modal').modal('hide');
		renderPracticeGrpTable();
		$("#practice-grp-success").show();
		var dynamicname = $("#msg").val();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>' + dynamicname + ' added successfully!</strong></div>';
		$("#practice-grp-success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#practice-grp-success").hide();
		}, 3000);
	}
	// else {
	//        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
	//        $("#practice-grp-success").html(txt);
	//        $("#practice-grp-success").show();
	//        setTimeout(function () {
	//            $("#practice-grp-success").hide();
	//        }, 3000);
	//    }
};

var onAddpracticethreshold = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#practice-threshold-success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice Threshold added successfully!</strong></div>';
		$("#practice-threshold-success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#practice-threshold-success").hide();
		}, 3000);
	}
};

var onAddorgthreshold = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#org-threshold-success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Org Threshold added successfully!</strong></div>';
		$("#org-threshold-success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		//goToNextStep("call_step_1_id");
		setTimeout(function () {
			$("#org-threshold-success").hide();
		}, 3000);
	}
};
var onResult = function (form, fields, response, error) {
	if (error) {
	}
	else {
		window.location.href = response.data.redirect;
	}
};


//editaddition
function clearBox(removeChild) {
	$(removeChild).parentsUntil('.rowItem').html('');
}

var init = function () {

	$('body').on('click', '.editPractices', function () {
		$('#viewlogo').html('');
		$('#image_path').val('');
		var id = $(this).data('id');
		$('#modelHeading1').html("Edit Practice");
		$('#buttonHeading1').html("Update");
		$('#add_practice_modal').modal('show');
		var url = URL_POPULATE + "/" + id + "/populate";
		//alert(url);
		populateForm(id, url);

	});
	$('body').on('click', '.editDocs', function () {
		// $('#viewdocs').html('');
		// $('#doc_filecontent').val('');
		var id = $(this).data('id');
		$('#modelHeading1').html("Edit Document");
		// $('#uploaddocfile').html("Update");
		$('#add_docs_modal').modal('show');
		var url = URL_DOCUMENT_POPULATE + "/" + id + "/populate";
		//alert(asss);
		populateForm(id, url);

	});

	$('body').on('click', '.editPracticesGroup', function () {
		var id = $(this).data('id');
		$('#spanHeading1').html("Edit");
		$("#btnHeading1").html("Update");
		$('#add_practice_grp_modal').modal('show');
		var url = URL_PRACTICE_GRP_POPULATE + "/" + id + "/grp_prac_populate";
		//alert(url);
		populateForm(id, url);

	});

	$('body').on('click', '.practicethreshold', function () {
		var id = $(this).data('id');
		$('#prid').val(id);
		$('#practice_threshold_modal').modal('show');
		var url = URL_PRACTICE_THRESHOLS_POPULATE + "/" + id + "/populate";
		//alert(url);
		populateForm(id, url);
	});

	$('body').on('click', '.orgthreshold', function () {
		var id = $(this).data('id');
		$('#orgid').val(id);
		$('#org_threshold_modal').modal('show');
		var url = URL_ORG_THRESHOLS_POPULATE + "/" + id + "/populate";
		//alert(url);
		populateForm(id, url);
	});

	$('body').on('click', '.change_docstatus_active', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Deactivate this Document")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'get',
				url: '/org/changeDocumentStatus/' + id,
				data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderDocsTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Report Deactivated Successfully!</strong></div>';
					$("#success").html(txt);
					setTimeout(function () {
						$("#success").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});

	$('body').on('click', '.change_docstatus_deactive', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Activate this Document")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'get',
				url: '/org/changeDocumentStatus/' + id,
			    data: {"_token": "{{ csrf_token() }}","id": id},
			   data: { "id": id },
				success: function (response) {
					//dd(data);
					renderDocsTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Report Activated Successfully!</strong></div>';
					$("#success").html(txt);
					setTimeout(function () {
						$("#success").hide();
					}, 3000);
				}
			});
		} else { return false; }
	});
	
	$('body').on('click', '.change_practicestatus_active', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Deactivate this Practice")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'get',
				url: '/org/changePracticeStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderPracticeTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice Deactivated Successfully!</strong></div>';
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

	$('body').on('click', '.change_practicestatus_deactive', function () {
		var id = $(this).data('id');

		if (confirm("Are you sure you want to Activate this Practice")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'get',
				url: '/org/changePracticeStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderPracticeTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice Activated Successfully!</strong></div>';
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

	$('body').on('click', '.change_practicegrpstatus_active', function () {
		var id = $(this).data('id');
		if (confirm("Are you sure you want to Deactivate this Practice")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'get',
				url: '/org/changePracticeGrpStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderPracticeGrpTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice Deactivated Successfully!</strong></div>';
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

	$('body').on('click', '.change_practicegrpstatus_deactive', function () {
		var id = $(this).data('id');

		if (confirm("Are you sure you want to Activate this Practice")) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'get',
				url: '/org/changePracticeGrpStatus/' + id,
				// data: {"_token": "{{ csrf_token() }}","id": id},
				data: { "id": id },
				success: function (response) {
					renderPracticeGrpTable();
					$("#success").show();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Practice Activated Successfully!</strong></div>';
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



	form.ajaxForm("AddPracticeForm", onAddpracticeData, function () {
		return true;
	});
	
	
	//ajax wala code
	// form.ajaxForm("AddDocumentForm", onAddDocumentData, function () {
	// 	return true;
	// });

	// form.ajaxForm("EditpracticeForm", onEditpracticeData, function () {
	// 	return true;
	// });


	form.ajaxForm("AddPracticeGrpForm", onAddpracticeGrpData, function () {
		return true;
	});

	form.ajaxForm("practice_threshold_form", onAddpracticethreshold, function () {
		return true;
	});

	form.ajaxForm("org_threshold_form", onAddorgthreshold, function () {
		return true;
	});

	$(document).on("click", ".remove-icons", function () {
		var button_id = $(this).closest('div').attr('id');
		$('#' + button_id).remove();
	});

	$('#addPractice').click(function () {
		$('#viewlogo').html('');
		$('#image_path').val('');
		$('#id').val("");
		$("#AddPracticeForm")[0].reset();
		$("#billable-yes").click();
		$("#partner_id").val(1);
		$('#modelHeading1').html("Add Practice");
		$('#buttonHeading1').html("Save");
		$('#add_practice_modal').modal('show');
		//alert('1');
	});
	$('#add_docs').click(function () {
		//debugger; 
		$("#AddDocumentForm")[0].reset();
		$('#viewdocs').html('');
		$('#doc_filecontent').val('');
		$('#id').val('');
		$('#modelHeading1').html("Add Document");
		$('#buttonHeading1').html("Save");
		$('#add_docs_modal').modal('show');
		//alert('1');
	});

	$('#addPracticeGrp').click(function () {
		$('#id').val("");
		$("#AddPracticeGrpForm")[0].reset();
		$('#spanHeading1').html("Add");
		$('#btnHeading1').html("Save");
		$("form[name='AddPracticeGrpForm'] input[name='practice_name']").removeClass('is-invalid')
		$('.invalid-feedback').html('');
		$('#add_practice_grp_modal').modal('show');
	});


};

window.practices = {
	init: init,
	onResult: onResult
};