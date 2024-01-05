
<div class="col-md-6 form-group mb-3">
<label for="fname">First Name<span class="error">*</span>:</label>
@text("fname[]", ["id" => "review_live_fname_0"])
</div>
<div class="col-md-6 form-group mb-3">
<label for="lname">Last Name<span class="error">*</span>:</label>
@text("lname[]", ["id" => "review_live_lname_0"])
</div>
<div class="col-md-6 form-group mb-3">
<label for="relationship">Relationship</label>
@selectFamilyRelationship("relationship[]", ["id" => "review_live_relationship_0"]) 
</div>
<div class="col-md-6 form-group mb-3" style="display: none;" id="relatnname_0">
<label for="relationship_name">Relationship Name<span class="error">*</span>:</label>
@text("relationship_txt[]", ["id" => "relationship_name_0"])
</div>
<div class="col-md-6 form-group mb-3">
<label for="notes">Additional Notes</label>
@text("additional_notes[]", ["id" => "review_live_additional_notes_0"])
</div>
