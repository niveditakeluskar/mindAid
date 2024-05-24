
/**
 * 
 */
const URL_POPULATE = "/org/ajax/populateResponsibilityForm";


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
				// var module_id = $('#module_id').find(":selected").val();
				//    if(!module_id){ 
				//    		util.updateSubModuleList(parseInt(module_id), "#component_id"); 
				// 	};

			}
		}
	).fail(function (result) {
		console.error("Population Error:", result);
	});

};

var onEditResponsibilityMainForm = function (formObj, fields, response) {
	if (response.status == 200) {
		$(".alert").show();
		$("#text").html('Responsibility Updated Successfully!');
		$("#edit_responsibility_modal").modal('hide');
		getresponsibilitylisting();
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () { 
		$('.alert-success').fadeOut();
		//goToNextStep("drug-icon-pill"); 
		}, 3000);
	}
};

var onResponsibilityMainForm = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#main_responsibility_form")[0].reset();
		$(".alert").show();
		$("#text").html('Responsibility Added Successfully!');
		$("#add_responsibility_modal").modal('hide');
		getresponsibilitylisting();
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () { 
		$('.alert-success').fadeOut();
		//goToNextStep("drug-icon-pill"); 
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
	getresponsibilitylisting();
	form.ajaxForm("main_responsibility_form", onResponsibilityMainForm, function () {
		return true;
	});
	
	form.ajaxForm("main_edit_responsibility_form", onEditResponsibilityMainForm, function () {
		return true;
	});	
	

	$('body').on('click', '.addResponsibility', function () {
	    $("#main_responsibility_form")[0].reset();
	    $('#add_responsibility_modal').modal('show');
	    
	});
	

	$('body').on('click', '.editResponsibility', function () {
	    $("#main_edit_responsibility_form")[0].reset();
	    var id = $(this).data('id');
	    if(id!=''){
	    	$('#saveBtn').html("Update");
	    } 
	    var data = "";
	        $('#edit_responsibility_modal').modal('show');
	    var formpopulateurl = URL_POPULATE + "/" + id;
	    populateForm(data, formpopulateurl);
	});

	
	$('body').on('click', '.change_responsibilitystatus_active', function () {
     	var id = $(this).data('id');
     	if(confirm("Are you sure you want to Deactivate this Responsibility")){
	        $.ajaxSetup({ 
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
      		$.ajax({
	            type   : 'post',
	            url    : '/org/delete-responsibility/'+id,
	           // data: {"_token": "{{ csrf_token() }}","id": id},
	            data   :  {"id": id},
	            success: function(response) {
	            	getresponsibilitylisting();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Responsibility Deactivated Successfully!</strong></div>';
	        		$("#success").html(txt);
	        		setTimeout(function () { $('.alert-success').fadeOut();
					//goToNextStep("drug-icon-pill"); 
					}, 3000);
	            }
        	});
	    }else{ return false;}
	});
	$('body').on('click', '.change_responsibilitystatus_deactive', function () {
     	var id = $(this).data('id');

     	if(confirm("Are you sure you want to Activate this Responsibility")){
	        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
      		$.ajax({
	            type   : 'post',
	            url    : '/org/delete-responsibility/'+id,
	           // data: {"_token": "{{ csrf_token() }}","id": id},
	            data   :  {"id": id},
	            success: function(response) {
	            	getresponsibilitylisting();
					var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Responsibility Activated Successfully!</strong></div>';
	        		$("#success").html(txt);
	        		setTimeout(function () { $('.alert-success').fadeOut();
					//goToNextStep("drug-icon-pill"); 
					}, 3000);
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

window.responsibility = {
	init: init, 
	onResult: onResult
};

 