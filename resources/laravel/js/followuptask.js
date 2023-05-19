const URL_POPULATE = "/org/ajax/FollowupTask_populate";


var populateForm = function (id, url) {
	$.get(
		url,
		id,
		function (result) {
			console.log(result);
			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);
				if(result[key].static['task'] != null){
					var task = result[key].static['task']; 
					$('#followuptask_form input[name="task"]').val(task);
				}
			}
		}

	).fail(function (result) {
		console.error("Population Error:", result);
	}); 

};

var onAddFollowupTaskData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#success").show();
		renderFollowupTaskTable();
		if($("#modelHeading1").text()=='Add Followup Task'){
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Follow Up Task Added Successfully!</strong></div>';
		}else{
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Follow Up Task Updated Successfully!</strong></div>';
		}
		$("#followuptask_form")[0].reset();
		$('#add_followuptask_modal').modal('hide');
		
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

// var onEditDiagnosisMainForm = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("#success").show();
// 		renderFollowupTaskTable();
// 		$("#followuptask_form")[0].reset();
// 		$('#add_followuptask_modal').modal('hide');
// 		var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Follow Up Task Updated Successfully!</strong></div>';
// 		$("#success").html(txt);
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$("#success").hide();
// 		}, 3000); 

// 	} else{
//         var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
//         $("#success").html(txt);
//         $("#success").show();
//         setTimeout(function () {
//             $("#success").hide();
//         }, 3000);
//     }
// };

$('body').on('click', '.editTask', function () {
$("form[name='followuptask_form'] #task").removeClass('is-invalid');
$("form[name='followuptask_form'] #task").next('.invalid-feedback').html('');
$("#modelHeading1").text('Edit Followup Task');
$('#add_followuptask_modal').modal('show');
var id = $(this).data('id');
var data = "";
var formpopulateurl = URL_POPULATE + "/" + id +"/populate";
populateForm(data, formpopulateurl);
});


$('#addFollowupTask').click(function () {
    // debugger;
    $("form[name='followuptask_form'] #task").removeClass('is-invalid');
    $("form[name='followuptask_form'] #task").next('.invalid-feedback').html('');
    $("#modelHeading1").text('Add Followup Task');
    $("#followuptask_form")[0].reset();
    $('#add_followuptask_modal').modal('show');
    $('#id').val("");
});

$('body').on('click', '.change_followupTask_status_active', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Deactivate this Task")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/delete-FollowupTask/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    renderFollowupTaskTable();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Followup TaskDeactivated Successfully!</strong></div>';
                    $("#success").html(txt);
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { return false; }
    });
    $('body').on('click', '.change_followupTask_status_deactive', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Activate this Task")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/delete-FollowupTask/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    renderFollowupTaskTable();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Followup Task Activated Successfully!</strong></div>';
                    $("#success").html(txt);
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { return false; }
    });

var init = function () {
	form.ajaxForm("followuptask_form", onAddFollowupTaskData, function () {
		return true;
	}); 
	
	// form.ajaxForm("followuptask_form", onEditDiagnosisMainForm, function () {
	// 	return true;
	// }); 
};




window.followuptask = {
	init: init
	//onResult: onResult
};