<div class="col-md-6 form-group mb-3">
	<label for="firstName1">Address <span class='error'>*</span></label>
	<textarea class="form-control" name="add_1" id="patient_address"  placeholder="" ></textarea> <!--id="address" -->
	<div class='invalid-feedback'></div>
</div>

<div class="col-md-6 form-group mb-3">
	<label for="home_number">Primary Contact Number<span class='error'>*</span></label>
	@phone("mob")
	<!-- <input type="text" class="form-control" name="home_number"  placeholder="" id="home_number"><id="home_number" -->
</div>

<div class="col-md-6 form-group mb-3">
	<label for="mob">Secondary Contact Number</label> <!-- <span class='error'>*</span> -->
	@phone("home_number")
	<!-- <input type="text" class="form-control" name="mob"  placeholder=""id="mob" ><id="mob" -->
</div>

<div class="col-md-6 form-group mb-3">
	<label for="email">Email</label> <!-- <span class='error'>*</span> -->
	@email("email")
	<!-- <input type="email" class="form-control" name="email"  placeholder="" id="email"><id="email" -->
</div>