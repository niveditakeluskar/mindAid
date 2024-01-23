<template>
  <LayoutComponent>
    <div class="row text-align-center">
      <div class="col-md-12">
          <input type="hidden" id="page_landing_times" name="timearr[form_start_time]" class="timearr form_start_time" :value='landingtime' v-model="page_landing_times"/>
          <PatientBasicInfo :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" v-if ="basicinfo" />
          <PatientMonthlyMonitoringDetails :patientId="patientId" :moduleId="moduleId" :stageid="stageid" :componentId="componentId" />
      </div>
    </div>
  </LayoutComponent>
</template>

<script >
  import LayoutComponent from '../LayoutComponent.vue'; // Import your layout component
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
    data() {
      return {
        landingtime: null,
        basicinfo:false,
      };
    },
    mounted() {
      this.setLandingTime();
    },
    methods: {
      async setLandingTime() {
        axios.get(`/system/get-landing-time`)
          .then(response => {
            this.landingtime = response.data[['landing_time']];
            console.log(this.landingtime);
            this.basicinfo = true;
          })
          .catch(error => {
            console.error('Error fetching data:', error);
          });
      }
    },
    // Your component logic
  }
</script>

<style>
  /* Your component-specific styles here */
</style>
