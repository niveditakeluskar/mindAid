<div class="row">       
	<div class="col-lg-6" style="border-right: 1px solid; border-color: #eaf1f3">
		<div class="form-row mb-4">
		<div class="col-md-12 forms-element">
			<div class="mr-3 d-inline-flex align-self-center">
				<label for="{{$section}}_condition_requirnment_new" class="checkbox  checkbox-primary mr-3">
					<input type="checkbox" name="condition_requirnment1" id="{{$section}}_condition_requirnment_new" value="1" class="CRclass" formControlName="cehckbox" <?php (isset($callp[0]->condition_requirnment1) && ($callp[0]->condition_requirnment1 == "1") ) ? print("checked") : 'test'; ?>>
					<?php (isset($callp[0]->condition_requirnment1) && ($callp[0]->condition_requirnment1 == "1") ) ? print("checked") : 'test'; 
					?>
					<span>New Hospitalization</span>
					<span class="checkmark"></span>
				</label>
				<label for="{{$section}}_condition_requirnment_er_visit" class="checkbox  checkbox-primary mr-3">
					<input type="checkbox" name="condition_requirnment2" id="{{$section}}_condition_requirnment_er_visit" value="1" class="CRclass" formControlName="checkbox"
					<?php (isset($callp[0]->condition_requirnment2) && ($callp[0]->condition_requirnment2 == "1") ) ? print("checked") : 'tessst'; ?>>
					<span>ER Visits</span>
					<span class="checkmark"></span>
				</label>
				<label for="{{$section}}_condition_requirnment_urgent_care" class="checkbox  checkbox-primary mr-3">
					<input type="checkbox" name="condition_requirnment3" id="{{$section}}_condition_requirnment_urgent_care" value="1" class="CRclass" formControlName="checkbox" <?php (isset($callp[0]->condition_requirnment3) && ($callp[0]->condition_requirnment3 == "1") ) ? print("checked") : ''; ?>>
					<span>Urgent Care</span>
					<span class="checkmark"></span>
				</label>
				<label for="{{$section}}_condition_requirnment_none" class="checkbox  checkbox-primary mr-3">
					<input type="checkbox" name="condition_requirnment4" id="{{$section}}_condition_requirnment_none" value="1"  formControlName="checkbox"  <?php (isset($callp[0]->condition_requirnment4) && ($callp[0]->condition_requirnment4 == "1") ) ? print("checked") : ''; ?>> 
					<span>None</span>
					<span class="checkmark"></span>
					<span class="error">*</span>
				</label>
			</div>
		</div>		
		<div id="CPmsg" class="invalid-feedback" style="font-size: 13px;"></div>
		</div> 
		<div id="{{$section}}_note" class="notes mb-4" >

		<textarea class="form-control" name="condition_requirnment_notes" id="{{$section}}_condition_requirnment_notes"> <?php (isset($callp[0]->condition_requirnment_notes) && ($callp[0]->condition_requirnment_notes!='') ) ? print($callp[0]->condition_requirnment_notes) : ''; ?></textarea>
		<div id="condition_requirnment_notes" class="invalid-feedback"></div>
		</div>
		<!-- New Office Visit  -->
		<div class="form-row mb-4">
			<div class="col-md-12 forms-element">
				<label for="step-1_office_visit" class="mr-3 mb-4"><b>New Office Visit(s): <span class="error">*</span></b></label>
				<div class="mr-3 d-inline-flex align-self-center">
					<label for ="{{$section}}_newofficevisit_yes" class="radio radio-primary mr-3">
						<input type="radio"  formControlName="radio" name="newofficevisit" id="{{$section}}_newofficevisit_yes" value="1"
						<?php (isset($callp[0]->newofficevisit) && ($callp[0]->newofficevisit == "1") ) ? print("checked") : ''; ?>>
						<span>Yes</span>
						<span class="checkmark"></span>
					</label>
					<label for="{{$section}}_newofficevisit_no" class="radio radio-primary mr-3">
						<input type="radio" formControlName="radio" name="newofficevisit" id="{{$section}}_newofficevisit_no" value ="0" 
						<?php (isset($callp[0]->newofficevisit) && ($callp[0]->newofficevisit == "0") ) ? print("checked") : ''; ?>>
						<span>No</span>
						<span class="checkmark"></span>
					</label>
				</div>
			</div>
			<div id="newofficevisit" class="invalid-feedback">office visit</div>
		</div>
		<div id="{{$section}}_new-office-visit-note" class="office_visit_note mb-4" style="display: none">
			<textarea class="form-control" name="nov_notes" id="{{$section}}_nov_notes"><?php (isset($callp[0]->nov_notes) && ($callp[0]->nov_notes !='') ) ? print($callp[0]->nov_notes) : '';?></textarea>
			<div id="nov_notes" class="invalid-feedback"></div>
		</div>
		<!-- End New Office Visit  -->
		<!-- New Dignosis -->
		<div class="form-row mb-4">    
			<div class="col-md-12 forms-element">
				<label for="step-1_new_dignosis" class="mr-3 mb-4"><b>New Condition(s)</b>: <span class="error">*</span></label>
					<div class="mr-3 d-inline-flex align-self-center">
					<label for="{{$section}}_newdiagnosis_yes" class="radio radio-primary mr-3">
					<input type="radio" name="newdiagnosis"  id="{{$section}}_newdiagnosis_yes" value="1" formControlName="radio" 
					<?php (isset($callp[0]->newdiagnosis) && ($callp[0]->newdiagnosis == "1") ) ? print("checked") : ''; ?>>
					<span>Yes</span>
					<span class="checkmark"></span>
					</label>
					<label for="{{$section}}_newdiagnosis_no" class="radio radio-primary mr-3">
					<input type="radio" name="newdiagnosis" id="{{$section}}_newdiagnosis_no" value="0" formControlName="radio"
					<?php (isset($callp[0]->newdiagnosis) && ($callp[0]->newdiagnosis == "0") ) ? print("checked") : ''; ?>>
					<span>No</span>
					<span class="checkmark"></span>
					</label>
				</div>
			</div>
			<div id="newdiagnosis" class="invalid-feedback"></div>
		</div>
		<div id="{{$section}}_new-dignosis-model" class="new_diagnosis_note mb-4" style="display: none">
			<!--button id="{{$section}}-code-diagnosis-modal" class="btn btn-primary" data-toggle="modal" data-target="#myModal" target="diagnosis-codes">Diagnosis</button-->
			<div id="nd_notes-model" class="invalid-feedback"></div>
		</div>
		<div id="{{$section}}_new-dignosis" class="new_diagnosis_note mb-4" style="display: none">
			<textarea class="form-control" name="nd_notes" id="{{$section}}_nd_notes"><?php (isset($callp[0]->nd_notes) && ($callp[0]->nd_notes !='') ) ? print($callp[0]->nd_notes) : ''; ?> 
			</textarea>
			<div id="nd_notes" class="invalid-feedback"></div>
		</div>
		<!-- End New Dignosis -->
		<!-- Medications added or discontinued -->
		<div class="form-row mb-4">
			<div class="col-md-12 forms-element">
				<label for="step-1_medication_added_or_discontinued" class="mr-3 mb-4"><b>Medications added or discontinued:</b> <span class="error">*</span></label>
				<div class="mr-3 d-inline-flex align-self-center">
					<label for="{{$section}}_med_added_or_discon_yes" class="radio radio-primary mr-3">
					<input type="radio" name="med_added_or_discon" id="{{$section}}_med_added_or_discon_yes" value="1" formControlName="radio"
					<?php (isset($callp[0]->med_added_or_discon) && ($callp[0]->med_added_or_discon == "1") ) ?print("checked") : ''; ?> >
					<span>Yes</span>
					<span class="checkmark"></span>
					</label>
					<label for="{{$section}}_med_added_or_discon_no" class="radio radio-primary mr-3">
					<input type="radio" name="med_added_or_discon" id="{{$section}}_med_added_or_discon_no"  value="0" formControlName="radio"
					<?php (isset($callp[0]->med_added_or_discon) && ($callp[0]->med_added_or_discon == "0") ) ?print("checked") : '';?> >
					<span>No</span>
					<span class="checkmark"></span>
					</label>
				</div>
			</div>
			<div id="med_added_or_discon" class="invalid-feedback"></div>
		</div> 
		<div id="{{$section}}_new-medication-model" class="med_add_dis_note mb-4" style="display: none">
			<button type="button" id="{{$section}}-medications-modal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" target="medication">Edit Medication</button>
			<div id="nd_notes-model" class="invalid-feedback"></div>
		</div>
		<div id="{{$section}}_medication-added-or-discontinued" class="med_add_dis_note mb-4" style="display: none">
			<textarea class="form-control" name="med_added_or_discon_notes" id="{{$section}}_med_added_or_discon_notes"><?php (isset($callp[0]->med_added_or_discon_notes) && ($callp[0]->med_added_or_discon_notes !='') ) ? print($callp[0]->med_added_or_discon_notes) : '';?></textarea>
			<div id="med_added_or_discon_notes" class="invalid-feedback"></div>
		</div>
           <label for="allergies_id" class="mr-3 mb-4"><b>Allergies add or edit:</b>
			<button type="button" name="allergies_id" id="{{$section}}-allergies-model" class="btn btn-primary click_id allergiesclick" data-toggle="modal" data-target="#myModal" target="allergy-information">Edit Allergies</button>
			<div id="nd_notes-model" class="invalid-feedback"></div> 
			<?php if((date('F') == 'January' || date('F') == 'December')){?> 
				<input type ="hidden" name="this_month" value="1">
				<!-- // $section == 'call_preparation' &&  -->
                     <div class="form-row mb-4 mt-2">
                        <div class="col-md-12 forms-element"> 
                           <label for="step-1_pcp_reviwewd" class="mr-3 mb-4"><b>Is a current copy of the Care Plan signed by the PCP and in the EMR? </b></label>
							<!-- Has the PCP reviewed, revised as needed, and signed the Care Plan in the last year? -->
                           <div class="mr-3 d-inline-flex align-self-center">
                              <label for="{{$section}}_pcp_reviwewd_yes" class="radio radio-primary mr-3">
                              <input type="radio" name="pcp_reviwewd" id="{{$section}}_pcp_reviwewd_yes" value="1" formControlName="radio" 
                              <?php (isset($callp[0]->pcp_reviwewd) && ($callp[0]->pcp_reviwewd == "1") ) ? print("checked") : ''; ?>>
                              <span>Yes</span>
                              <span class="checkmark"></span>
                              </label>
                              <label for="{{$section}}_pcp_reviwewd_no" class="radio radio-primary mr-3">
                              <input type="radio" name="pcp_reviwewd" id="{{$section}}_pcp_reviwewd_no"  value="0" formControlName="radio" 
                              <?php (isset($callp[0]->pcp_reviwewd) && ($callp[0]->pcp_reviwewd == "0") ) ? print("checked") : ''; ?>>
                              <span>No</span>
                              <span class="checkmark"></span>
                              </label>
                           </div>
                        </div>
                        <div id="pcp_reviwewd" class="invalid-feedback"></div>
                     </div> 

                    <!--  <div class="form-row mb-4">
                        <div class="col-md-12 forms-element">
                           <label for="step-1_submited_to_emr" class="mr-3 mb-4"><b>Is the most recent version of the Care Plan in the EHR, and is it less than one year old? </b></label>
                           <div class="mr-3 d-inline-flex align-self-center">
                              <label for="{{$section}}_submited_to_emr_yes" class="radio radio-primary mr-3">
                              <input type="radio" name="submited_to_emr" id="{{$section}}_submited_to_emr_yes" value="1" formControlName="radio" 
                              <?php //(isset($callp[0]->submited_to_emr) && ($callp[0]->submited_to_emr == "1") ) ? print("checked") : ''; ?>>
                              <span>Yes</span>
                              <span class="checkmark"></span>
                              </label>
                              <label for="{{$section}}_submited_to_emr_no" class="radio radio-primary mr-3">
                              <input type="radio" name="submited_to_emr" id="{{$section}}_submited_to_emr_no"  value="0" formControlName="radio" 
                              <?php //(isset($callp[0]->submited_to_emr) && ($callp[0]->submited_to_emr == "0") ) ? print("checked") : ''; ?>>
                              <span>No</span>
                              <span class="checkmark"></span>
                              </label>
                           </div>
                        </div>
                        <div id="submited_to_emr" class="invalid-feedback"></div>
                     </div>  -->
					 <?php  }else{ ?> <input type ="hidden" name="this_month" value="0"> <?php } ?>
		<!-- End Medications added or discontinued -->
	<!-- start Solid Bar -->
	</div>
	<div class="col-lg-6 ">
		<div class="form-row mb-4">
		<div class="col-md-12 forms-element">
			<div class="mr-3 d-inline-flex align-self-center">
				<label for="{{$section}}_report_requirnment_new_lab" class="checkbox checkbox-primary mr-3">
					<input type="checkbox" name="report_requirnment1" id="{{$section}}_report_requirnment_new_lab" value="1" class="RRclass" formControlName="checkbox"
					<?php (isset($callp[0]->report_requirnment1) && ($callp[0]->report_requirnment1 == "1") ) ?print("checked") : '';?>>
					<span>New Labs</span>
					<span class="checkmark"></span>
				</label>
				<label for="{{$section}}_report_requirnment_diag_img" class="checkbox checkbox-primary mr-3">
					<input type="checkbox" name="report_requirnment2" id="{{$section}}_report_requirnment_diag_img" value="1" class="RRclass" formControlName="checkbox"
					<?php (isset($callp[0]->report_requirnment2) && ($callp[0]->report_requirnment2 == "1") ) ?print("checked") : '';?>>
					<span>Diagnostic Imaging</span>
					<span class="checkmark"></span>
				</label>
				<label for="{{$section}}_report_requirnment_health" class="checkbox checkbox-primary mr-3">
					<input type="checkbox" name="report_requirnment4" id="{{$section}}_report_requirnment_health" value="1" class="RRclass" formControlName="checkbox"
					<?php (isset($callp[0]->report_requirnment4) && ($callp[0]->report_requirnment4 == "1") ) ?print("checked") : '';?>>
					<span>Health Data</span>
					<span class="checkmark"></span>
				</label>
				<label for="{{$section}}_report_requirnment_new_vitals" class="checkbox checkbox-primary mr-3">
					<input type="checkbox" name="report_requirnment5" id="{{$section}}_report_requirnment_new_vitals" value="1" class="RRclass" formControlName="checkbox"
					<?php (isset($callp[0]->report_requirnment5) && ($callp[0]->report_requirnment5 == "1") ) ?print("checked") : '';?>>
					<span>Vitals Data</span>
					<span class="checkmark"></span>
				</label>
				<label for="{{$section}}_report_requirnment_none" class="checkbox checkbox-primary mr-3">
					<input type="checkbox" name="report_requirnment3"  id="{{$section}}_report_requirnment_none" value="1" class="RRclass" formControlName="checkbox"
					<?php (isset($callp[0]->report_requirnment3) && ($callp[0]->report_requirnment3 == "1") ) ?print("checked") : '';?>>
					<span>None</span><span class="error">*</span>
					<span class="checkmark"></span>
					<!-- <span class="error">*</span> -->
				</label>
			</div>
		</div> 
		<div id="report_requirnment" class="invalid-feedback" style="font-size: 13px;"></div>
		</div>
		<div id="{{$section}}_requirnment"  class="rep_req_note mb-4" >
			<div class="col-md-12 forms-element" id='{{$section}}_report_requirnment_notes'>
				<label for="step-1_vitalsHealth_add" class="mr-3 mb-4"><!-- <b>Vitals and Health Data added or edit:</b> -->
					<button type="button" id="{{$section}}-vitalsHealth-modal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" target="vitalsHealth">Modify Vitals & Health Data</button>
				</label>
			</div> 
		<div id="report_requirnment_notes" class="invalid-feedback"></div>
		</div>
		<div class="form-row mb-4">
			<div class="col-md-12 forms-element">
				<label for="services" class="mr-3 mb-4"><b>Services added or edit:</b>
					<button type="button" id="{{$section}}-services-modal"  type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" target="healthcare-services">Edit Services</button>
				</label>
			</div> 
		</div> 
	
	<!-- End Services -->

        <div class="form-row mb-4">
			<div class="col-md-12">
			<div id="step-1-medication-added-or-discontinued">
				<label for="" class="mr-3 mb-4"><b>Last Month’s Review:</b> </label>
				<textarea class="form-control mb-4" name="anything_else" id="{{$section}}_anything_else"><?php (isset($callp[0]->anything_else) && ($callp[0]->anything_else !='') ) ?print($callp[0]->anything_else) : '';?></textarea>
				<div id="anything_else" class="invalid-feedback"></div>
			</div>
		    </div> 
	    </div>
       <div class="form-row mb-4" id="relation_building" style='display:none'>
          <div class="col-md-12 forms-element">
            <div id="{{$section}}_patient_relationship_building" class="patient_relationship_building mb-4">
              <label><b>Patient Relationship Building<span class='error'>*</span></b></label> 
              
              <textarea class="form-control forms-element" name="patient_relationship_building" id="{{$section}}_patient_relationship_building"></textarea>
              <div id="patient_relationship_building" class="invalid-feedback"></div>
            </div>
          </div>
       </div>
		<!--  -->
		<button type="button" id="{{$section}}-code-diagnosis-modal" class="btn btn-primary createcareplanbutton" data-toggle="modal" data-target="#myModal" target="diagnosis-codes" style="display:none">Create Care Plan</button>
		<button type="button" id="{{$section}}-code-diagnosis-modal" class="btn btn-primary reviewcareplanbutton" data-toggle="modal" data-target="#myModal" target="diagnosis-codes" >Review Care Plan</button><mark data-toggle="tooltip"  title ="Assess clinical relevance and ICD10 code" class="reviewcareplanbuttoncount" id="reviewcareplanbuttoncount"></mark>
		
		
		<div class="form- mt-3 mb-4">
		<div class="col-md-12 forms-element">
			<label><b>Print Care Plan : </b></label> 
        	<a href="/ccm/{{\Request::segment(2)}}/patient-care-plan/{{$patient_id}}" class="btn btn-primary" target="_blank">PDF</a>
		    <a href="/ccm/{{\Request::segment(2)}}/generate-docx/{{$patient_id}}" class="btn btn-primary" target="_blank">Word</a>	      
        </div>
        </div>
	</div>
	<!-- end::form -->
</div>
