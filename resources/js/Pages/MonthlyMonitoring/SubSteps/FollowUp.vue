<template>
	<loading-spinner :isLoading="isLoading"></loading-spinner>
	<div class="row">
		<div class="col-lg-12 mb-3">


			<div class="card">
				<div class="card-body">
					<form id="followup_form" ref="followupFormRef" name="followup_form"
							@submit.prevent="submitFollowupForm">
							<input type="hidden" name="uid" v-model="this.uid" :value="`${patientId}`" />
							<input type="hidden" name="patient_id" v-model="this.patient_id" :value="`${patientId}`" />
							<input type="hidden" name="start_time" v-model="this.start_time" value="00:00:00">
							<input type="hidden" name="end_time" v-model="this.end_time" value="00:00:00">
							<input type="hidden" name="module_id" v-model="this.module_id" :value="`${moduleId}`" />
							<input type="hidden" name="component_id" v-model="this.component_id"
								:value="`${componentId}`" />
							<input type="hidden" name="stage_id" v-model="followupStageId" :value="followupStageId" />
							<input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time"
								:value="time">
							<input type="hidden" name="step_id" v-model="this.step_id" value="0">
							<input type="hidden" name="form_name" v-model="this.form_name" value="followup_form">
					<div id='error-msg'></div>
					<div class="card-title">Follow-up</div>
					<div>
					
							<div v-for="(item, index) in items" :key="index">
								<div class="col-md-12">
									<div class='row ml-1'>
										<div class="col-md-4 form-group">
											<input type="text" name="task_name[]" class="forms-element form-control"
												placeholder="Task" v-model="item.task_name" />
										</div>
										<div class="col-md-4 form-group selects">
											<select name="followupmaster_task[]" id="followupmaster_task"
												class="custom-select show-tick select2"
												v-model="item.selectedFollowupMasterTask">
												<option value="">Select Task Category</option>
												<option v-for="followupMasterTask in followupMasterTaskList"
													:key="followupMasterTask.id" :value="followupMasterTask.id">
													{{ followupMasterTask.task }}
												</option>
											</select>
											<div class="invalid-feedback" v-if="formErrors.followupmaster_task"
												style="display: block;">{{ formErrors.followupmaster_task[0] }}</div>
										</div>
										<!-- <input type="hidden" name="selected_task_name[]" id="selected_task_name_0" /> -->
										<div class="col-md-4 form-group">
											<label class="radio radio-primary col-md-4 float-left">
												<input type="radio" class="status_flag" :name="'status_flag[' + index + ']'"
													value="0" formControlName="radio" checked v-model="item.status_flag"
													@change="handleScheduledSelected(item, index)">
												<span>To be Scheduled</span>
												<span class="checkmark"></span>
											</label>
											<label class="radio radio-primary col-md-4 float-left">
												<input type="radio" class="status_flag" :name="'status_flag[' + index + ']'"
													value="1" formControlName="radio" v-model="item.status_flag"
													@change="setCurrentDateIfCompleted(item, index)">
												<span>Completed</span>
												<span class="checkmark"></span>
											</label>
											<div class="invalid-feedback" v-if="formErrors.status_flag"
												style="display: block;">{{ formErrors.status_flag[index] }}</div>
										</div>
									</div>
									<div class='row ml-1'>
										<div class="col-md-6 form-group">
											<textarea name="notes[]" class="forms-element form-control" placeholder="Notes"
												v-model="item.notes"></textarea>
											<div class="invalid-feedback" v-if="formErrors.notes" style="display: block;">{{
												formErrors.notes[0] }}</div>
										</div>
										<div class="col-md-2 form-group"><input type="date" name="task_date[]"
												class="forms-element form-control" v-model="item.task_date" />
											<div class="invalid-feedback" v-if="formErrors.task_date"
												style="display: block;">{{ formErrors.task_date[0] }}</div>

										</div>
									</div>
								</div>
								<div v-if="index > 0">
									<div @click="removeItem(index)"><i class="remove-icons i-Remove float-right mb-3"
											title="Remove Follow-up Task"></i></div>
								</div>
								<hr />
							</div>
							<!-- button add and minus task -->
							<div class="col-md-1 form-group">
								<div @click="addNewItem">
									<i class="plus-icons i-Add" id="add_followup_task" title="Add Follow-up Task"></i>
								</div>
							</div>
							<!-- <div class="col-md-12 form-group mb-3" id="append_followup_task"><hr></div> -->
					</div>
					<div class="mb-4">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label class="forms-element checkbox checkbox-outline-primary">
									<input type="checkbox" name="emr_complete" id="emr_complete" v-model="emr_complete"
										@click="handleCheckboxChange"><span>EMR system entry completed</span><span
										class="checkmark"></span>
								</label>
								<div id="followup_emr_system_entry_complete_error" class="invalid-feedback"
									v-if="formErrors.emr_complete" style="display: block;">{{ formErrors.emr_complete[0]
									}}</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 text-right">
								<button type="submit" class="btn  btn-primary m-1 office-visit-save">Save</button>
							</div>

						</div>
					</div>
				</form>
				</div>
			
				<FollowupModal ref="FollowupModalRef" :moduleId="moduleId" :componentId="componentId" :stageId="followupStageId"/>

				<hr>
				<div class="col-md-12">
					<AgGridTable :rowData="rowData" :columnDefs="columnDefs" />

				</div>

				<div class="card-footer">
					<div class="mc-footer"></div>
				</div>
			</div>


		</div>

	</div>
