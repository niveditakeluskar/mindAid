<div class="row">
	<div class="col-md-10  form-group mb-3"> 
		<div class="form-group">
			<label>Code</label><!-- <span class="error">*</span> -->
			@text("code",["name" => "code[]","id"=>"code_0" ,"onkeypress"=>"valid_numbers(e);"])
		</div>
		<div id="appendcode"></div>
	</div>
	<div class="col-md-1" style="margin-top: 27px;">
        <div >
            <i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalcode" title="Add Parameter"></i>
        </div>
    </div>
                    

	<!-- <div class="col-6"> -->
	<div class="col-md-10  form-group mb-3"> 
		<div class="form-group">
			<label>Condition</label><span class="error">*</span>
			@text("condition",["name" => "condition","id"=>"condition"])
			<div class="invalid-feedback"></div>
		</div>
	</div>
	<div class="col-md-10  form-group mb-3"> 
		<div class="form-check form-switch">
			<label class="switch">Qualified for billing<span class="error">*</span><span class="toggle_value"></span>
				<input type=checkbox  class="checkbox checkbox-primary qualified" id="qualified" name="qualified">
  				<span class="slider round"></span>
				<div class="invalid-feedback"></div>
			</label>
		</div>
	</div>
	<!-- <div class="col-md-10  form-group mb-3" style="margin-left:-40px"> 
		<div class="form-check form-switch">
	  		<label for="qualified" class="checkbox  checkbox-primary">
				<yes-no name="qualified" label-no="No" label-yes="Yes" class="yesno">Qualified for billing <span class="error">*</span></yes-no>
			</label>
		</div>
	</div> -->
</div>
	
<div class="card-footer">
	<div class="mc-footer">             
		<div class="row">
			<!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
			<div class="col-12 text-right form-group mb-4">
				<button type="submit" class="btn btn-primary" id="diagnosissubmit">Submit</button>
			</div>
		</div>
	</div>
</div>


