<!-- ModalForm.vue -->
<template>
    <div class="tab-pane fade show active" id="labs" role="tabpanel" aria-labelledby="labs-icon-pill">
        <div class="card">  
            <div class="card-header"><h4>Labs</h4></div>
            <form id="number_tracking_labs_form" name="number_tracking_labs_form" @submit.prevent="submiLabsHealthDataForm">
                <div class="alert alert-success" :style="{ display: showLabsAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Labs data saved successfully! </strong><span id="text"></span>
                </div>  
                <div class="col-md-12">
                    <input type="hidden" name="uid" :value="patientId"/>
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="start_time" value="00:00:00"> 
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="stage_id" :value="labsStageId"/>
                    <input type="hidden" name="step_id" :value="labsStepId">
                    <input type="hidden" name="form_name" value="number_tracking_labs_form">
                    <input type="hidden" name="billable" value="1">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="labsTime" />
                    <div class="form-row">
                        <div class="col-md-4 form-group mb-3">
                            <label>Labs<span class="error">*</span> :</label><br>
                            <select name="lab[]" class="custom-select show-tick select2 col-md-10" id="lab" v-model="selectedLabs">
                                <option value="">Select Lab</option>
                                <option v-for="lab in labs" :key="lab.id" :value="lab.id">
                                    {{ lab.description }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="formErrors.med_id" style="display: block;">{{ formErrors.med_id[0] }}</div>
                            <!-- @selectlab("lab[]",["class"=>"col-md-10", "onchange"=>"carePlanDevelopment.getLabParamsOnLabChange(this)", "id"=>"lab"]) -->
                        </div>
                        <div class="col-md-4 form-group mb-3">   
                            <label for="labdate">Date<span class="error">*</span> :</label>
                            <input type="date" name="labdate[]" id="labdate" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_number_tracking_labs_form">Save</button>
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
                            id="labs-list"
                            class="ag-theme-alpine"
                            :columnDefs="columnDefs.value"
                            :rowData="labsRowData.value"
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
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageId: Number,
    },
    components: {
        AgGridVue,
    },
    setup(props) {
        let showLabsAlert = ref(false);
        let labsStageId = ref(0);
        let labsStepId = ref(0);
        let labsTime = ref(null);
        let labs = ref([]);
        let formErrors = ref([]);
        let selectedLabs = ref(0);
        const loading = ref(false);
        const loadingCellRenderer = ref(null);
        const loadingCellRendererParams = ref(null);
        const labsRowData = reactive({ value: [] });
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
                { headerName: 'Lab', field: 'description', filter: true },
                { headerName: 'Lab Date', field: 'lab_date' },
                { headerName: 'Reading', field: 'labparameter' },
                { headerName: 'Notes', field: 'notes' },
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

        const fetchPatientLabsList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/care-plan-development-labs-labslist/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch labs list');
                }
                loading.value = false;
                const data = await response.json();
                labsRowData.value = data.data;
            } catch (error) {
                console.error('Error fetching labs list:', error);
                loading.value = false;
            }
        };

        let submiLabsHealthDataForm = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('number_tracking_labs_form');
            let formData = new FormData(myForm);
            let formDataObject = {};

            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const saveServicesResponse = await axios.post('/ccm/care-plan-development-numbertracking-labs', formData);
                    showLabsAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(saveServicesResponse.form_start_time);
                    await fetchPatientLabsList();
                    document.getElementById("number_tracking_labs_form").reset();
                    setTimeout(() => {
                        showLabsAlert.value = false;
                        labsTime.value = document.getElementById('page_landing_times').value;
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
                let stepname = 'NumberTracking-Labs';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                labsStepId.value = response.data.stepID;
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        let fetchLabs = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/org/active-labs`);
                if (!response.ok) {
                    throw new Error('Failed to fetch lab list');
                }
                const labsData = await response.json();
                labs.value = labsData;
            } catch (error) {
                console.error('Error fetching labs list:', error);
                loading.value = false;
            }
        };

        watch(() => props.stageId, (newValue, oldValue) => {
            getStepID(newValue);
        });

        watch(() => showLabsAlert, (newShowLabsAlert, oldShowLabsAlert) => {
                showLabsAlert.value = newShowLabsAlert;
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
            fetchLabs();
            fetchPatientLabsList();
        });

        onMounted(async () => {
            try {
                labsTime.value = document.getElementById('page_landing_times').value;
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            loading,
            submiLabsHealthDataForm,
            labsStageId,
            labsStepId,
            formErrors,
            labsTime,
            showLabsAlert,
            columnDefs,
            labsRowData,
            defaultColDef,
            gridOptions,
            fetchPatientLabsList,
            deleteServices,
            editService,
            fetchLabs,
            labs,
        };
    }
};
</script>