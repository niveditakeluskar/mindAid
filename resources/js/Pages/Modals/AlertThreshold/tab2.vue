<template>
<div>
<!-- Content of the tab goes here -->
<div class="tab-pane show " id="Patient-Threshold_2" role="tabpanel" aria-labelledby="call-icon-tab" style="margin-top: -26">
        <h4 id="heading_threshold"><span :textContent="heading"></span></h4>
        <div class="form-group">
            <input type="hidden" name="patient_id" id="patient_id" :value="patientId">
                <input type="hidden" name="module_id" id="page_module_id" :value="moduleId">
                <input type="hidden" name="component_id" id="page_component_id" :value="componentId">
                <input type="hidden" name="stage_id" :value="stageid" />
            <div class="row">
                <div class="col-md-6 form-group mb-3 ">
                    <label>Systolic High : <p style="display:inline" id="p_systolichigh" :textContent="system_systolichigh"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Systolic Low : <p style="display:inline" id="p_systoliclow" :textContent="system_systoliclow"></p> </label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Diastolic High : <p style="display:inline" id="p_diastolichigh" :textContent="system_diastolichigh"></p> </label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Diastolic Low : <p style="display:inline" id="p_diastoliclow" :textContent="system_diastoliclow"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Heart Rate High : <p style="display:inline" id="p_heartratehigh" :textContent="system_bpmhigh"></p></label>
                </div>
                <div class="col-md-6 form-group mb-3 ">
                    <label>Heart Rate Low : <p style="display:inline" id="p_heartratelow" :textContent="system_bpmlow"></p> </label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Oxygen Saturation High : <p style="display:inline" id="p_oxygensaturationhigh" :textContent="system_oxsathigh"></p> </label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Oxygen Saturation Low : <p style="display:inline" id="p_Oxygensaturationlow" :textContent="system_oxsatlow"></p> </label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Glucose High : <p style="display:inline" id="p_glucosehigh" :textContent="system_glucosehigh"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Glucose Low : <p style="display:inline" id="p_glucoselow" :textContent="system_glucoselow"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Temperature High : <p style="display:inline" id="p_temperaturehigh" :textContent="system_temperaturehigh"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Temperature Low : <p style="display:inline" id="p_temperaturelow" :textContent="system_temperaturelow"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Weight High : <p style="display:inline" id="p_weighthigh" :textContent="system_weighthigh"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Weight Low : <p style="display:inline" id="p_weightlow" :textContent="system_weightlow"></p></label>
                </div>
                
                <div class="col-md-6 form-group mb-3 ">
                    <label>Spirometer-FEV High : <p style="display:inline" id="p_Spirometer-fevhigh" :textContent="system_Spirometerfevhigh"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Spirometer-FEV Low : <p style="display:inline" id="p_Spirometer-fevlow" :textContent="system_Spirometerfevlow"></p></label>
                </div>
                
                <div class="col-md-6 form-group mb-3 ">
                    <label>Spirometer-PEF High : <p style="display:inline" id="p_Spirometer-pefhigh" :textContent="system_Spirometer-pefhigh"></p></label>
                </div>

                <div class="col-md-6 form-group mb-3 ">
                    <label>Spirometer-PEF Low : <p style="display:inline" id="p_Spirometer-peflow" :textContent="system_Spirometer-peflow"></p></label>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import {
    ref,
    onBeforeMount,
    onMounted,
    // watch,
    // Add other common imports if needed
} from '../../commonImports';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageid: Number,
    },

    components: {

    },
    
    setup(props) {
        // let alertThresholdTime = ref(null);
        let heading = ref(null);
        let system_systolichigh = ref(null);
        let system_systoliclow = ref(null);
        let system_diastolichigh = ref(null); 
        let system_diastoliclow = ref(null); 
        let system_bpmhigh = ref(null); 
        let system_bpmlow = ref(null); 
        let system_oxsathigh = ref(null); 
        let system_oxsatlow = ref(null); 
        let system_glucosehigh = ref(null); 
        let system_glucoselow = ref(null); 
        let system_temperaturehigh = ref(null); 
        let system_temperaturelow = ref(null); 
        let system_weighthigh = ref(null); 
        let system_weightlow = ref(null); 
        let system_spirometerfevhigh = ref(null); 
        let system_spirometerfevlow = ref(null); 
        let system_spirometerpefhigh = ref(null); 
        let system_spirometerpeflow = ref(null); 
        // const isOpen = ref(false); 
        // const showAlert = ref(false);
        // let formErrors = ref([]);
        const loading = ref(false);
        //   const personal_notes = ref(null);
        // const openModal = () => {
        //     console.log('Open personal modal called');
        //     isOpen.value = true;
        // };

        // const closeModal = () => {
        //     console.log('Close personal modal called');
        //     isOpen.value = false;
        // };
        

        // let submit_patient_threshold_form = async () => {
        //     let myForm = document.getElementById('patient_threshold_form');
        //     let formData = new FormData(myForm);
        //     axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        //     try {
        //         formErrors.value = {};
        //         const response = await axios.post('/patients/patient-threshold', formData);
        //         if (response && response.status == 200) {
        //             showAlert.value = true;
        //             updateTimer(props.patientId, '1', props.moduleId);
        //             $(".form_start_time").val(response.data.form_start_time);
        //             document.getElementById("patient_threshold_form").reset();
        //             setTimeout(() => {
        //                 showAlert.value = false;
        //                 alertThresholdTime.value = document.getElementById('page_landing_times').value;
        //             }, 3000);
        //         }
        //     } catch (error) {
        //         if (error.response && error.response.status === 422) {
        //             formErrors.value = error.response.data.errors;
        //         } else {
        //             console.error('Error submitting form:', error);
        //         }
        //     }
        //     this.closeModal();
        // }
        
        let get_default_threshold = async()=> {
        try {
            const response = await fetch(`/patients/systemThresholdTab/${props.patientId}/${props.moduleId}`);
            if (!response.ok) {
                throw new Error(`Failed to fetch Patient details - ${response.status} ${response.statusText}`);
            }
            const data = await response.json();
            // console.log(data.threshold.bpmhigh+'systemThresholdTab');
            // console.log(data.heading.heading+'systemThresholdTabHEADING');
            heading.value = data.heading;
            system_systolichigh.value = data.threshold.systolichigh;
            system_systoliclow.value = data.threshold.systoliclow;
            system_diastolichigh.value = data.threshold.diastolichigh;
            system_diastoliclow.value = data.threshold.diastoliclow;
            system_bpmhigh.value = data.threshold.bpmhigh;
            system_bpmlow.value = data.threshold.bpmlow;
            system_oxsathigh.value = data.threshold.oxsathigh;
            system_oxsatlow.value = data.threshold.oxsatlow;
            system_glucosehigh.value = data.threshold.glucosehigh;
            system_glucoselow.value = data.threshold.glucoselow;
            system_temperaturehigh.value = data.threshold.temperaturehigh;
            system_temperaturelow.value = data.threshold.temperaturelow;
            system_weighthigh.value = data.threshold.weighthigh;
            system_weightlow.value = data.threshold.weightlow;
            system_spirometerfevhigh.value = data.threshold.spirometerfevhigh;
            system_spirometerfevlow.value = data.threshold.temperaturelow;
            system_spirometerpefhigh.value = data.threshold.spirometerpefhigh;
            system_spirometerpeflow.value = data.threshold.spirometerpeflow;

            // personal_notes_data.value = data.personal_notes.static.personal_notes;
            } catch (error) {
                    if (error.response && error.response.status === 422) {
                        formErrors.value = error.response.data.errors;
                    } else {
                        console.error('Error submitting form:', error);
                    }
                }
        }
        onMounted(async () => {
            try {
                get_default_threshold();
                // alertThresholdTime.value = document.getElementById('page_landing_times').value;
            } catch (error) {
                console.error('Error on page load:', error);
            }
            // fetchPersonalNotesForm();
        });

        onBeforeMount(() => {

        });

        // watch(() => showAlert, (newShowAlert, oldShowAlert) => {
        //         showAlert.value = newShowAlert;
        //     }
        // );

        return {
            loading,
            heading,
            // isOpen,
            // openModal,
            // closeModal,
            // showAlert,
            // alertThresholdTime,
            // submit_patient_threshold_form,
            get_default_threshold,
           system_systolichigh,
           system_systoliclow,
           system_diastolichigh, 
           system_diastoliclow, 
           system_bpmhigh, 
           system_bpmlow, 
           system_oxsathigh, 
           system_oxsatlow, 
           system_glucosehigh, 
           system_glucoselow, 
           system_temperaturehigh, 
           system_temperaturelow, 
           system_weighthigh, 
           system_weightlow, 
           system_spirometerfevhigh, 
           system_spirometerfevlow, 
           system_spirometerpefhigh, 
           system_spirometerpeflow, 

        };
    },
};
</script>
