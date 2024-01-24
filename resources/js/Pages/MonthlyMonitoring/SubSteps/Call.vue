<template>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="horizontal-tabs">
                        <ul class="nav nav-tabs">
                            <li v-for="(callTab, index) in callTabs" :key="index" @click="changeCallTab(index)"
                                    :class="{ active: activeCallTabs === index }" >
                            <a href="#">{{ callTab }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <component :is="selectedCallComponent" v-bind="componentCallProps" :patientId="patientId" :moduleId="moduleId" :componentId="componentId"></component>
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
    methods: {
        changeCallTab(index) {
            this.activeCallTabs = index;
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
</style>