<template>
	<div class="row">
		<div id="success"></div>
		<div class="col-lg-12 mb-4">
			<form id="call_close_form" name="call_close_form" @submit.prevent="submitCallCloseForm">
				<input type="hidden" name="stage_id" />
				<input type ="hidden" name="form" value="ccm"> 
				<div class="card">  
					<div class="card-body">
						<div class="mb-4">
							<div class="alert alert-success" id="success-alert" :style="{ display: showAlert ? 'block' : 'none' }">
								<button type="button" class="close" data-dismiss="alert">x</button>
								<strong>Call close data saved successfully! </strong><span id="text"></span>
							</div>
							<span>Are there any other issues impacting your health that you would like to talk about today that we have not addressed yet?<span class="error">*</span></span>
							<br>
							<div class="forms-element d-inline-flex"> 
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query1" value="1" v-model="query1">
									<span>Yes</span>
									<span class="checkmark"></span>
								</label>
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query1" value="0" v-model="query1">
									<span>No</span>
									<span class="checkmark"></span>
								</label>
							</div>
							<div class="invalid-feedback" v-if="formErrors.query1" style="display: block;">{{ formErrors.query1[0] }}</div>
							<div v-if="query1==1">
								<label class="mr-3 col-lg-12">Monthly Notes:
									<textarea class="forms-element form-control" name="q1_notes" id="q1_notes" v-model="q1_notes"></textarea>
								</label>
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="mb-4">
							<span>Do you have a preferred day and time for our call next month?<span class="error">*</span></span>
							<br>
							<div class="forms-element d-inline-flex">
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query2" value="1" id="newquery2_yes" v-model="query2">
									<span>Yes</span>
									<span class="checkmark"></span>
								</label> 
								<label class="radio radio-primary mr-3">
									<input type="radio" name="query2" value="0" id="newquery2_no" v-model="query2">
									<span>No</span>
									<span class="checkmark"></span>
								</label>
							</div>
							<div class="invalid-feedback" v-if="formErrors.query2" style="display: block;">{{ formErrors.query2[0] }}</div>
							<div v-if="query2 == 1 || query2 == 0" id="next_month_call_div" class="nextcall">
								<div class="mr-3 d-inline-flex align-self-center">
									<label class="forms-element mr-3">Select Date:<span class="error">*</span>
										<input type="date" name="q2_datetime" v-model="q2_datetime" id="next_month_call_date" class="forms-element form-control" />
										<div class="invalid-feedback" v-if="formErrors.q2_datetime" style="display: block;">{{ formErrors.q2_datetime[0] }}</div>
									</label>
									<label class="forms-element mr-3" >Select Time:<span class="error">*</span>
										<input type="time" name="q2_time" v-model="q2_time" id="next_month_call_time" class="forms-element form-control" />
										<div class="invalid-feedback" v-if="formErrors.q2_time" style="display: block;">{{ formErrors.q2_time[0] }}</div>
									</label>
								</div>
								<div class="">
									<label style="width: 100%;">Monthly Notes:
										<textarea class="forms-element form-control" name="q2_notes" v-model="q2_notes"></textarea>
									</label>
									<div class="invalid-feedback"></div>
								</div>
								<div>
									<hr />
									<div class="col-12 text-center"><h3>Best Time to contact</h3></div>
									<ContactTime />  
									<hr />
								</div> 
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="mc-footer">
							<div class="row">
								<div class="col-lg-12 text-right">
									<button type="submit" class="btn  btn-primary m-1 office-visit" id="save-call-close" :disabled="(timerStatus == 1) === true ">Next</button>								
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</template>
<script>
import ContactTime from '../../../../../laravel/js/components/ContactTime.vue';
import axios from 'axios';
export default {
	props: {
		patientId: Number,
		moduleId: Number,
		componentId: Number,
	},
	data() {
		return {
			stepId: 0,
			query1: null,
			query2: null,
			formErrors: {},
			showAlert: false,
			timerStatus:null,
		};
	},
	components: {
		ContactTime,
	},
	mounted() {
		this.getCallCloseStageID();
		this.timerStatus = document.getElementById('timer_runing_status').value;
	},
	methods: {
		async getCallCloseStageID() {
			try {
				let stageName = 'Call_Close';
				let response = await axios.get(`/get_stage_id/${this.moduleId}/${this.componentId}/${stageName}`);
				this.stageId = response.data.stageID;
			} catch (error) {
				throw new Error('Failed to fetch stageID');
			}
		},
		async submitCallCloseForm() {
			const formData = {
				uid: this.patientId,
				patient_id: this.patientId,
				module_id: this.moduleId,
				component_id: this.componentId,
				stage_id: this.stageId,
				step_id: this.stepId,
				form_name: 'call_close_form',
				form: 'ccm',
				billable: 1,
				start_time: "00:00:00",
				end_time: "00:00:00",
				_token: document.querySelector('meta[name="csrf-token"]').content,
				timearr: {
					"form_start_time": document.getElementById('page_landing_times').value, //"12-27-2023 11:59:57",
					"form_save_time": "",
					"pause_start_time": "",
					"pause_end_time": "",
					"extra_time": ""
				},
				query1: this.query1,
				q1_notes: this.q1_notes,
				query2: this.query2,
				q2_datetime: this.q2_datetime,
				q2_time: this.q2_time,
				q2_notes: this.q2_notes,
				mon_0: this.mon_0,
				mon_1: this.mon_1,
				mon_2: this.mon_2,
				mon_3: this.mon_3,
				mon_any: this.mon_any,
				tue_0: this.tue_0,
				tue_1: this.tue_1,
				tue_2: this.tue_2,
				tue_3: this.tue_3,
				tue_any: this.tue_any,
				wed_0: this.wed_0,
				wed_1: this.wed_1,
				wed_2: this.wed_2,
				wed_3: this.wed_3,
				wed_any: this.wed_any,
				thu_0: this.thu_0,
				thu_1: this.thu_1,
				thu_2: this.thu_2,
				thu_3: this.thu_3,
				thu_any: this.thu_any,
				fri_0: this.fri_0,
				fri_1: this.fri_1,
				fri_2: this.fri_2,
				fri_3: this.fri_3,
				fri_any: this.fri_any,
			};
			axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
			try {
				this.formErrors = {};
				const response = await axios.post('/ccm/monthly-monitoring-call-callclose', formData);
				if (response && response.status == 200) {
					this.showAlert = true;
					updateTimer(this.patientId, 1, this.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
					setTimeout(() => {
						this.showAlert = false;
					}, 3000);
					this.$emit('form-submitted');
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