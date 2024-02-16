<div class="row">
	<div class="col-lg-12 mb-3">
		<div class="card">
			<div class="card-body">
				<div class="card-title mb-3"><b>Software Usage Instruction</b></div> 
				<div class="form-row">
					<div class="form-group col-md-12">
						<p class="blood selectdevice"> 
							<p id="content2"></p>
							<label class="checkbox checkbox-outline-primary blood selectdevice "><input type="checkbox" class="blood selectdevice checkbox" id="check2" name="software-usage-instruction" data-target="#step-3"> <span>Complete Usage linstructions</span><span class="checkmark"></span></label> </p>
					</div> 
				</div>	 
			</div>
		</div>
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