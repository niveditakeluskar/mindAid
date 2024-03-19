<template>
<!--    <form id="call_preparation_preparation_followup_form" name="call_preparation_preparation_followup_form"
      action="{{ route('monthly.monitoring.call.preparation') }}" method="post"> -->
      <loading-spinner :isLoading="isLoading"></loading-spinner>
      <form id="call_preparation_preparation_followup_form" @submit.prevent="submitPrepareForm">
         <div class="row call mb-4 ">
         <div class="col-lg-12 mb-4 ">
            <div class="card">
               <div class="card-body">
                  <div id="preparationAlert"></div>
                   <!-- Display validation errors -->
                  <div class="card-title">Call Preparation</div>
                  <input type="hidden" name="uid" :value="patientId"/>
                  <input type="hidden" name="patient_id" :value="patientId" /> <!-- Bind patientId to the input field -->
                  <input type="hidden" name="module_id" :value="moduleId" /> <!-- Bind moduleId to the input field -->
                  <input type="hidden" name="component_id" :value="componentId" /> <!-- Bind componentId to the input field -->      
                  <input type="hidden" name="start_time" :value="'00:00:00'">
                  <input type="hidden" name="end_time" :value="'00:00:00'">
                  <input type="hidden" :name="sectionName" :value="sectionName" v-model="sectionName" />
                  <input type="hidden" name="form_name" :value="call_preparation_followup_form">
                  <input type="hidden" name="stage_id" :value="stageid">
                  <input type="hidden" name="step_id" value="0">
                  <input type="hidden" name="_token" :value="csrfToken" />
                  <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" >
                  <!--input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="preparationTime"-->
                  <PreparationForm :sectionName="sectionName" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :formErrors="formErrors"/>
                  <!-- @checkConditionRequirnments="checkConditionRequirnments"/> -->
               </div>
               <div class="card-footer">
                  <div class="mc-footer">
                     <div class="row"> 
                        <div class="col-lg-12 text-right">
                           <button type="button" class="btn btn-primary m-1 draft_preparation" @click="callPreparationDraft" :disabled="(timerStatus == 1) === true ">Draft Save</button>
                           <button type="submit" class="btn btn-primary m-1 save_preparation" :disabled="(timerStatus == 1) === true ">Save</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
</template>

<script>
import PreparationForm from '../Components/PreparationFollowUpForm.vue';
import { defineComponent,ref,onMounted  } from 'vue';
import axios from 'axios';
// import stepWizard from 'js/app.js';
export default {
   props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
      stageid: Number,
   },
   data() {
      return {
         sectionName: 'call_preparation',
         csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
         validationErrors: {},
      };
   },
   components: {
      PreparationForm
   },
    setup(props, {emit}) {
      const isLoading = ref(false);
      let preparationTime = ref();
      let timerStatus = ref();
      let formErrors = ref();
      
      onMounted(async () => {
         // this.$emit('checkConditionRequirnments');
            try {
                //preparationTime.value = document.getElementById('page_landing_times').value;
                var time = document.getElementById('page_landing_times').value;
                $(".timearr").val(time);
               const timerStatusElement = document.getElementById('timer_runing_status');
               if (timerStatusElement !== null) {
                  timerStatus.value = timerStatusElement.value;
               }
            } catch (error) {
                console.error('Error on page load:', error);
            }
        });

      const callPreparationDraft = async()=> {
            try {
               isLoading.value = true;
                let myForm = document.getElementById('call_preparation_preparation_followup_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
                const response = await axios.post('/ccm/monthly-monitoring-call-preparation-form-draft', formData);
                if (response && response.status == 200) {
                  $('#preparationAlert').html('<div class="alert alert-success" id="success-alert"><strong>Call Preparation Draft Saved Successfully! </strong> </div>');
                  updateTimer(props.patientId, '1', props.moduleId);
                  $(".form_start_time").val(response.data.form_start_time);
                  //preparationTime.value = document.getElementById('page_landing_times').value;
                setTimeout(function () {
						$('#preparationAlert').html('');
                }, 3000);
                emit('form-submitted');
               }
                isLoading.value = false;
                clearValidationErrors();
            } catch (error) {
               if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                    setTimeout(function () {
						formErrors.value = {};
                }, 3000);
                    console.log(formErrors);
                } else {
                  $('#preparationAlert').html('<div class="alert alert-danger">Error: Something Went wrong! Please try Again.</div>');
                    console.error('Error submitting form:', error);
                    setTimeout(function () {
                      $('#preparationAlert').html('');
                                    }, 3000);
                }
                isLoading.value = false;
            }
        }

       const clearValidationErrors = async () => {
            const invalidFeedback = document.querySelectorAll('.invalid-feedback');
            invalidFeedback.forEach(element => element.innerHTML = '');
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(element => element.classList.remove('is-invalid'));
        }

      const submitPrepareForm = async () => {
            try {
               isLoading.value = true;
               let myForm = document.getElementById('call_preparation_preparation_followup_form');
               let formData = new FormData(myForm);
               axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
               const response = await axios.post('/ccm/monthly-monitoring-call-preparation-form', formData);
               if (response && response.status == 200) {
                  $('#preparationAlert').html('<div class="alert alert-success" id="success-alert"><strong>Call Preparation Completed! </strong> </div>');
                  updateTimer(props.patientId, '1', props.moduleId);
                  $(".form_start_time").val(response.data.form_start_time);
                  //preparationTime.value = document.getElementById('page_landing_times').value;
                  setTimeout(function () {
                      $('#preparationAlert').html('');
                  }, 3000);
                  formErrors.value = {};
                  emit('form-submitted');
               }
                isLoading.value = false;

               //  clearValidationErrors();

            } catch (error) {
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
               //      setTimeout(function () {
					// 	formErrors.value = {};
               //  }, 3000);
               //      console.log(formErrors);
                } else {
                  $('#preparationAlert').html('<div class="alert alert-danger">Error: Something Went wrong! Please try Again.</div>');
                    console.error('Error submitting form:', error);
                    setTimeout(function () {
                      $('#preparationAlert').html('');
                                    }, 3000);
                }
                isLoading.value = false;
            }
        };
        return{
         formErrors,
         preparationTime,
         clearValidationErrors,
         callPreparationDraft,
          submitPrepareForm,
          isLoading,
          timerStatus,
        };
    }
};
</script>

<style>
.error-messages {
  color: red;
  margin-top: 10px;
}
</style>
 