<div class="form-row col-md-12 mb-3 pet" id="pet">
<div class="col-md-4 form-group mb-3">
<label for="firstName1">Name<span class="error">*</span></label>
@text("pet_name[]",["id" =>"review_pet_name"])
<!-- <input type="text" name="description" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-4 form-group mb-3">
<label for="lastName1">Type</label>
@text("pet_type[]",["id" =>"review_pet_type"])
<!-- <input type="text" name="type" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-4 form-group mb-3">
<label for="phone">Additional Notes</label>
@text("notes[]", ["id" =>"review_pet_notes"])
<!-- <textarea name="notes[]" id="review_pet_notes" class="form-control"  placeholder=""></textarea> -->
</div>
</div>