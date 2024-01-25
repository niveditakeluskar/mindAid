<!-- ModalForm.vue -->
<template>
    <div class="tab-pane fade show active" id="imaging" role="tabpanel" aria-labelledby="imaging-icon-pill">
        <div class="card">  
            <div class="card-header"><h4>Imaging</h4></div>
            <form id="number_tracking_imaging_form" name="number_tracking_imaging_form" @submit.prevent="submiLabsHealthDataForm">
                <div class="alert alert-success" :style="{ display: showImagingAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Imaging data saved successfully! </strong><span id="text"></span>
                </div>  
                <div class="col-md-12">
                    <input type="hidden" name="uid" :value="patientId"/>
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="start_time" value="00:00:00"> 
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="stage_id" :value="imagingStageId"/>
                    <input type="hidden" name="step_id" :value="imagingStepId">
                    <input type="hidden" name="form_name" value="number_tracking_imaging_form">
                    <input type="hidden" name="billable" value="1">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="imagingTime" />
                    <div v-for="(item, index) in imagingItems" :key="index" class="form-row">
                        <div class="col-md-4">
                            <label>Imaging : <span class="error">*</span></label>
                            <input type="text" name="imaging[]" placeholder="Enter Imaging" class="forms-element form-control" />
                        </div>
                        <div class="col-md-4">
                            <label >Date<span class="error">*</span> :</label>
                            <input type="date" name="imaging_date[]" class="forms-element form-control"/>
                        </div>
                        <div class="col-md-1">
                            <i v-if="index > 0" class="remove-icons i-Remove float-right mb-3" title="Remove Follow-up Task" @click="removeImagingItem(index)"></i>
                        </div>
                    </div>
                    <hr/>
                    <div @click="addImagingItem">
                        <i class="plus-icons i-Add" id="add_imaging" title="Add imaging"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_number_tracking_imaging_form">Save</button>
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
                        <AgGridTable :rowData="imagingRowData" :columnDefs="columnDefs"/>
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
        let imagingStageId = ref(0);
        let imagingStepId = ref(0);
        let imagingTime = ref(null);
        let imaging = ref([]);
        let formErrors = ref([]);
        const loading = ref(false);
        const imagingRowData = ref([]);
        let imagingItems = ref([
            {
                imaging: '',
                imaging_date: ''
            }
        ]);
        let columnDefs = ref([
                {
                    headerName: 'Sr. No.',
                    valueGetter: 'node.rowIndex + 1',
                    initialWidth: 20,
                },
                { headerName: 'Imaging Date', field: 'description', filter: true },
                { headerName: 'Imaging', field: 'notes' },
            ]);

        const fetchPatientImagingList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/care-plan-development-imaging-imaginglist/${props.patientId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch imaging list');
                }
                loading.value = false;
                const data = await response.json();
                imagingRowData.value = data.data;
            } catch (error) {
                console.error('Error fetching imaging list:', error);
                loading.value = false;
            }
        };

        let submiLabsHealthDataForm = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('number_tracking_imaging_form');
            let formData = new FormData(myForm);
            let formDataObject = {};

            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const saveServicesResponse = await axios.post('/ccm/care-plan-development-numbertracking-imaging', formData);
                    showImagingAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(saveServicesResponse.form_start_time);
                    await fetchPatientImagingList();
                    document.getElementById("number_tracking_imaging_form").reset();
                    setTimeout(() => {
                        showImagingAlert.value = false;
                        imagingTime.value = document.getElementById('page_landing_times').value;
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
                let stepname = 'NumberTracking-Imaging';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                imagingStepId.value = response.data.stepID;
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        const addImagingItem = async () => {
            imagingItems.value.push({
                imaging: '',
                imaging_date: ''
            });
        };

        const removeImagingItem = async (index) => {
            imagingItems.value.splice(index, 1);
        };

        watch(() => props.stageId, (newValue, oldValue) => {
            getStepID(newValue);
        });

        watch(() => showImagingAlert, (newShowImagingAlert, oldShowImagingAlert) => {
                showImagingAlert.value = newShowImagingAlert;
            }
        );

        onBeforeMount(() => {
            fetchPatientImagingList();
        });

        onMounted(async () => {
            try {
                imagingTime.value = document.getElementById('page_landing_times').value;
                getStepID(props.stageId);
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            loading,
            submiLabsHealthDataForm,
            imagingStageId,
            imagingStepId,
            formErrors,
            imagingTime,
            showImagingAlert,
            columnDefs,
            imagingRowData,
            fetchPatientImagingList,
            deleteServices,
            editService,
            imaging,
            imagingItems,
            addImagingItem,
            removeImagingItem,
        };
    }
};
</script>