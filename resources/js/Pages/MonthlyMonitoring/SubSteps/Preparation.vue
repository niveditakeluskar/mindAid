<template>
<!--    <form id="call_preparation_preparation_followup_form" name="call_preparation_preparation_followup_form"
      action="{{ route('monthly.monitoring.call.preparation') }}" method="post"> -->
      <form id="call_preparation_preparation_followup_form" @submit.prevent="saveForm">
      <div class="row call mb-4 ">
         <div class="col-lg-12 mb-4 ">
            <div class="card">
               <div class="card-body">
                  <div class="alert alert-success" id="success-alert" style="display: none;">
                     <button type="button" class="close" data-dismiss="alert">x</button>
                     <strong>Call Preparation Completed! </strong><span id="text"></span>
                  </div>
                  <div class="card-title">Call Preparation</div>
                  <input type="hidden" name="uid" :value="patientId"/>
                  <input type="hidden" name="patient_id" :value="patientId" /> <!-- Bind patientId to the input field -->
                  <input type="hidden" name="module_id" :value="moduleId" /> <!-- Bind moduleId to the input field -->
                  <input type="hidden" name="component_id" :value="componentId" /> <!-- Bind componentId to the input field -->      
                  <input type="hidden" name="start_time" value="00:00:00">
                  <input type="hidden" name="end_time" value="00:00:00">
                  <input type="hidden" :name="sectionName" v-model="sectionName" />
                  <input type="hidden" name="form_name" value="call_preparation_followup_form">
                  <input type="hidden" name="stage_id" :value="stageid">
                  <input type="hidden" name="step_id" value="0">
                  <input type="hidden" name="_token" :value="csrfToken" />
                  <PreparationForm :sectionName="sectionName" :patientId="patientId" />
               </div>
               <div class='loadscreen' id="preloader" v-show="isLoading">
        <div class="loader "><!-- spinner-bubble spinner-bubble-primary -->
            <img src="/images/loading.gif" width="150" height="150">
        </div>
    </div> <!-- Pre Loader end  -->
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
      </div>
   </form>
</template>
<style>
/* Your spinner CSS */
.spinner {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 50px; /* Adjust size as needed */
}

.spinner-inner {
  border: 3px solid #ccc; /* Spinner color */
  border-top: 3px solid #3498db; /* Spinner color */
  border-radius: 50%;
  width: 20px; /* Spinner size */
  height: 20px; /* Spinner size */
  animation: spin 1s linear infinite; /* Spinner animation */
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<script>
import PreparationForm from '../Components/PreparationFollowUpForm.vue';
import { defineComponent } from 'vue';
import axios from 'axios';
// import stepWizard from 'js/app.js';
export default {

   props: {
    patientId: {
      type: Number,
      required: true
    },
    moduleId: {
      type: Number,
      required: true
    },
    componentId: {
      type: Number,
      required: true
    },
    stageid:{
      type: Number,
      required: true
    },
  },
   data() {
      return {
         sectionName: 'call_preparation',
         csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
         isLoading: false // Initially hide the spinner
      };
   },
   components: {
      PreparationForm
   }, 
   mounted() {
  },
  methods: {
    saveForm() {
      this.isLoading = true;
      const formData = new FormData(this.$refs.form); // Create FormData object

      formData.append('_token', this.csrfToken);
      axios.post('/ccm/monthly-monitoring-call-preparation-form', formData)
        .then(response => {
          // Handle success response
          console.log('Form saved successfully', response.data);
          // Optionally, perform any additional actions on successful form submission
        })
        .catch(error => {
          // Handle error response
          console.error('Error saving form', error);
          // Optionally, perform any actions on failed form submission
        })
        .finally(() => {
          // Hide the spinner when the request is complete (success or failure)
          this.isLoading = false;
        });
    },
  }
};
</script>
