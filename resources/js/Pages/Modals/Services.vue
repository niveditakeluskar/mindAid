<template>
    <div class="modal fade" :class="{ 'show': isOpen }" >
	<div class="modal-dialog modal-xl" style="padding-top:0px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Services</h4> 
                <button type="button" class="close" data-dismiss="modal" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-12 mb-4">
                                    <ul class="nav nav-pills" id="myPillTab" role="tablist">
                                        <li class="nav-item"
                                        v-for="(tab, index) in tabs"
                                        :key="index"
                                        @click="changeTab(index)"
                                        >
                                            <a class="nav-link tabClick serviceslist"
                                            :class="{ 'active': activeTab === index }"
                                            id="dme-services-icon-pill" data-toggle="pill" href="#dme" role="tab" aria-controls="dme-services" aria-selected="false" value="1">
                                                <i class="nav-icon color-icon-2 i-Home1 mr-1"></i>
                                                {{ tab.label }}
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <component :is="tabs[activeTab].component" v-bind="componentServicesProps" />
                                    </div>
                                </div>
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
    </div>
</template>

<script>
import {
    ref,
    onBeforeMount,
} from '../commonImports';
import DME from './ServicesModals/DME.vue';
import HomeHealth from './ServicesModals/HomeHealth.vue';
import Dialysis from './ServicesModals/Dialysis.vue';
import Therapy from './ServicesModals/Therapy.vue';
import SocialServices from './ServicesModals/SocialServices.vue';
import MedicalSupplies from './ServicesModals/MedicalSupplies.vue';
import Other from './ServicesModals/Other.vue';
import SubStepCall from '../MonthlyMonitoring/SubSteps/CallSubSteps/Call.vue';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    components: {
        DME,
        HomeHealth,
        Dialysis,
        Therapy,
        SocialServices,
        MedicalSupplies,
        Other,
        SubStepCall,
    },
	setup(props) {
        const tabs = ref([
            { label: 'DME', component: 'DME' },
            { label: 'Home Health (skilled)', component: 'HomeHealth' },
            { label: 'Dialysis', component: 'Dialysis' },
            { label: 'Therapy', component: 'Therapy' },
            { label: 'Social Services', component: 'SocialServices' },
            { label: 'Medical Supplies', component: 'MedicalSupplies' },
            { label: 'Other', component: 'Other' }
        ]);

        const activeTab = ref(0);

        const changeTab = (index) => {
            activeTab.value = index;
        };

        let isOpen = ref(false);

        let openModal = () => {
            isOpen.value = true;
            document.body.classList.add('modal-open');
        };

        let closeModal = () => {
            isOpen.value = false;
            document.body.classList.remove('modal-open');
        };
        
        let servicesStageId = ref(0);

        let componentServicesProps = ref({
            patientId: props.patientId,
            moduleId: props.moduleId,
            componentId: props.componentId,
            stageId: servicesStageId,
        });

        let getStageID = async () => {
            try {
                let servicesStageName = 'Patient_Data';
                let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${servicesStageName}`);
                servicesStageId.value = response.data.stageID;
                componentServicesProps.value = {
                    patientId: props.patientId,
                    moduleId: props.moduleId,
                    componentId: props.componentId,
                    stageId: servicesStageId.value,
                };
            } catch (error) {
                console.error('Error fetching Services stageID:', error);
            }
        };

        onBeforeMount(async () => {
            await getStageID();
        });
        
        return {
            tabs,
            activeTab,
            changeTab,
            isOpen,
            openModal,
            closeModal,
            componentServicesProps,
        };
    }
};
</script>