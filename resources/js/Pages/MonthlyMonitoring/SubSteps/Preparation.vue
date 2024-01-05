<template>
<form id="call_preparation_preparation_followup_form" name="call_preparation_preparation_followup_form" action="{{ route('monthly.monitoring.call.preparation') }}" method="post" > 
   <div class="row call mb-4 ">
      <div class="col-lg-12 mb-4 ">
         <div class="card" >
            <div class="card-body"> 
               <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call Preparation Completed! </strong><span id="text"></span>
               </div>
               <div class="card-title">Call Preparation</div>
               <input type="hidden" name="uid" />
               <input type="hidden" name="patient_id" />
               <input type="hidden" name="module_id" />
               <input type="hidden" name="component_id" />
               <input type="hidden" name="" />
               <input type="hidden" name="form_name" value="call_preparation_followup_form" />
               <input type="hidden" name="stage_id" />
	            <input type="hidden" name="step_id"  value="0">
               <PreparationForm :sectionName="sectionName" />
            </div>
            <div class="card-footer">
               <div class="mc-footer">
                  <div class="row"> 
                     <div class="col-lg-12 text-right">
                        <button type="button" class="btn btn-primary m-1 draft_preparation" sid="draft_call_preparation" id="call_preparation_draft">test Draft Save</button>
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

<script>
   import PreparationForm from '../Components/PreparationFollowUpForm.vue';
// import stepWizard from 'js/app.js';
export default {
   data() {
      return {
         sectionName: 'call_preparation',
      };
   },
   components: {
      PreparationForm
   },
	mounted() {
   
},
};
</script>
