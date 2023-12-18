<template>
	<div class="row">
		<div class="col-lg-12 mb-3">
			<form id="followup_form" name="followup_form" action="" method="post">
				<input type="hidden" name="uid" />
				<input type="hidden" name="patient_id" id="patient_id" />
				<input type="hidden" name="start_time" value="00:00:00">
				<input type="hidden" name="end_time" value="00:00:00">
				<input type="hidden" name="module_id" />
				<input type="hidden" name="component_id" />
				<input type="hidden" name="stage_id" />
				<input type="hidden" name="step_id" value="0">
				<input type="hidden" name="form_name" value="followup_form">
				<div class="card">
					<div class="card-body">
					<div id='error-msg'></div> 
					<div class="card-title">Follow-up</div>
						<div class="row"> 
	                        <div class="col-md-12">
								<div class='row ml-1'> 
									<div class="col-md-4 form-group">
										<input type="text" name="task_name[]" id="task_name" placeholder="Task" />
									</div>
									<div class="col-md-4 form-group selects" id="followupTaskDrpdwn_0">
										<select name="followupmaster_task[]" id="followupmaster_task" class="custom-select show-tick select2"
											v-model="selectedFollowupMasterTask">
											<option value="">Select Task Category</option>
											<option v-for="followupMasterTask in followupMasterTaskList" :key="followupMasterTask.id" :value="followupMasterTask.id">
												{{ followupMasterTask.task }}
											</option>
										</select>
										<!-- @selectfuturefollowuptask("followupmaster_task[]",["id"=>"followupmaster_task"]) -->
									</div>
									<input type="hidden" name="selected_task_name[]" id="selected_task_name_0" />
									<div class="col-md-4 form-group">
			                                <label class="radio radio-primary col-md-4 float-left">
			                                    <input type="radio" id="scheduled_0" class="status_flag" name="status_flag[0]" value="0" formControlName="radio" checked>
			                                    <span>To be Scheduled</span>
			                                    <span class="checkmark"></span>
			                                </label>
			                                <label class="radio radio-primary col-md-4 float-left">
			                                    <input type="radio" id="completed_0" class="status_flag" name="status_flag[0]" value="1" formControlName="radio">
			                                    <span>Completed</span>
			                                    <span class="checkmark"></span>
			                                </label>
									</div>
								</div>
								<div class='row ml-1'>
									<div class="col-md-6 form-group">
										<textarea name="notes[]" class="forms-element form-control" id="notes_0" placeholder="Notes"></textarea>
										<div class="invalid-feedback"></div>
									</div>
									<div class="col-md-2 form-group"><input type="date" name="task_date[]" id="task_date_0" /></div>
								</div>
	                        </div>
	                        <!-- button add and minus task -->
							<div class="col-md-1 form-group">
	                        	<i class="plus-icons i-Add"  id="add_followup_task" title="Add Follow-up Task"></i>
	                    	</div>
	                        <div class="col-md-12 form-group mb-3" id="append_followup_task"><hr></div>
	                    </div>
						<div class="mb-4">
							<div class="form-row">
								<div class="form-group col-md-12">
									<label class="forms-element checkbox checkbox-outline-primary">
										<input type="checkbox" name="emr_complete" id="emr_complete" value="1"><span>EMR system entry completed</span><span class="checkmark"></span>
									</label>
									<div id="followup_emr_system_entry_complete_error" class="invalid-feedback"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 text-right">
									<button type="submit" id="save-followup" class="btn  btn-primary m-1 office-visit-save" >Save</button>
								</div> 
							</div>	
						</div>
					</div>
					<hr>	
					<div class="col-md-12">
						<div v-if="loading" class="table-responsive loading-spinner">
							<p>Loading...</p>
						</div>
						<div v-else class="table-responsive">
							<table id="task-list" ref="dataTable" class="display table table-striped table-bordered"
							style="width:100%">
							</table>
						</div>
						<div class="table-responsive">
						<ag-grid-vue
							style="width: 100%; height: 100%;"
							class="ag-theme-alpine"
							:columnDefs="columnDefs.value"
							:rowData="rowData.value"
							:defaultColDef="defaultColDef"
							:gridOptions="gridOptions"
							:loadingCellRenderer="loadingCellRenderer"
										:loadingCellRendererParams="loadingCellRendererParams"
										:rowModelType="rowModelType"
										:cacheBlockSize="cacheBlockSize"
										:maxBlocksInCache="maxBlocksInCache"></ag-grid-vue>
							<!-- <table class="display table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
										<th>Sr No.</th>                        
										<th>Task</th>    
										<th>Category</th>
										<th>Notes</th>
										<th>Date Scheduled</th>
										<th>Task Time</th>
										<th>Mark as Complete</th>
										<th>Task Completed Date</th> 
										<th>Created By</th>
									</tr>
								</thead>
							</table>  -->
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
					<form  action="" method="post" name="followup_task_edit_notes" id="followup_task_edit_notes">
						<div class="modal-body">
							<input type="hidden" name="uid" />
							<input type="hidden" name="patient_id" id="patient_id" />
							<input type="hidden" name="start_time" value="00:00:00">
							<input type="hidden" name="end_time" value="00:00:00">
							<input type="hidden" name="module_id" />
							<input type="hidden" name="component_id" />
							<input type="hidden" name="stage_id" />
							<input type="hidden" name="step_id" value="0">
							<input type="hidden" name="form_name" value="followup_task_edit_notes">
							<input type="hidden" name="topic" id="topic" />
							<input type="hidden" name="id" id="hiden_idhiden_id" />
							<p><b>Task : </b><span id ="task_notes"></span></p>
							<p><b>Category : </b><span id ="category"></span> </p>
							<p><input type="date" name="task_date" id="task_date_val" /></p>
							<textarea id="notes" name ="notes" class="forms-element form-control"></textarea>
							<div class="form-group col-md-12 mt-2">
								<label class="forms-element checkbox checkbox-outline-primary">
									<input type="checkbox" id="status_flag" name="status_flag"><span>Mark as completed</span><span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn  btn-primary m-1" >Save</button>
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
		AgGridVue,
