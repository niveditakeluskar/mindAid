<template>
    <loading-spinner :isLoading="isLoading"></loading-spinner>
    <form id="enroll_step_form" name="enroll_step_form" @submit.prevent="submitEnrollStepFormData">
        <div class="alert alert-success" id="success-alert"
                                                :style="{ display: showAlert ? 'block' : 'none' }">
                                                <button type="button" class="close" data-dismiss="alert">x</button>
                                                <strong>Patient Enrolled successfully!</strong><span id="text"></span>
                                            </div>
        <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" v-model="landingtime">
        <input type="hidden" name="module_id" :value="moduleId">
        <input type="hidden" name="patient_id" :value="patientId">
        <input type="hidden" name="component_id" :value="componentId">
        <input type="hidden" name="stage_id" :value="stageId">
        <input type="hidden" name="start_time" value="00:00:00">
        <input type="hidden" name="end_time" value="00:00:00">
        <input type="hidden" name="form_name" value="Enrollment">
        <input type="hidden" name="content_title" value="Enrollment Script">
        <input type="hidden" id="page_landing_times" name="page_landing_times" v-model='landingtime'>
        <Timer :moduleId="moduleId" :componentId="componentId" :stageId="stageId" :patientId="patientId" v-if="isTimer">
        </Timer>
        <div class="form-row">
            <div class="col-md-6 form-group mb-6">
                <label for="practicename"> Select Enrollment Script<span class="error">*</span></label>
                <select name="script" class="custom-select show-tick select2" data-live-search="true"
                    v-model="selectedScript" @change="handlePracticeChange">
                    <option value="">Select Enroll Script</option>
                    <option v-for="enroll in enrollmentScript" :key="enroll.id" :value="enroll.id">
                        {{ enroll.content_title }}
                    </option>
                </select>
                <div class="form-row invalid-feedback" v-if="formErrors.script" style="display: block;">{{
                formErrors.script[0] }}</div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 form-group mb-3">
                <textarea name="text_msg" class="form-control" id="ccm_content_area"
                    style="padding: 5px;min-height: 5em;overflow: auto; height: 300px!important;">{{ script }}</textarea>
                    <div class="form-row invalid-feedback" v-if="formErrors.text_msg" style="display: block;">{{
                formErrors.text_msg[0] }}</div>
            </div>
            <br>
        </div>
        <h4>Accept/Decline</h4>
        <div class="btn-group">
            <button type="submit" class="btn btn-primary" >Yes</button>
            <button type="button" class="btn btn-primary" @click="prev">No</button>
        </div>
    </form>
</template>
<script>
import {
    ref,
    onMounted,
    onBeforeMount,
    watch
} from '../../commonImports';
import axios from 'axios';
import Timer from './Timer.vue';
export default {
    props: {
        moduleId: Number,
        componentId: Number,
        stageId: Number,
        patientId: Number,
    },
    components: {
        Timer
    },
    setup(props, { emit }) {
        const enrollmentScript = ref([]);
        const stepID = ref('');
        const selectedScript = ref('');
        const script = ref('');
        const landingtime = ref('');
        const isTimer = ref(false);
        let formErrors = ref([]);
        const isLoading = ref(false);
        const showAlert = ref(false);

        watch(selectedScript, (newScriptId) => {
            getContent(newScriptId);
        });

        const fetchEnrollScriptList = async () => {
            await axios.get(`/org/get_content_scripts/${props.moduleId}/${props.componentId}/${props.stageId}/${stepID.value}/content_template`)
                .then(response => {
                    enrollmentScript.value = response.data;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        const getStepID = async () => {
            let stepname = 'Enrollment_Script';
            axios.get(`/get_step_id/${props.moduleId}/${props.componentId}/${props.stageId}/${stepname}`)
                .then(response => {
                    stepID.value = response.data.stepID;
                    fetchEnrollScriptList();
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                })
        }

        const getContent = async (id) => {
            await axios.get(`/ccm/get-call-scripts-by-id/${id}/${props.patientId}/call-script`)
                .then(response => {
                    var data = response.data.finaldata;
                    data = data.replace(/(<([^>]+)>)/ig, '');
                    data = data.replace(/&nbsp;/g, ' ');
                    script.value = data.replace(/&amp;/g, '&');
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        const prev = async() => { 
            isLoading.value = true;
            let myForm = document.getElementById('enroll_step_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                formErrors.value = [];
                const response = await axios.post('/patients/saveenrolleddataDeactive', formData);
                if (response && response.status == 200) {
                    updateTimer(props.patientId, 0, props.moduleId);
                    showAlert.value = true;
                    isLoading.value = false;
                setTimeout(() => {
                    isTimer.value = false;
                    showAlert.value = false;
                    emit('aceptDecline', 0);
                }, 3000);
                }
                isLoading.value = false;
            } catch (error) {
                isLoading.value = false;
                if (error.response && error.response.status === 422) {
                formErrors.value = error.response.data.errors;
                } else {
                console.error('Error submitting form:', error);
                }
            }
        }

        const setLandingTime = async () => {
            axios.get(`/patients/getLandingTime`)
                .then(response => {
                    landingtime.value = response.data.landing_time;
                    isTimer.value = true;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                })
        }

        const submitEnrollStepFormData = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('enroll_step_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                formErrors.value = [];
                const response = await axios.post('/patients/saveenrolleddata', formData);
                if (response && response.status == 200) {
                    updateTimer(props.patientId, 1, props.moduleId);
                    showAlert.value = true;
                    isLoading.value = false;
                setTimeout(() => {
                    isTimer.value = false;
                    showAlert.value = false;
                    emit('aceptDecline', 1);
                }, 3000);
                }
                isLoading.value = false;
            } catch (error) {
                isLoading.value = false;
                if (error.response && error.response.status === 422) {
                formErrors.value = error.response.data.errors;
                } else {
                console.error('Error submitting form:', error);
                }
            }
        }

        onMounted(() => {
            getStepID();
        });

        onBeforeMount(() => {
            setLandingTime();
        });

        return {
            enrollmentScript,
            stepID,
            selectedScript,
            script,
            landingtime,
            isTimer,
            isLoading,
            formErrors,
            showAlert,
            fetchEnrollScriptList,
            getStepID,
            getContent,
            prev,
            setLandingTime,
            submitEnrollStepFormData
        }
    }
};
</script>