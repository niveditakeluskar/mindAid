<div class="col-md-6 form-group mb-3">
	<label for="lastName1">Specify <span class="error">*</span></label>
	@text("specify")
	<!-- <input type="text" name="specify" class="form-control"  placeholder="">id="lastName1"-->
</div>

 <div class="col-md-6 form-group mb-3">
	<label for="exampleInputEmail1">Type of Reaction <span class="error">*</span></label>
	@text("type_of_reactions")
	<!-- <input type="text"  name="type_of_reactions" class="form-control"  placeholder="">id="exampleInputEmail1"-->
</div>

<div class="col-md-6 form-group mb-3">
	<label for="exampleInputEmail1">Severity <span class="error">*</span></label>
	@text("severity")
	<!-- <input type="text" name="severity" class="form-control"  placeholder="">id="exampleInputEmail1"-->
</div>

<div class="col-md-6 form-group mb-3">
	<label for="phone">Course of Treatment <span class="error">*</span></label>
	@text("course_of_treatment")
	<!-- <input type="text" name="course_of_treatment" class="form-control"  placeholder="">id="lastName1"-->
</div>

<div class="col-md-12 form-group mb-3">
	<label for="firstName1">Additional Notes : </label>
	<textarea name="notes" class="form-control"  placeholder=""></textarea><!--id="firstName1"--> 
	<div class="invalid-feedback"></div>
</div>