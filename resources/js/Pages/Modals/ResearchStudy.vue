<!-- ResearchStudy.vue -->
<template>
    <div v-if="isOpen" class="overlay open" @click="closeModal"></div>
    <div v-if="isOpen" class="modal fade open">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Research Study</h4> 
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="alert alert-success" :style="{ display: showAlert ? 'block' : 'none' }">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>data saved successfully! </strong><span id="text"></span>
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
                        <button type="submit" class="btn btn-primary float-right" id="submit-personal-notes" @click="submitResearchStudyForm">Save</button>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" @click="closeModal">Close</button>
          </div>
        </div>
    </div>  
  </template>
<script>
import {
    ref,
    onBeforeMount,
    onMounted,
    watch,
    // Add other common imports if needed
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
    components:{

    },
    
    setup(props) {
      let researchstudyTime = ref(null);
      const research_study_data = ref(null);
        const isOpen = ref(false); 
        const showAlert = ref(false);
        let formErrors = ref([]);
        const loading = ref(false);

        const openModal = () => {
            isOpen.value = true;
        };

        const closeModal = () => {
            isOpen.value = false;
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
                    }, 3000);
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
            this.closeModal();
        }

        onMounted(async () => {
            try {
                research_study_data.value = props.research_study_data;
                researchstudyTime.value = document.getElementById('page_landing_times').value;
            } catch (error) {
                console.error('Error on page load:', error);
            }
            // fetchPersonalNotesForm();
        });
 
        onBeforeMount(() => {

        });


        watch(() => showAlert, (newShowAlert, oldShowAlert) => {
                showAlert.value = newShowAlert;
            }
        );
        
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
        };
    },
};
</script>
  <style>
  /* Modal styles */
  .modal {
  display: none;
  position: fixed;
  background-color: white;
  z-index: 1000;
  margin: 2%;
  opacity: 0;
  transition: opacity 0.3s ease;
  }
  
  /* Style the overlay */
  .overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 999;
  display: none;
  }
  
  /* Show the overlay and modal when modal is open */
  .modal.open {
  display: block;
  opacity: 1;
  }
  
  .overlay.open {
  display: block;
  }
  
  .modal-content {
    overflow-y: auto !important;
    height: auto !important;
    /* height: 800px !important; */
  }
  </style>
  