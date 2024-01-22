<!-- ResearchStudy.vue -->
<template>
    <div v-if="isOpen" class="overlay open" @click="closeModal"></div>
    <div v-if="isOpen" class="modal fade open">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Research Study</h4> 
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                            <input type="hidden" name="hidden_id" id="hidden_id" :value="patientId">
                            <input type="hidden" name="page_module_id" id="page_module_id" :value="moduleId">
                            <input type="hidden" name="page_component_id" id="page_component_id">
                            <input type="hidden" name="service_status" id="service_status">
                            <input type="hidden" id="timer_runing_status" value="0">
                        <label>Part of Research Study<span class='error'>*</span></label>
                        <textarea name="part_of_research_study" class="form-control forms-element part_of_research_study" v-model="textValue">{{  }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" @click="closeModal">Close</button>
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
      AgGridVue,
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
      //   const personal_notes = ref(null);
        const openModal = () => {
            console.log('Open personal modal called');
            isOpen.value = true;
        };

        const closeModal = () => {
            console.log('Close personal modal called');
            isOpen.value = false;
        };

          let getPersonalNotes = async () => {
              try {
                  await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                  const response = await fetch(`/patients/patient-details/${props.patientId}/${props.moduleId}/patient-details`);
                  if (!response.ok) {
                      throw new Error('Failed to fetch medication list');
                  }
                  personal_notes_textValue = response.data; 
                  console.log(personal_notes_textValue +"DASDASDASDASDASD");
              } catch (error) {
                  console.error('Error fetching medications list:', error);
                  loading.value = false;
              }
          };
          onBeforeMount(() => {
              // getPersonalNotes();
          });

        return {
            isOpen,
            openModal,
            closeModal,
            personal_notes_textValue:'',
          //   personal_notes,
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
  