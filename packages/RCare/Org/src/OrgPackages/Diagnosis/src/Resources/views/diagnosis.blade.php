<div class="row">
	<div class="col-md-12  form-group mb-3"> 
		<div class="form-group">
			<label>Code<!-- <span class="error">*</span> --></label>
			@text("code",["name" => "code","id"=>"code"])
		</div>
	</div>

	<!-- <div class="col-6"> -->
		<div class="col-md-12  form-group mb-3"> 
		<div class="form-group">
			<label>Condition</label><span class="error">*</span>
			@text("condition",["name" => "condition","id"=>"condition"])
		</div>
	</div>
</div>
	
  <div class="card-footer">
                <div class="mc-footer">
                 
<div class="row">
	<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
	<div class="col-12 text-right form-group mb-4">
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
</div>
</div>
</div>