// Add other common imports if needed
} from '../../commonImports';
import LayoutComponent from '../../LayoutComponent.vue'; // Import your layout component
import axios from 'axios';
const loading = ref(false);
const rowData = reactive({ value: [] });
let columnDefs = reactive({
	value: [
		{
			headerName: 'Sr. No.',
			valueGetter: 'node.rowIndex + 1',
		},
		{ headerName: 'Task', field: 'task_notes', filter: true },
		{ headerName: 'Category', field: 'task' },
		{
			headerName: 'Notes', field: 'notes'
			// ,
			// cellRenderer: function (params) {
			// 	const row = params.data;
			// 	if (row.pstatus === 1 && row.pstatus !== undefined) {
			// 		return full['notes'] + '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + full['id'] + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a> ';
			// 	} else {
			// 		return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + full['id'] + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a>';
			// 	}
			// },
		},
		{ headerName: 'Date Scheduled', field: 'tt' },
		{ headerName: 'Task Time', field: 'task_time' },
		{ headerName: 'Mark as Complete', field: 'action' },
		{ headerName: 'Created By', field: 'action' },
	]
});
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
			selectedFollowupMasterTask: null,
			followupMasterTaskList: null,
		};
	},
	mounted() {
		this.fetchFollowupMasterTask();
		this.fetchFollowupMasterTaskList();
	},
	methods: {
		async fetchFollowupMasterTask() {
			await axios.get(`/org/get_future_followup_task`)
				.then(response => {
					this.followupMasterTaskList = response.data;
					console.log("followupMasterTaskList", response.data);
				})
				.catch(error => {
					console.error('Error fetching data:', error);
				});
		},
		async fetchFollowupMasterTaskList() {
			try {
				loading.value = true;
				await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
				const response = await fetch(`/ccm/patient-followup-task/${this.patientId}/${this.moduleId}/followuplist`);
				if (!response.ok) {
					throw new Error('Failed to fetch followup task list');
				}
				loading.value = false;
				const data = await response.json();
				rowData.value = data.data; // Replace data with the actual fetched data
				console.log(rowData.value);
			} catch (error) {
				console.error('Error fetching followup task list:', error);
				loading.value = false;
			}
		},
	},
}
// import {
// 	ref,
// 	DataTable,
// 	// Add other common imports if needed
// } from '../../commonImports';
// import axios from 'axios';

// const loading = ref(false);
// const dataTable = ref([]);

