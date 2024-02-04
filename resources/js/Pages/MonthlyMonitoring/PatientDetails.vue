<template>
  <LayoutComponent>
	<div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
      <div class="col-md-12">
          <input type="hidden" id="page_landing_times" name="timearr[form_start_time]" class="timearr form_start_time" :value='landingtime' v-model="page_landing_times"/>
          <PatientBasicInfo :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" v-if="basicinfo" />
          <PatientMonthlyMonitoringDetails :patientId="patientId" :moduleId="moduleId" :stageid="stageid" :componentId="componentId" v-if="basicinfo" />
      </div>
    </div>
  </LayoutComponent>
</template>

<script>
import {
  ref,
  onBeforeMount,
} from '../commonImports';
import LayoutComponent from '../LayoutComponent.vue';
import PatientBasicInfo from '../Patients/Components/PatientBasicInfo.vue';
import PatientMonthlyMonitoringDetails from './PatientMonthlyMonitoringDetails.vue';
import axios from 'axios';
export default {
  props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
      stageid:Number
  },
  components: { 
      LayoutComponent,
      PatientBasicInfo,
      PatientMonthlyMonitoringDetails
  },
  setup() {
    let landingtime = ref(null);
    let basicinfo = ref(false);
    const setLandingTime = async () => {
      axios.get(`/system/get-landing-time`)
        .then(response => {
          landingtime.value = response.data[['landing_time']];
          basicinfo.value = true;
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    };
    onBeforeMount(() => {
      setLandingTime();
    });

    return {
      landingtime,
      basicinfo,
    };
  },
}
</script>