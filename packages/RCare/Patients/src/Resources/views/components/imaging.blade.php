
<div class="col-md-10 border-bottom-primary pb-4 pt-2">
	<div class="row"><div class="col-md-4"><label>Imaging : <span class="error">*</span></label>
	@text("imaging[]", ["placeholder" => "Enter Imaging", "id" => "imaging_0"])</div>
	<div class="col-md-4"><label >Date<span class="error">*</span> :</label>      
        @date("imaging_date[]",["id"=>"imaging_date"]) </div></div>
		<br/>
	<div class="col-md-12 pt-1" id="append_imaging"></div>
	<div class="col-md-12 mb-3" style="display:none"><label>Comment :</label><textarea class="forms-element form-control" id="imaging_comment" name="comment"></textarea><div class="invalid-feedback"></div></div>
	 <hr/>
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  onclick="carePlanDevelopment.additionalimaging(this)" title="Add imaging"></i>
</div>