</template>

<script>
import {
	reactive,
	ref,
	onBeforeMount,
	onMounted,
	AgGridTable,
	// Add other common imports if needed
} from '../../commonImports';
import FollowupModal from '../../Modals/FollowupModal.vue'
import axios from 'axios';
import { getCurrentInstance } from "vue";

export default {
	props: {
		patientId: Number,
		moduleId: Number,
		componentId: Number,
	},
	components: {
		FollowupModal,
		AgGridTable,
	},
	data() {
		return {
			items: [
				{
					task_name: '',
					selectedFollowupMasterTask: '',
					status_flag: '',
					notes: '',
					task_date: ''
				}
			],
			uid: '',
			patient_id: '',
			module_id: '',
			component_id: '',
			stepId: 0,
			start_time: '',
			end_time: '',
			form_name: '',
			billable: '',
			emr_complete: 0,
			folllowUpTaskData: {},
		};
	},
	methods: {
		handleScheduledSelected(item, index) {
			if (item.status_flag === '0') { // Check if 'To be Scheduled' radio button is selected
				item.task_date = ''; // Clear the task date
			}
		},
		setCurrentDateIfCompleted(item, index) {
			if (item.status_flag === '1') { // 'Completed' radio button is selected
				item.task_date = new Date().toISOString().substr(0, 10); // Set current date in ISO format (YYYY-MM-DD)
			} else if (item.status_flag === '0') { // 'To be Scheduled' radio button is selected
				item.task_date = ''; // Remove the date by assigning an empty string
			}
		},
		addNewItem() {
			this.items.push({
				task_name: '',
				selectedFollowupMasterTask: '',
				status_flag: '',
				notes: '',
				task_date: ''
			});
		},
		removeItem(index) {
			this.items.splice(index, 1);
		},

	},
	setup(props) {
		let time = ref(null);
		const FollowupModalRef = ref();
		const isLoading = ref(false);
		const followupMasterTaskList = ref();
		const formErrors = ref({});
		let followupStageId = ref();
		const rowData = ref();
		const loading = ref(false);

		const items = ref([
			{
				task_name: '',
				selectedFollowupMasterTask: '',
				status_flag: '',
				notes: '',
				task_date: ''
			}
		]);

		const columnDefs = ref([
			{
				headerName: 'Sr. No.',
				valueGetter: 'node.rowIndex + 1',
			},
			{ headerName: 'Task', field: 'task_notes', filter: true },
			{ headerName: 'Category', field: 'task' },
			{
				headerName: 'Notes', field: 'notes',
				cellRenderer: function (params) {
					const row = params.data;
					const link = document.createElement('a');
					const icon = document.createElement('i');
					icon.classList.add('editform', 'i-Pen-4');

					if (row && row.notes) {
						link.appendChild(document.createTextNode(row.notes));
					}

					link.appendChild(icon);
					link.classList.add('editfollowupnotes');
					link.href = 'javascript:void(0)';
					link.setAttribute('data-id', row.id); // Add data-id attribute
					link.setAttribute('data-original-title', 'Edit'); // Add data-original-title attribute
					link.addEventListener('click', () => {
						openEditModal(row.id); // 'this' refers to the Vue component instance
					});

					return link;
				},
			},
			{ headerName: 'Date Scheduled', field: 'tt' },
			{ headerName: 'Task Time', field: 'task_time' },
			{
				headerName: 'Mark as Complete', field: 'action',
				cellRenderer: function (params) {
					const row = params.data;
					if (row && row.action) {
						return row.action; // Returning the HTML content as provided from the controller
					} else {
						return ''; // Or handle the case where the 'action' value is not available
					}
				},
			},
			{ headerName: 'Task Completed Date', field: 'task_completed_at' },
			{
				headerName: 'Created By', field: 'created_by',
				cellRenderer: function (params) {
					const row = params.data;
					return row && row.f_name ? row.f_name + ' ' + row.l_name : 'N/A';
				},
			},
		]);

		const fetchFollowupMasterTask = async () => {
			await axios.get(`/org/get_future_followup_task`)
				.then(response => {
					followupMasterTaskList.value = response.data;
				})
				.catch(error => {
					console.error('Error fetching data:', error);
				});
		};

		const openEditModal = (id) => {
			console.log("u clicked me");
			FollowupModalRef.value.openModal(id, props.patientId);
		};

		const fetchFollowupMasterTaskList = async () => {
			try {
				loading.value = true;
				await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
				const response = await fetch(`/ccm/patient-followup-task/${props.patientId}/${props.moduleId}/followuplist`);
				if (!response.ok) {
					throw new Error('Failed to fetch followup task list');
				}
				loading.value = false;
				const data = await response.json();
				rowData.value = data.data; // Replace data with the actual fetched data
			} catch (error) {
				console.error('Error fetching followup task list:', error);
				loading.value = false;
			}
		};

		const getStageID = async () => {
			try {
				const stageName = 'Follow_Up';
				let response = await fetch(`/get_stage_id/${props.moduleId}/${props.componentId}/Follow_Up`);
				if (!response.ok) {
					throw new Error(`HTTP error! Status: ${response.status}`);
				}
				let responseData = await response.json();
				if (!responseData || typeof responseData !== 'object') {
					throw new Error('Invalid response data format');
				}
				followupStageId.value = responseData.stageID;
				console.log(followupStageId);
			} catch (error) {
				console.error('Error fetching stageID:', error);
				throw new Error('Failed to fetch stageID');
			}
		};

		const followupFormRef = ref(null);
		const submitFollowupForm = async () => {
			isLoading.value = true;
			let myForm = document.getElementById('followup_form');
			let formData = new FormData(myForm);
			axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
			try {
				const response = await axios.post('/ccm/monthly-monitoring-followup-inertia', formData);
				console.log('Form submitted successfully!', response);
				if (response && response.status == 200) {
					alert("Form submitted successfully");
				}
			} catch (error) {
				isLoading.value = false;
				if (error.response && error.response.status === 422) {
					formErrors.value = error.response.data.errors;
					console.log(error.response.data.errors);
				} else {
					// Handle other types of errors
					console.error('Error submitting form:', error);
				}
			}
			isLoading.value = false;
		};

		const handleCheckboxChange = (event) => {
			emr_complete.value = event.target.checked;
		};



		onMounted(async () => {
			try {
				fetchFollowupMasterTask();
				fetchFollowupMasterTaskList();
				getStageID();
				time.value = document.getElementById('page_landing_times').value;
			} catch (error) {
				console.error('Error on page load:', error);
			}
		});

		return {
			FollowupModalRef,
			time,
			isLoading,
			followupStageId,
			loading,
			columnDefs,
			rowData,
			formErrors,
			fetchFollowupMasterTask,
			fetchFollowupMasterTaskList,
			getStageID,
			submitFollowupForm,
			followupFormRef,
			followupMasterTaskList,
			getStageID,
			handleCheckboxChange,
		};
	}
}

