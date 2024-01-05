<template>
  <div>
    <div class="tab-container">
      <div
        v-for="(tab, index) in tabs"
        :key="index"
        @click="changeTab(index)"
        :class="{ active: activeTab === index }"
        class="tab"
      >
        {{ tab }}
      </div>
    </div>

    <component :is="selectedComponent"></component>
  </div>
</template>

<script>
import Preparation from './MonthlyMonitoring/SubSteps/Preparation.vue';
   import SubStepConditionReview from './MonthlyMonitoring/SubSteps/CallSubSteps/ConditionReview.vue';
   import SubStepCall from './MonthlyMonitoring/SubSteps/CallSubSteps/Call.vue';
   import SubStepVerification from './MonthlyMonitoring/SubSteps/CallSubSteps/Verification.vue';
   import SubStepRelationship from './MonthlyMonitoring/SubSteps/CallSubSteps/Relationship.vue';      

export default {
  components: {
    Preparation,
    SubStepConditionReview,
        SubStepCall,
        SubStepVerification,
    SubStepRelationship
  },
  data() {
    return {
      tabs: ['Tab 1', 'Tab 2', 'Tab 3', 'Tab 4', 'Tab 5'],
      activeTab: 0,
    };
  },
  computed: {
    selectedComponent() {
      switch (this.activeTab) {
        case 0:
          return 'Preparation';
        case 1:
          return 'SubStepConditionReview';
        case 2:
          return 'SubStepCall';
                case 3:
                    return 'SubStepVerification';
                case 4:
                    return 'SubStepRelationship';
        default:
          return 'Preparation'; // Default component if activeTab is not in the range
      }
    },
  },
  methods: {
    changeTab(index) {
      this.activeTab = index;
    },
  },
};
</script>

<style>
/* Your tab styles here */
</style>
