const URL_POPULATE = "/reports/ajax/shippingreports_populate";

var devicecodecount = 0;
var populateForm = function (id, url) {
	$.get(
		url,
		id,
		function (result) {
			console.log(result);
			for (var key in result) { console.log(result[key]);
				form.dynamicFormPopulate(key, result[key]);
					if (result[key]!= null && result[key]!= '' && result[key].length!='0') {

						console.log(result[key].length + "count");
						if(result[key][0].shipping_status!=null && result[key][0].shipping_status!=''){ 
							var statusValue = result[key][0].shipping_status;
							$('#shipping_status option[value="' + statusValue + '"]').prop('selected', true);
						}else {
							var statusValue = '3';
							$('#shipping_status option[value="' + statusValue + '"]').prop('selected', true);
						}
						
						if(result[key][0].courier_service_provider!=null && result[key][0].courier_service_provider!=''){
							$('#courier_service_provider').val(result[key][0].courier_service_provider);
						}else {
							$('#courier_service_provider').val('');
						}

						if(result[key][0].welcome_call!=null && result[key][0].welcome_call!=''){
							if (result[key][0].welcome_call == 0) {
								$('#shipping_form input[name="status"][value="0"]').prop('checked', true);
							} else if (result[key][0].welcome_call == 1) {
								$('#shipping_form input[name="status"][value="1"]').prop('checked', true);
							}
						}else{
							$('#shipping_form input[name="status"][value="0"]').prop('checked', true);
						}

						if(result[key][0].shipping_date!=null && result[key][0].shipping_date!=''){
							console.log(result[key][0].shipping_date);
							console.log("if");
							var date = result[key][0].shipping_date;
							var date = new Date(date);
							var formattedDate = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
							$('#shipping_form input[name="shipping_date"]').val(formattedDate);
						}else {
							console.log("else");
							$('#shipping_form input[name="shipping_date"]').val('');
						}
					} else{
					var statusValue = '3';
					$('#shipping_status option[value="' + statusValue + '"]').prop('selected', true);
					$('#courier_service_provider').val('');
					$('#shipping_form input[name="status"][value="0"]').prop('checked', true);
					$('#shipping_form input[name="shipping_date"]').val('');
				}
			}
		}

	).fail(function (result) {
		console.error("Population Error:", result);
	}); 

};

var onAddEnrolledShippingData = function (formObj, fields, response) {
	if (response.status == 200) { 
		$("#success").show();
		// renderReportsMasterTable();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Shipping Details Added Successfully!</strong></div>';
	
		$("#shipping_form")[0].reset();
		$('#shippingdetailmodel').modal('hide');
		$("#success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () {
			$("#success").hide(); 
		}, 3000); 
        getrefreshtable();

	} 
};

var onAdddeviceData = function (formObj, fields, response) {
	if (response.status == 200) { 
		console.log(response);
		$("#success").show();
		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Device Added Successfully!</strong></div>';
		$("#device_form")[0].reset();
		$('#devicedetailsmodel').modal('hide');
		$("#success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () {
			$("#success").hide(); 
		}, 3000); 
		getrefreshtable();
	}  
	// else if (response.status == 400) {
    //     console.log(response); 
	// 	$("#error").show();
	// 	var txt = '<div class="alert alert-error alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>This Device Code already added!</strong></div>';
    //     var errorMessage = response.responseJSON.error;
	// 	$('#error').html(errorMessage);
	// 	setTimeout(function () {
	// 		$("#success").hide(); 
	// 	}, 3000); 
	// }
};

$('body').on('click', '.change_device_status_active', function () {
	var id = $(this).data('id');
	var patient = $(this).data('additional-id');
	if(confirm("Are you sure you want to Deactivate this Device")){
	   $.ajaxSetup({
		   headers: {
			   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		   }
	   });
		 $.ajax({
		   type   : 'post',
		   url    : '/reports/delete-devices/'+id,
		  // data: {"_token": "{{ csrf_token() }}","id": id},
		   data   :  {"id": id},
		   success: function(response) {
			//    getdevicecode(patient);
			   var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Device Deactivated Successfully!</strong></div>';
			   $("#success").html(txt);
		   }
	   });
   }else{ return false;}
});
$('body').on('click', '.change_device_status_deactive', function () {
	var id = $(this).data('id');
	var patient = $(this).data('additional-id');
	if(confirm("Are you sure you want to Activate this Device")){
	   $.ajaxSetup({
		   headers: {
			   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		   }
	   });
		 $.ajax({
		   type   : 'post',
		   url    : '/reports/delete-devices/'+id,
		  // data: {"_token": "{{ csrf_token() }}","id": id},
		   data   :  {"id": id},
		   success: function(response) {
			//    getdevicecode(patient);
			   var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Device Activated Successfully!</strong></div>';
			   $("#success").html(txt);
		   }
	   });
   }else{ return false;}
});

$('body').on('click', '.shippingdetail', function () {
    $("form[name='shipping_form'] #courier_service_provider").removeClass('is-invalid');
    $("form[name='shipping_form'] #courier_service_provider").next('.invalid-feedback').html('');

    $("form[name='shipping_form'] #shipping_date").removeClass('is-invalid');
    $("form[name='shipping_form'] #shipping_date").next('.invalid-feedback').html('');

    // $("form[name='shipping_form'] #shipping_status").removeClass('is-invalid');
    // $("form[name='shipping_form'] #shipping_status").next('.invalid-feedback').html('');

    $("form[name='shipping_form'] #status").removeClass('is-invalid');
    $("form[name='shipping_form'] #status").next('.invalid-feedback').html('');
	
	$("#shipping_form")[0].reset();
	
    $('#shippingdetailmodel').modal('show');
    var id = $(this).data('id');
	var shipping_status = $('#shippingstatus').val(); 
	
	if(shipping_status ==''){
		shipping_status='0';
	}
    console.log("ID:", id);
    console.log("Shipping Status:", shipping_status);
    $("#id").val(id);
	getshippinglist(id,shipping_status);
	util.pateintdevicecode(parseInt(id),$("#device_id"));
});

$("form[name='shipping_form'] #device_id").on("change", function () {
	const patinet_id = $('#patientIdField').val();
	var device_code = $(this).val()
	if(device_code==''){
		device_code='0';
	}
	var data = "";
    var formpopulateurl = URL_POPULATE + "/" + patinet_id + "/" + device_code +"/populate";
    console.log(formpopulateurl);
    populateForm(data, formpopulateurl);
   
});

var init = function () {
	form.ajaxForm("shipping_form", onAddEnrolledShippingData, function () {
		return true;
    });

	form.ajaxForm("device_form", onAdddeviceData, function () {
		return true;
    });

	$('#additionalcode').click(function () {
		devicecodecount++;
		$('#appendcode').append('<div class="btn_remove" id="btn_removecode_' + devicecodecount + '" style="margin-bottom: 10px;"><input type="text" class="form-control col-md-11" name ="code[]" id ="code_' + devicecodecount + '" placeholder ="Enter code"><div class="invalid-feedback"></div><i class="remove-icons i-Remove float-right mb-3" id="remove_code_' + devicecodecount + '" title="Remove Code" style="margin-top: -26px;"></i><div class="error_msg" style="display:none;color:red">Please Enter Code</div></div>');
	});

};




window.enrolledShippingReport = {
	init: init
	//onResult: onResult
};