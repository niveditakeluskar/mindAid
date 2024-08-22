<template>
    <loading-spinner :isLoading="isLoading"></loading-spinner>
     <div class="card">
        <form id="relationship_form" name="relationship_form" @submit.prevent="submitRelationshipForm"> 
                <div class="card-body">
                    
                    <input type="hidden" name="patient_id" :value="patientId"/>
                    <input type="hidden" name="module_id" :value="moduleId"/>
                    <input type="hidden" name="component_id" :value="componentId"/>
                    <input type="hidden" name="hid_stage_id" />
                    <input type="hidden" name="form_name" value="relationship_form">
                    <!--input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="time"-->
                    <div class="alert alert-success" :style="{ display: showAlert ? 'block' : 'none' }">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong> Question saved successfully! </strong><span id="text"></span>
                    </div>
                    <div v-html="RelationshipQuestionnaire"></div>
                    <div class="alert alert-success" id="success-alert" :style="{ display: showAlert ? 'block' : 'none' }">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong> Question saved successfully! </strong><span id="text"></span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" id="save-question" class="btn btn-primary m-1" >Save</button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</template>
<script>
import {
  ref,
  onMounted,
  watch,
  onBeforeMount,
  Head
} from '../commonImports';
import axios from 'axios';
export default {
    props: {
    patientId: Number,
    moduleId: Number,
    componentId: Number
  },
  
  setup(props) {

    const RelationshipQuestionnaire = ref([]);
    const showAlert = ref(false);
    const showAlerts = ref(false);
    const isLoading = ref(false);

    const getSavedQuestion = async() => {
        await  axios.get(`/patients/patient-relationship-questionnaire/${props.patientId}/${props.moduleId}/${props.componentId}/patient-relationship-questionnaire`)
          .then(response => {
            RelationshipQuestionnaire.value = response.data;
          })
          .catch(error => {
            console.error('Error fetching data:', error);
          });
    }

    const submitRelationshipForm = async () => {
        isLoading.value = true;
        let myForm = document.getElementById('relationship_form'); 
        let formData = new FormData(myForm);
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        try {
            const response = await axios.post('/patients/monthly-monitoring-call-relationship', formData);
				if (response && response.status == 200) {
					showAlert.value = true;
                    isLoading.value = false;
					setTimeout(() => {
                       // this.time = document.getElementById('page_landing_times').value;
                       showAlert.value  = false;
					}, 3000);
				}
                isLoading.value = false;
        } catch (error) {
            isLoading.value = false;
            if (error.response && error.response.status === 422) {
            showAlert.value = true;
                setTimeout(() => {
                    showAlert.value = false;
                }, 3000);
            } else {
            console.error('Error submitting form:', error);
            }
      }
      isLoading.value = false;
    };


    onMounted(() => {
        getSavedQuestion();
    });

    return {
        getSavedQuestion,
        submitRelationshipForm,
        RelationshipQuestionnaire,
        showAlert,
        showAlerts,
        isLoading
    }
  }
};
</script>
<style>
/* From Uiverse.io by Yaya12085 */ 
.radio-input input {
  display: none;
}

.radio-input {
  display: flex;
  flex-direction: column;
  padding: 12px;
  background: #fff;
  color: #000;
  border-radius: 10px;
  box-shadow: 0 0 .4vw rgba(0,0,0,0.5), 0 0 0 .15vw transparent;
  width: 320px;
}

.text-input {
  padding: 12px;
  background: #fff;
  color: #000;
  border-radius: 10px;
  box-shadow: 0 0 .4vw rgba(0,0,0,0.5), 0 0 0 .15vw transparent;
  width: 320px;
}

.info {
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.question {
  color: rgb(49, 49, 49);
  font-size: 1rem;
  line-height: 1rem;
  font-weight: 800;
}

.steps {
  background-color: rgb(0, 0, 0);
  padding: 4px;
  color: #fff;
  border-radius: 4px;
  font-size: 12px;
  line-height: 12px;
  font-weight: 600;
}

.radio-input  label {
  display: flex;
  background-color: #fff;
  padding: 14px;
  margin: 8px 0 0 0;
  font-size: 13px;
  font-weight: 600;
  border-radius: 10px;
  cursor: pointer;
  border: 1px solid rgba(187, 187, 187, 0.164);
  color: #000;
  transition: .3s ease;
}

.radio-input  label:hover {
  background-color: rgba(24, 24, 24, 0.13);
  border: 1px solid #bbb;
}

.radio-input input:checked + label {
  border-color: red;
  color: red;
}

.mutation-selection-container {
    background-color: #f7f7fc;
    border-radius: 10px;
    padding: 20px;
    width: 320px;
    font-family: Arial, sans-serif;
    box-shadow: 0 0 .4vw rgba(0,0,0,0.5), 0 0 0 .15vw transparent;
}

h2 {
    font-size: 18px;
    margin-bottom: 10px;
}

p {
    font-size: 14px;
    margin-bottom: 15px;
}

label {
    font-size: 16px;
    margin-bottom: 10px;
    display: block;
}

input[type="checkbox"] {
    margin-right: 10px;
}

/* button {
    background-color: #333;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #555;
} */

</style>