// export default {
// 	props: {
// 		patientId: Number,
// 		moduleId: Number,
// 		componentId: Number,
// 	},
// 	data() {
// 		return {
// 			selectedFollowupMasterTask: null,
// 			followupMasterTaskList: null,
// 		};
// 	},
// 	mounted() {
// 		this.fetchFollowupMasterTask();
// 		this.fetchFollowupMasterTaskList();
// 		this.initDataTable(dataTable.value);
// 	},
// 	methods: {
// 		async fetchFollowupMasterTask() {
// 			await axios.get(`/org/get_future_followup_task`)
// 				.then(response => {
// 					this.followupMasterTaskList = response.data;
// 					console.log("followupMasterTaskList", response.data);
// 				})
// 				.catch(error => {
// 					console.error('Error fetching data:', error);
// 				});
// 		},
// 		async initDataTable() {
// 			$('#task-list').on('click', '.ActiveDeactiveClass', (event) => {
// 				// Extract the row data using DataTable API
// 				const table = $('#task-list').DataTable();
// 				const rowData = table.row($(event.target).closest('tr')).data();
// 				// Call the Vue method with the extracted parameters from the row
// 				callExternalFunctionWithParams(rowData.pid, rowData.pstatus);
// 			});
// 			let tableInstance;
// 			const columns = [
// 				{ title: 'Sr. No.', data: 'DT_RowIndex', name: 'DT_RowIndex' },
// 				{ title: 'Task', data: 'task_notes', name: 'task_notes' },
// 				{ title: 'Category', data: 'task', name: 'task' },
// 				{
// 					title: 'Notes', data: 'notes', name: 'notes', render: function (data, type, full, meta) {
// 						if (data != '' && data != 'NULL' && data != undefined) {
// 							return full['notes'] + '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + full['id'] + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a> ';
// 						} else { return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + full['id'] + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a>'; }
// 					}
// 				},
// 				{
// 					title: 'Date Scheduled', data: 'tt', type: 'date-dd-mm-yyyy',
// 					"render": function (value) {
// 						if (value === null) return "";
// 						return value;
// 						// return util.viewsDateFormat(value);
// 					}
// 				},
// 				{
// 					title: 'Task Time', data: 'task_time', name: 'task_time',
// 					render: function (data, type, full, meta) {
// 						if (data != '' && data != 'NULL' && data != undefined) {
// 							return full['task_time'];
// 						} else {
// 							return '';
// 						}
// 					}
// 				},
// 				{ title: 'Mark as Complete', data: 'action', name: 'action', orderable: false, searchable: false },
// 				// {
// 				// 	data: 'Task Completed Date', type: 'date-dd-mm-yyyy h:i:s', name: 'task_completed_at', "render": function (value) {
// 				// 		if (value === null) return "";
// 				// 		return value;
// 				// 		// return util.viewsDateFormat(value);
// 				// 	}
// 				// },
// 				{
// 					title: 'Created By', data: null,
// 					render: function (data, type, full, meta) {
// 						if (data != '' && data != 'NULL' && data != undefined) {
// 							return full['f_name'] + ' ' + full['l_name'];
// 						} else {
// 							return '';
// 						}
// 					}
// 				},
// 			];
// 			const dataTableElement = document.getElementById('task-list');
// 			// var url = `/ccm/patient-followup-task/${this.patientId}/${this.moduleId}/followuplist`;
// 			if (dataTableElement) {
// 				// util.renderDataTable('task-list', url, columns, "{{ asset('') }}");
// 				tableInstance = $(dataTableElement).DataTable({
// 					columns: columns,
// 					data: dataTable.value,
// 					destroy: true,
// 					paging: true,
// 					searching: true,
// 					processing: true,
// 					dom: 'Bfrtip',
// 					buttons: [
// 						{
// 							extend: 'copyHtml5',
// 							text: '<img src="/assets/images/copy_icon.png" width="20" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copy">',
// 							titleAttr: 'Copy',
// 						},
// 						{
// 							extend: 'excelHtml5',
// 							text: '<img src="/assets/images/excel_icon.png" width="20" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Excel">',
// 							titleAttr: 'Excel',
// 						},
// 						{
// 							extend: 'csvHtml5',
// 							text: '<img src="/assets/images/csv_icon.png" width="20" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="CSV">',
// 							titleAttr: 'CSV',
// 						},
// 						{
// 							extend: 'pdfHtml5',
// 							text: '<img src="/assets/images/pdf_icon.png" width="20" alt="" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF">',
// 							titleAttr: 'PDF',
// 						},
// 					],
// 				});

// 				tableInstance.clear().rows.add(dataTable.value).draw();

// 			} else {
// 				console.error('DataTables library not loaded or initialized properly');
// 			}
// 		},
// 		async fetchFollowupMasterTaskList() {
// 			try {
// 				loading.value = true;
// 				await new Promise((resolve) => setTimeout(resolve, 2000));
// 				const response = await fetch(`/ccm/patient-followup-task/${this.patientId}/${this.moduleId}/followuplist`);
// 				if (!response.ok) {
// 					throw new Error('Failed to fetch followup task list');
// 				}
// 				loading.value = false;
// 				const data = await response.json();
// 				dataTable.value = data.data; // Replace data with the actual fetched data
// 				// console.log("data===followup====", dataTable.value);
// 				this.initDataTable(dataTable.value);
// 			} catch (error) {
// 				console.error('Error fetching followup task list:', error);
// 				loading.value = false;
// 			}
// 		},
// 	},
// };
</script>
<style>
@import 'ag-grid-community/styles/ag-grid.css';
@import 'ag-grid-community/styles/ag-theme-alpine.css';
/* Use the theme you prefer */

.loading-spinner {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100px;
	/* Adjust as needed */
}
</style>