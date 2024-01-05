
	<div class="row">

	<div class="col-lg-12 mb-3">
		<div class="card">
				<div class="card-body">
					<div class="card-title mb-3"><b>Patient Traning Info & Checklist</b></div>
					<div class="form-row">
						<div class="form-group col-md-8">
							<label for="sel1">Select Device:</label>
								   <select class="form-control" name="devices" id="device_id">
                
										<option selected>Choose One...</option>
										@foreach($devices as $value)
										<option value="{{ $value->id }}">{{ $value->device_name }}</option>
										@endforeach
								
									</select>

							</div>
					   <div class="form-group col-md-2">
					   	    	
						 	<!-- <select class="form-control" id="select-device">
						 		<option value="">Select Device</option>
							    <option value="blood">Blood Pressure Cuff</option>
						  	</select> -->
					    	</div>
						<div class="form-group col-md-2">
						
					</div>
					</div>
					<div class="form-row ">
						<div class="form-group col-md-12" id="software_download_content" style="display:none;">
							
							<p class="blood selectdevice" >
								
								<label for="name" >Software Download Protocol</label>

						
							<p id="content1"></p>
								
							
						<!-- 	<input type="checkbox"  class="blood selectdevice checkbox" name=""  style="margin-left: 66em; margin-top: 0.3em;"> <p class="blood selectdevice float-right"> -->
								<br><br>
								<!-- onclick='save("1"), window.location.assign("#step-2")' -->
							<label class="checkbox checkbox-outline-primary blood"><input type="checkbox" class="blood selectdevice checkbox" id="check1" name="software-download-Protocol"  /> <span>Software Download Protocol</span><span class="checkmark"></span></label>


						</p>
						</div> 
					</div>
					<!-- <div class="form-row">
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
 -->
				

					 
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

	
	<!-- <script type="text/javascript">
		    //  $(document).ready(function(){
		    //  $("select").change(function(){
		    //     $(this).find("option:selected").each(function(){
		    //         var optionValue = $(this).attr("value");
		    //         if(optionValue){
		    //             $(".selectdevice").not("." + optionValue).hide();
		    //             $("." + optionValue).show();
		    //         } else{
		    //             $(".selectdevice").hide();
		    //         }
		    //     });
		    // }).change();

			$("#device_id").change(function(){
				$("#software_download_content").show()
		       });

				  $(function() {
		        $(".checkbox").click(function(){

		        $('.delete').prop('disabled',$('input.checkbox:checked').length < 1);
		    });
		});
	// 	function stripHtml(html){
    // // Create a new div element
	// 		var temporalDivElement = document.createElement("div");
	// 		// Set the HTML content with the providen
	// 		temporalDivElement.innerHTML = html;
	// 		// Retrieve the text property of the element (cross-browser support)
	// 		return temporalDivElement.textContent || temporalDivElement.innerText || "";
	// 	}
		
	function get_content(val){
        $.ajax({
            type: 'post',
            url: '/rpm/getContent',
            data: {
                _token: '{!! csrf_token() !!}',
                device_id:val
            },
            success: function (response) {
                // document.getElementById("stages").innerHTML=response; 
				alert(response);
            }
        });
    }
	</script> -->
