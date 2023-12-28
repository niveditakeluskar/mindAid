<template>
   <div class="tsf-step-content">
      <div class="row">
         <div class="col-lg-12 mb-3">
            <form id="callstatus_form" name="callstatus_form" action="" method="post"> 
               <input type="hidden" name="uid" />
               <input type="hidden" name="patient_id" />
               <input type="hidden" name="start_time" value="00:00:00"> 
               <input type="hidden" name="end_time" value="00:00:00">
               <input type="hidden" name="module_id" />
               <input type="hidden" name="component_id" />
               <input type="hidden" name="stage_id" />
               <input type="hidden" name="template_type_id" value="2">
               <input type="hidden" name="form_name" value="callstatus_form">
               <input type="hidden" name="call_answered_step_id" >
               <input type="hidden" name="call_notanswered_step_id" >
               <input type="hidden" name="content_title">
               <input type="hidden" name="call_not_text_message">
               <input type="hidden" name="billable" value="1">
               <input type="hidden" name="hourtime" id="hourtime">
               <div class="card">
                  <div class="alert alert-success" id="success-alert" style="display: none;">
                     <button type="button" class="close" data-dismiss="alert">x</button>
                     <strong>Call data saved successfully! </strong><span id="text"></span>
                  </div>
                  <div class="twilo-error"></div>
                  <div class=" card-body">
                     <div class="row">
                           <div class="col-md-3">
                              <span class=" forms-element">
                                 <div class="form-row">
                                    <span class="col-md-8 float-left">Call Answered</span>
                                    <label for="answered" class="radio radio-primary col-md-4">
                                       <input type="radio" name="call_status" value="1" formControlName="radio" id="answered" v-model="callStatus" />
                                       <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-row">
                                    <span class="col-md-8 float-left">Call Not Answered</span>
                                    <label for="not_answered" class="radio radio-primary col-md-4">
                                       <input type="radio" name="call_status" value="2" formControlName="radio" id="not_answered" v-model="callStatus" />
                                       <span class="checkmark"></span>
                                    </label>
                                 </div>
                              </span>
                              <div class="form-row invalid-feedback"></div>
                           </div>
                           <div v-if="callStatus == 1"  class="col-md-8" id="callAnswer">
                              <select name="call_answer_template" class="custom-select show-tick select2" v-model="selectedCallAnswerdContentScript">
                                 <option value="">Select Template</option>
                                 <option v-for="callAnswerScript in callAnswerContentScript" :key="callAnswerScript.id" :value="callAnswerScript.id">
                                 {{ callAnswerScript.content_title }}
                                 </option>
                              </select>
                              <span><br/>
                                 <h6>Introduction Script</h6>
                                 <div class="call_answer_template"></div>
                                 <textarea hidden="hidden" name="call_answer_template" class="form-control call_answer_template" id="call_answer_template"></textarea>
                              </span>
                              <div class="">
                                 <span>Is this good time to talk? <span class="error">*</span></span><br />
                                 <div class="form-row forms-element">
                                    <label class="radio radio-primary col-md-4 float-left">
                                       <input type="radio" id="role1" class="" name="call_continue_status" value="1" formControlName="radio" v-model="callContinueStatus">
                                       <span>Yes</span>
                                       <span class="checkmark"></span>
                                    </label>
                                    <label class="radio radio-primary col-md-4 float-left">
                                       <input type="radio" id="role2" class="" name="call_continue_status" value="0" formControlName="radio" v-model="callContinueStatus">
                                       <span>No</span>
                                       <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-row invalid-feedback"></div>
                              </div>
                              <div class="row mb-3" id="schedule_call_ans_next_call" v-if="callContinueStatus == 0"  >
                                 <div class="col-md-6">
                                    <span>Select Call Follow-up date: </span> 
                                    <input type="date" name="answer_followup_date" id="answer_followup_date" class="forms-element form-control" />
                                    <div id="call_continue_followup_date_error" class="invalid-feedback"></div>
                                 </div>
                                 <div class="col-md-6">
                                    <span>Select Call Follow-up Time:</span>
                                    <input type="time" name="answer_followup_time" id="answer_followup_time" class="forms-element form-control" />
                                 </div>
                              </div>
                           </div>
                           <div v-if="callStatus == 2" class="col-md-6" id="callNotAnswer">
                              <div class="mb-3">
                                 <select class="forms-element custom-select" id="answer" name="voice_mail" v-model="voiceMailAction">
                                    <option value="">Select Voice Mail Action</option>
                                    <option value="1">Left Voice Mail</option>
                                    <option value="2">No Voice Mail</option>
                                    <option value="3">Send Text Message</option>
                                 </select>
                                 <div class="invalid-feedback"></div>
                              </div>
                              <div v-if="voiceMailAction == 1" class="row" id="voicetextarea">
                                 <div class="col-md-12 form-group mb-3">
                                    <select name="voice_scripts_select" class="custom-select show-tick select2" v-model="selectedCallNotAnswerdContentScript">
                                       <option value="">Select Template</option>
                                       <option v-for="callNotAnswerScript in callNotAnswerContentScript" :key="callNotAnswerScript.id" :value="callNotAnswerScript.id">
                                       {{ callNotAnswerScript.content_title }}
                                       </option>
                                    </select>
                                    <span><br/>
                                       <h6>Voice Mail Script</h6>
                                       <div class="voice_mail_template"></div>
                                       <textarea hidden="hidden" name="voice_template" class="form-control voice_mail_template" style="padding: 5px;width: 47em;min-height: 5em;overflow: auto;height: 87px;" id="voice_mail_template"></textarea>
                                    </span>
                                 </div>
                              </div>
                              <SendTextMessage v-if="voiceMailAction == 3" />
                              <div class="mb-3">
                                 <span>Select Call Follow-up date: </span>
                                 <input type="date" name="call_followup_date" id="call_followup_date" class="forms-element form-control" />
                                 <div id="call_followup_date_error" class="invalid-feedback"></div>
                              </div>
                           </div>
                     </div>
                     <div>
                        <hr />
                        <div class="col-12 text-center"><h3>Best Time to contact</h3></div>
                        <ContactTime />  
                     </div>       
                  </div>
                  <div class="card-footer">
                     <div class="mc-footer">
                        <div class="row">
                           <div class="col-lg-12 text-right" id="call-save-button" ></div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
            <CallHistory :patientId="patientId" />
         </div>
      </div>
   </div>
