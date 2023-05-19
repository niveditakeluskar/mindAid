
	<div class="row">
	<div class="col-lg-12 mb-3">
		<div class="card">
				<div class="card-body">
					<div class="card-title mb-3"><b>Patient Traning Info & Checklist</b></div>
					<div class="form-row">
						<div class="form-group col-md-8">
							<label for="sel1">Select Device:</label>
							<select class="form-control" name="devices" id="device_id">
								<option selected>Choose One...</option>
								@foreach($devices as $value)
								<option value="{{ $value->id }}">{{ $value->device_name }}</option>
								@endforeach
							</select>
						</div>
					   	<div class="form-group col-md-2"></div>
						<div class="form-group col-md-2"></div>
					</div>
					<div class="form-row ">
						<div class="form-group col-md-12" id="software_download_content" style="display:none;">
							<p class="blood selectdevice" >
								<label for="name" >Software Download Protocol</label>
								<p id="content1"></p>
								<label class="checkbox checkbox-outline-primary blood"><input type="checkbox" class="blood selectdevice checkbox" id="check1" name="software-download-Protocol"  /> <span>Software Download Protocol</span><span class="checkmark"></span></label>
							</p>
						</div> 
					</div> 
				</div>
			</div>
		</div>
	</div>
	
	