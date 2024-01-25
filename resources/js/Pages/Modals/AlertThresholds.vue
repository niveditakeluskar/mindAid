<!-- Modal.vue -->
<template>
    <div class="overlay" :class="{ 'open': isOpen }" @click="closeModal"></div>
    <div class="modal fade" :class="{ 'open': isOpen }" > <!-- :style="{ display: isOpen ? 'block' : 'none' }"> -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Alert Thresholds</h4> 
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-4"> 
                            <ul class="nav nav-tabs">
                                <li class="nav-nav-item" v-for="(tab, index) in tabs" :key="index" @click="changeTab(index)" :class="{ 'active': activeTab === index }">
                                    <a class="nav-link" aria-current="page" href="#">
                                        {{ tab.label }}
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <component :is="tabs[activeTab].component" v-bind="componentConstProps"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="closeModal">Close</button>
            </div>
        </div>
    </div>
</template>


<script>
import {
    ref,
    onBeforeMount,
    onMounted,
    watch,
    // Add other common imports if needed
} from '../commonImports';
import axios from 'axios';
import Tab1Component from '../Modals/AlertThreshold/tab1.vue';
import Tab2Component from '../Modals/AlertThreshold/tab2.vue';
export default {
    props: { 
        patientId: Number,
        moduleId: Number,
        componentId: Number,
        stageid: Number,
        patient_systolichigh:Number,
        patient_systoliclow:Number,
        patient_diastolichigh:Number, 
        patient_diastoliclow:Number, 
        patient_bpmhigh:Number, 
        patient_bpmlow:Number, 
        patient_oxsathigh:Number, 
        patient_oxsatlow:Number, 
        patient_glucosehigh:Number, 
        patient_glucoselow:Number, 
        patient_temperaturehigh:Number, 
        patient_temperaturelow:Number, 
        patient_weighthigh:Number, 
        patient_weightlow:Number, 
        patient_spirometerfevhigh:Number, 
        patient_spirometerfevlow:Number, 
        patient_spirometerpefhigh:Number, 
        patient_spirometerpeflow:Number,
    },

    components: {
        Tab1Component,
        Tab2Component,
    },
    
    setup(props) {
        const tabs = ref([ 
          { label: 'Tab 1', component: 'Custom Threshold' },
          { label: 'Tab 2', component: 'Stanard Threshold' },
         
        ]);


        const isOpen = ref(false); 
        const loading = ref(false);

        const activeTab = ref(0);
        const changeTab = (index) => {
            activeTab.value = index;
        };

        const openModal = () => {
            isOpen.value = true;
        };

        const closeModal = () => {
            isOpen.value = false;
        };
        let componentConstProps = ref({
            patientId: props.patientId,
            moduleId: props.moduleId,
            componentId: props.componentId,
            stageid: props.stageid,
            patient_systolichigh: props.patient_systolichigh,
            patient_systoliclow: props.patient_systoliclow,
            patient_diastolichigh: props.patient_diastolichigh,  
            patient_diastoliclow: props.patient_diastoliclow,  
            patient_bpmhigh: props.patient_bpmhigh,  
            patient_bpmlow: props.patient_bpmlow,  
            patient_oxsathigh: props.patient_oxsathigh,  
            patient_oxsatlow: props.patient_oxsatlow,  
            patient_glucosehigh: props.patient_glucosehigh,  
            patient_glucoselow: props.patient_glucoselow,  
            patient_temperaturehigh: props.patient_temperaturehigh,  
            patient_temperaturelow: props.patient_temperaturelow,  
            patient_weighthigh: props.patient_weighthigh,  
            patient_weightlow: props.patient_weightlow,  
            patient_spirometerfevhigh: props.patient_spirometerfevhigh,  
            patient_spirometerfevlow: props.patient_spirometerfevlow,  
            patient_spirometerpefhigh: props.patient_spirometerpefhigh,  
            patient_spirometerpeflow: props.patient_spirometerpeflow,  
        });
        onMounted(async () => {

        });

        onBeforeMount(() => {

        });

        

        return {
            
            tabs,
            activeTab,
            changeTab,
            loading,
            isOpen,
            openModal,
            closeModal,
            componentConstProps,
        };
    },
};
</script>

<style>
/* Modal styles */
.modal {
display: none;
position: fixed;
background-color: white;
z-index: 1000;
margin: 2%;
opacity: 0;
transition: opacity 0.3s ease;
}

/* Style the overlay */
.overlay {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.5);
z-index: 999;
display: none;
}

/* Show the overlay and modal when modal is open */
.modal.open {
display: block;
opacity: 1;
}

.overlay.open {
display: block;
}

.modal-content {
  overflow-y: auto !important;
  height: auto !important;
  /* height: 800px !important; */
}
</style>
