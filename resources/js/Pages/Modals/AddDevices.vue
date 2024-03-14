<!-- AlertThresholds.vue -->
<template>
  <div v-if="isOpen" class="modal fade show" >
	  <div class="modal-dialog ">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Veteran Service</h4> 
              <button type="button" class="close" @click="closeModal">×</button>
          </div>
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
    reactive,
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
  data() {
		return {
		
		};
	},
    mounted() {
		
	},
    
  setup() {
      const isOpen = ref(false);
      const veteranService = ref(null);
      const openModal = () => {
          console.log('Open modal called');
          isOpen.value = true;
      };

      const closeModal = () => {
          console.log('Close modal called');
      isOpen.value = false;
      };

    //   let getVtservice = async () => {
    //         try {
    //             await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
    //             const response = await fetch(`/ccm/veteran-service/76964135/vt`);
    //             if (!response.ok) {
    //                 throw new Error('Failed to fetch medication list');
    //             }
    //             veteranService.value = response.data; 
    //             console.log(veteranService.value+"veteranService.value");
    //         } catch (error) {
    //             console.error('Error fetching medications list:', error);
    //         }
    //     };
        onMounted(() => {
            // getVtservice();
        });

      return {
          isOpen,
          openModal,
          closeModal,
          veteranService,
      };
  },
};
</script>

