<template>
  <LayoutComponent ref="layoutComponentRef">
    <loading-spinner :isLoading="isLoading"></loading-spinner>
    <div class="breadcrusmb">
      <div class="row" style="margin-top: 10px">
        <div class="col-md-8">
          <h4 class="card-title mb-3">Enrollment</h4>
        </div>
        <div class="form-group col-md-4"></div>
      </div>
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <div class="row">
      <div class="col-md-12 mb-4">
        <div class="card text-left">
          <div class="card-body">
            <div class="alert alert-success" id="success-alert"
                                                :style="{ display: showAlert ? 'block' : 'none' }">
                                                <button type="button" class="close" data-dismiss="alert">x</button>
                                                <strong>Patient Register successfully! </strong><span id="text"></span>
                                            </div>
            <div class="form-group">
              <select name="patient_list" class="custom-select show-tick" v-model="selectList"
                @change="patientRegisterUpdate" v-if="toShowList">
                <option v-for="(option, index) in options" :key="index" :value="option.value">{{ option.label }}
                </option>
              </select>
            </div>

            <div class="form-row" v-if="practicePatientFilter">
              <div class="col-md-6 form-group mb-6">
                <label for="practicename">Select Practice</label>
                <select name="practices" class="custom-select show-tick select2" data-live-search="true"
                  v-model="selectedPracticePatient" @change="handlePracticePatientChange">
                  <option value="">Select All Practices</option>
                  <option v-for="practice in practices" :key="practice.id" :value="practice.id">
                    {{ practice.name }}
                  </option>
                </select>
                <div class="form-row invalid-feedback" v-if="formErrors.practices" style="display: block;">{{
      formErrors.practices[0] }}</div>
              </div>
              <div class="col-md-6 form-group mb-6">
                <label for="patientsname">Patient Name</label>
                <select name="patient" class="custom-select show-tick select2" v-model="selectedPatient">
                  <option value="" selected>Select Patient</option>
                  <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                    {{ patient.fname }} {{ patient.mname }} {{ patient.lname }}
                  </option>
                </select>
                <div class="form-row invalid-feedback" v-if="formErrors.pcp" style="display: block;">{{
      formErrors.pcp[0] }}</div>
              </div>
            </div>

            <form method="post" enctype="multipart/form-data" name="patient_registrations_form"
              id="patient_registrations_form" @submit.prevent="submitRegisterForm" v-if="step1">
              <input type="hidden" name="patient_id" :value="patientId" v-if="practicePatientFilter">
              <div class="form-row">
                <div class="col-md-6 form-group mb-6">
                  <label for="practicename">Select Practice<span class="error">*</span></label>
                  <select name="practices" class="custom-select show-tick select2" data-live-search="true"
                    v-model="selectedPractice" @change="handlePracticeChange">
                    <option value="">Select All Practices</option>
                    <option v-for="practice in practices" :key="practice.id" :value="practice.id">
                      {{ practice.name }}
                    </option>
                  </select>
                  <div class="form-row invalid-feedback" v-if="formErrors.practices" style="display: block;">{{
      formErrors.practices[0] }}</div>
                </div>
                <div class="col-md-6 form-group mb-6">
                  <label for="patientsname">Select Primary Care Provider (PCP)<span class="error">*</span></label>
                  <select name="pcp" class="custom-select show-tick select2" v-model="selectedPCP">
                    <option value="" selected>Select Primary Care Provider (PCP)</option>
                    <option v-for="physician in physicians" :key="physician.id" :value="physician.id">
                      {{ physician.name }}
                    </option>
                  </select>
                  <div class="form-row invalid-feedback" v-if="formErrors.pcp" style="display: block;">{{
      formErrors.pcp[0] }}</div>
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label for="lname">Last Name<span class="error">*</span></label>
                    <input type="text" name="lname" class="form-control capitalize" v-model="lastname" ref="lname"
                      @focusout="generateUID">
                    <input type="hidden" name="uid" id="uid">
                  </div>
                  <div class="form-row invalid-feedback" v-if="formErrors.lname" style="display: block;">{{
      formErrors.lname[0] }}</div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="fname">First Name<span class="error">*</span></label>
                    <input type="text" name="fname" class="form-control capitalize" v-model="firstname" ref="fname"
                      @focusout="generateUID">

                  </div>
                  <div class="form-row invalid-feedback" v-if="formErrors.fname" style="display: block;">{{
      formErrors.fname[0] }}</div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="mname">Middle Name</label>
                    <input type="text" name="mname" class="form-control capitalize" v-model="middlename" ref="mlame">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-2">
                  <div class="form-group">
                    <label>Date of Birth<span class="error">*</span></label>
                    <input type="date" name="dob" class="form-control" ref="dob" v-model="dateofbirth"
                      @focusout="generateUID">
                  </div>
                  <div class="form-row invalid-feedback" v-if="formErrors.dob" style="display: block;">{{
      formErrors.dob[0] }}</div>
                </div>

                <div class="col-md-3 form-group">
                  <label for="mob">Primary Phone Number<span class="error">*</span></label>
                  <div class="input-group form-group">
                    <div class="input-group-prepend btn-group btn-group-toggle">
                      <label class="btn btn-outline-primary" for="phone-primary-preferred">
                        Preferred
                        <input id="phone-primary-preferred" value="0" data-feedback="contact-preferred-feedback"
                          name="preferred_contact" type="radio" autocomplete="off" class="form-control">
                      </label>
                    </div>
                    <input id="mob" name="mob" type="text"
                      v-model="phonenumber" autocomplete="off" class="form-control" >
                  </div>
                  <div class="form-row invalid-feedback" v-if="formErrors.mob" style="display: block;">{{
      formErrors.mob[0] }}</div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="emr">EMR#<span class="error">*</span></label>
                    <input type="text" name="practice_emr" v-model="emrnumber" class="form-control">
                  </div>
                  <div class="form-row invalid-feedback" v-if="formErrors.practice_emr" style="display: block;">{{
      formErrors.practice_emr[0] }}</div>
                </div>
              </div>
              <br>
              <h4>Patient Complete?</h4>
              <div class="btn-group">
                <button type="submit" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-primary" @click="deActivate">No</button>
              </div>
            </form>
            <Step2 :moduleId="moduleId" :componentId="componentId" :stageId="stageId" :patientId="patientId"
              v-if="step2" @aceptDecline="handleStepEvent" />
            <Step3 :moduleId="moduleId" :componentId="componentId" :stageId="stageId" :patientId="patientId"
              :practices="practices" :physicians="physicians" :selectPractice="selectPractice" v-if="step3"
              @aceptDecline="handleStepEvent" />
          </div>
        </div>
      </div>
    </div>
  </LayoutComponent>

  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Patient Registration & Enrollment" />
  </Head>
