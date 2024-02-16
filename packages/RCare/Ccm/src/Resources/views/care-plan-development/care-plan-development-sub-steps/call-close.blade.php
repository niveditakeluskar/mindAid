<div class="row">

	<div id="success"> 
	</div>	
   <div class="col-lg-12 mb-3">
        <div class="card">
            <form id="call_close_form" name="call_close_form" action="{{ route("monthly.monitoring.call.callclose") }}" method="post"> 
				<div class="card-body">
					<div class="">
						@include('Theme::layouts.flash-message')
						@csrf 
						<?php
							$module_id    = getPageModuleName();
							$component_id = getPageSubModuleName();
							$stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Call Close');
						?>

						<input type="hidden" name="uid" value="{{$patient_id}}" />
						<input type="hidden" name="patient_id" value="{{$patient_id}}" />
						<input type="hidden" name="start_time" value="00:00:00">
						<input type="hidden" name="end_time" value="00:00:00">
						<input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
						<input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
						<input type="hidden" name="stage_id" value="{{ $stage_id }}" />
						<input type="hidden" name="step_id" value="0">
			            <input type="hidden" name="form_name" value="call_close_form">
						<input type="hidden" name="billable" value ="<?php if($patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>">
						<div class="mb-4">
						<div class="alert alert-success" id="success-alert" style="display: none;">
							<button type="button" class="close" data-dismiss="alert">x</button>
							<strong>Call close data saved successfully! </strong><span id="text"></span>
						</div>
                            <label class="">Next month will be more of a normal call where I will begin to help you to maintain your health.  I look forward to our call.  
                            Do you have a preferred day and time that I should call? <span class="error">*</span></label><br>    
							<div class="forms-element d-inline-flex">
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query2" value="1" id="query123"  onchange="ccmcpdcommonJS.newcheckquery2(this.value)" formControlName="radio" <?php (isset($callclose[0]->query2)&& ($callclose[0]->query2==1) )? print("checked") :'';?>>
									<span>Yes</span>
									<span class="checkmark"></span>
								</label>
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query2" value="0"  id="query123"  onchange="ccmcpdcommonJS.newcheckquery2(this.value)" formControlName="radio" <?php (isset($callclose[0]->query2)&& ($callclose[0]->query2==0) )? print("checked") :'';?>>
									<span>No</span>
									<span class="checkmark"></span>
								</label>
							</div>

							



                        	<div class="invalid-feedback"></div>
						<div id="next_month_call_div" class="nextcall">
							<div class="ml-2 d-inline-flex align-self-center">
								<label for="q2_date" class="forms-element mr-3">Select Date:<span class="error">*</span>
									@date("q2_datetime", ["id"=>"next_month_call_date"])
									<span id="nextmonth-date" class="error"></span> 
								</label>
								<label for="q2_date" class="forms-element mr-3" >Select Time:<span class="error">*</span>
									@time("q2_time", ["id"=>"next_month_call_time"])
								</label>
							</div>
						</div> 
						</div>  
					</div>
					
					<div id="ignore" style="display:none;">
					   
					</div>
					
					<div class="card-footer">
						<div class="mc-footer">
							<div class="row">
								<div class="col-lg-12 text-right">
								<!-- onclick="window.location.assign('#step-4')" -->
									<button type="submit" class="btn  btn-primary m-1" id="save-call-close">Next</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
   </div>
</div>


@section('bottom-js')
<script type="text/javascript">
$(document).ready(function() {
		

	if(($("input[name='query2']:checked").val() != null ) || ($("input[name='query2']:checked").val() != "" ))
		{
			if($("input[name='query2']:checked").val() != undefined ){

			$("#ignore").show();
			}
			else{
				$("#ignore").show();
			}
		}
		else{
			$("#ignore").hide(); 
		}
	

		
});
function checkquery1(value) 
{
	
	
	if(value==0 || value==1)
	{
		$("#ignore").show(); 
	}
	else{
		$("#ignore").hide();
	}

	if(value==0)	
	{
		// alert("heeloo"); 
		// alert(value);

		$("#next_month_call_div").show(); 
	}

}

</script> 

 @endsection