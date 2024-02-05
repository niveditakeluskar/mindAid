<template>
  <LayoutComponent>
	<div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
      <div class="col-md-12">
          <input type="hidden" id="page_landing_times" name="timearr[form_start_time]" class="timearr form_start_time" :value='landingtime' />
          <PatientBasicInfo :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" />
          <PatientMonthlyMonitoringDetails :patientId="patientId" :moduleId="moduleId" :stageid="stageid" :componentId="componentId" />
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
export default {
  props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
      stageid: Number,
      landingTime: String,
  },
  components: { 
      LayoutComponent,
      PatientBasicInfo,
      PatientMonthlyMonitoringDetails
  },
  setup(props) {
    let landingtime = ref(null);
    onBeforeMount(() => {
      landingtime.value = props.landingTime.landing_time;
    });
    return {
      landingtime,
    };
  },
}
</script>