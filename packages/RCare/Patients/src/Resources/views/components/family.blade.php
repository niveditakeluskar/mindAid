	<div class="col-md-6 form-group mb-3">
		<label for="firstName1">First Name<span class="error">*</label>
		@text("fname")
		<!-- <input type="text" class="form-control" name="first_name"  placeholder="">id="first_name" -->
	</div>

	<div class="col-md-6 form-group mb-3">
		<label for="lastName1">Last Name<span class="error">*</label>
		@text("lname")
		<!-- <input type="text" class="form-control" name="last_name"  placeholder="">id="last_name" -->
	</div>
 
	<div class="col-md-6 form-group mb-3">
		<label for="firstName1">Address<span class="error">*</label>
		<textarea class="form-control" name="address"   placeholder=""></textarea><!--id="address" -->
		<div class='invalid-feedback'></div>

	</div>

	<div class="col-md-6 form-group mb-3"> 
		<label for="lastName1">Primary Contact Number</label>
		@phone("mobile")
		<!-- <input type="text" class="form-control" name="home_number"  placeholder="">id="home_number" -->
	</div>

	<div class="col-md-6 form-group mb-3">
		<label for="exampleInputEmail1">Secondary Contact Number</label> 
		@phone("phone_2")
		<!-- <input type="text" class="form-control" name="mob"  placeholder="">id="mob" -->
	</div>

	<div class="col-md-6 form-group mb-3">
		<label for="phone">Email Address</label>
		@email("email")
		<!-- <input type="email" class="form-control" name="email"  placeholder="">id="email"-->
	</div>
	
    