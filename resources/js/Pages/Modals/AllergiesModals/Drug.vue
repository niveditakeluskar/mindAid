<!-- ModalForm.vue -->
<template>
    <div class="tab-pane fade show active" id="drug" role="tabpanel" aria-labelledby="drug-allergies-icon-pill">
        <div class="card">  
            <div class="card-header"><h4>Drug Data</h4></div>
            <form id="allergy_drug_form" name="allergy_drug_form" @submit.prevent="submitAllergiesForm">
                <div class="alert alert-success" :style="{ display: showDurgAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Drug Allergies data saved successfully! </strong><span id="text"></span>
                </div>  
                <div class="form-row col-md-12">
                    <input type="hidden" name="uid" :value="patientId"/>
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="start_time" value="00:00:00"> 
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="stage_id" :value="drugallergiesStageId"/>
                    <input type="hidden" name="step_id" :value="drugallergiesStepId">
                    <input type="hidden" name="form_name" value="allergy_drug_form">
                    <input type="hidden" name="billable" value="1">
                    <input type="hidden" name="allergy_type" value="drug">
                    <input type="hidden" name="hid" class="hid" value='1'>
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="drugallergiesTime">
                    
                    
					<input type="hidden" name="noallergymsg" id="noallergymsg" value="No Known Drug Allergies">   
                        
                    <label :for="`${sectionName}_noallergiescheckbox`" class="checkbox  checkbox-primary mr-3">
                        <input type="checkbox" name="allergy_status"
                            :id="`${sectionName}_noallergiescheckbox`" class="noallergiescheckbox" formControlName="checkbox" :value="1"
                            v-model="conditionRequirnment1" @change="checkConditionRequirnments()"  :checked="conditionRequirnment1">
                        <span>No Known Drug Allergies</span>
                        <div id="msg" style="color:red; display:none">Please delete all data from below table to enable checkbox!</div>  
                        <span class="checkmark"></span>
                    </label>

                    <input type = "hidden" id="drugcount" value =""> 
                    <DrugForm :formErrors="formErrors" />
                    
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_allergy_drug_form">Save</button>
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
                            id="drug-list"
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
                      <!-- <table id="drug-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                            <thead>
                            <tr> 
                                <th>Sr</th>
                                <th>Specify</th>
                                <th>Type of Reactions</th>
                                <th>Severity</th>
                                <th>Course of Treatment</th> 
                                <th>Allergy Status</th>
                                <th>Last Modified By</th>
                                <th>Last Modified On</th>
                                <th>Review</th>
                                <th>Action</th> 
                            </tr>
                            </thead>
                            <tbody>
                            </tbody> 
                        </table> -->
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
    onMounted,
    AgGridVue,
    // Add other common imports if needed
} from '../../commonImports';
import DrugForm from '../../Patients/Components/AllergiesShortForm.vue';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageId: Number,
    },
    components: {
        DrugForm,
    },
    setup(props) {
        let showDurgAlert = ref(false);
        let drugallergiesStageId = ref(0);
        let drugallergiesStepId = ref(0);
        let drugallergiesTime = ref(null);
        let formErrors = ref([]);
        const loading = ref(false);
        const loadingCellRenderer = ref(null);
        const loadingCellRendererParams = ref(null);
        const rowData = reactive({ value: [] });
        const maxBlocksInCache = ref(null);
        let columnDefs = reactive({
            value: [
                {
                    headerName: 'Sr. No.',
                    valueGetter: 'node.rowIndex + 1',
                },
                { headerName: 'Specify', field: 'specify', filter: true },
                { headerName: 'Type of Reactions', field: 'type_of_reactions' },
                { headerName: 'Severity', field: 'severity' },
                { headerName: 'Course of Treatment', field: 'course_of_treatment' },
                { headerName: 'Allergy Status', field: 'allergy_status'},
                { headerName: 'Last Modified By', field: 'updated_at' },
                { headerName: 'Last Modified On', field: 'updated_at' },
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
        
        const fetchPatientDrugList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`ccm/allergies/${props.patientId}/drug`);
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
        
        let submitAllergiesForm = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('allergy_drug_form');
            let formData = new FormData(myForm);
            let formDataObject = {};

            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            try {
                const saveAllergiesResponse = await saveAllergies(formDataObject);
                console.log("in durg saveAllergiesResponse", saveAllergiesResponse);
                    showDurgAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(saveAllergiesResponse.form_start_time);
                    // this.fetchPatientMedicationList();
                    document.getElementById("allergy_drug_form").reset();
                    setTimeout(() => {
                        showDurgAlert.value = false;
                        drugallergiesTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                // Handle the response here
                formErrors.value = [];
            
            } catch (error) {
                if (error.status && error.status === 422) {
                    formErrors.value = error.responseJSON.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
                console.error("Error in saveAllergies", formErrors.value);
                // Handle the error here
            }
        }

        let getStepID = async (sid) => {
            try {
                let stepname = 'Drug';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                drugallergiesStepId = response.data.stepID;
                console.log("stepIDstepID", drugallergiesStepId);
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        watch(() => props.stageId, (newValue, oldValue) => {
            getStepID(newValue);
        });

        watch(() => showDurgAlert, (newShowDurgAlert, oldShowDurgAlert) => {
                showDurgAlert.value = newShowDurgAlert;
            }
        );

        onMounted(async () => {
            try {
                drugallergiesTime.value = document.getElementById('page_landing_times').value;
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });
   

        return {
            loading,
            submitAllergiesForm,
            drugallergiesStageId,
            drugallergiesStepId,
            formErrors,
            drugallergiesTime,
            showDurgAlert,
            columnDefs,
            rowData,
            defaultColDef,
            gridOptions,
            fetchPatientDrugList,
        };
    }
};
</script>


