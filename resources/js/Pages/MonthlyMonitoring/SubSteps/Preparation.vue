<template>
<!--    <form id="call_preparation_preparation_followup_form" name="call_preparation_preparation_followup_form"
      action="{{ route('monthly.monitoring.call.preparation') }}" method="post"> -->
      <loading-spinner :isLoading="isLoading"></loading-spinner>
      <form id="call_preparation_preparation_followup_form" @submit.prevent="submitPrepareForm">
         <div class="row call mb-4 ">
         <div class="col-lg-12 mb-4 ">
            <div class="card">
               <div class="card-body">
                     <div class="alert alert-success" id="success-alert" :style="{ display: showAlert ? 'block' : 'none' }">
                     <button type="button" class="close" data-dismiss="alert">x</button>
                     <strong>Call Preparation Completed! </strong><span id="text"></span>
                  </div>
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
                  <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="time">
                  <PreparationForm :sectionName="sectionName" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
               </div>
            </div>
            <div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
                        <button type="button" class="btn btn-primary m-1 draft_preparation" sid="draft_call_preparation"
                              id="call_preparation_draft">Draft Save</button>
                        <button type="submit" class="btn btn-primary m-1 save_preparation">Save</button>
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
import { defineComponent,ref } from 'vue';
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
         time:null,
         sectionName: 'call_preparation',
         csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
         validationErrors: {},
      };
   },
   components: {
      PreparationForm
   }, 
   mounted() {
      this.time = document.getElementById('page_landing_times').value;
      console.log('Component mounted.');
   },
    setup(props) {
      const isLoading = ref(false);
      const submitPrepareForm = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('call_preparation_preparation_followup_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await axios.post('/ccm/monthly-monitoring-call-preparation-form', formData);
                if (response && response.status == 200) {
                    alert("Saved Successfully");
                    document.getElementById("call_preparation_preparation_followup_form").reset();
                }
                isLoading.value = false;
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
                isLoading.value = false;
            }
        };
        return{
          submitPrepareForm,
          isLoading,
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
