<!-- ModalForm.vue -->
<template>
    <div class="tab-pane fade show active" id="dme" role="tabpanel" aria-labelledby="dme-services-icon-pill">
        <div class="card">  
            <div class="card-header"><h4>Labs</h4></div>
            <form id="number_tracking_vitals_form" name="number_tracking_vitals_form" @submit.prevent="submiVitalsHealthDataForm">
                <div class="alert alert-success" :style="{ display: showDMEAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Labs data saved successfully! </strong><span id="text"></span>
                </div>  
                <div class="form-row col-md-12">
                    <input type="hidden" name="uid" :value="patientId"/>
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="start_time" value="00:00:00"> 
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="stage_id" :value="vitalsStageId"/>
                    <input type="hidden" name="step_id" :value="vitalsStepId">
                    <input type="hidden" name="form_name" value="number_tracking_vitals_form">
                    <input type="hidden" name="billable" value="1">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="vitalsTime" />
                    <div class="col-md-4 form-group mb-3">
                        <label for="height">Height (in)<!-- <span class="error">*</span> --> :</label>
                        <input type="text" name="height" id="height" class="form-control" />
                        <div class="invalid-feedback" v-if="formErrors.height" style="display: block;">{{ formErrors.height[0] }}</div>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="weight">Weight (lbs)<!-- <span class="error">*</span> --> :</label> 
                        <input type="text" name="weight" id="weight" class="form-control" />
                        <div class="invalid-feedback" v-if="formErrors.weight" style="display: block;">{{ formErrors.weight[0] }}</div>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="bmi">BMI<!-- <span class="error">*</span> --> :</label>
                        <input type="text" name="bmi" id="bmi" class="form-control" />
                        <div class="invalid-feedback" v-if="formErrors.bmi" style="display: block;">{{ formErrors.bmi[0] }}</div>
                        <!-- @text("bmi", ["id" => "bmi","readonly"=>"readonly"]) -->
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="bp">Blood Pressure<!-- <span class="error">*</span> --> :</label>
                        <div class="form-row col-md-12 form-group">
                            <span class="col-md-5">
                            <input type="text" name="bp" id="bp" class="form-control" placeholder="Systolic" />
                            <div class="invalid-feedback" v-if="formErrors.bp" style="display: block;">{{ formErrors.bp[0] }}</div>
                                <!-- @text("bp", ["id" => "bp","placeholder"=>"Systolic"]) -->
                            </span>
                            <span class="mt-1 pl-2 pr-2"> / </span>
                            <span class="col-md-6">
                                <input type="text" name="diastolic" id="diastolic" class="form-control" placeholder="Diastolic" />
                                <div class="invalid-feedback" v-if="formErrors.diastolic" style="display: block;">{{ formErrors.diastolic[0] }}</div>
                                <!-- @text("diastolic", ["id" => "diastolic","placeholder"=>"Diastolic"]) -->
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="o2">O2 Saturation<!-- <span class="error">*</span> --> :</label>
                        <input type="text" name="o2" id="o2" class="form-control" />
                        <div class="invalid-feedback" v-if="formErrors.o2" style="display: block;">{{ formErrors.o2[0] }}</div>
                        <!-- @text("o2", ["id" => "o2"]) -->
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="pulse_rate">Pulse Rate<!-- <span class="error">*</span>  -->:</label>
                        <input type="text" name="pulse_rate" id="pulse_rate" class="form-control" />
                        <div class="invalid-feedback" v-if="formErrors.pulse_rate" style="display: block;">{{ formErrors.pulse_rate[0] }}</div>
                        <!-- @text("pulse_rate", ["id" => "pulse_rate"]) -->
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="pain_level">Pain Level<!-- <span class="error">*</span>  -->:</label>
                        <select name="pain_level" id="pain_level" class="custom-select show-tick" >
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <div class="mr-3 d-inline-flex align-self-center">
                        <label class="radio radio-primary mr-3">
                            <input type="radio" id="yes" name="oxygen" value="1" formControlName="radio" v-model="oxygen">
                            <span>Room Air</span>
                            <span class="checkmark"></span>
                        </label> 
                        <label class="radio radio-primary mr-3">
                            <input type="radio" id="no" name="oxygen" value="0" formControlName="radio" v-model="oxygen">
                            <span>Supplemental Oxygen</span>
                            <span class="checkmark"></span>
                        </label> 
                        </div>
                    </div>  
                    <div class="col-md-12 mr-3 mb-3" v-if="oxygen == 0">
                        <label>Notes</label> 
                        <textarea class="form-control forms-element" name="notes"></textarea>
                        <div class="invalid-feedback"></div>
                    </div> 
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_number_tracking_vitals_form">Save</button>
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
                        <ag-grid-vue
                            style="width: 100%; height: 100%;"
                            id="dme-services-list"
                            class="ag-theme-alpine"
                            :columnDefs="columnDefs.value"
                            :rowData="vitalsRowData.value"
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
    </div>
