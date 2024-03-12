<!-- <div class="tsf-step-content"> -->
<div class="row">
      <div class="col-lg-12 mb-3">
         <form name="finalization_checklist_form" id="finalization_checklist_form" action="{{ route("patient.enrollment.call.finalisedchecklist") }}" method="post"> 
            @csrf 
            <?php
               $module_id = getPageModuleName();
               $submodule_id = getPageSubModuleName();
               $stage_id = getFormStageId($module_id, $submodule_id, $enrollServiceName);
               $finalization_checklist_step_id = getFormStepId($module_id, $submodule_id, $stage_id, 'Finalization Checklist');
               $practice_data = $patient_providers['practice']; 
               $outgoing_phone_number = empty($practice_data['outgoing_phone_number']) ? '[Outgoing Phone Number]' : $practice_data['outgoing_phone_number']; 
            ?>
            <input type="hidden" name="time_rec_module" value="{{$module_id}}">
            <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
            <input type="hidden" name="uid" value="{{$patient[0]->UID}}" />
            <input type="hidden" name="start_time" value="00:00:00" />
            <input type="hidden" name="end_time" value="00:00:00" />
            <input type="hidden" name="module_id" value="{{ $enroll_module_id }}" />
            <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
            <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
            <input type="hidden" name="step_id" value="{{ $finalization_checklist_step_id}}" />
		      <input type="hidden" name="form_name" value="enrollment_finalization_checklist_form">
            <input type="hidden" name="enroll_id" class="enroll_id" value="{{$enroll_module_id}}"/>
            <div class="card">
            	<div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call data saved successfully! </strong><span id="text"></span>
               </div>
                <div id="error_msg2"></div>
               <div class=" card-body">
                  <div id="finalization_checklist_form_questionnaire">
                     Okay, to get started, I need to verify the information I have for you on file:

                     <div class="form-row ml-1">
                        <ol>
						<?php
							$homeno = empty($patient[0]->home_number)?'':$patient[0]->home_number;
							
							$contactno = empty($patient[0]->mob)?'':$patient[0]->mob.'?';
							if(empty($contactno)){
								$contactno = $homeno;
							}
							
							if(empty($contactno)){
								$numqtn = "What is your contact number?";
							}
                     else
                     {
                        $numqtn = "Is your number <strong>".$contactno."</strong>  If not then enter the correct contact number.";
                     }
						?>
						<li class="mb-3"><label class='col-md-12'><?php echo $numqtn; ?> </label> @phone("mob", ["id"=> "mob", "class"=>"col-md-3"]) </li>
                  <li class="mb-3"><label class='col-md-3'>Is this a cell phone? </label>
                     @select(" ","is_this_cell_phone", [
                        "yes" => "Yes",
                        "no"  => "No"
                     ],["id"  => "is_this_cell_phone",  "class" => "col-md-3 mb-3"])
                     <!-- <select id='is_this_cell_phone' name='is_this_cell_phone'><option></option><option value='yes'>Yes</option><option value='no'>No</option></select>  -->
                     <div class="do_you_have_cellph row mb-3" style='display:none;'>
                        <label class='col-md-3'>Do you have a cell phone? </label>
                        @select(" ","do_you_have_cell_phone", [
                           "yes" => "Yes",
                           "no"  => "No"
                        ],["id"  => "do_you_have_cell_phone",  "class" => "col-md-3 mb-3"])
                        <!-- <select name='do_you_have_cell_phone' class='custom-select col-md-3'><option></option><option>Yes</option><option>No</option></select>  -->
                        <div class="cell_phone" style="display: none">
                           @phone("cell_phone", ["id"=> "cell_phone", "class"=>"col-md-3"])
                        </div>
                     </div>
						
						</li>
                 <?php 
                  if(empty($PatientAddress->add_2) && empty($PatientAddress->add_1))
                  {
                     $patientaddress='What is your address ?';
                  }
                  else
                  {
                     $patientaddress="Is your address <strong>".$PatientAddress->add_1.",".$PatientAddress->add_2."</strong>?  If not then enter your correct address.";
                  }
                 ?>
						<li class="mb-3"><label class='col-md-12'><?php echo $patientaddress; ?></label> <input type='text' class='form-control' name="address"/>
						</li>
						<li class="mb-3"><label class='col-md-12'>Okay, last question, what typically is the best day and time of day for us to call you for your monthly check-in?</label>
							<div class="row" id="date-time"  >
                        <div class="col-md-12"> 							
								<contact-time></contact-time>                          
                        </div>
                       
                     </div>
						</li>
						</ol>
                     </div>
					 <div>
					 <!-- Also, wanted to let you know that when you get your monthly call, we will be calling you from ___________________.  Just wanted you to know that is us.  You should be expecting a call from your personal Care Manager within the next two weeks to get everything started.<br>
					 I enjoyed meeting you.  Thank you for enrolling.  Have a great day!!! -->
                Also, wanted to let you know that when you get your monthly call, we will be calling you from <?php echo $outgoing_phone_number; //$provider_data = (array)$patient_providers; echo empty($provider_data['outgoing_phone_number']) ? '[Outgoing Phone Number]' : $provider_data['outgoing_phone_number']; ?>.  Just wanted you to know that is us.  You should be expecting a call from your personal Care Manager within the next two weeks to get everything started.<br>
					 I enjoyed meeting you.  Thank you for enrolling.  Have a great day!!!

					 </div>
                  </div>
               </div>
               <div class="card-footer">
                  <div class="mc-footer text-right">
                     <!--<button type="button" class="btn btn-secondary" onclick="backStep(4)"> Back </button>-->
                     <button type="submit" class="btn btn-primary m-1" style="display:none" id="save-checkliststatus">Save</button>
                     <button type="button" class="btn btn-primary m-1" id="save_checkliststatus">Save</button>

                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
<!-- </div> -->

