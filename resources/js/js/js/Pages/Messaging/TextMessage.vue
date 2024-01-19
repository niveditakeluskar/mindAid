<template>
   <div class="mb-3" id ="txt-msg">
      <div class="row" v-if="valid == 1">
         <div class="col-md-6"><label>Contact No. <span class="error">*</span></label>
            <select class="forms-element custom-select" name="phone_no" id="contact_number">
               <option value="">Choose Contact Number</option>
               <option :value="mobval" v-if="mob == 1">{{ mob_number }} (P)</option>
               <option :value="home_number_value" v-if="home == 1">{{ home_number }} (s)</option>
            </select>
            <div class="invalid-feedback" v-if="formErrors.phone_no" style="display: block;">{{ formErrors.phone_no[0] }}</div>
         </div>
         <div class="col-md-6"><label>Template Name <span class="error">*</span></label>
            <select id="ccm_content_title" class="custom-select show-tick custom-select"  name="call_not_answer_template_id" @change="callNotAnsScript(selectedCallNotAnswerdContentScript)" v-model="selectedCallNotAnswerdContentScript">
               <option value="0">Select Template</option>
               <option v-for="callNotAnswerScript in callNotAnswerContentScript" :key="callNotAnswerScript.id" :value="callNotAnswerScript.id" >
               {{ callNotAnswerScript.content_title }}
               </option>
            </select>
         </div>
         <div id=""></div>
         <div class='alert alert-warning mt-2 ml-2' v-if="mob == 0 && home == 0">Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div> <input type='hidden' name='error' value='Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.'>
      </div>
      <div class="row" id="textarea" v-if="valid == 1">
         <div class="col-md-12 form-group mb-3">
            <label><b>Content</b><span class="error">*</span></label>
            <textarea name="text_msg" class="form-control" id="ccm_content_area" style="padding: 5px;width: 47em;min-height: 5em;overflow: auto;height: 87px;}">{{ callNotScript }}</textarea>
            <div class="invalid-feedback" v-if="formErrors.text_msg" style="display: block;">{{ formErrors.text_msg[0] }}</div>
         </div>
      </div>
      <div class='alert alert-warning mt-2 ml-2' v-if="valid == 2">Texting has been disabled for the system</div> <input type='hidden' name='error' value='Texting has been disabled for the system.' />
      <div class='alert alert-warning mt-2 ml-2' v-if="valid == 3">Texting is disabled for Patient's Organization</div> <input type='hidden' name='error' value="Texting is disabled for Patient's Organization." />
      <div class='alert alert-warning mt-2 ml-2' v-if="valid == 0">Patient has not given consent to send text message</div> <input type='hidden' name='error' value='Patient has not given consent to send text message.' />
   </div> 
</template>
<script>
import axios from 'axios';
export default {
	props: {
		patientId: Number,
      moduleId: Number,
      componentId: Number,
      stageId: Number,
      stepId: Number,
      formErrors:String,
	},
	data() {
		return {
			selectedCallNotAnswerdContentScript:0,
         callNotAnswerContentScript:null,
         callNotScript:null,
         valid:null,
         mob_number:null,
         mobval:null,
         mob:null,
         home_number:null,
         home_number_value:null,
         home:null,
		};
	},
	mounted() {
		this.fetchCallNotAnswerContentScript();
      this.fetchMessageContent();
	},
	methods: {
		async fetchMessageContent(){
         await axios.get(`/ccm/get-calltext/${this.moduleId}/${this.patientId}/${this.componentId}/call_message`)
				.then(response => {
					this.valid = response.data.valid;
               this.mob_number = response.data.mob_number;
               this.mobval = response.data.mobval
               this.mob = response.data.mob;
               this.home_number = response.data.home_number;
               this.home_number_value = response.data.home_number_value;
               this.home = response.data.home;
				})
				.catch(error => {
					console.error('Error fetching data:', error);
				});
      },
      async fetchCallNotAnswerContentScript() {
         await axios.get(`/org/get_content_scripts/${this.moduleId}/${this.componentId}/${this.stageId}/${this.stepId}/content_template`)
            .then(response => {
               this.callNotAnswerContentScript = response.data;
               this.selectedCallNotAnswerdContentScript = this.callNotAnswerContentScript[(this.callNotAnswerContentScript).length-1].id;
               this.callNotAnsScript(this.selectedCallNotAnswerdContentScript);
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
	},
};
</script>