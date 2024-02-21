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
                    <input type="hidden" name="id" id="allergies_id"> 
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="drugallergiesTime">
                    
                    <input type="hidden" name="noallergymsg" value="No Known Drug Allergies">
                    <label class="checkbox noAllergiesLbl"  style="z-index: 1;"> 
                        <!-- @click="hideShowNKDAMsg('drugcount','msg');" -->
                    No Known Drug Allergies
                    <input type="checkbox" v-model="allergyStatus"  name="allergy_status" @change="noAllergiesCheck" style="position: absolute; z-index: -1;">
                    <div id="msg" style="color:red; display:none">Please delete all data from below table to enable checkbox!</div>
                    <span class="checkmark"></span>
                    </label>

                    <input type = "hidden" id="drugcount" :value="drugAllergiesRowCount"> 
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
                        <AgGridTable :rowData="drugAllergiesRowData" :columnDefs="drugAllergiescolumnDefs"/>

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
    AgGridTable,
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
        AgGridTable,
    },
    data() {
        return {
        allergyStatus: false, 
        drugAllergiesRowData: [] 
        };
    },
    computed: {
        drugAllergiesRowCount() {
        return this.drugAllergiesRowData.length;
        },
    },
    methods: {
        hideShowNKDAMsg(countid, msgid) { 
            var d = $("#" + countid).val();
            if (d > 0) {
                $("#" + msgid).show();
                setTimeout(function () {
                    $("#" + msgid).hide();
                }, 5000);
            }
        },
        noAllergiesCheck() {
            if (this.allergyStatus) {
                // alert("Checkbox is checked!");
                var form = $('.noallergiescheck').closest('form');
                var formname = "allergy_drug_form";
                this.hideShowNKDAMsg('drugcount', 'msg');
                // alert(formname);
                $("form[name='" + formname + "'] input[name='specify']").val("");
                $("form[name='" + formname + "'] input[name='type_of_reactions']").val("");
                $("form[name='" + formname + "'] input[name='severity']").val("");
                $("form[name='" + formname + "'] input[name='course_of_treatment']").val("");
                $("form[name='" + formname + "'] textarea[name='notes']").val("");

                $("form[name='" + formname + "'] input[name='specify']").attr("disabled", 'disabled');
                $("form[name='" + formname + "'] input[name='type_of_reactions']").prop("disabled", true);
                $("form[name='" + formname + "'] input[name='severity']").prop("disabled", true);
                $("form[name='" + formname + "'] input[name='course_of_treatment']").prop("disabled", true);
                $("form[name='" + formname + "'] textarea[name='notes']").prop("disabled", true);
            } else {
                // alert("Checkbox is unchecked!");
                var form = $('.noallergiescheck').closest('form');
                var formname = "allergy_drug_form";
                $("form[name='" + formname + "']")[0].reset();
                $("form[name='" + formname + "'] input[name='specify']").prop("disabled", false);
                $("form[name='" + formname + "'] input[name='type_of_reactions']").prop("disabled", false);
                $("form[name='" + formname + "'] input[name='severity']").prop("disabled", false);
                $("form[name='" + formname + "'] input[name='course_of_treatment']").prop("disabled", false);
                $("form[name='" + formname + "'] textarea[name='notes']").prop("disabled", false);
            }
        },
    },
    setup(props) {
        let showDurgAlert = ref(false);
        let drugallergiesStageId = ref(0);
        let drugallergiesStepId = ref(0);
        let drugallergiesTime = ref(null);
        let formErrors = ref([]);
        const loading = ref(false);
       
        const drugAllergiesRowData = ref([]);
        const drugAllergiescolumnDefs = ref([
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
                        if (row && row.users && row.users.f_name) {
                            return row.users.f_name + ' ' + (row.users.l_name || ''); // Added a check for l_name as well
                        } else {
                            return 'N/A';
                        }
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
            ]); 
       
        const fetchPatientDrugList = async () => {
            try { //debugger;
                loading.value = true;
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const baseUrl = window.location.origin;
                const sPageURL = window.location.pathname;
                const parts = sPageURL.split("/");
                const mm = parts[parts.length - 2];
                const response = await fetch(`${baseUrl}/ccm/allergies/${props.patientId}/drug`);
                if (!response.ok) {
                    throw new Error('Failed to fetch followup task list');
                }
                loading.value = false;
                const data = await response.json();
                drugAllergiesRowData.value = data.data;
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
                    await fetchPatientDrugList();
                    // this.fetchPatientMedicationList();
                    // document.getElementById("allergy_drug_form").reset();
                    var form = $("#allergy_drug_form")[0];
                    form.reset();
                    $(form).find(':input').prop('disabled', false);
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

        let deleteAllergies = async (id, obj) => {
             if (window.confirm("Are you sure you want to delete this Allergies?")) {
                const formData = {
                    id: id,
                    uid: props.patientId,
                    patient_id: props.patientId,
                    module_id: props.moduleId,
                    component_id: props.componentId,
                    stage_id: props.stageId,
                    step_id: drugallergiesStepId.value,
                    form_name: 'allergy_drug_form',
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
                    await fetchPatientDrugList();
                    document.getElementById("allergy_drug_form").reset();
                    setTimeout(() => {
                        // showDMEAlert.value = false;
                        drugallergiesTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                } catch (error) {
                    console.error('Error deletting record:', error);
                }
            }
        }

        let editAllergy = async (id) => {
            try {
                const allergiesToEdit = drugAllergiesRowData.value.find(allergies => allergies.id == id);
                console.log(allergiesToEdit);
                if (allergiesToEdit) {
                    const form = document.getElementById('allergy_drug_form');
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

        watch(() => showDurgAlert, (newShowDurgAlert, oldShowDurgAlert) => {
                showDurgAlert.value = newShowDurgAlert;
            }
        );

        onBeforeMount(() => {
           
            fetchPatientDrugList();
        });

        onMounted(async () => {
            try {
                drugallergiesTime.value = document.getElementById('page_landing_times').value;
                exposeDeleteAllergies();
                exposeEditAllergiess();
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
            drugAllergiescolumnDefs,
            drugAllergiesRowData,
          
            fetchPatientDrugList,
            deleteAllergies,
            editAllergy,

        };
    }
};
</script>