</template>
<script>
import CallHistory from '../../../Messaging/CallHistory.vue';
import SendTextMessage from '../../../Messaging/TextMessage.vue';
import ContactTime from '../../../../../laravel/js/components/ContactTime.vue';
import axios from 'axios';

export default {
   props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
   },
   data() {
      return {
         callAnswerContentScript: null,
         selectedCallAnswerdContentScript: null,
         callNotAnswerContentScript: null,
         selectedCallNotAnswerdContentScript: null,
         callStatus: null,
         voiceMailAction: null,
      };
   },
   components: {
      CallHistory,
      SendTextMessage,
      ContactTime,
   },
   mounted() {
      this.fetchCallAnswerContentScript();
      this.fetchCallNotAnswerContentScript();
   },
   methods: {
      async fetchCallAnswerContentScript() {
         await axios.get(`/org/get_content_scripts/${this.moduleId}/${this.componentId}/9/11/content_template`)
            .then(response => {
               this.callAnswerContentScript = response.data;
            })
            .catch(error => {
               console.error('Error fetching data:', error);
            });
      },
      async fetchCallNotAnswerContentScript() {
         await axios.get(`/org/get_content_scripts/${this.moduleId}/${this.componentId}/9/9/content_template`)
            .then(response => {
               this.callNotAnswerContentScript = response.data;
            })
            .catch(error => {
               console.error('Error fetching data:', error);
            });
      },
   },
};
</script>