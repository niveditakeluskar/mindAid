<template>
<form :id="`${sectionName}_preparation_followup_form`" :name="`${sectionName}_preparation_followup_form`"  @submit.prevent="submitResearchFollowForm"> 
   <div class="row call mb-4 ">
      <!-- start Solid Bar -->
      <div class="col-lg-12 mb-4 ">
         <div class="card" >
            <div class="card-body"> 
              
               <div class="card-title">Condition Review</div>
               <div id="conditionpreparationAlert"></div>
               <div class="form-row mb-4">
                  <input type="hidden" name="uid" :value="patientId"/>
                  <input type="hidden" name="patient_id" :value="patientId" /> <!-- Bind patientId to the input field -->
                  <input type="hidden" name="module_id" :value="moduleId" /> <!-- Bind moduleId to the input field -->
                  <input type="hidden" name="component_id" :value="componentId" /> <!-- Bind componentId to the input field -->      
                  <input type="hidden" name="start_time" :value="'00:00:00'">
                  <input type="hidden" name="end_time" :value="'00:00:00'">
                  <input type="hidden" :name="sectionName" :value="sectionName" v-model="sectionName" />
                  <input type="hidden" name="form_name" :value="`${sectionName}_preparation_followup_form`" />
                  <input type="hidden" name="stage_id" :value="conditionReviewStageID">
                  <input type="hidden" name="step_id" value="0">
                  <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="preparationTime">
                  <div class="col-md-12 forms-element">
                     <span class="mr-3 mb-4"><b>Call preparation completed?</b></span>
                     <div class="mr-3 d-inline-flex align-self-center"> 
                        <label for="_data_present_in_emr_yes" class="radio radio-primary mr-3">
                           <input type="radio" formControlName="radio" name="data_present_in_emr" :id="`${sectionName}_data_present_in_emr_yes`" v-model="data_present_in_emrYesNO" 
                            :checked = "data_present_in_emrYesNO=='1'" value="1">
                           <span>Yes</span>
                           <span class="checkmark"></span>
                        </label>
                        <label for="_data_present_in_emr_no" class="radio radio-primary mr-3">
                           <input type="radio" formControlName="radio" name="data_present_in_emr" :id="`${sectionName}_data_present_in_emr_no`" v-model="data_present_in_emrYesNO" 
                            :checked = "data_present_in_emrYesNO=='0'" value="0">
                           <span>No</span>
                           <span class="checkmark"></span>
                        </label>
                     </div>
                  </div>
                  <div class="invalid-feedback">office visit</div>
               </div>
               <div id="data_present_in_emr_show">
               <PreparationForm :sectionName="sectionName" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :formErrors="formErrors" />
               </div>
            </div>
            <div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
                        <button type="submit" class="btn btn-primary m-1 save_preparation" id="_save" :disabled="(timeStatus == 1) === true ">Next</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end::form -->
   </div>
</form>
</template>

<script>
import PreparationForm from '../../Components/PreparationFollowUpForm.vue';
import {
   ref,
   onBeforeMount,
   onMounted
} from '../../../commonImports';
import axios from 'axios';

export default {
   props: {
		sectionName: String,
		patientId: Number,
      moduleId: Number,
      componentId: Number,
   },
   components: {
      PreparationForm
   },
   setup(props) {
      const sectionName = 'research_follow_up';
      let preparationTime = ref();
      let timerStatus = ref();
      const isLoading = ref(false);
      let formErrors = ref();
      let conditionReviewStageID  = ref(0);

      const submitResearchFollowForm = async () => {
         isLoading.value = true;
         try {
            let myForm = document.getElementById(`${sectionName}_preparation_followup_form`);
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            const response = await axios.post('/ccm/monthly-monitoring-call-preparation-form', formData);
            if (response && response.status == 200) {
                $('#conditionpreparationAlert').html('<div class="alert alert-success" id="success-alert"><strong>Call Preparation Completed! </strong> </div>');
               updateTimer(props.patientId, '1', props.moduleId);
               $(".form_start_time").val(response.data.form_start_time);
               preparationTime.value = document.getElementById('page_landing_times').value;
               setTimeout(function () {
						$('#conditionpreparationAlert').html('');
                }, 3000);
               }
            isLoading.value = false;
            this.$emit('form-submitted');
         } catch (error) {
            if (error.response && error.response.status === 422) {
               formErrors.value = error.response.data.errors;
               setTimeout(function () {
						formErrors.value = {};
                }, 3000);
               console.log(formErrors);
            } else {
               $('#conditionpreparationAlert').html('<div class="alert alert-danger">Error: Something Went wrong! Please try Again.</div>');
               console.error('Error submitting form:', error);
               setTimeout(function () {
                  $('#conditionpreparationAlert').html('');
               }, 3000);
            }
            isLoading.value = false;
         }
      };

      let getConditionReviewStageID = async () => {
         try {
            let conditionReviewStageName = 'Condition_Review';
            let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${conditionReviewStageName}`);
            conditionReviewStageID.value = response.data.stageID;
         } catch (error) {
            console.error('Error fetching condition review stageID:', error);
         }
      };

      onBeforeMount(async () => {
         await getConditionReviewStageID();
      });

      onMounted(async () => {
         try {
            preparationTime.value = document.getElementById('page_landing_times').value;
            timerStatus.value = document.getElementById('timer_runing_status').value;
         } catch (error) {
            console.error('Error on page load:', error);
         }
      });

      return {
         sectionName,
         preparationTime,
         submitResearchFollowForm,
         isLoading,
         formErrors,
         conditionReviewStageID,
         timerStatus,
      };
   }
};
</script>