</template>
<script>
import {
  ref,
  onMounted,
  watch,
  onBeforeMount,
  Head
} from '../commonImports';
import LayoutComponent from '../LayoutComponent.vue';
import Step2 from './EnrollStep/Step2.vue';
import Step3 from './EnrollStep/Step3.vue';
import axios from 'axios';
import Inputmask from 'inputmask';

export default {
  props: {
    moduleId: Number,
    componentId: Number,
  },
  components: {
    LayoutComponent,
    Head,
    Step2,
    Step3
  },
  setup(props) {
    const title = 'Patient Enrollment';
    const layoutComponentRef = ref(null);
    const practices = ref([]);
    const physicians = ref([]);
    const selectedPractice = ref('');
    const selectedPCP = ref('');
    let formErrors = ref([]);
    const lname = ref('');
    const fname = ref('');
    const mname = ref('');
    const dob = ref('');
    const step1 = ref(false);
    const step2 = ref(false);
    const step3 = ref(false);
    const stageId = ref('');
    const patientId = ref('');
    const isLoading = ref(false);
    const selectList = ref('');
    const practicePatientFilter = ref(false);
    const selectedPracticePatient = ref('');
    const selectedPatient = ref('');
    const patients = ref([]);
    const toShowList = ref(true);
    const showAlert = ref(false);
    const options = [
      { label: 'Register New Patient / Update Existing Patient', value: '' },
      { label: 'Register New', value: 0 },
      { label: 'Update existing Patient', value: 1 }
    ];

    const pid = ref('');
    const pracid = ref('');
    const provid = ref('');
    const firstname = ref('');
    const middlename = ref('');
    const lastname = ref('');
    const phonenumber = ref('');
    const dateofbirth = ref('');
    const emrnumber = ref('');

    onMounted(() => {
      document.title = 'Enrollment | Renova Healthcare';
      fetchPractices();
      getStageID();
    });

    watch(selectedPractice, (newPracticeId) => {
      fetchPCP(newPracticeId);
    });

    watch(selectedPracticePatient, (newPatientID) => {
      fetchPatients(newPatientID)
    });

    watch(selectedPatient, (newPatientID) => {
      getPatientDetails(newPatientID);
    });

    const fetchPractices = async () => {
      try {
        const response = await fetch('../../org/practiceslist');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        practices.value = data;
      } catch (error) {
        console.error('Error fetching practices:', error);
      }
    };

    const fetchPCP = async (practiceId) => {
      try {
        const response = await fetch('../../org/ajax/provider/list/' + practiceId + '/Pcpphysicians');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        physicians.value = data;
      } catch (error) {
        console.error('Error fetching practices:', error);
      }
    };

    const submitRegisterForm = async () => {
      isLoading.value = true;
      let myForm = document.getElementById('patient_registrations_form');
      let formData = new FormData(myForm);
      axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
      try {
        formErrors.value = [];
        const response = await axios.post('/patients/register', formData);
        if (response && response.status == 200) {
          showAlert.value = true;
          patientId.value = response.data.patient_id;
          isLoading.value = false;
          setTimeout(() => {
            showAlert.value = false;
            step1.value = false;
            practicePatientFilter.value = false;
            toShowList.value = false;
            step2.value = true;
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
    };

    const deActivate = async () => {
      isLoading.value = true;
      let myForm = document.getElementById('patient_registrations_form');
      let formData = new FormData(myForm);
      axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
      try {
        formErrors.value = [];
        const response = await axios.post('/patients/registerDeactive', formData);
        if (response && response.status == 200) {
          showAlert.value = true;
          patientId.value = response.data.patient_id;
          isLoading.value = false;
          setTimeout(() => {
            showAlert.value = false;
            selectList.value  = '';
            step1.value = false;
            practicePatientFilter.value = false;
            myForm.reset();
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

    const generateUID = async () => {
      var lName = lname.value.value;
      var fName = fname.value.value;
      var db = dob.value.value;
      var mName = mname.value.value;
      var data = {
        fName: fName,
        lName: lName,
        dob: db,
        mName: mName
      };
      if ((fName != 'undefined' && fName != null && fName != "") && (lName != 'undefined' && lName != null && lName != "") && (db != 'undefined' && db != null && db != "")) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        try {
          const response = await axios.post('/patients/patient-uid/validate', data);
          if (response.status == 200 && response.data.uid) {
            $('#uid').val(response.data.uid);
          }
        } catch (error) {

        }
      }
    };

    const handleStepEvent = (status) => {
      if (status == 0) {
      selectList.value  = '';
      firstname.value = '';
      lastname.value = '';
      middlename.value = '';
      dateofbirth.value = '';
      phonenumber.value = '';
      selectedPractice.value = '';
      selectedPCP.value = '';
      emrnumber.value = '';
        step1.value = false;
        toShowList.value = true;
        step2.value = false;
        step3.value = false;
      } else {
        step1.value = false;
        step2.value = false;
        step3.value = true;
      }
    };

    const getStageID = async () => {
      try {
        let stageName = 'Call';
        let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${stageName}`);
        stageId.value = response.data.stageID;
      } catch (error) {
        throw new Error('Failed to fetch stageID');
      }
    };

    const patientRegisterUpdate = () => {
      firstname.value = '';
      lastname.value = '';
      middlename.value = '';
      dateofbirth.value = '';
      phonenumber.value = '';
      selectedPractice.value = '';
      selectedPCP.value = '';
      emrnumber.value = '';
      if (selectList.value == 0) {
        practicePatientFilter.value = false;
        step1.value = true;
        setTimeout(() => {
          Inputmask({ mask: '(999) 999-9999' }).mask("#mob");
        }, 3000);
          
      } else {
        step1.value = false;
        practicePatientFilter.value = true;
      }
     
    }

    const fetchPatients = async (practiceId) => {
      try {
        if (practiceId === undefined) {
          return;
        }

        if (!practiceId) {
          practiceId = null;
        }

        const response = await fetch('/patients/ajax/list/' + practiceId + '/patientlist');
        if (!response.ok) {
          throw new Error('Failed to fetch patients');
        }
        const data = await response.json();
        patients.value = data;
        return Promise.resolve(data);
      } catch (error) {
        console.error('Error fetching patients:', error);
        return Promise.reject(error);
      }
    };

    const getPatientDetails = async (id) => {
      step1.value = true;
      setTimeout(() => {
          Inputmask({ mask: '(999) 999-9999' }).mask("#mob");
        }, 3000);
      await axios.get(`/patients/getDetails/${id}`)
        .then(response => {
          const data = response.data;
          patientId.value = data.patients[0].id;
          firstname.value = data.patients[0].fname;
          lastname.value = data.patients[0].lname;
          middlename.value = data.patients[0].mname;
          dateofbirth.value = data.patients[0].dob;
          phonenumber.value = data.patients[0].mob;
          selectedPractice.value = data.practice_id;
          selectedPCP.value = data.provider_id;
          emrnumber.value = data.emr;
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    }

    return {
      title,
      practices,
      selectedPractice,
      fetchPractices,
      selectedPCP,
      fetchPCP,
      physicians,
      submitRegisterForm,
      formErrors,
      generateUID,
      lname,
      mname,
      fname,
      dob,
      step1,
      step2,
      step3,
      getStageID,
      stageId,
      handleStepEvent,
      patientId,
      deActivate,
      isLoading,
      practicePatientFilter,
      patientRegisterUpdate,
      selectList,
      selectedPracticePatient,
      selectedPatient,
      patients,
      options,
      fetchPatients,
      pid,
      pracid,
      provid,
      firstname,
      middlename,
      lastname,
      phonenumber,
      dateofbirth,
      emrnumber,
      toShowList,
      getPatientDetails,
      showAlert
    }
  }
};
</script>