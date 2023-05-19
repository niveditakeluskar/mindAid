<!-- <div class="tsf-step-content"> -->
   <div class="row">
      <div class="col-lg-12 mb-3">
         <form id="enroll_services_form" name="enroll_services_form" action="{{ route("patient.enrollment.call.enrollservices") }}" method="post"> 
            @csrf 
            <?php
               $module_id = getPageModuleName();
               $submodule_id = getPageSubModuleName();
               $stage_id = getFormStageId($module_id, $submodule_id, 'Call');
               $module_select_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Enrollment');
            ?>
            <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
            <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
            <input type="hidden" name="start_time" value="00:00:00" />
            <input type="hidden" name="end_time" value="00:00:00" />
            <input type="hidden" name="module_id" value="{{ $module_id }}" />
            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
            <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
            <input type="hidden" name="step_id" value="{{ $module_select_step_id}}" />
            <input type="hidden" name="edit" value="1" />
            <input type="hidden" name="enrollment_id" class="enrollment_id" />
            <div class="card">
            	<div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call data saved successfully! </strong><span id="text"></span>
               </div>
               <div class=" card-body moduleCheck">
                  <div class="card-title">Enroll Services <span class="error">*</span></div>
                  <!-- <div class="row"> -->
                     <?php
                        if(isset($services)){
									$i = 0;
									foreach($services as $service){
												// echo $service['module'];
										// dd($service);
										?>			
										<?php //echo $service[0]['module']; 
											$enrollin = $service['module'];
											$modules[] = $service['module'];
											$moduleid = $service['id'];
										?>	<!--						
											<div class="col-md-3 form-group"> 
											   @checkbox($enrollin, "enroll[".$moduleid."]", "enroll_".$i , $moduleid , ["id"=> "enroll_".$i])
											</div>	-->									
										<?php
										$i++;
										
									}	
									?>
									<div class="col-md-3 form-group"> 
									@selectmodules("enroll[]",  ["id" => "enroll_0"])
									</div>
									<?php
								}
                     ?>
                  <!-- </div> -->
               </div>
               <div class="card-footer">
                  <div class="mc-footer text-right">
                     <button type="button" class="btn btn-secondary" onclick="backStep(2)"> Back </button>
                     <button type="submit" class="btn btn-primary m-1" style="display:none" id="save-enroll-services">Next</button>
                     <button type="button" class="btn btn-primary m-1" id="save_enroll_services">Next</button>
                  </div>
               </div>

            </div>
         </form>
      </div>
   </div>
<!-- </div> -->