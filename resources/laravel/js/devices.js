
/**
 * 
 */
const URL_POPULATE = "/org/ajax/populateDeviceForm";


/**
 * Populate the form of the given patient
 *
 * 
 */
var populateForm = function (data, url) {

	$.get(
		url, 
		data,
		function (result) {
			console.log(result);
			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);
				updateBmi();

			}
		}
	).fail(function (result) {
		console.error("Population Error:", result);
	});

};

var onEditDeviceMainForm = function (formObj, fields, response) {
	if (response.status == 200) {
		$(".alert").show();
		$("#text").html('Device  Updated Successfully!');
		$("#edit_device_modal").modal('hide');
		getdevicelisting();
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () { //goToNextStep("drug-icon-pill"); 
		}, 3000);
	}
};

var onDeviceMainForm = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#main_devices_form")[0].reset();
		$(".alert").show();
		$("#text").html('Device Added Successfully!');
		$("#add_device_modal").modal('hide');
		getdevicelisting();
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () { //goToNextStep("drug-icon-pill"); 
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
var init = function () {
	getdevicelisting();
	form.ajaxForm("main_devices_form", onDeviceMainForm, function () {
		return true;
	});
	
	form.ajaxForm("main_edit_devices_form", onEditDeviceMainForm, function () {
		return true;
	});	
	

	$('body').on('click', '.addDevice', function () {
	    $("#main_devices_form")[0].reset();
	    $('#add_device_modal').modal('show');
	    
	});
	

	$('body').on('click', '.editDevice', function () {
	    $("#main_edit_devices_form")[0].reset();
	    $('#edit_device_modal').modal('show');
	    var sPageURL = window.location.pathname;
	    parts = sPageURL.split("/"),
	        id = parts[parts.length - 1];
	    var patientId = $(this).data('id');;

	    var data = "";
	    var formpopulateurl = URL_POPULATE + "/" + patientId;
	    populateForm(data, formpopulateurl);
	});

	
	$('body').on('click', '.change_status_active', function () {
     	var id = $(this).data('id');
     	if(confirm("Are you sure you want to Deactivate this Device")){
	        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
      		$.ajax({
	            type   : 'post',
	            url    : '/org/delete-devices/'+id,
	           // data: {"_token": "{{ csrf_token() }}","id": id},
	            data   :  {"id": id},
	            success: function(response) {
	            	getdevicelisting();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Device Deactivated Successfully!</strong></div>';
	        		$("#success").html(txt);
	            }
        	});
	    }else{ return false;}
	});
	$('body').on('click', '.change_status_deactive', function () {
     	var id = $(this).data('id');

     	if(confirm("Are you sure you want to Activate this Device")){
	        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
      		$.ajax({
	            type   : 'post',
	            url    : '/org/delete-devices/'+id,
	           // data: {"_token": "{{ csrf_token() }}","id": id},
	            data   :  {"id": id},
	            success: function(response) {
	            	getdevicelisting();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Device Activated Successfully!</strong></div>';
	        		$("#success").html(txt);
	            }
        	});
    	}else{ return false;}
	});

	
	// var sPageURL = window.location.pathname;
	// parts = sPageURL.split("/"),
	// 	id = parts[parts.length - 1];
	// var patientId = id;

	// var data = "";
	// var formpopulateurl = URL_POPULATE + "/" + patientId;
	// populateForm(data, formpopulateurl);
	
};

window.devices = {
	init: init,
	onResult: onResult
};

 