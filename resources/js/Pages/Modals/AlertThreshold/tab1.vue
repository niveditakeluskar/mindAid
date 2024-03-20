<template>
    <div class="alert alert-success col-md-10 ml-2" :style="{ display: showAlert ? 'block' : 'none' }">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Data saved successfully! </strong><span id="text"></span>
    </div>
    <div class=""> <!-- small-modal -->
      <!-- Content of the tab goes here -->
        <form class="patient_threshold_form" id="patient_threshold_form"  @submit.prevent="submit_patient_threshold_form" :formErrors="formErrors">
            <div class="row">
                <input type="hidden" name="patient_id" id="patient_id" :value="patientId">
                <input type="hidden" name="module_id" id="page_module_id" :value="moduleId">
                <input type="hidden" name="component_id" id="page_component_id" :value="componentId">
                <input type="hidden" name="stage_id" :value="stageid" />
                <input type="hidden" name="id">
                <input type="hidden" name="form_name" value="patient_threshold_form">
                <input type="hidden" id="timer_runing_status" value="0"> 
                <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" >
                <!-- systolic -->  
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Systolic High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="systolichigh" id="systolichigh" v-model="patient_systolichigh" :value="patient_systolichigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Systolic Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="systoliclow" id="systoliclow" v-model="patient_systoliclow" :value="patient_systoliclow">
                </div>
            <!-- </div>
            <div class="row"> -->
                <!-- diastolic -->
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Diastolic High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="diastolichigh" id="diastolichigh" v-model="patient_diastolichigh" :value="patient_diastolichigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Diastolic Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="diastoliclow" id="diastoliclow" v-model="patient_diastoliclow" :value="patient_diastoliclow">
                </div>
            <!-- </div>
            <div class="row"> -->
                <!-- Heart -->
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Heart Rate High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="bpmhigh" id="bpmhigh" v-model="patient_bpmhigh" :value="patient_bpmhigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Heart Rate Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="bpmlow" id="bpmlow" v-model="patient_bpmlow" :value="patient_bpmlow">
                </div>
            <!-- </div>
            <div class="row"> -->
                <!-- Oxygen -->
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Oxygen Saturatio High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="oxsathigh" id="oxsathigh" v-model="patient_oxsathigh" :value="patient_oxsathigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Oxygen Saturatio Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="oxsatlow" id="oxsatlow" v-model="patient_oxsatlow" :value="patient_oxsatlow">
                </div>
            <!-- </div>
            <div class="row"> -->
                <!-- Glucose -->
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Glucose High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="glucosehigh" id="glucosehigh" v-model="patient_glucosehigh" :value="patient_glucosehigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Glucose Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="glucoselow" id="glucoselow" v-model="patient_glucoselow" :value="patient_glucoselow">
                </div>
            <!-- </div>
            <div class="row"> -->
                <!-- Temperature -->
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Temperature High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="temperaturehigh" id="temperaturehigh" v-model="patient_temperaturehigh" :value="patient_temperaturehigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Temperature Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="temperaturelow" id="temperaturelow" v-model="patient_temperaturelow" :value="patient_temperaturelow">
                </div>
            <!-- </div>
            <div class="row"> -->
                <!-- Weight -->
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Weight High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="weighthigh" id="weighthigh" v-model="patient_weighthigh" :value="patient_weighthigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Weight Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="weightlow" id="weightlow" v-model="patient_weightlow" :value="patient_weightlow">
                </div>
            <!-- </div>
            <div class="row"> -->
                <!-- Spirometer  FEV-->
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Spirometer-FEV High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="spirometerfevhigh" id="spirometerfevhigh" v-model="patient_spirometerfevhigh" :value="patient_spirometerfevhigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Spirometer-FEV Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="spirometerfevlow" id="spirometerfevlow" v-model="patient_spirometerfevlow" :value="patient_spirometerfevlow">
                </div>
            <!-- </div>
            <div class="row"> -->
                <!-- Spirometer  PEF-->
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Spirometer-PEF High <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="spirometerpefhigh" id="spirometerpefhigh" v-model="patient_spirometerpefhigh" :value="patient_spirometerpefhigh">
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label for="practicename">Spirometer-PEF Low <!-- <span style="color:red">*</span> --></label>
                    <input type="text" class="form-control" name="spirometerpeflow" id="spirometerpeflow"  v-model="patient_spirometerpeflow" :value="patient_spirometerpeflow">
                </div>
            </div>
            <!-- <div class="modal-footer"> -->
                <!-- if ($role == 3 || $role == 2 || $role == 5)  -->
                    <button type="button" class="btn btn-primary float-right mb-4" @click="submit_patient_threshold_form()" :disabled="(timerStatus == 1) === true ">Submit</button>
            <!-- </div> -->
        </form>
    </div>
