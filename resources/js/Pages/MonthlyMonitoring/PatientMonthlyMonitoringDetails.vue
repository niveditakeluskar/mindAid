<template>
   <LayoutComponent>
      <div class="row mb-4">
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <!-- <form-wizard>
            <TabContent title="About You" :selected="true">
                  This is content of Tab 1
            </TabContent>
            <TabContent title="About your Company"> 
                  <p>Can contains</p>
                  <p>Multiple Elements</p>
            </TabContent>
            <TabContent title="Finishing Up">
                  <p>Or an image .. or any thing</p>
                  
            </TabContent>  
         </form-wizard> -->
                  <div class="tsf-wizard tsf-wizard-2">
                     <ul class="gsi-step-indicator triangle gsi-style-1 gsi-transition">
                        <li v-for="(step, index) in steps" :key="index" :class="{ active: index === activeStep }"
                           @click="changeStep(index)">
                           <a :href="`#${index}`">
                              <span class="desc">{{ step }}</span>
                           </a>
                        </li>
                     </ul>
                     <div class="tsf-container" style="width: 90%;">
                        <div class="tsf-content">
                           <div v-for="(content, index) in contents" :key="index" v-show="index === activeStep" :id="index"
                              class="tsf-step" v-bind:style="(activeStep === index )? 'display: block;' : 'display: none;'">
                              <!-- {{ index }},,..
                              {{ activeStep }}........

                              {{ content }} -->
                              <Preparation />
                              <SubStepCall :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
                              <SubStepVerification />
                              <SubStepRelationship :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
                              <SubStepConditionReview />
                              <SubStepGeneralQuestions :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
                              <SubStepCallClose />
                              <SubStepCallWrapUp />
                              <FollowUp :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
                              <Text :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </LayoutComponent>
</template>

<script>
   import LayoutComponent from '../LayoutComponent.vue'; // Import your layout component
   import Preparation from './SubSteps/Preparation.vue';
   import SubStepConditionReview from './SubSteps/CallSubSteps/ConditionReview.vue';
   import SubStepCall from './SubSteps/CallSubSteps/Call.vue';
   import SubStepVerification from './SubSteps/CallSubSteps/Verification.vue';
   import SubStepRelationship from './SubSteps/CallSubSteps/Relationship.vue';
   import SubStepGeneralQuestions from './SubSteps/CallSubSteps/GeneralQuestions.vue';
   import SubStepCallClose from './SubSteps/CallSubSteps/CallClose.vue';
   import SubStepCallWrapUp from './SubSteps/CallSubSteps/CallWrapUp.vue';
   import FollowUp from './SubSteps/FollowUp.vue';
   import Text from './SubSteps/Text.vue';
   // import { FormWizard, TabContent } from 'vue-step-wizard'
   // import 'vue-step-wizard/dist/vue-step-wizard.css'
// import stepWizard from 'js/app.js';
   export default {
      props: {
         patientId: Number,
         moduleId: Number,
         componentId: Number
      },
      components: {
         LayoutComponent,
         Preparation,
         SubStepConditionReview,
         SubStepCall,
         SubStepVerification,
         SubStepRelationship,
         SubStepGeneralQuestions,
         SubStepCallClose,
         SubStepCallWrapUp,
         FollowUp,
         Text,
      // FormWizard,
      // TabContent
      },
      data() {
         return {
            activeStep: 0,
            steps: ['Preparation', 'Review RPM', 'Call', 'Follow-up', 'Text'],
            contents: [
               'Content for Step 1',
               'Content for Step 2',
               'Content for Step 3',
               'Content for Step 4',
               'Content for Step 5'
            ]
         };
      },
      methods: {
         changeStep(index) {
            this.activeStep = index;

         }
      },
      mounted() {
         console.log('Component mounted.')
      }
   };
</script>
