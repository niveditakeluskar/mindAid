<template>
	<div class="row">
		<div id="success"></div>
		<div class="col-lg-12 mb-4">
			<form id="call_close_form" name="call_close_form" action="" method="post">
				<input type="hidden" name="uid" />
				<input type="hidden" name="patient_id" />
				<input type="hidden" name="start_time" value="00:00:00">
				<input type="hidden" name="end_time" value="00:00:00">
				<input type="hidden" name="module_id" />
				<input type="hidden" name="component_id" />
				<input type="hidden" name="stage_id" />
				<input type="hidden" name="step_id" value="0"> 
				<input type="hidden" name="form_name" value="call_close_form">
				<input type="hidden" name="billable" value="1">
				<input type ="hidden" name="form" value="ccm"> 
				<div class="card">  
					<div class="card-body">
						<div class="mb-4">
							<div class="alert alert-success" id="success-alert" style="display: none;">
								<button type="button" class="close" data-dismiss="alert">x</button>
								<strong>Call close data saved successfully! </strong><span id="text"></span>
							</div>
							<span>Are there any other issues impacting your health that you would like to talk about today that we have not addressed yet?<span class="error">*</span></span>
							<br>
							<div class="forms-element d-inline-flex"> 
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query1" value="1">
									<span>Yes</span>
									<span class="checkmark"></span>
								</label>
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query1" value="0">
									<span>No</span>
									<span class="checkmark"></span>
								</label>
							</div>
							<div class="invalid-feedback"></div>
							<div id="health_issue_notes_div">
								<label for="q1_notes" class="mr-3">Monthly Notes:</label>
								<textarea class="forms-element form-control" name="q1_notes"></textarea>
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="mb-4">
							<label for="addressed-issue">Do you have a preferred day and time for our call next month?<span class="error">*</span></label>
							<br>
							<div class="forms-element d-inline-flex">
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query2" value="1" id="newquery2"  onchange="ccmcpdcommonJS.newcheckquery2(this.value)">
									<span>Yes</span>
									<span class="checkmark"></span>
								</label> 
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query2" value="0" id="newquery2"  onchange="ccmcpdcommonJS.newcheckquery2(this.value)">
									<span>No</span>
									<span class="checkmark"></span>
								</label>
							</div>
							<div class="invalid-feedback"></div>
							<div id="next_month_call_div" class="nextcall">
								<div class="mr-3 d-inline-flex align-self-center">
									<label for="q2_date" class="forms-element mr-3">Select Date:<span class="error">*</span>
										@date("q2_datetime", ["id"=>"next_month_call_date"])
									<span id="nextmonth-date" class="error"></span>
									</label>

									<label for="q2_date" class="forms-element mr-3" >Select Time:<span class="error">*</span>
										@time("q2_time", ["id"=>"next_month_call_time"])
									</label>
								</div>
								<div class="">
									<label for="q2_notes" class="mr-3">Monthly Notes:</label>
									<textarea class="forms-element form-control" name="q2_notes"></textarea>
									<div class="invalid-feedback"></div>
								</div>
							</div>
						</div>
						<div id="ignore" style="display:none;"> </div>
					</div>
					<div class="card-footer">
						<div class="mc-footer">
							<div class="row">
								<div class="col-lg-12 text-right">
									<button type="submit" class="btn  btn-primary m-1 office-visit" id="save-call-close">Next</button>								
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</template>