</template>
<script>
import {
    ref,
    onBeforeMount,
    onMounted,
    watch,
    // Add other common imports if needed
} from '../../commonImports';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageid: Number,
        patient_systolichigh:Number,
        patient_systoliclow:Number,
        patient_diastolichigh:Number, 
        patient_diastoliclow:Number, 
        patient_bpmhigh:Number, 
        patient_bpmlow:Number, 
        patient_oxsathigh:Number, 
        patient_oxsatlow:Number, 
        patient_glucosehigh:Number, 
        patient_glucoselow:Number, 
        patient_temperaturehigh:Number, 
        patient_temperaturelow:Number, 
        patient_weighthigh:Number, 
        patient_weightlow:Number, 
        patient_spirometerfevhigh:Number, 
        patient_spirometerfevlow:Number, 
        patient_spirometerpefhigh:Number, 
        patient_spirometerpeflow:Number,
    },

    components: {

    },
    
    setup(props) {
        let alertThresholdTime = ref(null);
        let patient_systolichigh= ref(null);
        let patient_systoliclow= ref(null);
        let patient_diastolichigh= ref(null); 
        let patient_diastoliclow= ref(null); 
        let patient_bpmhigh= ref(null); 
        let patient_bpmlow= ref(null); 
        let patient_oxsathigh= ref(null); 
        let patient_oxsatlow= ref(null); 
        let patient_glucosehigh= ref(null); 
        let patient_glucoselow= ref(null); 
        let patient_temperaturehigh= ref(null); 
        let patient_temperaturelow= ref(null); 
        let patient_weighthigh= ref(null); 
        let patient_weightlow= ref(null); 
        let patient_spirometerfevhigh= ref(null); 
        let patient_spirometerfevlow= ref(null); 
        let patient_spirometerpefhigh= ref(null); 
        let patient_spirometerpeflow= ref(null);
        let timerStatus = ref();
        const showAlert = ref(false);
        let formErrors = ref([]);
        const loading = ref(false);

        let submit_patient_threshold_form = async () => {
            let myForm = document.getElementById('patient_threshold_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                formErrors.value = {};
                const response = await axios.post('/patients/patient-threshold', formData);
                if (response && response.status == 200) {
                    // Scroll the modal up
                    let modalElement = document.querySelector('.modal');
                    modalElement.scrollTop = 0;
                    window.scrollTo(0, 0);
                    showAlert.value = true;
                    updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
                    document.getElementById("patient_threshold_form").reset();
                    setTimeout(() => {
                        showAlert.value = false;
                        var time = document.getElementById('page_landing_times').value;
                        $(".timearr").val(time);
                        //alertThresholdTime.value = document.getElementById('page_landing_times').value;
                    }, 3000);
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                } else {
                    console.error('Error submitting form:', error);
                }
            }
            // this.closeModal();
            
        }
    
        onMounted(async () => {
            try {
                timerStatus.value = document.getElementById('timer_runing_status').value;
                var time = document.getElementById('page_landing_times').value;
                $(".timearr").val(time);
                patient_systolichigh.value = props.patient_systolichigh;
                patient_systoliclow.value = props.patient_systoliclow;
                patient_diastolichigh.value = props.patient_diastolichigh; 
                patient_diastoliclow.value = props.patient_diastoliclow; 
                patient_bpmhigh.value = props.patient_bpmhigh; 
                patient_bpmlow.value = props.patient_bpmlow; 
                patient_oxsathigh.value = props.patient_oxsathigh; 
                patient_oxsatlow.value = props.patient_oxsatlow; 
                patient_glucosehigh.value = props.patient_glucosehigh; 
                patient_glucoselow.value = props.patient_glucoselow; 
                patient_temperaturehigh.value = props.patient_temperaturehigh; 
                patient_temperaturelow.value = props.patient_temperaturelow; 
                patient_weighthigh.value = props.patient_weighthigh; 
                patient_weightlow.value = props.patient_weightlow; 
                patient_spirometerfevhigh.value = props.patient_spirometerfevhigh; 
                patient_spirometerfevlow.value = props.patient_spirometerfevlow; 
                patient_spirometerpefhigh.value = props.patient_spirometerpefhigh; 
                patient_spirometerpeflow.value = props.patient_spirometerpeflow; 

            } catch (error) {
                console.error('Error on page load:', error);
            }
            // fetchPersonalNotesForm();
        });

        onBeforeMount(() => {

        });

        watch(() => showAlert, (newShowAlert, oldShowAlert) => {
                showAlert.value = newShowAlert;
            }
        );

        return {
            loading,
            showAlert,
            alertThresholdTime,
            submit_patient_threshold_form,
            patient_systolichigh,
            patient_systoliclow,
            patient_diastolichigh, 
            patient_diastoliclow, 
            patient_bpmhigh, 
            patient_bpmlow, 
            patient_oxsathigh, 
            patient_oxsatlow, 
            patient_glucosehigh, 
            patient_glucoselow, 
            patient_temperaturehigh, 
            patient_temperaturelow, 
            patient_weighthigh, 
            patient_weightlow, 
            patient_spirometerfevhigh, 
            patient_spirometerfevlow, 
            patient_spirometerpefhigh, 
            patient_spirometerpeflow,
            timerStatus
        };
    },
};
</script>

<style>
/* ... your existing styles ... */

/* Additional styles for small modal */
.small-modal {
  width: 60%; /* Adjust the width as needed */
  max-height: 60vh; /* Adjust the max-height as needed */
  margin: 2% auto; /* Center the modal horizontally */
}

/* ... rest of your styles ... */
</style>