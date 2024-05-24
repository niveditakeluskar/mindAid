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
    setup() {
        const isOpen = ref(false);
        const veteranService = ref(null);
        let openModal = () => {
            isOpen.value = true;
            document.body.classList.add('modal-open');
        };

        let closeModal = () => {
            isOpen.value = false;
            document.body.classList.remove('modal-open');
        };
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