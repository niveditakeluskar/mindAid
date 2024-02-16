
	<div class="row">

	<div class="col-lg-12 mb-3">
		<div class="card">
				<div class="card-body">
					<div class="card-title mb-3"><b>Patient Traning Info & Checklist</b></div>
					<div class="form-row">
						<div class="form-group col-md-4">

							</div>
					   <div class="form-group col-md-4">
					   	    	<label for="sel1">Select Device:</label>
						 	<select class="form-control" id="select-device">
						 		<option value="">Select Device</option>
							    <option value="blood">Blood Pressure Cuff</option>
							    <!-- <option value="red">2</option>
							    <option value="blue">3</option>
							    <option value="">4</option> -->
						  	</select>
					    	</div>
						<div class="form-group col-md-4">
						
					</div>
					</div>
					<div class="form-row ">
						<div class="form-group col-md-12">
							<label for="name">Software Download Protocol</label>
							<p class="blood selectdevice">

								<ul class="blood selectdevice">
									<li>The app can learn from the user’s data (or behavior).
									</li>
									<li>The data never needs to leave the device (good for privacy).
										</li>
										<li>Anything you can do on-device saves money. Running servers is expensive.
										</li>
										<li>You can always be learning and update the model continuously.
										</li>
								</ul>	
							
						<!-- 	<input type="checkbox"  class="blood selectdevice checkbox" name=""  style="margin-left: 66em; margin-top: 0.3em;"> <p class="blood selectdevice float-right"> -->

							<label class="checkbox checkbox-outline-primary blood selectdevice "><input type="checkbox" class="blood selectdevice checkbox"> <span>Completed Downloading linstructions</span><span class="checkmark"></span></label></p>
						</div> 
					</div>
					<div class="form-row">
						 <div class="form-group col-md-12">
							<label for="uid">Software Usage Instruction</label>
							<p class="blood selectdevice"> <ul class="blood selectdevice">
									<li>The app can learn from the user’s data (or behavior).
									</li>
									<li>The data never needs to leave the device (good for privacy).
										</li>
										<li>Anything you can do on-device saves money. Running servers is expensive.
										</li>
										<li>You can always be learning and update the model continuously.
										</li>
								</ul>	
							
							
							

							<label class="checkbox checkbox-outline-primary blood selectdevice "><input type="checkbox" class="blood selectdevice checkbox "> <span>Completed Usage linstructions</span><span class="checkmark"></span></label> </p>
						</div> 
					</div>

				

					 
				</div>
			</div>
		</div>
		
		<!-- button -->
		
	</div>
		<div class="form-row">
						<div class="col-lg-12 mb-3"  id="smartwizard">
    					<a href="#step-3"><button type="submit"  value="submit" class="btn btn-primary btn-lg btn-block delete" disabled="disabled">Submit</button></a>
		</div>
	</div>

	
	
	<script type="text/javascript">
		     $(document).ready(function(){
		     $("select").change(function(){
		        $(this).find("option:selected").each(function(){
		            var optionValue = $(this).attr("value");
		            if(optionValue){
		                $(".selectdevice").not("." + optionValue).hide();
		                $("." + optionValue).show();
		            } else{
		                $(".selectdevice").hide();
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