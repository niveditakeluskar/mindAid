<template>
     <loading-spinner :isLoading="isLoading"></loading-spinner>
    <div class="row">
        <div class="col-lg-12 mb-3">
            <form id="text_form" name="text_form"  @submit.prevent="submitTextForm">
                <input type="hidden" name="uid" :value="patientId"/>
                <input type="hidden" name="patient_id" :value="patientId"/>
                <input type="hidden" name="start_time" value="00:00:00">
                <input type="hidden" name="end_time" value="00:00:00">
                <input type="hidden" name="module_id" :value="moduleId"/>
                <input type="hidden" name="component_id" :value="componentId"/>
                <input type="hidden" name="stage_id" :value="stageId"/>
                <input type="hidden" name="step_id" value="0">
                <input type="hidden" name="form_name" value="text_form">
                <input type="hidden" name="content_title" value="text_form">
                <input type="hidden" name="template_type_id" value="2"> 
                <!--input type="hidden" name="timearr[form_start_time]"  class="timearr form_start_time" :value="time"-->
                <input type="hidden" name="timearr[form_start_time]"  class="timearr form_start_time" >
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success" id="success-alert" :style="{ display: showAlert ? 'block' : 'none' }">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>Text data saved successfully! </strong><span id="text"></span>
                        </div>
                       <Text :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :formErrors="formErrors"/>
                    </div>
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" id="save-text" class="btn  btn-primary m-1" :disabled="(timerStatus == 1) === true ">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <TextHistory :patientId="patientId" :moduleId="moduleId"/>
        </div>
    </div>
</template>
<script>
import Text from '../../Messaging/Text.vue';
import TextHistory from '../../Messaging/TextHistory.vue';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    data() {
      return {
         formErrors: {},
         stageId:null,
         showAlert: false,
         timerStatus: null,
         isLoading:false,
      };
    },
    components: {
        Text,
        TextHistory,
    },
    mounted() {
      //this.time = document.getElementById('page_landing_times').value;
      var time = document.getElementById('page_landing_times').value;
      $(".timearr").val(time);
      this.timerStatus = document.getElementById('timer_runing_status').value;
      this.getStageID();
   },
   methods: {
        async getStageID() {
			try {
				let stageName = 'Text';
				let response = await axios.get(`/get_stage_id/${this.moduleId}/${this.componentId}/${stageName}`);
				this.stageId = response.data.stageID;
			} catch (error) {
				throw new Error('Failed to fetch stageID');
			}
		},
        async submitTextForm(){
            this.isLoading = true;
            let myForm = document.getElementById('text_form'); 
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
          try {
				this.formErrors = {};
				const response = await axios.post('/ccm/monthly-monitoring-text', formData);
				if (response && response.status == 200) {
					this.showAlert = true;
                    updateTimer(this.patientId, 1, this.moduleId);
                    const taskMangeResp = await axios.get(`/task-management/patient-to-do/${this.patientId}/${this.moduleId}/list`);
                     $("#toDoList").html(taskMangeResp.data);
                     $('.badge').html($('#count_todo').val());
                    $(".form_start_time").val(response.data.form_start_time);
					setTimeout(() => {
                        var time = document.getElementById('page_landing_times').value;
                        $(".timearr").val(time);
                        //this.time = document.getElementById('page_landing_times').value;
						this.showAlert = false;
					}, 3000);
				}
			} catch (error) {
                this.isLoading = false;
				if (error.response && error.response.status === 422) {
					this.formErrors = error.response.data.errors;
				} else {
					console.error('Error submitting form:', error);
				}
			}
            this.isLoading = false;
      },
    }
};
</script>