<template>    
    <div v-if="isOpen" class="modal fade show" >
	<div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Research Study</h4> 
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="alert alert-success col-md-10 ml-2" :style="{ display: showAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>Data saved successfully!   </strong><span id="text"></span>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <form id="part_of_research_study_form" name="part_of_research_study_form" @submit.prevent="submitResearchStudyForm" :formErrors="formErrors">
                            <input type="hidden" name="patient_id" id="patient_id" :value="patientId">
                            <input type="hidden" name="module_id" id="page_module_id" :value="moduleId">
                            <input type="hidden" name="component_id" id="page_component_id" :value="componentId">
                            <input type="hidden" name="stage_id" :value="stageid" />
                            <input type="hidden" name="id">
                            <input type="hidden" name="form_name" value="part_of_research_study_form">
                            <input type="hidden" id="timer_runing_status" value="0"> 
                            <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="researchstudyTime">
                            <label>Part of Research Study<span class='error'>*</span></label>
                            <textarea name="part_of_research_study" class="form-control forms-element" v-model="research_study_data"></textarea>
                            <div class="invalid-feedback" v-if="formErrors.part_of_research_study" style="display: block;">
                                <span :textContent="formErrors. part_of_research_study[0]"></span>
                            </div>
                        </form>
                    </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="submit" class="btn btn-primary float-right ml-2" id="submit-personal-notes" @click="submitResearchStudyForm" :disabled="(timerStatus == 1) === true ">Save</button>
                            <button type="button" class="btn btn-default" @click="closeModal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    </div>  
  </template>
<script>
import {
    ref,
    onMounted,
    watch,
} from '../commonImports';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageid: Number,
        research_study_data : String,
    },
    setup(props) {
        let researchstudyTime = ref(null);
        let timerStatus = ref();
        let research_study_data = ref(null);
        const isOpen = ref(false); 
        const showAlert = ref(false);
        let formErrors = ref([]);
        const loading = ref(false);

        const openModal = () => {
            isOpen.value = true;
            document.body.classList.add('modal-open');
            researchstudyTime.value = document.getElementById('page_landing_times').value;
            timerStatus.value = document.getElementById('timer_runing_status').value;
        };

        let closeModal = () => {
            isOpen.value = false;
            document.body.classList.remove('modal-open');
        };
        
        let submitResearchStudyForm = async () => {
            let myForm = document.getElementById('part_of_research_study_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                formErrors.value = {};
                const response = await axios.post('/patients/patient-research-study', formData);
                if (response && response.status == 200) { 
                    showAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
                    document.getElementById("part_of_research_study_form").reset();
                    setTimeout(() => {
                        showAlert.value = false;
                        researchstudyTime.value = document.getElementById('page_landing_times').value;
                        closeModal(); 
                    }, 3000);// Close the modal after 3 seconds (3000 milliseconds)
                    formErrors.value = [];
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                    setTimeout(function () {
						formErrors.value = {};
                }, 3000);
                } else {
                    console.error('Error submitting form:', error);
                }
            }
        }

        onMounted(async () => {
            try {
                research_study_data.value = props.research_study_data?.static?.part_of_research_study;
                researchstudyTime.value = document.getElementById('page_landing_times').value;
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

        watch(() => showAlert, (newShowAlert, oldShowAlert) => {
            showAlert.value = newShowAlert;
        });
        
        return {
            loading,
            isOpen,
            openModal,
            closeModal,
            showAlert,
            formErrors,
            research_study_data,
            researchstudyTime,
            submitResearchStudyForm,
            timerStatus,
        };
    },
};
</script>
