<template>
    <div v-if="isOpen" class="modal fade show"  >
        <loading-spinner :isLoading="isLoading"></loading-spinner>
	<div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Personal Notes</h4> 
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
                            <form id="personal_notes_form" name="personal_notes_form" @submit.prevent="submitPersonalNotesForm" :formErrors="formErrors">
                                <input type="hidden" name="patient_id" id="patient_id" :value="patientId">
                                <input type="hidden" name="module_id" id="page_module_id" :value="moduleId">
                                <input type="hidden" name="component_id" id="page_component_id" :value="componentId">
                                <input type="hidden" name="stage_id" :value="stageid" />
                                <input type="hidden" name="id">
                                <input type="hidden" name="form_name" value="personal_notes_form">
                                <input type="hidden" id="timer_runing_status" value="0"> 
                                <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="personalnotesTime">              
                                <label>Personal Notes<span class='error'>*</span></label>
                                <textarea name="personal_notes" class="form-control forms-element" v-model="personal_notes_data"></textarea>
                                <div class="invalid-feedback" v-if="formErrors.personal_notes" style="display: block;">
                                    <span :textContent="formErrors.personal_notes[0]"></span>
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
                            <button type="submit" class="btn btn-primary float-right ml-2" id="submit-personal-notes" @click="submitPersonalNotesForm" :disabled="(timerStatus == 1) === true ">Save</button>
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
        personal_notes_data : String,
    },
    
    setup(props) {
        let personalnotesTime = ref(null);
        let timerStatus = ref();
        let personal_notes_data = ref(null);
        const isOpen = ref(false); 
        const showAlert = ref(false);
        let isLoading = ref(false);
        let formErrors = ref([]);
        const loading = ref(false);
        const openModal = () => {
            isOpen.value = true;
            document.body.classList.add('modal-open');
            personalnotesTime.value = document.getElementById('page_landing_times').value;
            timerStatus.value = document.getElementById('timer_runing_status').value;
        };
        
        let closeModal = () => {
            isOpen.value = false;
            document.body.classList.remove('modal-open');
        };
        
        let submitPersonalNotesForm = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('personal_notes_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                formErrors.value = {};
                const response = await axios.post('/patients/patient-personal-notes', formData);
                if (response && response.status == 200) {
                    showAlert.value = true;
                    isLoading.value = false;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
                    document.getElementById("personal_notes_form").reset();
                    setTimeout(() => {
                        showAlert.value = false;
                        closeModal();
                        personalnotesTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                }
                isLoading.value = false;
            } catch (error) {
                isLoading.value = false;
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
        }

        onMounted(async () => {
            try {
                personalnotesTime.value = document.getElementById('page_landing_times').value;
                personal_notes_data.value = props.personal_notes_data?.static?.personal_notes;
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
            personalnotesTime,
            submitPersonalNotesForm,
            personal_notes_data,
            timerStatus,
            isLoading
        };
    },
};
</script>
