<!-- ModalForm.vue -->
<template>
    <div class="overlay" :class="{ 'open': isOpen }" @click="closeModal"></div>
    <div class="modal fade" :class="{ 'open': isOpen }" > <!-- :style="{ display: isOpen ? 'block' : 'none' }"> -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Services</h4> 
                <button type="button" class="close" data-dismiss="modal" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <div class="row mb-4" id="medications">
                    <div class="col-md-12 mb-4">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-12 mb-4">
                                    <ul class="nav nav-pills" id="myPillTab" role="tablist">
                                        <li class="nav-item"
                                        v-for="(tab, index) in tabs"
                                        :key="index"
                                        @click="changeTab(index)"
                                        :class="{ 'active': activeTab === index }"
                                        >
                                            <a class="nav-link tabClick serviceslist" id="dme-services-icon-pill" data-toggle="pill" href="#dme" role="tab" aria-controls="dme-services" aria-selected="false" value="1">
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
                <div class="separator-breadcrumb border-top"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            
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
    onMounted
} from '../commonImports';
import DME from './ServicesModals/DME.vue';
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
        Other,
        SubStepCall,
    },
	setup(props) {
        const tabs = ref([
            { label: 'DME', component: 'DME' },
            { label: 'Home Health (skilled)', component: 'SubStepCall' },
            { label: 'Dialysis', component: 'SubStepCall' },
            { label: 'Therapy', component: 'SubStepCall' },
            { label: 'Social Services', component: 'SubStepCall' },
            { label: 'Medical Supplies', component: 'SubStepCall' },
            { label: 'Other', component: 'Other' }
        ]);

        const activeTab = ref(0);
        const changeTab = (index) => {
            activeTab.value = index;
        };

        let isOpen = ref(false);

        let openModal = () => {
            isOpen.value = true;
        };

        let closeModal = () => {
            isOpen.value = false;
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