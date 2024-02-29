<template>
	<loading-spinner :isLoading="isLoading"></loading-spinner>
	<div class="row">
		<div class="col-lg-12 mb-3">


			<div class="card">
				<div class="card-body">
					<div id="followUpPageAlert"></div>

					<form id="followup_form" ref="followupFormRef" name="followup_form"
						@submit.prevent="submitFollowupForm">
						<input type="hidden" name="uid" :value="`${patientId}`" />
						<input type="hidden" name="patient_id" :value="`${patientId}`" />
						<input type="hidden" name="start_time" value="00:00:00">
						<input type="hidden" name="end_time" value="00:00:00">
						<input type="hidden" name="module_id" :value="`${moduleId}`" />
						<input type="hidden" name="component_id" :value="`${componentId}`" />
						<input type="hidden" name="stage_id" v-model="followupStageId" :value="followupStageId" />
						<input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="time">
						<input type="hidden" name="step_id" v-model="step_id" value="0">
						<input type="hidden" name="form_name" id="form_name" value="followup_form">
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
											true-value="1" false-value="0" @click="handleCheckboxChange"><span>EMR system
											entry completed</span><span class="checkmark"></span>
									</label>
									<!-- v-model="emr_complete" -->
									<!-- <div id="followup_emr_system_entry_complete_error" class="invalid-feedback"
										v-if="formErrors.emr_complete" style="display: block;">{{ formErrors.emr_complete[0]
										}}
									</div> -->
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 text-right">
									<button type="submit" class="btn  btn-primary m-1 office-visit-save"
										:disabled="(timerStatus == 1) === true">Save</button>
								</div>

							</div>
						</div>
					</form>
				</div>

				<FollowupModal ref="FollowupModalRef" :moduleId="moduleId" :componentId="componentId"
					:stageId="followupStageId" :patientId="patientId" :followupCallFunction="FollowupMainFunction" />
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

	},
	setup(props) {
		let time = ref(null);
		let timerStatus = ref();
		const FollowupModalRef = ref();
		const isLoading = ref(false);
		const followupMasterTaskList = ref();
		const formErrors = ref({});
		let followupStageId = ref();
		const rowData = ref();
		const loading = ref(false);

		const emr_complete = ref(0);

		const items = ref([
			{
				task_name: '',
				selectedFollowupMasterTask: '',
				status_flag: '',
				notes: '',
				task_date: ''
			}
		]);

		const changeStatusRenderer = (params) => {
			const row = params.data;
			if (row && row.action) {
				// Create a checkbox input element
				const checkbox = document.createElement('input');
				checkbox.setAttribute('type', 'checkbox');
				checkbox.classList.add('change_status_flag');
				checkbox.dataset.id = row.id;
				checkbox.dataset.moduleId = row.module_id;
				checkbox.dataset.componentId = row.component_id;
				checkbox.dataset.stageId = row.stage_id;
				checkbox.dataset.stepId = row.step_id;
				checkbox.value = row.status_flag === 1 ? 1 : 0;
				checkbox.checked = row.status_flag === 1;
				if (timerStatus.value == 1) {
					//document.getElementsByClassName("change_status_flag").disabled= true;
					checkbox.setAttribute('disabled', true);
				}
				// Bind click event handler
				checkbox.addEventListener('click', () => {
					changeStatus(row.id); // 'this' refers to the Vue component instance
				});
				return checkbox;
			} else {
				return ''; // Or handle the case where the 'action' value is not available
			}
		};

		// Define columnDefs after changeStatusRenderer is defined
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
				cellRenderer: changeStatusRenderer
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



		const handleScheduledSelected = (item, index) => {
			if (item.status_flag === '0') { // Check if 'To be Scheduled' radio button is selected
				item.task_date = ''; // Clear the task date
			}
		};

		const setCurrentDateIfCompleted = (item, index) => {
			if (item.status_flag === '1') { // 'Completed' radio button is selected
				item.task_date = new Date().toISOString().substr(0, 10); // Set current date in ISO format (YYYY-MM-DD)
			} else if (item.status_flag === '0') { // 'To be Scheduled' radio button is selected
				item.task_date = ''; // Remove the date by assigning an empty string
			}
		};

		const addNewItem = () => {
			const newItem = {
				task_name: '',
				selectedFollowupMasterTask: '',
				status_flag: '',
				notes: '',
				task_date: ''
			};
			items.value.push(newItem);
		};

		const removeItem = (index) => {
			items.value.splice(index, 1);
		};

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
			FollowupModalRef.value.openModal(id, props.patientId);
		};

		const FollowupMainFunction = () => {
			fetchFollowupMasterTaskList();
		};

		const fetchFollowupMasterTaskList = async () => {
			try {
				loading.value = true;
				//await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
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
			} catch (error) {
				console.error('Error fetching stageID:', error);
				throw new Error('Failed to fetch stageID');
			}
		};

		const followupFormRef = ref(null);
		const submitFollowupForm = async () => {
			isLoading.value = true;
			/* 	let emrCheckbox = document.getElementById('emr_complete');
				// Set its value based on its checked state
				let emrValue = emrCheckbox.checked ? 1 : 0;
				console.log(emrCheckbox,emrValue,"emrvlays");
					// Append the checkbox value to the FormData object
					myForm.append('emr_complete', emrValue); */
			let myForm = document.getElementById('followup_form');
			let formData = new FormData(myForm);
			axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
			try {
				const response = await axios.post('/ccm/monthly-monitoring-followup-inertia', formData);
				if (response && response.status == 200) {
					myForm.reset();
					items.value.splice(1);
					items.value[0] = {
						task_name: '',
						selectedFollowupMasterTask: '',
						status_flag: '',
						notes: '',
						task_date: ''
					};
					emr_complete.value = 0;
					fetchFollowupMasterTaskList();
					var year = (new Date).getFullYear();
               		var month = (new Date).getMonth() + 1
					$('#followUpPageAlert').html('<div class="alert alert-success"> Data Saved Successfully </div>');
					updateTimer(props.patientId, '1', props.moduleId);
					const taskMangeResp = await axios.get(`/task-management/patient-to-do/${props.patientId}/${props.moduleId}/list`);
                     $("#toDoList").html(taskMangeResp.data);
                     $('.badge').html($('#count_todo').val());
                     const previousMonths = await axios.get(`/ccm/previous-month-status/${props.patientId}/${props.moduleId}/${month}/${year}/previousstatus`);
                     $("#previousMonthData").html(previousMonths.data);
					$(".form_start_time").val(response.data.form_start_time);
					time.value = response.data.form_start_time;
					setTimeout(function () {
						$('#followUpPageAlert').html('');
					}, 3000);
				}
			} catch (error) {
				isLoading.value = false;
				if (error.response && error.response.status === 422) {
					formErrors.value = error.response.data.errors;
					setTimeout(function () {
						formErrors.value = {};
					}, 3000);
					console.log(error.response.data.errors);
				} else {
					// Handle other types of errors
					console.error('Error submitting form:', error);
					setTimeout(function () {
						$('#followUpPageAlert').html('');
					}, 3000);
				}
			}
			isLoading.value = false;
		};

		const changeStatus = (rid) => {
			const id = rid; //document.querySelector('.change_status_flag').getAttribute('data-id');
			const component_id = document.querySelector("form[name='followup_form'] input[name='component_id']").value;
			const module_id = document.querySelector("form[name='followup_form'] input[name='module_id']").value;
			const stage_id = document.querySelector("form[name='followup_form'] input[name='stage_id']").value;
			const step_id = document.querySelector("form[name='followup_form'] input[name='step_id']").value;
			const timer_start = document.querySelector("form[name='followup_form'] input[name='start_time']").value;
			const timer_paused = document.getElementById('time-container').textContent;
			const startTime = document.querySelector("form[name='followup_form'] .form_start_time").value;
			const form_name = document.getElementById('form_name').value;

			if (confirm('Are you sure you want to change the Status')) {
				fetch('/ccm/completeIncompleteTask', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					},
					body: new URLSearchParams({
						id,
						timer_start,
						timer_paused,
						module_id,
						component_id,
						stage_id,
						step_id,
						form_name,
						startTime
					})
				})
					.then(response => {
						if (!response.ok) {
							throw new Error(`HTTP error! Status: ${response.status}`);
						}
						return response.json();
					})
					.then(responseData => {
						document.getElementById('followUpPageAlert').innerHTML = '<div class="alert alert-success"> Data Saved Successfully </div>';
						setTimeout(() => {
							document.getElementById('followUpPageAlert').innerHTML = '';
						}, 3000);
						document.querySelector("form[name='followup_form'] .form_start_time").value = responseData.form_start_time;
						updateTimer(props.patientId, '1', props.moduleId);
						time.value = responseData.form_start_time;
						updateToDo();
					})
					.catch(error => {
						console.error('Error:', error);
					});
			} else {
				return false;
			}
		};

		const updateToDo = async () => {
			const taskMangeResp = await axios.get(`/task-management/patient-to-do/${props.patientId}/${props.moduleId}/list`);
			$("#toDoList").html(taskMangeResp.data);
			$('.badge').html($('#count_todo').val());
		};

		const handleCheckboxChange = (event) => {
			emr_complete.value = event.target.checked ? 1 : 0;
		};

		onMounted(async () => {
			try {
				fetchFollowupMasterTask();
				fetchFollowupMasterTaskList();
				getStageID();
				time.value = document.getElementById('page_landing_times').value;
				timerStatus.value = document.getElementById('timer_runing_status').value;
			} catch (error) {
				console.error('Error on page load:', error);
			}
		});

		return {
			FollowupMainFunction,
			changeStatus,
			items,
			handleScheduledSelected,
			setCurrentDateIfCompleted,
			addNewItem,
			removeItem,
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
			timerStatus,
			updateToDo,
		};
	}
}


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
	/* height: 500px !important; */
}
</style>
