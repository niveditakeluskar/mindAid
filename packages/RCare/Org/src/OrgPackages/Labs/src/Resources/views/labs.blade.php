<div class="row">
	<div class="col-md-12">
		<div class="col-md-10">
		<div class="form-group">
			<label>Lab <span class='error'>*</span></label>
			@text("lab",["name" => "lab","id"=>"editdescription"])
		</div>
	</div>
	</div>
</div>	
<div class="row">
	<div class="col-md-12">
		<div class="col-md-11">
				<div class="form-group">
			<label>Parameters <span class='error'>*</span> </label>
			@text("addparameters[]", ["placeholder" => "Enter Parameter", "id" => "addparameter_0" ,"class"=>"col-md-10"])
		</div>
		</div>
			<div class="col-md-1" style="float: right;margin-top: -36px; margin-right: 16%;">
				<div >
					<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalparameteradd" title="Add Parameter"></i>
				</div>
		</div>

	</div>
	</div>
		<div class="col-10">
		
		<div class="col-md-12 form-group mb-3" id="append_parameteradd"></div>
		</div>
	
								
<div class="row">
	<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
	<div class="col-12 text-right form-group mb-4">
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
</div>