$('body').on('click', '.change_status_flag', function () {
	var id = $(this).data('id');
	var component_id = $("form[name='followup_form'] input[name='component_id']").val();
	var module_id = $("form[name='followup_form'] input[name='module_id']").val();
	var stage_id = $("form[name='followup_form'] input[name='stage_id']").val();
	var step_id = $("form[name='followup_form'] input[name='step_id']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var startTime = $("form[name='followup_form'] .form_start_time").val();
	var form_name = $("#form_name").val();
	if (confirm("Are you sure you want to change the Status")) {
		$.ajax({
			type: 'post',
			url: '/ccm/completeIncompleteTask',
			data: 'id=' + id + '&timer_start=' + timer_start + '&timer_paused=' + timer_paused + '&module_id=' + module_id + '&component_id=' + component_id + '&stage_id=' + stage_id + '&step_id=' + step_id + '&form_name=' + form_name + '&startTime=' + startTime,
			success: function success(response) {
				$(".form_start_time").val(response.form_start_time);
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);
				updateTimer(props.patientId, '1', props.moduleId);
				time.value = document.getElementById('page_landing_times').value;

			}
		});
	} else {
		return false;
	}
}); 
</script>

<style>
.goal-container {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 2px;
}

/* Modal styles */
.modal {
	display: none;
	position: fixed;
	background-color: white;
	z-index: 1000;
	margin: 2%;
	opacity: 0;
	transition: opacity 0.3s ease;
}

/* Style the overlay */
.overlay {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.5);
	z-index: 999;
	display: none;
}

/* Show the overlay and modal when modal is open */
.modal.open {
	display: block;
	opacity: 1;
}

.overlay.open {
	display: block;
}

.modal-content {
	overflow-y: auto !important;
	height: 500px !important;
}</style>
