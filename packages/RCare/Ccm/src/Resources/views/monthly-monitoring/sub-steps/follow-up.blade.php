<div class="row">
	<div class="col-lg-12 mb-3">
		<form id="followup_form" name="followup_form" action="{{ route("monthly.monitoring.followup") }}" method="post"> 
            @csrf 
            <?php
		       $module_id    = getPageModuleName();
		       $submodule_id = getPageSubModuleName();
		       $stage_id =  getFormStageId($module_id , $submodule_id, 'Follow Up');
		    ?>
			<input type="hidden" name="uid" value="{{$patient_id}}" />
			<input type="hidden" name="patient_id" id="patient_id" value="{{$patient_id}}" />
			<input type="hidden" name="start_time" value="00:00:00">
			<input type="hidden" name="end_time" value="00:00:00">
			<input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
			<input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
			<input type="hidden" name="stage_id" value="{{$stage_id}}" />
			<input type="hidden" name="step_id" value="0">
			<input type="hidden" name="form_name" value="followup_form">
			<!-- <div class="mb-3" ><b>Follow-up</b></div> -->
			<div class="card">
				<div class="card-body">
				<div id='error-msg'></div> 
				<div class="card-title">Follow-up</div>
					<div class="row"> 
                        <div class="col-md-12">
							<div class='row ml-1'> 
								<div class="col-md-4 form-group">
									@text("task_name[]",["id"=>"task_name","placeholder"=>"Task"])
								</div>
								<div class="col-md-4 form-group selects" id="followupTaskDrpdwn_0">
									@selectfuturefollowuptask("followupmaster_task[]",["id"=>"followupmaster_task"])
								</div>
								@hidden("selected_task_name[]",["id"=>"selected_task_name_0"])
								<div class="col-md-4 form-group">
		                                <label class="radio radio-primary col-md-4 float-left">
		                                    <input type="radio" id="scheduled_0" class="status_flag" name="status_flag[0]" value="0" formControlName="radio" checked>
		                                    <span>To be Scheduled</span>
		                                    <span class="checkmark"></span>
		                                </label>
		                                <label class="radio radio-primary col-md-4 float-left">
		                                    <input type="radio" id="completed_0" class="status_flag" name="status_flag[0]" value="1" formControlName="radio">
		                                    <span>Completed</span>
		                                    <span class="checkmark"></span>
		                                </label>
								</div>
							</div>
							<div class='row ml-1'>
								<div class="col-md-6 form-group">
									<textarea name="notes[]" class="forms-element form-control" id="notes_0" placeholder="Notes"></textarea>
									<div class="invalid-feedback"></div>
								</div>
								<div class="col-md-2 form-group">@date('task_date[]',["id"=>"task_date_0"])</div>
							</div>
                        </div>
                        <!-- button add and minus task -->
						<div class="col-md-1 form-group">
                        	<i class="plus-icons i-Add"  id="add_followup_task" title="Add Follow-up Task"></i>
                    	</div>
                        <div class="col-md-12 form-group mb-3" id="append_followup_task"><hr></div>
                    </div>
					<div class="mb-4">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label class="forms-element checkbox checkbox-outline-primary">
									<input type="checkbox" name="emr_complete" id="emr_complete" value="1"><span>EMR system entry completed</span><span class="checkmark"></span>
								</label>
								<div id="followup_emr_system_entry_complete_error" class="invalid-feedback"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 text-right">
								<button type="submit" id="save-followup"class="btn  btn-primary m-1 office-visit-save" >Save</button>
							</div> 
						</div>	
					</div>
				</div>
				<hr>	
				<!-- table -->
					<div class="col-md-12">
				    	<div class="table-responsive">
				          <table id="task-list" class="display table table-striped table-bordered" style="width:100%">
				            <thead>
				                <tr>
				                    <th>Sr No.</th>                        
				                    <th>Task</th>    
				                    <th>Category</th>
				                    <th>Notes</th>
				                    <th>Date Scheduled</th>
				                    <th>Task Time</th>
				                    <!-- <th>Status</th> --> 
				                    <th>Mark as Complete</th>
				                    <th>Task Completed Date</th> 
				                    <th>Created By</th>
				                </tr>
				            </thead>
				          </table>
				        </div> 
				    </div>
				<!-- end table -->
				<div class="card-footer"> 
					<div class="mc-footer">
					</div>
				</div>
			</div>
		</form>
	</div>
<!--start edit model -->
<div class="modal fade" id="edit_notes_modal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modelHeading1">Modify Followup Task</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form  action="{{ route("save.followup.edit.data") }}" method="post" name ="followup_task_edit_notes"  id="followup_task_edit_notes">
				<div class="modal-body">
					@csrf 
					<?php 
					   $module_id    = getPageModuleName();
				       $submodule_id = getPageSubModuleName();
				       $stage_id =  getFormStageId($module_id , $submodule_id, 'Follow Up');
					?>
					<input type="hidden" name="uid" value="{{$patient_id}}" />
					<input type="hidden" name="patient_id" id="patient_id" value="{{$patient_id}}" />
					<input type="hidden" name="start_time" value="00:00:00">
					<input type="hidden" name="end_time" value="00:00:00">
					<input type="hidden" name="module_id" value="{{ getPageModuleName() }}" />
					<input type="hidden" name="component_id" value="{{ getPageSubModuleName() }}" />
					<input type="hidden" name="stage_id" value="{{$stage_id}}" />
					<input type="hidden" name="step_id" value="0">
					<input type="hidden" name="form_name" value="followup_task_edit_notes">
					<!-- <input type="hidden" name="id" id="hiden_id"> -->
					@hidden('id',['id' =>'hiden_id'])
					@hidden('topic',['id'=>'topic'])
					<p><b>Task : </b><span id ="task_notes"></span></p>
					<p><b>Category : </b><span id ="category"></span> </p>
					<!-- <p><b>Date : </b><span id ="task_date"></span></p> -->
					<p>@date('task_date',["id"=>"task_date_val"])</p>
					<textarea id="notes" name ="notes" class="forms-element form-control"></textarea>
					<div class="form-group col-md-12 mt-2">
						<label class="forms-element checkbox checkbox-outline-primary">
							<input type="checkbox" id="status_flag" name="status_flag"><span>Mark as completed</span><span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn  btn-primary m-1" >Save</button>
				</div>
			</form>
		</div>
	</div>
</div> 
<!--end edit model -->
</div>