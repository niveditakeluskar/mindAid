
<template>
	<div class="row">
		<div class="col-lg-6" style="border-right: 1px solid; border-color: #eaf1f3">
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_condition_requirnment_new`" class="checkbox  checkbox-primary mr-3">
							<input type="checkbox" name="condition_requirnment1"
								:id="`${sectionName}_condition_requirnment_new`" class="CRclass" formControlName="checkbox"
								v-model="conditionRequirnment1" @change="checkRequirnments()">
							<span>New Hospitalization</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_condition_requirnment_er_visit`"
							class="checkbox  checkbox-primary mr-3">
							<input type="checkbox" name="condition_requirnment2"
								:id="`${sectionName}_condition_requirnment_er_visit`" class="CRclass"
								formControlName="checkbox" v-model="conditionRequirnment2" @change="checkRequirnments()">
							<span>ER Visits</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_condition_requirnment_urgent_care`"
							class="checkbox  checkbox-primary mr-3">
							<input type="checkbox" name="condition_requirnment3"
								:id="`${sectionName}_condition_requirnment_urgent_care`" class="CRclass"
								formControlName="checkbox" v-model="conditionRequirnment3" @change="checkRequirnments()">
							<span>Urgent Care</span>
							<span class="checkmark"></span>
						</label>

						<label :for="`${sectionName}_condition_requirnment_none`" class="checkbox  checkbox-primary mr-3">
							<input type="checkbox" name="condition_requirnment4"
								:id="`${sectionName}_condition_requirnment_none`" class="CRclass" formControlName="checkbox"
								v-model="conditionRequirnment4" @click="noneConditionRequireement()">
							<span>None</span>
							<span class="checkmark"></span>
							<span class="error">*</span>
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_CPmsg`" class="invalid-feedback" style="font-size: 13px;"></div>
			</div>
			<div v-if="conditionRequirnment1 == true || conditionRequirnment2 == true || conditionRequirnment3 == true"
				:id="`${sectionName}_note`" class="notes mb-4">
				<textarea class="form-control" name="condition_requirnment_notes"
					:id="`${sectionName}_condition_requirnment_notes`"></textarea>
				<div :id="`${sectionName}_condition_requirnment_notes`" class="invalid-feedback"></div>
			</div>
			<!-- New Office Visit  -->
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<span class="mr-3 mb-4"><b>New Office Visit(s): <span class="error">*</span></b></span>
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_newofficevisit_yes`" class="radio radio-primary mr-3">
							<input type="radio" formControlName="radio" name="newofficevisit"
								:id="`${sectionName}_newofficevisit_yes`" v-model="officeVisitYesNo" value="Yes">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_newofficevisit_no`" class="radio radio-primary mr-3">
							<input type="radio" formControlName="radio" name="newofficevisit"
								:id="`${sectionName}_newofficevisit_no`" v-model="officeVisitYesNo" value="No">
							<span>No</span>
							<span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_newofficevisit`" class="invalid-feedback">office visit</div>
			</div>
			<div v-if="officeVisitYesNo == 'Yes'" :id="`${sectionName}_new-office-visit-note`" class="office_visit_note mb-4">
				<textarea class="form-control" name="nov_notes" :id="`${sectionName}_nov_notes`"></textarea>
				<div :id="`${sectionName}_nov_notes`" class="invalid-feedback"></div>
			</div>
			<!-- End New Office Visit  -->
			<!-- New Dignosis -->
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<span class="mr-3 mb-4"><b>New Condition(s)</b>: <span class="error">*</span></span>
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_newdiagnosis_yes`" class="radio radio-primary mr-3">
							<input type="radio" name="newdiagnosis" :id="`${sectionName}_newdiagnosis_yes`" v-model="newDiagnosisYesNo" value="Yes"
								formControlName="radio">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_newdiagnosis_no`" class="radio radio-primary mr-3">
							<input type="radio" name="newdiagnosis" :id="`${sectionName}_newdiagnosis_no`" v-model="newDiagnosisYesNo" value="No"
								formControlName="radio">
							<span>No</span>
							<span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_newdiagnosis`" class="invalid-feedback"></div>
			</div>
			<div :id="`${sectionName}_new-dignosis-model`" class="new_diagnosis_note mb-4">
				<div :id="`${sectionName}_nd_notes-model`" class="invalid-feedback"></div>
			</div>
			<div v-if="newDiagnosisYesNo == 'Yes'"  :id="`${sectionName}_new-dignosis`" class="new_diagnosis_note mb-4">
				<textarea class="form-control" name="nd_notes" :id="`${sectionName}_nd_notes`"></textarea>
				<div :id="`${sectionName}_nd_notes`" class="invalid-feedback"></div>
			</div>
			<!-- End New Dignosis -->
			<!-- Medications added or discontinued -->
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<span class="mr-3 mb-4"><b>Medications added or discontinued:</b> <span class="error">*</span></span>
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_med_added_or_discon_yes`" class="radio radio-primary mr-3">
							<input type="radio" name="med_added_or_discon" :id="`${sectionName}_med_added_or_discon_yes`"
								value="1" formControlName="radio">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_med_added_or_discon_no`" class="radio radio-primary mr-3">
							<input type="radio" name="med_added_or_discon" :id="`${sectionName}_med_added_or_discon_no`"
								value="0" formControlName="radio">
							<span>No</span>
							<span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_med_added_or_discon`" class="invalid-feedback"></div>
			</div>
			<div :id="`${sectionName}_new-medication-model`" class="med_add_dis_note mb-4" >
				<button type="button" :id="`${sectionName}_medications-modal`" class="btn btn-primary" data-toggle="modal"
					data-target="#myModal" target="medication">Edit Medication</button>
				<div :id="`${sectionName}_nd_notes-model`" class="invalid-feedback"></div>
			</div>
			<div :id="`${sectionName}_medication-added-or-discontinued`" class="med_add_dis_note mb-4">
				<textarea class="form-control" name="med_added_or_discon_notes"
					:id="`${sectionName}_med_added_or_discon_notes`"></textarea>
				<div :id="`${sectionName}_med_added_or_discon_notes`" class="invalid-feedback"></div>
			</div>
			<label :for="`${sectionName}_allergies-model`" class="mr-3 mb-4"><b>Allergies add or edit: </b>
				<button type="button" name="allergies_id" :id="`${sectionName}_allergies-model`"
					class="btn btn-primary click_id allergiesclick" data-toggle="modal" data-target="#myModal"
					target="allergy-information">Edit Allergies</button>
			</label>
			<div :id="`${sectionName}_nd_notes-model`" class="invalid-feedback"></div>
			<input type="hidden" name="this_month" value="1">
			<!-- // $section == 'call_preparation' &&  -->
			<div class="form-row mb-4 mt-2">
				<div class="col-md-12 forms-element">
					<span class="mr-3 mb-4"><b>Is a current copy of the Care Plan signed by the PCP and in the EMR?
						</b></span>
					<!-- Has the PCP reviewed, revised as needed, and signed the Care Plan in the last year? -->
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_pcp_reviwewd_yes`" class="radio radio-primary mr-3">
							<input type="radio" name="pcp_reviwewd" :id="`${sectionName}_pcp_reviwewd_yes`" value="1"
								formControlName="radio">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_pcp_reviwewd_no`" class="radio radio-primary mr-3">
							<input type="radio" name="pcp_reviwewd" :id="`${sectionName}_pcp_reviwewd_no`" value="0"
								formControlName="radio">
							<span>No</span>
							<span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_pcp_reviwewd`" class="invalid-feedback"></div>
			</div>
			<!-- <input type="hidden" name="this_month" value="0"> -->
			<!-- End Medications added or discontinued -->
			<!-- start Solid Bar -->
		</div>
		<div class="col-lg-6 ">
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_report_requirnment_new_lab`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment1"
								:id="`${sectionName}_report_requirnment_new_lab`" value="1" class="RRclass"
								formControlName="checkbox">
							<span>New Labs</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_report_requirnment_diag_img`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment2"
								:id="`${sectionName}_report_requirnment_diag_img`" value="1" class="RRclass"
								formControlName="checkbox">
							<span>Diagnostic Imaging</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_report_requirnment_health`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment4"
								:id="`${sectionName}_report_requirnment_health`" value="1" class="RRclass"
								formControlName="checkbox">
							<span>Health Data</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_report_requirnment_new_vitals`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment5"
								:id="`${sectionName}_report_requirnment_new_vitals`" value="1" class="RRclass"
								formControlName="checkbox">
							<span>Vitals Data</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_report_requirnment_none`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment3" :id="`${sectionName}_report_requirnment_none`"
								value="1" class="RRclass" formControlName="checkbox">
							<span>None</span><span class="error">*</span>
							<span class="checkmark"></span>
							<!-- <span class="error">*</span> -->
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_report_requirnment`" class="invalid-feedback" style="font-size: 13px;"></div>
			</div>
			<div :id="`${sectionName}_requirnment`" class="rep_req_note mb-4">
				<div class="col-md-12 forms-element" id='report_requirnment_notes'>
					<label :for="`${sectionName}_vitalsHealth-modal`"
						class="mr-3 mb-4"><!-- <b>Vitals and Health Data added or edit:</b> -->
						<button type="button" :id="`${sectionName}_vitalsHealth-modal`" class="btn btn-primary"
							data-toggle="modal" data-target="#myModal" target="vitalsHealth">Modify Vitals & Health
							Data</button>
					</label>
				</div>
				<div :id="`${sectionName}_report_requirnment_notes`" class="invalid-feedback"></div>
			</div>
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<label :for="`${sectionName}_services-modal`" class="mr-3 mb-4"><b>Services added or edit: </b>
						<button type="button" :id="`${sectionName}_services-modal`" class="btn btn-primary"
							data-toggle="modal" data-target="#myModal" target="healthcare-services">Edit Services</button>
					</label>
				</div>
			</div>
			<!-- End Services -->
			<div class="form-row mb-4">
				<div class="col-md-12">
					<div :id="`${sectionName}_step-1-medication-added-or-discontinued`">
						<span class="mr-3 mb-4"><b>Last Month’s Review:</b> </span>
						<textarea class="form-control mb-4" name="anything_else"
							:id="`${sectionName}_anything_else`"></textarea>
						<div :id="`${sectionName}_anything_else`" class="invalid-feedback"></div>
					</div>
				</div>
			</div>
			<div class="form-row mb-4" :id="`${sectionName}_relation_building`" style='display:none'>
				<div class="col-md-12 forms-element">
					<div :id="`${sectionName}_patient_relationship_building`" class="patient_relationship_building mb-4">
						<span><b>Patient Relationship Building<span class='error'>*</span></b></span>
						<textarea class="form-control forms-element" name="patient_relationship_building"
							:id="`${sectionName}_patient_relationship_building`"></textarea>
						<div :id="`${sectionName}_patient_relationship_building`" class="invalid-feedback"></div>
					</div>
				</div>
			</div>
			<!--  -->
			<button type="button" :id="`${sectionName}_code-diagnosis-modal`" class="btn btn-primary createcareplanbutton"
				data-toggle="modal" data-target="#myModal" target="diagnosis-codes" style="display:none">Create Care
				Plan</button>&nbsp;&nbsp;
			<button type="button" :id="`${sectionName}_code-diagnosis-modal`" class="btn btn-primary reviewcareplanbutton"
				data-toggle="modal" data-target="#myModal" target="diagnosis-codes">Review Care Plan</button><mark
				data-toggle="tooltip" title="Assess clinical relevance and ICD10 code" class="reviewcareplanbuttoncount"
				:id="`${sectionName}_reviewcareplanbuttoncount`"></mark>

			<div class="form- mt-3 mb-4">
				<div class="col-md-12 forms-element">
					<span>
						<b>Print Care Plan : </b>
						<a href="/ccm/{{\Request::segment(2)}}/patient-care-plan/{{$patient_id}}" class="btn btn-primary"
							target="_blank">PDF</a>&nbsp;&nbsp;
					<a href="/ccm/{{\Request::segment(2)}}/generate-docx/{{$patient_id}}" class="btn btn-primary"
						target="_blank">Word</a>
				</span>
			</div>
		</div>
	</div>
	<!-- end::form -->
