<template>
<!--    <form id="call_preparation_preparation_followup_form" name="call_preparation_preparation_followup_form"
      action="{{ route('monthly.monitoring.call.preparation') }}" method="post"> -->
      <loading-spinner :isLoading="isLoading"></loading-spinner>
      <form id="call_preparation_preparation_followup_form" @submit.prevent="saveForm">
         <div class="row call mb-4 ">
         <div class="col-lg-12 mb-4 ">
            <div class="card">
               <div class="card-body">
                     <div class="alert alert-success" id="success-alert" :style="{ display: showAlert ? 'block' : 'none' }">
                     <button type="button" class="close" data-dismiss="alert">x</button>
                     <strong>Call Preparation Completed! </strong><span id="text"></span>
                  </div>
                   <!-- Display validation errors -->
    <div v-if="hasValidationErrors" class="error-messages">
      <ul>
        <li v-for="(errorMessage, field) in validationErrors" :key="field">
          {{ errorMessage }}
        </li>
      </ul>
      </div>
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
import { defineComponent } from 'vue';
import axios from 'axios';
// import stepWizard from 'js/app.js';
export default {
   props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
   },
   data() {
      return {
         time:null,
         isLoading: false,
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
  methods: {
    saveForm() {
      this.clearValidationErrors();
      this.isLoading = true;
      const formData = new FormData();
      const formElements = document.getElementById('call_preparation_preparation_followup_form').elements;

      for (let i = 0; i < formElements.length; i++) {
        const element = formElements[i];
        // Check if the element is not a button or any other unwanted type
        if (element.tagName !== 'BUTTON' && element.type !== 'button') {
          formData.append(element.name, element.value);
        }
      }

      formData.append('_token', this.csrfToken);

      axios.post('/ccm/monthly-monitoring-call-preparation-form', formData)
        .then(response => {
         this.clearValidationErrors();
         alert("submitted successfully");
          // Handle success response
          console.log('Form saved successfully', response.data);
          // Optionally, perform any additional actions on successful form submission
        })
        .catch(error => {
          this.isLoading = false;

          // Check if the error response contains validation errors
          if (error.response && error.response.status === 422 && error.response.data.errors) {
            // Display validation errors
            this.validationErrors = error.response.data.errors;
          } else {
            alert("Oopz!. Something went Wrong, Please Contact Adminstrator");
            // Handle other types of errors (not validation errors)
            console.error('Error saving form', error);
            // Optionally, perform any actions on failed form submission
          }
        })
        .finally(() => {
          // Hide the spinner when the request is complete (success or failure)
          this.isLoading = false;
        });
    }, clearValidationErrors() {
    this.validationErrors = {};
  },
  },
  computed: {
    hasValidationErrors() {
      return Object.keys(this.validationErrors).length > 0;
    },
  },
};
</script>

<style>
.error-messages {
  color: red;
  margin-top: 10px;
}
</style>
