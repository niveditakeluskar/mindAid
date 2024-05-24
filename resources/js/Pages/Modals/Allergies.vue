<template>
    <div class="modal fade" :class="{ 'show': isOpen }" >
	<div class="modal-dialog  modal-xl">
        <div class="modal-content" style="padding-top:0px; margin:0px;">
            <div class="modal-header">
                <h4 class="modal-title">Allergies</h4> 
                <button type="button" class="close" data-dismiss="modal" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <div class="row" id="Allergies">
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills" id="myPillTab" role="tablist">
                                        <li class="nav-item"
                                        v-for="(tab, index) in tabs"
                                        :key="index"
                                        @click="changeTab(index)"
                                        >
                                            <a class="nav-link tabClick allergieslist" :class="{ 'active': activeTab === index }" id="dme-allergies-icon-pill" data-toggle="pill" href="#dme" role="tab" aria-controls="durg-allergies" aria-selected="false" value="1">
                                                <i class="nav-icon color-icon-2 i-Home1 mr-1"></i>
                                                {{ tab.label }}
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <component :is="tabs[activeTab].component" v-bind="componentAllergiesProps" />
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
import Drug from './AllergiesModals/Drug.vue';
import Food from './AllergiesModals/Food.vue';
import Enviromental from './AllergiesModals/Enviromental.vue';
import Insect from './AllergiesModals/Insect.vue';
import Latex from './AllergiesModals/Latex.vue';
import Pet_related from './AllergiesModals/Pet_related.vue';
import other from './AllergiesModals/other.vue';
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    components: {
        Drug,
        Food,
        Enviromental,
        Insect,
        Latex,
        Pet_related,
        other
    },
	setup(props) {
        const tabs = ref([
            { label: 'Drug', component: 'Drug' },
            { label: 'Food', component: 'Food' },
            { label: 'Enviromental', component: 'Enviromental' },
            { label: 'Insect', component: 'Insect' },
            { label: 'Latex', component: 'Latex' },
            { label: 'Pet-Related', component: 'Pet_related' },
            { label: 'Other', component: 'other' }
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

        let allergiesStageId = ref(0);

        let componentAllergiesProps = ref({
            patientId: props.patientId,
            moduleId: props.moduleId,
            componentId: props.componentId,
            stageId: allergiesStageId,
        });
        
        
        let getStageID = async () => {
            try {
                let allergiesStageName = 'Patient_Data';
                let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${allergiesStageName}`);
                allergiesStageId.value = response.data.stageID;
                componentAllergiesProps.value = {
                    patientId: props.patientId,
                    moduleId: props.moduleId,
                    componentId: props.componentId,
                    stageId: allergiesStageId.value,
                };
                console.log("componentAllergiesProps", componentAllergiesProps);
            } catch (error) {
                console.error('Error fetching Allergies stageID:', error);
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
            componentAllergiesProps
        };
    }
};
</script>