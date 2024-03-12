
<div class="row">
	<div class="col-lg-12 mb-3">
		<div class="card">
			<form name="patient_traning_info_and_checklist_form" id="patient_traning_info_and_checklist" action="{{ route("divice.traning.patient.traning") }}" method="post">	
				<div class="card-body">
					@csrf
					<?php $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Patient Traning Info and Checklist'); ?>
					<input type="hidden"  name="module_id" value="{{ getPageModuleName() }}">
					<input type="hidden"  name="component_id" value="{{ getPageSubModuleName() }}">
					<input type="hidden" name="start_time" value="00:00:00">
					<input type="hidden" name="end_time" value="00:00:00">
					<input type="hidden" name="stage_id" value="{{$stage_id}}">
					<input type="hidden" name="content_id_patient" id="content1_id" value="" >
					<input type="hidden" name="patient_id" value="{{$checklist->id}}" >
					<input type="hidden" name="step" value="1">
					<div id="newsuccess1"></div>
					<div id="newdanger1"></div> 
					<div class="card-title mb-3"><b>Patient Training Info & Checklist</b></div>
					
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
								<div class=" forms-element">
									<label class="checkbox checkbox-outline-primary blood">
									<input type="checkbox" class="blood selectdevice checkbox" value="1" name="software-download-Protocol"  /> 
									<span>Software Download Protocol</span><span class="checkmark"></span></label>
								</div>	
								<div class="form-row invalid-feedback"></div>
							</p>
						</div> 
					</div> 
					
				</div>
				<div class="card-footer">
					<div class="mc-footer">
						<div class="row">
							<div class="col-lg-12 text-right">
							<!-- onclick="window.location.assign('#step-4')" -->
								<button type="submit" class="btn  btn-primary m-1" id="save_patient_traning_info_and_checklist">Next</button>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	
	