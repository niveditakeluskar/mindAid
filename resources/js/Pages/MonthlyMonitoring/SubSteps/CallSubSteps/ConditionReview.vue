<template>
<form method="post" > 
   <div class="row call mb-4 ">
      <!-- start Solid Bar -->
      <div class="col-lg-12 mb-4 ">
         <div class="card" >
            <div class="card-body"> 
               <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Research Follow up Completed!</strong><span id="text"></span>
               </div>
               <div class="card-title">Condition Review</div>
               <div class="form-row mb-4">
               <div class="card-title">Call Preparation</div>
               <input type="hidden" name="uid" />
               <input type="hidden" name="patient_id" />
               <input type="hidden" name="module_id" />
               <input type="hidden" name="component_id" />
               <input type="hidden" name="" />
               <input type="hidden" name="form_name" value="_preparation_followup_form" />
               <input type="hidden" name="stage_id" />
	            <input type="hidden" name="step_id"  value="0">
                  <div class="col-md-12 forms-element">
                     <span class="mr-3 mb-4"><b>Call preparation completed?</b></span>
                     <div class="mr-3 d-inline-flex align-self-center"> 
                        <label for="_data_present_in_emr_yes" class="radio radio-primary mr-3">
                           <input type="radio" formControlName="radio" name="data_present_in_emr" id="_data_present_in_emr_yes" value="1">
                           <span>Yes</span>
                           <span class="checkmark"></span>
                        </label>
                        <label for="_data_present_in_emr_no" class="radio radio-primary mr-3">
                           <input type="radio" formControlName="radio" name="data_present_in_emr" id="_data_present_in_emr_no" value="0">
                           <span>No</span>
                           <span class="checkmark"></span>
                        </label>
                     </div>
                  </div>
                  <div class="invalid-feedback">office visit</div>
               </div>
               <div id="data_present_in_emr_show">
               <PreparationForm :sectionName="sectionName" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
               </div>
            </div>
            <div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
                        <button type="submit" class="btn btn-primary m-1 save_preparation" id="_save">Next</button>
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
// import stepWizard from 'js/app.js';
export default {
   props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
   },
   data() {
      return {
         sectionName: 'research_follow_up',
      };
   },
   components: {
      PreparationForm
   },
   mounted() {
      // console.log('Component mounted.');
   }
};
</script>
