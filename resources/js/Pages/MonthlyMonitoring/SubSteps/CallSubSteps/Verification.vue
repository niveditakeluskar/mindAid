<template>
<div class="row">
	<div class="col-lg-12 mb-3">
		<!-- 	<div class="mb-3" ><b>Call Wrap up</b></div> -->
		<div class="card">
				<form id="hippa_form" name="hippa_form" @submit.prevent="submitVerificationForm">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-success" :style="{ display: showAlert ? 'block' : 'none' }" id="hippa-success-alert">
								<button type="button" class="close" data-dismiss="alert">x</button>
								<strong> Hippa data saved successfully! </strong><span id="text"></span>
							</div>
							<p class="mb-4"><b>Verify HIPAA script</b><br/></p>
							<div class="forms-element form-group d-inline-flex mb-2">
								<label class="radio radio-primary mr-4" for="verification">
									<input type="radio" name="verification" id="verification" value="1" formControlName="radio" v-model="verification" :checked="verification=='1'">
									<span>HIPAA Verified<span class="error">*</span></span>
									<span class="checkmark"></span> 
								</label> 
							</div>
							<div class="invalid-feedback" v-if="formErrors.verification" style="display: block;">{{ formErrors.verification[0] }}</div>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="mc-footer">
						<div class="row">
							<div class="col-lg-12 text-right">
							<!-- onclick="window.location.assign('#step-4')" -->
								<button type="submit" class="btn  btn-primary m-1" id="save-hippa">Next</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</template>
<script>
import axios from 'axios';
export default {
	props: {
		patientId: Number,
		moduleId: Number,
		componentId: Number,
	},
	data() {
		return {
			uid: '',
			patient_id: '',
			module_id: '',
			component_id: '',
			stageId: 0,
			stepId: 0,
			start_time: '',
			end_time: '',
			form_name: '',
			billable: '',
			verification:'',
			formErrors: {},
			showAlert: false,
		};
	},
	mounted() {
		this.getStageID();
		this.populateFuntion();
	},
	methods: {
		async getStageID() {
			try {
				let response = await axios.get(`/get_stage_id/${this.moduleId}/${this.componentId}/Hippa`);
				this.stageId = response.data.stageID;
			} catch (error) {
				throw new Error('Failed to fetch stageID');
			}
		},
		async populateFuntion(){ 
			try{
				const response = await fetch(`/ccm/populate-monthly-monitoring-data/${this.patientId}`);
				if(!response.ok){  
						throw new Error(`Failed to fetch Patient Preaparation - ${response.status} ${response.statusText}`);
				}
				const data = await response.json();
				this.patientPrepSaveDetails = data;
				if(this.patientPrepSaveDetails.populateHippa!=''){
					this.verification = this.patientPrepSaveDetails.populateHippa.static.verification;
				}
			}catch(error){
				console.error('Error fetching Patient Preaparation:', error.message); // Log specific error message
			}
	    },
		async submitVerificationForm() {
			const formData = {
				uid: this.patientId,
				patient_id: this.patientId,
				module_id: this.moduleId,
				component_id: this.componentId,
				stage_id: this.stageId,
				step_id: this.stepId,
				form_name: 'hippa_form',
				billable: 1,
				start_time: "",
				end_time: "",
				verification: this.verification,
				_token: document.querySelector('meta[name="csrf-token"]').content, 
				timearr: {
					"form_start_time": document.getElementById('page_landing_times').value, //"12-27-2023 11:59:57",
					"form_save_time": "",
					"pause_start_time": "",
					"pause_end_time": "",
					"extra_time": ""
				},
			};
			axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
			try {
				this.formErrors = {};
				const response = await axios.post('/ccm/monthly-monitoring-call-hippa', formData);
				if (response && response.status == 200) {
					this.showAlert = true;
					updateTimer(this.patientId, 1, this.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
					setTimeout(() => {
						this.showAlert = false;
					}, 3000);
				}
			} catch (error) {
				if (error.response && error.response.status === 422) {
					this.formErrors = error.response.data.errors;
				} else {
					console.error('Error submitting form:', error);
				}
			}
		},
	},
}
</script>