<!-- ModalForm.vue -->
<template>
    <div class="overlay" :class="{ 'open': isOpen }" @click="closeModal"></div>
    <div class="modal fade" :class="{ 'open': isOpen }" > <!-- :style="{ display: isOpen ? 'block' : 'none' }"> -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Medication</h4> 
                <button type="button" class="close" data-dismiss="modal" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <div class="row mb-4" id="medications">
                    <div class="col-md-12 mb-4">
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
                                            <form id="medications_form" name="medications_form" @submit.prevent="submitMedicationForm">
                                                <div class="card-body">
                                                    <div class="alert alert-success" :style="{ display: showAlert ? 'block' : 'none' }">
                                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                                        <strong> Medication data saved successfully! </strong><span id="text"></span>
                                                    </div> 
                                                    <div class="form-row col-md-12">
                                                        <input type="hidden" name="id" id="medication_id"/>
                                                        <input type="hidden" name="uid" :value="patientId"/>
                                                        <input type="hidden" name="patient_id" :value="patientId"/>
                                                        <input type="hidden" name="start_time" value="00:00:00"> 
                                                        <input type="hidden" name="end_time" value="00:00:00">
                                                        <input type="hidden" name="module_id" :value="moduleId"/>
                                                        <input type="hidden" name="component_id" :value="componentId"/>
                                                        <input type="hidden" name="stage_id" :value="medicationStageId"/>
                                                        <input type="hidden" name="step_id" :value="stepID">
                                                        <input type="hidden" name="form_name" value="medications_form">
                                                        <input type="hidden" name="billable" value="1">
                                                        <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="medicationTime">
                                                        <div class="col-md-6 form-group mb-3 med_id">
                                                            <label for="medication_med_id">Select Medication<span class='error'>*</span></label> 
                                                            <select name="med_id" class="custom-select show-tick select2" id="medication_med_id" v-model="selectedMedication">
                                                                <option value="">Select Medication</option>
                                                                <option v-for="medication in medications" :key="medication.id" :value="medication.id">
                                                                    {{ medication.description }}
                                                                </option>
                                                            </select>
                                                            <div class="invalid-feedback" v-if="formErrors.med_id" style="display: block;">{{ formErrors.med_id[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3" style="display:none" id="med_name"> 
                                                            <label for="description">Medication Name<span class='error'>*</span></label>
                                                            <input type="text" name="med_description" id="description" class="form-control" placeholder="Enter Medication Description Name" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.med_description" style="display: block;">{{ formErrors.med_description[0] }}</div> -->
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3 description">
                                                            <label for="medication_description">Description</label>
                                                            <input type="text" name="description" id="medication_description" class="form-control" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.description" style="display: block;">{{ formErrors.description[0] }}</div> -->
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3"> 
                                                            <label for="medication_purpose">Purpose<span class='error'>*</span></label>
                                                            <input type="text" name="purpose" id="medication_purpose" class="form-control" />
                                                            <div class="invalid-feedback" v-if="formErrors.purpose" style="display: block;">{{ formErrors.purpose[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="medication_strength">Strength<span class='error'>*</span></label>
                                                            <input type="text" name="strength" id="medication_strength" class="form-control" />
                                                            <div class="invalid-feedback" v-if="formErrors.strength" style="display: block;">{{ formErrors.strength[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="medication_dosage">Dosage<span class='error'>*</span></label>
                                                            <input type="text" name="dosage" id="medication_dosage" class="form-control" />
                                                            <div class="invalid-feedback" v-if="formErrors.dosage" style="display: block;">{{ formErrors.dosage[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="medication_route">Route<span class='error'>*</span></label>
                                                            <input type="text" name="route" id="medication_route" class="form-control" />
                                                            <div class="invalid-feedback" v-if="formErrors.route" style="display: block;">{{ formErrors.route[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="medication_frequency">Frequency<span class='error'>*</span></label>
                                                            <input type="text" name="frequency" id="medication_frequency" class="form-control" />
                                                            <div class="invalid-feedback" v-if="formErrors.frequency" style="display: block;">{{ formErrors.frequency[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="duration">Duration<span class='error'>*</span></label>
                                                            <input type="text" name="duration" id="duration" class="form-control" />
                                                            <div class="invalid-feedback" v-if="formErrors.duration" style="display: block;">{{ formErrors.duration[0] }}</div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="pharmacy_name">Pharmacy Name</label>
                                                            <input type="text" name="pharmacy_name" id="pharmacy_name" class="form-control" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.pharmacy_name" style="display: block;">{{ formErrors.pharmacy_name[0] }}</div> -->
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="pharmacy_phone_no">Pharmacy Phone Number</label>
                                                            <input type="text" name="pharmacy_phone_no" id="pharmacy_phone_no" class="form-control" />
                                                            <div class="invalid-feedback" v-if="formErrors.pharmacy_phone_no" style="display: block;">{{ formErrors.pharmacy_phone_no[0] }}</div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="medication_drug_reaction">Adverse Drug Reactions</label>
                                                            <input type="text" name="drug_reaction" id="medication_drug_reaction" class="form-control" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.drug_reaction" style="display: block;">{{ formErrors.drug_reaction[0] }}</div> -->
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="medication_pharmacogenetic_test">Pharmacogenetics Test</label>
                                                            <input type="text" name="pharmacogenetic_test" id="medication_pharmacogenetic_test" class="form-control" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.pharmacogenetic_test" style="display: block;">{{ formErrors.pharmacogenetic_test[0] }}</div> -->
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
            formErrors: {},
            showAlert: false,
        };
    },
    components: {
        LayoutComponent,
        AgGridVue,
    },
    methods: {
        openModal() {
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
        },
        async submitMedicationForm() {
            let myForm = document.getElementById('medications_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                this.formErrors = {};
                const response = await axios.post('/ccm/care-plan-development-medications', formData);
                if (response && response.status == 200) {
                    this.showAlert = true;
                    updateTimer(this.patientId, '1', this.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
                    this.fetchPatientMedicationList();
                    document.getElementById("medications_form").reset();
                    let select_box = document.getElementById("medication_med_id");
                    select_box.selectedIndex = -1;
                    setTimeout(() => {
                        this.showAlert = false;
                        this.medicationTime = document.getElementById('page_landing_times').value;
                    }, 3000);
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
            // this.closeModal();
        }
    },
	setup(props) {
        const rowData = reactive({ value: [] }); // Initialize rowData as an empty array
        const loading = ref(false);
        const loadingCellRenderer = ref(null);
        const loadingCellRendererParams = ref(null);
        const rowModelType = ref(null);
        const cacheBlockSize = ref(null);
        let medications = ref([]);
        let selectedMedication = ref('');
        const maxBlocksInCache = ref(null);
        let columnDefs = reactive({
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
                { 
                    headerName: 'Action',
                    field: 'action',
                    cellRenderer: function (params) {
                        const row = params.data;
                        return row && row.action ? row.action : '';
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
        let medicationTime = ref(null);
        let medicationStageId = ref(0);
        let stepID = ref(0);

        const fetchPatientMedicationList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/care-plan-development-medications-medicationslist/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                loading.value = false;
                const data = await response.json();
                rowData.value = data.data;
            } catch (error) {
                console.error('Error fetching followup task list:', error);
                loading.value = false;
            }
        };

        let fetchMedications = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/org/ajax/medication/list`);
                if (!response.ok) {
                    throw new Error('Failed to fetch medication list');
                }
                const medicationsData = await response.json();
                medications.value = medicationsData; 
            } catch (error) {
                console.error('Error fetching medications list:', error);
                loading.value = false;
            }
        };
        
        let getStageID = async () => {
            try {
                let medicationSageName = 'Patient_Data';
                let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${medicationSageName}`);
                medicationStageId.value = response.data.stageID;
                getStepID(medicationStageId.value);
            } catch (error) {
                throw new Error('Failed to fetch Patient Data stageID');
            }
        };

        let deleteMedications = async (id, obj) => {
            if (window.confirm("Are you sure you want to delete this Medication?")) {
                const formData = {
                    id: id,
                    uid: props.patientId,
                    patient_id: props.patientId,
                    module_id: props.moduleId,
                    component_id: props.componentId,
                    stage_id: medicationStageId.value,
                    step_id: stepID.value,
                    form_name: 'medications_form',
                    billable: 1,
                    start_time: "00:00:00",
                    end_time: "00:00:00",
                    form_start_time: document.getElementById('page_landing_times').value,
                };
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
                try {
                    await new Promise((resolve) => setTimeout(resolve, 2000));
                    const response = await axios.post(`/ccm/delete-medications_patient-by-id`, formData);
                    if (response && response.status == 200) {
                        // showMedicalSuppliesAlert.value = true;
                        updateTimer(props.patientId, '1', props.moduleId);
                        $(".form_start_time").val(response.data.form_start_time);
                        await fetchPatientMedicationList();
                        setTimeout(() => {
                            // showMedicalSuppliesAlert.value = false;
                            medicationTime.value = document.getElementById('page_landing_times').value;
                        }, 3000);
                    }
                } catch (error) {
                    console.error('Error deletting record:', error);
                }
            }
        }

        const exposeDeleteMedication = () => {
            window.deleteMedications = deleteMedications;
        };

        let editMedications = async (id) => {
            // console.log("edit medication id==>", id);
            try {
                const serviceToEdit = rowData.value.find(service => service.id == id);
                if (serviceToEdit) {
                    const form = document.getElementById('medications_form');
                    form.querySelector('#medication_id').value = serviceToEdit.id;
                    const medicationIdDropdown = form.querySelector('#medication_med_id');
                    medicationIdDropdown.value = serviceToEdit.med_id;
                    form.querySelector('#medication_description').value = serviceToEdit.description;
                    form.querySelector('#medication_purpose').value = serviceToEdit.purpose;
                    form.querySelector('#medication_strength').value = serviceToEdit.strength;
                    form.querySelector('#medication_dosage').value = serviceToEdit.dosage;
                    form.querySelector('#medication_route').value = serviceToEdit.route;
                    form.querySelector('#medication_frequency').value = serviceToEdit.frequency;
                    form.querySelector('#duration').value = serviceToEdit.duration;
                    form.querySelector('#pharmacy_name').value = serviceToEdit.pharmacy_name;
                    form.querySelector('#pharmacy_phone_no').value = serviceToEdit.pharmacy_phone_no;
                    form.querySelector('#medication_drug_reaction').value = serviceToEdit.id;
                    form.querySelector('#medication_pharmacogenetic_test').value = serviceToEdit.id;
                    console.log("medication_id medications_form-->>", medications_form);
                    // selectedMedication = serviceToEdit.med_id;
                    form.scrollIntoView({ behavior: 'smooth' });
                }
            } catch (error) {
                console.error('Error editing service:', error);
            }
        }

        const exposeEditMedication = () => {
            window.editMedications = editMedications;
        };

        let getStepID = async (sid) => {
            try {
                let stepname = 'Medication';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                stepID.value = response.data.stepID;
            } catch (error) {
                throw new Error('Failed to fetch stageID');
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
            fetchPatientMedicationList();
            fetchMedications();
            getStageID();
        });

        onMounted(async () => {
            try {
                medicationTime.value = document.getElementById('page_landing_times').value;
                exposeDeleteMedication();
                exposeEditMedication();
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
            medications,
            selectedMedication,
            medicationTime,
            medicationStageId,
            stepID,
            fetchPatientMedicationList,
            editMedications,
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
  overflow-y:auto;
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
</style>
