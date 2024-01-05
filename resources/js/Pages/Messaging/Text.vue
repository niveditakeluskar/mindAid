<template>
    <div class="alert alert-success" id="success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Text data saved successfully! </strong><span id="text"></span>
    </div>
    <div class="card-title">Text</div>
    <div class="mb-4">
        <div class="form-group" id="template_content">
            <div class="twilo-error"></div>
            <div class="row">
                <div class="col-md-4 form-group mb-3" id="cntctlist">
                    <label>Contact No.<span class="error">*</span>
                        <select class="custom-select" name="contact_no" id="text_contact_number">
                            <option value="">Choose Contact Number</option>
                            <option value="mob">mob</option>
                            <option value="homee">home_number</option>
                        </select>
                    </label>
                    <div class="invalid-feedback"></div>  
                </div>
                <div class="col-md-8 form-group mb-3">
                    <label>Template <span class="error">*</span>
                        <select name="template" id="text_template_id" class="custom-select show-tick select2"
                            v-model="selectedTextTemplate">
                            <option value="">Select Template</option>
                            <option v-for="textTemplate in textTemplates" :key="textTemplate.id" :value="textTemplate.id">
                                {{ textTemplate.content_title }}
                            </option>
                        </select>
                        <!-- @selectcontentscript("template",getPageModuleName(),getPageSubModuleName(),getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Text'), '',["id"=>"text_template_id","class"=>"custom-select"]) -->
                    </label>
                </div>
                <div class='alert alert-warning mt-2 ml-2'>Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div>
            </div>
            <div class="row" id = "textarea">
                <div class="col-md-8 offset-4 form-group mb-3" >
                    <label>Message <span class="error">*</span>
                    <textarea name="message" class="forms-element form-control" id="templatearea_sms"></textarea>  
                    </label>
                    <div class="invalid-feedback"></div>
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
    },
    data() {
        return {
            selectedTextTemplate: null,
            textTemplates: null,
        };
    },
    mounted() {
        this.fetchTextTemplate();
    },
    methods: {
        async fetchTextTemplate() {
            await axios.get(`/org/get_content_scripts/${this.moduleId}/${this.componentId}/12/0/content_template`)
                .then(response => {
                    this.textTemplates = response.data;
                    console.log("textTemplates", response.data);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
    },
};
</script>