</template>
<script>
import {
    reactive,
    ref,
    watch,
    onBeforeMount,
    onMounted,
    AgGridVue,
    // Add other common imports if needed
} from '../../commonImports';
// import DMEForm from './SubForms/ServicesShortForm.vue';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageId: Number,
    },
    components: {
        // DMEForm,
        AgGridVue,
    },
    setup(props) {
        let showDMEAlert = ref(false);
        let vitalsStageId = ref(0);
        let vitalsStepId = ref(0);
        let vitalsTime = ref(null);
        let formErrors = ref([]);
        const loading = ref(false);
        const loadingCellRenderer = ref(null);
        const loadingCellRendererParams = ref(null);
        const vitalsRowData = reactive({ value: [] });
        const rowModelType = ref(null);
        const cacheBlockSize = ref(null);
        const maxBlocksInCache = ref(null);
        let columnDefs = reactive({
            value: [
                {
                    headerName: 'Sr. No.',
                    valueGetter: 'node.rowIndex + 1',
                    initialWidth: 20,
                },
                { headerName: 'Rec Date', field: 'rec_date', filter: true },
                { headerName: 'Height (in)', field: 'height' },
                { headerName: 'Weight (lbs)', field: 'weight' },
                { headerName: 'BMI', field: 'bmi' },
                { headerName: 'Systolic', field: 'bp' },
                { headerName: 'Diastolic', field: 'diastolic' },
                { headerName: 'O2 Saturation', field: 'o2' },
                { headerName: 'Pulse Rate', field: 'pulse_rate' },
                { headerName: 'Pain Level', field: 'pain_level' },
                { headerName: 'Oxygen', field: 'oxygen' },
                { headerName: 'Notes', field: 'notes' },
            ]
        });
        const defaultColDef = ref({
            sortable: true,
            filter: true,
            pagination: true,
            flex: 1,
            editable: false,
            cellClass: "cell-wrap-text",
            autoHeight: true,
        });
        const gridOptions = reactive({
            // other properties...
            pagination: true,
            paginationPageSize: 20, // Set the number of rows per page
            domLayout: 'autoHeight', // Adjust the layout as needed
            defaultColDef: {
                resizable: true,
                wrapHeaderText: true,
                autoHeaderHeight: true,
            },
        });

        const fetchPatientVitalsList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/care-plan-development-vital-vitallist/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch vitals list');
                }
                loading.value = false;
                const data = await response.json();
                vitalsRowData.value = data.data;
            } catch (error) {
                console.error('Error fetching vitals list:', error);
                loading.value = false;
            }
        };

        let submiVitalsHealthDataForm = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('number_tracking_vitals_form');
            let formData = new FormData(myForm);
            let formDataObject = {};

            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const saveServicesResponse = await axios.post('/ccm/care-plan-development-numbertracking-vitals', formData);
                    showDMEAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(saveServicesResponse.form_start_time);
                    await fetchPatientVitalsList();
                    document.getElementById("number_tracking_vitals_form").reset();
                    setTimeout(() => {
                        showDMEAlert.value = false;
                        vitalsTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                // Handle the response here
                formErrors.value = [];
            } catch (error) {
                if (error.status && error.status === 422) {
                    formErrors.value = error.responseJSON.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
        }

        let getStepID = async (sid) => {
            try {
                let stepname = 'NumberTracking-Vitals_Data';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                vitalsStepId.value = response.data.stepID;
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        watch(() => props.stageId, (newValue, oldValue) => {
            getStepID(newValue);
        });

        watch(() => showDMEAlert, (newShowDMEAlert, oldShowDMEAlert) => {
                showDMEAlert.value = newShowDMEAlert;
            }
        );

        onBeforeMount(() => {
            loadingCellRenderer.value = 'CustomLoadingCellRenderer';
            loadingCellRendererParams.value = {
                loadingMessage: 'One moment please...',
            };
            rowModelType.value = 'serverSide';
            cacheBlockSize.value = 20;
            maxBlocksInCache.value = 10;
            fetchPatientVitalsList();
        });

        onMounted(async () => {
            try {
                vitalsTime.value = document.getElementById('page_landing_times').value;
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            loading,
            submiVitalsHealthDataForm,
            vitalsStageId,
            vitalsStepId,
            formErrors,
            vitalsTime,
            showDMEAlert,
            columnDefs,
            vitalsRowData,
            defaultColDef,
            gridOptions,
            fetchPatientVitalsList,
            deleteServices,
            editService,
        };
    }
};
</script>