<template>
	<div class="row">
		<div class="col-lg-6" style="border-right: 1px solid; border-color: #eaf1f3">
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_condition_requirnment_new`" class="checkbox  checkbox-primary mr-3">
							<input type="checkbox" name="condition_requirnment1"
								:id="`${sectionName}_condition_requirnment_new`" class="CRclass" formControlName="checkbox"
								v-model="conditionRequirnment1" @change="checkConditionRequirnments()"  :checked="conditionRequirnment1" value="1">
							<span>New Hospitalization</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_condition_requirnment_er_visit`"
							class="checkbox  checkbox-primary mr-3">
							<input type="checkbox" name="condition_requirnment2"
								:id="`${sectionName}_condition_requirnment_er_visit`" class="CRclass"
								formControlName="checkbox" v-model="conditionRequirnment2" @change="checkConditionRequirnments()"  :checked="conditionRequirnment2" value="1">
							<span>ER Visits</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_condition_requirnment_urgent_care`"
							class="checkbox  checkbox-primary mr-3">
							<input type="checkbox" name="condition_requirnment3"
								:id="`${sectionName}_condition_requirnment_urgent_care`" class="CRclass"
								formControlName="checkbox" v-model="conditionRequirnment3" @change="checkConditionRequirnments()"  :checked="conditionRequirnment3" value="1">
							<span>Urgent Care</span>
							<span class="checkmark"></span>
						</label>

						<label :for="`${sectionName}_condition_requirnment_none`" class="checkbox  checkbox-primary mr-3">
							<input type="checkbox" name="condition_requirnment4"
								:id="`${sectionName}_condition_requirnment_none`" class="CRclass" formControlName="checkbox"
								v-model="conditionRequirnment4" @click="noneConditionRequireement()"  :checked="conditionRequirnment4" value="0">
							<span>None</span>
							<span class="checkmark"></span>
							<span class="error">*</span>
						</label>
					</div>
				</div> 
				<div :id="`${sectionName}_CPmsg`" class="invalid-feedback" style="font-size: 13px;"></div>
			</div>
			<div v-if="((conditionRequirnment1 == 1 || conditionRequirnment2 == 1 || conditionRequirnment3 == 1) &&  conditionRequirnment4!=1)"
				:id="`${sectionName}_note`" class="notes mb-4">
				<textarea class="form-control" name="condition_requirnment_notes"
					:id="`${sectionName}_condition_requirnment_notes`" v-model="condition_requirnment_notes"></textarea>
				<div :id="`${sectionName}_condition_requirnment_notes`" class="invalid-feedback"></div>
			</div>
			<!-- New Office Visit  -->
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<span class="mr-3 mb-4"><b>New Office Visit(s): <span class="error">*</span></b></span>
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_newofficevisit_yes`" class="radio radio-primary mr-3">
							<input type="radio" formControlName="radio" name="newofficevisit"
								:id="`${sectionName}_newofficevisit_yes`" v-model="officeVisitYesNo" :checked ="officeVisitYesNo=='1'" value="1">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_newofficevisit_no`" class="radio radio-primary mr-3">
							<input type="radio" formControlName="radio" name="newofficevisit"
								:id="`${sectionName}_newofficevisit_no`" v-model="officeVisitYesNo" :checked ="officeVisitYesNo=='0'" value="0">
							<span>No</span>
							<span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_newofficevisit`" class="invalid-feedback">office visit</div>
			</div>
			<div v-if="officeVisitYesNo == '1'" :id="`${sectionName}_new-office-visit-note`" class="office_visit_note mb-4">
				<textarea class="form-control" name="nov_notes" :id="`${sectionName}_nov_notes`" v-model="officeVisitNotes"></textarea>
				<div :id="`${sectionName}_nov_notes`" class="invalid-feedback"></div>
			</div>
			<!-- End New Office Visit  -->
			<!-- New Dignosis -->
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<span class="mr-3 mb-4"><b>New Condition(s)</b>: <span class="error">*</span></span>
					<div class="mr-3 d-inline-flex align-self-center">
						<label :for="`${sectionName}_newdiagnosis_yes`" class="radio radio-primary mr-3">
							<input type="radio" name="newdiagnosis" :id="`${sectionName}_newdiagnosis_yes`" v-model="newDiagnosisYesNo" :checked="newDiagnosisYesNo=='1'" value="1"
								formControlName="radio">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_newdiagnosis_no`" class="radio radio-primary mr-3">
							<input type="radio" name="newdiagnosis" :id="`${sectionName}_newdiagnosis_no`" v-model="newDiagnosisYesNo" :checked="newDiagnosisYesNo=='0'" value="0"
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
			<div v-if="newDiagnosisYesNo == '1'"  :id="`${sectionName}_new-dignosis`" class="new_diagnosis_note mb-4">
				<textarea class="form-control" name="nd_notes" :id="`${sectionName}_nd_notes`" v-model="newDiagnosisNotes"></textarea>
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
								value="1" formControlName="radio" v-model="med_added_or_disconYesNo" :checked = "med_added_or_disconYesNo=='1'">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_med_added_or_discon_no`" class="radio radio-primary mr-3">
							<input type="radio" name="med_added_or_discon" :id="`${sectionName}_med_added_or_discon_no`"
								value="0" formControlName="radio" v-model="med_added_or_disconYesNo" :checked = "med_added_or_disconYesNo=='0'">
							<span>No</span>
							<span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_med_added_or_discon`" class="invalid-feedback"></div>
			</div>
			<div v-if="med_added_or_disconYesNo == 'Yes'">
			<div :id="`${sectionName}_new-medication-model`" class="med_add_dis_note mb-4">
					<button type="button" :id="`${sectionName}-medication-model`" class="btn btn-primary edit_medication" @click="openModal">Edit Medication</button>
	 				<ModalForm ref="modalForm" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
				<div :id="`${sectionName}_nd_notes-model`" class="invalid-feedback"></div>
			</div>
			<div :id="`${sectionName}_medication-added-or-discontinued`" class="med_add_dis_note mb-4">
				<textarea class="form-control" name="med_added_or_discon_notes"
					:id="`${sectionName}_med_added_or_discon_notes`" V-model="med_added_or_disconNotes"></textarea>
				<div :id="`${sectionName}_med_added_or_discon_notes`" class="invalid-feedback"></div>
			</div>
		</div>
			<label :for="`${sectionName}_allergies-model`" class="mr-3 mb-4"><b>Allergies add or edit: </b>
				<button type="button" name="allergies_id" :id="`${sectionName}_allergies-model`" class="btn btn-primary click_id allergiesclick" @click="openAllergiesModal">Edit Allergies</button>
				<AllergiesModalForm ref="allergiesModalForm" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
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
							<input type="radio" name="pcp_reviwewd" :id="`${sectionName}_pcp_reviwewd_yes`" V-model="pcpReviwewdYesNo" :checked="pcpReviwewdYesNo=='1'"
							value="1"
								formControlName="radio">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_pcp_reviwewd_no`" class="radio radio-primary mr-3">
							<input type="radio" name="pcp_reviwewd" :id="`${sectionName}_pcp_reviwewd_no`" V-model="pcpReviwewdYesNo" :checked="pcpReviwewdYesNo=='0'"
							value="0"
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
								:id="`${sectionName}_report_requirnment_new_lab`" v-model="report_requirnment1" class="RRclass"
								formControlName="checkbox" @change="checkReportRequirnments()" :checked="report_requirnment1" value="1">
							<span>New Labs</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_report_requirnment_diag_img`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment2"
								:id="`${sectionName}_report_requirnment_diag_img`" v-model="report_requirnment2" class="RRclass"
								formControlName="checkbox"  @change="checkReportRequirnments()" :checked="report_requirnment2" value="1">
							<span>Diagnostic Imaging</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_report_requirnment_health`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment4"
								:id="`${sectionName}_report_requirnment_health`" v-model="report_requirnment4" class="RRclass"
								formControlName="checkbox" @change="checkReportRequirnments()" :checked="report_requirnment4" value="1">
							<span>Health Data</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_report_requirnment_new_vitals`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment5"
								:id="`${sectionName}_report_requirnment_new_vitals`" v-model="report_requirnment5" class="RRclass"
								formControlName="checkbox" @change="checkReportRequirnments()" :checked="report_requirnment5" value="1">
							<span>Vitals Data</span>
							<span class="checkmark"></span>
						</label>
						<label :for="`${sectionName}_report_requirnment_none`" class="checkbox checkbox-primary mr-3">
							<input type="checkbox" name="report_requirnment3" :id="`${sectionName}_report_requirnment_none`" v-model="report_requirnment3"
								 class="RRclass" formControlName="checkbox" @click="noneReportRequirements()" value="0">
							<span>None</span><span class="error">*</span>
							<span class="checkmark"></span>
							<!-- <span class="error">*</span> -->
						</label>
					</div>
				</div>
				<div :id="`${sectionName}_report_requirnment`" class="invalid-feedback" style="font-size: 13px;"></div>
			</div>
			<div v-if="report_requirnment1 == 1 || report_requirnment2 == 1 || report_requirnment4 == 1 || report_requirnment5 == 1"
			 :id="`${sectionName}_requirnment`" class="rep_req_note mb-4">
				<div class="col-md-12 forms-element" id='report_requirnment_notes'>
					<label :for="`${sectionName}_vitalsHealth-modal`"
						class="mr-3 mb-4"><!-- <b>Vitals and Health Data added or edit:</b> -->
						<button type="button" :id="`${sectionName}_vitalsHealth-modal`" class="btn btn-primary" @click="openVitalsHealthDataModalForm">Modify Vitals & Health Data</button>
		 				<vitalsHealthDataModalForm ref="vitalsHealthDataModalForm" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
					</label>
				</div>
				<div :id="`${sectionName}_report_requirnment_notes`" class="invalid-feedback"></div>
			</div>
			<div class="form-row mb-4">
				<div class="col-md-12 forms-element">
					<label :for="`${sectionName}_services-modal`" class="mr-3 mb-4"><b>Services added or edit: </b>
						<button type="button" :id="`${sectionName}_services-modal`" class="btn btn-primary" @click="openServicesModal">Edit Services</button>
		 				<ServicesModalForm ref="servicesModalForm" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
					</label>
				</div>
			</div>
			<!-- End Services -->
			<div class="form-row mb-4">
				<div class="col-md-12">
					<div :id="`${sectionName}_step-1-medication-added-or-discontinued`">
						<span class="mr-3 mb-4"><b>Last Month’s Review:</b> </span>
						<textarea class="form-control mb-4" name="anything_else"
							:id="`${sectionName}_anything_else`" v-model="anything_else"></textarea>
						<div :id="`${sectionName}_anything_else`" class="invalid-feedback"></div>
					</div>
				</div>
			</div>
			<div class="form-row mb-4" :id="`${sectionName}_relation_building`">
				<div class="col-md-12 forms-element">
					<div :id="`${sectionName}_patient_relationship_building`" class="patient_relationship_building mb-4">
						<span><b>Patient Relationship Building<span class='error'>*</span></b></span>
						<textarea class="form-control forms-element" name="patient_relationship_building"
							:id="`${sectionName}_patient_relationship_building`" v-model="patient_relationship_building"></textarea>
						<div :id="`${sectionName}_patient_relationship_building`" class="invalid-feedback"></div>
					</div>
				</div>
			</div>
			<!--  -->
			<button type="button" :id="`${sectionName}_code-diagnosis-modal`" class="btn btn-primary createcareplanbutton"
				data-toggle="modal" data-target="#myModal" target="diagnosis-codes" style="display:none">Create Care
				Plan</button>&nbsp;&nbsp;
			<button type="button" :id="`${sectionName}_code-diagnosis-modal`" class="btn btn-primary reviewcareplanbutton" data-toggle="modal" data-target="#myModal" target="diagnosis-codes" @click="openReviewCarePlanModalModal">Review Care Plan</button>
			<ReviewCarePlanModal ref="ReviewCarePlanModal" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
			<mark data-toggle="tooltip" title="Assess clinical relevance and ICD10 code" class="reviewcareplanbuttoncount"
				:id="`${sectionName}_reviewcareplanbuttoncount`"></mark>

			<div class="form- mt-3 mb-4">
				<div class="col-md-12 forms-element">
					<span>
						<b>Print Care Plan : </b>
						<a :href="generatePdfUrl()" class="btn btn-primary" target="_blank">PDF</a>&nbsp;&nbsp;
						<a :href="generateWordUrl() " class="btn btn-primary" target="_blank">Word</a>
				</span>
			</div>
		</div>
	</div>
	<!-- end::form -->
</div></template>

<script>
import ModalForm from '../../Modals/Medication.vue';
import AllergiesModalForm from '../../Modals/Allergies.vue';
import ReviewCarePlanModal from '../../Modals/ReviewCarePlanModal.vue';
import ServicesModalForm from '../../Modals/Services.vue';
import vitalsHealthDataModalForm from '../../Modals/VitalsHealthData.vue';
export default {
	props: {
		sectionName: String,
		patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
	components: {
		ModalForm ,
		ReviewCarePlanModal,
	},
	components: {
		ModalForm, // Register the modal component
		AllergiesModalForm,
		ServicesModalForm,
		vitalsHealthDataModalForm,
		ReviewCarePlanModal
	},
	data() {
		return {
			// time:null,
			conditionRequirnment1:'',
			conditionRequirnment2:'',
			conditionRequirnment3:'',
			conditionRequirnment4:'',

			report_requirnment1:'',
			report_requirnment2:'',
			report_requirnment4:'',
			report_requirnment5:'',

			officeVisitYesNo:'',

			newDiagnosisYesNo:'',

			pcpReviwewdYesNo :'',

			med_added_or_disconYesNo:'',
			
			data_present_in_emrYesNO:'',
		};
	},
	mounted() {
        // this.time = document.getElementById('page_landing_times').value;
        const script = document.createElement('script');
		script.src = '/assets/js/laravel/iapp.js';
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
		this.populateFuntion(this.patientId); 
	},
	methods: {
		noneConditionRequireement() {
			this.conditionRequirnment1 = 0;
			this.conditionRequirnment2 = 0;
			this.conditionRequirnment3 = 0;
		},
		checkConditionRequirnments() {
		    this.conditionRequirnment4 = 0;
			if (this.conditionRequirnment1 === 1 || this.conditionRequirnment2 === 1 || this.conditionRequirnment3 === 1) {
				this.conditionRequirnment4 = 0; // Uncheck conditionRequirnment4 if any other checkbox is checked
			}
		},
		noneReportRequirements() {
			this.report_requirnment1 = 0;
			this.report_requirnment2 = 0;
			this.report_requirnment4 = 0;
			this.report_requirnment5 = 0;
		},
		checkReportRequirnments() {
			this.report_requirnment3 = 0; 
			if (this.report_requirnment1 === 1 || this.report_requirnment2 === 1 || this.report_requirnment4 === 1 || this.report_requirnment5 === 1) {
				this.report_requirnment3 = 0; // Uncheck conditionRequirnment4 if any other checkbox is checked
			}
		},
		async populateFuntion(patientId){ 
			try{
				const response = await fetch(`/ccm/populate-monthly-monitoring-data/${patientId}`); 
				if(!response.ok){ 
						throw new Error(`Failed to fetch Patient Preaparation - ${response.status} ${response.statusText}`);
				}
				const data = await response.json();
				this.patientPrepSaveDetails = data;
				if(this.patientPrepSaveDetails.populateCallPreparation!=''){
					this.data_present_in_emrYesNO = this.patientPrepSaveDetails.populateCallPreparation[0].submited_to_emr;
					this.conditionRequirnment1 = this.patientPrepSaveDetails.populateCallPreparation[0].condition_requirnment1;
					this.conditionRequirnment2 = this.patientPrepSaveDetails.populateCallPreparation[0].condition_requirnment2;
					this.conditionRequirnment3 = this.patientPrepSaveDetails.populateCallPreparation[0].condition_requirnment3;
					this.conditionRequirnment4 = this.patientPrepSaveDetails.populateCallPreparation[0].condition_requirnment4;

					this.condition_requirnment_notes = this.patientPrepSaveDetails.populateCallPreparation[0].condition_requirnment_notes;
					this.officeVisitYesNo =  this.patientPrepSaveDetails.populateCallPreparation[0].newofficevisit;
					this.officeVisitNotes =  this.patientPrepSaveDetails.populateCallPreparation[0].nov_notes; 

					this.newDiagnosisYesNo = this.patientPrepSaveDetails.populateCallPreparation[0].newdiagnosis;
					this.newDiagnosisNotes = this.patientPrepSaveDetails.populateCallPreparation[0].nd_notes;
					
					this.med_added_or_disconYesNo = this.patientPrepSaveDetails.populateCallPreparation[0].med_added_or_discon;
					this.med_added_or_disconNotes = this.patientPrepSaveDetails.populateCallPreparation[0].med_added_or_discon_notes;

					this.pcpReviwewdYesNo = this.patientPrepSaveDetails.populateCallPreparation[0].pcp_reviwewd;
					
					this.report_requirnment1 = this.patientPrepSaveDetails.populateCallPreparation[0].report_requirnment1;
					this.report_requirnment2 = this.patientPrepSaveDetails.populateCallPreparation[0].report_requirnment2;
					this.report_requirnment4 = this.patientPrepSaveDetails.populateCallPreparation[0].report_requirnment4;
					this.report_requirnment3 = this.patientPrepSaveDetails.populateCallPreparation[0].report_requirnment3;
					this.report_requirnment5 = this.patientPrepSaveDetails.populateCallPreparation[0].report_requirnment5;
					
					this.anything_else = this.patientPrepSaveDetails.populateCallPreparation[0].anything_else;
					this.patient_relationship_building =  this.patientPrepSaveDetails.populateCallPreparation[0].patient_relationship_building;
				}
				console.log('Fetched Patient Preaparation details:', data);
				
			}catch(error){
				console.error('Error fetching Patient Preaparation:', error.message); // Log specific error message
			}
		},
		openModal() {
			// Access the modal component through a ref
			console.log("openMModel called");
			this.$refs.modalForm.openModal();
		},
		openReviewCarePlanModalModal() {
			// Access the modal component through a ref
			console.log("ReviewCarePlanModal called");
			this.$refs.ReviewCarePlanModal.openModal();
			this.$refs.modalForm.openModal();
		},
		openAllergiesModal() {
			console.log("openAllergiesModal called");
			this.$refs.allergiesModalForm.openModal();
		},
		openServicesModal() {
			this.$refs.servicesModalForm.openModal();
		},

		openVitalsHealthDataModalForm() {
			this.$refs.vitalsHealthDataModalForm.openModal();
		},
	},
	setup(props){
		const generatePdfUrl = () => {
      return `/ccm/monthly-monitoring/patient-care-plan/${props.patientId}`;
    };
	const generateWordUrl = () => {
      return `/ccm/monthly-monitoring/generate-docx/${props.patientId}`;
    };
		return{
			generatePdfUrl,
			generateWordUrl,
		};
	}

}



</script>
