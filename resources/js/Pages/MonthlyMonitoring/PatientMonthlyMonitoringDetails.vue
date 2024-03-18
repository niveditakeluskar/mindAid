<template>
   <div class="row mb-4">
      <div class="col-md-12">
         <div class="card">
            <div class="card-body">
               <div class="tm" style="float: left; width: 10%;">
                  <div v-for="(tab, index) in tabs" :key="index" @click="changeTab(index)"
                     :class="{ active: activeTab === index }" class="tm-section">
                     <div class="tm-tab">{{ tab }}</div>
                  </div>
               </div>
               <div style="width: 90%; float: right;">
                  <component :is="selectedComponent" v-bind="componentProps" :patientId="patientId" :moduleId="moduleId"
                     :componentId="componentId" :stageid="stageid" @form-submitted="handleFormSubmission"></component>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
import {
   ref,
   computed,
} from '../commonImports';
import Preparation from './SubSteps/Preparation.vue';
import SubStepConditionReview from './SubSteps/SubStepConditionReview.vue';
import Call from './SubSteps/Call.vue';
import FollowUp from './SubSteps/FollowUp.vue';
import Text from './SubSteps/Text.vue';

export default {
   props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
      stageid:Number,
      ccmRpm:Number
   },

   components: {
      Preparation,
      SubStepConditionReview,
      Call,
      FollowUp,
      Text,
   },

   setup(props) {
      const activeTab = ref(0);
      const componentProps = ref({
         patientId: props.patientId,
         moduleId: props.moduleId,
         componentId: props.componentId,
      });

      const tabs = ['Preparation', props.ccmRpm === 1 ? 'Review RPM' : '', 'Call', 'Follow-up', 'Text'].filter(tab => tab !== '');

      const changeTab = async (index) => {
         activeTab.value = index;
         updatePropsForComponent();
      };

      const updatePropsForComponent = () => {
         componentProps.value = {
         patientId: props.patientId,
         moduleId: props.moduleId,
         componentId: props.componentId,
         };
      };

      const selectedComponent = computed(() => {
         if (props.ccmRpm === 1) {
            switch (activeTab.value) {
               case 0:
                  return Preparation;
               case 1:
                  return SubStepConditionReview;
               case 2:
                  return Call;
               case 3:
                  return FollowUp;
               case 4:
                  return Text;
               default:
                  return null;
            }
         } else {
            switch (activeTab.value) {
               case 0:
                  return Preparation;
               case 1:
                  return Call;
               case 2:
                  return FollowUp;
               case 3:
                  return Text;
               default:
                  return null;
            }
         }
      });

      const handleFormSubmission = () => {
         const nextTabIndex = activeTab.value + 1;
         changeTab(nextTabIndex);
      };

      return {
         activeTab,
         tabs,
         componentProps,
         changeTab,
         selectedComponent,
         updatePropsForComponent,
         handleFormSubmission,
      };
   },
};
</script>
<style>
.tm {
   margin-top: 20px;
   position: relative;

}

.tm:before {
   position: absolute;
   content: '';
   width: 5px;
   height: calc(83% + 0px);
   background: #edeff0;
   left: 14px;
   top: 5px;
   border-radius: 4px;
}

.tm-section {
   padding-left: 35px;
   display: block;
   position: relative;
   margin-bottom: 20px;
   counter-increment: inc;
   cursor: pointer;
}

.tm-tab {
   margin-bottom: 15px;
   position: relative;
   display: inline-block;
   border-radius: 20px;
   color: #2c3f4c;
}

.tm {
   counter-reset: inc;
}

.tm-section:after {
   content: counter(inc);
   position: absolute;
   width: 20px;
   height: 20px;
   background: #edeff0;
   top: 2px;
   left: 7px;
   border-radius: 100%;
   z-index: 2;
   text-align: center;
}

.tm .active .tm-tab {
   color: #27a8de;
}

.tm div.active:after {
   background: #27a8de;
   color: white;
}

.tm-tab:hover {
   color: #27a8de;
}
</style>
