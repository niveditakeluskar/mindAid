<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>Location <span class='error'>*</span></label>
			@text("location",["name" => "location"])
		</div>
		<div class="form-group">
			<label>Address </label>
			@text("address",["name" => "address"])
		</div>
		<div class="form-group">
			<label>Phone </label>
			@phone("phone",["name" => "phone"])
		</div>
	</div>
</div>								
<div class="row">
	<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
	<div class="col-12 text-right form-group mb-4">
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
</div>