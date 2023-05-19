<div class="col-md-4 form-group mb-3">
<label>Location <span class='error'>*</span></label>
<!-- <input name="location" type="text" class="form-control"  placeholder=""> -->
@text("location[]" ,['id' => 'review_travel_location'])
</div>

<div class="col-md-4 form-group mb-3">
<label for="lastName1">Type of Travel</label>
@text("travel_type[]" ,['id' => 'review_travel_travel_type'])
<!-- <input name="type_of_travel" type="text" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-4 form-group mb-3">
<label for="exampleInputEmail1">Frequency</label>
@text("frequency[]" ,['id' => 'review_travel_frequency'])
<!-- <input name="frequency" type="text" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-4 form-group mb-3">
<label for="phone">With Whom</label>
@text("with_whom[]" ,['id' => 'review_travel_with_whom'])
<!-- <input name="with_whom" type="text" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-4 form-group mb-3">
<label for="credit1">Upcoming Trips</label>
@text("upcoming_tips[]" ,['id' => 'review_travel_upcoming_tips'])
<!-- <input name="upcoming_trips" type="text" class="form-control"  placeholder=""> -->
</div>

<div class="col-md-4 form-group mb-3">
<label for="picker3">Additional Notes</label>
@text("notes[]" ,['id' => 'review_travel_notes'])

<!-- <textarea name="notes[]" id="review_travel_notes"  class="form-control"  placeholder=""></textarea> -->
</div>

