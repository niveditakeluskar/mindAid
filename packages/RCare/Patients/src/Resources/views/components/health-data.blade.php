


<div class="col-md-10 border-bottom-primary pb-4 pt-2">
<div class="row"><div class="col-md-4">
	<label>Health Data : <span class="error">*</span></label>
	@text("health_data[]", ["placeholder" => "Enter Health Data", "id" => "healthdata_0"])
	</div>
	<div class="col-md-4"><label >Date<span class="error">*</span> :</label>      
        @date("health_date[]",["id"=>"health_date"]) </div>
	</div>
	<div class="col-md-12 pt-1" id="append_healthdata"></div>
	<div class="col-md-12 mb-3" style="display:none"><label>Comment :</label><textarea class="forms-element form-control" id="healthdata_comment" name="comment"></textarea><div class="invalid-feedback"></div></div>
	 <hr/> 
	<i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  onclick="carePlanDevelopment.additionalhealthdata(this)" title="Add Health Data"></i>
</div>