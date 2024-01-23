<!-- ModalForm.vue -->
<template>
    <div class="tab-pane fade show active" id="food" role="tabpanel" aria-labelledby="food-allergies-icon-pill">
        <div class="card">  
            <div class="card-header"><h4>food</h4></div>
            <form id="allergy_food_form" name="allergy_food_form" @submit.prevent="submitAllergiesForm">
                <div class="alert alert-success" :style="{ display: showfoodAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> Food Allergies data saved successfully! </strong><span id="text"></span>
                </div>  
                <div class="form-row col-md-12">
                    <input type="hidden" name="uid" :value="patientId"/>
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="start_time" value="00:00:00"> 
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="stage_id" :value="foodallergiesStageId"/>
                    <input type="hidden" name="step_id" :value="foodallergiesStepId">
                    <input type="hidden" name="form_name" value="allergy_food_form">
                    <input type="hidden" name="billable" value="1">
                    <input type="hidden" name="allergy_type" value="food">
                    <input type="hidden" name="hid" class="hid" value='1'>
                    <input type="hidden" name="id" id="allergies_id"> 
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="foodallergiesTime">
                    
                    
					<input type="hidden" name="noallergymsg" id="noallergymsg" value="No Known Food Allergies">   
                        
                    <label :for="`${sectionName}_noallergiescheckbox`" class="checkbox  checkbox-primary mr-3">
                        <input type="checkbox" name="allergy_status"
                            :id="`${sectionName}_noallergiescheckbox`" class="noallergiescheckbox" formControlName="checkbox" :value="1"
                            v-model="conditionRequirnment1" @change="checkConditionRequirnments()"  :checked="conditionRequirnment1">
                        <span>No Known Food Allergies</span>
                        <div id="msg" style="color:red; display:none">Please delete all data from below table to enable checkbox!</div>  
                        <span class="checkmark"></span>
                    </label>

                    <input type = "hidden" id="foodcount" value =""> 
                    <FoodForm :formErrors="formErrors" />
                    
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_allergy_food_form">Save</button>
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
                            id="food-list"
                            class="ag-theme-alpine"
                            :columnDefs="foodAllergiescolumnDefs.value"
                            :rowData="foodAllergiesRowData.value"
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
import FoodForm from '../../Patients/Components/AllergiesShortForm.vue';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageId: Number,
    },
    components: {
        FoodForm,
        AgGridVue,
    },
    setup(props) {
        let showfoodAlert = ref(false);
        let foodallergiesStageId = ref(0);
        let foodallergiesStepId = ref(0);
        let foodallergiesTime = ref(null);
        let formErrors = ref([]);
        const loading = ref(false);
        const loadingCellRenderer = ref(null);
        const loadingCellRendererParams = ref(null);
        const foodAllergiesRowData = reactive({ value: [] });
        const rowModelType = ref(null);
        const cacheBlockSize = ref(null);
        const maxBlocksInCache = ref(null);
        let foodAllergiescolumnDefs = reactive({
            value: [
                {
                    headerName: 'Sr. No.',
                    valueGetter: 'node.rowIndex + 1',
                    initialWidth: 20,
                },
                { headerName: 'Specify', field: 'specify', filter: true },
                { headerName: 'Type of Reactions', field: 'type_of_reactions' },
                { headerName: 'Severity', field: 'severity' },
                { headerName: 'Course of Treatment', field: 'course_of_treatment' },
                { headerName: 'Allergy Status', field: 'allergy_status'},
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
        
        const fetchPatientFoodList = async () => {
            try {
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                 const baseUrl = window.location.origin;
                const response = await fetch(`${baseUrl}/ccm/allergies/${props.patientId}/food`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                loading.value = false;
                const data = await response.json();
                foodAllergiesRowData.value = data.data;
            } catch (error) {
                console.error('Error fetching followup task list:', error);
                loading.value = false;
            }
        };

        
        
        let submitAllergiesForm = async () => {
            formErrors.value = {};
            let myForm = document.getElementById('allergy_food_form');
            let formData = new FormData(myForm);
            let formDataObject = {};

            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            try {
                const saveAllergiesResponse = await saveAllergies(formDataObject);
                console.log("in food saveAllergiesResponse", saveAllergiesResponse);
                    showfoodAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(saveAllergiesResponse.form_start_time);
                    // this.fetchPatientMedicationList();
                    await fetchPatientFoodList();
                    document.getElementById("allergy_food_form").reset();
                    setTimeout(() => {
                        showfoodAlert.value = false;
                        foodallergiesTime.value = document.getElementById('page_landing_times').value;
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
                let stepname = 'Food';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                foodallergiesStepId = response.data.stepID;
                console.log("stepIDstepID", foodallergiesStepId);
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        let deleteAllergies = async (id, obj) => {
             if (window.confirm("Are you sure you want to delete this Allergies?")) {
                const formData = {
                    id: id,
                    uid: props.patientId,
                    patient_id: props.patientId,
                    module_id: props.moduleId,
                    component_id: props.componentId,
                    stage_id: props.stageId,
                    step_id: foodallergiesStepId.value,
                    form_name: 'allergy_food_form',
                    billable: 1,
                    start_time: "00:00:00",
                    end_time: "00:00:00",
                    form_start_time: document.getElementById('page_landing_times').value,
                };
                try {
                    const deleteAllergiesResponse = await deleteAllergiesDetails(formData);
                    // showDMEAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(deleteAllergiesResponse.form_start_time);
                    await fetchPatientFoodList();
                    document.getElementById("allergy_food_form").reset();
                    setTimeout(() => {
                        // showDMEAlert.value = false;
                        foodallergiesTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                } catch (error) {
                    console.error('Error deletting record:', error);
                }
            }
        }

        let editAllergy = async (id) => {
            try {
                const allergiesToEdit = foodAllergiesRowData.value.find(allergies => allergies.id == id);
                console.log(allergiesToEdit);
                if (allergiesToEdit) {
                    const form = document.getElementById('allergy_food_form');
                    form.querySelector('#allergies_id').value = allergiesToEdit.id;
                    form.querySelector('#specify').value = allergiesToEdit.specify;
                    form.querySelector('#type_of_reactions').value = allergiesToEdit.type_of_reactions;
                    form.querySelector('#severity').value = allergiesToEdit.severity;
                    form.querySelector('#course_of_treatment').value = allergiesToEdit.course_of_treatment;
                    form.querySelector('#notes').value = allergiesToEdit.notes;
                    form.scrollIntoView({ behavior: 'smooth' });
                }
            } catch (error) {
                console.error('Error editing allergies:', error);
            }
        };

        const exposeDeleteAllergies = () => {
            window.deleteAllergies = deleteAllergies;
        };

        const exposeEditAllergiess = () => {
            window.editAllergy = editAllergy;
        };

        watch(() => props.stageId, (newValue, oldValue) => {
            getStepID(newValue);
        });

        watch(() => showfoodAlert, (newShowfoodAlert, oldShowfoodAlert) => {
                showfoodAlert.value = newShowfoodAlert;
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
            fetchPatientFoodList();
        });

        onMounted(async () => {
            try {
                foodallergiesTime.value = document.getElementById('page_landing_times').value;
                exposeDeleteAllergies();
                exposeEditAllergiess();
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });
   

        return {
            loading,
            submitAllergiesForm,
            foodallergiesStageId,
            foodallergiesStepId,
            formErrors,
            foodallergiesTime,
            showfoodAlert,
            foodAllergiescolumnDefs,
            foodAllergiesRowData,
            defaultColDef,
            gridOptions,
            fetchPatientFoodList,
            deleteAllergies,
            editAllergy,

        };
    }
};
</script>


