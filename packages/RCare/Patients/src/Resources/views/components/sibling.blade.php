<div class="col-md-6 form-group mb-3">
<label for="name">First Name<span class="error">*</span>:</label>
@text("fname[]", ["id" => "review_sibling_fname"])
</div>
<div class="col-md-6 form-group mb-3">
<label for="name">Last Name<span class="error">*</span>:</label>
@text("lname[]", ["id" => "review_sibling_lname"])
</div>
<div class="col-md-6 form-group mb-3">
<label for="age">Age</label>
@text("age[]", ["id" => "review_sibling_age"]) 
</div>
<div class="col-md-6 form-group mb-3">
<label for="location">Address</label>
@text("address[]", ["id" => "review_sibling_address"])
</div>
<div class="col-md-6 form-group mb-3">
<label for="notes">Additional Notes</label>
@text("additional_notes[]", ["id" => "review_sibling_additional_notes"])
</div>