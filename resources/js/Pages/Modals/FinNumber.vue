<template>
    <div v-if="isOpen" class="modal fade show" >
        <loading-spinner :isLoading="isLoading"></loading-spinner>
	<div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">FIN Number</h4>  
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
                            <form id="fin_number_form" name="fin_number_form" @submit.prevent="submitFinNumberForm">
                                <input type="hidden" name="patient_id" id="hidden_id" :value="patientId">
                                <input type="hidden" name="uid" id="uid" :value="patientId">
                                <input type="hidden" name="module_id" id="page_module_id" :value="moduleId">
                                <input type="hidden" name="component_id" id="page_component_id" :value="componentId">
                                <input type="hidden" name="stage_id" :value="stageid" />
                                <input type="hidden" name="id">
                                <input type="hidden" name="form_name" value="fin_number_form">
                                <input type="hidden" id="timer_runing_status" value="0"> 
                                <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="finnumberTime">
                                <label>FIN Number<span class='error'>*</span></label>
                                <textarea name="fin_number" class="form-control forms-element" v-model="finNumber" ></textarea>
                                <div class="invalid-feedback" v-if="formErrors" style="display: block;"> 
                                    <span :textContent="formErrors.fin_number"></span>
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
                            <button type="submit" class="btn btn-primary float-right ml-2" id="submit-fin-number" @click="submitFinNumberForm" :disabled="(timerStatus == 1) === true ">Save</button>
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
      finNumber : Number,
      patientFinNumberTab:{
        type: Function,
        required: true,
        },
  },
    components: {
 
    },
  setup(props) {
        const finnumberTime = ref(null);
        let timerStatus = ref();
        const finNumber= ref(null);
        const isOpen = ref(false); 
        const showAlert = ref(false);
        let formErrors = ref([]);
        const loading = ref(false);
        let isLoading = ref(false);

        const openModal = () => {
            isOpen.value = true;
            document.body.classList.add('modal-open');
            finnumberTime.value = document.getElementById('page_landing_times').value;
            timerStatus.value = document.getElementById('timer_runing_status').value;
        };

        const closeModal = () => {
            isOpen.value = false;
            document.body.classList.remove('modal-open');
        };
        let submitFinNumberForm = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('fin_number_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                formErrors.value == {};
                const response = await axios.post('/patients/save-patient-fin-number', formData);
                if (response && response.status == 200) {
                    showAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    isLoading.value = false;
                    if(typeof props.patientFinNumberTab === 'function'){
                        props.patientFinNumberTab();
                    } 
                    $(".form_start_time").val(response.data.form_start_time); 
                    document.getElementById("fin_number_form").reset();
                    setTimeout(() => {
                        showAlert.value = false;
                        finnumberTime.value = document.getElementById('page_landing_times').value;
                        closeModal();
                    }, 3000);// Close the modal after 3 seconds (3000 milliseconds)
                    formErrors.value = [];
                }
                isLoading.value = false;
            } catch (error) {
                isLoading.value = false;
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

        onMounted(() => {
            try {
                finnumberTime.value = document.getElementById('page_landing_times').value;
                finNumber.value = props.finNumber;
            } catch (error) {
                console.error('Error on page load:', error);
            }
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
            finnumberTime,
            formErrors,
            finNumber,
            submitFinNumberForm,
            timerStatus,
            isLoading,
        };
    },
};
</script>