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
                    <input type="hidden" name="start_time" value="00:00:00" /> 
                    <input type="hidden" name="end_time" value="00:00:00" />
                    <input type="hidden" name="module_id" :value="moduleId" />
                    <input type="hidden" name="component_id" :value="componentId" />
                    <input type="hidden" name="stage_id" :value="stageId" />
                    <input type="hidden" name="step_id" :value="labsStepId" />
                    <input type="hidden" name="form_name" value="number_tracking_labs_form" />
                    <input type="hidden" name="billable" value="1" />
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="labsTime" />
                    <input type="hidden" name="editform" id="editform" :value="editform" />
                    <input type="hidden" name="olddate" id="olddate" :value="olddate" />
                    <input type="hidden" name="oldlab" id="oldlab" :value="oldlab" />
                    <input type="hidden" name="labdateexist" id="labdateexist" :value="labdateexist" />
                    <div class="form-row">
                        <div class="col-md-4 form-group mb-3">
                            <label>Labs<span class="error">*</span> :</label><br>
                            <select name="lab[]" class="custom-select show-tick select2 col-md-10" id="lab" @change="onLabchange" v-model="selectedLabs">
                                <option value="">Select Lab</option>
                                <option v-for="lab in labs" :key="lab.id" :value="lab.id">
                                    {{ lab.description }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="formErrors['lab.0']" style="display: block;">{{ formErrors['lab.0'][0] }}</div>
                        </div>
                        <div class="col-md-4 form-group mb-3">   
                            <label for="labdate">Date<span class="error">*</span> :</label>
                            <input type="date" name="labdate[]" id="labdate" class="form-control" v-model="labDate"/>
                            <div class="invalid-feedback" v-if="formErrors['labdate.0']" style="display: block;">{{ formErrors['labdate.0'][0] }}</div>
                        </div>
                    </div>
                    <div v-html="labParams" class="form-row"></div>
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
                    <AgGridTable :rowData="labsRowData" :columnDefs="columnDefs"/>
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
    AgGridTable,
    // Add other common imports if needed
} from '../../commonImports';
import axios from 'axios';
import moment from 'moment';
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
        const showLabsAlert = ref(false);
        const labsStepId = ref(0);
        const labsTime = ref(null);
        const labs = ref([]);
        const labParams = ref('');
        const formErrors = ref([]);
        const selectedLabs = ref(0);
        const labDate = ref('');
        const loading = ref(false);
        const editform = ref('');
        const olddate = ref('');
        const oldlab = ref('');
        const labdateexist = ref('');
        const labsRowData = ref([]);
       
        const columnDefs = ref( [
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
        );

        const fetchPatientLabsList = async () => {
            try {
                loading.value = true;
                // await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
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

        const submiLabsHealthDataForm = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('number_tracking_labs_form');
            let formData = new FormData(myForm);
            let formDataObject = {};

            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const saveLabResponse = await axios.post('/ccm/care-plan-development-numbertracking-labs', formData);
                if (saveLabResponse && saveLabResponse.status === 200) {
                    showLabsAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(saveLabResponse.data.form_start_time);
                    await fetchPatientLabsList();
                    document.getElementById("number_tracking_labs_form").reset();
                    selectedLabs.value = null;
                    labParams.value = null;
                    editform.value = null;
                    olddate.value = null;
                    oldlab.value = null;
                    labdateexist.value = null;
                    setTimeout(() => {
                        showLabsAlert.value = false;
                        labsTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                    // Handle the response here
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

        const onLabchange = async (event) => {
            const labId = event.target.value;
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const getLabResponse = await axios.post('/ccm/lab-param', { lab: labId });
                labParams.value = getLabResponse.data;
            } catch (error) {
                if (error.status && error.status === 422) {
                    formErrors.value = error.responseJSON.errors;
                } else {
                    console.error('Error getting lab params:', error);
                }
            }
        }

        const getStepID = async (sid) => {
            try {
                let stepname = 'NumberTracking-Labs';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                labsStepId.value = response.data.stepID;
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        const fetchLabs = async () => {
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

        const deleteLabs = async (date, patient_id, lab_test_id, labdateexist) => {
            if (window.confirm("Are you sure you want to delete this Service?")) {
                const formData = {
                    labid: lab_test_id,
                    uid: props.patientId,
                    patient_id: props.patientId,
                    module_id: props.moduleId,
                    component_id: props.componentId,
                    stage_id: props.stageId,
                    step_id: labsStepId.value,
                    form_name: 'number_tracking_labs_form',
                    billable: 1,
                    start_time: "00:00:00",
                    end_time: "00:00:00",
                    form_start_time: document.getElementById('page_landing_times').value,
                    labdate: date,
                    patientid: patient_id,
                    labdateexist: labdateexist,
                };
                try {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
                    const deleteServicesResponse = await axios.post(`/ccm/delete-lab`, formData);
                    // showDMEAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(deleteServicesResponse.form_start_time);
                    await fetchPatientLabsList();
                    document.getElementById("service_dme_form").reset();
                    setTimeout(() => {
                        // showDMEAlert.value = false;
                        labsTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                } catch (error) {
                    console.error('Error deletting record:', error);
                }
            }
        }

        const exposeDeleteLab = () => {
            window.deleteLabs = deleteLabs;
        };

        const editlabsformnew = async (lab_date, patient_id, lab_test_id, lab_date_exist) => {
            console.log("lab_date" + lab_date +", patient_id" + patient_id+", lab_test_id" + lab_test_id+", lab_date_exist"+ lab_date_exist);
            try {
                loading.value = true;
                const response = await fetch(`/ccm/care-plan-development-populateLabs/${patient_id}/${lab_date}/${lab_test_id}/${lab_date_exist}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch labs details');
                }
                loading.value = false;
                const data = await response.json();
                console.log("data", data);
                const labs = data.number_tracking_labs_details.dynamic.lab;
                console.log("data labs", labs);
                selectedLabs.value = lab_test_id;
                let dt = moment(lab_date, 'DD-MM-YYYY').format('YYYY-MM-DD'); //new Date(lab_date).toISOString().split('T')[0];
                labDate.value = dt;
                labParams.value = generateLabParams(lab_test_id, labs);
                editform.value = 'edit';
                olddate.value = dt;
                oldlab.value = lab_test_id;
                labdateexist.value = dt;
            } catch (error) {
                console.error('Error fetching labs details:', error);
                loading.value = false;
            }
        };

        const generateLabParams = (lab, labParams) => {
            let params = '';
            let labNotes = '';
            console.log("labParams", labParams);
            labParams.forEach((value) => {
                params += `<div class='col-md-6 mb-3'>`;
                params += `<label>${value.parameter} <span class='error'>*</span></label>`;
                params += `<input type='hidden' name='lab_test_id[${lab}][]'  value='${value.lab_test_id}'>`;
                params += `<input type='hidden' name='lab_params_id[${lab}][]' value='${value.lab_test_parameter_id}'>`;

                if (value.parameter === 'COVID-19') {
                    params += `<div class='form-row'><div class='col-md-5'>`;
                    params += `<select class='forms-element form-control mr-1 pl-3' name='reading[${lab}][]'>`;
                    params += `<option value=''>Select Reading</option>`;
                    params += `<option value='positive' ${value.reading === 'positive' ? 'selected' : ''}>Positive</option>`;
                    params += `<option value='negative' ${value.reading === 'negative' ? 'selected' : ''}>Negative</option></select>`;
                    params += `<div class='invalid-feedback'></div></div>`;
                } else {
                    params += `<div class='form-row'><div class='col-md-5'>`;
                    params += `<select class='forms-element form-control mr-1 pl-3 labreadingclass' name='reading[${lab}][]'>`;
                    params += `<option value=''>Select Reading</option>`;
                    params += `<option value='high' ${value.reading === 'high' ? 'selected' : ''}>High</option>`;
                    params += `<option value='normal' ${value.reading === 'normal' ? 'selected' : ''}>Normal</option>`;
                    params += `<option value='low' ${value.reading === 'low' ? 'selected' : ''}>Low</option>`;
                    params += `<option value='test_not_performed' ${value.reading === 'test_not_performed' ? 'selected' : ''}>Test not performed</option></select>`;
                    params += `<div class='invalid-feedback'></div></div>`;
                    params += `<div class='col-md-6'>`;
                    params += `<input type='text' class='forms-element form-control' name='high_val[${lab}][]' value='${value.high_val}' />`;
                    params += `<div class='invalid-feedback'></div></div>`;
                }
                labNotes = value.notes;
                params += `</div></div>`;
            });

            params += `<div class="col-md-12 mb-3"><label>Notes:</label>`;
            params += `<textarea class="forms-element form-control" name="notes[${lab}]">${labNotes}</textarea>`;
            params += `<div class="invalid-feedback"></div></div>`;
            return params;
        };

        const exposeEditLab = () => {
            window.editlabsformnew = editlabsformnew;
        };

        watch(() => props.stageId, (newValue, oldValue) => {
            getStepID(newValue);
        });

        watch(() => showLabsAlert, (newShowLabsAlert, oldShowLabsAlert) => {
                showLabsAlert.value = newShowLabsAlert;
            }
        );

        onBeforeMount(() => {
            fetchLabs();
            fetchPatientLabsList();
        });

        onMounted(async () => {
            try {
                labsTime.value = document.getElementById('page_landing_times').value;
                exposeDeleteLab();
                exposeEditLab();
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            loading,
            submiLabsHealthDataForm,
            labsStepId,
            formErrors,
            labsTime,
            showLabsAlert,
            columnDefs,
            labsRowData,
            selectedLabs,
            labDate,
            fetchPatientLabsList,
            deleteServices,
            editService,
            fetchLabs,
            labs,
            onLabchange,
            labParams,
            editform,
            olddate,
            oldlab,
            labdateexist,
        };
    }
};
</script>