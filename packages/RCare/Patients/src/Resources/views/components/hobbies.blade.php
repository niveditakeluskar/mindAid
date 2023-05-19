<div class="col-md-6 form-group mb-3">
<label for="firstName1">Name/Description <span class='error'>*</span></label>
<!-- <input name="description" type="text" class="form-control"  placeholder=""> -->
@text("hobbies_name[]", ['id' => 'review_hobbie_description'])
</div>

<div class="col-md-6 form-group mb-3">
<label for="lastName1">Location</label>
<!-- <input name="location" type="text" class="form-control"  placeholder=""> -->
@text("location[]", ['id' => 'review_hobbie_location'])
</div>

<div class="col-md-4 form-group mb-3">
<label for="exampleInputEmail1">Frequency</label>
<!-- <input name="frequency" type="text" class="form-control"  placeholder=""> -->
@text("frequency[]", ['id' => 'review_hobbie_frequency'])
</div>

<div class="col-md-4 form-group mb-3">
<label for="phone">With Whom</label>
<!-- <input name="with_whom" type="ematextil" class="form-control" placeholder=""> -->
@text("with_whom[]", ['id' => 'review_hobbie_with_whom'])
</div>

<div class="col-md-4 form-group mb-3">
<label for="phone">Additional Notes</label>
@text("notes[]", ['id' => 'review_hobbie_notes'])
<!-- <textarea name="notes[]" id="review_hobbie_notes"class="form-control" placeholder=""></textarea> -->
</div>