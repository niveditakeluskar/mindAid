<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>Responsibility <span class='error'>*</span></label>
			@text("responsibility",["name" => "responsibility","id"=>"responsibility"])
		</div>
	</div> 
	<div class="col-md-6 form-group mb-3">
        <label for="name" class="control-label"><span class="error">*</span> Module</label>
        @selectOrgModule("module_id",["id"=>"module_id", "class"=>"module_id"])
    </div>
    <div class="col-md-6 form-group mb-3"> 
        <label for="name" class="control-label"><span class="error">*</span> Sub Module</label>
        <!-- <select name="component_id"  id ="component_id" class="component_id form-control">
        </select> -->
        @selectOrgSubModule("component_id",["id"=>"component_id", "class"=>"component_id"])
        <!-- <div class="invalid-feedback"></div> -->
    </div> 
</div>								
<div class="row">
	<div class="col-12 text-right form-group mb-4">
		<button type="submit" id="saveBtn" class="btn btn-primary">Submit</button>
	</div>
</div>