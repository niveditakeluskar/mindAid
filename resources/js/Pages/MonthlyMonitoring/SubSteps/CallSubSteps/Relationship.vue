<template>
    <div class="card">
        <form id="relationship_form" name="relationship_form" @submit.prevent="submitRelationshipForm"> 
                <div class="card-body">
                    <input type="hidden" name="uid" />
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="start_time" value="00:00:00">
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="hid_stage_id" />
                    <input type="hidden" name="form_name" value="relationship_form">
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="time">
                    <div class="alert alert-success" :style="{ display: showAlert ? 'block' : 'none' }">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong> Relationship data saved successfully! </strong><span id="text"></span>
                    </div>
                    <div v-html="RelationshipQuestionnaire"></div>
                    <div class="alert alert-success" id="success-alert" :style="{ display: showAlert ? 'block' : 'none' }">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong> Relationship data saved successfully! </strong><span id="text"></span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" id="save-question" class="btn btn-primary m-1" :disabled="(timerStatus == 1) === true ">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number
    },
    data() {
        return {
            RelationshipQuestionnaire: null,
            stageId: 0,
            formErrors: {},
			showAlert: false,
            time:null,
            timerStatus:null,
        };
    },
    mounted() {
        this.fetchData();
        this.time = document.getElementById('page_landing_times').value;
        this.timerStatus = document.getElementById('timer_runing_status').value;
    },
    methods: {
        fetchData() {
            axios.get(`/patients/patient-relationship-questionnaire/${this.patientId}/${this.moduleId}/${this.componentId}/patient-relationship-questionnaire`)
                .then(response => {
                    this.RelationshipQuestionnaire = response.data;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        async submitRelationshipForm(){
            let myForm = document.getElementById('relationship_form'); 
            let formData = new FormData(myForm);
          axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
         try {
				this.formErrors = {};
				const response = await axios.post('/ccm/monthly-monitoring-call-relationship', formData);
				if (response && response.status == 200) {
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