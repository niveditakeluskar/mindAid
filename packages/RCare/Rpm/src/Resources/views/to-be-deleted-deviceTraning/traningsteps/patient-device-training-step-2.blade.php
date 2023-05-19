
	<div class="row">

	<div class="col-lg-12 mb-3">
		<div class="card">
				<div class="card-body">
			<div class="card-title mb-3"><b>Software Usage Instruction</b></div> 
					
					
					<div class="form-row">
						 <div class="form-group col-md-12">
						<!-- 	<label for="uid">Software Usage Instruction</label> -->
							<p class="blood selectdevice"> 

							<p id="content2"></p>
             
								<!-- <ul class="blood selectdevice">
									<li>The app can learn from the user’s data (or behavior).
									</li>
									<li>The data never needs to leave the device (good for privacy).
										</li>
										<li>Anything you can do on-device saves money. Running servers is expensive.
										</li>
										<li>You can always be learning and update the model continuously.
										</li>
								</ul> -->	
							
							
							
									<!-- onclick='save(2), window.location.assign("#step-3")' -->
							<label class="checkbox checkbox-outline-primary blood selectdevice "><input type="checkbox" class="blood selectdevice checkbox" id="check2" name="software-usage-instruction" data-target="#step-3"> <span>Complete Usage linstructions</span><span class="checkmark"></span></label> </p>
						</div> 
					</div>

				

					 
				</div>
			</div>
		</div>
		
		<!-- button -->
		
	</div>
		<!-- <div class="form-row">
						<div class="col-lg-12 mb-3"  id="smartwizard">
    					<a href="#step-3"><button type="submit"  value="submit" class="btn btn-primary btn-lg btn-block delete" disabled="disabled">Submit</button></a>
		</div>
	</div> -->

	
	
	<script type="text/javascript">
		     $(document).ready(function(){
		     $("select").change(function(){
		        $(this).find("option:selected").each(function(){
		            var optionValue = $(this).attr("value");
		            if(optionValue){
		                // $(".selectdevice").not("." + optionValue).hide();
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