<div data-dynamic-template-id="symptoms" class="row dynamic-form">
	<div class="form-group col-md-12">
		<label >Symptoms</label>
		<div class="input-group mb-1">
			@text("value", ["data-feedback" => "symptoms-feedback"])
			<div class="input-group-append">
				<button class="btn btn-outline-primary" data-dynamic-action="remove" type="button">Remove</button>
			</div>
		</div>
		<div data-feedback-area="symptoms-feedback" class="invalid-feedback visible"></div>
	</div>
</div>
