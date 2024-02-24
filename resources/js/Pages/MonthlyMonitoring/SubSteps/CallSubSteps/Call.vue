<template>
   <div class="tsf-step-content">
      <div class="row">
         <div class="col-lg-12 mb-3">
            <form id="callstatus_form" name="callstatus_form" @submit.prevent="submitCallForm"> 
               <input type="hidden" name="uid" :value="patientId"/>
               <input type="hidden" name="patient_id" :value="patientId"/>
               <input type="hidden" name="start_time" value="00:00:00"> 
               <input type="hidden" name="end_time" value="00:00:00">
               <input type="hidden" name="module_id" :value="moduleId"/>
               <input type="hidden" name="component_id" :value="componentId"/>
               <input type="hidden" name="stage_id" :value="stageId"/>
               <input type="hidden" name="template_type_id" value="2">
               <input type="hidden" name="form_name" value="callstatus_form">
               <input type="hidden" name="call_answered_step_id" :value="callAnsStepId">
               <input type="hidden" name="call_notanswered_step_id" :value="callNotAnsStepId">
               <input type="hidden" name="content_title">
               <input type="hidden" name="call_not_text_message">
               <input type="hidden" name="billable" value="1">
               <input type="hidden" name="hourtime" id="hourtime">
               <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="time">
               <div class="card">
                  <div class="alert alert-success" id="success-alert" :style="{ display: showAlert ? 'block' : 'none' }">
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
                                       <input type="radio" name="call_status" value="1" formControlName="radio" @click="fetchCallAnswerContentScript()" id="answered" v-model="callStatus" />
                                       <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-row">
                                    <span class="col-md-8 float-left">Call Not Answered</span>
                                    <label for="not_answered" class="radio radio-primary col-md-4">
                                       <input type="radio" name="call_status" value="2" formControlName="radio" @click="fetchCallNotAnswerContentScript()" id="not_answered" v-model="callStatus" />
                                       <span class="checkmark"></span>
                                    </label>
                                 </div>
                              </span>
                              <div class="form-row invalid-feedback" v-if="formErrors.call_status" style="display: block;">{{ formErrors.call_status[0] }}</div>
                           </div>
                           <div v-if="callStatus == 1"  class="col-md-8" id="callAnswer">
                              <select name="call_answer_template_id" class="custom-select show-tick select2" >
                                 <option value="">Select Template</option>
                                 <option v-for="callAnswerScript in callAnswerContentScript" :key="callAnswerScript.id" :value="callAnswerScript.id" :selected="callAnswerScript.id == callAnsSelectes">
                                 {{ callAnswerScript.content_title }}
                                 </option>
                              </select>
                              <span><br/>
                                 <h6>Introduction Script</h6>
                                 <div class="call_answer_template"> {{selectedCallAnswerdContentScript}} </div>
                                 <textarea hidden="hidden" name="call_answer_template" class="form-control call_answer_template" id="call_answer_template">{{ selectedCallAnswerdContentScript }}</textarea>
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
                                 <div class="form-row invalid-feedback" v-if="formErrors.call_continue_status" style="display: block;">{{ formErrors.call_continue_status[0] }}</div>
                              </div>
                              <div class="row mb-3" id="schedule_call_ans_next_call" v-if="callContinueStatus == 0"  >
                                 <div class="col-md-6">
                                    <span>Select Call Follow-up date: </span> 
                                    <input type="date" name="answer_followup_date" id="answer_followup_date" class="forms-element form-control" />
                                    <div id="call_continue_followup_date_error" class="invalid-feedback" v-if="formErrors.answer_followup_date" style="display: block;">{{ formErrors.answer_followup_date[0] }}</div>
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
                                    <option value="0" selected>Select Voice Mail Action</option>
                                    <option value="1">Left Voice Mail</option>
                                    <option value="2">No Voice Mail</option>
                                    <option value="3">Send Text Message</option>
                                 </select>
                                 <div class="invalid-feedback" v-if="formErrors.voice_mail" style="display: block;">{{ formErrors.voice_mail[0] }}</div>
                              </div>
                              <div v-if="voiceMailAction == 1" class="row" id="voicetextarea">
                                 <div class="col-md-12 form-group mb-3">
                                    <select name="voice_scripts_select" class="custom-select show-tick select2" @change="callNotAnsScript(selectedCallNotAnswerdContentScript)" v-model="selectedCallNotAnswerdContentScript">
                                       <option value="">Select Template</option>
                                       <option v-for="callNotAnswerScript in callNotAnswerContentScript" :key="callNotAnswerScript.id" :value="callNotAnswerScript.id" >
                                       {{ callNotAnswerScript.content_title }}
                                       </option>
                                    </select>
                                    <span><br/>
                                       <h6>Voice Mail Script</h6>
                                       <div class="voice_mail_template">{{ callNotScript }}</div>
                                       <textarea hidden="hidden" name="voice_template" class="form-control voice_mail_template" style="padding: 5px;width: 47em;min-height: 5em;overflow: auto;height: 87px;" id="voice_mail_template">{{ callNotScript }}</textarea>
                                    </span>
                                 </div>
                              </div>
                              <SendTextMessage v-if="voiceMailAction == 3" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageId="stageId" :stepId="callNotAnsStepId" :formErrors="formErrors"/>
                              <div class="mb-3">
                                 <span>Select Call Follow-up date: </span>
                                 <input type="date" name="call_followup_date" id="call_followup_date" class="forms-element form-control" />
                                 <div id="call_followup_date_error" class="invalid-feedback" v-if="formErrors.call_followup_date" style="display: block;">{{ formErrors.call_followup_date[0] }}</div>
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
                           <div class="col-lg-12 text-right" id="call-save-button" ><button type="submit" class="btn  btn-primary m-1" id="save-callstatus" :disabled="(timerStatus == 1) === true ">Next</button></div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
            <CallHistory :patientId="patientId" v-if="renderComponent " />
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
         callNotScript:null,
         callStatus: null,
         voiceMailAction: 0,
         callAnsSelectes:null,
         callNotAnsSelectes:null,
         callContinueStatus:null,
         formErrors: {},
         stageId:null,
         callAnsStepId:null,
         callNotAnsStepId:null,
         showAlert: false,
         renderComponent : true,
         timerStatus:null,
      };
   },
   components: {
      CallHistory,
      SendTextMessage,
      ContactTime,
   },
   mounted() {
      //this.fetchCallAnswerContentScript();
      //this.fetchCallNotAnswerContentScript();
      this.time = document.getElementById('page_landing_times').value;
      this.timerStatus = document.getElementById('timer_runing_status').value;
      this.getStageID();
   },
   methods: {
      async getStageID() {
			try {
				let stageName = 'Call';
				let response = await axios.get(`/get_stage_id/${this.moduleId}/${this.componentId}/${stageName}`);
				this.stageId = response.data.stageID;
			} catch (error) {
				throw new Error('Failed to fetch stageID');
			}
		},
      async fetchCallAnswerContentScript() {
         setTimeout(() => {
         let stepname1 = 'Call_Answered';
          axios.get(`/get_step_id/${this.moduleId}/${this.componentId}/${this.stageId}/${stepname1}`)
            .then(response => {
            this.callAnsStepId = response.data.stepID;
            this.fetchCallAnswerContentScripts();
            })
            .catch(error => {
                  console.error('Error fetching data:', error);
            })
         }, 500)
      },

      async fetchCallAnswerContentScripts(){
         await axios.get(`/org/get_content_scripts/${this.moduleId}/${this.componentId}/${this.stageId}/${this.callAnsStepId}/content_template`)
               .then(response => {
                  this.callAnswerContentScript = response.data;
                  this.callAnsSelectes = this.callAnswerContentScript[(this.callAnswerContentScript).length-1].id;
                  this.callAnsScript(this.callAnsSelectes);
               })
               .catch(error => {
                  console.error('Error fetching data:', error);
               });
      },

      async fetchCallNotAnswerContentScript() {
         setTimeout(() => {
         let stepname2 = 'Call_Not_Answered';
          axios.get(`/get_step_id/${this.moduleId}/${this.componentId}/${this.stageId}/${stepname2}`)
         .then(response => {
            this.callNotAnsStepId = response.data.stepID;
            this.fetchCallNotAnswerContentScripts();
            })
            .catch(error => {
                  console.error('Error fetching data:', error);
            })
         }, 500)
      },

      async fetchCallNotAnswerContentScripts(){
         await axios.get(`/org/get_content_scripts/${this.moduleId}/${this.componentId}/${this.stageId}/${this.callNotAnsStepId}/content_template`)
            .then(response => {
               this.callNotAnswerContentScript = response.data;
               this.selectedCallNotAnswerdContentScript = this.callNotAnswerContentScript[(this.callNotAnswerContentScript).length-1].id;
               this.callNotAnsScript(this.selectedCallNotAnswerdContentScript);
            })
            .catch(error => {
               console.error('Error fetching data:', error);
            });
      },

      async callAnsScript(id){
         await axios.get(`/ccm/get-call-scripts-by-id/${id}/${this.patientId}/call-script`)
            .then(response => {
               this.selectedCallAnswerdContentScript = response.data.finaldata;
               this.selectedCallAnswerdContentScript = this.selectedCallAnswerdContentScript.replace(/(<([^>]+)>)/ig, '');
               this.selectedCallAnswerdContentScript = this.selectedCallAnswerdContentScript.replace(/&nbsp;/g, ' ');
               this.selectedCallAnswerdContentScript = this.selectedCallAnswerdContentScript.replace(/&amp;/g, '&');
            })
            .catch(error => {
               console.error('Error fetching data:', error);
            });
      },
      async callNotAnsScript(id){
         await axios.get(`/ccm/get-call-scripts-by-id/${id}/${this.patientId}/call-script`)
            .then(response => {
               this.callNotScript = response.data.finaldata;
               this.callNotScript = this.callNotScript.replace(/(<([^>]+)>)/ig, '');
               this.callNotScript = this.callNotScript.replace(/&nbsp;/g, ' ');
               this.callNotScript = this.callNotScript.replace(/&amp;/g, '&');
            })
            .catch(error => {
               console.error('Error fetching data:', error);
            });
      },
      async submitCallForm(){
            let myForm = document.getElementById('callstatus_form'); 
            let formData = new FormData(myForm);
            this.renderComponent = false;
          axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
          try {
				this.formErrors = {};
				const response = await axios.post('/ccm/monthly-monitoring-call-callstatus', formData);
				if (response && response.status == 200) {
               this.renderComponent = true;
					this.showAlert = true;
                    updateTimer(this.patientId, 1, this.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
					setTimeout(() => {
                  this.time = document.getElementById('page_landing_times').value;
						this.showAlert = false;
               }, 3000);
               this.$emit('form-submitted');
				}
			} catch (error) {
				if (error.response && error.response.status === 422) {
					this.formErrors = error.response.data.errors;
				} else {
					console.error('Error submitting form:', error);
				}
			}
      },
   },
};
</script>