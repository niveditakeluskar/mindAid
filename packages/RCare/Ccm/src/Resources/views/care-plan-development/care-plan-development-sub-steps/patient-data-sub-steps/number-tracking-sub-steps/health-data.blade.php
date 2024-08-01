<form id="number_tracking_healthdata_form" name="number_tracking_healthdata_form" action="{{route("care.plan.development.numbertracking.healthdata")}}" method="post">
	<div class="alert alert-success" id="success-alert" style="display: none;">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong> Health data saved successfully! </strong><span id="text"></span>
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
		$step_id      = getFormStepId($module_id, $submodule_id, $stage_id, 'NumberTracking-Health Data');
		?>
		<input type="hidden" name="patient_id" value="{{$patient_id}}" />
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="module_id" value="{{ $module_id }}" />
		<input type="hidden" name="component_id" value="{{ $submodule_id }}" />
		<input type="hidden" name="stage_id" value="{{$stage_id}}" />
		<input type="hidden" name="step_id" value="{{$step_id}}">
		<input type="hidden" name="form_name" value="number_tracking_healthdata_form">
		<input type="hidden" name="billable" value="<?php if (isset($patient_enroll_date[0]->finalize_cpd) && $patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0) {
														echo 0;
													} else {
														echo 1;
													} ?>">
		@include('Patients::components.health-data')
	</div>
	<div class="card-footer">
		<div class="mc-footer">
			<div class="row">
				<div class="col-lg-12 text-right">
					<button type="submit" class="btn  btn-primary m-1">Save</button>
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
			<table id="health-list" class="display table table-striped table-bordered" style="width:100%">
				<thead>
					<tr>
						<th style="width:auto!important">Sr No.</th>
						<th style="width:auto!important">Health Date</th>
						<th style="width:auto!important">Health Data</th>
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
	function editHealthData(id) {
		var time = document.getElementById("page_landing_times").value;
		$(".timearr").val(time);
		url = `/ccm/get-patient-healthdata-by-id/${id}/patient-healthdata`;
		$("#preloader").css("display", "block");
		$.get(url, id,
			function(result) {
				$("#preloader").css("display", "none");
				for (var key in result) {
					if (key == 'number_tracking_healthdata_form') {
						if (result[key].static['health_data'] != null) {
							var health_data = (result[key].static['health_data']);
							var health_date = (result[key].static['health_date']);
							if (health_date) {
								const dateFormat = /^\d{2}-\d{2}-\d{4}$/;
								const dateTimeFormat = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/;
								let formattedDate;
								if (dateFormat.test(health_date)) {
									formattedDate = moment(health_date, "MM-DD-YYYY").format("YYYY-MM-DD");
								} else if (dateTimeFormat.test(health_date)) {
									formattedDate = moment(health_date, "YYYY-MM-DD HH:mm:ss").format(
										"YYYY-MM-DD"
									);
								} else {
									console.log("Date format is unknown or invalid");
									return "unknown";
								}
								$('#health_date').val(formattedDate);
							}
							$('#healthdata_0').val(health_data);
							var id = result[key].static['id'];
							$("#number_tracking_healthdata_form [name='id']").val(id);
						}
					}
				}
				var mainContent = $(".main-content");
				if (mainContent.length) {
					var scrollPos = mainContent.offset().top;
					if (scrollPos > 0) {
						$(window).scrollTop(scrollPos);
					} else {
						console.log("Element has no top offset");
					}
				} else {
					console.log("Element not found");
				}
			}
		).fail(function(result) {
			console.error("Population Error:", result);
			$("#preloader").css("display", "none");
		});
	}

	function deleteHealthData(id) {
		if (
			window.confirm("Are you sure you want to delete this Health Data?")
		) {
			var patient_id = $("#number_tracking_healthdata_form [name='patient_id']").val();
			var module_id = $("#number_tracking_healthdata_form [name='module_id']").val();
			const data = {
				id: id,
				patientid: patient_id,
				module_id: module_id,
				component_id: $("#number_tracking_healthdata_form [name='component_id']").val(),
				start_time: $("#number_tracking_healthdata_form [name='start_time']").val(),
				end_time: $("#number_tracking_healthdata_form [name='end_time']").val(),
				stage_id: $("#number_tracking_healthdata_form [name='stage_id']").val(),
				step_id: $("#number_tracking_healthdata_form [name='step_id']").val(),
				form_name: $("#number_tracking_healthdata_form [name='form_name']").val(),
				billable: $("#number_tracking_healthdata_form [name='billable']").val(),
				"timearr[form_start_time]": $("#number_tracking_healthdata_form [name='timearr[form_start_time]']").val(),
			};
			$.ajax({
				type: 'POST',
				url: "/ccm/delete-patient-healthdata-by-id",
				data: data,
				success: function(response) {
					console.log("response", response);
					// window.carePlanDevelopment.onNumberTrackingImaging(response);
					window.util.getPatientCareplanNotes(patient_id, module_id);
					util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
					// window.carePlanDevelopment.CompletedCheck();
					window.carePlanDevelopment.renderHealthTable();
					var scrollPos = $(".main-content").offset().top;
					$(window).scrollTop(scrollPos);
					setTimeout(function() {
						$('.alert').fadeOut('fast');
					}, 3000);
					var timer_paused = $("form[name='number_tracking_healthdata_form'] input[name='end_time']").val();
					$("#timer_start").val(timer_paused);
					$(".form_start_time").val(response.form_start_time);
					$("form[name='number_tracking_healthdata_form']")[0].reset();
					$("#append_imaging").html("");
				}
			});
		}
	}
</script>