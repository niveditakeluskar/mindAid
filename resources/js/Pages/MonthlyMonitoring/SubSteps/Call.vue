<template>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="horizontal-tabs">
                        <ul class="nav nav-tabs">
                            <li v-for="(callTab, index) in callTabs" :key="index"
                                @click="changeCallTab(index)"
                                :class="{ active: activeCallTabs === index, disabled: isTabDisabled(index) }">
                                <a href="#" :class="{ 'disabled-verification': isVerificationTabDisabled(index), 'clickable': !isTabDisabled(index) }">{{ callTab }}</a>
                            </li>

                        </ul>
                    </div>
                    <div class="tab-content">
                        <component :is="selectedCallComponent" v-bind="componentCallProps" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" @updateverification="enabledTab"></component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SubStepCall from './CallSubSteps/Call.vue';
import SubStepVerification from './CallSubSteps/Verification.vue';
import SubStepRelationship from './CallSubSteps/Relationship.vue';
import SubStepConditionReview from './CallSubSteps/ConditionReview.vue';
import SubStepGeneralQuestions from './CallSubSteps/GeneralQuestions.vue';
import SubStepCallClose from './CallSubSteps/CallClose.vue';
import SubStepCallWrapUp from './CallSubSteps/CallWrapUp.vue';

export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    components: {
        SubStepCall,
        SubStepVerification,
        SubStepRelationship,
        SubStepConditionReview,
        SubStepGeneralQuestions,
        SubStepCallClose,
        SubStepCallWrapUp,
        
    },
    data() {
        return {
            callTabs: ['Call', 'Verification', 'Relationship', 'Condition Review', 'Monthly Questions', 'Call Close', 'Call Wrap up'],
            activeCallTabs: 0,
            componentCallProps: {},
            verification:'',
        };
    },
    created() { 
        this.updatePropsForCallComponent();
    },
    computed: {
        selectedCallComponent() {
            switch (this.activeCallTabs) {
                case 0:
                    return 'SubStepCall';
                case 1:
                    return 'SubStepVerification';
                case 2:
                    return 'SubStepRelationship';
                case 3:
                    return 'SubStepConditionReview';
                case 4:
                    return 'SubStepGeneralQuestions';
                case 5:
                    return 'SubStepCallClose';
                case 6:
                    return 'SubStepCallWrapUp';
                default:
                    return 'SubStepCall'; // Default component if activeCallTabs is not in the range
            }
        },
    },
    mounted() {
		this.populateFuntion();
	},
    methods: {

        async populateFuntion(){ 
			try{
				const response = await fetch(`/ccm/populate-monthly-monitoring-data/${this.patientId}`);
				if(!response.ok){  
						throw new Error(`Failed to fetch Patient Preaparation - ${response.status} ${response.statusText}`);
				}
				const data = await response.json();
				this.patientPrepSaveDetails = data;
				if(this.patientPrepSaveDetails.populateHippa!=''){
					this.verification = this.patientPrepSaveDetails.populateHippa.static.verification;
				}
			}catch(error){
				console.error('Error fetching Patient Preaparation:', error.message); // Log specific error message
			}
	    },

        enabledTab(){
            this.verification = 1;
        },

        isTabDisabled(index) {
            // Add your logic to determine if the tab is disabled
            // Return true if the tab is disabled, false otherwise
            return this.verification !=1 && (index === 2 || index === 3 || index === 4 || index === 5);
        },

        isVerificationTabDisabled(index) { 
            // Add your specific logic for disabling tabs when verification is  not 1
            // Return true if the tab is disabled, false otherwise
            return this.verification !=1 && (index === 2 || index === 3 || index === 4 || index === 5);
        },

        changeCallTab(index) {
            // console.log(this.verification+"XXXXXXXXXXXXXXXX");
            if(this.verification !=1 && (index==2 || index==3 || index==4 ||index==5)){
            //this.activeCallTabs = '';
            }else{
                this.activeCallTabs = index; 
            }
            this.updatePropsForCallComponent();
        },
        updatePropsForCallComponent() {
            this.componentCallProps = {
                patientId: this.patientId,
                moduleId: this.moduleId,
                componentId: this.componentId
            };
        },

    },
};
</script>
<style scoped>
/* Your tab styles here */
.horizontal-tabs {
    margin-bottom: 20px;
}

.nav-tabs {
    border-bottom: 1px solid #ccc;
    display: flex;
    list-style: none;
    padding-left: 0;
}

.nav-tabs li {
    cursor: pointer;
    margin-bottom: -1px;
    margin-right: 2px;
    border: 1px solid transparent;
}

.nav-tabs li.active {
    border-top: 2px solid #007bff;
    border-bottom: 1px solid #fff;
}

.nav-tabs a {
    display: block;
    padding: 8px 16px;
    text-decoration: none;
}


.nav-tabs li.disabled a {
  cursor: not-allowed;
  opacity: 0.5; /* Adjust the opacity to visually indicate the tab is disabled */
  /* Add additional styles for disabled tabs here */
}

.nav-tabs li.disabled-verification a {
  /* Add styles for disabled tabs when verification is 1 */
  color: #b1c0c0; /* Change the color for better visibility */
}

.nav-tabs li a.clickable {
  /* Add styles for clickable tabs here */
  color: #27a8de; 
  /* Change the color for clickable tabs */
}
</style>