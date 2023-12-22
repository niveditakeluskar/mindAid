<template>
	<div class="row">
		<div class="col-lg-12 mb-3">
			<form id="followup_form" name="followup_form" @submit.prevent="submitFollowupForm">
				<input type="hidden" name="uid" v-model="this.uid" :value="`${patientId}`"/>
				<input type="hidden" name="patient_id" v-model="this.patient_id" :value="`${patientId}`" />
				<input type="hidden" name="start_time" v-model="this.start_time" value="00:00:00">
				<input type="hidden" name="end_time" v-model="this.end_time" value="00:00:00">
				<input type="hidden" name="module_id" v-model="this.module_id" :value="`${moduleId}`" />
				<input type="hidden" name="component_id" v-model="this.component_id" :value="`${componentId}`" />
				<input type="hidden" name="stage_id" v-model="this.stage_id" />
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
											<input type="text" name="task_name[]" class="forms-element form-control" placeholder="Task" v-model="item.task_name" />
										</div>
										<div class="col-md-4 form-group selects">
											<select name="followupmaster_task[]" class="custom-select show-tick select2"
												v-model="item.selectedFollowupMasterTask">
												<option value="">Select Task Category</option>
												<option v-for="followupMasterTask in followupMasterTaskList" :key="followupMasterTask.id" :value="followupMasterTask.id">
													{{ followupMasterTask.task }}
												</option>
											</select>
											<!-- @selectfuturefollowuptask("followupmaster_task[]",["id"=>"followupmaster_task"]) -->
										</div>
										<!-- <input type="hidden" name="selected_task_name[]" id="selected_task_name_0" /> -->
										<div class="col-md-4 form-group">
				                                <label class="radio radio-primary col-md-4 float-left">
				                                    <input type="radio" class="status_flag" name="status_flag[0]" value="0" formControlName="radio" checked v-model="item.status_flag">
				                                    <span>To be Scheduled</span>
				                                    <span class="checkmark"></span>
				                                </label>
				                                <label class="radio radio-primary col-md-4 float-left">
				                                    <input type="radio" class="status_flag" name="status_flag[0]" value="1" formControlName="radio" v-model="item.status_flag">
				                                    <span>Completed</span>
				                                    <span class="checkmark"></span>
				                                </label>
										</div>
									</div>
									<div class='row ml-1'>
										<div class="col-md-6 form-group">
											<textarea name="notes[]" class="forms-element form-control" placeholder="Notes" v-model="item.notes" ></textarea>
											<div class="invalid-feedback"></div>
										</div>
										<div class="col-md-2 form-group"><input type="date" name="task_date[]" class="forms-element form-control" v-model="item.task_date" /></div>
									</div>
		                        </div>
								<div v-if="index > 0">
									<div @click="removeItem(index)"><i class="remove-icons i-Remove float-right mb-3" title="Remove Follow-up Task"></i></div>
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
										<input type="checkbox" name="emr_complete" id="emr_complete" value="1"><span>EMR system entry completed</span><span class="checkmark"></span>
									</label>
									<div id="followup_emr_system_entry_complete_error" class="invalid-feedback"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 text-right">
									<button type="submit" id="save-followup" class="btn  btn-primary m-1 office-visit-save" @click="submitFollowupFormData">Save</button>
								</div> 
							</div>	
						</div>
					</div>
					<hr>	
					<div class="col-md-12">
						<div class="table-responsive">
							<ag-grid-vue
								style="width: 100%; height: 100%;"
								id="task-list"
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
	onBeforeMount,
	onMounted,
	AgGridVue,
// Add other common imports if needed
} from '../../commonImports';
import LayoutComponent from '../../LayoutComponent.vue'; // Import your layout component
import axios from 'axios';
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
			uid: '', // Add default values or leave them as empty strings
			patient_id: '',
			start_time: '',
			end_time: '',
			module_id: '',
			component_id: '',
			stage_id: '',
			step_id: '',
			form_name: '',
			items: [
				{
					task_name: '',
					selectedFollowupMasterTask: '',
					status_flag: '',
					notes: '',
					task_date: ''
				}
			],
		};
	},
	methods: {
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

		submitFollowupFormData() {
			const formData = {
				uid: this.uid,
				patient_id: this.patient_id,
				start_time: this.start_time,
				end_time: this.end_time,
				module_id: this.module_id,
				component_id: this.component_id,
				stage_id: this.stage_id,
				step_id: this.step_id,
				form_name: this.form_name,
				dynammicData: this.items.map(index => index.value),
			};
			console.log("formData==>>", formData);
			// axios.post('/your-api-endpoint', this.formData)
			// 	.then(response => {
			// 		console.log('Form submitted successfully!', response.data);
			// 	})
			// 	.catch(error => {
			// 		// Handle error response
			// 		console.error('Error submitting form:', error);
			// 	});
		},
	},
	setup(props) {
		const rowData = reactive({ value: [] }); // Initialize rowData as an empty array
		const loading = ref(false);
		const loadingCellRenderer = ref(null);
		const loadingCellRendererParams = ref(null);
		const rowModelType = ref(null);
		const cacheBlockSize = ref(null);
		const maxBlocksInCache = ref(null);
		const followupMasterTaskList = ref([]);
		// 

		let columnDefs = reactive({
			value: [
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
						if (row && row.notes) {
							return row.notes + '<a  data-toggle="tooltip" data-id="' + row.id + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a>'; // Returning the HTML content as provided from the controller
						} else {
							return '<a  data-toggle="tooltip" data-id="' + row.id + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a>'; // Or handle the case where the 'action' value is not available
						}
					},
					// cellEditorSelector: (params) => {
					// 	return {
					// 		component: 'agRichSelectCellEditor',
					// 		params: {
					// 			values: ['Male', 'Female'],
					// 		},
					// 		popup: true,
					// 	};
					// },
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
					valueGetter: function (params) {
						const row = params.data;
						return row && row.f_name ? row.f_name + ' ' + row.l_name : 'N/A';
					},
				},
			]
		});

		const defaultColDef = ref({
			sortable: true,
			filter: true,
			pagination: true,
			minWidth: 100,
			flex: 1,
			editable: false,
		});

		const gridOptions = reactive({
			// other properties...
			pagination: true,
			paginationPageSize: 20, // Set the number of rows per page
			domLayout: 'autoHeight', // Adjust the layout as needed
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

		onBeforeMount(() => {
			loadingCellRenderer.value = 'CustomLoadingCellRenderer';
			loadingCellRendererParams.value = {
				loadingMessage: 'One moment please...',
			};
			rowModelType.value = 'serverSide';
			cacheBlockSize.value = 20;
			maxBlocksInCache.value = 10;
		});

		onMounted(async () => {
			try {
				fetchFollowupMasterTask();
				fetchFollowupMasterTaskList();
			} catch (error) {
				console.error('Error on page load:', error);
			}
		});

		return {
			loading,
			columnDefs,
			rowData,
			defaultColDef,
			gridOptions,
			fetchFollowupMasterTask,
			fetchFollowupMasterTaskList,
			followupMasterTaskList,
			// addNewItem,
			// removeItem,
		};
	}
}
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