</div></template>

<script>
export default {
	props: {
		sectionName: {
			type: String,
			required: true,
		},
	},

	data() {
		return {
			conditionRequirnment1: false,
			conditionRequirnment2: false,
			conditionRequirnment3: false,
			conditionRequirnment4: false,
			officeVisitYesNo:'No',
			newDiagnosisYesNo:'No',
		};
	},
	mounted() {
     const script = document.createElement('script');
    script.src = '/assets/js/laravel/ccmMonthlyMonitoring.js';
    script.async = true;

    // Listen to the script's onload event to ensure it's loaded properly
    script.onload = () => {
        console.log('Script has been loaded successfully.');
        // You can add logic here that relies on the loaded script
    };

    // Listen to any errors while loading the script
    script.onerror = () => {
        console.error('Error loading script.');
    };

    document.body.appendChild(script); 
},
	methods: {
		noneConditionRequireement() {
			this.conditionRequirnment1 = false;
			this.conditionRequirnment2 = false;
			this.conditionRequirnment3 = false;
		},
		checkRequirnments() {
			if (this.conditionRequirnment1 === true || this.conditionRequirnment2 === true || this.conditionRequirnment3 === true) {
				this.conditionRequirnment4 = false; // Uncheck conditionRequirnment4 if any other checkbox is checked
			}
		},
	},

}
</script>
