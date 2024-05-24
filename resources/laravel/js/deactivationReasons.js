const URL_POPULATE = "/org/ajax/DeactivationReasons_populate";

var populateForm = function (id, url) {
	$.get(
		url,
		id,
		function (result) {
			console.log(result);
			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);
                console.log(result[key] + 'reasons');
				if(result[key].static['reasons'] != null){ 
					var reasons = result[key].static['reasons']; 
					$('#deactivationReasons_form input[name="reasons"]').val(reasons);
				}
			}
		}
 
	).fail(function (result) {
		console.error("Population Error:", result);
	}); 

};

var onAddDeactivationReasonsData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#success").show();
		renderDeactivationReasons();
		if($("#modelHeading1").text()=='Add Deactivation Reasons'){
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Deactivation Reasons Added Successfully!</strong></div>';
		}else{
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Deactivation Reasons Updated Successfully!</strong></div>';
		}
		$("#deactivationReasons_form")[0].reset();
		$('#add_deactivationReasons_modal').modal('hide');
		
		$("#success").html(txt); 
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos); 
		setTimeout(function () {
			$("#success").hide(); 
		}, 3000); 

	} 
    // else{
    //     var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    //     $("#success").html(txt);
    //     $("#success").show();
    //     setTimeout(function () {
    //         $("#success").hide();
    //     }, 3000);
    // }
};

$('body').on('click', '.editReasons', function () {
$("form[name='deactivationReasons_form'] #reasons").removeClass('is-invalid');
$("form[name='deactivationReasons_form'] #reasons").next('.invalid-feedback').html('');
$("#modelHeading1").text('Edit deactivation Reasons');
$('#add_deactivationReasons_modal').modal('show');

var id = $(this).data('id'); 
var data = ""; 
// var reasons_id = $('#reasons_id').val(id);
var formpopulateurl = URL_POPULATE + "/" + id +"/populate";
populateForm(data, formpopulateurl);
});

 
$('#addDeactivationReasons').click(function () {
    // debugger;
    $("form[name='deactivationReasons_form'] #reasons").removeClass('is-invalid');
    $("form[name='deactivationReasons_form'] #reasons").next('.invalid-feedback').html('');
    $("#modelHeading1").text('Add Deactivation Reasons');
    $("#deactivationReasons_form")[0].reset();
    $('#add_deactivationReasons_modal').modal('show');
    $('#reasons_id').val("");
}); 

$('body').on('click', '.change_deactivationReasons_status_active', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Deactivate this Reason")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/delete-DeactivationReasons/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    renderDeactivationReasons(); 
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Reason Deactivated Successfully!</strong></div>';
                    $("#success").html(txt);
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { return false; } 
    });
    $('body').on('click', '.change_deactivationReasons_status_deactive', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Activate this Reason")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/delete-DeactivationReasons/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) { 
                    renderDeactivationReasons();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Reason Activated Successfully!</strong></div>';
                    $("#success").html(txt);
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { return false; }
    }); 

var init = function () {
	form.ajaxForm("deactivationReasons_form", onAddDeactivationReasonsData, function () {
		return true;
	}); 
};



window.deactivationReasons= {
	init: init
	//onResult: onResult
};