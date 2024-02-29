<template>
<div v-if="enrolled_date !=''">
  <LayoutComponent>
	<div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
      <div class="col-md-12"> 
          <input type="hidden" id="page_landing_times" name="timearr[form_start_time]" class="timearr form_start_time" :value='landingtime' />
          <PatientBasicInfo :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" />
          <PatientMonthlyMonitoringDetails :patientId="patientId" :moduleId="moduleId" :stageid="stageid" :componentId="componentId" :ccmRpm="ccmRpm" />
      </div>
    </div>
  </LayoutComponent>
  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Monthly Monitoring Page" />
  </Head>
</div>
<div v-else>
    <LayoutComponent>
      <patient_not_exist/>
    </LayoutComponent>
</div>
</template>

<script>
import {
  ref,
  onBeforeMount,
  Head
} from '../commonImports';
import LayoutComponent from '../LayoutComponent.vue';
import PatientBasicInfo from '../Patients/Components/PatientBasicInfo.vue';
import PatientMonthlyMonitoringDetails from './PatientMonthlyMonitoringDetails.vue';
import patient_not_exist from '../patient-doesnt-exist.vue';
export default {
  props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
      stageid: Number,
      landingTime: String,
      enrolled_date: String,
      ccmRpm:Number
  },
  components: { 
      LayoutComponent,
      PatientBasicInfo,
      PatientMonthlyMonitoringDetails,
      Head,
      patient_not_exist
  },
  setup(props) {
    const title = 'Monthly Monitor ';
    let landingtime = ref(null);
    let enrolled_date = ref(null);
    const patComDetails = async () => {
      try {
          const response = await fetch(`/patients/patient-details/${props.patientId}/${props.moduleId}/patient-details`);
          if (!response.ok) {
              throw new Error(`Failed to fetch Patient details - ${response.status} ${response.statusText}`);
          }
          const data = await response.json();
          enrolled_date.value = data.date_enrolled;
      } catch (error) {
          console.error('Error fetching Patient details:', error.message); // Log specific error message
          // Handle the error appropriately
      }
    }
    onBeforeMount(() => {
      document.title = 'Monthly Monitoring |  Renova Healthcare';
      landingtime.value = props.landingTime.landing_time;
      patComDetails();
    });
    return {
      patComDetails,
      enrolled_date,
      landingtime,
      title
    };
  },
}
</script>