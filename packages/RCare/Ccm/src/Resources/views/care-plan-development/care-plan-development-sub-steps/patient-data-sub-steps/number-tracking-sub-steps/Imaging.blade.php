<form id="number_tracking_imaging_form" name="number_tracking_imaging_form" action="{{route("care.plan.development.numbertracking.imaging")}}" method="post">
	<div class="alert alert-success" id="success-alert" style="display: none;">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong> Imaging data saved successfully! </strong><span id="text"></span>
	</div>
	<div class="alert alert-danger" id="success-alert" style="display: none;">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong> Please fill all mandatory fields! </strong><span id="text"></span>
	</div>
	<div class="form-row col-md-12">
		@include('Theme::layouts.flash-message')
		@csrf
		<?php
		$module_id    = getPageModuleName();
		$submodule_id = getPageSubModuleName();
		$stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Data');
		$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'NumberTracking-Imaging');
		?>
		<input type="hidden" name="patient_id" value="{{$patient_id}}" />
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" />
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" />
		<input type="hidden" name="stage_id" value="{{$stage_id}}" />
		<input type="hidden" name="step_id" value="{{$step_id}}">
		<input type="hidden" name="form_name" value="number_tracking_imaging_form">
		<input type="hidden" name="id">
		<input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" />
		<input type="hidden" name="billable" value="<?php if (isset($patient_enroll_date[0]->finalize_cpd) && $patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0) {
														echo 0;
													} else {
														echo 1;
													} ?>">
		@include('Patients::components.imaging')
	</div>
	<div class="card-footer">
		<div class="mc-footer">
			<div class="row">
				<div class="col-lg-12 text-right">
					<button type="submit" class="btn  btn-primary m-1" id="save_number_tracking_vitals_form">Save</button>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="separator-breadcrumb border-top"></div>
<div id="msgsccess"></div>
<div class="row">
	<div class="col-12">
		<div class="table-responsive">
			<table id="imaging-list" class="display table table-striped table-bordered" style="width:100%">
				<thead>
					<tr>
						<th style="width:auto!important">Sr No.</th>
						<th style="width:auto!important">Imaging Date</th>
						<th style="width:auto!important">Imaging</th>
						<!--th style="width:auto!important">Comment</th-->
						<th style="width:auto!important">Action</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

		</div>
	</div>
</div>
<script>
	function editImagingData(id) {
		var time = document.getElementById("page_landing_times").value;
		$(".timearr").val(time);
		url = `/ccm/get-patient-imaging-by-id/${id}/patient-imaging`;
		$("#preloader").css("display", "block");
		$.get(url, id,
			function(result) {
				$("#preloader").css("display", "none");
				for (var key in result) {
					if (key == 'number_tracking_imaging_form') {
						if (result[key].static['imaging_details'] != null) {
							var imagingDetails = (result[key].static['imaging_details']);
							var imagingDate = (result[key].static['imaging_date']);
							if (imagingDate) {
								const dateFormat = /^\d{2}-\d{2}-\d{4}$/;
								const dateTimeFormat = /^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}$/;
								let formattedDate;
								if (dateFormat.test(imagingDate)) {
									formattedDate = moment(imagingDate, "MM-DD-YYYY").format("YYYY-MM-DD");
								} else if (dateTimeFormat.test(imagingDate)) {
									formattedDate = moment(imagingDate, "MM-DD-YYYY HH:mm:ss").format(
										"YYYY-MM-DD"
									);
								} else {
									console.log("Date format is unknown or invalid");
									return "unknown";
								}
								$('#imaging_date').val(formattedDate);
							}
							var comment = (result[key].static['comment']);
							$('#imaging_0').val(imagingDetails);
							$('#imaging_comment').val(comment);
							var id = result[key].static['id'];
							$("#number_tracking_imaging_form [name='id']").val(id);
						}
					}
				}
				var scrollPos = $(".main-content").offset().top;
				$(window).scrollTop(scrollPos);
			}
		).fail(function(result) {
			console.error("Population Error:", result);
			$("#preloader").css("display", "none");
		});
	}

	function deleteImagingData(id) {
		if (
			window.confirm("Are you sure you want to delete this Imaging Data?")
		) {
			var patient_id = $("#number_tracking_imaging_form [name='patient_id']").val();
			var module_id = $("#number_tracking_imaging_form [name='module_id']").val();
			const data = {
				id: id,
				patientid: patient_id,
				module_id: module_id,
				component_id: $("#number_tracking_imaging_form [name='component_id']").val(),
				start_time: $("#number_tracking_imaging_form [name='start_time']").val(),
				end_time: $("#number_tracking_imaging_form [name='end_time']").val(),
				stage_id: $("#number_tracking_imaging_form [name='stage_id']").val(),
				step_id: $("#number_tracking_imaging_form [name='step_id']").val(),
				form_name: $("#number_tracking_imaging_form [name='form_name']").val(),
				billable: $("#number_tracking_imaging_form [name='billable']").val(),
				"timearr[form_start_time]": $("#number_tracking_imaging_form [name='timearr[form_start_time]']").val(),
			};
			$.ajax({
				type: 'POST',
				url: "/ccm/delete-patient-imaging-by-id",
				data: data,
				success: function(response) {
					console.log("response", response);
					// window.carePlanDevelopment.onNumberTrackingImaging(response);
					window.util.getPatientCareplanNotes(patient_id, module_id);
					util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
					// window.carePlanDevelopment.CompletedCheck();
					window.carePlanDevelopment.renderImagingTable();
					var scrollPos = $(".main-content").offset().top;
					$(window).scrollTop(scrollPos);
					setTimeout(function() {
						$('.alert').fadeOut('fast');
					}, 3000);
					var timer_paused = $("form[name='number_tracking_imaging_form'] input[name='end_time']").val();
					$("#timer_start").val(timer_paused);
					$(".form_start_time").val(response.form_start_time);
					$("form[name='number_tracking_imaging_form']")[0].reset();
					$("#append_imaging").html("");
				}
			});
		}
	}
</script>