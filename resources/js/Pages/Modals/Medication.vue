<template>
    <div class="modal fade" :class="{ 'show': isOpen }" >
        <loading-spinner :isLoading="isLoading"></loading-spinner>
	<div class="modal-dialog modal-xl">
        <div class="modal-content" style="padding-top:0px; margin:0px;">
            <div class="modal-header">
                <h4 class="modal-title">Medication</h4> 
                <button type="button" class="close" data-dismiss="modal" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <div class="row" id="medications">
                    <div class="col-md-12">
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
                                                        <input type="hidden" name="id" id="medication_id" v-model="medication_id"/>
                                                        <input type="hidden" name="uid" :value="patientId"/>
                                                        <input type="hidden" name="patient_id" :value="patientId"/>
                                                        <input type="hidden" name="start_time" value="00:00:00"> 
                                                        <input type="hidden" name="end_time" value="00:00:00">
                                                        <input type="hidden" name="module_id" :value="moduleId"/>
                                                        <input type="hidden" name="component_id" :value="componentId"/>
                                                        <input type="hidden" name="module_name" :value="module_name" />
                                                        <input type="hidden" name="component_name" id="component_name" :value="component_name" />
                                                        <input type="hidden" name="stage_id" :value="medicationStageId"/>
                                                        <input type="hidden" name="step_id" :value="stepID">
                                                        <input type="hidden" name="form_name" value="medications_form">
                                                        <input type="hidden" name="billable" value="1">
                                                        <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" >
                                                        <div class="col-md-6 form-group mb-3 med_id">
                                                            <label for="medication_med_id">Select Medication<span class='error'>*</span></label> 
                                                            <select name="med_id" class="custom-select show-tick select2" id="medication_med_id" v-model="selectedMedication" @change="onMedicationChanged()">
                                                                <option value="">Select Medication</option>
                                                                <option v-for="medication in medications" :key="medication.id" :value="medication.id">
                                                                    {{ medication.description }}
                                                                </option>
                                                            </select>
                                                            <div class="invalid-feedback" v-if="formErrors.med_id" style="display: block;">{{ formErrors.med_id[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3" style="display:none" id="med_name"> 
                                                            <label for="description">Medication Name<span class='error'>*</span></label>
                                                            <input type="text" name="med_description" id="description" class="form-control" placeholder="Enter Medication Description Name" v-model="med_description" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.med_description" style="display: block;">{{ formErrors.med_description[0] }}</div> -->
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3 description">
                                                            <label for="medication_description">Description</label>
                                                            <input type="text" name="description" id="medication_description" class="form-control" v-model="description" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.description" style="display: block;">{{ formErrors.description[0] }}</div> -->
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3"> 
                                                            <label for="medication_purpose">Purpose<span class='error'>*</span></label>
                                                            <input type="text" name="purpose" id="medication_purpose" class="form-control" v-model="purpose" />
                                                            <div class="invalid-feedback" v-if="formErrors.purpose" style="display: block;">{{ formErrors.purpose[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="medication_strength">Strength<span class='error'>*</span></label>
                                                            <input type="text" name="strength" id="medication_strength" class="form-control" v-model="strength" />
                                                            <div class="invalid-feedback" v-if="formErrors.strength" style="display: block;">{{ formErrors.strength[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="medication_dosage">Dosage<span class='error'>*</span></label>
                                                            <input type="text" name="dosage" id="medication_dosage" class="form-control" v-model="dosage" />
                                                            <div class="invalid-feedback" v-if="formErrors.dosage" style="display: block;">{{ formErrors.dosage[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="medication_route">Route<span class='error'>*</span></label>
                                                            <input type="text" name="route" id="medication_route" class="form-control" v-model="route" />
                                                            <div class="invalid-feedback" v-if="formErrors.route" style="display: block;">{{ formErrors.route[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="medication_frequency">Frequency<span class='error'>*</span></label>
                                                            <input type="text" name="frequency" id="medication_frequency" class="form-control" v-model="frequency" />
                                                            <div class="invalid-feedback" v-if="formErrors.frequency" style="display: block;">{{ formErrors.frequency[0] }}</div>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label for="duration">Duration<span class='error'>*</span></label>
                                                            <input type="text" name="duration" id="duration" class="form-control" v-model="duration" />
                                                            <div class="invalid-feedback" v-if="formErrors.duration" style="display: block;">{{ formErrors.duration[0] }}</div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="pharmacy_name">Pharmacy Name</label>
                                                            <input type="text" name="pharmacy_name" id="pharmacy_name" class="form-control" v-model="pharmacy_name" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.pharmacy_name" style="display: block;">{{ formErrors.pharmacy_name[0] }}</div> -->
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="pharmacy_phone_no">Pharmacy Phone Number</label>
                                                            <input type="text" name="pharmacy_phone_no" id="pharmacy_phone_no" class="form-control" v-model="pharmacy_phone_no" />
                                                            <div class="invalid-feedback" v-if="formErrors.pharmacy_phone_no" style="display: block;">{{ formErrors.pharmacy_phone_no[0] }}</div>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="medication_drug_reaction">Adverse Drug Reactions</label>
                                                            <input type="text" name="drug_reaction" id="medication_drug_reaction" class="form-control" v-model="drug_reaction" />
                                                            <!-- <div class="invalid-feedback" v-if="formErrors.drug_reaction" style="display: block;">{{ formErrors.drug_reaction[0] }}</div> -->
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label for="medication_pharmacogenetic_test">Pharmacogenetics Test</label>
                                                            <input type="text" name="pharmacogenetic_test" id="medication_pharmacogenetic_test" class="form-control" v-model="pharmacogenetic_test" />
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
                        <AgGridTable :rowData="passRowData" :columnDefs="columnDefs"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="closeModal">Close</button>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import {
    ref,
    onBeforeMount,
    onMounted,
    AgGridTable,
} from '../commonImports';
import axios from 'axios';
import Inputmask from 'inputmask';

export default {
	props: {
		patientId: Number,
		moduleId: Number,
        componentId: Number,
	},
    components: {
        AgGridTable,
    },
	setup(props) {
        const passRowData = ref([]); // Initialize rowData as an empty array
        const loading = ref(false);
        let isOpen = ref(false);
        let isLoading = ref(false);
        const module_name = ref('');
        const component_name = ref('');
        let formErrors = ref([]);
        let showAlert = ref(false);
        let medications = ref([]);
        let selectedMedication = ref('');
        let med_description = ref('');
        let description = ref('');
        let purpose = ref('');
        let strength = ref('');
        let dosage = ref('');
        let route = ref('');
        let frequency = ref('');
        let duration = ref('');
        let pharmacy_name = ref('');
        let pharmacy_phone_no = ref('');
        let drug_reaction = ref('');
        let pharmacogenetic_test = ref('');
        let medication_id = ref('');
        let columnDefs = ref([
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
            ]);
       
        let medicationTime = ref(null);
        let medicationStageId = ref(0);
        let stepID = ref(0);

        const fetchPatientMedicationList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const component_name = document.getElementById('component_name').value; 
                const response = await fetch(`/ccm/care-plan-development-medications-medicationslist/${props.patientId}/${component_name}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch patient medication list');
                }
                loading.value = false;
                const data = await response.json();
                passRowData.value = data.data;
            } catch (error) {
                console.error('Error fetching patient medication list:', error);
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

        let submitMedicationForm = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('medications_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                formErrors.value = {};
                const response = await axios.post('/ccm/care-plan-development-medications', formData);
                if (response && response.status == 200) {
                    showAlert.value = true;
                    isLoading.value = false;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
                    await fetchPatientMedicationList();
                    // document.getElementById("medications_form").reset();
                    selectedMedication.value = null;
                    description.value = null;
                    purpose.value = null;
                    strength.value = null;
                    dosage.value = null;
                    route.value = null;
                    frequency.value = null;
                    duration.value = null;
                    pharmacy_name.value = null;
                    pharmacy_phone_no.value = null;
                    drug_reaction.value = null;
                    pharmacogenetic_test.value = null;
                    medication_id.value = null;
                    setTimeout(() => {
                        showAlert.value = false;
                        //.value = document.getElementById('page_landing_times').value;
                        var time = document.getElementById('page_landing_times').value;
                        $(".timearr").val(time);
                    }, 3000);
                }
                isLoading.value = false;
            } catch (error) {
                isLoading.value = false;
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
        }
        
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
                            //medicationTime.value = document.getElementById('page_landing_times').value;
                            var time = document.getElementById('page_landing_times').value;
                            $(".timearr").val(time);
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
            try {
                const medicationToEdit = passRowData.value.find(medication => medication.id == id);
                if (medicationToEdit) {
                    const form = document.getElementById('medications_form');
                    medication_id.value = medicationToEdit.id;
                    selectedMedication.value = medicationToEdit.med_id;
                    description.value = medicationToEdit.description;
                    purpose.value = medicationToEdit.purpose;
                    strength.value = medicationToEdit.strength;
                    dosage.value = medicationToEdit.dosage;
                    route.value = medicationToEdit.route;
                    frequency.value = medicationToEdit.frequency;
                    duration.value = medicationToEdit.duration;
                    pharmacy_name.value = medicationToEdit.pharmacy_name;
                    pharmacy_phone_no.value = medicationToEdit.pharmacy_phone_no;
                    drug_reaction.value = medicationToEdit.drug_reaction;
                    pharmacogenetic_test.value = medicationToEdit.pharmacogenetic_test;
                    form.scrollIntoView({ behavior: 'smooth' });
                }
            } catch (error) {
                console.error('Error editing medication:', error);
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

        let openModal = () => {
            isOpen.value = true;
            document.body.classList.add('modal-open');
            var time = document.getElementById('page_landing_times').value;
            $(".timearr").val(time);
        };

        let closeModal = () => {
            isOpen.value = false;
            document.body.classList.remove('modal-open');
        };

        let onMedicationChanged = async () => {
            let med_id = selectedMedication.value;
            try {
                loading.value = true;
                const response = await axios.get(`/ccm/get-selected-medications_patient-by-id/${props.patientId}/${med_id}/selectedmedicationspatient`);
                if (response && response.status == 200) {
                    loading.value = false;
                    let data = response.data?.medications_form?.static ?? null;
                    if (data) {
                        medication_id.value = data.id;
                        selectedMedication.value = data.med_id;
                        description.value = data.description;
                        purpose.value = data.purpose;
                        strength.value = data.strength;
                        dosage.value = data.dosage;
                        route.value = data.route;
                        frequency.value = data.frequency;
                        duration.value = data.duration;
                        pharmacy_name.value = data.pharmacy_name;
                        pharmacy_phone_no.value = data.pharmacy_phone_no;
                        drug_reaction.value = data.drug_reaction;
                        pharmacogenetic_test.value = data.pharmacogenetic_test;
                    }
                }
            } catch (error) {
                console.error('Error fetching patient medication:', error);
                loading.value = false;
            }
        }

        onBeforeMount(() => {
            fetchPatientMedicationList();
            fetchMedications();
            getStageID();
            const pathname = window.location.pathname;
            const segments = pathname.split('/');
            segments.shift();
            module_name.value = segments[0];
            component_name.value = segments[1];
        });

        onMounted(async () => {
            try {
                //medicationTime.value = document.getElementById('page_landing_times').value;
                var time = document.getElementById('page_landing_times').value;
                $(".timearr").val(time);
                exposeDeleteMedication();
                exposeEditMedication();
                Inputmask({ mask: '(999) 999-9999' }).mask('#pharmacy_phone_no');
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            loading,
            columnDefs,
            passRowData,
            medications,
            submitMedicationForm,
            selectedMedication,
            med_description,
            description,
            purpose,
            strength,
            dosage,
            route,
            frequency,
            duration,
            pharmacy_name,
            pharmacy_phone_no,
            drug_reaction,
            pharmacogenetic_test,
            medication_id,
            medicationTime,
            medicationStageId,
            stepID,
            fetchPatientMedicationList,
            editMedications,
            showAlert,
            formErrors,
            openModal,
            closeModal,
            isOpen,
            onMedicationChanged,
            module_name,
            component_name,
            isLoading,
        };
    }

};
</script>