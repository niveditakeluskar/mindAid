<template>
  <input type="hidden" id="page_landing_times" name="page_landing_times" :value='landingtime'>
  <Timer :moduleId="moduleId" :componentId="componentId" :stageId="stageId" :patientId="patientId" v-if="isTimer">
  </Timer>
  <loading-spinner :isLoading="isLoading"></loading-spinner>
  <div class="alert alert-success" id="success-alert" :style="{ display: showAlert ? 'block' : 'none' }">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Patient Update successfully! </strong><span id="text"></span>
  </div>
  <div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"
    :style="{ display: showAlerts ? 'block' : 'none' }">
    <button type="button" class="close" data-dismiss="alert">× </button>
    <strong>Please Fill All Mandatory Fields!</strong>
  </div>
  <form method="post" enctype="multipart/form-data" name="patient_registrations_form" id="patient_registrations_form"
    @submit.prevent="updateRegisterForm">
    <input type="hidden" name="patient_id" :value="patientId">
    <input type="hidden" name="module_id" :value="moduleId">
    <input type="hidden" name="component_id" :value="componentId">
    <input type="hidden" name="start_time" value="00:00:00">
    <input type="hidden" name="end_time" value="00:00:00">
    <input type="hidden" name="form_name" value="Patient_update">
    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" v-model="landingtime">
    <div class="form-row">
      <div class="col-md-6 form-group mb-6">
        <label for="practicename">Select Practice<span class="error">*</span></label>
        <select name="practices" class="custom-select show-tick select2" data-live-search="true"
          v-model="selectedPracticeStep" @change="handlePracticeChange">
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
          <input type="text" name="lname" class="form-control capitalize" v-model="lname">
          <input type="hidden" name="uid" id="uid">
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.lname" style="display: block;">{{
    formErrors.lname[0] }}</div>
      </div>
      <div class="col-4">
        <div class="form-group">
          <label for="fname">First Name<span class="error">*</span></label>
          <input type="text" name="fname" class="form-control capitalize" v-model="fname">

        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.fname" style="display: block;">{{
    formErrors.fname[0] }}</div>
      </div>
      <div class="col-4">
        <div class="form-group">
          <label for="mname">Middle Name</label>
          <input type="text" name="mname" class="form-control capitalize" v-model="mname">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-2">
        <div class="form-group">
          <label>Gender<span class="error">*</span></label>
          <select name="gender" class="custom-select show-tick" v-model="gender">
            <option value="">Select Gender</option>
            <option value="0">Male</option>\
            <option value="1">Female</option>
          </select>
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.gender" style="display: block;">{{
    formErrors.gender[0] }}</div>
      </div>
      <div class="col-md-2 form-group">
        <label>Marital Status<span class="error">*</span></label>
        <select name="marital_status" class="custom-select show-tick" v-model="marital_status">
          <option value="">Select Marital Status</option>
          <option value="single">Single</option>
          <option value="partnered">Partnered</option>
          <option value="married">Married</option>
          <option value="separated">Separated</option>
          <option value="divorced">Divorced</option>
          <option value="widowed">Widowed</option>
        </select>
        <div class="invalid-feedback"></div>
        <div class="form-row invalid-feedback" v-if="formErrors.marital_status" style="display: block;">{{
    formErrors.marital_status[0] }}</div>
      </div>
      <div class="col-4">
        <div class="form-group">
          <label>Date of Birth<span class="error">*</span></label>
          <input type="date" name="dob" class="form-control" v-model="dob" max="9999-12-31"  min="1902-01-01">
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.dob" style="display: block;">{{
    formErrors.dob[0] }}</div>
      </div>
      <div class="col-4">
        <div class="form-group">
          <label for="emr">EMR#<span class="error">*</span></label> 
          <input id="practice_emr" name="practice_emr" type="text" v-model="emr" autocomplete="off" class="form-control">  
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.practice_emr" style="display: block;">{{
    formErrors.practice_emr[0] }}</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 form-group">
        <label>Primary No. Country Code</label>
        <select name="country_code" class="custom-select show-tick select2" data-live-search="true"
          v-model="selectedCode">
          <option value="">Select Country Code</option>
          <option v-for="code in codes" :key="code.countries_isd_code" :value="'+' + code.countries_isd_code">
            {{ code.countries_name }} ({{ code.countries_iso_code }}) {{ code.countries_isd_code }}
          </option>
        </select>
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
          <input id="mob" data-inputmask="'mask': '(999) 999-9999'" name="mob" type="text" v-model="mob"
            autocomplete="off" class="form-control" im-insert="true">
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.mob" style="display: block;">{{
    formErrors.mob[0] }}</div>
      </div>
      <div class="col-md-2 form-group">
        <label>Is Cell Phone</label><br>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn btn-outline-primary btn-toggle " :class="{ active: iscell == 1 }">
            <input type="radio" name="primary_cell_phone" id="option1" value="1" autocomplete="off" v-model="iscell"
              :checked="iscell == 1"> Yes
          </label>
          <label class="btn btn-outline-primary btn-toggle" :class="{ active: iscell == 0 }">
            <input type="radio" name="primary_cell_phone" id="option2" value="0" autocomplete="off" v-model="iscell"
              :checked="iscell == 0"> No
          </label>
        </div>
      </div>
      <div class="col-md-2 form-group">
        <label>Consent To Text</label><br>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn btn-outline-primary btn-toggle " :class="{ active: concent == 1 }">
            <input type="radio" name="consent_to_text" id="option1" value="1" autocomplete="off" v-model="concent"
              :checked="concent == 1"> Yes
          </label>
          <label class="btn btn-outline-primary btn-toggle" :class="{ active: concent == 0 }">
            <input type="radio" name="consent_to_text" id="option2" value="0" autocomplete="off" v-model="concent"
              :checked="concent == 0"> No
          </label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 form-group">
        <label>Secondary No. Country Code</label>
        <select name="secondary_country_code" class="custom-select show-tick select2" data-live-search="true"
          v-model="selectedsCode">
          <option value="">Select Country Code</option>
          <option v-for="code in codes" :key="code.countries_isd_code" :value="'+' + code.countries_isd_code">
            {{ code.countries_name }} ({{ code.countries_iso_code }}) {{ code.countries_isd_code }}
          </option>
        </select>
      </div>
      <div class="col-md-3 form-group">
        <label for="mob">Secondary Phone Number</label>
        <div class="input-group form-group">
          <div class="input-group-prepend btn-group btn-group-toggle">
            <label class="btn btn-outline-primary" for="phone-secondary-preferred">
              Preferred
              <input id="phone-secondary-preferred" value="0" data-feedback="contact-preferred-feedback"
                name="preferred_contact" type="radio" autocomplete="off" class="form-control">
            </label>
          </div>
          <input id="home_number" data-inputmask="'mask': '(999) 999-9999'" name="home_number" type="text" v-model="homemob"
            autocomplete="off" class="form-control" im-insert="true">
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.home_number" style="display: block;">{{
    formErrors.home_number[0] }}</div>
      </div>
      <div class="col-md-2 form-group">
        <label>Is Cell Phone</label><br>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn btn-outline-primary btn-toggle " :class="{ active: isscell == 1 }">
            <input type="radio" name="secondary_cell_phone" id="option1" value="1" autocomplete="off" v-model="isscell"
              :checked="isscell == 1"> Yes
          </label>
          <label class="btn btn-outline-primary btn-toggle" :class="{ active: isscell == 0 }">
            <input type="radio" name="secondary_cell_phone" id="option2" value="0" autocomplete="off" v-model="isscell"
              :checked="isscell == 0"> No
          </label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="city">City<span class="error">*</span></label>
          <input id="city" name="city" type="text" v-model="city" autocomplete="off" class="form-control capitalize">
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.city" style="display: block;">{{
    formErrors.city[0] }}</div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="state">State<span class="error">*</span></label>
          <select name="state" class="custom-select show-tick select2" v-model="state">
            <option value="">Select State</option>
            <option v-for="state in states" :key="state.state_code" :value="state.state_code">
              {{ state.state_name }}
            </option>
          </select>
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.state" style="display: block;">{{
    formErrors.state[0] }}</div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="zipcode">Zip Code<span class="error">*</span></label>
          <input id="zip" name="zipcode" type="text" v-model="zipcode" autocomplete="off" class="form-control">
        </div>
        <div class="form-row invalid-feedback" v-if="formErrors.zipcode" style="display: block;">{{
    formErrors.zipcode[0] }}</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 form-group">
        <label for="add_1">Address <span class="error">*</span></label>
        <input id="addr1" name="add_1" type="text" v-model="address" autocomplete="off" class="form-control">
      </div>
      <div class="form-row invalid-feedback" v-if="formErrors.add_1" style="display: block;">{{
    formErrors.add_1[0] }}</div>
    </div>
    <div class="row">
      <div class="col-md-4 form-group">
        <label>We can offer some additional services to Veterans or their Spouse's, were you or your spousea Veteran?
          <span class="error">*</span></label>
        <select id="military" name="military_status" class="custom-select show-tick" v-model="selectedVeteran"
          @change="checkMiletry">
          <option value="">Select Veteran status</option>
          <option value="0">Yes</option>
          <option value="1">No</option>
          <option value="2">Unknown</option>
        </select>
        <div class="form-row invalid-feedback" v-if="formErrors.military_status" style="display: block;">{{
    formErrors.military_status[0] }}</div>
      </div>
    </div>
    <div class="row" id="veteran-question" v-if="veteran">
      <div class="col-lg-12 mb-3">
        <div class="card">
          <div class="card-body" v-html="vt">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 mb-3">
        <div class="card">
          <div class="card-body" v-html="qualitymetric">
          </div>
        </div>
      </div>
    </div>
    <!--div class="row" >
                <div class="col-md-8 form-group">
                    <label>Current Monthly Notes:</label> 
                    <textarea name="call_monthly_notes" class="form-control" id="ccm_content_area"
                    style="padding: 5px;min-height: 5em;overflow: auto; "></textarea>
                    <div class="invalid-feedback"></div>
                </div>
              </div-->
    <br>
    <h4>Go To CPD?</h4>
    <div class="btn-group">
      <button type="button" class="btn btn-primary" @click="updateRegisterForm(1)">Yes</button>
      <button type="button" class="btn btn-primary" @click="updateRegisterForm(0)">No</button>
    </div>
    <button type="button" class="btn btn-primary" @click="updateRegisterForm(2)"
      style="margin-left: 20px; margin-bottom: 8px;">Save</button>
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
import Inputmask from 'inputmask';
//import { createRouter } from 'vue-router';
//const router = createRouter()
export default {
  props: {
    moduleId: Number,
    componentId: Number,
    stageId: Number,
    patientId: Number,
    practices: Array,
    physicians: Array,
  },
  components: {
    Timer
  },
  setup(props, { emit }) {
    const isTimer = ref(false);
    const landingtime = ref('');
    const selectedPracticeStep = ref('');
    const selectedPCP = ref('');
    let formErrors = ref([]);
    const states = ref([]);
    const selectedVeteran = ref('');
    const vt = ref('');
    const qualitymetric = ref('');
    const veteran = ref(false);
    const fname = ref('');
    const lname = ref('');
    const mname = ref('');
    const dob = ref('');
    const emr = ref('');
    const mob = ref('');
    const homemob = ref('');
    const gender = ref('');
    const marital_status = ref('');
    const country_code = ref('');
    const city = ref('');
    const state = ref('');
    const zipcode = ref('');
    const address = ref('');
    const military_status = ref('');
    const iscell = ref('');
    const isscell = ref('');
    const concent = ref('');
    const notes = ref('');
    const isLoading = ref(false);
    const selectedCode = ref('');
    const selectedsCode = ref('');
    const codes = ref([]);
    const showAlert = ref(false);
    const showAlerts = ref(false);

    watch(selectedPracticeStep, (newPracticeId) => {
      fetchPCP(newPracticeId);
    });

    onMounted(() => {
      Inputmask({ mask: '(999) 999-9999' }).mask('#mob');
      Inputmask({ mask: '(999) 999-9999' }).mask('#home_number');
    });

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

    const fetchState = async () => {
      try {
        const response = await fetch('/patients/state');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        states.value = data;
      } catch (error) {
        console.error('Error fetching practices:', error);
      }
    }

    const fetchCountryCode = async () => {
      try {
        const response = await fetch('/patients/countryCode');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        codes.value = data;
      } catch (error) {
        console.error('Error fetching practices:', error);
      }
    }

    const fetchVQ = async () => {
      await axios.get(`/patients/vetneranQuestion/${props.patientId}`)
        .then(response => {
          vt.value = response.data;
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    }

    const checkMiletry = () => {
      console.log(selectedVeteran.value);
      if (selectedVeteran.value == '0') {
        veteran.value = true;
      } else {
        veteran.value = false;
      }
    }
    /* 
             watch(() => props.patientId, (newVal, oldVal) => {
              props.patientId = newVal;
              getPatientDetailsPopulate();
          console.log('patientId changed:',props.patientId, newVal);
        }); */
    const getPatientDetailsPopulate = async () => {
      await axios.get(`/patients/getDetails/${props.patientId}`)
        .then(response => {
          const data = response.data;
          fname.value = data.patients[0].fname;
          lname.value = data.patients[0].lname;
          mname.value = data.patients[0].mname;
          dob.value = data.patients[0].dob;
          mob.value = data.patients[0].mob;
          selectedPracticeStep.value = data.practice_id;
          selectedPCP.value = data.provider_id;
          gender.value = data.gender;
          selectedVeteran.value = data.military_status;
          city.value = data.city;
          state.value = data.state;
          zipcode.value = data.zipcode;
          address.value = data.add_1;
          concent.value = data.patients[0].consent_to_text;
          iscell.value = data.patients[0].primary_cell_phone;
          selectedCode.value = data.patients[0].country_code;
          selectedsCode.value = data.patients[0].secondary_country_code;
          isscell.value = data.patients[0].secondary_cell_phone;
          homemob.value = data.patients[0].home_number;
          marital_status.value = data.marital_status;
          emr.value = data.emr;
          if (selectedVeteran.value == '0') {
            veteran.value = true;
          }
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    }

    onBeforeMount(() => {
      getPatientDetailsPopulate();
      fetchState();
      fetchCountryCode();
      setLandingTime();
      fetchVQ();
      fetchQualityMetric();
    });

    const updateRegisterForm = async (val) => {
      isLoading.value = true;
      let myForm = document.getElementById('patient_registrations_form');
      let formData = new FormData(myForm);
      axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
      try {
        formErrors.value = [];
        const response = await axios.post('/patients/updatePatient', formData);
        if (response && response.status == 200) {
          updateTimer(props.patientId, val, props.moduleId);
          showAlert.value = true;
          window.scrollTo(0, 0);
          //patientId.value = response.data.patient_id;
          isLoading.value = false;
          setTimeout(() => {
            showAlert.value = false;
            if (val == 0) {
              //isTimer.value = false;
              //emit('aceptDecline', 0);
              window.location.href = "/patients/patient-reg-enrollment/patients";
            } else if (val == 2) {
              const previousURL = document.referrer;
              // console.log(previousURL);
              const urlParams = window.location.pathname;
              const parts = urlParams.split('/');
              const idFromUrl = parts[parts.length - 1];
              console.log(idFromUrl);
              if (idFromUrl == 'patients') {
                isTimer.value = false;
                emit('aceptDecline', 0);
              } else {
                window.location.href = previousURL;
              }

            } else {
              //router.push('/another-route');
              window.location.href = "/ccm/care-plan-development/" + response.data.patient_id;
            }
          }, 3000);
        }
        isLoading.value = false;
      } catch (error) {
        isLoading.value = false;
        if (error.response && error.response.status === 422) {
          window.scrollTo(0, 0);
          showAlerts.value = true;
          formErrors.value = error.response.data.errors;
          setTimeout(() => {
            showAlerts.value = false;
          }, 3000);
        } else {
          console.error('Error submitting form:', error);
        }
      }
    }

    const fetchPCP = async (practiceId) => {
      try {
        const response = await fetch('../../org/ajax/provider/list/' + practiceId + '/Pcpphysicians');
        if (!response.ok) {
          throw new Error('Failed to fetch practices');
        }
        const data = await response.json();
        props.physicians = data;
      } catch (error) {
        console.error('Error fetching practices:', error);
      }
    };

    const fetchQualityMetric = async () => {
      await axios.get(`/patients/qualityMQuestion/${props.patientId}`)
        .then(response => {
          qualitymetric.value = response.data;
          savedGeneralQuestion();
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    }

    const savedGeneralQuestion = async () => {
      await axios.get(`/ccm/get-savedQuestion/3/${props.patientId}/0/saved_question`)
        .then(response => {
          let genQuestion = response.data;
          var off = 1;
          for (var i = 0; i < genQuestion.length; i++) {
            var editGq = JSON.parse(genQuestion[i].template);
            var javaObj = (genQuestion[i].template).replace("'", "&#39;");
            checkQuestion(editGq, off);
            off++;
          }
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    }

    const checkQuestion = async (obj, i) => {
      var tree = JSON.stringify(obj);
      var treeobj = JSON.parse(tree);
      for (var j = 1; j < 15; j++) {
        //Object.keys(treeobj.qs.opt).forEach(function(j) {
        if ((treeobj.qs.opt).hasOwnProperty(j)) {
          var prnt = $('input[value="' + (treeobj.qs.q).replace(/[\r\n]+/gm, "") + '"]').parents('.mb-4').attr('id');
          $('#' + prnt).find('input:radio[value="' + treeobj.qs.opt[j].val + '"], input:checkbox[value="' + treeobj.qs.opt[j].val + '"]').attr('checked', true).change();
          if ($('#' + prnt).find('input[type=text]').attr('id')) {
            var textid = $('#' + prnt).find('input[type=text]').attr('id');
            $('#' + textid).val(treeobj.qs.opt[j].val);
          }
          var obj1 = treeobj.qs.opt;
          renderEditquestion(obj1, j, i);
        }
      }
    }

    const renderEditquestion = async (obj1, i, nct) => {
      var tree = JSON.stringify(obj1);
      var treeobj = JSON.parse(tree);
      var l = 1;
      var objj = '';
      var z = 0;
      if (treeobj[i].hasOwnProperty('qs')) {
        Object.keys(treeobj[i].qs).forEach(function (key) {
          if ((treeobj[i].qs[key]).hasOwnProperty('opt')) {
            Object.keys(treeobj[i].qs[key].opt).forEach(function (j) {
              if ((treeobj[i].qs[key].opt).hasOwnProperty(j)) {
                var qval = (treeobj[i].qs[key].q).replace(/[\r\n]+/gm, "");
                var prnt = $('input[value="' + qval + '"]').parents('.mb-4').attr('id');
                if (prnt != undefined) {
                  var string1 = prnt;
                  var result = string1.substring(string1.indexOf('general_question') + 1);
                  var strValue = result;
                  strValue = strValue.replace('general_question', '').replace('eneral_question', '');
                  var strValue1 = string1.replace('general_question' + strValue, '');
                  if ($('#options' + strValue + '' + strValue1).find('input[type=text]').attr('id')) {
                    var inputbox = $('#options' + strValue + '' + strValue1).find('input[type=text]').attr('id');
                    $("#" + inputbox).val(treeobj[i].qs[key].opt[j].val);
                  }
                }

                $('#' + prnt).find('input:radio[value="' + treeobj[i].qs[key].opt[j].val + '"], input:checkbox[value="' + treeobj[i].qs[key].opt[j].val + '"]').attr('checked', true).change();
                objj = treeobj[i].qs[key].opt;
                z = j;
                // this.renderEditquestion(objj, j, nct);
              }
            });
          }
        });
      }
      if (z != 0) {
        renderEditquestion(objj, z, nct);
      }
    }

    return {
      isTimer,
      selectedPracticeStep,
      selectedPCP,
      formErrors,
      landingtime,
      states,
      selectedVeteran,
      vt,
      fname,
      lname,
      mname,
      dob,
      mob,
      isLoading,
      selectedCode,
      codes,
      veteran,
      qualitymetric,
      gender,
      marital_status,
      country_code,
      city,
      state,
      zipcode,
      address,
      military_status,
      iscell,
      concent,
      notes,
      showAlert,
      showAlerts,
      selectedsCode,
      isscell,
      homemob,
      emr,
      fetchPCP,
      setLandingTime,
      fetchState,
      fetchVQ,
      checkMiletry,
      getPatientDetailsPopulate,
      updateRegisterForm,
      fetchCountryCode,
      fetchQualityMetric,
      savedGeneralQuestion,
      checkQuestion,
      renderEditquestion
    }
  }
};
</script>