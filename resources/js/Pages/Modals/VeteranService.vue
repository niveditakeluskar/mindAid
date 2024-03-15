<template>  
  <div v-if="isOpen" class="modal fade show" >
  <div class="modal-dialog ">
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
  </div>
</template>

<script>
import {
    ref,
    onMounted,
} from '../commonImports';
import axios from 'axios';
export default {
  props: {
      patientId: Number,
      moduleId: Number,
      componentId: Number,
  },
  data() {
        return {
            isOpen: false,
        };
    },
    methods: {
        openModal() {
            this.isOpen = true;
            document.body.classList.add('modal-open');
        },
        closeModal() {
            this.isOpen = false;
            document.body.classList.remove('modal-open');
        },
    },
    
  setup(props) {
     
      const veteranService = ref();
      
      const getVtservice = async () => {

        let response = await axios.get(`/patients/patient-VeteranServiceData/${props.patientId}/patient-VeteranServiceData`);
        veteranService.value = response.data;
            // try {
            //     //await new Promise((resolve) => setTimeout(resolve, 1000)); // Simulating a 2-second delay
            //     const response = await fetch(`/patients/patient-VeteranServiceData/${props.patientId}/patient-VeteranServiceData`);
            //     if (!response.ok) {
            //         throw new Error('Failed to fetch medication list');
            //     }
            //     veteranService.value = response.data; 
            //     console.log(response,"veteranService");
            // } catch (error) {
            //     console.error('Error fetching medications list:', error);
            // }
        };
        onMounted(() => {
            getVtservice();

        });

      return {
          veteranService,
          getVtservice,
      };
  },
};
</script>
