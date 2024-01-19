<!-- ModalForm.vue -->
<template>
    <div class="tab-pane fade show active" id="pet_related" role="tabpanel" aria-labelledby="pet-related-allergies-icon-pill">
        <div class="card">  
            <div class="card-header"><h4>Pet_Related</h4></div>
            <form id="allergy_petrelated_form" name="allergy_petrelated_form" @submit.prevent="submitAllergiesForm">
                <div class="alert alert-success" :style="{ display: showAlert ? 'block' : 'none' }">
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
                    <input type="hidden" name="stage_id" :value="petrelatedallergiesStageId"/>
                    <input type="hidden" name="step_id" :value="petrelatedallergiesStepId">
                    <input type="hidden" name="form_name" value="allergy_petrelated_form">
                    <input type="hidden" name="billable" value="1">
                    <input type="hidden" name="allergy_type" value="petrelated">
                    <input type="hidden" name="hid" class="hid" value='1'>
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="petrelatedallergiesTime">
                    
                    <div class="col-md-12 form-group mt-3">
						<input type="hidden" name="noallergymsg" id="noallergymsg" value="No Known Pet_Related Allergies">   
                        
                        <label :for="`${sectionName}_noallergiescheckbox`" class="checkbox  checkbox-primary mr-3">
                            <input type="checkbox" name="allergy_status"
                                :id="`${sectionName}_noallergiescheckbox`" class="noallergiescheckbox" formControlName="checkbox" :value="1"
                                v-model="conditionRequirnment1" @change="checkConditionRequirnments()"  :checked="conditionRequirnment1">
                            <span>No Known Pet_Related Allergies</span>
                            <div id="msg" style="color:red; display:none">Please delete all data from below table to enable checkbox!</div>  
                            <span class="checkmark"></span>
                        </label>

                        <input type = "hidden" id="PetRelatedcount" value =""> 
                        <DrugForm />
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn  btn-primary m-1" id="save_allergy_petrelated_form">Save</button>
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
                        <table id="PetRelated-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
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
                        </table>
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
    onBeforeMount,
    onMounted,
    AgGridVue,
    // Add other common imports if needed
} from '../../commonImports';
import DrugForm from '../../Patients/Components/AllergiesShortForm.vue';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    components: {
        DrugForm,
    },
   setup() {
        let showDMEAlert = ref(false);
        let petrelatedallergiesStageId = ref(0);
        let petrelatedallergiesStepId = ref(0);
        let petrelatedallergiesTime = ref(0);

        let submitAllergiesForm = async () => {
            let myForm = document.getElementById('allergy_petrelated_form');
            let formData = new FormData(myForm);
            let formDataObject = {};
            formData.forEach((value, key) => {
                formDataObject[key] = value;
            });
            const saveAllergiesResponse = await saveAllergies(formDataObject);
            console.log("in dme saveAllergiesResponse", saveAllergiesResponse);
        }

        let getStageID = async () => {
            try {
                let allergiesStageName = 'Patient_Data';
                let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${allergiesStageName}`);
                petrelatedallergiesStageId = response.data.stageID;
                console.log("petrelatedallergiesStageId", petrelatedallergiesStageId);
                getStepID(petrelatedallergiesStageId);
            } catch (error) {
                console.error('Error fetching Allergies stageID:', error);
            }
        };

        let getStepID = async (sid) => {
            try {
                let stepname = 'Allergies-Dme';
                let response = await axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${sid}/${stepname}`);
                petrelatedallergiesStepId = response.data.stepID;
                console.log("petrelatedallergiesStepId", petrelatedallergiesStepId);
            } catch (error) {
                throw new Error('Failed to fetch stageID');
            }
        };

        onBeforeMount(() => {
            getStageID();
        });

        onMounted(async () => {
            try {
                petrelatedallergiesTime.value = document.getElementById('page_landing_times').value;
                console.log("petrelatedallergiesTime time", petrelatedallergiesTime);
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        return {
            submitAllergiesForm,
            petrelatedallergiesTime,
            petrelatedallergiesStageId,
            petrelatedallergiesStepId,
        };
    }
};
</script>