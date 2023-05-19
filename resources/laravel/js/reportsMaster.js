const URL_POPULATE = "/org/ajax/reports_populate";


var populateForm = function (id, url) {
	$.get(
		url,
		id,
		function (result) {
			console.log(result);
			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);
				if(result[key].static['display_name'] != null){
					var display_name = result[key].static['display_name']; 
					var report_path_name = result[key].static['report_file_path']; 
					// $('#ReportNameForm input[name="report_name"]').val(report_name);
					$('#ReportNameForm input[name="display_name"]').val(display_name);
					$('#ReportNameForm input[name="report_file_path"]').val(report_path_name);       

				}
			}
		}

	).fail(function (result) {
		console.error("Population Error:", result);
	}); 

};

var onAddReportsMasterData = function (formObj, fields, response) {
	if (response.status == 200) {
		$("#success").show();
		renderReportsMasterTable();
		if($("#modelHeading1").text()=='Add Reports Name'){
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Reports Added Successfully!</strong></div>';
		}else{
			var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Reports Updated Successfully!</strong></div>';
		}
		$("#ReportNameForm")[0].reset();
		$('#add_reportsmaster_modal').modal('hide');
		$("#success").html(txt);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () {
			$("#success").hide(); 
		}, 3000); 

	} 
};


$('body').on('click', '.edit', function () {
// $("form[name='ReportNameForm'] #report_name").removeClass('is-invalid');
// $("form[name='ReportNameForm'] #report_name").next('.invalid-feedback').html('');
$("form[name='ReportNameForm'] #display_name").removeClass('is-invalid');
$("form[name='ReportNameForm'] #display_name").next('.invalid-feedback').html('');
$("#modelHeading1").text('Edit Report Master');
$('#add_reportsmaster_modal').modal('show');
var id = $(this).data('id');
$("#id").val(id);
var data = "";
var formpopulateurl = URL_POPULATE + "/" + id +"/populate";
populateForm(data, formpopulateurl);
});


$('#addReports').click(function () {
    // debugger;
    // $("form[name='ReportNameForm'] #report_name").removeClass('is-invalid');
    // $("form[name='ReportNameForm'] #report_name").next('.invalid-feedback').html('');
	$("form[name='ReportNameForm'] #display_name").removeClass('is-invalid');
    $("form[name='ReportNameForm'] #display_name").next('.invalid-feedback').html('');
    $("#modelHeading1").text('Add Reports Name');
    $("#ReportNameForm")[0].reset();
    $('#add_reportsmaster_modal').modal('show');
    $('#id').val("");
});



var init = function () {
	form.ajaxForm("ReportNameForm", onAddReportsMasterData, function () {
		return true;
    });
	
$('body').on('click', '.change_Report_active', function () {
	var id = $(this).data('id');
	if (confirm("Are you sure you want to Deactivate this Report")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/org/ReportsStatus/' + id,
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "id": id },
			success: function (response) {
				renderReportsMasterTable();
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

$('body').on('click', '.change_Report_deactive', function () {
	var id = $(this).data('id');
	if (confirm("Are you sure you want to Activate this Report")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/org/ReportsStatus/' + id,
		  // data: {"_token": "{{ csrf_token() }}","id": id},
		   data: { "id": id },
			success: function (response) {
				dd(data);
				renderReportsMasterTable();
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
};




window.reportsMaster = {
	init: init
	//onResult: onResult
};