<!-- AlertThresholds.vue -->
<template>
  <div v-if="isOpen" class="overlay open" @click="closeModal"></div>
  <div v-if="isOpen" class="modal fade open">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Veteran Service</h4> 
              <button type="button" class="close" @click="closeModal">×</button>
          </div>
            <input type="hidden" name="patient_id" id="patient_id" :value="patientId">
            <input type="hidden" name="module_id" id="page_module_id" :value="moduleId">
            <input type="hidden" name="component_id" id="page_component_id" :value="componentId">
            <input type="hidden" name="stage_id" :value="stageid" />
            <input type="hidden" name="id">
          <div class="modal-body" v-html="veteranService">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" @click="closeModal">Close</button>
          </div>
      </div>
  </div>
</template>

<script>
import {
    ref,
    onBeforeMount,
    onMounted,
    // Add other common imports if needed
} from '../commonImports';
import axios from 'axios';
export default {
  props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
  },
    
  setup(props) {
      const isOpen = ref(false);
      const veteranService = ref(null);
      
      const openModal = () => {
          isOpen.value = true;
      };

      const closeModal = () => {
          isOpen.value = false;
      }; 

      const getVtservice = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 1000)); // Simulating a 2-second delay
                const response = await fetch(`/patients/patient-VeteranServiceData/${props.patientId}/patient-VeteranServiceData`);
                if (!response.ok) {
                    throw new Error('Failed to fetch medication list');
                }
                veteranService.value = response.data; 
                console.log(response.data+"veteranService");
            } catch (error) {
                console.error('Error fetching medications list:', error);
            }
        };
        onMounted(() => {
            getVtservice();

        });

      return {
          isOpen,
          openModal,
          closeModal,
          veteranService,
          getVtservice,
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
