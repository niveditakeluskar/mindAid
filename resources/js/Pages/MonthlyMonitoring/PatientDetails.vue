<template>
    <div class="row text-align-center">
      <div class="col-md-12">
          <PatientBasicInfo :patientId="patientId" :moduleId="moduleId" />
          <PatientMonthlyMonitoringDetails :patientId="patientId" :moduleId="moduleId" />
      </div>
    </div>
  </template>

  <script >
  import PatientBasicInfo from '../Patients/Components/PatientBasicInfo.vue';
  import PatientMonthlyMonitoringDetails from './PatientMonthlyMonitoringDetails.vue';

  export default {
    props: {
      patientId: Number,
      moduleId: Number
    },
    components: {
      PatientBasicInfo,
      PatientMonthlyMonitoringDetails
    },
    // Your component logic
  }
  </script>

  <style>
  /* Your component-specific styles here */
  </style>
