<div class="row">
	<div class="col-lg-12 mb-3">
	<form name="software_usage_instruction_form" id="software_usage_instruction_form" action="{{ route("divice.traning.patient.traning") }}" method="post">
	@csrf
	<?php $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Software Usage Instruction'); ?>
		<input type="hidden"  name="module_id" value="{{ getPageModuleName() }}">
		<input type="hidden"  name="component_id" value="{{ getPageSubModuleName() }}">
		<input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="stage_id" value="{{$stage_id}}">
		<input type="hidden" name="content_id_softwear_usages" value="" id="content2_id">
		<input type="hidden" name="patient_id" value="{{$checklist->id}}" >
		<input type="hidden" name="step" value="3">
		<input type="hidden" name="devices" id="step2_devices" value="">
		<div class="card">
			<div class="card-body">
			    <div id="newsuccess2"></div> 
				<div id="newdanger2"></div>   
				<div class="card-title mb-3"><b>Software Usage Instruction</b></div> 
				
				<div class="form-row">
					<div class="form-group col-md-12">
						<p class="blood selectdevice"> 
							<p id="content2"></p>
							<div class=" forms-element">
								<label class="checkbox checkbox-outline-primary blood selectdevice ">
								<input type="checkbox" class="blood selectdevice checkbox" value="1" name="software-download-Protocol" >
								<span>Complete Usage linstructions</span><span class="checkmark"></span></label>
							</div>	 
							 <div class="form-row invalid-feedback"></div>
						</p>
					</div> 
				</div>	 
			</div>
			<div class="card-footer">
					<div class="mc-footer">
						<div class="row">
							<div class="col-lg-12 text-right">
							<!-- onclick="window.location.assign('#step-4')" -->
								<button type="submit" class="btn  btn-primary m-1" id="save_software_usage_instruction_form">Next</button>
							</div>
						</div>
					</div>
				</div>
		</div>
	</form>
	</div>
</div>
<script type="text/javascript">
		     $(document).ready(function(){
		     $("select").change(function(){
		        $(this).find("option:selected").each(function(){
		            var optionValue = $(this).attr("value");
		            if(optionValue){
		                $("." + optionValue).show();
		            } else{
		                // $(".selectdevice").hide();
		            }
		        });
		    }).change();
		       });

				  $(function() {
		        $(".checkbox").click(function(){

		        $('.delete').prop('disabled',$('input.checkbox:checked').length < 1);
		    });
		});
		
	</script>