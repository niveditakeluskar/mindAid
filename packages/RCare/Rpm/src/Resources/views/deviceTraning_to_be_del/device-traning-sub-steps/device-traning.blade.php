<div class="row">
    <div class="col-lg-12 mb-3">
    <form name="device_traning_form" id="device_traning_form" action="{{ route("divice.traning.patient.traning") }}" method="post">
    @csrf
    <?php $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Device Traning'); ?>
		<input type="hidden"  name="module_id" value="{{ getPageModuleName() }}">
		<input type="hidden"  name="component_id" value="{{ getPageSubModuleName() }}">
        <input type="hidden" name="start_time" value="00:00:00">
		<input type="hidden" name="end_time" value="00:00:00">
		<input type="hidden" name="stage_id" value="{{$stage_id}}">
        <input type="hidden" name="content_id_device_traning" id="content3_id" value="" >
        <input type="hidden" name="patient_id" value="{{$checklist->id}}" >
        <input type="hidden" name="step" value="3">
        <input type="hidden" name="devices" id="step3_devices" value="">
        <div class="card">
            <div class="card-body">
            <!-- <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Device Training saved successfully! </strong><span id="text"></span>
               </div> -->
			   <div id="newsuccess3"></div> 
			   <div id="newdanger3"></div>  
                <div class="card-title mb-3"><b>Device Training</b></div>
                <div class="form-row">
					<div class="form-group col-md-12">
						<p class="blood selectdevice"> 
							<p id="content3"></p>
							<div class=" forms-element">
								<label class="checkbox checkbox-outline-primary blood selectdevice ">
								<input type="checkbox" class="blood selectdevice checkbox" value="1" name="software-download-Protocol" >
								<span>Complete Device Training</span><span class="checkmark"></span></label>
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
								<button type="submit" class="btn  btn-primary m-1" id="save_device_traning_form">Next</button>
							</div>
						</div>
					</div>
				</div>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
</script> 