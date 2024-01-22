<template>
	  <loading-spinner :isLoading="isLoading"></loading-spinner>
	<div class="row">
		<div class="col-lg-12 mb-3">

			<form id="followup_form" ref="followupFormRef" name="followup_form" @submit.prevent="submitFollowupForm">
				<input type="hidden" name="uid" v-model="this.uid" :value="`${patientId}`" />
				<input type="hidden" name="patient_id" v-model="this.patient_id" :value="`${patientId}`" />
				<input type="hidden" name="start_time" v-model="this.start_time" value="00:00:00">
				<input type="hidden" name="end_time" v-model="this.end_time" value="00:00:00">
				<input type="hidden" name="module_id" v-model="this.module_id" :value="`${moduleId}`" />
				<input type="hidden" name="component_id" v-model="this.component_id" :value="`${componentId}`" />
				<input type="hidden" name="stage_id" v-model="followupStageId" :value="followupStageId" />
				<input type="hidden" name="step_id" v-model="this.step_id" value="0">
				<input type="hidden" name="form_name" v-model="this.form_name" value="followup_form">
				<div class="card">
					<div class="card-body">
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
											<select name="followupmaster_task[]" class="custom-select show-tick select2"
												v-model="item.selectedFollowupMasterTask">
												<option value="">Select Task Category</option>
												<option v-for="followupMasterTask in followupMasterTaskList"
													:key="followupMasterTask.id" :value="followupMasterTask.id">
													{{ followupMasterTask.task }}
												</option>
											</select>
											<!-- @selectfuturefollowuptask("followupmaster_task[]",["id"=>"followupmaster_task"]) -->
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
										</div>
									</div>
									<div class='row ml-1'>
										<div class="col-md-6 form-group">
											<textarea name="notes[]" class="forms-element form-control" placeholder="Notes"
												v-model="item.notes"></textarea>
											<div class="invalid-feedback"></div>
										</div>
										<div class="col-md-2 form-group"><input type="date" name="task_date[]"
												class="forms-element form-control" v-model="item.task_date" /></div>
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
									<div id="followup_emr_system_entry_complete_error" class="invalid-feedback"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 text-right">
									<button type="submit" id="save-followup"
										class="btn  btn-primary m-1 office-visit-save">Save</button>
								</div>

							</div>
						</div>
					</div>
					<hr>
					<div class="col-md-12">
						<div class="table-responsive">
							<ag-grid-vue style="width: 100%; height: 100%;" class="ag-theme-quartz-dark"
								:gridOptions="gridOptions" :defaultColDef="defaultColDef" :columnDefs="columnDefs"
								:rowData="rowData" @grid-ready="onGridReady"
								:paginationPageSizeSelector="paginationPageSizeSelector"
								:paginationNumberFormatter="paginationNumberFormatter"
								:popupParent="popupParent"></ag-grid-vue>
						</div>
					</div>

					<div class="card-footer">
						<div class="mc-footer"></div>
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
					<form action="" method="post" name="followup_task_edit_notes" id="followup_task_edit_notes">
						<div class="modal-body">
							<input type="hidden" name="uid" />
							<input type="hidden" name="patient_id" id="patient_id" v-model="this.patient_id" :value="`${patientId}`"/>
							<input type="hidden" name="start_time" id="timer_start" value="00:00:00">
							<input type="hidden" name="end_time" value="00:00:00">
							<input type="hidden" name="module_id" v-model="this.module_id" :value="`${moduleId}`" />
				<input type="hidden" name="component_id" v-model="this.component_id" :value="`${componentId}`" />
				<input type="hidden" name="stage_id" v-model="followupStageId" :value="followupStageId" />
							<input type="hidden" name="step_id" value="0">
							<input type="hidden" name="form_name" id="form_name" value="followup_task_edit_notes">
							<input type="hidden" name="topic" id="topic" />
							<input type="hidden" name="id" id="hiden_idhiden_id" />
							<p><b>Task : </b><span id="task_notes"></span></p>
							<p><b>Category : </b><span id="category"></span> </p>
							<p><input type="date" name="task_date" id="task_date_val" /></p>
							<textarea id="notes" name="notes" class="forms-element form-control"></textarea>
							<div class="form-group col-md-12 mt-2">
								<label class="forms-element checkbox checkbox-outline-primary">
									<input type="checkbox" id="status_flag" name="status_flag"><span>Mark as
										completed</span><span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn  btn-primary m-1">Save</button>
						</div>
					</form>
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
	AgGridVue,
	// Add other common imports if needed
} from '../../commonImports';
import LayoutComponent from '../../LayoutComponent.vue'; // Import your layout component
import axios from 'axios';
import { getCurrentInstance } from "vue";

