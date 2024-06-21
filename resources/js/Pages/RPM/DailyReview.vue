<template>
    <LayoutComponent>
	<div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
      <div class="col-md-12"> 
          <input type="hidden" id="page_landing_times" name="timearr[form_start_time]" class="timearr form_start_time" :value='landingtime' />
          <PatientBasicInfo :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid" />
          <div class="row mb-4">
      <div class="col-md-12">
         <div class="card">
            <div class="card-body">
               <SubStepConditionReview :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid"/>
            </div>
         </div>
      </div>
   </div>
      </div>
    </div>
  </LayoutComponent>
</template>
<script>
import {
  ref,
  onBeforeMount,
  Head
} from '../commonImports';
import LayoutComponent from '../LayoutComponent.vue';
import PatientBasicInfo from '../Patients/Components/PatientBasicInfo.vue';
import SubStepConditionReview from '../MonthlyMonitoring/SubSteps/SubStepConditionReview.vue';
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
      SubStepConditionReview
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
};
</script>