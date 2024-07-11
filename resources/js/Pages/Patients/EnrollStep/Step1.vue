<template>
     <form method="post" enctype="multipart/form-data" name="patient_registrations_form"
              id="patient_registrations_form" @submit.prevent="submitRegisterForm" v-if="step1">
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
                    <input type="text" name="lname" class="form-control capitalize" ref="lname" @focusout="generateUID">
                    <input type="hidden" name="uid" id="uid">
                  </div>
                  <div class="form-row invalid-feedback" v-if="formErrors.lname" style="display: block;">{{
                formErrors.lname[0] }}</div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="fname">First Name<span class="error">*</span></label>
                    <input type="text" name="fname" class="form-control capitalize" ref="fname" @focusout="generateUID">

                  </div>
                  <div class="form-row invalid-feedback" v-if="formErrors.fname" style="display: block;">{{
                formErrors.fname[0] }}</div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="mname">Middle Name</label>
                    <input type="text" name="mname" class="form-control capitalize" ref="mlame">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-2">
                  <div class="form-group">
                    <label>Date of Birth<span class="error">*</span></label>
                    <input type="date" name="dob" class="form-control" ref="dob" @focusout="generateUID" max="9999-12-31"  min="1902-01-01">
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
                    <input id="mob" data-inputmask="'mask': '(999) 999-9999'" name="mob" type="text" value=""
                      autocomplete="off" class="form-control" im-insert="true">
                  </div>
                  <div class="form-row invalid-feedback" v-if="formErrors.mob" style="display: block;">{{
                formErrors.mob[0] }}</div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="emr">EMR#<span class="error">*</span></label>
                    <input type="text" name="practice_emr" class="form-control">
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
</template> 
<script>

import {
  ref,
  onMounted,
  watch,
  onBeforeMount,
  Head
} from '../../commonImports';
export default {
    props: {
        moduleId: Number,
        componentId: Number,
        stageId: Number,
        patientId: Number,
        practices: Array,
        physicians: Array,
    },
    setup(props) {
        const selectedPractice = ref('');
        const selectedPCP = ref('');
        let formErrors = ref([]);
        const lname = ref('');
        const fname = ref('');
        const mname = ref('');
        const dob = ref('');
    }
};
</script>