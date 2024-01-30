<!-- ModalForm.vue -->
<template>
    <div class="tab-pane fade show active" id="healthdata" role="tabpanel" aria-labelledby="healthdata-icon-pill">
        <div class="card">  
            <div class="card-header"><h4>Health Data</h4></div>
            <form id="number_tracking_healthdata_form" name="number_tracking_healthdata_form" @submit.prevent="submitHealthDataForm">
                <div class="alert alert-success" :style="{ display: showImagingAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Health Data data saved successfully! </strong><span id="text"></span>
                </div>  
                <div class="col-md-12">
                    <input type="hidden" name="uid" :value="patientId"/>
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="start_time" value="00:00:00"> 
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="stage_id" :value="healthdataStageId"/>
                    <input type="hidden" name="step_id" :value="healthdataStepId">
                    <input type="hidden" name="form_name" value="number_tracking_healthdata_form">
                    <input type="hidden" name="billable" value="1">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="healthdataTime" />
                    <div v-for="(item, index) in healthdataItems" :key="index" class="form-row">
                        <div class="col-md-4">
                            <label>Health Data:<span class="error">*</span></label>
                            <input type="text" name="health_data[]" placeholder="Enter Health Data" class="forms-element form-control" />
                            <div class="invalid-feedback" v-if="formErrors['health_data.' + index]" style="display: block;">{{ formErrors['health_data.' + index][0] }}</div>
                        </div>
                        <div class="col-md-4">
                            <label >Date<span class="error">*</span> :</label>
                            <input type="date" name="health_date[]" class="forms-element form-control"/>
                            <div class="invalid-feedback" v-if="formErrors['health_date.' + index]" style="display: block;">{{ formErrors['health_date.' + index][0] }}</div>
                        </div>
                        <div class="col-md-1">
                            <i v-if="index > 0" class="remove-icons i-Remove float-right mb-3" title="Remove Follow-up Task" @click="removeImagingItem(index)"></i>
                        </div>
                    </div>
                    <hr/>
                    <div @click="addImagingItem">
                        <i class="plus-icons i-Add" id="add_healthdata" title="Add health_data"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_number_tracking_healthdata_form">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger" id="danger-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Please Fill All mandatory Fields! </strong><span id="text"></span>
                </div>
            </form> 

            <div class="separator-breadcrumb border-top"></div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <AgGridTable :rowData="healthdataRowData" :columnDefs="columnDefs" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
    reactive,
    ref,
    watch,
    onBeforeMount,
    onMounted,
} from '../../commonImports';
import AgGridTable from '../../components/AgGridTable.vue';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageId: Number,
    },
    components: {
        AgGridTable,
    },
    setup(props) {
        let showImagingAlert = ref(false);
        let healthdataStageId = ref(0);
        let healthdataStepId = ref(0);
        let healthdataTime = ref(null);
        let healthdata = ref([]);
        let formErrors = ref([]);
        const loading = ref(false);
        const healthdataRowData = ref([]);
        let healthdataItems = ref([
            {
                health_data: '',
                healthdata_date: ''
            }
        ]);
        let columnDefs = ref([
                {
                    headerName: 'Sr. No.',
                    valueGetter: 'node.rowIndex + 1',
                    initialWidth: 20,
                },
                { headerName: 'Health Date', field: 'health_date', filter: true },
                { headerName: 'Health Data', field: 'health_data' },
            ]);

        const fetchPatientHealthDataList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/care-plan-development-health-healthlist/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch health data list');
                }
                loading.value = false;
                const data = await response.json();
                healthdataRowData.value = data.data;
            } catch (error) {
                console.error('Error fetching health data list:', error);
                loading.value = false;
            }
        };

        let submitHealthDataForm = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('number_tracking_healthdata_form');
            let formData = new FormData(myForm);
            let formDataObject = {};

            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const saveServicesResponse = await axios.post('/ccm/care-plan-development-numbertracking-healthdata', formData);
                if (saveServicesResponse && saveServicesResponse.status == 200) {
                    showImagingAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(saveServicesResponse.data.form_start_time);
                    await fetchPatientHealthDataList();
                    document.getElementById("number_tracking_healthdata_form").reset();
                    setTimeout(() => {
                        showImagingAlert.value = false;
                        healthdataTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                    formErrors.value = [];
                }
            } catch (error) {
                if (error.response.status && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
        }

        let getStepID = async (sid) => {
            try {
                let stepname = 'NumberTracking-Health_Data';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                healthdataStepId.value = response.data.stepID;
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        const addImagingItem = async () => {
            healthdataItems.value.push({
                health_data: '',
                healthdata_date: ''
            });
        };

        const removeImagingItem = async (index) => {
            healthdataItems.value.splice(index, 1);
        };

        watch(() => props.stageId, (newValue, oldValue) => {
            getStepID(newValue);
        });

        watch(() => showImagingAlert, (newShowImagingAlert, oldShowImagingAlert) => {
                showImagingAlert.value = newShowImagingAlert;
            }
        );

        onBeforeMount(() => {
            fetchPatientHealthDataList();
        });

        onMounted(async () => {
            try {
                healthdataTime.value = document.getElementById('page_landing_times').value;
                // getStepID(props.stageId);
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            loading,
            fetchPatientHealthDataList,
            healthdataStageId,
            healthdataStepId,
            formErrors,
            healthdataTime,
            showImagingAlert,
            columnDefs,
            healthdataRowData,
            fetchPatientHealthDataList,
            deleteServices,
            editService,
            healthdata,
            healthdataItems,
            addImagingItem,
            removeImagingItem,
            submitHealthDataForm,
        };
    }
};
</script>