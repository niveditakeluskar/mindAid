<!-- ModalForm.vue -->
<template>
    <div class="tab-pane fade show active" id="dialysis-services" role="tabpanel" aria-labelledby="dialysis-services-icon-pill">
        <div class="card">  
            <div class="card-header"><h4>Dialysis</h4></div>
            <form id="service_dialysis_form" name="service_dialysis_form" @submit.prevent="submitSrvicesForm">
                <div class="alert alert-success" :style="{ display: showDialysisAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Dialysis Services data saved successfully! </strong><span id="text"></span>
                </div>  
                <div class="form-row col-md-12">
                    <input type="hidden" name="uid" :value="patientId"/>
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="start_time" value="00:00:00"> 
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="stage_id" :value="dialysisServicesStageId"/>
                    <input type="hidden" name="step_id" :value="dialysisServicesStepId">
                    <input type="hidden" name="form_name" value="service_dialysis_form">
                    <input type="hidden" name="service_type" value="dialysis">
        		    <input type="hidden" name="hid" class="hid" value='3'>
                    <input type="hidden" name="id" id="service_id">
                    <input type="hidden" name="billable" value="1">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="DialysisServicesTime" />
                    <DialysisForm :formErrors="formErrors" />
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_service_dialysis_form">Save</button>
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
                            id="dialysis-services-list"
                            class="ag-theme-alpine"
                            :columnDefs="columnDefs.value"
                            :rowData="dialysisServiceRowData.value"
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
import DialysisForm from './SubForms/ServicesLongForm.vue';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageId: Number,
    },
    components: {
        DialysisForm,
        AgGridVue,
    },
    setup(props) {
        let showDialysisAlert = ref(false);
        let dialysisServicesStageId = ref(0);
        let dialysisServicesStepId = ref(0);
        let DialysisServicesTime = ref(null);
        let formErrors = ref([]);
        const loading = ref(false);
        const loadingCellRenderer = ref(null);
        const loadingCellRendererParams = ref(null);
        const dialysisServiceRowData = reactive({ value: [] });
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
                { headerName: 'Types', field: 'type', filter: true },
                { headerName: 'Purpose', field: 'purpose' },
                { headerName: 'Company Name', field: 'specify' },
                { headerName: 'Prescribing Provider', field: 'brand' },
                { headerName: 'Frequency', field: 'frequency' },
                { headerName: 'Service Start Date', field: 'service_start_date' },
                { headerName: 'Service End Date', field: 'service_end_date' },
                {
                    headerName: 'Last Modified By',
                    field: 'users.f_name',
                    cellRenderer: function (params) {
                        const row = params.data;
                        return row && row.users.f_name ? row.users.f_name + ' ' + row.users.l_name : 'N/A';
                    },
                },
                { headerName: 'Last Modified On', field: 'updated_at' },
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

        const fetchPatientDialysisServiceList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/ccm/care-plan-development-services-list/${props.patientId}/3`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                loading.value = false;
                const data = await response.json();
                dialysisServiceRowData.value = data.data;
            } catch (error) {
                console.error('Error fetching followup task list:', error);
                loading.value = false;
            }
        };

        let submitSrvicesForm = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('service_dialysis_form');
            let formData = new FormData(myForm);
            let formDataObject = {};

            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            try {
                const saveServicesResponse = await saveServices(formDataObject);
                    showDialysisAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(saveServicesResponse.form_start_time);
                    await fetchPatientDialysisServiceList();
                    document.getElementById("service_dialysis_form").reset();
                    setTimeout(() => {
                        showDialysisAlert.value = false;
                        DialysisServicesTime.value = document.getElementById('page_landing_times').value;
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
                let stepname = 'Service-Dialysis';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                dialysisServicesStepId.value = response.data.stepID;
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };
        
        let deleteServices = async (id, obj) => {
            if (window.confirm("Are you sure you want to delete this Service?")) {
                const formData = {
                    id: id,
                    uid: props.patientId,
                    patient_id: props.patientId,
                    module_id: props.moduleId,
                    component_id: props.componentId,
                    stage_id: props.stageId,
                    step_id: dialysisServicesStepId.value,
                    form_name: 'service_dialysis_form',
                    billable: 1,
                    start_time: "00:00:00",
                    end_time: "00:00:00",
                    form_start_time: document.getElementById('page_landing_times').value,
                };
                try {
                    const deleteServicesResponse = await deleteServiceDetails(formData);
                    // showDialysisAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(deleteServicesResponse.form_start_time);
                    await fetchPatientDialysisServiceList();
                    document.getElementById("service_dialysis_form").reset();
                    setTimeout(() => {
                        // showDialysisAlert.value = false;
                        DialysisServicesTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                } catch (error) {
                    console.error('Error deletting record:', error);
                }
            }
        }

        let editService = async (id) => {
            try {
                const serviceToEdit = dialysisServiceRowData.value.find(service => service.id == id);
                if (serviceToEdit) {
                    const form = document.getElementById('service_dialysis_form');
                    form.querySelector('#service_id').value = serviceToEdit.id;
                    form.querySelector('#type').value = serviceToEdit.type;
                    form.querySelector('#purpose').value = serviceToEdit.purpose;
                    form.querySelector('#specify').value = serviceToEdit.specify;
                    form.querySelector('#brand').value = serviceToEdit.brand;
                    if (serviceToEdit.service_start_date) {
                        const startDate = new Date(serviceToEdit.service_start_date);
                        const formattedStartDate = startDate.toISOString().split('T')[0];
                        form.querySelector('#service_start_date').value = formattedStartDate;
                    }
                    form.querySelector('#frequency').value = serviceToEdit.frequency;
                    if (serviceToEdit.service_end_date) {
                        const endDate = new Date(serviceToEdit.service_end_date);
                        const formattedEndDate = endDate.toISOString().split('T')[0];
                        form.querySelector('#service_end_date').value = formattedEndDate;
                    }
                    form.querySelector('#notes').value = serviceToEdit.notes;
                    form.scrollIntoView({ behavior: 'smooth' });
                }
            } catch (error) {
                console.error('Error editing service:', error);
            }
        };

        const exposeDeleteServices = () => {
            window.deleteServices = deleteServices;
        };

        const exposeEditServices = () => {
            window.editService = editService;
        };

        watch(() => props.stageId, (newValue, oldValue) => {
            getStepID(newValue);
        });

        watch(() => showDialysisAlert, (newShowDialysisAlert, oldShowDialysisAlert) => {
                showDialysisAlert.value = newShowDialysisAlert;
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
            fetchPatientDialysisServiceList();
        });

        onMounted(async () => {
            try {
                DialysisServicesTime.value = document.getElementById('page_landing_times').value;
                exposeDeleteServices();
                exposeEditServices();
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            loading,
            submitSrvicesForm,
            dialysisServicesStageId,
            dialysisServicesStepId,
            formErrors,
            DialysisServicesTime,
            showDialysisAlert,
            columnDefs,
            dialysisServiceRowData,
            defaultColDef,
            gridOptions,
            fetchPatientDialysisServiceList,
            deleteServices,
            editService,
        };
    }
};
</script>