export default {
	props: {
		patientId: Number,
		moduleId: Number,
		componentId: Number,
	},
	components: {
		LayoutComponent,
		AgGridVue,
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
			formErrors: {},
			showAlert: false,
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
		const isLoading = ref(false);
		const followupMasterTaskList = ref();
		const followupStageId = ref();
		const rowData = ref();
		const loading = ref(false);
		const gridApi = ref(null);
		const gridColumnApi = ref(null);
		const popupParent = ref(null);
		const paginationPageSizeSelector = ref(null);
		const paginationNumberFormatter = ref(null);

		const items = ref([
			{
				task_name: '',
				selectedFollowupMasterTask: '',
				status_flag: '',
				notes: '',
				task_date: ''
			}
		]);

		const onGridReady = (params) => {
			gridApi.value = params.api; // Set the grid API when the grid is ready
			gridColumnApi.value = params.columnApi;
			paginationPageSizeSelector.value = [10, 20, 30, 40, 50, 100];
			paginationNumberFormatter.value = (params) => {
				return '[' + params.value.toLocaleString() + ']';
			};
		};
	
		let columnDefs = ref([
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
		const defaultColDef = ref({
			sortable: true,
			filter: true,
			flex: 1,
			minWidth: 100,
			editable: false,
		});
		const gridOptions = reactive({
			pagination: true,
			paginationPageSize: 10, // Set the number of rows per page
			domLayout: 'autoHeight',
		});

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
			// Code to open the modal using jQuery or Vue methods
			// You might need to adjust this based on your modal library or implementation
			//$('#edit_notes_modal').modal('show');

			// Code to set data in the modal based on the row with the given ID
			//const rowData = this.gridOptions.api.getRowNode(id).data;
			// Set data in the modal fields based on rowData
			// Example: document.getElementById('task_notes').innerText = rowData.notes;
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

		let getStageID = async () => {
			try {
				let stageName = 'Follow_Up';
				const response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${stageName}`);
				followupStageId = response.data.stageID;
			} catch (error) {
				console.error('Error fetching stageID:', error);
				throw new Error('Failed to fetch stageID');
			}
		};

		const followupFormRef = ref(null);
		const submitFollowupForm = async () => {
			isLoading.value = true;
			// Access the form element using $refs
			const myForm = followupFormRef.value; // Access form reference directly
			if (!myForm || !(myForm instanceof HTMLFormElement)) {
				console.error('Invalid form reference');
				return;
			}
			// Create a FormData object from the form element
			const formData = new FormData(myForm);

			/*  const formData = {
				uid: props.patientId,
				patient_id: props.patientId,
				module_id: props.moduleId,
				component_id: props.componentId,
				stage_id: props.stageid,
				step_id: this.step_id,
				form_name: 'hippa_form',
				billable: 1,
				start_time: "",
				end_time: "",
				_token: document.querySelector('meta[name="csrf-token"]').content,
				timearr: {
				  "form_start_time": document.getElementById('page_landing_times').value, //"12-27-2023 11:59:57",
				  "form_save_time": "",
				  "pause_start_time": "",
				  "pause_end_time": "",
				  "extra_time": ""
				},
				folllowUpTaskData: this.items.map(item => ({
				  task_name: item.task_name,
				  selectedFollowupMasterTask: item.selectedFollowupMasterTask,
				  status_flag: item.status_flag,
				  notes: item.notes,
				  task_date: item.task_date,
				})),
				emr_complete: this.emr_complete,
			  }; */
			axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
			try {
				const response = await axios.post('/ccm/monthly-monitoring-followup-inertia', formData);
				console.log('Form submitted successfully!', response);
				if (response && response.status == 200) {
					this.showAlert = true;
					setTimeout(() => {
						this.showAlert = false;
					}, 3000);
				}
			} catch (error) {
				isLoading.value = false;
				if (error.response && error.response.status === 422) {
					// Handle validation errors (422 Unprocessable Entity)
					// Set formErrors based on the response
					this.formErrors = error.response.data.errors;
				} else {
					// Handle other types of errors
					console.error('Error submitting form:', error);
				}
			}
			isLoading.value = false;
		
			// axios.post('/your-api-endpoint', this.formData)
			//  .then(response => {
			//    console.log('Form submitted successfully!', response.data);
			//  })
			//  .catch(error => {
			//    // Handle error response
			//    console.error('Error submitting form:', error);
			//  });
		};


		const handleCheckboxChange = (event) => {
			emr_complete.value = event.target.checked;
		};

		onBeforeMount(() => {
			popupParent.value = document.body;

		});
		const onFirstDataRendered = (params) => {
			params.api.paginationGoToPage(1);
		};
		onMounted(async () => {
			try {
				fetchFollowupMasterTask();
				fetchFollowupMasterTaskList();
				getStageID();
			} catch (error) {
				console.error('Error on page load:', error);
			}
		});

		return {
			isLoading,
			followupStageId,
			loading,
			columnDefs,
			rowData,
			defaultColDef,
			gridOptions,
			popupParent,
			gridApi,
			gridColumnApi,
			onGridReady,
			paginationPageSizeSelector,
			paginationNumberFormatter,
			onFirstDataRendered,
			fetchFollowupMasterTask,
			fetchFollowupMasterTaskList,
			getStageID,
			submitFollowupForm,
			followupFormRef,
			followupMasterTaskList,
			getStageID,
			handleCheckboxChange,
			// addNewItem,
			// removeItem,
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
          util.getToDoListData($("#patient_id").val(), $("form[name='followup_form'] input[name='module_id']").val()); //util.getDataCalender($("#patient_id").val(), $("form[name='followup_form'] input[name='module_id']").val());

          $(".form_start_time").val(response.form_start_time);
          var table = $('#callwrap-list');
          table.DataTable().ajax.reload();
          var table1 = $('#task-list');
          table1.DataTable().ajax.reload();
          $("#time-container").val(AppStopwatch.pauseClock);
          $("#timer_start").val(timer_paused);
          $("#timer_end").val(timer_paused);
          $("#time-container").val(AppStopwatch.startClock);
          util.totalTimeSpentByCM();
          util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
        }
      });
    } else {
      return false;
    }
  }); // $('.patient_data_allergies_tab').click(function (e) { 
  //     // alert('patient_data_allergies_tab'); 
  //     var target = $(e.target).attr("href") // activated tab  
  //     var form = $(target).find("form").attr('name');
  //     var allergy_type = $("form[name=" + form + "] input[name='allergy_type']").val();
  //     var id = $("#patient_id").val();
  //     util.refreshAllergyCountCheckbox(id, allergy_type, form);
  // });

  $('body').on('click', '.editfollowupnotes', function () {
    $('#task_date').html('');
    $('#topic').val('');
    $('#task_date_val').val('');
    $('#task_notes').html('');
    $('#notes').html('');
    $('#category').html('');
    $("#followup_task_edit_notes")[0].reset();
    var patientId = $("#patient_id").val();
    var id = $(this).data('id');
    $("#hiden_id").val(id);
    $("#edit_notes_modal").modal('show');
   var url = '/ccm/getFollowupListData-edit/' + id + '/' + patientId + '/followupnotespopulate';
    populateForm(id, url);
  });

</script>
<style>
@import 'ag-grid-community/styles/ag-grid.css';
@import 'ag-grid-community/styles/ag-theme-quartz.css';
/* Use the theme you prefer */

.ag-theme-quartz,
.ag-theme-quartz-dark {
	--ag-foreground-color: rgb(63, 130, 154);
	--ag-background-color: rgb(238, 238, 238);
	--ag-header-foreground-color: rgb(63, 130, 154);
	--ag-header-background-color: rgb(238, 238, 238);
	--ag-odd-row-background-color: rgb(255, 255, 255);
	--ag-header-column-resize-handle-color: rgb(63, 130, 154);

	--ag-font-size: 17px;
	--ag-font-family: monospace;
}

.loading-spinner {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100px;
	/* Adjust as needed */
}

.quick-filter {
	display: flex;
	align-items: center;
}

.export-button {
	cursor: pointer;
}

.search-container {
	display: inline-block;
	position: relative;
	border-radius: 50px;
	/* To create an oval shape, use a large value for border-radius */
	overflow: hidden;
	width: 200px;
	/* Adjust width as needed */
}

.oval-search-container {
	position: relative;
	display: inline-block;
	/*  border: 1px solid #ccc; */
	/* Adding a visible border */
	/* border-radius: 20px; */
	/* Adjust border-radius for a rounded shape */
	/* width: 200px; */
	/* Adjust width as needed */
	margin-right: 10px;
	/* Adjust margin between the search box and icons */
}

input[type="text"] {
	width: calc(100% - 0px);
	/* Adjust the input width considering the icon */
	/*  border: none; */
	outline: none;
	border-radius: 10px;
}

.search-icon {
	position: absolute;
	top: 50%;
	right: 1px;
	transform: translateY(-50%);
	width: 20px;
	/* Adjust icon size as needed */
	height: auto;
}

/* Align the export icons properly */
.ml-auto img {
	margin-right: 5px;
	/* Adjust margin between the export icons */
}
</style>
