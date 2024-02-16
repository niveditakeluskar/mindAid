<div class="row">
	<!-- <div class="col-6"> -->
	<div class="col-md-10  form-group mb-3"> 
		<div class="form-group">
			<label>Event</label><span class="error">*</span>
			@text("event",["name" => "event","id"=>"event"])
			<div class="invalid-feedback"></div>
		</div>
	</div>
	<div class="col-md-10  form-group mb-3"> 
		<div class="form-group">
			<label>Date</label><span class="error">*</span>
            <!-- @text("date",["name" => "date","id"=>"date"]) -->
            @date('date',["name" => "date" ,"id" => "date"])   
			<div class="invalid-feedback"></div>
		</div>
	</div>
</div>
	
<div class="card-footer">
	<div class="mc-footer">             
		<div class="row">
			<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
			<div class="col-12 text-right form-group mb-4">
				<button type="submit" class="btn btn-primary" id="holidaysubmit">Submit</button>
			</div>
		</div>
	</div>
</div>


