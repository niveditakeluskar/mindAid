<template>
    <div class="card-title">Text</div>
    <div class="mb-4">
        <div class="form-group" id="template_content">
            <div class="twilo-error"></div>
            <div class="row">
                <div class="col-md-4 form-group mb-3" id="cntctlist">
                    <label>Contact No.<span class="error">*</span></label>
                        <select class="custom-select" name="contact_no" id="text_contact_number">
                            <option value="">Choose Contact Number</option>
                            <option :value="mobval" v-if="mob == 1">{{ mob_number }} (P)</option>
                            <option :value="home_number_value" v-if="home == 1">{{ home_number }} (s)</option>
                        </select>
                    <div class="invalid-feedback" v-if="formErrors.contact_no" style="display: block;">{{ formErrors.contact_no[0] }}</div>  
                </div>
                <div class="col-md-8 form-group mb-3">
                    <label>Template <span class="error">*</span></label>
                        <select name="template" id="text_template_id" class="custom-select show-tick custom-select"
                            v-model="selectedTextTemplate" @change="textScript(selectedTextTemplate)">
                            <option value="0">Select Template</option>
                            <option v-for="textTemplate in textTemplates" :key="textTemplate.id" :value="textTemplate.id">
                                {{ textTemplate.content_title }}
                            </option>
                        </select>
                        <div class="invalid-feedback" v-if="formErrors.template" style="display: block;">{{ formErrors.template[0] }}</div>
                </div>
                <div class='alert alert-warning mt-2 ml-2' v-if="mob == 0 && home == 0">Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div>
            </div>
            <div class="row" id = "textarea">
                <div class="col-md-8 offset-4 form-group mb-3" >
                    <label>Message <span class="error">*</span> </label>
                    <textarea name="message" class="forms-element form-control" id="templatearea_sms" style="height:50px;overflow-y:hidden;">{{ textScripts }}</textarea>  
                    <div class="invalid-feedback" v-if="formErrors.message" style="display: block;">{{ formErrors.message[0] }}</div>
                </div>    
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        formErrors: String,
    },
    data() {
        return {
            selectedTextTemplate: null,
            textTemplates: null,
            textScripts:null,
            valid:null,
            mob_number:null,
            mobval:null,
            mob:null,
            home_number:null,
            home_number_value:null,
            home:null,
            stageId:null,
        };
    },
    mounted() {
        this.fetchTextTemplate();
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
        async fetchTextTemplate() {
                let stageName = 'Text';
				let response1 = await axios.get(`/get_stage_id/${this.moduleId}/${this.componentId}/${stageName}`);
				this.stageId = response1.data.stageID;
            await axios.get(`/org/get_content_scripts/${this.moduleId}/${this.componentId}/${this.stageId}/0/content_template`)
                .then(response => {
                    this.textTemplates = response.data;
                    console.log("textTemplates", response.data);
                    this.selectedTextTemplate = this.textTemplates[(this.textTemplates).length-1].id;
                    this.textScript(this.selectedTextTemplate);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        async textScript(id){
         await axios.get(`/ccm/get-call-scripts-by-id/${id}/${this.patientId}/call-script`)
            .then(response => {
               this.textScripts = response.data.finaldata;
               this.textScripts = this.textScripts.replace(/(<([^>]+)>)/ig, '');
               this.textScripts = this.textScripts.replace(/&nbsp;/g, ' ');
               this.textScripts = this.textScripts.replace(/&amp;/g, '&');
            })
            .catch(error => {
               console.error('Error fetching data:', error);
            });
      },
    },
};
</script>