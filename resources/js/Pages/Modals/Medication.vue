<!-- ModalForm.vue -->
<template>
    <div class="overlay" :class="{ 'open': isOpen }" @click="closeModal"></div>
    <div class="modal fade" :class="{ 'open': isOpen }" > <!-- :style="{ display: isOpen ? 'block' : 'none' }"> -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Medication</h4> 
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="row mb-4" id="medications">
                    <div class="col-md-12 mb-4">
                        <div class="success" id="success"></div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-12  mb-4">
                                <ul class="nav nav-pills" id="myPillTab" role="tablist">
                                    <li class="nav-item">
                                        <!-- <a class="nav-link active" id="medication-icon-pill" data-toggle="pill" href="#medication" role="tab" aria-controls="medication" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>MEDICATION</a> -->
                                    </li>
                                </ul>
                                <div class="tab-content" id="myPillTabContent">
                                    <div class="tab-pane fade show active" id="medication" role="tabpanel" aria-labelledby="medication-icon-pill">
                                        <div class="card mb-4">
                                            <div class="card-header mb-3">MEDICATION</div>
                                            <form id="medications_form" name="medications_form" action="" method="post">
                                                <div class="card-body">
                                                    <div class="alert alert-success" id="success-alert" style="display: none;">
                                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                                        <strong> Medication data saved successfully! </strong><span id="text"></span>
                                                    </div> 
                                                    <div class="form-row col-md-12">
                                                    
                                                        <input type="hidden" name="patient_id" value="{{$patient_id}}" />
                                                        <input type="hidden" name="uid" value="{{$patient_id}}">
                                                        <input type="hidden" name="start_time" value="00:00:00">
                                                        <input type="hidden" name="end_time" value="00:00:00">
                                                        <input type="hidden" name="module_id" value="{{ $module_id }}" />
                                                        <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                                                        <input type="hidden" name="stage_id" value="{{$stage_id}}" />
                                                        <input type="hidden" name="step_id" value="{{$step_id}}">
                                                        <input type="hidden" name="form_name" value="medications_form">
                                                        <input type="hidden" name="id">
                                                        <input type="hidden" name="billable" value ="<?php if(isset($patient_enroll_date[0]->finalize_cpd) && $patient_enroll_date[0]->finalize_cpd == 0 && $billable == 0 && $enroll_in_rpm == 0){echo 0;}else{echo 1;} ?>"> 
                                                        <div class="col-md-6 form-group mb-3 med_id">
                                                            <label for="tags">Select Medication<span class='error'>*</span></label> 
                                                            <!-- @selectmedications("med_id",["id"=>"medication_med_id","onchange"=>"carePlanDevelopment.selectMedicationOther(this.value)"]) -->
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3" style="display:none" id="med_name"> 
                                                            <label for="tags">Medication Name<span class='error'>*</span></label>
                                                            <input type="text" name="med_description" id="description" class="form-control" placeholder="Enter Medication Description Name" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3 description">
                                                            <label for="description">Description</label>
                                                            <input type="text" name="description" id="medication_description" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3"> 
                                                            <label for="purpose">Purpose<span class='error'>*</span></label>
                                                            <input type="text" name="purpose" id="medication_purpose" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="strength">Strength<span class='error'>*</span></label>
                                                            <input type="text" name="strength" id="medication_strength" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="dosage">Dosage<span class='error'>*</span></label>
                                                            <input type="text" name="dosage" id="medication_dosage" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="route">Route<span class='error'>*</span></label>
                                                            <input type="text" name="route" id="medication_route" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="frequency">Frequency<span class='error'>*</span></label>
                                                            <input type="text" name="frequency" id="medication_frequency" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="time">Duration<span class='error'>*</span></label>
                                                            <input type="text" name="duration" id="duration" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="pharmacy_name">Pharmacy Name</label>
                                                            <input type="text" name="pharmacy_name" id="pharmacy_name" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="pharmacy_phone_no">Pharmacy Phone Number</label>
                                                            <input type="text" name="pharmacy_phone_no" id="pharmacy_phone_no" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="drug_reaction">Adverse Drug Reactions</label>
                                                            <input type="text" name="drug_reaction" id="medication_drug_reaction" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="pharmacogenetic_test">Pharmacogenetics Test</label>
                                                            <input type="text" name="pharmacogenetic_test" id="medication_pharmacogenetic_test" class="form-control" />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                <div class="mc-footer">
                                                    <div class="row">
                                                    <div class="col-lg-12 text-right">
                                                        <button type="submit" class="btn btn-primary float-right patient_data-save save_medications_form" id="save_medications_form">Save</button>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>
                                            <div class="alert alert-danger" id="danger-alert" style="display: none;">
                                                <button type="button" class="close" data-dismiss="alert">x</button>
                                                <strong> Please Fill All Mandatory Fields! </strong><span id="text"></span>
                                            </div>   
                                            </form> 
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator-breadcrumb border-top"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <ag-grid-vue
                                style="width: 100%; height: 100%;"
                                id="medication-list"
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="closeModal">Close</button>
                <a>Close</a>
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
} from '../commonImports';
import LayoutComponent from '../LayoutComponent.vue'; // Import your layout component
import axios from 'axios';
export default {
	props: {
		patientId: Number,
		moduleId: Number,
		componentId: Number,
	},
    data() {
        return {
            isOpen: false,
            formData: {
                field1: '',
                field2: '',
                // Add more fields as needed
            }
        };
    },
    components: {
        LayoutComponent,
        AgGridVue,
    },
    methods: {
        openModal() {
            console.log('Modal opening...');
            this.isOpen = true;
        },
        closeModal() {
            console.log('Modal closing...');
            this.isOpen = false;
        },
        submitForm() {
            // Handle form submission logic
            // For example, you can emit an event or call an API
            console.log('Form submitted:', this.formData);
            // Close the modal after submission
            this.closeModal();
        }
    },
	setup(props) {
        const rowData = reactive({ value: [] }); // Initialize rowData as an empty array
        const loading = ref(false);
        const loadingCellRenderer = ref(null);
        const loadingCellRendererParams = ref(null);
        const rowModelType = ref(null);
        const cacheBlockSize = ref(null);
        const maxBlocksInCache = ref(null); let columnDefs = reactive({
            value: [
                {
                    headerName: 'Sr. No.',
                    valueGetter: 'node.rowIndex + 1',
                },
                { headerName: 'Name', field: 'name', filter: true },
                { headerName: 'Description', field: 'description' },
                { headerName: 'Purpose', field: 'purpose' },
                { headerName: 'Dosage', field: 'dosage' },
                { headerName: 'Strength', field: 'strength'},
                { headerName: 'Frequency', field: 'frequency' },
                { headerName: 'Route', field: 'route' },
                { headerName: 'Duration', field: 'duration' },
                { headerName: 'Pharmacy Name', field: 'pharmacy_name' },
                { headerName: 'Pharmacy Phone No', field: 'pharmacy_phone_no' },
                { headerName: 'Created By', field: 'users'},
                { headerName: 'Last Modified On', field: 'updated_at' },
                { headerName: 'Reviewed Data', field: 'task_completed_at' },
                { headerName: 'Action', field: 'action' },
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

        const fetchMedicationList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/care-plan-development-medications-medicationslist/${props.patientId}`);
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
                console.log("patientId", props.patientId);
                fetchMedicationList();
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
            // fetchMedicationList,
        };
    }

};
</script>

<style>
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
    height: 800px !important;
}
</style>
